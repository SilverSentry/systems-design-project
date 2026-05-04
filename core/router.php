<?php
//Archivo Router para manejar rutas y controladores

class Router {

    //Arreglos para almacenar rutas y controladores
    private $views = [];
    private $controllers = [];

    //Registramos una ruta y el archivo de vista que debe cargar
    public function addView($route, $file) {
        $this->views[$route] = $file;
    }

    //Registramos una ruta, el controlador y el método para POST
    public function addController($route, $controller, $method) {
        $this->controllers[$route] = ['controller' => $controller, 'method' => $method];
    }

    //Comparamos la URL actual con lo que tenemos registrada y ejecutamos la acción correspondiente
    public function run($currentRoute) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();

        } else {
            $this->handleGet($currentRoute);
        }

    }

    //Manejo de las peticiones GET
    private function handleGet($currentRoute) {

        //Si la ruta está vacía, vamos al login por defecto
        $path = $currentRoute ?: 'login';

        //Verificamos si la ruta existe en las vistas registradas
        if(array_key_exists($path, $this->views)) {
            $viewPath = $this->views[$path];

            //Verificamos que el archivo de vista exista físicamente antes de incluirlo
            if(file_exists($viewPath)) {
                include $viewPath;

            } else {
                $this->render404(); //Si el archivo no existe físicamente
            }

        } else {
            $this->render404(); //Si la ruta no está registrada
        }

    }

    //Manejo de las peticiones POST
    private function handlePost() {

        //Verificamos que se haya enviado una acción y que esté registrada en los controladores
        if(isset($_POST['action']) && array_key_exists($_POST['action'], $this->controllers)) {

            $config = $this->controllers[$_POST['action']];
            $controllerName = $config['controller'];
            $method = $config['method'];

            //Instanciar el controlador y llamar el método
            $controller = new $controllerName();
            $controller->$method();

        } else {
            $this->render404(); //Si la acción no está registrada o no se envió
        }
    }

    //Método para mostrar un error 404
    private function render404() {
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        exit();
    }

}

?>