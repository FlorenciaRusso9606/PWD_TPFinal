-- SQLBook: Code
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2024 a las 14:23:29
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
-- Base de datos: `bd_tpfinal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `idcompra` bigint(20) NOT NULL,
  `cofecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idusuario` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`idcompra`, `cofecha`, `idusuario`) VALUES
(15, '2024-11-17 17:06:03', 1),
(16, '2024-11-17 17:09:43', 1),
(17, '2024-11-17 17:13:06', 1),
(18, '2024-11-17 17:16:38', 1),
(19, '2024-11-17 17:25:28', 1),
(20, '2024-11-17 17:28:29', 1),
(21, '2024-11-17 17:31:47', 1),
(22, '2024-11-17 17:32:30', 1),
(23, '2024-11-17 17:36:28', 1),
(24, '2024-11-17 17:42:04', 1),
(25, '2024-11-17 17:44:53', 1),
(26, '2024-11-17 17:45:16', 1),
(27, '2024-11-17 17:49:13', 1),
(28, '2024-11-19 03:56:32', 3),
(29, '2024-11-20 22:16:43', 1),
(30, '2024-11-20 22:20:48', 1),
(31, '2024-11-20 22:25:57', 1),
(32, '2024-11-20 22:35:46', 1),
(33, '2024-11-21 04:22:53', 3),
(34, '2024-11-21 06:16:04', 2),
(35, '2024-11-21 18:27:16', 2),
(36, '2024-11-21 18:40:14', 2),
(37, '2024-11-21 18:43:02', 2),
(38, '2024-11-21 18:50:26', 2),
(39, '2024-11-21 18:54:02', 2),
(40, '2024-11-21 18:56:44', 2),
(41, '2024-11-21 18:59:51', 2),
(42, '2024-11-21 19:16:57', 2),
(43, '2024-11-21 19:20:04', 2),
(44, '2024-11-21 19:23:27', 2),
(45, '2024-11-21 19:26:17', 2),
(46, '2024-11-21 19:54:10', 2),
(47, '2024-11-21 19:57:01', 2),
(48, '2024-11-21 19:59:21', 2),
(49, '2024-11-21 20:04:04', 2),
(50, '2024-11-21 20:07:53', 2),
(51, '2024-11-21 20:13:24', 2),
(52, '2024-11-21 20:15:35', 2),
(53, '2024-11-21 20:20:57', 2),
(54, '2024-11-21 20:35:21', 2),
(55, '2024-11-21 21:02:15', 2),
(56, '2024-11-21 22:05:54', 26),
(57, '2024-11-21 22:06:08', 26),
(58, '2024-11-22 03:24:09', 3),
(59, '2024-11-22 04:14:25', 2),
(60, '2024-11-22 04:19:09', 2),
(61, '2024-11-22 04:29:29', 2),
(62, '2024-11-22 04:49:17', 3),
(63, '2024-11-22 05:40:01', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraestado`
--

