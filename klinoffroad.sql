-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2024 at 08:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE
= "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone
= "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klinoffroad`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts`
(
  `CartID` int
(11) NOT NULL,
  `UserID` int
(11) NOT NULL,
  `Items` longtext CHARACTER
SET utf8mb4
COLLATE utf8mb4_bin DEFAULT NULL CHECK
(json_valid
(`Items`)),
  `LastUpdated` timestamp NOT NULL DEFAULT current_timestamp
() ON
UPDATE current_timestamp()
) ENGINE
=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products`
(
  `ProductID` int
(11) NOT NULL,
  `ProductName` varchar
(100) NOT NULL,
  `Price` decimal
(10,2) NOT NULL,
  `Stock` int
(11) DEFAULT 0,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users`
(
  `UserID` int
(11) NOT NULL,
  `Username` varchar
(50) NOT NULL,
  `Email` varchar
(100) NOT NULL,
  `PasswordHash` varchar
(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
ADD PRIMARY KEY
(`CartID`),
ADD UNIQUE KEY `UserID`
(`UserID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
ADD PRIMARY KEY
(`ProductID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY
(`UserID`),
ADD UNIQUE KEY `Username`
(`Username`),
ADD UNIQUE KEY `Email`
(`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `CartID` int
(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int
(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int
(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY
(`UserID`) REFERENCES `users`
(`UserID`) ON
DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
