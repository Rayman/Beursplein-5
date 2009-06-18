-- phpMyAdmin SQL Dump
-- version 3.1.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 17, 2009 at 02:54 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_cards`
--

DROP TABLE IF EXISTS `jos_beursplein_cards`;
CREATE TABLE IF NOT EXISTS `jos_beursplein_cards` (
  `id` int(9) NOT NULL auto_increment,
  `type` int(9) NOT NULL,
  `group` int(9) NOT NULL,
  `stock_id` int(9) NOT NULL,
  `user_id` int(9) default NULL,
  `images` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=341 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_history`
--

DROP TABLE IF EXISTS `jos_beursplein_history`;
CREATE TABLE IF NOT EXISTS `jos_beursplein_history` (
  `id` int(9) NOT NULL auto_increment,
  `stock_id` int(9) NOT NULL,
  `value` int(9) NOT NULL,
  `volume` int(9) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_portfolio`
--

DROP TABLE IF EXISTS `jos_beursplein_portfolio`;
CREATE TABLE IF NOT EXISTS `jos_beursplein_portfolio` (
  `id` int(9) NOT NULL auto_increment,
  `owner` int(9) NOT NULL default '0',
  `stock_id` int(9) NOT NULL default '0',
  `amount` bigint(20) NOT NULL default '0',
  `can_sell` int(9) NOT NULL default '2',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_stocks`
--

DROP TABLE IF EXISTS `jos_beursplein_stocks`;
CREATE TABLE IF NOT EXISTS `jos_beursplein_stocks` (
  `id` bigint(64) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `image` varchar(64) NOT NULL default 'default.jpg',
  `value` int(11) NOT NULL default '0',
  `change` int(9) NOT NULL default '0',
  `speed` int(11) NOT NULL default '0',
  `growing` enum('true','false') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=199 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_users`
--

DROP TABLE IF EXISTS `jos_beursplein_users`;
CREATE TABLE IF NOT EXISTS `jos_beursplein_users` (
  `id` int(11) NOT NULL default '0',
  `money` bigint(20) NOT NULL default '0',
  `card_id` int(9) default NULL,
  `stock_id` int(9) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
