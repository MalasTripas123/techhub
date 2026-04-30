<?php
$pageTitle = 'Nuevo Producto';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>/index.php?controller=admin&action=productos" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
        <h3 class="fw-bold mb-0"><i class="bi bi-plus-circle text-warning"></i> Nuevo Producto</h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="<?= BASE_URL ?>/index.php?controller=admin&action=crear"
                          enctype="multipart/form-data" novalidate>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre del producto *</label>
                            <input type="text" name="nombre" class="form-control" required
                                   placeholder="Ej: MacBook Air M3">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"
                                      placeholder="Describe el producto..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Precio (CLP) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio" class="form-control"
                                           min="0" step="1" required placeholder="99990">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Stock *</label>
                                <input type="number" name="stock" class="form-control"
                                       min="0" required placeholder="10">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Categoría *</label>
                            <select name="categoria" class="form-select" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="Notebooks">Notebooks</option>
                                <option value="Tablets">Tablets</option>
                                <option value="Monitores">Monitores</option>
                                <option value="Accesorios">Accesorios</option>
                                <option value="Almacenamiento">Almacenamiento</option>
                                <option value="Audio">Audio</option>
                                <option value="Gaming">Gaming</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Imagen del producto</label>
                            <input type="file" name="imagen" class="form-control"
                                   accept="image/jpeg,image/png,image/webp"
                                   id="inputImagen">
                            <small class="text-muted">Formatos: JPG, PNG, WEBP. Si no subes imagen se usará la predeterminada.</small>
                            <!-- Preview de imagen -->
                            <div id="previewImagen" class="mt-2" style="display:none;">
                                <img id="imgPreview" src="" alt="Preview" class="img-thumbnail" style="max-height:120px;">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark px-4 fw-bold">
                                <i class="bi bi-check-circle"></i> Guardar producto
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
// Preview de imagen antes de subir
document.getElementById('inputImagen').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('previewImagen').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>
