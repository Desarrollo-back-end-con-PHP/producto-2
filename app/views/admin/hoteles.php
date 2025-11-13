<?php
// $data que pasa el AdminController
$hoteles = $data['hoteles'] ?? [];
$titulo = $data['titulo'] ?? 'Gestión de Hoteles';
require 'nav.php';
?>

<h1 class="display-6 fw-bold mb-4"><?php echo htmlspecialchars($titulo); ?></h1>

<div class="row g-5">

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h3 class="fw-light my-2">Crear Nuevo Hotel</h3>
            </div>
            <div class="card-body">

                <form action="<?php echo APP_URL; ?>/admin/crearHotelPost" method="POST">

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Nombre del Hotel (Usuario)</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="comision" class="form-label">Comisión (%)</label>
                        <input type="number" class="form-control" id="comision" name="comision" min="0" max="100" step="1" value="10" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Guardar Hotel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h3 class="fw-light my-2">Hoteles Existentes</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Hotel (Usuario)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($hoteles)): ?>
                                <?php foreach ($hoteles as $hotel): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($hotel['id_hotel']); ?></td>
                                        <td><?php echo htmlspecialchars($hotel['usuario']); ?></td>

                                        <td>
                                            <a href="<?php echo APP_URL; ?>/admin/editarHotel/<?php echo $hotel['id_hotel']; ?>"
                                                class="btn btn-sm btn-warning"
                                                title="Editar">
                                                <i class="bi bi-pencil"></i>Editar
                                            </a>

                                            <form action="<?php echo APP_URL; ?>/admin/eliminarHotel/<?php echo $hotel['id_hotel']; ?>"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Estás seguro de que quieres eliminar este hotel?');">

                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash"></i>Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No hay hoteles registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>