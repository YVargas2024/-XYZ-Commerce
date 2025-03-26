<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../src/models/Cliente.php';
require_once '../src/models/Producto.php';
require_once '../src/models/Pedido.php';

$database = new Database();
$db = $database->connect();

$clienteModel = new Cliente($db);
$productoModel = new Producto($db);
$pedidoModel = new Pedido($db);

try {
    $clientes = $clienteModel->read()->rowCount();
    $productos = $productoModel->read()->rowCount();
    $pedidos = $pedidoModel->read()->rowCount();
    
    echo json_encode([
        'success' => true,
        'clientes' => $clientes,
        'productos' => $productos,
        'pedidos' => $pedidos
    ]);
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>