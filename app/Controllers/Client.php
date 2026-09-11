<?php

namespace App\Controllers;

use App\Models\Clients;

class Client extends BaseController
{
    /**
     * Guarda un nuevo cliente en la base de datos usando el modelo Clients
     */
    public function save()
    {
        // 1.0 Verificar sesión activa
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado. Debe iniciar sesión.']);
        }

        // 2.0 Validar método POST
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Método no permitido.']);
        }

        $nombreCliente    = trim((string)$this->request->getPost('nombre_cliente'));
        $tipoDocumento    = trim((string)$this->request->getPost('tipo_documento'));
        $numeroDocumento  = trim((string)$this->request->getPost('numero_documento'));
        $telefonoCliente  = trim((string)$this->request->getPost('telefono_cliente'));
        $correoCliente    = trim((string)$this->request->getPost('correo_cliente'));
        $direccionCliente = trim((string)$this->request->getPost('direccion_cliente'));
        $cityRaw          = $this->request->getPost('city');
        $cityId           = filter_var($cityRaw, FILTER_VALIDATE_INT) ?: null;

        // 3.0 Validar campos requeridos
        if (empty($nombreCliente)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El nombre o razón social es obligatorio.']);
        }

        if (empty($numeroDocumento)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El número de documento es obligatorio.']);
        }

        // 4.0 Validar límites de longitud según columnas de base de datos
        if (mb_strlen($nombreCliente) > 255) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El nombre no puede exceder 255 caracteres.']);
        }

        if (mb_strlen($numeroDocumento) > 100) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El número de documento no puede exceder 100 caracteres.']);
        }

        if (!empty($tipoDocumento) && mb_strlen($tipoDocumento) > 50) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El tipo de documento no es válido.']);
        }

        if (!empty($telefonoCliente) && mb_strlen($telefonoCliente) > 50) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El teléfono no puede exceder 50 caracteres.']);
        }

        if (!empty($correoCliente)) {
            if (!filter_var($correoCliente, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'El correo electrónico no tiene un formato válido.']);
            }
            if (mb_strlen($correoCliente) > 255) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'El correo no puede exceder 255 caracteres.']);
            }
        }

        if (!empty($direccionCliente) && mb_strlen($direccionCliente) > 255) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'La dirección no puede exceder 255 caracteres.']);
        }

        if ($cityId !== null) {
            $citiesModel = new \App\Models\Cities();
            $cityRecord = $citiesModel->find($cityId);
            if (!$cityRecord) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'La ciudad seleccionada no es válida.']);
            }
        }

        $data = [
            'nombre_cliente'    => $nombreCliente,
            'tipo_documento'    => !empty($tipoDocumento) ? $tipoDocumento : null,
            'numero_documento'  => $numeroDocumento,
            'telefono_cliente'  => !empty($telefonoCliente) ? $telefonoCliente : null,
            'correo_cliente'    => !empty($correoCliente) ? $correoCliente : null,
            'direccion_cliente' => !empty($direccionCliente) ? $direccionCliente : null,
            'city'              => $cityId,
        ];

        try {
            $model = new Clients();
            $model->insert($data);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Cliente guardado correctamente.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar cliente: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error interno al guardar los datos del cliente.'
            ]);
        }
    }

    /**
     * Lista los clientes registrados para la tabla interactiva DataTables
     */
    public function listing()
    {
        // 1.0 Verificar sesión activa
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado.']);
        }

        try {
            $model = new Clients();
            $data = $model->select('clients.id, clients.nombre_cliente, clients.tipo_documento, clients.numero_documento, clients.telefono_cliente, clients.correo_cliente, clients.direccion_cliente, clients.city, cities.name as city_name, clients.created_at')
                          ->join('cities', 'cities.id = clients.city', 'left')
                          ->orderBy('clients.id', 'DESC')
                          ->findAll();

            return $this->response->setJSON([
                'draw'            => 1,
                'recordsTotal'    => count($data),
                'recordsFiltered' => count($data),
                'data'            => $data
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al listar clientes: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw'            => 1,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    }
}
