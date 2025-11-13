<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class AuthController extends Controller
{

    private $userModel; 

    public function __construct()
    {
        // Se carga la conexión a la bbdd
        parent::__construct();
        //se crea una instancia del modelo Usuario
        $this->userModel = new Usuario();
    }

    //muestra página login
    public function login()
    {
        $data = ['title' => 'Iniciar Sesión'];
        $this->loadView('auth/login', $data);
    }
    //muestra página registro
    public function register()
    {
        $data = ['title' => 'Registro'];
        $this->loadView('auth/register', $data);
    }

    public function store()
    {
        $this->requireMethod('POST'); //solo se permite acceso por POST

        //recogemos datos formulario
        $nombre = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        //VALIDACIÓN
        if (empty($nombre) || empty($email) || empty($password)) {
            header('Location: ' . APP_URL . '/auth/register?error=empty');
            exit;
        }
        if ($password !== $password_confirm) {
            header('Location: ' . APP_URL . '/auth/register?error=mismatch');
            exit;
        }

        //si todo funciona se llama al método registrar del modelo usuario
        $exito = $this->userModel->registrar($nombre, $email, $password);

        if ($exito) { //si es exitoso se redirige a login con un mensaje
            header('Location: ' . APP_URL . '/auth/login?success=registered');
            exit;
        } else { // 
            header('Location: ' . APP_URL . '/auth/register?error=duplicate');
            exit;
        }
    }

public function authenticate()
    {
        $this->requireMethod('POST');

        //recogemos los datos del formulario
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        //llama al modelo para verificar las credenciales
        $usuario = $this->userModel->verificarCredenciales($email, $password);

        //da error si las credenciales son incorectas
        if ($usuario === false) {
            header('Location: ' . APP_URL . '/auth/login?error=credentials');
            exit;
        }
        //se regenera la sesión para evitar ataques de fijación de sesión
        session_regenerate_id(true);

        //se guardan datos del usuario en la sesión
        $_SESSION['user_id'] = $usuario['id_viajero'];
        $_SESSION['user_name'] = $usuario['nombre'];
        $_SESSION['user_email'] = $usuario['email'];

        // Comprobamos si el email es el del admin y guardamos el ROL
        if ($usuario['email'] === 'admin@islatransfers.com') {
            $_SESSION['user_rol'] = 'admin';
        } else {
            $_SESSION['user_rol'] = 'particular';
        }
        // Redirigir al panel de admin si es admin, o al perfil si es particular
        if ($_SESSION['user_rol'] === 'admin') {
            header('Location: ' . APP_URL . '/admin/calendar');
            exit;
        } else {
            header('Location: ' . APP_URL . '/usuario/mostrarPerfil');
            exit;
        }
    }
    public function logout()
    {
        //borra todas las variables de la sesión
        session_unset();

        //destruye la sesión del servidor
        session_destroy();

        //redirige a login
        header('Location: ' . APP_URL . '/auth/login?success=logout');
    }
}
