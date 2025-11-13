<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\ProfileMessageHelper;
use App\Models\Reserva;
use App\Models\Trayecto;
use App\Models\Hotel;
use App\Models\Usuario;

class ReservaController extends Controller
{
    protected $reservaModel;
    protected $trayectoModel;
    protected $hotelModel;
    protected $userModel;

    public function __construct()
    {
        // Aseguramos que el usuario esté logueado
        $this->requiereLoginGuard();

        $this->reservaModel = new Reserva();
        $this->trayectoModel = new Trayecto();
        $this->hotelModel = new Hotel();
        $this->userModel = new Usuario();
    }

    public function index()
    {
        $this->crear();
    }

    public function crear()
    {
        $trayectos = $this->trayectoModel->getAllTrayectos();
        $hoteles = $this->hotelModel->getAll();

        $mensaje = $_SESSION['mensaje_error'] ?? null;

        if ($mensaje) {
            unset($_SESSION['mensaje_error']);
        }

        $this->loadView('reservas/crear_reserva', [
            'trayectos' => $trayectos,
            'hoteles' => $hoteles,
            'mensaje' => $mensaje
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

        $numero_vuelo_salida = $_POST['numero_vuelo_salida'] ?? null;
        $hora_recogida = $_POST['hora_recogida'] ?? null;

        $email_cliente = null;
        $codigo_admin = $_POST['codigo_admin'] ?? null;
        $perfil_completo = true; //asumimos que el perfil esta completo


        if ($this->isAdminLoggedIn()) {
            $email_cliente = $_POST['email_cliente'] ?? null;
            $codigo_admin = $_POST['codigo_admin'] ?? null;

            if (!$email_cliente || !$codigo_admin) {
                $_SESSION['mensaje_error'] = "Debes indicar email y código admin.";
                header("Location: " . APP_URL . "/reserva/crear");
                exit;
            }
            if (!$this->userModel->isProfileComplete($email_cliente)) {
                $_SESSION['mensaje_error'] = "El perfil del cliente ($email_cliente) está incompleto. Pida al cliente que rellene sus datos en 'Mi Perfil' antes de reservar.";
                header("Location: " . APP_URL . "/reserva/crear");
                exit;
            }
        } else {
            $email_cliente = $_SESSION['user_email'];
            if (!$this->userModel->isProfileComplete($email_cliente)) {
                $perfil_completo = false;
            }
        }

        if (!$perfil_completo) {
            // 1. Guardamos TODOS los datos del formulario en la sesión
            $_SESSION['reserva_temporal'] = $_POST;

            // guardamos mensaje
            $_SESSION['mensaje'] = 'PROFILE_REQUIRED';

            // 2. Guardamos el email que necesita ser actualizado
            $_SESSION['email_para_actualizar'] = $email_cliente;

            // 3. Redirigimos a la página de perfil
            header("Location: " . APP_URL . "/usuario/mostrarPerfil?causa=reserva");
            exit;
        }

        $localizador = $this->reservaModel->crearReserva(
            $id_tipo_reserva,
            $id_destino,
            $fecha_entrada,
            $hora_entrada,
            $num_viajeros,
            $id_vehiculo,
            $numero_vuelo_entrada,
            $origen_vuelo_entrada,
            $fecha_vuelo_salida,
            $hora_vuelo_salida,
            $email_cliente,
            $numero_vuelo_salida,
            $hora_recogida

        );

        if ($localizador) {
            if ($this->isAdminLoggedIn()) { // Añadir también el código admin a la comprobación?
                $id_reserva = $this->reservaModel->getUltimaReservaId();
                $this->reservaModel->guardarReservaAdmin($id_reserva, $codigo_admin);
            }

            $_SESSION['mensaje_exito'] = ProfileMessageHelper::EXITO_RESERVA;
            header("Location: " . APP_URL . "/reserva/misreservas");
            exit;
        } else {
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


    /** ------------------- METODOS DE LA API ----------------------- */
    public function crearApi() // GET
    {
        header('Content-Type: application/json');

        try {

            $trayectos = $this->trayectoModel->getAllTrayectos();
            $hoteles = $this->hotelModel->getAll();


            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Datos del formulario obtenidos con éxito.',
                'data' => [
                    'trayectos' => $trayectos,
                    'hoteles' => $hoteles
                ]
            ]);
            exit;
        } catch (\Exception $e) {

            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor al obtener datos.']);
            exit;
        }
    }


    public function crearReservaPostApi() // POST
    {
        header('Content-Type: application/json');
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

        $email_cliente = $_SESSION['user_email'] ?? null;

        $numero_vuelo_salida = $_POST['numero_vuelo_salida'] ?? null;
        $hora_recogida = $_POST['hora_recogida'] ?? null;
        $codigo_admin = null;

        if ($this->isAdminLoggedIn()) {
            $email_cliente = $_POST['email_cliente'] ?? null;
            $codigo_admin = $_POST['codigo_admin'] ?? null;

            if (!$email_cliente || !$codigo_admin) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => "Como administrador, debes indicar el email del cliente y tu ID de admin."
                ]);
                return;
            }
        }

