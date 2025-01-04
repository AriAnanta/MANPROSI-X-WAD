-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Jan 03, 2025 at 10:36 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenledger`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_admin` varchar(255) DEFAULT NULL,
  `nama_admin` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `kode_admin`, `nama_admin`, `email`, `password`, `no_telepon`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ADM-s9GBr7', 'Admin', 'admin@example.com', '$2y$12$vL8tHY.IYyqvnpYUtifzJONapx5hDAUoag/rgIY.ndti.q6zXqhfi', '12345678910', NULL, '2025-01-03 10:32:39', '2025-01-03 10:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext,
  `expiration` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `expiration` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pembelian_carbon_credit` varchar(255) DEFAULT NULL,
  `kode_manager` varchar(255) DEFAULT NULL,
  `comment` text,
  `admin_reply` text,
  `manager_read` tinyint(1) DEFAULT '0',
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emisi_carbons`
--

CREATE TABLE `emisi_carbons` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_emisi_karbon` varchar(255) DEFAULT NULL,
  `kategori_emisi_karbon` varchar(255) DEFAULT NULL,
  `sub_kategori` varchar(255) DEFAULT NULL,
  `nilai_aktivitas` decimal(10,2) DEFAULT NULL,
  `faktor_emisi` decimal(10,2) DEFAULT NULL,
  `kadar_emisi_karbon` decimal(10,2) GENERATED ALWAYS AS ((`nilai_aktivitas` * `faktor_emisi`)) STORED,
  `deskripsi` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `kode_manager` varchar(255) DEFAULT NULL,
  `kode_user` varchar(255) DEFAULT NULL,
  `kode_admin` varchar(255) DEFAULT NULL,
  `tanggal_emisi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `emisi_carbons`
