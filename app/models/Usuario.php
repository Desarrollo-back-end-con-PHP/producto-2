<?php

namespace App\Models;

use App\Core\Model;

class Usuario extends Model
{
    /**
     * función para registrar usuario
     */
    public function registrar($nombre, $email, $password)
    {
        //Se hashea la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        //para respetar el not null de la BBDD se rellenará con default
        $apellido1_default = '';
        $apellido2_default = '';
        $direccion_default = '';
        $codigoPostal_default = '';
        $ciudad_default = '';
        $pais_default = '';

        //Consulta
        $sql = "INSERT INTO transfer_viajeros (nombre,email,password, apellido1, apellido2, direccion, codigoPostal, ciudad, pais) VALUES (?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);

        //si hay un error se sale
        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }
        //se asocian los parámetros, sss indica 3 strings
        $stmt->bind_param("sssssssss", $nombre, $email, $password_hash, $apellido1_default, $apellido2_default, $direccion_default, $codigoPostal_default, $ciudad_default, $pais_default);

        try {
            $stmt->execute();
            return true; //usuario registrado
        } catch (\mysqli_sql_exception $e) {

            if ($e->getCode() === 1062) {
                return false; // codigo de entidad duplicada, email duplicado error.
            } else {
                error_log("Error al ejecutar el registro, email duplicado: " . $e->getMessage());
                return false;
            }
        }
    }

    /**
     * Función para verificar las credenciales para login
     */
    public function verificarCredenciales($email, $password)
    {
        //se prepara la consulta
        $sql = "SELECT * FROM transfer_viajeros WHERE email = ?";
        $stmt = $this->db->prepare($sql);

        //si hay un error se sale
        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows !== 1) {
            return false; //no se encontró ningún  usuario con ese email
        }

        $usuario = $resultado->fetch_assoc();

        //verificar contraseña
        if (password_verify($password, $usuario['password'])) { //se compara el password con el hash de la bbdd
            return $usuario;
        } else {
            return false; //contraseña incorrecta
        }
    }




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
