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
        $sql = "SELECT id_hotel, usuario, id_zona FROM tranfer_hotel ORDER BY usuario ASC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta de hoteles: " . $this->db->error);
            return false;
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        
        // Devolvemos todos los resultados como un array asociativo
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
    
}