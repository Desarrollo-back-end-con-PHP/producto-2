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
                <!-- A number from PHP would go here, e.g., <?php echo $pendingReservations; ?> -->
                <p class="card-text display-4 fw-bold">8</p>
                <a href="<?php echo APP_URL; ?>/admin/reservas" class="stretched-link text-white">View details</a>
            </div>
        </div>
    </div>

    <!-- Card 2: Monthly Revenue -->
    <div class="col-md-6 col-lg-4">
        <div class="card text-bg-success shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title fs-4">Revenue (This Month)</h5>
                <!-- A number from PHP would go here, e.g., <?php echo $monthlyRevenue; ?> -->
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
                <!-- A number from PHP would go here, e.g., <?php echo $newUsersToday; ?> -->
                <p class="card-text display-4 fw-bold">12</p>
                <a href="#" class="stretched-link text-white">Manage users</a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- Card 4: Hoteles -->
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
<div class="card shadow-sm border-0 rounded-lg mt-5">
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
                    <!-- TODO: Load data dynamically -->
                    <tr>
                        <td>#1024</td>
                        <td>Ana García</td>
                        <td>28/10/2025 - 10:30</td>
                        <td>Airport</td>
                        <td>Hotel Palace</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#1023</td>
                        <td>Carlos Pérez</td>
                        <td>28/10/2025 - 11:15</td>
                        <td>Hotel Sol</td>
                        <td>Airport</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>