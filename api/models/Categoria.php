<?php

require_once __DIR__ . '/../config/Database.php';

class Categoria{

    //Función para traer todas las categorías
    public static function listar(){

        //Conectamos a la base de datos
        $pdo = Database::conectar();

        $consulta = $pdo->query('SELECT id, nombre, margen FROM categoria ORDER BY nombre');

        return $consulta -> fetchAll();


    }
}