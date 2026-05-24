<?php
//Archivo principal para manejar las solicitudes y enrutar a controladores o vistas

require_once 'core/Router.php';

$router = new Router();

//Registro de rutas para vistas
//Parámetros: ruta, archivo de vista
$router->addView('login', 'app/views/login.php');
$router->addView('register', 'app/views/register.php');
$router->addView('AdminDashboard', 'app/views/admin_dashboard.php');
$router->addView('empleados', 'app/views/empleados/index.php');

//Registro de rutas para controladores
//Parámetros: ruta, controlador, método
$router->addController('login', 'AuthController', 'login');
$router->addController('register', 'AuthController', 'register');
$router->addController('logout', 'AuthController', 'logout');

//Ejecutamos el router con la ruta actual
$router->run(isset($_GET['p']) ? $_GET['p'] : 'login');

?>