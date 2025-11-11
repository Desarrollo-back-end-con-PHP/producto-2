<?php
// Inicia la sesión para toda la aplicación
session_start();

// 1. Cargar configuración (define DB_HOST, APP_URL, etc.)
require_once '../app/config/config.php';

// 2. Cargar el Autoloader de Composer (para Dotenv y otras librerías)
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 3. Cargar variables de entorno (.env)
try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
} catch (Exception $e) {
    // No hacer nada si no hay .env, pero no debe fallar
}

// 4. Importar y ejecutar el Router
use App\Core\Router;

Router::dispatch();
