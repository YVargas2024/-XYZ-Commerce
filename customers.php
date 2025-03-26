<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/models/Cliente.php';

// 1. Conectar a la base de datos
$database = new Database();
$db = $database->connect();

// 2. Instanciar el modelo
$clienteModel = new Cliente($db);

// 3. Obtener todos los clientes
$stmt = $clienteModel->read();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Incluir el header
include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-users"></i> Gestión de Clientes</h2>
            <a href="create_customer.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </a>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id'] ?></td>
                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                        <td><?= htmlspecialchars($cliente['email']) ?></td>
                        <td><?= $cliente['telefono'] ?? 'N/A' ?></td>
                        <td>
                            <a href="edit_customer.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete_customer.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cliente?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>