<?php

header('Content-Type: application/json; charset=utf-8');

$recurso = $_GET['recurso'] ?? '';

switch($recurso){
    case 'categorias':
        echo json_encode(['mensaje' => 'Acá van a ir las categorías']);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error'=> 'Recurso no encontrado']);
}