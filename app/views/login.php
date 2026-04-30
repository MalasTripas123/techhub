<?php
// ── LOGIN ────────────────────────────────────────────────────
$pageTitle = 'Iniciar sesión';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-cpu-fill text-warning display-5"></i>
                        <h2 class="fw-bold mt-2">TechHub Store</h2>
                        <p class="text-muted">Inicia sesión en tu cuenta</p>
                    </div>

                    <form method="POST" action="<?= BASE_URL ?>/index.php?controller=auth&action=login" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control"
                                       placeholder="tu@email.com" required
                                       value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="••••••••" required minlength="6">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0 text-muted">
                        ¿No tienes cuenta?
                        <a href="<?= BASE_URL ?>/index.php?controller=auth&action=registroForm" class="text-dark fw-bold">
                            Regístrate gratis
                        </a>
                    </p>

                    <!-- Credenciales de prueba -->
                    <div class="alert alert-light border mt-3 small text-muted text-center">
                        <strong>Demo:</strong><br>
                        Admin: admin@techhub.cl / password<br>
                        Cliente: juan@email.com / password
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
unset($_SESSION['form_data']);
include BASE_PATH . '/app/views/layout/footer.php';
?>
