-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 14. Mai 2012 um 14:02
-- Server Version: 5.5.9
-- PHP-Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `roster`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth`
--

CREATE TABLE `auth` (
  `sid` varchar(40) NOT NULL DEFAULT '',
  `session_type` char(1) NOT NULL DEFAULT '',
  `user_ip` varchar(15) DEFAULT NULL,
  `user_hostname` varchar(255) DEFAULT NULL,
  `lastaction` int(255) DEFAULT NULL,
  `revisit` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `clearpwd` varchar(255) DEFAULT NULL,
  `session` mediumtext NOT NULL,
  UNIQUE KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `auth`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `barcode`
--

CREATE TABLE `barcode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `barcode`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE `category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` VALUES(1, 'Macbook');
INSERT INTO `category` VALUES(2, 'Auto');
INSERT INTO `category` VALUES(3, 'Fenster');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categoryitem`
--

CREATE TABLE `categoryitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- Daten für Tabelle `categoryitem`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customcontent`
--

CREATE TABLE `customcontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `customcontent`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customfield`
--

CREATE TABLE `customfield` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `fieldtype` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `values` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `customfield`
--

INSERT INTO `customfield` VALUES(1, 'item', 'text', 'waek', 'Ein neues Feld', '');
INSERT INTO `customfield` VALUES(2, 'item', 'text', 'bla', 'Höhö', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Daten für Tabelle `image`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item`
--

CREATE TABLE `item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `barcode` int(10) NOT NULL COMMENT 'barcode id',
  `category` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `buydate` int(10) NOT NULL,
  `buycondition` int(10) NOT NULL,
  `buyplace` int(10) NOT NULL,
  `buyprice` float NOT NULL,
  `image` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `delete` int(1) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Daten für Tabelle `item`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `package`
--

CREATE TABLE `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `person` varchar(255) NOT NULL,
  `startdate` int(11) NOT NULL,
  `duedate` int(11) NOT NULL,
  `returndate` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `delete` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Daten für Tabelle `package`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `packageitem`
--

CREATE TABLE `packageitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `back` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `packageitem`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'item',
  `grade` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `status`
--

INSERT INTO `status` VALUES(1, 'im Lager', 'item', 'active');
INSERT INTO `status` VALUES(5, 'Ausgelehnt', 'item', 'passive');
INSERT INTO `status` VALUES(6, 'Ausgelehnt', 'package', 'active');
INSERT INTO `status` VALUES(8, 'Zurück', 'package', 'passive');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `prename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('false','true') NOT NULL DEFAULT 'false',
  `lastlogin` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `users`
--

