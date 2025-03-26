<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/PedidoController.php';

$database = new Database();
$db = $database->connect();

$pedidoController = new PedidoController($db);

// Obtener el ID del pedido
$pedido_id = $_GET['id'] ?? null;

if (!$pedido_id) {
    header("Location: orders.php?error=Pedido no especificado");
    exit;
}

// Obtener información del pedido
$pedido = $pedidoController->getPedidoById($pedido_id);

if (!$pedido) {
    header("Location: orders.php?error=Pedido no encontrado");
    exit;
}

include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-file-invoice"></i> Detalle del Pedido #<?= $pedido['id'] ?></h2>
            <a href="orders.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Pedidos
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Información del Cliente</h5>
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['cliente_nombre'] ?? 'N/A') ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($pedido['cliente_email'] ?? 'N/A') ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['cliente_telefono'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Información del Pedido</h5>
                        <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-<?= 
                                $pedido['estado'] == 'completado' ? 'success' : 
                                ($pedido['estado'] == 'pendiente' ? 'warning' : 'secondary') 
                            ?>">
                                <?= ucfirst($pedido['estado']) ?>
                            </span>
                        </p>
                        <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Productos del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pedido['detalles'])): ?>
                                <tr>
                                    <td colspan="4" class="text-center">No hay productos en este pedido</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pedido['detalles'] as $detalle): ?>
                                <tr>
                                    <td><?= htmlspecialchars($detalle['nombre_producto'] ?? 'Producto desconocido') ?></td>
                                    <td>$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                                    <td><?= $detalle['cantidad'] ?></td>
                                    <td>$<?= number_format($detalle['precio_unitario'] * $detalle['cantidad'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>$<?= number_format($pedido['total'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>