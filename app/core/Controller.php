<?php

namespace App\Core;

class Controller
{
    public function __construct(){}
    
    protected function loadView($viewName, $data = [])
    {
        extract($data);

        $viewFile = '../app/views/' . $viewName . '.php';

        if (file_exists($viewFile)) {
            require_once '../app/views/layout/header.php'; // Carga el header

            require_once $viewFile; // Carga la vista específica

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
}
