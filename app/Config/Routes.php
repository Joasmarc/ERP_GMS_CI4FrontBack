<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1.0 Rutas de autenticación
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');
$routes->post('auth/change_pin', 'Auth::change_pin');

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

// Counterparties (Admin actions)
$routes->group('counterparty', ['filter' => 'cors'], static function ($routes) {
    $routes->get('listing', 'Counterparty::listing');
    $routes->post('save', 'Counterparty::save');
    $routes->post('share', 'Counterparty::share');
    $routes->post('upload_policy', 'Counterparty::upload_policy');
});

// Public form (Guest access)
$routes->get('register/form/(:any)', 'PublicForm::index/$1');
$routes->post('register/submit/(:any)', 'PublicForm::submit/$1');
$routes->get('public/departments', 'PublicForm::getDepartments');
$routes->get('public/cities/(:num)', 'PublicForm::getCitiesByDepartment/$1');

// Dispatch (Remisiones)
$routes->group('dispatch', ['filter' => 'cors'], static function ($routes) {
    $routes->post('save', 'Dispatch::save');
    $routes->get('listing', 'Dispatch::listing');
    $routes->get('view_pdf/(:num)', 'Dispatch::view_pdf/$1');
});

// Warehouses (Bodegas)
$routes->group('warehouse', ['filter' => 'cors'], static function ($routes) {
    $routes->get('listing', 'Warehouse::listing');
    $routes->get('active_list', 'Warehouse::active_list');
    $routes->get('active_list/(:num)', 'Warehouse::active_list/$1');
    $routes->get('balance/(:num)', 'Warehouse::balance/$1');
    $routes->get('search_families', 'Warehouse::search_families');
    $routes->post('save', 'Warehouse::save');
    $routes->post('save_item', 'Warehouse::save_item');
    $routes->post('save_remision', 'Warehouse::save_remision');
    $routes->post('transfer', 'Warehouse::transfer');
    $routes->post('adjust', 'Warehouse::adjust');
    $routes->get('download_batch_template', 'Warehouse::download_batch_template');
    $routes->post('import_batch_items', 'Warehouse::import_batch_items');
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
    $routes->post('delete_document', 'Product::delete_document');
    $routes->post('delete_picture', 'Product::delete_picture');
    $routes->post('delete_video', 'Product::delete_video');
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