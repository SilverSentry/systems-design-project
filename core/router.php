<?php
//Archivo Router para manejar rutas y controladores

namespace App\Core;

class Router
{

    //Arreglos para almacenar rutas y controladores
    private $views = [];
    private $controllers = [];
    private $getControllers = [];

    /**
     * Registra una ruta y el archivo de vista que debe cargar
     * @param mixed $route
     * @param mixed $file
     */
    public function addView($route, $file)
    {
        $this->views[$route] = $file;
    }

    /**
     * Registra una ruta, el controlador y el método para POST
     * @param mixed $route
     * @param mixed $controller
     * @param mixed $method
     */
    public function addController($route, $controller, $method)
    {
        $this->controllers[$route] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Registra controladores accesibles por GET (API endpoints, etc.)
     * @param mixed $route
     * @param mixed $controller
     * @param mixed $method
     */
    public function addGetController($route, $controller, $method)
    {
        $this->getControllers[$route] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Comparamos la URL actual con lo que tenemos registrada y ejecutamos la acción correspondiente
     * @param mixed $currentRoute
     */
    public function run($currentRoute)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($currentRoute);
        } else {
            $this->handleGet($currentRoute);
        }
    }

    //Rutas públicas que no requieren sesión
    private function isPublicRoute(string $path): bool
    {
        $public = [
            'login',
            'register',
            'logout'
        ];

        //Permitir APIs públicas
        if (strpos($path, 'api/') === 0) {
            return true;
        }

        return in_array($path, $public, true);
    }

    /**
     * Manejo de las peticiones GET
     * @param mixed $currentRoute
     */
    private function handleGet($currentRoute)
    {

        //Limpiamos barras vacías al inicio y al final (ej: "/employees/" se vuelve "employees")
        $cleanRoute = trim($currentRoute, '/');

        //Si la ruta está vacía, vamos al login por defecto
        $path =  $cleanRoute ?: 'login';

        //Aplicar control de expiración de sesión por inactividad
        Session::enforceTimeout(15 * 60);

        //Si la ruta no está registrada como vista ni como controlador GET, mostramos 404
        if (!array_key_exists($path, $this->views) && !array_key_exists($path, $this->getControllers)) {
            $this->render404();
        }

        //Si la ruta no es pública y el usuario no está logueado, redirigimos al login
        if (!Session::isLogged() && !$this->isPublicRoute($path)) {
            redirect('login?auth_error=1');
            exit();
        }

        //Verificamos si la ruta existe en las vistas registradas
        if (array_key_exists($path, $this->views)) {
            $viewPath = $this->views[$path];

            //Verificamos que el archivo de vista exista físicamente antes de incluirlo
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                $this->render404(); //Si el archivo no existe físicamente
            }
        } else {

            //Si la ruta no está registrada como vista, comprobar si es un controlador GET (API)
            $config = $this->getControllers[$path];
            $controllerName = $config['controller'];
            $method = $config['method'];

            $controller = new $controllerName();
            $controller->$method();
            return;
        }
    }

    /**
     * Manejo de las peticiones POST
     * @param mixed $currentRoute
     */
    private function handlePost($currentRoute)
    {

        //Limpiamos la ruta
        $cleanRoute = trim($currentRoute, '/');
        $path = $cleanRoute ?: 'login';

        //Aplicar control de expiración de sesión por inactividad
        Session::enforceTimeout(15 * 60);

        //Para POST: permitir acciones públicas (login, register) sin sesión
        if (!Session::isLogged() && !$this->isPublicRoute($path)) {
            redirect('login');
            exit();
        }

        //Ahora comprobamos si la URL (ej: 'clients/edit') existe en tus controladores POST
        if (array_key_exists($path, $this->controllers)) {

            $config = $this->controllers[$path];
            $controllerName = $config['controller'];
            $method = $config['method'];

            $controller = new $controllerName();
            $controller->$method();
        } else {

            //Verificamos que se haya enviado una acción y que esté registrada en los controladores
            if (isset($_POST['action']) && array_key_exists($_POST['action'], $this->controllers)) {

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
    }

    //Método para mostrar un error 404
    private function render404()
    {
        http_response_code(404);

        $viewPath = __DIR__ . '/../app/views/404.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "<h1>404 - Página no encontrada</h1>";
        }

        exit();
    }
}
