document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para eliminar productos
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este producto?')) {
                e.preventDefault();
            }
        });
    });

    // Actualizar el dashboard cada 30 segundos
    if (document.querySelector('.dashboard')) {
        setInterval(() => {
            fetch('api/stats.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelectorAll('.stat-info p')[0].textContent = data.clientes;
                        document.querySelectorAll('.stat-info p')[1].textContent = data.productos;
                        document.querySelectorAll('.stat-info p')[2].textContent = data.pedidos;
                    }
                });
        }, 30000);
    }
});