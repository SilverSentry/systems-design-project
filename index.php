<?php

//Se cargan las configuraciones y el autoload
require_once 'config/routes.php';
require_once 'autoload.php';

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
        include 'app/views/404.php';
        break;
}

?>