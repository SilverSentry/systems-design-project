<?php
//Archivo principal para manejar las solicitudes y enrutar a controladores o vistas

use App\Core\Router;

$router = new Router();

/**
 * Registro de rutas GET (Páginas, Vistas, etc)
 * Parámetros: ruta, controlador, método
 */

//Rutas de Autenticación
$router->addGetController('login', \App\Controllers\AuthController::class, 'showLogin');
$router->addGetController('register', \App\Controllers\AuthController::class, 'showRegister');
$router->addGetController('logout', \App\Controllers\AuthController::class, 'logout');

//Rutas de Dashboard
$router->addGetController('admin_dashboard', \App\Controllers\DashboardController::class, 'index');
$router->addGetController('dashboard', \App\Controllers\DashboardController::class, 'index');

//Rutas de Empleados
$router->addGetController('employees', \App\Controllers\EmployeeController::class, 'index');

//Rutas de Clientes
$router->addGetController('clients/create', \App\Controllers\ClientController::class, 'create');
$router->addGetController('clients', \App\Controllers\ClientController::class, 'index');

//Rutas de Citas
$router->addGetController('appointments', \App\Controllers\AppointmentController::class, 'index');
$router->addGetController('appointments/create', \App\Controllers\AppointmentController::class, 'create');
$router->addGetController('services', \App\Controllers\AppointmentController::class, 'showServices');
$router->addGetController('services/create', \App\Controllers\AppointmentController::class, 'createService');

//Rutas de Servicios POST
$router->addController('services/store', \App\Controllers\AppointmentController::class, 'storeService');

//Rutas de Inventario
$router->addGetController('inventory', \App\Controllers\InventarioController::class, 'index');
$router->addGetController('api/inventory/history', \App\Controllers\InventarioController::class, 'history');

//Rutas de Facturación
$router->addGetController('invoices', \App\Controllers\InvoiceController::class, 'index');
$router->addGetController('invoices/create', \App\Controllers\InvoiceController::class, 'create');
$router->addGetController('invoices/show', \App\Controllers\InvoiceController::class, 'show');
$router->addGetController('api/appointments/details', \App\Controllers\InvoiceController::class, 'getAppointmentDetails');

//API: búsqueda SNOMED proxyeada por el backend
$router->addGetController('api/search', \App\Controllers\ApiController::class, 'search');

//API: actualización de la tasa oficial del BCV
$router->addController('api/tasa-bcv/actualizar', \App\Controllers\ApiController::class, 'updateTasa');

/**
 * Registro de rutas POST (Acciones, Formularios, etc)
 * Parámetros: ruta, controlador, método
 */

//Rutas de Autenticación
$router->addController('login', \App\Controllers\AuthController::class, 'login');
$router->addController('register', \App\Controllers\AuthController::class, 'register');
$router->addController('logout', \App\Controllers\AuthController::class, 'logout');

//Rutas de Clientes
$router->addController('clients/register', \App\Controllers\ClientController::class, 'register');
$router->addController('clients/edit', \App\Controllers\ClientController::class, 'edit');

//Rutas de Citas
$router->addController('appointments/schedule', \App\Controllers\AppointmentController::class, 'schedule');

//Rutas de Inventario
$router->addController('inventory/create', \App\Controllers\InventarioController::class, 'create');
$router->addController('inventory/edit', \App\Controllers\InventarioController::class, 'edit');
$router->addController('inventory/delete', \App\Controllers\InventarioController::class, 'delete');
$router->addController('inventory/movement', \App\Controllers\InventarioController::class, 'movement');

//Rutas de Facturación
$router->addController('invoices/store', \App\Controllers\InvoiceController::class, 'store');
$router->addController('invoices/cancel', \App\Controllers\InvoiceController::class, 'cancel');

//Ejecutamos el router con la ruta actual
$router->run(isset($_GET['p']) ? $_GET['p'] : 'login');
