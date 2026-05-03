<?php

class Router {

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

                $this->render404(); //Si el archivo no existe físicamente

            }

        }else {

            $this->render404(); //Si la ruta no está registrada

        }
    }

    private function render404() {

        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        exit();

    }
}

?>