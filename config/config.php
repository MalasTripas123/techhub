<?php

// URL base del proyecto
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/techhub/');
define('BASE_PATH', __DIR__ . '/..');

// Configuración de la sesión
session_start();

// Reportar errores (cambiar a 0 en producción)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Zona horaria Chile
date_default_timezone_set('America/Santiago');

// Autoload simple: incluye modelos y controladores automáticamente
spl_autoload_register(function($clase) {
    $rutas = [
        BASE_PATH . '/app/models/' . $clase . '.php',
        BASE_PATH . '/app/controllers/' . $clase . '.php',
    ];
    foreach ($rutas as $ruta) {
        if (file_exists($ruta)) {
            require_once $ruta;
            return;
        }
    }
});
