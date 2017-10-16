-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-10-2017 a las 18:39:29
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `somar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `nick` varchar(50) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` bigint(20) NOT NULL,
  `nombre_categoria` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`) VALUES
(2, 'Hogar'),
(3, 'Electrodomesticos'),
(4, 'Ferreteria'),
(5, 'Eletronica'),
(6, 'Belleza'),
(7, 'Ropa'),
(8, 'cocina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_pedido` bigint(20) NOT NULL,
  `id_producto` bigint(20) NOT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_pedido`, `id_producto`, `cantidad`) VALUES
(41, 10, 2),
(41, 19, 1),
(42, 10, 1),
(44, 10, 1),
(54, 10, 1),
(54, 19, 3);

--
-- Disparadores `detalle_pedido`
--
DELIMITER $$
CREATE TRIGGER `triggerDetallePedidoActualizar` BEFORE UPDATE ON `detalle_pedido` FOR EACH ROW BEGIN
declare precioProducto int(11);	
SELECT precio into precioProducto from producto where id_producto=old.id_producto;
UPDATE pedido set valor_pedido=valor_pedido-(old.cantidad* precioProducto)+(new.cantidad* precioProducto) where id_pedido=old.id_pedido;
UPDATE producto set cant_disponibles=(cant_disponibles+old.cantidad-new.cantidad) where id_producto=old.id_producto;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `triggerDetallePedidoEliminar` BEFORE DELETE ON `detalle_pedido` FOR EACH ROW BEGIN
declare precioProducto int(11);	
SELECT precio into precioProducto from producto where id_producto=old.id_producto;
UPDATE pedido set valor_pedido=valor_pedido-(old.cantidad* precioProducto) where id_pedido=old.id_pedido;
UPDATE producto set cant_disponibles=(cant_disponibles+old.cantidad) where id_producto=old.id_producto;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `triggerDetallePedidoInsertar` BEFORE INSERT ON `detalle_pedido` FOR EACH ROW BEGIN
declare precioProducto int(11);	
SELECT precio into precioProducto from producto where id_producto=new.id_producto;
UPDATE pedido set valor_pedido=valor_pedido+(new.cantidad* precioProducto) where id_pedido=new.id_pedido;
UPDATE producto set cant_disponibles=(cant_disponibles-new.cantidad) where id_producto=new.id_producto;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` bigint(20) NOT NULL,
  `nick_usuario` varchar(50) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(25) DEFAULT 'progreso',
  `url_comprobante_pago` varchar(255) DEFAULT NULL,
  `valor_pedido` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `nick_usuario`, `fecha_pedido`, `status`, `url_comprobante_pago`, `valor_pedido`) VALUES
