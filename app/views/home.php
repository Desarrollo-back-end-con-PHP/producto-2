<div class="p-5 mb-4 bg-light rounded-3 text-center shadow-sm">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">
            <?php echo isset($title) ? $title : 'Bienvenido'; ?>
        </h1>
        <p class="fs-5 text-muted">
            <?php echo isset($description) ? $description : 'Tu servicio de traslados de confianza.'; ?>
        </p>
        <a href="<?php echo APP_URL; ?>/registro" class="btn btn-primary btn-lg">¡Reserva ahora!</a>
    </div>
</div>

<!-- Sección de Servicios -->
<div class="container px-4 py-5">
    <h2 class="pb-2 border-bottom text-center">Nuestros Servicios</h2>

    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <!-- Servicio 1 -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h3 class="fs-4 fw-bold">Traslados Aeropuerto-Hotel</h3>
                    <p>Te recogemos en el aeropuerto y te llevamos a tu hotel sin esperas.</p>
                </div>
            </div>
        </div>

        <!-- Servicio 2 -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h3 class="fs-4 fw-bold">Traslados Hotel-Aeropuerto</h3>
                    <p>Programamos tu recogida en el hotel para que llegues a tu vuelo a tiempo.</p>
                </div>
            </div>
        </div>

        <!-- Servicio 3 -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h3 class="fs-4 fw-bold">Ida y Vuelta</h3>
                    <p>Reserva ambos trayectos y olvídate de preocupaciones.</p>
                </div>
            </div>
        </div>
    </div>
</div>