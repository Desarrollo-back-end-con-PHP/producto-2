<?php

namespace App\Models;


class Usuario
{
    /**
     * constructor
     */
    private $db;

    public function __construct($db_connection)
    {
        // Creamos la conexión a la base de datos 
        $this->db = $db_connection;
    }

    //public function registrar($usuario)


    /**
     *Obtener los datos personales del usuario por su ID.
     */
    public function obtenerDatosPersonales($id_viajero)
    {
        // Consulta SQL para traer todos los campos personales del viajero
        $sql = "SELECT nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, email 
                FROM transfer_viajeros
                WHERE id_viajero = ?";

        // Preparamos la consulta y asociamos el parámetro
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_viajero);
        $stmt->execute();

        // Obtenemos los resultados y devolvemos una fila asociativa
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /**
     * Actualizar los datos personales del usuario (nombre, email, dirección, etc.)
     */
    public function actualizarDatosPersonales($id_viajero, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email)
    {
        $sql = "UPDATE transfer_viajeros 
                SET nombre = ?, apellido1 = ?, apellido2 = ?, direccion = ?, codigoPostal = ?, ciudad = ?, pais = ?, email = ?
                WHERE id_viajero = ?";

        $stmt = $this->db->prepare($sql);

        // "ssssssssi" → 8 strings y 1 entero
        $stmt->bind_param("ssssssssi", $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $id_viajero);

        return $stmt->execute();
    }

    /**
     * Actualizar la contraseña del usuario.
     * La contraseña se guarda siempre encriptada con password_hash().
     */
    public function actualizarContrasena($id_viajero, $nuevaContrasena)
    {
        // Hasheamos (encriptamos) la nueva contraseña antes de guardarla
        $hashContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        $sql = "UPDATE transfer_viajeros SET password = ? WHERE id_viajero = ?";
        $stmt = $this->db->prepare($sql);

        // "si" → 1 string (contraseña) y 1 entero (id)
        $stmt->bind_param("si", $hashContrasena, $id_viajero);

        return $stmt->execute();
    }
}
