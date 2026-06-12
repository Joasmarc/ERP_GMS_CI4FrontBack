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
    $routes->post('add_comment', 'Client::add_comment');
    $routes->get('list_comments/(:num)', 'Client::list_comments/$1');
});

// Dispatch (Remisiones)
$routes->group('dispatch', ['filter' => 'cors'], static function ($routes) {
    $routes->post('save', 'Dispatch::save');
    $routes->get('listing', 'Dispatch::listing');
    $routes->get('view_pdf/(:num)', 'Dispatch::view_pdf/$1');
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
    $routes->post('upload_family_document', 'Product::upload_family_document');
    $routes->post('upload_family_picture', 'Product::upload_family_picture');
    $routes->post('upload_family_video', 'Product::upload_family_video');
});

// Preview Email Template Route
$routes->get('preview-email', function() {
    $data = [
        'client_name'     => 'David Monzant',
        'meeting_date'    => '15 de Mayo de 2026',
        'meeting_time'    => '10:00 AM',
        'meeting_subject' => 'Presentación de Nuevos Insumos Médicos',
        'teams_link'      => 'https://teams.microsoft.com/'
    ];
    return view('emails/teams_invitation', $data);
});