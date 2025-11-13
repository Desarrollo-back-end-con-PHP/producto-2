<?php
// El controlador nos dirá qué vista es la 'activa'
$vista_actual = $data['vista_actual'] ?? 'dashboard';


if (isset($data['fecha_base']) && $vista_actual == 'calendar') {
    $ano_nav = $data['fecha_base']->format('Y');
    $mes_nav = $data['fecha_base']->format('m');
    $dia_nav = $data['fecha_base']->format('d');
} else {
    // Si no, usamos la fecha de hoy
    $ano_nav = date('Y');
    $mes_nav = date('m');
    $dia_nav = date('d');
}
?>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?php if ($vista_actual == 'dashboard') echo 'active'; ?>" 
           href="<?php echo APP_URL; ?>/admin/dashboard">
           <i class="bi bi-bar-chart-line-fill"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($vista_actual == 'calendar') echo 'active'; ?>" 
           href="<?php echo APP_URL; ?>/admin/calendar?vista=mes&ano=<?php echo $ano_nav; ?>&mes=<?php echo $mes_nav; ?>&dia=<?php echo $dia_nav; ?>">
           <i class="bi bi-calendar-week-fill"></i> Calendario
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($vista_actual == 'hoteles') echo 'active'; ?>" 
           href="<?php echo APP_URL; ?>/admin/hoteles">
           <i class="bi bi-building-fill"></i> Gestionar Hoteles
        </a>
    </li>
</ul>
