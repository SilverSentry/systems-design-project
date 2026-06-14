<?php
//Archivo principal para manejar las solicitudes y enrutar a controladores o vistas

use App\Core\Router;

$router = new Router();

//Registro de rutas para controladores que manejan GET (páginas, vistas, etc.)
//Parámetros: ruta, controlador, método
$router->addGetController('login', \App\Controllers\AuthController::class, 'showLogin');
$router->addGetController('register', \App\Controllers\AuthController::class, 'showRegister');
$router->addGetController('admin_dashboard', \App\Controllers\DashboardController::class, 'index');
$router->addGetController('dashboard', \App\Controllers\DashboardController::class, 'index');
$router->addGetController('employees', \App\Controllers\EmployeeController::class, 'index');
$router->addGetController('clients/create', \App\Controllers\ClientController::class, 'create');
$router->addGetController('clients', \App\Controllers\ClientController::class, 'index');
$router->addGetController('logout', \App\Controllers\AuthController::class, 'logout');
$router->addGetController('appointments', \App\Controllers\AppointmentController::class, 'index');
$router->addGetController('appointments/create', \App\Controllers\AppointmentController::class, 'create');

//API: búsqueda SNOMED proxyeada por el backend
$router->addGetController('api/search', \App\Controllers\ApiController::class, 'search');

//Registro de rutas para controladores que manejan POST (acciones, formularios, etc.)
//Parámetros: ruta, controlador, método
$router->addController('login', \App\Controllers\AuthController::class, 'login');
$router->addController('register', \App\Controllers\AuthController::class, 'register');
$router->addController('logout', \App\Controllers\AuthController::class, 'logout');
$router->addController('clients/register', \App\Controllers\ClientController::class, 'register');
$router->addController('clients/edit', \App\Controllers\ClientController::class, 'edit');
$router->addController('appointments/schedule', \App\Controllers\AppointmentController::class, 'schedule');

//Ejecutamos el router con la ruta actual
$router->run(isset($_GET['p']) ? $_GET['p'] : 'login');
