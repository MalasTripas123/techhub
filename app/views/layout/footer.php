<!-- ── FOOTER ────────────────────────────────────────────── -->
</main>
<footer class="bg-dark text-light mt-auto py-4">
    <div class="container">
        <div class="row gy-3">
            <div class="col-md-4">
                <h5 class="fw-bold text-white"><i class="bi bi-cpu-fill text-warning"></i> TechHub Store</h5>
                <p class="text-light small opacity-75">Tu tienda de tecnología de confianza. Los mejores productos al mejor precio.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-white">Categorías</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Notebooks" class="text-light text-decoration-none footer-link">Notebooks</a></li>
                    <li><a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Tablets" class="text-light text-decoration-none footer-link">Tablets</a></li>
                    <li><a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Accesorios" class="text-light text-decoration-none footer-link">Accesorios</a></li>
                    <li><a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo&categoria=Monitores" class="text-light text-decoration-none footer-link">Monitores</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-white">Contacto</h6>
                <ul class="list-unstyled small text-light">
                    <li class="mb-1"><i class="bi bi-envelope text-warning"></i> contacto@techhub.cl</li>
                    <li class="mb-1"><i class="bi bi-telephone text-warning"></i> +56 9 1234 5678</li>
                    <li><i class="bi bi-geo-alt text-warning"></i> Santiago, Chile</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary opacity-25">
        <p class="text-center text-light small mb-0 opacity-75">
            &copy; Gisselle Sepúlveda - Renata Zambrano - Gaspar Vieira
        </p>
        <p class="text-center text-light small mb-0 opacity-75">
            &copy; <?= date('Y') ?> TechHub Store — Proyecto Evaluación Sumativa 2 · AIEP
        </p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS propio -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>

</html>