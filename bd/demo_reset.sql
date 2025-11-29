-- Reinicio diario de la demo — derivado de restaurante_08_con_datos.sql
--
-- A diferencia del dump original, este archivo NO hace DROP/CREATE DATABASE
-- ni USE: solo trabaja a nivel de tabla, porque el usuario de MySQL que
-- cPanel asigna a la BBDD normalmente solo tiene privilegios sobre esa BBDD,
-- no privilegios de servidor para crear/borrar bases de datos completas.
--
-- FOREIGN_KEY_CHECKS se desactiva porque este script se ejecuta cada noche
-- sobre una BBDD que YA tiene las tablas y sus claves foráneas puestas (por
-- la ejecución de la noche anterior): sin esto, los DROP TABLE fallarían por
-- las relaciones entre pedido/producto/producto_pedido/reserva/usuario.
--
-- Regenerar a mano si restaurante_08_con_datos.sql cambia: mismo contenido,
-- quitando el bloque DROP DATABASE/CREATE DATABASE/USE del principio y
-- envolviendo el resto entre los dos SET FOREIGN_KEY_CHECKS de aquí abajo.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'fecha del pedido',
  `estado` tinyint(1) NOT NULL COMMENT '0- no servido 1- servido 2- pagado',
  `numMesa` int(11) NOT NULL,
  `comensales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `usuario`, `fecha`, `estado`, `numMesa`, `comensales`) VALUES
(1, '3', '2025-11-01 12:05:00', 2, 1, 4),
(2, '4', '2025-11-22 22:00:04', 2, 5, 10),
(3, '5', '2025-11-22 22:00:05', 2, 2, 2),
(4, '3', '2025-11-22 22:00:07', 2, 8, 6),
(5, '4', '2025-11-03 12:30:00', 2, 3, 3),
(6, '5', '2025-11-22 22:00:08', 2, 7, 12),
(7, '3', '2025-11-22 22:00:11', 2, 4, 1),
(8, '4', '2025-11-22 22:00:12', 2, 6, 5),
(9, '5', '2025-11-22 22:00:14', 2, 9, 8),
(10, '3', '2025-11-05 21:00:00', 2, 2, 15),
(11, '4', '2025-11-22 22:00:15', 2, 1, 2),
(12, '5', '2025-11-06 20:30:00', 2, 5, 9),
(13, '3', '2025-11-22 22:00:16', 2, 3, 3),
(14, '4', '2025-11-22 22:00:18', 2, 4, 6),
(15, '5', '2025-11-08 12:25:00', 2, 6, 7),
(16, '3', '2025-11-22 22:00:19', 2, 8, 2),
(17, '4', '2025-11-22 22:00:22', 2, 7, 11),
(18, '5', '2025-11-22 22:00:23', 2, 9, 5),
(19, '3', '2025-11-10 13:35:00', 2, 2, 4),
(20, '4', '2025-11-22 22:00:25', 2, 3, 8),
(21, '5', '2025-11-22 22:00:26', 2, 4, 2),
(22, '3', '2025-11-22 22:00:27', 2, 5, 13),
(23, '4', '2025-11-12 13:10:00', 2, 6, 6),
(24, '5', '2025-11-22 22:00:29', 2, 7, 9),
(25, '3', '2025-11-22 22:00:30', 2, 8, 3),
(26, '4', '2025-11-22 22:00:32', 2, 9, 14),
(27, '5', '2025-11-14 13:25:00', 2, 1, 5),
(28, '3', '2025-11-22 22:00:33', 2, 2, 7),
(29, '4', '2025-11-15 12:15:00', 2, 3, 10),
(30, '5', '2025-11-22 22:00:35', 2, 4, 6);

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

--
-- Volcado de datos para la tabla `producto_pedido`
--

INSERT INTO `producto_pedido` (`id`, `idPedido`, `idProducto`, `cant`, `servido`, `comentario`) VALUES
(1, 1, 1, 4, 1, ''),
(2, 1, 8, 2, 1, 'Sin cebolla'),
(3, 1, 16, 4, 1, ''),
(4, 1, 21, 4, 1, ''),
(5, 2, 4, 10, 1, ''),
(6, 2, 6, 3, 1, ''),
(7, 2, 9, 2, 1, ''),
(8, 2, 22, 5, 1, 'Cumpleaños en la mesa'),
(9, 3, 2, 2, 1, ''),
(10, 3, 11, 1, 1, ''),
(11, 3, 14, 2, 1, 'Para compartir'),
(12, 4, 5, 6, 1, 'Sin hielo'),
(13, 4, 12, 2, 1, ''),
(14, 4, 18, 6, 1, ''),
(15, 4, 23, 6, 1, 'Con sirope de chocolate'),
(16, 5, 4, 3, 1, ''),
(17, 5, 15, 3, 1, ''),
(18, 5, 17, 3, 1, 'Uno muy hecho'),
(19, 5, 24, 3, 1, ''),
(20, 6, 1, 6, 1, ''),
(21, 6, 7, 2, 1, ''),
(22, 6, 20, 6, 1, 'Extra queso'),
(23, 6, 25, 3, 1, ''),
(24, 7, 3, 1, 1, ''),
(25, 7, 6, 1, 1, ''),
(26, 7, 19, 1, 1, 'Sin salsa'),
(27, 8, 5, 5, 1, ''),
(28, 8, 13, 3, 1, ''),
(29, 8, 16, 2, 1, ''),
(30, 8, 21, 5, 1, ''),
(31, 9, 2, 4, 1, ''),
(32, 9, 9, 3, 1, ''),
(33, 9, 18, 4, 1, 'Salir rápido de cocina'),
(34, 10, 26, 5, 1, ''),
(35, 10, 27, 5, 1, 'Dos descafeinados'),
(36, 10, 28, 3, 1, ''),
(37, 10, 30, 2, 1, 'Con hielo'),
(38, 11, 1, 2, 1, ''),
(39, 11, 10, 1, 1, ''),
(40, 11, 14, 2, 1, ''),
(41, 12, 4, 6, 1, ''),
(42, 12, 8, 3, 1, ''),
(43, 12, 17, 4, 1, 'Al punto'),
(44, 12, 23, 4, 1, ''),
(45, 13, 2, 2, 1, ''),
(46, 13, 6, 1, 1, ''),
(47, 13, 11, 1, 1, ''),
(48, 14, 5, 4, 1, 'Poco azúcar'),
(49, 14, 12, 2, 1, ''),
(50, 14, 18, 3, 1, ''),
(51, 14, 22, 3, 1, ''),
(52, 15, 3, 5, 1, ''),
(53, 15, 9, 3, 1, ''),
(54, 15, 19, 4, 1, ''),
(55, 15, 24, 4, 1, ''),
(56, 16, 1, 2, 1, ''),
(57, 16, 7, 1, 1, ''),
(58, 16, 16, 2, 1, ''),
(59, 16, 21, 2, 1, ''),
(60, 17, 4, 6, 1, ''),
(61, 17, 6, 2, 1, ''),
(62, 17, 15, 3, 1, ''),
(63, 18, 2, 5, 1, ''),
(64, 18, 8, 2, 1, ''),
(65, 18, 13, 2, 1, ''),
(66, 18, 23, 3, 1, ''),
(67, 19, 1, 4, 1, ''),
(68, 19, 11, 2, 1, ''),
(69, 19, 17, 2, 1, 'Al punto + salsa'),
(70, 19, 22, 4, 1, ''),
(71, 20, 5, 4, 1, ''),
(72, 20, 9, 2, 1, ''),
(73, 20, 18, 4, 1, ''),
(74, 20, 25, 2, 1, ''),
(75, 21, 3, 1, 1, ''),
(76, 21, 6, 1, 1, ''),
(77, 21, 19, 1, 1, ''),
(78, 22, 2, 8, 1, ''),
(79, 22, 10, 3, 1, ''),
(80, 22, 14, 4, 1, ''),
(81, 22, 21, 5, 1, 'Postre para todos'),
(82, 23, 4, 5, 1, ''),
(83, 23, 12, 3, 1, ''),
(84, 23, 16, 3, 1, ''),
(85, 23, 24, 3, 1, ''),
(86, 24, 1, 4, 1, ''),
(87, 24, 7, 2, 1, ''),
(88, 24, 20, 4, 1, 'Sin pepinillo'),
(89, 24, 23, 2, 1, ''),
(90, 25, 5, 2, 1, ''),
(91, 25, 11, 1, 1, ''),
(92, 25, 18, 2, 1, ''),
(93, 26, 2, 6, 1, ''),
(94, 26, 9, 3, 1, ''),
(95, 26, 15, 4, 1, ''),
(96, 26, 22, 4, 1, ''),
(97, 27, 4, 4, 1, ''),
(98, 27, 8, 2, 1, ''),
(99, 27, 17, 3, 1, 'Poco hecho'),
(100, 27, 25, 2, 1, ''),
(101, 28, 1, 3, 1, ''),
(102, 28, 6, 2, 1, ''),
(103, 28, 19, 3, 1, ''),
(104, 28, 21, 3, 1, ''),
(105, 29, 3, 6, 1, ''),
(106, 29, 10, 3, 1, ''),
(107, 29, 16, 5, 1, ''),
(108, 29, 24, 4, 1, ''),
(109, 30, 26, 4, 1, ''),
(110, 30, 27, 4, 1, 'Dos descafeinados'),
(111, 30, 28, 2, 1, ''),
(112, 30, 30, 2, 1, 'Servir al final');

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
('1', '1', 'Encargado', 'Demo', 2, 'admin@elquintopino.com', '1', NULL, 0),
('2', '2', 'Camarero', 'Demo', 1, 'camarero@elquintopino.com', '2', NULL, 0),
('3', '3', 'Cliente01', 'Prueba01', 0, 'cliprueb01@quintopino.com', '3', NULL, 0),
('4', '4', 'Cliente02', 'Prueba02', 0, 'cliprueb02@quintopino.com', '4', NULL, 0),
('5', '5', 'Cliente03', 'Prueba03', 0, 'cliprueb03@quintopino.com', '9871', 'C/ goku, 2h', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `producto_pedido`
--
ALTER TABLE `producto_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

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

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
