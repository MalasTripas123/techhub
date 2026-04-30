<?php

// 1. Cargar configuración (sesión, autoload, constantes)
require_once __DIR__ . '/config/config.php';

// 2. Leer controlador y acción desde la URL
//    Ejemplo: index.php?controller=auth&action=login
$controlador = $_GET['controller'] ?? 'producto';
$accion      = $_GET['action']     ?? 'catalogo';

// 3. Limpiar para evitar inyecciones
$controlador = preg_replace('/[^a-zA-Z]/', '', $controlador);
$accion      = preg_replace('/[^a-zA-Z]/', '', $accion);

// 4. Mapeo de controladores a sus archivos
$mapaControladores = [
    'auth'     => BASE_PATH . '/app/controllers/AuthController.php',
    'producto' => BASE_PATH . '/app/controllers/ProductoController.php',
    'carrito'  => BASE_PATH . '/app/controllers/CarritoController.php',
    'admin'    => BASE_PATH . '/app/controllers/AdminController.php',
    'usuario'  => BASE_PATH . '/app/controllers/AdminController.php', // UsuarioController está en el mismo archivo
];

// 5. Mapeo de controladores a sus clases PHP
$mapaClases = [
    'auth'     => 'AuthController',
    'producto' => 'ProductoController',
    'carrito'  => 'CarritoController',
    'admin'    => 'AdminController',
    'usuario'  => 'UsuarioController',
];

// 6. Verificar que el controlador existe
if (!array_key_exists($controlador, $mapaControladores)) {
    // Controlador no encontrado → ir al catálogo
    header('Location: ' . BASE_URL . '/index.php?controller=producto&action=catalogo');
    exit;
}

// 7. Incluir el archivo del controlador
require_once $mapaControladores[$controlador];

// 8. Instanciar la clase del controlador
$clase = $mapaClases[$controlador];
$obj   = new $clase();

// 9. Verificar que el método (acción) existe en la clase
if (!method_exists($obj, $accion)) {
    // Acción no encontrada → ir al catálogo
    header('Location: ' . BASE_URL . '/index.php?controller=producto&action=catalogo');
    exit;
}

// 10. Ejecutar la acción
$obj->$accion();
