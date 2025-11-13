<?php
$reservas   = $data['reservas'] ?? [];
$hotelesMap = $data['hotelesMap'] ?? [];
$user_id    = $data['user_id'] ?? 0;
?>

<!-- Título -->
<div class="mb-4">
    <h1>Mis Reservas</h1>
    <p class="fs-5 text-muted">
        Aquí puedes ver tus reservas actuales y detalles de cada trayecto.
    </p>
</div>

<?php if (!empty($reservas)): ?>
    <div class="row g-4">
        <?php foreach ($reservas as $reserva): ?>
            <div class="col-lg-6">
                <div class="card shadow-sm border">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">
                            Tipo de Trayecto:
                            <?php
                            $tipos = [
                                1 => 'Aeropuerto a Hotel (Llegada)',
                                2 => 'Hotel a Aeropuerto (Salida)',
                                3 => 'Ida y Vuelta (Llegada y Salida)'
                            ];
                            echo $tipos[$reserva['id_tipo_reserva']] ?? 'Desconocido';
                            ?>
                        </h5>

                        <p><strong>Hotel:</strong> <?= htmlspecialchars($hotelesMap[$reserva['id_destino']] ?? '-') ?></p>
                        <p><strong>Número de Viajeros:</strong> <?= $reserva['num_viajeros'] ?></p>

                        <p>
                            <strong>Creado por:</strong>
                            <?= isset($data['reservasAdminMap'][$reserva['id_reserva']]) ? 'Administrador' : 'Usuario' ?>

                            (<?= htmlspecialchars($reserva['email_cliente']) ?>)
                        </p>

                        <p>
                            <strong>Localizador:</strong>
                            <span class="fw-bold text-primary"><?= htmlspecialchars($reserva['localizador']) ?></span>
                        </p>


                        <?php if ($reserva['id_tipo_reserva'] == 1 || $reserva['id_tipo_reserva'] == 3): ?>
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="mb-2">Detalles de Llegada</h6>
                                <p><strong>Fecha:</strong> <?= $reserva['fecha_entrada'] ?? '-' ?></p>
                                <p><strong>Hora:</strong> <?= $reserva['hora_entrada'] ?? '--:--' ?></p>
                                <p><strong>Vuelo:</strong> <?= $reserva['numero_vuelo_entrada'] ?? '-' ?></p>
                                <p><strong>Origen:</strong> <?= $reserva['origen_vuelo_entrada'] ?? '-' ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($reserva['id_tipo_reserva'] == 2 || $reserva['id_tipo_reserva'] == 3): ?>
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="mb-2">Detalles de Salida</h6>
                                <p><strong>Fecha:</strong> <?= $reserva['fecha_vuelo_salida'] ?? '-' ?></p>
                                <p><strong>Hora:</strong> <?= $reserva['hora_vuelo_salida'] ?? '--:--' ?></p>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <div class="text-end">
                            <a href="<?php echo APP_URL; ?>/reserva/editar/<?php echo $reserva['id_reserva']; ?>" class="btn btn-primary btn-sm">
                                Editar
                            </a>
                            <form action="<?php echo APP_URL; ?>/reserva/cancelar/<?php echo $reserva['id_reserva']; ?>" method="POST" style="display: inline;">

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Estás seguro de que quieres cancelar esta reserva?');">
                                    Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info shadow-sm" role="alert">
        No tienes reservas todavía.
    </div>
<?php endif; ?>