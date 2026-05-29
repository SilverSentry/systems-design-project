<?php

namespace App\Controllers;

use App\Models\Client;
use App\Models\ClientAntecedent;
use App\Config\Connection;
use App\Core\Paths;
use App\Config\Messages;
use App\Core\Session;
use DateTime;

class ClientController {

    public $clientModel;
    public $clientAntecedentModel;

    public function __construct() {
        $this->clientModel = new Client();
        $this->clientAntecedentModel = new ClientAntecedent();
    }

    //Método para mostrar la vista de clientes
    public function index() {

        //1. Validamos la sesión
        if(!Session::isLogged()) {
            \redirect('login');
        }

        $user = Session::getUser();
        $rawClients = $this->clientModel->read(); //Trae los datos asociativos de la DB

        //2. Rutas para acciones
        $urlCreate = Paths::to('clients/create');
        $urlEdit = Paths::to('clients/edit');

        //3. Creamos un array limpio para las vistas
        $clients = [];

        //4. Recorremos cada cliente para agregarle la edad calculada y cualquier otro dato que necesitemos para mostrar en la vista, evitando mezclar lógica de presentación con la consulta original
        foreach ($rawClients as $client) {

        $dateValue = $client['fecha_nacimiento'] ?? null;

        //Varible con valor por defecto para mostrar en caso de que la fecha esté vacía, evitando que el cálculo falle
        $ageText = 'S/D';

         //Cálculo de la edad basado en la fecha de nacimiento
        if($dateValue && $dateValue !== '0000-00-00' && $dateValue !== '') {

            $birthdate = new DateTime($dateValue);
            $today = new DateTime();                
            $diff = $today->diff($birthdate);                
            $ageText = $diff->y . ' años';

        }

        //Inyectamos la edad calculada
        $client['edad'] = $ageText;

        //Guardamos el cliente completo (que ya incluye su $client['id'])
        $clients[] = $client;
    }

    //4. Configuramos metadatos de la vista
    $title = 'Clientes';
    $bodyClass = 'layout-footer';
    $extraScripts = [
            'DataTables/jquery-3.7.0.min.js', 
            'DataTables/jquery.dataTables.min.js', 
            'DataTables/dataTables.bootstrap5.min.js', 
            'js/sidebar.js', 
            'js/clients.js'
    ];

    require_once __DIR__ . '/../views/clients/index.php';

    }

    public function create() {

        if(!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();
        $title = 'Crear cliente';
        $bodyClass = 'layout-footer';
        $extraScripts = ['js/sidebar.js', 'js/client-create.js'];

        require_once __DIR__ . '/../views/clients/create.php';
    }

    public function register() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name']);
            $surname = trim($_POST['surname']);
            $phone = trim($_POST['phone']);
            $dni = trim($_POST['dni']);
            $birthdate = trim($_POST['birthdate']);
            $gender = trim($_POST['gender']);

            //Obtenemos la conexión para manejar la transacción entre ambos modelos
            $db = Connection::getInstance()->getConnection();

            //Aseguramos que la cabecera de respuesta siempre indique JSON
            header('Content-Type: application/json');

            /*
            Bloque para las validaciones de los campos del formulario
            Se valida que no estén vacíos, que el nombre y apellido sean solo letras, que el teléfono tenga un formato válido, que la fecha de nacimiento sea una fecha válida y que se haya seleccionado un género
            */
            if(empty($name) || empty($surname) || empty($phone) || empty($dni) || empty($birthdate) || empty($gender)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_EMPTY_FIELDS,
                    'field' => 'all'
                ]);
                exit();

            } elseif(!preg_match("/^[a-zA-z]+$/", $name)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_NAME_INVALID,
                    'field' => 'name'
                ]);
                exit(); 

            } elseif(!preg_match("/^[a-zA-z]+$/", $surname)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_SURNAME_INVALID,
                    'field' => 'surname'
                ]);
                exit();

            } elseif(!preg_match("/^\d{11}$/", $phone)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_PHONE_INVALID,
                    'field' => 'phone'
                ]);
                exit();
            }elseif(!preg_match("/^\d{7,8}$/", $dni)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_DNI_INVALID,
                    'field' => 'dni'
                ]);
                exit();

            } elseif(!strtotime($birthdate)){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_BIRTHDATE_INVALID,
                    'field' => 'birthdate'
                ]);
                exit();

            } elseif(!in_array($gender, ['Masculino', 'Femenino', 'Otro'])){

                echo json_encode([
                    'status' => 'error',
                    'message' => Messages::ERR_EMPTY_FIELDS,
                    'field' => 'gender'
                ]);
                exit();

            }

            //Se utiliza un bloque try-catch para asegurar que ambos modelos se ejecuten correctamente o ninguno lo haga, manteniendo la integridad de los datos
            try {

                //Iniciamos una transacción para asegurar la integridad de los datos entre ambos modelos
                $db->beginTransaction();

                //1. Registrar el cliente
                $clientId = $this->clientModel->create(
                    $name,
                    $surname,
                    $phone,
                    $dni,
                    $birthdate,
                    $gender
                );

                //2. Registrar los antecedentes de BioPortal
                if(!empty($_POST['antecedentes']) && is_array($_POST['antecedentes'])) {
                    foreach ($_POST['antecedentes'] as $antecedente) {
                        
                        $this->clientAntecedentModel->create(
                            $clientId,
                            intval($antecedente['tipo_id']),
                            $antecedente['concept_id'], //CUI / Notation de BioPortal
                            htmlspecialchars($antecedente['term_name']),
                            'Declarado en el registro inicial de BioPortal.'
                        );
                    }
                }

                //Si todo salió bien en ambos modelos, confirmamos los cambios
                $db->commit();
                
                echo json_encode([
                    'status' => 'success',
                    'redirect' => Paths::to('clients/create?success')
                ]);
                exit();

            } catch (\Exception $e) {
                //Si cualquiera de los dos modelos falla, se cancela todo
                $db->rollBack();

                //Respondemos un JSON estructurado para el catch del Javascript
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error crítico en la base de datos al procesar el registro.',
                    'debug' => $e->getMessage() //Opcional: solo para guiarte en desarrollo
                ]);
                exit();
            }

        }

    }

}


?>