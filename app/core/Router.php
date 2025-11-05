<?php

namespace App\Core;

/**
 * Clase Router
 * Analiza la URL y carga el controlador y método correspondientes.
 */
class Router
{
    /**
     * Método principal que "despacha" la ruta.
     */
    public static function dispatch()
    {
        // --- LÓGICA DEL ROUTER ---
        // (Esta es la lógica que tú proporcionaste, ahora encapsulada)

        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index';

        if (empty($_GET['url'])) {
            header('Location: /home'); // Redirige a /home si la URL está vacía
            exit;
        }

        $urlParts = explode('/', filter_var($url, FILTER_SANITIZE_URL));

        // Por defecto, apuntamos al namespace de controladores WEB
        $baseNamespace = 'App\\Controllers\\';

        // *** LÓGICA DE API ***
        // Comprueba si la primera parte de la URL es 'api'
        if (!empty($urlParts[0]) && $urlParts[0] === 'api') {
            // Es una petición API, cambia el namespace base
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
                http_response_code(404);
                die('Error 404: El método "' . $methodName . '" no existe en la clase "' . $fqcn . '".');
            }
        } else {
            http_response_code(404);
            die('Error 404: La clase controladora "' . $fqcn . '" no fue encontrada.');
        }
    }
}