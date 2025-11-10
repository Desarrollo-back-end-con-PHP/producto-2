<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ProfileMessageHelper;
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
    public function crearReservaPost() //POST
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
            $_SESSION['mensaje_exito'] = ProfileMessageHelper::EXITO_RESERVA; //Separar responsabilidades
            header("Location: " . APP_URL . "/reserva/misreservas");
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

    public function misreservas()
    {
        $user_email = $_SESSION['user_email'];
        $user_id    = $_SESSION['user_id'];

        if ($user_email === 'admin@islatransfers.com') {
            $reservas = $this->reservaModel->getTodasReservas();
        } else {
            $reservas = $this->reservaModel->getReservasPorEmail($user_email);
        }

        $hoteles = $this->hotelModel->getAll();
        $hotelesMap = [];
        foreach ($hoteles as $hotel) {
            $hotelesMap[$hotel['id_hotel']] = $hotel['usuario'];
        }


        $reservasAdmin = $this->reservaModel->getReservasAdminIds();
        $reservasAdminMap = array_flip($reservasAdmin);

        // Cargamos la vista con TODOS los datos
        $this->loadView('user/mis_reservas', [
            'reservas'         => $reservas,
            'hotelesMap'       => $hotelesMap,
            'user_id'          => $user_id,
            'reservasAdminMap' => $reservasAdminMap
        ]);
    }

    public function editar($id_reserva) //GET
    {
        //se usa el modelo para obtener los datos de la reserva
        $reserva = $this->reservaModel->getReservaPorId($id_reserva);

        //comprueba que es admin o es el dueño de la reserva
        if (!$reserva) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=no_existe");
            exit;
        }
        $esAdmin = $this->isAdminLoggedIn();
        $esDueño = ($reserva['email_cliente'] === $_SESSION['user_email']);

        if (!$esAdmin && !$esDueño) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=no_autorizado");
            exit;
        }

        // se obtienen los datos
        $hoteles = $this->hotelModel->getAll();
        $trayectos = $this->trayectoModel->getAllTrayectos();

        //se carga la vista dle formulario de edición
        $this->loadView('reservas/editar_reserva', [
            'reserva' => $reserva,
            'hoteles' => $hoteles,
            'trayectos' => $trayectos,
            'mensaje' => $_GET['mensaje'] ?? null
        ]);
    }

    public function editarPost($id_reserva) //POST
    {

        //los datos llegan por POST
        $this->requireMethod('POST');

        $reserva = $this->reservaModel->getReservaPorId($id_reserva);

        $esAdmin = $this->isAdminLoggedIn();
        $esDueño = ($reserva && $reserva['email_cliente'] === $_SESSION['user_email']);

        if (!$esAdmin && !$esDueño) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=no_autorizado");
            exit;
        }

        //recoge los datos del formulario
        $datos = $_POST;
        //se llama al metodo actualizarReserva del modelo para actualizar la base de datos
        $exito = $this->reservaModel->actualizarReserva($id_reserva, $datos);

        if ($exito) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=actualizado_ok");
            exit;
        } else {
            header("Location: " . APP_URL . "/reserva/editar/" . $id_reserva . "?mensaje=error_actualizar");
            exit;
        }
    }

    public function cancelar($id_reserva) // POST
    {
        $this->requireMethod('POST');

        //se obtinee la reserva
        $reserva = $this->reservaModel->getReservaPorId($id_reserva);

        //comprobamos si la reserva existe
        if (!$reserva) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=no_existe");
            exit;
        }

        //comprobar los permisos
        $esAdmin = $this->isAdminLoggedIn();
        $esDueño = ($reserva && $reserva['email_cliente'] === $_SESSION['user_email']);

        if (!$esAdmin && !$esDueño) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=no_autorizado");
            exit;
        }

        //se llama al método cancelar de Reserva
        $exito = $this->reservaModel->cancelarReserva($id_reserva);

        if ($exito) {
            header("Location: " . APP_URL . "/reserva/misreservas?mensaje=cancelado_ok");
            exit;
        } else {
            header("Location: " . APP_URL . "/reserva/editar/" . $id_reserva . "?mensaje=error_cancelar");
            exit;
        }
    }
}
