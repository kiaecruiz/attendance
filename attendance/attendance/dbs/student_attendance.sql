-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2024 at 07:21 AM
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
-- Database: `student_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `adminName`, `username`, `password`) VALUES
(1, 'LDES', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_in` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` varchar(25) NOT NULL,
  `qr` varchar(2555) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `teacher_id`, `subject_id`, `time_in`, `remarks`, `qr`) VALUES
(1, 1, 1, 4, '2024-07-30 09:55:45', 'Absent', ''),
(2, 2, 1, 4, '2024-07-30 10:19:27', 'Late', ''),
(3, 3, 1, 4, '2024-07-30 10:25:47', 'Late', ''),
(4, 4, 1, 3, '2024-07-30 14:31:47', 'Present', ''),
(5, 3, 1, 3, '2024-07-30 14:34:49', 'Present', ''),
(6, 2, 1, 3, '2024-07-30 14:35:29', 'Present', ''),
(7, 5, 1, 3, '2024-07-30 15:00:30', 'Absent', ''),
(8, 4, 1, 3, '2024-08-01 02:56:33', 'Present', ''),
(9, 2, 1, 3, '2024-08-01 02:57:42', 'Present', ''),
(10, 3, 1, 3, '2024-08-01 02:58:06', 'Present', ''),
(11, 1, 1, 3, '2024-09-16 16:00:00', 'Absent', '');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `grade_id` int(11) NOT NULL,
  `grade` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`grade_id`, `grade`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `start_class` time NOT NULL,
  `end_class` time NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `start_class`, `end_class`, `grade_id`, `section_id`, `subject_id`, `teacher_id`) VALUES
(1, '00:00:00', '23:59:00', 1, 1, 4, 1),
(2, '21:00:00', '22:30:00', 3, 1, 12, 2),
(4, '23:00:00', '23:45:00', 2, 1, 10, 3),
(5, '00:00:00', '23:59:00', 1, 1, 3, 1),
(6, '23:00:00', '23:30:00', 1, 2, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `section` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `section`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_lrn` varchar(200) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `guardian` varchar(200) NOT NULL,
  `guardianContact` varchar(200) NOT NULL,
  `grade_id` int(200) NOT NULL,
  `section_id` int(11) NOT NULL,
  `image` varchar(200) NOT NULL,
  `qr` varchar(200) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Low Risk',
  `actionTake` varchar(255) NOT NULL DEFAULT '-',
  `result` varchar(255) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_lrn`, `firstName`, `lastName`, `gender`, `email`, `guardian`, `guardianContact`, `grade_id`, `section_id`, `image`, `qr`, `status`, `actionTake`, `result`) VALUES
(1, '1367200453267', 'Kim', 'Cruz', 'Female', 'maesy220902@gmail.com', 'Mar Cruz', '09204563781', 1, 1, '66a8b7d8e9a7b.png', 'i8G0RgxQrV', 'Medium Risk', '-', ''),
(2, '1367195202144', 'Uno Ezekiel', 'Lim', 'Male', 'maesy220902@gmail.com', 'Lily Lim', '09856478962', 1, 1, '66a8be12d9851.png', '2RaaRQDDTP', 'Low Risk', 'Home Visit', 'Sick'),
(3, '1367195226845', 'Blanca ', 'Kopiko', 'Female', 'maesy220902@gmail.com', 'Dubu Kopiko', '09851247856', 1, 1, '66a8bf999e11c.png', '1I8xhDKTtp', 'Low Risk', 'Call Parent', 'Sick'),
(4, '13671980076546', 'Blanca ', 'Park', 'Female', 'knlmcrz@gmail.com', 'Dubu Park', '09879806578', 3, 1, '66a8dcfe5aa17.png', 'IobKGZQXNS', 'Low Risk', '-', ''),
(5, '12y37273', 'Kiyah', 'Lopez', 'Female', 'kiyahc53@gmail.com', 'Leah Lopez', '09564356429', 1, 2, '66a8ffda839f6.png', 'tqwogdF4YN', 'Medium Risk', '-', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_risk`
--

CREATE TABLE `student_risk` (
  `risk_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `actionTake` varchar(255) NOT NULL,
  `result` varchar(255) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `grade_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject`, `grade_id`) VALUES
(1, 'Math 1', 1),
(2, 'Science 1', 1),
(3, 'English 1', 1),
(4, 'Filipino 1', 1),
(6, 'Mathematics 2', 2),
(7, 'Science 2', 2),
(8, 'English 2', 2),
(9, 'Filipino 2', 2),
(10, 'Edukasyon sa Pagpapakatao 2', 2),
(11, 'Mathematics 3', 3),
(12, 'Science 3', 3),
(13, 'Filipino 3', 3),
(14, 'English 3', 3),
(15, 'Edukasyon sa Pagpapakatao 3', 3),
(16, 'Mathematics 4', 4),
(17, 'Science 4', 4),
(18, 'English 4', 4),
(19, 'Filipino 4', 4),
(20, 'Edukasyon sa Pagpapakatao 4', 4),
(21, 'Mathematics 5', 5),
(22, 'Science 5', 5),
(23, 'English 5', 5),
(24, 'Filipino 5', 5),
(25, 'Edukasyon sa Pagpapakatao 5', 5),
(26, 'Mathematics 6', 6),
(27, 'Science 6', 6),
(28, 'English 6', 6),
(29, 'Filipino 6', 6),
(30, 'Edukasyon sa Pagpapakatao 6', 6);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `courtesy` varchar(2555) NOT NULL,
  `name` varchar(200) NOT NULL,
  `contact` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `grade_id` int(20) NOT NULL,
  `image` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `courtesy`, `name`, `contact`, `username`, `password`, `subject_id`, `grade_id`, `image`, `status`) VALUES
(1, 'MS.', 'Kiae Cruiz', '09205723674', 'kiae.d', 'kimcruiz', 3, 1, '66a8b76d5caf7.png', 'Online'),
(2, 'MRS.', 'Lucy Pineda', '09898765467', 'lucy.p', 'lucypineda', 12, 3, '66a8db7597259.png', 'Offline'),
(3, 'MS.', 'Melissa Torres', '09564356429', 'melissa.t', 'melissatorres', 10, 2, '66a8f770028c5.png', 'Offline'),
(4, 'MS.', 'Andie', '09987654678', 'ands', 'adns', 2, 1, '66eb4aaba1d4a.jpeg', 'Offline'),
(5, 'MS.', 'Friend', '09999999999', 'friend', 'friend', 1, 1, '66ec393361e49.jpg', 'Online');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `student_risk`
--
ALTER TABLE `student_risk`
  ADD PRIMARY KEY (`risk_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_risk`
--
ALTER TABLE `student_risk`
  MODIFY `risk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
