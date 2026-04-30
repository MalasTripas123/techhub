<?php
$pageTitle = 'Panel Admin';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Panel Admin</h6>
                    <nav class="nav flex-column gap-1">
                        <a href="?controller=admin&action=dashboard" class="nav-link text-dark fw-semibold active-link">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="?controller=admin&action=productos" class="nav-link text-dark">
                            <i class="bi bi-box-seam"></i> Productos
                        </a>
                        <a href="?controller=producto&action=catalogo" class="nav-link text-muted">
                            <i class="bi bi-shop"></i> Ver tienda
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-10">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-speedometer2 text-warning"></i> Dashboard
            </h3>

            <!-- Tarjetas de estadísticas -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <i class="bi bi-box-seam display-5 text-warning"></i>
                        <h2 class="fw-bold mt-2 mb-0"><?= $totalProductos ?></h2>
                        <p class="text-muted small mb-0">Productos</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <i class="bi bi-people display-5 text-info"></i>
                        <h2 class="fw-bold mt-2 mb-0"><?= $totalUsuarios ?></h2>
                        <p class="text-muted small mb-0">Usuarios</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <i class="bi bi-bag-check display-5 text-success"></i>
                        <h2 class="fw-bold mt-2 mb-0"><?= $totalOrdenes ?></h2>
                        <p class="text-muted small mb-0">Órdenes</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <i class="bi bi-currency-dollar display-5 text-danger"></i>
                        <h2 class="fw-bold mt-2 mb-0 fs-4"><?= $totalVentas ?></h2>
                        <p class="text-muted small mb-0">Ventas totales</p>
                    </div>
                </div>
            </div>

            <!-- Últimas órdenes -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-list-ul"></i> Últimas órdenes
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($ordenes, 0, 10) as $o): ?>
                            <tr>
                                <td><strong>#<?= $o->id ?></strong></td>
                                <td><?= htmlspecialchars($o->cliente) ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($o->email) ?></td>
                                <td class="text-end fw-bold">$<?= number_format($o->total, 0, ',', '.') ?></td>
                                <td class="text-center"><?= Orden::badgeEstado($o->estado) ?></td>
                                <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($o->created_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ordenes)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No hay órdenes aún.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>
