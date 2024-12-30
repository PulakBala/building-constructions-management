-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 02:28 PM
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
-- Database: `sobarmarth`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_value` decimal(10,2) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `registration_cost` decimal(10,2) NOT NULL,
  `other_expenses` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `asset_name`, `asset_value`, `contact`, `registration_cost`, `other_expenses`, `date`, `created_at`, `updated_at`) VALUES
(2, 'dsf', 34.00, '324', 23174.00, 3434.00, '2024-12-05', '2024-12-07 12:58:42', '2024-12-07 12:58:42'),
(3, 'w', 55.00, '632', 33.00, 33.00, '2024-12-08', '2024-12-08 04:47:05', '2024-12-08 05:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `building_info`
--

CREATE TABLE `building_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `manager_name` varchar(255) NOT NULL,
  `manager_number` varchar(20) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `guard_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `building_info`
--

INSERT INTO `building_info` (`id`, `name`, `address`, `manager_name`, `manager_number`, `guard_name`, `guard_number`, `created_at`) VALUES
(1, 'Addal twoer', 'Dhanmondi 27', 'Rahim', '0172349343', 'Sakil', '0138475345', '2024-12-30 11:16:06'),
(2, 'Sobarmart Tower', 'Rampura', 'Isral', '0172349343', 'Emon', '0138475345', '2024-12-30 11:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `construction_cost`
--

CREATE TABLE `construction_cost` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `construction_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `construction_cost`
--

