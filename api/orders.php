<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../src/models/Pedido.php';

$database = new Database();
$db = $database->connect();

$pedidoModel = new Pedido($db);

try {
    if(isset($_GET['recent'])) {  // ✅ Corrección: Paréntesis completos
        // Obtener pedidos recientes (últimos 5)
        $stmt = $pedidoModel->readRecent(5);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'orders' => $pedidos
        ]);
    } else {
        // Obtener todos los pedidos (versión básica sin paginación)
        $query = 'SELECT p.*, c.nombre as cliente_nombre 
                 FROM pedidos p
                 JOIN clientes c ON p.cliente_id = c.id
                 ORDER BY p.fecha DESC';
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'orders' => $pedidos,
            'total' => count($pedidos)
        ]);
    }
    
} catch(Exception $e) {
    http_response_code(500); // ✅ Código de error HTTP
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar pedidos: ' . $e->getMessage()
    ]);
}
?>