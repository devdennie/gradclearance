-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 12:30 PM
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
-- Database: `grad_clearance`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `book_name` varchar(100) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `cleared` enum('yes','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `student_id`, `book_name`, `borrow_date`, `due_date`, `return_date`, `cleared`) VALUES
(1, '202300001', 'Quantum Physics Textbook', '2023-10-01', '2023-10-08', '2023-10-07', 'yes'),
(2, '202300002', 'Business Management Guide', '2023-10-01', '2023-10-08', NULL, 'no'),
(3, '202300003', 'Biology Reference', '2023-11-01', '2023-11-08', '2023-11-06', 'yes'),
(4, '202300004', 'Project Management Book', '2023-11-15', '2023-11-22', '2023-11-25', 'yes'),
(5, '202300005', 'Software Engineering Manual', '2023-12-01', '2023-12-08', '2023-12-07', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `clearance_status`
--

CREATE TABLE `clearance_status` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `academic_cleared` enum('yes','no') DEFAULT 'no',
  `academic_reason` text DEFAULT NULL,
  `payment_cleared` enum('yes','no') DEFAULT 'no',
  `payment_reason` text DEFAULT NULL,
  `library_cleared` enum('yes','no') DEFAULT 'no',
  `library_reason` text DEFAULT NULL,
  `overall_cleared` enum('yes','no') GENERATED ALWAYS AS (case when `academic_cleared` = 'yes' and `payment_cleared` = 'yes' and `library_cleared` = 'yes' then 'yes' else 'no' end) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clearance_status`
--

INSERT INTO `clearance_status` (`id`, `student_id`, `academic_cleared`, `academic_reason`, `payment_cleared`, `payment_reason`, `library_cleared`, `library_reason`) VALUES
(1, '202300001', 'yes', NULL, 'yes', NULL, 'no', NULL),
(2, '202300002', 'no', 'Failing grade \'D\' in Basic Mathematics (Semester 1). Must repeat. Failing grade \'F\' in Accounting 101 (Semester 4). Must repeat. ', 'no', 'Total pending fees: K7,500.00 across semesters. (Fee: K2500/course, max 4/semester; Degree: 8 semesters, Diploma: 6 semesters)', 'no', 'Business Management Guide: Still borrowed (Overdue: 731 days, Fine: K7,240.00). Total fine: K7,240.00. Grace period: 7 days; K10/day overdue. Return/pay to clear.'),
(3, '202300003', 'no', NULL, 'no', NULL, 'no', NULL),
(4, '202300004', 'yes', NULL, 'yes', NULL, 'yes', NULL),
(5, '202300005', 'no', NULL, 'no', NULL, 'no', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `semester` int(11) NOT NULL,
  `grade` enum('A+','A','B+','B','C+','C','D+','D','F') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `student_id`, `course_name`, `semester`, `grade`) VALUES
(1, '202300001', 'Advanced Mathematics', 1, 'A'),
(2, '202300001', 'Introductory Physics', 2, 'B+'),
(3, '202300001', 'Computer Science Fundamentals', 3, 'C'),
(4, '202300001', 'Economics Principles', 4, 'A'),
(5, '202300001', 'Literature Analysis', 5, 'B'),
(6, '202300002', 'Basic Mathematics', 1, 'D'),
(7, '202300002', 'Business English', 1, 'A'),
(8, '202300002', 'Introductory Chemistry', 2, 'C+'),
(9, '202300002', 'Marketing Basics', 3, 'B'),
(10, '202300002', 'Accounting 101', 4, 'F'),
(11, '202300003', 'Calculus I', 1, 'A'),
(12, '202300003', 'Biology I', 2, 'B'),
(13, '202300003', 'History of Science', 3, 'D+'),
(14, '202300003', 'Statistics', 4, 'C'),
(15, '202300003', 'Philosophy', 5, 'D'),
(16, '202300004', 'Diploma Math', 1, 'B+'),
(17, '202300004', 'Communication Skills', 2, 'A'),
(18, '202300004', 'Environmental Science', 3, 'C+'),
(19, '202300004', 'Project Management', 3, 'B'),
(20, '202300004', 'Ethics in Business', 4, 'A'),
(21, '202300005', 'Engineering Physics', 1, 'A'),
(22, '202300005', 'Data Structures', 2, 'B'),
(23, '202300005', 'Organic Chemistry', 3, 'C'),
(24, '202300005', 'International Relations', 4, 'A+'),
(25, '202300005', 'Software Engineering', 5, 'B+');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `semester` int(11) NOT NULL,
  `courses_taken` int(11) DEFAULT 1,
  `fee_per_course` decimal(10,2) DEFAULT 2500.00,
  `total_fee` decimal(10,2) GENERATED ALWAYS AS (`courses_taken` * `fee_per_course`) STORED,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `pending_amount` decimal(10,2) GENERATED ALWAYS AS (`total_fee` - `paid_amount`) STORED,
  `paid_status` enum('paid','pending') GENERATED ALWAYS AS (case when `pending_amount` = 0 then 'paid' else 'pending' end) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `semester`, `courses_taken`, `fee_per_course`, `paid_amount`) VALUES
(1, '202300001', 1, 1, 2500.00, 2500.00),
(2, '202300001', 2, 1, 2500.00, 2500.00),
(3, '202300001', 3, 1, 2500.00, 2500.00),
(4, '202300001', 4, 1, 2500.00, 2500.00),
(5, '202300001', 5, 1, 2500.00, 2500.00),
(6, '202300002', 1, 2, 2500.00, 2500.00),
(7, '202300002', 2, 1, 2500.00, 0.00),
(8, '202300002', 3, 1, 2500.00, 2500.00),
(9, '202300002', 4, 1, 2500.00, 0.00),
(10, '202300003', 1, 1, 2500.00, 2500.00),
(11, '202300003', 2, 1, 2500.00, 2500.00),
(12, '202300003', 3, 1, 2500.00, 2500.00),
(13, '202300003', 4, 1, 2500.00, 2500.00),
(14, '202300003', 5, 1, 2500.00, 2500.00),
(15, '202300004', 1, 1, 2500.00, 2500.00),
(16, '202300004', 2, 1, 2500.00, 2500.00),
(17, '202300004', 3, 2, 2500.00, 5000.00),
(18, '202300004', 4, 1, 2500.00, 2500.00),
(19, '202300005', 1, 1, 2500.00, 2500.00),
(20, '202300005', 2, 1, 2500.00, 2500.00),
(21, '202300005', 3, 1, 2500.00, 0.00),
(22, '202300005', 4, 1, 2500.00, 2500.00),
(23, '202300005', 5, 1, 2500.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `program` enum('degree','diploma') NOT NULL,
  `total_semesters` int(11) NOT NULL,
  `current_semester` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `student_id`, `program`, `total_semesters`, `current_semester`) VALUES
(1, 'John Doe', '202300001', 'degree', 8, 1),
(2, 'Jane Smith', '202300002', 'diploma', 6, 1),
(3, 'Alice Johnson', '202300003', 'degree', 8, 1),
(4, 'Charlie Brown', '202300004', 'diploma', 6, 1),
(5, 'Bob Wilson', '202300005', 'degree', 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('student','staff') NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `student_id`, `role`) VALUES
(7, 'admin', 'admin123', 'staff', NULL, NULL),
(8, '202300001', 'pass123', 'student', '202300001', NULL),
(9, '202300002', 'pass123', 'student', '202300002', NULL),
(10, '202300003', 'pass123', 'student', '202300003', NULL),
(11, '202300004', 'pass123', 'student', '202300004', NULL),
(12, '202300005', 'pass123', 'student', '202300005', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `clearance_status`
--
ALTER TABLE `clearance_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clearance_status`
--
ALTER TABLE `clearance_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `clearance_status`
--
ALTER TABLE `clearance_status`
  ADD CONSTRAINT `clearance_status_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
