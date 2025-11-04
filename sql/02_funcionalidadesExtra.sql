
USE isla_transfers;

-- crea tabla de usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `email` VARCHAR (100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- añade una columna con estado de la reserva
ALTER TABLE `transfer_reservas`
ADD COLUMN `status` ENUM('pendiente', 'confirmada', 'cancelada', 'completada')
NOT NULL DEFAULT 'pendiente'
AFTER `id_vehiculo`;