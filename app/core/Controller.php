<?php

namespace App\Core;

class Controller
{
    public function __construct() {}

    protected function loadView($viewName, $data = [])
    {
        extract($data);

        $viewFileRoute = '../app/views/' . $viewName . '.php';

        if (file_exists($viewFileRoute)) {
            require_once '../app/views/layout/header.php'; // Carga el header

            require_once $viewFileRoute; // Carga la vista específica

            require_once '../app/views/layout/footer.php'; // Carga el footer
        } else {
            die('Error Fatal: La vista "' . $viewName . '.php" no existe en la carpeta app/views/.');
        }
    }


    protected function requireMethod(string $method)
    {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            http_response_code(405);
            header('Allow: ' . strtoupper($method));
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido. Use ' . strtoupper($method) . '.']);
            die();
        }
    }


    /**
     * Comprueba si el usuario está actualmente autenticado.
     * @return bool
     */
    protected function isUserLoggedIn(): bool
    {
        // Si la sesión no está iniciada, no puede estar logueado
        if (session_status() === PHP_SESSION_NONE) {
            return false;
        }

        // Devuelve true si 'user_id' existe y no es nulo
        return isset($_SESSION['user_id']);
    }

    /**
     * Protege un controlador (o un método).
     * Se utiliza en el constructor del controlador para asegurar que el usuario esté logueado.
     */
    protected function requiereLoginGuard()
    {
        // Llama a la función de chequeo que acabamos de crear
        if (!$this->isUserLoggedIn()) {

            // Si devuelve false, redirige al login y detiene todo
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }
}
