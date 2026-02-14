<?php

namespace App\Controllers;

use App\Models\Users;

class Auth extends BaseController
{
    // 1.0 Inicializar propiedades del controlador
    protected $userModel;

    public function __construct()
    {
        // 1.1 Cargar modelo de usuario
        $this->userModel = new Users();
    }

    // 2.0 Mostrar formulario de login
    public function login()
    {
        // 2.1 Verificar si usuario ya está autenticado
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        // 2.2 Pasar datos a la vista
        $data = [
            'title' => 'Login - Sistema de Administración'
        ];

        // 2.3 Renderizar vista de login
        return view('auth/login', $data);
    }

    // 3.0 Procesar datos de login
    public function authenticate()
    {
        // 3.1 Validar método POST
        if (!$this->request->is('post')) {
            return redirect()->to('login')->with('error', 'Método no permitido');
        }

        // 3.2 Obtener datos del formulario
        $email = trim($this->request->getPost('email'));
        $pin = trim($this->request->getPost('pin'));

        // 3.3 Validar campos requeridos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'pin' => 'required|exact_length[4]'
        ]);

        if (!$validation->run(['email' => $email, 'pin' => $pin])) {
            $errors = $validation->getErrors();
            log_message('error', 'Validación fallida: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // 3.4 Buscar usuario por email
        $user = $this->userModel->where('email', $email)->first();

        // 3.5 Verificar si usuario existe
        if (!$user) {
            return redirect()->back()->with('error', 'Correo no encontrado');
        }

        // 3.6 Verificar PIN
        if ($user['pin'] !== $pin) {
            return redirect()->back()->with('error', 'PIN incorrecto');
        }

        // 3.7 Crear sesión de usuario
        $sessionData = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'isLoggedIn' => true
        ];

        // 3.8 Guardar sesión
        session()->set($sessionData);

        // 3.9 Redirigir al dashboard
        return redirect()->to('dashboard')->with('success', 'Bienvenido: ' . $user['name']);
    }

    // 4.0 Cerrar sesión
    public function logout()
    {
        // 4.1 Destruir la sesión
        session()->destroy();

        // 4.2 Redirigir al login
        return redirect()->to('/login')->with('success', 'Sesión cerrada correctamente');
    }
}
