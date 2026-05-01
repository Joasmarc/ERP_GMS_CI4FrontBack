<?php

namespace App\Controllers;

class Main extends BaseController
{
    // Mostrar el dashboard protegido
    public function dashboard()
    {
        // 1.0 Inicializar interfaz, helpers y verificar sesión - Iniciar variable de interfaz
        $MAIN = [];
        // 1.1 Cargar helper de utilidades
        helper('utils');
        // 1.2 Verificar si usuario está autenticado
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión primero');
        }

        // 2.0 Preparar datos de la vista - Obtener datos de sesión
        $userData = [
            'user_id' => session('user_id'),
            'email'   => session('email'),
            'name'    => session('name'),
            'title'   => 'Dashboard - Sistema de Administración'
        ];
        // 2.1 Formatear credenciales usando helper
        $userData['credentials'] = pad_right_zeros((string) session('credentials'));

        // 3.0 Renderizar vista del dashboard
        return view('dashboard', $userData);
    }
}
