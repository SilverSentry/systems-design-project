<?php

namespace App\Controllers;

use App\Core\Session;

class AdminController {

    public function index() {

        if(!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();
        $title = 'Panel de Administración';
        $bodyClass = 'layout-footer';
        $extraScripts = ['js/sidebar.js'];

        require_once __DIR__ . '/../views/admin_dashboard.php';
    }

}

?>