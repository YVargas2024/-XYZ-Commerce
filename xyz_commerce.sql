-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-03-2025 a las 22:34:15
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `xyz_commerce`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `email`, `direccion`, `telefono`, `created_at`) VALUES
(1, 'Juan Pérez', 'juan@example.com', 'Calle Falsa 123, Madrid', '+34 600 111 222', '2025-03-26 18:00:57'),
(2, 'María García', 'maria@example.com', 'Avenida Real 456, Barcelona', '+34 677 889 900', '2025-03-26 18:00:57'),
(3, 'Tech Solutions SL', 'tech@example.com', 'Polígono Industrial, Valencia', '+34 961 111 333', '2025-03-26 18:00:57'),
(4, 'juan prueba', 'juan@gmail.com', NULL, '325789456', '2025-03-26 19:14:25'),
(5, 'Laura Martínez', 'laura.m@example.com', 'Calle Sol 45, Valencia', '+34 611 222 333', '2025-03-26 20:09:06'),
(6, 'Andrés López', 'andres.l@empresa.com', 'Gran Vía 78, Zaragoza', '+34 644 555 666', '2025-03-26 20:09:06'),
(7, 'ElectroHome SL', 'ventas@electrohome.es', 'Pol. Ind. Norte 12, Málaga', '+34 951 222 333', '2025-03-26 20:09:06'),
(8, 'Ana Sánchez', 'ana.s@example.com', 'Av. Central 101, Sevilla', '+34 677 888 999', '2025-03-26 20:09:06'),
(9, 'Global Tech Inc', 'sales@globaltech.com', 'Tech Park 5, London, UK', '+44 20 7946 0958', '2025-03-26 20:09:06'),
(10, 'David González', 'david.g@example.com', 'Calle Luna 33, Bilbao', '+34 688 777 111', '2025-03-26 20:09:06'),
(11, 'Smart Devices SA', 'info@smartdevices.es', 'Calle Innovación 7, Barcelona', '+34 934 444 555', '2025-03-26 20:09:06'),
(12, 'Marta Rodríguez', 'marta.r@example.com', 'Paseo Marítimo 22, Alicante', '+34 699 000 222', '2025-03-26 20:09:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_product`
--

CREATE TABLE `client_product` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `client_product`
--

INSERT INTO `client_product` (`id`, `cliente_id`, `producto_id`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 2),
(4, 2, 4),
(5, 3, 1),
(6, 3, 2),
(7, 3, 3),
(8, 4, 5),
(9, 4, 7),
(10, 5, 6),
(11, 5, 8),
(12, 6, 9),
(13, 6, 10),
(14, 6, 11),
(15, 7, 5),
(16, 7, 12),
(17, 8, 7),
(18, 8, 9),
(19, 8, 11),
(20, 9, 6),
(21, 9, 8),
(22, 10, 10),
(23, 10, 12),
(24, 11, 5),
(25, 11, 6),
(26, 11, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 1, 999.99),
(2, 1, 3, 1, 450.00),
(3, 2, 2, 1, 299.50),
(4, 2, 4, 1, 89.99),
(5, 3, 1, 1, 999.99),
(6, 3, 2, 2, 299.50),
(7, 4, 5, 1, 349.99),
(8, 4, 7, 1, 119.99),
(9, 5, 6, 2, 129.99),
(10, 5, 8, 1, 599.00),
(11, 6, 9, 1, 499.99),
(12, 6, 10, 2, 249.50),
(13, 6, 11, 1, 899.00),
(14, 7, 5, 1, 349.99),
(15, 7, 12, 1, 749.99),
(16, 8, 7, 2, 119.99),
(17, 8, 9, 1, 499.99),
(18, 8, 11, 1, 899.00),
(19, 9, 6, 1, 129.99),
(20, 9, 8, 1, 599.00),
(21, 10, 10, 1, 249.50),
(22, 10, 12, 1, 749.99),
(23, 11, 5, 1, 349.99),
(24, 11, 6, 1, 129.99),
(25, 11, 7, 1, 119.99),
(26, 12, 6, 2, 129.99),
(27, 12, 8, 1, 599.00),
(28, 13, 6, 1, 129.99),
(29, 13, 8, 1, 599.00),
(30, 14, 9, 1, 499.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `fecha`, `estado`, `total`) VALUES
(1, 1, '2025-03-26 18:00:58', 'completado', 1449.99),
(2, 2, '2025-03-26 18:00:58', 'pendiente', 389.49),
(3, 3, '2025-03-26 18:00:58', 'completado', 1749.49),
(4, 4, '2025-03-26 20:09:07', 'completado', 469.98),
(5, 5, '2025-03-26 20:09:07', '', 728.99),
(6, 6, '2025-03-26 20:09:07', 'completado', 1649.49),
(7, 7, '2025-03-26 20:09:07', 'pendiente', 1099.99),
(8, 8, '2025-03-26 20:09:07', '', 1549.48),
(9, 9, '2025-03-26 20:09:07', 'completado', 728.99),
(10, 10, '2025-03-26 20:09:07', '', 999.49),
(11, 11, '2025-03-26 20:09:07', 'pendiente', 599.97),
(12, 5, '2025-03-26 20:42:24', 'pendiente', 858.98),
(13, 5, '2025-03-26 20:42:40', 'pendiente', 728.99),
(14, 6, '2025-03-26 20:48:18', 'pendiente', 499.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `created_at`) VALUES
(1, 'Laptop Pro', 'Portátil 15\" i7 16GB RAM', 999.99, 50, '2025-03-26 18:00:57'),
(2, 'Teléfono Smart', 'Smartphone 128GB Android', 299.50, 100, '2025-03-26 18:00:57'),
(3, 'Monitor 4K', 'Monitor 27\" UHD 4K', 450.00, 30, '2025-03-26 18:00:57'),
(4, 'Teclado Mecánico', 'Teclado RGB switches azules', 89.99, 75, '2025-03-26 18:00:57'),
(5, 'Tablet Elite', 'Tablet 10\" 128GB, 8GB RAM', 349.99, 40, '2025-03-26 20:09:07'),
(6, 'Auriculares Pro', 'Cancelación de ruido, inalámbricos', 129.99, 57, '2025-03-26 20:09:07'),
(7, 'Disco SSD 1TB', 'SSD NVMe, veloc. 3500MB/s', 119.99, 30, '2025-03-26 20:09:07'),
(8, 'Impresora 3D', 'Área 200x200x200mm, resolución 0.1mm', 599.00, 13, '2025-03-26 20:09:07'),
(9, 'Monitor Curvo', '32\" QHD, 144Hz, 1ms', 499.99, 24, '2025-03-26 20:09:07'),
(10, 'Router WiFi 6', 'AX6000, Triple banda', 249.50, 20, '2025-03-26 20:09:07'),
(11, 'Smart TV 55\"', '4K OLED, Android TV', 899.00, 18, '2025-03-26 20:09:07'),
(12, 'Cámara DSLR', '24MP, grabación 4K', 749.99, 12, '2025-03-26 20:09:07'),
(13, 'balon', NULL, 50000.00, 58, '2025-03-26 20:54:46');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `client_product`
--
ALTER TABLE `client_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cliente_id` (`cliente_id`,`producto_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `client_product`
--
ALTER TABLE `client_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `client_product`
--
ALTER TABLE `client_product`
  ADD CONSTRAINT `client_product_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_product_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
