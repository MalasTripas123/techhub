<?php

class Database {
    // Datos de conexión
    private static $host     = 'localhost';
    private static $dbname   = 'techhub';
    private static $user     = 'root';
    private static $password = '';          // En XAMPP por defecto es vacío

    // Conexión única (patrón Singleton)
    private static $conexion = null;

    // Método estático para obtener la conexión
    public static function conectar(): PDO {
        if (self::$conexion === null) {
            try {
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8mb4';
                self::$conexion = new PDO($dsn, self::$user, self::$password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die('<div style="font-family:sans-serif;color:red;padding:20px;">
                    <h2> Error de conexión a la base de datos</h2>
                    <p>' . $e->getMessage() . '</p>
                    <p>Verifica que XAMPP esté corriendo y la base de datos "techhub" exista.</p>
                </div>');
            }
        }
        return self::$conexion;
    }
}
