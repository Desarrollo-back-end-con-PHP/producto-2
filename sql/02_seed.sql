-- Usar la base de datos correcta
USE wordpress4;

-- --------------------------------------------------------
-- PASO 1: VACIAR TABLAS (IDEMPOTENTE)
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE `transfer_reservas`;
TRUNCATE TABLE `transfer_precios`;
TRUNCATE TABLE `tranfer_hotel`;
TRUNCATE TABLE `transfer_zona`;
TRUNCATE TABLE `transfer_viajeros`;
TRUNCATE TABLE `transfer_vehiculo`;
TRUNCATE TABLE `transfer_tipo_reserva`;
SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- PASO 2: INSERCIÓN DE DATOS
-- (Se omiten los IDs al insertar para que funcione el AUTO_INCREMENT)
-- --------------------------------------------------------

-- DATOS PARA 'transfer_zona'
INSERT INTO `transfer_zona` (`descripcion`) VALUES
('Palma (Aeropuerto)'),
('Zona Norte (Alcudia, Pollensa)'),
('Zona Este (Cala d''Or, Cala Millor)'),
('Zona Oeste (Andratx, Paguera)');

-- DATOS PARA 'transfer_viajeros'
INSERT INTO `transfer_viajeros` (`nombre`, `apellido1`, `apellido2`, `direccion`, `codigoPostal`, `ciudad`, `pais`, `email`, `password`) VALUES
('Ana', 'García', 'Pérez', 'Avenida Principal 45', '07005', 'Palma', 'España', 'ana.garcia@email.com', '$2y$10$Y9g/4.aP.j/b.n.i.k.M.t.g.h.A.X.I.E.b.L.O.B.N'),
('Carlos', 'Ruiz', 'Martínez', 'Paseo Marítimo 10', '07600', 'El Arenal', 'España', 'carlos.ruiz@email.com', '$2y$10$Y9g/4.aP.j/b.n.i.k.M.t.g.h.A.X.I.E.b.L.O.B.N'),
('Laura', 'Schmidt', 'Müller', 'Hauptstrasse 15', '10115', 'Berlín', 'Alemania', 'laura.schmidt@email.de', '$2y$10$Y9g/4.aP.j/b.n.i.k.M.t.g.h.A.X.I.E.b.L.O.B.N');

-- DATOS PARA 'tranfer_hotel'
INSERT INTO `tranfer_hotel` (`id_zona`, `Comision`, `usuario`, `password`) VALUES
(2, 10, 'Hotel Iberostar Alcudia', '$2y$10$T.s.R.a.P.O.v.C.l.O.t.c.S.B.a.g.h.c.P.B.S.Z.I'),
(2, 12, 'Hotel Meliá Pollensa', '$2y$10$T.s.R.a.P.O.v.C.l.O.t.c.S.B.a.g.h.c.P.B.S.Z.I'),
(3, 10, 'Hotel Riu Cala d''Or', '$2y$10$T.s.R.a.P.O.v.C.l.O.t.c.S.B.a.g.h.c.P.B.S.Z.I'),
(4, 15, 'Hotel Hesperia Andratx', '$2y$10$T.s.R.a.P.O.v.C.l.O.t.c.S.B.a.g.h.c.P.B.S.Z.I');

-- DATOS PARA 'transfer_tipo_reserva'
INSERT INTO `transfer_tipo_reserva` (`descripcion`) VALUES
('Aeropuerto a Hotel (Llegada)'),
('Hotel a Aeropuerto (Salida)'),
('Ida y Vuelta (Llegada y Salida)');

-- DATOS PARA 'transfer_vehiculo'
INSERT INTO `transfer_vehiculo` (`descripcion`, `email_conductor`, `password`) VALUES
('Sedan Standard (4 pax)', 'conductor1@islatransfers.com', '$2y$10$A.b.C.d.E.f.G.h.I.j.K.l.M.n.O.p.Q.r.S.t.U.v.W'),
('Minivan (8 pax)', 'conductor2@islatransfers.com', '$2y$10$A.b.C.d.E.f.G.h.I.j.K.l.M.n.O.p.Q.r.S.t.U.v.W'),
('Vehículo Adaptado (PMR)', 'conductor3@islatransfers.com', '$2y$10$A.b.C.d.E.f.G.h.I.j.K.l.M.n.O.p.Q.r.S.t.U.v.W');

-- DATOS PARA 'transfer_precios'
-- (Esta tabla ya era correcta, 'id_precios' era AI)
INSERT INTO `transfer_precios` (`id_vehiculo`, `id_hotel`, `Precio`) VALUES
(1, 1, 50), (1, 2, 55), (1, 3, 60), (1, 4, 45),
(2, 1, 80), (2, 2, 85), (2, 3, 90), (2, 4, 75);

-- DATOS PARA 'transfer_reservas'
INSERT INTO `transfer_reservas` (`localizador`, `id_hotel`, `id_tipo_reserva`, `email_cliente`, `fecha_reserva`, `fecha_modificacion`, `id_destino`, `fecha_entrada`, `hora_entrada`, `numero_vuelo_entrada`, `origen_vuelo_entrada`, `hora_vuelo_salida`, `fecha_vuelo_salida`, `num_viajeros`, `id_vehiculo`) VALUES
('IT-ABC123', NULL, 1, 'ana.garcia@email.com', '2025-11-01 10:00:00', '2025-11-01 10:00:00', 1, '2025-11-10', '14:30:00', 'IB3902', 'Madrid (MAD)', NULL, NULL, 2, 1),
('IT-XYZ789', 3, 3, 'carlos.ruiz@email.com', '2025-11-02 15:00:00', '2025-11-02 15:00:00', 3, '2025-11-12', '09:15:00', 'RYR1001', 'Londres (STN)', '11:00:00', '2025-11-19', 5, 2);

COMMIT;