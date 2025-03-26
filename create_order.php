<?php
// Activar reporte de errores para desarrollo (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/PedidoController.php';
require_once __DIR__ . '/src/controllers/ClienteController.php';
require_once __DIR__ . '/src/controllers/ProductoController.php';

try {
    $database = new Database();
    $db = $database->connect();
    
    // Verificar conexión
    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $pedidoController = new PedidoController($db);
    $clienteController = new ClienteController($db);
    $productoController = new ProductoController($db);

    // Obtener todos los clientes
    $clientesData = $clienteController->getClientes();
    if (!isset($clientesData['clientes'])) {
        throw new Exception("Error al obtener clientes");
    }
    $clientes = $clientesData['clientes'];

    // Obtener productos disponibles
    $todosProductos = $productoController->getProductosDisponibles();
    if (!is_array($todosProductos)) {
        throw new Exception("Error al obtener productos");
    }

    // Obtener relaciones cliente-producto
    $relaciones = [];
    $stmt = $db->query("SELECT cliente_id, producto_id FROM client_product");
    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($relaciones[$row['cliente_id']])) {
                $relaciones[$row['cliente_id']] = [];
            }
            $relaciones[$row['cliente_id']][] = $row['producto_id'];
        }
    }

    // Manejo del POST
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $pedidoController->crearPedido(
            $_POST['cliente_id'] ?? null, 
            json_decode($_POST['productos'] ?? '[]', true)
        );
        
        if ($result['success']) {
            header("Location: orders.php?success=1&id={$result['pedido_id']}");
            exit;
        }
        $error = $result['message'] ?? 'Error desconocido al crear pedido';
    }

    // Incluir cabecera
    include 'includes/header.php';
    ?>

    <div class="dashboard">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="header">
                <h2><i class="fas fa-cart-plus"></i> Nuevo Pedido</h2>
            </div>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form id="order-form" method="POST">
                <div class="form-group">
                    <label>Cliente</label>
                    <select name="cliente_id" id="cliente-select" class="form-control" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= htmlspecialchars($cliente['id']) ?>">
                            <?= htmlspecialchars($cliente['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="productos-container" class="mt-4">
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-user-circle fa-2x"></i>
                        <p>Seleccione un cliente para ver los productos disponibles</p>
                    </div>
                </div>

                <input type="hidden" name="productos" id="productos-data">

                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check"></i> Confirmar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pasamos datos desde PHP a JavaScript
        const ALL_PRODUCTS = <?= json_encode($todosProductos) ?>;
        const CLIENT_PRODUCT_RELATIONS = <?= json_encode($relaciones) ?>;
        
        // Depuración
        console.log('Productos cargados:', ALL_PRODUCTS);
        console.log('Relaciones cliente-producto:', CLIENT_PRODUCT_RELATIONS);
    </script>

    <script src="assets/js/create_order.js"></script>
    <?php 
    include 'includes/footer.php';

} catch (Exception $e) {
    // Manejo básico de errores
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>