<?php

require_once __DIR__ . '/../models/Proveedor.php';

class ProveedorController
{
    public static function listar(){
        echo json_encode(Proveedor::listar());
    }

    public static function ver($id){
        $proveedor = Proveedor::buscar($id);

        if ($proveedor === false) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El proveedor no existe']);
            return;
        }

        echo json_encode($proveedor);
    }

    // Crear

    public static function crear(){
        $nombre        = trim($_POST['nombre'] ?? '');
        $telefono      = self::opcional($_POST['telefono'] ?? '');
        $observaciones = self::opcional($_POST['observaciones'] ?? '');

        $error = self::validar($nombre);

        if ($error !== null) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $error]);
            return;
        }

        try {
            $id = Proveedor::crear($nombre, $telefono, $observaciones);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                http_response_code(409);
                echo json_encode(['ok' => false, 'error' => 'Ya existe un proveedor con ese nombre']);
                return;
            }
            throw $e;
        }

        http_response_code(201);
        echo json_encode(['ok' => true, 'id' => $id]);
    }

    // Modificar

    public static function modificar($id){
        if (Proveedor::buscar($id) === false) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El proveedor no existe']);
            return;
        }

        $datos = json_decode(file_get_contents('php://input'), true) ?? [];

        $nombre        = trim($datos['nombre'] ?? '');
        $telefono      = self::opcional($datos['telefono'] ?? '');
        $observaciones = self::opcional($datos['observaciones'] ?? '');

        $error = self::validar($nombre);

        if ($error !== null) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $error]);
            return;
        }

        try {
            Proveedor::modificar($id, $nombre, $telefono, $observaciones);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                http_response_code(409);
                echo json_encode(['ok' => false, 'error' => 'Ya existe un proveedor con ese nombre']);
                return;
            }
            throw $e;
        }

        echo json_encode(['ok' => true]);
    }

    // Eliminar

    public static function eliminar($id){
        if (Proveedor::buscar($id) === false) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El proveedor no existe']);
            return;
        }

        try {
            Proveedor::eliminar($id);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1451) {
                http_response_code(409);
                echo json_encode(['ok' => false, 'error' => 'No se puede eliminar: el proveedor tiene productos asociados']);
                return;
            }
            throw $e;
        }

        echo json_encode(['ok' => true]);
    }

    // Funcionas validación
    // Un campo opcional: sin espacios a los costados, y NULL si quedó vacío
    private static function opcional($valor){
        $valor = trim($valor);

        if ($valor === '') {
            return null;
        }

        return $valor;
    }

    // Devuelve el mensaje de error, o null si está todo bien
    private static function validar($nombre){
        if ($nombre === '') {
            return 'El nombre es obligatorio';
        }

        return null;
    }
}