-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 09:01 PM
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
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Mouse', ''),
(2, 'Keyboard', ''),
(3, 'Monitor', ''),
(4, 'Chair', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  `stock` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `stock`, `category_id`, `created_at`) VALUES
(1, 'Bloody v6 mouse', 'veri good gaming mouse', 10.99, '1794569926_1515871950_l20m01.jpg', 6, 1, '2025-09-09 08:43:12'),
(2, 'keyboard', 'very good gaming keyboard', 99.99, '4551622827_8719133711_midnight_sun.jpg', 6, 2, '2025-09-09 18:18:43'),
(3, 'Mouse Basic', 'Basic wired mouse', 9.99, 'default.png', 100, 1, '2025-09-09 18:41:26'),
(4, 'Mouse Wireless', 'Wireless mouse with USB receiver', 19.99, 'default.png', 80, 1, '2025-09-09 18:41:26'),
(5, 'Mouse Gaming', 'High DPI gaming mouse', 29.99, 'default.png', 60, 1, '2025-09-09 18:41:26'),
(6, 'Mouse Ergonomic', 'Ergonomic mouse for comfort', 24.99, 'default.png', 40, 1, '2025-09-09 18:41:26'),
(7, 'Keyboard Basic', 'Standard keyboard', 14.99, 'default.png', 90, 2, '2025-09-09 18:41:26'),
(8, 'Keyboard Mechanical', 'Mechanical keyboard with blue switches', 59.99, 'default.png', 50, 2, '2025-09-09 18:41:26'),
(9, 'Keyboard Wireless', 'Wireless keyboard', 34.99, 'default.png', 70, 2, '2025-09-09 18:41:26'),
(10, 'Keyboard Compact', 'Compact keyboard for travel', 19.99, 'default.png', 30, 2, '2025-09-09 18:41:26'),
(11, 'Monitor 24\"', '24-inch Full HD monitor', 129.99, 'default.png', 25, 3, '2025-09-09 18:41:26'),
(12, 'Monitor 27\"', '27-inch QHD monitor', 199.99, 'default.png', 15, 3, '2025-09-09 18:41:26'),
(13, 'Monitor Curved', 'Curved gaming monitor', 249.99, 'default.png', 10, 3, '2025-09-09 18:41:26'),
(14, 'Monitor UltraWide', '34-inch ultrawide monitor', 349.99, 'default.png', 8, 3, '2025-09-09 18:41:26'),
(15, 'Chair Basic', 'Basic office chair', 49.99, 'default.png', 20, 4, '2025-09-09 18:41:26'),
(16, 'Chair Ergonomic', 'Ergonomic mesh chair', 99.99, 'default.png', 15, 4, '2025-09-09 18:41:26'),
(17, 'Chair Executive', 'Executive leather chair', 149.99, 'default.png', 10, 4, '2025-09-09 18:41:26'),
(18, 'Chair Gaming', 'Gaming chair with lumbar support', 129.99, 'default.png', 12, 4, '2025-09-09 18:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `avatar`, `role`, `created_at`) VALUES
(1, 'kajus', 'uganda@gmail.com', '$2y$10$RO6/y9FxT.98V3ynU1zrVOPgK5yLO04TU0MS7R40zhlVjdNAzJBZm', 'Kajus uganda', '1446939174_', 'admin', '2025-09-09 07:52:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `NAME` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
