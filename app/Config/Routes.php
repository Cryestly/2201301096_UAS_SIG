<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/detail/(:segment)', 'Home::detail/$1');
$routes->post('/tambah-data', 'Home::tambahData');
$routes->post('/edit', 'Home::edit');
$routes->get('/delete/(:segment)', 'Home::delete/$1');