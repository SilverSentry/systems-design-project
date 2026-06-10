<?php

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

        require_once __DIR__ . '/../views/employees/index.php';
    }
}
