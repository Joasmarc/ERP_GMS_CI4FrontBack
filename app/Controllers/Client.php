<?php

namespace App\Controllers;

use App\Models\Clients;

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
}
