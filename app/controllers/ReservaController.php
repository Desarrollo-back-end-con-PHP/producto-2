<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reserva;
use App\Models\Trayecto;
use App\Models\Hotel;

class ReservaController extends Controller
{
    protected $reservaModel;
    protected $trayectoModel;
    protected $hotelModel;

   public function __construct()
    {
        // Aseguramos que el usuario esté logueado
        $this->requiereLoginGuard();

        $this->reservaModel = new Reserva();
        $this->trayectoModel = new Trayecto();
        $this->hotelModel = new Hotel();
    }

    public function index()
{
    // Redirige a mostrarReservas o implementa aquí mismo
    $this->mostrarReservas();
}
   public function mostrarReservas()
{
    // Obtenemos el ID del usuario logueado
    $id_viajero = $_SESSION['user_id'] ?? 1;

    // Obtenemos todas las reservas de este usuario
    $reservas = $this->reservaModel->getReservasPorEmail($id_viajero);

    // Obtenemos los hoteles y trayectos para llenar el formulario
    $hoteles = $this->hotelModel->getAll();
    $trayectos = $this->trayectoModel->getAllTrayectos();

    // Cargamos la vista con todos los datos necesarios
    $this->loadView('reservas/crear_reserva', [
        'reservas' => $reservas,
        'hoteles' => $hoteles,
        'trayectos' => $trayectos,
        'mensaje' => $_GET['mensaje'] ?? null,
    ]);
}

    /**
     * Mostrar el formulario de creación de reserva
     */
    public function crear()
    {
        $trayectos = $this->trayectoModel->getAllTrayectos();
        $hoteles = $this->hotelModel->getAll();

        $this->loadView('reservas/crear_reserva', [
            'trayectos' => $trayectos,
            'hoteles' => $hoteles
        ]);
    }

    /**
     * Procesar el formulario y crear la reserva
     */
    public function crearReservaPost()
    {
        $this->requireMethod('POST');

        $id_tipo_reserva = $_POST['id_tipo_reserva'] ?? null;
        $id_destino = $_POST['id_destino'] ?? null;
        $num_viajeros = $_POST['num_viajeros'] ?? 1;
        $id_vehiculo = $_POST['id_vehiculo'] ?? 1;

        $fecha_entrada = $_POST['fecha_entrada'] ?? null;
        $hora_entrada = $_POST['hora_entrada'] ?? null;
        $numero_vuelo_entrada = $_POST['numero_vuelo_entrada'] ?? null;
        $origen_vuelo_entrada = $_POST['origen_vuelo_entrada'] ?? null;

        $fecha_vuelo_salida = $_POST['fecha_vuelo_salida'] ?? null;
        $hora_vuelo_salida = $_POST['hora_vuelo_salida'] ?? null;

  
        $exito = $this->reservaModel->crearReserva(
            $id_tipo_reserva,
            $id_destino,
            $fecha_entrada,
            $hora_entrada,
            $num_viajeros,
            $id_vehiculo,
            $numero_vuelo_entrada,
            $origen_vuelo_entrada,
            $fecha_vuelo_salida,
            $hora_vuelo_salida
        );

        if ($exito) {
            header("Location: " . APP_URL . "/perfil/listarReservas");
            exit;
        } else {
            // Enviar mensaje de error a la vista
            $trayectos = $this->trayectoModel->getAllTrayectos();
            $hoteles = $this->hotelModel->getAll();
            $this->loadView('reservas/crear_reserva', [
                'trayectos' => $trayectos,
                'hoteles' => $hoteles,
                'mensaje' => 'error_creacion'
            ]);
        }
    }


}
