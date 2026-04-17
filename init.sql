-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 17, 2026 at 02:51 PM
-- Server version: 8.0.45
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pit_finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `target_table` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `target_id` int DEFAULT NULL,
  `details` text COLLATE utf8mb4_general_ci,
  `performed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `target_table`, `target_id`, `details`, `performed_at`) VALUES
(1, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 15:31:27'),
(2, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 15:39:15'),
(3, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:01:19'),
(4, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:10:31'),
(5, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:15:20'),
(6, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:17:12'),
(7, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:21:34'),
(8, 4, 'LOGIN', 'admin_users', 4, 'Data Analyst logged in', '2026-03-22 16:25:08'),
(9, 4, 'LOGIN', 'admin_users', 4, 'Data Analyst logged in', '2026-03-22 16:34:24'),
(10, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:36:20'),
(11, 4, 'LOGIN', 'admin_users', 4, 'Data Analyst logged in', '2026-03-22 16:37:08'),
(12, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 16:43:08'),
(13, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-22 19:53:01'),
(14, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-23 17:12:37'),
(15, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-23 20:54:30'),
(16, 1, 'LOGIN', 'admin_users', 1, 'Administrator logged in', '2026-03-24 19:48:37'),
(17, 5, 'LOGIN', 'admin_users', 5, 'Administrator logged in', '2026-03-28 04:01:56'),
(18, 5, 'LOGIN', 'admin_users', 5, 'Administrator logged in', '2026-04-17 14:40:36');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'admin',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `full_name`, `role`, `created_at`, `last_login`) VALUES
(1, 'edcel', 'e02de3868e38db749e8fd33cb5469c14fa353612895df93278aea3d2d5764204', 'Administrator', 'admin', '2026-03-22 15:26:16', '2026-03-24 19:48:37'),
(2, 'hanz', 'hanz123', 'Hanz Bande Jr.', 'admin', '2026-03-22 16:20:03', NULL),
(4, 'John Mark', '43d9423892ab14c9da8388f7036067fa0a15b5663e563f22d3383cfc933c1690', 'Data Analyst', 'admin', '2026-03-22 16:24:45', '2026-03-22 16:37:08'),
(5, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Administrator', 'admin', '2026-03-28 04:01:51', '2026-04-17 14:40:36');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `stroke_color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fill_color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `name`, `description`, `stroke_color`, `fill_color`, `date_created`) VALUES
(1, 'Volleyball Court', 'Court for Volleyball Players ', NULL, NULL, '2026-03-04 23:07:42'),
(2, 'Basketball Court', ' bflat, rectangular surface where the game of basketball is played, featuring a basket (hoop) at each end.', NULL, NULL, '2026-03-07 14:01:58'),
(3, 'COTE Bldg', 'College of Technology and Engineering', NULL, NULL, '2026-03-07 15:50:05'),
(4, 'CGS Bldg', 'Building for fungkal\'s', NULL, NULL, '2026-03-08 09:50:59'),
(5, 'High School Bldg', 'High School Building', NULL, NULL, '2026-03-08 10:03:03'),
(7, 'COG Building', 'College of Graduate Studies', NULL, NULL, '2026-03-08 10:17:48'),
(8, 'In Construction-site', NULL, NULL, NULL, '2026-03-09 00:09:28'),
(9, 'guidance Office', NULL, NULL, NULL, '2026-03-09 00:15:49'),
(10, 'CAS Building', NULL, NULL, NULL, '2026-03-09 00:19:17'),
(11, 'mini Forest', 'asd', NULL, NULL, '2026-03-09 00:21:43'),
(12, 'Oval', 'asd', NULL, NULL, '2026-03-09 00:26:14'),
(13, 'Bahay Alumnai', 'asd', NULL, NULL, '2026-03-09 00:28:36'),
(14, 'CTE Building', 'asd', NULL, NULL, '2026-03-09 00:31:20'),
(15, 'High School Bldg', 'asd', NULL, NULL, '2026-03-09 00:45:26'),
(16, 'Gymnasium', 'asd', NULL, NULL, '2026-03-10 09:33:39'),
(17, 'ICT', 'tabodi', NULL, NULL, '2026-03-21 17:06:11');

-- --------------------------------------------------------

--
-- Table structure for table `building_points`
--

