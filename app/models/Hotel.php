<?php

namespace App\Models;

use App\Core\Model;

/**
 * Clase Hotel
 * Gestiona la información de los hoteles (clientes corporativos) y los destinos.
 * La tabla principal es 'tranfer_hotel'.
 */
class Hotel extends Model
{
    /**
     * Obtiene una lista de todos los hoteles de la base de datos.
     * Esta función es útil para rellenar un <select> en un formulario de reserva.
     * * @return array|bool Devuelve un array de hoteles o false si hay un error/no hay resultados.
     */
    public function getAll()
    {
        $sql = "SELECT id_hotel, usuario, Comision AS comision, id_zona 
                FROM tranfer_hotel 
                WHERE status = 'activo'
                ORDER BY usuario ASC";
    
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta de hoteles: " . $this->db->error);
            return false;
        }
    
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }



    /**
     * Obtiene los detalles de un hotel específico por su ID.
     * * @param int $id_hotel El ID del hotel a buscar.
     * @return array|bool Devuelve un array asociativo con los datos del hotel o false si no se encuentra.
     */
    public function getById(int $id_hotel)
    {
        $sql = "SELECT id_hotel, usuario, Comision, id_zona FROM tranfer_hotel WHERE id_hotel = ?";

        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta de hotel por ID: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("i", $id_hotel);
        $stmt->execute();

        $resultado = $stmt->get_result();

        // Devolvemos una única fila
        return $resultado->fetch_assoc();
    }

    public function crearhotel($nombre_usuario, $password, $comision)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql_hotel = "INSERT INTO tranfer_hotel (usuario, password, Comision, id_zona) VALUES (?,?,?,NULL)";

        $stmt_hotel = $this->db->prepare($sql_hotel);
        if ($stmt_hotel === false) {
            error_log("Error al preparar el hotel: " . $this->db->error);
            return false;
        }

        $stmt_hotel->bind_param("ssd", $nombre_usuario, $password_hash, $comision);

        try {
            return $stmt_hotel->execute();
        } catch (\mysqli_sql_exception $e) {
            error_log("Error al crear hotel: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarHotel($id_hotel, $datos)
    {
        $nombre_usuario = $datos['usuario'] ?? null;
        $comision = $datos['comision'] ?? 0;
        $password = $datos['password'] ?? null;

        $sql_parts = [
            'usuario = ?',
            'Comision = ?'
        ];
        $params = [$nombre_usuario, $comision];
        $types = 'sd';

        // Solo añadimos la contraseña si no está vacía
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql_parts[] = 'password = ?';
            $params[] = $password_hash;
            $types .= 's';
        }

        $params[] = $id_hotel;
        $types .= 'i';

        $sql = "UPDATE tranfer_hotel SET " . implode(', ', $sql_parts) . " WHERE id_hotel = ?";

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar (actualizar hotel): " . $this->db->error);
            return false;
        }

        $stmt->bind_param($types, ...$params);

        try {
            return $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            error_log("Error al actualizar hotel: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarHotel($id_hotel)
    {

        $sql = "UPDATE tranfer_hotel SET status = 'inactivo' WHERE id_hotel = ?";

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la eliminación del hotel: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("i", $id_hotel);
        return $stmt->execute();
    }
}
