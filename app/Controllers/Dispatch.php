<?php

namespace App\Controllers;

use App\Models\DispatchAdvices;
use App\Models\DispatchAdviceItems;

class Dispatch extends BaseController
{
    // Formulario_G05
    public function save()
    {
        // 1.0 Verificar sesión
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        // 2.0 Recibir datos POST
        $ciudad = $this->request->getPost('ciudad');
        // Las remisiones creadas desde este formulario siempre son de tipo 'REMISION'
        $type = 'REMISION';
        $cliente = $this->request->getPost('cliente');
        $nit = $this->request->getPost('nit');
        $adress = $this->request->getPost('adress');
        $transferCode = $this->request->getPost('transfer_code');
        $dispatcher = $this->request->getPost('dispatcher');
        $observacion = $this->request->getPost('observacion');

        $descripciones = $this->request->getPost('item_descripcion');
        $cantidades = $this->request->getPost('item_cantidad');
        $referencias = $this->request->getPost('item_referencia');
        $lotes = $this->request->getPost('item_lote');
        $vencimientos = $this->request->getPost('item_vencimiento');

        if (!$ciudad) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Falta el campo ciudad que es obligatorio para el consecutivo']);
        }

        // 3.0 Obtener último consecutivo de la ciudad
        $dispatchModel = new DispatchAdvices();

        $lastDispatch = $dispatchModel->where('city', $ciudad)
            ->orderBy('sequence', 'DESC')
            ->first();

        $nextSequence = ($lastDispatch && isset($lastDispatch['sequence'])) ? ((int)$lastDispatch['sequence'] + 1) : 1;

        // 4.0 Configurar fecha hora colombiana
        $timezone = new \DateTimeZone('America/Bogota');
        $date = new \DateTime('now', $timezone);
        $createdAt = $date->format('Y-m-d H:i:s');

        // 5.0 Preparar datos cabecera
        $headerData = [
            'client'        => $cliente,
            'nit'           => $nit,
            'adress'        => $adress,
            'sequence'      => $nextSequence,
            'city'          => $ciudad,
            'type'          => $type,
            'transfer_code' => $transferCode,
            'observation'   => $observacion,
            'dispatcher'    => $dispatcher,
            'did_user'      => session('user_id'),
            'created_at'    => $createdAt,
            'updated_at'    => $createdAt
        ];

        // 6.0 Guardar cabecera y obtener ID
        $db = \Config\Database::connect();
        $db->transStart();

        $dispatchModel->insert($headerData);
        $dispatchId = $dispatchModel->getInsertID();

        // 7.0 Preparar y guardar items
        $itemsModel = new DispatchAdviceItems();

