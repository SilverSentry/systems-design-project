<?php
//Archivo principal para manejar las solicitudes y enrutar a controladores o vistas

require_once 'core/Router.php';

$router = new Router();

//Registro de rutas para vistas
//Parámetros: ruta, archivo de vista
$router->addView('login', 'app/views/auth/login.php');
$router->addView('register', 'app/views/auth/register.php');
$router->addView('admin_dashboard', 'app/views/admin_dashboard.php');
$router->addView('employees', 'app/views/employees/index.php');
$router->addView('clients', 'app/views/clients/index.php');
$router->addView('clients/create', 'app/views/clients/create.php');

//API: búsqueda SNOMED proxyeada por el backend
$router->addGetController('api/search', 'ApiController', 'search');

//Registro de rutas para controladores
//Parámetros: ruta (value del input), controlador, método
$router->addController('login', 'AuthController', 'login');
$router->addController('register', 'AuthController', 'register');
$router->addController('logout', 'AuthController', 'logout');
$router->addController('clients/register', 'ClientController', 'register');

//Ejecutamos el router con la ruta actual
$router->run(isset($_GET['p']) ? $_GET['p'] : 'login');

?>