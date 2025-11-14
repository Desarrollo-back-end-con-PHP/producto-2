<!-- app/views/admin/dashboard.php -->
<?php require 'nav.php'; ?>

<h1 class="display-6 fw-bold mb-4">Información General</h1>

<!-- Stats Cards Row -->
<div class="row g-4">

    <!-- Card 1: Pending Reservations -->
    <div class="col-md-6 col-lg-4">
        <div class="card text-bg-primary shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fs-4">Pending Reservations</h5>
                <?php echo $pendingReservations; ?> 
                <p class="card-text display-4 fw-bold">8</p>
                <a href="<?php echo APP_URL; ?>/reserva/misreservas" class="stretched-link text-white">View details</a>
            </div>
        </div>
    </div>

    <!-- Card 2: Monthly Revenue -->
    <div class="col-md-6 col-lg-4">
        <div class="card text-bg-success shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fs-4">Revenue (This Month)</h5>
                <?php echo $monthlyRevenue; ?> 
                <p class="card-text display-4 fw-bold">$1,250</p>
                <a href="#" class="stretched-link text-white">View reports</a>
            </div>
        </div>
    </div>

    <!-- Card 3: New Users -->
    <div class="col-md-12 col-lg-4">
        <div class="card text-bg-info shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fs-4">New Users (Today)</h5>
                <?php echo $newUsersToday; ?> 
                <p class="card-text display-4 fw-bold">12</p>
                <a href="#" class="stretched-link text-white">Manage users</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card text-bg-secondary shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fs-4">Hoteles Activos</h5>
                <p class="card-text display-4 fw-bold"><?php echo $data['totalHoteles'] ?? 0; ?></p>
                <a href="<?php echo APP_URL; ?>/admin/hoteles" class="stretched-link text-white">Gestionar Hoteles</a>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Reservations (Table) -->
<div class="card shadow-sm border-0 rounded-lg mt-5">
    <div class="card-header">
        <h3 class="fw-light my-2">Upcoming Reservations</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Date</th>
                        <th scope="col">Origin</th>
                        <th scope="col">Destination</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['reservas'])): ?>
                        
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay reservas próximas.</td>
                        </tr>

                    <?php else: ?>
                        
                        <?php foreach ($data['reservas'] as $reserva): ?>
                            <?php
                                // Lógica para origen y destino
                                $origen = 'N/D';
                                $destino = 'N/D';
                                $fecha = 'N/D';
                                $hora = '';

                                // 1 = Aeropuerto a Hotel
                                if ($reserva['id_tipo_reserva'] == 1) {
                                    $origen = 'Aeropuerto';
                                    $destino = $data['hotelesMap'][$reserva['id_destino']] ?? 'Hotel Desconocido';
                                    $fecha = $reserva['fecha_entrada'];
                                    $hora = $reserva['hora_entrada'];
                                } 
                                // 2 = Hotel a Aeropuerto
                                else if ($reserva['id_tipo_reserva'] == 2) {
                                    $origen = $data['hotelesMap'][$reserva['id_destino']] ?? 'Hotel Desconocido';
                                    $destino = 'Aeropuerto';
                                    $fecha = $reserva['fecha_vuelo_salida'];
                                    $hora = $reserva['hora_recogida'];
                                }
                                // 3 = Ida y Vuelta (se podría mostrar solo la ida)
                                else if ($reserva['id_tipo_reserva'] == 3) {
                                    $origen = 'Aeropuerto (Ida y Vuelta)';
                                    $destino = $data['hotelesMap'][$reserva['id_destino']] ?? 'Hotel Desconocido';
                                    $fecha = $reserva['fecha_entrada'];
                                    $hora = $reserva['hora_entrada'];
                                }
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($reserva['localizador']); ?></strong></td>
                                
                                <td><?php echo htmlspecialchars($reserva['email_cliente']); ?></td>
                                
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fecha))); ?> - <?php echo htmlspecialchars(date('H:i', strtotime($hora))); ?></td>
                                
                                <td><?php echo htmlspecialchars($origen); ?></td>
                                
                                <td><?php echo htmlspecialchars($destino); ?></td>
                                
                                <td><span class="badge bg-success">Confirmada</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>