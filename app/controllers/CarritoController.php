<?php

require_once BASE_PATH . '/app/models/Database.php';
require_once BASE_PATH . '/app/models/Producto.php';
require_once BASE_PATH . '/app/models/Carrito.php';
require_once BASE_PATH . '/app/models/Orden.php';

class CarritoController {

    // Ver carrito (página)
    public function ver(): void {
        $items   = Carrito::obtener();
        $total   = Carrito::totalFormateado();
        include BASE_PATH . '/app/views/carrito.php';
    }

    // Agregar producto — responde JSON (AJAX)
    public function agregar(): void {
        header('Content-Type: application/json');
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['exito' => false, 'mensaje' => 'Debes iniciar sesión para agregar al carrito.', 'login' => true]);
            exit;
        }
        $productoId = (int)($_POST['producto_id'] ?? 0);
        $cantidad   = (int)($_POST['cantidad'] ?? 1);
        $resultado  = Carrito::agregar($productoId, $cantidad);
        echo json_encode($resultado);
        exit;
    }

    // Eliminar producto del carrito — AJAX
    public function eliminar(): void {
        header('Content-Type: application/json');
        $productoId = (int)($_POST['producto_id'] ?? 0);
        $resultado  = Carrito::eliminar($productoId);
        $resultado['total']       = Carrito::totalFormateado();
        $resultado['total_items'] = Carrito::totalItems();
        echo json_encode($resultado);
        exit;
    }

    // Actualizar cantidad — AJAX
    public function actualizar(): void {
        header('Content-Type: application/json');
        $productoId = (int)($_POST['producto_id'] ?? 0);
        $cantidad   = (int)($_POST['cantidad'] ?? 1);
        $resultado  = Carrito::actualizarCantidad($productoId, $cantidad);
        $resultado['total']       = Carrito::totalFormateado();
        $resultado['total_items'] = Carrito::totalItems();
        echo json_encode($resultado);
        exit;
    }

    // Finalizar compra
    public function checkout(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=loginForm');
            exit;
        }
        $resultado = Orden::crearDesdeCarrito($_SESSION['usuario_id']);
        if ($resultado['exito']) {
            $_SESSION['exito'] = $resultado['mensaje'];
            header('Location: ' . BASE_URL . '/index.php?controller=usuario&action=historial');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header('Location: ' . BASE_URL . '/index.php?controller=carrito&action=ver');
        }
        exit;
    }
}
