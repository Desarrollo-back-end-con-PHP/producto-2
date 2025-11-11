<?php
$hotel = $data['hotel'];
$titulo = $data['titulo'] ?? 'Editar Hotel';
?>

<h1 class="display-6 fw-bold mb-4"><?php echo htmlspecialchars($titulo); ?></h1>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h3 class="fw-light my-2">Datos del Hotel</h3>
            </div>
            <div class="card-body">

                <form action="<?php echo APP_URL; ?>/admin/editarHotelPost/<?php echo $hotel['id_hotel']; ?>" method="POST">

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Nombre del Hotel (Login)</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                            value="<?php echo htmlspecialchars($hotel['usuario']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="comision" class="form-label">Comisión (%)</label>
                        <input type="number" class="form-control" id="comision" name="comision"
                            value="<?php echo htmlspecialchars($hotel['Comision']); ?>" required>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Dejar vacío para no cambiar la contraseña actual">
                        <div class="form-text">
                            Solo rellena este campo si quieres cambiar la contraseña.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo APP_URL; ?>/admin/hoteles" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>