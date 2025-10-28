<?php

namespace App\Controllers;

class BaseController {


    protected function loadView($viewName, $data = []) {
        extract($data); 

        $viewFile = '../app/views/' . $viewName . '.php';

        if (file_exists($viewFile)) {
            require_once '../app/views/layout/header.php'; // Carga el header
            
            require_once $viewFile; // Carga la vista específica

            require_once '../app/views/layout/footer.php'; // Carga el footer
        } else {
            die('Error Fatal: La vista "' . $viewName . '.php" no existe en la carpeta app/views/.');
        }
    }
}
?>

