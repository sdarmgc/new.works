-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Apr 24, 2026 at 05:57 AM
-- Server version: 11.4.10-MariaDB-ubu2404
-- PHP Version: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `listen13_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

-- CREATE TABLE IF NOT EXISTS `permissions` (
--   `id` int(10) UNSIGNED NOT NULL,
--   `name` varchar(191) NOT NULL,
--   `guard_name` varchar(191) NOT NULL,
--   `created_at` timestamp NULL DEFAULT NULL,
--   `updated_at` timestamp NULL DEFAULT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view backend', 'web', '2019-03-17 00:15:08', '2019-03-17 00:15:08'),
(2, 'access translation', 'web', '2019-03-17 00:19:02', '2019-03-17 00:19:02');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

-- CREATE TABLE IF NOT EXISTS `roles` (
--   `id` int(10) UNSIGNED NOT NULL,
--   `name` varchar(191) NOT NULL,
--   `guard_name` varchar(191) NOT NULL,
--   `created_at` timestamp NULL DEFAULT NULL,
--   `updated_at` timestamp NULL DEFAULT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'web', '2019-03-17 00:15:08', '2019-03-17 00:15:08'),
(2, 'executive', 'web', '2019-03-17 00:15:08', '2019-03-17 00:15:08'),
(3, 'user', 'web', '2019-03-17 00:15:08', '2019-03-17 00:15:08'),
(4, 'translator', 'web', '2019-03-17 00:19:02', '2019-03-17 00:19:02'),
(5, 'pab', 'web', '2020-10-25 09:58:05', '2020-10-25 09:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

-- CREATE TABLE IF NOT EXISTS `model_has_permissions` (
--   `permission_id` int(10) UNSIGNED NOT NULL,
--   `model_type` varchar(191) NOT NULL,
--   `model_id` bigint(20) UNSIGNED NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 208);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

-- CREATE TABLE IF NOT EXISTS `model_has_roles` (
--   `role_id` int(10) UNSIGNED NOT NULL,
--   `model_type` varchar(191) NOT NULL,
--   `model_id` bigint(20) UNSIGNED NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 34),
(3, 'App\\Models\\User', 35),
(4, 'App\\Models\\User', 35),
(4, 'App\\Models\\User', 36),
(4, 'App\\Models\\User', 40),
(4, 'App\\Models\\User', 41),
(4, 'App\\Models\\User', 51),
(4, 'App\\Models\\User', 54),
(5, 'App\\Models\\User', 54),
(4, 'App\\Models\\User', 59),
(4, 'App\\Models\\User', 60),
(4, 'App\\Models\\User', 61),
(4, 'App\\Models\\User', 62),
(1, 'App\\Models\\User', 65),
(4, 'App\\Models\\User', 69),
(4, 'App\\Models\\User', 70),
(3, 'App\\Models\\User', 71),
(4, 'App\\Models\\User', 71),
(3, 'App\\Models\\User', 72),
(4, 'App\\Models\\User', 72),
(3, 'App\\Models\\User', 73),
(4, 'App\\Models\\User', 73),
(3, 'App\\Models\\User', 74),
(4, 'App\\Models\\User', 74),
(3, 'App\\Models\\User', 75),
(4, 'App\\Models\\User', 75),
(3, 'App\\Models\\User', 78),
(4, 'App\\Models\\User', 78),
(3, 'App\\Models\\User', 79),
(4, 'App\\Models\\User', 79),
(3, 'App\\Models\\User', 80),
(4, 'App\\Models\\User', 80),
(3, 'App\\Models\\User', 81),
(4, 'App\\Models\\User', 81),
(3, 'App\\Models\\User', 82),
(4, 'App\\Models\\User', 82),
(3, 'App\\Models\\User', 86),
(4, 'App\\Models\\User', 86),
(3, 'App\\Models\\User', 91),
(4, 'App\\Models\\User', 91),
(3, 'App\\Models\\User', 92),
(3, 'App\\Models\\User', 94),
(3, 'App\\Models\\User', 95),
(4, 'App\\Models\\User', 95),
(3, 'App\\Models\\User', 96),
(4, 'App\\Models\\User', 96),
(3, 'App\\Models\\User', 98),
(4, 'App\\Models\\User', 98),
(3, 'App\\Models\\User', 101),
(4, 'App\\Models\\User', 101),
(3, 'App\\Models\\User', 103),
(4, 'App\\Models\\User', 103),
(3, 'App\\Models\\User', 104),
(3, 'App\\Models\\User', 105),
(4, 'App\\Models\\User', 105),
(3, 'App\\Models\\User', 106),
(4, 'App\\Models\\User', 106),
(3, 'App\\Models\\User', 107),
(3, 'App\\Models\\User', 110),
(3, 'App\\Models\\User', 111),
(4, 'App\\Models\\User', 112),
(3, 'App\\Models\\User', 114),
(4, 'App\\Models\\User', 114),
(3, 'App\\Models\\User', 115),
(3, 'App\\Models\\User', 117),
(3, 'App\\Models\\User', 118),
(4, 'App\\Models\\User', 118),
(3, 'App\\Models\\User', 120),
(3, 'App\\Models\\User', 123),
(3, 'App\\Models\\User', 130),
(4, 'App\\Models\\User', 130),
(3, 'App\\Models\\User', 134),
(3, 'App\\Models\\User', 137),
(3, 'App\\Models\\User', 139),
(3, 'App\\Models\\User', 140),
(3, 'App\\Models\\User', 141),
(3, 'App\\Models\\User', 145),
(4, 'App\\Models\\User', 145),
(3, 'App\\Models\\User', 151),
(3, 'App\\Models\\User', 153),
(3, 'App\\Models\\User', 157),
(3, 'App\\Models\\User', 158),
(3, 'App\\Models\\User', 163),
(3, 'App\\Models\\User', 164),
(3, 'App\\Models\\User', 166),
(3, 'App\\Models\\User', 168),
(3, 'App\\Models\\User', 169),
(3, 'App\\Models\\User', 170),
(4, 'App\\Models\\User', 170),
(3, 'App\\Models\\User', 172),
(4, 'App\\Models\\User', 175),
(3, 'App\\Models\\User', 181),
(3, 'App\\Models\\User', 183),
(4, 'App\\Models\\User', 183),
(3, 'App\\Models\\User', 185),
(3, 'App\\Models\\User', 187),
(3, 'App\\Models\\User', 188),
(4, 'App\\Models\\User', 189),
(5, 'App\\Models\\User', 189),
(3, 'App\\Models\\User', 190),
(3, 'App\\Models\\User', 191),
(3, 'App\\Models\\User', 192),
(3, 'App\\Models\\User', 193),
(3, 'App\\Models\\User', 195),
(3, 'App\\Models\\User', 200),
(3, 'App\\Models\\User', 202),
(3, 'App\\Models\\User', 203),
(4, 'App\\Models\\User', 203),
(3, 'App\\Models\\User', 205),
(4, 'App\\Models\\User', 205),
(3, 'App\\Models\\User', 207),
(1, 'App\\Models\\User', 208),
(3, 'App\\Models\\User', 208),
(4, 'App\\Models\\User', 208),
(5, 'App\\Models\\User', 208),
(3, 'App\\Models\\User', 210),
(4, 'App\\Models\\User', 210),
(3, 'App\\Models\\User', 211),
(4, 'App\\Models\\User', 211),
(3, 'App\\Models\\User', 212),
(4, 'App\\Models\\User', 212),
(3, 'App\\Models\\User', 213),
(4, 'App\\Models\\User', 213),
(3, 'App\\Models\\User', 216),
(3, 'App\\Models\\User', 219),
(3, 'App\\Models\\User', 220),
(3, 'App\\Models\\User', 221),
(3, 'App\\Models\\User', 222),
(4, 'App\\Models\\User', 222),
(3, 'App\\Models\\User', 223),
(3, 'App\\Models\\User', 226),
(4, 'App\\Models\\User', 226),
(3, 'App\\Models\\User', 227),
(3, 'App\\Models\\User', 228),
(3, 'App\\Models\\User', 229),
(4, 'App\\Models\\User', 229),
(3, 'App\\Models\\User', 230),
(3, 'App\\Models\\User', 232),
(3, 'App\\Models\\User', 234),
(4, 'App\\Models\\User', 234),
(3, 'App\\Models\\User', 235),
(4, 'App\\Models\\User', 235),
(3, 'App\\Models\\User', 237),
(4, 'App\\Models\\User', 237),
(5, 'App\\Models\\User', 237),
(3, 'App\\Models\\User', 238),
(4, 'App\\Models\\User', 238),
(3, 'App\\Models\\User', 239),
(4, 'App\\Models\\User', 239),
(3, 'App\\Models\\User', 240),
(3, 'App\\Models\\User', 242),
(3, 'App\\Models\\User', 243),
(3, 'App\\Models\\User', 244),
(4, 'App\\Models\\User', 244),
(3, 'App\\Models\\User', 245),
(4, 'App\\Models\\User', 245),
(3, 'App\\Models\\User', 247),
(4, 'App\\Models\\User', 247),
(3, 'App\\Models\\User', 248),
(3, 'App\\Models\\User', 249),
(4, 'App\\Models\\User', 249),
(3, 'App\\Models\\User', 251),
(3, 'App\\Models\\User', 252),
(3, 'App\\Models\\User', 253),
(4, 'App\\Models\\User', 253),
(3, 'App\\Models\\User', 254),
(3, 'App\\Models\\User', 255),
(4, 'App\\Models\\User', 255),
(3, 'App\\Models\\User', 256),
(4, 'App\\Models\\User', 256),
(3, 'App\\Models\\User', 269),
(3, 'App\\Models\\User', 270),
(3, 'App\\Models\\User', 278),
(3, 'App\\Models\\User', 279),
(3, 'App\\Models\\User', 280),
(3, 'App\\Models\\User', 282),
(3, 'App\\Models\\User', 283),
(4, 'App\\Models\\User', 283),
(3, 'App\\Models\\User', 284),
(4, 'App\\Models\\User', 284),
(3, 'App\\Models\\User', 285),
(3, 'App\\Models\\User', 286),
(3, 'App\\Models\\User', 287),
(3, 'App\\Models\\User', 288),
(3, 'App\\Models\\User', 289),
(3, 'App\\Models\\User', 290),
(3, 'App\\Models\\User', 291),
(3, 'App\\Models\\User', 292),
(3, 'App\\Models\\User', 293),
(4, 'App\\Models\\User', 293),
(3, 'App\\Models\\User', 294),
(3, 'App\\Models\\User', 295),
(4, 'App\\Models\\User', 295),
(3, 'App\\Models\\User', 296),
(3, 'App\\Models\\User', 297),
(4, 'App\\Models\\User', 297),
(3, 'App\\Models\\User', 298),
(4, 'App\\Models\\User', 298),
(3, 'App\\Models\\User', 299),
(3, 'App\\Models\\User', 300),
(3, 'App\\Models\\User', 301),
(3, 'App\\Models\\User', 302),
(3, 'App\\Models\\User', 303),
(4, 'App\\Models\\User', 303),
(3, 'App\\Models\\User', 304),
(3, 'App\\Models\\User', 305),
(4, 'App\\Models\\User', 305),
(3, 'App\\Models\\User', 307),
(3, 'App\\Models\\User', 308),
(3, 'App\\Models\\User', 309),
(3, 'App\\Models\\User', 310),
(3, 'App\\Models\\User', 311),
(3, 'App\\Models\\User', 312),
(3, 'App\\Models\\User', 313),
(3, 'App\\Models\\User', 314),
(3, 'App\\Models\\User', 315),
(3, 'App\\Models\\User', 316),
(3, 'App\\Models\\User', 317),
(3, 'App\\Models\\User', 318),
(3, 'App\\Models\\User', 319),
(3, 'App\\Models\\User', 320),
(3, 'App\\Models\\User', 321),
(3, 'App\\Models\\User', 322),
(3, 'App\\Models\\User', 323),
(3, 'App\\Models\\User', 324),
(3, 'App\\Models\\User', 325),
(3, 'App\\Models\\User', 326),
(3, 'App\\Models\\User', 327),
(3, 'App\\Models\\User', 328),
(3, 'App\\Models\\User', 329),
(3, 'App\\Models\\User', 330),
(3, 'App\\Models\\User', 331),
(3, 'App\\Models\\User', 332),
(3, 'App\\Models\\User', 333),
(3, 'App\\Models\\User', 334),
(3, 'App\\Models\\User', 335),
(3, 'App\\Models\\User', 336),
(3, 'App\\Models\\User', 337),
(3, 'App\\Models\\User', 338),
(3, 'App\\Models\\User', 339),
(3, 'App\\Models\\User', 340),
(3, 'App\\Models\\User', 341),
(3, 'App\\Models\\User', 342),
(3, 'App\\Models\\User', 343),
(3, 'App\\Models\\User', 344),
(3, 'App\\Models\\User', 345),
(3, 'App\\Models\\User', 346),
(3, 'App\\Models\\User', 347),
(3, 'App\\Models\\User', 348),
(3, 'App\\Models\\User', 349),
(3, 'App\\Models\\User', 350),
(3, 'App\\Models\\User', 351),
(3, 'App\\Models\\User', 352),
(3, 'App\\Models\\User', 353),
(3, 'App\\Models\\User', 354),
(3, 'App\\Models\\User', 355),
(3, 'App\\Models\\User', 356),
(3, 'App\\Models\\User', 357),
(3, 'App\\Models\\User', 358),
(3, 'App\\Models\\User', 359),
(3, 'App\\Models\\User', 360),
(3, 'App\\Models\\User', 361),
(3, 'App\\Models\\User', 362),
(3, 'App\\Models\\User', 363),
(3, 'App\\Models\\User', 364),
(3, 'App\\Models\\User', 365),
(3, 'App\\Models\\User', 366),
(3, 'App\\Models\\User', 367),
(3, 'App\\Models\\User', 368),
(3, 'App\\Models\\User', 369),
(3, 'App\\Models\\User', 370),
(3, 'App\\Models\\User', 371),
(3, 'App\\Models\\User', 372),
(3, 'App\\Models\\User', 373),
(3, 'App\\Models\\User', 374),
(3, 'App\\Models\\User', 375),
(3, 'App\\Models\\User', 376),
(3, 'App\\Models\\User', 377),
(3, 'App\\Models\\User', 378),
(3, 'App\\Models\\User', 379),
(3, 'App\\Models\\User', 380),
(3, 'App\\Models\\User', 381),
(3, 'App\\Models\\User', 382),
(3, 'App\\Models\\User', 383),
(3, 'App\\Models\\User', 384),
(3, 'App\\Models\\User', 385),
(3, 'App\\Models\\User', 386),
(3, 'App\\Models\\User', 387),
(3, 'App\\Models\\User', 388),
(3, 'App\\Models\\User', 389),
(3, 'App\\Models\\User', 390),
(3, 'App\\Models\\User', 391),
(3, 'App\\Models\\User', 392),
(3, 'App\\Models\\User', 393),
(4, 'App\\Models\\User', 393),
(5, 'App\\Models\\User', 393),
(3, 'App\\Models\\User', 394),
(3, 'App\\Models\\User', 395),
(4, 'App\\Models\\User', 395),
(3, 'App\\Models\\User', 396),
(3, 'App\\Models\\User', 397),
(3, 'App\\Models\\User', 398),
(3, 'App\\Models\\User', 399),
(3, 'App\\Models\\User', 400),
(3, 'App\\Models\\User', 401),
(3, 'App\\Models\\User', 402),
(3, 'App\\Models\\User', 403),
(3, 'App\\Models\\User', 404),
(3, 'App\\Models\\User', 405),
(3, 'App\\Models\\User', 406),
(3, 'App\\Models\\User', 407),
(3, 'App\\Models\\User', 408),
(3, 'App\\Models\\User', 409),
(3, 'App\\Models\\User', 410),
(3, 'App\\Models\\User', 411),
(3, 'App\\Models\\User', 412),
(3, 'App\\Models\\User', 413),
(3, 'App\\Models\\User', 414),
(3, 'App\\Models\\User', 415),
(3, 'App\\Models\\User', 416),
(3, 'App\\Models\\User', 417),
(3, 'App\\Models\\User', 418),
(3, 'App\\Models\\User', 419),
(3, 'App\\Models\\User', 420),
(3, 'App\\Models\\User', 421),
(3, 'App\\Models\\User', 422),
(3, 'App\\Models\\User', 423),
(3, 'App\\Models\\User', 424),
(3, 'App\\Models\\User', 425),
(3, 'App\\Models\\User', 426),
(3, 'App\\Models\\User', 427),
(3, 'App\\Models\\User', 428),
(3, 'App\\Models\\User', 429),
(3, 'App\\Models\\User', 430),
(3, 'App\\Models\\User', 431),
(3, 'App\\Models\\User', 432),
(3, 'App\\Models\\User', 433),
(3, 'App\\Models\\User', 434),
(3, 'App\\Models\\User', 435),
(3, 'App\\Models\\User', 436),
(3, 'App\\Models\\User', 437),
(3, 'App\\Models\\User', 438),
(3, 'App\\Models\\User', 439),
(3, 'App\\Models\\User', 440),
(3, 'App\\Models\\User', 441),
(3, 'App\\Models\\User', 442),
(3, 'App\\Models\\User', 443),
(3, 'App\\Models\\User', 444),
(3, 'App\\Models\\User', 445),
(3, 'App\\Models\\User', 446),
(3, 'App\\Models\\User', 447),
(3, 'App\\Models\\User', 448),
(3, 'App\\Models\\User', 449),
(3, 'App\\Models\\User', 450),
(3, 'App\\Models\\User', 451),
(3, 'App\\Models\\User', 452),
(3, 'App\\Models\\User', 453),
(3, 'App\\Models\\User', 454),
(3, 'App\\Models\\User', 455),
(3, 'App\\Models\\User', 456),
(3, 'App\\Models\\User', 457),
(3, 'App\\Models\\User', 458),
(3, 'App\\Models\\User', 459),
(3, 'App\\Models\\User', 460),
(3, 'App\\Models\\User', 461),
(3, 'App\\Models\\User', 462),
(3, 'App\\Models\\User', 463),
(3, 'App\\Models\\User', 464),
(3, 'App\\Models\\User', 465),
(3, 'App\\Models\\User', 466),
(3, 'App\\Models\\User', 467),
(3, 'App\\Models\\User', 468),
(3, 'App\\Models\\User', 469),
(3, 'App\\Models\\User', 470),
(3, 'App\\Models\\User', 471),
(3, 'App\\Models\\User', 472),
(3, 'App\\Models\\User', 473),
(3, 'App\\Models\\User', 474),
(3, 'App\\Models\\User', 475),
(3, 'App\\Models\\User', 476),
(3, 'App\\Models\\User', 477),
(3, 'App\\Models\\User', 478),
(3, 'App\\Models\\User', 479),
(3, 'App\\Models\\User', 480),
(3, 'App\\Models\\User', 481),
(3, 'App\\Models\\User', 482),
(3, 'App\\Models\\User', 483),
(3, 'App\\Models\\User', 484),
(3, 'App\\Models\\User', 485),
(3, 'App\\Models\\User', 486),
(3, 'App\\Models\\User', 487),
(3, 'App\\Models\\User', 488),
(3, 'App\\Models\\User', 489),
(3, 'App\\Models\\User', 490),
(3, 'App\\Models\\User', 491),
(3, 'App\\Models\\User', 492),
(3, 'App\\Models\\User', 493),
(3, 'App\\Models\\User', 494),
(3, 'App\\Models\\User', 495),
(3, 'App\\Models\\User', 496),
(3, 'App\\Models\\User', 497),
(3, 'App\\Models\\User', 498),
(3, 'App\\Models\\User', 499),
(3, 'App\\Models\\User', 500),
(3, 'App\\Models\\User', 501),
(3, 'App\\Models\\User', 502),
(3, 'App\\Models\\User', 503),
(3, 'App\\Models\\User', 504),
(3, 'App\\Models\\User', 505),
(3, 'App\\Models\\User', 506),
(3, 'App\\Models\\User', 507),
(3, 'App\\Models\\User', 508),
(3, 'App\\Models\\User', 509),
(3, 'App\\Models\\User', 510),
(3, 'App\\Models\\User', 511),
(3, 'App\\Models\\User', 512),
(3, 'App\\Models\\User', 513),
(3, 'App\\Models\\User', 514),
(3, 'App\\Models\\User', 515),
(3, 'App\\Models\\User', 516),
(3, 'App\\Models\\User', 517),
(3, 'App\\Models\\User', 518),
(3, 'App\\Models\\User', 519),
(3, 'App\\Models\\User', 520),
(3, 'App\\Models\\User', 521),
(3, 'App\\Models\\User', 522),
(3, 'App\\Models\\User', 523),
(3, 'App\\Models\\User', 524),
(3, 'App\\Models\\User', 525),
(3, 'App\\Models\\User', 526),
(3, 'App\\Models\\User', 527),
(3, 'App\\Models\\User', 528),
(3, 'App\\Models\\User', 529),
(3, 'App\\Models\\User', 530),
(3, 'App\\Models\\User', 531),
(3, 'App\\Models\\User', 532),
(3, 'App\\Models\\User', 533),
(3, 'App\\Models\\User', 534),
(3, 'App\\Models\\User', 535),
(3, 'App\\Models\\User', 536),
(3, 'App\\Models\\User', 537),
(3, 'App\\Models\\User', 538),
(3, 'App\\Models\\User', 539),
(3, 'App\\Models\\User', 540),
(3, 'App\\Models\\User', 541),
(3, 'App\\Models\\User', 542),
(3, 'App\\Models\\User', 543),
(3, 'App\\Models\\User', 544),
(3, 'App\\Models\\User', 545),
(3, 'App\\Models\\User', 546),
(3, 'App\\Models\\User', 547),
(3, 'App\\Models\\User', 548),
(3, 'App\\Models\\User', 549),
(3, 'App\\Models\\User', 550),
(3, 'App\\Models\\User', 551),
(3, 'App\\Models\\User', 552),
(3, 'App\\Models\\User', 553),
(3, 'App\\Models\\User', 554),
(3, 'App\\Models\\User', 555),
(3, 'App\\Models\\User', 556),
(3, 'App\\Models\\User', 557),
(3, 'App\\Models\\User', 558),
(3, 'App\\Models\\User', 559),
(3, 'App\\Models\\User', 560),
(3, 'App\\Models\\User', 561),
(3, 'App\\Models\\User', 562),
(3, 'App\\Models\\User', 563),
(3, 'App\\Models\\User', 564),
(3, 'App\\Models\\User', 565),
(3, 'App\\Models\\User', 566),
(3, 'App\\Models\\User', 567),
(3, 'App\\Models\\User', 568),
(3, 'App\\Models\\User', 569),
(3, 'App\\Models\\User', 570),
(3, 'App\\Models\\User', 571),
(3, 'App\\Models\\User', 572),
(3, 'App\\Models\\User', 573),
(3, 'App\\Models\\User', 574),
(3, 'App\\Models\\User', 575),
(3, 'App\\Models\\User', 576),
(3, 'App\\Models\\User', 577),
(3, 'App\\Models\\User', 578),
(3, 'App\\Models\\User', 579),
(3, 'App\\Models\\User', 580),
(3, 'App\\Models\\User', 581),
(3, 'App\\Models\\User', 582),
(3, 'App\\Models\\User', 583),
(3, 'App\\Models\\User', 584),
(3, 'App\\Models\\User', 585),
(3, 'App\\Models\\User', 586),
(3, 'App\\Models\\User', 587),
(3, 'App\\Models\\User', 588),
(3, 'App\\Models\\User', 589),
(3, 'App\\Models\\User', 590),
(3, 'App\\Models\\User', 591),
(3, 'App\\Models\\User', 592),
(3, 'App\\Models\\User', 593),
(3, 'App\\Models\\User', 594),
(3, 'App\\Models\\User', 595),
(3, 'App\\Models\\User', 596),
(3, 'App\\Models\\User', 598),
(1, 'App\\Models\\User', 599),
(3, 'App\\Models\\User', 599),
(4, 'App\\Models\\User', 599),
(5, 'App\\Models\\User', 599),
(3, 'App\\Models\\User', 600),
(4, 'App\\Models\\User', 600),
(3, 'App\\Models\\User', 601),
(3, 'App\\Models\\User', 602),
(4, 'App\\Models\\User', 602),
(3, 'App\\Models\\User', 603),
(3, 'App\\Models\\User', 605),
(4, 'App\\Models\\User', 605),
(3, 'App\\Models\\User', 606),
(3, 'App\\Models\\User', 607),
(4, 'App\\Models\\User', 607),
(3, 'App\\Models\\User', 608),
(4, 'App\\Models\\User', 608),
(3, 'App\\Models\\User', 609),
(4, 'App\\Models\\User', 609),
(3, 'App\\Models\\User', 610),
(3, 'App\\Models\\User', 611),
(3, 'App\\Models\\User', 612),
(3, 'App\\Models\\User', 613),
(3, 'App\\Models\\User', 614),
(3, 'App\\Models\\User', 615),
(3, 'App\\Models\\User', 616),
(3, 'App\\Models\\User', 617),
(3, 'App\\Models\\User', 618),
(3, 'App\\Models\\User', 619),
(4, 'App\\Models\\User', 619),
(3, 'App\\Models\\User', 620),
(3, 'App\\Models\\User', 621),
(3, 'App\\Models\\User', 622),
(3, 'App\\Models\\User', 623),
(3, 'App\\Models\\User', 625),
(3, 'App\\Models\\User', 626),
(3, 'App\\Models\\User', 627),
(4, 'App\\Models\\User', 627),
(3, 'App\\Models\\User', 628),
(3, 'App\\Models\\User', 629),
(3, 'App\\Models\\User', 630),
(3, 'App\\Models\\User', 631),
(3, 'App\\Models\\User', 632),
(4, 'App\\Models\\User', 632),
(3, 'App\\Models\\User', 634),
(3, 'App\\Models\\User', 635),
(3, 'App\\Models\\User', 636),
(4, 'App\\Models\\User', 636),
(3, 'App\\Models\\User', 637),
(3, 'App\\Models\\User', 638),
(3, 'App\\Models\\User', 639),
(3, 'App\\Models\\User', 640),
(3, 'App\\Models\\User', 641),
(3, 'App\\Models\\User', 642),
(3, 'App\\Models\\User', 643),
(3, 'App\\Models\\User', 644),
(3, 'App\\Models\\User', 645),
(3, 'App\\Models\\User', 646),
(3, 'App\\Models\\User', 647),
(3, 'App\\Models\\User', 648);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

-- CREATE TABLE IF NOT EXISTS `role_has_permissions` (
--   `permission_id` int(10) UNSIGNED NOT NULL,
--   `role_id` int(10) UNSIGNED NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 4),
(2, 5);

-- --
-- -- Indexes for dumped tables
-- --

-- --
-- -- Indexes for table `model_has_permissions`
-- --
-- ALTER TABLE `model_has_permissions`
--   ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
--   ADD KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`);

-- --
-- -- Indexes for table `model_has_roles`
-- --
-- ALTER TABLE `model_has_roles`
--   ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
--   ADD KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`);

-- --
-- -- Indexes for table `permissions`
-- --
-- ALTER TABLE `permissions`
--   ADD PRIMARY KEY (`id`);

-- --
-- -- Indexes for table `roles`
-- --
-- ALTER TABLE `roles`
--   ADD PRIMARY KEY (`id`),
--   ADD KEY `roles_name_index` (`name`);

-- --
-- -- Indexes for table `role_has_permissions`
-- --
-- ALTER TABLE `role_has_permissions`
--   ADD PRIMARY KEY (`permission_id`,`role_id`),
--   ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

-- --
-- -- AUTO_INCREMENT for dumped tables
-- --

-- --
-- -- AUTO_INCREMENT for table `permissions`
-- --
-- ALTER TABLE `permissions`
--   MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --
-- -- AUTO_INCREMENT for table `roles`
-- --
-- ALTER TABLE `roles`
--   MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@COLLATION_CONNECTION */;
