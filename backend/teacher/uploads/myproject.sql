-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2024 at 09:12 AM
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
-- Database: `myproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `admin_id` int(3) NOT NULL,
  `admin_fullname` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_tel` int(10) NOT NULL,
  `admin_username` varchar(100) NOT NULL,
  `admin_password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`admin_id`, `admin_fullname`, `admin_email`, `admin_tel`, `admin_username`, `admin_password`) VALUES
(1, 'khadafee sama', 'dafee@gmail.com', 1234567890, 'admin1', '1111');

-- --------------------------------------------------------

--
-- Table structure for table `tb_announcements`
--

CREATE TABLE `tb_announcements` (
  `announcement_id` int(11) NOT NULL,
  `announcement_title` varchar(255) NOT NULL,
  `announcement_image` text NOT NULL,
  `announcement_details` text NOT NULL,
  `announcement_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_announcements`
--

INSERT INTO `tb_announcements` (`announcement_id`, `announcement_title`, `announcement_image`, `announcement_details`, `announcement_date`) VALUES
(14, 'ประกาศ14/7/2567', '../uploadsbankls.png', 'พนหยุดเรียนนนนนนนนนนนนนนน', '2024-07-13 18:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `tb_homework`
--

CREATE TABLE `tb_homework` (
  `homework_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_pass` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` datetime NOT NULL,
  `file_path` text DEFAULT NULL,
  `assigned_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_homework`
--

INSERT INTO `tb_homework` (`homework_id`, `subject_id`, `teacher_id`, `subject_pass`, `title`, `description`, `deadline`, `file_path`, `assigned_date`) VALUES
(13, 4, 3, 'ว21101', 'ฟหกฟหก', 'ฟหกฟหก', '2024-08-30 02:32:00', '[\"uploads/ภาษาไทยม1เล่ม2.jpg\",\"uploads/วิทยาศาสตร์และเทคโนโลยี ชั้น ม.1 เล่ม 1.jpg\"]', '2024-08-27 02:32:00'),
(20, 7, 3, 'ท21102', 'adad', 'adadada', '2024-08-30 15:32:00', '[\"uploads/วิทยาศาสตร์และเทคโนโลยี ชั้น ม.1 เล่ม 1.jpg\"]', '2024-08-29 15:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `member_id` int(11) NOT NULL,
  `member_number` varchar(50) NOT NULL,
  `member_fullname` varchar(255) NOT NULL,
  `member_address` varchar(255) NOT NULL,
  `member_tel` varchar(20) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_username` varchar(50) NOT NULL,
  `member_password` varchar(255) NOT NULL,
  `member_status` tinyint(1) NOT NULL,
  `member_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`member_id`, `member_number`, `member_fullname`, `member_address`, `member_tel`, `member_email`, `member_username`, `member_password`, `member_status`, `member_datetime`) VALUES
