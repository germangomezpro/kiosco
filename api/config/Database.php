<?php

class Database
{
    public static function conectar()
    {
        $config = require __DIR__ . '/config.php';

        $pdo = new PDO(
            'mysql:host=' . $config['host'] . ';dbname=' . $config['base'] . ';charset=utf8mb4',
            $config['usuario'],
            $config['password']
        );

        return $pdo;
    }
}