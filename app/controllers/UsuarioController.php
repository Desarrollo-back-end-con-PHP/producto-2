<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Helpers\ProfileMessageHelper; 

class UsuarioController extends Controller
{
    // AÑADIDO: Declarar $db (aunque esté vacío al inicio) es una buena práctica 
    // si el framework o router lo inyecta después del constructor.
    protected $db; 

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

        // CORRECCIÓN CLAVE: Inicializamos el modelo SIN PASARLE $this->db,
        // replicando el patrón del AuthController que funciona.
        $this->userModel = new Usuario(); 
    }

    /**
     * Muestra la página de perfil del usuario.
     */
    public function mostrarPerfil()
    {
        $id_viajero = $_SESSION['user_id']?? 1;
        $datosUsuario = $this->userModel->obtenerDatosPersonales($id_viajero);

        $this->loadView('user/my_profile', [
            'usuario' => $datosUsuario,
            'mensaje' => $_GET['mensaje'] ?? null,
        ]);
    }

    
 public function actualizarDatos()
{
    $this->requireMethod('POST');
    $id_viajero = $_SESSION['user_id'];
    $redirectURL = APP_URL . '/usuario/mostrarPerfil';

    // Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $apellido1 = $_POST['apellido1'] ?? '';
    $apellido2 = $_POST['apellido2'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $codigoPostal = $_POST['codigoPostal'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validación simple de email
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); 
        echo json_encode(['status' => 'error', 'message' => 'El formato del correo electrónico no es válido.']);
        return;
    }

    // Llamada al modelo con el orden correcto de parámetros
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

    // Redirección con mensaje
    if ($exito) {
        header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::EXITO_DATOS);
    } else {
        header('Location: ' . $redirectURL . '?mensaje=' . ProfileMessageHelper::ERROR_DATOS);
    }
    exit();
}
  
    public function actualizarContrasena()
    {
        // 1. Requerir que la petición sea POST
        $this->requireMethod('POST');

        $id_viajero = $_SESSION['id_viajero'] ?? 1;
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
            // Si las contraseñas no coinciden o están vacías
            header('Location: /perfil?mensaje=' . ProfileMessageHelper::ERROR_PASS_MISMATCH);
        }
        exit();
    }
}
