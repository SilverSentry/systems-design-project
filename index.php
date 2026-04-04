<?php

session_start();

//Se cargan las configuraciones y el autoload
require_once 'config/config.php';
require_once 'config/routes.php';
require_once 'autoload.php';

//Si hay una acción por POST, se llama al controlador
if (isset($_POST['action'])) {

    $controller = new userController();
    $controller->handleRequest();

}

$pag = isset($_GET['p']) ? $_GET['p'] : 'login';

if(array_key_exists($pag, $routes)) {

    include $routes[$pag];

} else{

    include 'app/views/exception.php';

}

?>