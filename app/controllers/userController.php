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

        header('location: ' . URL_BASE . 'index.php?p=registro&error_fill_all');
        exit();

        //Se valida el correo electrónico
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        header('location: ' . URL_BASE . 'index.php?p=registro&error_email');
        exit();

        } else{

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

        if($user) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            
            header('Location: ' . URL_BASE . 'index.php?p=welcome');

        } else {

            $_SESSION['error'] = "Credenciales incorrectas.";
            header('Location: ' . URL_BASE . 'index.php?=login&error');

        }

        exit();

    }

}

?>