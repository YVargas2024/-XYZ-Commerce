USE xyz_commerce;

-- Insertar clientes
INSERT INTO clientes (nombre, email, direccion, telefono) VALUES
('Juan Pérez', 'juan@example.com', 'Calle Falsa 123, Madrid', '+34 600 111 222'),
('María García', 'maria@example.com', 'Avenida Real 456, Barcelona', '+34 677 889 900'),
('Tech Solutions SL', 'tech@example.com', 'Polígono Industrial, Valencia', '+34 961 111 333');

-- Insertar productos
INSERT INTO productos (nombre, descripcion, precio, stock) VALUES
('Laptop Pro', 'Portátil 15" i7 16GB RAM', 999.99, 50),
('Teléfono Smart', 'Smartphone 128GB Android', 299.50, 100),
('Monitor 4K', 'Monitor 27" UHD 4K', 450.00, 30),
('Teclado Mecánico', 'Teclado RGB switches azules', 89.99, 75);

-- Asignar productos a clientes
INSERT INTO client_product (cliente_id, producto_id) VALUES
(1, 1), (1, 3),  -- Juan puede comprar Laptop y Monitor
(2, 2), (2, 4),  -- María puede comprar Teléfono y Teclado
(3, 1), (3, 2), (3, 3);  -- Tech Solutions puede comprar todos

-- Crear pedidos de ejemplo
INSERT INTO pedidos (cliente_id, estado, total) VALUES
(1, 'completado', 1449.99),
(2, 'pendiente', 389.49),
(3, 'completado', 1749.49);

-- Detalles de pedidos
INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES
(1, 1, 1, 999.99),  
(1, 3, 1, 450.00),  
(2, 2, 1, 299.50),   
(2, 4, 1, 89.99),    
(3, 1, 1, 999.99),    
(3, 2, 2, 299.50);    