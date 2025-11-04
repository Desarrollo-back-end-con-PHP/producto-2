<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Usuario;

class UsuarioController extends BaseController
{

    // Declaramos la propiedad $userModel para guardar una instancia de la clase User
    private $userModel;

    // El constructor se ejecuta automáticamente al crear un objeto UserController
    public function __construct()
    {
        //Se llama al constructor padre (BaseController)
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
        // 1. Obtener ID del usuario logeado desde la sesión.
        // **Recordatorio**: ¡DEBES validar que la sesión exista!
        // Usamos 'email_viajero' para el ejemplo, pero puede ser 'id_viajero'
        $email_viajero = $_SESSION['email_viajero'] ?? 'test@example.com';

        // Asumiremos que el modelo User ya tiene la lógica para obtener el ID si solo tienes el email
        // O mejor: si el login guarda el ID, lo usamos. Usaremos un ID de ejemplo por ahora.
        $id_viajero = $_SESSION['id_viajero'] ?? 1;

        // 2. Obtener los datos del modelo
        $datosUsuario = $this->userModel->obtenerDatosPersonales($id_viajero);

        // 3. Cargar la vista usando la función loadView del BaseController
        // Le pasamos 'perfil/perfil_view' y los datos en un array asociativo.
        $this->loadView('perfil/perfil_view', [
            'usuario' => $datosUsuario,
            'mensaje' => $_GET['mensaje'] ?? null // Para mostrar mensajes después de una acción
        ]);
    }

    /**
     * Procesa la actualización de los datos personales (nombre, email, dirección).
     */
    public function actualizarDatos()
    {
        // 1. Requerir que la petición sea POST, usando el método de BaseController
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
        if ($exito) {
            header('Location: /perfil?mensaje=exito_datos');
        } else {
            header('Location: /perfil?mensaje=error_datos');
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

            if ($exito) {
                header('Location: /perfil?mensaje=exito_pass');
            } else {
                header('Location: /perfil?mensaje=error_bd_pass');
            }
        } else {
            // Si las contraseñas no coinciden o están vacías
            header('Location: /perfil?mensaje=error_pass_mismatch');
        }
        exit();
    }
}
