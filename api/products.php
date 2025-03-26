<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/ProductoController.php';

$database = new Database();
$db = $database->connect();

$controller = new ProductoController($db);

include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-box-open"></i> Productos</h2>
            <a href="create_product.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>
        
        <?php $controller->showAll(); ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>