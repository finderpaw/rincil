-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2026 at 04:29 AM
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
-- Database: `pawfinder_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `health_drive`
--

CREATE TABLE `health_drive` (
  `id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `owner_name` varchar(150) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_drive`
--

INSERT INTO `health_drive` (`id`, `event_name`, `owner_name`, `contact_number`, `pet_name`, `pet_type`, `registration_date`) VALUES
(1, 'Free Anti-Rabies Vaccination Drive', 'Mikhaela Villanueva', '09054970091', 'Lorenz', 'Dog', '2026-11-06 08:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `health_drives`
--

CREATE TABLE `health_drives` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(100) NOT NULL,
  `location` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Upcoming','Ongoing','Completed','Cancelled') DEFAULT 'Upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_drives`
--

INSERT INTO `health_drives` (`id`, `name`, `event_date`, `event_time`, `location`, `description`, `image`, `status`, `created_at`) VALUES
(1, 'Free Anti-Rabies Vaccination Drive', '2026-11-15', '8:00 AM - 4:00 PM', 'Community Pet Center', 'Free anti-rabies vaccination for dogs and cats.', 'image/vacine.jpg', 'Upcoming', '2026-11-06 08:11:06'),
(2, 'Free Pet Health Checkup', '2026-11-20', '9:00 AM - 3:00 PM', 'Barangay Hall', 'Basic physical examination and health assessment.', 'image/checkup.jpg', 'Upcoming', '2026-11-06 08:11:06'),
(3, 'Pet Deworming Program', '2026-11-25', '8:00 AM - 2:00 PM', 'Pawfinder Partner Shelter', 'Free deworming services for rescued pets.', 'image/deworm.png', 'Upcoming', '2026-11-06 08:11:06'),
(4, 'Spay and Neuter Mission', '2026-12-05', '8:00 AM - 5:00 PM', 'Community Veterinary Center', 'Affordable spaying and neutering services.', 'image/spay.jpg', 'Upcoming', '2026-11-06 08:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `pet_species` varchar(50) NOT NULL,
  `breed` varchar(100) NOT NULL,
  `age` varchar(50) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `pet_photo` varchar(255) NOT NULL,
  `adoption_reason` text NOT NULL,
  `health_issues` text DEFAULT NULL,
  `pet_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `pet_name`, `pet_species`, `breed`, `age`, `barangay`, `pet_photo`, `adoption_reason`, `health_issues`, `pet_details`, `created_at`) VALUES
(1, 'Shan', 'dog', 'Golden Retriever', '2 Years', 'Brgy. Lakandula, Mabalacat City', 'uploads/1793951044_dog1.jpg', 'Unable to care', '', 'Minsan suplado pero makulit', '2026-11-06 07:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `registered`
--

CREATE TABLE `registered` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `role` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rescue_reports`
--

CREATE TABLE `rescue_reports` (
  `id` int(11) NOT NULL,
  `reporter_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `pet_condition` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Pending','In Progress','Rescued','Rejected') DEFAULT 'Pending',
  `report_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_reports`
--

INSERT INTO `rescue_reports` (`id`, `reporter_name`, `contact_number`, `location`, `pet_type`, `pet_condition`, `description`, `status`, `report_date`) VALUES
(1, 'Ela Villanueva', '09054970091', 'Brgy. Lakandula', 'Dog', 'Abandoned', 'huhu', 'Pending', '2026-11-06 09:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `venue` varchar(150) NOT NULL,
  `services` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `organizer` varchar(150) NOT NULL,
  `reminders` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Local',
  `account_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Approved',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `address`, `contact`, `password`, `role`, `account_status`, `created_at`) VALUES
(1, '', 'admin', 'pawfinder96@gmail.com', '', '', '$2y$10$KNWH7dDSvDsxjISOBCFowuf.le6BLmasBupJ1/FxpKq79PLetVwKO', 'admin', 'Approved', '2026-11-10 11:01:23'),
(2, 'rhencelpogi', 'rhencel', 'rhenceljohnyalung@gmail.com', 'mabalacat city', '09923669239', '$2y$10$tTx7K4uyGRbslgWdpQJfK.8.d9JRvY8Y.ERatowCMjWVtB231xSRK', 'Local', 'Approved', '2026-11-10 11:08:59'),
(3, 'Aaron', 'adopter', 'aggarcia0807@gmail.com', 'Mabalacat', '09287486958', '$2y$10$N6LpQYvAv7AOMrAT37lKL.VakRcvOxjV1nPAIHHqDKzLYEl9Lt702', 'Rescuer', 'Pending', '2026-11-10 11:23:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `health_drive`
--
ALTER TABLE `health_drive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `health_drives`
--
ALTER TABLE `health_drives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registered`
--
ALTER TABLE `registered`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `rescue_reports`
--
ALTER TABLE `rescue_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `health_drive`
--
ALTER TABLE `health_drive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `health_drives`
--
ALTER TABLE `health_drives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registered`
--
ALTER TABLE `registered`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rescue_reports`
--
ALTER TABLE `rescue_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
