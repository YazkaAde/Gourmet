-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2025 at 12:18 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gourmet`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_payment_methods`
--

CREATE TABLE `bank_payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('bank_transfer','e_wallet') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_payment_methods`
--

INSERT INTO `bank_payment_methods` (`id`, `bank_name`, `account_number`, `account_holder_name`, `type`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Dana', '12345678', 'Gourmet', 'e_wallet', 1, 'Nama bank yang dituju harus nama restoran, jika tidak maka itu fake account!!', '2025-10-09 07:33:03', '2025-10-09 07:34:58'),
(2, 'Bank Central Asia (BCA)', '17081945', 'Gourmet', 'bank_transfer', 1, 'Nama bank yang dituju harus nama restoran, jika tidak maka itu fake account!!', '2025-10-09 07:34:40', '2025-10-10 06:37:11'),
(3, 'Gopay', '12345678', 'Gourmet', 'e_wallet', 1, NULL, '2025-10-09 07:41:34', '2025-10-09 07:41:34'),
(4, 'Bank Negara Indonesia', '123456789', 'Gourmet', 'bank_transfer', 1, NULL, '2025-10-10 07:26:18', '2025-10-10 07:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `blacklists`
--

CREATE TABLE `blacklists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `banned_by` bigint UNSIGNED DEFAULT NULL,
  `banned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Drinks', 'drinks', '2025-09-15 21:31:57', '2025-09-15 21:31:57'),
(2, 'Dessert', 'dessert', '2025-09-15 21:32:06', '2025-09-15 21:32:06'),
(3, 'Main Course', 'main-course', '2025-09-15 21:32:16', '2025-09-15 21:32:16'),
(4, 'Extra', 'extra', '2025-10-07 02:35:56', '2025-10-07 02:35:56');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `order_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `slug`, `name`, `category_id`, `description`, `price`, `image_url`, `status`, `order_count`, `created_at`, `updated_at`) VALUES
(1, 'rendang', 'Rendang', 3, 'Sepotong daging sapi dan seporsi nasi', 25000.00, 'menu_images/mLC3qhTWjkZ5M9VhxaRwJzJiQ2BImb9UGIJIE4XJ.jpg', 'available', 0, '2025-09-15 21:33:45', '2025-10-19 05:16:00'),
(2, 'strawberry-cake', 'Strawberry Cake', 2, 'Sepotong kue strawberry', 15000.00, 'menu_images/6nTKsCd1GW59My6PZEXIgP0CTMolLdhJU7lWbhs3.jpg', 'available', 0, '2025-09-15 21:34:10', '2025-09-15 21:34:10'),
(3, 'americano', 'Americano', 1, 'Segelas Americano ice/hot', 15000.00, 'menu_images/qFq1QpCiMkfliDB3gBWTBwtPc8fwqeLTvqH9cTQB.jpg', 'unavailable', 0, '2025-09-15 21:34:52', '2025-10-23 03:14:13'),
(4, 'lontong-opor', 'Lontong Opor', 3, 'Lontong dan satu opor ayam', 25000.00, 'menu_images/yxOuZoiVM5nJImHZt0lzW5uYf8BPkxr3J0EuSYce.jpg', 'available', 0, '2025-10-07 02:12:00', '2025-10-07 02:12:00'),
(5, 'nasi-goreng', 'Nasi Goreng', 3, 'Seporsi nasi goreng', 20000.00, 'menu_images/jCV5hxQdflvPXMXQhcoVV2EOqVe1ZdiZg1QAODtv.jpg', 'available', 0, '2025-10-07 02:13:02', '2025-10-07 02:13:02'),
(6, 'kue-pukis', 'Kue Pukis', 2, 'Seporsi kue pukis', 12000.00, 'menu_images/vU5xb18GEzFxmqjJr6goO7JwP9sJHOWvun2peJPX.jpg', 'unavailable', 0, '2025-10-07 02:19:24', '2025-10-23 01:45:19'),
(7, 'pisang-coklat-piscok', 'Pisang Coklat (Piscok)', 2, 'Seporsi Pisang coklat', 12000.00, 'menu_images/K1I6ZpvdPIjZ7Kr5G6nSReahoAyg73WFzqLe9D5t.jpg', 'available', 0, '2025-10-07 02:20:20', '2025-10-07 02:21:03'),
(8, 'es-pisang-ijo', 'Es Pisang Ijo', 2, 'Seporsi es pisang ijo', 20000.00, 'menu_images/YedRj4F49GmXMR3pJMWuy2CWb6k9JvTKYzv5YrBS.jpg', 'available', 0, '2025-10-07 02:20:51', '2025-10-07 02:20:51'),
(9, 'teateh', 'Tea/teh', 1, 'Segelas teh ice/hot', 10000.00, 'menu_images/nCzs04U7FT1oQj40yO9cOqOBqzP8zO1mVsizZgQw.jpg', 'available', 0, '2025-10-07 02:25:54', '2025-10-07 02:27:23'),
(10, 'lemon-teateh', 'Lemon Tea/teh', 1, 'Segelas teh lemon ice/hot', 12000.00, 'menu_images/Q8UYvKBvEaHZIIghItdRBDzx9sSuGL10CYOsoqwh.jpg', 'available', 0, '2025-10-07 02:26:48', '2025-10-07 02:27:38'),
(11, 'matcha-latte', 'Matcha Latte', 1, 'Segelas matcha latte', 15000.00, 'menu_images/CMsBWcE6gEQ7DwnN8UYNSwwAkvRJw7Z0QKW7oI6I.jpg', 'available', 0, '2025-10-07 02:31:44', '2025-10-07 02:31:44'),
(12, 'dumpling', 'Dumpling', 3, 'Dumpling ayam/sapi', 20000.00, 'menu_images/lRUB86NOQdXAVYYYY4DrV9q7l3aGYHgUNjLYSQPf.jpg', 'available', 0, '2025-10-07 02:32:36', '2025-10-07 06:41:29'),
(13, 'sate-ayam', 'Sate Ayam', 3, 'Seporsi sate ayam dan lontong', 20000.00, 'menu_images/7YEZfCzDWvyX6j00036Tk1CUmBR5TlB6Nj3SvyJm.jpg', 'available', 0, '2025-10-07 02:37:00', '2025-10-07 02:37:00'),
(14, 'sop-buntut', 'Sop Buntut', 3, 'Seporsi sop buntut sapi dan nasi', 40000.00, 'menu_images/VusxzBeAp1Wtb5kx9kYS6V7FA5J7BUVCEwXCFSuc.jpg', 'unavailable', 0, '2025-10-07 02:37:51', '2025-10-21 06:22:59'),
(16, 'nasi-putih', 'Nasi Putih', 4, 'Seporsi nasi putih', 5000.00, 'menu_images/ho7DerRpmYjrw1ffgYiCHK9m5om60PTCSte7hoJw.jpg', 'unavailable', 0, '2025-10-17 01:34:12', '2025-10-22 08:44:13'),
(17, 'steak', 'Steak', 3, 'Steak', 100000.00, 'menu_images/9S40Go6mKGtFnif2m6XuPPZqG0chaRYFX1OErkCF.jpg', 'available', 0, '2025-10-23 02:58:21', '2025-10-23 02:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_06_075913_create_categories_table', 1),
(5, '2025_08_01_000000_create_number_tables', 1),
(6, '2025_08_01_064836_create_menus_table', 1),
(7, '2025_08_01_064848_create_reservations_table', 1),
(8, '2025_08_01_064901_create_orders_table', 1),
(9, '2025_08_01_064910_create_order_items_table', 1),
(10, '2025_08_01_064922_create_payments_table', 1),
(11, '2025_08_13_075914_create_blacklists_table', 1),
(12, '2025_08_15_042738_create_carts_table', 1),
(13, '2025_08_21_065557_add_order_id_to_carts_table', 1),
(14, '2025_08_28_040433_create_reviews_table', 1),
(15, '2025_09_01_055146_modify_carts_unique_constraint', 1),
(16, '2025_09_04_025133_add_reservation_id_to_payments_table', 1),
(17, '2025_09_04_033939_make_order_id_nullable_in_payments_table', 1),
(18, '2025_09_05_000001_add_reservation_id_to_order_items_table', 1),
(19, '2025_09_05_000003_change_table_capacity_to_integer', 1),
(20, '2025_09_30_041847_add_end_time_to_reservations_table', 2),
(24, '2025_10_07_141945_add_payment_details_to_payments_table', 3),
(25, '2025_10_08_140648_create_bank_payment_methods_table', 3),
(26, '2025_10_09_142625_update_payment_method_enum_in_payments_table', 3),
(27, '2025_10_19_102138_add_status_to_menus_table', 4),
(28, '2025_08_02_000000_add_order_type_and_notes_to_orders_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `number_tables`
--

CREATE TABLE `number_tables` (
  `id` bigint UNSIGNED NOT NULL,
  `table_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_capacity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `number_tables`
--

INSERT INTO `number_tables` (`id`, `table_number`, `table_capacity`, `created_at`, `updated_at`) VALUES
(1, 'A1', 2, '2025-09-15 21:31:19', '2025-09-15 21:31:19'),
(2, 'A2', 2, '2025-09-15 21:31:28', '2025-09-15 21:31:28'),
(3, 'B1', 4, '2025-09-15 21:31:36', '2025-09-15 21:31:36'),
(4, 'C1', 8, '2025-09-15 21:31:45', '2025-09-15 21:31:45'),
(5, 'A3', 2, '2025-10-23 02:59:27', '2025-10-23 02:59:27');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `table_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_type` enum('dine_in','take_away') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine_in',
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `reservation_id`, `table_number`, `total_price`, `status`, `created_at`, `updated_at`, `order_type`, `notes`) VALUES
(1, 2, NULL, 'A1', 15000.00, 'completed', '2025-09-18 00:43:29', '2025-09-18 00:50:27', 'dine_in', NULL),
(2, 2, 13, 'C1', 270000.00, 'completed', '2025-09-21 20:41:14', '2025-09-21 21:44:01', 'dine_in', NULL),
(3, 2, 14, 'B1', 60000.00, 'completed', '2025-09-22 00:44:50', '2025-09-22 00:48:17', 'dine_in', NULL),
(4, 2, 15, 'C1', 160000.00, 'cancelled', '2025-09-22 00:58:08', '2025-10-06 01:33:09', 'dine_in', NULL),
(5, 2, 16, 'C1', 180000.00, 'completed', '2025-09-22 19:07:49', '2025-09-26 00:53:07', 'dine_in', NULL),
(6, 2, 17, 'B1', 160000.00, 'completed', '2025-09-26 00:56:10', '2025-09-26 01:01:31', 'dine_in', NULL),
(7, 2, 18, 'B1', 60000.00, 'cancelled', '2025-10-03 07:09:34', '2025-10-06 01:38:14', 'dine_in', NULL),
(8, 2, 19, 'B1', 220000.00, 'completed', '2025-10-06 01:36:29', '2025-10-06 01:40:08', 'dine_in', NULL),
(9, 4, 20, 'B1', 160000.00, 'completed', '2025-10-06 01:44:55', '2025-10-06 01:46:01', 'dine_in', NULL),
(10, 4, NULL, 'A1', 50000.00, 'completed', '2025-10-06 06:23:55', '2025-10-06 06:24:20', 'dine_in', NULL),
(11, 5, 22, 'B1', 160000.00, 'completed', '2025-10-06 07:34:13', '2025-10-06 07:41:37', 'dine_in', NULL),
(12, 5, 21, 'B1', 120000.00, 'completed', '2025-10-07 02:42:30', '2025-10-07 02:43:43', 'dine_in', NULL),
(13, 5, NULL, 'A1', 80000.00, 'completed', '2025-10-07 02:44:44', '2025-10-07 02:45:01', 'dine_in', NULL),
(14, 5, NULL, 'A1', 90000.00, 'completed', '2025-10-07 06:06:37', '2025-10-07 06:06:50', 'dine_in', NULL),
(15, 5, NULL, 'A1', 117000.00, 'completed', '2025-10-07 06:51:15', '2025-10-07 06:51:45', 'dine_in', NULL),
(16, 5, NULL, 'A1', 48000.00, 'completed', '2025-10-07 07:42:13', '2025-10-07 07:42:28', 'dine_in', NULL),
(17, 5, NULL, 'A2', 55000.00, 'completed', '2025-10-08 02:52:25', '2025-10-08 02:52:41', 'dine_in', NULL),
(18, 5, 23, 'B1', 180000.00, 'completed', '2025-10-13 05:41:20', '2025-10-13 05:43:20', 'dine_in', NULL),
(19, 5, 24, 'A1', 134000.00, 'completed', '2025-10-13 05:48:48', '2025-10-13 07:15:40', 'dine_in', NULL),
(20, 5, 25, 'B1', 140000.00, 'completed', '2025-10-13 15:12:21', '2025-10-14 04:19:18', 'dine_in', NULL),
(21, 5, NULL, 'A1', 40000.00, 'completed', '2025-10-14 02:31:16', '2025-10-14 04:07:23', 'dine_in', NULL),
(22, 4, NULL, 'A1', 110000.00, 'completed', '2025-10-17 07:02:16', '2025-10-17 07:02:34', 'dine_in', NULL),
(23, 4, NULL, 'C1', 372000.00, 'completed', '2025-10-18 04:23:23', '2025-10-18 04:26:31', 'dine_in', NULL),
(24, 4, NULL, 'A2', 95000.00, 'completed', '2025-10-19 08:49:16', '2025-10-19 08:49:38', 'dine_in', NULL),
(25, 4, NULL, NULL, 195000.00, 'completed', '2025-10-20 01:55:32', '2025-10-20 02:00:12', 'take_away', NULL),
(26, 5, NULL, 'A2', 130000.00, 'completed', '2025-10-20 04:08:48', '2025-10-20 06:30:06', 'dine_in', NULL),
(27, 4, NULL, 'B1', 108000.00, 'completed', '2025-10-20 06:34:25', '2025-10-21 15:17:37', 'dine_in', NULL),
(28, 5, 27, 'C1', 152000.00, 'completed', '2025-10-22 08:07:18', '2025-10-22 08:35:49', 'dine_in', NULL),
(29, 4, NULL, NULL, 40000.00, 'completed', '2025-10-22 08:45:46', '2025-10-22 11:54:33', 'take_away', NULL),
(30, 4, NULL, NULL, 100000.00, 'completed', '2025-10-22 11:56:11', '2025-10-22 13:12:53', 'take_away', NULL),
(31, 4, NULL, NULL, 30000.00, 'completed', '2025-10-22 12:06:08', '2025-10-22 13:12:51', 'take_away', NULL),
(32, 7, NULL, 'A2', 30000.00, 'completed', '2025-10-23 03:02:44', '2025-10-23 03:06:17', 'dine_in', NULL),
(33, 7, 29, 'C1', 35000.00, 'completed', '2025-10-23 03:11:53', '2025-10-23 03:13:17', 'dine_in', NULL),
(34, 4, NULL, NULL, 30000.00, 'completed', '2025-12-04 08:04:53', '2025-12-04 08:07:10', 'take_away', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `reservation_id`, `quantity`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, NULL, 3, 25000.00, 75000.00, '2025-09-15 21:35:35', '2025-09-15 21:35:35'),
(2, NULL, 2, NULL, 3, 15000.00, 45000.00, '2025-09-15 21:35:35', '2025-09-15 21:35:35'),
(3, NULL, 1, NULL, 4, 25000.00, 100000.00, '2025-09-15 23:50:30', '2025-09-15 23:50:30'),
(4, NULL, 2, NULL, 3, 15000.00, 45000.00, '2025-09-15 23:50:30', '2025-09-15 23:50:30'),
(5, NULL, 3, NULL, 2, 15000.00, 30000.00, '2025-09-15 23:50:30', '2025-09-15 23:50:30'),
(6, NULL, 1, NULL, 4, 25000.00, 100000.00, '2025-09-16 00:55:11', '2025-09-16 00:55:11'),
(7, NULL, 2, NULL, 3, 15000.00, 45000.00, '2025-09-16 00:55:11', '2025-09-16 00:55:11'),
(8, NULL, 3, NULL, 2, 15000.00, 30000.00, '2025-09-16 00:55:11', '2025-09-16 00:55:11'),
(9, NULL, 1, NULL, 4, 25000.00, 100000.00, '2025-09-17 19:55:09', '2025-09-17 19:55:09'),
(10, NULL, 3, NULL, 4, 15000.00, 60000.00, '2025-09-17 19:55:09', '2025-09-17 19:55:09'),
(11, NULL, 1, NULL, 4, 25000.00, 100000.00, '2025-09-17 20:47:24', '2025-09-17 20:47:24'),
(12, NULL, 3, NULL, 4, 15000.00, 60000.00, '2025-09-17 20:47:24', '2025-09-17 20:47:24'),
(13, NULL, 1, NULL, 4, 25000.00, 100000.00, '2025-09-17 20:59:36', '2025-09-17 20:59:36'),
(14, NULL, 3, NULL, 4, 15000.00, 60000.00, '2025-09-17 20:59:36', '2025-09-17 20:59:36'),
(15, NULL, 1, NULL, 4, 25000.00, 100000.00, '2025-09-17 21:27:32', '2025-09-17 21:27:32'),
(16, NULL, 3, NULL, 4, 15000.00, 60000.00, '2025-09-17 21:27:32', '2025-09-17 21:27:32'),
(17, NULL, 2, 8, 2, 15000.00, 30000.00, '2025-09-17 23:15:02', '2025-09-17 23:15:02'),
(18, NULL, 1, 9, 4, 25000.00, 100000.00, '2025-09-17 23:47:14', '2025-09-17 23:47:14'),
(19, 1, 3, NULL, 1, 15000.00, 15000.00, '2025-09-18 00:43:29', '2025-09-18 00:43:29'),
(20, NULL, 3, 10, 4, 15000.00, 60000.00, '2025-09-18 23:39:49', '2025-09-18 23:39:49'),
(21, NULL, 3, 11, 4, 15000.00, 60000.00, '2025-09-19 00:05:38', '2025-09-19 00:05:38'),
(22, NULL, 3, 12, 7, 15000.00, 105000.00, '2025-09-21 19:40:57', '2025-09-21 19:40:57'),
(23, NULL, 2, 12, 5, 15000.00, 75000.00, '2025-09-21 19:40:57', '2025-09-21 19:40:57'),
(24, 2, 3, 13, 8, 15000.00, 120000.00, '2025-09-21 20:40:48', '2025-09-21 20:41:14'),
(25, 2, 2, 13, 5, 15000.00, 75000.00, '2025-09-21 20:40:48', '2025-09-21 20:41:14'),
(26, 2, 1, 13, 3, 25000.00, 75000.00, '2025-09-21 20:40:48', '2025-09-21 20:41:14'),
(27, 3, 3, 14, 4, 15000.00, 60000.00, '2025-09-22 00:43:48', '2025-09-22 00:44:50'),
(28, 4, 1, 15, 4, 25000.00, 100000.00, '2025-09-22 00:57:39', '2025-09-29 19:40:17'),
(29, 5, 1, 16, 3, 25000.00, 75000.00, '2025-09-23 00:37:32', '2025-09-26 00:30:31'),
(31, 5, 3, 16, 3, 15000.00, 45000.00, '2025-09-23 20:56:36', '2025-09-25 23:13:39'),
(34, 5, 2, 16, 4, 15000.00, 60000.00, '2025-09-24 19:26:13', '2025-09-26 00:30:37'),
(35, 4, 2, 15, 4, 15000.00, 60000.00, '2025-09-24 20:43:54', '2025-10-06 01:33:09'),
(37, 6, 1, 17, 4, 25000.00, 100000.00, '2025-09-26 00:55:13', '2025-09-26 00:56:10'),
(38, 6, 2, 17, 4, 15000.00, 60000.00, '2025-09-26 00:57:24', '2025-09-26 00:58:01'),
(39, 7, 3, 18, 4, 15000.00, 60000.00, '2025-10-03 07:06:53', '2025-10-03 07:09:34'),
(40, 8, 2, 19, 4, 15000.00, 60000.00, '2025-10-06 01:34:52', '2025-10-06 01:36:29'),
(41, 8, 3, 19, 4, 15000.00, 60000.00, '2025-10-06 01:34:52', '2025-10-06 01:36:29'),
(42, 8, 1, 19, 4, 25000.00, 100000.00, '2025-10-06 01:34:52', '2025-10-06 01:36:29'),
(43, 9, 2, 20, 2, 15000.00, 30000.00, '2025-10-06 01:44:03', '2025-10-06 01:44:55'),
(44, 9, 1, 20, 4, 25000.00, 100000.00, '2025-10-06 01:44:03', '2025-10-06 01:44:55'),
(45, 9, 3, 20, 2, 15000.00, 30000.00, '2025-10-06 01:44:03', '2025-10-06 01:44:55'),
(46, 10, 1, NULL, 2, 25000.00, 50000.00, '2025-10-06 06:23:55', '2025-10-06 06:23:55'),
(47, 12, 2, 21, 4, 15000.00, 60000.00, '2025-10-06 06:57:57', '2025-10-07 02:42:30'),
(48, 12, 3, 21, 4, 15000.00, 60000.00, '2025-10-06 06:57:57', '2025-10-07 02:42:30'),
(49, 11, 3, 22, 4, 15000.00, 60000.00, '2025-10-06 07:33:40', '2025-10-06 07:34:13'),
(50, 11, 1, 22, 4, 25000.00, 100000.00, '2025-10-06 07:33:40', '2025-10-06 07:34:13'),
(51, 13, 1, NULL, 2, 25000.00, 50000.00, '2025-10-07 02:44:44', '2025-10-07 02:44:44'),
(52, 13, 2, NULL, 2, 15000.00, 30000.00, '2025-10-07 02:44:44', '2025-10-07 02:44:44'),
(53, 14, 4, NULL, 2, 25000.00, 50000.00, '2025-10-07 06:06:37', '2025-10-07 06:06:37'),
(54, 14, 8, NULL, 2, 20000.00, 40000.00, '2025-10-07 06:06:37', '2025-10-07 06:06:37'),
(55, 15, 5, NULL, 2, 20000.00, 40000.00, '2025-10-07 06:51:15', '2025-10-07 06:51:15'),
(56, 15, 6, NULL, 1, 12000.00, 12000.00, '2025-10-07 06:51:15', '2025-10-07 06:51:15'),
(57, 15, 8, NULL, 1, 20000.00, 20000.00, '2025-10-07 06:51:15', '2025-10-07 06:51:15'),
(58, 15, 9, NULL, 1, 10000.00, 10000.00, '2025-10-07 06:51:15', '2025-10-07 06:51:15'),
(59, 15, 11, NULL, 1, 15000.00, 15000.00, '2025-10-07 06:51:15', '2025-10-07 06:51:15'),
(60, 15, 12, NULL, 1, 20000.00, 20000.00, '2025-10-07 06:51:15', '2025-10-07 06:51:15'),
(61, 16, 7, NULL, 2, 12000.00, 24000.00, '2025-10-07 07:42:13', '2025-10-07 07:42:13'),
(62, 16, 10, NULL, 2, 12000.00, 24000.00, '2025-10-07 07:42:13', '2025-10-07 07:42:13'),
(63, 17, 9, NULL, 1, 10000.00, 10000.00, '2025-10-08 02:52:25', '2025-10-08 02:52:25'),
(64, 17, 14, NULL, 1, 40000.00, 40000.00, '2025-10-08 02:52:25', '2025-10-08 02:52:25'),
(66, 18, 3, 23, 2, 15000.00, 30000.00, '2025-10-13 05:25:25', '2025-10-13 05:41:20'),
(67, 18, 11, 23, 2, 15000.00, 30000.00, '2025-10-13 05:25:25', '2025-10-13 05:41:20'),
(68, 18, 5, 23, 2, 20000.00, 40000.00, '2025-10-13 05:25:25', '2025-10-13 05:41:20'),
(69, 18, 13, 23, 1, 20000.00, 20000.00, '2025-10-13 05:25:25', '2025-10-13 05:41:20'),
(70, 18, 12, 23, 3, 20000.00, 60000.00, '2025-10-13 05:25:25', '2025-10-13 05:42:44'),
(71, 19, 10, 24, 2, 12000.00, 24000.00, '2025-10-13 05:48:15', '2025-10-13 05:48:48'),
(72, 19, 8, 24, 1, 20000.00, 20000.00, '2025-10-13 05:48:15', '2025-10-13 05:48:48'),
(73, 19, 4, 24, 1, 25000.00, 25000.00, '2025-10-13 05:48:15', '2025-10-13 05:48:48'),
(75, 19, 14, 24, 1, 40000.00, 40000.00, '2025-10-13 05:48:15', '2025-10-13 05:48:48'),
(76, 19, 12, 24, 1, 20000.00, 20000.00, '2025-10-13 05:48:15', '2025-10-13 05:48:48'),
(77, 20, 2, 25, 2, 15000.00, 30000.00, '2025-10-13 08:10:28', '2025-10-13 15:12:21'),
(78, 20, 11, 25, 2, 15000.00, 30000.00, '2025-10-13 08:10:28', '2025-10-13 15:12:21'),
(79, 20, 12, 25, 2, 20000.00, 40000.00, '2025-10-13 08:10:28', '2025-10-13 15:12:21'),
(80, 20, 5, 25, 2, 20000.00, 40000.00, '2025-10-13 08:10:28', '2025-10-13 15:12:21'),
(81, 21, 5, NULL, 1, 20000.00, 20000.00, '2025-10-14 02:31:16', '2025-10-14 02:31:16'),
(82, 21, 8, NULL, 1, 20000.00, 20000.00, '2025-10-14 02:31:16', '2025-10-14 02:31:16'),
(83, 22, 2, NULL, 2, 15000.00, 30000.00, '2025-10-17 07:02:16', '2025-10-17 07:02:16'),
(84, 22, 3, NULL, 2, 15000.00, 30000.00, '2025-10-17 07:02:16', '2025-10-17 07:02:16'),
(85, 22, 4, NULL, 2, 25000.00, 50000.00, '2025-10-17 07:02:16', '2025-10-17 07:02:16'),
(86, 23, 1, NULL, 2, 25000.00, 50000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(87, 23, 3, NULL, 4, 15000.00, 60000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(88, 23, 5, NULL, 3, 20000.00, 60000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(89, 23, 6, NULL, 4, 12000.00, 48000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(90, 23, 10, NULL, 2, 12000.00, 24000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(91, 23, 11, NULL, 2, 15000.00, 30000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(92, 23, 12, NULL, 2, 20000.00, 40000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(93, 23, 13, NULL, 3, 20000.00, 60000.00, '2025-10-18 04:23:23', '2025-10-18 04:23:23'),
(94, 24, 3, NULL, 2, 15000.00, 30000.00, '2025-10-19 08:49:16', '2025-10-19 08:49:16'),
(95, 24, 4, NULL, 1, 25000.00, 25000.00, '2025-10-19 08:49:16', '2025-10-19 08:49:16'),
(96, 24, 5, NULL, 1, 20000.00, 20000.00, '2025-10-19 08:49:16', '2025-10-19 08:49:16'),
(97, 24, 12, NULL, 1, 20000.00, 20000.00, '2025-10-19 08:49:16', '2025-10-19 08:49:16'),
(98, 25, 1, NULL, 1, 25000.00, 25000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(99, 25, 2, NULL, 4, 15000.00, 60000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(100, 25, 3, NULL, 1, 15000.00, 15000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(101, 25, 5, NULL, 2, 20000.00, 40000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(102, 25, 9, NULL, 2, 10000.00, 20000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(103, 25, 11, NULL, 1, 15000.00, 15000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(104, 25, 13, NULL, 1, 20000.00, 20000.00, '2025-10-20 01:55:32', '2025-10-20 01:55:32'),
(105, 26, 3, NULL, 2, 15000.00, 30000.00, '2025-10-20 04:08:48', '2025-10-20 04:08:48'),
(106, 26, 5, NULL, 2, 20000.00, 40000.00, '2025-10-20 04:08:48', '2025-10-20 04:08:48'),
(107, 26, 8, NULL, 2, 20000.00, 40000.00, '2025-10-20 04:08:48', '2025-10-20 04:08:48'),
(108, 26, 12, NULL, 1, 20000.00, 20000.00, '2025-10-20 04:08:48', '2025-10-20 04:08:48'),
(109, 27, 6, NULL, 2, 12000.00, 24000.00, '2025-10-20 06:34:25', '2025-10-20 07:32:39'),
(110, 27, 10, NULL, 2, 12000.00, 24000.00, '2025-10-20 06:34:25', '2025-10-20 07:32:39'),
(111, 27, 13, NULL, 3, 20000.00, 60000.00, '2025-10-20 06:34:25', '2025-10-20 07:32:39'),
(113, NULL, 12, 26, 5, 20000.00, 100000.00, '2025-10-21 14:41:40', '2025-10-21 14:41:40'),
(114, NULL, 5, 26, 1, 20000.00, 20000.00, '2025-10-21 14:41:40', '2025-10-21 14:41:40'),
(118, 28, 11, 27, 2, 15000.00, 30000.00, '2025-10-22 04:27:13', '2025-10-22 08:07:18'),
(119, 28, 3, 27, 2, 15000.00, 30000.00, '2025-10-22 05:56:30', '2025-10-22 08:07:18'),
(120, 28, 9, 27, 2, 10000.00, 20000.00, '2025-10-22 06:02:42', '2025-10-22 08:07:18'),
(123, 28, 6, 27, 1, 12000.00, 12000.00, '2025-10-22 07:10:27', '2025-10-22 08:07:18'),
(124, 28, 5, 27, 3, 20000.00, 60000.00, '2025-10-22 07:10:51', '2025-10-22 08:13:57'),
(125, 29, 1, NULL, 1, 25000.00, 25000.00, '2025-10-22 08:45:46', '2025-10-22 08:45:46'),
(126, 29, 3, NULL, 1, 15000.00, 15000.00, '2025-10-22 08:45:46', '2025-10-22 08:45:46'),
(127, 30, 5, NULL, 2, 20000.00, 40000.00, '2025-10-22 11:56:11', '2025-10-22 12:06:41'),
(128, 30, 8, NULL, 2, 20000.00, 40000.00, '2025-10-22 11:56:11', '2025-10-22 12:06:41'),
(129, 30, 9, NULL, 2, 10000.00, 20000.00, '2025-10-22 11:56:11', '2025-10-22 12:06:41'),
(130, 31, 3, NULL, 2, 15000.00, 30000.00, '2025-10-22 12:06:08', '2025-10-22 12:06:54'),
(131, NULL, 4, 28, 4, 25000.00, 100000.00, '2025-10-23 02:08:34', '2025-10-23 02:19:37'),
(132, NULL, 1, 28, 4, 25000.00, 100000.00, '2025-10-23 02:08:34', '2025-10-23 02:17:03'),
(133, NULL, 3, 28, 1, 15000.00, 15000.00, '2025-10-23 02:13:10', '2025-10-23 02:13:10'),
(134, NULL, 5, 28, 1, 20000.00, 20000.00, '2025-10-23 02:19:45', '2025-10-23 02:19:45'),
(135, 32, 11, NULL, 2, 15000.00, 30000.00, '2025-10-23 03:02:44', '2025-10-23 03:02:44'),
(136, 33, 2, 29, 1, 15000.00, 15000.00, '2025-10-23 03:10:22', '2025-10-23 03:11:53'),
(137, 33, 5, 29, 1, 20000.00, 20000.00, '2025-10-23 03:10:22', '2025-10-23 03:11:53'),
(138, 34, 5, NULL, 1, 20000.00, 20000.00, '2025-12-04 08:04:53', '2025-12-04 08:04:53'),
(139, 34, 9, NULL, 1, 10000.00, 10000.00, '2025-12-04 08:04:53', '2025-12-04 08:04:53');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `change` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','bank_transfer','e_wallet','qris') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qriss_issuer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qris_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `receipt_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `reservation_id`, `user_id`, `amount`, `amount_paid`, `change`, `payment_method`, `bank_name`, `account_number`, `card_type`, `card_number`, `qriss_issuer`, `qris_number`, `status`, `receipt_url`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 6, 2, 40000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #6', '2025-09-17 21:00:10', '2025-09-17 21:00:54'),
(2, NULL, 6, 2, 40000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #6', '2025-09-17 21:00:34', '2025-09-17 21:00:47'),
(3, NULL, 8, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #8', '2025-09-17 23:15:22', '2025-09-17 23:15:39'),
(4, NULL, 9, 2, 100000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #9', '2025-09-17 23:47:39', '2025-09-17 23:47:48'),
(5, NULL, 9, 2, 40000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #9', '2025-09-18 00:25:00', '2025-09-18 00:25:12'),
(6, 1, NULL, 2, 15000.00, 20000.00, 5000.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-09-18 23:16:12', '2025-09-18 23:16:35'),
(7, NULL, 10, 2, 50000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #10', '2025-09-18 23:40:13', '2025-09-18 23:40:26'),
(8, NULL, 11, 2, 15000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #11', '2025-09-19 00:07:30', '2025-09-19 00:07:37'),
(9, NULL, 12, 2, 100000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #12', '2025-09-21 19:41:14', '2025-09-21 19:41:26'),
(10, NULL, 13, 2, 100000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #13', '2025-09-21 20:41:01', '2025-09-21 20:41:14'),
(11, NULL, 13, 2, 170000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #13', '2025-09-21 21:42:55', '2025-09-21 21:43:10'),
(12, NULL, 13, 2, 64000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #13', '2025-09-21 21:43:51', '2025-09-21 21:44:01'),
(13, 2, NULL, 2, 270000.00, 300000.00, 30000.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-09-21 23:47:29', '2025-09-21 23:47:48'),
(14, NULL, 14, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #14', '2025-09-22 00:44:33', '2025-09-22 00:44:50'),
(15, NULL, 14, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #14', '2025-09-22 00:47:59', '2025-09-22 00:48:17'),
(16, NULL, 15, 2, 200000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #15', '2025-09-22 00:57:56', '2025-09-22 00:58:08'),
(17, NULL, 16, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #16', '2025-09-22 19:07:40', '2025-09-22 19:07:49'),
(18, NULL, 17, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #17', '2025-09-26 00:55:55', '2025-09-26 00:56:10'),
(19, NULL, 17, 2, 100000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #17', '2025-09-26 00:59:39', '2025-09-26 01:00:46'),
(20, NULL, 17, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #17', '2025-09-26 01:01:21', '2025-09-26 01:01:31'),
(21, NULL, 18, 2, 50000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #18', '2025-10-03 07:09:10', '2025-10-03 07:09:34'),
(22, NULL, 19, 2, 60000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #19', '2025-10-06 01:35:22', '2025-10-06 01:36:29'),
(23, NULL, 19, 2, 200000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #19', '2025-10-06 01:39:51', '2025-10-06 01:40:08'),
(24, NULL, 20, 4, 20000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #20', '2025-10-06 01:44:42', '2025-10-06 01:44:55'),
(25, NULL, 20, 4, 180000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #20', '2025-10-06 01:45:46', '2025-10-06 01:46:01'),
(26, 10, NULL, 4, 50000.00, 50000.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-06 06:25:17', '2025-10-06 06:25:43'),
(27, NULL, 22, 5, 25000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #22', '2025-10-06 07:34:01', '2025-10-06 07:34:13'),
(28, NULL, 22, 5, 175000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #22', '2025-10-06 07:41:28', '2025-10-06 07:41:37'),
(29, NULL, 21, 5, 60000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #21', '2025-10-07 02:41:55', '2025-10-07 02:42:30'),
(30, NULL, 21, 5, 100000.00, 100000.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment for reservation #21', '2025-10-07 02:43:43', '2025-10-07 02:43:43'),
(31, 13, NULL, 5, 80000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-07 02:45:14', '2025-10-07 02:45:26'),
(32, 14, NULL, 5, 90000.00, 100000.00, 10000.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-07 06:07:16', '2025-10-07 06:07:34'),
(33, 15, NULL, 5, 117000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-07 07:38:02', '2025-10-07 07:38:18'),
(34, 16, NULL, 5, 48000.00, NULL, 0.00, 'bank_transfer', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-09 02:52:13', '2025-10-09 02:52:49'),
(35, 17, NULL, 5, 55000.00, NULL, 0.00, 'e_wallet', 'Gopay', '12345678', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-10 07:25:40', '2025-10-10 07:28:12'),
(36, NULL, 23, 5, 25000.00, NULL, 0.00, 'e_wallet', 'Dana', '12345678', NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 1 for reservation #23', '2025-10-13 05:40:45', '2025-10-13 05:41:20'),
(37, NULL, 23, 5, 195000.00, 195000.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 2 for reservation #23', '2025-10-13 05:43:20', '2025-10-13 05:43:20'),
(38, NULL, 24, 5, 20000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 1 for reservation #24', '2025-10-13 05:48:39', '2025-10-13 05:48:48'),
(39, NULL, 24, 5, 134000.00, 134000.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 2 for reservation #24', '2025-10-13 07:15:40', '2025-10-13 07:15:40'),
(40, NULL, 25, 5, 20000.00, NULL, 0.00, 'e_wallet', 'Dana', '12345678', NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 1 for reservation #25', '2025-10-13 08:11:03', '2025-10-13 15:12:20'),
(41, 21, NULL, 5, 40000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-14 04:09:32', '2025-10-14 04:19:04'),
(42, NULL, 25, 5, 160000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 2 for reservation #25', '2025-10-14 04:18:45', '2025-10-14 04:19:18'),
(43, 22, NULL, 4, 110000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-17 07:02:58', '2025-10-17 07:03:05'),
(44, 23, NULL, 4, 372000.00, 400000.00, 28000.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-18 04:27:05', '2025-10-18 04:27:53'),
(45, 24, NULL, 4, 95000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-19 08:50:08', '2025-10-19 08:50:24'),
(46, 25, NULL, 4, 195000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-20 02:00:32', '2025-10-20 02:00:44'),
(47, 26, NULL, 5, 130000.00, 150000.00, 20000.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-20 06:30:24', '2025-10-20 06:30:51'),
(48, NULL, 26, 4, 184000.00, NULL, 0.00, 'e_wallet', 'Dana', '12345678', NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 1 for reservation #26', '2025-10-21 15:15:18', '2025-10-21 15:15:58'),
(49, 27, NULL, 4, 108000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-22 06:24:29', '2025-10-22 06:24:39'),
(50, NULL, 27, 5, 100000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 1 for reservation #27', '2025-10-22 08:07:05', '2025-10-22 08:07:18'),
(51, NULL, 27, 5, 116000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 2 for reservation #27', '2025-10-22 08:35:39', '2025-10-22 08:35:49'),
(52, 29, NULL, 4, 40000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-22 11:54:59', '2025-10-22 11:55:10'),
(53, 30, NULL, 4, 100000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-22 13:13:17', '2025-10-22 13:13:48'),
(54, 31, NULL, 4, 30000.00, NULL, 0.00, 'bank_transfer', 'Bank Central Asia (BCA)', '17081945', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-22 13:13:31', '2025-10-22 13:13:41'),
(55, 32, NULL, 7, 30000.00, NULL, 0.00, 'qris', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-10-23 03:07:23', '2025-10-23 03:07:42'),
(56, NULL, 29, 7, 20000.00, NULL, 0.00, 'e_wallet', 'Dana', '12345678', NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 1 for reservation #29', '2025-10-23 03:11:42', '2025-10-23 03:11:53'),
(57, NULL, 29, 7, 79000.00, 79000.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, 'paid', NULL, 'Payment 2 for reservation #29', '2025-10-23 03:13:16', '2025-10-23 03:13:16'),
(58, 34, NULL, 4, 30000.00, NULL, 0.00, 'e_wallet', 'Dana', '12345678', NULL, NULL, NULL, NULL, 'paid', NULL, NULL, '2025-12-04 08:08:32', '2025-12-04 08:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `end_time` time NOT NULL,
  `guest_count` int NOT NULL,
  `table_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `reservation_date`, `reservation_time`, `end_time`, `guest_count`, `table_number`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-09-17', '14:40:00', '00:00:00', 3, 'B1', 'completed', NULL, '2025-09-15 21:35:35', '2025-09-21 19:31:45'),
(2, 2, '2025-09-17', '15:50:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-15 23:50:30', '2025-09-16 00:53:47'),
(3, 2, '2025-09-17', '16:00:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-16 00:55:11', '2025-09-17 19:54:31'),
(4, 2, '2025-09-19', '11:00:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-17 19:55:09', '2025-09-17 20:46:43'),
(5, 2, '2025-09-20', '11:50:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-17 20:47:24', '2025-09-17 20:59:00'),
(6, 2, '2025-09-19', '11:00:00', '00:00:00', 4, 'B1', 'completed', NULL, '2025-09-17 20:59:36', '2025-09-21 19:37:54'),
(7, 2, '2025-09-20', '13:30:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-17 21:27:32', '2025-09-17 23:14:36'),
(8, 2, '2025-09-19', '13:15:00', '00:00:00', 2, 'A1', 'completed', NULL, '2025-09-17 23:15:02', '2025-09-21 19:37:54'),
(9, 2, '2025-09-19', '15:50:00', '00:00:00', 4, 'B1', 'completed', NULL, '2025-09-17 23:47:14', '2025-09-21 19:36:05'),
(10, 2, '2025-09-20', '15:40:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-18 23:39:49', '2025-10-18 01:51:22'),
(11, 2, '2025-09-20', '16:05:00', '00:00:00', 4, 'B1', 'cancelled', NULL, '2025-09-19 00:05:38', '2025-10-18 01:51:16'),
(12, 2, '2025-09-24', '11:45:00', '00:00:00', 7, 'C1', 'cancelled', NULL, '2025-09-21 19:40:57', '2025-09-21 20:39:37'),
(13, 2, '2025-09-23', '11:45:00', '00:00:00', 8, 'C1', 'completed', NULL, '2025-09-21 20:40:48', '2025-09-21 21:44:01'),
(14, 2, '2025-09-23', '15:50:00', '00:00:00', 4, 'B1', 'completed', NULL, '2025-09-22 00:43:48', '2025-09-22 00:48:17'),
(15, 2, '2025-09-23', '16:50:00', '00:00:00', 8, 'C1', 'cancelled', NULL, '2025-09-22 00:57:39', '2025-10-18 01:51:11'),
(16, 2, '2025-09-24', '11:00:00', '00:00:00', 8, 'C1', 'cancelled', NULL, '2025-09-22 19:07:13', '2025-09-26 00:44:05'),
(17, 2, '2025-09-27', '16:00:00', '00:00:00', 4, 'B1', 'completed', NULL, '2025-09-26 00:55:13', '2025-09-26 01:01:31'),
(18, 2, '2025-10-04', '15:15:00', '16:15:00', 4, 'B1', 'cancelled', NULL, '2025-10-03 07:06:53', '2025-10-06 01:32:26'),
(19, 2, '2025-10-07', '09:30:00', '11:00:00', 4, 'B1', 'completed', NULL, '2025-10-06 01:34:52', '2025-10-06 01:40:08'),
(20, 4, '2025-10-07', '09:45:00', '10:45:00', 4, 'B1', 'completed', NULL, '2025-10-06 01:44:03', '2025-10-06 01:46:01'),
(21, 5, '2025-10-08', '14:30:00', '15:30:00', 4, 'B1', 'completed', NULL, '2025-10-06 06:57:57', '2025-10-07 02:43:43'),
(22, 5, '2025-10-07', '20:00:00', '21:00:00', 4, 'B1', 'completed', NULL, '2025-10-06 07:33:40', '2025-10-06 07:41:37'),
(23, 5, '2025-10-14', '13:30:00', '15:00:00', 4, 'B1', 'completed', NULL, '2025-10-13 05:25:25', '2025-10-13 05:43:20'),
(24, 5, '2025-10-14', '13:50:00', '14:50:00', 2, 'A1', 'completed', NULL, '2025-10-13 05:48:15', '2025-10-13 07:15:40'),
(25, 5, '2025-10-14', '16:00:00', '17:30:00', 4, 'B1', 'completed', NULL, '2025-10-13 08:10:28', '2025-10-14 04:19:18'),
(26, 4, '2025-10-24', '15:40:00', '16:40:00', 5, 'C1', 'completed', NULL, '2025-10-21 14:41:40', '2025-10-21 15:15:58'),
(27, 5, '2025-10-23', '19:30:00', '21:00:00', 5, 'C1', 'completed', NULL, '2025-10-22 02:28:07', '2025-10-22 08:35:49'),
(28, 4, '2025-10-24', '10:20:00', '11:20:00', 4, 'B1', 'pending', NULL, '2025-10-23 02:08:34', '2025-10-23 02:20:14'),
(29, 7, '2025-10-25', '13:10:00', '15:06:00', 5, 'C1', 'completed', NULL, '2025-10-23 03:10:22', '2025-10-23 03:13:16');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `rating` int UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reservation_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `menu_id`, `order_id`, `rating`, `comment`, `admin_reply`, `replied_at`, `created_at`, `updated_at`, `reservation_id`) VALUES
(2, 2, 3, 1, 5, 'enak bgt cuy', 'Makasih kaka', '2025-10-15 01:42:32', '2025-09-18 23:38:57', '2025-10-15 01:42:32', NULL),
(3, 2, 3, 2, 5, NULL, NULL, NULL, '2025-09-21 23:48:52', '2025-09-21 23:48:52', NULL),
(4, 2, 2, 2, 4, 'kurang besar min', NULL, NULL, '2025-09-21 23:49:11', '2025-09-21 23:49:11', NULL),
(5, 2, 1, 2, 5, NULL, NULL, NULL, '2025-09-21 23:49:18', '2025-09-21 23:49:18', NULL),
(9, 4, 2, NULL, 5, 'enak bgt', NULL, NULL, '2025-10-06 04:41:30', '2025-10-06 04:41:30', 20),
(11, 4, 3, NULL, 5, 'enakkk', NULL, NULL, '2025-10-06 04:45:26', '2025-10-06 04:45:26', 20),
(13, 4, 1, NULL, 5, NULL, NULL, NULL, '2025-10-06 06:37:11', '2025-10-06 06:37:11', 20),
(16, 5, 3, NULL, 5, NULL, NULL, NULL, '2025-10-07 02:43:58', '2025-10-07 02:43:58', 21),
(17, 5, 1, NULL, 5, NULL, NULL, NULL, '2025-10-07 02:44:27', '2025-10-07 02:44:27', 22),
(18, 5, 4, 14, 5, 'Enak bgt bang', NULL, NULL, '2025-10-07 06:08:30', '2025-10-07 06:08:30', NULL),
(19, 5, 8, 14, 5, 'Pisang ijo terenak yang pernah gue makan', NULL, NULL, '2025-10-07 06:08:50', '2025-10-07 06:08:50', NULL),
(20, 5, 2, 13, 5, 'enak bgt', NULL, NULL, '2025-10-07 06:21:42', '2025-10-07 06:21:42', NULL),
(21, 5, 5, 15, 5, 'enak', NULL, NULL, '2025-10-07 07:40:42', '2025-10-07 07:40:42', NULL),
(22, 5, 6, 15, 4, 'b aja si', NULL, NULL, '2025-10-07 07:41:00', '2025-10-07 07:41:00', NULL),
(23, 5, 9, 15, 4, 'b aja', NULL, NULL, '2025-10-07 07:41:11', '2025-10-07 07:41:11', NULL),
(24, 5, 11, 15, 5, 'enk bgt woy', NULL, NULL, '2025-10-07 07:41:28', '2025-10-07 07:41:28', NULL),
(25, 5, 12, 15, 5, NULL, NULL, NULL, '2025-10-07 07:41:41', '2025-10-07 07:41:41', NULL),
(26, 4, 4, 22, 5, 'enak bgt, bumbunya pas', NULL, NULL, '2025-10-18 01:52:35', '2025-10-18 01:52:35', NULL),
(27, 7, 11, 32, 5, NULL, NULL, NULL, '2025-10-23 03:08:23', '2025-10-23 03:08:23', NULL),
(28, 7, 2, NULL, 5, 'enak', NULL, NULL, '2025-10-23 03:20:03', '2025-10-23 03:20:03', 29);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aYXamQAtXKapX0BZRTRxYSZbYr1iwmRbY5HY8zR3', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSnpmQmVjeFZnaGFBa2hSQ1ZqNm5KVGoydWwxbjhtM091QXN0VkZFeCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1764835581),
('rdcvA8gKuY105pgUnPjNCjhC3iKK0PvhUShPCG6I', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMVg3WjhVQTZlcVBXMk9tWENiblZKaThuUlZGMHRNaWUzVnptdTlqNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXNoaWVyL21lbnVzL3N0YXR1cy1jb3VudHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1764835826);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'YazkaAdeF.', 'yazka@gmail.com', NULL, '$2y$12$8DGFboIkfjZq9XOY/7KSq.Cylph8NmDhZ9n7V/nwFg9G5DW8LheYy', 'admin', NULL, '2025-09-15 21:29:25', '2025-09-15 21:29:25'),
(2, 'Dyas', 'dyas@gmail.com', NULL, '$2y$12$/wMcOZO93wrA8f5JfitfY.gY6GgthQZ7sbTsI2JmXlk0/.4jVAaIG', 'customer', NULL, '2025-09-15 21:29:42', '2025-09-15 21:29:42'),
(3, 'Jonathan', 'jojo@gmail.com', NULL, '$2y$12$oKfxUO1kyipZW614pNKhtuhNeLORjjs62Vjz.72P7KjUeC3AvrZS2', 'cashier', 'FRWKUlkgqCNYRRXJoUDF0JH4tn6HX9Ycxh9ZF26ZSKmisy5UjytI1Z9e5Ict', '2025-09-15 21:31:06', '2025-09-15 21:31:06'),
(4, 'Kaka', 'kaka@gmail.com', NULL, '$2y$12$nb/iBFfDk3FEG9CvlABpA.jn7eokOGbl0dXcaSww4JRaVaCjAQuyi', 'customer', NULL, '2025-10-06 01:42:55', '2025-10-06 01:42:55'),
(5, 'Keke', 'keke@gmail.com', NULL, '$2y$12$JKJTY4gtuz3pS5Q.hNavSeeDco7driMRzmAx5uK1d99bnBEpOlbGi', 'customer', NULL, '2025-10-06 06:57:07', '2025-10-06 06:57:07'),
(6, 'Alif', 'alif@gmail.com', NULL, '$2y$12$oOvJ2VbGdWm0vSVaWn1OPOdNhKk6NsVSf.Lt9AySgyJG1PN1TsgFK', 'cashier', NULL, '2025-10-08 01:47:16', '2025-10-08 01:47:16'),
(7, 'Dede', 'sutomo@gmail.com', NULL, '$2y$12$nIOdQXnfqLllCmG4OJ5nw.e9iCkW2S3NwXfpGVhOwoPhyb.wyzlQq', 'customer', NULL, '2025-10-23 03:01:21', '2025-10-23 03:01:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_payment_methods`
--
ALTER TABLE `bank_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blacklists`
--
ALTER TABLE `blacklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blacklists_user_id_foreign` (`user_id`),
  ADD KEY `blacklists_banned_by_foreign` (`banned_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_user_id_menu_id_order_id_unique` (`user_id`,`menu_id`,`order_id`),
  ADD KEY `carts_order_id_foreign` (`order_id`),
  ADD KEY `carts_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_slug_unique` (`slug`),
  ADD KEY `menus_category_id` (`category_id`),
  ADD KEY `menus_status_index` (`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `number_tables`
--
ALTER TABLE `number_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number_tables_table_number_unique` (`table_number`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_table_number_foreign` (`table_number`),
  ADD KEY `orders_reservation_id_index` (`reservation_id`),
  ADD KEY `orders_user_id_index` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_menu_id_foreign` (`menu_id`),
  ADD KEY `order_items_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_reservation_id_foreign` (`reservation_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_user_id_foreign` (`user_id`),
  ADD KEY `reservations_table_number_foreign` (`table_number`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_menu_id_unique` (`user_id`,`menu_id`,`order_id`) USING BTREE,
  ADD KEY `reviews_menu_id_foreign` (`menu_id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_reservation_id_foreign` (`reservation_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_payment_methods`
--
ALTER TABLE `bank_payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blacklists`
--
ALTER TABLE `blacklists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `number_tables`
--
ALTER TABLE `number_tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blacklists`
--
ALTER TABLE `blacklists`
  ADD CONSTRAINT `blacklists_banned_by_foreign` FOREIGN KEY (`banned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blacklists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_table_number_foreign` FOREIGN KEY (`table_number`) REFERENCES `number_tables` (`table_number`) ON DELETE RESTRICT,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_table_number_foreign` FOREIGN KEY (`table_number`) REFERENCES `number_tables` (`table_number`) ON DELETE RESTRICT,
  ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
