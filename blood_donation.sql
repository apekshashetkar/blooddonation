-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2026 at 11:44 AM
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
-- Database: `blood_donation`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `units_required` int(11) NOT NULL,
  `hospital` varchar(200) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `urgency` enum('Low','Medium','High','Critical') DEFAULT 'Medium',
  `status` enum('Pending','Fulfilled','Cancelled') DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `last_donation_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `blood_group`, `age`, `phone`, `email`, `address`, `last_donation_date`, `user_id`, `created_at`) VALUES
(3, 'Ansh', 'A+', 18, '1234556777', 'ansh@gmail.com', 'mapsa', '2010-05-06', 4, '2025-11-22 05:41:47'),
(4, 'apeksha', 'B+', 21, '1234567899', 'apeksha@gmail.com', 'mapsa', '2003-06-04', 5, '2025-11-22 05:46:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Apeksha', 'apeksha@mail', 'apek', '2025-11-22 05:30:37', 'admin'),
(4, 'Ansh', 'ansh@gmail.com', '$2y$10$4N8/TYl1oeW8oTI.6/wPAevjbat.K3ajibTOo4gH5gYGSx4fL0qfe', '2025-11-22 05:39:32', 'user'),
(5, 'Apeksha R', 'apek@gmail.com', '$2y$10$N94g2NhRZb.uHyKdWSDV0.6SNc7Z2IXHkVr6xDx52ttxk5BGNJCma', '2025-11-22 05:45:02', 'user'),
(8, 'Apeksha R Shetkar', 'ape@gmail.com', '$2y$10$WRnoNEsVxznr7lII3EP.I.y6iYVZ/j5uu7ue16TOW6sLxUbOutKza', '2025-11-22 06:23:51', 'user'),
(12, 'Shetkar apeksha', 'apeks@gmail.com', '$2y$10$.nklQT1YkFU1YMlOchhea.smPBBlAjati5wrg47Y4PdCklNP6aydO', '2025-11-22 06:26:27', 'user'),
(13, 'shetkar shreya', 'shetkar4@gmail.com', '$2y$10$XK34BbvBIuXizs2ieq2FuOd6y4JWHDCB2HnGVbs8La7ktmOUPiTUm', '2025-11-22 06:39:14', 'user'),
(15, 'NEHA', 'anshshetkar@gmail.com', '$2y$10$YJxaiheQTschJ3VfJkZz5.0UiQOsUzc67S9DbILib/f77TJD3IvPq', '2026-10-11 18:40:43', 'user'),
(16, 'adminuser', 'admin@gmail.com', 'admin123', '2026-10-11 19:15:37', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `blood_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