(1, '4982', 'เด็กชายต่วนนุฟ สาอิอาลี', 'saiburi', '1234567890', '1@gmail.com', '4982', '1234', 1, '2024-08-10 11:52:00'),
(2, '4945', 'เด็กหญิงมาซีเต๊าะ ยูโซ๊ะ', 'บือเระ', '1234567890', '3@gmail.com', '4945', '1111', 1, '2009-10-11 15:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_student_homework`
--

CREATE TABLE `tb_student_homework` (
  `student_homework_id` int(11) NOT NULL,
  `homework_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `submission_time` datetime NOT NULL,
  `file_path` text NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `checked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_student_homework`
--

INSERT INTO `tb_student_homework` (`student_homework_id`, `homework_id`, `member_id`, `submission_time`, `file_path`, `grade`, `feedback`, `checked`) VALUES
(1, 13, 2, '2024-08-28 18:26:04', '[\"../../backend/teacher/uploads/ภาษาไทยม1เล่ม2.jpg\",\"../../backend/teacher/uploads/วิทยาศาสตร์และเทคโนโลยี ชั้น ม.1 เล่ม 1.jpg\"]', '5', 'แก้นิด', 1),
(4, 20, 2, '2024-08-29 10:34:52', '[\"../../backend/teacher/uploads/406459017.pdf\"]', NULL, NULL, 0),
(5, 13, 1, '2024-08-29 10:52:00', '[\"../../backend/teacher/uploads/S1.jpg\"]', NULL, NULL, 0),
(6, 20, 1, '2024-08-29 11:02:13', '[\"../../backend/teacher/uploads/S1.jpg\"]', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_student_subject`
--

CREATE TABLE `tb_student_subject` (
  `student_subject_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `submission_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_student_subject`
--

INSERT INTO `tb_student_subject` (`student_subject_id`, `subject_id`, `member_id`, `submission_time`) VALUES
(1, 4, 1, '2024-08-26 16:24:05'),
(2, 4, 2, '2024-08-26 16:24:05'),
(3, 7, 1, '2024-08-27 21:56:12'),
(4, 7, 2, '2024-08-27 21:56:12');

-- --------------------------------------------------------

--
-- Table structure for table `tb_subject`
--

CREATE TABLE `tb_subject` (
  `subject_id` int(11) NOT NULL,
  `subject_pass` varchar(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `subject_detail` text DEFAULT NULL,
  `subject_cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_subject`
--

INSERT INTO `tb_subject` (`subject_id`, `subject_pass`, `subject_name`, `teacher_id`, `subject_detail`, `subject_cover`) VALUES
(4, 'ว21101', 'วิทยาศาสตร์พื้นฐาน 1', 3, 'วิทยาศาสตร์และเทคโนโลยี ชั้น ม.1 เล่ม 1', 'uploads/วิทยาศาสตร์และเทคโนโลยี ชั้น ม.1 เล่ม 1.jpg'),
(7, 'ท21102', 'ภาษาไทย2', 3, 'ภาษาไทย2 เล่ม 2', 'uploads/ภาษาไทยม1เล่ม2.jpg'),
(8, '121212', 'หนึ่งสองหนึ่งสอง', 3, 'เป้นรายวิชา หนึ่งสอง หนึ่งสอง', 'uploads/ภาษาไทยม1เล่ม2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_teacher`
--

CREATE TABLE `tb_teacher` (
  `teacher_id` int(11) NOT NULL,
  `teacher_fullname` varchar(100) NOT NULL,
  `teacher_username` varchar(100) NOT NULL,
  `teacher_password` varchar(50) NOT NULL,
  `teacher_tel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_teacher`
--

INSERT INTO `tb_teacher` (`teacher_id`, `teacher_fullname`, `teacher_username`, `teacher_password`, `teacher_tel`) VALUES
(1, 'นางสาวสวย สุดสวย', 'teacher1', '1212', '0987654321'),
(3, 'อับดุลเลาะ บากา', 'teacher2', '1111', '1234567890');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tb_announcements`
--
ALTER TABLE `tb_announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `tb_homework`
--
ALTER TABLE `tb_homework`
  ADD PRIMARY KEY (`homework_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_number` (`member_number`),
  ADD UNIQUE KEY `member_username` (`member_username`);

--
-- Indexes for table `tb_student_homework`
--
ALTER TABLE `tb_student_homework`
  ADD PRIMARY KEY (`student_homework_id`),
  ADD KEY `homework_id` (`homework_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `tb_student_subject`
--
ALTER TABLE `tb_student_subject`
  ADD PRIMARY KEY (`student_subject_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `tb_subject`
--
ALTER TABLE `tb_subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tb_teacher`
--
ALTER TABLE `tb_teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `admin_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_announcements`
--
ALTER TABLE `tb_announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_homework`
--
ALTER TABLE `tb_homework`
  MODIFY `homework_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_student_homework`
--
ALTER TABLE `tb_student_homework`
  MODIFY `student_homework_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_student_subject`
--
ALTER TABLE `tb_student_subject`
  MODIFY `student_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_subject`
--
ALTER TABLE `tb_subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_teacher`
--
ALTER TABLE `tb_teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_homework`
--
ALTER TABLE `tb_homework`
  ADD CONSTRAINT `tb_homework_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `tb_subject` (`subject_id`),
  ADD CONSTRAINT `tb_homework_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `tb_teacher` (`teacher_id`);

--
-- Constraints for table `tb_student_homework`
--
ALTER TABLE `tb_student_homework`
  ADD CONSTRAINT `tb_student_homework_ibfk_1` FOREIGN KEY (`homework_id`) REFERENCES `tb_homework` (`homework_id`),
  ADD CONSTRAINT `tb_student_homework_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `tb_member` (`member_id`);

--
-- Constraints for table `tb_student_subject`
--
ALTER TABLE `tb_student_subject`
  ADD CONSTRAINT `tb_student_subject_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `tb_subject` (`subject_id`),
  ADD CONSTRAINT `tb_student_subject_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `tb_member` (`member_id`);

--
-- Constraints for table `tb_subject`
--
ALTER TABLE `tb_subject`
  ADD CONSTRAINT `tb_subject_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `tb_teacher` (`teacher_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
