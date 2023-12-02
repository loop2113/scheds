-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2023 at 12:54 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schedule`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`) VALUES
(1, 'BSIT'),
(7, 'BSCE'),
(8, 'BSEE');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `dept` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `dept`) VALUES
(1, 'Engineering'),
(2, 'Education'),
(3, 'Technology'),
(4, 'BEM');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `emp_id` int(150) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `dept_id` int(15) NOT NULL,
  `course_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `emp_id`, `name`, `email`, `dept_id`, `course_id`) VALUES
(2, 654321, 'Jasten Keneth Treceñe', 'jasten.keneth@gmail.com', 1, 1),
(3, 111111, 'Greg Campos', 'greg.campos@gmail.com', 1, 1),
(13, 819586, 'Aljon Abines', 'aljon.abines@gmail.com', 1, 1),
(15, 610199, 'Romeo A. Alvarado Jr.', 'romeo.alvarado@gmail.com', 1, 1),
(16, 132727, 'Michelle Ann C. Orbeta', 'michellanncorbeta@gmail.com', 1, 1),
(17, 387388, 'Aimee O. Mitra', 'aimeemitra@gmail.com', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(255) NOT NULL,
  `room` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room`, `description`) VALUES
(8, 'ACAD 01', 'Academic Building'),
(9, 'ACAD 02', 'Academic Building'),
(10, 'ACAD 03', 'Academic Building'),
(11, 'ACAD 04', 'Academic Building'),
(12, 'NEW ACAD 01', 'New Academic Building'),
(13, 'NEW ACAD 02', 'New Academic Building'),
(14, 'NEW ACAD 03', 'New Academic Building'),
(15, 'NEW ACAD 04', 'New Academic Building');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `weekday` int(100) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `faculty_id` int(150) NOT NULL,
  `subject_id` int(150) NOT NULL,
  `section_id` int(150) NOT NULL,
  `room_id` int(150) NOT NULL,
  `semester_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `title`, `weekday`, `start_time`, `end_time`, `faculty_id`, `subject_id`, `section_id`, `room_id`, `semester_id`) VALUES
(8, 'Class', 2, '10:00:00', '11:00:00', 2, 9, 1, 8, 1),
(9, 'Class', 3, '08:00:00', '09:00:00', 2, 9, 1, 8, 1),
(10, 'Class', 2, '13:30:00', '14:30:00', 3, 9, 1, 8, 1),
(11, 'Class', 2, '07:00:00', '09:00:00', 3, 9, 1, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `course_id` int(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section`, `course_id`) VALUES
(1, 'IT 1A', 1),
(2, 'IT 2A', 1),
(3, 'IT 2B', 1),
(4, 'IT 3A', 1),
(5, 'IT 3B', 1),
(6, 'IT 4A', 1),
(12, 'CE 1A', 7),
(13, 'CE 2B', 7),
(14, 'CE 2B', 7),
(15, 'CE 1B', 7),
(16, 'CE 3A', 7),
(17, 'CE 3B', 7),
(18, 'CE 4A', 7),
(19, 'CE 4B', 7),
(20, 'EE 1A', 8),
(21, 'EE 1B', 8),
(22, 'EE 2A', 8),
(23, 'EE 2B', 8),
(24, 'EE 3A', 8),
(25, 'EE 3B', 8),
(31, 'EE 4A', 8),
(32, 'IT 4B', 1),
(33, 'IT 1B', 1);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `semester_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester_name`) VALUES
(1, '1st Semester'),
(2, '2nd Semester');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(30) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `lec` int(15) NOT NULL,
  `lab` int(15) NOT NULL,
  `description` text NOT NULL,
  `course_id` int(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject`, `lec`, `lab`, `description`, `course_id`) VALUES
(9, 'GEN. ED. 001', 1, 3, 'Purposive Communication', 1),
(10, 'GEN. ED. 002', 1, 3, 'Understanding the Self', 1),
(11, 'GEN. ED. 004', 1, 3, 'Mathematics in the Modern World', 1),
(12, 'GE. EL. 001', 1, 3, 'General Education Electives	\r\n\r\n', 1),
(13, 'DRR 113', 1, 3, 'Disaster Risk Reduction and Education in Emergencies', 1),
(14, 'MATH ENHANCE 1', 1, 3, 'College Algebra & Trigonometry', 1),
(15, 'PATHFIT 112', 1, 2, 'Movement Competency Training', 1),
(16, 'NSTP 113', 1, 3, 'CWTS, LTS, MTS (Naval or Air Force)', 1),
(23, 'PATHFIT 112', 1, 2, 'PATHFIT-I (Movement Competency Training)', 1),
(24, 'GEN. ED. 003', 1, 3, 'Readings in Philippine History', 1),
(26, 'MATH 113', 1, 3, 'College Algebra 1', 1),
(35, 'IT 113', 2, 3, 'Introduction to Computing (*)', 1),
(36, 'IT 123', 2, 3, 'Introduction to Human Computer Interaction (*)', 1),
(37, 'IT 143', 3, 0, 'Discrete Mathematics', 1),
(38, 'IT 163', 2, 3, 'Computer Programming 2 (*)', 1),
(39, 'IT 134', 3, 3, 'Computer Programming 1 (*)', 1),
(40, 'GEN. ED. 006', 3, 0, 'Ethics', 1),
(41, 'GEN. ED. 007', 3, 0, 'The Contemporary World', 1),
(42, 'PATHFIT 122', 2, 0, 'Fitness Training', 1),
(43, 'NSTP 123', 3, 0, 'CWTS, LTS, MTS (Naval or Air Force)', 1),
(44, 'IT 213', 2, 3, 'Data Structures and Algorithms (*)', 1),
(45, 'IT 233', 2, 3, 'Object Oriented Programming (*)', 1),
(46, 'IT 253', 3, 0, 'Platform Technologies (*)', 1),
(47, 'IT 273', 2, 3, 'Web Systems and Technologies (*)', 1),
(48, 'IT 293', 3, 0, 'Statistics and Probability', 1),
(49, 'CCNA 213', 2, 3, 'Introduction to Networks (*)', 1),
(50, 'PATHFIT 212', 2, 0, 'Dance, Sport, Group Exercise, Outdoor and Adventure Activities', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `user_type` varchar(150) NOT NULL,
  `session_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `user_type`, `session_id`) VALUES
(1, 'Administrator', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', ''),
(2, 'Mary Jane', 'secretary', '889b2b111b4bc3adb722f0fcff480901', 'Secretary', ''),
(3, 'Developer', 'developer', '5e8edd851d2fdfbd7415232c67367cc3', 'Administrator', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `faculty_link` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `room_link` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  ADD CONSTRAINT `section_link` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_link` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
