<?php

class Database
{
    public static function conectar()
    {
        $config = require __DIR__ . '/config.php';

        $pdo = new PDO(
            'mysql:host=' . $config['host'] . ';dbname=' . $config['base'] . ';charset=utf8mb4',
            $config['usuario'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        return $pdo;
    }
}