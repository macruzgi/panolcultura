-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2023 a las 21:35:01
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `panol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliente_id` int(10) NOT NULL,
  `cliente_dni` varchar(30) NOT NULL,
  `cliente_nombre` varchar(50) NOT NULL,
  `cliente_apellido` varchar(50) NOT NULL,
  `cliente_telefono` varchar(20) NOT NULL,
  `cliente_direccion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `cliente_dni`, `cliente_nombre`, `cliente_apellido`, `cliente_telefono`, `cliente_direccion`) VALUES
(1, '44670120', 'Sebastian', 'Braz', '1122842815', 'Sistemas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle`
--

CREATE TABLE `detalle` (
  `detalle_id` int(100) NOT NULL,
  `detalle_cantidad` int(10) NOT NULL,
  `detalle_formato` varchar(20) NOT NULL,
  `detalle_tiempo` int(7) NOT NULL,
  `detalle_costo_tiempo` decimal(30,2) NOT NULL,
  `detalle_descripcion` varchar(150) NOT NULL,
  `prestamo_codigo` varchar(200) NOT NULL,
  `item_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle`
--

INSERT INTO `detalle` (`detalle_id`, `detalle_cantidad`, `detalle_formato`, `detalle_tiempo`, `detalle_costo_tiempo`, `detalle_descripcion`, `prestamo_codigo`, `item_id`) VALUES
(4, 1, 'Dias', 1, 1.00, '52A3 Shure Beta 52', 'CP3897630-1', 263),
(5, 1, 'Dias', 1, 1.00, '04785950 Lente Sony 28 135', 'CP1147406-2', 9),
(6, 1, 'Dias', 1, 1.00, '04785951 Lente Sony 28 135', 'CP1147406-2', 8),
(7, 1, 'Dias', 1, 1.00, '04782731 Televisor Led Samsung 55 Pulgadas', 'CP5556100-3', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `empresa_id` int(2) NOT NULL,
  `empresa_nombre` varchar(90) NOT NULL,
  `empresa_email` varchar(70) NOT NULL,
  `empresa_telefono` varchar(20) NOT NULL,
  `empresa_direccion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`empresa_id`, `empresa_nombre`, `empresa_email`, `empresa_telefono`, `empresa_direccion`) VALUES
(1, 'Centro Cultural Kirchner', 'cck@cultura.gob.ar', '1112345678', 'Sarmiento 151');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item`
--

CREATE TABLE `item` (
  `item_id` int(10) NOT NULL,
  `item_codigo` varchar(50) NOT NULL,
  `item_nombre` varchar(150) NOT NULL,
  `item_stock` int(10) NOT NULL,
  `item_estado` varchar(20) NOT NULL,
  `item_detalle` varchar(200) NOT NULL,
  `item_patrimonio` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `item`
--

INSERT INTO `item` (`item_id`, `item_codigo`, `item_nombre`, `item_stock`, `item_estado`, `item_detalle`, `item_patrimonio`) VALUES
(3, '0118', 'Monitor Coradir', 1, 'Habilitado', '', ''),
(5, '0479088704790887', 'CONSOLA QL1 YAMAHA', 1, 'Habilitado', '', ''),
(7, '04785888', 'Cámara Sony A7S3', 1, 'Habilitado', '', ''),
(8, '04785951', 'Lente Sony 28-135', 1, 'Habilitado', '', '04785951'),
(9, '04785950', 'Lente Sony 28-135', 1, 'Habilitado', '', '04785950'),
(10, '04785584', 'Valija Pelican 1550', 1, 'Habilitado', '', ''),
(11, '04785585', 'Valija Pelican 1550', 1, 'Habilitado', '', ''),
(12, '04785932', 'Lente Sony 70-200', 1, 'Habilitado', '', '04785932'),
(13, '04785945', 'Lente Sony 16-35', 1, 'Habilitado', '', '04785945'),
(14, '04785942', 'Lente Sony 85', 1, 'Habilitado', '', '04785942'),
(15, '04782731', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', ''),
(19, '4973751', 'Cámara Sony A7S3', 1, 'Habilitado', '', '04785889'),
(20, '5000198', 'Opera 15\'', 1, 'Habilitado', '', '04782785'),
(21, '8000019', 'Opera 15\'', 1, 'Habilitado', '', '04782786'),
(22, 'L738000050', 'Opera 15\'', 1, 'Habilitado', '', '04782780'),
(23, 'L738000053', 'Opera15\'', 1, 'Habilitado', '', '04782784'),
(24, '8000029', 'Opera 15\'', 1, 'Habilitado', '', '04782783'),
(25, '8000023', 'Opera 15\'', 1, 'Habilitado', '', '04782781'),
(26, '8000046', 'Opera 15\'', 1, 'Habilitado', '', '04782789'),
(27, '8000044', 'Opera 15\'', 1, 'Habilitado', '', '04782790'),
(28, '8000005', 'Opera 15\'', 1, 'Habilitado', '', '04782779'),
(29, '8000039', 'Opera 15\'', 1, 'Habilitado', '', '04782782'),
(30, '8000033', 'Opera 15\'', 1, 'Habilitado', '', '04782778'),
(31, '5000213', 'Opera 15\'', 1, 'Habilitado', '', '001014000000'),
(32, '8000079', 'Opera 15\'', 1, 'Habilitado', '', '001018000006'),
(33, '6000059', 'Opera 12\'', 1, 'Habilitado', '', '04782813'),
(34, '6000038', 'Opera 12\'', 1, 'Habilitado', '', '04782809'),
(35, '6000017', 'Opera 12\'', 1, 'Habilitado', '', '04782806'),
(36, '3000017', 'Opera 12\'', 1, 'Habilitado', '', '04782808'),
(37, '6000024', 'Opera 12\'', 1, 'Habilitado', '', '04782811'),
(38, '6000025', 'Opera 12\'', 1, 'Habilitado', '', '04782810'),
(39, '6000044', 'Opera 12\'', 1, 'Habilitado', '', '04782812'),
(40, '6000019', 'Opera 12\'', 1, 'Habilitado', '', '04782805'),
(41, '6000023', 'Opera 12\'', 1, 'Habilitado', '', '04782814'),
(42, '6000016', 'Opera 12\'', 1, 'Habilitado', '', '04782807'),
(43, '6000052', 'Opera 12\'', 1, 'Habilitado', '', '04782815'),
(44, '4000155', 'Opera 10\'', 1, 'Habilitado', '', '04782801'),
(45, '4000134', 'Opera 10\'', 1, 'Habilitado', '', '04782803'),
(46, '4000139', 'Opera 10\'', 1, 'Habilitado', '', '04782797'),
(47, '4000135', 'Opera 10\'', 1, 'Habilitado', '', '04782795'),
(48, '4000144', 'Opera 10\'', 1, 'Habilitado', '', '04782804'),
(49, '4000151', 'Opera 10\'', 1, 'Habilitado', '', '04782796'),
(50, '204158301DBFX0765', 'Parlante MR5', 1, 'Habilitado', '', '04790922'),
(51, 'CL1507156118642', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790924'),
(52, 'CL1507156118466', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790925'),
(53, 'CL1507156118206', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790930'),
(54, 'CL1312156101688', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790929'),
(55, 'CL1507156118213', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790928'),
(56, 'CL1507156118459', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '000437000000'),
(57, 'CL1507156118153', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790932'),
(58, 'CL1507156118179', 'Parlante M-Audio BX5', 1, 'Habilitado', '', '04790931'),
(59, '21VTASP01002', 'Yamaha Rio1608-D', 1, 'Habilitado', '', '04790125'),
(60, '000222274522', 'Controlador GBR', 1, 'Habilitado', '', '04790892'),
(61, '000222274523', 'Controlador GBR', 1, 'Habilitado', '', '04790891'),
(62, '003089501BRCA0132', 'Consola ONYX 1640i', 1, 'Habilitado', '', '04790890'),
(63, 'WTATY01007', 'Consola Yamaha 01V 96i', 1, 'Habilitado', '', '04790889'),
(64, 'EAUN01057', 'Consola Yamaha 01V 96i', 1, 'Habilitado', '', '04790888'),
(65, 'EEYP01573', 'Consola Yamaha Stagepas 600BT', 1, 'Habilitado', '', '04790777'),
(66, 'EEYP01489', 'Consola Yamaha Stagepas 600BT', 1, 'Habilitado', '', '04790776'),
(67, 'EEZH01377', 'Consola Yamaha Stagepas 600BT', 1, 'Habilitado', '', '04790773'),
(68, '21VTATX01008', 'Consola Rio3224-D', 1, 'Habilitado', '', '04790944'),
(69, 'WTARO01005', 'Potencia Yamaha PW800W', 1, 'Habilitado', '', '000485000007'),
(70, 'WTARK01003', 'Potencia Yamaha PW800W', 1, 'Habilitado', '', '000484000008'),
(71, 'WTASI01008', 'Potencia Yamaha PW800W', 1, 'Habilitado', '', '000486000006'),
(72, 'WTARO01011', 'Potencia Yamaha PW800W', 1, 'Habilitado', '', '000487000005'),
(73, 'A1', 'Auriculares Altair', 1, 'Habilitado', '', '04790869'),
(74, 'A2', 'Auriculares Altair', 1, 'Habilitado', '', '04790872'),
(75, 'A3', 'Auriculares Altair', 1, 'Habilitado', '', '04790871'),
(76, 'A4', 'Auriculares Altair', 1, 'Habilitado', '', '04790870'),
(77, '1', 'Auricular Studio Monitor', 1, 'Habilitado', '', '04790884'),
(78, 'EEYH01133', 'Consola Yamaha Stagepas 600BT', 1, 'Habilitado', '', '04790775'),
(79, '050546Z51180377AE', 'Parlante Bose', 1, 'Habilitado', '', '000474000001'),
(80, '057412Z51230319AE', 'Parlante Bose', 1, 'Habilitado', '', '000473000002'),
(81, '12000407135', 'Caja Directa BSS', 1, 'Habilitado', '', '04790524'),
(82, '12000407132', 'Caja Directa BSS', 1, 'Habilitado', '', '04790521'),
(83, '12000597924', 'Caja Directa BSS', 1, 'Habilitado', '', '04790526'),
(84, '12000597923', 'Caja Directa BSS', 1, 'Habilitado', '', '04790522'),
(85, '12000597922', 'Caja Directa BSS', 1, 'Habilitado', '', '04790523'),
(86, '12000407137', 'Caja Directa BSS', 1, 'Habilitado', '', '04790525'),
(87, '12000597919', 'Caja Directa BSS', 1, 'Habilitado', '', '04790527'),
(88, '000212000003', 'Caja Directa Samson', 1, 'Habilitado', '', '1'),
(89, '000211000004', 'Caja Directa Samson', 1, 'Habilitado', '', '2'),
(90, '000214000001', 'Caja Directa Samson', 1, 'Habilitado', '', '3'),
(91, '000215000000', 'Caja Directa Samson', 1, 'Habilitado', '', '4'),
(92, '000216000009', 'Caja Directa Samson', 1, 'Habilitado', '', '5'),
(93, '000213000002', 'Caja Directa Samson', 1, 'Habilitado', '', '6'),
(94, 'AE1', 'Audio Technica 5100', 1, 'Habilitado', '', '04790665'),
(95, 'AE2', 'Audio Technica 5100', 1, 'Habilitado', '', '04790666'),
(96, 'AE3', 'Audio Technica 5100', 1, 'Habilitado', '', '04790675'),
(97, 'AE4', 'Audio Technica 5100', 1, 'Habilitado', '', '04790676'),
(98, 'AE5', 'Audio Technica 5100', 1, 'Habilitado', '', '04790673'),
(99, 'AE6', 'Audio Technica 5100', 1, 'Habilitado', '', '04790670'),
(100, 'AE7', 'Audio Technica 5100', 1, 'Habilitado', '', '04790674'),
(101, 'AE8', 'Audio Technica 5100', 1, 'Habilitado', '', '04790663'),
(102, 'AE9', 'Audio Technica 5100', 1, 'Habilitado', '', '04790672'),
(103, 'AE10', 'Audio Technica 5100', 1, 'Habilitado', '', '04790668'),
(104, 'AE11', 'Audio Technica 5100', 1, 'Habilitado', '', '04790671'),
(105, 'AE12', 'Audio Technica 5100', 1, 'Habilitado', '', '04790664'),
(106, '52A1', 'Shure Beta 52', 1, 'Habilitado', '', '04790658'),
(107, '52A2', 'Shure Beta 52', 1, 'Habilitado', '', '04790656'),
(108, '13034', 'Beyerdynamic  TGD70', 1, 'Habilitado', '', '04790662'),
(109, '13011', 'Beyerdynamic  TGD70', 1, 'Habilitado', '', '04790661'),
(110, '000143000004', 'Shure Beta 91A', 1, 'Habilitado', '', '04790660'),
(111, 'IMP1', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790545'),
(112, 'IMP2', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790530'),
(113, 'IMP3', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790528'),
(114, 'IMP4', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790544'),
(115, 'IMP5', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790536'),
(116, 'IMP6', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790541'),
(117, 'IMP7', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790533'),
(118, 'IMP8', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790529'),
(119, 'IMP9', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790531'),
(120, 'IMP10', 'Caja Directa IMP2', 1, 'Habilitado', '', '04790546'),
(121, 'IMP11', 'Caja Directa IMP2', 1, 'Habilitado', '', 'SP'),
(122, 'IMP12', 'Caja Directa IMP2', 1, 'Habilitado', '', 'SP1'),
(123, '581', 'Shure SM58', 1, 'Habilitado', '', '04790619'),
(124, '582', 'Shure SM58', 1, 'Habilitado', '', '04790616'),
(125, '583', 'Shure SM58', 1, 'Habilitado', '', '04790614'),
(126, '584', 'Shure SM58', 1, 'Habilitado', '', '04790609'),
(127, '585', 'Shure SM58', 1, 'Habilitado', '', '04790620'),
(128, '586', 'Shure SM58', 1, 'Habilitado', '', '04790617'),
(129, '587', 'Shure SM58', 1, 'Habilitado', '', '04790622'),
(130, '588', 'Shure SM58', 1, 'Habilitado', '', '04790612'),
(131, '589', 'Shure SM58', 1, 'Habilitado', '', '04790611'),
(132, '5810', 'Shure SM58', 1, 'Habilitado', '', '04790621'),
(133, '5811', 'Shure SM58', 1, 'Habilitado', '', '04791623'),
(134, '5812', 'Shure SM58', 1, 'Habilitado', '', '04790610'),
(135, '871', 'Shure Beta 87A', 1, 'Habilitado', '', '04790653'),
(136, '872', 'Shure Beta 87A', 1, 'Habilitado', '', '04790650'),
(137, '873', 'Shure Beta 87A', 1, 'Habilitado', '', '04790652'),
(138, '874', 'Shure Beta 87A', 1, 'Habilitado', '', '04790651'),
(139, '875', 'Shure Beta 87A', 1, 'Habilitado', '', '04790649'),
(140, '876', 'Shure Beta 87A', 1, 'Habilitado', '', '04790648'),
(141, '877', 'Shure Beta 87A', 1, 'Habilitado', '', '04790647'),
(142, '4000158', 'Opera 10', 1, 'Habilitado', '', '04782798'),
(143, '4000148', 'Opera 10', 1, 'Habilitado', '', '04782802'),
(144, '4000132', 'Opera 10', 1, 'Habilitado', '', '04782799'),
(145, '4000156', 'Opera 10', 1, 'Habilitado', '', '04782800'),
(146, 'GHH0U0060', 'Parlante QSC', 1, 'Habilitado', '', '04790766'),
(147, 'GHH0U0037', 'Parlante QSC', 1, 'Habilitado', '', '04790768'),
(148, 'G031800Z3', 'Parlante QSC', 1, 'Habilitado', '', '04790767'),
(149, 'V481822W5', 'Parlante QSC', 1, 'Habilitado', '', '04790770'),
(150, 'G031800W9', 'Parlante QSC', 1, 'Habilitado', '', '04790762'),
(151, 'V481822VX', 'Parlante QSC', 1, 'Habilitado', '', '04790763'),
(152, 'G031800YG', 'Parlante QSC', 1, 'Habilitado', '', '04790769'),
(153, 'V481822V3', 'Parlante QSC', 1, 'Habilitado', '', '04790765'),
(154, 'V481822T6', 'Parlante QSC', 1, 'Habilitado', '', '04790764'),
(155, 'P0311-27868', 'Parlante JBL SB-2', 1, 'Habilitado', '', '04790806'),
(156, 'P0311-27818', 'Parlante JBL SB-2', 1, 'Habilitado', '', '04790805'),
(157, 'P0311-27867', 'Parlante JBL SB-2', 1, 'Habilitado', '', '04790804'),
(161, 'P1312-09853', 'Parlante JBL PRX712', 1, 'Habilitado', '', '04790517'),
(162, 'P1312-09888', 'Parlante JBL PRX712', 1, 'Habilitado', '', '04790516'),
(163, 'P1314-11586', 'Parlante JBL PRX715', 1, 'Habilitado', '', '04790518'),
(164, 'P1314-11601', 'Parlante JBL PRX715', 1, 'Habilitado', '', '04790519'),
(165, '000456000005', 'parlante Electro-Voice', 1, 'Habilitado', '', '04790520'),
(166, 'CGPC12016579', 'Sistema de audio Fender 300Pro', 1, 'Habilitado', '', '04790771'),
(167, 'L391004522', 'Subwoofer TB', 1, '', '', '04790506'),
(168, '1190143764', 'Proyector Barco', 1, 'Habilitado', '', '04790472'),
(169, '1190143173', 'Proyector Barco', 1, 'Habilitado', '', '04790473'),
(170, '1190143608', 'Proyector Barco', 1, 'Habilitado', '', '04790470'),
(171, '1190143163', 'Proyector Barco', 1, 'Habilitado', '', '04790469'),
(172, '1190143607', 'Proyector Barco', 1, 'Habilitado', '', '04790468'),
(173, '1190143604', 'Proyector Barco', 1, 'Habilitado', '', '04790467'),
(175, '1190143172', 'Proyector Barco', 1, 'Habilitado', '', '04790474'),
(176, '1190143768', 'Proyector Barco', 1, 'Habilitado', '', '04790475'),
(177, '1190143165', 'Proyector Barco', 1, 'Habilitado', '', '04790465'),
(178, '1190143171', 'Proyector Barco', 1, 'Habilitado', '', '04790464'),
(179, '1190143170', 'Proyector Barco', 1, 'Habilitado', '', '04790471'),
(180, '47574', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790634'),
(181, '45826', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790641'),
(182, '44510', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790638'),
(183, '44512', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790627'),
(184, '44520', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790631'),
(185, '44521', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790633'),
(186, '44523', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790637'),
(187, '45854', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790643'),
(188, '44530', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790630'),
(189, '45829', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790636'),
(190, '44551', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790640'),
(191, '45831', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790626'),
(192, 'SC', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790642'),
(193, '44540', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790632'),
(194, '44544', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790628'),
(195, '44543', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790629'),
(196, '44503', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790625'),
(197, '44514', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790644'),
(198, '44515', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790639'),
(199, '47551', 'Beyerdynamic TGV50', 1, '', '', 'SC'),
(200, '44505', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790635'),
(201, '45850', 'Beyerdynamic TGV50', 1, 'Habilitado', '', '04790645'),
(202, '571', 'Shure SM57', 1, 'Habilitado', '', '04790590'),
(203, '572', 'Shure SM57', 1, 'Habilitado', '', '04790593'),
(204, '573', 'Shure SM57', 1, 'Habilitado', '', '04790589'),
(205, '574', 'Shure SM57', 1, 'Habilitado', '', '04790591'),
(206, '575', 'Shure SM57', 1, 'Habilitado', '', '04790583'),
(207, '576', 'Shure SM57', 1, 'Habilitado', '', '04790572'),
(208, '577', 'Shure SM57', 1, 'Habilitado', '', '04790588'),
(209, '578', 'Shure SM57', 1, 'Habilitado', '', '04790577'),
(210, '579', 'Shure SM57', 1, 'Habilitado', '', '04790598'),
(211, '5710', 'Shure SM57', 1, 'Habilitado', '', '04790597'),
(212, '5712', 'Shure SM57', 1, 'Habilitado', '', '04790587'),
(213, '5713', 'Shure SM57', 1, 'Habilitado', '', '04790578'),
(214, '5714', 'Shure SM57', 1, 'Habilitado', '', '04790594'),
(215, '5715', 'Shure SM57', 1, 'Habilitado', '', '04790575'),
(216, '5716', 'Shure SM57', 1, 'Habilitado', '', '04790584'),
(217, '5717', 'Shure SM57', 1, 'Habilitado', '', '04790600'),
(218, '5718', 'Shure SM57', 1, 'Habilitado', '', '04790603'),
(219, '5719', 'Shure SM57', 1, 'Habilitado', '', '04790574'),
(220, '5720', 'Shure SM57', 1, 'Habilitado', '', '04790595'),
(221, '5721', 'Shure SM57', 1, 'Habilitado', '', '04790585'),
(222, '5722', 'Shure SM57', 1, 'Habilitado', '', '04790596'),
(223, '5723', 'Shure SM57', 1, 'Habilitado', '', '04790601'),
(224, '5724', 'Shure SM57', 1, 'Habilitado', '', '04790576'),
(225, '5725', 'Shure SM57', 1, 'Habilitado', '', '04790586'),
(226, '5726', 'Shure SM57', 1, 'Habilitado', '', '04790602'),
(227, '5727', 'Shure SM57', 1, 'Habilitado', '', '04790581'),
(228, '5728', 'Shure SM57', 1, 'Habilitado', '', '04790580'),
(229, '5729', 'Shure SM57', 1, 'Habilitado', '', '04790592'),
(231, '001055000007', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782732'),
(232, '027CH9XF500082R', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782736'),
(233, '001056000006', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782735'),
(234, '001052000000', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782728'),
(235, '001044000001', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782734'),
(236, '027CH9XF800341Y', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782738'),
(237, '001042000003', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04794781'),
(238, '001054000008', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04782729'),
(239, '710NSJD03148', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04794477'),
(240, '710NSAC03144', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04794476'),
(241, 'ZA1A1821008903', 'Televisor Led Samsung 55 Pulgadas', 1, 'Habilitado', '', '04790459'),
(242, '6Y00189RE', 'Proyector NEC M353', 1, 'Habilitado', '', '04794479'),
(243, '6Y00195RE', 'Proyector NEC M353', 1, 'Habilitado', '', '04794482'),
(244, '6Y00227RE', 'Proyector NEC M353', 1, 'Habilitado', '', '04794481'),
(245, 'Q406109HAAAAC0406', 'Proyector GT1090', 1, 'Habilitado', '', '04794763'),
(246, '1D0G1102111520275', 'PROYECTOR OPTOMA TH1060', 1, 'Habilitado', '', '04794764'),
(247, 'A922EBAF1C2054030', 'PROYECTOR CASIO M240', 1, 'Habilitado', '', '04794727'),
(248, 'A155D6J', 'EXTRON DVI DA4+', 1, 'Habilitado', '', '04794709'),
(249, 'A0N8AJF', 'EXTRON DVI DA4+', 1, 'Habilitado', '', '04794708'),
(250, 'A10T4AL', 'EXTRON VGA DA4x1', 1, 'Habilitado', '', '04794711'),
(253, 'A0F0C2000732', 'BrightSign HD1010', 1, 'Habilitado', '', '04794668'),
(254, 'A0F0CN000696', 'BrightSign HD1010', 1, 'Habilitado', '', '04794678'),
(255, 'A0F0C6000490', 'BrightSign HD1010', 1, 'Habilitado', '', '04794679'),
(256, 'A0F0CU000783', 'BrightSign HD1010', 1, 'Habilitado', '', '04794675'),
(257, 'A0F0C5000820', 'BrightSign HD1010', 1, 'Habilitado', '', '04794669'),
(258, 'A0F0CW000862', 'BrightSign HD1010', 1, 'Habilitado', '', '04794676'),
(259, 'L2D54K001594', 'BrightSign XD232', 1, 'Habilitado', '', '04794670'),
(260, 'L2D54P001573', 'BrightSign XD232', 1, 'Habilitado', '', '04794677'),
(261, 'A0F0C6000612', 'BrightSign HD1010', 1, 'Habilitado', '', '04794666'),
(262, 'A0F0C1000822', 'BrightSign HD1010', 1, 'Habilitado', '', '04794665'),
(263, '52A3', 'Shure Beta 52', 1, 'Habilitado', '', '04790657'),
(264, '1922869', 'Lente Sony 90', 1, 'Habilitado', '', '04785947'),
(265, '1835980', 'Lente Sony 50', 1, 'Habilitado', '', '04785937'),
(266, '1808760', 'Lente Sony 12-24', 1, 'Habilitado', '', '04785936');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `pago_id` int(20) NOT NULL,
  `pago_total` decimal(30,2) NOT NULL,
  `pago_fecha` date NOT NULL,
  `prestamo_codigo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`pago_id`, `pago_total`, `pago_fecha`, `prestamo_codigo`) VALUES
(2, 1.00, '2023-05-30', 'CP3897630-1'),
(3, 2.00, '2023-05-30', 'CP1147406-2'),
(4, 1.00, '2023-05-30', 'CP5556100-3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `prestamo_id` int(50) NOT NULL,
  `prestamo_codigo` varchar(200) NOT NULL,
  `prestamo_fecha_inicio` date NOT NULL,
  `prestamo_hora_inicio` varchar(15) NOT NULL,
  `prestamo_fecha_final` date NOT NULL,
  `prestamo_hora_final` varchar(15) NOT NULL,
  `prestamo_cantidad` int(10) NOT NULL,
  `prestamo_total` decimal(30,2) NOT NULL,
  `prestamo_destino` varchar(100) NOT NULL,
  `prestamo_pagado` decimal(30,2) NOT NULL,
  `prestamo_estado` varchar(20) NOT NULL,
  `prestamo_observacion` varchar(535) NOT NULL,
  `usuario_id` int(10) NOT NULL,
  `cliente_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`prestamo_id`, `prestamo_codigo`, `prestamo_fecha_inicio`, `prestamo_hora_inicio`, `prestamo_fecha_final`, `prestamo_hora_final`, `prestamo_cantidad`, `prestamo_total`, `prestamo_destino`, `prestamo_pagado`, `prestamo_estado`, `prestamo_observacion`, `usuario_id`, `cliente_id`) VALUES
(3, 'CP3897630-1', '2023-05-30', '2023-05-30', '2023-05-31', '05:31 AM', 1, 1.00, 'VIDEO', 1.00, 'Prestamo', '', 1, 1),
(4, 'CP1147406-2', '2023-05-30', '2023-05-30', '2023-05-31', '05:36 AM', 2, 2.00, '', 2.00, 'Prestamo', '', 1, 1),
(5, 'CP5556100-3', '2023-05-30', '2023-05-30', '2023-05-31', '05:44 AM', 1, 1.00, 'Sistemas', 1.00, 'Prestamo', '', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(10) NOT NULL,
  `usuario_dni` varchar(20) NOT NULL,
  `usuario_nombre` varchar(50) NOT NULL,
  `usuario_apellido` varchar(50) NOT NULL,
  `usuario_telefono` varchar(20) NOT NULL,
  `usuario_direccion` varchar(200) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `usuario_usuario` varchar(50) NOT NULL,
  `usuario_clave` varchar(535) NOT NULL,
  `usuario_estado` varchar(17) NOT NULL,
  `usuario_privilegio` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_dni`, `usuario_nombre`, `usuario_apellido`, `usuario_telefono`, `usuario_direccion`, `usuario_email`, `usuario_usuario`, `usuario_clave`, `usuario_estado`, `usuario_privilegio`) VALUES
(1, '000000000000000000', 'Administrador', 'Principal', '918235458', 'Huaraz', 'admin@admin.com', 'Administrador', 'enJDK1NMbkJHbm1kZFZxbkxyNUhsQT09', 'Activa', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliente_id`);

--
-- Indices de la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `prestamo_codigo` (`prestamo_codigo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`empresa_id`);

--
-- Indices de la tabla `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`pago_id`),
  ADD KEY `prestamo_codigo` (`prestamo_codigo`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`prestamo_id`),
  ADD UNIQUE KEY `prestamo_codigo` (`prestamo_codigo`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliente_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle`
--
ALTER TABLE `detalle`
  MODIFY `detalle_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `empresa_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `pago_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `prestamo_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle`
--
ALTER TABLE `detalle`
  ADD CONSTRAINT `detalle_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`),
  ADD CONSTRAINT `detalle_ibfk_2` FOREIGN KEY (`prestamo_codigo`) REFERENCES `prestamo` (`prestamo_codigo`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`prestamo_codigo`) REFERENCES `prestamo` (`prestamo_codigo`);

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`),
  ADD CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
