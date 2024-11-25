-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 03:51 PM
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
-- Database: `gradebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `adminCode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fullName`, `email`, `password`, `adminCode`) VALUES
(1, 'Musawir Khalid', 'musawir1000@gmail.com', '$2y$10$l6YHsVHsgFt37V1O/q/veuA/SKcrerhxTQvtSVTm/6Xa9scyIYLTS', 'ADM001'),
(2, 'Muzammil Hussain', 'muzammil2000@gmail.com', '$2y$10$XpnVhGH1LEwEeT0mdBVUJO2UzGaq/UXYaErWxSWi7/4EAwcxfNfSC', 'ADM002'),
(3, 'Abdur Rehman', 'aarehman2k4@gmail.com', '$2y$10$oaN5ie3aVSsbbtO8uXhFIuTpXbvKPB7o0JksDtfB1xaQ5u6Pn57Yy', 'ADM003');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `Credit_Hrs` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `Credit_Hrs`) VALUES
(111, 'Computer Programming Lab', 1),
(112, 'Computer Programming', 3),
(113, 'Introduction To Information and Communication Technology Lab', 1),
(114, 'Introduction To Information and Communication Technology', 3),
(116, 'Calculus and Analytical Geometry', 3),
(118, 'Applied Physics', 2),
(120, 'Functional English', 3),
(122, 'Object Oriented Programming', 3),
(123, 'Object Oriented Programming Lab', 1),
(124, 'Digital Logic Design', 3),
(125, 'Digital Logic Design Lab', 1),
(126, 'Communication Skills', 3),
(127, 'Critical Thinking', 3),
(128, 'Discrete Mathematics', 3),
(129, 'Professional Practices', 3),
(130, 'Data Structures and Algorithms', 3),
(131, 'Data Structures and Algorithms Lab', 1),
(132, 'Probability and Statistics', 3),
(133, 'Multivariable Calculus', 3),
(211, 'Linear Algebra', 3),
(212, 'Entrepreneurship', 2),
(214, 'Data Communication and Networking', 3),
(215, 'Data Communication and Networking Lab', 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `facultyId` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `Contact_No` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `fullName`, `email`, `password`, `facultyId`, `department`, `Contact_No`) VALUES
(1, 'Ahmad Khan', 'ahmed617@gmail.com', '$2y$10$tGYweObtRxOMhd0ywb9.ouZ/q6/JAX4OaXduiwzs/3YRwwBD2p0fS', '002145', 'Computer Science', NULL),
(2, 'muzammil', 'muzammil2001@gmail.com', '$2y$10$vfD6qeY8i46R0UpOpEhPLeOjkOzWZkw2pvyo8YSuNj47GtV6pX0SW', '123', 'CS', '0300-3698547'),
(3, 'Test', 'test@gmail.com', '$2y$10$luIlhOIftyTzJp42ZvHZWuHxcnMm4HISnIK76GT0HSEpl3508L.22', '589', 'computer science', '03155989898'),
(4, 'HelloFaclty', 'hello@faulty.com', '$2y$10$mCAXoESgkh0XN4r5okD5LeDgAxd1XznPBduGH3y4uOdgqGeaSS83S', 'ABC123', '', ''),
(6, 'Musawir Khalid', 'musawir00@gmail.com', '$2y$10$B3lbhS9Rh3Iylz0O1O7fseGAMb7YXtjtreykHLQIqGieJN2XAXAqe', '002115', 'Computer Science', '03487850012'),
(9, 'Dr Islam', 'ik2244@gmail.com', '$2y$10$sYsarxVraNdt0qqAUQjEEuWz9P5lZHt222yHDOiJvlmf8WM.Yg11G', '002118', 'Computer Science', '03687960012'),
(10, 'Shaheen Khan', 'shk42@gmail.com', '$2y$10$UErcmBIhgc5vmGi8INdvEOJ3fFSbbQcFRn.YQpuxePG.m0hkNVPBu', '00220', 'Electrical Engineering', '03387962013'),
(11, 'Waqas Shahid', 'waqas98@gmail.com', '$2y$10$chChBtsewJlBPlpQW1lVouLSHg3JQLFbWrvEIvlYtl175kC3lD1x.', '00225', 'Electrical Engineering', '03345660014'),
(13, 'Burhan Abbasi', 'burhan88@gmail.com', '$2y$10$OYnkzppbfWR9h2WUnzhkJOz59GzGJ9RADHSnsdNRQZ1FDp7B.bom.', '00229', 'Computer Science', '03417568911');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_courses`
--

