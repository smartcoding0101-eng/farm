-- ==========================================================
-- DUMP COMPLETO Y SEGURO PARA PRODUCCION - FARMALUZ
-- Base de datos: smartcoding_farma (cPanel)
-- Incluye: DDL estructurado, Multitienda, Datos iniciales y Migraciones
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';

-- --------------------------------------------------------
-- Estructura de tabla para `tiendas`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tiendas`;
CREATE TABLE `tiendas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `dominio` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'BOB',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tiendas_slug_unique` (`slug`),
  UNIQUE KEY `tiendas_dominio_unique` (`dominio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'cliente',
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `tienda_user`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tienda_user`;
CREATE TABLE `tienda_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'dueno',
  `es_predeterminada` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tienda_user_tienda_id_user_id_unique` (`tienda_id`,`user_id`),
  KEY `tienda_user_user_id_foreign` (`user_id`),
  CONSTRAINT `tienda_user_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tienda_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `categorias`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `categoria_padre_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `icono` varchar(50) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `destacada` tinyint(1) NOT NULL DEFAULT 0,
  `meta_titulo` varchar(255) DEFAULT NULL,
  `meta_descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_slug_unique` (`slug`),
  KEY `categorias_categoria_padre_id_foreign` (`categoria_padre_id`),
  KEY `categorias_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `categorias_categoria_padre_id_foreign` FOREIGN KEY (`categoria_padre_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `categorias_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `marcas`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `marcas`;
CREATE TABLE `marcas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `nombre` varchar(120) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marcas_slug_unique` (`slug`),
  KEY `marcas_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `marcas_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `productos`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `categoria_id` bigint(20) unsigned NOT NULL,
  `marca_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `slug` varchar(230) NOT NULL,
  `sku` varchar(60) NOT NULL,
  `codigo_barras` varchar(80) DEFAULT NULL,
  `descripcion_corta` text DEFAULT NULL,
  `descripcion_completa` longtext DEFAULT NULL,
  `precio_venta` decimal(12,2) NOT NULL,
  `costo_adquisicion` decimal(12,2) NOT NULL DEFAULT 0.00,
  `precio_oferta` decimal(12,2) DEFAULT NULL,
  `oferta_inicio` timestamp NULL DEFAULT NULL,
  `oferta_fin` timestamp NULL DEFAULT NULL,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `peso_kg` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tipo` enum('fisico','digital','servicio') NOT NULL DEFAULT 'fisico',
  `estado` enum('borrador','publicado','archivado') NOT NULL DEFAULT 'publicado',
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `nuevo` tinyint(1) NOT NULL DEFAULT 1,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `meta_titulo` varchar(255) DEFAULT NULL,
  `meta_descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_slug_unique` (`slug`),
  UNIQUE KEY `productos_sku_unique` (`sku`),
  KEY `productos_categoria_id_foreign` (`categoria_id`),
  KEY `productos_marca_id_foreign` (`marca_id`),
  KEY `productos_codigo_barras_index` (`codigo_barras`),
  KEY `productos_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `productos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `productos_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `zonas_envio`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `zonas_envio`;
CREATE TABLE `zonas_envio` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `nombre` varchar(100) NOT NULL,
  `departamento` varchar(50) NOT NULL DEFAULT 'La Paz',
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_minimo_envio_gratis` decimal(10,2) DEFAULT NULL,
  `tiempo_estimado` varchar(80) NOT NULL DEFAULT '24 a 48 horas',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zonas_envio_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `zonas_envio_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `cupones`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cupones`;
CREATE TABLE `cupones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `codigo` varchar(50) NOT NULL,
  `tipo` enum('porcentaje','fijo') NOT NULL DEFAULT 'porcentaje',
  `valor` decimal(10,2) NOT NULL,
  `monto_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `limite_usos` int(11) DEFAULT NULL,
  `usos_actuales` int(11) NOT NULL DEFAULT 0,
  `fecha_inicio` timestamp NULL DEFAULT NULL,
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cupones_codigo_unique` (`codigo`),
  KEY `cupones_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `cupones_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `configuraciones`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `configuraciones`;
CREATE TABLE `configuraciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `clave` varchar(80) NOT NULL,
  `valor` text DEFAULT NULL,
  `grupo` varchar(50) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuraciones_clave_unique` (`clave`),
  KEY `configuraciones_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `configuraciones_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `banners`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `titulo` varchar(150) NOT NULL,
  `subtitulo` varchar(250) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `texto_boton` varchar(60) NOT NULL DEFAULT 'Ver Catálogo',
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banners_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `banners_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `movimientos_inventario`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `movimientos_inventario`;
CREATE TABLE `movimientos_inventario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `producto_id` bigint(20) unsigned NOT NULL,
  `variante_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `pedido_id` bigint(20) unsigned DEFAULT NULL,
  `tipo` enum('entrada','salida','ajuste','devolucion') NOT NULL DEFAULT 'entrada',
  `cantidad` int(11) NOT NULL,
  `stock_anterior` int(11) NOT NULL,
  `stock_posterior` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_inventario_producto_id_foreign` (`producto_id`),
  KEY `movimientos_inventario_variante_id_foreign` (`variante_id`),
  KEY `movimientos_inventario_user_id_foreign` (`user_id`),
  KEY `movimientos_inventario_pedido_id_foreign` (`pedido_id`),
  KEY `movimientos_inventario_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `movimientos_inventario_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `movimientos_inventario_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_inventario_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_inventario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `movimientos_inventario_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `variantes_producto` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `pedidos`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `numero_pedido` varchar(50) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `cliente_nombre` varchar(150) NOT NULL,
  `cliente_email` varchar(150) DEFAULT NULL,
  `cliente_telefono` varchar(30) NOT NULL,
  `modalidad_entrega` enum('domicilio','retiro_tienda') NOT NULL DEFAULT 'domicilio',
  `zona_envio_id` bigint(20) unsigned DEFAULT NULL,
  `departamento` varchar(50) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `direccion_envio` text DEFAULT NULL,
  `referencia_envio` text DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `descuento` decimal(12,2) NOT NULL DEFAULT 0.00,
  `costo_envio` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `cupon_aplicado` varchar(50) DEFAULT NULL,
  `estado_pedido` enum('pendiente','confirmado','en_preparacion','listo_despacho','en_camino','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `metodo_pago` enum('qr_simple','transferencia','tarjeta','efectivo_contraentrega','pasarela_online') NOT NULL DEFAULT 'qr_simple',
  `estado_pago` enum('pendiente','en_verificacion','pagado','fallido','reembolsado') NOT NULL DEFAULT 'pendiente',
  `notas_cliente` text DEFAULT NULL,
  `notas_administrador` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pedidos_numero_pedido_unique` (`numero_pedido`),
  KEY `pedidos_user_id_foreign` (`user_id`),
  KEY `pedidos_zona_envio_id_foreign` (`zona_envio_id`),
  KEY `pedidos_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `pedidos_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedidos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_zona_envio_id_foreign` FOREIGN KEY (`zona_envio_id`) REFERENCES `zonas_envio` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `detalles_pedido`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `detalles_pedido`;
CREATE TABLE `detalles_pedido` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `variante_id` bigint(20) unsigned DEFAULT NULL,
  `producto_nombre` varchar(200) NOT NULL,
  `sku` varchar(60) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `costo_adquisicion_unitario` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalles_pedido_pedido_id_foreign` (`pedido_id`),
  KEY `detalles_pedido_producto_id_foreign` (`producto_id`),
  KEY `detalles_pedido_variante_id_foreign` (`variante_id`),
  CONSTRAINT `detalles_pedido_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalles_pedido_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  CONSTRAINT `detalles_pedido_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `variantes_producto` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `pagos`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pagos`;
CREATE TABLE `pagos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `referencia_pago` varchar(80) NOT NULL,
  `metodo` enum('qr','transferencia','tarjeta','efectivo','pasarela') NOT NULL DEFAULT 'qr',
  `monto` decimal(12,2) NOT NULL,
  `comprobante_archivo` varchar(255) DEFAULT NULL,
  `datos_transaccion` text DEFAULT NULL,
  `estado` enum('pendiente','en_revision','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `verificado_por` bigint(20) unsigned DEFAULT NULL,
  `fecha_verificacion` timestamp NULL DEFAULT NULL,
  `motivo_rechazo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pagos_referencia_pago_unique` (`referencia_pago`),
  KEY `pagos_pedido_id_foreign` (`pedido_id`),
  KEY `pagos_verificado_por_foreign` (`verificado_por`),
  CONSTRAINT `pagos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagos_verificado_por_foreign` FOREIGN KEY (`verificado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `carritos`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `carritos`;
CREATE TABLE `carritos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carritos_user_id_foreign` (`user_id`),
  KEY `carritos_session_id_index` (`session_id`),
  KEY `carritos_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `carritos_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carritos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `items_carrito`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `items_carrito`;
CREATE TABLE `items_carrito` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `carrito_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `variante_id` bigint(20) unsigned DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_carrito_carrito_id_foreign` (`carrito_id`),
  KEY `items_carrito_producto_id_foreign` (`producto_id`),
  KEY `items_carrito_variante_id_foreign` (`variante_id`),
  CONSTRAINT `items_carrito_carrito_id_foreign` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `items_carrito_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `items_carrito_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `variantes_producto` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `variantes_producto`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `variantes_producto`;
CREATE TABLE `variantes_producto` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `sku_variante` varchar(60) NOT NULL,
  `precio_adicional` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `variantes_producto_sku_variante_unique` (`sku_variante`),
  KEY `variantes_producto_producto_id_foreign` (`producto_id`),
  CONSTRAINT `variantes_producto_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `imagenes_producto`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `imagenes_producto`;
CREATE TABLE `imagenes_producto` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint(20) unsigned NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `alt_texto` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imagenes_producto_producto_id_foreign` (`producto_id`),
  CONSTRAINT `imagenes_producto_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Volcado de datos para la tabla `tiendas`
-- --------------------------------------------------------
INSERT INTO `tiendas` (`id`, `nombre`, `slug`, `dominio`, `logo`, `telefono`, `email`, `moneda`, `activo`, `created_at`, `updated_at`) VALUES
(2, 'FARMALUZ - Preparados Magistrales', 'farmaluz-magistrales', NULL, NULL, '+591 70012345', 'contacto@farmaluz.bo', 'BOB', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `users`
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `email`, `role`, `telefono`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dra. Luz Morales - Regente Farmacéutico', 'admin@tienda.test', 'admin', '+591 70012345', 1, NULL, '$2y$12$GtwtYCRtlk1PVlrC/wH87unNwbMnZ50xHcHxBxkXjFLTh1aQwaAjC', NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 'María René Fernández', 'cliente@tienda.test', 'cliente', '+591 76543210', 1, NULL, '$2y$12$2wq640KPvply2wxWXwGsyuSW17fQ54fUb.kdVKevKqCocd0kZQup2', NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `tienda_user`
-- --------------------------------------------------------
INSERT INTO `tienda_user` (`id`, `tienda_id`, `user_id`, `rol`, `es_predeterminada`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'dueno', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `categorias`
-- --------------------------------------------------------
INSERT INTO `categorias` (`id`, `tienda_id`, `categoria_padre_id`, `nombre`, `slug`, `descripcion`, `imagen`, `icono`, `orden`, `activo`, `destacada`, `meta_titulo`, `meta_descripcion`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'Fitoterapia & Tinturas Madre', 'fitoterapia-tinturas-madre', 'Extractos hidroalcohólicos y gotas naturales estandarizadas de pasiflora, valeriana, propóleo y alcachofa.', NULL, 'seedling', 0, 1, 1, NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 2, NULL, 'Preparados Magistrales', 'preparados-magistrales', 'Fórmulas individualizadas, cápsulas botánicas, ungüentos galénicos y emulsiones elaboradas bajo receta farmacéutica.', NULL, 'mortar-pestle', 0, 1, 1, NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(3, 2, NULL, 'Dermofarmacia & Cuidado de la Piel', 'dermofarmacia-cuidado-de-la-piel', 'Cremas nutritivas con caléndula, árnica montana, geles puros de aloe vera y serums antiedad botánicos.', NULL, 'spa', 0, 1, 1, NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(4, 2, NULL, 'Aceites Esenciales Puros', 'aceites-esenciales-puros', 'Aceites 100% puros y quimiotipados de lavanda francesa, árbol de té, eucalipto glóbulus y menta piperita.', NULL, 'droplet', 0, 1, 1, NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(5, 2, NULL, 'Suplementos & Antioxidantes', 'suplementos-antioxidantes', 'Citrato de magnesio puro, colágeno hidrolizado marino, espirulina orgánica, cúrcuma y zinc biodisponible.', NULL, 'capsules', 0, 1, 1, NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(6, 2, NULL, 'Jarabes & Vías Respiratorias', 'jarabes-vias-respiratorias', 'Jarabes bronquiales a base de miel de abeja virgen, propóleo verde, eucalipto, wira wira y tomillo.', NULL, 'bottle-droplet', 0, 1, 0, NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `marcas`
-- --------------------------------------------------------
INSERT INTO `marcas` (`id`, `tienda_id`, `nombre`, `slug`, `logo`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 2, 'Farmaluz Magistral', 'farmaluz-magistral', NULL, 'Fórmulas exclusivas y preparados galénicos elaborados en nuestro laboratorio farmacéutico.', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 2, 'Botánica Andina', 'botanica-andina', NULL, 'Extractos y tinturas de hierbas medicinales nativas con certificación ecológica.', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(3, 2, 'PhytoVital', 'phytovital', NULL, 'Suplementos nutricionales y oligoelementos con alta biodisponibilidad celular.', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(4, 2, 'DermoNatura', 'dermonatura', NULL, 'Dermofarmacia hipoalergénica con aceites vegetales prensados en frío.', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(5, 2, 'AromaLab Pura', 'aromalab-pura', NULL, 'Aceites esenciales de grado terapéutico y destilación por arrastre de vapor.', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `productos`
-- --------------------------------------------------------
INSERT INTO `productos` (`id`, `tienda_id`, `categoria_id`, `marca_id`, `nombre`, `slug`, `sku`, `codigo_barras`, `descripcion_corta`, `descripcion_completa`, `precio_venta`, `costo_adquisicion`, `precio_oferta`, `oferta_inicio`, `oferta_fin`, `stock_actual`, `stock_minimo`, `peso_kg`, `tipo`, `estado`, `destacado`, `nuevo`, `imagen_principal`, `meta_titulo`, `meta_descripcion`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'Tintura Madre Valeriana & Pasiflora (Relax Nocturno 50ml)', 'tintura-madre-valeriana-pasiflora-relax-nocturno-50ml', 'FIT-VAL-PAS-50ML', NULL, 'Extracto hidroalcohólico concentrado para conciliar el sueño profundo y aliviar la ansiedad.', 'Fórmula botánica con extracto fluido de raíz de Valeriana officinalis y flores de Passiflora incarnata. Promueve la relajación del sistema nervioso sin generar dependencia ni somnolencia residual diurna.', 65.00, 32.00, 55.00, NULL, NULL, 35, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_tintura_valeriana.jpg', NULL, NULL, '2026-10-23 10:30:47', '2026-10-23 10:55:43'),
(2, 2, 1, 2, 'Gotas Digestivas de Boldo, Carqueja & Menta (Digest-Luz 60ml)', 'gotas-digestivas-de-boldo-carqueja-menta-digest-luz-60ml', 'FIT-DIG-BOL-60ML', NULL, 'Regulador hepático y digestivo natural, alivia pesadez y cólicos estomacales.', 'Combinación magistral de hierbas amargas hepatoprotectoras. Estimula la producción biliar, optimiza la asimilación de alimentos grasos y previene la hinchazón abdominal tras las comidas.', 58.00, 28.00, NULL, NULL, NULL, 40, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_gotas_digestivas.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:54:24'),
(3, 2, 2, 1, 'Ungüento Magistral de Árnica, Harpagofito & Sauce 100g', 'unguento-magistral-de-arnica-harpagofito-sauce-100g', 'MAG-ARN-HAR-100G', NULL, 'Antiinflamatorio y analgésico tópico galénico para dolores articulares y musculares.', 'Pomada elaborada artesanalmente con extracto de Árnica montana al 15%, corteza de sauce blanco (salicina natural) y raíz de harpagofito. Ideal para contracturas, artrosis, esguinces y fatiga muscular.', 75.00, 38.00, 65.00, NULL, NULL, 25, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_unguento_arnica.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 10:55:43'),
(4, 2, 2, 1, 'Cápsulas Magistrales de Cardo Mariano & Alcachofa (60 Cápsulas)', 'capsulas-magistrales-de-cardo-mariano-alcachofa-60-capsulas', 'MAG-CAR-ALC-60CAP', NULL, 'Desintoxicante hepático intensivo con Silimarina estandarizada al 80%.', 'Cápsulas vegetales compuestas por extracto seco estandarizado de Silybum marianum (Silimarina) y Cynara scolymus. Protege los hepatocitos contra toxinas, coadyuva en hígado graso y favorece la digestión.', 110.00, 60.00, NULL, NULL, NULL, 30, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_capsulas_cardo.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:54:24'),
(5, 2, 3, 4, 'Crema Facial Regeneradora de Caléndula & Manzanilla 100g', 'crema-facial-regeneradora-de-calendula-manzanilla-100g', 'DER-CAL-MAN-100G', NULL, 'Crema calmante y cicatrizante para pieles sensibles, rosácea o irritadas.', 'Base hipoalergénica no comedogénica enriquecida con oleato de caléndula officinales, bisabolol natural de manzanilla y manteca de karité pura. Calma el enrojecimiento, repara la barrera cutánea y restaura la hidratación profunda.', 85.00, 42.00, 75.00, NULL, NULL, 28, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_crema_calendula.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 10:55:43'),
(6, 2, 3, 4, 'Serum Botánico Antiedad con Ácido Hialurónico Vegetal & Rosa Mosqueta 30ml', 'serum-botanico-antiedad-con-acido-hialuronico-vegetal-rosa-mosqueta-30ml', 'DER-SER-ROS-30ML', NULL, 'Fórmula sedosa reafirmante con antioxidantes botánicos y aceite virgen de rosa mosqueta.', 'Serum intensivo libre de parabenos y siliconas. Penetra capas epidérmicas profundas para estimular la síntesis de colágeno, atenuar líneas de expresión y unificar el tono de la piel.', 125.00, 65.00, 110.00, NULL, NULL, 20, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_serum_botanico.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:54:24'),
(7, 2, 4, 5, 'Aceite Esencial Puro de Lavanda Francesa Grado Terapéutico 15ml', 'aceite-esencial-puro-de-lavanda-francesa-grado-terapeutico-15ml', 'ACE-LAV-FRA-15ML', NULL, 'Aceite 100% puro para difusor, masajes relajantes y aromaterapia de paz mental.', 'Destilado al vapor de sumidades floridas de Lavandula angustifolia. Reconocido por sus propiedades ansiolíticas, antisépticas leves y su aroma reconfortante para dormitorios y espacios de descanso.', 70.00, 35.00, NULL, NULL, NULL, 45, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_aceite_lavanda.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:54:24'),
(8, 2, 4, 5, 'Aceite Esencial de Árbol de Té Australiano (Tea Tree) 15ml', 'aceite-esencial-de-arbol-de-te-australiano-tea-tree-15ml', 'ACE-TEA-TRE-15ML', NULL, 'Potente antibacteriano, antifúngico y purificante natural para cutis graso y uñas.', 'Melaleuca alternifolia 100% pura y sin diluir. Indicado para tratar brotes de acné, hongos en uñas (onicomicosis), picaduras de insectos y desinfección ambiental en vaporizadores.', 68.00, 34.00, 59.00, NULL, NULL, 38, 5, '0.00', 'fisico', 'publicado', 0, 1, '/images/prod_aceite_teatree.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:54:24'),
(9, 2, 5, 3, 'Citrato de Magnesio Puro en Polvo 250g (Grado USP Alta Absorción)', 'citrato-de-magnesio-puro-en-polvo-250g-grado-usp-alta-absorcion', 'SUP-MAG-CIT-250G', NULL, 'Mineral biodisponible para aliviar calambres, fatiga, mejorar el descanso y la función muscular.', 'Magnesio enlazado a ácido cítrico para una tasa de absorción superior al 90%, evitando malestares gástricos. Rinde más de 80 tomas diarias. Libre de aditivos, saborizantes o azúcar añadida.', 95.00, 50.00, 85.00, NULL, NULL, 50, 5, '0.00', 'fisico', 'publicado', 1, 1, '/images/prod_citrato_magnesio.jpg', NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:54:24'),
(10, 2, 5, 3, 'Propóleo Verde Titulado al 30% en Gotas 30ml', 'propoleo-verde-titulado-al-30-en-gotas-30ml', 'SUP-PRO-VER-30ML', NULL, 'Escudo inmunológico natural con bioflavonoides y Artepillina-C antiviral.', 'Extracto concentrado de propóleo virgen de abejas con alta concentración de compuestos fenólicos. Refuerza las defensas naturales, protege la garganta y desinfecta las vías bucofaríngeas.', 50.00, 24.00, NULL, NULL, NULL, 60, 5, '0.00', 'fisico', 'publicado', 0, 1, NULL, NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:55:12'),
(11, 2, 6, 2, 'Jarabe Balsámico de Miel, Propóleo, Eucalipto & Wira Wira 200ml', 'jarabe-balsamico-de-miel-propoleo-eucalipto-wira-wira-200ml', 'JAR-BAL-WIR-200ML', NULL, 'Expectorante y calmante bronquial con hierbas andinas tradicionales y miel pura.', 'Jarabe tradicional con infusión concentrada de Wira Wira (Gnaphalium), hojas tiernas de eucalipto, tomillo y miel pura de valles bolivianos. Suaviza la garganta irritada y facilita la expulsión de flemas.', 48.00, 22.00, 42.00, NULL, NULL, 45, 5, '0.00', 'fisico', 'publicado', 1, 1, NULL, NULL, NULL, '2026-10-23 10:30:48', '2026-10-23 11:55:12');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `zonas_envio`
-- --------------------------------------------------------
INSERT INTO `zonas_envio` (`id`, `tienda_id`, `nombre`, `departamento`, `costo_envio`, `monto_minimo_envio_gratis`, `tiempo_estimado`, `activo`, `created_at`, `updated_at`) VALUES
(1, 2, 'La Paz (Centro, Miraflores, Sopocachi)', 'La Paz', 15.00, 180.00, 'Mismo día (2 a 4 horas)', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 2, 'Zona Sur (Calacoto, San Miguel, Achumani, Obrajes)', 'La Paz', 20.00, 220.00, 'Mismo día (3 a 5 horas)', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(3, 2, 'El Alto (Ceja, Satélite, Villa Adela)', 'La Paz', 20.00, 220.00, '24 horas', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(4, 2, 'Envíos Nacionales Especializados (Cochabamba, Santa Cruz, Sucre)', 'Nacional', 35.00, 350.00, '24 a 48 horas con embalaje térmico protector', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `cupones`
-- --------------------------------------------------------
INSERT INTO `cupones` (`id`, `tienda_id`, `codigo`, `tipo`, `valor`, `monto_minimo`, `limite_usos`, `usos_actuales`, `fecha_inicio`, `fecha_fin`, `activo`, `created_at`, `updated_at`) VALUES
(1, 2, 'SALUDNATURAL', 'porcentaje', 10.00, 80.00, 200, 0, '2026-10-22 10:30:47', '2027-04-23 10:30:47', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 2, 'MAGISTRAL30', 'fijo', 30.00, 250.00, 100, 0, '2026-10-22 10:30:47', '2027-01-23 10:30:47', 1, '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `configuraciones`
-- --------------------------------------------------------
INSERT INTO `configuraciones` (`id`, `tienda_id`, `clave`, `valor`, `grupo`, `created_at`, `updated_at`) VALUES
(1, 2, 'nombre_tienda', 'FARMALUZ - Preparados Magistrales', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 2, 'slogan', 'Farmacia Natural, Fitoterapia y Fórmulas Magistrales Personalizadas', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(3, 2, 'moneda', 'BOB', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(4, 2, 'simbolo_moneda', 'Bs.', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(5, 2, 'telefono_whatsapp', 59170012345, 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(6, 2, 'mensaje_whatsapp_default', '¡Hola Farmaluz! Deseo consultar sobre preparados magistrales o medicina natural: ', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(7, 2, 'email_contacto', 'contacto@farmaluz.bo', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(8, 2, 'direccion_tienda', 'Av. Arce esq. Cordero, Edificio Platinum Mezzanine 2, La Paz - Bolivia', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(9, 2, 'horario_atencion', 'Lunes a Viernes de 08:30 a 19:30 | Sábados de 09:00 a 14:00', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(10, 2, 'qr_banco', 'Banco Nacional de Bolivia / BCP', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(11, 2, 'qr_titular', 'FARMALUZ LABORATORIOS S.R.L.', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(12, 2, 'qr_imagen', '/images/farmaluz-logo.png', 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(13, 2, 'habilitar_qr', 1, 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(14, 2, 'habilitar_transferencia', 1, 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(15, 2, 'habilitar_contraentrega', 1, 'general', '2026-10-23 10:30:47', '2026-10-23 10:30:47');

-- --------------------------------------------------------
-- Volcado de datos para la tabla `movimientos_inventario`
-- --------------------------------------------------------
INSERT INTO `movimientos_inventario` (`id`, `tienda_id`, `producto_id`, `variante_id`, `user_id`, `pedido_id`, `tipo`, `cantidad`, `stock_anterior`, `stock_posterior`, `motivo`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, 1, NULL, 'entrada', 35, 0, 35, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:47', '2026-10-23 10:30:47'),
(2, 2, 2, NULL, 1, NULL, 'entrada', 40, 0, 40, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(3, 2, 3, NULL, 1, NULL, 'entrada', 25, 0, 25, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(4, 2, 4, NULL, 1, NULL, 'entrada', 30, 0, 30, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(5, 2, 5, NULL, 1, NULL, 'entrada', 28, 0, 28, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(6, 2, 6, NULL, 1, NULL, 'entrada', 20, 0, 20, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(7, 2, 7, NULL, 1, NULL, 'entrada', 45, 0, 45, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(8, 2, 8, NULL, 1, NULL, 'entrada', 38, 0, 38, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(9, 2, 9, NULL, 1, NULL, 'entrada', 50, 0, 50, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(10, 2, 10, NULL, 1, NULL, 'entrada', 60, 0, 60, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48'),
(11, 2, 11, NULL, 1, NULL, 'entrada', 45, 0, 45, 'Inventario inicial Farmaluz Preparados Magistrales', '2026-10-23 10:30:48', '2026-10-23 10:30:48');

-- --------------------------------------------------------
-- Estructura para tabla de framework `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_22_000001_create_catalogo_and_usuarios_tables', 1),
(5, '2026_10_22_000002_create_pedidos_pagos_and_inventario_tables', 1),
(6, '2026_10_23_095407_create_tiendas_and_multitienda_support_tables', 1);

-- --------------------------------------------------------
-- Estructura para tabla de framework `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura para tabla de framework `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura para tabla de framework `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura para tabla de framework `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura para tabla de framework `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura para tabla de framework `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estructura para tabla de framework `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