CREATE TABLE `building_points` (
  `id` int NOT NULL,
  `building_id` int NOT NULL,
  `lat` decimal(20,15) DEFAULT NULL,
  `lng` decimal(20,15) DEFAULT NULL,
  `point_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `building_points`
--

INSERT INTO `building_points` (`id`, `building_id`, `lat`, `lng`, `point_order`) VALUES
(1, 1, 11.054092680000000, 124.386579390000000, 1),
(2, 1, 11.054040040000000, 124.386874440000000, 2),
(3, 1, 11.053768890000000, 124.386815430000000, 3),
(4, 1, 11.053818910000000, 124.386536480000000, 4),
(5, 2, 11.053997920000000, 124.386979040000000, 1),
(6, 2, 11.053968960000000, 124.387105110000000, 2),
(7, 2, 11.053753100000000, 124.387043420000000, 3),
(8, 2, 11.053763630000000, 124.386941490000000, 4),
(9, 3, 11.052734340000000, 124.386831520000000, 1),
(10, 3, 11.053479320000000, 124.386957590000000, 2),
(11, 3, 11.053571460000000, 124.386606220000000, 3),
(12, 3, 11.052797520000000, 124.386450650000000, 4),
(13, 4, 11.051771770000000, 124.386769080000000, 1),
(14, 4, 11.051733420000000, 124.387001930000000, 2),
(15, 4, 11.051619940000000, 124.386981190000000, 3),
(16, 4, 11.051662980000000, 124.386760310000000, 4),
(17, 5, 11.051392213115161, 124.386110410565010, 1),
(18, 5, 11.051312522286056, 124.386489327796910, 2),
(19, 5, 11.051219549624744, 124.386463390010190, 3),
(20, 5, 11.051295920027265, 124.386074323209600, 4),
(21, 3, 11.052734340000000, 124.386831520000000, 1),
(22, 3, 11.053479320000000, 124.386957590000000, 2),
(23, 3, 11.053571460000000, 124.386606220000000, 3),
(24, 3, 11.052797520000000, 124.386450650000000, 4),
(25, 7, 11.051786650000000, 124.387046100000000, 1),
(26, 7, 11.051718210000000, 124.387402830000000, 2),
(27, 7, 11.051452330000000, 124.387349190000000, 3),
(28, 7, 11.051462860000000, 124.387201670000000, 4),
(29, 7, 11.051594480000000, 124.387233850000000, 5),
(30, 7, 11.051644500000000, 124.387011230000000, 6),
(31, 8, 11.051857730000000, 124.386523070000000, 1),
(32, 8, 11.051844570000000, 124.386592810000000, 2),
(33, 8, 11.051515510000000, 124.386547210000000, 3),
(34, 8, 11.051591850000000, 124.386150240000000, 4),
(35, 8, 11.051678720000000, 124.386171700000000, 5),
(36, 8, 11.051631340000000, 124.386456010000000, 6),
(37, 9, 11.052378960000000, 124.386378230000000, 1),
(38, 9, 11.052294720000000, 124.386362140000000, 2),
(39, 9, 11.052257860000000, 124.386587440000000, 3),
(40, 9, 11.052347370000000, 124.386598170000000, 4),
(41, 9, 11.052413180000000, 124.386391640000000, 5),
(42, 10, 11.052434240000000, 124.386863710000000, 1),
(43, 10, 11.051970930000000, 124.386788610000000, 2),
(44, 10, 11.051749800000000, 124.387467210000000, 3),
(45, 10, 11.052257860000000, 124.387558400000000, 4),
(46, 10, 11.052442140000000, 124.386869070000000, 5),
(47, 11, 11.052234170000000, 124.387840030000000, 1),
(48, 11, 11.052170990000000, 124.388011690000000, 2),
(49, 11, 11.051844570000000, 124.387872220000000, 3),
(50, 11, 11.051623440000000, 124.387920500000000, 4),
(51, 11, 11.051570790000000, 124.388081430000000, 5),
(52, 11, 11.051323340000000, 124.388161900000000, 6),
(53, 11, 11.051170660000000, 124.388473030000000, 7),
(54, 11, 11.051112740000000, 124.388515950000000, 8),
(55, 11, 11.051144330000000, 124.387663010000000, 9),
(56, 11, 11.051275950000000, 124.387539630000000, 10),
(57, 11, 11.051918280000000, 124.387679100000000, 11),
(58, 12, 11.054187450000000, 124.387394790000000, 1),
(59, 12, 11.054103210000000, 124.387899040000000, 2),
(60, 12, 11.052771190000000, 124.387609360000000, 3),
(61, 12, 11.052929140000000, 124.387030010000000, 4),
(62, 13, 11.052005150000000, 124.386262890000000, 1),
(63, 13, 11.051805080000000, 124.386203890000000, 2),
(64, 13, 11.051757700000000, 124.386431870000000, 3),
(65, 13, 11.051963030000000, 124.386485520000000, 4),
(66, 14, 11.052671160000000, 124.386954900000000, 1),
(67, 14, 11.052607980000000, 124.387268720000000, 2),
(68, 14, 11.052473730000000, 124.387257990000000, 3),
(69, 14, 11.052547440000000, 124.386938810000000, 4),
(70, 15, 11.051086420000000, 124.386563300000000, 1),
(71, 15, 11.050931100000000, 124.386579390000000, 2),
(72, 15, 11.050917940000000, 124.386480150000000, 3),
(73, 15, 11.050986380000000, 124.386469420000000, 4),
(74, 15, 11.050941630000000, 124.386037590000000, 5),
(75, 15, 11.051017970000000, 124.386016130000000, 6),
(76, 16, 11.053128298626733, 124.387825955523350, 1),
(77, 16, 11.053627148153803, 124.387932573332420, 2),
(78, 16, 11.053562596885767, 124.388215880006300, 3),
(79, 16, 11.053075863012937, 124.388119200512500, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `building_id` int NOT NULL,
  `floor` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `details` text COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `building_id`, `floor`, `name`, `details`, `type`, `created_at`) VALUES
(1, 3, 1, 'Room ELEC 21', 'Space for Electrical Engineers and Technology', NULL, '2026-03-23 17:21:53'),
(2, 3, 1, 'Room 1', 'Normal room for visitors', NULL, '2026-03-23 17:21:53'),
(3, 3, 2, 'BINDTECH Office', 'Office for Industrial Technologies', NULL, '2026-03-23 17:21:53'),
(4, 3, 1, 'Information Technology Office', 'Office for IT faculties and instructors', NULL, '2026-03-23 17:21:53'),
(5, 3, 1, 'Room ELEC 22', 'Laboratory for Electrical Systems Practice', NULL, '2026-03-23 17:24:03'),
(6, 3, 1, 'Room ELEC 23', 'Advanced Electrical Circuits Room', NULL, '2026-03-23 17:24:03'),
(7, 3, 1, 'Room COTE 101', 'Lecture room for engineering students', NULL, '2026-03-23 17:24:03'),
(8, 3, 1, 'Room COTE 102', 'General classroom for discussions', NULL, '2026-03-23 17:24:03'),
(9, 3, 1, 'Room Drafting 1', 'Drafting and design workspace', NULL, '2026-03-23 17:24:03'),
(10, 3, 1, 'Room Drafting 2', 'Engineering drawing and planning room', NULL, '2026-03-23 17:24:03'),
(12, 3, 1, 'COTE Conference Room', 'Meeting and conference area', NULL, '2026-03-23 17:24:03'),
(13, 3, 1, 'Storage Room 1', 'Storage for equipment and tools', NULL, '2026-03-23 17:24:03'),
(14, 3, 1, 'Room IT Lab 1', 'Computer laboratory for IT students', NULL, '2026-03-23 17:24:03'),
(15, 3, 2, 'Room IT Lab 2', 'Advanced computer laboratory', NULL, '2026-03-23 17:24:03'),
(16, 3, 2, 'Room IT Lab 3', 'Networking and programming lab', NULL, '2026-03-23 17:24:03'),
(17, 3, 2, 'Room COTE 201', 'Second floor lecture room', NULL, '2026-03-23 17:24:03'),
(18, 3, 2, 'Room COTE 202', 'Discussion and group activity room', NULL, '2026-03-23 17:24:03'),
(19, 3, 2, 'Room Electronics Lab', 'Electronics and circuits lab', NULL, '2026-03-23 17:24:03'),
(20, 3, 2, 'Room Robotics Lab', 'Robotics and automation workspace', NULL, '2026-03-23 17:24:03'),
(21, 3, 2, 'Faculty Room B', 'Faculty office for senior staff', NULL, '2026-03-23 17:24:03'),
(22, 3, 2, 'Research Room', 'Room for research and thesis work', NULL, '2026-03-23 17:24:03'),
(23, 3, 2, 'Storage Room 2', 'Additional storage for materials', NULL, '2026-03-23 17:24:03'),
(24, 3, 2, 'COTE Library Extension', 'Mini library for engineering resources', NULL, '2026-03-23 17:24:03'),
(26, 4, 1, 'CGS Room 102', 'Discussion room for graduate students', NULL, '2026-03-23 17:26:39'),
(27, 4, 1, 'CGS Seminar Hall', 'Seminar and presentation room for graduate programs', NULL, '2026-03-23 17:26:39'),
(28, 4, 1, 'CGS Faculty Office A', 'Office for graduate program instructors', NULL, '2026-03-23 17:26:39'),
(29, 4, 1, 'CGS Research Room 1', 'Research workspace for graduate students', NULL, '2026-03-23 17:26:39'),
(30, 4, 2, 'CGS Room 201', 'Advanced lecture room for graduate studies', NULL, '2026-03-23 17:26:39'),
(31, 4, 2, 'CGS Room 202', 'Collaborative learning space for graduate students', NULL, '2026-03-23 17:26:39'),
(32, 4, 2, 'CGS Thesis Room', 'Dedicated room for thesis writing and consultation', NULL, '2026-03-23 17:26:39'),
(33, 4, 2, 'CGS Faculty Office B', 'Office for senior faculty members', NULL, '2026-03-23 17:26:39'),
(34, 4, 2, 'CGS Research Room 2', 'Quiet research and study area', NULL, '2026-03-23 17:26:39'),
(35, 5, 1, 'HS Room 101', 'Classroom for junior high school students', NULL, '2026-03-23 17:28:06'),
(36, 5, 1, 'HS Room 102', 'Learning space for high school classes', NULL, '2026-03-23 17:28:06'),
(37, 5, 1, 'HS Faculty Room', 'Office for high school teachers', NULL, '2026-03-23 17:28:06'),
(68, 7, 1, 'Alumni Hall', 'Main hall for alumni gatherings and events', NULL, '2026-03-23 17:32:19'),
(69, 7, 1, 'Alumni Office', 'Office for alumni relations and coordination', NULL, '2026-03-23 17:32:19'),
(70, 7, 1, 'Alumni Lounge', 'Relaxation and meeting space for alumni visitors', NULL, '2026-03-23 17:32:19'),
(71, 14, 2, 'Room 23', 'Student & Teacher\'s Main Hall', NULL, '2026-03-24 20:34:30'),
(72, 14, 1, 'Room 101', 'General Education Classroom', 'Classroom', '2026-03-24 20:40:19'),
(73, 14, 1, 'Room 102', 'Teaching Methods Laboratory', 'Laboratory', '2026-03-24 20:40:19'),
(74, 14, 1, 'Room 103', 'Demonstration Teaching Room', 'Classroom', '2026-03-24 20:40:19'),
(75, 14, 1, 'Room 104', 'Early Childhood Education Room', 'Classroom', '2026-03-24 20:40:19'),
(76, 14, 1, 'Room 105', 'Dean\'s Office - CTE', 'Office', '2026-03-24 20:40:19'),
(77, 14, 1, 'Room 106', 'Program Chair Office', 'Office', '2026-03-24 20:40:19'),
(78, 14, 1, 'Room 107', 'Faculty Room', 'Office', '2026-03-24 20:40:19'),
(79, 14, 1, 'Room 108', 'Guidance & Counseling Room', 'Office', '2026-03-24 20:40:19'),
(80, 14, 1, 'Room 109', 'Student Teacher Lounge', 'Lounge', '2026-03-24 20:40:19'),
(81, 14, 1, 'Room 110', 'Curriculum Development Room', 'Office', '2026-03-24 20:40:19'),
(82, 14, 1, 'Room 111', 'Lecture Hall A', 'Classroom', '2026-03-24 20:40:19'),
(83, 14, 1, 'Room 112', 'Lecture Hall B', 'Classroom', '2026-03-24 20:40:19'),
(84, 14, 1, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:40:19'),
(85, 14, 1, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:40:19'),
(86, 14, 2, 'Room 201', 'Educational Technology Room', 'Laboratory', '2026-03-24 20:40:19'),
(87, 14, 2, 'Room 202', 'Micro Teaching Laboratory', 'Laboratory', '2026-03-24 20:40:19'),
(88, 14, 2, 'Room 203', 'Special Education Room', 'Classroom', '2026-03-24 20:40:19'),
(89, 14, 2, 'Room 204', 'Filipino Language Room', 'Classroom', '2026-03-24 20:40:19'),
(90, 14, 2, 'Room 205', 'Mathematics Teaching Room', 'Classroom', '2026-03-24 20:40:19'),
(91, 14, 2, 'Room 206', 'Science Teaching Room', 'Classroom', '2026-03-24 20:40:19'),
(92, 14, 2, 'Room 207', 'English Language Room', 'Classroom', '2026-03-24 20:40:19'),
(93, 14, 2, 'Room 208', 'Social Studies Room', 'Classroom', '2026-03-24 20:40:19'),
(94, 14, 2, 'Room 209', 'Audio Visual Room', 'Classroom', '2026-03-24 20:40:19'),
(95, 14, 2, 'Room 210', 'Seminar Room', 'Meeting Room', '2026-03-24 20:40:19'),
(96, 14, 2, 'Room 211', 'Student Organization Room', 'Office', '2026-03-24 20:40:19'),
(97, 14, 2, 'Room 212', 'Records & Evaluation Room', 'Office', '2026-03-24 20:40:19'),
(98, 14, 2, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:40:19'),
(99, 14, 2, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:40:19'),
(100, 14, 3, 'Room 301', 'Practice Teaching Coordination Room', 'Office', '2026-03-24 20:40:19'),
(101, 14, 3, 'Room 302', 'Research & Development Room', 'Office', '2026-03-24 20:40:19'),
(102, 14, 3, 'Room 303', 'Thesis Writing Room', 'Office', '2026-03-24 20:40:19'),
(103, 14, 3, 'Room 304', 'Values Education Room', 'Classroom', '2026-03-24 20:40:19'),
(104, 14, 3, 'Room 305', 'Physical Education Theory Room', 'Classroom', '2026-03-24 20:40:19'),
(105, 14, 3, 'Room 306', 'Arts & Crafts Room', 'Laboratory', '2026-03-24 20:40:19'),
(106, 14, 3, 'Room 307', 'Music Room', 'Laboratory', '2026-03-24 20:40:19'),
(107, 14, 3, 'Room 308', 'Home Economics Room', 'Laboratory', '2026-03-24 20:40:19'),
(108, 14, 3, 'Room 309', 'Student Teacher Conference Room', 'Meeting Room', '2026-03-24 20:40:19'),
(109, 14, 3, 'Room 310', 'Instructional Materials Room', 'Storage', '2026-03-24 20:40:19'),
(110, 14, 3, 'Room 311', 'Study Hall', 'Lounge', '2026-03-24 20:40:19'),
(111, 14, 3, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:40:19'),
(112, 14, 3, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:40:19'),
(113, 9, 1, 'Main Reception', 'Student Reception and Waiting Area', 'Office', '2026-03-24 20:43:50'),
(114, 9, 1, 'Counseling Room 1', 'Individual Counseling Session Room', 'Office', '2026-03-24 20:43:50'),
(115, 9, 1, 'Counseling Room 2', 'Individual Counseling Session Room', 'Office', '2026-03-24 20:43:50'),
(116, 9, 1, 'Group Counseling Room', 'Group Session and Peer Counseling Room', 'Office', '2026-03-24 20:43:50'),
(117, 9, 1, 'Testing Room', 'Psychological and Career Assessment Room', 'Office', '2026-03-24 20:43:50'),
(118, 9, 1, 'Records Room', 'Student Records and Documentation', 'Storage', '2026-03-24 20:43:50'),
(119, 9, 1, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:43:50'),
(120, 9, 1, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:43:50'),
(121, 9, 2, 'Guidance Director Office', 'Office of the Guidance Director', 'Office', '2026-03-24 20:43:50'),
(122, 9, 2, 'Guidance Counselor Office', 'Office of the Guidance Counselors', 'Office', '2026-03-24 20:43:50'),
(123, 9, 2, 'Career Development Room', 'Career Guidance and Job Placement Room', 'Office', '2026-03-24 20:43:50'),
(124, 9, 2, 'Scholarship Office', 'Student Scholarship Assistance Room', 'Office', '2026-03-24 20:43:50'),
(125, 9, 2, 'Conference Room', 'Staff Meeting and Conference Room', 'Meeting Room', '2026-03-24 20:43:50'),
(126, 9, 2, 'Student Wellness Room', 'Mental Health and Student Wellness Area', 'Lounge', '2026-03-24 20:43:50'),
(127, 9, 2, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:43:50'),
(128, 9, 2, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:43:50'),
(129, 10, 1, 'CAS Dean Office', 'Office of the College Dean', 'Office', '2026-03-24 20:48:30'),
(130, 10, 1, 'CAS Faculty Room', 'Faculty Workspace and Lounge', 'Office', '2026-03-24 20:48:30'),
(131, 10, 1, 'Lecture Room 101', 'General Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(132, 10, 1, 'Lecture Room 102', 'General Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(133, 10, 1, 'Science Laboratory 1', 'Biology and Chemistry Experiments', 'Laboratory', '2026-03-24 20:48:30'),
(134, 10, 1, 'Storage Room', 'Equipment and Supply Storage', 'Storage', '2026-03-24 20:48:30'),
(135, 10, 1, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:48:30'),
(136, 10, 1, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:48:30'),
(137, 10, 2, 'Lecture Room 201', 'Advanced Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(138, 10, 2, 'Lecture Room 202', 'Advanced Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(139, 10, 2, 'Computer Laboratory 1', 'IT and Programming Lab', 'Laboratory', '2026-03-24 20:48:30'),
(140, 10, 2, 'Research Room 1', 'Student Research Area', 'Office', '2026-03-24 20:48:30'),
(141, 10, 2, 'Audio-Visual Room', 'Multimedia and Presentations', 'Lecture Hall', '2026-03-24 20:48:30'),
(142, 10, 2, 'Student Lounge', 'Study and Relaxation Area', 'Lounge', '2026-03-24 20:48:30'),
(143, 10, 2, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:48:30'),
(144, 10, 2, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:48:30'),
(145, 10, 3, 'Lecture Room 301', 'Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(146, 10, 3, 'Lecture Room 302', 'Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(147, 10, 3, 'Physics Laboratory', 'Physics Experiments and Equipment', 'Laboratory', '2026-03-24 20:48:30'),
(148, 10, 3, 'Chemistry Laboratory', 'Chemical Analysis and Experiments', 'Laboratory', '2026-03-24 20:48:30'),
(149, 10, 3, 'Faculty Office 1', 'Faculty Office Space', 'Office', '2026-03-24 20:48:30'),
(150, 10, 3, 'Faculty Office 2', 'Faculty Office Space', 'Office', '2026-03-24 20:48:30'),
(151, 10, 3, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:48:30'),
(152, 10, 3, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:48:30'),
(153, 10, 4, 'Lecture Room 401', 'Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(154, 10, 4, 'Lecture Room 402', 'Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(155, 10, 4, 'Computer Laboratory 2', 'Advanced Programming Lab', 'Laboratory', '2026-03-24 20:48:30'),
(156, 10, 4, 'Research Room 2', 'Advanced Research Workspace', 'Office', '2026-03-24 20:48:30'),
(157, 10, 4, 'Conference Room', 'Meetings and Academic Discussions', 'Meeting Room', '2026-03-24 20:48:30'),
(158, 10, 4, 'Student Organization Room', 'Clubs and Organization Activities', 'Office', '2026-03-24 20:48:30'),
(159, 10, 4, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:48:30'),
(160, 10, 4, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:48:30'),
(161, 10, 5, 'Lecture Room 501', 'Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(162, 10, 5, 'Lecture Room 502', 'Lecture Classroom', 'Classroom', '2026-03-24 20:48:30'),
(163, 10, 5, 'Thesis Room', 'Thesis Writing and Consultation Room', 'Office', '2026-03-24 20:48:30'),
(164, 10, 5, 'Library Extension', 'Reading and Study Area', 'Library', '2026-03-24 20:48:30'),
(165, 10, 5, 'Seminar Room', 'Seminars and Academic Events', 'Lecture Hall', '2026-03-24 20:48:30'),
(166, 10, 5, 'Graduate Studies Office', 'Graduate Program Office', 'Office', '2026-03-24 20:48:30'),
(167, 10, 5, 'Comfort Room (Male)', 'Male Restroom', 'Restroom', '2026-03-24 20:48:30'),
(168, 10, 5, 'Comfort Room (Female)', 'Female Restroom', 'Restroom', '2026-03-24 20:48:30'),
(169, 11, 1, 'Sports Office', 'Office for sports coordinator and staff', 'Office', '2026-03-24 20:51:30'),
(170, 11, 1, 'Equipment Storage Room 1', 'Storage for basketball and volleyball equipment', 'Storage', '2026-03-24 20:51:30'),
(171, 11, 1, 'Equipment Storage Room 2', 'Storage for badminton and table tennis equipment', 'Storage', '2026-03-24 20:51:30'),
(172, 11, 1, 'Training Room 1', 'Athlete training and conditioning room', 'Training Room', '2026-03-24 20:51:30'),
(173, 11, 1, 'Training Room 2', 'Athlete training and conditioning room', 'Training Room', '2026-03-24 20:51:30'),
(174, 11, 1, 'Locker Room (Male)', 'Male athlete locker and changing room', 'Locker Room', '2026-03-24 20:51:30'),
(175, 11, 1, 'Locker Room (Female)', 'Female athlete locker and changing room', 'Locker Room', '2026-03-24 20:51:30'),
(176, 11, 1, 'Shower Room (Male)', 'Male shower area', 'Restroom', '2026-03-24 20:51:30'),
(177, 11, 1, 'Shower Room (Female)', 'Female shower area', 'Restroom', '2026-03-24 20:51:30'),
(178, 11, 1, 'Medical Room', 'First aid and sports clinic room', 'Clinic', '2026-03-24 20:51:30'),
(179, 11, 1, 'Fitness Room', 'Fitness and warm-up area for athletes', 'Training Room', '2026-03-24 20:51:30'),
(180, 11, 1, 'Coach Room', 'Room for coaches and trainers', 'Office', '2026-03-24 20:51:30'),
(181, 11, 1, 'Utility Room', 'Maintenance tools and janitorial supplies', 'Utility', '2026-03-24 20:51:30'),
(182, 11, 1, 'Comfort Room (Male)', 'Male restroom', 'Restroom', '2026-03-24 20:51:30'),
(183, 11, 1, 'Comfort Room (Female)', 'Female restroom', 'Restroom', '2026-03-24 20:51:30'),
(184, 11, 2, 'Main Hall', 'Covered gymnasium main hall for sports events, practices, and large gatherings', 'Gym', '2026-03-24 20:51:30'),
(185, 16, 1, 'Sports Office', 'Office for sports coordinator and staff', 'Office', '2026-03-24 20:56:15'),
(186, 16, 1, 'Equipment Storage Room 1', 'Storage for basketball and volleyball equipment', 'Storage', '2026-03-24 20:56:15'),
(187, 16, 1, 'Equipment Storage Room 2', 'Storage for badminton and table tennis equipment', 'Storage', '2026-03-24 20:56:15'),
(188, 16, 1, 'Training Room 1', 'Athlete training and conditioning room', 'Training Room', '2026-03-24 20:56:15'),
(189, 16, 1, 'Training Room 2', 'Athlete training and conditioning room', 'Training Room', '2026-03-24 20:56:15'),
(190, 16, 1, 'Locker Room (Male)', 'Male athlete locker and changing room', 'Locker Room', '2026-03-24 20:56:15'),
(191, 16, 1, 'Locker Room (Female)', 'Female athlete locker and changing room', 'Locker Room', '2026-03-24 20:56:15'),
(192, 16, 1, 'Shower Room (Male)', 'Male shower area', 'Restroom', '2026-03-24 20:56:15'),
(193, 16, 1, 'Shower Room (Female)', 'Female shower area', 'Restroom', '2026-03-24 20:56:15'),
(194, 16, 1, 'Medical Room', 'First aid and sports clinic room', 'Clinic', '2026-03-24 20:56:15'),
(195, 16, 1, 'Fitness Room', 'Fitness and warm-up area for athletes', 'Training Room', '2026-03-24 20:56:15'),
(196, 16, 1, 'Coach Room', 'Room for coaches and trainers', 'Office', '2026-03-24 20:56:15'),
(197, 16, 1, 'Utility Room', 'Maintenance tools and janitorial supplies', 'Utility', '2026-03-24 20:56:15'),
(198, 16, 1, 'Comfort Room (Male)', 'Male restroom', 'Restroom', '2026-03-24 20:56:15'),
(199, 16, 1, 'Comfort Room (Female)', 'Female restroom', 'Restroom', '2026-03-24 20:56:15'),
(200, 16, 2, 'Main Hall', 'Covered gymnasium main hall for sports events, practices, and large gatherings', 'Gym', '2026-03-24 20:56:15'),
(201, 13, 1, 'Alumni Office', 'Office for alumni relations and coordination', 'Office', '2026-03-24 20:58:29'),
(202, 13, 1, 'Reception Area', 'Reception and waiting area for alumni visitors', 'Office', '2026-03-24 20:58:29'),
(203, 13, 1, 'Conference Room', 'Meetings and alumni gatherings', 'Meeting Room', '2026-03-24 20:58:29'),
(204, 13, 1, 'Function Hall', 'Events, reunions, and alumni programs', 'Hall', '2026-03-24 20:58:29'),
(205, 13, 1, 'Alumni Lounge', 'Relaxation and networking space for alumni', 'Lounge', '2026-03-24 20:58:29'),
(206, 13, 1, 'Records Room', 'Storage of alumni records and documents', 'Storage', '2026-03-24 20:58:29'),
(207, 13, 1, 'Pantry', 'Food preparation and refreshments area', 'Utility', '2026-03-24 20:58:29'),
(208, 13, 1, 'Storage Room', 'General storage for equipment and supplies', 'Storage', '2026-03-24 20:58:29'),
(209, 13, 1, 'Comfort Room (Male)', 'Male restroom', 'Restroom', '2026-03-24 20:58:29'),
(210, 13, 1, 'Comfort Room (Female)', 'Female restroom', 'Restroom', '2026-03-24 20:58:29'),
(211, 5, 1, 'Classroom 101', 'Grade 7 Classroom', 'Classroom', '2026-03-24 20:59:46'),
(212, 5, 1, 'Classroom 102', 'Grade 8 Classroom', 'Classroom', '2026-03-24 20:59:46'),
(213, 5, 1, 'Classroom 103', 'Grade 9 Classroom', 'Classroom', '2026-03-24 20:59:46'),
(214, 5, 1, 'Classroom 104', 'Grade 10 Classroom', 'Classroom', '2026-03-24 20:59:46'),
(215, 5, 1, 'Science Laboratory', 'General Science Experiments', 'Laboratory', '2026-03-24 20:59:46'),
(216, 5, 1, 'Computer Laboratory', 'ICT and Programming Room', 'Laboratory', '2026-03-24 20:59:46'),
(217, 5, 1, 'Faculty Room', 'Teachers workspace and lounge', 'Office', '2026-03-24 20:59:46'),
(218, 5, 1, 'Guidance Office', 'Student counseling and support', 'Office', '2026-03-24 20:59:46'),
(219, 5, 1, 'Comfort Room (Male)', 'Male restroom', 'Restroom', '2026-03-24 20:59:46'),
(220, 5, 1, 'Comfort Room (Female)', 'Female restroom', 'Restroom', '2026-03-24 20:59:46'),
(221, 3, 1, 'Mechanical Workshop', 'Hands-on machining and mechanical training', 'Laboratory', '2026-03-24 21:01:33'),
(222, 3, 1, 'Welding Shop', 'Metal welding and fabrication training', 'Laboratory', '2026-03-24 21:01:33'),
(223, 3, 1, 'Electrical Workshop', 'Electrical wiring and installation training', 'Laboratory', '2026-03-24 21:01:33'),
(224, 3, 1, 'Electronics Laboratory', 'Circuit design and electronics projects', 'Laboratory', '2026-03-24 21:01:33'),
(225, 3, 1, 'Automotive Workshop', 'Vehicle maintenance and repair training', 'Laboratory', '2026-03-24 21:01:33'),
(226, 3, 1, 'Carpentry Shop', 'Woodwork and furniture making', 'Laboratory', '2026-03-24 21:01:33'),
(227, 3, 1, 'Plumbing Workshop', 'Pipe fitting and plumbing systems training', 'Laboratory', '2026-03-24 21:01:33'),
(228, 3, 1, 'Engineering Drawing Room', 'Technical drafting and design', 'Classroom', '2026-03-24 21:01:33'),
(229, 3, 1, 'CAD Laboratory', 'Computer-aided design and modeling', 'Laboratory', '2026-03-24 21:01:33'),
(230, 3, 1, 'Materials Testing Lab', 'Testing strength and properties of materials', 'Laboratory', '2026-03-24 21:01:33'),
(231, 3, 1, 'Tool Room', 'Storage of tools and equipment', 'Storage', '2026-03-24 21:01:33'),
(232, 3, 1, 'Equipment Storage', 'Heavy equipment storage area', 'Storage', '2026-03-24 21:01:33'),
(233, 3, 1, 'Instructor Room', 'Faculty workspace for instructors', 'Office', '2026-03-24 21:01:33'),
(234, 3, 1, 'Safety Room', 'Safety gear and training room', 'Utility', '2026-03-24 21:01:33'),
(235, 3, 1, 'Project Room 1', 'Student project workspace', 'Laboratory', '2026-03-24 21:01:33'),
(236, 3, 1, 'Project Room 2', 'Student project workspace', 'Laboratory', '2026-03-24 21:01:33'),
(237, 3, 1, 'Comfort Room (Male)', 'Male restroom', 'Restroom', '2026-03-24 21:01:33'),
(238, 3, 1, 'Comfort Room (Female)', 'Female restroom', 'Restroom', '2026-03-24 21:01:33'),
(239, 3, 2, 'Culinary Kitchen 1', 'Basic cooking and food preparation', 'Laboratory', '2026-03-24 21:01:33'),
(240, 3, 2, 'Culinary Kitchen 2', 'Advanced cooking and culinary training', 'Laboratory', '2026-03-24 21:01:33'),
(241, 3, 2, 'Baking Laboratory', 'Bread and pastry production', 'Laboratory', '2026-03-24 21:01:33'),
(242, 3, 2, 'Food Processing Lab', 'Food preservation and processing', 'Laboratory', '2026-03-24 21:01:33'),
(243, 3, 2, 'Dining Simulation Room', 'Restaurant service training', 'Laboratory', '2026-03-24 21:01:33'),
(244, 3, 2, 'Bar and Beverage Lab', 'Beverage preparation and bartending', 'Laboratory', '2026-03-24 21:01:33'),
(245, 3, 2, 'Nutrition Lab', 'Food nutrition and analysis', 'Laboratory', '2026-03-24 21:01:33'),
(246, 3, 2, 'Housekeeping Lab', 'Hotel and housekeeping training', 'Laboratory', '2026-03-24 21:01:33'),
(247, 3, 2, 'Hospitality Training Room', 'Customer service and hospitality skills', 'Classroom', '2026-03-24 21:01:33'),
(248, 3, 2, 'Cold Storage Room', 'Storage for perishable ingredients', 'Storage', '2026-03-24 21:01:33'),
(249, 3, 2, 'Dry Storage Room', 'Storage for dry food supplies', 'Storage', '2026-03-24 21:01:33'),
(250, 3, 2, 'Chef Instructor Room', 'Office for culinary instructors', 'Office', '2026-03-24 21:01:33'),
(251, 3, 2, 'Demo Kitchen', 'Cooking demonstrations and lectures', 'Laboratory', '2026-03-24 21:01:33'),
(252, 3, 2, 'Food Tasting Room', 'Food evaluation and tasting', 'Laboratory', '2026-03-24 21:01:33'),
(253, 3, 2, 'Project Kitchen', 'Student culinary projects', 'Laboratory', '2026-03-24 21:01:33'),
(254, 3, 2, 'Laundry Room', 'Linen and kitchen uniform cleaning', 'Utility', '2026-03-24 21:01:33'),
(255, 3, 2, 'Comfort Room (Male)', 'Male restroom', 'Restroom', '2026-03-24 21:01:33'),
(256, 3, 2, 'Comfort Room (Female)', 'Female restroom', 'Restroom', '2026-03-24 21:01:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `building_points`
--
ALTER TABLE `building_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `building_id` (`building_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `building_id` (`building_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `building_points`
--
ALTER TABLE `building_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`);

--
-- Constraints for table `building_points`
--
ALTER TABLE `building_points`
  ADD CONSTRAINT `building_points_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
