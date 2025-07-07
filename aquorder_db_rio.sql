-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2025 at 10:58 AM
-- Server version: 10.6.22-MariaDB-cll-lve
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aquorder_db_rio`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `notes`, `created_at`) VALUES
(7, 5, 5, '2025-06-11', '12:00:00', 'cancelled', 'Operasi dilakukan tertutup', '2025-06-10 04:39:33'),
(8, 6, 5, '2025-06-25', '19:00:00', 'completed', 'Pasien Menderita Kecerdasan yang Tidak Masuk Akal', '2025-06-17 11:38:55');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Ikatan Dokter Indonesia', 'Melayani sepenuh hati!', '2025-06-06 07:28:13'),
(2, 'Kesehatan', 'Jaga Kesehatan, mari kita lawan penyakit', '2025-06-07 04:28:39'),
(5, 'Rumah Sakit Jiwa', 'Mengobati segala penyakit Kejiwaan', '2025-06-09 06:43:10');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `department_id`, `specialization`, `phone`, `email`, `created_at`) VALUES
(5, 'dr. Andreas Rio Christian, MARS', 1, 'Ortopedi', '0862535362723', 'dr.rio@gmail.com', '2025-06-10 04:38:43'),
(6, 'Dr. Azzam Laksamana, NEPTUNe', 5, 'Jiwa Yang Tersakiti', '08123456789', 'Neptune@gmail.com', '2025-07-05 13:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('M','F','O') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `dob`, `gender`, `phone`, `address`, `created_at`) VALUES
(5, 'Sayyid Abdullah Azzam', '2005-01-10', 'M', '086254657585', 'Jakarta', '2025-06-10 04:37:52'),
(6, 'Naufal Satriani', '1990-02-20', 'M', '082472845834', 'Jakarta', '2025-06-17 11:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'andrsmll', '$2y$10$yBtH4Yi0ocHPsh7HdyKIh.2d/QXBz5Rwb8v99skTyGhlY1mIE9pIm', 'doctor', '2025-06-06 07:19:24'),
(2, 'jess', '$2y$10$nbuO1LsliL7jnAkIIxih0ezf31iGlEhKTpvGAd6vDx0e//MFyI.x2', 'staff', '2025-06-07 04:19:36'),
(3, 'user1', '$2y$10$JCuGY8BbpO30NflChWFfvO5YpmYtowSLS7TzU7PM1YsObR6xnfdim', 'doctor', '2025-06-07 04:21:42'),
(4, 'dr', '$2y$10$5sDjfwoLnvau9UZDE5dmce8Sv4kvGTljoE/EW6WhWR7HXTBriF/4m', 'staff', '2025-06-07 05:23:34'),
(5, 'uhuy', '$2y$10$hKm6jf0WaeenKwbkeQtH4O9uywE3JJS44AkcLxaQRRoHzASBeRPV2', 'staff', '2025-06-07 06:43:58'),
(6, 'rio', '$2y$10$KAekkkCimUmqE8tdOF9Kye5IHERpcJ1rKS3pS0NUzVy5P7IGJcB/y', 'doctor', '2025-06-07 06:52:34'),
(7, 'nopal', '$2y$10$NG2yDfrX80tEXva/2Cv7GeSQBWxN7gsJdtxFJ1pShv5QBO3QBiLwO', 'staff', '2025-06-07 07:16:02'),
(8, 'akunku', '$2y$10$wBbhV0.WrUJwhWP8ZRDPDOj8JIgZsonkhUR2CSDNXE8dfLWdkKC1y', 'admin', '2025-06-08 06:34:02'),
(9, 'Dods', '$2y$10$5SXqdfrEhFTUtcj6i0wIcuuJwNQwPPAeKUdRpPyqr.0v.j8SCuVDm', 'staff', '2025-06-09 06:40:10'),
(10, 'Adalah', '$2y$10$vB/EyGHZNamJ/GWjg4oXBeNLGrKs.fKMr6TUUrxxTq1N1ZHn5.nc6', 'admin', '2025-06-10 04:19:33'),
(11, 'Arya', '$2y$10$L3n4WU9sMp8pAMq8hiFnJeEntIJwxoCXa9ehbt8H/M3ObAOkjakXe', 'admin', '2025-06-11 04:38:33'),
(12, 'Akun 1', '$2y$10$zbSiwuOGxXva5KWms1dZcuu.23HACxBX1ZhOSsR6XgQLWDauUS7cC', 'staff', '2025-06-13 23:55:32'),
(13, 'admin', '$2y$10$U8VvYBtWJUIJe1qFrmN9De3XCtADjg7r2HZhLINu5l36oQft2FiPm', 'staff', '2025-06-13 23:57:30'),
(14, 'halo', '$2y$10$5zO1erkWhBG7Ki.1moqVRu2IyiNoYiyxjnjoGLtcktWIK3HkyZ5R6', 'staff', '2025-06-17 17:48:33'),
(15, 'gengs', '$2y$10$dO3EljeOi3XoM/R7B6JLmO.3aBJuUGcgIhvjE4JNfDOayDDHZLeZG', 'admin', '2025-06-23 05:27:34'),
(16, 'Dems', '$2y$10$8LoYOXqP0I1YX1XF/mOOaOpfY85v9auZqNeTozP9LGfwtA0PlHaGO', 'staff', '2025-06-23 07:54:32'),
(17, 'jeks', '$2y$10$8y8y0aGo0C8wyGyjMiavxu.UMNZPrCfzS/eCI7d2904xkrnS2Q6qe', 'staff', '2025-06-30 08:43:11'),
(18, 'Mons', '$2y$10$Z8hQwE9yTYOEDh5rHGDbs.SWzd1iOBSUk1X2kxt8RrPfmPy.JR2Sq', 'doctor', '2025-06-30 09:03:08'),
(19, 'Asep', '$2y$10$CnCQ.O/7uv7NzoWjzqFrEOBP0S6GKJLBZS0KafdMXMlb5OrEMriKG', 'admin', '2025-06-30 09:06:50'),
(20, 'hes', '$2y$10$bI/NhXdzrJQdCDF2MkJ6ROgfMBzvkVGIvTn1KbKLeEX/6T9IHa35O', 'staff', '2025-06-30 09:16:27'),
(21, 'kuluk', '$2y$10$g6RFYz/q4.ABd6fzRvbEQeEB7m90UOB0RQ./il4IO7vcPXaGpTI/m', 'doctor', '2025-07-01 06:01:45'),
(22, 'Fixe', '$2y$10$zIJiVvt203cLOgMBvLNpMuK7p98lGlPJNO0Y4ZBjuyPHfoCqabtba', 'staff', '2025-07-01 06:08:20'),
(23, 'Sandi_yudhaa', '$2y$10$MyL6cLbGb6/vCaXqkJoQ7ue31u7/FpwRq6pgKDVExqmqYZdMsGJne', 'staff', '2025-07-03 08:28:16'),
(24, 'demo', '$2y$10$UcO5MIqemxXbj5fLfcZiy.DpYGQfDdGAu.e21UOm.MkPS7n6qc5wq', 'doctor', '2025-07-05 13:50:11'),
(25, 'Deks', '$2y$10$5mZny1gan5fFEumXCI6T/updRYxe0QkWjfiudjaSmHSaKtx1FnewG', 'staff', '2025-07-06 04:58:59'),
(26, 'Iwan', '$2y$10$g5AwTKRrV1MjpMn6qSAXoO/Lt3rEY9x1emfy7e/iB4rCj0VKxxJE2', 'admin', '2025-07-06 15:01:29'),
(27, 'rio123', '$2y$10$w0mbW7ePS9RXq4Np2j094O/zBcDfqJ9ql4YUPZ4ILwcNyadNb2TO.', 'admin', '2025-07-07 03:12:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
