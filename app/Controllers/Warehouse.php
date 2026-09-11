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
use App\Models\Users;

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
        return (isset($credentials[13]) && $credentials[13] === '1') || 
               (isset($credentials[14]) && $credentials[14] === '1') || 
               (isset($credentials[0]) && $credentials[0] === '1');
    }

    /**
     * Verificar si el usuario en sesión es responsable de la bodega o administrador
     */
    private function canOperateWarehouse(int $warehouseId): bool
    {
        if (!session()->has('user_id')) {
            return false;
        }

        $credentials = session('credentials');
        // Administrador general (credentials[0]) o Administrador de todas las bodegas (credentials[14])
        if ((isset($credentials[0]) && $credentials[0] === '1') || 
            (isset($credentials[14]) && $credentials[14] === '1')) {
            return true;
        }

        $warehouseModel = new WarehousesBase();
        $warehouse = $warehouseModel->where('id', $warehouseId)
            ->where('deleted_at IS NULL')
            ->first();

        if (!$warehouse) {
            return false;
        }

        return (int)($warehouse['id_user_admin'] ?? $warehouse['id_user'] ?? 0) === (int)session('user_id');
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

        $warehouseModel = new WarehousesBase();
        $records = $warehouseModel->getWarehousesWithDetails();

        $mainWarehouseId = $this->getMainWarehouseId();
        $currentUserId = (int)session('user_id');
        $credentials = session('credentials');
        $isAdmin = (isset($credentials[0]) && $credentials[0] === '1');
        $isWarehouseAdmin = (isset($credentials[14]) && $credentials[14] === '1');

        foreach ($records as &$rec) {
            $rec['is_main'] = ($mainWarehouseId !== null && (int)$rec['id'] === $mainWarehouseId);
            $rec['is_responsible'] = ($currentUserId === (int)($rec['id_user_admin'] ?? $rec['id_user'] ?? 0));
            $rec['can_operate'] = ($isAdmin || $isWarehouseAdmin || $rec['is_responsible']);
            // Compatibilidad y aliases
            $rec['admin'] = $rec['id_user_admin'];
            $rec['titular'] = $rec['id_client'];
            $rec['id_user'] = $rec['id_user_admin'];
            $rec['user_name'] = $rec['admin_name'] ?? null;
            $rec['user_email'] = $rec['admin_email'] ?? null;
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

        $currentUserId = (int)session('user_id');
        $credentials = session('credentials');
        $isAdmin = (isset($credentials[0]) && $credentials[0] === '1');
        $isWarehouseAdmin = (isset($credentials[14]) && $credentials[14] === '1');
        $isResponsible = ($currentUserId === (int)($warehouse['id_user_admin'] ?? $warehouse['id_user'] ?? 0));
        $canOperate = ($isAdmin || $isWarehouseAdmin || $isResponsible);
        $canAdjust = $isWarehouseAdmin; // La opción de ajustar es exclusiva para usuarios con credencial 14

        $warehouse['is_responsible'] = $isResponsible;
        $warehouse['can_operate'] = $canOperate;
        $warehouse['can_adjust'] = $canAdjust;

        $idAdmin = $warehouse['id_user_admin'] ?? $warehouse['id_user'] ?? null;
        if (!empty($idAdmin)) {
            $userModel = new Users();
            $respUser = $userModel->select('users.id, users.name, users.email, users.dni, users.adress, users.city, users.phone, cities.name as city_name')
                ->join('cities', 'cities.id = users.city', 'left')
                ->find($idAdmin);
            $warehouse['admin_id'] = $respUser ? $respUser['id'] : null;
            $warehouse['admin_name'] = $respUser ? $respUser['name'] : 'Sin asignar';
            $warehouse['admin_email'] = $respUser ? $respUser['email'] : '';
            $warehouse['admin_dni'] = $respUser ? (string)$respUser['dni'] : '';
            $warehouse['admin_adress'] = $respUser ? ($respUser['adress'] ?? '') : '';
            $warehouse['admin_city'] = $respUser ? $respUser['city'] : null;
            $warehouse['admin_city_name'] = $respUser ? ($respUser['city_name'] ?? '') : '';
            $warehouse['admin_phone'] = $respUser ? (string)$respUser['phone'] : '';
            $warehouse['responsible_name'] = $warehouse['admin_name'];
            $warehouse['responsible_email'] = $warehouse['admin_email'];
        } else {
            $warehouse['admin_id'] = null;
            $warehouse['admin_name'] = 'Sin asignar';
            $warehouse['admin_email'] = '';
            $warehouse['admin_dni'] = '';
            $warehouse['admin_adress'] = '';
            $warehouse['admin_city'] = null;
            $warehouse['admin_city_name'] = '';
            $warehouse['admin_phone'] = '';
            $warehouse['responsible_name'] = 'Sin asignar';
            $warehouse['responsible_email'] = '';
        }

        if (!empty($warehouse['id_client'])) {
            $clientModel = new \App\Models\Clients();
            $client = $clientModel->select('id, nombre_cliente, numero_documento')->find($warehouse['id_client']);
            $warehouse['client_name'] = $client ? $client['nombre_cliente'] : 'Sin asignar';
            $warehouse['titular_name'] = $warehouse['client_name'];
        } else {
            $warehouse['client_name'] = 'Sin asignar';
            $warehouse['titular_name'] = 'Sin asignar';
        }

        $balanceModel = new WarehousesBalance();
        $items = $balanceModel->getBalanceWithFamilies($warehouseId);

        return $this->response->setJSON([
            'status'      => 'success',
            'warehouse'   => $warehouse,
            'is_main'     => $isMain,
            'can_operate' => $canOperate,
            'can_adjust'  => $canAdjust,
            'data'        => $items
        ]);
    }

    /**
     * Obtener listado de usuarios autorizados para asignación como responsables de bodega
     */
    public function get_users()
    {
        if (!$this->checkPermission()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado.'
            ])->setStatusCode(403);
        }

        helper('utils');
        $userModel = new Users();
        $allUsers = $userModel->select('id, name, email, credentials')->orderBy('name', 'ASC')->findAll();

        $warehouseUsers = [];
        foreach ($allUsers as $u) {
            $userCreds = pad_right_zeros((string)($u['credentials'] ?? ''));
            if ((isset($userCreds[13]) && $userCreds[13] === '1') || (isset($userCreds[14]) && $userCreds[14] === '1')) {
                $warehouseUsers[] = [
                    'id'    => (int)$u['id'],
                    'name'  => $u['name'],
                    'email' => $u['email']
                ];
            }
        }

        // Fallback si ningún usuario tiene la credencial configurada aún
        if (empty($warehouseUsers)) {
            foreach ($allUsers as $u) {
                $warehouseUsers[] = [
                    'id'    => (int)$u['id'],
                    'name'  => $u['name'],
                    'email' => $u['email']
                ];
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $warehouseUsers
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
        $state = trim($this->request->getPost('state') ?? 'ACTIVE');
        $typeInput = strtoupper(trim($this->request->getPost('type') ?? 'EXTERNA'));
        $idUserAdmin = filter_var($this->request->getPost('id_user_admin') ?? $this->request->getPost('id_user'), FILTER_VALIDATE_INT);
        $idClient = filter_var($this->request->getPost('id_client'), FILTER_VALIDATE_INT) ?: null;

        if (empty($name)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El nombre de la bodega es un campo requerido.'
            ]);
        }

        if (!$idUserAdmin) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe seleccionar un usuario administrador para la bodega.'
            ]);
        }

        $type = in_array($typeInput, ['INTERNA', 'INTERNO'], true) ? 'INTERNO' : 'EXTERNO';
        if ($type === 'INTERNO') {
            $idClient = null;
        }

        $userModel = new Users();
        $assignedUser = $userModel->find($idUserAdmin);
        if (!$assignedUser) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El usuario seleccionado como administrador no existe.'
            ]);
        }

        if ($idClient) {
            $clientModel = new \App\Models\Clients();
            if (!$clientModel->find($idClient)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'El cliente titular seleccionado no existe.'
                ]);
            }
        }

        if (!in_array($state, ['ACTIVE', 'INACTIVE'], true)) {
            $state = 'ACTIVE';
        }

        $timezone = new \DateTimeZone('America/Bogota');
        $now = (new \DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $warehouseModel = new WarehousesBase();
        $data = [
            'name'          => $name,
            'state'         => $state,
            'type'          => $type,
            'id_user_admin' => $idUserAdmin,
            'id_client'     => $idClient,
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        if ($warehouseModel->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Bodega creada correctamente.',
                'id'      => $warehouseModel->getInsertID()
            ]);
        }

        $errors = $warehouseModel->errors();
        $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Error al registrar la bodega.';

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $errorMessage
        ]);
    }

    /**
     * Buscar referencias activas con sus familias para autocompletado en registro de items
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

        $refModel = new \App\Models\FamiliesReference();
        $builder = $refModel->select('families_reference.id as id, families_reference.id as reference_id, families_reference.reference, families_reference.id_family, families.keyword as family_name, CONCAT(families_reference.reference, " - ", families.keyword) as keyword')
            ->join('families', 'families.id = families_reference.id_family AND families.deleted_at IS NULL', 'inner')
            ->where('families.state', 'ACTIVO')
            ->where('families_reference.status', 'ACTIVE');

        if (!empty($term)) {
            $builder->groupStart()
                ->like('families_reference.reference', $term)
                ->orLike('families.keyword', $term)
                ->groupEnd();
        }

        $results = $builder->orderBy('families_reference.reference', 'ASC')
            ->orderBy('families.keyword', 'ASC')
            ->limit(50)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $results
        ]);
    }

    /**
     * Guardar un nuevo artículo en el balance de la bodega con id_reference
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
        $idReference = filter_var($this->request->getPost('id_reference') ?? $this->request->getPost('id_family'), FILTER_VALIDATE_INT);
        $quantity    = filter_var($this->request->getPost('quantity'), FILTER_VALIDATE_INT);
        $lotRaw      = trim($this->request->getPost('lot') ?? '');
        $lot         = $lotRaw === '' ? null : substr($lotRaw, 0, 25);
        $expDateRaw  = trim($this->request->getPost('expiration_date') ?? '');
        $expirationDate = null;
        $statusRaw   = trim($this->request->getPost('status') ?? '');
        $status      = in_array($statusRaw, ['MUESTRA', 'PRUEBA', 'VENTA', 'DISPONIBLE']) ? $statusRaw : 'DISPONIBLE';

        if (!$this->canOperateWarehouse((int)$idWarehouse)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No tiene permisos para registrar artículos en esta bodega. Solo el responsable asignado puede operar.'
            ])->setStatusCode(403);
        }
        if (!empty($expDateRaw)) {
            $parsedDate = date_create($expDateRaw);
            if ($parsedDate) {
                $expirationDate = $parsedDate->format('Y-m-d H:i:s');
            }
        }

        if (!$idWarehouse || !$idReference || $quantity === false || $quantity < 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe seleccionar una referencia válida de la lista y especificar una cantidad permitida.'
            ]);
        }

        $refModel = new \App\Models\FamiliesReference();
        $refRecord = $refModel->getWithFamilyById($idReference);

        if (!$refRecord || $refRecord['status'] !== 'ACTIVE') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'La referencia seleccionada no existe o no se encuentra activa en el catálogo.'
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
            ->where('id_reference', $idReference)
            ->where('status', $status)
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
                    'message' => 'Se incrementaron las existencias de la referencia en la bodega correctamente.'
                ]);
            }
        } else {
            $data = [
                'id_warehouse'    => $idWarehouse,
                'id_reference'    => $idReference,
                'quantity'        => $quantity,
                'lot'             => $lot,
                'expiration_date' => $expirationDate,
                'status'          => $status,
                'created_at'      => $now,
                'updated_at'      => $now
            ];

            if ($balanceModel->insert($data)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Referencia agregada al balance correctamente.'
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

        if (!$this->canOperateWarehouse((int)$idWarehouse)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No tiene permisos para generar remisiones en esta bodega. Solo el responsable asignado puede operar.'
            ])->setStatusCode(403);
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

        // Obtener datos del administrador de la bodega desde la tabla users
        $adminUserId = ($warehouseDest && !empty($warehouseDest['id_user_admin'])) ? (int)$warehouseDest['id_user_admin'] : null;
        $userModel = new Users();
        $adminUser = $adminUserId ? $userModel->find($adminUserId) : null;

        // Fallback al usuario actual en sesión si no hay administrador asignado a la bodega
        if (!$adminUser && session('user_id')) {
            $adminUser = $userModel->find((int)session('user_id'));
        }

        $adminName = $adminUser ? $adminUser['name'] : $destName;
        $adminDni = ($adminUser && !empty($adminUser['dni'])) ? (string)$adminUser['dni'] : '1';
        $adminAdress = ($adminUser && !empty($adminUser['adress'])) ? $adminUser['adress'] : '';
        $adminCityId = ($adminUser && !empty($adminUser['city'])) ? (int)$adminUser['city'] : null;

        // Validar que la ciudad exista en la tabla cities para evitar violaciones de clave foránea
        $db = \Config\Database::connect();
        $cityRow = null;
        if ($adminCityId) {
            $cityRow = $db->table('cities')->where('id', $adminCityId)->where('state', 'ACTIVO')->get()->getRowArray();
        }
        if (!$cityRow) {
            $cityRow = $db->table('cities')->where('state', 'ACTIVO')->orderBy('id', 'ASC')->get()->getRowArray();
            $adminCityId = $cityRow ? (int)$cityRow['id'] : 1;
        }

        // El campo dispatcher es clave foránea hacia users(id), se asigna el ID del administrador de la bodega o usuario en sesión
        $dispatcherUserId = $adminUser ? (int)$adminUser['id'] : (int)session('user_id');

        // Asignar en la cabecera del documento de tipo INGRESO la información del administrador de la bodega
        $ciudad = $adminCityId;
        $dispatcher = $dispatcherUserId;
        $cliente = substr($adminName, 0, 75);
        $nit = substr($adminDni, 0, 25);
        $adress = substr($adminAdress, 0, 105);
        if (empty($observacion)) {
            $observacion = 'Esta remision es automatica por el sistema para registrar los ingresos a bodega principal.';
        } else {
            $observacion = substr($observacion, 0, 250);
        }

        $refIds = $this->request->getPost('id_reference') ?? $this->request->getPost('id_family');
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

        // Obtener último consecutivo para la ciudad de la remisión
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
            'did_user'      => (int)session('user_id'),
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
        $refModel = new \App\Models\FamiliesReference();

        $itemsCount = count($cantidades);
        $processedCount = 0;

        for ($i = 0; $i < $itemsCount; $i++) {
            $qty = filter_var($cantidades[$i] ?? 0, FILTER_VALIDATE_INT);
            $rId = filter_var($refIds[$i] ?? null, FILTER_VALIDATE_INT);
            $rawName = trim($itemNames[$i] ?? '');
            $refCode = trim($referencias[$i] ?? '');
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

            // Buscar referencia activa
            $refRecord = null;
            if ($rId) {
                $refRecord = $refModel->getWithFamilyById($rId);
            }

            if (!$refRecord && !empty($refCode)) {
                $refRecord = $refModel->getWithFamily($refCode);
            }

            if (!$refRecord && !empty($rawName)) {
                $refRecord = $refModel->where('reference', $rawName)->first();
                if ($refRecord) {
                    $refRecord = $refModel->getWithFamilyById((int)$refRecord['id']);
                }
            }

            if (!$refRecord || ($refRecord['status'] ?? 'ACTIVE') !== 'ACTIVE') {
                $db->transRollback();
                $itemLabel = !empty($refCode) ? $refCode : (!empty($rawName) ? $rawName : ('Línea #' . ($i + 1)));
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "La referencia '{$itemLabel}' no existe o no se encuentra activa en el catálogo."
                ]);
            }

            $actualRefId = (int)$refRecord['id'];
            $actualRefCode = $refRecord['reference'];
            $familyName = $refRecord['family_name'] ?? ($refRecord['family_keyword'] ?? 'Producto');
            $description = $familyName . ' [' . $actualRefCode . ']';

            // 1. Guardar item de la remisión
            $itemData = [
                'id_base'         => $dispatchId,
                'reference'       => $actualRefCode,
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
                ->where('id_reference', $actualRefId)
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
                    'id_reference'    => $actualRefId,
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
        $targetStatuses = $this->request->getPost('target_status') ?? $this->request->getPost('item_status');

        if (!$idWarehouseSend || !$idWarehouseReceives) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Debe especificar tanto la bodega de origen como la de destino.'
            ]);
        }

        if (!$this->canOperateWarehouse((int)$idWarehouseSend)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No tiene permisos para transferir stock desde esta bodega. Solo el responsable asignado puede operar.'
            ])->setStatusCode(403);
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

        $sendAdminId = !empty($sendWarehouse['id_user_admin']) ? (int)$sendWarehouse['id_user_admin'] : (int)session('user_id');
        $receivesAdminId = !empty($receivesWarehouse['id_user_admin']) ? (int)$receivesWarehouse['id_user_admin'] : null;
        $userModel = new Users();
        $receivesAdmin = $receivesAdminId ? $userModel->find($receivesAdminId) : null;
        $receivesAdress = $receivesAdmin ? ($receivesAdmin['adress'] ?? '') : '';

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
            'dispatcher'    => $sendAdminId,
            'did_user'      => (int)session('user_id'),
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
            $rawTargetStatus = trim($targetStatuses[$i] ?? '');

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
                $famRefModel = new \App\Models\FamiliesReference();
                $refMatch = $famRefModel->where('reference', $rawName)->where('status', 'ACTIVE')->first();
                if ($refMatch) {
                    $originBalance = $balanceModel->where('id_warehouse', $idWarehouseSend)
                        ->where('id_reference', $refMatch['id'])
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

            // Validar estado destino seleccionado por el usuario o tomar el estado de origen
            $allowedStatuses = ['MUESTRA', 'PRUEBA', 'VENTA', 'DISPONIBLE'];
            $targetStatus = in_array($rawTargetStatus, $allowedStatuses, true) 
                ? $rawTargetStatus 
                : (!empty($originBalance['status']) ? $originBalance['status'] : 'DISPONIBLE');

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

            // 4.0 Aumentar saldo o registrar nuevo artículo en bodega destino con el estado destino
            $destQuery = $balanceModel->where('id_warehouse', $idWarehouseReceives)
                ->where('id_reference', $originBalance['id_reference'])
                ->where('status', $targetStatus)
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
                    'id_reference'    => $originBalance['id_reference'],
                    'quantity'        => $qty,
                    'lot'             => $itemLot,
                    'expiration_date' => $originBalance['expiration_date'] ?? null,
                    'status'          => $targetStatus,
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

            // 5.0 Registrar línea del item transferido con id_reference
            $transferItemsModel->insert([
                'id_warehouse_transfer' => $transferId,
                'id_reference'          => $originBalance['id_reference'],
                'quantity'              => $qty
            ]);

            // 5.1 Registrar línea correspondiente en la remisión de traslado
            $famRefModel = new \App\Models\FamiliesReference();
            $refRecord = $famRefModel->getWithFamilyById((int)$originBalance['id_reference']);
            $refCode = $refRecord ? $refRecord['reference'] : ('REF-' . $originBalance['id_reference']);
            $familyName = $refRecord ? ($refRecord['family_name'] ?? $refRecord['family_keyword']) : (!empty($rawName) ? $rawName : ('Referencia #' . $originBalance['id_reference']));
            $originStatus = !empty($originBalance['status']) ? $originBalance['status'] : 'DISPONIBLE';
            $statusLabel = ($originStatus === $targetStatus) ? $targetStatus : "{$originStatus} → {$targetStatus}";
            $displayName = $familyName . ' [' . $refCode . ']' . (($itemLot !== null && $itemLot !== '') ? ' [Lote: ' . $itemLot . ']' : '') . ' [Estado: ' . $statusLabel . ']';

            $dispatchItemsModel->insert([
                'id_base'         => $dispatchId,
                'reference'       => $refCode,
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

        // La opción de ajustar es exclusiva para usuarios con la credencial 14 activa
        $credentials = session('credentials');
        if (!isset($credentials[14]) || $credentials[14] !== '1') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No autorizado. La opción de ajuste de inventario es exclusiva para usuarios con la credencial 14 autorizada.'
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

        if (!$this->canOperateWarehouse((int)$idWarehouse)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No tiene permisos para realizar ajustes en esta bodega.'
            ])->setStatusCode(403);
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
        $adminUserId = !empty($warehouse['id_user_admin']) ? (int)$warehouse['id_user_admin'] : (int)session('user_id');
        $userModel = new Users();
        $adminUser = $userModel->find($adminUserId);
        $destAdress = $adminUser ? ($adminUser['adress'] ?? '') : '';
        $dispatcher = $adminUserId;

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
            'did_user'      => (int)session('user_id'),
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

            // Obtener datos de la referencia y familia para descripción
            $refId = !empty($existing['id_reference']) ? (int)$existing['id_reference'] : null;
            $refRecord = null;
            if ($refId) {
                $famRefModel = new \App\Models\FamiliesReference();
                $refRecord = $famRefModel->getWithFamilyById($refId);
            }
            $ref = $refRecord ? $refRecord['reference'] : '';
            $familyName = $refRecord ? ($refRecord['family_name'] ?? $refRecord['family_keyword']) : ('Referencia #' . $balId);
            $description = $ref ? ($familyName . ' [' . $ref . ']') : $familyName;

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
     * Organizada exclusivamente por REFERENCIA de las familias activas
     */
    public function download_batch_template()
    {
        if (!$this->checkPermission()) {
            return $this->response->setStatusCode(403)->setBody('No autorizado.');
        }

        $headers = [
            'ID_REFERENCIA',
            'REFERENCIA',
            'FAMILIA',
            'CANTIDAD',
            'LOTE',
            'FECHA_VENCIMIENTO'
        ];

        $rows = $this->getCatalogItemsForTemplate();

        $xlsxContent = ExcelHelper::generateXlsx($headers, $rows);
        $filename = 'plantilla_cargue_masivo_referencias.xlsx';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0, no-cache, must-revalidate')
            ->setBody($xlsxContent);
    }

    /**
     * Obtener el listado de referencias asociadas a familias activas para la plantilla
     * Solo incluye referencias reales activas de familias (no familias solas)
     *
     * @return array Filas listas para Excel [reference_id, reference, family_name, '', '', '']
     */
    private function getCatalogItemsForTemplate(): array
    {
        $refModel = new \App\Models\FamiliesReference();

        // Asegurar que families y families_reference estén sincronizadas
        $siigoService = new \App\Libraries\SiigoService();
        try {
            $siigoService->ensureSynced();
        } catch (\Throwable $t) {
            log_message('error', 'Error en ensureSynced para plantilla: ' . $t->getMessage());
        }

        // Consultar referencias activas directamente desde families_reference con families
        $activeReferences = $refModel->getAllActiveWithFamilies();

        // Formatear filas para Excel: [ID_REFERENCIA, REFERENCIA, FAMILIA, CANTIDAD, LOTE, FECHA_VENCIMIENTO]
        $excelRows = [];
        if (!empty($activeReferences)) {
            foreach ($activeReferences as $item) {
                $excelRows[] = [
                    (int)$item['reference_id'],
                    $item['reference'],
                    $item['family_name'],
                    '', // Cantidad vacía
                    '', // Lote vacío
                    ''  // Fecha de vencimiento vacía
                ];
            }
        }

        return $excelRows;
    }

    /**
     * Obtener el catálogo de productos de Siigo usando SiigoService
     */
    private function getSiigoCatalogProducts(): array
    {
        $siigoService = new \App\Libraries\SiigoService();
        return $siigoService->fetchProducts();
    }

    /**
     * Obtener o renovar el token de autenticación de Siigo usando SiigoService
     */
    private function getSiigoToken(): ?string
    {
        $siigoService = new \App\Libraries\SiigoService();
        return $siigoService->getAuthToken();
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

        if (!$this->canOperateWarehouse($mainWarehouseId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No tiene permisos para realizar cargues masivos en la bodega principal. Solo el responsable asignado puede operar.'
            ])->setStatusCode(403);
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

        // Primera fila puede ser encabezado: comprobar si contiene palabras clave típicas de encabezados
        $headerCandidate = $parsedRows[0];
        $isHeader = false;
        foreach ($headerCandidate as $cellVal) {
            $cellStr = mb_strtolower(trim((string)$cellVal), 'UTF-8');
            if (
                preg_match('/(^|[\s_-])id([\s_-]|$)/i', $cellStr) ||
                strpos($cellStr, 'id_') === 0 ||
                stripos($cellStr, 'referencia') !== false ||
                stripos($cellStr, 'familia') !== false ||
                stripos($cellStr, 'producto') !== false ||
                stripos($cellStr, 'nombre') !== false ||
                stripos($cellStr, 'cant') !== false ||
                stripos($cellStr, 'lote') !== false ||
                stripos($cellStr, 'venc') !== false
            ) {
                $isHeader = true;
                break;
            }
        }

        $dataRows = $isHeader ? array_slice($parsedRows, 1) : $parsedRows;

        // Detección dinámica de índices de columnas según encabezados
        // Formato plantilla oficial: [0 => ID_REFERENCIA, 1 => REFERENCIA, 2 => FAMILIA, 3 => CANTIDAD, 4 => LOTE, 5 => FECHA_VENCIMIENTO]
        // Formato anterior compatible: [0 => ID, 1 => NOMBRE/FAMILIA, 2 => CANTIDAD, 3 => LOTE, 4 => REFERENCIA, 5 => FECHA_VENCIMIENTO]
        $colMap = [
            'id'       => 0,
            'ref'      => 1,
            'name'     => 2,
            'qty'      => 3,
            'lot'      => 4,
            'exp_date' => 5
        ];

        if ($isHeader) {
            foreach ($headerCandidate as $idx => $headerVal) {
                $h = mb_strtolower(trim((string)$headerVal), 'UTF-8');
                if ($h === '') {
                    continue;
                }
                // 1. CANTIDAD: Evaluar primero para evitar falsos positivos con 'id' (cantIDad)
                if (stripos($h, 'cant') !== false || stripos($h, 'qty') !== false || stripos($h, 'unidades') !== false) {
                    $colMap['qty'] = $idx;
                }
                // 2. ID: Identificador único (id, id_referencia, id referencia, etc.)
                elseif (preg_match('/(^|[\s_-])id([\s_-]|$)/i', $h) || strpos($h, 'id_') === 0 || $h === 'id') {
                    $colMap['id'] = $idx;
                }
                // 3. REFERENCIA o CÓDIGO
                elseif (stripos($h, 'ref') !== false || stripos($h, 'codigo') !== false || stripos($h, 'código') !== false) {
                    $colMap['ref'] = $idx;
                }
                // 4. NOMBRE o FAMILIA
                elseif (stripos($h, 'familia') !== false || stripos($h, 'nombre') !== false || stripos($h, 'producto') !== false || stripos($h, 'desc') !== false) {
                    $colMap['name'] = $idx;
                }
                // 5. LOTE
                elseif (stripos($h, 'lote') !== false || stripos($h, 'batch') !== false) {
                    $colMap['lot'] = $idx;
                }
                // 6. FECHA DE VENCIMIENTO
                elseif (stripos($h, 'venc') !== false || stripos($h, 'fecha') !== false || stripos($h, 'exp') !== false) {
                    $colMap['exp_date'] = $idx;
                }
            }
        } else {
            // Si no hay encabezados, detectar si es formato anterior [id, name, qty, lot, ref, exp_date]
            // donde col 2 era numérica (cantidad) y col 3 no numérica (lote o texto)
            if (!empty($dataRows)) {
                $firstRow = $dataRows[0];
                $col2 = trim((string)($firstRow[2] ?? ''));
                $col3 = trim((string)($firstRow[3] ?? ''));
                if (is_numeric($col2) && !is_numeric($col3)) {
                    $colMap = [
                        'id'       => 0,
                        'name'     => 1,
                        'qty'      => 2,
                        'lot'      => 3,
                        'ref'      => 4,
                        'exp_date' => 5
                    ];
                }
            }
        }

        // Cargar catálogo de referencias activas con familias para indexación rápida
        $refModel = new \App\Models\FamiliesReference();
        $siigoService = new \App\Libraries\SiigoService();
        try {
            $siigoService->ensureSynced();
        } catch (\Throwable $t) {
            log_message('error', 'Error en ensureSynced para importación: ' . $t->getMessage());
        }

        $activeReferences = $refModel->getAllActiveWithFamilies();
        $refsById = [];
        $refsByCode = [];
        foreach ($activeReferences as $r) {
            $refsById[(int)$r['reference_id']] = $r;
            $refsByCode[mb_strtolower(trim($r['reference']), 'UTF-8')] = $r;
        }

        // Procesar y validar filas
        $validItems = [];
        $skippedCount = 0;
        $invalidRows = [];
        $totalQuantity = 0;

        foreach ($dataRows as $index => $row) {
            $rowNumber = $isHeader ? ($index + 2) : ($index + 1);

            $idRaw = trim((string)($row[$colMap['id']] ?? ''));
            $nameRaw = trim((string)($row[$colMap['name']] ?? ''));
            $refRaw = trim((string)($row[$colMap['ref']] ?? ''));
            $qtyRaw = trim((string)($row[$colMap['qty']] ?? ''));
            $lotRaw = trim((string)($row[$colMap['lot']] ?? ''));
            $expDateRaw = trim((string)($row[$colMap['exp_date']] ?? ''));

            // Si la cantidad está vacía, ignorar silenciosamente la fila
            if ($qtyRaw === '') {
                $skippedCount++;
                continue;
            }

            // Normalizar formato de número (espacios, coma decimal o miles)
            $qtyNorm = str_replace(' ', '', $qtyRaw);
            if (strpos($qtyNorm, ',') !== false && strpos($qtyNorm, '.') === false) {
                $qtyNorm = str_replace(',', '.', $qtyNorm);
            } else {
                $qtyNorm = str_replace(',', '', $qtyNorm);
            }

            // Validar que la cantidad sea numérica y positiva
            if (!is_numeric($qtyNorm) || (float)$qtyNorm <= 0) {
                // Si la fila no tiene ningún dato más, omitir
                if ($idRaw === '' && $nameRaw === '' && $refRaw === '' && $lotRaw === '') {
                    $skippedCount++;
                    continue;
                }
                // Si tiene datos pero cantidad negativa, reportar
                if (is_numeric($qtyNorm) && (float)$qtyNorm < 0) {
                    $invalidRows[] = "Fila {$rowNumber}: la cantidad no puede ser negativa ({$qtyRaw}).";
                    continue;
                }
                $skippedCount++;
                continue;
            }

            $qty = (int)round((float)$qtyNorm);

            // Identificar referencia por ID o por Código de Referencia
            $refRecord = null;
            $refId = filter_var($idRaw, FILTER_VALIDATE_INT);
            if ($refId && isset($refsById[$refId])) {
                $refRecord = $refsById[$refId];
            } elseif (!empty($refRaw)) {
                $key = mb_strtolower($refRaw, 'UTF-8');
                if (isset($refsByCode[$key])) {
                    $refRecord = $refsByCode[$key];
                }
            }

            if (!$refRecord) {
                $itemLabel = !empty($refRaw) ? $refRaw : (!empty($nameRaw) ? $nameRaw : (!empty($idRaw) ? "ID #{$idRaw}" : "Fila {$rowNumber}"));
                $invalidRows[] = "Fila {$rowNumber}: la referencia '{$itemLabel}' no existe o no se encuentra activa en el catálogo.";
                continue;
            }

            // Normalizar lote (máx 25 para warehouse balance, máx 10 para dispatch item batch)
            $lot = ($lotRaw === '') ? null : substr($lotRaw, 0, 25);
            $batchForDispatch = ($lot !== null) ? substr($lot, 0, 10) : '';

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
                'id_reference'      => (int)$refRecord['reference_id'],
                'reference'         => $refRecord['reference'],
                'id_family'         => (int)$refRecord['id_family'],
                'name'              => $refRecord['family_name'],
                'quantity'          => $qty,
                'lot'               => $lot,
                'batch_dispatch'    => $batchForDispatch,
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

        // Obtener datos de la bodega principal y de su administrador desde la tabla users
        $warehouseModel = new WarehousesBase();
        $warehouseDest = $warehouseModel->find($mainWarehouseId);
        $destName = $warehouseDest ? $warehouseDest['name'] : 'Bodega Principal';

        $adminUserId = ($warehouseDest && !empty($warehouseDest['id_user_admin'])) ? (int)$warehouseDest['id_user_admin'] : null;
        $userModel = new Users();
        $adminUser = $adminUserId ? $userModel->find($adminUserId) : null;

        if (!$adminUser && session('user_id')) {
            $adminUser = $userModel->find((int)session('user_id'));
        }

        $adminName = $adminUser ? $adminUser['name'] : $destName;
        $adminDni = ($adminUser && !empty($adminUser['dni'])) ? (string)$adminUser['dni'] : '1';
        $adminAdress = ($adminUser && !empty($adminUser['adress'])) ? $adminUser['adress'] : '';
        $adminCityId = ($adminUser && !empty($adminUser['city'])) ? (int)$adminUser['city'] : null;

        $db = \Config\Database::connect();
        $cityRow = null;
        if ($adminCityId) {
            $cityRow = $db->table('cities')->where('id', $adminCityId)->where('state', 'ACTIVO')->get()->getRowArray();
        }
        if (!$cityRow) {
            $cityRow = $db->table('cities')->where('state', 'ACTIVO')->orderBy('id', 'ASC')->get()->getRowArray();
            $adminCityId = $cityRow ? (int)$cityRow['id'] : 1;
        }

        $dispatcherUserId = $adminUser ? (int)$adminUser['id'] : (int)session('user_id');

        // Datos oficiales para remisión de tipo INGRESO con la información del administrador
        $ciudad = $adminCityId;
        $dispatcher = $dispatcherUserId;
        $cliente = substr($adminName, 0, 75);
        $nit = substr($adminDni, 0, 25);
        $adress = substr($adminAdress, 0, 105);
        $observacion = 'Cargue masivo automático a bodega principal desde archivo Excel (' . substr($originalName, 0, 50) . ').';

        // Obtener último consecutivo para la ciudad
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
            'did_user'      => (int)session('user_id'),
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
                'description'     => $item['name'] . ' [' . $item['reference'] . ']',
                'batch'           => $item['batch_dispatch'],
                'expiration_date' => $item['expiration_date'],
                'quiantity'       => $item['quantity'],
                'created_at'      => $now,
                'updated_at'      => $now
            ];
            $itemsModel->insert($itemData);

            // 2. Actualizar existencias en warehouses_balance
            $balQuery = $balanceModel->where('id_warehouse', $mainWarehouseId)
                ->where('id_reference', $item['id_reference'])
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
                    'id_reference'    => $item['id_reference'],
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
