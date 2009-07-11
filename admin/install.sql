-- phpMyAdmin SQL Dump
-- version 3.1.2deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 11, 2009 at 10:00 PM
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

CREATE TABLE IF NOT EXISTS `jos_beursplein_cards` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `type` int(9) NOT NULL,
  `group` int(9) NOT NULL,
  `stock_id` int(9) unsigned NOT NULL,
  `user_id` int(9) unsigned default NULL,
  `status` enum('deck','played') NOT NULL default 'deck',
  `images` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=341 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_history`
--

CREATE TABLE IF NOT EXISTS `jos_beursplein_history` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `stock_id` int(9) unsigned NOT NULL,
  `value` int(9) unsigned NOT NULL,
  `volume` int(9) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_log`
--

CREATE TABLE IF NOT EXISTS `jos_beursplein_log` (
  `id` int(32) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned default NULL,
  `date` datetime NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_portfolio`
--

CREATE TABLE IF NOT EXISTS `jos_beursplein_portfolio` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `owner` int(9) unsigned NOT NULL default '0',
  `stock_id` int(9) unsigned NOT NULL default '0',
  `amount` bigint(20) unsigned NOT NULL default '0',
  `can_sell` int(9) NOT NULL default '2',
  PRIMARY KEY  (`id`),
  KEY `owner` (`owner`),
  KEY `stock_id` (`stock_id`),
  KEY `can_sell` (`can_sell`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_stocks`
--

CREATE TABLE IF NOT EXISTS `jos_beursplein_stocks` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `image` varchar(64) NOT NULL default 'default.jpg',
  `value` int(9) unsigned NOT NULL default '0',
  `change` int(9) NOT NULL default '0',
  `speed` int(9) NOT NULL default '0',
  `growing` enum('true','false') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `jos_beursplein_users`
--

CREATE TABLE IF NOT EXISTS `jos_beursplein_users` (
  `id` int(11) NOT NULL,
  `money` bigint(20) NOT NULL,
  `card_id` int(9) unsigned default NULL,
  `stock_id` int(9) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- END OF FILE
--

