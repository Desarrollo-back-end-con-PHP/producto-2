<?php
use App\Helpers\ProfileMessageHelper; 


$mensaje =  $data['mensaje'] ?? '';
$textoMensaje = ProfileMessageHelper::getText($mensaje);
$claseAlerta = ProfileMessageHelper::getClaseAlerta($mensaje);
$usuario = $data['usuario'];

?>


<div class="d-flex align-items-center justify-content-between">
    <div class="w-100">
        <h1>Mi Perfil</h1>
        <p class="fs-5 text-muted">
            Hola, <strong class="fw-semibold text-dark"><?php echo htmlspecialchars($usuario['nombre']); ?></strong>.
            Aquí puedes gestionar tu información personal y tu contraseña.
        </p>

<!-- Contenedor de Alerta (si existe) -->
<?php if ($textoMensaje): ?>
    <div id="tempralAlert" class="alert <?php echo $claseAlerta; ?> shadow-sm" role="alert">
        <?php echo $textoMensaje; ?>
    </div>
<?php endif; ?>


<!-- Contenedor principal con dos columnas de Bootstrap -->
<div class="row g-4">

    <!-- Columna 1: Formulario de Datos Personales -->
    <div class="col-lg-8">
        <div class="card shadow-sm border">
            <div class="card-body p-4">
                <h2 class="card-title h4 mb-3 border-bottom pb-2">
                    Datos Personales
                </h2>
                
                <!-- El 'action' apunta al método 'actualizarDatos' del 'UsuarioController' -->
                <form action="<?php echo APP_URL; ?>/usuario/actualizarDatos" method="POST">
                    
                    <!-- Fila: Nombre y Apellidos -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="apellido1" class="form-label">Primer Apellido</label>
                            <input type="text" name="apellido1" id="apellido1" value="<?php echo htmlspecialchars($usuario['apellido1']); ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="apellido2" class="form-label">Segundo Apellido</label>
                            <input type="text" name="apellido2" id="apellido2" value="<?php echo htmlspecialchars($usuario['apellido2']); ?>" class="form-control">
                        </div>
                    </div>

                    <!-- Fila: Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" class="form-control">
                    </div>

                    <!-- Fila: Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" placeholder="Ej: Calle Principal, 123" class="form-control">
                    </div>

                    <!-- Fila: Cód. Postal y Ciudad -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="codigoPostal" class="form-label">Cód. Postal</label>
                            <input type="text" name="codigoPostal" id="codigoPostal" value="<?php echo htmlspecialchars($usuario['codigoPostal']); ?>" placeholder="Ej: 07001" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" id="ciudad" value="<?php echo htmlspecialchars($usuario['ciudad']); ?>" placeholder="Ej: Palma de Mallorca" class="form-control">
                        </div>
                    </div>

                    <!-- Fila: País -->
                    <div class="mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" name="pais" id="pais" value="<?php echo htmlspecialchars($usuario['pais']); ?>" placeholder="Ej: España" class="form-control">
                    </div>
                    
                    <!-- Botón de Guardar -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Columna 2: Formulario de Contraseña -->
    <div class="col-lg-4">
        <div class="card shadow-sm border">
            <div class="card-body p-4">
                <h2 class="card-title h4 mb-3 border-bottom pb-2">
                    Cambiar Contraseña
                </h2>
                
                <!-- El 'action' apunta al método 'actualizarContrasena' -->
                <form action="<?php echo APP_URL; ?>/usuario/actualizarContrasena" method="POST">
                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="nueva_contrasena" id="nueva_contrasena" placeholder="Introduce tu nueva contraseña" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" placeholder="Vuelve a escribir la contraseña" class="form-control">
                    </div>

                    <!-- Botón de Actualizar Contraseña -->
                    <div class="text-end mt-4">
                        <button type="submit" class_alias="btn btn-dark px-4" class="btn btn-dark px-4">
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<?php if ($textoMensaje): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertElement = document.getElementById('tempralAlert');
            
            if (alertElement) {
                setTimeout(() => {
                    alertElement.style.opacity = '0'; 
                    alertElement.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        alertElement.remove();
                    }, 500); 
                    
                }, 2000);
            }
        });
    </script>
<?php endif; ?>