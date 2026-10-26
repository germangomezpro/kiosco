<?php

//Traemos el modelo Categoria.php

require_once __DIR__ . '/../models/Categoria.php';


class CategoriaController{

    //Funcion para pedirle al modelo la función listar

    public static function listar(){
        
        //Guardamos en una variable lo que nos envía el modelo
        $categorias = Categoria::listar();

        //Respondemos a la vista con json
        echo json_encode ($categorias);
    }

    public static function crear(){
        $nombre = trim($_POST['nombre'] ?? '');
        $margen = trim($_POST['margen'] ?? '');
        //Validación por si el cliente utiliza una coma en lugar de un punto en el decimal
        $margen = str_replace(',', '.', $margen);
        
        //Validaciones
        $error = self::validar($nombre, $margen);

        if($error !== null){
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $error]);
            return;
        }
    


        //Try catch para comprobar que no se esté guardando una categoría con un nombre ya existente
        try {
            $id = Categoria::crear($nombre, $margen);
        } catch (PDOException $e) {
            //errorInfo[1] es el número de error de MySQL. 1062 significa “valor repetido en una columna UNIQUE”.
            if($e->errorInfo[1] === 1062){
                http_response_code(409); //el pedido es válido, pero choca con algo que ya existe.
                echo json_encode (['ok' => false, 'error' => 'Ya existe una categoría con ese nombre']);
                return;
            }
            throw $e;
        }
        

        http_response_code(201);


        //Por ahora imprime acá el resultado.
        echo json_encode(['ok' => true, 'id' => $id]);
    }

    //Metodo del controlador para mostrar la categoría por id
    public static function ver($id){
        $categoria = Categoria::buscar($id);

        if($categoria === false){
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'La categoría no existe']);
            return;
        }
        echo json_encode($categoria);
    }


    //Método para validar nombre y margen de categorías
    private static function validar($nombre, $margen){
        if($nombre === ''){
            return 'El nombre es oblitagorio';
        }
        if (!is_numeric($margen) || $margen < 0){
            return 'El margen tiene que ser un número mayor o igual a cero';
        }
        return null; 
    }


}