CREATE TABLE `compraestado` (
  `idcompraestado` bigint(20) UNSIGNED NOT NULL,
  `idcompra` bigint(11) NOT NULL,
  `idcompraestadotipo` int(11) NOT NULL,
  `cefechaini` timestamp NOT NULL DEFAULT current_timestamp(),
  `cefechafin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `compraestado`
--

INSERT INTO `compraestado` (`idcompraestado`, `idcompra`, `idcompraestadotipo`, `cefechaini`, `cefechafin`) VALUES
(3, 31, 4, '2024-11-17 17:06:03', '2024-11-20 22:26:49'),
(4, 16, 4, '2024-11-17 17:09:43', '2024-11-20 22:49:17'),
(5, 17, 4, '2024-11-17 17:13:06', '2024-11-20 23:00:29'),
(6, 18, 4, '2024-11-17 17:16:38', '2024-11-21 18:02:58'),
(7, 19, 4, '2024-11-17 17:25:28', '2024-11-21 18:25:15'),
(8, 21, 4, '2024-11-17 17:31:47', '2024-11-21 18:24:44'),
(9, 22, 4, '2024-11-17 17:32:30', '2024-11-21 18:23:37'),
(10, 23, 4, '2024-11-17 17:36:28', '2024-11-21 18:23:11'),
(11, 24, 4, '2024-11-17 17:42:04', '2024-11-21 18:20:55'),
(12, 25, 4, '2024-11-17 17:44:53', '2024-11-21 18:16:14'),
(13, 26, 4, '2024-11-17 17:45:16', '2024-11-21 18:14:35'),
(14, 27, 4, '2024-11-17 17:49:13', '2024-11-21 18:12:02'),
(15, 28, 4, '2024-11-19 03:56:32', '2024-11-21 18:10:45'),
(16, 29, 4, '2024-11-20 22:16:43', '2024-11-21 18:09:29'),
(17, 30, 4, '2024-11-20 22:20:48', '2024-11-21 18:08:15'),
(18, 31, 1, '2024-11-20 22:25:57', '1970-01-01 03:00:00'),
(19, 32, 4, '2024-11-20 22:35:46', '2024-11-20 22:37:13'),
(20, 33, 4, '2024-11-21 04:22:53', '2024-11-21 18:05:29'),
(21, 34, 4, '2024-11-21 06:16:04', '2024-11-21 18:03:27'),
(22, 35, 4, '2024-11-21 18:27:16', '2024-11-21 18:35:47'),
(23, 36, 4, '2024-11-21 18:40:14', '2024-11-21 18:42:49'),
(24, 37, 4, '2024-11-21 18:43:02', '2024-11-21 18:43:08'),
(25, 38, 4, '2024-11-21 18:50:26', '2024-11-21 18:53:41'),
(26, 39, 4, '2024-11-21 18:54:02', '2024-11-21 18:55:15'),
(27, 40, 4, '2024-11-21 18:56:44', '2024-11-21 18:58:07'),
(28, 41, 4, '2024-11-21 18:59:51', '2024-11-21 19:15:07'),
(29, 42, 4, '2024-11-21 19:16:57', '2024-11-21 19:18:51'),
(30, 43, 4, '2024-11-21 19:20:04', '2024-11-21 19:20:13'),
(31, 44, 4, '2024-11-21 19:23:27', '2024-11-21 19:25:55'),
(32, 45, 4, '2024-11-21 19:26:17', '2024-11-21 19:52:11'),
(33, 46, 4, '2024-11-21 19:54:10', '2024-11-21 19:54:43'),
(34, 47, 4, '2024-11-21 19:57:01', '2024-11-21 19:57:06'),
(35, 48, 4, '2024-11-21 19:59:21', '2024-11-21 20:03:36'),
(36, 49, 4, '2024-11-21 20:04:04', '2024-11-21 20:06:37'),
(37, 50, 4, '2024-11-21 20:07:54', '2024-11-21 20:08:20'),
(38, 51, 4, '2024-11-21 20:13:24', '2024-11-21 20:13:32'),
(39, 52, 4, '2024-11-21 20:15:35', '2024-11-21 20:16:47'),
(40, 53, 4, '2024-11-21 20:20:57', '2024-11-21 20:35:59'),
(41, 54, 4, '2024-11-21 20:35:21', '2024-11-21 21:00:54'),
(42, 55, 4, '2024-11-21 21:02:16', '2024-11-22 05:56:22'),
(43, 56, 3, '2024-11-21 22:05:54', '1970-01-01 03:00:00'),
(44, 57, 4, '2024-11-21 22:06:08', '2024-11-21 22:09:50'),
(45, 58, 3, '2024-11-22 03:24:09', '1970-01-01 03:00:00'),
(46, 59, 3, '2024-11-22 04:14:25', '1970-01-01 03:00:00'),
(47, 60, 3, '2024-11-22 04:19:09', '1970-01-01 03:00:00'),
(48, 61, 3, '2024-11-22 04:29:29', '1970-01-01 03:00:00'),
(49, 62, 4, '2024-11-22 04:49:17', '2024-11-22 05:54:25'),
(50, 63, 4, '2024-11-22 05:40:01', '2024-11-22 05:51:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraestadotipo`
--

CREATE TABLE `compraestadotipo` (
  `idcompraestadotipo` int(11) NOT NULL,
  `cetdescripcion` varchar(50) NOT NULL,
  `cetdetalle` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `compraestadotipo`
--

INSERT INTO `compraestadotipo` (`idcompraestadotipo`, `cetdescripcion`, `cetdetalle`) VALUES
(1, 'iniciada', 'cuando el usuario : cliente inicia la compra de uno o mas productos del carrito'),
(2, 'aceptada', 'cuando el usuario administrador da ingreso a uno de las compras en estado = 1 '),
(3, 'enviada', 'cuando el usuario administrador envia a uno de las compras en estado =2 '),
(4, 'cancelada', 'un usuario administrador podra cancelar una compra en cualquier estado y un usuario cliente solo en estado=1 ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compraitem`
--

CREATE TABLE `compraitem` (
  `idcompraitem` bigint(20) UNSIGNED NOT NULL,
  `idproducto` bigint(20) NOT NULL,
  `idcompra` bigint(20) NOT NULL,
  `cicantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `compraitem`
--

INSERT INTO `compraitem` (`idcompraitem`, `idproducto`, `idcompra`, `cicantidad`) VALUES
(7, 3, 15, 1),
(8, 4, 15, 1),
(9, 4, 16, 2),
(10, 1, 16, 1),
(11, 1, 17, 2),
(12, 1, 18, 1),
(13, 1, 19, 1),
(14, 3, 20, 1),
(15, 3, 21, 2),
(16, 3, 22, 1),
(17, 3, 23, 1),
(18, 3, 24, 1),
(19, 3, 25, 3),
(20, 3, 26, 2),
(21, 1, 27, 1),
(22, 2, 27, 1),
(23, 2, 28, 4),
(24, 3, 29, 1),
(25, 2, 29, 2),
(26, 3, 30, 51),
(27, 3, 31, 5),
(28, 2, 31, 1),
(29, 3, 32, 2),
(30, 3, 33, 1),
(31, 2, 34, 2),
(32, 1, 35, 1),
(33, 1, 36, 1),
(34, 1, 37, 1),
(35, 2, 38, 1),
(36, 2, 39, 1),
(37, 2, 40, 1),
(38, 2, 41, 1),
(39, 2, 42, 1),
(40, 2, 43, 1),
(41, 2, 44, 1),
(42, 2, 45, 1),
(43, 1, 46, 1),
(44, 1, 47, 3),
(45, 1, 48, 2),
(46, 1, 49, 3),
(47, 1, 50, 1),
(48, 3, 50, 2),
(49, 3, 51, 2),
(50, 2, 52, 2),
(51, 5, 53, 2),
(52, 4, 54, 2),
(53, 4, 55, 3),
(54, 2, 56, 2),
(55, 3, 57, 3),
(56, 2, 58, 70),
(57, 1, 59, 2),
(58, 1, 60, 1),
(59, 1, 61, 2),
(60, 3, 62, 1),
(61, 1, 63, 1),
(62, 3, 63, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `idmenu` bigint(20) NOT NULL,
  `menombre` varchar(50) NOT NULL COMMENT 'Nombre del item del menu',
  `medescripcion` varchar(124) NOT NULL COMMENT 'Descripcion mas detallada del item del menu',
  `idpadre` bigint(20) DEFAULT NULL COMMENT 'Referencia al id del menu que es subitem',
  `medeshabilitado` timestamp NULL DEFAULT current_timestamp() COMMENT 'Fecha en la que el menu fue deshabilitado por ultima vez'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`idmenu`, `menombre`, `medescripcion`, `idpadre`, `medeshabilitado`) VALUES
(1, 'MenuAdmin', 'Acceso al menú de administración', NULL, NULL),
(2, 'MenuDeposito', 'Acceso al menú de depósito', NULL, NULL),
(3, 'MenuCliente', 'Acceso al menú de cliente', NULL, NULL),
(15, 'carritoCompra', '<i class=\"shopping cart icon\"></i>Carrito', 3, NULL),
(18, 'infoUsuario', '<i class=\"user icon\"></i>Mi Cuenta', 3, NULL),
(21, 'abmMenu', 'AbmMenu', 1, NULL),
(22, 'abmRoles', 'AbmRoles', 1, NULL),
(32, 'abmUsuarios', 'AbmUsuarios', 1, NULL),
(35, 'Test', 'MenuTest', NULL, NULL),
(37, 'SubMenu', 'test', 37, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menurol`
--

CREATE TABLE `menurol` (
  `idmenu` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `menurol`
--

INSERT INTO `menurol` (`idmenu`, `idrol`) VALUES
(1, 1),
(2, 2),
(3, 3),
(15, 3),
(18, 3),
(32, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` bigint(20) NOT NULL,
  `pronombre` varchar(255) NOT NULL,
  `prodetalle` varchar(512) NOT NULL,
  `procantstock` int(11) NOT NULL,
  `proprecio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idproducto`, `pronombre`, `prodetalle`, `procantstock`, `proprecio`) VALUES
(1, 'Cien Años de Soledad', 'Una novela escrita por el autor colombiano Gabriel García Márquez.', 65, 10000.00),
(2, 'Don Quijote de la Mancha', 'Una novela escrita por el autor español Miguel de Cervantes.', 0, 12000.00),
(3, '1984', 'Una novela escrita por el autor británico George Orwell.', 226, 8000.00),
(4, 'El Principito', 'Una novela escrita por el autor francés Antoine de Saint-Exupéry.', 68, 9000.00),
(5, 'Matar a un Ruiseñor', 'Una novela escrita por la autora estadounidense Harper Lee.', 20, 8500.00),
(24, 'Orgullo y Prejuicio', 'Una obra de Jane Austen que mezcla romance e ingenio mientras explora los prejuicios de clase y las relaciones familiares a través de la vida de Elizabeth Bennet y el misterioso Sr. Darcy.', 30, 24000.00),
(25, 'La Odisea', 'La épica de Homero que relata las aventuras de Odiseo en su arduo viaje de regreso a casa tras la guerra de Troya, enfrentando dioses, monstruos y el destino.', 15, 12000.00),
(26, 'Crimen y Castigo', 'Una novela de Fiódor Dostoyevski que explora las profundidades de la moralidad y la psicología humana a través de la historia de Raskólnikov, un hombre atormentado por su crimen.', 10, 11000.00),
(27, 'Los Juegos del Hambre', 'Una trilogía de Suzanne Collins que sigue a Katniss Everdeen en su lucha contra un régimen opresivo en una sociedad distópica dividida en distritos.', 40, 9500.00),
(28, 'Harry Potter y la Piedra Filosofal', 'El inicio de la famosa saga de J.K. Rowling, donde Harry descubre que es un mago y asiste a Hogwarts, una escuela llena de magia y misterio.', 50, 10500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` bigint(20) NOT NULL,
  `roldescripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `roldescripcion`) VALUES
(1, 'Administrador'),
(2, 'Depósito'),
(3, 'Cliente'),
(6, 'RolTest2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` bigint(20) NOT NULL,
  `usnombre` varchar(50) NOT NULL,
  `uspass` varchar(50) NOT NULL,
  `usmail` varchar(50) NOT NULL,
  `usdeshabilitado` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `usnombre`, `uspass`, `usmail`, `usdeshabilitado`) VALUES
(1, 'admin', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example1.com', NULL),
(2, 'deposito', '71a071b0fa4e1a671c5dc9cfbd6e5b27', 'deposito@example.com', NULL),
(3, 'cliente', '8e1be2d5700ed83423c3ce6532c277a2', 'cliente@example.com', NULL),
(8, 'test12', '827ccb0eea8a706c4c34a16891f84e7b', 'test123@test.com', NULL),
(9, 'Test123', '37a6259cc0c1dae299a7866489dff0bd', 'test123@test.com', NULL),
(10, 'test123', '202cb962ac59075b964b07152d234b70', 'email@email.com', NULL),
(16, 'Usuario1', '483f721e61eb24da095b733bd904ae6d', 'email123@email.com', '2024-11-21 21:10:53'),
(17, 'test1', '098f6bcd4621d373cade4e832627b4f6', 'test1_@mail.co', '0000-00-00 00:00:00'),
(18, 'test2', '098f6bcd4621d373cade4e832627b4f6', 'test1__2@gmail', '0000-00-00 00:00:00'),
(20, 'admin2', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example2.com', '0000-00-00 00:00:00'),
(21, 'admin3', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example3.com', '0000-00-00 00:00:00'),
(22, 'admin4', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example4.com', '0000-00-00 00:00:00'),
(23, 'admin5', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example5.com', '0000-00-00 00:00:00'),
(24, 'admin6', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example6.com', '0000-00-00 00:00:00'),
(25, 'admin7', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example7.com', '0000-00-00 00:00:00'),
(26, 'admin8', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@example8.com', NULL),
(27, 'test123', '4297f44b13955235245b2497399d7a93', '123q123', '0000-00-00 00:00:00'),
(28, 'cliente1', 'd9b1d7db4cd6e70935368a1efb10e377', 'test@cliente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariorol`
--

CREATE TABLE `usuariorol` (
  `idusuario` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuariorol`
--

INSERT INTO `usuariorol` (`idusuario`, `idrol`) VALUES
(1, 1),
(2, 2),
(3, 3),
(8, 3),
(9, 2),
(9, 3),
(10, 3),
(10, 6),
(24, 3),
(25, 3),
(26, 1),
(27, 6),
(28, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`idcompra`),
  ADD UNIQUE KEY `idcompra` (`idcompra`),
  ADD KEY `fkcompra_1` (`idusuario`);

--
-- Indices de la tabla `compraestado`
--
ALTER TABLE `compraestado`
  ADD PRIMARY KEY (`idcompraestado`),
  ADD UNIQUE KEY `idcompraestado` (`idcompraestado`),
  ADD KEY `fkcompraestado_1` (`idcompra`),
  ADD KEY `fkcompraestado_2` (`idcompraestadotipo`);

--
-- Indices de la tabla `compraestadotipo`
--
ALTER TABLE `compraestadotipo`
  ADD PRIMARY KEY (`idcompraestadotipo`);

--
-- Indices de la tabla `compraitem`
--
ALTER TABLE `compraitem`
  ADD PRIMARY KEY (`idcompraitem`),
  ADD UNIQUE KEY `idcompraitem` (`idcompraitem`),
  ADD KEY `fkcompraitem_1` (`idcompra`),
  ADD KEY `fkcompraitem_2` (`idproducto`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idmenu`),
  ADD UNIQUE KEY `idmenu` (`idmenu`),
  ADD KEY `fkmenu_1` (`idpadre`);

--
-- Indices de la tabla `menurol`
--
ALTER TABLE `menurol`
  ADD PRIMARY KEY (`idmenu`,`idrol`),
  ADD KEY `fkmenurol_2` (`idrol`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`),
  ADD UNIQUE KEY `idproducto` (`idproducto`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`),
  ADD UNIQUE KEY `idrol` (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD PRIMARY KEY (`idusuario`,`idrol`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idrol` (`idrol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `idcompra` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `compraestado`
--
ALTER TABLE `compraestado`
  MODIFY `idcompraestado` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `compraitem`
--
ALTER TABLE `compraitem`
  MODIFY `idcompraitem` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `idmenu` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fkcompra_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `compraestado`
--
ALTER TABLE `compraestado`
  ADD CONSTRAINT `fkcompraestado_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fkcompraestado_2` FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `compraitem`
--
ALTER TABLE `compraitem`
  ADD CONSTRAINT `fkcompraitem_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fkcompraitem_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fkmenu_1` FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `menurol`
--
ALTER TABLE `menurol`
  ADD CONSTRAINT `fkmenurol_1` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fkmenurol_2` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD CONSTRAINT `fkmovimiento_1` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
