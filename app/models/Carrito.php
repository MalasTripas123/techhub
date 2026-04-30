<?php

class Carrito
{
    // El carrito vive en la sesión del usuario
    private static string $sessionKey = 'carrito';

    // Obtener todos los items del carrito desde la sesión
    public static function obtener(): array
    {
        return $_SESSION[self::$sessionKey] ?? [];
    }

    // Agregar producto al carrito
    public static function agregar(int $productoId, int $cantidad = 1): array
    {
        $producto = Producto::buscarPorId($productoId);
        if (!$producto) {
            return ['exito' => false, 'mensaje' => 'Producto no encontrado.'];
        }
        if ($producto->stock < 1) {
            return ['exito' => false, 'mensaje' => 'Producto sin stock.'];
        }

        $carrito = self::obtener();

        if (isset($carrito[$productoId])) {
            // Si ya existe, sumar cantidad
            $nuevaCantidad = $carrito[$productoId]['cantidad'] + $cantidad;
            if ($nuevaCantidad > $producto->stock) {
                return ['exito' => false, 'mensaje' => 'No hay suficiente stock.'];
            }
            $carrito[$productoId]['cantidad'] = $nuevaCantidad;
        } else {
            // Agregar nuevo item
            $carrito[$productoId] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'imagen'   => $producto->imagen,
                'cantidad' => $cantidad,
            ];
        }

        $_SESSION[self::$sessionKey] = $carrito;
        return ['exito' => true, 'mensaje' => 'Producto agregado al carrito.', 'total_items' => self::totalItems()];
    }

    // Eliminar un producto del carrito
    public static function eliminar(int $productoId): array
    {
        $carrito = self::obtener();
        if (isset($carrito[$productoId])) {
            unset($carrito[$productoId]);
            $_SESSION[self::$sessionKey] = $carrito;
            return ['exito' => true, 'mensaje' => 'Producto eliminado del carrito.'];
        }
        return ['exito' => false, 'mensaje' => 'El producto no estaba en el carrito.'];
    }

    // Actualizar cantidad
    public static function actualizarCantidad(int $productoId, int $cantidad): array
    {
        if ($cantidad <= 0) return self::eliminar($productoId);

        // 1. Buscar el producto en la BD para verificar su stock real
        $producto = Producto::buscarPorId($productoId);
        if (!$producto) {
            return ['exito' => false, 'mensaje' => 'Producto no encontrado.'];
        }

        // 2. Validar que la cantidad deseada no supere el stock
        if ($cantidad > $producto->stock) {
            return ['exito' => false, 'mensaje' => 'No hay suficiente stock. Máximo disponible: ' . $producto->stock];
        }

        $carrito = self::obtener();
        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] = $cantidad;
            $_SESSION[self::$sessionKey] = $carrito;
            return ['exito' => true, 'mensaje' => 'Cantidad actualizada.'];
        }
        return ['exito' => false, 'mensaje' => 'Producto no encontrado en carrito.'];
    }

    // Vaciar el carrito
    public static function vaciar(): void
    {
        $_SESSION[self::$sessionKey] = [];
    }

    // Total en pesos
    public static function total(): float
    {
        $total   = 0;
        $carrito = self::obtener();
        if (count($carrito) < 1) {
            return 0;
        }

        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    // Total formateado
    public static function totalFormateado(): string
    {
        return '$' . number_format(self::total(), 0, ',', '.');
    }

    // Cantidad total de items
    public static function totalItems(): int
    {
        $total   = 0;
        $carrito = self::obtener();
        foreach ($carrito as $item) {
            $total += $item['cantidad'];
        }
        return $total;
    }
}
