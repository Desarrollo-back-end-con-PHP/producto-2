<?php

namespace App\Core;

use \mysqli;

class Database
{
    //Variable pública para guardar la conexión
    public $connection;

    //Constructor se ejecuta automáticamente al crear una new Base de datos.
    public function  __construct()
    {
        //Intenta crear una conexión con las variables de config/database.php
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        //Comprueba si hay errores en la conexión
        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }

        //Asegura que se use UTF-8
        $this->connection->set_charset("utf8mb4");
    }
}