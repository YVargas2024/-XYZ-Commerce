<?php
// Verificar estructura de directorios antes de continuar
if (!file_exists(__DIR__ . '/config/database.php')) {
    die("Error: El archivo database.php no existe en la ruta esperada: " . __DIR__ . '/config/database.php');
}

if (!file_exists(__DIR__ . '/includes/header.php')) {
    die("Error: El archivo header.php no existe en la ruta esperada: " . __DIR__ . '/includes/header.php');
}

// Configuración de rutas absolutas
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/PedidoController.php';
require_once __DIR__ . '/src/controllers/ClienteController.php';
require_once __DIR__ . '/src/controllers/ProductoController.php';

// Inicializar conexión y controladores
try {
    $database = new Database();
    $db = $database->connect();

    $pedidoController = new PedidoController($db);
    $clienteController = new ClienteController($db);
    $productoController = new ProductoController($db);
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Manejar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $result = $pedidoController->update($_POST['id'], [
            'estado' => $_POST['estado']
        ]);
        
        if ($result['success']) {
            header("Location: orders.php?success=Pedido actualizado correctamente");
            exit;
        } else {
            $error = $result['message'];
        }
    } catch (Exception $e) {
        $error = "Error al actualizar el pedido: " . $e->getMessage();
    }
}

// Obtener ID del pedido
$pedido_id = $_GET['id'] ?? null;

if (!$pedido_id || !is_numeric($pedido_id)) {
    header("Location: orders.php?error=ID de pedido no válido");
    exit;
}

// Obtener datos del pedido
try {
    $pedido = $pedidoController->getPedidoById($pedido_id);
    if (!$pedido) {
        header("Location: orders.php?error=Pedido no encontrado");
        exit;
    }
    
    $detalles = $pedidoController->getDetallesPedido($pedido_id);
} catch (Exception $e) {
    die("Error al obtener datos del pedido: " . $e->getMessage());
}

// Incluir cabecera
require_once __DIR__ . '/includes/header.php';
?>

<div class="dashboard">
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header bg-primary text-white p-3 rounded-top">
            <h2><i class="fas fa-edit"></i> Editar Pedido #<?= htmlspecialchars($pedido['id']) ?></h2>
            <a href="orders.php" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Volver a Pedidos
            </a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= htmlspecialchars($pedido['id']) ?>">
            
            <div class="card mb-4 mt-3 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5><i class="fas fa-user"></i> Información del Cliente</h5>
                                <hr>
                                <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['cliente_nombre'] ?? 'N/A') ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($pedido['cliente_email'] ?? 'N/A') ?></p>
                                <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['cliente_telefono'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5><i class="fas fa-truck"></i> Estado del Pedido</h5>
                                <hr>
                                <div class="form-group">
                                    <label for="estado" class="form-label">Seleccione estado:</label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="pendiente" <?= $pedido['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="procesando" <?= $pedido['estado'] === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                                        <option value="completado" <?= $pedido['estado'] === 'completado' ? 'selected' : '' ?>>Completado</option>
                                        <option value="cancelado" <?= $pedido['estado'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione un estado válido
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Productos del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-end">Precio Unitario</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td><?= htmlspecialchars($detalle['nombre_producto'] ?? 'Producto desconocido') ?></td>
                                    <td class="text-end">$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($detalle['cantidad']) ?></td>
                                    <td class="text-end">$<?= number_format($detalle['precio_unitario'] * $detalle['cantidad'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end">$<?= number_format($pedido['total'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-4 d-flex justify-content-between">
                <a href="orders.php" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php';
?>

<script>
// Validación del formulario
(function() {
    'use strict';
    const form = document.querySelector('.needs-validation');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
})();
</script>