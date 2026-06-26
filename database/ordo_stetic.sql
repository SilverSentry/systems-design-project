-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2026 a las 01:44:43
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
-- Base de datos: `ordo_stetic`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `antecedentes_clientes`
--

CREATE TABLE `antecedentes_clientes` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_tipo_antecedente` int(11) NOT NULL,
  `concept_id` varchar(255) NOT NULL,
  `term_name` varchar(255) NOT NULL,
  `nota` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `antecedentes_clientes`
--

INSERT INTO `antecedentes_clientes` (`id`, `id_cliente`, `id_tipo_antecedente`, `concept_id`, `term_name`, `nota`) VALUES
(1, 4, 2, '0', 'Diabetes resolved', 'Declarado en el registro inicial de BioPortal.'),
(2, 7, 2, '309417009', 'Diabetes dietitian', 'Declarado en el registro inicial de BioPortal.'),
(3, 54, 1, '111088007', 'Latex', 'Declarado en el registro inicial de BioPortal.'),
(4, 54, 1, '229924006', 'Egg sandwich', 'Declarado en el registro inicial de BioPortal.'),
(5, 55, 2, '43015000', 'Candidiasis mucocutánea crónica', 'Declarado en el registro inicial de BioPortal.'),
(6, 56, 1, '294503000', 'Alergia a anestésicos locales', 'Declarado en el registro inicial de BioPortal.'),
(7, 57, 3, '43015000', 'Candidiasis mucocutánea crónica', 'Declarado en el registro inicial de BioPortal.'),
(8, 58, 1, '300913000', 'Alergia a los mariscos', 'Declarado en el registro inicial de BioPortal.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `id_cliente`, `id_empleado`, `fecha`, `hora_inicio`, `hora_fin`, `monto_total`, `id_estado`, `notas`, `created_at`, `updated_at`) VALUES
(1, 4, 18, '2026-06-16', '14:30:00', '15:00:00', 50.00, 1, 'xd', '2026-06-14 22:38:55', '2026-06-14 22:38:55'),
(2, 54, 18, '2026-06-16', '10:32:00', '11:32:00', 20.00, 1, 'nada xd', '2026-06-15 12:32:37', '2026-06-15 12:32:37'),
(3, 4, 18, '2026-06-16', '00:47:00', '14:47:00', 20.00, 1, 'xd', '2026-06-15 12:48:06', '2026-06-15 12:48:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `dni` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('Masculino','Femenino','Otro') NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `telefono`, `dni`, `fecha_nacimiento`, `genero`, `creado_en`, `id_rol`) VALUES
(4, 'gregory', 'arrieta', '04169110558', '', '2026-05-27', 'Masculino', '2026-05-27 23:50:24', 3),
(7, 'gregory', 'arrieta', '04169110558', '', '2026-05-04', 'Otro', '2026-05-28 01:19:42', 3),
(53, 'gregory', 'arrieta', '04169110558', '30640191', '2026-05-12', 'Masculino', '2026-05-28 23:11:11', 3),
(54, 'Abraham', 'Rios', '04169110558', '30640192', '2004-04-05', 'Masculino', '2026-05-28 23:57:31', 3),
(55, 'Grego', 'Rios', '04169110558', '1234567', '2019-01-25', 'Masculino', '2026-06-07 11:40:02', 3),
(56, 'asdad', 'asdad', '12345677846', '12345689', '2025-06-05', 'Masculino', '2026-06-07 22:33:34', 3),
(57, 'xdd', 'xdxd', '12345678901', '12345678', '2022-02-07', 'Femenino', '2026-06-07 23:51:51', 3),
(58, 'abraham', 'flores', '04169110558', '30640193', '2002-02-14', 'Masculino', '2026-06-14 13:34:01', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_cita`
--

