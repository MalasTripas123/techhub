-- ============================================================
-- Ejecutar en phpMyAdmin o MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS techhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techhub;

-- ============================================================
-- TABLA: usuarios
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin') DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: productos
-- ============================================================
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    categoria VARCHAR(100),
    imagen VARCHAR(300) DEFAULT 'default.jpg',
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: ordenes
-- ============================================================
CREATE TABLE IF NOT EXISTS ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente','pagado','enviado','cancelado') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLA: detalles_orden
-- ============================================================
CREATE TABLE IF NOT EXISTS detalles_orden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLA: carritos (persistencia de sesión)
-- ============================================================
CREATE TABLE IF NOT EXISTS carritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_item (usuario_id, producto_id)
);

-- ============================================================
-- DATOS DE PRUEBA
-- ============================================================

-- Admin por defecto (password: admin123)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@techhub.cl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Pérez', 'juan@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente'),
('María González', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente');

-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen) VALUES
('MacBook Air M3', 'Notebook Apple con chip M3, 8GB RAM, 256GB SSD. Ultradelgado y potente.', 1299990, 15, 'Notebooks', 'macbook-air.jpg'),
('Dell XPS 15', 'Notebook Dell con Intel Core i7, 16GB RAM, 512GB SSD, pantalla 4K OLED.', 1899990, 8, 'Notebooks', 'dell-xps.jpg'),
('Lenovo ThinkPad X1', 'Notebook empresarial con Intel Core i5, 16GB RAM, 256GB SSD. Batería de larga duración.', 999990, 12, 'Notebooks', 'thinkpad.jpg'),
('iPad Pro 12.9"', 'Tablet Apple con chip M2, pantalla Liquid Retina XDR, compatible con Apple Pencil.', 1099990, 20, 'Tablets', 'ipad-pro.jpg'),
('Samsung Galaxy Tab S9', 'Tablet Samsung con pantalla AMOLED 11", Snapdragon 8 Gen 2, 12GB RAM.', 799990, 18, 'Tablets', 'galaxy-tab.jpg'),
('Logitech MX Master 3', 'Mouse inalámbrico ergonómico con scroll de alta precisión. Compatible con múltiples SO.', 89990, 50, 'Accesorios', 'mx-master.jpg'),
('Teclado Mecánico Keychron K2', 'Teclado mecánico inalámbrico con switches Gateron Red, retroiluminación RGB.', 129990, 30, 'Accesorios', 'keychron.jpg'),
('Monitor LG 27" 4K', 'Monitor 4K UHD con panel IPS, HDR400, frecuencia de 144Hz. Ideal para diseño y gaming.', 549990, 10, 'Monitores', 'lg-monitor.jpg'),
('Sony WH-1000XM5', 'Audífonos inalámbricos con cancelación de ruido líder en la industria. 30h de batería.', 349990, 25, 'Accesorios', 'sony-xm5.jpg'),
('Webcam Logitech C920', 'Cámara web Full HD 1080p con corrección de luz automática. Ideal para videoconferencias.', 79990, 40, 'Accesorios', 'c920.jpg'),
('SSD Samsung 1TB', 'Disco de estado sólido externo Samsung T7, velocidad hasta 1050MB/s, USB 3.2.', 109990, 35, 'Almacenamiento', 'samsung-ssd.jpg'),
('Hub USB-C 7 en 1', 'Hub multipuerto con HDMI 4K, USB-A x3, USB-C PD, lector SD y microSD.', 49990, 60, 'Accesorios', 'usb-hub.jpg');
