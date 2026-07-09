<?php
//Controlador para manejar la lógica relacionada con empleados (usuarios del sistema)

namespace App\Controllers;

use App\Models\User;
use App\Core\Session;

class EmployeeController
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {

        if (!Session::isLogged()) {
            redirect('login');
        }

        if (Session::isEmployee()) {
            redirect('dashboard');
        }

        $user = Session::getUser();
        $users = $this->userModel->getAll();

        $title = 'Panel de Empleados';
        $bodyClass = 'layout-footer';

        $extraScripts = [
            'DataTables/jquery-3.7.0.min.js',
            'DataTables/jquery.dataTables.min.js',
            'DataTables/dataTables.bootstrap5.min.js',
            'js/sidebar.js',
            'js/employees.js'
        ];

        $roleBadges = [
            'admin' => 'text-bg-primary',
            'superadmin' => 'text-bg-dark',
            'usuario' => 'text-bg-info',
            'cliente' => 'text-bg-secondary',
        ];

        $statusBadges = [
            'activo' => 'text-bg-success',
            'inactivo' => 'text-bg-danger',
        ];

        require_once __DIR__ . '/../views/employees/index.php';
    }

    /**
     * Show edit form for a single employee.
     *
     * @return void
     */
    public function edit(): void
    {
        if (!Session::isLogged()) {
            redirect('login');
        }

        if (Session::isEmployee()) {
            redirect('dashboard');
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            redirect('employees');
        }

        $employee = $this->userModel->getById($id);
        if (!$employee) {
            redirect('employees');
        }

        $title = 'Editar Empleado';
        $bodyClass = 'layout-footer';
        $extraScripts = ['js/sidebar.js'];

        require_once __DIR__ . '/../views/employees/edit.php';
    }

    /**
     * Update employee data.
     *
     * @return void
     */
    public function update(): void
    {
        if (!Session::isLogged()) {
            redirect('login');
        }

        if (Session::isEmployee()) {
            redirect('dashboard');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $surname = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $state = isset($_POST['estado']) ? intval($_POST['estado']) : 1;

        if ($id <= 0 || $name === '' || $surname === '' || $email === '') {
            redirect('employees');
        }

        $success = $this->userModel->update($id, $name, $surname, $email, $state);

        redirect('employees');
    }
}
