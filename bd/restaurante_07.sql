-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2025 a las 15:10:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurante`
--
DROP DATABASE IF EXISTS `restaurante`;
CREATE DATABASE IF NOT EXISTS `restaurante` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `restaurante`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '0- bloqueado 1- activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `estado`) VALUES
(1, 'Bebidas', 1),
(2, 'Aperitivo', 1),
(3, 'Primero', 1),
(4, 'Segundo', 1),
(5, 'Postres', 1),
(6, 'Cafés', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

DROP TABLE IF EXISTS `mesa`;
CREATE TABLE `mesa` (
  `numMesa` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '0- bloqueada 1- desbloqueada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`numMesa`, `estado`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

DROP TABLE IF EXISTS `pedido`;
CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '0- no pagado 1- pagado',
  `numMesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '0- bloqueado 1-activo',
  `categoria` int(11) NOT NULL,
  `estado_categoria` tinyint(1) NOT NULL COMMENT '0- bloqueado 1- activo',
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `precio`, `stock`, `estado`, `categoria`, `estado_categoria`, `imagen`) VALUES
(1, 'Agua mineral', 1.50, 100, 1, 1, 1, '../img_productos/001.png'),
(2, 'Coca-Cola', 2.00, 80, 1, 1, 1, '../img_productos/002.png'),
(3, 'Fanta Naranja', 2.00, 75, 1, 1, 1, '../img_productos/003.png'),
(4, 'Cerveza', 2.50, 200, 1, 1, 1, '../img_productos/004.png'),
(5, 'Té frío', 1.80, 50, 1, 1, 1, '../img_productos/005.png'),
(6, 'Patatas fritas', 3.00, 150, 1, 2, 1, '../img_productos/006.png'),
(7, 'Aceitunas', 2.50, 60, 1, 2, 1, '../img_productos/007.png'),
(8, 'Croquetas caseras', 4.50, 30, 1, 2, 1, '../img_productos/008.png'),
(9, 'Calamares a la romana', 5.00, 50, 1, 2, 1, '../img_productos/009.png'),
(10, 'Pan con alioli', 2.00, 45, 1, 2, 1, '../img_productos/010.png'),
(11, 'Ensalada mixta', 6.00, 25, 1, 3, 1, '../img_productos/011.png'),
(12, 'Sopa de verduras', 5.50, 20, 1, 3, 1, '../img_productos/012.png'),
(13, 'Gazpacho', 5.00, 30, 1, 3, 1, '../img_productos/013.png'),
(14, 'Pasta boloñesa', 7.50, 40, 1, 3, 1, '../img_productos/014.png'),
(15, 'Arroz a la cubana', 7.00, 60, 1, 3, 1, '../img_productos/015.png'),
(16, 'Pollo al horno', 9.00, 45, 1, 4, 1, '../img_productos/016.png'),
(17, 'Entrecot de ternera', 14.00, 25, 1, 4, 1, '../img_productos/017.png'),
(18, 'Salmón a la plancha', 12.00, 25, 1, 4, 1, '../img_productos/018.png'),
(19, 'Lomo con patatas', 8.50, 25, 1, 4, 1, '../img_productos/019.png'),
(20, 'Hamburguesa completa', 9.50, 40, 1, 4, 1, '../img_productos/020.png'),
(21, 'Flan casero', 3.50, 25, 1, 5, 1, '../img_productos/021.png'),
(22, 'Tarta de queso', 4.00, 30, 1, 5, 1, '../img_productos/022.png'),
(23, 'Helado de vainilla', 3.00, 40, 1, 5, 1, '../img_productos/023.png'),
(24, 'Brownie con helado', 4.50, 20, 1, 5, 1, '../img_productos/024.png'),
(25, 'Fruta del tiempo', 2.50, 25, 1, 5, 1, '../img_productos/025.png'),
(26, 'Café solo', 1.20, 100, 1, 6, 1, '../img_productos/026.png'),
(27, 'Café con leche', 1.50, 100, 1, 6, 1, '../img_productos/027.png'),
(28, 'Cappuccino', 2.00, 50, 1, 6, 1, '../img_productos/028.png'),
(29, 'Café americano', 1.80, 40, 1, 6, 1, '../img_productos/029.png'),
(30, 'Carajillo', 2.20, 30, 1, 6, 1, '../img_productos/030.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_pedido`
--

DROP TABLE IF EXISTS `producto_pedido`;
CREATE TABLE `producto_pedido` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `servido` tinyint(1) NOT NULL COMMENT '0- no servido 1- servido',
  `comentario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

DROP TABLE IF EXISTS `reserva`;
CREATE TABLE `reserva` (
  `dni` varchar(255) NOT NULL,
  `numMesa` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `comensales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `dni` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL COMMENT '0- normal\r\n1- camarero\r\n2- encargado',
  `email` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL COMMENT '0- desbloqueado 1- bloqueado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`dni`, `password`, `nombre`, `apellido`, `rol`, `email`, `telefono`, `direccion`, `estado`) VALUES
('1', '1', 'Álvaro', 'Martínez', 2, 'alvaro@gmail.com', '666666666', NULL, 0),
('2', '2', 'Pedro', 'El camarero', 1, '2@gmail.com', '2', NULL, 0),
('3', '3', 'Cliente01', 'Prueba01', 0, 'cliprueb01@quintopino.com', '3', NULL, 0),
('4', '4', 'Cliente02', 'Prueba02', 0, 'cliprueb02@quintopino.com', '4', NULL, 0),
('5', '5', 'Cliente03', 'Prueba03', 0, 'cliprueb03@quintopino.com', '9871', 'C/ goku, 3c', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`numMesa`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_pedido_usuario` (`usuario`),
  ADD KEY `FK_pedido_mesa` (`numMesa`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria` (`categoria`);

--
-- Indices de la tabla `producto_pedido`
--
ALTER TABLE `producto_pedido`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idPedido_2` (`idPedido`,`idProducto`),
  ADD KEY `idPedido` (`idPedido`,`idProducto`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`dni`,`numMesa`,`fecha`),
  ADD KEY `dni` (`dni`,`numMesa`),
  ADD KEY `FK_reserva_mesa` (`numMesa`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `producto_pedido`
--
ALTER TABLE `producto_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `FK_pedido_mesa` FOREIGN KEY (`numMesa`) REFERENCES `mesa` (`numMesa`),
  ADD CONSTRAINT `FK_pedido_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`dni`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `FK_categoria_producto` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_pedido`
--
ALTER TABLE `producto_pedido`
  ADD CONSTRAINT `producto_pedido_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_pedido_ibfk_3` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `FK_reserva_mesa` FOREIGN KEY (`numMesa`) REFERENCES `mesa` (`numMesa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_reserva_usuario` FOREIGN KEY (`dni`) REFERENCES `usuario` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
