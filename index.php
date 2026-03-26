<?php

//Se cargan las configuraciones y el autoload
require_once 'config/routes.php';
require_once 'autoload.php';

//Si hay una acción por POST, se llamam al controlador
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
        
    case 'welcome':
        include 'app/views/welcome.php';
        break;

    default:
        include 'app/views/hola.php';
        break;
}

?>