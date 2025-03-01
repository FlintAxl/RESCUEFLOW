-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 03:05 AM
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
-- Database: `rescuenet`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `incident_id` int(11) DEFAULT NULL,
  `alert_time` datetime DEFAULT current_timestamp(),
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Available','In Use','Maintenance','Damaged') DEFAULT 'Available',
  `last_maintenance_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `asset_name`, `description`, `status`, `last_maintenance_date`) VALUES
(9, 'qdwd1', 'sqs1', 'In Use', '2025-03-08'),
(10, 'gig1`', 'qsq1', 'Available', '2025-01-30');

-- --------------------------------------------------------

--
-- Table structure for table `assets_image`
--

CREATE TABLE `assets_image` (
  `asset_id` int(11) NOT NULL,
  `img_path` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets_image`
--

INSERT INTO `assets_image` (`asset_id`, `img_path`) VALUES
(5, 'asset/images/celetaria_Completed Time.png'),
(6, 'asset/images/celetaria_starttime_Activity 6.5.1.png'),
(7, 'asset/images/1.jpg'),
(9, 'asset/images/7EqlRZh.jpg'),
(9, 'asset/images/8.jpg'),
(10, 'asset/images/WIN_20250207_08_59_19_Pro.jpg'),
(10, 'asset/images/WIN_20250207_08_59_23_Pro.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`) VALUES
(1, 'Bagumbayan'),
(2, 'Bambang'),
(3, 'Calzada'),
(19, 'Cembo'),
(20, 'Central Bicutan'),
(21, 'Central Signal Village'),
(4, 'Comembo'),
(22, 'East Rembo'),
(23, 'Fort Bonifacio'),
(5, 'Hagonoy'),
(6, 'Ibayo-Tipas'),
(24, 'Katuparan'),
(7, 'Ligid-Tipas'),
(8, 'Lower Bicutan'),
(25, 'Maharlika Village'),
(10, 'Napindan'),
(9, 'New Lower Bicutan'),
(26, 'North Daang Hari'),
(27, 'North Signal Village'),
(11, 'Palingon'),
(12, 'Pembo'),
(28, 'Pinagsama'),
(29, 'Pitogo'),
(30, 'Post Proper Northside'),
(31, 'Post Proper Southside'),
(13, 'Rizal'),
(14, 'San Miguel'),
(15, 'Santa Ana'),
(32, 'South Cembo'),
(33, 'South Daang Hari'),
(34, 'South Signal Village'),
(35, 'Tanyag'),
(16, 'Tuktukan'),
(36, 'Upper Bicutan'),
(17, 'Ususan'),
(18, 'Wawa'),
(37, 'West Rembo'),
(38, 'Western Bicutan');

-- --------------------------------------------------------

--
-- Table structure for table `dispatches`
--

