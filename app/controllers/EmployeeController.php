<?php
//Controlador para manejar la lógica relacionada con empleados (usuarios del sistema)

namespace App\Controllers;

use App\Config\Messages;
use App\Models\User;
use App\Core\Session;
use App\Core\ValidationHelper;

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
        $extraScripts = ['js/sidebar.js', 'js/register.js'];

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
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $passwordConfirm = isset($_POST['passwordConfirm']) ? trim($_POST['passwordConfirm']) : '';

        if ($id <= 0 || $name === '' || $surname === '' || $email === '') {
            redirect('employees');
        }

        $rules = [];

        if ($password !== '' || $passwordConfirm !== '') {
            $rules[] = [
                'condition' => empty($password) || empty($passwordConfirm),
                'message' => Messages::ERR_EMPTY_FIELDS,
                'field' => 'password'
            ];
            $rules[] = [
                'condition' => !preg_match("/(?=.*[A-Z])(?=.*\d).{8,}$/", $password),
                'message' => Messages::ERR_PASS_INVALID,
                'field' => 'password'
            ];
            $rules[] = [
                'condition' => $password !== $passwordConfirm,
                'message' => Messages::ERR_PASS_DOES_NOT_MATCH,
                'field' => 'passwords'
            ];
        }

        ValidationHelper::validate($rules, 'employees/edit?id=' . $id, 'employee_error');

        $success = $this->userModel->update($id, $name, $surname, $email, $state, $password !== '' ? $password : null);

        redirect('employees');
    }
}
