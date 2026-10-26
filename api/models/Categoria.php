<?php

require_once __DIR__ . '/../config/Database.php';

class Categoria{

    //Método para traer todas las categorías
    public static function listar(){

        //Conectamos a la base de datos
        $pdo = Database::conectar();

        $consulta = $pdo->query('SELECT id, nombre, margen FROM categoria ORDER BY nombre');

        return $consulta -> fetchAll();


    }

    //Método para agregar una cateogoría 
    public static function crear($nombre, $margen){
        $pdo = Database::conectar();

        $consulta = $pdo->prepare('INSERT INTO categoria (nombre, margen) VALUES (:nombre, :margen)');
        $consulta->execute([':nombre' => $nombre, ':margen' => $margen]);

        return $pdo->lastInsertId();
    }

    //Método para buscar
    public static function buscar($id){
        $pdo = Database::conectar();

        $consulta = $pdo->prepare('SELECT id, nombre, margen FROM categoria WHERE id = :id');
        $consulta->execute([':id' => $id]);

        return $consulta->fetch();
    }

    //Método para modificar categoría
    public static function modificar($id, $nombre, $margen){
        $pdo = Database::conectar();

        $consulta = $pdo->prepare('UPDATE categoria SET nombre = :nombre, margen = :margen WHERE id = :id');
        $consulta->execute([':id' => $id, ':nombre' => $nombre, ':margen' => $margen]);

    }


}