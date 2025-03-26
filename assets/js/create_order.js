document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('cliente-select');
    const productosContainer = document.getElementById('productos-container');
    const productosData = document.getElementById('productos-data');
    const orderForm = document.getElementById('order-form');

    function showAlert(message, type = 'danger') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Limpiar alertas anteriores
        const existingAlert = productosContainer.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        productosContainer.prepend(alertDiv);
    }

    clienteSelect.addEventListener('change', function() {
        const clienteId = parseInt(this.value);

        if (!clienteId) {
            productosContainer.innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="fas fa-user-circle fa-2x"></i>
                    <p>Seleccione un cliente para ver los productos disponibles</p>
                </div>`;
            return;
        }

        // Mostrar estado de carga
        productosContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Cargando productos disponibles...</p>
            </div>`;

        // Pequeño retraso para mejor experiencia de usuario
        setTimeout(() => {
            try {
                // Filtrar productos disponibles para este cliente
                const productosDisponiblesIds = CLIENT_PRODUCT_RELATIONS[clienteId] || [];
                const productosDisponibles = ALL_PRODUCTS.filter(producto =>
                    productosDisponiblesIds.includes(producto.id)
                );

                if (productosDisponibles.length === 0) {
                    productosContainer.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay productos disponibles para este cliente.
                        </div>`;
                    return;
                }

                // Generar tabla de productos
                let html = `
                    <div class="table-responsive">
                        <h4 class="mb-3">
                            <i class="fas fa-boxes"></i> Productos Disponibles
                        </h4>
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th width="120">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>`;

                productosDisponibles.forEach(producto => {
                    html += `
                        <tr class="producto-item" data-id="${producto.id}">
                            <td>${producto.nombre}</td>
                            <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                            <td class="${producto.stock > 10 ? 'text-success' : 'text-warning'}">
                                ${producto.stock} unidades
                            </td>
                            <td>
                                <input type="number" 
                                       min="0" 
                                       max="${producto.stock}" 
                                       value="0" 
                                       class="form-control cantidad-input">
                            </td>
                        </tr>`;
                });

                html += `</tbody></table></div>`;
                productosContainer.innerHTML = html;

                // Validación en tiempo real de cantidades
                document.querySelectorAll('.cantidad-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const max = parseInt(this.max);
                        const value = parseInt(this.value) || 0;

                        if (value < 0) {
                            this.value = 0;
                        } else if (value > max) {
                            this.value = max;
                            showAlert(`La cantidad no puede exceder el stock disponible (${max})`, 'warning');
                        }
                    });
                });

            } catch (error) {
                console.error('Error:', error);
                productosContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        Error al cargar productos: ${error.message}
                    </div>`;
            }
        }, 300);
    });

    orderForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const clienteId = clienteSelect.value;
        const productos = [];
        let totalProductos = 0;

        // Validar cliente seleccionado
        if (!clienteId) {
            showAlert('Debe seleccionar un cliente', 'danger');
            return;
        }

        // Recoger productos seleccionados
        document.querySelectorAll('.producto-item').forEach(row => {
            const cantidadInput = row.querySelector('.cantidad-input');
            const cantidad = parseInt(cantidadInput.value) || 0;

            if (cantidad > 0) {
                productos.push({
                    producto_id: parseInt(row.dataset.id),
                    cantidad: cantidad
                });
                totalProductos += cantidad;
            }
        });

        // Validar al menos un producto con cantidad > 0
        if (totalProductos === 0) {
            showAlert('Debe seleccionar al menos un producto con cantidad mayor a 0', 'warning');
            return;
        }

        // Confirmación antes de enviar
        if (!confirm('¿Está seguro de crear este pedido?')) {
            return;
        }

        // Deshabilitar botón para evitar múltiples envíos
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        // Enviar datos
        productosData.value = JSON.stringify(productos);
        this.submit();
    });
});