<?php
$pageTitle = htmlspecialchars($producto->nombre);
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo" class="text-muted text-decoration-none">Catálogo</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=<?= urlencode($producto->categoria) ?>"
                   class="text-muted text-decoration-none"><?= htmlspecialchars($producto->categoria) ?></a>
            </li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($producto->nombre) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Imagen -->
        <div class="col-md-5">
            <div class="bg-light rounded-3 p-4 text-center" style="min-height:320px; display:flex; align-items:center; justify-content:center;">
                <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($producto->imagen) ?>"
                     alt="<?= htmlspecialchars($producto->nombre) ?>"
                     class="img-fluid" style="max-height:300px; object-fit:contain;"
                     onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
            </div>
        </div>

        <!-- Info -->
        <div class="col-md-7">
            <span class="badge bg-warning text-dark mb-2"><?= htmlspecialchars($producto->categoria) ?></span>
            <h1 class="fw-bold fs-3 mb-3"><?= htmlspecialchars($producto->nombre) ?></h1>

            <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($producto->descripcion)) ?></p>

            <!-- Precio -->
            <div class="mb-3">
                <span class="display-6 fw-bold text-dark">
                    $<?= number_format($producto->precio, 0, ',', '.') ?>
                </span>
                <span class="text-muted ms-1">CLP</span>
            </div>

            <!-- Stock -->
            <div class="mb-4">
                <?php if ($producto->stock > 0): ?>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="bi bi-check-circle"></i> En stock (<?= $producto->stock ?> disponibles)
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="bi bi-x-circle"></i> Sin stock
                    </span>
                <?php endif; ?>
            </div>

            <!-- Cantidad y botón agregar -->
            <?php if ($producto->stock > 0): ?>
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="input-group" style="max-width:140px;">
                    <button class="btn btn-outline-secondary" id="btnMenos">−</button>
                    <input type="number" id="cantidadDetalle" class="form-control text-center"
                           value="1" min="1" max="<?= $producto->stock ?>">
                    <button class="btn btn-outline-secondary" id="btnMas">+</button>
                </div>
                <button class="btn btn-dark btn-lg px-4 fw-bold btn-agregar-carrito-detalle"
                        data-id="<?= $producto->id ?>" data-nombre="<?= htmlspecialchars($producto->nombre) ?>">
                    <i class="bi bi-cart-plus"></i> Agregar al carrito
                </button>
            </div>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al catálogo
            </a>
        </div>
    </div>
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

<script>
const BASE_URL = '<?= BASE_URL ?>';
const cantInput = document.getElementById('cantidadDetalle');
const maxStock  = <?= $producto->stock ?>;

document.getElementById('btnMenos')?.addEventListener('click', () => {
    if (parseInt(cantInput.value) > 1) cantInput.value = parseInt(cantInput.value) - 1;
});
document.getElementById('btnMas')?.addEventListener('click', () => {
    if (parseInt(cantInput.value) < maxStock) cantInput.value = parseInt(cantInput.value) + 1;
});

document.querySelector('.btn-agregar-carrito-detalle')?.addEventListener('click', function() {
    const id       = this.dataset.id;
    const cantidad = parseInt(cantInput.value);
    agregarAlCarrito(id, cantidad);
});
</script>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>