(41, 'nathalia', '2017-10-08 00:17:20', 'aprobado', 'public/upload/comprobante-pago-1507421480.jpeg', 2321900),
(42, 'nathalia', '2017-10-07 02:56:18', 'aprobado', 'public/upload/comprobante-pago-1507344958.jpeg', 861000),
(44, 'nathalia', '2017-10-08 00:29:49', 'aprobado', 'public/upload/comprobante-pago-1507422560.jpeg', 861000),
(54, 'nathalia', '2017-10-10 11:46:50', 'aprobado', 'public/upload/comprobante-pago-1507635976.jpeg', 2660700);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` bigint(20) NOT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `precio` int(11) NOT NULL,
  `cant_disponibles` int(11) NOT NULL,
  `descripcion` text,
  `disponibilidad` varchar(50) NOT NULL DEFAULT 'disponible',
  `url_img1` varchar(255) DEFAULT NULL,
  `url_img2` varchar(255) DEFAULT NULL,
  `url_img3` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `referencia`, `nombre_producto`, `precio`, `cant_disponibles`, `descripcion`, `disponibilidad`, `url_img1`, `url_img2`, `url_img3`, `fecha_registro`) VALUES
(6, 'H998NHJ8778N', 'Celular Samsung Galaxy J2 Prime (2016) ', 349900, 51, 'Â·         Pantalla sÃºper AMOLED de 5 pulgadas.\r\nÂ·         CÃ¡mara frontal de 5MP.\r\nÂ·         CÃ¡mara posterior de 8MP con flash LED.\r\nÂ·         Conectividad Wi-Fi y recibe redes 4G LTE.\r\nÂ·         Capacidad para doble SIM Card.\r\nÂ·         Sintonizador de radio FM.\r\nÂ·         Memoria interna de 8GB, expandible hasta 128GB .\r\nÂ·         Memoria RAM de 1.5GB.\r\nÂ·         Procesador Quad Core de 1,5GHz.\r\nÂ·         Sistema operativo Android OS, v6.0.1 (Marshmallow)', 'disponible', 'public/upload/imagen1-1496617338.jpeg', 'public/upload/imagen2-1496617338.jpeg', '', '2017-06-14 13:19:04'),
(7, 'UUI449NJH', 'Chaqueta Azar Beisbolera CH001-Azul', 49000, 15, '70% AlgodÃ³n 30% PoliÃ©ster\r\nEstilo Casual\r\nDespachamos en menos de 24 horas\r\nEl modelo mide 1,75 cms y esta usando una chaqueta talla M\r\nHecho en Colombia.', 'disponible', 'public/upload/imagen1-1496617434.jpeg', 'public/upload/imagen2-1496617434.jpeg', 'public/upload/imagen3-1496617434.jpeg', '2017-10-10 12:00:30'),
(8, 'IUI9898GG', 'Cepillo Alisador Electrico', 43700, 27, 'Pelo aplicable en seco\r\nControlador de temperatura: Digital.\r\nFuente de energÃ­a: ElÃ©ctrico.\r\nVoltaje: 110 V.\r\nOperaciÃ³n simple y mÃ¡s rÃ¡pido\r\nEste producto no harÃ¡ daÃ±o a su pelo.\r\nUna combinaciÃ³n perfecta de peine y plancha de pelo.\r\nPotencia: 25W-39W.\r\nLa vida Ãºtil de la placa tÃ©rmica / barra: MÃ¡s de 50.000 veces.', 'disponible', 'public/upload/imagen1-1496617622.jpeg', 'public/upload/imagen2-1496617622.png', '', '2017-10-10 12:00:40'),
(9, 'UI9898JHJH', 'Fajas Mujer De Cinturilla Termica Neopreno', 86400, 29, 'aumenta el calor corporal.\r\nmaximina tus rutinas diarias.\r\nesta prenda es excelente para hacer cualquier tipo de deporte.\r\nse puede utilizar debajo de tu ropa diaria.\r\najusta lo necesario en la parte abdominal.\r\nlavar con agua fria .\r\nno retorcer .\r\nno planchar.\r\nno utilizar blanqueador.', 'disponible', 'public/upload/imagen1-1496618500.jpeg', '', 'public/upload/imagen3-1496618500.jpeg', '2017-06-14 13:19:04'),
(10, 'CCN230FXAX', 'Nevera No Frost Centrales', 861000, 222, 'Capacidad 230 litros.\r\n1 puerta refrigerador.\r\n1 puerta congelador.\r\nColor: Plata.\r\nDispensador de agua removible de 2 litros.\r\nBandejas de vidrio templado anti-derrame.\r\nModo de ahorro de energia.\r\nEnfriamiento rapido.', 'disponible', 'public/upload/imagen1-1496618596.jpeg', 'public/upload/imagen2-1496618596.jpeg', '', '2017-10-10 12:00:45'),
(11, 'IUIU9983KKJFK', 'Comedor Milan 4 Puestos - Chocolate', 749900, 50, 'Material: Tapa en Vidrio Templado de Seguridad de 8mm. Dos bases en Madera MDP madecor , cada una de 20 cm de ancho y 36 mm de espesor\r\nCon recubrimiento melamÃ­nico.\r\nEstructura madera madecor.\r\nColor: Chocolate\r\nMedidas: 120 x 76 x 75 (cm)\r\nPatas de 20 cm de ancho y 36 mm de espesor', 'disponible', 'public/upload/imagen1-1496618707.jpeg', 'public/upload/imagen2-1496618707.jpeg', '', '2017-09-24 21:48:48'),
(12, '9IUI9897887', 'Puff Pera Silla Precio De Lanzamiento', 45000, 19, 'Puff tipo pera.\r\nTela lona.\r\nTamaÃ±o: Mediano.\r\nDoble Costura.\r\nDoble Cremallera.', 'disponible', 'public/upload/imagen1-1496620950.jpeg', 'public/upload/imagen2-1496620950.jpeg', '', '2017-06-05 00:02:30'),
(13, '98JJOIO6776', 'Water Zoom Manguera Hidrolavadora', 59900, 2, 'La mejor herramienta para su manguera transformandola fuerza del agua en un chorro de presion  WATER ZOOM -POWER CLEAN Facilita el lavado de limpiezay remocion de moho pegado, muroa, ladrillos, paredes y autos.Solo conectelo a la manguera y listo.WATER ZOOM-POWER CLEAN utiliza la tecnologia compresora creando una maquina de lavado en uina manguera.', 'disponible', 'public/upload/imagen1-1496621472.jpeg', 'public/upload/imagen2-1496621472.jpeg', '', '2017-06-05 00:11:12'),
(14, 'IUI878775HGH', 'Portatil Asus X441UV Core I5', 1689000, 7, 'Procesador Intel Core i5 7200U ( 2,3 Ghz 3M Cache UP3.1 Ghz ).\r\nMemoria Ram 8 Gb DDR4.\r\nDisco Duro 1 TB.\r\nVideo Nvidia GT 920M DDR3.\r\nUnidad DVD/RW.\r\nPantalla 14. HD ( 1366x768).\r\nUSB 3.0 x 1 USB 2.0 X 1 Tipo C x 1.\r\nHdmi.\r\nSO Endless.', 'disponible', 'public/upload/imagen1-1496621673.jpeg', 'public/upload/imagen2-1496621673.jpeg', 'public/upload/imagen3-1496621673.jpeg', '2017-06-14 13:19:04'),
(15, 'HHI878778', 'PlayStation 4 500GB', 1149990, 39, 'Lector Blu-Ray Resolucion 1080p\r\nBluetooth 2.1 (EDR)\r\nAcceso a PlayStation Network\r\nConexion WIFI\r\nFuncion 3D\r\nControl Bateria Recargable de Ion-Litio Integrada\r\nControl con Panel Tactil de 2 Puntos, Click y Capacitivo\r\nBarra de Luz, Vibracion y Altavoz Mono Integrado\r\nControl con Puerto USB Y Conector de Audio 3.5mm', 'disponible', 'public/upload/imagen1-1496621980.jpeg', '', '', '2017-06-14 00:15:28'),
(16, 'JH878YUY', 'Buso Manga Ranglan Slim Fit Aranzazu', 51900, 7, 'Buso manga ranglan\r\nSlim fit\r\nPespuntes decorativos a lo largo de la manga\r\nEn algodÃ³n fresco y con elongaciÃ³n\r\nMangas y puÃ±os en color combinado\r\nCuello, puÃ±os y pretina reforzados en ribb\r\nDiseÃ±ado y elaborado en Colombia\r\nDisponible en otros colores', 'disponible', 'public/upload/imagen1-1496622143.jpeg', 'public/upload/imagen2-1496622143.jpeg', 'public/upload/imagen3-1496622143.jpeg', '2017-06-05 00:22:23'),
(17, '787SS7F87', 'Juego De Ratchet Copas 40 Piezas', 17780, 15, 'Caja con 40 Herramientas diferentes\r\nIncluye Raches y Destornilladores\r\nEmpacado individualmente\r\nSatisfaccion Garantizada', 'disponible', 'public/upload/imagen1-1496622280.jpeg', '', '', '2017-09-24 21:48:53'),
(18, 'GDG543GDFG', 'Manguera Expandible TV', 39900, 11, 'Manguera Expandible.\r\n22.5 mts\r\nBoquilla de riego.\r\nSe expande 3 veces su tamaÃ±o.\r\nNo se enreda.\r\nLigera.', 'disponible', 'public/upload/imagen1-1496622364.jpeg', 'public/upload/imagen2-1496622364.jpeg', '', '2017-09-24 21:48:18'),
(19, 'JHJH87878', 'Disco Duro Solido Kingston SSDNOW 300 480 GB - Gris', 599900, 5, 'Capacidad: 480 GB.\r\nInterfaz: SATA Rev. 3.0 (6Gb/seg) â€“ con compatibilidad inversa para SATA Rev. 2.0\r\nConsumo de energÃ­a: 0.640 W Inactiva/ 1.423 W Lectura / 2.052 W Escritura.\r\nTemperatura de almacenamiento: -40 a 85Â°C.\r\nTemperaturas de operaciÃ³n: 0 a 70 Â°C.\r\nExpectativa de vida Ãºtil: 1 millÃ³n de horas como MTBF.\r\nTransferencia comprensible: 450MB/seg (Lectura) y 450MB/seg (Escritura).\r\nTransferencia incomprensible: 450MB/seg (Lectura) y 208MB/seg (Escritura)\r\nColor: gris.\r\nModelo: SV300S37A/480G.', 'disponible', 'public/upload/imagen1-1496923735.jpeg', 'public/upload/imagen2-1496923735.jpeg', '', '2017-10-10 12:00:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_categoria`
--

