<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class AdminController extends Controller
{
    private $userModel;

    public function __construct()
    {
        //Se llama al constructor padre (Controller)
        parent::__construct();

        $this->requiereLoginGuard();

        $this->requiereAdminGuard(); //Comprueba que el usuario logeado es el admin (con correo admin@islatransfers.com)

        $this->userModel = new Usuario();
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
