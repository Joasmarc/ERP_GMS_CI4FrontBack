<?php

namespace App\Controllers;

use App\Models\Cities;
use App\Models\Users;

class Main extends BaseController
{
    // Dasboard_G02 - Mostrar el dashboard protegido
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

        // 1.4 Obtener lista de usuarios con credencial para bodegas (credentials[13] === '1')
        $usersModel = new Users();
        $allUsers = $usersModel->select('id, name, email, credentials')->orderBy('name', 'ASC')->findAll();
        $warehouseUsers = [];
        foreach ($allUsers as $u) {
            $userCreds = pad_right_zeros((string)($u['credentials'] ?? ''));
            if ((isset($userCreds[13]) && $userCreds[13] === '1') || (isset($userCreds[14]) && $userCreds[14] === '1')) {
                $warehouseUsers[] = [
                    'id'    => (int)$u['id'],
                    'name'  => $u['name'],
                    'email' => $u['email']
                ];
            }
        }
        // Fallback: Si ningún usuario tiene la credencial configurada aún, listar todos para evitar selector vacío
        if (empty($warehouseUsers)) {
            foreach ($allUsers as $u) {
                $warehouseUsers[] = [
                    'id'    => (int)$u['id'],
                    'name'  => $u['name'],
                    'email' => $u['email']
                ];
            }
        }

        // 2.0 Preparar datos de la vista - Obtener datos de sesión
        $userData = [
            'user_id'        => session('user_id'),
            'email'          => session('email'),
            'name'           => session('name'),
            'gender'         => session('gender'),
            'credentials'    => session('credentials'),
            'title'          => 'Dashboard - Sistema de Administración',
            'cities'         => $cities,
            'warehouseUsers' => $warehouseUsers
        ];

        // 3.0 Renderizar vista del dashboard
        return view('dashboard', $userData);
    }
}
