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

// Clients
$routes->group('client', ['filter' => 'cors'], static function ($routes) {
    $routes->post('save', 'Client::save');
    $routes->get('listing', 'Client::listing');
});

// Products
$routes->group('product', ['filter' => 'cors'], static function ($routes) {
    // $routes->options('(.*)', 'Home::cors');
    $routes->get('listing', 'Product::listing');
    $routes->get('listing_siigo', 'Product::listing_siigo');
    $routes->get('img/(:num)', 'Product::listing_img/$1');
    $routes->get('img/(:any)', 'Product::listing_img/$1'); // Adicionado por precaución para UUID
    $routes->get('video/(:num)', 'Product::listing_video/$1');
    $routes->get('video/(:any)', 'Product::listing_video/$1');
    $routes->get('document/(:num)', 'Product::listing_document/$1');
    $routes->get('document/(:any)', 'Product::listing_document/$1');
    $routes->post('upload_image', 'Product::upload_image');
});