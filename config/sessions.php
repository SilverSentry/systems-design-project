<?php
//Archivo para manejar las sesiones

//Se inicia sesión de forma segura (Solo si no se ha iniciado)
if(session_status() === PHP_SESSION_NONE) {
    
    session_start();

}

//Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Función para redirecciones seguras
function redirect($page) {

    header('Location: ' . URL_BASE . $page);
    exit();

}

?>