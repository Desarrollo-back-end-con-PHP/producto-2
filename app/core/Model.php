<?php

namespace App\Core;

use App\Core\Database;

/**
 * Clase Modelo Base
 * Todos los modelos de la aplicación heredarán de esta clase.
 * Proporciona automáticamente la conexión a la base de datos.
 */
class Model
{

    protected $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connection;
    }
}