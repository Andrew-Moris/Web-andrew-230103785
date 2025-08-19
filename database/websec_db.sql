-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 12:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websec_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba', 'i:3;', 1755636407),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1755636407;', 1755636407),
('laravel_cache_b1d5781111d84f7b3fe45a0852e59758cd7a87e5', 'i:2;', 1754602065),
('laravel_cache_b1d5781111d84f7b3fe45a0852e59758cd7a87e5:timer', 'i:1754602065;', 1754602065),
('laravel_cache_login_attempts_127.0.0.1', 'i:5;', 1755637253);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_27_230739_create_products_table_websec', 1),
(5, '2025_07_27_230835_create_user_permissions_table', 1),
(6, '2025_08_02_101507_add_google_oauth_fields_to_users_table', 1),
(7, '2025_08_03_182724_make_password_nullable_in_users_table', 1),
(8, '2025_08_03_231122_add_phone_to_users_table', 1),
(9, '2025_08_06_212032_create_reports_table', 2),
(10, '2025_08_06_213702_add_purchases_to_products_table', 2),
(11, '2024_01_08_000000_add_facebook_id_to_users_table', 3),
(12, '2025_08_07_212031_add_permissions_to_users_table', 4),
(13, '2025_08_07_225620_add_phone_to_users_table', 5),
(14, '2025_08_11_091305_add_credit_to_users_table', 5),
(15, '2025_08_11_101300_add_stock_to_products_table', 6),
(16, '2025_08_11_101400_create_purchases_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `purchases` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `model`, `price`, `description`, `image`, `stock`, `purchases`, `created_at`, `updated_at`) VALUES
(1, 'LG TV 50\"', 'TV01', 'LG8768787', 100.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1', -9, 0, '2025-08-04 02:14:32', '2025-08-12 17:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price_paid` decimal(8,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `product_id`, `price_paid`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 100.00, 1, '2025-08-11 06:38:06', '2025-08-11 06:38:06'),
(2, 10, 1, 100.00, 1, '2025-08-11 06:40:13', '2025-08-11 06:40:13'),
(3, 10, 1, 100.00, 1, '2025-08-11 06:40:19', '2025-08-11 06:40:19'),
(4, 10, 1, 100.00, 1, '2025-08-11 06:44:41', '2025-08-11 06:44:41'),
(5, 12, 1, 100.00, 1, '2025-08-12 17:34:11', '2025-08-12 17:34:11'),
(6, 12, 1, 100.00, 1, '2025-08-12 17:34:14', '2025-08-12 17:34:14'),
(7, 12, 1, 100.00, 1, '2025-08-12 17:34:17', '2025-08-12 17:34:17'),
(8, 10, 1, 100.00, 1, '2025-08-12 17:40:54', '2025-08-12 17:40:54'),
(9, 10, 1, 100.00, 1, '2025-08-12 17:40:57', '2025-08-12 17:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `credit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `facebook_id` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `google_id` text DEFAULT NULL,
  `google_token` text DEFAULT NULL,
  `google_refresh_token` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `credit`, `facebook_id`, `profile_picture`, `phone`, `google_id`, `google_token`, `google_refresh_token`, `email_verified_at`, `password`, `remember_token`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'andrew', 'loudakingxx@gmail.com', 0.00, NULL, NULL, '01066889412', NULL, NULL, NULL, NULL, '$2y$12$tkrwuw6V/WWBZj8BTTcfW.e9W36u/cbF1v8J1gsyPuZ26.EwAuwSO', 'YkoFyoZqzGIDRRm0L3FABYdOXPKjz51SkQfZPlltlhY850Mi6qa6oyEy5HIB', NULL, '2025-08-04 01:28:41', '2025-08-04 01:28:41'),
(2, 'andrew', 'am3442203@gmail.com', 0.00, NULL, NULL, '01225009498', NULL, NULL, NULL, NULL, '$2y$12$xRwwlbg/RMMOyWn.eA4nB.qq036XMDs0noAX7cXUiro/nuCufK.AW', NULL, NULL, '2025-08-04 01:48:31', '2025-08-04 01:48:31'),
(3, 'John Smith', 'john@test.com', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$D7xZqmA5S4UWeMYL4uspZeVicgQcSLvdnI9hkpmiPQotxJahdNAp.', NULL, NULL, '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(4, 'Sarah Johnson', 'sarah@test.com', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$UreCuOx20Gn5R1YmlHhOaOnHuA/kfpi5kgJYwyaGjfuzLhUdgnuZy', NULL, NULL, '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(5, 'Mike Wilson', 'mike@test.com', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$E2ykX/wYdt9VnyAohyd3veK076JDPqzhSyhvf2F0LTX/zaWeDHBQC', NULL, NULL, '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(6, 'Admin User', 'admin@test.com', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$/oJiiMOqUjVGGAwVlRkl8O.Up8Sy/dqCUfNX2I4LGxuZonZAObnhu', NULL, NULL, '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(8, 'andrew', 'loudakingxx@gmail.co', 0.00, NULL, NULL, '01066889412', NULL, NULL, NULL, NULL, '$2y$12$pCZ.2chYcfVk38SrbYjBAuPY6mPOadjCeWtW1wmL.sizbLmBiVg7.', 'JBHVL8EFeOoPHSIV2JSwtfNMmLwlwGjtNFlsEPFa02ri42TqLozVnOw8blmA', NULL, '2025-08-04 02:43:38', '2025-08-04 02:43:38'),
(9, 'andrew', 'l@gmail.com', 0.00, NULL, NULL, '01066889412', NULL, NULL, NULL, NULL, '$2y$12$jgr1V.z6g284bWCVp2ZPiuMs.sOm/m/je57.0d1jc1chWbiYvxatC', NULL, NULL, '2025-08-04 03:21:44', '2025-08-04 03:21:44'),
(10, 'Admin User', 'admin@example.com', 10099500.00, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 05:58:23', '$2y$12$oC/eEfEmh47H23rhiERgy.uPcf9k0qoy9WAScENeAM..G5ZB2XSma', 'obpLMcHx9WXlD2lPEb72hIURlcHRnJ7oxgNhZEC9oYsO9HXzEmqcOZtLSc7g', '[\"users\",\"products\",\"reports\",\"settings\"]', '2025-08-04 05:58:23', '2025-08-12 17:40:57'),
(11, 'CAT', 'cat@gmail.com', 0.00, NULL, NULL, '01066889412', NULL, NULL, NULL, NULL, '$2y$12$GFdT4KOXmuWvgC3FjFPbreI6FxArVc8C6SPY9bCc8Mic5wuW4OiNy', NULL, NULL, '2025-08-04 06:20:25', '2025-08-04 06:20:25'),
(12, 'ANDREW MORES', 'andrewmores9498@gmail.com', 700.00, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 08:46:47', NULL, NULL, '[\"users\",\"products\",\"reports\",\"settings\"]', '2025-08-05 08:46:47', '2025-08-12 17:34:17'),
(13, 'andrew', 'king@gmail.com', 0.00, NULL, NULL, '01029479904', NULL, NULL, NULL, NULL, '$2y$12$UQ7iZf1aczWZ9l6gKL9.vOuDyq/9x3GF58alQdcbOORoI6DnVtYdS', NULL, NULL, '2025-08-08 08:24:04', '2025-08-08 08:24:04'),
(14, 'khalil', 'khalil@gmail.com', 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$dpMNz2AbeuPtmcp3YUApFuMZeA0zPBfgIHY5MzG/TK1BOhiNLjkTe', NULL, NULL, '2025-08-08 08:47:11', '2025-08-08 08:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `role`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', '[\"reports\"]', '2025-08-04 02:00:37', '2025-08-06 13:39:49'),
(2, 3, 'editor', '[\"create\",\"edit\",\"reports\"]', '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(3, 4, 'viewer', '[\"reports\"]', '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(4, 5, 'admin', '[\"create\",\"edit\",\"delete\",\"reports\",\"users\"]', '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(5, 6, 'admin', '[\"create\",\"edit\",\"delete\",\"reports\",\"users\"]', '2025-08-04 02:14:32', '2025-08-04 02:14:32'),
(6, 8, 'admin', '[\"create\",\"edit\",\"delete\",\"reports\",\"users\"]', '2025-08-04 02:43:38', '2025-08-04 02:47:49'),
(7, 9, 'viewer', '[\"reports\"]', '2025-08-04 03:21:44', '2025-08-04 03:21:44'),
(8, 10, 'admin', '[\"create\",\"edit\",\"delete\",\"reports\",\"users\"]', '2025-08-04 05:58:24', '2025-08-04 05:58:24'),
(9, 11, 'admin', '[\"reports\"]', '2025-08-04 06:20:25', '2025-08-07 16:44:46'),
(10, 12, 'viewer', '[\"reports\"]', '2025-08-05 08:46:47', '2025-08-08 08:22:20'),
(11, 2, 'viewer', '[\"reports\"]', '2025-08-07 16:44:32', '2025-08-07 16:48:47'),
(12, 10, 'viewer', '[\"reports\"]', '2025-08-07 18:21:05', '2025-08-07 18:21:05'),
(13, 10, 'viewer', '[\"reports\"]', '2025-08-07 18:26:28', '2025-08-07 18:26:28'),
(14, 13, 'viewer', '[\"reports\"]', '2025-08-08 08:24:04', '2025-08-08 08:24:04'),
(15, 14, 'admin', '[\"create\",\"edit\",\"delete\",\"reports\",\"users\"]', '2025-08-08 08:47:11', '2025-08-08 09:00:00');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_code_unique` (`code`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_product_id_foreign` (`product_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_facebook_id_unique` (`facebook_id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_permissions_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
