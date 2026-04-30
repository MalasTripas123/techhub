<?php

class Producto {
    private int    $id;
    private string $nombre;
    private string $descripcion;
    private float  $precio;
    private int    $stock;
    private string $categoria;
    private string $imagen;
    private bool   $activo;

    public function __construct(
        string $nombre      = '',
        string $descripcion = '',
        float  $precio      = 0,
        int    $stock       = 0,
        string $categoria   = '',
        string $imagen      = 'default.jpg'
    ) {
        $this->nombre      = $nombre;
        $this->descripcion = $descripcion;
        $this->precio      = $precio;
        $this->stock       = $stock;
        $this->categoria   = $categoria;
        $this->imagen      = $imagen;
        $this->activo      = true;
    }

    // ── GETTERS ─────────────────────────────────────────────
    public function getId():          int    { return $this->id; }
    public function getNombre():      string { return $this->nombre; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getPrecio():      float  { return $this->precio; }
    public function getStock():       int    { return $this->stock; }
    public function getCategoria():   string { return $this->categoria; }
    public function getImagen():      string { return $this->imagen; }
    public function estaActivo():     bool   { return $this->activo; }

    // Precio formateado en pesos chilenos
    public function getPrecioFormateado(): string {
        return '$' . number_format($this->precio, 0, ',', '.');
    }

    // Verificar si hay stock disponible
    public function hayStock(): bool {
        return $this->stock > 0;
    }

    // ── SETTERS con validación ───────────────────────────────
    public function setPrecio(float $precio): void {
        if ($precio < 0) throw new Exception('El precio no puede ser negativo.');
        $this->precio = $precio;
    }

    public function setStock(int $stock): void {
        if ($stock < 0) throw new Exception('El stock no puede ser negativo.');
        $this->stock = $stock;
    }

    // Aplicar descuento porcentual
    public function aplicarDescuento(float $porcentaje): float {
        if ($porcentaje < 0 || $porcentaje > 100)
            throw new Exception('El descuento debe estar entre 0 y 100.');
        return $this->precio * (1 - $porcentaje / 100);
    }

    // ── MÉTODOS DE BASE DE DATOS ────────────────────────────

    // Listar todos los productos activos
    public static function listarTodos(): array {
        $db   = Database::conectar();
        $stmt = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY nombre ASC');
        return $stmt->fetchAll();
    }

    // 🔥 NUEVO: Obtener productos por categoría (usado por navbar)
    public static function obtenerPorCategoria(string $categoria): array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT * FROM productos 
             WHERE activo = 1 AND LOWER(categoria) = LOWER(?) 
             ORDER BY nombre ASC'
        );

        $stmt->execute([$categoria]);

        return $stmt->fetchAll();
    }

    // Buscar por ID
    public static function buscarPorId(int $id): ?object {
        $db   = Database::conectar();
        $stmt = $db->prepare('SELECT * FROM productos WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // Buscar por nombre o categoría (AJAX)
    public static function buscar(string $termino, string $categoria = ''): array {
        $db     = Database::conectar();
        $sql    = 'SELECT * FROM productos WHERE activo = 1';
        $params = [];

        if (!empty($termino)) {
            $sql     .= ' AND (nombre LIKE ? OR descripcion LIKE ?)';
            $params[] = "%$termino%";
            $params[] = "%$termino%";
        }

        if (!empty($categoria)) {
            $sql     .= ' AND LOWER(categoria) = LOWER(?)';
            $params[] = $categoria;
        }

        $sql .= ' ORDER BY nombre ASC';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Listar categorías únicas
    public static function listarCategorias(): array {
        $db   = Database::conectar();
        $stmt = $db->query('SELECT DISTINCT categoria FROM productos WHERE activo = 1 ORDER BY categoria');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Guardar nuevo producto (admin)
    public static function crear(array $data): bool {
        $db   = Database::conectar();
        $stmt = $db->prepare(
            'INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen)
             VALUES (:nombre, :descripcion, :precio, :stock, :categoria, :imagen)'
        );
        return $stmt->execute($data);
    }

    // Actualizar producto (admin)
    public static function actualizar(int $id, array $data): bool {
        $db   = Database::conectar();
        $stmt = $db->prepare(
            'UPDATE productos SET nombre=:nombre, descripcion=:descripcion,
             precio=:precio, stock=:stock, categoria=:categoria, imagen=:imagen
             WHERE id=:id'
        );
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Eliminar (soft delete)
    public static function eliminar(int $id): bool {
        $db   = Database::conectar();
        $stmt = $db->prepare('UPDATE productos SET activo = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // Contar productos
    public static function contar(): int {
        $db = Database::conectar();
        return (int) $db->query('SELECT COUNT(*) FROM productos WHERE activo = 1')->fetchColumn();
    }
}