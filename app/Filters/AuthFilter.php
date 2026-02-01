<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    // 1.0 Ejecutar antes de procesar la solicitud
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1.1 Verificar si usuario está autenticado
        if (!session()->has('user_id')) {
            // 1.2 Redirigir al login si no está autenticado
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión');
        }

        // 1.3 Retornar null si pasa la validación
        return null;
    }

    // 2.0 Ejecutar después de procesar la solicitud
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 2.1 No se requiere procesamiento posterior
        return null;
    }
}
