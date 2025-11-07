<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reserva;
use App\Models\Hotel;

class PerfilController extends Controller
{
    protected $reservaModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->requiereLoginGuard();
        $this->reservaModel = new Reserva();
        $this->hotelModel   = new Hotel();
    }


    public function listarReservas()
    {
        $user_email = $_SESSION['user_email'];
        $user_id    = $_SESSION['user_id'];

        if ($user_email === 'admin@islatransfers.com') {
            $reservas = $this->reservaModel->getTodasReservas(); //adicionar las fucnionalidades extras de admim
        } else {
            $reservas = $this->reservaModel->getReservasPorEmail($user_email);
        }

        // Obtener hoteles para traducir id_destino a nombre
        $hoteles = $this->hotelModel->getAll();
        $hotelesMap = [];
        foreach ($hoteles as $hotel) {
            $hotelesMap[$hotel['id_hotel']] = $hotel['usuario'];
        }

        $this->loadView('user/mis_reservas', [
            'reservas'   => $reservas,
            'hotelesMap' => $hotelesMap,
            'user_id'    => $user_id
        ]);
    }
}
