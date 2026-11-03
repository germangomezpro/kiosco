<?php

require_once __DIR__ . '/../models/Producto.php';

class ProductoController{
    
    //Listar
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

}