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
