<?php

//Avisamos al cliente que la respuesta del servidor es un archivo de texto en formato JSON y codificado en UTF 8
header('Content-Type: application/json; charset=utf-8');

// Traemos el Controlador de Categorias
require_once __DIR__ . '/controllers/CategoriaController.php';

$recurso = $_GET['recurso'] ?? '';

//Identificamos el método por el que llega el pedido
$metodo = $_SERVER['REQUEST_METHOD'];


switch($recurso){
    case 'categorias':

        if($metodo === 'GET'){
            // Si pidieron categorías, le pasamos el pedido al controlador de categorías.
            // El controlador le pide los datos al modelo y responde en JSON.
            CategoriaController::listar();
        }elseif($metodo === 'POST'){
            echo json_encode(['mensaje' => 'Aca se va a crear una categoría']);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
        
        
        break;

    default:
        http_response_code(404);
        echo json_encode(['error'=> 'Recurso no encontrado']);
}