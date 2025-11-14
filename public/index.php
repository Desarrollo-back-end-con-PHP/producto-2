<?php
// Inicia la sesión para toda la aplicación
session_start();

// 1. Cargar el Autoloader de Composer (SIEMPRE PRIMERO)
// Esto nos da acceso a la clase Dotenv
require_once dirname(__DIR__) . '/vendor/autoload.php';

// 2. Cargar variables de entorno (.env)
try {
    // Busca el .env en el directorio raíz (un nivel arriba de /public)
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    
    // -> Opcional: Asegurarse de que las variables clave existen
    // $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

} catch (Exception $e) {
    // ESTA ES TU PETICIÓN: Avisar si no se carga
    error_log($e->getMessage()); // Guarda el error en el log del servidor
    die("<b>Error Crítico:</b> No se pudo cargar la configuración del entorno (.env). <br>Por favor, revisa que el archivo exista en la raíz del proyecto.");
}

// 3. Cargar configuración (AHORA SÍ)
// Ahora config.php puede leer las variables de $_ENV que cargó Dotenv
require_once '../app/config/config.php';

// 4. Importar y ejecutar el Router
use App\Core\Router;

Router::dispatch();