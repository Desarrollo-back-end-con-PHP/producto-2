<?php

namespace App\Models;

use App\Core\Model;
class Reserva extends Model
{

    // recibe la conexión que le pasa el controlador
    public function __construct()
    {
        parent::__construct();}

    /**
     * Obtener todas las reservas asociadas a un usuario particular
     * según su email.
     */
    public function obtenerReservasPorEmail($email)
    {
        $sql = "SELECT 
                    r.id_reserva,
                    r.localizador,
                    r.fecha_reserva,
                    r.fecha_entrada,
                    r.hora_entrada,
                    r.numero_vuelo_entrada,
                    r.origen_vuelo_entrada,
                    r.fecha_vuelo_salida,
                    r.hora_vuelo_salida,
                    r.num_viajeros,
                    r.status, 
                    v.Descripción AS descripcion_vehiculo, 
                    CASE 
                        WHEN r.id_hotel IS NULL THEN 'Creada por el usuario'
                        ELSE 'Creada por el administrador'
                    END AS origen_reserva
                FROM transfer_reservas r
                INNER JOIN transfer_viajeros tv ON r.email_cliente = tv.id_viajero 
                LEFT JOIN transfer_vehiculo v ON r.id_vehiculo = v.id_vehiculo
                WHERE tv.email = ? 
                ORDER BY r.fecha_reserva DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crear una nueva reserva hecha por un usuario particular.
     * (el localizador se genera automáticamente)
     */
    public function crearReserva($email_cliente, $id_tipo_reserva, $id_destino, $fecha_entrada, $hora_entrada, $num_viajeros, $id_vehiculo, $numero_vuelo_entrada, $origen_vuelo_entrada, $fecha_vuelo_salida, $hora_vuelo_salida)
    {

        $localizador = uniqid("LOC-"); // genera un código único
        $fecha_actual = date("Y-m-d H:i:s");

        // Buscamos el id del viajero por su email
        $sqlEmail = "SELECT id_viajero FROM transfer_viajeros WHERE email = ?";
        $stmtEmail = $this->db->prepare($sqlEmail);
        $stmtEmail->bind_param("s", $email_cliente);
        $stmtEmail->execute();
        $resultado = $stmtEmail->get_result();
        $viajero = $resultado->fetch_assoc();

        if (!$viajero) {
            return false; // Si no existe ese usuario
        }

        $id_viajero = $viajero['id_viajero'];

        // Insertar la reserva
        $sql = "INSERT INTO transfer_reservas 
                (localizador, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion, id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada, fecha_vuelo_salida, hora_vuelo_salida, num_viajeros, id_vehiculo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "siissssssssiii",
            $localizador,
            $id_tipo_reserva,
            $id_viajero,
            $fecha_actual,
            $fecha_actual,
            $id_destino,
            $fecha_entrada,
            $hora_entrada,
            $numero_vuelo_entrada,
            $origen_vuelo_entrada,
            $fecha_vuelo_salida,
            $hora_vuelo_salida,
            $num_viajeros,
            $id_vehiculo
        );

        return $stmt->execute();
    }
}
