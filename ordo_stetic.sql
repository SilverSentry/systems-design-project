-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-03-2026 a las 23:05:57
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

CREATE DATABASE IF NOT EXISTS ordo_stetic;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `name`, `surname`, `email`, `password`) VALUES
(1, 'jose', 'martinez', 'hola@gmail.con', '$2y$10$iWaTJFftuV8FR/3vmDoqEeW3gXY8/Y2dqQ0ULhgtzS6Kiu4qZjkhq'),
(2, 'gregory', 'arrieta', 'josefamacuaya@gmail.com', '$2y$10$twW5JaMNniWVfH6yy8NFlegR2S2fh9y15Hgx3OmQOxI0GM0l.9lRy'),
(6, 'Yaneth', 'Rios', 'yaneska@gmail.com', '$2y$10$Bazz4NCy3b6ti700zsStvepQ3eI1zxOTgc4A.ec4N1C95hNL76wrC'),
(7, 'carly', 'acosta', 'carly@gmail.com', '$2y$10$HhfPg4.eOeu5a2riUth2q.HXjiigCRU.KzA9uya1V1cHGSwpnGvqq'),
(17, 'jesus', 'pacheco', 'pacheco@gmail.com', '$2y$10$RUqn.xAvC0Yh16NJEJHtx.4fh87EGIssFIOQAIGckDyUT4Pn.i6P6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
