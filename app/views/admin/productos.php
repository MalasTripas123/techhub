<?php
// ── ADMIN: LISTADO DE PRODUCTOS ──────────────────────────────
$pageTitle = 'Gestión de Productos';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Panel Admin</h6>
                    <nav class="nav flex-column gap-1">
                        <a href="?controller=admin&action=dashboard" class="nav-link text-dark">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="?controller=admin&action=productos" class="nav-link text-dark fw-semibold">
                            <i class="bi bi-box-seam"></i> Productos
                        </a>
                        <a href="?controller=producto&action=catalogo" class="nav-link text-muted">
                            <i class="bi bi-shop"></i> Ver tienda
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-box-seam text-warning"></i> Productos
                </h3>
                <a href="<?= BASE_URL ?>/index.php?controller=admin&action=crearForm" class="btn btn-dark">
                    <i class="bi bi-plus-circle"></i> Nuevo producto
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th class="text-end">Precio</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($p->imagen) ?>"
                                             width="45" height="45" class="object-fit-contain bg-light rounded p-1"
                                             onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
                                        <span class="fw-semibold"><?= htmlspecialchars($p->nombre) ?></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($p->categoria) ?></span></td>
                                <td class="text-end fw-bold">$<?= number_format($p->precio, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php if ($p->stock > 5): ?>
                                        <span class="badge bg-success"><?= $p->stock ?></span>
                                    <?php elseif ($p->stock > 0): ?>
                                        <span class="badge bg-warning text-dark"><?= $p->stock ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">0</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= BASE_URL ?>/index.php?controller=admin&action=editarForm&id=<?= $p->id ?>"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/index.php?controller=admin&action=eliminar&id=<?= $p->id ?>"
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>