INSERT INTO `construction_cost` (`id`, `project_name`, `construction_name`, `amount`, `date`, `payment_type`, `note`, `created_at`) VALUES
(6, 'df', 'dsf', 4444.00, '2024-12-10', 'Bank Transfer', 'Sakil', '2024-12-10 07:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `amount`, `date`, `category`, `description`, `created_at`) VALUES
(61, 6000.00, '2024-12-10', '', 'fdf', '2024-12-10 07:02:59'),
(62, 34.00, '2024-12-10', '', 'fdfdf', '2024-12-10 07:04:16'),
(63, 3.00, '2024-12-10', '', 'Check Developer', '2024-12-10 07:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `ex_id` int(11) NOT NULL,
  `ex_name` text DEFAULT NULL,
  `ex_amount` text DEFAULT NULL,
  `ex_type` text DEFAULT NULL,
  `ex_save` int(11) DEFAULT 0,
  `ex_date` text DEFAULT NULL,
  `ex_update_date` varchar(50) DEFAULT NULL,
  `ex_day` text DEFAULT NULL,
  `ex_month` text DEFAULT NULL,
  `ex_year` text DEFAULT NULL,
  `ex_description` text DEFAULT NULL,
  `ex_for` text DEFAULT NULL,
  `ex_pixel` int(11) DEFAULT NULL,
  `ex_leader` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`ex_id`, `ex_name`, `ex_amount`, `ex_type`, `ex_save`, `ex_date`, `ex_update_date`, `ex_day`, `ex_month`, `ex_year`, `ex_description`, `ex_for`, `ex_pixel`, `ex_leader`) VALUES
(5, 'Abdullaha', '2000', 'Salary', 0, '2024-12-18', '2024-12-18', '18', '12', '2024', '', '', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `flats`
--

CREATE TABLE `flats` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `optional_number` varchar(15) DEFAULT NULL,
  `flatname` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flats`
--

INSERT INTO `flats` (`id`, `owner_name`, `mobile_number`, `optional_number`, `flatname`, `created_at`) VALUES
(200, 'Pulak Bala', '01754826927', '01754826927', 'Bakul phul', '2024-12-26 12:58:36'),
(201, 'Rahim s', '01754826927', '0145263574', 'Pushpa Buillding', '2024-12-26 13:02:25');

-- --------------------------------------------------------

--
-- Table structure for table `flat_bill`
--

CREATE TABLE `flat_bill` (
  `f_id` int(11) NOT NULL,
  `f_date` varchar(10) DEFAULT NULL,
  `f_month` varchar(15) DEFAULT NULL,
  `f_year` varchar(4) DEFAULT NULL,
  `f_service_charge` varchar(20) DEFAULT NULL,
  `f_int_bill` varchar(20) DEFAULT NULL,
  `f_dish_bill` varchar(20) DEFAULT NULL,
  `f_flat_rent` varchar(20) DEFAULT NULL,
  `f_c_current_bill` varchar(20) DEFAULT NULL,
  `f_c_center_rent` varchar(255) DEFAULT NULL,
  `f_details` varchar(255) DEFAULT NULL,
  `f_guard_slry` varchar(20) DEFAULT NULL,
  `f_empty_flat` varchar(20) DEFAULT NULL,
  `f_c_center_various` varchar(20) DEFAULT NULL,
  `f_atic_rent` varchar(20) DEFAULT NULL,
  `f_d_donation` varchar(20) DEFAULT NULL,
  `f_d_various_charge` varchar(20) DEFAULT NULL,
  `f_total` varchar(50) DEFAULT NULL,
  `f_paid_amount` decimal(10,2) DEFAULT 0.00,
  `f_due` decimal(10,2) DEFAULT 0.00,
  `f_flatId` int(11) NOT NULL,
  `f_status` varchar(10) DEFAULT NULL,
  `f_invoice_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flat_bill`
--

INSERT INTO `flat_bill` (`f_id`, `f_date`, `f_month`, `f_year`, `f_service_charge`, `f_int_bill`, `f_dish_bill`, `f_flat_rent`, `f_c_current_bill`, `f_c_center_rent`, `f_details`, `f_guard_slry`, `f_empty_flat`, `f_c_center_various`, `f_atic_rent`, `f_d_donation`, `f_d_various_charge`, `f_total`, `f_paid_amount`, `f_due`, `f_flatId`, `f_status`, `f_invoice_number`) VALUES
(429, '2024-12-26', 'December', '2024', '0', '0', '0', '2333', '222', '0', 'aaaa', '222', '222', '222', '0', '0', '0', '3221', 3000.00, 221.00, 201, 'Received', 1),
(430, '2024-12-29', 'December', '2024', '0', '0', '0', '234324', '0', '0', '', '0', '0', '0', '0', '0', '0', '234324', 0.00, 0.00, 201, 'Pending', 2);

-- --------------------------------------------------------

--
-- Table structure for table `flat_info`
--

CREATE TABLE `flat_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `nid_number` varchar(50) NOT NULL,
  `nid_img` varchar(255) DEFAULT NULL,
  `rent` decimal(10,2) NOT NULL,
  `advance` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flat_info`
--

INSERT INTO `flat_info` (`id`, `name`, `mobile_number`, `nid_number`, `nid_img`, `rent`, `advance`, `date`, `created_at`, `updated_at`) VALUES
(6, 'Masum', '01754826927', '23443243', 'https://wh3school.com', 2222.00, 222.00, '0000-00-00', '2024-12-26 10:14:04', '2024-12-26 10:14:04'),
(9, 'dfdff', '01754826927', '0124242424', 'https://wh3school.com', 343434.00, 234324.00, '0000-00-00', '2024-12-28 07:22:42', '2024-12-28 07:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workers_details`
--

CREATE TABLE `workers_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `worker_title` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers_details`
--

INSERT INTO `workers_details` (`id`, `name`, `address`, `contact`, `worker_title`, `date`, `created_at`) VALUES
(4, 'f', 'f', '3', 'f', '2024-12-10', '2024-12-10 07:41:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `building_info`
--
ALTER TABLE `building_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `construction_cost`
--
ALTER TABLE `construction_cost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`ex_id`);

--
-- Indexes for table `flats`
--
ALTER TABLE `flats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flat_bill`
--
ALTER TABLE `flat_bill`
  ADD PRIMARY KEY (`f_id`),
  ADD UNIQUE KEY `f_invoice_number` (`f_invoice_number`);

--
-- Indexes for table `flat_info`
--
ALTER TABLE `flat_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workers_details`
--
ALTER TABLE `workers_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `building_info`
--
ALTER TABLE `building_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `construction_cost`
--
ALTER TABLE `construction_cost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `ex_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `flats`
--
ALTER TABLE `flats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `flat_bill`
--
ALTER TABLE `flat_bill`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;

--
-- AUTO_INCREMENT for table `flat_info`
--
ALTER TABLE `flat_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `workers_details`
--
ALTER TABLE `workers_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
