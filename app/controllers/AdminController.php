<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Models\Hotel;
use App\Models\Reserva;

class AdminController extends Controller
{
    private $userModel;
    protected $hotelModel;
    private $reservaModel;

    public function __construct()
    {
        parent::__construct();

        $this->requiereLoginGuard();
        $this->requiereAdminGuard();

        $this->userModel = new Usuario();
        $this->hotelModel = new Hotel();
        $this->reservaModel = new Reserva();
    }

    public function index()
    {
        $this->dashboard();
    }

public function dashboard()
    {
        // 1. Obtener hoteles y contarlos (esto ya lo tenías)
        $hoteles = $this->hotelModel->getAll();
        $totalHoteles = $hoteles ? count($hoteles) : 0;

        // 2. [NUEVO] Crear el mapa de hoteles (para mostrar nombres en la tabla)
        $hotelesMap = [];
        foreach ($hoteles as $hotel) {
            // Asumiendo que 'usuario' es el nombre del hotel, 
            // igual que en tu ReservaController
            $hotelesMap[$hotel['id_hotel']] = $hotel['usuario']; 
        }

        // 3. [NUEVO] Obtener las reservas
        // Usamos el mismo método que en ReservaController para admin
        $reservas = $this->reservaModel->getTodasReservas();

        // 4. [MODIFICADO] Pasar los nuevos datos a la vista
        $data = [
            'title'          => 'Admin Dashboard',
            'totalHoteles'   => $totalHoteles,
            'reservas'       => $reservas ?? [], // Pasamos las reservas
            'hotelesMap'     => $hotelesMap,     // Pasamos el mapa de hoteles
            'vista_actual'   => 'dashboard'
        ];
        
        $this->loadView('admin/dashboard', $data);
    }

    public function reservations()
    {
        $data = [
            'title' => 'Admin - Gestionar Reservas'
        ];
        $this->loadView('admin/reservations', $data);
    }

public function calendar()
{
    // Obtener la vista y la fecha base desde GET, con fallback a hoy
    $vista = $_GET['vista'] ?? 'mes';
    $ano   = (int)($_GET['ano'] ?? date('Y'));
    $mes   = (int)($_GET['mes'] ?? date('m'));
    $dia   = (int)($_GET['dia'] ?? date('d'));

    try {
        $fecha_base = new \DateTime("$ano-$mes-$dia");
    } catch (\Exception $e) {
        $fecha_base = new \DateTime('now');
    }

    // Calcular fecha_inicio y fecha_fin según la vista
    switch ($vista) {
        case 'dia':
            $fecha_inicio_dt = clone $fecha_base;
            $fecha_fin_dt = clone $fecha_base;
            $titulo_calendario = 'Día ' . $fecha_base->format('d/m/Y');
            break;

        case 'semana':
            $fecha_inicio_dt = (clone $fecha_base)->modify('monday this week');
            $fecha_fin_dt = (clone $fecha_inicio_dt)->modify('+6 days');
            $titulo_calendario = 'Semana del ' . $fecha_inicio_dt->format('d/m') . ' al ' . $fecha_fin_dt->format('d/m/Y');
            break;

        case 'mes':
        default:
            $fecha_inicio_dt = (clone $fecha_base)->modify('first day of this month');
            $fecha_fin_dt = (clone $fecha_base)->modify('last day of this month');

            $formatter = new \IntlDateFormatter(
                'es_ES', \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, "LLLL 'de' yyyy"
            );
            $titulo_calendario = $formatter->format($fecha_base);
            break;
    }

    // Obtener reservas del modelo
    $fecha_inicio = $fecha_inicio_dt->format('Y-m-d');
    $fecha_fin = $fecha_fin_dt->format('Y-m-d');
    $reservas = $this->reservaModel->getReservasPorRango($fecha_inicio, $fecha_fin);

    // Calcular botones de navegación correctamente según la vista
    switch ($vista) {
        case 'dia':
            $fecha_anterior_nav = (clone $fecha_base)->modify('-1 day');
            $fecha_siguiente_nav = (clone $fecha_base)->modify('+1 day');
            break;
        case 'semana':
            $fecha_anterior_nav = (clone $fecha_base)->modify('-1 week');
            $fecha_siguiente_nav = (clone $fecha_base)->modify('+1 week');
            break;
        case 'mes':
        default:
            $fecha_anterior_nav = (clone $fecha_base)->modify('first day of last month');
            $fecha_siguiente_nav = (clone $fecha_base)->modify('first day of next month');
            break;
    }

    // Preparar datos para la vista
    $data = [
        'title'             => 'Admin - Calendario',
        'titulo_calendario' => ucwords($titulo_calendario),
        'reservas'          => $reservas,

        'fecha_anterior_nav' => $fecha_anterior_nav,
        'fecha_siguiente_nav' => $fecha_siguiente_nav,

        'fecha_base'        => $fecha_base,
        'vista'             => $vista,
        'fecha_inicio_obj'  => $fecha_inicio_dt,
        'fecha_fin_obj'     => $fecha_fin_dt,
        'vista_actual'      => 'calendar'
    ];

    $this->loadView('admin/calendar', $data);
}


    // --- Resto de métodos (hoteles, API, etc.) igual que antes ---

    public function hoteles()
    {
        $hoteles = $this->hotelModel->getAll();

        $data = [
            'title' => 'Admin - Gestionar Hoteles',
            'hoteles' => $hoteles,
            'vista_actual' => 'hoteles'
        ];

        $this->loadView('admin/hoteles', $data);
    }

}
