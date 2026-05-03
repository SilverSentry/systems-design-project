<?php

//Se cargan las configuraciones y el autoload
require_once 'config/config.php';
require_once 'autoload.php';

//Si hay una acción por POST, se llama al controlador
if (isset($_POST['action'])) {

    $controller = new AuthController();
    $controller->handleRequest();

}

require_once 'routes/web.php';

?>