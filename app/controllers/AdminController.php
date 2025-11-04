<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Usuario;

class AdminController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        //Se llama al constructor padre (BaseController)
        parent::__construct();

        // TODO: Añadir seguridad para que solo admins puedan acceder
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

        $this->userModel = new Usuario($this->db);
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard'
        ];
        $this->loadView('admin/dashboard', $data);
    }

    public function reservations()
    {
        $data = [
            'title' => 'Admin - Gestionar Reservas'
        ];
        $this->loadView('admin/reservations', $data);
    }


    public function calendar()
    {
        $data = [
            'title' => 'Admin - Calendario'
        ];
        $this->loadView('admin/calendar', $data);
    }
}