CREATE TABLE `detalles_cita` (
  `id` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_cita`
--

INSERT INTO `detalles_cita` (`id`, `id_cita`, `id_servicio`) VALUES
(1, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_cita`
--

CREATE TABLE `estados_cita` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_cita`
--

INSERT INTO `estados_cita` (`id`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'Asistida'),
(3, 'Cancelada'),
(4, 'No_asistio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_usuario`
--

CREATE TABLE `estados_usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_usuario`
--

INSERT INTO `estados_usuario` (`id`, `nombre`) VALUES
(1, 'activo'),
(2, 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `sotck_actual` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `precio_compra` decimal(10,2) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'usuario'),
(3, 'cliente'),
(4, 'superadmin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `estado`, `created_at`) VALUES
(1, 'Radiofrecuencia', 'no se xd', 20.00, 1, '2026-06-14 22:48:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `superadmin`
--

CREATE TABLE `superadmin` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_antecedentes`
--

CREATE TABLE `tipos_antecedentes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_antecedentes`
--

INSERT INTO `tipos_antecedentes` (`id`, `nombre`) VALUES
(1, 'Patologia'),
(2, 'Alergia'),
(3, 'Biopolimero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password`, `fecha_registro`, `id_rol`, `id_estado`) VALUES
(1, 'Admin', 'Ordo', 'admin@ordostetic.com', '$2y$10$2uWL3Lvswlrcd9pJOdfHW.5tmBHwLRpHvpnZeyBc09aHDG29HuXZm', '2026-05-25 15:51:03', 1, 1),
(2, 'gregory', 'arrieta', 'josefamacuaya@gmail.com', '$2y$10$twW5JaMNniWVfH6yy8NFlegR2S2fh9y15Hgx3OmQOxI0GM0l.9lRy', '2026-05-25 15:51:03', 2, 1),
(6, 'Yaneth', 'Rios', 'yaneska@gmail.com', '$2y$10$Bazz4NCy3b6ti700zsStvepQ3eI1zxOTgc4A.ec4N1C95hNL76wrC', '2026-05-25 15:51:03', 2, 1),
(7, 'carly', 'acosta', 'carly@gmail.com', '$2y$10$HhfPg4.eOeu5a2riUth2q.HXjiigCRU.KzA9uya1V1cHGSwpnGvqq', '2026-05-25 15:51:03', 2, 1),
(17, 'jesus', 'pacheco', 'pacheco@gmail.com', '$2y$10$RUqn.xAvC0Yh16NJEJHtx.4fh87EGIssFIOQAIGckDyUT4Pn.i6P6', '2026-05-25 15:51:03', 2, 1),
(18, 'gregory', 'arrieta', 'gregory@gmail.com', '$2y$10$bVZE9MOat5vxw98J.U8mPeeZOHgEz.GH8BViJL.xSP5X6dHZaoPN2', '2026-05-25 15:51:03', 2, 1),
(20, 'ekide', 'ekide', 'ekide@gmail.com', '$2y$10$OPXZqnhfP1mn9POdq8IQg.xT83EXY7sGNAOrE9pCFBzRI9H9WqtF.', '2026-05-25 16:38:11', 2, 1),
(21, 'abraham', 'rios', 'abraham@gmail.com', '$2y$10$b.EbbYTUS8nNsHi8y7Tes.YTVc3XMOzB70XyLhKlPM.IH3uhTcPSG', '2026-05-28 03:06:24', 2, 1),
(22, 'gregory', 'arrr', 'hola@gmail.com', '$2y$10$BezhMryIN8tKRTDbfj6DqutWCWA.SE3MYCl9NznI/7Yhs6Uw5RC6m', '2026-05-31 13:26:11', 2, 1),
(23, 'greg', 'hola', 'hola1@gmail.com', '$2y$10$qkOvj02tX.pRo6GBWJbwNeDTpIVipSNcCXjiyFskfV6fH2UUlhU3q', '2026-06-01 11:43:58', 2, 1),
(24, 'Juan', 'Vicente', 'vicent@gmail.com', '$2y$10$1lKLQ30oD1Kc./Yw0x6mIOi5W9NwCSDuDUQjyPECjC2l5b8V04rXi', '2026-06-07 23:24:28', 2, 1),
(25, 'xdd', 'xdddd', 'xd@gmail.com', '$2y$10$N8x0w2vEElw3LR9o5H46duWmpK.j0dvBNARNHMQ8BpQlysJGQEiDK', '2026-06-07 23:28:15', 2, 1),
(26, 'xdd', 'dddd', 'sxdxdxd@gmail.com', '$2y$10$Pz3SZpcXDirnBqCGMADXGuG8LhZ6pO3fY84KLHFeVdG5AuXAy5dHG', '2026-06-07 23:32:30', 2, 1),
(27, 'ekide', 'xdddd', 'xdxdxd@gmail.com', '$2y$10$Jl5INJ5wHzFt6AoshxvF/.8JJtzES6jxqRz1qjO.dpfqCDwZ9zbqK', '2026-06-14 13:08:28', 2, 1),
(28, 'gregory', 'arrieta', 'jijija@gmail.com', '$2y$10$haPSquj/EZes.oSwul3t7e32U/Phq6IZKvolwXFYl1sIhGje11k5e', '2026-06-14 13:09:08', 2, 1),
(29, 'hernandez', 'ekide', 'nose@gmail.com', '$2y$10$A.E6hov0r1g0SONvuOzaY.pR8nO.btCF3mnCwft9nAkr3ZZBZVyLW', '2026-06-14 13:11:42', 2, 1),
(30, 'tumadre', 'latuya', 'we@gmail.com', '$2y$10$5B26GAVBt.TIBCPhzOeDK.rjSUjF6kOrCGCvtv6.Mj7e3u/rEwUHa', '2026-06-14 13:15:18', 2, 1),
(31, 'yaneth', 'josefina', 'yaneth@gmail.com', '$2y$10$LR1eJgUEp4Y96yhSuSDAF.7m5NbWl48mbIs1L4MDxQ6Pu/6vj7j32', '2026-06-14 15:19:21', 2, 1),
(32, 'tusabe', 'comoe', 'laguachafita@gmail.com', '$2y$10$adxKjGaBjltYEf.fUA2REujWFPjIi7mn2z4kkCwdR.FpT5IXapmDy', '2026-06-18 22:41:37', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `antecedentes_clientes`
--
ALTER TABLE `antecedentes_clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_tipo_antecedente` (`id_tipo_antecedente`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `detalles_cita`
--
ALTER TABLE `detalles_cita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cita` (`id_cita`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `estados_cita`
--
ALTER TABLE `estados_cita`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados_usuario`
--
ALTER TABLE `estados_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `tipos_antecedentes`
--
ALTER TABLE `tipos_antecedentes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `antecedentes_clientes`
--
ALTER TABLE `antecedentes_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `detalles_cita`
--
ALTER TABLE `detalles_cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_cita`
--
ALTER TABLE `estados_cita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados_usuario`
--
ALTER TABLE `estados_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_antecedentes`
--
ALTER TABLE `tipos_antecedentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `antecedentes_clientes`
--
ALTER TABLE `antecedentes_clientes`
  ADD CONSTRAINT `antecedentes_clientes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `antecedentes_clientes_ibfk_2` FOREIGN KEY (`id_tipo_antecedente`) REFERENCES `tipos_antecedentes` (`id`);

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados_cita` (`id`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`rol`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_cita`
--
ALTER TABLE `detalles_cita`
  ADD CONSTRAINT `detalles_cita_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_cita_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `movimientos_inventario_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `superadmin`
--
ALTER TABLE `superadmin`
  ADD CONSTRAINT `superadmin_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `superadmin_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados_usuario` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados_usuario` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