--
DELIMITER $$
CREATE TRIGGER `set_faktor_emisi_before_insert` BEFORE INSERT ON `emisi_carbons` FOR EACH ROW BEGIN
                SET NEW.faktor_emisi = (
                    SELECT nilai_faktor
                    FROM faktor_emisis
                    WHERE kategori_emisi_karbon = NEW.kategori_emisi_karbon
                    AND sub_kategori = NEW.sub_kategori
                    LIMIT 1
                );
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_faktor_emisi_before_update` BEFORE UPDATE ON `emisi_carbons` FOR EACH ROW BEGIN
                IF (NEW.kategori_emisi_karbon != OLD.kategori_emisi_karbon OR NEW.sub_kategori != OLD.sub_kategori) THEN
                    SET NEW.faktor_emisi = (
                        SELECT nilai_faktor
                        FROM faktor_emisis
                        WHERE kategori_emisi_karbon = NEW.kategori_emisi_karbon
                        AND sub_kategori = NEW.sub_kategori
                        LIMIT 1
                    );
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `connection` text,
  `queue` text,
  `payload` longtext,
  `exception` longtext,
  `failed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faktor_emisis`
--

CREATE TABLE `faktor_emisis` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_emisi_karbon` varchar(255) DEFAULT NULL,
  `sub_kategori` varchar(255) DEFAULT NULL,
  `nilai_faktor` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) DEFAULT NULL,
  `payload` longtext,
  `attempts` tinyint UNSIGNED DEFAULT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED DEFAULT NULL,
  `created_at` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `total_jobs` int DEFAULT NULL,
  `pending_jobs` int DEFAULT NULL,
  `failed_jobs` int DEFAULT NULL,
  `failed_job_ids` longtext,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kompensasi_emisi`
--

CREATE TABLE `kompensasi_emisi` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_kompensasi` varchar(255) DEFAULT NULL,
  `kode_emisi_karbon` varchar(255) DEFAULT NULL,
  `jumlah_kompensasi` decimal(10,2) DEFAULT NULL,
  `tanggal_kompensasi` date DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_manager` varchar(255) DEFAULT NULL,
  `nama_manager` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `kode_manager`, `nama_manager`, `email`, `password`, `no_telepon`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'MGR-dypXiV', 'Manager', 'manager@contoh.com', '$2y$12$1hijoXj7BWjR6dpqsjxwXOagIXJ10YO.nSXPBZoVjgoF7vyyKefUy', '1234567890', NULL, '2025-01-03 10:32:58', '2025-01-03 10:32:58');

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
(4, '2024_03_21_create_penyedia_carbon_credits_table', 1),
(5, '2024_12_07_140000_create_faktor_emisi_table', 1),
(6, '2024_12_07_144603_create_penggunas_table', 1),
(7, '2024_12_07_144759_create_admins_table', 1),
(8, '2024_12_07_144833_create_managers_table', 1),
(9, '2024_12_07_150000_create_emisi_karbon_table', 1),
(10, '2024_12_07_150001_create_kompensasi_emisi', 1),
(11, '2024_12_07_150002_create_notifikasi_table', 1),
(12, '2024_12_07_150004_create_pembelian_carbon_credit_table', 1),
(13, '2024_12_24_012542_create_comments_table', 1),
(14, '2024_12_25_000001_add_reply_columns_to_comments_table', 1),
(15, '2024_12_26_150042_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'unread',
  `data` json DEFAULT NULL,
  `for_role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasis`
--

CREATE TABLE `notifikasis` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_notifikasi` varchar(255) DEFAULT NULL,
  `kategori_notifikasi` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kode_user` varchar(255) DEFAULT NULL,
  `kode_admin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_carbon_credits`
--

CREATE TABLE `pembelian_carbon_credits` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pembelian_carbon_credit` varchar(255) DEFAULT NULL,
  `kode_penyedia` varchar(255) DEFAULT NULL,
  `kode_kompensasi` varchar(255) DEFAULT NULL,
  `kode_manager` varchar(255) DEFAULT NULL,
  `kode_admin` varchar(255) DEFAULT NULL,
  `jumlah_kompensasi` decimal(10,2) DEFAULT NULL,
  `harga_per_ton` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(15,2) DEFAULT NULL,
  `tanggal_pembelian_carbon_credit` date DEFAULT NULL,
  `bukti_pembelian` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penggunas`
--

CREATE TABLE `penggunas` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_user` varchar(255) DEFAULT NULL,
  `nama_user` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggunas`
--

INSERT INTO `penggunas` (`id`, `kode_user`, `nama_user`, `email`, `password`, `no_telepon`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'USR-lbgoAe', 'User', 'madeariananta@gmail.com', '$2y$12$PegxG3oYTd2JG3j94/iFpemWwAeJrZY6iTaPlFNQI24E5Vl7kZjnS', '12345678910', NULL, '2025-01-03 10:29:55', '2025-01-03 10:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `penyedia_carbon_credits`
--

CREATE TABLE `penyedia_carbon_credits` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_penyedia` varchar(255) DEFAULT NULL,
  `nama_penyedia` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `harga_per_ton` decimal(10,2) DEFAULT NULL,
  `mata_uang` varchar(10) DEFAULT 'IDR',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext,
  `last_activity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_admin` (`kode_admin`),
  ADD UNIQUE KEY `email` (`email`);

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
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_pembelian_carbon_credit` (`kode_pembelian_carbon_credit`),
  ADD KEY `kode_manager` (`kode_manager`);

--
-- Indexes for table `emisi_carbons`
--
ALTER TABLE `emisi_carbons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_emisi_karbon` (`kode_emisi_karbon`),
  ADD KEY `kode_manager` (`kode_manager`),
  ADD KEY `kode_user` (`kode_user`),
  ADD KEY `kode_admin` (`kode_admin`),
  ADD KEY `kategori_emisi_karbon` (`kategori_emisi_karbon`,`sub_kategori`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `faktor_emisis`
--
ALTER TABLE `faktor_emisis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_sub` (`kategori_emisi_karbon`,`sub_kategori`);

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
-- Indexes for table `kompensasi_emisi`
--
ALTER TABLE `kompensasi_emisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kompensasi` (`kode_kompensasi`),
  ADD KEY `kode_emisi_karbon` (`kode_emisi_karbon`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_manager` (`kode_manager`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifiable` (`notifiable_type`,`notifiable_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_for_role` (`for_role`);

--
-- Indexes for table `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_notifikasi` (`kode_notifikasi`),
  ADD KEY `kode_user` (`kode_user`),
  ADD KEY `kode_admin` (`kode_admin`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembelian_carbon_credits`
--
ALTER TABLE `pembelian_carbon_credits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pembelian_carbon_credit` (`kode_pembelian_carbon_credit`),
  ADD KEY `kode_manager` (`kode_manager`),
  ADD KEY `kode_kompensasi` (`kode_kompensasi`),
  ADD KEY `kode_admin` (`kode_admin`),
  ADD KEY `kode_penyedia` (`kode_penyedia`);

--
-- Indexes for table `penggunas`
--
ALTER TABLE `penggunas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_user` (`kode_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `penyedia_carbon_credits`
--
ALTER TABLE `penyedia_carbon_credits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_penyedia` (`kode_penyedia`),
  ADD KEY `kode_penyedia_2` (`kode_penyedia`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emisi_carbons`
--
ALTER TABLE `emisi_carbons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faktor_emisis`
--
ALTER TABLE `faktor_emisis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kompensasi_emisi`
--
ALTER TABLE `kompensasi_emisi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifikasis`
--
ALTER TABLE `notifikasis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian_carbon_credits`
--
ALTER TABLE `pembelian_carbon_credits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penggunas`
--
ALTER TABLE `penggunas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penyedia_carbon_credits`
--
ALTER TABLE `penyedia_carbon_credits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`kode_pembelian_carbon_credit`) REFERENCES `pembelian_carbon_credits` (`kode_pembelian_carbon_credit`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`kode_manager`) REFERENCES `managers` (`kode_manager`) ON DELETE CASCADE;

--
-- Constraints for table `emisi_carbons`
--
ALTER TABLE `emisi_carbons`
  ADD CONSTRAINT `emisi_carbons_ibfk_1` FOREIGN KEY (`kode_manager`) REFERENCES `managers` (`kode_manager`) ON DELETE CASCADE,
  ADD CONSTRAINT `emisi_carbons_ibfk_2` FOREIGN KEY (`kode_user`) REFERENCES `penggunas` (`kode_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `emisi_carbons_ibfk_3` FOREIGN KEY (`kode_admin`) REFERENCES `admins` (`kode_admin`) ON DELETE CASCADE,
  ADD CONSTRAINT `emisi_carbons_ibfk_4` FOREIGN KEY (`kategori_emisi_karbon`,`sub_kategori`) REFERENCES `faktor_emisis` (`kategori_emisi_karbon`, `sub_kategori`);

--
-- Constraints for table `kompensasi_emisi`
--
ALTER TABLE `kompensasi_emisi`
  ADD CONSTRAINT `kompensasi_emisi_ibfk_1` FOREIGN KEY (`kode_emisi_karbon`) REFERENCES `emisi_carbons` (`kode_emisi_karbon`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD CONSTRAINT `notifikasis_ibfk_1` FOREIGN KEY (`kode_user`) REFERENCES `penggunas` (`kode_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasis_ibfk_2` FOREIGN KEY (`kode_admin`) REFERENCES `admins` (`kode_admin`) ON DELETE CASCADE;

--
-- Constraints for table `pembelian_carbon_credits`
--
ALTER TABLE `pembelian_carbon_credits`
  ADD CONSTRAINT `pembelian_carbon_credits_ibfk_1` FOREIGN KEY (`kode_manager`) REFERENCES `managers` (`kode_manager`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembelian_carbon_credits_ibfk_2` FOREIGN KEY (`kode_kompensasi`) REFERENCES `kompensasi_emisi` (`kode_kompensasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembelian_carbon_credits_ibfk_3` FOREIGN KEY (`kode_admin`) REFERENCES `admins` (`kode_admin`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembelian_carbon_credits_ibfk_4` FOREIGN KEY (`kode_penyedia`) REFERENCES `penyedia_carbon_credits` (`kode_penyedia`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
