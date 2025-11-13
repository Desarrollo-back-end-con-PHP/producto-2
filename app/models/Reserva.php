<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Reserva;

class Reserva extends Model
{

    // recibe la conexión que le pasa el controlador
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtener todas las reservas asociadas a un usuario particular
     * según su email.
     */
    public function getReservasPorEmail($email)
    {
        $sql = "SELECT * FROM transfer_reservas WHERE email_cliente = ? AND status !='cancelada'"; //Se modifica para no mostrar las canceladas (eliminadas)
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTodasReservas()
    {
        $sql = "SELECT * FROM transfer_reservas WHERE status !='cancelada' ORDER BY fecha_reserva DESC"; //Se modifica para no mostrar las canceladas (eliminadas)
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene todas las reservas (llegadas o salidas) que caen
     * dentro de un rango de fechas específico.
     * Ideal para el calendario.
     *
     * @param string $fecha_inicio Formato 'Y-m-d'
     * @param string $fecha_fin Formato 'Y-m-d'
     * @return array|bool
     */
    public function getReservasPorRango($fecha_inicio, $fecha_fin)
    {
        /*
         * Buscamos reservas donde:
         * 1. La fecha de LLEGADA esté en el rango.
         * O
         * 2. La fecha de SALIDA esté en el rango.
         * Y
         * 3. No estén canceladas.
         * También unimos con la tabla de hoteles para coger el nombre.
         */
        $sql = "SELECT r.*, h.usuario as nombre_hotel 
                FROM transfer_reservas r
                LEFT JOIN tranfer_hotel h ON r.id_destino = h.id_hotel
                WHERE r.status != 'cancelada' 
                AND (
                       (r.fecha_entrada >= ? AND r.fecha_entrada <= ?)
                       OR 
                       (r.fecha_vuelo_salida >= ? AND r.fecha_vuelo_salida <= ?)
                )
                ORDER BY r.fecha_entrada, r.hora_entrada, r.fecha_vuelo_salida";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta de rango: " . $this->db->error);
            return false;
        }

        // Usamos las mismas fechas para ambos rangos (llegada y salida)
        $stmt->bind_param("ssss", $fecha_inicio, $fecha_fin, $fecha_inicio, $fecha_fin);
        
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Crear una nueva reserva hecha por un usuario particular.
     * (el localizador se genera automáticamente)
     */
    public function crearReserva(
        $id_tipo_reserva,
        $id_destino,
        $fecha_entrada = null,
        $hora_entrada = null,
        $num_viajeros = 1,
        $id_vehiculo = null,
        $numero_vuelo_entrada = null,
        $origen_vuelo_entrada = null,
        $fecha_vuelo_salida = null,
        $hora_vuelo_salida = null,
        $email_cliente = null,
        $numero_vuelo_salida = null,
        $hora_recogida = null
    ) {
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
            return false;
        }

        $email_cliente = $email_cliente ?: $_SESSION['user_email']; 
        $localizador = uniqid("LOC-");
        $fecha_actual = date("Y-m-d H:i:s");

        // Ajustar campos según tipo de reserva
        if ($id_tipo_reserva == 1) { // Llegada
            $fecha_vuelo_salida = null;
            $hora_vuelo_salida = null;
            $numero_vuelo_salida = null;
            $hora_recogida = null;
        } elseif ($id_tipo_reserva == 2) { // Salida
            $fecha_entrada = null;
            $hora_entrada = null;
            $numero_vuelo_entrada = null;
            $origen_vuelo_entrada = null;
        }

        // Valores por defecto
        $id_vehiculo = $id_vehiculo ?: 1;
        $num_viajeros = $num_viajeros ?: 1;

        // Normalizar fechas y horas: si no hay, poner NULL
        $fecha_entrada = $fecha_entrada ? date('Y-m-d', strtotime($fecha_entrada)) : null;
        $hora_entrada = $hora_entrada ? date('H:i:s', strtotime($hora_entrada)) : null;
        $fecha_vuelo_salida = $fecha_vuelo_salida ? date('Y-m-d', strtotime($fecha_vuelo_salida)) : null;
        $hora_vuelo_salida = $hora_vuelo_salida ? date('H:i:s', strtotime($hora_vuelo_salida)) : null;
        $hora_recogida = $hora_recogida ? date('H:i:s', strtotime($hora_recogida)) : null;

        $sql = "INSERT INTO transfer_reservas 
        (localizador, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion, id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada, fecha_vuelo_salida, hora_vuelo_salida, num_viajeros, id_vehiculo, numero_vuelo_salida, hora_recogida)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }

        // Para manejar NULL correctamente en MySQL, se usa "s" y pasar null directamente
        $stmt->bind_param(
            "sisssissssssiiiss",
            $localizador,
            $id_tipo_reserva,
            $email_cliente,
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
            $id_vehiculo,
            $numero_vuelo_salida,
            $hora_recogida
        );

