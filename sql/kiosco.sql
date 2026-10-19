/*Creamos la base de datos*/

CREATE DATABASE IF NOT EXISTS kiosco
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;



USE kiosco;

/* Tabla de usuarios */

CREATE TABLE IF NOT EXISTS usuario(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
)ENGINE=InnoDB;

/* Tabla de categorias */

CREATE TABLE IF NOT EXISTS categoria(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    margen DECIMAL(5,2) NOT NULL,
    PRIMARY KEY (id)
)ENGINE=InnoDB;

/* Tabla de proveedores */

CREATE TABLE IF NOT EXISTS proveedor(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(255),
    observaciones TEXT,
    PRIMARY KEY (id)
)ENGINE=InnoDB;

/* Tabla de productos */

CREATE TABLE IF NOT EXISTS producto(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    categoria_id INT(11) NOT NULL,
    proveedor_id INT(11) NOT NULL,
    unidad_medida ENUM('kg', 'unidad') NOT NULL,
    costo DECIMAL(9,2) NOT NULL,
    margen_propio DECIMAL(5,2),
    precio_venta DECIMAL(9,2) NOT NULL,
    stock_actual DECIMAL(7,3) NOT NULL,
    stock_minimo DECIMAL(7,3) NOT NULL,
    costo_actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_producto_categoria FOREIGN KEY (categoria_id) REFERENCES categoria(id),
    CONSTRAINT fk_producto_proveedor FOREIGN KEY (proveedor_id) REFERENCES proveedor(id),
    PRIMARY KEY(id)
)ENGINE=InnoDB;

/* INSERTS de prueba */

INSERT INTO categoria (nombre, margen) VALUES
('Lácteos', 40),
('Copetín', 40),
('Gaseosas', 35),
('Cervezas', 30);

INSERT INTO proveedor (nombre) VALUES
('Distribuidora Andina'),
('Distribuidora Coca Cola'),
('Lacteos Don Angel');

INSERT INTO producto (nombre, categoria_id, proveedor_id, unidad_medida, costo, precio_venta, stock_actual, stock_minimo) VALUES
('Dulce de Leche Repostero Pote - 500gr', 1, 3, 'unidad', 1200, 1680, 5, 2),
('Cerveza Quilmes de 1 Litro', 3, 1, 'unidad', 2500, 3250, 12, 4),
('Coca Cola de 2 Lts.', 2, 2, 'unidad', 2600, 3510, 10, 3),
('Bolsa de papas fritas sueltas de 1 KG', )


