<?php
//Helpers globales para las vistas y controladores

namespace App\Core;

class Helpers
{
    /**
     * Compara la página actual con el enlace y devuelve 'active' si coinciden
     *
     * @param string $keyword Palabra clave para buscar en la URL
     * @return string
     */
    public static function activeClass(string $keyword): string
    {
        //Captura lo que está escrito en la barra de direcciones (ej: "/employees")
        $currentUrl = $_SERVER['REQUEST_URI'];

        //Comprobamos si la palabra clave existe dentro de la URL actual
        if (strpos($currentUrl, $keyword) !== false) {
            return 'active';
        }

        return '';
    }
}
