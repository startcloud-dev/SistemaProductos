<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../modelos/Producto.php';
require_once __DIR__ . '/../controladores/productoControlador.php';


$database = new Conexion();
$db = $database->getConexion();
$controlador = new ProductoControlador($db);


$action = $_GET['action'] ?? '';

switch ($action) {
    case 'cargarCombos':
        $controlador->cargarCombos();
        break;
    default:
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        break;
}