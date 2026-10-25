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
        $margen = $_POST['margen'] ?? '';

        $id = Categoria::crear($nombre, $margen);

        http_response_code(201);

        echo json_encode(['ok' => true, 'id' => $id]);
    }


}