<?php
//Archivo de configuración

namespace {

    use App\Core\Paths;

    //Configuración de errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //Función para redirecciones seguras
    function redirect($page)
    {
        header('Location: ' . Paths::to($page));
        exit();
    }

}
