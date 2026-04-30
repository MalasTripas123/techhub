<?php

require_once BASE_PATH . '/app/models/Database.php';
require_once BASE_PATH . '/app/models/Usuario.php';

class AuthController {

    // Mostrar formulario de login
    public function loginForm(): void {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=producto&action=catalogo');
            exit;
        }
        include BASE_PATH . '/app/views/login.php';
    }

    // Procesar login (POST)
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errores  = [];

        if (empty($email))    $errores[] = 'El email es obligatorio.';
        if (empty($password)) $errores[] = 'La contraseña es obligatoria.';

        if (empty($errores)) {
            $usuario = Usuario::buscarPorEmail($email);
            if ($usuario && password_verify($password, $usuario->password)) {
                // Guardar datos en sesión
                $_SESSION['usuario_id']     = $usuario->id;
                $_SESSION['usuario_nombre'] = $usuario->nombre;
                $_SESSION['usuario_rol']    = $usuario->rol;

                // Redirigir según rol
                if ($usuario->rol === 'admin') {
                    header('Location: ' . BASE_URL . '/index.php?controller=admin&action=dashboard');
                } else {
                    header('Location: ' . BASE_URL . '/index.php?controller=producto&action=catalogo');
                }
                exit;
            } else {
                $errores[] = 'Email o contraseña incorrectos.';
            }
        }

        // Si hay errores, volver al formulario con mensaje
        $_SESSION['errores'] = $errores;
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
        exit;
    }

    // Mostrar formulario de registro
    public function registroForm(): void {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=producto&action=catalogo');
            exit;
        }
        include BASE_PATH . '/app/views/registro.php';
    }

    // Procesar registro (POST)
    public function registro(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=registroForm');
            exit;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $errores  = [];

        // Validaciones
        if (empty($nombre))                   $errores[] = 'El nombre es obligatorio.';
        if (empty($email))                    $errores[] = 'El email es obligatorio.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no tiene formato válido.';
        if (strlen($password) < 6)            $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        if ($password !== $confirm)           $errores[] = 'Las contraseñas no coinciden.';

        if (empty($errores)) {
            try {
                $usuario = new Usuario($nombre, $email, '');
                $usuario->setEmail($email);
                $usuario->setPassword($password);
                $usuario->registrar();

                $_SESSION['exito'] = '¡Cuenta creada! Ahora puedes iniciar sesión.';
                header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
                exit;
            } catch (Exception $e) {
                $errores[] = $e->getMessage();
            }
        }

        $_SESSION['errores'] = $errores;
        $_SESSION['form_data'] = ['nombre' => $nombre, 'email' => $email];
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=registroForm');
        exit;
    }

    // Cerrar sesión
    public function logout(): void {
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
        exit;
    }
}
