<?php
//Archivo para el llamado de las rutas

class paths{

    //Raiz del proyecto
    private static $base = "http://localhost/ordo_stetic/";

    //Método estático para obtener cualquier ruta limpia
    public static function to($route = ""){

        return self::$base . $route;

    }

    public static function asset($path) {

        return self::$base . "assets/" . $path;

    }

}

?>