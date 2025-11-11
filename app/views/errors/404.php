<?php
// app/views/errors/404.php

// Definimos un título para la pestaña del navegador
$title = "Página no encontrada (404)";

// Incluimos el header (que ya tiene Bootstrap y tu menú)
// Esto asume que la variable $data (si la usas) no es estrictamente necesaria para el header.
// Si lo es, puedes definir $data = ['title' => $title];
?>

<!-- Contenido de la página 404 -->
<div class="container text-center">
    <div class="row vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="display-1 fw-bold text-danger">Error 404</h1>
            <h2 class="mb-4">Página No Encontrada</h2>
            <p class="lead">
                Lo sentimos, la página que buscas no existe o ha sido movida.
            </p>
            
            <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
                <!-- 
                  Opcional: Mostrar un mensaje de error más técnico.
                  Considera ocultar esto en producción por seguridad, 
                  quizás comprobando una variable APP_DEBUG de tu config.
                -->
                <div class="alert alert-warning my-4">
                    <strong>Detalle del error:</strong> <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <p id="countdown-message" class="fs-5">
                Serás redirigido a la página de inicio en <strong id="countdown" class="text-primary">5</strong> segundos...
            </p>

            <a href="<?php echo APP_URL; ?>/home" class="btn btn-primary btn-lg mt-3">
                Volver al Inicio Ahora
            </a>
        </div>
    </div>
</div>

<!-- Script para la cuenta atrás y redirección -->
<script>
    (function() {
        let seconds = 10; // Inicia el contador en 5
        const countdownElement = document.getElementById('countdown');
        const countdownMessage = document.getElementById('countdown-message');
        
        // La URL de inicio definida por tu constante PHP
        const homeUrl = "<?php echo APP_URL; ?>/home"; 

        // Ejecuta esta función cada segundo
        const interval = setInterval(() => {
            seconds--; // Reduce el contador
            
            if (countdownElement) {
                countdownElement.textContent = seconds; // Actualiza el número
            }

            // Cuando el contador llega a 0
            if (seconds <= 0) {
                clearInterval(interval); // Detiene el intervalo
                
                if (countdownMessage) {
                    countdownMessage.textContent = "Redirigiendo...";
                }
                
                // Redirige al usuario
                window.location.href = homeUrl;
            }
        }, 1000); // 1000 ms = 1 segundo
    })();
</script>

<?php
// Para una página de error 404, a veces es mejor
// no incluir el footer estándar, pero si lo deseas, descomenta la línea:
// require_once '../app/views/inc/footer.php'; 
?>
</body>
</html>