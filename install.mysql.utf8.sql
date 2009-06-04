-- phpMyAdmin SQL Dump
-- version 2.6.4-pl4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generatie Tijd: 10 Mei 2009 om 17:49
-- Server versie: 4.1.22
-- PHP Versie: 5.2.9
-- 
-- Database: `raymannl`
-- 

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `jos_beursplein_portfolio`
-- 

CREATE TABLE `jos_beursplein_portfolio` (
  `id` int(9) NOT NULL auto_increment,
  `owner` int(9) NOT NULL default '0',
  `stock_id` int(9) NOT NULL default '0',
  `amount` bigint(20) NOT NULL default '0',
  `can_sell` int(9) NOT NULL default '2',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=6 AUTO_INCREMENT=6 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `jos_beursplein_portfolio`
-- 

INSERT INTO `jos_beursplein_portfolio` VALUES (2, 0, 19, 123, 2);
INSERT INTO `jos_beursplein_portfolio` VALUES (3, 0, 21, 12, 2);
INSERT INTO `jos_beursplein_portfolio` VALUES (4, 62, 22, 1, 2);
INSERT INTO `jos_beursplein_portfolio` VALUES (5, 62, 25, 300, 2);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `jos_beursplein_stocks`
-- 

CREATE TABLE `jos_beursplein_stocks` (
  `id` bigint(64) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `image` varchar(64) NOT NULL default 'default.jpg',
  `value` int(11) NOT NULL default '0',
  `change` int(9) NOT NULL default '0',
  `speed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=199 AUTO_INCREMENT=199 ;

-- 
-- Gegevens worden uitgevoerd voor tabel `jos_beursplein_stocks`
-- 

INSERT INTO `jos_beursplein_stocks` VALUES (16, 'Dommelsch nv', 'stocks/dommel.jpg', 356, 12, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (17, 'Pamir Shoarma', 'stocks/pamir.jpg', 23, -56, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (18, 'Bob Construction bv', 'stocks/bob.jpg', 100, 46, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (19, 'Scouting NL', 'stocks/scouting.gif', 100, 164, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (20, 'Van Dijk metaalgroep', 'stocks/vandijk.jpg', 17, -6, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (21, 'Pong computer games', 'stocks/PongNV.jpg', 157, -67, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (22, 'Ben Hur yachting team', 'stocks/benhur.gif', 75, 98, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (23, 'Island happy holidays', 'stocks/island.jpg', 278, 12, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (24, 'J.Wilbers Bergsport', 'stocks/bergsport.jpg', 123, -56, 0);
INSERT INTO `jos_beursplein_stocks` VALUES (25, 'van Delluf Audio Solutions', 'stocks/delluf.jpg', 12, 56, 0);

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `jos_beursplein_users`
-- 

CREATE TABLE `jos_beursplein_users` (
  `id` int(11) NOT NULL default '0',
  `money` bigint(20) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;

-- 
-- Gegevens worden uitgevoerd voor tabel `jos_beursplein_users`
-- 

INSERT INTO `jos_beursplein_users` VALUES (12, 12);
INSERT INTO `jos_beursplein_users` VALUES (62, 500);
