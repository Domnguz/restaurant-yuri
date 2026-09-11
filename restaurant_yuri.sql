-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2026 a las 01:51:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurant_yuri`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Mixto', 1, '2026-08-24 06:50:09', '2026-08-24 06:50:09'),
(2, 'Bebidas Frias', 1, '2026-08-24 06:50:09', '2026-08-27 08:52:18'),
(3, 'Postres', 1, '2026-08-24 06:50:09', '2026-08-24 06:50:09'),
(5, 'Helados', 1, '2026-08-26 02:24:37', '2026-08-26 02:24:37'),
(6, 'Bebibas Calientes', 1, '2026-09-04 08:04:45', '2026-09-04 08:04:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes_mixtos`
--

CREATE TABLE `componentes_mixtos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `componentes_mixtos`
--

INSERT INTO `componentes_mixtos` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Arroz', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(2, 'Tollito', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(3, 'Tallarines', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(4, 'Ceviche', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(5, 'Cancha', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(6, 'Camote', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(7, 'Mondongo', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26'),
(8, 'Chanfaina', 1, '2026-08-24 06:52:26', '2026-08-24 06:52:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero` int(10) UNSIGNED NOT NULL,
  `zona` enum('interior','exterior') NOT NULL DEFAULT 'interior',
  `estado` enum('disponible','ocupada','reservada') NOT NULL DEFAULT 'disponible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero`, `zona`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'interior', 'ocupada', '2026-08-24 07:22:08', '2026-09-04 08:47:03'),
(2, 2, 'interior', 'ocupada', '2026-08-24 07:22:08', '2026-09-04 08:34:32'),
(3, 3, 'interior', 'disponible', '2026-08-24 07:22:08', '2026-09-04 08:58:51'),
(4, 4, 'interior', 'disponible', '2026-08-24 07:22:08', '2026-08-24 07:22:08'),
(5, 5, 'interior', 'disponible', '2026-08-24 07:22:08', '2026-08-24 07:22:08'),
(6, 6, 'interior', 'ocupada', '2026-08-24 07:22:08', '2026-09-04 08:47:16'),
(7, 7, 'exterior', 'disponible', '2026-08-24 07:22:08', '2026-08-24 07:22:08'),
(8, 8, 'exterior', 'disponible', '2026-08-24 07:22:08', '2026-08-24 07:22:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_24_002625_create_categorias_table', 2),
(5, '2026_08_24_002927_create_productos_table', 2),
(6, '2026_08_24_003210_create_mesas_table', 2),
(7, '2026_08_24_003605_create_pedidos_table', 2),
(8, '2026_08_24_003851_create_pedido_detalles_table', 2),
(9, '2026_08_24_004316_create_pedido_detalle_producto_table', 2),
(10, '2026_08_24_005302_add_role_to_users_table', 2),
(11, '2026_08_24_011659_create_componente_mixtos_table', 2),
(12, '2026_08_24_011824_create_pedido_detalle_componentes_table', 3),
(13, '2026_08_28_203854_add_pago_fields_to_pedidos_table', 4),
(14, '2026_09_04_211230_modificar_estados_de_pedidos_table', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mesa_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('pendiente','listo','entregado','pagado','cancelado') NOT NULL DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metodo_pago` enum('efectivo','yape','plin','tarjeta') DEFAULT NULL,
  `monto_recibido` decimal(10,2) DEFAULT NULL,
  `vuelto` decimal(10,2) DEFAULT NULL,
  `pagado_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `mesa_id`, `user_id`, `estado`, `total`, `metodo_pago`, `monto_recibido`, `vuelto`, `pagado_at`, `created_at`, `updated_at`) VALUES
