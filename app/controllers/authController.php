<?php
//Controlador para manejar las peticiones del usuario

class AuthController {

    private $userModel;

    public function __construct(){

        $database = new Connection();
        $db = $database->getConnection();
        $this->userModel = new UserModel($db);

    }

    //Método para manejar las peticiones mediante POST
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

    //Método para el registro
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
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'all' //Identificador del input
            ]);
            exit();

        //Validación para el nombre, que sean solo letras
        } elseif(!preg_match("/^[a-zA-z]+$/", $name)){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_NAME_INVALID,
                'field' => 'name'
            ]);
            exit(); 

        //Validación para el apellido, que sea solo letras
        } elseif(!preg_match("/^[a-zA-z]+$/", $surname)){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_SURNAME_INVALID,
                'field' => 'surname'
            ]);
            exit(); 

        //Validación para el correo electrónico
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_EMAIL_INVALID,
                'field' => 'email'
            ]);
            exit(); 

        //Validación para la contraseña para que cumpla los requisitos
        } elseif(!preg_match("/(?=.*[A-Z])(?=.*\d).{8,}$/",$password)){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_PASS_INVALID,
                'field' => 'password'
            ]);
            exit();

        //Validación para que las contraseñas coincidan
        } elseif($password != $passwordConfirm){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_PASS_DOES_NOT_MATCH,
                'field' => 'passwords'
            ]);
            exit();

        //Se verifica que el usuario no exista en la base de datos
        //Se usa un método que viene de userModel que verifica que el correo no exista en la BD
        } elseif($this->userModel->emailExists($email)){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_ALREADY_EXISTS,
                'field' => 'email'
            ]);
            exit();

        } else{

            $user = $this->userModel->register($name, $surname, $email, $password);

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

    }

    //Método para el login
    private function login(){

        //Se sanitizan los datos
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        //Antes de iniciar sesión, se verifica que los campos no estén vacíos
        if(empty($email) || empty($password)){

           echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'all'
            ]);
            exit();

        //Se valida que el correo ingresado no tenga un formato incorrecto
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            echo json_encode([
                'status' => 'error',
                'message' => Messages::ERR_EMAIL_INVALID,
                'field' => 'email'
            ]);
            exit();
            
        }

        $user = $this->userModel->login($email, $password);

        //Si todo es correcto, se intenta iniciar sesión
        if($user) {

            SessionManager::regenerate(); //Previene session fixation
            SessionManager::setUser([
                'id' => $user['id_user'],
                'name' => $user['name']
            ]);

            echo json_encode([
                'status' => 'success',
                'redirect' => Paths::to('AdminDashboard')
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

        SessionManager::logout();
        SessionManager::destroy();
        redirect('login');

    }

}

?>
