
USE isla_transfers;

-- modificación tabla trasfer_viajeros para usarla para el login
ALTER TABLE `transfer_viajeros`
ADD UNIQUE(`email`); -- el email debe ser único para el login

ALTER TABLE `transfer_viajeros`
ADD COLUMN `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP; -- se añade como buena práctica.



-- añade una columna con estado de la reserva
ALTER TABLE `transfer_reservas`
ADD COLUMN `status` ENUM('pendiente', 'confirmada', 'cancelada', 'completada')
NOT NULL DEFAULT 'pendiente'
AFTER `id_vehiculo`;

-- creacion de una tabla reservas de administrador para registrar que reservas fueran creadas por el admim
CREATE TABLE reserva_admin (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,              
    id_admin INT NOT NULL,                
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_reserva) REFERENCES transfer_reservas(id_reserva) ON DELETE CASCADE
) ENGINE=InnoDB;