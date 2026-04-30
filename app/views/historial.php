<?php
// ── HISTORIAL DE COMPRAS ─────────────────────────────────────
$pageTitle = 'Mis Compras';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-clock-history text-warning"></i> Historial de compras</h2>

    <?php if (empty($ordenes)): ?>
        <div class="text-center py-5">
            <i class="bi bi-bag-x display-3 text-muted"></i>
            <h4 class="mt-3 text-muted">Aún no tienes compras</h4>
            <a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo" class="btn btn-dark mt-3">
                <i class="bi bi-shop"></i> Ir al catálogo
            </a>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th># Orden</th>
                            <th>Fecha</th>
                            <th class="text-center">Items</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ordenes as $orden): ?>
                        <tr>
                            <td><strong>#<?= $orden->id ?></strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($orden->created_at)) ?></td>
                            <td class="text-center"><?= $orden->total_items ?> productos</td>
                            <td class="text-end fw-bold">$<?= number_format($orden->total, 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?= Orden::badgeEstado($orden->estado) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>
