<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class HomeController extends BaseController {

    public function index() {
        
        $data = [
            'title' => 'Bienvenido a Isla Transfers',
            'description' => 'Tu servicio de traslados en la isla. Aquí puedes presentar la aplicación, sus características y funcionamiento.'
        ];
        
        $this->loadView('home', $data);
    }
}
?>
