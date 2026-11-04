<?php

//Avisamos al cliente que la respuesta del servidor es un archivo de texto en formato JSON y codificado en UTF 8
header('Content-Type: application/json; charset=utf-8');

// Traemos el Controlador de Categorias, Proveedor, Producto...
require_once __DIR__ . '/controllers/CategoriaController.php';
require_once __DIR__ . '/controllers/ProveedorController.php';
require_once __DIR__ . '/controllers/ProductoController.php';

//Qué página se busca mostrar
$recurso = $_GET['recurso'] ?? '';

//Comprobamos si recibimos un id
$id = $_GET['id'] ?? null;

//Identificamos el método por el que llega el pedido
$metodo = $_SERVER['REQUEST_METHOD'];


switch($recurso){
    case 'categorias':

        if($metodo === 'GET'){
            /* Si pidieron categorías, le pasamos el pedido al controlador de categorías.
             El controlador le pide los datos al modelo y responde en JSON.*/

            if($id === null){
                CategoriaController::listar();  
            }else{
                CategoriaController::ver($id);
            }


        }elseif($metodo === 'POST'){
            CategoriaController::crear();
        }elseif($metodo === 'PUT'){
            if($id === null){
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta el id de la categoría']);
            }else{
                CategoriaController::modificar($id);
            }
        }elseif($metodo === 'DELETE'){
            if($id === null){
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta el id de la categoría']);
            }else{
                CategoriaController::eliminar($id);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
        
        break;

    case 'proveedores':
        if ($metodo === 'GET') {
            // Consultar: la lista, o uno solo si vino el id
            if ($id === null) {
                ProveedorController::listar();
            } else {
                ProveedorController::ver($id);
            }
        } elseif ($metodo === 'POST') {
            // Crear
            ProveedorController::crear();
        } elseif ($metodo === 'PUT') {
            // Modificar: hace falta saber cuál
            if ($id === null) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta el id del proveedor']);
            } else {
                ProveedorController::modificar($id);
            }
        } elseif ($metodo === 'DELETE') {
            // Eliminar: hace falta saber cuál
            if ($id === null) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta el id del proveedor']);
            } else {
                ProveedorController::eliminar($id);
            }
        } else {
            // Cualquier otro método
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
        }
        break;

    case 'productos':
        if($metodo === 'GET'){
            if($id === null){
                ProductoController::listar();
            }else{
                ProductoController::ver($id);
            }
        }elseif($metodo === 'POST'){
            ProductoController::crear();
        }elseif($metodo === 'PUT'){
             // Modificar: comprobamos si se pasó el id
            if ($id === null) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta el id del producto']);
            } else {
                ProductoController::modificar($id);
            }
        }elseif($metodo === 'DELETE'){
            //Comprobamos si hay id
            if ($id === null) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta el id del producto']);
            } else {
                ProductoController::eliminar($id);
            }
        }else{
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error'=> 'Recurso no encontrado']);
}