<?php
//mensajes error
$error_msg = '';

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'empty':
            $error_msg = 'Por favor, rellena todos los campos del formualrio.';
            break;
        case 'mismatch':
            $error_msg = 'Las contraseñas no coinciden. Inténtalo de nuevo.';
            break;
        case 'duplicate':
            $error_msg = 'Ese email ya está registrado. Por favor, <a href="' . APP_URL . '/auth/login">inicia sesión</a>';
            break;
        default:
            $error_msg = 'Ha ocurrido un error inesperado';
    }
}

?>


<!-- app/views/pages/register.php -->

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">

        <!-- Tarjeta de Bootstrap para el formulario -->
        <div class="card shadow-lg border-0 rounded-lg mt-5">

            <div class="card-header">
                <h3 class="text-center fw-light my-4">Crear una Cuenta</h3>
            </div>

            <div class="card-body">

                <!-- MOSTRAR MENSAJES -->
                <?php if (!empty($error_msg)): ?>
                    <div class=" alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>


                <!-- El action del formulario apunta a un método 'store' (debes crearlo en tu controlador) -->
                <form action="<?php echo APP_URL; ?>/auth/store" method="POST">

                    <!-- Campo de Nombre -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="name" name="name" type="text" placeholder="Tu nombre completo" required />
                        <label for="name">Nombre Completo</label>
                    </div>

                    <!-- Campo de Email -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email" placeholder="correo@ejemplo.com" required />
                        <label for="email">Correo Electrónico</label>
                    </div>

                    <!-- Campos de Contraseña (en una fila) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <!-- Campo de Contraseña -->
                            <div class="form-floating mb-3 mb-md-0">
                                <input class="form-control" id="password" name="password" type="password" placeholder="Crear contraseña" required />
                                <label for="password">Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Campo de Confirmar Contraseña -->
                            <div class="form-floating">
                                <input class="form-control" id="password_confirm" name="password_confirm" type="password" placeholder="Confirmar contraseña" required />
                                <label for="password_confirm">Confirmar Contraseña</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de envío (ocupa todo el ancho) -->
                    <div class="d-grid gap-2 mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-lg">Crear Cuenta</button>
                    </div>

                </form>
            </div>

            <div class="card-footer text-center py-3">
                <div class="small">
                    <a href="<?php echo APP_URL; ?>/login">¿Ya tienes una cuenta? Inicia sesión</a>
                </div>
            </div>

        </div>
    </div>
</div>