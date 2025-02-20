-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 12:58 AM
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
-- Database: `docconnect_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` varchar(16) NOT NULL,
  `contact` varchar(64) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `account_image` varchar(64) NOT NULL,
  `account_type` varchar(16) NOT NULL,
  `verification_status` varchar(16) NOT NULL DEFAULT 'Unverified',
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0,
  `user_role` int(11) NOT NULL,
  `campus_id` int(11) DEFAULT NULL,
  `birthdate` timestamp NULL DEFAULT NULL,
  `role` enum('doctor','patient') NOT NULL DEFAULT 'patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `email`, `password`, `firstname`, `middlename`, `lastname`, `gender`, `contact`, `address`, `account_image`, `account_type`, `verification_status`, `is_created`, `is_updated`, `is_deleted`, `user_role`, `campus_id`, `birthdate`, `role`) VALUES
(9, 'qb202101164@wmsu.edu.ph', '$2y$10$wNYeevuu4mXWBvMsxueJTecDB01lsFCL3bcKdMQlV3ZzqXUbXre1u', 'Hilal', 'J', 'Abdulajid', '', NULL, NULL, '', '', 'Verified', '2024-09-01 06:30:45', '2024-10-26 18:05:11', 0, 0, NULL, NULL, 'patient'),
(11, 'benten@wmsu.edu.ph', '$2y$10$wNYeevuu4mXWBvMsxueJTecDB01lsFCL3bcKdMQlV3ZzqXUbXre1u', 'Ben', '', 'Ten', '', NULL, NULL, '', '', 'Unverified', '2024-09-11 07:33:39', '2024-09-11 08:31:34', 0, 2, 1, NULL, 'patient'),
(12, 'try@wmsu.edu.ph', '$2y$10$7xQdl3p91t7SDFpC5bbsquLldOKDGBpdAyaiAj6VCduFYQKoMcQjO', 'Try ', '', 'Andtry', '', NULL, NULL, '', '', 'Unverified', '2024-09-11 14:24:51', '2024-09-11 14:24:51', 0, 2, 1, NULL, 'patient'),
(15, 'feithan@wmsu.edu.ph', '$2y$10$wNYeevuu4mXWBvMsxueJTecDB01lsFCL3bcKdMQlV3ZzqXUbXre1u', 'Doc', 'Than', 'Fei', 'male', NULL, NULL, '66f5b764886419.82838911.jpg', '', 'Verified', '2024-09-11 16:44:37', '2025-01-17 17:47:27', 0, 1, NULL, '2021-02-11 16:00:00', 'patient'),
(16, 'nestea@wmsu.edu.ph', '$2y$10$SGtMpjX8jGtBKmUHnKrJW..pzcMAZBJmiuv.7NkNt2Jrk8kqkCVRG', 'Nes', 'Tea', 'Lemon', 'female', '099999999999\r\n', NULL, '', '', 'Unverified', '2024-09-11 16:49:45', '2024-10-02 13:20:20', 0, 1, NULL, '1999-01-31 16:00:00', 'patient'),
(17, 'nesteaapple@wmsu.edu.ph', '$2y$10$eaK7BsSyQjxC324zAnlxzejBiQKZJoau4cVyz9YtYcFB7prKB/rwu', 'Nes', 'Tea', 'Apple', 'female', '09993228979', NULL, '', '', 'Verified', '2024-09-11 16:50:35', '2024-09-30 12:00:32', 0, 1, NULL, '2018-02-27 16:00:00', 'patient'),
(19, 'www@wmsu.edu.ph', '$2y$10$oefvsQpyW0dBtXpNOWlFjOsiNaeTV8Nj0JskhRZzrJBLiy68Y6jSa', 'Wew', 'Waw', 'Wow', 'male', '099999999999', NULL, '671d6ceab710a3.70928484.png', '', 'Verified', '2024-09-11 16:59:02', '2025-01-06 17:22:43', 0, 1, NULL, '2024-09-05 16:00:00', 'doctor'),
(23, 'testuser@wmsu.edu.ph', '$2y$10$oefvsQpyW0dBtXpNOWlFjOsiNaeTV8Nj0JskhRZzrJBLiy68Y6jSa', 'Test', '', 'User', 'Male', '09999232232', NULL, '', '', 'Unverified', '2024-09-17 06:48:14', '2024-09-21 10:24:02', 0, 3, 1, '2000-04-11 16:00:00', 'patient'),
(24, 'qb202100150@wmsu.edu.ph', '$2y$10$wNYeevuu4mXWBvMsxueJTecDB01lsFCL3bcKdMQlV3ZzqXUbXre1u', 'Franklin', 'Ituralde', 'Oliveros', 'Male', '1234567890', 'KCC Mall De Zamboanga, Zamboanga City', 'default_profile.png', '', 'Verified', '2024-09-21 11:24:35', '2025-02-10 07:19:38', 0, 3, 1, '2002-09-20 16:00:00', 'patient'),
(10000, 'xt202000631@wmsu.edu.ph', '$2y$10$wNYeevuu4mXWBvMsxueJTecDB01lsFCL3bcKdMQlV3ZzqXUbXre1u', 'Test', '', 'Doctor', 'Male', '099999999999 ', ' Guiwan Zamboanga City', '671d6ceab710a3.70928484.png', '', 'Verified', '2024-10-26 22:25:04', '2024-11-23 12:04:55', 0, 1, NULL, '1991-02-27 16:00:00', 'patient');

