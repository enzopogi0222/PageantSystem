<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Admin::dashboard');
$routes->get('dashboard', 'Admin::dashboard');
$routes->get('api/dashboard-stats', 'Admin::dashboardStats');
$routes->get('contestant', 'Contestant::index');
$routes->get('judges', 'Judges::index');
$routes->get('rounds', 'Rounds::index');
$routes->get('results', 'Results::index');