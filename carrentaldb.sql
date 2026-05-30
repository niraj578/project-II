-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2026 at 05:16 AM
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
-- Database: `carrentaldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'unverified',
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `name`, `status`, `created_at`) VALUES
(1, 'admin@gmail.com', 'admin123', 'Hancy Pandey', 'verified', '2024-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `booked_cars`
--

CREATE TABLE `booked_cars` (
  `id` int(11) NOT NULL,
  `carid` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `bookedBy` varchar(255) NOT NULL,
  `bookingDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `carid` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `dropoff_location` varchar(255) NOT NULL,
  `booking_to` date NOT NULL,
  `booking_from` date DEFAULT NULL,
  `booking_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `total money` int(11) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT 'cash',
  `pidx` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `tidx` varchar(255) DEFAULT NULL,
  `khalti_status` varchar(50) DEFAULT 'Pending',
  `esewa_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `carid`, `name`, `email`, `phone`, `pickup_location`, `dropoff_location`, `booking_to`, `booking_from`, `booking_time`, `created_at`, `status`, `total money`, `payment_method`, `pidx`, `transaction_id`, `tidx`, `khalti_status`, `esewa_status`) VALUES
(41, '003', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'radheradhe', 'purano thimi', '2026-01-15', '2026-01-13', '08:10:00', '2026-01-12 01:25:47', 'approved', NULL, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(42, '115', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'newthimi', 'purano thimi', '2026-01-14', '2026-01-13', '22:10:00', '2026-01-12 14:25:26', 'approved', NULL, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(43, '003', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'newthimi', 'baneshwor', '2026-01-17', '2026-01-13', '23:24:00', '2026-01-12 14:39:29', 'approved', 5000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(44, '8', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'radheradhe', 'balkumari', '2026-01-19', '2026-01-16', '00:20:00', '2026-01-12 15:32:30', 'declined', 40000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(45, '50', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'palung', 'birendrachowk', '2026-01-23', '2026-01-15', '08:10:00', '2026-01-13 02:22:20', 'pending', 18000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(46, '50', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'balkumari', 'purano thimi', '2026-01-16', '2026-01-14', '11:11:00', '2026-01-13 03:26:26', 'approved', 6000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(47, '30', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'balkumari', 'sinamangal', '2026-01-16', '2026-01-15', '09:54:00', '2026-01-13 04:09:40', 'approved', 12000, 'online', NULL, NULL, NULL, 'Pending', 'Pending'),
(48, '003', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'newthimi', 'purano thimi', '2026-01-17', '2026-01-14', '11:36:00', '2026-01-13 04:51:34', 'pending', 4000, 'online', NULL, NULL, NULL, 'Pending', 'Pending'),
(49, '003', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'newthimi', 'purano thimi', '2026-01-18', '2026-01-14', '11:36:00', '2026-01-13 04:59:21', 'pending', 5000, 'online', NULL, NULL, NULL, 'Pending', 'Pending'),
(50, '8', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'newthimi', 'sinamangal', '2026-02-12', '2026-02-08', '20:13:00', '2026-02-07 14:28:47', 'approved', 50000, 'online', 't75NTfHuGVYoygpN28dxud', '3mMdf4L2VHKjKdWnmZJFjP', '3mMdf4L2VHKjKdWnmZJFjP', 'Completed', 'Pending'),
(51, '8', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'newthimi', 'sinamangal', '2026-02-08', '2026-02-08', '20:13:00', '2026-02-07 14:53:50', 'approved', 10000, 'online', 'XXqvbY5RjLGQMsVF9KjXu3', 'dythExN57FgUFWwrGQUkf7', 'dythExN57FgUFWwrGQUkf7', 'Completed', 'Pending'),
(52, '003', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'balkumari', 'balkumari', '2026-02-09', '2026-02-10', '20:59:00', '2026-02-07 15:14:40', 'pending', 2000, 'online', '3ok6FLT5vcG2ggdZ4gYzth', NULL, NULL, 'Pending', 'Pending'),
(53, '8', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'newthimi', 'baneshwor', '2026-02-27', '2026-02-26', '13:15:00', '2026-02-26 07:30:46', 'pending', 20000, 'online', NULL, NULL, NULL, 'Pending', 'Pending'),
(54, '8', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'newthimi', 'sinamangal', '2026-04-09', '2026-04-08', '09:51:00', '2026-04-07 16:06:41', 'pending', 20000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(55, '003', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'newthimi', 'birendrachowk', '2026-04-11', '2026-04-09', '18:58:00', '2026-04-08 01:14:04', 'approved', 3000, 'online', NULL, '000ES8D', 'Booking_55_1775610848', 'Pending', 'Completed'),
(56, '222', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'sinamangal', 'baneshwor', '2026-04-11', '2026-04-09', '09:05:00', '2026-04-08 01:20:51', 'approved', 27000, 'online', NULL, '000ES8E', 'Booking_56_1775611253', 'Pending', 'Completed'),
(57, '005', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'balkumari', 'newthimi', '2026-04-10', '2026-04-09', '21:27:00', '2026-04-08 01:43:18', 'approved', 8000, 'online', NULL, '000ES8J', 'Booking_57_1775612600', 'Pending', 'Completed'),
(58, '115', 'niraj pandey', 'np694212@gmail.com', '9841975672', 'palung', 'birendrachowk', '2026-04-10', '2026-04-09', '19:33:00', '2026-04-08 01:48:33', 'approved', 11000, 'online', NULL, '000ES8M', 'Booking_58_1775612915', 'Pending', 'Completed'),
(59, '50', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'radheradhe', 'birendrachowk', '2026-04-17', '2026-04-16', '21:25:00', '2026-04-08 03:41:03', 'pending', 4000, 'online', NULL, NULL, 'Booking_59_1775619665', 'Pending', 'Pending'),
(60, '003', 'jeevan sir', 'jeevan2@gmail.com', '9999999999', 'sinamangal', 'newthimi', '2026-04-29', '2026-04-28', '21:04:00', '2026-04-27 15:20:06', 'approved', 2000, 'online', NULL, '000F1JC', 'Booking_60_1777303216', 'Pending', 'Completed'),
(61, '30', 'gagan bhusaal', 'gagan6@gmail.com', '9841665577', 'sinamangal', 'balkumari', '2026-05-02', '2026-04-30', '07:20:00', '2026-04-29 01:35:58', 'declined', 18000, 'online', NULL, NULL, 'Booking_61_1777426561', 'Pending', 'Pending'),
(62, '003', 'gagan bhusaal', 'gagan6@gmail.com', '9841665577', 'sinamangal', 'balkumari', '2026-05-01', '2026-04-30', '07:20:00', '2026-04-29 01:39:21', 'approved', 2000, 'online', NULL, '000F2T8', 'Booking_62_1777426763', 'Pending', 'Completed'),
(63, '006', 'gita', 'gita@gmail.com', '9802083296', 'newthimi', 'baneshwor', '2026-05-19', '2026-05-18', '22:04:00', '2026-05-17 04:19:21', 'approved', 180000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(64, '222', 'gita', 'gita@gmail.com', '9802083296', 'radheradhe', 'birendrachowk', '2026-05-21', '2026-05-19', '11:25:00', '2026-05-17 04:40:43', 'pending', 27000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending'),
(65, '30', 'gita', 'gita@gmail.com', '9802083296', 'balkumari', 'balkumari', '2026-05-18', '2026-05-17', '10:44:00', '2026-05-17 05:00:01', 'pending', 12000, 'cash', NULL, NULL, NULL, 'Pending', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `carid` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'Sedan',
  `image2` varchar(255) DEFAULT '',
  `image3` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `carid`, `name`, `model`, `year`, `price`, `image`, `type`, `image2`, `image3`) VALUES
(8, '8', 'CRETA', 'city', 2015, 10000.00, 'uploads/creta.jpg', 'Sedan', 'uploads/creta.jpg', 'uploads/creta.jpg'),
(9, '003', 'Nissan Gtr', 'sports', 2019, 1000.00, 'uploads/Nissan-GT-R-PNG-Photo.png', 'Sedan', 'uploads/gtr_interior.jpg', 'uploads/gtr_exterior.jpg'),
(10, '222', 'FORD RAPTOR', 'pickup', 2025, 9000.00, 'uploads/Ford-Raptor-Transparent-File.jpg', 'Sedan', 'uploads/Ford-Raptor-Transparent-File.jpg', 'uploads/Ford-Raptor-Transparent-File.jpg'),
(13, '221', 'jeep compass', 'suv', 2019, 5000.00, 'uploads/compass.jpg', 'Sedan', 'uploads/compass.jpg', 'uploads/compass.jpg'),
(14, '005', 'Ecosport', 'suv', 2019, 4000.00, 'uploads/22Ford-EcoSport-S-LighteningBlueMetallic-Jellybean (1).jpg', 'Sedan', 'uploads/22Ford-EcoSport-S-LighteningBlueMetallic-Jellybean (1).jpg', 'uploads/22Ford-EcoSport-S-LighteningBlueMetallic-Jellybean (1).jpg'),
(15, '001', 'Prado VX', 'lux suv', 2024, 50000.00, 'uploads/prado.jpg', 'Sedan', 'uploads/prado.jpg', 'uploads/prado.jpg'),
(16, '006', 'Fortuner', 'suv', 2025, 90000.00, 'uploads/fortuner.jpg', 'Sedan', 'uploads/fortuner.jpg', 'uploads/fortuner.jpg'),
(17, '115', 'Bumble Bee', 'Transform', 2001, 5500.00, 'uploads/500ea04f392876a1e159199645376173.jpg', 'Sedan', 'uploads/500ea04f392876a1e159199645376173.jpg', 'uploads/500ea04f392876a1e159199645376173.jpg'),
(18, '50', 'Tata Nexon', 'EV', 2022, 2000.00, 'uploads/test_Z5rCV5a.jpg', 'Sedan', 'uploads/nexon_interior.jpg', 'uploads/nexon_exterior.jpg'),
(19, '30', 'Tesla', 'EV', 2025, 6000.00, 'uploads/5a5218d02f93c7a8d5137f9a.jpg', 'Sedan', 'uploads/5a5218d02f93c7a8d5137f9a.jpg', 'uploads/5a5218d02f93c7a8d5137f9a.jpg'),
(20, '80', 'Vintage', 'idol', 1990, 40000.00, 'uploads/vintage1.jpg', 'Sedan', 'uploads/vintage1.jpg', 'uploads/vintage1.jpg'),
(21, '60', 'Mustang', 'sports', 2022, 15000.00, 'uploads/mustang.jpg', 'Sedan', 'uploads/mustang.jpg', 'uploads/mustang.jpg'),
(23, '666', 'musa', 'xyz', 2022, 1000.00, 'uploads/Lightning-McQueen-10000501-TB2Hero2 (1).jpeg', 'SUV', '', ''),
(25, '120', 'hilux', 'xyzz', 2025, 2000.00, 'uploads/Lightning-McQueen-10000501-TB2Hero2 (1).jpeg', 'SUV', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `admin_reply`, `status`, `created_at`) VALUES
(9, 'niraj pandey', 'np694212@gmail.com', 'hello ', 'Hi ', 'read', '2026-01-12 14:41:10'),
(10, 'gagan bhusaal', 'gagan6@gmail.com', 'hi', NULL, 'unread', '2026-04-29 01:48:56'),
(11, 'saddu', 'saddu@gmai.com', 'hi ', NULL, 'unread', '2026-04-29 01:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `preferred_car` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone_number`, `password`, `created_at`, `preferred_car`) VALUES
(27, 'niraj pandey', 'np694212@gmail.com', '9841975672', '$2y$10$5Qfb4h3W6Dhz2jYG0R7sb.Tl.Wqifkd9ZhpDYvjJOIJ/hlFbBnmUq', '2026-01-11 14:36:31', NULL),
(28, 'jeevan sir', 'jeevan2@gmail.com', '9999999999', '$2y$10$73Xt6O.dMve7b7AdXMj7C.c5KI5kr2CUtCwRabGaqOK2C0WlA951m', '2026-01-13 03:24:57', NULL),
(29, 'gagan bhusaal', 'gagan6@gmail.com', '9841665577', '$2y$10$Qm2mDVAW1TWOEiHaybGMnOU68Z5mqqdVaYUxDjiS9.CewZNJAE4b.', '2026-04-29 01:34:35', NULL),
(30, 'nirjala sapkota', 'nirjala2@gmail.com', '9712456789', '$2y$10$RnolurpS3A1vR8VIV5ai/OqJO9f/z9XXw59tZ6RIXkyj9MXsITcAi', '2026-04-29 06:32:26', NULL),
(31, 'gita', 'gita@gmail.com', '9802083296', '$2y$10$9FzUCsEQNU2ObRfZ5oGpD./8JxBEAzJp3MlB0gm.7rXtzcARNUFFi', '2026-05-17 04:17:46', 'EV');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`);

--
-- Indexes for table `booked_cars`
--
ALTER TABLE `booked_cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carid` (`carid`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booked_cars`
--
ALTER TABLE `booked_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