        $itemsCount = count($descripciones);
        for ($i = 0; $i < $itemsCount; $i++) {
            // Evitar guardar líneas vacías
            if (empty($referencias[$i]) && empty($descripciones[$i])) {
                continue;
            }
            $itemData = [
                'id_base'         => $dispatchId,
                'reference'       => $referencias[$i],
                'description'     => $descripciones[$i],
                'batch'           => $lotes[$i],
                'expiration_date' => !empty($vencimientos[$i]) ? $vencimientos[$i] : null,
                'quiantity'       => (int)$cantidades[$i],
                'created_at'      => $createdAt,
                'updated_at'      => $createdAt
            ];
            $itemsModel->insert($itemData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar en base de datos']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Remisión guardada correctamente con consecutivo ' . $nextSequence,
            'sequence' => $nextSequence
        ]);
    }

    // Tabla_G05
    public function listing()
    {
        $db = \Config\Database::connect();

        // 1. Obtener listado de bodegas para mapear ID y nombre
        $warehouses = $db->table('warehouses_base')
            ->select('id, name, adress, id_user_admin, id_client')
            ->where('deleted_at IS NULL')
            ->get()
            ->getResultArray();

        $warehouseMap = [];
        $warehouseByName = [];
        foreach ($warehouses as $w) {
            $warehouseMap[(int)$w['id']] = $w;
            $warehouseByName[mb_strtolower(trim($w['name']))] = $w;
        }

        // 2. Obtener usuarios para mapear administradores
        $users = $db->table('users')
            ->select('id, name, email')
            ->get()
            ->getResultArray();
        $userMap = [];
        foreach ($users as $u) {
            $userMap[(int)$u['id']] = $u;
        }

        // 3. Obtener clientes para mapear titulares
        $clients = $db->table('clients')
            ->select('id, nombre_cliente, numero_documento')
            ->get()
            ->getResultArray();
        $clientMap = [];
        $clientByName = [];
        foreach ($clients as $c) {
            $clientMap[(int)$c['id']] = $c;
            $clientByName[mb_strtolower(trim($c['nombre_cliente']))] = $c;
        }

        // 4. Hacemos JOIN con cities para obtener el nombre de la ciudad
        $builder = $db->table('dispatch_advice da');
        $builder->select('da.id, da.type, da.client, da.nit, da.adress, da.sequence, da.transfer_code, da.dispatcher, da.did_user, da.created_at, c.name as city_name, c.code as city_code');
        $builder->join('cities c', 'c.id = da.city', 'left');
        $builder->orderBy('da.id', 'DESC');

        $records = $builder->get()->getResultArray();
        $enrichedRecords = [];

        foreach ($records as $r) {
            $transferCode = trim($r['transfer_code'] ?? '');
            $didUserId = !empty($r['did_user']) ? (int)$r['did_user'] : null;

            $sendWarehouse = null;
            $receiveWarehouse = null;

            // 5. Detectar bodegas por código de traslado/ingreso/ajuste
            if (preg_match('/TRAS-BOD-(\d+)-(\d+)/i', $transferCode, $m)) {
                $idSend = (int)$m[1];
                $idRec  = (int)$m[2];
                $sendWarehouse    = $warehouseMap[$idSend] ?? null;
                $receiveWarehouse = $warehouseMap[$idRec] ?? null;
            } elseif (preg_match('/ING-BOD-(\d+)/i', $transferCode, $m)) {
                $idRec = (int)$m[1];
                $receiveWarehouse = $warehouseMap[$idRec] ?? null;
            } elseif (preg_match('/AJUSTE-BOD-(\d+)/i', $transferCode, $m)) {
                $idWh = (int)$m[1];
                $sendWarehouse    = $warehouseMap[$idWh] ?? null;
                $receiveWarehouse = $warehouseMap[$idWh] ?? null;
            }

            // Fallback por nombre de bodega
            if (!$sendWarehouse && !empty($r['dispatcher'])) {
                $sendWarehouse = $warehouseByName[mb_strtolower(trim($r['dispatcher']))] ?? null;
            }
            if (!$receiveWarehouse && !empty($r['client'])) {
                $receiveWarehouse = $warehouseByName[mb_strtolower(trim($r['client']))] ?? null;
            }

            // 6. Configurar datos de Origen / Envía
            $sendUserIds   = [];
            $sendClientIds = [];
            $sendWarehouseId   = $sendWarehouse ? (int)$sendWarehouse['id'] : null;

            $dispatcherUserId = !empty($r['dispatcher']) && is_numeric($r['dispatcher']) ? (int)$r['dispatcher'] : null;
            $dispatcherUserName = $dispatcherUserId && isset($userMap[$dispatcherUserId]) ? $userMap[$dispatcherUserId]['name'] : null;

            if ($r['type'] === 'INGRESO') {
                $sendWarehouseName = 'Proveedor / Ingreso';
            } else {
                $sendWarehouseName = $sendWarehouse ? $sendWarehouse['name'] : ($dispatcherUserName ?? ($r['dispatcher'] ?? 'N/A'));
            }

            $sendAdminName = null;
            $sendAdminId   = null;
            if ($sendWarehouse && !empty($sendWarehouse['id_user_admin'])) {
                $sendAdminId = (int)$sendWarehouse['id_user_admin'];
                $sendUserIds[] = $sendAdminId;
                $sendAdminName = $userMap[$sendAdminId]['name'] ?? ('Usuario #' . $sendAdminId);
            } elseif ($dispatcherUserName) {
                $sendAdminId = $dispatcherUserId;
                $sendUserIds[] = $dispatcherUserId;
                $sendAdminName = $dispatcherUserName;
            }
            if ($didUserId && !in_array($didUserId, $sendUserIds, true)) {
                $sendUserIds[] = $didUserId;
            }

            $sendTitularName = null;
            $sendTitularId   = null;
            if ($sendWarehouse && !empty($sendWarehouse['id_client'])) {
                $sendTitularId = (int)$sendWarehouse['id_client'];
                $sendClientIds[] = $sendTitularId;
                $sendTitularName = $clientMap[$sendTitularId]['nombre_cliente'] ?? ('Cliente #' . $sendTitularId);
            }

            // 7. Configurar datos de Destino / Recibe
            $receiveUserIds   = [];
            $receiveClientIds = [];
            $receiveWarehouseId   = $receiveWarehouse ? (int)$receiveWarehouse['id'] : null;
            $receiveWarehouseName = $receiveWarehouse ? $receiveWarehouse['name'] : ($r['client'] ?? 'N/A');

            $receiveAdminName = null;
            $receiveAdminId   = null;
            if ($receiveWarehouse && !empty($receiveWarehouse['id_user_admin'])) {
                $receiveAdminId = (int)$receiveWarehouse['id_user_admin'];
                $receiveUserIds[] = $receiveAdminId;
                $receiveAdminName = $userMap[$receiveAdminId]['name'] ?? ('Usuario #' . $receiveAdminId);
            }

            $receiveTitularName = null;
            $receiveTitularId   = null;
            if ($receiveWarehouse && !empty($receiveWarehouse['id_client'])) {
                $receiveTitularId = (int)$receiveWarehouse['id_client'];
                $receiveClientIds[] = $receiveTitularId;
                $receiveTitularName = $clientMap[$receiveTitularId]['nombre_cliente'] ?? ('Cliente #' . $receiveTitularId);
            } elseif (!empty($r['client'])) {
                $matchedClient = $clientByName[mb_strtolower(trim($r['client']))] ?? null;
                if ($matchedClient) {
                    $receiveTitularId = (int)$matchedClient['id'];
                    $receiveClientIds[] = $receiveTitularId;
                    $receiveTitularName = $matchedClient['nombre_cliente'];
                }
            }

            // Adjuntar datos enriquecidos
            $r['send_warehouse_id']   = $sendWarehouseId;
            $r['send_warehouse_name'] = $sendWarehouseName;
            $r['send_admin_id']       = $sendAdminId;
            $r['send_admin_name']     = $sendAdminName;
            $r['send_titular_id']     = $sendTitularId;
            $r['send_titular_name']   = $sendTitularName;
            $r['send_user_ids']       = $sendUserIds;
            $r['send_client_ids']     = $sendClientIds;

            $r['receive_warehouse_id']   = $receiveWarehouseId;
            $r['receive_warehouse_name'] = $receiveWarehouseName;
            $r['receive_admin_id']       = $receiveAdminId;
            $r['receive_admin_name']     = $receiveAdminName;
            $r['receive_titular_id']     = $receiveTitularId;
            $r['receive_titular_name']   = $receiveTitularName;
            $r['receive_user_ids']       = $receiveUserIds;
            $r['receive_client_ids']     = $receiveClientIds;

            $enrichedRecords[] = $r;
        }

        return $this->response->setJSON([
            'data' => $enrichedRecords
        ]);
    }

    // Tabla_G05
    public function view_pdf($id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('dispatch_advice da');
        $builder->select('da.*, c.name as city_name, c.code as city_code, u.name as dispatcher_name, u.dni as dispatcher_dni');
        $builder->join('cities c', 'c.id = da.city', 'left');
        $builder->join('users u', 'u.id = da.dispatcher', 'left');
        $builder->where('da.id', $id);

        $dispatch = $builder->get()->getRowArray();

        if (!$dispatch) {
            return "Remisión no encontrada.";
        }

        $itemsModel = new DispatchAdviceItems();
        $items = $itemsModel->where('id_base', $id)->findAll();

        return view('remision_pdf', [
            'dispatch' => $dispatch,
            'items' => $items
        ]);
    }
}
