CREATE DATABASE IF NOT EXISTS megatec CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE megatec;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'operario', 'administrador') DEFAULT 'usuario',
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de artículos
CREATE TABLE IF NOT EXISTS articulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    condicion ENUM('nuevo', 'usado', 'reacondicionado') DEFAULT 'nuevo',
    envio_gratis BOOLEAN DEFAULT FALSE,
    creador_id INT NOT NULL,
    categoria_id INT,
    ml_item_id VARCHAR(50) NULL,
    estado ENUM('borrador', 'activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Tabla de imágenes de artículos
CREATE TABLE IF NOT EXISTS imagenes_articulo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    articulo_id INT NOT NULL,
    ruta_imagen VARCHAR(500) NOT NULL,
    orden TINYINT DEFAULT 1,
    FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
);

-- Tabla de carrito
CREATE TABLE IF NOT EXISTS carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    articulo_id INT NOT NULL,
    cantidad INT DEFAULT 1 CHECK (cantidad > 0),
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_carrito_item (usuario_id, articulo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
);

-- Tabla de favoritos
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    articulo_id INT NOT NULL,
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorito (usuario_id, articulo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
);

-- Tabla de chat
CREATE TABLE IF NOT EXISTS chat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remitente_id INT NOT NULL,
    destinatario_id INT NOT NULL,
    articulo_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (remitente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (destinatario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
);

-- Tabla de permisos de operario
CREATE TABLE IF NOT EXISTS permisos_operario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operario_id INT NOT NULL,
    subir_banners BOOLEAN DEFAULT FALSE,
    modificar_perfiles_usuarios BOOLEAN DEFAULT FALSE,
    usar_chat BOOLEAN DEFAULT FALSE,
    publicar_contenido BOOLEAN DEFAULT FALSE,
    cambiar_perfil BOOLEAN DEFAULT TRUE,
    UNIQUE KEY unique_operario (operario_id),
    FOREIGN KEY (operario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Insertar usuarios de ejemplo
INSERT IGNORE INTO usuarios (nombre, email, password, rol) VALUES
('Admin MegaTec', 'admin@megatec.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador'),
('Operario Juan', 'juan@megatec.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'operario'),
('Usuario Cliente', 'cliente@megatec.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario');

-- Insertar categorías
INSERT IGNORE INTO categorias (nombre, descripcion) VALUES
('Electrónica', 'Dispositivos electrónicos y gadgets.'),
('Informática', 'Computadoras, componentes y accesorios.'),
('Celulares', 'Teléfonos móviles y accesorios.'),
('Videojuegos', 'Consolas, juegos y accesorios.'),
('Herramientas', 'Herramientas manuales y eléctricas.'),
('Vehículos', 'Autos, motos y accesorios.'),
('Antigüedades', 'Objetos antiguos y coleccionables.'),
('Componentes', 'Componentes electrónicos y de computación.'),
('Accesorios', 'Accesorios varios.'),
('Juguetes', 'Juguetes para niños y adultos.'),
('Cuidado Personal', 'Productos de belleza y cuidado personal.'),
('Servicios', 'Servicios varios.'),
('Varios', 'Productos varios.');

-- Insertar artículos de ejemplo por categoría
INSERT IGNORE INTO articulos (titulo, descripcion, precio, stock, condicion, envio_gratis, creador_id, categoria_id, estado) VALUES
('Laptop Gamer RTX 3060', 'Laptop de última generación para gaming y diseño.', 1200.00, 10, 'nuevo', TRUE, 2, 1, 'activo'),
('Smartphone Galaxy S24', 'El mejor smartphone del mercado con cámara profesional.', 900.00, 15, 'nuevo', TRUE, 2, 3, 'activo'),
('Consola PlayStation 5', 'Consola de nueva generación, edición estándar con 1 juego incluido.', 800.00, 5, 'nuevo', TRUE, 1, 4, 'activo'),
('iPhone 15 Pro Max', '256GB, color azul, nuevo, sellado con garantía oficial.', 1500.00, 3, 'nuevo', FALSE, 1, 3, 'activo'),
('Teclado Mecánico RGB', 'Teclado mecánico con retroiluminación RGB y switches azules.', 150.00, 20, 'nuevo', TRUE, 2, 2, 'activo'),
('Drone con Cámara 4K', 'Drone plegable con cámara 4K y control por app.', 450.00, 8, 'nuevo', FALSE, 1, 1, 'activo'),
('Juego de Herramientas', 'Juego de 120 piezas para mecánica y bricolaje.', 85.00, 12, 'nuevo', TRUE, 2, 5, 'activo'),
('Casco de Moto Integral', 'Casco de moto integral con certificación de seguridad.', 220.00, 6, 'nuevo', FALSE, 1, 6, 'activo'),
('Reloj de Bolsillo Antiguo', 'Reloj de bolsillo de los años 50 en perfecto estado.', 350.00, 1, 'usado', TRUE, 2, 7, 'activo'),
('Fuente de Poder 750W', 'Fuente de poder modular de 750W 80 Plus Gold.', 180.00, 10, 'nuevo', TRUE, 1, 8, 'activo'),
('Mochila para Laptop', 'Mochila ergonómica con compartimento acolchado para laptop.', 45.00, 25, 'nuevo', TRUE, 2, 9, 'activo'),
('Lego Star Wars Millennium Falcon', 'Set de Lego Star Wars Millennium Falcon con 7541 piezas.', 650.00, 3, 'nuevo', FALSE, 1, 10, 'activo'),
('Secador de Pelo Profesional', 'Secador de pelo iónico con 3 velocidades y 2 temperaturas.', 75.00, 18, 'nuevo', TRUE, 2, 11, 'activo'),
('Clases de Guitarra Online', '10 clases de guitarra online con profesor certificado.', 200.00, 50, 'nuevo', TRUE, 1, 12, 'activo'),
('Lámpara de Mesa LED', 'Lámpara de mesa con luz LED regulable y puerto USB.', 35.00, 30, 'nuevo', TRUE, 2, 13, 'activo');

-- Insertar imágenes de ejemplo
INSERT IGNORE INTO imagenes_articulo (articulo_id, ruta_imagen, orden) VALUES
(1, 'laptop1.jpg', 1),
(2, 'smartphone1.jpg', 1),
(3, 'ps5.jpg', 1),
(4, 'iphone15.jpg', 1),
(5, 'teclado.jpg', 1),
(6, 'drone.jpg', 1),
(7, 'herramientas.jpg', 1),
(8, 'casco.jpg', 1),
(9, 'reloj.jpg', 1),
(10, 'fuente.jpg', 1),
(11, 'mochila.jpg', 1),
(12, 'lego.jpg', 1),
(13, 'secador.jpg', 1),
(14, 'clases.jpg', 1),
(15, 'lampara.jpg', 1);