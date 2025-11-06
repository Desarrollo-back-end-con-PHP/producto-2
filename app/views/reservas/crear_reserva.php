<?php
// Asumimos que el controlador nos pasa $hoteles (de HotelModel)
// y opcionalmente un $mensaje de error.
$hoteles = $data['hoteles'] ?? [];
$mensaje = $data['mensaje'] ?? null;
?>

<!-- Título de la Página -->
<div class="mb-4">
    <h1>Crear Nueva Reserva</h1>
    <p class="fs-5 text-muted">
        Por favor, rellena los detalles de tu trayecto.
    </p>
</div>

<!-- Contenedor de Alerta (si existe) -->
<?php if ($mensaje && $mensaje === 'error_creacion'): ?>
    <div id="alertaTemporal" class="alert alert-danger shadow-sm" role="alert">
        Hubo un error al crear tu reserva. Por favor, inténtalo de nuevo.
    </div>
<?php endif; ?>

<!-- Tarjeta con el Formulario -->
<div class="card shadow-sm border">
    <div class="card-body p-4">
        
        <form action="<?php echo APP_URL; ?>/reserva/crearReserva" method="POST" id="formReserva">

            <!-- Fila 1: Tipo de Reserva y Destino -->
            <div class="row g-3 mb-3">
                
                <!-- Tipo de Reserva -->
                <div class="col-md-6">
                    <label for="id_tipo_reserva" class="form-label">Tipo de Trayecto</label>
                    <select id="id_tipo_reserva" name="id_tipo_reserva" class="form-select" required>
                        <option value="" selected disabled>Selecciona un tipo...</option>
                        <option value="1">1. Aeropuerto a Hotel (Llegada)</option>
                        <option value="2">2. Hotel a Aeropuerto (Salida)</option>
                        <option value="3">3. Ida y Vuelta (Llegada y Salida)</option>
                    </select>
                </div>

                <!-- Hotel/Destino -->
                <div class="col-md-6">
                    <label for="id_destino" class="form-label">Hotel (Destino o Recogida)</label>
                    <select id="id_destino" name="id_destino" class="form-select" required>
                        <option value="" selected disabled>Selecciona un hotel...</option>
                        
                        <?php if (!empty($hoteles)): ?>
                            <?php foreach ($hoteles as $hotel): ?>
                                <option value="<?php echo htmlspecialchars($hotel['id_hotel']); ?>">
                                    <?php echo htmlspecialchars($hotel['usuario']); // Asumimos que 'usuario' es el nombre del hotel ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay hoteles disponibles</option>
                        <?php endif; ?>
                        
                    </select>
                </div>
            </div>

            <!-- Fila 2: Número de Viajeros -->
            <div class="mb-3">
                <label for="num_viajeros" class="form-label">Número de Viajeros</label>
                <input type="number" id="num_viajeros" name="num_viajeros" class="form-control" min="1" value="1" required>
            </div>

            <hr class="my-4">

            <!-- Sección de Llegada (Oculta por defecto) -->
            <div id="camposLlegada" style="display: none;">
                <h4 class="h5">Detalles de Llegada (Aeropuerto a Hotel)</h4>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="fecha_entrada" class="form-label">Fecha de Llegada</label>
                        <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="hora_entrada" class="form-label">Hora de Llegada (Aterrizaje)</label>
                        <input type="time" id="hora_entrada" name="hora_entrada" class="form-control">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="numero_vuelo_entrada" class="form-label">Número de Vuelo</label>
                        <input type="text" id="numero_vuelo_entrada" name="numero_vuelo_entrada" class="form-control" placeholder="Ej: IB3901">
                    </div>
                    <div class="col-md-6">
                        <label for="origen_vuelo_entrada" class="form-label">Aeropuerto de Origen</label>
                        <input type="text" id="origen_vuelo_entrada" name="origen_vuelo_entrada" class="form-control" placeholder="Ej: Madrid (MAD)">
                    </div>
                </div>
            </div>

            <!-- Sección de Salida (Oculta por defecto) -->
            <div id="camposSalida" style="display: none;">
                <h4 class="h5">Detalles de Salida (Hotel a Aeropuerto)</h4>
                
                 <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="fecha_vuelo_salida" class="form-label">Fecha de Salida</Vuelo></label>
                        <input type="date" id="fecha_vuelo_salida" name="fecha_vuelo_salida" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="hora_vuelo_salida" class="form-label">Hora de Salida (Despegue)</label>
                        <input type="time" id="hora_vuelo_salida" name="hora_vuelo_salida" class="form-control">
                    </div>
                     <!-- (Según requisitos, aquí iría "hora de recogida", pero el controller espera 'hora_vuelo_salida') -->
                </div>
            </div>

            <!-- Botón de Enviar -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Confirmar Reserva</button>
            </div>

        </form>
    </div>
</div>


<!-- JavaScript para mostrar/ocultar campos del formulario -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const tipoReservaSelect = document.getElementById('id_tipo_reserva');
        const camposLlegada = document.getElementById('camposLlegada');
        const camposSalida = document.getElementById('camposSalida');

        // Inputs dentro de las secciones
        const inputsLlegada = camposLlegada.querySelectorAll('input, select');
        const inputsSalida = camposSalida.querySelectorAll('input, select');

        function toggleCampos() {
            const tipo = tipoReservaSelect.value;

            // Por defecto, ocultamos todo y deshabilitamos 'required'
            camposLlegada.style.display = 'none';
            camposSalida.style.display = 'none';
            inputsLlegada.forEach(input => input.required = false);
            inputsSalida.forEach(input => input.required = false);

            if (tipo === '1') {
                // Tipo 1: Aeropuerto a Hotel
                camposLlegada.style.display = 'block';
                inputsLlegada.forEach(input => input.required = true);
            
            } else if (tipo === '2') {
                // Tipo 2: Hotel a Aeropuerto
                camposSalida.style.display = 'block';
                inputsSalida.forEach(input => input.required = true);
            
            } else if (tipo === '3') {
                // Tipo 3: Ida y Vuelta
                camposLlegada.style.display = 'block';
                camposSalida.style.display = 'block';
                inputsLlegada.forEach(input => input.required = true);
                inputsSalida.forEach(input => input.required = true);
            }
        }

        // Escuchar cambios en el select
        tipoReservaSelect.addEventListener('change', toggleCampos);

        // Llamar una vez al cargar por si hay valores preseleccionados (aunque aquí no aplica)
        toggleCampos();
    });
</script>

<!-- Script para la alerta de error (si existe) -->
<?php if ($mensaje): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.getElementById('alertaTemporal');
        if (alertElement) {
            setTimeout(() => {
                alertElement.style.transition = 'opacity 0.5s ease-out';
                alertElement.style.opacity = '0';
                setTimeout(() => alertElement.remove(), 500);
            }, 4000); // 4 segundos
        }
    });
    </script>
<?php endif; ?>