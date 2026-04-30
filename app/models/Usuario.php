<?php

class Usuario {
    // Propiedades privadas (encapsulamiento)
    private int    $id;
    private string $nombre;
    private string $email;
    private string $password;
    private string $rol;
    private string $created_at;

    // Constructor: se ejecuta al crear un objeto Usuario
    public function __construct(
        string $nombre = '',
        string $email  = '',
        string $password = '',
        string $rol = 'cliente'
    ) {
        $this->nombre   = $nombre;
        $this->email    = $email;
        $this->password = $password;
        $this->rol      = $rol;
    }

    // ── GETTERS ─────────────────────────────────────────────
    public function getId():        int    { return $this->id; }
    public function getNombre():    string { return $this->nombre; }
    public function getEmail():     string { return $this->email; }
    public function getRol():       string { return $this->rol; }
    public function getCreatedAt(): string { return $this->created_at; }

    // ── SETTERS con validación ───────────────────────────────
    public function setNombre(string $nombre): void {
        if (empty(trim($nombre))) throw new Exception('El nombre no puede estar vacío.');
        $this->nombre = htmlspecialchars(trim($nombre));
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception('El email no tiene formato válido.');
        $this->email = strtolower(trim($email));
    }

    public function setPassword(string $password): void {
        if (strlen($password) < 6) throw new Exception('La contraseña debe tener al menos 6 caracteres.');
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Verificar si la contraseña ingresada coincide con el hash
    public function verificarPassword(string $password): bool {
        return password_verify($password, $this->password);
    }

    public function esAdmin(): bool {
        return $this->rol === 'admin';
    }

    // ── MÉTODOS DE BASE DE DATOS ────────────────────────────

    // Registrar nuevo usuario
    public function registrar(): bool {
        $db = Database::conectar();
        // Verificar si el email ya existe
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$this->email]);
        if ($stmt->fetch()) throw new Exception('Este email ya está registrado.');

        $stmt = $db->prepare(
            'INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)'
        );
        return $stmt->execute([$this->nombre, $this->email, $this->password, $this->rol]);
    }

    // Buscar usuario por email (para login)
    public static function buscarPorEmail(string $email): ?object {
        $db   = Database::conectar();
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([strtolower(trim($email))]);
        return $stmt->fetch() ?: null;
    }

    // Buscar por ID
    public static function buscarPorId(int $id): ?object {
        $db   = Database::conectar();
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // Listar todos los usuarios (para admin)
    public static function listarTodos(): array {
        $db   = Database::conectar();
        $stmt = $db->query('SELECT id, nombre, email, rol, created_at FROM usuarios ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    // Contar usuarios
    public static function contar(): int {
        $db = Database::conectar();
        return (int) $db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    }
}
