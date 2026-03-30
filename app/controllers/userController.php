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

        //Se verifica que los campos no estén vacíos
        if (empty($name) || empty($surname) || empty($email) || empty($password)) {

            header('location: ' . URL_BASE . 'index.php?p=registro&error=empty_fields');
            exit();

        //Se valida el nombre y el apellido
        } elseif(!preg_match("/^[a-zA-z]+$/", $name) || !preg_match("/^[a-zA-z]+$/", $surname)){

            header('location: ' . URL_BASE . 'index.php?p=registro&error=invalid_name');
            exit();

        //Se valida el correo electrónico
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            header('location: ' . URL_BASE . 'index.php?p=registro&error=invalid_email');
            exit();

        }else{

            //Si todo es correcto, se registra el usuario
            if($this->userModel->register($name, $surname, $email, $password)){

                header('location: ' . URL_BASE . 'index.php?p=login&success=1');

            } else{

                header('location: ' . URL_BASE . 'index.php?p=registro&error_already_exist');

            }

        }

    }

    private function login(){

        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $user = $this->userModel->login($email, $password);

         //Se verifica que los campos no estén vacíos
        if (empty($email) || empty($password)){

            header('location: ' . URL_BASE . 'index.php?p=login&error=empty_fields');
            exit();

        }

        if($user) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            
            header('Location: ' . URL_BASE . 'index.php?p=welcome');

        } else {

            header('Location: ' . URL_BASE . 'index.php?p=login&error=auth_failed');

        }

        exit();

    }

}

?>