<?php

require_once BASE_PATH . '/app/models/Database.php';
require_once BASE_PATH . '/app/models/Producto.php';
require_once BASE_PATH . '/app/models/Carrito.php';

class ProductoController {

    // Mostrar catálogo principal (CON FILTRO POR CATEGORÍA)
    public function catalogo(): void {

        $categoria = $_GET['categoria'] ?? '';

        if (!empty($categoria)) {
            $productos = Producto::obtenerPorCategoria($categoria);
        } else {
            $productos = Producto::listarTodos();
        }

        $categorias = Producto::listarCategorias();

        include BASE_PATH . '/app/views/home.php';
    }

    // Detalle de un producto
    public function detalle(): void {
        $id       = (int)($_GET['id'] ?? 0);
        $producto = Producto::buscarPorId($id);

        if (!$producto) {
            $_SESSION['errores'] = ['Producto no encontrado.'];
            header('Location: ' . BASE_URL . '/index.php?controller=producto&action=catalogo');
            exit;
        }
        include BASE_PATH . '/app/views/detalle_producto.php';
    }

    // Búsqueda AJAX — devuelve JSON
    public function buscar(): void {
        header('Content-Type: application/json');

        $termino   = trim($_GET['q'] ?? '');
        $categoria = trim($_GET['categoria'] ?? '');

        $productos = Producto::buscar($termino, $categoria);

        // Formatear precios para el JSON
        $resultado = array_map(function($p) {
            return [
                'id'          => $p->id,
                'nombre'      => $p->nombre,
                'descripcion' => $p->descripcion,
                'precio'      => $p->precio,
                'precio_fmt'  => '$' . number_format($p->precio, 0, ',', '.'),
                'stock'       => $p->stock,
                'categoria'   => $p->categoria,
                'imagen'      => $p->imagen,
            ];
        }, $productos);

        echo json_encode([
            'exito'     => true,
            'productos' => $resultado,
            'total'     => count($resultado)
        ]);
        exit;
    }
}