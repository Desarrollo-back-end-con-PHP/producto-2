<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Helpers\ProfileMessageHelper; 

class UsuarioController extends Controller
{

    // Declaramos la propiedad $userModel para guardar una instancia de la clase User
    private $userModel;

    // El constructor se ejecuta automáticamente al crear un objeto UserController
    public function __construct()
    {
        //Se llama al constructor padre (Controller)
        parent::__construct();

        //si el usuario NO esta logeado se redirige al login.
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

        // Inicializamos el modelo para acceder a las funciones de base de datos.
        $this->userModel = new Usuario($this->db);
    }

    /**
     * Muestra la página de perfil del usuario.
     */
    public function mostrarPerfil()
    {
        $id_viajero = $_SESSION['id_viajero'] ?? 1;
        $datosUsuario = $this->userModel->obtenerDatosPersonales($id_viajero);

        $this->loadView('user/my_profile', [
            'usuario' => $datosUsuario,
            'mensaje' => $_GET['mensaje'] ?? null,
        ]);
    }

    /**
     * Procesa la actualización de los datos personales (nombre, email, dirección).
     */
    public function actualizarDatos()
    {
        // 1. Requerir que la petición sea POST, usando el método de Controller
        $this->requireMethod('POST');

        // 2. Recoger y validar los datos del formulario
        $id_viajero = $_SESSION['id_viajero'] ?? 1;
        $nombre = $_POST['nombre'] ?? '';
        $apellido1 = $_POST['apellido1'] ?? '';
        $apellido2 = $_POST['apellido2'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $codigoPostal = $_POST['codigoPostal'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $pais = $_POST['pais'] ?? '';
        $email = $_POST['email'] ?? '';

        // 3. Llamar al modelo para actualizar
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

        // 4. Redirigir con mensaje
        $redirectURL = APP_URL . '/usuario/mostrarPerfil';

        if ($exito) {
            header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::EXITO_DATOS);
        } else {
            header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_DATOS);
        }
        exit();
    }

    /**
     * Procesa la actualización de la contraseña.
     */
    public function actualizarContrasena()
    {
        // 1. Requerir que la petición sea POST
        $this->requireMethod('POST');

        $id_viajero = $_SESSION['id_viajero'] ?? 1;
        $nuevaContrasena = $_POST['nueva_contrasena'] ?? '';
        $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';

        // 2. Validación de la contraseña: debe coincidir
        if ($nuevaContrasena === $confirmarContrasena && !empty($nuevaContrasena)) {

            // 3. Llamar al modelo para actualizar
            $exito = $this->userModel->actualizarContrasena($id_viajero, $nuevaContrasena);

            $redirectURL = APP_URL . '/usuario/mostrarPerfil';

            if ($exito) {
                header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::EXITO_PASS);
            } else {
                header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_BD_PASS);
            }
        } else {
            // Si las contraseñas no coinciden o están vacías
            header('Location: /perfil?mensaje=' . ProfileMessageHelper::ERROR_PASS_MISMATCH);
        }
        exit();
    }
}
