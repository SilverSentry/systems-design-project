<?php

class userController{

    private $userModel;

    public function __construct(){

        $database = new connection();
        $db = $database->getConnection();
        $this->userModel = new userModel($db);

    }

    public function handleRequest(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            if(isset($_POST['action'])) {

                switch($_POST['action']) {

                    case 'register':

                        $this->register();
                        break;

                    case 'login':

                        $this->login();
                        break;

                    case 'logout':

                        $this->logout();
                        break;
                }
            }
        }
    }

    private function register(){

        //Limpieza de datos
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        //Array para las respuestas
        $response = [];

        //Se verifica que los campos no estén vacíos
        if (empty($name) || empty($surname) || empty($email) || empty($password)) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Rellene todos los campos',
                'field' => 'all' //Identificador del input
            ]);
            exit();

        //Se valida el nombre y el apellido
        } elseif(!preg_match("/^[a-zA-z]+$/", $name)){

            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre ingresado no es válido',
                'field' => 'name'
            ]);
            exit(); 

        } elseif(!preg_match("/^[a-zA-z]+$/", $surname)){

            echo json_encode([
                'status' => 'error',
                'message' => 'El apellido ingresado no es válido',
                'field' => 'surname'
            ]);
            exit(); 

        //Se valida el correo electrónico
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            echo json_encode([
                'status' => 'error',
                'message' => 'El formato del email no es válido',
                'field' => 'email'
            ]);
            exit(); 

        }else{

            $user = $this->userModel->register($name, $surname, $email, $password);

            //Si todo es correcto, se registra el usuario
            if($user){

                $response['status'] = 'success';
                $response['redirect'] = URL_BASE . 'index.php?p=login&success=1';

            } else{

                $response['status'] = 'error';
                $response['message'] = 'Ocurrió un error inesperado';

            }

        }

        //Ahora se envía el JSON y se corta la ejecución
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();

    }

    private function login(){

        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        //Array para las respuestas
        $response = [];

         //Se verifica que los campos no estén vacíos
        if (empty($email) || empty($password)){

           echo json_encode([
                'status' => 'error',
                'message' => 'Rellene todos los campos'
            ]);
            exit();

        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            echo json_encode([
                'status' => 'error',
                'message' => 'El formato del email no es válido',
                'field' => 'email'
            ]);
            exit();
            
        }

        $user = $this->userModel->login($email, $password);

        if($user) {

            session_start();

            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['name'] = $user['name'];
            $response['status'] = 'success';
            $response['redirect'] = URL_BASE . 'index.php?p=dashboard';

        } else {

          echo json_encode([
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ]);
            exit();

        }

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();

    }

    public function logout(){

        session_start();
        session_unset();//Borra las variables de la sesión actual
        session_destroy();

        header('location: ' . URL_BASE . 'index.php?p=login');
        exit();

    }

}

?>