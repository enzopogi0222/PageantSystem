<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('admin', 'Admin::adminHome');
    $routes->get('events/create', 'Events::create');
    $routes->post('events', 'Events::store');
    $routes->post('events/activate/(:num)', 'Events::activate/$1');
    $routes->get('api/dashboard-stats', 'Admin::dashboardStats');
    $routes->get('contestant', 'Contestant::index');
    $routes->get('judges', 'Judges::index/1'); 
    $routes->get('events/(:num)/judges', 'Judges::index/$1');
    $routes->post('events/(:num)/judges/create', 'Judges::create/$1');
    $routes->get('rounds', 'Rounds::index');
    $routes->get('results', 'Results::index');
});
// Auth routes
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');