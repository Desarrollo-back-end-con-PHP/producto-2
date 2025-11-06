<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

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

        try {
            // 2. Para probar la BBDD, instanciamos un Modelo.
            // El constructor de Usuario llamará al constructor de core/Model.
            // core/Model llamará al constructor de core/Database.
            // Si core/Database falla, la aplicación se detendrá (die()).
            $testModel = new Usuario();

            // 3. Si llegamos aquí, la conexión (gestionada por el Modelo) fue exitosa.
            echo "✅ Conexión exitosa a la base de datos: " . DB_NAME . ".";
            
        } catch (\Exception $e) {
            // Captura genérica por si algo más falla (ej: si el modelo Usuario no existe)
            echo ("❌ Error al instanciar el modelo: " . $e->getMessage());
        }
    }
}
