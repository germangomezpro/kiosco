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

    //Función para agregar una cateogoría 
    public static function crear($nombre, $margen){
        $pdo = Database::conectar();

        $consulta = $pdo->prepare('INSERT INTO categoria (nombre, margen) VALUES (:nombre, :margen)');
        $consulta->execute([':nombre' => $nombre, ':margen' => $margen]);

        return $pdo->lastInsertId();
    }

}