CREATE TABLE `dispatches` (
  `disp_id` int(11) NOT NULL,
  `incident_id` int(11) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `dispatched_unit` varchar(100) NOT NULL,
  `dispatched_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_id` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatches`
--

INSERT INTO `dispatches` (`disp_id`, `incident_id`, `location`, `dispatched_unit`, `dispatched_at`, `status_id`) VALUES
(1, 0, 'taguig tup', 'Firetruck', '2025-02-26 06:21:35', 3),
(2, 0, 'western bicutan', 'Firetruck', '2025-02-26 06:26:48', 3),
(3, NULL, 'market market', 'Firetruck', '2025-02-26 09:38:55', 3),
(4, NULL, 'tup taguig', 'Firetruck', '2025-02-26 09:39:19', 3),
(5, NULL, 'blk 131 lt 1 taguig city upper bicutan', 'Firetruck', '2025-02-26 09:39:41', 3),
(6, NULL, 'tenement', 'Firetruck', '2025-02-26 09:41:55', 3),
(7, NULL, 'gladiola brgy rizal', 'Firetruck', '2025-02-26 09:43:40', 3),
(8, NULL, 'gladiola brgy rizal', 'Firetruck', '2025-02-26 09:54:14', 3),
(9, NULL, 'arc south taguig', 'Firetruck', '2025-02-27 11:59:44', 3),
(10, NULL, 'bicutan', 'Firetruck', '2025-02-27 12:01:04', 3),
(11, NULL, 'arc south taguig', 'Firetruck', '2025-02-27 13:15:12', 3),
(12, NULL, 'arc south taguig', 'Firetruck', '2025-02-28 14:07:10', 3),
(13, NULL, 'bicutan', 'Firetruck', '2025-02-28 14:36:17', 3),
(14, NULL, 'arc south taguig', 'Firetruck', '2025-03-01 02:04:03', 3);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_details`
--

CREATE TABLE `emergency_details` (
  `id` int(11) NOT NULL,
  `dispatch_id` int(11) NOT NULL,
  `what` text NOT NULL,
  `where` text NOT NULL,
  `why` text NOT NULL,
  `caller_name` varchar(255) NOT NULL,
  `caller_phone` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_details`
--

INSERT INTO `emergency_details` (`id`, `dispatch_id`, `what`, `where`, `why`, `caller_name`, `caller_phone`, `timestamp`) VALUES
(1, 1, 'fire', 'aksakls', 'arson', 'beatrice', '121221', '2025-02-27 13:22:33'),
(2, 1, 'fire', 'aksakls', 'arson', 'beatrice', '121221', '2025-02-27 13:26:39'),
(4, 1, 'fire', 'aksakls', 'arson', 'beatrice', '121221', '2025-02-27 13:27:47'),
(5, 1, 'afire', 'lo antips', 'ndnd', 'bri', '121212', '2025-02-27 13:28:06'),
(6, 1, 'afire', 'lo antips', 'ndnd', 'bri', '121212', '2025-02-27 13:31:59'),
(7, 1, 'wdklwd apy', 'jan', 'ooqsiqs', 'rose', '10930193', '2025-02-28 12:33:28'),
(8, 1, 'wdklwd apy', 'jan', 'ooqsiqs', 'rose', '10930193', '2025-02-28 12:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` int(11) NOT NULL,
  `incident_type` varchar(100) NOT NULL,
  `severity_id` int(11) DEFAULT NULL,
  `location` text NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) NOT NULL,
  `reported_time` datetime DEFAULT current_timestamp(),
  `status_id` int(11) DEFAULT NULL,
  `cause` enum('Electrical Faults','Unattended Cooking','Candles & Open Flames','Smoking Indoors','Gas Leaks','Flammable Liquids','Children Playing with Fire','Heating Equipment','Faulty Appliances','Arson') DEFAULT NULL,
  `attachments` text DEFAULT NULL,
  `actions_taken` varchar(255) DEFAULT NULL,
  `barangay_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`incident_id`, `incident_type`, `severity_id`, `location`, `address`, `reported_by`, `reported_time`, `status_id`, `cause`, `attachments`, `actions_taken`, `barangay_id`) VALUES
(50, 'fire1', 5, '', '1', '1', '2025-02-28 21:22:19', NULL, 'Arson', '../uploads/1740748939_422085094_344462838558740_416192920533265070_n.png', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `shift_schedule` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `first_name`, `last_name`, `email`, `phone`, `role_id`, `rank_id`, `shift_schedule`, `image`) VALUES
(2, 'jemuel1', 'malaga', 'jem@gmail.com', '0916784', 1, 2, NULL, '7EqlRZh.jpg'),
(4, 'ashly', 'celetaria', 'ashly@gmail.com', '09167841212', 1, 1, NULL, '61hh60jmEnL.jpg'),
(8, 'babette', 'celetaria', 'babette@gmail.com', '0916', 1, 1, NULL, '2.jpg'),
(9, 'nel1', 'alab1', 'nel@gmail.com', '65656', 4, 1, NULL, 'celetaria_Start time.png'),
(10, 'daniel1', 'magpantay', 'daniel@gmail.com', '9090980', 2, 5, NULL, '5.jpg'),
(12, 'Beatrice', 'Balagtas', 'bea.balagtas127@gmail.com', '0909088', 2, 3, NULL, '1740750252_61hh60jmEnL.jpg'),
(13, 'FLINT AXL', 'CELETARIA', 'flintaxl.celetaria@gmail.com', '01290192', 2, 4, NULL, '1740751555_po.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `members_image`
--

CREATE TABLE `members_image` (
  `member_id` int(11) NOT NULL,
  `img_path` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `rank_id` int(11) NOT NULL,
  `rank_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`rank_id`, `rank_name`) VALUES
