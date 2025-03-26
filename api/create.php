<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/controllers/PedidoController.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Validación básica
if (empty($input['cliente_id']) || empty($input['productos'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$database = new Database();
$db = $database->connect();

$controller = new PedidoController($db);
$result = $controller->crearPedido($input['cliente_id'], $input['productos']);

if ($result['success']) {
    http_response_code(201);
} else {
    http_response_code(400);
}

echo json_encode($result);
?>