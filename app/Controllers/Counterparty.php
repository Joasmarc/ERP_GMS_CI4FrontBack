<?php

namespace App\Controllers;

use App\Models\Counterparties;
use App\Models\SharedForms;
use DateTime;
use DateTimeZone;

class Counterparty extends BaseController
{
    // Listar contrapartes (Proveedores/Clientes) en base al tipo
    public function listing()
    {
        // 1.0 Verificar sesión
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        $type = $this->request->getGet('type');
        
        $model = new Counterparties();
        if ($type) {
            $data = $model->where('contraparte_type', $type)->findAll();
        } else {
            $data = $model->findAll();
        }

        return $this->response->setJSON([
            'draw'            => 1,
            'recordsTotal'    => count($data),
            'recordsFiltered' => count($data),
            'data'            => $data
        ]);
    }

    // Guardar una contraparte directamente desde el panel administrativo
    public function save()
    {
        // 1.0 Verificar sesión
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        $model = new Counterparties();

        // 2.0 Recibir todos los datos POST
        $data = $this->request->getPost();

        // Validaciones básicas de campos críticos
        if (empty($data['contraparte_type']) || empty($data['person_type'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tipo de contraparte y de persona son obligatorios.']);
        }

        if (empty($data['numero_identificacion'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El número de identificación es obligatorio.']);
        }

        // Validaciones de formato
        if (!empty($data['email_principal']) && !filter_var($data['email_principal'], FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El correo electrónico principal no tiene un formato válido.']);
        }
        if (!empty($data['telefono_principal']) && !preg_match('/^[0-9]+$/', $data['telefono_principal'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El teléfono principal debe contener únicamente dígitos numéricos.']);
        }
        if (!empty($data['telefono_secundario']) && !preg_match('/^[0-9]+$/', $data['telefono_secundario'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El teléfono secundario debe contener únicamente dígitos numéricos.']);
        }
        if (!empty($data['pagina_web']) && !filter_var($data['pagina_web'], FILTER_VALIDATE_URL)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'La dirección de la página web no tiene un formato URL válido.']);
        }
        if (isset($data['anos_experiencia']) && $data['anos_experiencia'] !== '') {
            if (!is_numeric($data['anos_experiencia']) || intval($data['anos_experiencia']) < 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Los años de experiencia deben ser un número entero mayor o igual a 0.']);
            }
        }

        // Manejo especial de antecedentes laborales en formato JSON (si es persona natural y viene como arreglo)
        if (isset($data['antecedentes_laborales']) && is_array($data['antecedentes_laborales'])) {
            $data['antecedentes_laborales'] = json_encode($data['antecedentes_laborales']);
        }

        // 3.0 Guardar en BD
        try {
            if ($model->save($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Registro guardado correctamente.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar el registro: ' . json_encode($model->errors())]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Excepción: ' . $e->getMessage()]);
        }
    }

    // Generar enlace compartido de registro
    public function share()
    {
        // 1.0 Verificar sesión
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        // 2.0 Obtener y validar datos
        $nitCédula = $this->request->getPost('numero_identificacion');
        $contraparteType = $this->request->getPost('contraparte_type'); // 'proveedor' o 'cliente'
        $personType = $this->request->getPost('person_type');           // 'juridica' o 'natural'

        if (empty($nitCédula)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Debe ingresar el NIT o Cédula para compartir el formulario.']);
        }

        if (empty($contraparteType) || empty($personType)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tipo de contraparte y tipo de persona son requeridos.']);
        }

        // 3.0 Generar Token Seguro
        try {
            $token = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            $token = md5(uniqid(rand(), true));
        }

        // 4.0 Definir vencimiento (Ej: 7 días a partir de ahora en zona de Colombia)
        $timezone = new DateTimeZone('America/Bogota');
        $date = new DateTime('now', $timezone);
        $date->modify('+7 days');
        $expiresAt = $date->format('Y-m-d H:i:s');

        // 5.0 Guardar en shared_forms
        $sharedModel = new SharedForms();
        $saveData = [
            'token'                 => $token,
            'numero_identificacion' => $nitCédula,
            'contraparte_type'      => $contraparteType,
            'person_type'           => $personType,
            'status'                => 'pending',
            'expires_at'            => $expiresAt
        ];

        if ($sharedModel->insert($saveData)) {
            $link = base_url('register/form/' . $token);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Enlace generado correctamente.',
                'link'   => $link
            ]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar el enlace en base de datos.']);
        }
    }

    // Subir y actualizar el archivo PDF de la política de tratamiento de datos
    public function upload_policy()
    {
        // 1.0 Verificar sesión
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        // 2.0 Validar archivo
        $file = $this->request->getFile('policy_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Archivo no válido o ausente.']);
        }

        if ($file->getMimeType() !== 'application/pdf') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El archivo debe ser un formato PDF válido.']);
        }

        // 3.0 Mover archivo a public/assets/pdf/ y renombrarlo como politica_privacidad.pdf
        $targetDir = ROOTPATH . 'public/assets/pdf/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Borrar el archivo viejo si existe para evitar problemas de permisos
        $targetFile = $targetDir . 'politica_privacidad.pdf';
        if (file_exists($targetFile)) {
            unlink($targetFile);
        }

        if ($file->move($targetDir, 'politica_privacidad.pdf')) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Política de privacidad PDF actualizada correctamente.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo guardar el archivo.']);
        }
    }
}
