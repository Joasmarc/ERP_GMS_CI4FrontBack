<?php

namespace App\Controllers;

use App\Models\Clients;
use App\Models\ClientComments;

class Client extends BaseController
{
    public function save()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Método no permitido.']);
        }

        $model = new Clients();
        
        $data = [
            'nombre_cliente'    => $this->request->getPost('nombre_cliente'),
            'tipo_documento'    => $this->request->getPost('tipo_documento'),
            'numero_documento'  => $this->request->getPost('numero_documento'),
            'telefono_cliente'  => $this->request->getPost('telefono_cliente'),
            'correo_cliente'    => $this->request->getPost('correo_cliente'),
            'direccion_cliente' => $this->request->getPost('direccion_cliente'),
        ];

        if (empty($data['nombre_cliente']) || empty($data['numero_documento'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El nombre y número de documento son obligatorios.']);
        }
        
        try {
            $model->insert($data);
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Cliente guardado correctamente en la tabla'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar cliente: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Error interno de base de datos'
            ]);
        }
    }

    public function listing()
    {
        $model = new Clients();
        
        $data = $model->select('id, nombre_cliente, tipo_documento, numero_documento, telefono_cliente, correo_cliente, direccion_cliente')
                      ->findAll();

        $response = [
            "draw" => 1,
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        ];
        
        return $this->response->setJSON($response);
    }

    public function add_comment()
    {
        if (!$this->request->is('post')) return $this->response->setJSON(['status' => 'error', 'message' => 'Método no permitido.']);
        
        $model = new ClientComments();
        $data = [
            'client_id' => $this->request->getPost('client_id'),
            'comment'   => $this->request->getPost('comment')
        ];
        
        if (empty($data['client_id']) || empty($data['comment'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
        }
        
        $model->insert($data);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function list_comments($client_id)
    {
        $model = new ClientComments();
        $data = $model->where('client_id', $client_id)->orderBy('created_at', 'DESC')->findAll();
        
        // Formatear fecha para mejorar legibilidad
        foreach ($data as &$row) {
            if (!empty($row['created_at'])) {
                $row['created_at'] = date('d/m/Y H:i', strtotime($row['created_at']));
            }
        }
        
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }
}
