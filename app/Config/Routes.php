<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Page d'accueil : choix Client / Operateur
$routes->get('/', 'Home::index');

// Espace CLIENT

$routes->get('client/login', 'ClientController::login');
$routes->post('client/login', 'ClientController::doLogin');
$routes->get('client/dashboard', 'ClientController::dashboard');
$routes->get('client/operation', 'ClientController::operation');
$routes->post('client/operation', 'ClientController::doOperation');
$routes->get('client/historique', 'ClientController::historique');
$routes->get('client/logout', 'ClientController::logout');

// Espace OPERATEUR (admin)
// ---------------------------------------------------------------
$routes->get('admin/login', 'AdminController::login');
$routes->post('admin/login', 'AdminController::doLogin');
$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/clients', 'AdminController::clients');
$routes->get('admin/clients/(:num)', 'AdminController::clientDetail/$1');

$routes->get('admin/tranches', 'AdminController::tranches');
$routes->post('admin/tranches/add', 'AdminController::addTranche');
$routes->post('admin/tranches/delete/(:num)', 'AdminController::deleteTranche/$1');
$routes->get('admin/prefixes', 'AdminController::prefixes');
$routes->post('admin/prefixes/add', 'AdminController::addPrefixe');
$routes->post('admin/prefixes/delete/(:num)', 'AdminController::deletePrefixe/$1');
$routes->get('admin/logout', 'AdminController::logout');
