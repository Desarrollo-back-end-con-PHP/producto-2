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

        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index';

        if (empty($_GET['url'])) {
            header('Location: /home'); // Redirige a /home si la URL está vacía
            exit;
        }

        $urlParts = explode('/', filter_var($url, FILTER_SANITIZE_URL));

        $baseNamespace = 'App\\Controllers\\';

        // *** LÓGICA DE API ***
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

        // --- Obtener Parámetros ---
        $params = $urlParts;

        // --- Ejecutar ---
        if (class_exists($fqcn)) {
            $controller = new $fqcn();

            if (method_exists($controller, $methodName)) {
                // Llama al método y pasa los parámetros restantes
                call_user_func_array([$controller, $methodName], $params);
            } else {
                // --- INICIO DE CORRECCIÓN (Método no encontrado) ---
                // Preparamos el mensaje de error
                $errorMessage = 'El método "' . $methodName . '" no existe en la clase "' . $fqcn . '".';

                // Enviamos el código de estado HTTP 404
                http_response_code(404);

                // Cargamos nuestra vista 404 personalizada
                $controller = new Controller();
                $controller->loadView('errors/404', ['errorMessage' => $errorMessage]);

                // Detenemos la ejecución
                exit;
                // --- FIN DE CORRECCIÓN ---
            }
        } else {
            // --- INICIO DE CORRECCIÓN (Controlador no encontrado) ---
            // Preparamos el mensaje de error
            $errorMessage = 'La clase controladora "' . $fqcn . '" no fue encontrada.';

            // Enviamos el código de estado HTTP 404
            http_response_code(404);

            // Cargamos nuestra vista 404 personalizada
            $controller = new Controller();
            $controller->loadView('errors/404', ['errorMessage' => $errorMessage]);
            // Detenemos la ejecución
            exit;
            // --- FIN DE CORRECCIÓN ---
        }
    }
}
