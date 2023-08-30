-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 06, 2023 at 02:00 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `idques` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id` int(6) NOT NULL,
  `idquiz` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  `choice_1` varchar(255) NOT NULL,
  `choice_2` varchar(255) NOT NULL,
  `choice_3` varchar(255) NOT NULL,
  `choice_4` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  PRIMARY KEY (`idques`,`id`,`idquiz`),
  KEY `idquiz` (`idquiz`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`idques`, `id`, `idquiz`, `question`, `choice_1`, `choice_2`, `choice_3`, `choice_4`, `answer`) VALUES
(32, 7, 'a7', '4+4', '0', '4', '8', '16', 'answer3'),
(24, 19, 'b7', '2+2', '0', '2', '8', '4', 'answer4'),
(25, 19, 'b7', '8+8', '16', '0', '8', '88', 'answer1'),
(39, 7, 'c7', '2+2', '0', '2', '1', '4', 'answer4'),
(28, 7, '86', '2 * 1', '21', '1', '2', '3', 'answer3'),
(40, 7, '86', '4+4', '0', '2', '8', '16', 'answer3');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `idquiz` varchar(255) NOT NULL,
  `id` int(6) NOT NULL,
  `title` varchar(20) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idquiz`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`idquiz`, `id`, `title`, `total`) VALUES
('b7', 19, 'Math', 3),
('86', 7, 'Math', 4);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `idpart` int(6) NOT NULL AUTO_INCREMENT,
  `id` int(6) NOT NULL,
  `idquiz` varchar(255) NOT NULL,
  `name` varchar(45) NOT NULL,
  `result` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpart`,`id`,`idquiz`),
  KEY `idquiz` (`idquiz`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`idpart`, `id`, `idquiz`, `name`, `result`) VALUES
(1, 7, '86', 'Dana', 100);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) COLLATE utf16_bin NOT NULL,
  `lastname` varchar(45) COLLATE utf16_bin NOT NULL,
  `email` varchar(100) COLLATE utf16_bin NOT NULL,
  `username` varchar(45) COLLATE utf16_bin NOT NULL,
  `password` varchar(255) COLLATE utf16_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`) VALUES
(7, 'Tala', 'Hamdan', 'tala.hamdan@gmail.com', 'Tala19', 'tala123'),
(8, 'Manar', 'Shreif', 'manarshreif@gmail.com', 'Manoura', 'manar1122'),
(19, 'Ali', 'Al Moussawi', 'moussawiali2002@gmail.com', 'Abo 3lesh', 'ali2002'),
(14, 'Hadi', 'Tawil', 'Haditawil@gmail.com', 'HadiTawil', 'hadi123'),
(15, 'Ola', 'Shreif', 'olashreif@gmail.com', 'OlaShreif', 'ola123'),
(17, 'Ali', 'Hamdan', 'alihamdan@gmail.com', 'Ali2008', 'ali');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
