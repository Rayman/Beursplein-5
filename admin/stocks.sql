-- phpMyAdmin SQL Dump
-- version 3.1.2deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 11, 2009 at 10:54 PM
-- Server version: 5.0.75
-- PHP Version: 5.2.6-3ubuntu4.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `joomla`
--

--
-- Dumping data for table `jos_beursplein_stocks`
--

INSERT INTO `jos_beursplein_stocks` (`id`, `name`, `image`, `value`, `change`, `speed`, `growing`) VALUES
(26, 'Dommelsch nv ', 'media/com_beursplein/stocks/dommel.jpg', 264, -1, 4, 'false'),
(27, 'Pamir Shoarma ', 'media/com_beursplein/stocks/pamir.jpg', 206, 103, -10, 'true'),
(28, 'Scouting NL ', 'media/com_beursplein/stocks/scouting.gif', 142, -4, 1, 'false'),
(29, 'Ben Hur yachting team ', 'media/com_beursplein/stocks/benhur.gif', 63, -63, 4, 'false'),
(30, 'Fortis Bank', 'media/com_beursplein/stocks/fortis.png', 110, -2, -7, 'true'),
(31, 'Bob Construction bv ', 'com_beursplein/stocks/bob.jpg', 113, -2, 1, 'false');

