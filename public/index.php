<?php

session_start();

require_once '../app/config/config.php';

require_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
} catch (Exception $e) {
    // No hacer nada si no hay .env, pero no debe fallar
}


// --- INICIO DEL ROUTER CON NAMESPACES ---
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index'; 

if (empty($_GET['url'])) {
    header('Location: /home');  // <-- redirección
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

if (!empty($urlParts[0])) {
    $controllerName = ucfirst(strtolower($urlParts[0])) . 'Controller';
    array_shift($urlParts);
}

$fqcn = $baseNamespace . $controllerName;

$methodName = 'index'; // Método por defecto
if (!empty($urlParts[0])) {
    $methodName = strtolower($urlParts[0]);
    array_shift($urlParts);
}

$params = $urlParts;

if (class_exists($fqcn)) {
    $controller = new $fqcn(); 

    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        http_response_code(404);
        die('Error 404: El método "' . $methodName . '" no existe en la clase "' . $fqcn . '".');
    }
} else {
    http_response_code(404);
    die('Error 404: La clase controladora "' . $fqcn . '" no fue encontrada.');
}
?>