        $localizador = $this->reservaModel->crearReserva(
            $id_tipo_reserva,
            $id_destino,
            $fecha_entrada,
            $hora_entrada,
            $num_viajeros,
            $id_vehiculo,
            $numero_vuelo_entrada,
            $origen_vuelo_entrada,
            $fecha_vuelo_salida,
            $hora_vuelo_salida,
            $email_cliente,
            $numero_vuelo_salida,
            $hora_recogida
        );

        if ($localizador) {

            $id_reserva = $this->reservaModel->getUltimaReservaId();
            if ($this->isAdminLoggedIn()) {
                $this->reservaModel->guardarReservaAdmin($id_reserva, $codigo_admin);
            }


            echo json_encode([
                'success' => true,
                'message' => 'Reserva creada con éxito',
                'id_reserva' => $id_reserva
            ]);
            return;
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la reserva'
            ]);
            return;
        }
    }

    public function misreservasApi()
    {
        header('Content-Type: application/json'); // 1. Especificar que la respuesta es JSON


        $user_email = $_SESSION['user_email'];
        $user_id    = $_SESSION['user_id'];

        // 2. Lógica de negocio para obtener las reservas (la misma que en misreservas)
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

        // 4. Construir la respuesta JSON
        $response = [
            'status' => 'success',
            'user_email' => $user_email,
            'user_id' => $user_id,
            'reservas_count' => count($reservas),
            'reservas' => $reservas,
            'hotelesMap' => $hotelesMap
        ];

        // 5. Enviar la respuesta con código de estado 200 (OK)
        http_response_code(200);
        echo json_encode($response);
        exit;
    }




    public function cancelarApi($id_reserva)
    {
        $id_reserva = (int)$id_reserva;

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401); // 401 No Autorizado
            echo json_encode(['status' => 'error', 'message' => 'No autorizado. Se requiere inicio de sesión.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // 405 Método no permitido
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Se requiere POST.']);
            exit;
        }

        $reserva = $this->reservaModel->getReservaPorId($id_reserva);

        if (!$reserva) {
            http_response_code(404); // 404 No Encontrado
            echo json_encode(['status' => 'error', 'message' => 'Reserva no encontrada.']);
            exit;
        }

        $esAdmin = $this->isAdminLoggedIn();
        $esDueño = ($reserva['email_cliente'] === $_SESSION['user_email']);

        if (!$esDueño && !$esAdmin) {
            http_response_code(403); // 403 Prohibido
            echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para esta acción.']);
            exit;
        }

        $exito = $this->reservaModel->cancelarReserva($id_reserva);

        if ($exito) {
            http_response_code(200); // 200 OK
            echo json_encode([
                'status' => 'ok',
                'message' => 'Reserva cancelada con éxito.',
                'reserva_cancelada' => $id_reserva
            ]);
            exit;
        } else {
            http_response_code(500); // 500 Error Interno del Servidor
            echo json_encode(['status' => 'error', 'message' => 'Error del servidor al cancelar la reserva.']);
            exit;
        }
    }

    public function editarApi($id_reserva) //GET
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405); // 405 Método no permitido
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Se requiere GET.']);
            exit;
        }

        $reserva = $this->reservaModel->getReservaPorId($id_reserva);

        if (!$reserva) {
            http_response_code(404); // 404 No Encontrado
            echo json_encode(['status' => 'error', 'message' => 'Reserva no encontrada.']);
            exit;
        }
        $esAdmin = $this->isAdminLoggedIn();
        $esDueño = ($reserva['email_cliente'] === $_SESSION['user_email']);

        if (!$esAdmin && !$esDueño) {
            http_response_code(403); // 403 no autorizado
            echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para ver esta reserva.']);
            exit;
        }

        $hoteles = $this->hotelModel->getAll();
        $trayectos = $this->trayectoModel->getAllTrayectos();

        http_response_code(200); // 200 OK
        echo json_encode([
            'status' => 'ok',
            'data' => [
                'reserva' => $reserva,
                'hoteles' => $hoteles,
                'trayectos' => $trayectos
            ]
        ]);
        exit;
    }

    public function editarPostApi($id_reserva) //POST
    {

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Se requiere POST.']);
            exit;
        }

        $reserva = $this->reservaModel->getReservaPorId($id_reserva);

        if (!$reserva) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Reserva no encontrada.']);
            exit;
        }

        $esAdmin = $this->isAdminLoggedIn();
        $esDueño = ($reserva && $reserva['email_cliente'] === $_SESSION['user_email']);

        if (!$esAdmin && !$esDueño) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para modificar esta reserva.']);
            exit;
        }

        $datos = $_POST;

        $exito = $this->reservaModel->actualizarReserva($id_reserva, $datos);

        if ($exito) {
            http_response_code(200);
            echo json_encode(['status' => 'ok', 'message' => 'Reserva actualizada con éxito.']);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error del servidor al actualizar la reserva.']);
            exit;
        }
    }
}
