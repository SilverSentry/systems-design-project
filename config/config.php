<?php

namespace {

use App\Core\Paths;
use App\Core\Session;

//Archivo de configuración

//Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Función para redirecciones seguras
function redirect($page) {
    header('Location: ' . Paths::to($page));
    exit();
}

/**
 * Configuración de BioPortal con Variables de Entorno
 * * Graias a phpdotenv, primero se lee lee desde la superglobal $_ENV
 * Si por alguna razón se usan variables del sistema operativo, cae en getenv()
 * Si no se encuentra ninguna, queda vacía para evitar fallos de sintaxis
 */
$apiKey = $_ENV['BIOPORTAL_API_KEY'] ?? getenv('BIOPORTAL_API_KEY') ?? '';

define('BIOPORTAL_API_KEY', $apiKey);

}

