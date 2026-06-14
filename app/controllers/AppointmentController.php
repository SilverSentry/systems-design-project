<?php
//Controlador para manejar la lógica relacionada con las citas

namespace App\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Core\Session;
use App\Core\Paths;
use App\Config\Messages;

class AppointmentController
{

    public $appointmentModel;
    public $clientModel;
    public $serviceModel;

    public function __construct()
    {
        $this->appointmentModel = new Appointment();
        $this->clientModel = new Client();
        $this->serviceModel = new Service();
    }

    //Método para mostrar la vista de citas
    public function index()
    {

        if (!Session::isLogged()) {
            redirect('login');
        }

        $appointments = $this->appointmentModel->getAll();

        //Rutas para acciones
        $urlCreate = Paths::to('appointments/create');

        $title = 'Panel de Citas';
        $bodyClass = 'layout-footer';

        $extraScripts = [
            'DataTables/jquery-3.7.0.min.js',
            'DataTables/jquery.dataTables.min.js',
            'DataTables/dataTables.bootstrap5.min.js',
            'js/sidebar.js',
            'js/appointments.js'
        ];

        $statusBadges = [
            'pendiente' => 'text-bg-warning',
            'asistida' => 'text-bg-success',
            'cancelada' => 'text-bg-danger'
        ];

        require_once __DIR__ . '/../views/appointments/index.php';
    }

    //Método para mostrar la vista de agendar citas
    public function create()
    {

        if (!Session::isLogged()) {
            redirect('login');
        }

        $clients = $this->clientModel->read();
        $services = $this->serviceModel->getAll();

        $title = 'Agendar cita';
        $bodyClass = 'layout-footer';

        $extraScripts = ['js/sidebar.js', 'js/appointment-create.js'];

        require_once __DIR__ . '/../views/appointments/create.php';
    }

    //Método para procesar el formulario de agendar cita
    public function schedule()
    {
        if (!Session::isLogged()) {
            redirect('login');
        }

        $data = $this->validateScheduleRequest();

        $selectedServices = $this->serviceModel->getByIds($data['service_ids']);
        if (count($selectedServices) !== count($data['service_ids'])) {
            redirect('appointments/create');
        }

        $serviceNames = array_column($selectedServices, 'nombre');
        $notesList = ['Servicios: ' . implode(', ', $serviceNames)];

        if (!empty($data['notes'])) {
            $notesList[] = 'Notas: ' . $data['notes'];
        }

        $totalAmount = is_numeric($data['amount']) ? floatval($data['amount']) : 0.0;
        $employeeId = Session::getUser()['id'] ?? 0;

        $this->appointmentModel->create(
            $data['client_id'],
            $employeeId,
            $data['date'],
            $data['time_start'],
            $data['time_end'],
            $totalAmount,
            1,
            implode(' | ', $notesList)
        );

        redirect('appointments');
    }

    //Función para validar los datos del formulario de agendar cita
    private function validateScheduleRequest(): array
    {
        $clientId = intval($_POST['client_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $startTime = trim($_POST['time_start'] ?? '');
        $endTime = trim($_POST['time_end'] ?? '');
        $amount = trim($_POST['amount'] ?? '0');
        $notes = trim($_POST['notes'] ?? '');
        $serviceIdsRaw = trim($_POST['service_ids'] ?? '');

        $serviceIds = [];
        if ($serviceIdsRaw !== '') {
            $serviceIds = array_filter(array_map('intval', array_map('trim', explode(',', $serviceIdsRaw))));
        }

        $rules = [
            [
                'condition' => $clientId <= 0 || empty($date) || empty($startTime) || empty($endTime) || $serviceIdsRaw === '',
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'all'
            ],
            [
                'condition' => !strtotime($date),
                'message' => Messages::ERR_INVALID_DATE,
                'field' => 'date'
            ],
            [
                'condition' => !preg_match('/^\d{2}:\d{2}$/', $startTime),
                'message' => Messages::ERR_INVALID_TIME,
                'field' => 'time_start'
            ],
            [
                'condition' => !preg_match('/^\d{2}:\d{2}$/', $endTime),
                'message' => Messages::ERR_INVALID_TIME,
                'field' => 'time_end'
            ],
            [
                'condition' => strtotime($date . ' ' . $startTime) >= strtotime($date . ' ' . $endTime),
                'message' => Messages::ERR_INVALID_TIME,
                'field' => 'time_end'
            ],
            [
                'condition' => empty($serviceIds),
                'message' => Messages::ERR_INVALID_SERVICE_SELECTION,
                'field' => 'service_ids'
            ]
        ];

        $this->validateRules($rules);

        return [
            'client_id' => $clientId,
            'date' => $date,
            'time_start' => $startTime,
            'time_end' => $endTime,
            'amount' => $amount,
            'notes' => $notes,
            'service_ids' => $serviceIds
        ];
    }

    private function validateRules(array $rules): void
    {
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) {
                redirect('appointments/create');
            }
        }
    }
}
