/*Creamos la base de datos*/

CREATE DATABASE IF NOT EXISTS kiosco
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

/* Tabla de usuarios */

USE kiosco;

CREATE TABLE IF NOT EXISTS usuario(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categoria(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    margen DECIMAL(5,2) NOT NULL,
    PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS proveedor(
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(255),
    observaciones TEXT,
    PRIMARY KEY (id)
)ENGINE=InnoDB;