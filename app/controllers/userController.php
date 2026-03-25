<?php

require_once '../../config/Connection.php';
require_once '../models/userModel.php';

class userController{

    private $userModel;

    public function __construct(){

        $database = new connection();
        $db = $database->getConnection();
        $this->userModel = new user($db);

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

        if (empty($_POST['name']) || empty($_POST['surname']) || empty($_POST['email']) || empty($_POST['password'])) {

        header('location: ../views/register.php?=error_fill_all');

        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        header('location: ../views/login.php?=error');

        } else{

            if($this->userModel->register($name, $surname, $email, $password)){

                header('location: ../views/login.php?=succes');

            } else{

                header('location: ../views/register?=error_already_exist');

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
            
            header("Location: ../views/welcome.php");

        } else {

            $_SESSION['error'] = "Credenciales incorrectas.";
            header("Location: ../views/login.php?=error");

        }

        exit();

    }

}

//Se inicializa el controlador
$controller = new UserController();
$controller->handleRequest();

?>