        return $stmt->execute();
    }
    public function getUltimaReservaId()
    {
    return $this->db->insert_id;
}

    public function guardarReservaAdmin($id_reserva, $codigo_admin)
{
    $sql = "INSERT INTO reserva_admin (id_reserva, id_admin) VALUES (?, ?)";
    $stmt = $this->db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("is", $id_reserva, $codigo_admin);
        return $stmt->execute();
    }
    return false;
}

    /**
     * obener una reserva específica por su id
     */
    public function getReservaPorId($id_reserva)
    {
        // Preparar la consulta para buscar por resrva
        $sql = "SELECT * FROM transfer_reservas WHERE id_reserva = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al prepara la consulta: " . $this->db->error);
            return null;
        }

        //asociar el id a la consulta
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();

        $result = $stmt->get_result();

        //devuelve la fila de la reserva
        return $result->fetch_assoc();
    }

    public function actualizarReserva($id_reserva, $datos)
    {

        // obtenemos los datos
        $id_tipo_reserva = $datos['id_tipo_reserva'] ?? null;
        $id_destino = $datos['id_destino'] ?? null;
        $num_viajeros = $datos['num_viajeros'] ?? 1;

        $fecha_entrada = $datos['fecha_entrada'] ?? null;
        $hora_entrada = $datos['hora_entrada'] ?? null;
        $numero_vuelo_entrada = $datos['numero_vuelo_entrada'] ?? null;
        $origen_vuelo_entrada = $datos['origen_vuelo_entrada'] ?? null;

        $fecha_vuelo_salida = $datos['fecha_vuelo_salida'] ?? null;
        $hora_vuelo_salida = $datos['hora_vuelo_salida'] ?? null;

        $numero_vuelo_salida = $datos['numero_vuelo_salida'] ?? null;
        $hora_recogida = $datos['hora_recogida'] ?? null;

        $fecha_actual = date("Y-m-d H:i:s"); // Actualizamos la fecha de modificación

        if ($id_tipo_reserva == 1) { // 1 - Llegada
            $fecha_vuelo_salida = null;
            $hora_vuelo_salida = null;
            $numero_vuelo_salida = null;
            $hora_recogida = null;
        } elseif ($id_tipo_reserva == 2) { // 2 - Salida
            $fecha_entrada = null;
            $hora_entrada = null;
            $numero_vuelo_entrada = null;
            $origen_vuelo_entrada = null;
        }

        //normalizar fechas y horas (igual que en crearReserva)
        $fecha_entrada = $fecha_entrada ? date('Y-m-d', strtotime($fecha_entrada)) : null;
        $hora_entrada = $hora_entrada ? date('H:i:s', strtotime($hora_entrada)) : null;
        $fecha_vuelo_salida = $fecha_vuelo_salida ? date('Y-m-d', strtotime($fecha_vuelo_salida)) : null;
        $hora_vuelo_salida = $hora_vuelo_salida ? date('H:i:s', strtotime($hora_vuelo_salida)) : null;
        $hora_recogida = $hora_recogida ? date('H:i:s', strtotime($hora_recogida)) : null;

        //sql update
        $sql = "UPDATE transfer_reservas SET
            id_tipo_reserva = ?,
            id_destino = ?,
            fecha_modificacion = ?,
            fecha_entrada = ?,
            hora_entrada = ?,
            numero_vuelo_entrada = ?,
            origen_vuelo_entrada = ?,
            fecha_vuelo_salida = ?,
            hora_vuelo_salida = ?,
            num_viajeros = ?,
            numero_vuelo_salida = ?, 
            hora_recogida = ?        
            WHERE id_reserva = ?"; // La condición es el ID

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta UPDATE: " . $this->db->error);
            return false;
        }

        //se vinculan los parámetros
$stmt->bind_param(
            "iisssssssissi", 
            $id_tipo_reserva,
            $id_destino,
            $fecha_actual,
            $fecha_entrada,
            $hora_entrada,
            $numero_vuelo_entrada,
            $origen_vuelo_entrada,
            $fecha_vuelo_salida,
            $hora_vuelo_salida,
            $num_viajeros,
            $numero_vuelo_salida,
            $hora_recogida,
            $id_reserva 
        );

        return $stmt->execute();
    }


    public function getReservasAdminIds()
    {
        $sql = "SELECT id_reserva FROM reserva_admin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Usando mysqli
        $result = $stmt->get_result();
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id_reserva'];
        }

        return $ids;
    }

    public function cancelarReserva($id_reserva)
    {
        $nuevo_estado = 'cancelada';
        $fecha_actual = date("Y-m-d H:i:s"); //se actualiza la fecha de modificación

        $sql = "UPDATE transfer_reservas SET status = ?, fecha_modificacion = ? WHERE id_reserva = ?"; //Se hace soft delete con update y no con delete

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta de cancelar resrva: " . $this->db->error);
            return false;
        }

        //se vinculan los parametros
        $stmt->bind_param("ssi", $nuevo_estado, $fecha_actual, $id_reserva);
        return $stmt->execute();
    }
}
