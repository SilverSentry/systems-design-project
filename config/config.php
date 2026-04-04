<?php
//Archivo

//Se inicia sesión de forma segura (Solo si no ha iniciado)
if(session_status() === PHP_SESSION_NONE) {
    session_start();

}

//Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'ordo_stetic');
define('DB_USER', 'root');
define('DB_PASS', '');

//Configuración de Errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Función para redirecciones seguras
function redirect($page) {

    header("Location: " . URL_BASE . "index.php?p=" . $page);
    exit();

}

?>