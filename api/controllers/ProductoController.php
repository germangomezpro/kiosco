<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Proveedor.php';

class ProductoController{
    //Constante: múltiplo al que se redondean los precios.
    private const REDONDEO = 50;
    
    //LISTAR TODOS
    public static function listar(){
        echo json_encode(Producto::listar());
    }

    //Ver uno en específico
    public static function ver($id){
        $producto = Producto::buscar($id);

        if($producto === false){
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El producto no existe']);
            return;
        }

        echo json_encode($producto);
    }

    // CREAR

    public static function crear(){
        $datos = self::leerDatos($_POST);

        $error = self::validar($datos);

        if($error !== null){
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $error]);
            return;
        }

        $datos['precio_venta'] = self::precioDe($datos);

        $id = Producto::crear($datos);

        http_response_code(201);
        echo json_encode(['ok' => true, 'id' => $id, 'precio_venta' => $datos['precio_venta']]);
    }

    // MODIFICAR
    
     public static function modificar($id){
        //Comprobar si existe el producto
        if(Producto::buscar($id) === false){
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El producto no existe']);
            return;
        }

        //Abrimos el PUT
        $entrada = json_decode(file_get_contents('php://input'), true) ?? [];
        $datos = self::leerDatos($entrada);
        $error = self::validar($datos);

        //Comprobar errores

        if($error !== null){
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $error]);
            return;
        }

        // Calcular el precio de venta y lo agregamos a $datos
        $datos['precio_venta'] = self::precioDe($datos);
        // Enviamos al modelo el id y la planilla de datos para agregar a Base
        Producto::modificar($id, $datos);

        echo json_encode(['ok' => true, 'precio_venta' => $datos['precio_venta']]);
    }
    
    // "Eliminar" (dar de baja)

    public static function eliminar($id){
        //Comprobar si existe el producto
        if(Producto::buscar($id) === false){
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El producto no existe']);
            return;
        }

        // Enviamos el id al modelo para dar de baja
        Producto::darDeBaja($id);

        // Respondemos
        echo json_encode(['ok' => true]);
    }

    // FUNCIONES DE APOYO

    // Limpiar datos
    private static function leerDatos($entrada){
        $datos = [
            'nombre'        => trim($entrada['nombre'] ?? ''),
            'categoria_id'  => trim($entrada['categoria_id'] ?? ''),
            'proveedor_id'  => trim($entrada['proveedor_id'] ?? ''),
            'unidad_medida' => trim($entrada['unidad_medida'] ?? ''),
            'costo'         => self::numero($entrada['costo'] ?? ''),
            'margen_propio' => self::numero($entrada['margen_propio'] ?? ''),
            'stock_actual'  => self::numero($entrada['stock_actual'] ?? ''),
            'stock_minimo'  => self::numero($entrada['stock_minimo'] ?? '')
        ];

        if($datos['margen_propio'] === ''){
            $datos['margen_propio'] = null;
        }

        return $datos;
    }

   
    // Calcular el precio con margen propio o de categorías
    private static function precioDe($datos){
        if($datos['margen_propio'] !== null){
            $margen = $datos['margen_propio'];
        }else{
            //Buscamos la categoría para extraer su margen
            $categoria = Categoria::buscar($datos['categoria_id']);
            $margen = $categoria['margen'];
        }

        return self::calcularPrecio($datos['costo'], $margen);
    }

    // Formato números

    private static function numero($valor){
        return str_replace(',', '.', trim($valor));
    }
    

    // Función para calcular los precios
    private static function calcularPrecio($costo, $margen){
        $precio = $costo * (1 + $margen / 100);
        return ceil(round($precio, 2) / self::REDONDEO) * self::REDONDEO;
    }

    //Función para validar

    private static function validar($datos){
        if($datos['nombre'] === ''){
            return 'El nombre es obligatorio';
        }
        if(Categoria::buscar($datos['categoria_id']) === false){
            return 'La categoría elegida no existe';
        }
        if(Proveedor::buscar($datos['proveedor_id']) === false){
            return 'El proveedor elegido no existe';
        }
        if ($datos['unidad_medida'] !== 'unidad' && $datos['unidad_medida'] !== 'kg') {
            return 'La unidad de medida tiene que ser unidad o kg';
        }
        if (!is_numeric($datos['costo']) || $datos['costo'] < 0) {
            return 'El costo tiene que ser un número mayor o igual a cero';
        }
        if ($datos['margen_propio'] !== null && (!is_numeric($datos['margen_propio']) || $datos['margen_propio'] < 0)) {
            return 'El margen propio tiene que ser un número mayor o igual a cero';
        }
        if (!is_numeric($datos['stock_actual']) || $datos['stock_actual'] < 0) {
            return 'El stock actual tiene que ser un número mayor o igual a cero';
        }
        if (!is_numeric($datos['stock_minimo']) || $datos['stock_minimo'] < 0) {
            return 'El stock mínimo tiene que ser un número mayor o igual a cero';
        }

        return null;
    }
}