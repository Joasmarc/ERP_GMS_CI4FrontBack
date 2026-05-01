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
        // 3.2 Verificar PIN
        if ($AUTH['USER']['pin'] !== $AUTH['PIN']) {
            return redirect()->back()->with('error', 'PIN incorrecto');
        }

        // 4.0 Configurar sesión de usuario - Crear estructura base de sesión
        $sesion = [
            'user_id' => $AUTH['USER']['id'],
            'email' => $AUTH['USER']['email'],
            'name' => $AUTH['USER']['name'],
            'gender' => $AUTH['USER']['gender'],
            'credentials' => $AUTH['USER']['credentials'],
            'isLoggedIn' => true
        ];
        // 4.1 Integrar Token de Siigo si está disponible
        $token = $this->getSiigoToken();
        if ($token) {
            $sesion['siigo_token'] = $token['access_token'];
            $sesion['siigo_token_expires'] = time() + $token['expires_in'];
        }
        // 4.2 Guardar sesión
        session()->set($sesion);

        // 5.0 Redirigir al dashboard
        return redirect()->to('dashboard')->with('success', 'Bienvenido: ' . $AUTH['USER']['name']);
    }

    // Obtener token de la API de Siigo
    private function getSiigoToken()
    {
        // 1.0 Inicializar interfaz y cliente HTTP - Iniciar variable de interfaz
        $API = [];
        // 1.1 Iniciar cliente HTTP
        $httpClient = \Config\Services::curlrequest();

        // 2.0 Realizar petición a la API y manejar respuesta - Ejecutar petición
        try {
            $response = $httpClient->post(env('SIIGO_AUTH_URL'), [
                'headers' => [
                    'Partner-Id'   => env('SIIGO_PARTNER_ID'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'username'   => env('SIIGO_USERNAME'),
                    'access_key' => env('SIIGO_ACCESS_KEY'),
                ],
                'http_errors' => false
            ]);
            // 2.1 Verificar respuesta exitosa
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
            // 2.2 Manejar error de autenticación
            log_message('error', 'Error al autenticar en Siigo: ' . $response->getBody());
            return null;
        } catch (\Exception $e) {
            // 2.3 Manejar excepción de conexión
            log_message('error', 'Excepción al conectar con Siigo: ' . $e->getMessage());
            return null;
        }
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
}
