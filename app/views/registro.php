<?php
$pageTitle = 'Crear cuenta';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-warning display-5"></i>
                        <h2 class="fw-bold mt-2">Crear cuenta</h2>
                        <p class="text-muted">Únete a TechHub Store</p>
                    </div>

                    <form method="POST" action="<?= BASE_URL ?>/index.php?controller=auth&action=registro" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" class="form-control"
                                       placeholder="Tu nombre" required
                                       value="<?= htmlspecialchars($_SESSION['form_data']['nombre'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control"
                                       placeholder="tu@email.com" required
                                       value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="Mínimo 6 caracteres" required minlength="6"
                                       id="passInput">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirmar contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="confirm_password" class="form-control"
                                       placeholder="Repite tu contraseña" required
                                       id="passConfirm">
                            </div>
                            <div id="passError" class="text-danger small mt-1" style="display:none;">
                                Las contraseñas no coinciden.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold" id="btnRegistro">
                            <i class="bi bi-person-check"></i> Crear cuenta
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0 text-muted">
                        ¿Ya tienes cuenta?
                        <a href="<?= BASE_URL ?>/index.php?controller=auth&action=loginForm" class="text-dark fw-bold">
                            Iniciar sesión
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validación en cliente: contraseñas coincidan
document.getElementById('passConfirm').addEventListener('input', function() {
    const pass1   = document.getElementById('passInput').value;
    const pass2   = this.value;
    const err     = document.getElementById('passError');
    const btn     = document.getElementById('btnRegistro');
    if (pass2 && pass1 !== pass2) {
        err.style.display = 'block';
        btn.disabled      = true;
    } else {
        err.style.display = 'none';
        btn.disabled      = false;
    }
});
</script>

<?php
unset($_SESSION['form_data']);
include BASE_PATH . '/app/views/layout/footer.php';
?>
