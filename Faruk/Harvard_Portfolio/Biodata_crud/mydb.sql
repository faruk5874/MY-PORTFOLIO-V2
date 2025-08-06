-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2025 at 01:05 PM
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
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `biodata`
--

CREATE TABLE `biodata` (
  `id` int(11) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `siblings` text DEFAULT NULL,
  `education_qualification` varchar(255) DEFAULT NULL,
  `education_institute` varchar(255) DEFAULT NULL,
  `education_passing_year` int(11) DEFAULT NULL,
  `current_education` varchar(255) DEFAULT NULL,
  `certifications` text DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `annual_income` varchar(255) DEFAULT NULL,
  `career_plan` text DEFAULT NULL,
  `complexion` varchar(100) DEFAULT NULL,
  `body_type` varchar(100) DEFAULT NULL,
  `diet` varchar(100) DEFAULT NULL,
  `smoking` enum('No','Occasionally','Yes') DEFAULT NULL,
  `drinking` enum('No','Occasionally','Yes') DEFAULT NULL,
  `hobbies` text DEFAULT NULL,
  `partner_age_range` varchar(50) DEFAULT NULL,
  `partner_height` varchar(50) DEFAULT NULL,
  `partner_education` varchar(255) DEFAULT NULL,
  `partner_occupation` varchar(255) DEFAULT NULL,
  `partner_religion` varchar(100) DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `future_plans` text DEFAULT NULL,
  `health_issues` text DEFAULT NULL,
  `languages` text DEFAULT NULL,
  `preferred_location` text DEFAULT NULL,
  `social_media` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biodata`
--

INSERT INTO `biodata` (`id`, `photo_path`, `name`, `date_of_birth`, `place_of_birth`, `age`, `gender`, `height`, `marital_status`, `religion`, `nationality`, `blood_group`, `contact_number`, `email`, `permanent_address`, `present_address`, `father_name`, `father_occupation`, `mother_name`, `mother_occupation`, `siblings`, `education_qualification`, `education_institute`, `education_passing_year`, `current_education`, `certifications`, `occupation`, `annual_income`, `career_plan`, `complexion`, `body_type`, `diet`, `smoking`, `drinking`, `hobbies`, `partner_age_range`, `partner_height`, `partner_education`, `partner_occupation`, `partner_religion`, `about_me`, `future_plans`, `health_issues`, `languages`, `preferred_location`, `social_media`) VALUES
(2, 'uploads/photo_688dee8ddbdfb0.80309275.png', 'dgfdsf', '2025-08-20', '0', 33, 'Female', '33', 'Single', 'dfsd', 'sfd', 'sdf', 'dsf', 'dsfdsf@gmail.com', 'dsf', 'dsf', 'dfdsfdsf', 'dsfds', 'fdsff', 'dsfds', '5', 'dfsdf', '0', 3333, 'dfdsdf', 'sdf', 'sdf', '55555', 'dsfdsf', 'dsfds', 'dsf', 'sdf', 'No', 'No', 'dsfdsf', '22-23', '333', 'dfds', 'dsf', 'dsf', 'dsf', 'dsf', 'df', 'dsf', 'dsf', 'dddddddddddddddd');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(2, 'a', 'a@gmail.com', '$2y$10$v8zpp9bzbRtv6b/X.4Dy6etaEtqjAPeZjChD2DFe/t/2e66APFXUa', '2025-08-02 09:58:59'),
(4, 'aa', 'bzsisqibmx@cross.edu.pl', '$2y$10$fwuj3UWcTtl0STuc/Z8XsOwUA2Di5S3uMvrWzEH29pXJmJVcEIZjm', '2025-08-02 10:44:09'),
(5, 'b', 'b@gmail.com', '$2y$10$zUvKA0Cwc/o5iHhmwAakk.aG1O.YUsPzS6ezF94ldfLNi2HLRxz5u', '2025-08-02 10:51:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biodata`
--
ALTER TABLE `biodata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biodata`
--
ALTER TABLE `biodata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
