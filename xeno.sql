-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2015 at 02:53 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xeno`
--

-- --------------------------------------------------------

--
-- Table structure for table `eventlog`
--

DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE IF NOT EXISTS `eventlog` (
  `lid` int(11) NOT NULL COMMENT 'Primary Key: Unique event log ID.',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'The users.uid of the user who triggered the event.',
  `type` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Type of log message, for example "user" or "page not found."',
  `severity` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The severity level of the event; ranges from 0 (Emergency) to 7 (Debug)',
  `referer` text COLLATE utf8_bin COMMENT 'URL of referring page.',
  `message` longtext COLLATE utf8_bin NOT NULL COMMENT 'Text of log message to be passed into the t() function.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(128) COLLATE utf8_bin NOT NULL,
  `value` varchar(256) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('secret', 'coelacantsaretastyfishies');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventlog`
--
ALTER TABLE `eventlog`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `unique` (`name`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eventlog`
--
ALTER TABLE `eventlog`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique event log ID.';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
