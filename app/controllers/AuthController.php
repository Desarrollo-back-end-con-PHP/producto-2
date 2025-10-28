<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController {

    public function login() {
        $data = ['title' => 'Iniciar Sesión'];
        $this->loadView('auth/login', $data); 
    }

    public function register() {
        $data = ['title' => 'Registro'];
        $this->loadView('auth/register', $data); 
    }

}
?>

