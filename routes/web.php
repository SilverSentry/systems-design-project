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
$router->addGetController('services', \App\Controllers\AppointmentController::class, 'showServices');

$router->addGetController('inventory', \App\Controllers\InventarioController::class, 'index');
$router->addGetController('api/inventory/history', \App\Controllers\InventarioController::class, 'history');

//API: búsqueda SNOMED proxyeada por el backend
$router->addGetController('api/search', \App\Controllers\ApiController::class, 'search');

//API: actualización de la tasa oficial del BCV
$router->addController('api/tasa-bcv/actualizar', \App\Controllers\ApiController::class, 'updateTasa');

//Registro de rutas para controladores que manejan POST (acciones, formularios, etc.)
//Parámetros: ruta, controlador, método
$router->addController('login', \App\Controllers\AuthController::class, 'login');
$router->addController('register', \App\Controllers\AuthController::class, 'register');
$router->addController('logout', \App\Controllers\AuthController::class, 'logout');
$router->addController('clients/register', \App\Controllers\ClientController::class, 'register');
$router->addController('clients/edit', \App\Controllers\ClientController::class, 'edit');
$router->addController('appointments/schedule', \App\Controllers\AppointmentController::class, 'schedule');

$router->addController('inventory/create', \App\Controllers\InventarioController::class, 'create');
$router->addController('inventory/edit', \App\Controllers\InventarioController::class, 'edit');
$router->addController('inventory/delete', \App\Controllers\InventarioController::class, 'delete');
$router->addController('inventory/movement', \App\Controllers\InventarioController::class, 'movement');

//Ejecutamos el router con la ruta actual
$router->run(isset($_GET['p']) ? $_GET['p'] : 'login');
