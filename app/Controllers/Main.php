<?php

namespace App\Controllers;

use App\Models\Cities;

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

        // 1.3 Obtener lista de ciudades activas
        $citiesModel = new Cities();
        $cities = $citiesModel->where('state', 'ACTIVO')->findAll();

        // 2.0 Preparar datos de la vista - Obtener datos de sesión
        $userData = [
            'user_id' => session('user_id'),
            'email'   => session('email'),
            'name'    => session('name'),
            'gender' => session('gender'),
            'credentials' => session('credentials'),
            'title'   => 'Dashboard - Sistema de Administración',
            'cities'  => $cities
        ];

        // 3.0 Renderizar vista del dashboard
        return view('dashboard', $userData);
    }
}
