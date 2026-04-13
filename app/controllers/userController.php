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
        $passwordConfirm = $_POST['passwordConfirm'];

        //Se verifica que los campos no estén vacíos
        if(empty($name) || empty($surname) || empty($email) || empty($password) || empty($passwordConfirm)) {

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

        } elseif(!preg_match("/(?=.*[A-Z])(?=.*\d).{8,}$/",$password)){

            echo json_encode([
                'status' => 'error',
                'message' => 'La contraseña no cumple con los requisitos',
                'field' => 'password'
            ]);
            exit();

        } elseif($password != $passwordConfirm){

            echo json_encode([
                'status' => 'error',
                'message' => 'Las contraseñas no coinciden',
                'field' => 'passwords'
            ]);
            exit();

        } elseif($this->userModel->emailExists($email)){

            echo json_encode([
                'status' => 'error',
                'message' => 'Ya existe un usuario con el correo ingresado',
                'field' => 'email'
            ]);
            exit();

        } else{

            $user = $this->userModel->register($name, $surname, $email, $password);

            //Si todo es correcto, se registra el usuario
            if($user){

                echo json_encode([
                    'status' => 'success',
                    'redirect' => URL_BASE . 'login?success=1'
                ]);
                exit();

            } else{

                echo json_encode([
                    'status' => 'error',
                    'message' => 'ocurrió un error inesperado'
                ]);
                exit();

            }

        }

    }

    private function login(){

        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        //Se verifica que los campos no estén vacíos
        if(empty($email) || empty($password)){

           echo json_encode([
                'status' => 'error',
                'message' => messages::ERR_EMPTY_FIELDS
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

            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['name'] = $user['name'];

            echo json_encode([
                'status' => 'success',
                'redirect' => URL_BASE . 'dashboard'
            ]);
            exit();

        } else {

            echo json_encode([
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ]);
            exit();

        }

    }

    public function logout(){

        session_start();
        session_unset();//Borra las variables de la sesión actual
        session_destroy();

        header('location: ' . URL_BASE . 'login');
        exit();

    }

}

?>