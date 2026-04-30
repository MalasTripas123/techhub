<?php

class Orden {
    private int    $id;
    private int    $usuarioId;
    private float  $total;
    private string $estado;
    private string $created_at;

    public function __construct(int $usuarioId, float $total) {
        $this->usuarioId = $usuarioId;
        $this->total     = $total;
        $this->estado    = 'pendiente';
    }

    // ── GETTERS ─────────────────────────────────────────────
    public function getId():        int    { return $this->id; }
    public function getTotal():     float  { return $this->total; }
    public function getEstado():    string { return $this->estado; }
    public function getCreatedAt(): string { return $this->created_at; }

    // Total formateado
    public function getTotalFormateado(): string {
        return '$' . number_format($this->total, 0, ',', '.');
    }

    // Badge de estado con color
    public static function badgeEstado(string $estado): string {
        $colores = [
            'pendiente' => 'warning',
            'pagado'    => 'success',
            'enviado'   => 'info',
            'cancelado' => 'danger',
        ];
        $color = $colores[$estado] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$estado}</span>";
    }

    // ── MÉTODOS DE BASE DE DATOS ────────────────────────────

    // Crear orden desde el carrito actual
    public static function crearDesdeCarrito(int $usuarioId): array {
        $items = Carrito::obtener();
        if (empty($items)) {
            return ['exito' => false, 'mensaje' => 'El carrito está vacío.'];
        }

        $db    = Database::conectar();
        $total = Carrito::total();

        try {
            $db->beginTransaction();

            // Crear la orden
            $stmt = $db->prepare('INSERT INTO ordenes (usuario_id, total) VALUES (?, ?)');
            $stmt->execute([$usuarioId, $total]);
            $ordenId = $db->lastInsertId();

            // Insertar detalles
            $stmtDetalle = $db->prepare(
                'INSERT INTO detalles_orden (orden_id, producto_id, cantidad, precio_unitario)
                 VALUES (?, ?, ?, ?)'
            );
            foreach ($items as $item) {
                $stmtDetalle->execute([$ordenId, $item['id'], $item['cantidad'], $item['precio']]);
                // Descontar stock
                $db->prepare('UPDATE productos SET stock = stock - ? WHERE id = ?')
                   ->execute([$item['cantidad'], $item['id']]);
            }

            $db->commit();
            Carrito::vaciar();
            return ['exito' => true, 'mensaje' => '¡Orden creada con éxito!', 'orden_id' => $ordenId];

        } catch (Exception $e) {
            $db->rollBack();
            return ['exito' => false, 'mensaje' => 'Error al procesar la orden: ' . $e->getMessage()];
        }
    }

    // Historial de órdenes de un usuario
    public static function historialUsuario(int $usuarioId): array {
        $db   = Database::conectar();
        $stmt = $db->prepare(
            'SELECT o.*, COUNT(d.id) as total_items
             FROM ordenes o
             LEFT JOIN detalles_orden d ON o.id = d.orden_id
             WHERE o.usuario_id = ?
             GROUP BY o.id
             ORDER BY o.created_at DESC'
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    // Detalle de una orden
    public static function detalle(int $ordenId): array {
        $db   = Database::conectar();
        $stmt = $db->prepare(
            'SELECT d.*, p.nombre, p.imagen
             FROM detalles_orden d
             JOIN productos p ON d.producto_id = p.id
             WHERE d.orden_id = ?'
        );
        $stmt->execute([$ordenId]);
        return $stmt->fetchAll();
    }

    // Listar todas (admin)
    public static function listarTodas(): array {
        $db   = Database::conectar();
        $stmt = $db->query(
            'SELECT o.*, u.nombre as cliente, u.email
             FROM ordenes o
             JOIN usuarios u ON o.usuario_id = u.id
             ORDER BY o.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    // Contar órdenes
    public static function contar(): int {
        $db = Database::conectar();
        return (int) $db->query('SELECT COUNT(*) FROM ordenes')->fetchColumn();
    }

    // Total de ventas
    public static function totalVentas(): float {
        $db = Database::conectar();
        return (float) $db->query("SELECT COALESCE(SUM(total),0) FROM ordenes WHERE estado != 'cancelado'")->fetchColumn();
    }
}
