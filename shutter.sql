-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2019 a las 21:36:13
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `shutter`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE if not exists `comentario` (
  `id` int(11) NOT NULL,
  `contenido` varchar(2000) COLLATE utf16_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `id_publicacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`id`, `contenido`, `fecha`, `id_publicacion`, `id_usuario`) VALUES
(1, 'Que bonito! Me encanta!', '2019-04-11', 1, 1),
(2, 'Si tienes razón', '2019-04-11', 1, 3),
(3, 'Como se llama?', '2019-04-11', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cubre`
--

CREATE TABLE if not exists `cubre` (
  `id_preferencia` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `cubre`
--

INSERT INTO `cubre` (`id_preferencia`, `id_evento`) VALUES
(4, 5),
(6, 5),
(9, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE if not exists `evento` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) COLLATE utf16_unicode_ci NOT NULL,
  `descripcion` varchar(1000) COLLATE utf16_unicode_ci NOT NULL,
  `reglas` varchar(5000) COLLATE utf16_unicode_ci NOT NULL,
  `fin_f_presentacion` date NOT NULL,
  `fin_f_votacion` date NOT NULL,
  `estado` varchar(12) COLLATE utf16_unicode_ci DEFAULT NULL,
  `limite_participantes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id`, `titulo`, `descripcion`, `reglas`, `fin_f_presentacion`, `fin_f_votacion`, `estado`, `limite_participantes`) VALUES
(1, 'Temática libre', 'Sube lo que pase por delante de tu objetivo y deja que los demás vean si el destino fue generoso', 'Puedes subir lo que quieras. Respeta las normas del sitio. Respetaos.', '2019-04-14', '2019-04-20', 'vota', 6),
(2, 'Monocromo', 'Haz una foto de algo interesante y haz tu mejor trabajo retocándola para resaltar las sombras en blanco y negro', 'La imagen debe estan en una calidad buena. Se permite el uso de programas de edición.', '2019-03-31', '2019-04-09', 'fin', 6),
(3, 'El momento justo', 'Hay que ser muy rápido para poder capturar el momento justo, eso es lo que pedimos en este evento.', 'No se permiten programas de edición. No se permiten demasiados filtros.', '2019-04-18', '2019-04-19', 'inicio', 8),
(5, 'Realidad o Ficción?', 'Te engañan tus ojos? Eso se espera de este evento, acerca la realidad a los cuentos y las películas con tu cámara.', 'No se pueden usar programas de edición. Se pueden usar modelos. El archivo debe tener una calidad aceptable.', '2019-06-11', '2019-06-18', 'inicio', 12);

--
-- Disparadores `evento`
--
/*
DELIMITER $$
CREATE TRIGGER `check_shutter_events_state` BEFORE INSERT ON `evento` FOR EACH ROW BEGIN
	IF(CURDATE()>new.fin_f_votacion) THEN
    SET new.estado = 'fin';
    END IF;
    IF(CURDATE()<new.fin_f_votacion AND CURDATE()>=new.fin_f_presentacion) THEN
    SET new.estado = 'vota';
    END IF;
    IF(CURDATE()<new.fin_f_presentacion) THEN
	SET new.estado = 'inicio';
    END IF;
    
END
$$
DELIMITER ;
*/

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferencia`
--

CREATE TABLE if not exists `preferencia` (
  `id` int(11) NOT NULL,
  `tag` varchar(15) COLLATE utf16_unicode_ci NOT NULL,
  `descripcion` varchar(1000) COLLATE utf16_unicode_ci NOT NULL,
  `color` varchar(6) COLLATE utf16_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `preferencia`
--

INSERT INTO `preferencia` (`id`, `tag`, `descripcion`, `color`) VALUES
(1, 'noche', 'Fotos nocturnas, la luz de la luna saca a la luz una magia oculta a los ojos de aquellos que no son capaces de aguantar el sueño...', '1E2B58'),
(2, 'animales', 'Todo tipo de animales, reunidos aquí. Donde he visto eso antes?', 'ab226e'),
(3, 'dia', 'Fotos realizadas durante el día se reúnen bajo esta categoría.', 'c08811'),
(4, 'personas', 'Fotos que incluyan o exclusivamente de personas.', '377346'),
(5, 'fiestas', 'Ponte tus mejores galas y sal a buscar la mejor foto a la celebración más cercana', 'a6def8'),
(6, 'orden', 'Si te gusta encontrar la calma en cosas extrañamente ordenadas por el destino, esta es tu preferencia.', 'f17e31'),
(7, 'aleatorio', 'Eres una persona aventurera, te gusta enfrentarte a lo que el destino te pone delante. Me caes bien.', 'ec1903'),
(8, 'filtros', 'Prefieres usar programas informáticos para hacer un retoque de tus fotografías.', '6dcd1e'),
(9, 'sin filtros', 'Te gusta subir las imágenes que capturas tal cual, sin editar.', 'd57e88');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion`
--

CREATE TABLE if not exists `publicacion` (
  `id` int(11) NOT NULL,
  `contenido` varchar(2000) COLLATE utf16_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `imagen` varchar(500) COLLATE utf16_unicode_ci DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `publicacion`
--

INSERT INTO `publicacion` (`id`, `contenido`, `fecha`, `imagen`, `id_usuario`) VALUES
(1, 'Me he levantado con ganas de fotografiar gatos hoy, aquí va uno.', '2019-03-20', 'gato.png', 3),
(2, 'Creo que voy a descansar un poco de eventos, estoy un poco #off ...', '2019-03-22', NULL, 3),
(3, 'He viajado a Japón y creo que he descubierto mi pasión por los paisajes!', '2019-04-01', 'fuji-mountain.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE if not exists `reporte` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_comentario` int(11) DEFAULT NULL,
  `id_publicacion` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sigue`
--

CREATE TABLE if not exists `sigue` (
  `id_usuario_1` int(11) NOT NULL,
  `id_usuario_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `sigue`
--

INSERT INTO `sigue` (`id_usuario_1`, `id_usuario_2`) VALUES
(1, 1),
(1, 3),
(3, 3),
(6, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiene`
--

CREATE TABLE if not exists `tiene` (
  `id_usuario` int(11) NOT NULL,
  `id_preferencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `tiene`
--

INSERT INTO `tiene` (`id_usuario`, `id_preferencia`) VALUES
(1, 1),
(1, 2),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(6, 1),
(6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajo`
--

CREATE TABLE if not exists `trabajo` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf16_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf16_unicode_ci NOT NULL,
  `f_present` date NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `id_evento_ganador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `trabajo`
--

INSERT INTO `trabajo` (`id`, `titulo`, `url`, `f_present`, `id_usuario`, `id_evento`, `id_evento_ganador`) VALUES
(2, 'Prueba1', 'https://66.media.tumblr.com/f7b9f8eb7924536cf8e910ce11e94345/tumblr_pshpjy1YWD1qajoreo1_500.jpg', '2019-03-18', 1, 1, NULL),
(3, '¿Lo ves?', 'https://mymodernmet.com/wp/wp-content/uploads/2017/03/best-animal-camouflage-17.jpg', '2019-03-17', 1, 1, 1),
(4, 'Paz', 'https://www.blogdelfotografo.com/wp-content/uploads/2017/08/DSC9225.jpg', '2019-03-29', 1, 2, 2),
(5, 'Tensión en un instante', 'http://www.fotodesign-chile.com/wp-content/uploads/2016/03/fotografia-artistica-011-1030x661.jpg', '2019-03-27', 1, 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE if not exists `usuario` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) COLLATE utf16_unicode_ci NOT NULL,
  `img` varchar(100) COLLATE utf16_unicode_ci DEFAULT NULL,
  `nivel` int(11) NOT NULL,
  `biografia` varchar(1000) COLLATE utf16_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf16_unicode_ci NOT NULL,
  `pass` varchar(64) COLLATE utf16_unicode_ci NOT NULL,
  `rol` varchar(5) COLLATE utf16_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_usuario`, `img`, `nivel`, `biografia`, `email`, `pass`, `rol`) VALUES
(1, 'icarus', 'profile.png', 13, '\"Los mares en calma no hacen buenos marineros\"', 'icarus@mail.com', 'icarus', 'users'),
(2, 'admin', NULL, 500, NULL, 'admin@shutter.com', 'admin', 'admin'),
(3, 'paco', NULL, 2, '\"Una sociedad prospera cuando los ancianos plantan árboles de cuya sombra no podrán disfrutar\" - Proverbio Griego', 'paco@mail.com', 'paco', 'users'),
(6, 'maria', NULL, 0, NULL, 'maria@mail.com', 'maria', 'users');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vota`
--

CREATE TABLE if not exists `vota` (
  `id_usuario` int(11) NOT NULL,
  `id_trabajo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comentario_fk_cm_pb` (`id_publicacion`),
  ADD KEY `comentario_fk_cm_us` (`id_usuario`);

--
-- Indices de la tabla `cubre`
--
ALTER TABLE `cubre`
  ADD PRIMARY KEY (`id_preferencia`,`id_evento`),
  ADD KEY `cubre_fk_cb_ev` (`id_evento`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preferencia`
--
ALTER TABLE `preferencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publicacion_fk_pu_us` (`id_usuario`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporte_fk_rp_cm` (`id_comentario`),
  ADD KEY `reporte_fk_rp_pb` (`id_publicacion`),
  ADD KEY `reporte_fk_rp_us` (`id_usuario`),
  ADD KEY `reporte_fk_rp_ev` (`id_evento`);

--
-- Indices de la tabla `sigue`
--
ALTER TABLE `sigue`
  ADD PRIMARY KEY (`id_usuario_1`,`id_usuario_2`),
  ADD KEY `sigue_us_2` (`id_usuario_2`),
  ADD KEY `sigue_us_1` (`id_usuario_1`);

--
-- Indices de la tabla `tiene`
--
ALTER TABLE `tiene`
  ADD PRIMARY KEY (`id_usuario`,`id_preferencia`),
  ADD KEY `tiene_fk_tn_pf` (`id_preferencia`),
  ADD KEY `tiene_fk_tn_us` (`id_usuario`);

--
-- Indices de la tabla `trabajo`
--
ALTER TABLE `trabajo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trabajo_fk_tb_us` (`id_usuario`),
  ADD KEY `trabajo_fk_tb_ev` (`id_evento`),
  ADD KEY `trabajo_fk_tb_ev_win` (`id_evento_ganador`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vota`
--
ALTER TABLE `vota`
  ADD PRIMARY KEY (`id_usuario`,`id_trabajo`),
  ADD KEY `vota_fk_vt_tb` (`id_trabajo`),
  ADD KEY `vota_fk_vt_us` (`id_usuario`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `preferencia`
--
ALTER TABLE `preferencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `trabajo`
--
ALTER TABLE `trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_fk_cm_pb` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentario_fk_cm_us` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cubre`
--
ALTER TABLE `cubre`
  ADD CONSTRAINT `cubre_fk_cb_ev` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cubre_fk_cb_pf` FOREIGN KEY (`id_preferencia`) REFERENCES `preferencia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `publicacion_fk_pu_us` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `reporte_fk_rp_cm` FOREIGN KEY (`id_comentario`) REFERENCES `comentario` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `reporte_fk_rp_ev` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `reporte_fk_rp_pb` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `reporte_fk_rp_us` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sigue`
--
ALTER TABLE `sigue`
  ADD CONSTRAINT `sigue_us_1` FOREIGN KEY (`id_usuario_1`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sigue_us_2` FOREIGN KEY (`id_usuario_2`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tiene`
--
ALTER TABLE `tiene`
  ADD CONSTRAINT `tiene_fk_tn_pf` FOREIGN KEY (`id_preferencia`) REFERENCES `preferencia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tiene_fk_tn_us` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `trabajo`
--
ALTER TABLE `trabajo`
  ADD CONSTRAINT `trabajo_fk_tb_ev` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trabajo_fk_tb_ev_win` FOREIGN KEY (`id_evento_ganador`) REFERENCES `evento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trabajo_fk_tb_us` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vota`
--
ALTER TABLE `vota`
  ADD CONSTRAINT `vota_fk_vt_tb` FOREIGN KEY (`id_trabajo`) REFERENCES `trabajo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vota_fk_vt_us` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
