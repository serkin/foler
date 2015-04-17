-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 16, 2015 at 02:48 PM
-- Server version: 5.5.9-log
-- PHP Version: 5.6.0RC4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `foler`
--

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
`id_language` tinyint(4) NOT NULL,
  `code` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
`id_message` int(11) NOT NULL,
  `id_project` tinyint(4) NOT NULL,
  `code` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
`id_project` tinyint(4) NOT NULL,
  `name` varchar(256) NOT NULL,
  `path` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `translation`
--

CREATE TABLE IF NOT EXISTS `translation` (
  `id_language` tinyint(4) NOT NULL,
  `id_message` int(11) NOT NULL,
  `translation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `language`
--
ALTER TABLE `language`
 ADD PRIMARY KEY (`id_language`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
 ADD PRIMARY KEY (`id_message`), ADD KEY `id_project` (`id_project`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
 ADD PRIMARY KEY (`id_project`);

--
-- Indexes for table `translation`
--
ALTER TABLE `translation`
 ADD KEY `id_message` (`id_message`), ADD KEY `id_language` (`id_language`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
MODIFY `id_language` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
MODIFY `id_project` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE CASCADE;

--
-- Constraints for table `translation`
--
ALTER TABLE `translation`
ADD CONSTRAINT `translation_ibfk_2` FOREIGN KEY (`id_message`) REFERENCES `message` (`id_message`) ON DELETE CASCADE,
ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON DELETE CASCADE;
