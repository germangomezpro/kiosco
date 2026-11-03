<?php

require_once __DIR__ . '/../config/Database.php';

class Producto{
    // Las columnas que devuelven listar y buscar, con los nombres de la categoría y el proveedor
    private const COLUMNAS = '
        p.id,
        p.nombre,
        p.categoria_id,
        c.nombre AS categoria,
        c.margen AS margen_categoria,
        p.proveedor_id,
        pr.nombre AS proveedor,
        p.unidad_medida,
        p.costo,
        p.margen_propio,
        p.precio_venta,
        p.stock_actual,
        p.stock_minimo,
        p.costo_actualizado_en
    ';

    // Las tres tablas juntas
    private const TABLAS = '
        producto p
        INNER JOIN categoria c ON c.id = p.categoria_id
        INNER JOIN proveedor pr ON pr.id = p.proveedor_id
    ';

    // Todos los productos activos, ordenados por nombre
    public static function listar(){
        $pdo = Database::conectar();
        $consulta = $pdo->query('SELECT ' . self::COLUMNAS . ' FROM ' . self::TABLAS . ' WHERE p.activo = 1 ORDER BY p.nombre');
        return $consulta->fetchAll();
    }

    // Un producto por su id, o false si no existe
    public static function buscar($id){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM ' . self::TABLAS . ' WHERE p.id = :id AND p.activo = 1');
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }
}