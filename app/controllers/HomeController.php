<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{

    public function index()
    {

        $data = [
            'title' => 'Bienvenido a Isla Transfers',
            'description' => 'Tu servicio de traslados en la isla. Aquí puedes presentar la aplicación, sus características y funcionamiento.'
        ];

        $this->loadView('home', $data);
    }

    //test de conexión a BBDD
    public function testdb()
    {
        echo "Iniciando prueba de conexión... <br><br>";

        if ($this->db) {
            echo "✅ Conexión exitosa a la base de datos: " . DB_NAME . ".";
        } else {
            echo ("❌ Conexión fallida");
        }
    }
}
