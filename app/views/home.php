<?php
$pageTitle = 'Catálogo';
include BASE_PATH . '/app/views/layout/header.php';
?>

<!-- Hero Banner -->
<div class="hero-banner text-white py-5 mb-4">
    <div class="container text-center py-3">
        <h1 class="display-5 fw-bold">Tecnología para todos</h1>
        <p class="lead mb-4">Notebooks, tablets, accesorios y más al mejor precio.</p>
    </div>
</div>

<div class="container" id="catalogo">

    <!-- Título -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-12">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-bag-heart text-warning"></i> Nuestros productos
                <span class="badge bg-secondary ms-2"><?= count($productos) ?></span>
            </h4>
        </div>
    </div>

    <!-- Grid de productos -->
    <div id="gridProductos" class="row g-4">
        <?php foreach ($productos as $p): ?>
        <div class="col-6 col-md-4 col-lg-3 producto-card">
            <div class="card h-100 border-0 shadow-sm card-hover"
                 onclick="window.location='<?= BASE_URL ?>/index.php?controller=producto&action=detalle&id=<?= $p->id ?>'"
                 style="cursor:pointer;">
                <div class="card-img-wrapper bg-light text-center p-3" style="height:180px; overflow:hidden;">
                    <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($p->imagen) ?>"
                         alt="<?= htmlspecialchars($p->nombre) ?>"
                         class="img-fluid h-100 object-fit-contain"
                         onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
                </div>
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-warning text-dark mb-1 align-self-start small">
                        <?= htmlspecialchars($p->categoria) ?>
                    </span>
                    <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($p->nombre) ?></h6>
                    <p class="text-muted small mb-2 flex-grow-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        <?= htmlspecialchars($p->descripcion) ?>
                    </p>
                    <p class="fw-bold fs-5 text-dark mb-2">
                        $<?= number_format($p->precio, 0, ',', '.') ?>
                    </p>

                    <?php if ($p->stock > 0): ?>
                        <span class="badge bg-success-subtle text-success small mb-2">
                            <i class="bi bi-check-circle"></i> Stock: <?= $p->stock ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger small mb-2">
                            <i class="bi bi-x-circle"></i> Sin stock
                        </span>
                    <?php endif; ?>

                    <div class="d-flex gap-1">
                        <?php if ($p->stock > 0): ?>
                        <button class="btn btn-dark btn-sm w-100 btn-agregar-carrito"
                                data-id="<?= $p->id ?>"
                                data-nombre="<?= htmlspecialchars($p->nombre) ?>"
                                onclick="event.stopPropagation()">
                            <i class="bi bi-cart-plus"></i> Agregar al carrito
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Sin resultados (solo si backend no trae productos) -->
    <?php if (empty($productos)): ?>
    <div class="text-center py-5">
        <i class="bi bi-search display-4 text-muted"></i>
        <p class="text-muted mt-2">No hay productos disponibles.</p>
    </div>
    <?php endif; ?>

</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="toastCarrito" class="toast align-items-center text-white border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMensaje"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>