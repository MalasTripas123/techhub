<?php

require_once BASE_PATH . '/app/models/Database.php';
require_once BASE_PATH . '/app/models/Producto.php';
require_once BASE_PATH . '/app/models/Usuario.php';
require_once BASE_PATH . '/app/models/Orden.php';

class AdminController {

    // Verificar que sea admin, si no redirigir
    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }
    }

    // Dashboard con estadísticas
    public function dashboard(): void {
        $this->verificarAdmin();
        $totalProductos = Producto::contar();
        $totalUsuarios  = Usuario::contar();
        $totalOrdenes   = Orden::contar();
        $totalVentas    = '$' . number_format(Orden::totalVentas(), 0, ',', '.');
        $ordenes        = Orden::listarTodas();
        include BASE_PATH . '/app/views/admin/dashboard.php';
    }

    // Listar productos (admin)
    public function productos(): void {
        $this->verificarAdmin();
        $productos = Producto::listarTodos();
        include BASE_PATH . '/app/views/admin/productos.php';
    }

    // Formulario crear producto
    public function crearForm(): void {
        $this->verificarAdmin();
        include BASE_PATH . '/app/views/admin/producto_form.php';
    }

    // Guardar nuevo producto
    public function crear(): void {
        $this->verificarAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?controller=admin&action=productos');
            exit;
        }

        $errores = [];
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);
        $categoria   = trim($_POST['categoria'] ?? '');

        if (empty($nombre))    $errores[] = 'El nombre es obligatorio.';
        if ($precio <= 0)      $errores[] = 'El precio debe ser mayor a 0.';
        if (empty($categoria)) $errores[] = 'La categoría es obligatoria.';

        if (empty($errores)) {
            $imagen = 'default.jpg';
            // Manejo de imagen
            if (!empty($_FILES['imagen']['name'])) {
                $ext    = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $imagen = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagen']['tmp_name'], BASE_PATH . '/assets/img/' . $imagen);
            }

            Producto::crear([
                'nombre'      => htmlspecialchars($nombre),
                'descripcion' => htmlspecialchars($descripcion),
                'precio'      => $precio,
                'stock'       => $stock,
                'categoria'   => htmlspecialchars($categoria),
                'imagen'      => $imagen,
            ]);

            $_SESSION['exito'] = 'Producto creado correctamente.';
        } else {
            $_SESSION['errores'] = $errores;
        }

        header('Location: ' . BASE_URL . '/index.php?controller=admin&action=productos');
        exit;
    }

    // Formulario editar producto
    public function editarForm(): void {
        $this->verificarAdmin();
        $id       = (int)($_GET['id'] ?? 0);
        $producto = Producto::buscarPorId($id);
        if (!$producto) {
            header('Location: ' . BASE_URL . '/index.php?controller=admin&action=productos');
            exit;
        }
        include BASE_PATH . '/app/views/admin/producto_editar.php';
    }

    // Guardar edición
    public function editar(): void {
        $this->verificarAdmin();
        $id = (int)($_POST['id'] ?? 0);

        $imagen = $_POST['imagen_actual'] ?? 'default.jpg';
        if (!empty($_FILES['imagen']['name'])) {
            $ext    = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $imagen = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], BASE_PATH . '/assets/img/' . $imagen);
        }

        Producto::actualizar($id, [
            'nombre'      => htmlspecialchars(trim($_POST['nombre'] ?? '')),
            'descripcion' => htmlspecialchars(trim($_POST['descripcion'] ?? '')),
            'precio'      => (float)($_POST['precio'] ?? 0),
            'stock'       => (int)($_POST['stock'] ?? 0),
            'categoria'   => htmlspecialchars(trim($_POST['categoria'] ?? '')),
            'imagen'      => $imagen,
        ]);

        $_SESSION['exito'] = 'Producto actualizado correctamente.';
        header('Location: ' . BASE_URL . '/index.php?controller=admin&action=productos');
        exit;
    }

    // Eliminar producto
    public function eliminar(): void {
        $this->verificarAdmin();
        $id = (int)($_GET['id'] ?? 0);
        Producto::eliminar($id);
        $_SESSION['exito'] = 'Producto eliminado.';
        header('Location: ' . BASE_URL . '/index.php?controller=admin&action=productos');
        exit;
    }
}


// ============================================================
// UsuarioController — historial de compras
// ============================================================

require_once BASE_PATH . '/app/models/Orden.php';

class UsuarioController {

    public function historial(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }
        $ordenes = Orden::historialUsuario($_SESSION['usuario_id']);
        include BASE_PATH . '/app/views/historial.php';
    }
}
