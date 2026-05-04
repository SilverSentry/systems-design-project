<?php
//Archivo de configuración

//Cargamos la clase de rutas
require_once __DIR__ . '/../core/Paths.php';

//Cargamos el manejador de sesiones
require_once __DIR__ . '/../core/Session.php';

//Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Función para redirecciones seguras
function redirect($page) {
    header('Location: ' . Paths::to($page));
    exit();
}
