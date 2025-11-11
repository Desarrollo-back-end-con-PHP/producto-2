<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Models\Hotel;

class AdminController extends Controller
{
    private $userModel;
    protected $hotelModel;

    public function __construct()
    {
        //Se llama al constructor padre (Controller)
        parent::__construct();

        $this->requiereLoginGuard();

        $this->requiereAdminGuard(); //Comprueba que el usuario logeado es el admin (con correo admin@islatransfers.com)

        $this->userModel = new Usuario();
        $this->hotelModel = new Hotel();
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

    public function hoteles()
    {
        $hoteles = $this->hotelModel->getAll();

        $data = [
            'title' => 'Admin - Gestionar Hoteles',
            'hoteles' => $hoteles
        ];

        $this->loadView('admin/hoteles', $data);
    }

    public function crearHotelPost()
    {
        $this->requireMethod('POST');

        $nombre_usuario = $_POST['usuario'] ?? null;
        $password = $_POST['password'] ?? null;
        $comision = $_POST['comision'] ?? 0;

        if (empty($nombre_usuario) || empty($password)) {
            header('Location: ' . APP_URL . '/admin/hoteles?error=campos_vacios');
            exit;
        }

        $exito = $this->hotelModel->crearHotel($nombre_usuario, $password, $comision);

        if ($exito) {
            header('Location: ' . APP_URL . '/admin/hoteles?exito=creado');
            exit;
        } else {
            header('Location: ' . APP_URL . '/admin/hoteles?error=creacion');
            exit;
        }
    }

    /** ------------------- METODOS DE LA API ----------------------- */

    public function crearHotelApi()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Método no permitido
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Se requiere POST.']);
            exit;
        }

        $nombre_usuario = $_POST['usuario'] ?? null;
        $password = $_POST['password'] ?? null;
        $comision = $_POST['comision'] ?? 0;


        if (empty($nombre_usuario) || empty($password)) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Campos vacíos o incorrectos.']);
            exit;
        }

        $exito = $this->hotelModel->crearHotel($nombre_usuario, $password, $comision);

        if ($exito) {
            http_response_code(201); // 201 Created (más específico)
            echo json_encode(['status' => 'ok', 'message' => '¡Hotel creado con éxito!']);
            exit;
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el hotel (posiblemente duplicado).']);
            exit;
        }
    }
}
