<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/styles.css">

    <title><?php echo isset($title) ? $title : 'Isla Transfers'; ?></title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="<?php echo APP_URL; ?>/home">Isla Transfers</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <!-- Modificación para añadir la sesión -->
                        <?php if (isset($_SESSION['user_id'])) : ?>
                            <!-- si el usuario si esta logeado -->
                            <li class="nav-item">
                                <!-- Mostramos su nombre (guardado en la sesión) -->
                                <span class="navbar-text text-white me-3">
                                    Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL; ?>/usuario/mostrarPerfil">Mi Perfil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL; ?>/auth/logout">Cerrar Sesión</a>
                            </li>


                        <?php else: ?>
                            <!-- Si el usuario no esta logeado-->
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo APP_URL; ?>/home">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL; ?>/auth/login">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APP_URL; ?>/auth/register">Registro</a>
                            </li>

                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-4">