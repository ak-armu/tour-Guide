-- phpMyAdmin SQL Dump
-- version 5.1.1
-- //www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 11, 2022 at 03:11 PM
-- Server version: 5.7.36
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tourist_guide`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `user_id` int(9) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@touristguide.com', '$2y$10$eC5qedsn/YuHKxKYr.e4Jufp5Vs8q0febGy.w.wDgbPtkBxyFcHHG');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(9) NOT NULL,
  `feedback` text NOT NULL,
  `submitted_at` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

DROP TABLE IF EXISTS `places`;
CREATE TABLE IF NOT EXISTS `places` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `address` text NOT NULL,
  `area` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cost_by_air` int(10) NOT NULL,
  `cost_by_road` int(10) NOT NULL,
  `cost_by_train` int(10) NOT NULL,
  `cost_by_ocean` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tour_plans`
--

DROP TABLE IF EXISTS `tour_plans`;
CREATE TABLE IF NOT EXISTS `tour_plans` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user_id` int(9) NOT NULL,
  `total_days` int(9) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(9) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `area` varchar(255) NOT NULL,
  `gender` varchar(8) NOT NULL,
  `age` varchar(50) NOT NULL,
  `likes_beach` tinyint(1) NOT NULL,
  `likes_hill` tinyint(1) NOT NULL,
  `likes_forest` tinyint(1) NOT NULL,
  `likes_waterfalls` tinyint(1) NOT NULL,
  `likes_landscape_view` tinyint(1) NOT NULL,
  `likes_temple` tinyint(1) NOT NULL,
  `registered_at` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
