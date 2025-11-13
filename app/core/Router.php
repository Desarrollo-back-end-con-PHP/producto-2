<?php

namespace App\Core;

use App\Core\Controller;

/**
 * Clase Router
 * Analiza la URL y carga el controlador y método correspondientes.
 */
class Router
{
    public function __construct() {}

    /**
     * Método principal que "despacha" la ruta.
     */
    public static function dispatch()
    {
        // La URL que el router va a procesar (solo la ruta, ej: 'admin/calendar')
        // Usamos isset() para evitar errores si no viene ninguna ruta
        $url_path = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index';

        // Si el usuario simplemente accedió a la raíz sin ruta, lo redirigimos
        if (empty($_GET['url'])) {
            header('Location: ' . APP_URL . '/home'); // Redirige a /home si la URL está vacía
            exit;
        }

        // Separamos la ruta en partes (Controller, Method, Params)
        $urlParts = explode('/', filter_var($url_path, FILTER_SANITIZE_URL));

        $baseNamespace = 'App\\Controllers\\';

        // --- LÓGICA DE API ---
        if (!empty($urlParts[0]) && $urlParts[0] === 'api') {
            $baseNamespace = 'App\\Controllers\\Api\\';
            array_shift($urlParts); // Quita 'api' de la URL
        }

        // --- Obtener Controlador ---
        $controllerName = 'HomeController'; // Controlador por defecto
        if (!empty($urlParts[0])) {
            $controllerName = ucfirst(strtolower($urlParts[0])) . 'Controller';
            array_shift($urlParts);
        }
        $fqcn = $baseNamespace . $controllerName; // Fully Qualified Class Name

        // --- Obtener Método ---
        $methodName = 'index'; // Método por defecto
        if (!empty($urlParts[0])) {
            $methodName = strtolower($urlParts[0]);
            array_shift($urlParts);
        }

        // --- Obtener Parámetros de Ruta ---
        $params = $urlParts; 

        // --- Ejecutar ---
        if (class_exists($fqcn)) {
            $controller = new $fqcn();

            if (method_exists($controller, $methodName)) {
                // IMPORTANTE: call_user_func_array Llama al método y pasa los parámetros.
                // Los parámetros de la Query String (vista, mes, año) son accesibles globalmente.
                call_user_func_array([$controller, $methodName], $params);
            } else {
                // --- Manejo de Error (Método no encontrado) ---
                $errorMessage = 'El método "' . $methodName . '" no existe en la clase "' . $fqcn . '".';
                http_response_code(404);
                $controller = new Controller();
                $controller->loadView('errors/404', ['errorMessage' => $errorMessage]);
                exit;
            }
        } else {
            // --- Manejo de Error (Controlador no encontrado) ---
            $errorMessage = 'La clase controladora "' . $fqcn . '" no fue encontrada.';
            http_response_code(404);
            $controller = new Controller();
            $controller->loadView('errors/404', ['errorMessage' => $errorMessage]);
            exit;
        }
    }
} 