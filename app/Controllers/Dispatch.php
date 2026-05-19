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
        $dispatchModel = new DispatchAdvices();
        $db = \Config\Database::connect();

        // Hacemos JOIN con cities para obtener el nombre de la ciudad
        $builder = $db->table('dispatch_advice da');
        $builder->select('da.id, da.client, da.nit, da.adress, da.sequence, da.transfer_code, da.created_at, c.name as city_name, c.code as city_code');
        $builder->join('cities c', 'c.id = da.city', 'left');

        $records = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'data' => $records
        ]);
    }

    // Tabla_G05
    public function view_pdf($id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('dispatch_advice da');
        $builder->select('da.*, c.name as city_name, c.code as city_code');
        $builder->join('cities c', 'c.id = da.city', 'left');
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
