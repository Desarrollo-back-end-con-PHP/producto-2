<?php
$hoteles = $data['hoteles'] ?? [];
$titulo = $data['title'] ?? 'Gestión de Hoteles';
require 'nav.php';
?>

<h1 class="display-6 fw-bold mb-4"><?php echo htmlspecialchars($titulo); ?></h1>

<div class="row g-5">

    <!-- Crear Nuevo Hotel -->
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

    <!-- Hoteles Existentes -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h3 class="fw-light my-2">Hoteles Existentes</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="tabla-hoteles">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Hotel (Usuario)</th>
                                <th>Comisión (%)</th>
                                <th>Contraseña</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($hoteles)): ?>
                                <?php foreach ($hoteles as $hotel): ?>
                                    <tr data-id="<?php echo $hotel['id_hotel']; ?>">
                                        <td class="id"><?php echo htmlspecialchars($hotel['id_hotel']); ?></td>
                                        <td class="usuario"><?php echo htmlspecialchars($hotel['usuario']); ?></td>
                                        <td class="comision"><?php echo htmlspecialchars($hotel['comision']); ?></td>
                                        <td class="password">********</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning btn-editar" type="button" onclick="editarFila(this)">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>

                                            <form action="<?php echo APP_URL; ?>/admin/eliminarHotel/<?php echo $hotel['id_hotel']; ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este hotel?');">
                                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay hoteles registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editarFila(btn) {
    const fila = btn.closest('tr');
    const id = fila.dataset.id;

    // Si ya está en edición, no hacer nada
    if (fila.classList.contains('editing')) return;

    fila.classList.add('editing');

    const usuario = fila.querySelector('.usuario').textContent;
    const comision = fila.querySelector('.comision').textContent;

    // Guardar contenido original
    fila.dataset.originalUsuario = usuario;
    fila.dataset.originalComision = comision;

    fila.querySelector('.usuario').innerHTML = `<input type="text" class="form-control form-control-sm" name="usuario" value="${usuario}" required>`;
    fila.querySelector('.comision').innerHTML = `<input type="number" class="form-control form-control-sm" name="comision" min="0" max="100" step="1" value="${comision}" required>`;
    fila.querySelector('.password').innerHTML = `<input type="password" class="form-control form-control-sm" name="password" placeholder="Nueva contraseña (opcional)">`;

    // Cambiar botones
    btn.innerHTML = '<i class="bi bi-check-lg"></i> Guardar';
    btn.classList.remove('btn-warning');
    btn.classList.add('btn-success');
    btn.onclick = function() { guardarFila(fila, id); };

    const eliminarBtn = fila.querySelector('form button');
    eliminarBtn.disabled = true; // bloquear eliminar mientras se edita
}

function guardarFila(fila, id) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo APP_URL; ?>/admin/editarHotelPost/' + id;

    // Crear inputs ocultos
    const inputs = fila.querySelectorAll('input');
    inputs.forEach(input => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = input.name;
        hidden.value = input.value;
        form.appendChild(hidden);
    });

    document.body.appendChild(form);
    form.submit();
}
</script>
