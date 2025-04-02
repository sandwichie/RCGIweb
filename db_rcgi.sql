-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 07:12 AM
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
-- Database: `db_rcgi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_ID`, `username`, `password`) VALUES
(0, 'lapogi', 'AngpoginiL@28'),
(204, 'rcgiadmin3', 'Lepardoadmin2'),
(212, 'rcgiadmin4', '$2y$10$N3ND2jIE5lxGI2QUGfDp0.cQBhI8N72WruOjtk6u8ncvru4.5icEm'),
(687, 'rcgiadmin2', 'Mangadangadmin1'),
(1001, 'rcgiadmin1', '$2y$10$fepgjTRUijGa3cS1Q9Mwv.aUDJoINqWu82u4KoVX7OtoFbdjf7D8m');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_ID` int(11) NOT NULL,
  `employee_ID` int(11) DEFAULT NULL,
  `device_ID` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_ID`, `employee_ID`, `device_ID`, `date`, `time_in`, `time_out`) VALUES
(13, 1, NULL, '2025-03-08', '18:32:14', '18:56:26'),
(14, 1, NULL, '2025-03-09', '14:13:36', '14:14:12'),
(15, 2, NULL, '2025-03-22', '20:59:23', '21:01:42'),
(16, 3, NULL, '2025-03-23', '08:00:15', '05:01:26'),
(17, 2, NULL, '2025-03-23', '09:01:15', '06:01:26'),
(18, 2, NULL, '2025-03-24', '14:19:57', '14:20:39'),
(19, 4, NULL, '2025-03-25', '12:03:26', '12:05:12');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `device_ID` int(11) NOT NULL,
  `fingerprint_ID` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_ID` int(11) NOT NULL,
  `admin_ID` int(11) DEFAULT NULL,
  `fingerprint_ID` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `shift_start_time` time DEFAULT NULL,
  `shift_end_time` time DEFAULT NULL,
  `org` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_ID`, `admin_ID`, `fingerprint_ID`, `name`, `password`, `photo`, `hire_date`, `shift_start_time`, `shift_end_time`, `org`) VALUES
(2, 1001, 1234, 'Sharleen Ahn Olaguir', 'sharleenemployee', 'employee_image/67cd706c92744_˚ 🐰 ♡ ⋆｡˚ ❀.jpg', '2025-03-09', '09:00:00', '06:00:00', 'RCGI'),
(3, 1001, 6545, 'Laurence Andrew Gasmen', NULL, 'employee_image/67cef14155e6f_369553755_335531855858508_8337585010068833675_n.jpg', '2025-03-10', '09:00:00', '06:00:00', 'Terraco'),
(4, 1001, 9632, 'Shaima Mangadang', NULL, 'employee_image/67e15428ee6ca_Danielle.jpg', '2003-06-25', '08:00:00', '06:00:00', 'RCGI'),
(5, 1001, 6542, 'Francois', NULL, 'employee_image/67e22a35442f8_Screenshot 2023-05-02 143817.png', '2025-03-25', '08:00:00', '05:00:00', 'RCGI');

-- --------------------------------------------------------

--
-- Table structure for table `emp_forgotpass`
--

CREATE TABLE `emp_forgotpass` (
  `request_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_forgotpass`
--

INSERT INTO `emp_forgotpass` (`request_id`, `employee_id`, `name`, `reason`, `status`) VALUES
(1, 2, 'Sharleen Ahn Olaguir', 'Forgot Password', 'Pending'),
(2, 3, 'Laurence Andrew Gasmen', 'Account Locked', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `fingerprint`
--

CREATE TABLE `fingerprint` (
  `fingerprint_ID` int(11) NOT NULL,
  `enroll_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `on_leave`
--

CREATE TABLE `on_leave` (
  `on_leave_ID` int(11) NOT NULL,
  `employee_ID` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_ID` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `time_zone` varchar(50) DEFAULT NULL,
  `late_threshold` int(11) DEFAULT NULL,
  `admin_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_ID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_ID`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`device_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_ID`);

--
-- Indexes for table `emp_forgotpass`
--
ALTER TABLE `emp_forgotpass`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `fingerprint`
--
ALTER TABLE `fingerprint`
  ADD PRIMARY KEY (`fingerprint_ID`);

--
-- Indexes for table `on_leave`
--
ALTER TABLE `on_leave`
  ADD PRIMARY KEY (`on_leave_ID`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `emp_forgotpass`
--
ALTER TABLE `emp_forgotpass`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
