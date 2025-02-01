-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 01:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('Low','Medium','High') NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('In Progress','Completed') DEFAULT 'In Progress',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `priority`, `due_date`, `status`, `created_at`) VALUES
(1, 1, 'IPT project', 'asp.net (mvc)', 'High', '2025-02-03', 'In Progress', '2025-01-29 11:54:48'),
(2, 1, 'DevOps', 'system', 'High', '2025-02-04', 'In Progress', '2025-01-29 11:55:27'),
(3, 2, 'IPT', 'project system', 'Medium', '2025-02-04', 'In Progress', '2025-01-29 11:59:05'),
(4, 2, 'DevOps', 'system', 'High', '2025-02-03', 'In Progress', '2025-01-29 11:59:27'),
(5, 2, 'projects', '', 'Low', '2025-02-07', 'In Progress', '2025-01-29 12:00:14'),
(6, 3, 'ipt', 'system', 'High', '2025-02-07', 'In Progress', '2025-01-29 12:00:54'),
(7, 3, 'devops', 'commit & push', 'High', '2025-02-06', 'In Progress', '2025-01-29 12:01:19'),
(8, 4, 'ipt ipt', 'project', 'High', '2025-02-05', 'In Progress', '2025-01-29 12:01:59'),
(9, 4, 'devOps', 'systemmm', 'High', '2025-02-04', 'In Progress', '2025-01-29 12:02:19'),
(10, 5, 'Project ipt', 'system', 'High', '2025-02-06', 'In Progress', '2025-01-29 12:03:05'),
(11, 5, 'devops', 'system', 'High', '2025-02-05', 'In Progress', '2025-01-29 12:03:21'),
(12, 6, 'ipt', 'project system', 'High', '2025-02-07', 'In Progress', '2025-01-29 12:04:09'),
(13, 6, 'DevOps', 'project', 'High', '2025-02-06', 'In Progress', '2025-01-29 12:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Beyama', '$2y$10$2CuwTmRLqzWNRfJRvnfVXOpKmEn4lp8VT/ww/pURo7kHeszmNh8RO', '2025-01-29 11:54:11'),
(2, 'Monica', '$2y$10$Id/oQnVriK5hv0C0xK4mj.IAHxpdTU8.NiFebVAN84VJoQY4YwQLu', '2025-01-29 11:58:42'),
(3, 'Rachelle', '$2y$10$R7K6iAYJcPDodMeR4qMe/emFGdYDx.8bvAqCMfG2HVnZ5vTwNQRrq', '2025-01-29 12:00:36'),
(4, 'Romel', '$2y$10$TjOMJCFt40Avbcby/tT/fOAYwNnMo9KxxmocTbZJlLdrRyyjI6cEq', '2025-01-29 12:01:39'),
(5, 'jolas', '$2y$10$q40zy/LfHkPSqsffLjYQAeU.1lrveHo3c1lAle86kiEWIrQqIeWsy', '2025-01-29 12:02:36'),
(6, 'kayle', '$2y$10$ew1AsmoDkB6NivafY7.DyO30DaKNzWGoc6XWkvMO6SPoY49VrQHJe', '2025-01-29 12:03:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