CREATE TABLE `productos_categoria` (
  `id_producto` bigint(20) NOT NULL,
  `id_categoria` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos_categoria`
--

INSERT INTO `productos_categoria` (`id_producto`, `id_categoria`) VALUES
(6, 5),
(7, 6),
(7, 7),
(8, 6),
(9, 6),
(10, 3),
(11, 2),
(12, 2),
(13, 4),
(14, 3),
(15, 3),
(15, 5),
(16, 6),
(16, 7),
(17, 4),
(18, 4),
(19, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperar_claves`
--

CREATE TABLE `recuperar_claves` (
  `email` varchar(255) NOT NULL,
  `id_seguridad` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `recuperar_claves`
--

INSERT INTO `recuperar_claves` (`email`, `id_seguridad`, `fecha_registro`) VALUES
('omararturo15@hotmail.com', '7499752c84eda472e7800cb16dfdb1eb393cefca', '2017-06-14 04:22:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nick` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `cc` int(11) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nick`, `email`, `password`, `nombres`, `apellidos`, `cc`, `direccion`, `fecha_registro`, `tipo`) VALUES
('eliza', 'eliza@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'elizabeth', 'ramirez', 989898, 'asdasda', '2017-05-29 21:11:46', 'user'),
('ender', 'ender@hotmail.com', '2846afcde63a7f2595d7d7be2522678d18619be5', 'ender', 'ortega', 1111111, 'Calle falsa', '2017-06-03 18:52:38', 'user'),
('ender18', 'enderortega18@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Ender Hernando', 'Ortega Baez', 1090503218, 'Calle 37A n 2-69 br La Sabana', '2017-06-12 15:30:45', 'user'),
('gabriel01', 'gabrielcontreras1426@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'gabriel', 'contreras', 1090501307, 'call 11 av 8 #9-37 panamericano', '2017-06-14 13:40:43', 'user'),
('nathalia', 'omararturo15@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'nathalia', 'o080', 232131, 'asada', '2017-09-24 16:43:58', 'user'),
('omar', 'omararturo@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'OMAR ARTURO', 'CONTRERAS VERGEL', 211433, 'CALLE FALSA 1 N 13', '2017-09-24 16:43:47', 'admin'),
('pedro', 'pedro@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'pedro', 'gonsales', 1090501308, 'call falsa #123', '2017-06-10 14:47:55', 'user');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`nick`,`id_producto`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_pedido`,`id_producto`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `nick_usuario` (`nick_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `productos_categoria`
--
ALTER TABLE `productos_categoria`
  ADD PRIMARY KEY (`id_producto`,`id_categoria`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `recuperar_claves`
--
ALTER TABLE `recuperar_claves`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`nick`),
  ADD UNIQUE KEY `UQ_Usuario_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `FK_Detalle_Pedido_Pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `FK_Detalle_Pedido_Producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `FK_Pedido_Usuario` FOREIGN KEY (`nick_usuario`) REFERENCES `usuario` (`nick`);

--
-- Filtros para la tabla `productos_categoria`
--
ALTER TABLE `productos_categoria`
  ADD CONSTRAINT `FK_Productos_Categoria_Categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  ADD CONSTRAINT `FK_Productos_Categoria_Producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
