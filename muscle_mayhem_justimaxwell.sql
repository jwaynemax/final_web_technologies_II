-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2023 at 02:46 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muscle_mayhem_justimaxwell`
--
CREATE DATABASE IF NOT EXISTS `muscle_mayhem_justimaxwell` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `muscle_mayhem_justimaxwell`;


-- drop user 'popeye'@'localhost';
-- flush privileges;
CREATE USER 'popeye'@'localhost' IDENTIFIED BY 'spinach';
GRANT ALL PRIVILEGES ON `muscle_mayhem_justimaxwell`.* TO 'popeye'@'localhost';

-- --------------------------------------------------------

--
-- Table structure for table `Classes`
--

DROP TABLE IF EXISTS `Classes`;
CREATE TABLE `Classes` (
  `Class_id` int(11) NOT NULL,
  `Class_Name` varchar(255) NOT NULL,
  `Day_of_Week` varchar(255) NOT NULL,
  `Class_Time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Classes`
--

INSERT INTO `Classes` (`Class_id`, `Class_Name`, `Day_of_Week`, `Class_Time`) VALUES
(1, 'Bugs Bunny Bicep Blaster', 'Monday', '2023-05-08 12:00:00'),
(2, 'Lola Bunny\'s Pilates Class', 'Tuesday', '2023-05-30 3:30:00'),
(3, 'Tasmanian Devil\'s CrossFit Class', 'Thursday', '2023-05-18 5:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

DROP TABLE IF EXISTS `Customers`;
CREATE TABLE `Customers` (
  `Customer_id` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL UNIQUE,
  `Password` varchar(225) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `City` varchar(50) NOT NULL,
  `State` varchar(50) NOT NULL,
  `Postal` varchar(20) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`Customer_id`, `Username`, `Password`, `First_Name`, `Last_Name`, `Address`, `City`, `State`, `Postal`, `Phone`, `Email`) VALUES
(1, 'jdoe', 'password123', 'John', 'Doe', '123 Main St.', 'Atlanta', 'GA', '30000', '7701234567', 'jdoe@sample.com'),
(2, 'harrypotter', 'sourcerstone123', 'Harry', 'Potter', '456 Hogwarts', 'Roswell', 'GA', '31100', NULL, 'harrypotter@gmail.com'),
(3, 'darthvador', 'redlightsaber77', 'Darth', 'Vader', '44 Star Ship', 'Hollywood', 'CA', '90000', '1234567890', 'dvader@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `Registered_Classes`
--

DROP TABLE IF EXISTS `Registered_Classes`;
CREATE TABLE `Registered_Classes` (
  `Registered_Class_id` int(11) NOT NULL,
  `Customer_id` int(11) NOT NULL,
  `Class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Registered_Classes`
--

INSERT INTO `Registered_Classes` (`Registered_Class_id`, `Customer_id`, `Class_id`) VALUES
(1, 3, 2),
(2, 2, 3),
(3, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Classes`
--
ALTER TABLE `Classes`
  ADD PRIMARY KEY (`Class_id`);

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`Customer_id`);

--
-- Indexes for table `Registered_Classes`
--
ALTER TABLE `Registered_Classes`
  ADD PRIMARY KEY (`Registered_Class_id`),
  ADD KEY `customerId` (`Customer_id`),
  ADD KEY `classId` (`Class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Classes`
--
ALTER TABLE `Classes`
  MODIFY `Class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `Customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Registered_Classes`
--
ALTER TABLE `Registered_Classes`
  MODIFY `Registered_Class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Registered_Classes`
--
ALTER TABLE `Registered_Classes`
  ADD CONSTRAINT `classId` FOREIGN KEY (`Class_id`) REFERENCES `Classes` (`Class_id`),
  ADD CONSTRAINT `customerId` FOREIGN KEY (`Customer_id`) REFERENCES `Customers` (`Customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;