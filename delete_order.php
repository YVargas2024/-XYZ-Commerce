<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/PedidoController.php';

$database = new Database();
$db = $database->connect();

$pedidoController = new PedidoController($db);

$id = $_GET['id'] ?? null;

if ($id) {
    $result = $pedidoController->delete($id);
    
    if ($result['success']) {
        header("Location: orders.php?success=Pedido eliminado correctamente");
    } else {
        header("Location: orders.php?error=" . urlencode($result['message']));
    }
} else {
    header("Location: orders.php?error=ID de pedido no especificado");
}
exit;
?>