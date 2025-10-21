-- añade una columna con estado de la reserva
ALTER TABLE `transfer_reservas`
ADD COLUMN `status` ENUM('pendiente', 'confirmada', 'cancelada', 'completada')
NOT NULL DEFAULT 'pendiente'
AFTER `id_vehiculo`;