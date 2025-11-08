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
                        <!-- Tipo de trayecto -->
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

                        <!-- Hotel y número de viajeros -->
                        <p><strong>Hotel:</strong> <?= htmlspecialchars($hotelesMap[$reserva['id_destino']] ?? '-') ?></p>
                        <p><strong>Número de Viajeros:</strong> <?= $reserva['num_viajeros'] ?></p>

                        <!-- Creado por -->
                        <p>
                            <strong>Creado por:</strong>
                            <?= isset($data['reservasAdminMap'][$reserva['id_reserva']]) ? 'Administrador' : 'Usuario' ?>
                        </p>

                        <!-- Detalles de llegada -->
                        <?php if ($reserva['id_tipo_reserva'] == 1 || $reserva['id_tipo_reserva'] == 3): ?>
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="mb-2">Detalles de Llegada</h6>
                                <p><strong>Fecha:</strong> <?= $reserva['fecha_entrada'] ?? '-' ?></p>
                                <p><strong>Hora:</strong> <?= $reserva['hora_entrada'] ?? '--:--' ?></p>
                                <p><strong>Vuelo:</strong> <?= $reserva['numero_vuelo_entrada'] ?? '-' ?></p>
                                <p><strong>Origen:</strong> <?= $reserva['origen_vuelo_entrada'] ?? '-' ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Detalles de salida -->
                        <?php if ($reserva['id_tipo_reserva'] == 2 || $reserva['id_tipo_reserva'] == 3): ?>
                            <div class="mt-3 p-3 bg-light rounded">
                                <h6 class="mb-2">Detalles de Salida</h6>
                                <p><strong>Fecha:</strong> <?= $reserva['fecha_vuelo_salida'] ?? '-' ?></p>
                                <p><strong>Hora:</strong> <?= $reserva['hora_vuelo_salida'] ?? '--:--' ?></p>
                            </div>
                        <?php endif; ?>
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
