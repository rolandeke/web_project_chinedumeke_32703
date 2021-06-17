-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2021 at 03:30 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `usersdb_32703`
--

-- --------------------------------------------------------

--
-- Table structure for table `userregister`
--

CREATE TABLE `userregister` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `UserPassword` varchar(200) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `RegistrationDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userregister`
--

INSERT INTO `userregister` (`UserID`, `UserName`, `UserPassword`, `FullName`, `Email`, `RegistrationDate`) VALUES
(5, 'Chinedumeke', '$2y$10$WzUWInxQFuuHk8S/r2hQXOZh4x4oX1Pl4xfUkspd2mWl8iv6aDLcq', 'Chinedum Roland Eke', 'rolandeke49@gmail.com', '2021-06-04 13:28:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `userregister`
--
ALTER TABLE `userregister`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `userregister`
--
ALTER TABLE `userregister`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