-- --------------------------------------------------------

--
-- Table structure for table `allergy`
--

CREATE TABLE `allergy` (
  `allergy_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `allergy_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` timestamp NULL DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `estimated_end` time NOT NULL,
  `reason` text DEFAULT NULL,
  `appointment_link` varchar(255) DEFAULT NULL,
  `appointment_status` varchar(32) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `estimated_end`, `reason`, `appointment_link`, `appointment_status`, `is_created`, `is_updated`, `is_deleted`) VALUES
(8, 4, 4, '2025-02-18 16:00:00', '10:00:00', '10:59:00', 'Consultation', NULL, 'Incoming', '2025-02-10 16:38:11', '2025-02-13 20:09:52', 0),
(9, 4, 4, '2025-02-12 16:00:00', '13:00:00', '13:59:00', 'Check up', NULL, 'Pending', '2025-02-10 16:46:10', '2025-02-11 18:18:21', 0),
(10, 4, 4, '2025-02-18 16:00:00', '09:00:00', '09:59:00', 'Check up for Headache', NULL, 'Incoming', '2025-02-10 16:47:48', '2025-02-13 20:09:56', 0),
(12, 4, 4, '2025-02-18 16:00:00', '11:00:00', '11:59:00', 'Test', NULL, 'Incoming', '2025-02-11 17:10:54', '2025-02-13 20:10:00', 0),
(13, 4, 4, '2025-02-18 16:00:00', '12:30:00', '13:29:00', 'Testing 2', NULL, 'Incoming', '2025-02-11 18:27:52', '2025-02-13 20:10:04', 0),
(14, 4, 4, '2025-02-18 16:00:00', '11:00:00', '11:59:00', 'Testing 3', NULL, 'Pending', '2025-02-11 18:40:02', '2025-02-13 20:10:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `campus`
--

CREATE TABLE `campus` (
  `campus_id` int(11) NOT NULL,
  `campus_profile` varchar(255) DEFAULT NULL,
  `campus_name` varchar(255) NOT NULL,
  `campus_address` varchar(255) NOT NULL,
  `campus_contact` varchar(32) NOT NULL,
  `campus_email` varchar(255) NOT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campus`
--

INSERT INTO `campus` (`campus_id`, `campus_profile`, `campus_name`, `campus_address`, `campus_contact`, `campus_email`, `is_created`, `is_updated`, `is_deleted`) VALUES
(1, '66e7db42336204.60963457.jpg', 'WMSU MAIN CAMPUS', 'W376+CGQ, Normal Rd, Zamboanga, 7000 Zamboanga del Sur', '(062) 991 1040', 'wmsu@wmsu.edu.ph', '2024-09-08 13:00:26', '2024-09-16 07:18:23', 0),
(4, '66e7db42336204.60963457.jpg', 'Test Campus', 'W376+CGQ, Normal Rd, Zamboanga, 7000 Zamboanga del Sur', '(062) 991 1040', 'test@wmsu.edu.ph', '2024-09-16 07:16:18', '2024-09-16 07:16:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_messages`
--

CREATE TABLE `chatbot_messages` (
  `cb_message_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `message_type` varchar(16) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_messages`
--

INSERT INTO `chatbot_messages` (`cb_message_id`, `account_id`, `message`, `message_type`, `is_created`) VALUES
(1, 24, 'hello', 'user', '2024-10-26 06:54:46'),
(2, 24, 'my head hurts since yesterday, give me a step by step process of what to do', 'bot', '2024-10-26 07:18:29'),
(3, 24, 'what is herpes', 'user', '2024-10-26 08:56:05'),
(4, 24, 'who is available in monday?', 'bot', '2024-10-26 08:56:52'),
(40, 24, 'Check Key', 'user', '2025-01-28 22:05:54'),
(41, 24, 'OpenAI key is not set.', 'bot', '2025-01-28 22:05:54'),
(42, 24, 'Test', 'user', '2025-01-28 22:08:25'),
(43, 24, 'I\'m sorry, but I\'m unable to assist with that request. If you have any medical symptoms or questions related to them, feel free to share, and I can help.', 'bot', '2025-01-28 22:08:29'),
(44, 24, 'Tell me the available doctors', 'user', '2025-01-28 22:08:41'),
(45, 24, 'Here is the list of available doctors:\n\n1. Nes Tea Apple - Dentist (Monday to Tuesday 08:09 PM - 11:09 PM)\n2. Wew Waw Wow - Physician (Saturday to Sunday 08:00 AM - 08:30 PM)\n3. Test Doctor - Physician (Wednesday to Friday 09:00 AM - 02:00 PM)', 'bot', '2025-01-28 22:08:43'),
(46, 24, 'hi\n', 'user', '2025-02-04 19:22:14'),
(47, 24, 'Hello! How can I assist you today? If you have any medical questions or need information about our doctors, feel free to ask.', 'bot', '2025-02-04 19:22:17'),
(48, 24, 'hi\n', 'user', '2025-02-04 19:22:17'),
(49, 24, 'Hello! How can I assist you today? If you have any symptoms you\'d like to discuss, feel free to share.', 'bot', '2025-02-04 19:22:19'),
(50, 24, 'Hii Who are the doctors available for today?', 'user', '2025-02-11 18:53:28'),
(51, 24, 'I\'m unable to provide information specifically for today, but here is the list of available doctors:\n\n1. Nes Tea Apple - Dentist (Monday to Tuesday 08:09 PM - 11:09 PM)\n2. Wew Waw Wow - Physician (Saturday to Sunday 08:00 AM - 08:30 PM)\n3. Test Doctor - Physician (Wednesday to Friday 09:00 AM - 02:00 PM)\n\nIf you have specific symptoms, I can recommend a doctor based on that!', 'bot', '2025-02-11 18:53:32'),
(52, 24, 'Who is the available doctor for today feb 12 2025', 'user', '2025-02-11 18:54:33'),
(53, 24, 'I\'m sorry, but I cannot provide information about specific dates or future availability of doctors. However, here is the list of available doctors:\n\n1. Nes Tea Apple - Dentist (Monday to Tuesday 08:09 PM - 11:09 PM)\n2. Wew Waw Wow - Physician (Saturday to Sunday 08:00 AM - 08:30 PM)\n3. Test Doctor - Physician (Wednesday to Friday 09:00 AM - 02:00 PM)\n\nIf you have any symptoms you\'d like to discuss, I can recommend a doctor based on that information.', 'bot', '2025-02-11 18:54:36'),
(54, 24, 'I have swollen ankle', 'user', '2025-02-11 18:56:21'),
(55, 24, 'Swelling in the ankle can be caused by various factors such as injury, fluid retention, or other medical conditions. For your symptoms, I recommend seeing a physician.\n\nThe available physician is Wew Waw Wow, who is available on Saturday to Sunday from 08:00 AM to 08:30 PM and Test Doctor, who is available from Wednesday to Friday from 09:00 AM to 02:00 PM.\n\nPlease let me know if you’d like to book an appointment, and I can provide you with a link to the appointment page.', 'bot', '2025-02-11 18:56:24'),
(56, 24, 'who is available at saturday', 'user', '2025-02-11 18:58:21'),
(57, 24, 'On Saturday, Wew Waw Wow, a Physician, is available from 08:00 AM to 08:30 PM.', 'bot', '2025-02-11 18:58:23'),
(58, 24, 'available doctors', 'user', '2025-02-11 18:59:28'),
(59, 24, 'Here is the list of available doctors:\n\n1. Nes Tea Apple - Dentist (Monday to Tuesday 08:09 PM - 11:09 PM)\n2. Wew Waw Wow - Physician (Saturday to Sunday 08:00 AM - 08:30 PM)\n3. Test Doctor - Physician (Wednesday to Friday 09:00 AM - 02:00 PM) \n\nIf you have specific symptoms, I can help recommend a doctor based on those.', 'bot', '2025-02-11 18:59:31'),
(60, 24, 'who is available today', 'user', '2025-02-11 18:59:41'),
(61, 24, 'Today is Wednesday, and the available doctors are:\n\n1. Test Doctor - Physician (Wednesday to Friday 09:00 AM - 02:00 PM)\n\nPlease let me know if you need further information or would like to make an appointment!', 'bot', '2025-02-11 18:59:43'),
(62, 24, 'who is available at 12/02/2025', 'user', '2025-02-11 19:00:03'),
(63, 24, 'I\'m sorry, but I do not have information regarding availability for the date 12/02/2025.', 'bot', '2025-02-11 19:00:04'),
(64, 24, 'who is available at 12/02/2025', 'user', '2025-02-11 19:02:06'),
(65, 24, 'I don\'t have information about doctor availability on the date you provided. The available doctors and their schedules are as follows:\n\n1. Nes Tea Apple - Dentist (Monday to Tuesday 08:09 PM - 11:09 PM)\n2. Wew Waw Wow - Physician (Saturday to Sunday 08:00 AM - 08:30 PM)\n3. Test Doctor - Physician (Wednesday to Friday 09:00 AM - 02:00 PM)\n\nIf you need assistance with specific symptoms or wish to book an appointment, feel free to ask!', 'bot', '2025-02-11 19:02:09'),
(66, 24, 'Who is available on 12/02/2025', 'user', '2025-02-11 19:04:55'),
(67, 24, 'I\'m sorry, but I cannot provide information about availability on that date. Please check back closer to the date for appointment scheduling.', 'bot', '2025-02-11 19:04:57'),
(68, 24, 'who is available on saturday', 'user', '2025-02-11 19:05:16'),
(69, 24, 'On Saturday, Wew Waw Wow, a Physician, is available from 08:00 AM to 08:30 PM.', 'bot', '2025-02-11 19:05:18'),
(70, 24, 'what date is this coming saturday', 'user', '2025-02-11 19:05:43'),
(71, 24, 'I\'m sorry, but I am unable to assist with that request.', 'bot', '2025-02-11 19:05:44'),
(72, 24, 'Who is available for feb 13 2025', 'user', '2025-02-11 19:07:26'),
(73, 24, 'I\'m sorry, but I don\'t have data available for appointments on February 13, 2025. The available doctors and their schedules are limited to the current week. Please check back closer to that date for updated information.', 'bot', '2025-02-11 19:07:30'),
(74, 24, 'Who is available for feb 13 2025', 'user', '2025-02-11 19:09:15'),
(75, 24, 'I\'m sorry, but I cannot provide information for dates beyond the current list of available doctors and appointments. If you have any symptoms or specific inquiries regarding the available doctors, please let me know!', 'bot', '2025-02-11 19:09:17'),
(76, 24, 'Who is available for feb 13 2025', 'user', '2025-02-11 19:09:38'),
(77, 24, 'I\'m sorry, but I do not have information about availability outside of the current week. If you need assistance with available doctors and appointments for this week, please let me know!', 'bot', '2025-02-11 19:09:40'),
(78, 24, 'Saturday', 'user', '2025-02-11 19:10:21'),
(79, 24, 'I can assist you with medical questions or help you find available doctors. Please let me know what symptoms you are experiencing, and I will recommend a doctor for you.', 'bot', '2025-02-11 19:10:29'),
(80, 24, 'can you recommended me doctor that can help me with my toothache', 'user', '2025-02-11 19:11:30'),
(81, 24, 'I recommend seeing Nes Tea Apple, who is a Dentist. \n\nHere are the available days and times for the next 7 days:\n\n- **Monday:** 08:09 PM - 11:09 PM\n- **Tuesday:** 08:09 PM - 11:09 PM\n\nYou can book an appointment through the appointment page.', 'bot', '2025-02-11 19:11:35'),
(82, 24, 'who are available for today', 'user', '2025-02-11 19:12:46'),
(83, 24, 'For today, the available doctors are:\n\n1. Nes Tea Apple - Dentist (Available from 08:09 PM - 11:09 PM)\n2. Wew Waw Wow - Physician (Available from 08:00 AM - 08:30 PM)\n\nPlease let me know if you would like to book an appointment or need more information!', 'bot', '2025-02-11 19:12:49'),
(84, 24, 'How about for feb 13 this year', 'user', '2025-02-11 19:13:20'),
(85, 24, 'I\'m sorry, but I can\'t provide information regarding dates that have already passed. If you have any specific questions about symptoms or need information on available appointments in the upcoming days, please let me know!', 'bot', '2025-02-11 19:13:22'),
(86, 24, 'How about the available doctor for february 13 this year', 'user', '2025-02-11 19:13:47'),
(87, 24, 'I\'m sorry, but I don\'t have the availability information for specific dates in the past. I can provide current and upcoming availability based on the list of doctors. If you need assistance with that, please let me know!', 'bot', '2025-02-11 19:13:48');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_info`
--

CREATE TABLE `doctor_info` (
  `doctor_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `specialty` varchar(64) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `start_wt` time DEFAULT NULL,
  `end_wt` time DEFAULT NULL,
  `start_day` varchar(16) NOT NULL,
  `end_day` varchar(16) NOT NULL,
  `appointment_limits` int(11) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_info`
--

INSERT INTO `doctor_info` (`doctor_id`, `account_id`, `specialty`, `bio`, `start_wt`, `end_wt`, `start_day`, `end_day`, `appointment_limits`, `is_created`, `is_updated`, `is_deleted`) VALUES
(1, 19, ' Physician', 'As a medical practitioner, I provide compassionate healthcare with expertise in diagnosing, treating, and preventing illnesses and injuries. My patient-centered approach emphasizes trust, communication, and personalized treatment plans.', '08:00:00', '20:30:00', 'Saturday', 'Sunday', NULL, '2024-09-24 14:59:04', '2024-10-27 04:34:06', 0),
(3, 17, 'Dentist', 'HEHE', '20:09:46', '23:09:46', 'Monday', 'Tuesday', 3, '2024-10-02 12:10:36', '2025-01-04 04:49:19', 0),
(4, 10000, ' Physician', '&quot;Dr. Test is a dedicated physician specializing in patient care, focusing on preventive medicine and holistic treatment approaches. With a commitment to improving community health, Dr. Test combines extensive medical knowledge with compassionate care', '09:00:00', '14:00:00', 'Wednesday', 'Friday', NULL, '2024-10-26 22:25:04', '2024-10-26 22:28:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `immunization`
--

CREATE TABLE `immunization` (
  `immu_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `immunization_name` varchar(255) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `medhis_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `his_condition` varchar(255) DEFAULT NULL,
  `diagnosis_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `medication_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `medication_name` varchar(255) DEFAULT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `med_usage` varchar(50) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('sent','delivered','seen') DEFAULT 'sent',
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `created_at`, `status`, `is_read`) VALUES
(866, 24, 19, 'ad', '2024-10-25 15:09:58', '2024-10-25 15:09:58', 'sent', 1),
(867, 24, 19, 'as', '2024-10-25 15:10:08', '2024-10-25 15:10:08', 'sent', 1),
(868, 24, 19, 'da', '2024-10-25 15:10:19', '2024-10-25 15:10:19', 'sent', 1),
(869, 24, 19, 'ad', '2024-10-25 15:10:29', '2024-10-25 15:10:29', 'sent', 1),
(870, 24, 19, 'ad', '2024-10-25 15:10:34', '2024-10-25 15:10:34', 'sent', 1),
(871, 24, 19, 'ad', '2024-10-26 04:01:41', '2024-10-26 04:01:41', 'sent', 1),
(872, 19, 24, 'llallaa', '2024-10-26 04:45:54', '2024-10-26 04:45:54', 'sent', 1),
(873, 24, 19, 'kwk', '2024-10-26 04:48:07', '2024-10-26 04:48:07', 'sent', 1),
(874, 24, 19, 'kk', '2024-10-26 04:48:22', '2024-10-26 04:48:22', 'sent', 1),
(875, 24, 19, 'k', '2024-10-26 04:48:34', '2024-10-26 04:48:34', 'sent', 1),
(876, 24, 19, 'ad', '2024-10-26 06:13:02', '2024-10-26 06:13:02', 'sent', 1),
(877, 24, 19, 'ertyuiop', '2024-10-26 06:13:14', '2024-10-26 06:13:14', 'sent', 1),
(878, 19, 24, 'Hello', '2024-10-26 06:22:24', '2024-10-26 06:22:24', 'sent', 1),
(879, 24, 19, 'hi', '2024-10-26 06:22:31', '2024-10-26 06:22:31', 'sent', 1),
(880, 19, 24, 'Pangit itchie', '2024-10-26 06:22:46', '2024-10-26 06:22:46', 'sent', 1),
(881, 24, 19, 'mareng tu', '2024-10-26 06:22:51', '2024-10-26 06:22:51', 'sent', 1),
(882, 24, 19, 'jahsdkjahsd', '2024-10-26 06:27:34', '2024-10-26 06:27:34', 'sent', 1),
(883, 24, 19, 'hello', '2024-10-26 09:05:04', '2024-10-26 09:05:04', 'sent', 1),
(884, 24, 19, 'j', '2024-10-26 09:05:32', '2024-10-26 09:05:32', 'sent', 1),
(885, 24, 19, 'l', '2024-10-26 09:05:37', '2024-10-26 09:05:37', 'sent', 1),
(886, 15, 24, 'ajshgd', '2024-10-26 09:34:09', '2024-10-26 09:34:09', 'sent', 1),
(887, 15, 24, 'adj', '2024-10-26 09:35:45', '2024-10-26 09:35:45', 'sent', 1),
(888, 19, 24, 'qeqeqeqe', '2024-10-26 11:51:01', '2024-10-26 11:51:01', 'sent', 1),
(889, 19, 24, 'adada', '2024-10-26 11:51:03', '2024-10-26 11:51:03', 'sent', 1),
(890, 19, 24, 'i', '2024-10-26 11:51:05', '2024-10-26 11:51:05', 'sent', 1),
(891, 24, 15, 'bente', '2024-10-26 18:17:18', '2024-10-26 18:17:18', 'sent', 0),
(892, 24, 19, 'ahahaha', '2025-01-09 17:30:46', '2025-01-09 17:30:46', 'sent', 0),
(893, 24, 15, 'HAHAHAHAHHAHA\r\nasdasd', '2025-01-09 17:41:09', '2025-01-09 17:41:09', 'sent', 0),
(894, 24, 15, 'Wazzzzzzup po', '2025-01-09 17:41:25', '2025-01-09 17:41:25', 'sent', 0),
(895, 24, 15, 'hi helooooooo', '2025-01-09 17:46:58', '2025-01-09 17:46:58', 'sent', 0),
(896, 24, 15, 'reloading', '2025-01-09 18:06:51', '2025-01-09 18:06:51', 'sent', 0),
(897, 24, 19, 'wewew', '2025-01-13 14:26:05', '2025-01-13 14:26:05', 'sent', 0),
(898, 24, 15, 'HAHAHAA', '2025-01-13 14:30:01', '2025-01-13 14:30:01', 'sent', 0),
(899, 24, 19, 'wew', '2025-01-13 16:02:58', '2025-01-13 16:02:58', 'sent', 0),
(900, 24, 19, 'hehe', '2025-01-13 16:03:49', '2025-01-13 16:03:49', 'sent', 0),
(901, 24, 19, 'wow', '2025-01-13 16:07:12', '2025-01-13 16:07:12', 'sent', 0),
(902, 24, 19, 'hehe', '2025-01-13 16:22:15', '2025-01-13 16:22:15', 'sent', 0),
(903, 24, 19, 'wowwwwwwwwwwww', '2025-01-13 16:22:31', '2025-01-13 16:22:31', 'sent', 0),
(904, 24, 15, 'test polling', '2025-01-13 16:28:19', '2025-01-13 16:28:19', 'sent', 0),
(905, 24, 19, 'hehe\n', '2025-01-13 16:33:44', '2025-01-13 16:33:44', 'sent', 0),
(906, 24, 19, 'wowoww', '2025-01-13 16:33:55', '2025-01-13 16:33:55', 'sent', 0),
(907, 24, 19, 'kupal?', '2025-01-13 16:34:09', '2025-01-13 16:34:09', 'sent', 0),
(908, 24, 19, 'ayun na', '2025-01-13 16:34:17', '2025-01-13 16:34:17', 'sent', 0),
(909, 24, 19, 'hahahahhaaa', '2025-01-13 16:46:45', '2025-01-13 16:46:45', 'sent', 0),
(910, 24, 19, 'hehe', '2025-01-13 17:11:22', '2025-01-13 17:11:22', 'sent', 0),
(911, 24, 19, 'heeehee', '2025-01-13 17:46:20', '2025-01-13 17:46:20', 'sent', 0),
(912, 24, 19, 'hahaha', '2025-01-14 06:27:33', '2025-01-14 06:27:33', 'sent', 0),
(913, 24, 19, 'ewew', '2025-01-14 07:30:28', '2025-01-14 07:30:28', 'sent', 0),
(914, 24, 19, 'ewew', '2025-01-14 07:30:28', '2025-01-14 07:30:28', 'sent', 0),
(915, 24, 19, 'lol', '2025-01-14 07:30:59', '2025-01-14 07:30:59', 'sent', 0),
(916, 24, 19, 'test new', '2025-01-14 07:48:17', '2025-01-14 07:48:17', 'sent', 0),
(917, 24, 15, 'test new message', '2025-01-14 07:49:19', '2025-01-14 07:49:19', 'sent', 0),
(918, 24, 19, 'testing again', '2025-01-14 08:18:20', '2025-01-14 08:18:20', 'sent', 0),
(919, 24, 19, 'testingg abort\nhehe', '2025-01-14 08:24:02', '2025-01-14 08:24:02', 'sent', 0),
(920, 24, 19, 'a', '2025-01-14 09:02:08', '2025-01-14 09:02:08', 'sent', 0),
(921, 24, 19, 'heyyy', '2025-01-14 09:06:46', '2025-01-14 09:06:46', 'sent', 0),
(922, 24, 19, 'huuhuh', '2025-01-14 09:13:04', '2025-01-14 09:13:04', 'sent', 0),
(923, 24, 19, 'qweqwe', '2025-01-14 09:19:41', '2025-01-14 09:19:41', 'sent', 0),
(924, 24, 15, '2323', '2025-01-14 09:34:40', '2025-01-14 09:34:40', 'sent', 0),
(925, 24, 15, 'ahaha', '2025-01-14 09:40:53', '2025-01-14 09:40:53', 'sent', 0),
(929, 24, 15, 'gg', '2025-01-14 11:57:03', '2025-01-14 11:57:03', 'sent', 0),
(930, 24, 15, 'gegee', '2025-01-14 12:09:15', '2025-01-14 12:09:15', 'sent', 0),
(931, 24, 15, 'testing abort error', '2025-01-14 12:25:16', '2025-01-14 12:25:16', 'sent', 0),
(932, 24, 19, 'test here aborttttttt', '2025-01-14 12:26:13', '2025-01-14 12:26:13', 'sent', 0),
(933, 24, 19, 'test counterr', '2025-01-17 15:32:21', '2025-01-17 15:32:21', 'sent', 0),
(934, 10000, 24, 'Test new chat\r\n', '2025-01-17 17:53:14', '2025-01-17 17:53:14', 'sent', 1),
(935, 10000, 24, 'Test new chat\r\n', '2025-01-17 17:53:17', '2025-01-17 17:53:17', 'sent', 1),
(936, 24, 10000, 'Testing polling', '2025-01-17 18:24:30', '2025-01-17 18:24:30', 'sent', 1),
(937, 24, 10000, 'Check Unread', '2025-01-17 18:33:52', '2025-01-17 18:33:52', 'sent', 1),
(938, 24, 10000, 'hey hey', '2025-01-17 18:37:53', '2025-01-17 18:37:53', 'sent', 1),
(939, 24, 10000, 're run test', '2025-01-17 18:54:49', '2025-01-17 18:54:49', 'sent', 1),
(940, 24, 10000, 'again', '2025-01-17 18:55:15', '2025-01-17 18:55:15', 'sent', 1),
(941, 10000, 24, 'Test other side', '2025-01-17 18:57:47', '2025-01-17 18:57:47', 'sent', 1),
(942, 10000, 24, 'another test', '2025-01-17 18:58:07', '2025-01-17 18:58:07', 'sent', 1),
(943, 24, 10000, 'hii', '2025-01-17 19:01:42', '2025-01-17 19:01:42', 'sent', 1),
(944, 10000, 24, 'Hii\n', '2025-01-17 19:23:09', '2025-01-17 19:23:09', 'sent', 1),
(945, 24, 10000, 'hello', '2025-01-17 19:23:16', '2025-01-17 19:23:16', 'sent', 1),
(946, 24, 10000, 'testt chat ', '2025-01-27 18:34:32', '2025-01-27 18:34:32', 'sent', 1),
(947, 24, 10000, 'hi', '2025-01-28 21:07:29', '2025-01-28 21:07:29', 'sent', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patient_info`
--

CREATE TABLE `patient_info` (
  `patient_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_contact` varchar(16) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_info`
--

INSERT INTO `patient_info` (`patient_id`, `account_id`, `parent_name`, `parent_contact`, `is_created`, `is_updated`, `is_deleted`) VALUES
(3, 31, NULL, NULL, '2024-10-01 10:36:24', '2024-10-01 10:36:24', 0),
(4, 24, NULL, NULL, '2024-10-26 22:34:19', '2024-10-26 22:34:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `vitals`
--

CREATE TABLE `vitals` (
  `vitals_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `heart_rate` varchar(32) DEFAULT NULL,
  `respi_rate` varchar(32) DEFAULT NULL,
  `blood_press` varchar(32) DEFAULT NULL,
  `temperature` varchar(32) DEFAULT NULL,
  `height` varchar(32) DEFAULT NULL,
  `weight` varchar(32) DEFAULT NULL,
  `bmi` varchar(32) DEFAULT NULL,
  `is_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_campus` (`campus_id`);

--
-- Indexes for table `allergy`
--
ALTER TABLE `allergy`
  ADD PRIMARY KEY (`allergy_id`),
  ADD KEY `fk_allpat` (`patient_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_apppat` (`patient_id`),
  ADD KEY `fk_appdoc` (`doctor_id`);

--
-- Indexes for table `campus`
--
ALTER TABLE `campus`
  ADD PRIMARY KEY (`campus_id`);

--
-- Indexes for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD PRIMARY KEY (`cb_message_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `doctor_info`
--
ALTER TABLE `doctor_info`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `fk_docacc` (`account_id`);

--
-- Indexes for table `immunization`
--
ALTER TABLE `immunization`
  ADD PRIMARY KEY (`immu_id`),
  ADD KEY `fk_immpat` (`patient_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`medhis_id`),
  ADD KEY `fk_mhpat` (`patient_id`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`medication_id`),
  ADD KEY `fk_medpat` (`patient_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `patient_info`
--
ALTER TABLE `patient_info`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `fk_patacc` (`account_id`);

--
-- Indexes for table `vitals`
--
ALTER TABLE `vitals`
  ADD PRIMARY KEY (`vitals_id`),
  ADD KEY `fk_vitpat` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10001;

--
-- AUTO_INCREMENT for table `allergy`
--
ALTER TABLE `allergy`
  MODIFY `allergy_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `campus`
--
ALTER TABLE `campus`
  MODIFY `campus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  MODIFY `cb_message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `doctor_info`
--
ALTER TABLE `doctor_info`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `immunization`
--
ALTER TABLE `immunization`
  MODIFY `immu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `medhis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `medication_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=948;

--
-- AUTO_INCREMENT for table `patient_info`
--
ALTER TABLE `patient_info`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vitals`
--
ALTER TABLE `vitals`
  MODIFY `vitals_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `fk_campus` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`campus_id`);

--
-- Constraints for table `allergy`
--
ALTER TABLE `allergy`
  ADD CONSTRAINT `fk_allpat` FOREIGN KEY (`patient_id`) REFERENCES `patient_info` (`patient_id`);

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_appdoc` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_info` (`doctor_id`),
  ADD CONSTRAINT `fk_apppat` FOREIGN KEY (`patient_id`) REFERENCES `patient_info` (`patient_id`);

--
-- Constraints for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD CONSTRAINT `chatbot_messages_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `immunization`
--
ALTER TABLE `immunization`
  ADD CONSTRAINT `fk_immpat` FOREIGN KEY (`patient_id`) REFERENCES `patient_info` (`patient_id`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `fk_mhpat` FOREIGN KEY (`patient_id`) REFERENCES `patient_info` (`patient_id`);

--
-- Constraints for table `medication`
--
ALTER TABLE `medication`
  ADD CONSTRAINT `fk_medpat` FOREIGN KEY (`patient_id`) REFERENCES `patient_info` (`patient_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `vitals`
--
ALTER TABLE `vitals`
  ADD CONSTRAINT `fk_vitpat` FOREIGN KEY (`patient_id`) REFERENCES `patient_info` (`patient_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
