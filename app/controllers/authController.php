<?php
//Controlador para manejar la autenticación de usuarios

namespace App\Controllers;

use App\Models\User;
use App\Core\Paths;
use App\Core\Session;
use App\Core\ValidationHelper;
use App\Config\Messages;

class AuthController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    //Método para mostrar el formulario de login
    public function showLogin() {

        //Capturamos si viene un error de autenticación desde la URL (?auth_error=1)
        //Lo convertimos a un booleano estricto (true o false)
        $authError = isset($_GET['auth_error']) && $_GET['auth_error'] === '1';

        if(Session::isLogged()) {
            redirect('admin_dashboard');
        }

        $title = 'Inicio de Sesión';
        $bodyClass = 'd-flex align-items-center min-vh-100 body-lr';
        require_once __DIR__ . '/../views/auth/login.php';

    }

    //Método para mostrar el formulario de registro
    public function showRegister() {

        if(Session::isLogged()) {
            redirect('admin_dashboard');
        }

        $title = 'Registro de Usuario';
        $bodyClass = 'd-flex align-items-center min-vh-100 body-lr';
        require_once __DIR__ . '/../views/auth/register.php';

    }

    //Método para el registro
    public function register(){

        //1. Primero recuperamos el arreglo con los datos limpios y validados
        //Ejecutamos la validación. Si algo falla, este método corta la ejecución internamente
        $data = $this->validateRegisterRequest();

        //2. Pasamos los datos al modelo leyéndolos directamente desde el arreglo $data
        $user = $this->userModel->register(
            $data['name'], 
            $data['surname'], 
            $data['email'], 
            $data['password'], 
            2, 
            1
        );

        //Si todo es correcto, se registra el usuario
        if($user){

            echo json_encode([
                'status' => 'success',
                'redirect' => Paths::to('login?success=1')
            ]);
            exit();

        //Si ha ocurrido un error, se muestra un mensaje genérico
        } else{

            echo json_encode([
                'status' => 'error',
                'message' => Messages::UNEXPECTED_ERR
            ]);
            exit();

        }

    }

    //Método para validar los datos
    private function validateRegisterRequest() { 

        //Limpieza de datos
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        //Arreglo de reglas de validación. Cada regla tiene una condición, un mensaje de error y el campo al que corresponde
        $rules = [
            [
                'condition' => empty($name) || empty($surname) || empty($email) || empty($password) || empty($passwordConfirm),
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'all'
            ],
            [
                'condition' => !preg_match("/^[a-zA-Z]+$/", $name),
                'message' => Messages::ERR_NAME_INVALID,
                'field' => 'name'
            ],
            [
                'condition' => !preg_match("/^[a-zA-Z]+$/", $surname),
                'message' => Messages::ERR_SURNAME_INVALID,
                'field' => 'surname'
            ],
            [
                'condition' => !filter_var($email, FILTER_VALIDATE_EMAIL),
                'message' => Messages::ERR_EMAIL_INVALID,
                'field' => 'email'
            ],
            [
                'condition' => !preg_match("/(?=.*[A-Z])(?=.*\d).{8,}$/", $password),
                'message' => Messages::ERR_PASS_INVALID,
                'field' => 'password'
            ],
            [
                'condition' => $password != $passwordConfirm,
                'message' => Messages::ERR_PASS_DOES_NOT_MATCH,
                'field' => 'passwords'
            ],
            [
                'condition' => $this->userModel->emailExists($email),
                'message' => Messages::ERR_ALREADY_EXISTS,
                'field' => 'email'
            ]
        ];

        //Delegamos la ejecución de reglas al helper central
        ValidationHelper::validate($rules);

        //Retornamos los datos al método principal si todo es correcto
        return [
            'name'     => $name,
            'surname'  => $surname,
            'email'    => $email,
            'password' => $password
        ];

     }

    //Método para el login
    public function login(){

        //Se sanitizan los datos
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        $rules = [
            [
                'condition' => empty($email) || empty($password),
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'all'
            ],
            [
                'condition' => !filter_var($email, FILTER_VALIDATE_EMAIL),
                'message' => Messages::ERR_EMAIL_INVALID,
                'field' => 'email'
            ]
        ];

        ValidationHelper::validate($rules);

        $user = $this->userModel->login($email, $password);

        //Si todo es correcto, se intenta iniciar sesión
        if($user) {

            Session::regenerate(); //Previene session fixation
            Session::setUser([
                'id' => $user['id'],
                'name' => $user['nombre']
            ]);

            echo json_encode([
                'status' => 'success',
                'redirect' => Paths::to('admin_dashboard')
            ]);
            exit();

        } else{

            //Si el inicio de sesión falla, se muestra el mensaje de error
            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_INCORRECT_CREDENTIALS,
                'field' => 'all'
            ]);
            exit();
    }

    }

    //Método para cerrar la sesión
    public function logout(){

        Session::logout();
        Session::destroy();
        redirect('login');

    }

}

?>
