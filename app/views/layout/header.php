<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Asegúrate de que esta ruta es correcta y el archivo CSS se está cargando -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">

    <title><?php echo isset($title) ? $title : 'Isla Transfers'; ?></title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
            <div class="container-fluid">
                <a class="text-light navbar-brand fw-bold" href="<?php echo APP_URL; ?>/home">Isla Transfers</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- 
                  ESTA ES LA SECCIÓN CORREGIDA
                  Hemos eliminado 'div.left-side' y 'div.right-side'
                  y hemos creado dos UL separados.
                -->
                <div class="collapse navbar-collapse" id="navbarNav">

                    <!-- 1. MENÚ IZQUIERDA: con 'me-auto' -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="text-light nav-link active" href="<?php echo APP_URL; ?>/home">Inicio</a>
                        </li>
                    </ul>

                    <!-- 2. MENÚ DERECHA: con 'ms-auto' -->
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_id'])) : ?>

                            <?php
                            // --- Lógica para el avatar ---
                            $userName = $_SESSION['user_name'];
                            $firstLetter = mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'));
                            $firstLetterSafe = htmlspecialchars($firstLetter);
                            ?>

                            <!-- Dropdown de Usuario -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar me-2">
                                        <?php echo $firstLetterSafe; ?>
                                    </div>
                                    <span class="text-light d-none d-lg-inline">
                                        Hola, <?php echo htmlspecialchars($userName); ?>
                                    </span>
                                </a>
                                <!-- DROPDOWN -->
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/usuario/mostrarPerfil">Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/Reserva/listarReservas">Mis Reservas</a></li>

                                    <?php
                                    // --- Opciónes Admin ---
                                    if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@islatransfers.com'): ?>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                    <?php endif; ?>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/auth/logout">Cerrar Sesión</a></li>
                                </ul>
                            </li>
                            <!-- Fin Dropdown de Usuario -->

                        <?php else: ?>
                            <!-- Links para usuario no logueado -->
                            <li class="nav-item">
                                <a class="text-light nav-link" href="<?php echo APP_URL; ?>/auth/login">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="text-light nav-link" href="<?php echo APP_URL; ?>/auth/register">Registro</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!-- Fin Menú Derecha -->

                </div> <!-- Fin .collapse -->
            </div> <!-- Fin .container-fluid -->
        </nav>
    </header>



    <main class="container py-4">
        <!-- El resto de tu página va aquí -->