<?php

class router {

    private $routes = [];

    //Registramos una ruta y el archivo que debe cargar
    public function add($route, $file) {

        $this->routes[$route] = $file;

    }

    //Comparamos la URL actual con lo que tenemos registrado
    public function run($currentRoute) {

        //Si la ruta está vacía, vamos al login por defecto
        $path = $currentRoute ?: 'login';

        if(array_key_exists($path, $this->routes)) {

            $viewPath = $this->routes[$path];

            if(file_exists($viewPath)) {

                include $viewPath;

            }else {

                echo "Error: La vista no existe físicamente en $viewPath";

            }

        }else {

            http_response_code(404);
            echo "<h2>Error 404 - Página No Encontrada</h2>";

        }
    }
}

?>