<?php 

namespace App\Models;

use App\Core\Model;
class Trayecto extends Model {

  
    protected $table = 'transfer_tipo_reserva'; 

    public function getAllTrayectos() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_tipo_reserva";
        $result = $this->db->query($sql);

        if ($result === false) {
            return []; 
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id_tipo_reserva = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}