(4, 'Captain'),
(5, 'Chief'),
(2, 'Firefighter First Class'),
(3, 'Lieutenant'),
(1, 'Probationary Firefighter');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(4, 'Administrator'),
(3, 'Dispatcher'),
(1, 'Firefighter'),
(2, 'Team Leader');

-- --------------------------------------------------------

--
-- Table structure for table `severity`
--

CREATE TABLE `severity` (
  `id` int(11) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `severity`
--

INSERT INTO `severity` (`id`, `level`) VALUES
(1, 'low'),
(2, 'moderate'),
(3, 'high'),
(4, 'very high'),
(5, 'extreme');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `member_id`, `start_time`, `end_time`, `assigned_by`, `created_at`) VALUES
(7, 12, '2025-02-28 21:54:00', '2025-03-01 21:54:00', 1, '2025-02-28 13:54:33'),
(8, 13, '2025-02-28 22:06:00', '2025-03-01 22:06:00', 1, '2025-02-28 14:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'Pending'),
(2, 'In progress'),
(3, 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `training_id` int(11) NOT NULL,
  `training_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `scheduled_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainings`
--

INSERT INTO `trainings` (`training_id`, `training_name`, `description`, `scheduled_date`) VALUES
(2, 'bimbang', 'qw', '2025-02-14'),
(3, 'bembangan', 'ok', '2025-02-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `username`, `password_hash`, `member_id`, `role_id`) VALUES
(1, 'axl@gmail.com', 'axl', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, 4),
(2, 'bea@gmail.com', 'bea', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users_image`
--

CREATE TABLE `users_image` (
  `user_id` int(11) NOT NULL,
  `img_path` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `incident_id` (`incident_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD UNIQUE KEY `barangay_name` (`barangay_name`);

--
-- Indexes for table `dispatches`
--
ALTER TABLE `dispatches`
  ADD PRIMARY KEY (`disp_id`);

--
-- Indexes for table `emergency_details`
--
ALTER TABLE `emergency_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispatch_id` (`dispatch_id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD KEY `reported_by` (`reported_by`),
  ADD KEY `fk_severity` (`severity_id`),
  ADD KEY `fk_status` (`status_id`),
  ADD KEY `fk_barangay` (`barangay_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `rank_id` (`rank_id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`rank_id`),
  ADD UNIQUE KEY `rank_name` (`rank_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `severity`
--
ALTER TABLE `severity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`training_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `member_id` (`member_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `dispatches`
--
ALTER TABLE `dispatches`
  MODIFY `disp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `emergency_details`
--
ALTER TABLE `emergency_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `training_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_details`
--
ALTER TABLE `emergency_details`
  ADD CONSTRAINT `emergency_details_ibfk_1` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`disp_id`);

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `fk_barangay` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_severity` FOREIGN KEY (`severity_id`) REFERENCES `severity` (`id`),
  ADD CONSTRAINT `fk_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `members_ibfk_2` FOREIGN KEY (`rank_id`) REFERENCES `ranks` (`rank_id`) ON DELETE SET NULL;

--
-- Constraints for table `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `shifts_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shifts_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
