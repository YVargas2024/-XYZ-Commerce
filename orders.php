<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/models/Pedido.php';

// 1. Conectar a la base de datos
$database = new Database();
$db = $database->connect();

// 2. Instanciar el modelo
$pedidoModel = new Pedido($db);

// 3. Obtener todos los pedidos con datos de cliente
$pedidos = $pedidoModel->getAllWithClientInfo();

// 4. Incluir el header
include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-shopping-cart"></i> Gestión de Pedidos</h2>
            <a href="create_order.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Pedido
            </a>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($pedidos) && is_array($pedidos)): ?>
                        <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?= $pedido['id'] ?></td>
                            <td><?= htmlspecialchars($pedido['cliente_nombre'] ?? 'Cliente eliminado') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></td>
                            <td>$<?= number_format($pedido['total'] ?? 0, 2) ?></td>
                            <td>
                                <span class="status-badge <?= htmlspecialchars($pedido['estado'] ?? 'pendiente') ?>">
                                    <?= ucfirst(htmlspecialchars($pedido['estado'] ?? 'Pendiente')) ?>
                                </span>
                            </td>
                            <td>
                                <a href="order_detail.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-primary" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_order.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_order.php?id=<?= $pedido['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   title="Eliminar"
                                   onclick="return confirm('¿Eliminar este pedido?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay pedidos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>