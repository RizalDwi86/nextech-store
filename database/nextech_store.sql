-- phpMyAdmin SQL Dump
-- NexTech Store - Database Schema Lengkap
-- Host: 127.0.0.1
-- Server version: 10.4.32-MariaDB

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Database: `nextech_store`
-- --------------------------------------------------------

-- --------------------------------------------------------
-- Tabel: users
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `alamat` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `alamat`, `created_at`) VALUES
(1, 'NexTech', 'admin@nextech.com', '$2y$10$Vct/hzIbovSgbFKTu.8U0.UeuRNm4UdMSldq7ozjNJELp440gJHbG', 'admin', 'Semarang', '2026-07-01 06:18:38'),
(2, 'Shikz', 'zal@gmail.com', '$2y$10$j2i./svGPQcniVVkL9xs0.ZBTciDzgEBnpbnWJOn7iC6qFNV7aYRK', 'customer', 'jl.petek', '2026-07-01 07:01:12');

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------
-- Tabel: products
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`id`, `nama`, `harga`, `stok`, `deskripsi`, `gambar`, `created_at`) VALUES
(1, 'Laptop ASUS VivoBook 15', 8500000.00, 15, 'Laptop ringan dengan prosesor Intel Core i5 generasi ke-12, RAM 8GB, SSD 512GB. Cocok untuk kerja dan kuliah.', NULL, NOW()),
(2, 'Samsung Galaxy A54', 4999000.00, 20, 'Smartphone Android dengan layar Super AMOLED 6.4 inci, kamera 50MP, baterai 5000mAh.', NULL, NOW()),
(3, 'Headphone Sony WH-1000XM4', 3750000.00, 8, 'Headphone wireless dengan noise cancelling terbaik di kelasnya. Baterai tahan hingga 30 jam.', NULL, NOW()),
(4, 'SSD Kingston 1TB', 850000.00, 30, 'SSD NVMe M.2 kecepatan baca 3500MB/s. Cocok untuk upgrade laptop dan PC.', NULL, NOW()),
(5, 'Keyboard Mechanical Rexus', 650000.00, 25, 'Keyboard gaming mechanical dengan switch blue, backlight RGB, dan anti-ghosting.', NULL, NOW());

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- --------------------------------------------------------
-- Tabel: orders
-- --------------------------------------------------------

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_penerima` varchar(150) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------
-- Tabel: order_details
-- --------------------------------------------------------

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------
-- Foreign Keys
-- --------------------------------------------------------

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
