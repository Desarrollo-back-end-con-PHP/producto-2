<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Helpers\ProfileMessageHelper;

class UsuarioController extends Controller
{
    protected $db;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->requiereLoginGuard(); //si el usuario NO esta logeado se redirige al login.
        $this->userModel = new Usuario();
    }

    public function mostrarPerfil()
    {
        $id_viajero = $_SESSION['user_id'];
        $datosUsuario = $this->userModel->obtenerDatosPersonales($id_viajero);

        $mensaje = $_GET['mensaje'] ?? null;
        // Comprueba si hay un mensaje especial en la sesión (el que acabamos de poner)
        if (isset($_SESSION['mensaje'])) {
            $mensaje = $_SESSION['mensaje']; // Sobrescribe el mensaje de la URL
            unset($_SESSION['mensaje']); // Límpialo para que no se repita
        }

        $this->loadView('user/my_profile', [
            'usuario' => $datosUsuario,
            'mensaje' => $mensaje,
        ]);
    }

    public function actualizarDatos()
    {
        $this->requireMethod('POST');
        $id_viajero = $_SESSION['user_id'];
        $redirectURL = APP_URL . '/usuario/mostrarPerfil';

        $nombre = $_POST['nombre'] ?? null;
        $apellido1 = $_POST['apellido1'] ?? null;
        $apellido2 = $_POST['apellido2'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $codigoPostal = $_POST['codigoPostal'] ?? null;
        $ciudad = $_POST['ciudad'] ?? null;
        $pais = $_POST['pais'] ?? null;
        $email = $_POST['email'] ?? null;

        $campos = [
            'nombre'       => $nombre,
            'apellido1'    => $apellido1,
            'apellido2'    => $apellido2,
            'direccion'    => $direccion,
            'codigoPostal' => $codigoPostal,
            'ciudad'       => $ciudad,
            'pais'         => $pais,
            'email'        => $email,
        ];

        $camposVacios = array_filter($campos, fn($valor) => trim((string)$valor) === '');

        if (!empty($camposVacios)) {
            $mensaje = ProfileMessageHelper::ERROR_CAMPOS_VACIOS;
            header('Location: ' . $redirectURL . '?mensaje=' . $mensaje);
            exit();
        }


        if (!filter_var($campos['email'], FILTER_VALIDATE_EMAIL)) {
            $mensaje = ProfileMessageHelper::ERROR_EMAIL;
            header('Location: ' . $redirectURL . '?mensaje=' . $mensaje);
            exit();
        }


        $exito = $this->userModel->actualizarDatosPersonales(
            $id_viajero,
            $nombre,
            $apellido1,
            $apellido2,
            $direccion,
            $codigoPostal,
            $ciudad,
            $pais,
            $email
        );

        if ($exito) {
            if (isset($_SESSION['reserva_temporal'])) {

                //  Si hay una reserva pausada redirigimos de vuelta al formulario de reserva.
                header('Location: ' . APP_URL . '/reserva/crear');
                exit;
            }
            header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::EXITO_DATOS);
        } else {
            header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_DATOS);
        }
        exit();
    }

    public function actualizarContrasena()
    {
        $this->requireMethod('POST');

        $id_viajero = $_SESSION['user_id'];
        $nuevaContrasena = $_POST['nueva_contrasena'] ?? '';
        $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';

        $redirectURL = APP_URL . '/usuario/mostrarPerfil';


        if ($nuevaContrasena === $confirmarContrasena && !empty($nuevaContrasena)) {
            if (strlen($nuevaContrasena) < 8) {
                header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_PASS_SHORT);
                exit();
            }
            $exito = $this->userModel->actualizarContrasena($id_viajero, $nuevaContrasena);

            if ($exito) {
                header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::EXITO_PASS);
            } else {
                header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_BD_PASS);
            }
        } else {
            header('Location:' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_PASS_MISMATCH);
        }
        exit();
    }

    public function eliminarUsuario()
    {

        $this->requireMethod('POST');

        $id_viajero = $_SESSION['user_id'] ?? null;

        if (!$id_viajero) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        $exito = $this->userModel->eliminarUsuario($id_viajero);

        if ($exito) {

            session_unset();

            session_destroy();

            session_start();
            $_SESSION['mensaje_logout'] = 'Tu cuenta ha sido desactivada correctamente.';

            header('Location: ' . APP_URL . '/login');
            exit();
        } else {
            $mensaje = 'error_eliminar';
            header('Location:' . APP_URL . '/usuario/mostrarPerfil?mensaje=' . $mensaje);
            exit();
        }
    }
}
