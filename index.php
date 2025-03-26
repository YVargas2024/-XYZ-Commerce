<?php
// Activar errores para desarrollo (quitar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/models/Cliente.php';
require_once __DIR__ . '/src/models/Producto.php';
require_once __DIR__ . '/src/models/Pedido.php';

// 1. Conexión a DB
$database = new Database();
$db = $database->connect();

// 2. Inicializar modelos
$clienteModel = new Cliente($db);
$productoModel = new Producto($db);
$pedidoModel = new Pedido($db);

// 3. Obtener datos
$totalClientes = $clienteModel->count();
$totalProductos = $productoModel->count();
$totalPedidos = $pedidoModel->count();
$ultimosPedidos = $pedidoModel->getRecent(5); // Asegúrate que este método exista



// 4. Incluir vista
include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-tachometer-alt"></i> Panel</h2>
            <div class="user-info">
                <span>Bienvenido, administrador</span>
                <img src="assets/images/avatar.png" alt="Usuario">
            </div>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #4CAF50;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Clientes</h3>
                    <p><?= $totalClientes ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #2196F3;">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="stat-info">
                    <h3>Productos</h3>
                    <p><?= $totalProductos ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #FF9800;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>Pedidos</h3>
                    <p><?= $totalPedidos ?></p>
                </div>
            </div>
        </div>
        
        <div class="recent-orders">
            <h3><i class="fas fa-clock"></i> Pedidos Recientes</h3>
            <div class="table-container">
                <table>
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
                        <?php if(is_array($ultimosPedidos)): ?>
                            <?php foreach ($ultimosPedidos as $pedido): ?>
                            <tr>
                                <td>#<?= $pedido['id'] ?></td>
                                <td><?= htmlspecialchars($pedido['cliente_nombre'] ?? 'N/A') ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['fecha'])) ?></td>
                                <td>$<?= number_format($pedido['total'] ?? 0, 2) ?></td>
                                <td>
                                    <span class="status-badge <?= $pedido['estado'] ?? 'pendiente' ?>">
                                        <?= ucfirst($pedido['estado'] ?? 'Pendiente') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="order_detail.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No hay pedidos recientes</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>