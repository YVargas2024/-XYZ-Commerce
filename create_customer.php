<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/ClienteController.php';

$database = new Database();
$db = $database->connect();
$controller = new ClienteController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->create($_POST);
    if ($result['success']) {
        header('Location: customers.php?success=1');
        exit;
    }
    $error = $result['message'];
}

include 'includes/header.php';
?>

<div class="dashboard">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h2><i class="fas fa-user-plus"></i> Nuevo Cliente</h2>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required class="form-control">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required class="form-control">
            </div>
            
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>