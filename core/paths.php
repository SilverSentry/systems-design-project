<?php
//Archivo Paths para definir rutas base

namespace App\Core;

class Paths {

    //Raiz del proyecto
    private static $base = "/StudioOrdoStetic/";

    //Método estático para obtener cualquier ruta limpia
    public static function to($route = "") {

        return self::$base . $route;

    }

    //Método para obtener la ruta de un asset
    public static function asset($path) {

        return self::$base . "assets/" . $path;

    }

}

?>
