<?php
//Controlador para manejar la lógica del dashboard, tanto para administradores como para empleados

namespace App\Controllers;

use App\Core\Session;
use App\Models\Appointment;
use App\Models\Service;

class DashboardController
{

    /**
     * Método para mostrar el dashboard
     * Este método se encarga de verificar si el usuario está autenticado y redirigirlo al dashboard correspondiente según su rol (administrador o empleado)
     * También carga la vista adecuada para cada tipo de usuario
     */
    public function index()
    {

        if (!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();
        if (!$user) {
            redirect('login');
        }

        $roleId = $user['roleId'] ?? $user['id_rol'] ?? 2;
        $isAdmin = in_array($roleId, [1, 4], true);

        //$CurrentPath se utiliza para redirigir al usuario a la ruta correcta si intenta acceder a la ruta del otro tipo de usuario
        $currentPath = trim($_GET['p'] ?? '', '/');

        if ($currentPath === 'admin_dashboard' && !$isAdmin) {
            redirect('dashboard');
            return;
        }

        if ($currentPath === 'dashboard' && $isAdmin) {
            redirect('admin_dashboard');
            return;
        }

        $title = $isAdmin ? 'Panel de Administración' : 'Panel de Empleados';
        $bodyClass = 'layout-footer';
        $extraScripts = ['js/sidebar.js', 'Chart.js/chart.js', 'js/dashboard.js'];

        $clientsAttended = 0;
        $totalRevenue = 0;
        $appointmentsPending = 0;
        $activeServices = 0;
        $todayAppointments = 0;

        if ($isAdmin) {
            $appointmentModel = new Appointment();
            $clientsAttended = $appointmentModel->countDistinctClients();
            $totalRevenue = $appointmentModel->sumRevenue();
            $appointmentsPending = $appointmentModel->countByState(1);
            $todayAppointments = $appointmentModel->countTodayAppointments();

            $serviceModel = new Service();
            $activeServices = $serviceModel->countActive();
        }

        if ($isAdmin) {
            require_once __DIR__ . '/../views/dashboard/admin.php';
        } else {
            require_once __DIR__ . '/../views/dashboard/index.php';
        }
    }
}
