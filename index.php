<?php

session_start();

//Se cargan las configuraciones, sesiones, rutas y el autoload
require_once 'config/config.php';
require_once 'config/routes.php';
require_once 'autoload.php';

//Si hay una acción por POST, se llama al controlador
if (isset($_POST['action'])) {

    $controller = new userController();
    $controller->handleRequest();

}

$pag = isset($_GET['p']) ? $_GET['p'] : 'login';

//Sistema de ruteo simple
switch ($pag) {

    case 'login':
        include 'app/views/login.php';
        break;
        
    case 'registro':
        include 'app/views/register.php';
        break;
        
    case 'dashboard':
        include 'app/views/dashboard.php';
        break;

    default:
        include 'app/views/exception.php';
        break;
}

?>