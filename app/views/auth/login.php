<?php

$error_msg = '';
$success_msg = '';
//Mensajes de error:
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'credentials':
            $error_msg = 'El email o contraseña són incorrectos. Por favor inténtalo de nuevo.';
            break;
        case 'empty':
            $error_msg = 'Por favor, rellena todos los campos del formualrio.';
            break;
        default:
            $error_msg = 'Ha ocurrido un error inesperado';
    }
}

//Mensajes de éxito
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'registered':
            $succes_msg = 'Resgistro completado correctamente. Inicia sesión';
            break;
        case 'logout':
            $succes_msg = 'Sesión cerrada correctamente';
            break;
    }
}

?>

<!-- app/views/pages/login.php -->

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <!-- Tarjeta de Bootstrap para el formulario -->
        <div class="card shadow-lg border-0 rounded-lg mt-5">

            <div class="card-header">
                <h3 class="text-center fw-light my-4">Iniciar Sesión</h3>
            </div>

            <div class="card-body">

                <!-- MOSTRAR MENSAJES -->
                <?php if (!empty($success_msg)): ?>
                    <div class=" alert alert-success" role="alert">
                        <?php echo htmlspecialchars($success_msg); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class=" alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>


                <!-- El action del formulario apunta a un método 'authenticate' (debes crearlo en tu controlador) -->
                <form action="<?php echo APP_URL; ?>/auth/authenticate" method="POST">

                    <!-- Campo de Email (con etiqueta flotante) -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email" placeholder="correo@ejemplo.com" required />
                        <label for="email">Correo Electrónico</label>
                    </div>

                    <!-- Campo de Contraseña (con etiqueta flotante) -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña" required />
                        <label for="password">Contraseña</label>
                    </div>

                    <!-- Botón de envío (ocupa todo el ancho) -->
                    <div class="d-grid gap-2 mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                    </div>

                </form>
            </div>

            <div class="card-footer text-center py-3">
                <div class="small">
                    <a href="<?php echo APP_URL; ?>/auth/register">¿No tienes cuenta? Regístrate aquí</a>
                </div>
            </div>

        </div>
    </div>
</div>