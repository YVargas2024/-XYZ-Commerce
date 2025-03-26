document.addEventListener('DOMContentLoaded', function() {
    // Cargar estadísticas
    loadStats();

    // Cargar pedidos recientes
    loadRecentOrders();
});

function loadStats() {
    fetch('api/stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('clientes-count').textContent = data.clientes;
                document.getElementById('productos-count').textContent = data.productos;
                document.getElementById('pedidos-count').textContent = data.pedidos;
            } else {
                console.error('Error al cargar estadísticas:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function loadRecentOrders() {
    fetch('api/orders.php?recent=true')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.querySelector('#orders-table tbody');
                tableBody.innerHTML = '';

                data.orders.forEach(order => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>#${order.id}</td>
                        <td>${order.cliente_nombre}</td>
                        <td>${new Date(order.fecha).toLocaleDateString()}</td>
                        <td>$${order.total.toFixed(2)}</td>
                        <td><span class="status status-${order.estado}">${order.estado}</span></td>
                        <td>
                            <button class="btn btn-primary" onclick="viewOrder(${order.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            } else {
                console.error('Error al cargar pedidos:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function viewOrder(orderId) {
    window.location.href = `order_detail.php?id=${orderId}`;
}