(3, 1, 2, 'pagado', 18.50, 'efectivo', 18.50, 0.00, '2026-09-04 07:14:00', '2026-08-28 06:17:10', '2026-09-04 07:14:00'),
(4, 2, 2, 'pagado', 8.00, 'yape', NULL, NULL, '2026-09-04 07:12:41', '2026-08-28 06:23:13', '2026-09-04 07:12:41'),
(5, 1, 2, 'pagado', 13.50, 'efectivo', 20.00, 6.50, '2026-09-04 07:47:04', '2026-09-04 07:46:01', '2026-09-04 07:47:04'),
(6, 1, 2, 'pagado', 4.50, 'efectivo', 5.05, 0.55, '2026-09-04 08:02:36', '2026-09-04 08:01:22', '2026-09-04 08:02:36'),
(7, 1, 2, 'pagado', 7.00, 'efectivo', 10.00, 3.00, '2026-09-04 08:03:39', '2026-09-04 08:03:05', '2026-09-04 08:03:39'),
(8, 1, 2, 'pagado', 3.00, 'efectivo', 10.00, 7.00, '2026-09-04 08:10:30', '2026-09-04 08:05:40', '2026-09-04 08:10:30'),
(9, 1, 2, 'pagado', 12.50, 'efectivo', 12.50, 0.00, '2026-09-04 08:32:48', '2026-09-04 08:31:37', '2026-09-04 08:32:48'),
(10, 2, 2, 'pendiente', 15.50, NULL, NULL, NULL, NULL, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(11, 1, 2, 'pendiente', 11.00, NULL, NULL, NULL, NULL, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(12, 6, 2, 'pendiente', 3.50, NULL, NULL, NULL, NULL, '2026-09-04 08:47:16', '2026-09-04 08:47:16'),
(13, 3, 2, 'pagado', 9.50, 'efectivo', 10.00, 0.50, '2026-09-04 08:58:51', '2026-09-04 08:57:12', '2026-09-04 08:58:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`id`, `pedido_id`, `precio`, `observaciones`, `created_at`, `updated_at`) VALUES
(5, 3, 1.50, NULL, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(6, 3, 2.00, NULL, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(7, 3, 8.00, NULL, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(8, 4, 8.00, 'poquito arroz', '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(9, 3, 7.00, NULL, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(10, 5, 8.00, NULL, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(11, 5, 2.00, NULL, '2026-09-04 07:46:38', '2026-09-04 07:46:38'),
(12, 5, 1.50, NULL, '2026-09-04 07:46:38', '2026-09-04 07:46:38'),
(13, 5, 2.00, NULL, '2026-09-04 07:46:38', '2026-09-04 07:46:38'),
(14, 6, 1.50, NULL, '2026-09-04 08:01:22', '2026-09-04 08:01:22'),
(15, 6, 3.00, NULL, '2026-09-04 08:01:22', '2026-09-04 08:01:22'),
(16, 7, 7.00, NULL, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(17, 8, 1.50, NULL, '2026-09-04 08:05:40', '2026-09-04 08:05:40'),
(18, 8, 1.50, NULL, '2026-09-04 08:05:40', '2026-09-04 08:05:40'),
(19, 9, 1.50, NULL, '2026-09-04 08:31:37', '2026-09-04 08:31:37'),
(20, 9, 11.00, NULL, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(21, 10, 1.50, NULL, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(22, 10, 2.00, NULL, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(23, 10, 3.00, NULL, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(24, 10, 9.00, 'Poco arroz', '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(25, 11, 11.00, NULL, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(26, 12, 1.50, NULL, '2026-09-04 08:47:16', '2026-09-04 08:47:16'),
(27, 12, 2.00, NULL, '2026-09-04 08:47:16', '2026-09-04 08:47:16'),
(28, 13, 8.00, 'Danelsillo gay', '2026-09-04 08:57:12', '2026-09-04 08:57:12'),
(29, 13, 1.50, NULL, '2026-09-04 08:57:12', '2026-09-04 08:57:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle_componentes`
--

CREATE TABLE `pedido_detalle_componentes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_detalle_id` bigint(20) UNSIGNED NOT NULL,
  `componente_mixto_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedido_detalle_componentes`
--

INSERT INTO `pedido_detalle_componentes` (`id`, `pedido_detalle_id`, `componente_mixto_id`, `created_at`, `updated_at`) VALUES
(1, 7, 1, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(2, 7, 2, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(3, 7, 3, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(4, 7, 4, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(5, 7, 5, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(6, 7, 6, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(7, 7, 7, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(8, 8, 1, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(9, 8, 4, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(10, 8, 5, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(11, 8, 6, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(12, 8, 7, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(13, 8, 8, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(14, 9, 1, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(15, 9, 2, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(16, 9, 3, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(17, 9, 4, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(18, 9, 5, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(19, 9, 7, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(20, 9, 8, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(21, 10, 1, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(22, 10, 2, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(23, 10, 3, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(24, 10, 4, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(25, 10, 5, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(26, 10, 6, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(27, 10, 7, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(28, 10, 8, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(29, 16, 1, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(30, 16, 2, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(31, 16, 3, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(32, 16, 4, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(33, 16, 5, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(34, 16, 6, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(35, 16, 7, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(36, 20, 1, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(37, 20, 2, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(38, 20, 3, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(39, 20, 4, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(40, 20, 5, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(41, 20, 6, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(42, 20, 8, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(43, 24, 1, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(44, 24, 2, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(45, 24, 3, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(46, 24, 4, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(47, 24, 5, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(48, 24, 8, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(49, 25, 1, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(50, 25, 2, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(51, 25, 3, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(52, 25, 4, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(53, 25, 5, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(54, 25, 6, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(55, 25, 7, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(56, 28, 1, '2026-09-04 08:57:12', '2026-09-04 08:57:12'),
(57, 28, 2, '2026-09-04 08:57:12', '2026-09-04 08:57:12'),
(58, 28, 5, '2026-09-04 08:57:12', '2026-09-04 08:57:12'),
(59, 28, 6, '2026-09-04 08:57:12', '2026-09-04 08:57:12'),
(60, 28, 8, '2026-09-04 08:57:12', '2026-09-04 08:57:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle_producto`
--

CREATE TABLE `pedido_detalle_producto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_detalle_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedido_detalle_producto`
--

INSERT INTO `pedido_detalle_producto` (`id`, `pedido_detalle_id`, `producto_id`, `created_at`, `updated_at`) VALUES
(10, 5, 4, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(11, 6, 2, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(12, 7, 6, '2026-08-28 06:17:10', '2026-08-28 06:17:10'),
(13, 8, 6, '2026-08-28 06:23:13', '2026-08-28 06:23:13'),
(14, 9, 6, '2026-08-28 06:35:35', '2026-08-28 06:35:35'),
(15, 10, 6, '2026-09-04 07:46:01', '2026-09-04 07:46:01'),
(16, 11, 1, '2026-09-04 07:46:38', '2026-09-04 07:46:38'),
(17, 12, 4, '2026-09-04 07:46:38', '2026-09-04 07:46:38'),
(18, 13, 5, '2026-09-04 07:46:38', '2026-09-04 07:46:38'),
(19, 14, 4, '2026-09-04 08:01:22', '2026-09-04 08:01:22'),
(20, 15, 3, '2026-09-04 08:01:22', '2026-09-04 08:01:22'),
(21, 16, 6, '2026-09-04 08:03:05', '2026-09-04 08:03:05'),
(22, 17, 7, '2026-09-04 08:05:40', '2026-09-04 08:05:40'),
(23, 18, 7, '2026-09-04 08:05:40', '2026-09-04 08:05:40'),
(24, 19, 7, '2026-09-04 08:31:37', '2026-09-04 08:31:37'),
(25, 20, 6, '2026-09-04 08:31:49', '2026-09-04 08:31:49'),
(26, 21, 4, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(27, 22, 2, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(28, 23, 3, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(29, 24, 6, '2026-09-04 08:34:32', '2026-09-04 08:34:32'),
(30, 25, 6, '2026-09-04 08:47:03', '2026-09-04 08:47:03'),
(31, 26, 4, '2026-09-04 08:47:16', '2026-09-04 08:47:16'),
(32, 27, 1, '2026-09-04 08:47:16', '2026-09-04 08:47:16'),
(33, 28, 6, '2026-09-04 08:57:12', '2026-09-04 08:57:12'),
(34, 29, 7, '2026-09-04 08:57:12', '2026-09-04 08:57:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `precio`, `activo`, `created_at`, `updated_at`) VALUES
(1, 2, 'Cevada', 2.00, 1, '2026-08-24 19:57:56', '2026-08-24 19:57:56'),
(2, 2, 'Clarito', 2.00, 1, '2026-08-24 19:57:56', '2026-08-24 19:57:56'),
(3, 2, 'Gaseosa', 3.00, 1, '2026-08-24 19:57:56', '2026-08-24 19:57:56'),
(4, 2, 'Agua', 1.50, 1, '2026-08-24 19:57:56', '2026-08-27 09:09:24'),
(5, 2, 'Chicha Morada', 2.00, 1, '2026-08-26 00:28:32', '2026-08-26 00:28:32'),
(6, 1, 'Mixto', NULL, 1, '2026-08-28 00:28:42', '2026-08-28 00:28:42'),
(7, 6, 'Cafe', 1.50, 1, '2026-09-04 08:05:05', '2026-09-04 08:05:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3Xl9hATRaKF7AMCSmZXgezsqYjZ7q0X6d32KUccd', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'eyJfdG9rZW4iOiJmWTRmZ1FiNEt4a2VkZGs4YXpGaUU0NWNNdEJJdEwyR0xqWGNLQjc0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcGVkaWRvc1wvMTIiLCJyb3V0ZSI6ImFkbWluLnBlZGlkb3Muc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1788556661),
('5i3vIFKTc2CBNjjpe8DxpsxAfTn1y5V9nzbyrLnS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'eyJfdG9rZW4iOiJYd2R5RFJPM3ZSaFQ2QnoxcFU1TVNYOGc2cjZ6QnRVWFlNcG5FU0FhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=', 1788487499),
('bERCvGyIsdJ09BqOGePa62qIVv1vjIyLRvLACbZ9', 2, '192.168.1.20', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJKU3ZqVGl3TjhYYnVOcEM3SnhjNldwY09EQVlnbFBYdXJ4Qm1MSUpLIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTkyLjE2OC4xLjcxOjgwMDBcL2FkbWluXC9tZXNlcm9cL3BlZGlkb3MiLCJyb3V0ZSI6ImFkbWluLm1lc2Vyby5wZWRpZG9zLmluZGV4In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', 1788494234),
('lgHiw9MCY6hlNAJHLrqgoXUzs7lQfITDwIU3CRvh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.135.0 Chrome/148.0.7778.280 Electron/42.8.1 Safari/537.36', 'eyJfdG9rZW4iOiJieEVqdFhXU0h4aXFtTUhBZFpOdERPckFEcHNzUDh3MzJXMDE2TTJiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1788487832),
('LJkw3BwRBJxocwdgGWXydc9CuYUdpM4ZuMDFhnU5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.136.1 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36', 'eyJfdG9rZW4iOiJUNWNDOTB3U1JDSlBFZXZPeDFqZkk0cHV6SWxFZk9BaUwwR29vcmhoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1788554426),
('O6kXPK2e111A8xI7uPDj1uT6mbBMJR0bJmIDTJ3m', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'eyJfdG9rZW4iOiJVN1lvSkthbEJ6QXI1eDlycXRwRk9SUFRUTVhHYzkzdldJM2ZHSjRsIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluIiwicm91dGUiOiJhZG1pbi5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1788494337),
('q85RCsiPE8zMsrkuIqUPiCq6wW6kqN9sOsxndgjD', NULL, '192.168.1.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'eyJfdG9rZW4iOiJoUjE5cEdKZDRtMWhOcThXRW13SmYyVU8yS2ZMRWlmdkdXWUVraHN0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMS43MTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1788491807),
('sjKE8RU8js23Yj14uCkhAN4529LoqPNI1m8V8RXS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.136.1 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36', 'eyJfdG9rZW4iOiJpd0NrU3hOYldGMXpmbTg1UnNVTHdMb0M1a2pjUXJWb2R0ZFY3R1JTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1788897022),
('zQpRNACTCDcb81WkkLCrrHi2o7pN2QqFHNTwgxes', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'eyJfdG9rZW4iOiJlQndrZGQyNjl5UlRmQWtUWGl6OFFTUWltQVUxQVFzTHNoS2xxNzFWIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1788492812);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','mesero') NOT NULL DEFAULT 'mesero',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@restaurantyuri.com', 'admin', NULL, '$2y$12$UefKPEJ4v5XO2qVQmV7TK.xSd9HXmPWLLTOmz3dvuqfV3i/PKHtkC', NULL, '2026-08-24 06:50:09', '2026-08-24 06:50:09'),
(2, 'Yanet', 'mesero1@restaurantyuri.com', 'mesero', NULL, '$2y$12$zs.bD5iV2bSkIFI4quoLfOPi0MDEu5NGPGu2bDRXyMDZ2LoHalmjm', NULL, '2026-08-24 06:50:09', '2026-08-28 06:45:32'),
(3, 'Mesero 2', 'mesero2@restaurantyuri.com', 'mesero', NULL, '$2y$12$yRVCh2I1K9.5LxbhJ/c/Sucp92gjYZKPAxVHFOOIQlj3YbgWn9lx2', NULL, '2026-08-24 06:50:09', '2026-08-24 06:50:09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_nombre_unique` (`nombre`);

--
-- Indices de la tabla `componentes_mixtos`
--
ALTER TABLE `componentes_mixtos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mesas_numero_unique` (`numero`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_mesa_id_foreign` (`mesa_id`),
  ADD KEY `pedidos_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_detalles_pedido_id_foreign` (`pedido_id`);

--
-- Indices de la tabla `pedido_detalle_componentes`
--
ALTER TABLE `pedido_detalle_componentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `detalle_componente_unique` (`pedido_detalle_id`,`componente_mixto_id`),
  ADD KEY `pedido_detalle_componentes_componente_mixto_id_foreign` (`componente_mixto_id`);

--
-- Indices de la tabla `pedido_detalle_producto`
--
ALTER TABLE `pedido_detalle_producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pedido_detalle_producto_pedido_detalle_id_producto_id_unique` (`pedido_detalle_id`,`producto_id`),
  ADD KEY `pedido_detalle_producto_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `componentes_mixtos`
--
ALTER TABLE `componentes_mixtos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle_componentes`
--
ALTER TABLE `pedido_detalle_componentes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle_producto`
--
ALTER TABLE `pedido_detalle_producto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_mesa_id_foreign` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `pedidos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido_detalle_componentes`
--
ALTER TABLE `pedido_detalle_componentes`
  ADD CONSTRAINT `pedido_detalle_componentes_componente_mixto_id_foreign` FOREIGN KEY (`componente_mixto_id`) REFERENCES `componentes_mixtos` (`id`),
  ADD CONSTRAINT `pedido_detalle_componentes_pedido_detalle_id_foreign` FOREIGN KEY (`pedido_detalle_id`) REFERENCES `pedido_detalles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido_detalle_producto`
--
ALTER TABLE `pedido_detalle_producto`
  ADD CONSTRAINT `pedido_detalle_producto_pedido_detalle_id_foreign` FOREIGN KEY (`pedido_detalle_id`) REFERENCES `pedido_detalles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_detalle_producto_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
