<?php

require_once __DIR__ . '/../models/Proveedor.php';

class ProveedorController
{
    public static function listar()
    {
        echo json_encode(Proveedor::listar());
    }

    public static function ver($id)
    {
        $proveedor = Proveedor::buscar($id);

        if ($proveedor === false) {
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'El proveedor no existe']);
            return;
        }

        echo json_encode($proveedor);
    }

    public static function crear()
    {
        $nombre        = trim($_POST['nombre'] ?? '');
        $telefono      = self::opcional($_POST['telefono'] ?? '');
        $observaciones = self::opcional($_POST['observaciones'] ?? '');

        if ($nombre === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio']);
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

    // Un campo opcional: sin espacios a los costados, y NULL si quedó vacío
    private static function opcional($valor)
    {
        $valor = trim($valor);

        if ($valor === '') {
            return null;
        }

        return $valor;
    }
}