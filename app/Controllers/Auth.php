<?php

namespace App\Controllers;

use App\Models\Users;

class Auth extends BaseController
{
    // Propiedades del controlador
    protected $userModel;

    // Constructor
    public function __construct()
    {
        $this->userModel = new Users();
        helper('utils');
    }

    // Mostrar formulario de login
    public function login()
    {
        // 1.0 Inicializar interfaz y verificar sesión - Iniciar variable de interfaz
        $LOGIN = [];
        // 1.1 Verificar si usuario ya está autenticado
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        // 2.0 Preparar datos para la vista
        $viewData = [
            'title' => 'Login - Sistema de Administración'
        ];

        // 3.0 Renderizar vista de login
        return view('auth/login', $viewData);
    }

    // Procesar datos de login
    public function authenticate()
    {
        // 1.0 Inicializar interfaz y validar petición - Iniciar variable de interfaz
        $AUTH = [];
        // 1.1 Validar método POST
        if (!$this->request->is('post')) {
            return redirect()->to('login')->with('error', 'Método no permitido');
        }

        // 2.0 Obtener y validar datos del formulario - Obtener credenciales
        $AUTH['EMAIL'] = trim($this->request->getPost('email'));
        $AUTH['PIN'] = trim($this->request->getPost('pin'));
        // 2.1 Configurar reglas de validación
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'pin' => 'required|exact_length[4]'
        ]);
        // 2.2 Ejecutar validación
        if (!$validation->run(['email' => $AUTH['EMAIL'], 'pin' => $AUTH['PIN']])) {
            $errores = $validation->getErrors();
            log_message('error', 'Validación fallida: ' . json_encode($errores));
            return redirect()->back()->withInput()->with('errors', $errores);
        }

        // 3.0 Autenticar usuario - Buscar usuario por email
        $AUTH['USER'] = $this->userModel->where('email', $AUTH['EMAIL'])->first();
        // 3.1 Verificar si usuario existe
        if (!$AUTH['USER']) {
            return redirect()->back()->with('error', 'Correo no encontrado');
        }
        // 3.2 Verificar PIN usando BCRYPT con fallback a MD5 y texto plano para migración
        $is_valid = false;
        $needs_rehash = false;
        if (password_verify($AUTH['PIN'], $AUTH['USER']['pin'])) {
            $is_valid = true;
        } elseif ($AUTH['USER']['pin'] === md5($AUTH['PIN']) || $AUTH['USER']['pin'] === $AUTH['PIN']) {
            $is_valid = true;
            $needs_rehash = true;
        }
        // 3.3 Validar resultado de verificación
        if (!$is_valid) {
            return redirect()->back()->with('error', 'PIN incorrecto');
        }
        // 3.4 Re-hashear y actualizar el PIN en base de datos al nuevo formato seguro BCRYPT
        if ($needs_rehash) {
            $this->userModel->update($AUTH['USER']['id'], ['pin' => $AUTH['PIN']]);
        }

        // 4.0 Configurar sesión de usuario - Crear estructura base de sesión
        $sesion = [
            'user_id' => $AUTH['USER']['id'],
            'email' => $AUTH['USER']['email'],
            'name' => $AUTH['USER']['name'],
            'gender' => $AUTH['USER']['gender'],
            'credentials_raw' => $AUTH['USER']['credentials'],
            'credentials' => null,
            'isLoggedIn' => true
        ];
        // 4.1 Integrar Token de Siigo si está disponible y sincronizar catálogo
        $siigoService = new \App\Libraries\SiigoService();
        $token = $siigoService->getAuthToken();
        if ($token) {
            $sesion['siigo_token'] = $token;
            $sesion['siigo_token_expires'] = time() + 86400;

            // 4.1.1 Sincronizar familias y referencias de Siigo al iniciar sesión en el sistema
            try {
                $siigoService->syncFamiliesAndReferences();
            } catch (\Throwable $e) {
                log_message('error', 'Error sincronizando Siigo al iniciar sesión: ' . $e->getMessage());
            }
        }
        // 4.2 Formatear credenciales usando helper
        $sesion['credentials'] = pad_right_zeros((string) $sesion['credentials_raw']);
        // 4.3 Guardar sesión
        session()->set($sesion);

        // 5.0 Redirigir al dashboard
        return redirect()->to('dashboard')->with('success', 'Bienvenido: ' . $AUTH['USER']['name']);
    }

    // Obtener token de la API de Siigo
    private function getSiigoToken()
    {
        $siigoService = new \App\Libraries\SiigoService();
        $token = $siigoService->getAuthToken();
        if ($token) {
            return [
                'access_token' => $token,
                'expires_in'   => 86400
            ];
        }
        return null;
    }

    // Cerrar sesión
    public function logout()
    {
        // 1.0 Inicializar interfaz y destruir sesión - Iniciar variable de interfaz
        $LOGOUT = [];
        // 1.1 Destruir la sesión
        session()->destroy();

        // 2.0 Redirigir al login
        return redirect()->to('/login')->with('success', 'Sesión cerrada correctamente');
    }

    // Cambiar PIN de la cuenta iniciada
    public function change_pin()
    {
        // 1.0 Inicializar interfaz y verificar sesión - Iniciar variable de interfaz
        $CHANGE = [];
        // 1.1 Verificar si usuario está autenticado
        if (!session()->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }
        // 1.2 Obtener ID de usuario de la sesión
        $CHANGE['USER_ID'] = session()->get('user_id');

        // 2.0 Obtener y validar datos de entrada - Obtener parámetros
        $CHANGE['PIN_ACTUAL'] = trim((string)$this->request->getPost('pin_actual'));
        $CHANGE['PIN_NUEVO'] = trim((string)$this->request->getPost('pin_nuevo'));
        $CHANGE['PIN_CONFIRMAR'] = trim((string)$this->request->getPost('pin_confirmar'));
        // 2.1 Configurar reglas de validación
        $validation = \Config\Services::validation();
        $validation->setRules([
            'pin_actual' => 'required|numeric|exact_length[4]',
            'pin_nuevo' => 'required|numeric|exact_length[4]',
            'pin_confirmar' => 'required|matches[pin_nuevo]'
        ]);
        // 2.2 Ejecutar validación
        if (!$validation->run([
            'pin_actual' => $CHANGE['PIN_ACTUAL'],
            'pin_nuevo' => $CHANGE['PIN_NUEVO'],
            'pin_confirmar' => $CHANGE['PIN_CONFIRMAR']
        ])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => implode(' | ', $validation->getErrors())
            ]);
        }

        // 3.0 Verificar PIN actual - Buscar usuario en base de datos
        $CHANGE['USER'] = $this->userModel->find($CHANGE['USER_ID']);
        // 3.1 Validar existencia del usuario
        if (!$CHANGE['USER']) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Usuario no encontrado']);
        }
        // 3.2 Verificar coincidencia de PIN actual con fallbacks
        $is_valid = false;
        if (password_verify($CHANGE['PIN_ACTUAL'], $CHANGE['USER']['pin'])) {
            $is_valid = true;
        } elseif ($CHANGE['USER']['pin'] === md5($CHANGE['PIN_ACTUAL']) || $CHANGE['USER']['pin'] === $CHANGE['PIN_ACTUAL']) {
            $is_valid = true;
        }
        // 3.3 Validar resultado de verificación
        if (!$is_valid) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El PIN actual es incorrecto']);
        }

        // 4.0 Actualizar PIN en la base de datos - Guardar nuevo PIN
        $update_data = ['pin' => $CHANGE['PIN_NUEVO']];
        // 4.1 Ejecutar actualización mediante el modelo
        if (!$this->userModel->update($CHANGE['USER_ID'], $update_data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo actualizar el PIN en el sistema'
            ]);
        }
        // 4.2 Retornar respuesta exitosa
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'El PIN ha sido actualizado con éxito'
        ]);
    }
}