CREATE TABLE `faculty_courses` (
  `faculty_course_id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_courses`
--

INSERT INTO `faculty_courses` (`faculty_course_id`, `faculty_id`, `course_id`) VALUES
(1, 2, 111),
(2, 2, 112),
(3, 2, 113),
(4, 2, 114),
(7, 10, 124),
(8, 10, 125),
(9, 9, 116),
(10, 11, 129),
(11, 13, 122),
(12, 13, 123),
(13, 13, 127),
(16, 13, 120),
(17, 13, 120),
(19, 1, 127),
(20, 6, 130);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_grades`
--

CREATE TABLE `faculty_grades` (
  `faculty_grade_id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `grade` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_grades`
--

INSERT INTO `faculty_grades` (`faculty_grade_id`, `faculty_id`, `student_id`, `course_id`, `grade`) VALUES
(1, 2, 4, 111, 'C-'),
(2, 2, 4, 112, 'B+'),
(3, 2, 4, 113, 'B+'),
(4, 2, 4, 114, 'C'),
(8, 2, 1, 111, 'A+'),
(9, 10, 16, 125, 'B+'),
(10, 13, 1, 122, 'B+'),
(11, 13, 1, 123, 'A-'),
(12, 13, 18, 122, 'A+'),
(13, 13, 18, 123, 'B+');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `studentId` varchar(50) NOT NULL,
  `major` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `fullName`, `email`, `password`, `studentId`, `major`) VALUES
(1, 'Musawir Khalid', 'musawirkhalid59@gmail.com', '$2y$10$qQTa9U9LWK0Ue5IRgncQ/eHx5NDF/BQZcyBqWwFR0ulMoe2VA2leO', 'CS112', 'CS'),
(2, 'Umair Khan', 'uk10@gmail.com', '$2y$10$USn3DjvWLrtHBP8X.Auqaud38uMszIoF0fiyHmPlb14O3o/5brQWG', 'EE110', 'EE'),
(3, 'Ahmad Khan', 'ahmed617@gmail.com', '$2y$10$5q7K0Ed7MaqPJa2GmvR4WuGY6KsxYo2Jyk5R1LJc/tZ/Ai1/dn/c6', 'AI114', 'AI'),
(4, 'Alia', 'alia2323@gmail.com', '$2y$10$wjSHJS6X1y1iDzh/PRm.YOAv/ENhmQ.oAnEDqu3BKEO8.gxlQQDNi', 'CS005', 'CS'),
(5, 'Hello', 'hello@gmail.com', '$2y$10$pe9nA5iyHFdGDatloJB8N.PEFRPiYJerL363aBZ8DYi.2BZACeXQu', '112', ''),
(6, 'Error', 'hello@tuent.om', '$2y$10$ciwYEEp2qjdMA2pJtB/j.ed9bEHpfYvcMwtUNTcVnbdzndi21xzCS', 'CS111', 'CS'),
(15, '', 'afnan2k2@gmail.com', '$2y$10$.pFcmqRlNGvZNos7z8gdMufpTOp.rW8wCX4Zgx0wAqQXoBQHe/GIC', 'CS145', 'CS'),
(16, 'Abdur Rehman', 'ark2k2@gmail.com', '$2y$10$LiysU6cNe1dEdz8LM6C8IO/w5JiwImRKJcKCGfzcsyBsy4zhWqErm', 'CS002', 'CS'),
(17, 'Faheem Umer', 'Fm23@gmail.com', '$2y$10$5QRjVNX2TiHQ3J3DnFtCYuZwGQJWihAy8Nn3gOkHQ2XulFN1frbdu', 'CS043', 'CS'),
(18, 'Shayan ', 'shayan123@gmail.com', '$2y$10$QfPfxXmdqnyCxDOzM6x82.G5nWQv9rN5hsxIIlJqAwX.RqBl9/ZJO', '123', 'CS'),
(19, 'Taha Ehtisham', 'taha21@gmail.com', '$2y$10$dUvPiMsLM9qOVqaHmI1bCeWV41pxrOC1tYLFB5iuazAVCsVaVNSW6', 'AI004', 'AI');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `student_id` varchar(255) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`student_id`, `course_id`) VALUES
('123', 122),
('123', 123),
('AI004', 211),
('AI114', 112),
('AI114', 120),
('CS002', 124),
('CS002', 125),
('CS005', 111),
('CS005', 112),
('CS005', 113),
('CS005', 114),
('CS005', 118),
('CS112', 122),
('CS112', 123),
('CS112', 128),
('EE110', 124),
('EE110', 125);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `adminCode` (`adminCode`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `facultyId` (`facultyId`);

--
-- Indexes for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD PRIMARY KEY (`faculty_course_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `faculty_grades`
--
ALTER TABLE `faculty_grades`
  ADD PRIMARY KEY (`faculty_grade_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `studentId` (`studentId`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  MODIFY `faculty_course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `faculty_grades`
--
ALTER TABLE `faculty_grades`
  MODIFY `faculty_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD CONSTRAINT `faculty_courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`),
  ADD CONSTRAINT `faculty_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `faculty_grades`
--
ALTER TABLE `faculty_grades`
  ADD CONSTRAINT `faculty_grades_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`),
  ADD CONSTRAINT `faculty_grades_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `faculty_grades_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`studentId`),
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
