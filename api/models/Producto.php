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

    // Buscar producto por nombre, categoría o proveedor
    public static function buscarPorTexto($texto){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM ' . self::TABLAS . '
            WHERE p.activo = 1
              AND (p.nombre LIKE :texto1 OR c.nombre LIKE :texto2 OR pr.nombre LIKE :texto3)
            ORDER BY p.nombre');
            
        //Ponemos comodines al texto a buscar
        $patron = '%'.$texto.'%';
        $consulta->execute(['texto1' => $patron, 'texto2' => $patron, 'texto3' => $patron]);
        return $consulta->fetchAll();
    }   

    // Un producto por su id, o false si no existe
    public static function buscar($id){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM ' . self::TABLAS . ' WHERE p.id = :id AND p.activo = 1');
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Crear un producto
    public static function crear($datos){
        $pdo = Database::conectar();

        $consulta = $pdo->prepare('INSERT INTO producto
            (nombre, categoria_id, proveedor_id, unidad_medida, costo, margen_propio, precio_venta, stock_actual, stock_minimo)
            VALUES
            (:nombre, :categoria_id, :proveedor_id, :unidad_medida, :costo, :margen_propio, :precio_venta, :stock_actual, :stock_minimo)');

        $consulta->execute($datos);
        return $pdo->lastInsertId();
    }

    // Modificar un producto

    public static function modificar($id, $datos){
        $pdo = Database::conectar();

        // Si el costo cambió anotamos la fecha. Tiene que ir antes del Update principal

        $consulta = $pdo->prepare('UPDATE producto SET costo_actualizado_en = NOW() WHERE id = :id AND costo <> :costo');
        $consulta->execute(['id' => $id, 'costo' => $datos['costo']]);

        // Guardamos los datos

        $datos['id'] = $id;

        $consulta = $pdo->prepare('UPDATE producto SET
         nombre = :nombre,
         categoria_id = :categoria_id,
         proveedor_id = :proveedor_id,
         unidad_medida = :unidad_medida,
         costo = :costo,
         margen_propio = :margen_propio,
         precio_venta = :precio_venta,
         stock_actual = :stock_actual,
         stock_minimo = :stock_minimo
         WHERE id = :id');

        $consulta->execute($datos);
    }

    // Dar de baja un producto: No se borra la fila. Se marca como inactiva
    public static function darDeBaja($id){
        $pdo = Database::conectar();

        $consulta = $pdo->prepare('UPDATE producto SET activo = 0 WHERE id = :id');
        $consulta->execute([':id' => $id]);

    }

       // Productos de una categoría que usan el margen de la categoría (no tienen margen propio)
    public static function sinMargenPropio($categoriaId){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('SELECT id, costo, precio_venta FROM producto WHERE categoria_id = :categoria_id AND margen_propio IS NULL');
        $consulta->execute(['categoria_id' => $categoriaId]);
        return $consulta->fetchAll();
    }

    // Guardar el precio nuevo
    public static function actualizarPrecio($id, $precio){
        $pdo = Database::conectar();
        $consulta = $pdo->prepare('UPDATE producto SET precio_venta = :precio WHERE id = :id');
        $consulta->execute([':precio' => $precio, ':id' => $id]);
    }
}