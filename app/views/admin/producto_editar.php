<?php
$pageTitle = 'Editar Producto';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>/index.php?controller=admin&action=productos" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
        <h3 class="fw-bold mb-0"><i class="bi bi-pencil-square text-warning"></i> Editar Producto</h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="<?= BASE_URL ?>/index.php?controller=admin&action=editar"
                          enctype="multipart/form-data" novalidate>

                        <input type="hidden" name="id" value="<?= $producto->id ?>">
                        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($producto->imagen) ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre del producto *</label>
                            <input type="text" name="nombre" class="form-control" required
                                   value="<?= htmlspecialchars($producto->nombre) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($producto->descripcion) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Precio (CLP) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio" class="form-control"
                                           min="0" required value="<?= $producto->precio ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Stock *</label>
                                <input type="number" name="stock" class="form-control"
                                       min="0" required value="<?= $producto->stock ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Categoría *</label>
                            <select name="categoria" class="form-select" required>
                                <?php foreach (['Notebooks','Tablets','Monitores','Accesorios','Almacenamiento','Audio','Gaming'] as $cat): ?>
                                    <option value="<?= $cat ?>" <?= $producto->categoria === $cat ? 'selected' : '' ?>>
                                        <?= $cat ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Imagen actual</label>
                            <div class="mb-2">
                                <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($producto->imagen) ?>"
                                     id="imgActual" alt="Imagen actual" class="img-thumbnail"
                                     style="max-height:100px;"
                                     onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
                            </div>
                            <input type="file" name="imagen" class="form-control"
                                   accept="image/jpeg,image/png,image/webp" id="inputImagen">
                            <small class="text-muted">Sube una nueva imagen solo si deseas cambiarla.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark px-4 fw-bold">
                                <i class="bi bi-check-circle"></i> Guardar cambios
                            </button>
                            <a href="<?= BASE_URL ?>/index.php?controller=admin&action=productos"
                               class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('inputImagen').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('imgActual').src = e.target.result; };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>
