<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Admin-only routes
    $routes->group('', ['filter' => 'admin'], static function ($routes) {
        $routes->get('dashboard', 'Admin::dashboard');
        $routes->get('admin', 'Admin::adminHome');
        $routes->get('events/create', 'Events::create');
        $routes->post('events', 'Events::store');
        $routes->post('events/activate/(:num)', 'Events::activate/$1');
        $routes->get('api/dashboard-stats', 'Admin::dashboardStats');
        $routes->get('events/(:num)/contestants', 'Contestant::index/$1');
        $routes->get('events/(:num)/judges', 'Judges::index/$1');
        $routes->post('events/(:num)/judges/create', 'Judges::create/$1');
        $routes->get('contestant', 'Contestant::index');
        $routes->post('contestant', 'Contestant::store');
        $routes->post('contestant/update/(:num)', 'Contestant::update/$1');
        $routes->get('judges', 'Judges::index');
        // Rounds routes
        $routes->get('rounds', 'Rounds::index');
        $routes->post('rounds', 'Rounds::storeActive');
        $routes->get('events/(:num)/rounds', 'Rounds::index/$1');
        $routes->post('events/(:num)/rounds', 'Rounds::store/$1');
        $routes->post('rounds/(:num)/update', 'Rounds::update/$1');
        $routes->post('rounds/(:num)/delete', 'Rounds::delete/$1');
        $routes->post('rounds/(:num)/status', 'Rounds::toggleStatus/$1');
        // Criteria routes
        $routes->get('rounds/(:num)/criteria', 'Criteria::index/$1');
        $routes->post('rounds/(:num)/criteria', 'Criteria::store/$1');
        $routes->post('criteria/(:num)/update', 'Criteria::update/$1');
        $routes->post('criteria/(:num)/delete', 'Criteria::delete/$1');
        $routes->get('results', 'Results::index');
    });

    // Judge routes
    $routes->group('judge', static function ($routes) {
        $routes->get('/', 'Judge::dashboard');
        $routes->get('contestants/(:num)', 'Judge::contestant/$1');
        $routes->get('contestants', 'Judge::contestants');
    });
});

// Auth routes
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');