<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends BaseController {

    public function __construct() {

        // TODO: Añadir seguridad para que solo admins puedan acceder
        //if (!isset($_SESSION['user_id'])) {
        //    header('Location: ' . APP_URL . '/auth/login');
        //    exit;
        //}
        
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $data = [
            'title' => 'Admin Dashboard'
        ];
        $this->loadView('admin/dashboard', $data);
    }

    public function reservations() {
        $data = [
            'title' => 'Admin - Gestionar Reservas'
        ];
        $this->loadView('admin/reservations', $data);
    }


    public function calendar() {
         $data = [
            'title' => 'Admin - Calendario'
         ];
        $this->loadView('admin/calendar', $data);
    }
}
?>

