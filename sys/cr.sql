-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2018 at 10:57 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cr`
--

-- --------------------------------------------------------

--
-- Table structure for table `cr_user`
--

CREATE TABLE `cr_user` (
  `userId` int(11) NOT NULL,
  `userName` varchar(50) DEFAULT NULL,
  `userPassword` varchar(250) DEFAULT NULL,
  `userPin` varchar(250) NOT NULL,
  `userFullname` varchar(200) DEFAULT NULL,
  `userGroupCode` varchar(20) NOT NULL,
  `userDeptCode` varchar(10) NOT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `userTel` varchar(100) DEFAULT NULL,
  `userPicture` varchar(250) DEFAULT NULL,
  `statusCode` char(1) DEFAULT NULL,
  `loginStatus` int(11) NOT NULL DEFAULT '0',
  `lastLoginTime` datetime NOT NULL,
  `SID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cr_user`
--

INSERT INTO `cr_user` (`userId`, `userName`, `userPassword`, `userPin`, `userFullname`, `userGroupCode`, `userDeptCode`, `userEmail`, `userTel`, `userPicture`, `statusCode`, `loginStatus`, `lastLoginTime`, `SID`) VALUES
(1, 'admin', 'f9c6c2e7d2dd5c8773d97faaa399692d', '43366a8c6902767fc2157b7def389f5e60f8b7ba1c36e546ce2c3beabca85ae9', 'Administrator', 'admin', '', 'admin@gmail.com', 'admin', 'user_admin.jpg', 'A', 1, '2018-08-01 15:56:45', '');

-- --------------------------------------------------------

--
-- Table structure for table `cr_user_group`
--

CREATE TABLE `cr_user_group` (
  `Id` int(11) NOT NULL,
  `Code` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `StatusId` int(11) NOT NULL,
  `CreateTime` datetime NOT NULL,
  `CreateUserId` int(11) NOT NULL,
  `UpdateTime` datetime NOT NULL,
  `UpdateUserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cr_user_group`
--

INSERT INTO `cr_user_group` (`Id`, `Code`, `Name`, `StatusId`, `CreateTime`, `CreateUserId`, `UpdateTime`, `UpdateUserId`) VALUES
(1, 'admin', 'admin', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(2, 'guest', 'Guest', 2, '2018-08-01 15:24:03', 1, '2018-08-01 15:54:08', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cr_user`
--
ALTER TABLE `cr_user`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `cr_user_group`
--
ALTER TABLE `cr_user_group`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cr_user`
--
ALTER TABLE `cr_user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `cr_user_group`
--
ALTER TABLE `cr_user_group`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
