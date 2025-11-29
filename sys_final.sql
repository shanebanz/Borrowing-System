-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2025 at 03:30 PM
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
-- Database: `sys_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessories`
--

CREATE TABLE `accessories` (
  `accessory_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_accessories`
--

CREATE TABLE `borrow_accessories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `accessory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_transactions`
--

CREATE TABLE `borrow_transactions` (
  `borrow_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `borrow_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL,
  `returned` tinyint(1) DEFAULT 0,
  `return_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_transactions`
--

INSERT INTO `borrow_transactions` (`borrow_id`, `user_id`, `equipment_id`, `quantity`, `borrow_date`, `due_date`, `returned`, `return_date`, `created_at`) VALUES
(9, 2, 1, 1, '2025-11-29 05:35:44', NULL, 0, NULL, '2025-11-29 05:35:44'),
(10, 2, 2, 2, '2025-11-29 05:36:24', NULL, 0, NULL, '2025-11-29 05:36:24'),
(12, 3, 5, 1, '2025-11-29 05:47:41', NULL, 0, NULL, '2025-11-29 05:47:41'),
(13, 3, 3, 1, '2025-11-29 05:59:42', NULL, 0, NULL, '2025-11-29 05:59:42'),
(14, 2, 10, 1, '2025-11-29 06:19:27', NULL, 0, NULL, '2025-11-29 06:19:27'),
(15, 2, 15, 1, '2025-11-29 06:19:35', NULL, 0, NULL, '2025-11-29 06:19:35'),
(16, 2, 17, 2, '2025-11-29 06:19:44', NULL, 1, '2025-11-29 14:23:13', '2025-11-29 06:19:44'),
(17, 2, 13, 1, '2025-11-29 06:19:55', NULL, 0, NULL, '2025-11-29 06:19:55'),
(18, 2, 7, 1, '2025-11-29 06:20:03', NULL, 0, NULL, '2025-11-29 06:20:03'),
(19, 2, 4, 1, '2025-11-29 06:20:13', NULL, 0, NULL, '2025-11-29 06:20:13'),
(20, 2, 9, 1, '2025-11-29 06:20:20', NULL, 0, NULL, '2025-11-29 06:20:20'),
(21, 2, 11, 1, '2025-11-29 06:22:45', NULL, 1, '2025-11-29 14:28:32', '2025-11-29 06:22:45'),
(22, 3, 10, 2, '2025-11-29 06:25:54', NULL, 0, NULL, '2025-11-29 06:25:54'),
(23, 3, 16, 1, '2025-11-29 06:26:44', NULL, 0, NULL, '2025-11-29 06:26:44'),
(24, 3, 7, 1, '2025-11-29 06:26:56', NULL, 1, '2025-11-29 14:28:03', '2025-11-29 06:26:56'),
(25, 2, 14, 1, '2025-11-29 06:28:45', NULL, 0, NULL, '2025-11-29 06:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `accessories` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `total_quantity` int(11) NOT NULL,
  `available_quantity` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `equipment_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equipment_id`, `name`, `description`, `accessories`, `category`, `total_quantity`, `available_quantity`, `is_active`, `equipment_image`, `created_at`) VALUES
(1, 'Router', 'Cable', NULL, 'Electronics', 10, 4, 1, NULL, '2025-11-28 15:42:44'),
(2, 'Projector', 'Epson Projector XGA', NULL, 'Electronics', 5, 3, 1, NULL, '2025-11-28 15:42:44'),
(3, 'Mac Keyboard', NULL, 'USB-C cable', 'Electronics', 15, 14, 1, NULL, '2025-11-29 09:54:47'),
(4, 'Mac Mouse', NULL, 'USB-C cable, charging dock', 'Electronics', 22, 21, 1, NULL, '2025-11-29 09:54:47'),
(5, 'HDMI Cable', NULL, 'Cable ties', 'Electronics', 18, 17, 1, NULL, '2025-11-29 09:54:47'),
(6, 'Wireless Mouse', 'Logitech Wireless Mouse', 'USB receiver, batteries', 'Electronics', 20, 20, 1, NULL, '2025-11-29 14:08:54'),
(7, 'USB Hub', '4-Port USB 3.0 Hub', 'Power adapter', 'Electronics', 12, 11, 1, NULL, '2025-11-29 14:08:54'),
(8, 'Webcam', 'HD 1080p Webcam', 'Tripod mount', 'Electronics', 8, 8, 1, NULL, '2025-11-29 14:08:54'),
(9, 'Extension Cord', '5-Meter Extension Cord', 'Cable organizer', 'Electronics', 25, 24, 1, NULL, '2025-11-29 14:08:54'),
(10, 'Network Switch', '8-Port Gigabit Switch', 'Ethernet cables', 'Electronics', 6, 3, 1, NULL, '2025-11-29 14:08:54'),
(11, 'Laptop Stand', 'Adjustable Laptop Stand', 'Cooling pad', 'Accessories', 10, 10, 1, NULL, '2025-11-29 14:08:54'),
(12, 'Monitor Stand', 'Dual Monitor Stand', 'Cable management', 'Accessories', 7, 7, 1, NULL, '2025-11-29 14:08:54'),
(13, 'Headset', 'USB Noise Cancelling Headset', 'Microphone windscreen', 'Electronics', 15, 14, 1, NULL, '2025-11-29 14:08:54'),
(14, 'Power Bank', '20000mAh Power Bank', 'USB cables', 'Electronics', 18, 17, 1, NULL, '2025-11-29 14:08:54'),
(15, 'Document Camera', 'HD Document Camera', 'USB cable, stand', 'Electronics', 4, 3, 1, NULL, '2025-11-29 14:08:54'),
(16, 'Laser Pointer', 'Wireless Presenter with Laser', 'Batteries, pouch', 'Accessories', 9, 8, 1, NULL, '2025-11-29 14:08:54'),
(17, 'Cable Organizer', 'Cable Management Box', NULL, 'Accessories', 14, 14, 1, NULL, '2025-11-29 14:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_items`
--

CREATE TABLE `equipment_items` (
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `item_code` varchar(100) NOT NULL,
  `condition_status` varchar(50) DEFAULT 'Good',
  `is_available` tinyint(1) DEFAULT 1,
  `is_usable` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_items`
--

INSERT INTO `equipment_items` (`item_id`, `equipment_id`, `item_code`, `condition_status`, `is_available`, `is_usable`) VALUES
(1, 1, 'Router', 'Good', 1, 1),
(2, 2, 'Projector', 'Good', 1, 1),
(3, 3, 'MKB-001', 'Good', 1, 1),
(4, 3, 'MKB-002', 'Good', 1, 1),
(5, 4, 'MMOUSE-001', 'Good', 1, 1),
(6, 4, 'MMOUSE-002', 'Good', 1, 1),
(7, 5, 'HDMI-001', 'Good', 1, 1),
(8, 5, 'HDMI-002', 'Good', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_logs`
--

CREATE TABLE `report_logs` (
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `report_type` varchar(100) DEFAULT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending' CHECK (`status` in ('Pending','Cancelled','Completed')),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `equipment_id`, `quantity`, `reservation_date`, `pickup_date`, `return_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2025-11-29 06:00:15', '2025-12-02', NULL, 'pending', 'For Demo', '2025-11-29 06:00:15', '2025-11-29 06:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `return_logs`
--

CREATE TABLE `return_logs` (
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `condition_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password_hash` text NOT NULL,
  `role` varchar(20) NOT NULL CHECK (`role` in ('ITSO','Associate','Student')),
  `is_active` tinyint(1) DEFAULT 1,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `profile_image`, `password_hash`, `role`, `is_active`, `is_verified`, `verification_token`, `reset_token`, `reset_token_expires`, `created_at`) VALUES
(1, 'Ely', 'Buendia', 'earthworm73390@mailshan.com', '1764408425_71e444669c9ab819aa1a.png', '$2y$10$SbApSbdKjJX9jNEge.T7guvqAOglezfTO4cd4tYf6F03racr0BQlS', 'ITSO', 1, 1, NULL, NULL, NULL, '2025-11-29 01:23:17'),
(2, 'Test', 'Student', 'lemeyiv176@httpsu.com', '1764423235_0a1b29bffc6b1ec38749.png', '$2y$10$Txokc16YjeriQQjSvNHZj.UK3N.5qO9FhHKdhp/OLb9BTD6DZF51y', 'Student', 1, 1, NULL, NULL, NULL, '2025-11-29 05:33:55'),
(3, 'Test', 'Associate', '2bof8gmmy9@yzcalo.com', '1764423442_1940a054dec8126ff62a.png', '$2y$10$GwYe7MV0HzyJ7735UqZlxeOJxp45p4SkpYr1QKnkKWoX11xdJ9qR6', 'Associate', 1, 1, NULL, NULL, NULL, '2025-11-29 05:37:22'),
(4, 'Test', 'ITSO', 'bison158@draughtier.com', '1764424861_bb843199de4086bb4a3e.jpg', '$2y$10$sWPRU8pwPP3oUGxGIfPCRO13lPATXmAcOs8psqaK7n2A/0NMBMsae', 'ITSO', 1, 1, NULL, NULL, NULL, '2025-11-29 06:01:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessories`
--
ALTER TABLE `accessories`
  ADD PRIMARY KEY (`accessory_id`);

--
-- Indexes for table `borrow_accessories`
--
ALTER TABLE `borrow_accessories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow_transactions`
--
ALTER TABLE `borrow_transactions`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`equipment_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`equipment_id`);

--
-- Indexes for table `equipment_items`
--
ALTER TABLE `equipment_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_code` (`item_code`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `report_logs`
--
ALTER TABLE `report_logs`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `return_logs`
--
ALTER TABLE `return_logs`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accessories`
--
ALTER TABLE `accessories`
  MODIFY `accessory_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_accessories`
--
ALTER TABLE `borrow_accessories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_transactions`
--
ALTER TABLE `borrow_transactions`
  MODIFY `borrow_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `equipment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `equipment_items`
--
ALTER TABLE `equipment_items`
  MODIFY `item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_logs`
--
ALTER TABLE `report_logs`
  MODIFY `report_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `return_logs`
--
ALTER TABLE `return_logs`
  MODIFY `return_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrow_transactions`
--
ALTER TABLE `borrow_transactions`
  ADD CONSTRAINT `borrow_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `borrow_transactions_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
