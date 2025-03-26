<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

try {
    $cliente_id = $_GET['cliente_id'] ?? null;

    if (!$cliente_id) {
        throw new Exception('Cliente no especificado');
    }

    $database = new Database();
    $db = $database->connect();

    $query = "SELECT p.* FROM productos p
              JOIN client_product cp ON p.id = cp.producto_id
              WHERE cp.cliente_id = ? AND p.stock > 0
              ORDER BY p.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$cliente_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'productos' => $productos
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}