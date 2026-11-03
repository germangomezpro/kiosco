<?php

require_once __DIR__ . '/../config/Database.php';

class Proveedor
{
    // Todos los proveedores, ordenados por nombre
    public static function listar(){
        $pdo = Database::conectar();
        $consulta = $pdo->query('SELECT id, nombre, telefono, observaciones FROM proveedor ORDER BY nombre');
        return $consulta->fetchAll();
    }

    // Un proveedor por su id, o false si no existe
    public static function buscar($id){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('SELECT id, nombre, telefono, observaciones FROM proveedor WHERE id = :id');
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Guarda un proveedor nuevo y devuelve el id que le tocó
    public static function crear($nombre, $telefono, $observaciones){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('INSERT INTO proveedor (nombre, telefono, observaciones) VALUES (:nombre, :telefono, :observaciones)');
        $consulta->execute([
            ':nombre'        => $nombre,
            ':telefono'      => $telefono,
            ':observaciones' => $observaciones,
        ]);
        return $pdo->lastInsertId();
    }

    // Cambia los datos de un proveedor
    public static function modificar($id, $nombre, $telefono, $observaciones){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('UPDATE proveedor SET nombre = :nombre, telefono = :telefono, observaciones = :observaciones WHERE id = :id');
        $consulta->execute([
            ':id'            => $id,
            ':nombre'        => $nombre,
            ':telefono'      => $telefono,
            ':observaciones' => $observaciones,
        ]);
    }

    // Borra un proveedor (MySQL lo impide si tiene productos)
    public static function eliminar($id){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('DELETE FROM proveedor WHERE id = :id');
        $consulta->execute([':id' => $id]);
    }
}