<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;

class AuthController extends BaseController {

    public function index() {
        $response = [
            'status' => 'success',
            'message' => 'API de Autenticación en línea.',
            'endpoints_disponibles' => [
                'POST /api/auth/authenticate' => 'Iniciar sesión.',
                'POST /api/auth/store_register' => 'Crear nuevo usuario.',
                'GET /api/auth/getcurrentuser' => 'Obtener datos del usuario (si hay sesión).',
                'GET /api/auth/logout' => 'Cerrar sesión.'
            ]
        ];
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    public function authenticate() {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            $response = [
                'status' => 'error',
                'message' => 'Email y contraseña son requeridos.'
            ];
            http_response_code(400); 
        } else {
            $_SESSION['user_id'] = 123; 
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = 'Usuario Simulado'; 
            
            $response = [
                'status' => 'success',
                'message' => 'Login (authenticate) exitoso para: ' . htmlspecialchars($email),
                'user_id' => $_SESSION['user_id']
            ];
            http_response_code(200); 
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        die(); 
    }


    public function store_register() {
        
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$name || !$email || !$password) {
             $response = [
                'status' => 'error',
                'message' => 'Nombre, email y contraseña son requeridos.'
            ];
            http_response_code(400);
        } else { 
            $response = [
                'status' => 'success',
                'message' => 'Usuario (store_register) creado para: ' . htmlspecialchars($name),
                'new_user_id' => 456 
            ];
            http_response_code(201); 
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        die(); 
    }

    public function getcurrentuser() {
        
        if (isset($_SESSION['user_id'])) {
            
            $response = [
                'status' => 'success',
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'email' => $_SESSION['user_email'],
                    'name' => $_SESSION['user_name']
                ]
            ];
            http_response_code(200); 
            
        } else {
            
            $response = [
                'status' => 'error',
                'message' => 'No hay una sesión activa.'
            ];
            http_response_code(401); 
        }

        
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    public function logout() {
        session_unset(); 
        session_destroy(); 

        $response = [
            'status' => 'success',
            'message' => 'Sesión cerrada correctamente.'
        ];
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
}
?>

