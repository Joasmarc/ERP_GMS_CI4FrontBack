<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1.0 Rutas de autenticación
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');

// 2.0 Rutas principales
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Main::dashboard');

// Products

$routes->group('product', ['filter' => 'cors'], static function ($routes) {
    // $routes->options('(.*)', 'Home::cors');
    $routes->get('listing', 'Product::listing');
});