<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/ProductoController.php';

$database = new Database();
$db = $database->connect();

$productoController = new ProductoController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre' => $_POST['nombre'],
        'precio' => $_POST['precio'],
        'stock' => $_POST['stock']
    ];
    
    $result = $productoController->create($data);
    
    if ($result['success']) {
        header("Location: products.php?success=1");
    } else {
        header("Location: products.php?error=" . urlencode($result['message'] ?? "Error al crear producto"));
    }
    exit;
}

include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-plus-circle"></i> Nuevo Producto</h2>
            <a href="products.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Precio</label>
                <input type="number" step="0.01" name="precio" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Stock Inicial</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>