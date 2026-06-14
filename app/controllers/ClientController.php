<?php
//Controlador para la gestión de clientes

namespace App\Controllers;

use App\Models\Client;
use App\Models\ClientAntecedent;
use App\Config\Connection;
use App\Core\Paths;
use App\Config\Messages;
use App\Core\Session;
use DateTime;
use App\Core\ValidationHelper;

class ClientController
{

    public $clientModel;
    public $clientAntecedentModel;

    public function __construct()
    {
        $this->clientModel = new Client();
        $this->clientAntecedentModel = new ClientAntecedent();
    }

    //Método para mostrar la vista de clientes
    public function index()
    {

        //1. Validamos la sesión
        if (!Session::isLogged()) {
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
            if ($dateValue && $dateValue !== '0000-00-00' && $dateValue !== '') {

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

    //Método para mostrar la vista de creación de clientes
    public function create()
    {

        if (!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();

        $title = 'Crear cliente';
        $bodyClass = 'layout-footer';

        $extraScripts = ['js/sidebar.js', 'js/client-create.js'];

        $roleBadges = [
            'cliente' => 'text-bg-secondary'
        ];

        require_once __DIR__ . '/../views/clients/create.php';
    }

    public function register()
    {

        //Validamos y limpiamos los datos
        $data = $this->validateClientRequest();

        //Obtenemos la conexión para manejar la transacción entre ambos modelos
        $db = Connection::getInstance()->getConnection();

        try {

            $db->beginTransaction();

            //1. Registrar el cliente
            $clientId = $this->clientModel->create(
                $data['name'],
                $data['surname'],
                $data['phone'],
                $data['dni'],
                $data['birthdate'],
                $data['gender']
            );

            //2. Registrar los antecedentes de BioPortal (si existen)
            if (!empty($_POST['antecedentes']) && is_array($_POST['antecedentes'])) {
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
                'message' => Messages::SUCCESS_CLIENT_CREATED,
                'redirect' => Paths::to('clients/create')
            ]);
            exit();
        } catch (\Exception $e) {
            $db->rollBack();

            echo json_encode([
                'status' => 'error',
                'message' => 'Error crítico en la base de datos al procesar el registro',
                'debug' => $e->getMessage()
            ]);
            exit();
        }
    }

    //Validador privado para las solicitudes de cliente
    private function validateClientRequest()
    {

        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $dni = trim($_POST['dni'] ?? '');
        $birthdate = trim($_POST['birthdate'] ?? '');
        $gender = trim($_POST['gender'] ?? '');

        $rules = [
            [
                'condition' => empty($name) || empty($surname) || empty($phone) || empty($dni) || empty($birthdate) || empty($gender),
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
                'condition' => !preg_match("/^\\d{11}$/", $phone),
                'message' => Messages::ERR_PHONE_INVALID,
                'field' => 'phone'
            ],
            [
                'condition' => !preg_match("/^\\d{7,8}$/", $dni),
                'message' => Messages::ERR_DNI_INVALID,
                'field' => 'dni'
            ],
            [
                'condition' => !strtotime($birthdate),
                'message' => Messages::ERR_BIRTHDATE_INVALID,
                'field' => 'birthdate'
            ],
            [
                'condition' => !in_array($gender, ['Masculino', 'Femenino', 'Otro']),
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'gender'
            ]
        ];

        //Delegamos la respuesta/error al helper central
        ValidationHelper::validate($rules);

        return [
            'name' => $name,
            'surname' => $surname,
            'phone' => $phone,
            'dni' => $dni,
            'birthdate' => $birthdate,
            'gender' => $gender
        ];
    }
}
