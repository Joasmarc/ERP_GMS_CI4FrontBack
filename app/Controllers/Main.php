<?php

namespace App\Controllers;

class Main extends BaseController
{
    // 1.0 Mostrar el dashboard protegido
    public function dashboard()
    {
        // 1.1 Verificar si usuario está autenticado
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión primero');
        }

        // 1.2 Obtener datos del usuario desde la sesión
        $userData = [
            'user_id' => session('user_id'),
            'email' => session('email'),
            'name' => session('name'),
            'title' => 'Dashboard - Sistema de Administración'
        ];

        // 1.3 Pasar datos a la vista del dashboard
        return view('dashboard', $userData);
    }
}
