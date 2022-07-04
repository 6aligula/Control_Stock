-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-04-2022 a las 10:49:51
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pruebas_php`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

DROP TABLE IF EXISTS `articulos`;
CREATE TABLE IF NOT EXISTS `articulos` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_seccion` mediumint(9) NOT NULL,
  `id_familia` mediumint(9) NOT NULL,
  `grupo` int(11) NOT NULL,
  `codigo` mediumint(9) NOT NULL,
  `nombre_articulo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `id_fabricante` mediumint(9) NOT NULL,
  `referencia_fabricante` char(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `descripcion_fabricante` char(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `ruta_foto` text COLLATE utf8_spanish2_ci,
  `observaciones` char(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `propiedad1` text COLLATE utf8_spanish2_ci,
  `propiedad2` text COLLATE utf8_spanish2_ci,
  `propiedad3` text COLLATE utf8_spanish2_ci,
  `propiedad4` text COLLATE utf8_spanish2_ci,
  `propiedad5` text COLLATE utf8_spanish2_ci,
  `propiedad6` text COLLATE utf8_spanish2_ci,
  `propiedad7` text COLLATE utf8_spanish2_ci,
  `propiedad8` text COLLATE utf8_spanish2_ci,
  `propiedad9` text COLLATE utf8_spanish2_ci,
  `propiedad10` text COLLATE utf8_spanish2_ci,
  `propiedad11` text COLLATE utf8_spanish2_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `id_seccion`, `id_familia`, `grupo`, `codigo`, `nombre_articulo`, `id_fabricante`, `referencia_fabricante`, `descripcion_fabricante`, `ruta_foto`, `observaciones`, `propiedad1`, `propiedad2`, `propiedad3`, `propiedad4`, `propiedad5`, `propiedad6`, `propiedad7`, `propiedad8`, `propiedad9`, `propiedad10`, `propiedad11`) VALUES
(1, 2, 2, 2, 1, 'CABLE', 2, 'FABRICANTE2', 'exigente', '/imagenes/cables', 'MOROSO', '1', '2.5', 'amarillo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 2, 2, 2, 'CABLE2', 1, 'FABRICANTE1', 'exigente', '/imagenes/cables', 'PAGADOR', '0', '0.5', 'azul', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 2, 2, 1, 1, 'CABLE3', 1, 'FABRICANTE1', 'exigente', '/imagenes/cables', 'PAGADOR', '1', '20', '0.25', 'verde', '1', '2', NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
