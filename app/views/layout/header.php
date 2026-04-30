<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechHub Store <?= isset($pageTitle) ? '— ' . $pageTitle : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- ── NAVBAR ───────────────────────────────────────────── -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-4" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo">
                <i class="bi bi-cpu-fill text-warning"></i> TechHub
            </a>

            <!-- Botón hamburguesa móvil -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">

                <!-- Barra de búsqueda AJAX (centro) -->
                <div class="mx-auto position-relative" style="max-width:420px; width:100%;">
                    <div class="input-group">
                        <input type="text" id="buscadorNav" class="form-control"
                            placeholder="Buscar productos..." autocomplete="off">
                        <button class="btn btn-warning" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Resultados búsqueda live -->
                    <div id="resultadosBusqueda" style="display:none; position:absolute; top:100%; left:0; width:100%; z-index:9999; margin-top:0.25rem;">
                        <div class="card shadow-lg border-0">
                            <div class="card-body p-2" id="resultadosInner"></div>
                        </div>
                    </div>
                </div>

                <!-- Links de navegación -->
                <ul class="navbar-nav ms-auto align-items-center gap-1">

                    <!-- Catálogo dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo" data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Catálogo
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo">
                                    <i class="bi bi-grid-3x3-gap text-warning"></i> Todos los productos
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Notebooks">
                                    <i class="bi bi-laptop text-muted"></i> Notebooks
                                </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Tablets">
                                    <i class="bi bi-tablet text-muted"></i> Tablets
                                </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Monitores">
                                    <i class="bi bi-display text-muted"></i> Monitores
                                </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Accesorios">
                                    <i class="bi bi-mouse text-muted"></i> Accesorios
                                </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Almacenamiento">
                                    <i class="bi bi-device-hdd text-muted"></i> Almacenamiento
                                </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Audio">
                                    <i class="bi bi-headphones text-muted"></i> Audio
                                </a></li>
                        </ul>
                    </li>

                    <?php if (isset($_SESSION['usuario_id'])): ?>

                        <!-- Carrito -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?= BASE_URL ?>/index.php?controller=carrito&action=ver" style="padding-right: 12px;">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span id="carritoBadge"
                                    class="position-absolute badge rounded-pill bg-warning text-dark"
                                    style="top: 4px; right: 0px; font-size: 0.65rem; <?= Carrito::totalItems() > 0 ? '' : 'display:none' ?>">
                                    <?= Carrito::totalItems() ?>
                                </span>
                            </a>
                        </li>

                        <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="<?= BASE_URL ?>/index.php?controller=admin&action=dashboard">
                                    <i class="bi bi-speedometer2"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Usuario logueado -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?controller=usuario&action=historial">
                                        <i class="bi bi-clock-history"></i> Mis compras
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/index.php?controller=auth&action=logout">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </a></li>
                            </ul>
                        </li>

                    <?php else: ?>

                        <!-- Usuario NO logueado -->
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/index.php?controller=auth&action=loginForm">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm" href="<?= BASE_URL ?>/index.php?controller=auth&action=registroForm">
                                Registrarse
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Mensajes globales -->
    <?php if (!empty($_SESSION['errores']) || !empty($_SESSION['exito'])): ?>
        <div class="container mt-3">
            <?php if (!empty($_SESSION['errores'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['errores'] as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['exito'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['exito']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['exito']); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <main class="flex-grow-1 mb-5">