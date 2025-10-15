<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Admin::dashboard');
$routes->get('contestant', 'Contestant::index');
$routes->get('judges', 'Judges::index');