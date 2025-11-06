-- Usar la base de datos
USE isla_transfers;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 1. Tabla de Zonas
--
DROP TABLE IF EXISTS `transfer_zona`;
CREATE TABLE `transfer_zona` (
  `id_zona` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL, -- Corregido (era INT)
  PRIMARY KEY (`id_zona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 2. Tabla de Hoteles
--
DROP TABLE IF EXISTS `tranfer_hotel`;
CREATE TABLE `tranfer_hotel` (
  `id_hotel` int(11) NOT NULL AUTO_INCREMENT,
  `id_zona` int(11) DEFAULT NULL,
  `Comision` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL, -- Corregido (era INT)
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id_hotel`),
  KEY `FK_HOTEL_ZONA` (`id_zona`),
  CONSTRAINT `FK_HOTEL_ZONA` FOREIGN KEY (`id_zona`) REFERENCES `transfer_zona` (`id_zona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 3. Tabla de Tipos de Reserva
--
DROP TABLE IF EXISTS `transfer_tipo_reserva`;
CREATE TABLE `transfer_tipo_reserva` (
  `id_tipo_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL, -- Corregido (era INT y tenía tilde)
  PRIMARY KEY (`id_tipo_reserva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 4. Tabla de Vehículos
--
DROP TABLE IF EXISTS `transfer_vehiculo`;
CREATE TABLE `transfer_vehiculo` (
  `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL, -- Corregido (tenía tilde)
  `email_conductor` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 5. Tabla de Viajeros (Usuarios)
--
DROP TABLE IF EXISTS `transfer_viajeros`;
CREATE TABLE `transfer_viajeros` (
  `id_viajero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(100) NOT NULL,
  `apellido2` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `codigoPostal` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id_viajero`),
  UNIQUE KEY `email` (`email`) -- Añadido para evitar emails duplicados
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 6. Tabla de Precios
--
DROP TABLE IF EXISTS `transfer_precios`;
CREATE TABLE `transfer_precios` (
  `id_precios` int(11) NOT NULL AUTO_INCREMENT, -- Corregido (ahora es PK y AI)
  `id_vehiculo` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `Precio` int(11) NOT NULL,
  PRIMARY KEY (`id_precios`),
  KEY `FK_PRECIOS_HOTEL` (`id_hotel`),
  KEY `FK_PRECIOS_VEHICULO` (`id_vehiculo`),
  CONSTRAINT `FK_PRECIOS_HOTEL` FOREIGN KEY (`id_hotel`) REFERENCES `tranfer_hotel` (`id_hotel`),
  CONSTRAINT `FK_PRECIOS_VEHICULO` FOREIGN KEY (`id_vehiculo`) REFERENCES `transfer_vehiculo` (`id_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 7. Tabla de Reservas
--
DROP TABLE IF EXISTS `transfer_reservas`;
CREATE TABLE `transfer_reservas` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `localizador` varchar(100) NOT NULL,
  `id_hotel` int(11) DEFAULT NULL COMMENT 'Es el hotel que realiza la reserva',
  `id_tipo_reserva` int(11) NOT NULL,
  `email_cliente` varchar(100) NOT NULL, -- Corregido (era INT)
  `fecha_reserva` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `id_destino` int(11) NOT NULL COMMENT 'Es el hotel de destino del viajero',
  `fecha_entrada` date DEFAULT NULL, -- Permitimos NULL
  `hora_entrada` time DEFAULT NULL, -- Permitimos NULL
  `numero_vuelo_entrada` varchar(50) DEFAULT NULL,
  `origen_vuelo_entrada` varchar(50) DEFAULT NULL,
  `hora_vuelo_salida` time DEFAULT NULL, -- Corregido (era TIMESTAMP)
  `fecha_vuelo_salida` date DEFAULT NULL, -- Permitimos NULL
  `num_viajeros` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `FK_RESERVAS_DESTINO` (`id_destino`),
  KEY `FK_RESERVAS_HOTEL` (`id_hotel`),
  KEY `FK_RESERVAS_TIPO` (`id_tipo_reserva`),
  KEY `FK_RESERVAS_VEHICULO` (`id_vehiculo`),
  CONSTRAINT `FK_RESERVAS_DESTINO` FOREIGN KEY (`id_destino`) REFERENCES `tranfer_hotel` (`id_hotel`),
  CONSTRAINT `FK_RESERVAS_HOTEL` FOREIGN KEY (`id_hotel`) REFERENCES `tranfer_hotel` (`id_hotel`),
  CONSTRAINT `FK_RESERVAS_TIPO` FOREIGN KEY (`id_tipo_reserva`) REFERENCES `transfer_tipo_reserva` (`id_tipo_reserva`),
  CONSTRAINT `FK_RESERVAS_VEHICULO` FOREIGN KEY (`id_vehiculo`) REFERENCES `transfer_vehiculo` (`id_vehiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;