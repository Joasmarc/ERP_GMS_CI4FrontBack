<?php

namespace App\Controllers;

use App\Models\Counterparties;
use App\Models\SharedForms;
use App\Models\Departments;
use App\Models\Cities;
use DateTime;
use DateTimeZone;

class PublicForm extends BaseController
{
    // Cargar la vista pública del formulario compartido
    public function index($token)
    {
        $sharedModel = new SharedForms();
        $invitation = $sharedModel->where('token', $token)->first();

        // 1.0 Validar existencia de la invitación
        if (!$invitation) {
            return $this->showErrorPage('El enlace de registro no existe o es inválido.');
        }

        // 2.0 Validar estado de la invitación
        if ($invitation['status'] === 'completed') {
            return $this->showErrorPage('Este formulario ya fue diligenciado y enviado anteriormente.');
        }

        // 3.0 Validar fecha de expiración
        if (!empty($invitation['expires_at'])) {
            $timezone = new DateTimeZone('America/Bogota');
            $now = new DateTime('now', $timezone);
            $expires = new DateTime($invitation['expires_at'], $timezone);
            if ($now > $expires) {
                return $this->showErrorPage('El enlace de registro ha expirado.');
            }
        }

        // 4.0 Renderizar vista
        $data = [
            'token'                 => $token,
            'numero_identificacion' => $invitation['numero_identificacion'],
            'contraparte_type'      => $invitation['contraparte_type'],
            'person_type'           => $invitation['person_type']
        ];

        return view('public_register', $data);
    }

    // Procesar envío del formulario de registro
    public function submit($token)
    {
        $sharedModel = new SharedForms();
        $invitation = $sharedModel->where('token', $token)->first();

        // 1.0 Validar token
        if (!$invitation || $invitation['status'] !== 'pending') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El formulario no es válido o ya fue enviado.']);
        }

        // Verificar expiración
        if (!empty($invitation['expires_at'])) {
            $timezone = new DateTimeZone('America/Bogota');
            $now = new DateTime('now', $timezone);
            $expires = new DateTime($invitation['expires_at'], $timezone);
            if ($now > $expires) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'El enlace ha expirado.']);
            }
        }

        // 2.0 Recibir datos POST
        $data = $this->request->getPost();

        // Forzar campos fijos que provienen del token
        $data['numero_identificacion'] = $invitation['numero_identificacion'];
        $data['contraparte_type']      = $invitation['contraparte_type'];
        $data['person_type']           = $invitation['person_type'];

        // 3.0 Validaciones backend de campos requeridos
        $errors = [];
        if ($data['person_type'] === 'juridica') {
            // Requeridos Jurídicos
            if (empty($data['nombre_completo'])) $errors[] = 'Nombre del proveedor / Razón social';
            if (empty($data['tipo_identificacion'])) $errors[] = 'Tipo de identificación';
            if (empty($data['email_principal'])) $errors[] = 'Email principal';
            if (empty($data['telefono_principal'])) $errors[] = 'Teléfono principal';
        } else {
            // Requeridos Naturales
            if (empty($data['tipo_identificacion'])) $errors[] = 'Tipo de documento de identidad';
            if (empty($data['nombre_completo'])) $errors[] = 'Nombre completo';
            if (empty($data['primer_apellido'])) $errors[] = 'Primer apellido';
            if (empty($data['fecha_nacimiento'])) $errors[] = 'Fecha de nacimiento';
            if (empty($data['pais_residencia'])) $errors[] = 'País de residencia';
            if (empty($data['direccion_completa'])) $errors[] = 'Dirección completa';
            if (empty($data['actividad_principal'])) $errors[] = 'Actividad principal / Ocupación';
            if (empty($data['profesion'])) $errors[] = 'Profesión';
            if (empty($data['area_especializacion'])) $errors[] = 'Área de especialización';
            if (empty($data['sector_economico'])) $errors[] = 'Sector económico (CIIU)';
            if (empty($data['anos_experiencia'])) $errors[] = 'Años de experiencia';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'status'  => 'validation_error',
                'message' => 'Faltan campos obligatorios por completar.',
                'fields'  => $errors
            ]);
        }

        // 3.1 Validaciones de formato
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

        // Manejo especial de antecedentes laborales en formato JSON (si es persona natural)
        if (isset($data['antecedentes_laborales']) && is_array($data['antecedentes_laborales'])) {
            // Limpiar filas vacías si las hay
            $cleanAntecedentes = [];
            foreach ($data['antecedentes_laborales'] as $ant) {
                if (!empty($ant['empresa']) || !empty($ant['cargo'])) {
                    $cleanAntecedentes[] = $ant;
                }
            }
            $data['antecedentes_laborales'] = json_encode($cleanAntecedentes);
        }

        // 4.0 Guardar registro
        $counterpartiesModel = new Counterparties();
        try {
            if ($counterpartiesModel->save($data)) {
                // 5.0 Marcar enlace compartido como completado
                $sharedModel->update($invitation['id'], ['status' => 'completed']);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Registro completado con éxito. ¡Muchas gracias!']);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Error al guardar el registro en el sistema: ' . json_encode($counterpartiesModel->errors())
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Excepción en servidor: ' . $e->getMessage()]);
        }
    }

    // JSON de Departamentos de Colombia
    public function getDepartments()
    {
        $deptModel = new Departments();
        $departments = $deptModel->orderBy('name', 'ASC')->findAll();
        return $this->response->setJSON($departments);
    }

    // JSON de Ciudades filtrado por departamento
    public function getCitiesByDepartment($deptId)
    {
        $cityModel = new Cities();
        $cities = $cityModel->where('department_id', $deptId)
            ->where('state', 'ACTIVO')
            ->orderBy('name', 'ASC')
            ->findAll();
        return $this->response->setJSON($cities);
    }

    // Mostrar página de error simple y bonita
    private function showErrorPage($message)
    {
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Enlace Inválido</title>
            <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap' rel='stylesheet'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
            <style>
                body {
                    font-family: 'Outfit', sans-serif;
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                    height: 100vh;
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .card {
                    background: white;
                    padding: 40px;
                    border-radius: 20px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    max-width: 450px;
                    width: 90%;
                }
                .icon {
                    font-size: 60px;
                    color: #e53935;
                    margin-bottom: 20px;
                }
                h1 {
                    font-size: 24px;
                    color: #333;
                    margin-bottom: 15px;
                    font-weight: 700;
                }
                p {
                    color: #666;
                    font-size: 16px;
                    line-height: 1.5;
                    margin-bottom: 30px;
                }
                .btn {
                    background: #1572e8;
                    color: white;
                    border: none;
                    padding: 12px 25px;
                    font-size: 15px;
                    font-weight: 600;
                    border-radius: 30px;
                    cursor: pointer;
                    text-decoration: none;
                    transition: background 0.3s;
                    display: inline-block;
                }
                .btn:hover {
                    background: #0d47a1;
                }
            </style>
        </head>
        <body>
            <div class='card'>
                <div class='icon'><i class='fas fa-exclamation-circle'></i></div>
                <h1>Acceso Denegado</h1>
                <p>" . esc($message) . "</p>
                <a href='#' class='btn' onclick='window.close(); return false;'>Cerrar Pestaña</a>
            </div>
        </body>
        </html>
        ";
    }
}
