-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-05-2016 a las 19:25:50
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `examen_usuarios`
--
CREATE DATABASE IF NOT EXISTS `examen_usuarios` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `examen_usuarios`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE IF NOT EXISTS `comentarios` (
  `idcomentario` int(11) NOT NULL,
  `comentario` varchar(600) DEFAULT NULL,
  `idalta` int(11) DEFAULT NULL,
  `foto` varchar(60) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`idcomentario`, `comentario`, `idalta`, `foto`, `fecha`) VALUES
(2, 'mensaje 1', 66, NULL, '2016-05-20 16:40:17'),
(3, 'mensaje 2', 66, NULL, '2016-05-20 16:40:22'),
(4, 'mensaje 3', 64, NULL, '2016-05-20 17:36:28'),
(8, 'mensaje 7', 64, NULL, '2016-05-20 17:37:57'),
(9, 'mensaje con foto', 66, NULL, '2016-05-23 15:33:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idpersona` int(11) NOT NULL,
  `nif` varchar(10) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `password` varchar(10) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipousuario` char(2) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idpersona`, `nif`, `nombre`, `apellidos`, `email`, `password`, `timestamp`, `tipousuario`) VALUES
(64, '123456789', 'Pepe', 'Viyuela', 'pepev@mail.com', 'pepe', '2016-05-20 14:53:31', NULL),
(66, '43510039', 'david', 'alcolea', 'davidalcolea@mail.com', 'admin', '2016-05-20 15:40:35', 'AD'),
(68, '12345654', 'pepito', 'botella', 'pepeb@mail.com', 'pepe', '2016-05-27 17:22:06', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`idcomentario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idpersona`), ADD UNIQUE KEY `nif_UNIQUE` (`nif`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `idcomentario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
