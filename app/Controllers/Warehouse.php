<?php

namespace App\Controllers;

use App\Models\WarehousesBase;
use App\Models\WarehousesBalance;
use App\Models\WarehousesTransferBase;
use App\Models\WarehousesTransferItems;
use App\Models\Families;
use App\Models\DispatchAdvices;
use App\Models\DispatchAdviceItems;
use App\Libraries\ExcelHelper;

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
     * Obtener el ID de la primera bodega registrada (bodega principal de la empresa)
     */
    private function getMainWarehouseId(): ?int
    {
        $warehouseModel = new WarehousesBase();
        $firstWarehouse = $warehouseModel->where('deleted_at IS NULL')
            ->orderBy('id', 'ASC')
            ->first();

        return $firstWarehouse ? (int)$firstWarehouse['id'] : null;
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
        $builder->join('warehouses_balance b', 'b.id_warehouse = wb.id AND b.deleted_at IS NULL AND b.quantity > 0', 'left');
        $builder->where('wb.deleted_at IS NULL');
        $builder->groupBy('wb.id, wb.name, wb.adress, wb.state, wb.created_at, wb.updated_at');
        $builder->orderBy('wb.id', 'ASC');

        $records = $builder->get()->getResultArray();

        $mainWarehouseId = $this->getMainWarehouseId();
        foreach ($records as &$rec) {
            $rec['is_main'] = ($mainWarehouseId !== null && (int)$rec['id'] === $mainWarehouseId);
        }
        unset($rec);

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

        $mainWarehouseId = $this->getMainWarehouseId();
        $isMain = ($mainWarehouseId !== null && (int)$warehouse['id'] === $mainWarehouseId);
        $warehouse['is_main'] = $isMain;

        $balanceModel = new WarehousesBalance();
        $items = $balanceModel->getBalanceWithFamilies($warehouseId);

        return $this->response->setJSON([
            'status'    => 'success',
            'warehouse' => $warehouse,
            'is_main'   => $isMain,
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
     * Buscar familias activas para autocompletado en registro de items
     */
    public function search_families()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $term = trim($this->request->getGet('q') ?? '');

        $familiesModel = new Families();
        $builder = $familiesModel->select('id, keyword, img_path')
            ->where('state', 'ACTIVO')
            ->where('deleted_at IS NULL');

        if (!empty($term)) {
            $builder->like('keyword', $term);
        }

        $families = $builder->orderBy('keyword', 'ASC')
            ->limit(50)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $families
        ]);
    }

    /**
     * Guardar un nuevo artículo en el balance de la bodega con id_family
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
        $idFamily    = filter_var($this->request->getPost('id_family'), FILTER_VALIDATE_INT);
        $quantity    = filter_var($this->request->getPost('quantity'), FILTER_VALIDATE_INT);
        $lotRaw      = trim($this->request->getPost('lot') ?? '');
        $lot         = $lotRaw === '' ? null : substr($lotRaw, 0, 25);
        $expDateRaw  = trim($this->request->getPost('expiration_date') ?? '');
        $expirationDate = null;
        if (!empty($expDateRaw)) {
            $parsedDate = date_create($expDateRaw);
            if ($parsedDate) {
                $expirationDate = $parsedDate->format('Y-m-d H:i:s');
            }
        }

        if (!$idWarehouse || !$idFamily || $quantity === false || $quantity < 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe seleccionar un producto válido de la lista y especificar una cantidad permitida.'
            ]);
        }

        $familyModel = new Families();
        $family = $familyModel->where('id', $idFamily)
            ->where('state', 'ACTIVO')
            ->where('deleted_at IS NULL')
            ->first();

        if (!$family) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El producto seleccionado no pertenece al catálogo de familias activas.'
            ])->setStatusCode(400);
        }

        $mainWarehouseId = $this->getMainWarehouseId();
        if ($mainWarehouseId === null || (int)$idWarehouse !== $mainWarehouseId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Solo se permite registrar artículos directamente en la bodega principal de la empresa.'
            ])->setStatusCode(400);
        }

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $balanceModel = new WarehousesBalance();
        $query = $balanceModel->where('id_warehouse', $idWarehouse)
            ->where('id_family', $idFamily)
            ->where('deleted_at IS NULL');

        if ($lot !== null) {
            $query->where('lot', $lot);
        } else {
            $query->where('(lot IS NULL OR lot = "")');
        }

        $existing = $query->first();

        if ($existing) {
            $newQuantity = (int)$existing['quantity'] + $quantity;
            $updateData = [
                'quantity'   => $newQuantity,
                'updated_at' => $now
            ];
            if ($expirationDate !== null) {
                $updateData['expiration_date'] = $expirationDate;
            }
            $updated = $balanceModel->update($existing['id'], $updateData);

            if ($updated) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Se incrementaron las existencias del producto en la bodega correctamente.'
                ]);
            }
        } else {
            $data = [
                'id_warehouse'    => $idWarehouse,
                'id_family'       => $idFamily,
                'quantity'        => $quantity,
                'lot'             => $lot,
                'expiration_date' => $expirationDate,
                'created_at'      => $now,
                'updated_at'      => $now
            ];

            if ($balanceModel->insert($data)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Artículo agregado al balance correctamente.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Error al agregar el artículo al inventario.'
        ]);
    }

    /**
     * Generar remisión con consecutivo oficial y registrar artículos en el balance de la bodega
     */
    public function save_remision()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $idWarehouse = filter_var($this->request->getPost('id_warehouse'), FILTER_VALIDATE_INT);
        $ciudad = filter_var($this->request->getPost('ciudad'), FILTER_VALIDATE_INT);
        $cliente = trim($this->request->getPost('cliente') ?? '');
        $nit = trim($this->request->getPost('nit') ?? '');
        $adress = trim($this->request->getPost('adress') ?? '');
        $dispatcher = trim($this->request->getPost('dispatcher') ?? '');
        $observacion = trim($this->request->getPost('observacion') ?? '');

        if (!$idWarehouse) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se especificó la bodega.'
            ]);
        }

        $mainWarehouseId = $this->getMainWarehouseId();
        if ($mainWarehouseId === null || (int)$idWarehouse !== $mainWarehouseId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Solo se permite generar remisiones de entrada directamente en la bodega principal.'
            ])->setStatusCode(400);
        }

        $warehouseModel = new WarehousesBase();
        $warehouseDest = $warehouseModel->find($idWarehouse);
        $destName = $warehouseDest ? $warehouseDest['name'] : 'Bodega Principal';
        $destAdress = $warehouseDest ? $warehouseDest['adress'] : '';

        // Datos fijos solicitados para cabecera de remisión de ingreso a bodega principal
        $ciudad = 1;
        $dispatcher = 'Proveedor';
        $cliente = $destName;
        $nit = '1';
        $adress = $destAdress;
        $observacion = 'Esta remision es automatica por el sistema para registrar los ingresos a bodega principal.';

        $familyIds = $this->request->getPost('id_family');
        $itemNames = $this->request->getPost('item_name');
        $referencias = $this->request->getPost('item_referencia');
        $lotes = $this->request->getPost('item_lote');
        $vencimientos = $this->request->getPost('item_vencimiento');
        $cantidades = $this->request->getPost('item_cantidad');

        if (!is_array($cantidades) || empty($cantidades)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe ingresar al menos una línea de artículo para la remisión.'
            ]);
        }

        // Obtener último consecutivo para la ciudad fija (1)
        $dispatchModel = new DispatchAdvices();
        $lastDispatch = $dispatchModel->where('city', $ciudad)
            ->orderBy('sequence', 'DESC')
            ->first();
        $nextSequence = ($lastDispatch && isset($lastDispatch['sequence'])) ? ((int)$lastDispatch['sequence'] + 1) : 1;

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $headerData = [
            'client'        => $cliente,
            'nit'           => $nit,
            'adress'        => $adress,
            'sequence'      => $nextSequence,
            'city'          => $ciudad,
            'type'          => 'INGRESO',
            'transfer_code' => 'ING-BOD-' . $idWarehouse,
            'observation'   => $observacion,
            'dispatcher'    => $dispatcher,
            'did_user'      => session('user_id'),
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $dispatchModel->insert($headerData);
        $dispatchId = $dispatchModel->getInsertID();

        if (!$dispatchId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al registrar la cabecera de la remisión.'
            ]);
        }

        $itemsModel = new DispatchAdviceItems();
        $balanceModel = new WarehousesBalance();
        $familyModel = new Families();

        $itemsCount = count($cantidades);
        $processedCount = 0;

        for ($i = 0; $i < $itemsCount; $i++) {
            $qty = filter_var($cantidades[$i] ?? 0, FILTER_VALIDATE_INT);
            $famId = filter_var($familyIds[$i] ?? null, FILTER_VALIDATE_INT);
            $rawName = trim($itemNames[$i] ?? '');
            $ref = trim($referencias[$i] ?? '');
            $lotRaw = trim($lotes[$i] ?? '');
            $lot = $lotRaw === '' ? null : substr($lotRaw, 0, 25);
            $expDateRaw = trim($vencimientos[$i] ?? '');
            $expDate = null;
            if (!empty($expDateRaw)) {
                $pDate = date_create($expDateRaw);
                if ($pDate) {
                    $expDate = $pDate->format('Y-m-d H:i:s');
                }
            }

            if ($qty === false || $qty <= 0) {
                continue;
            }

            // Buscar producto / familia
            $family = null;
            if ($famId) {
                $family = $familyModel->where('id', $famId)
                    ->where('state', 'ACTIVO')
                    ->where('deleted_at IS NULL')
                    ->first();
            }

            if (!$family && !empty($rawName)) {
                $family = $familyModel->where('keyword', $rawName)
                    ->where('state', 'ACTIVO')
                    ->where('deleted_at IS NULL')
                    ->first();
                if ($family) {
                    $famId = (int)$family['id'];
                }
            }

            if (!$family) {
                $db->transRollback();
                $itemLabel = !empty($rawName) ? $rawName : ('Línea #' . ($i + 1));
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "El producto '{$itemLabel}' no pertenece al catálogo de familias activas."
                ]);
            }

            $description = $family['keyword'];

            // 1. Guardar item de la remisión
            $itemData = [
                'id_base'         => $dispatchId,
                'reference'       => $ref,
                'description'     => $description,
                'batch'           => $lot ?? '',
                'expiration_date' => $expDate,
                'quiantity'       => $qty,
                'created_at'      => $now,
                'updated_at'      => $now
            ];
            $itemsModel->insert($itemData);

            // 2. Ingresar/actualizar existencias en warehouses_balance
            $balQuery = $balanceModel->where('id_warehouse', $idWarehouse)
                ->where('id_family', $famId)
                ->where('deleted_at IS NULL');

            if ($lot !== null) {
                $balQuery->where('lot', $lot);
            } else {
                $balQuery->where('(lot IS NULL OR lot = "")');
            }

            $existing = $balQuery->first();
            if ($existing) {
                $newQty = (int)$existing['quantity'] + $qty;
                $updateData = [
                    'quantity'   => $newQty,
                    'updated_at' => $now
                ];
                if ($expDate !== null) {
                    $updateData['expiration_date'] = $expDate;
                }
                $balanceModel->update($existing['id'], $updateData);
            } else {
                $balanceModel->insert([
                    'id_warehouse'    => $idWarehouse,
                    'id_family'       => $famId,
                    'quantity'        => $qty,
                    'lot'             => $lot,
                    'expiration_date' => $expDate,
                    'created_at'      => $now,
                    'updated_at'      => $now
                ]);
            }

            $processedCount++;
        }

        if ($processedCount === 0) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se ingresaron líneas válidas con cantidad superior a 0.'
            ]);
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al guardar la remisión y el inventario en la base de datos.'
            ]);
        }

        return $this->response->setJSON([
            'status'      => 'success',
            'message'     => 'Remisión generada exitosamente (Consecutivo N° ' . $nextSequence . ') e inventario cargado a la bodega.',
            'sequence'    => $nextSequence,
            'dispatch_id' => $dispatchId
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
        $balanceIds = $this->request->getPost('balance_id');
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

        $itemsList = is_array($balanceIds) ? $balanceIds : (is_array($itemNames) ? $itemNames : []);
        if (empty($itemsList) || empty($itemQuantities) || !is_array($itemQuantities)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe incluir al menos un artículo válido para transferir.'
            ]);
        }

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $warehouseModel = new WarehousesBase();
        $sendWarehouse = $warehouseModel->find($idWarehouseSend);
        $receivesWarehouse = $warehouseModel->find($idWarehouseReceives);

        $sendName = $sendWarehouse ? $sendWarehouse['name'] : ('Bodega #' . $idWarehouseSend);
        $receivesName = $receivesWarehouse ? $receivesWarehouse['name'] : ('Bodega #' . $idWarehouseReceives);
        $receivesAdress = $receivesWarehouse ? $receivesWarehouse['adress'] : '';

        $db = \Config\Database::connect();
        $db->transStart();

        // 1.0 Crear registro cabecera de la transferencia interna
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

        // 1.1 Crear remisión oficial automática por el traslado entre bodegas
        $dispatchModel = new DispatchAdvices();
        $lastDispatch = $dispatchModel->where('city', 1)
            ->orderBy('sequence', 'DESC')
            ->first();
        $nextSequence = ($lastDispatch && isset($lastDispatch['sequence'])) ? ((int)$lastDispatch['sequence'] + 1) : 1;

        $dispatchHeader = [
            'client'        => $receivesName,
            'nit'           => '1',
            'adress'        => $receivesAdress,
            'sequence'      => $nextSequence,
            'city'          => 1,
            'type'          => 'INTERNO',
            'transfer_code' => 'TRAS-BOD-' . $idWarehouseSend . '-' . $idWarehouseReceives,
            'observation'   => 'Esta remision es automatica por el sistema para registrar el traslado de ' . $sendName . ' a ' . $receivesName . '.',
            'dispatcher'    => $sendName,
            'did_user'      => session('user_id'),
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        $dispatchModel->insert($dispatchHeader);
        $dispatchId = $dispatchModel->getInsertID();

        if (!$dispatchId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al generar la remisión de la transferencia.'
            ]);
        }

        $balanceModel = new WarehousesBalance();
        $transferItemsModel = new WarehousesTransferItems();
        $dispatchItemsModel = new DispatchAdviceItems();
        $familyModel = new Families();

        $processedCount = 0;
        $itemsCount = count($itemsList);

        for ($i = 0; $i < $itemsCount; $i++) {
            $rawId = $balanceIds[$i] ?? null;
            $rawName = trim($itemNames[$i] ?? '');
            $qty = filter_var($itemQuantities[$i] ?? 0, FILTER_VALIDATE_INT);

            if ((empty($rawId) && empty($rawName)) || $qty === false || $qty <= 0) {
                continue;
            }

            // 2.0 Verificar stock disponible en bodega origen
            $originBalance = null;
            if (!empty($rawId)) {
                $originBalance = $balanceModel->where('id_warehouse', $idWarehouseSend)
                    ->where('id', (int)$rawId)
                    ->where('deleted_at IS NULL')
                    ->first();
            }

            if (!$originBalance && !empty($rawName)) {
                $family = $familyModel->where('keyword', $rawName)->where('deleted_at IS NULL')->first();
                if ($family) {
                    $originBalance = $balanceModel->where('id_warehouse', $idWarehouseSend)
                        ->where('id_family', $family['id'])
                        ->where('deleted_at IS NULL')
                        ->first();
                }
            }

            if (!$originBalance || (int)$originBalance['quantity'] < $qty) {
                $db->transRollback();
                $available = $originBalance ? (int)$originBalance['quantity'] : 0;
                $label = !empty($rawName) ? $rawName : ('ID #' . $rawId);
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "Stock insuficiente para '{$label}'. Disponible: {$available}, Solicitado: {$qty}."
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
                    'message' => "Error al descontar stock en la bodega de origen."
                ]);
            }

            // 4.0 Aumentar saldo o registrar nuevo artículo en bodega destino
            $destQuery = $balanceModel->where('id_warehouse', $idWarehouseReceives)
                ->where('id_family', $originBalance['id_family'])
                ->where('deleted_at IS NULL');

            $itemLot = !empty($originBalance['lot']) ? trim($originBalance['lot']) : null;
            if ($itemLot !== null && $itemLot !== '') {
                $destQuery->where('lot', $itemLot);
            } else {
                $destQuery->where('(lot IS NULL OR lot = "")');
            }

            $destBalance = $destQuery->first();

            if ($destBalance) {
                $newDestQty = (int)$destBalance['quantity'] + $qty;
                $updateDestData = [
                    'quantity'   => $newDestQty,
                    'updated_at' => $now
                ];
                if (!empty($originBalance['expiration_date']) && empty($destBalance['expiration_date'])) {
                    $updateDestData['expiration_date'] = $originBalance['expiration_date'];
                }

                $updateDest = $balanceModel->update($destBalance['id'], $updateDestData);

                if (!$updateDest) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => "Error al actualizar stock en la bodega de destino."
                    ]);
                }
            } else {
                $insertDest = $balanceModel->insert([
                    'id_warehouse'    => $idWarehouseReceives,
                    'id_family'       => $originBalance['id_family'],
                    'quantity'        => $qty,
                    'lot'             => $itemLot,
                    'expiration_date' => $originBalance['expiration_date'] ?? null,
                    'created_at'      => $now,
                    'updated_at'      => $now
                ]);

                if (!$insertDest) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => "Error al ingresar stock en la bodega de destino."
                    ]);
                }
            }

            // 5.0 Registrar línea del item transferido con id_family
            $transferItemsModel->insert([
                'id_warehouse_transfer' => $transferId,
                'id_family'             => $originBalance['id_family'],
                'quantity'              => $qty
            ]);

            // 5.1 Registrar línea correspondiente en la remisión de traslado
            $family = $familyModel->find($originBalance['id_family']);
            $familyName = $family ? $family['keyword'] : (!empty($rawName) ? $rawName : ('Producto #' . $originBalance['id_family']));
            $displayName = ($itemLot !== null && $itemLot !== '')
                ? $familyName . ' [Lote: ' . $itemLot . ']'
                : $familyName;

            $dispatchItemsModel->insert([
                'id_base'         => $dispatchId,
                'reference'       => 'TR-' . $originBalance['id_family'],
                'description'     => $displayName,
                'batch'           => $itemLot ?? '',
                'expiration_date' => $originBalance['expiration_date'] ?? null,
                'quiantity'       => $qty,
                'created_at'      => $now,
                'updated_at'      => $now
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
            'message'     => 'Transferencia realizada y Remisión N° ' . $nextSequence . ' generada correctamente.',
            'transfer_id' => $transferId,
            'dispatch_id' => $dispatchId,
            'sequence'    => $nextSequence
        ]);
    }

    /**
     * Realizar ajuste de inventario en una bodega (aumentar/ingresar saldos) y generar documento tipo AJUSTE
     */
    public function adjust()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        $idWarehouse = filter_var($this->request->getPost('id_warehouse'), FILTER_VALIDATE_INT);
        $observacion = trim($this->request->getPost('observacion') ?? '');

        if (!$idWarehouse) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se especificó la bodega a ajustar.'
            ]);
        }

        if (empty($observacion)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe ingresar una descripción o motivo para el ajuste.'
            ]);
        }

        $warehouseModel = new WarehousesBase();
        $warehouse = $warehouseModel->find($idWarehouse);
        if (!$warehouse) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'La bodega especificada no existe.'
            ]);
        }

        $balanceIds = $this->request->getPost('balance_id');
        $cantidades = $this->request->getPost('item_cantidad');

        if (!is_array($cantidades) || empty($cantidades) || !is_array($balanceIds) || empty($balanceIds)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe ingresar al menos una línea de artículo para el ajuste.'
            ]);
        }

        $ciudad = 1;
        $dispatchModel = new DispatchAdvices();
        $lastDispatch = $dispatchModel->where('city', $ciudad)
            ->orderBy('sequence', 'DESC')
            ->first();
        $nextSequence = ($lastDispatch && isset($lastDispatch['sequence'])) ? ((int)$lastDispatch['sequence'] + 1) : 1;

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $destName = $warehouse['name'];
        $destAdress = $warehouse['adress'] ?? '';
        $dispatcher = session('user_name') ?? 'Ajuste de Sistema';

        $headerData = [
            'client'        => 'Ajuste - ' . $destName,
            'nit'           => '1',
            'adress'        => $destAdress,
            'sequence'      => $nextSequence,
            'city'          => $ciudad,
            'type'          => 'AJUSTE',
            'transfer_code' => 'AJUSTE-BOD-' . $idWarehouse,
            'observation'   => $observacion,
            'dispatcher'    => $dispatcher,
            'did_user'      => session('user_id'),
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $dispatchModel->insert($headerData);
        $dispatchId = $dispatchModel->getInsertID();

        if (!$dispatchId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al registrar la cabecera del documento de ajuste.'
            ]);
        }

        $itemsModel = new DispatchAdviceItems();
        $balanceModel = new WarehousesBalance();
        $familyModel = new Families();

        $itemsCount = count($cantidades);
        $processedCount = 0;

        for ($i = 0; $i < $itemsCount; $i++) {
            $balId = filter_var($balanceIds[$i] ?? null, FILTER_VALIDATE_INT);
            $qty = filter_var($cantidades[$i] ?? 0, FILTER_VALIDATE_INT);

            if (!$balId || $qty === false || $qty === 0) {
                continue;
            }

            // Buscar registro existente en el inventario de esta bodega
            $existing = $balanceModel->where('id_warehouse', $idWarehouse)
                ->where('id', $balId)
                ->where('deleted_at IS NULL')
                ->first();

            if (!$existing) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "El artículo de la línea #" . ($i + 1) . " no existe en el inventario de esta bodega."
                ]);
            }

            $currentQty = (int)($existing['quantity'] ?? 0);
            $newQty = $currentQty + $qty;

            if ($newQty < 0) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "La cantidad a descontar en la línea #" . ($i + 1) . " ({$qty}) supera el saldo actual ({$currentQty}). El saldo no puede ser negativo."
                ]);
            }

            // Obtener datos de la familia para descripción y referencia
            $famId = !empty($existing['id_family']) ? (int)$existing['id_family'] : null;
            $family = null;
            if ($famId) {
                $family = $familyModel->where('id', $famId)->first();
            }
            $description = $family ? ($family['keyword'] ?? ('Producto #' . $famId)) : ('Producto #' . $balId);
            $ref = $family['reference'] ?? '';

            // 1. Guardar item del ajuste (dispatch_advice_items) para auditoría y remisión
            $itemData = [
                'id_base'         => $dispatchId,
                'reference'       => $ref,
                'description'     => $description,
                'batch'           => $existing['lot'] ?? '',
                'expiration_date' => $existing['expiration_date'] ?? null,
                'quiantity'       => $qty,
                'created_at'      => $now,
                'updated_at'      => $now
            ];
            $itemsModel->insert($itemData);

            // 2. Actualizar existencias en warehouses_balance
            $balanceModel->update($existing['id'], [
                'quantity'   => $newQty,
                'updated_at' => $now
            ]);

            $processedCount++;
        }

        if ($processedCount === 0) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se ingresaron líneas válidas con cantidad diferente de 0.'
            ]);
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al guardar el ajuste y las existencias en la base de datos.'
            ]);
        }

        return $this->response->setJSON([
            'status'      => 'success',
            'message'     => 'Ajuste registrado exitosamente (Documento N° ' . $nextSequence . ') e inventario actualizado.',
            'sequence'    => $nextSequence,
            'dispatch_id' => $dispatchId
        ]);
    }

    /**
     * Descargar plantilla Excel para cargue masivo de productos a la bodega principal
     */
    public function download_batch_template()
    {
        if (!$this->checkPermission()) {
            return $this->response->setStatusCode(403)->setBody('No autorizado.');
        }

        $familyModel = new Families();
        $families = $familyModel->select('id, keyword')
            ->where('state', 'ACTIVO')
            ->where('deleted_at IS NULL')
            ->orderBy('keyword', 'ASC')
            ->findAll();

        $headers = [
            'ID_PRODUCTO',
            'NOMBRE_PRODUCTO',
            'CANTIDAD',
            'LOTE',
            'REFERENCIA',
            'FECHA_VENCIMIENTO'
        ];

        $rows = [];
        foreach ($families as $f) {
            $rows[] = [
                (int)$f['id'],
                $f['keyword'],
                '', // Cantidad vacía
                '', // Lote vacío
                '', // Referencia vacía
                ''  // Fecha de vencimiento vacía
            ];
        }

        $xlsxContent = ExcelHelper::generateXlsx($headers, $rows);
        $filename = 'plantilla_cargue_masivo_bodega_principal.xlsx';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0, no-cache, must-revalidate')
            ->setBody($xlsxContent);
    }

    /**
     * Procesar cargue masivo de productos desde archivo Excel/CSV e ingresarlos a la bodega principal
     * Generando remisión con tipo INGRESO
     */
    public function import_batch_items()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado para acceder a este módulo.'
            ])->setStatusCode(403);
        }

        $mainWarehouseId = $this->getMainWarehouseId();
        if ($mainWarehouseId === null) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontró la bodega principal configurada en el sistema.'
            ])->setStatusCode(400);
        }

        $file = $this->request->getFile('excel_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe seleccionar un archivo válido para procesar el cargue masivo.'
            ]);
        }

        $ext = strtolower($file->getClientExtension());
        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        if (!in_array($ext, $allowedExtensions, true)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Formato no permitido. Solo se aceptan archivos Excel (.xlsx, .xls) o CSV (.csv).'
            ]);
        }

        // Límite de tamaño: 10MB
        if ($file->getSizeByUnit('mb') > 10) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El archivo supera el tamaño máximo permitido de 10 MB.'
            ]);
        }

        $tempPath = $file->getTempName();
        $originalName = $file->getClientName();

        try {
            $parsedRows = ExcelHelper::parseFile($tempPath, $originalName);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al leer el archivo Excel: ' . $e->getMessage()
            ]);
        }

        if (empty($parsedRows)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El archivo subido está vacío.'
            ]);
        }

        // Primera fila puede ser encabezado
        $headerCandidate = $parsedRows[0];
        $isHeader = false;
        if (isset($headerCandidate[0]) && (
            stripos((string)$headerCandidate[0], 'id') !== false ||
            stripos((string)$headerCandidate[0], 'producto') !== false ||
            stripos((string)($headerCandidate[1] ?? ''), 'nombre') !== false ||
            stripos((string)($headerCandidate[1] ?? ''), 'producto') !== false
        )) {
            $isHeader = true;
        }

        $dataRows = $isHeader ? array_slice($parsedRows, 1) : $parsedRows;

        // Cargar catálogo de familias activas para indexación rápida por ID y por keyword
        $familyModel = new Families();
        $allFamilies = $familyModel->where('state', 'ACTIVO')
            ->where('deleted_at IS NULL')
            ->findAll();

        $familiesById = [];
        $familiesByKeyword = [];
        foreach ($allFamilies as $fam) {
            $familiesById[(int)$fam['id']] = $fam;
            $familiesByKeyword[mb_strtolower(trim($fam['keyword']), 'UTF-8')] = $fam;
        }

        // Procesar y validar filas
        $validItems = [];
        $skippedCount = 0;
        $invalidRows = [];
        $totalQuantity = 0;

        foreach ($dataRows as $index => $row) {
            $rowNumber = $isHeader ? ($index + 2) : ($index + 1);

            $idRaw = trim($row[0] ?? '');
            $nameRaw = trim($row[1] ?? '');
            $qtyRaw = trim($row[2] ?? '');
            $lotRaw = trim($row[3] ?? '');
            $refRaw = trim($row[4] ?? '');
            $expDateRaw = trim($row[5] ?? '');

            // Si la cantidad está vacía o es 0, ignorar silenciosamente la fila
            if ($qtyRaw === '' || $qtyRaw === null) {
                $skippedCount++;
                continue;
            }

            // Validar que la cantidad sea numérica y positiva
            if (!is_numeric($qtyRaw) || (int)$qtyRaw <= 0) {
                // Si la fila no tiene ningún dato más, omitir
                if ($idRaw === '' && $nameRaw === '' && $lotRaw === '') {
                    $skippedCount++;
                    continue;
                }
                // Si tiene datos pero cantidad no válida, reportar
                if ((int)$qtyRaw < 0) {
                    $invalidRows[] = "Fila {$rowNumber}: la cantidad no puede ser negativa ({$qtyRaw}).";
                    continue;
                }
                $skippedCount++;
                continue;
            }

            $qty = (int)$qtyRaw;

            // Identificar producto por ID o por Nombre
            $family = null;
            $famId = filter_var($idRaw, FILTER_VALIDATE_INT);
            if ($famId && isset($familiesById[$famId])) {
                $family = $familiesById[$famId];
            } elseif (!empty($nameRaw)) {
                $key = mb_strtolower($nameRaw, 'UTF-8');
                if (isset($familiesByKeyword[$key])) {
                    $family = $familiesByKeyword[$key];
                    $famId = (int)$family['id'];
                }
            }

            if (!$family) {
                $itemLabel = !empty($nameRaw) ? $nameRaw : (!empty($idRaw) ? "ID #{$idRaw}" : "Fila {$rowNumber}");
                $invalidRows[] = "Fila {$rowNumber}: el producto '{$itemLabel}' no existe o no se encuentra activo en el catálogo.";
                continue;
            }

            // Normalizar lote (máx 25 para warehouse balance, máx 10 para dispatch item batch)
            $lot = ($lotRaw === '') ? null : substr($lotRaw, 0, 25);
            $batchForDispatch = ($lot !== null) ? substr($lot, 0, 10) : '';

            // Normalizar referencia
            $ref = ($refRaw === '') ? '' : substr($refRaw, 0, 75);

            // Normalizar fecha de vencimiento
            $expDate = null;
            if (!empty($expDateRaw)) {
                $parsedDate = null;
                if (is_numeric($expDateRaw) && (float)$expDateRaw > 30000 && (float)$expDateRaw < 60000) {
                    // Serial date de Excel
                    $unixTimestamp = ((float)$expDateRaw - 25569) * 86400;
                    $parsedDate = date_create('@' . (int)$unixTimestamp);
                } else {
                    $parsedDate = date_create($expDateRaw);
                }

                if ($parsedDate) {
                    $expDate = $parsedDate->format('Y-m-d H:i:s');
                }
            }

            $validItems[] = [
                'id_family'         => (int)$family['id'],
                'name'              => $family['keyword'],
                'quantity'          => $qty,
                'lot'               => $lot,
                'batch_dispatch'    => $batchForDispatch,
                'reference'         => $ref,
                'expiration_date'   => $expDate,
                'row_number'        => $rowNumber
            ];

            $totalQuantity += $qty;
        }

        if (!empty($invalidRows)) {
            $errorPreview = implode('<br>', array_slice($invalidRows, 0, 5));
            if (count($invalidRows) > 5) {
                $errorPreview .= '<br>... y ' . (count($invalidRows) - 5) . ' error(es) más.';
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Se encontraron inconsistencias en los datos del archivo:<br><br>' . $errorPreview
            ]);
        }

        if (empty($validItems)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron filas con cantidades mayores a 0 para ingresar a la bodega principal.'
            ]);
        }

        // Obtener datos de la bodega principal
        $warehouseModel = new WarehousesBase();
        $warehouseDest = $warehouseModel->find($mainWarehouseId);
        $destName = $warehouseDest ? $warehouseDest['name'] : 'Bodega Principal';
        $destAdress = $warehouseDest ? $warehouseDest['adress'] : '';

        // Datos oficiales para remisión de tipo INGRESO
        $ciudad = 1;
        $dispatcher = 'Proveedor';
        $cliente = $destName;
        $nit = '1';
        $adress = $destAdress;
        $observacion = 'Cargue masivo automático a bodega principal desde archivo Excel (' . substr($originalName, 0, 50) . ').';

        // Obtener último consecutivo para la ciudad fija (1)
        $dispatchModel = new DispatchAdvices();
        $lastDispatch = $dispatchModel->where('city', $ciudad)
            ->orderBy('sequence', 'DESC')
            ->first();
        $nextSequence = ($lastDispatch && isset($lastDispatch['sequence'])) ? ((int)$lastDispatch['sequence'] + 1) : 1;

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $headerData = [
            'client'        => $cliente,
            'nit'           => $nit,
            'adress'        => $adress,
            'sequence'      => $nextSequence,
            'city'          => $ciudad,
            'type'          => 'INGRESO',
            'transfer_code' => 'ING-BOD-' . $mainWarehouseId,
            'observation'   => $observacion,
            'dispatcher'    => $dispatcher,
            'did_user'      => session('user_id'),
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $dispatchModel->insert($headerData);
        $dispatchId = $dispatchModel->getInsertID();

        if (!$dispatchId) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error al registrar la cabecera de la remisión de ingreso.'
            ]);
        }

        $itemsModel = new DispatchAdviceItems();
        $balanceModel = new WarehousesBalance();

        foreach ($validItems as $item) {
            // 1. Registrar item de la remisión
            $itemData = [
                'id_base'         => $dispatchId,
                'reference'       => $item['reference'],
                'description'     => $item['name'],
                'batch'           => $item['batch_dispatch'],
                'expiration_date' => $item['expiration_date'],
                'quiantity'       => $item['quantity'],
                'created_at'      => $now,
                'updated_at'      => $now
            ];
            $itemsModel->insert($itemData);

            // 2. Actualizar existencias en warehouses_balance
            $balQuery = $balanceModel->where('id_warehouse', $mainWarehouseId)
                ->where('id_family', $item['id_family'])
                ->where('deleted_at IS NULL');

            if ($item['lot'] !== null) {
                $balQuery->where('lot', $item['lot']);
            } else {
                $balQuery->where('(lot IS NULL OR lot = "")');
            }

            $existing = $balQuery->first();
            if ($existing) {
                $newQty = (int)$existing['quantity'] + $item['quantity'];
                $updateData = [
                    'quantity'   => $newQty,
                    'updated_at' => $now
                ];
                if ($item['expiration_date'] !== null) {
                    $updateData['expiration_date'] = $item['expiration_date'];
                }
                $balanceModel->update($existing['id'], $updateData);
            } else {
                $balanceModel->insert([
                    'id_warehouse'    => $mainWarehouseId,
                    'id_family'       => $item['id_family'],
                    'quantity'        => $item['quantity'],
                    'lot'             => $item['lot'],
                    'expiration_date' => $item['expiration_date'],
                    'created_at'      => $now,
                    'updated_at'      => $now
                ]);
            }
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Ocurrió un error transaccional al guardar la remisión y el inventario en la base de datos.'
            ]);
        }

        $totalItemsCount = count($validItems);

        return $this->response->setJSON([
            'status'         => 'success',
            'message'        => "Cargue masivo procesado exitosamente. Se generó la remisión N° {$nextSequence} de tipo INGRESO con {$totalItemsCount} producto(s) y un total de {$totalQuantity} unidades.",
            'sequence'       => $nextSequence,
            'dispatch_id'    => $dispatchId,
            'total_items'    => $totalItemsCount,
            'total_quantity' => $totalQuantity
        ]);
    }
}
