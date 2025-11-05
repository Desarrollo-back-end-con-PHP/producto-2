<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reserva;

class ReservaController extends Controller
{
    private $reservaModel;

    public function __construct()
    {
        //Se llama al constructor padre (Controller)
        parent::__construct();

        //si el usuario NO esta logeado se redirige al login.
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

        $this->reservaModel = new Reserva($this->db);
    }

    /**
     * Muestra la lista de todas las reservas del usuario particular.
     */
    public function mostrarPanelParticular()
    {
        // 1. Obtener el email del usuario logeado desde la sesión
        $email_usuario = $_SESSION['email_viajero'] ?? 'test@example.com';

        // 2. Obtener los datos del modelo
        // Esto trae el listado de reservas con el campo 'origen_reserva'
        $reservas = $this->reservaModel->obtenerReservasPorEmail($email_usuario);

        // 3. Cargar la vista usando loadView
        $this->loadView('reservas/panel_particular_view', [
            'reservas' => $reservas,
            'email' => $email_usuario,
            'mensaje' => $_GET['mensaje'] ?? null // Para mensajes de éxito o error
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva reserva.
     * (Necesitarás un modelo que traiga hoteles/destinos/vehículos para el formulario real)
     */
    public function mostrarFormularioReserva()
    {
        // Aquí podrías obtener datos auxiliares (hoteles, tipos de vehículos) si los tuvieras
        // $hoteles = $this->hotelModel->obtenerHoteles();

        // Carga el formulario de la vista
        $this->loadView('reservas/crear_reserva_view', [
            // 'hoteles' => $hoteles
        ]);
    }

    /**
     * Procesa la creación de una nueva reserva enviada desde el formulario.
     */
    public function crearReserva()
    {
        // 1. Requerir que la petición sea POST
        $this->requireMethod('POST');

        // 2. Recoger datos
        $email_cliente = $_SESSION['email_viajero'] ?? 'test@example.com';

        // ... (Recoger todos los campos del formulario como en el controlador anterior)
        // Ejemplo de recolección:
        $id_tipo_reserva = $_POST['id_tipo_reserva'] ?? 1;
        $id_destino = $_POST['id_destino'] ?? 5;
        $num_viajeros = $_POST['num_viajeros'] ?? 1;

        // Para simplificar, asumimos que los datos están validados y recogidos correctamente:
        $fecha_entrada = $_POST['fecha_entrada'] ?? null;
        $hora_entrada = $_POST['hora_entrada'] ?? null;
        $numero_vuelo_entrada = $_POST['numero_vuelo_entrada'] ?? null;
        $origen_vuelo_entrada = $_POST['origen_vuelo_entrada'] ?? null;
        $fecha_vuelo_salida = $_POST['fecha_vuelo_salida'] ?? null;
        $hora_vuelo_salida = $_POST['hora_vuelo_salida'] ?? null;
        $id_vehiculo = 1;

        // 3. Llamar al modelo para insertar
        $exito = $this->reservaModel->crearReserva(
            $email_cliente,
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

        // 4. Redirigir
        if ($exito) {
            header('Location: /panel-usuario?mensaje=reserva_creada');
        } else {
            header('Location: /reserva/nueva?mensaje=error_creacion');
        }
        exit();
    }
}
