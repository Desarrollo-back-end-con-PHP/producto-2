<?php
echo "Iniciando prueba de conexión... <br><br>";

require_once '../app/config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database();

    if ($db->connection) {
        echo "✅ Conexión exitosa a la base de datos: " . DB_NAME . ".";
    }
} catch (Exception $e) {
    die("❌ Conexión fallida: " . $e->getMessage());
}
