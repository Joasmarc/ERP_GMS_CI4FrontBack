<?php

namespace App\Controllers;

use App\Models\WarehousesBase;
use App\Models\WarehousesBalance;
use App\Models\WarehousesTransferBase;
use App\Models\WarehousesTransferItems;

class Warehouse extends BaseController
{
    /**
     * Verificar autorización del usuario para el módulo de Bodegas (credentials[13])
     */
    private function checkPermission()
    {
        if (!session()->has('user_id')) {
            return false;
        }

        $credentials = session('credentials');
        return isset($credentials[13]) && $credentials[13] === '1';
    }

    /**
     * Listado general de bodegas con conteo de items y stock acumulado
     */
    public function listing()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado para acceder a este módulo.'
            ])->setStatusCode(403);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('warehouses_base wb');
        $builder->select('wb.id, wb.name, wb.adress, wb.state, wb.created_at, wb.updated_at, COUNT(b.id) as total_items');
        $builder->join('warehouses_balance b', 'b.id_warehouse = wb.id AND b.deleted_at IS NULL', 'left');
        $builder->where('wb.deleted_at IS NULL');
        $builder->groupBy('wb.id, wb.name, wb.adress, wb.state, wb.created_at, wb.updated_at');
        $builder->orderBy('wb.id', 'ASC');

        $records = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $records
        ]);
    }

    /**
     * Listado de bodegas activas para selección de destino en transferencias (excluyendo origen)
     */
    public function active_list($excludeId = null)
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $excludeId = filter_var($excludeId, FILTER_VALIDATE_INT);

        $warehouseModel = new WarehousesBase();
        $builder = $warehouseModel->where('state', 'ACTIVE')
            ->where('deleted_at IS NULL');

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        $warehouses = $builder->orderBy('name', 'ASC')->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $warehouses
        ]);
    }

    /**
     * Detalle de inventario / balance de una bodega específica
     */
    public function balance($warehouseId = null)
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado para acceder a este módulo.'
            ])->setStatusCode(403);
        }

        $warehouseId = filter_var($warehouseId, FILTER_VALIDATE_INT);
        if (!$warehouseId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Identificador de bodega no válido.'
            ])->setStatusCode(400);
        }

        $warehouseModel = new WarehousesBase();
        $warehouse = $warehouseModel->where('id', $warehouseId)
            ->where('deleted_at IS NULL')
            ->first();

        if (!$warehouse) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Bodega no encontrada.'
            ])->setStatusCode(404);
        }

        $balanceModel = new WarehousesBalance();
        $items = $balanceModel->where('id_warehouse', $warehouseId)
            ->where('deleted_at IS NULL')
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status'    => 'success',
            'warehouse' => $warehouse,
            'data'      => $items
        ]);
    }

    /**
     * Crear una nueva bodega
     */
    public function save()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $name = trim($this->request->getPost('name') ?? '');
        $adress = trim($this->request->getPost('adress') ?? '');
        $state = trim($this->request->getPost('state') ?? 'ACTIVE');

        if (empty($name) || empty($adress)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Nombre y dirección son campos requeridos.'
            ]);
        }

        if (!in_array($state, ['ACTIVE', 'INACTIVE'], true)) {
            $state = 'ACTIVE';
        }

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $warehouseModel = new WarehousesBase();
        $data = [
            'name'       => $name,
            'adress'     => $adress,
            'state'      => $state,
            'created_at' => $now,
            'updated_at' => $now
        ];

        if ($warehouseModel->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Bodega creada correctamente.',
                'id'      => $warehouseModel->getInsertID()
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Error al registrar la bodega.'
        ]);
    }

    /**
     * Guardar un nuevo artículo en el balance de la bodega
     */
    public function save_item()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $idWarehouse = filter_var($this->request->getPost('id_warehouse'), FILTER_VALIDATE_INT);
        $nameItem = trim($this->request->getPost('name_item') ?? '');
        $quantity = filter_var($this->request->getPost('quantity'), FILTER_VALIDATE_INT);

        if (!$idWarehouse || empty($nameItem) || $quantity === false || $quantity < 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Datos de artículo inválidos. Verifique el nombre y cantidad.'
            ]);
        }

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $balanceModel = new WarehousesBalance();
        $data = [
            'id_warehouse' => $idWarehouse,
            'name_item'    => $nameItem,
            'quantity'     => $quantity,
            'created_at'   => $now,
            'updated_at'   => $now
        ];

        if ($balanceModel->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Artículo agregado al balance correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Error al agregar el artículo al inventario.'
        ]);
    }

    /**
     * Realizar transferencia transaccional segura entre bodegas
     */
    public function transfer()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $idWarehouseSend = filter_var($this->request->getPost('id_warehouse_send'), FILTER_VALIDATE_INT);
        $idWarehouseReceives = filter_var($this->request->getPost('id_warehouse_receives'), FILTER_VALIDATE_INT);
        $itemNames = $this->request->getPost('item_name');
        $itemQuantities = $this->request->getPost('item_quantity');

        if (!$idWarehouseSend || !$idWarehouseReceives) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe especificar tanto la bodega de origen como la de destino.'
            ]);
        }

        if ($idWarehouseSend === $idWarehouseReceives) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'La bodega de destino no puede ser la misma de origen.'
            ]);
        }

        if (empty($itemNames) || !is_array($itemNames) || empty($itemQuantities) || !is_array($itemQuantities)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe incluir al menos un artículo válido para transferir.'
            ]);
        }

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        // 1.0 Crear registro cabecera de la transferencia
        $transferBaseModel = new WarehousesTransferBase();
        $transferData = [
            'id_warehouse_send'     => $idWarehouseSend,
            'id_warehouse_receives' => $idWarehouseReceives,
            'id_user'               => session('user_id'),
            'created_at'            => $now,
            'updated_at'            => $now
        ];

        $transferBaseModel->insert($transferData);
        $transferId = $transferBaseModel->getInsertID();

        if (!$transferId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al registrar la cabecera de la transferencia.'
            ]);
        }

        $balanceModel = new WarehousesBalance();
        $transferItemsModel = new WarehousesTransferItems();

        $processedCount = 0;
        $itemsCount = count($itemNames);

        for ($i = 0; $i < $itemsCount; $i++) {
            $nameItem = trim($itemNames[$i] ?? '');
            $qty = filter_var($itemQuantities[$i] ?? 0, FILTER_VALIDATE_INT);

            if (empty($nameItem) || $qty === false || $qty <= 0) {
                continue;
            }

            // 2.0 Verificar stock disponible en bodega origen
            $originBalance = $balanceModel->where('id_warehouse', $idWarehouseSend)
                ->where('name_item', $nameItem)
                ->where('deleted_at IS NULL')
                ->first();

            if (!$originBalance || (int)$originBalance['quantity'] < $qty) {
                $db->transRollback();
                $available = $originBalance ? (int)$originBalance['quantity'] : 0;
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "Stock insuficiente para '{$nameItem}'. Disponible: {$available}, Solicitado: {$qty}."
                ]);
            }

            // 3.0 Descontar saldo en bodega origen
            $newOriginQty = (int)$originBalance['quantity'] - $qty;
            $updateOrigin = $balanceModel->update($originBalance['id'], [
                'quantity'   => $newOriginQty,
                'updated_at' => $now
            ]);

            if (!$updateOrigin) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "Error al descontar stock de '{$nameItem}' en la bodega de origen."
                ]);
            }

            // 4.0 Aumentar saldo o registrar nuevo artículo en bodega destino
            $destBalance = $balanceModel->where('id_warehouse', $idWarehouseReceives)
                ->where('name_item', $nameItem)
                ->where('deleted_at IS NULL')
                ->first();

            if ($destBalance) {
                $newDestQty = (int)$destBalance['quantity'] + $qty;
                $updateDest = $balanceModel->update($destBalance['id'], [
                    'quantity'   => $newDestQty,
                    'updated_at' => $now
                ]);

                if (!$updateDest) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => "Error al actualizar stock de '{$nameItem}' en la bodega de destino."
                    ]);
                }
            } else {
                $insertDest = $balanceModel->insert([
                    'id_warehouse' => $idWarehouseReceives,
                    'name_item'    => $nameItem,
                    'quantity'     => $qty,
                    'created_at'   => $now,
                    'updated_at'   => $now
                ]);

                if (!$insertDest) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => "Error al ingresar stock de '{$nameItem}' en la bodega de destino."
                    ]);
                }
            }

            // 5.0 Registrar línea del item transferido
            $transferItemsModel->insert([
                'id_warehouse_transfer' => $transferId,
                'name'                  => $nameItem,
                'quantity'              => $qty
            ]);

            $processedCount++;
        }

        if ($processedCount === 0) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se procesó ningún artículo válido para la transferencia.'
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error durante la transacción de base de datos. Todos los cambios se han revertido de manera segura.'
            ]);
        }

        return $this->response->setJSON([
            'status'      => 'success',
            'message'     => 'Transferencia realizada y saldos ajustados correctamente.',
            'transfer_id' => $transferId
        ]);
    }
}
