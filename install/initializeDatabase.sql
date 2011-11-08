-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 28, 2011 at 05:21 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS monitor;
--
-- Database: `monitor`
--
DROP TABLE IF EXISTS `monitorAttachedServer`;
DROP TABLE IF EXISTS `monitorConfig`;
DROP TABLE IF EXISTS `monitorDeviceAndOid` ;
DROP TABLE IF EXISTS `monitorDeviceList` ;
DROP TABLE IF EXISTS `monitorErrorLog` ;
DROP TABLE IF EXISTS `monitorHourLog` ;
DROP TABLE IF EXISTS `monitorOidNameList` ;
DROP TABLE IF EXISTS `monitorSample` ;
DROP TABLE IF EXISTS `monitorThreshold` ;
DROP TABLE IF EXISTS `monitorUserList` ;

-- --------------------------------------------------------

--
-- Table structure for table `monitorAttachedServer`
--

CREATE TABLE IF NOT EXISTS `monitorAttachedServer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serverIp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serverName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secretMessage` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorAttachedServer`
--

INSERT INTO `monitorAttachedServer` (`id`, `ip`, `serverIp`, `serverName`, `port`, `secretMessage`) VALUES
(9, '192.168.184.10', '194.47.147.46', 'sia', '9999', 'shutdown'),
(4, '192.168.184.11', '127.0.0.4', 'server4', '6666', 'testSecretMessage'),
(5, '192.168.184.11', '127.0.0.5', 'server5', '5555', 'testSecretMessage');

-- --------------------------------------------------------

--
-- Table structure for table `monitorConfig`
--

CREATE TABLE IF NOT EXISTS `monitorConfig` (
  `probeInterval` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `perlPid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dataLastHour` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enableRoot` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `errorLimit` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dataLastSample` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numberOfSample` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numberOfHour` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numberOfError` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stop` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `monitorConfig`
--

INSERT INTO `monitorConfig` (`probeInterval`, `perlPid`, `dataLastHour`, `enableRoot`, `errorLimit`, `dataLastSample`, `numberOfSample`, `numberOfHour`, `numberOfError`, `stop`) VALUES
('5', NULL, '3600', 'Y', '1000', '3600', '0', '0', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `monitorDeviceAndOid`
--

CREATE TABLE IF NOT EXISTS `monitorDeviceAndOid` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `needStatisticAndThreshold` enum('N','Y') COLLATE utf8_unicode_ci DEFAULT NULL,
  `errorStatus` enum('N','Y') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorDeviceAndOid`
--

INSERT INTO `monitorDeviceAndOid` (`id`, `ip`, `oid`, `name`, `needStatisticAndThreshold`, `errorStatus`) VALUES
(1, '192.168.184.10', '1.3.6.1.2.1.1.3.0', 'SystemUpTime_10ms', 'N', 'N'),
(2, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.2.1.1.0', 'BatteryStatus', 'N', 'N'),
(3, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.4.2.1.0', 'OutputVoltage', 'Y', 'N'),
(4, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.2.2.3.0', 'BatteryRemaining_10ms', 'Y', 'N'),
(5, '192.168.184.11', '1.3.6.1.2.1.1.5.0', 'SystemName', 'N', 'N'),
(6, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.2.2.3.0', 'BatteryRemaining_10ms', 'Y', 'N'),
(7, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.5.1.2.1.2.1', 'AttactedDevice_1', 'N', 'N'),
(8, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.5.1.2.1.2.2', 'AttactedDevice_2', 'N', 'N'),
(9, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.5.1.2.1.2.3', 'AttactedDevice_3', 'N', 'N'),
(86, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.5.1.2.1.2.2', 'AttactedDevice_2', 'N', 'N'),
(87, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.5.1.2.1.2.3', 'AttactedDevice_3', 'N', 'N'),
(88, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.2.1.1.0', 'BatteryStatus', 'N', 'N'),
(81, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.4.2.1.0', 'OutputVoltage', 'Y', 'N'),
(82, '192.168.184.11', '1.3.6.1.2.1.1.3.0', 'SystemUpTime_10ms', 'N', 'N'),
(83, '192.168.184.10', '1.3.6.1.2.1.1.1.0', 'Description', 'N', 'N'),
(84, '192.168.184.11', '1.3.6.1.2.1.1.1.0', 'Description', 'N', 'N'),
(85, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.5.1.2.1.2.1', 'AttactedDevice_1', 'N', 'N'),
(89, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.2.2.1.0', 'BatteryRemaining_percent', 'Y', 'N'),
(90, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.2.2.2.0', 'Temperature_C', 'Y', 'N'),
(91, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.2.2.1.0', 'BatteryRemaining_percent', 'Y', 'N'),
(92, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.2.2.2.0', 'Temperature_C', 'Y', 'N'),
(93, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.3.2.1.0', 'InputVoltage', 'Y', 'N'),
(94, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.3.2.1.0', 'InputVoltage', 'Y', 'N'),
(95, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.4.2.3.0', 'OutputLoad_percent', 'Y', 'N'),
(96, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.4.2.3.0', 'OutputLoad_percent', 'Y', 'N'),
(97, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.1.2.1.0', 'FirmwareRevision', 'N', 'N'),
(98, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.1.2.1.0', 'FirmwareRevision', 'N', 'N'),
(99, '192.168.184.10', '1.3.6.1.2.1.1.6.0', 'Location', 'N', 'N'),
(100, '192.168.184.11', '1.3.6.1.2.1.1.6.0', 'Location', 'N', 'N'),
(101, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.1.1.1.0', 'BasicModel', 'N', 'N'),
(102, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.1.1.1.0', 'BasicModel', 'N', 'N'),
(103, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.1.1.2.0', 'UpsIdName', 'N', 'N'),
(104, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.1.1.2.0', 'UpsIdName', 'N', 'N'),
(105, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.5.2.4.0', 'AlarmCondition', 'N', 'N'),
(106, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.5.2.4.0', 'AlarmCondition', 'N', 'N'),
(107, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.5.2.5.0', 'AlarmTimer', 'Y', 'N'),
(108, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.5.2.5.0', 'AlarmTimer', 'Y', 'N'),
(109, '192.168.184.10', '1.3.6.1.2.1.1.5.0', 'SystemName', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `monitorDeviceList`
--

CREATE TABLE IF NOT EXISTS `monitorDeviceList` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmpVersion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `community` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailAddress` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numberOfOid` bigint(20) unsigned DEFAULT NULL,
  `numberOfStatisticOid` bigint(20) unsigned DEFAULT NULL,
  `emailSent` enum('N','Y') COLLATE utf8_unicode_ci DEFAULT 'N',
  `statusError` enum('N','Y') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorDeviceList`
--

INSERT INTO `monitorDeviceList` (`id`, `ip`, `name`, `snmpVersion`, `community`, `emailAddress`, `numberOfOid`, `numberOfStatisticOid`, `emailSent`, `statusError`) VALUES
(1, '192.168.184.10', 'UPS5', '2c', 'public', 'SunnyBingoMe@gmail.com', 19, 7, 'N', 'N'),
(2, '192.168.184.11', 'UPS6', '2c', 'public', 'SunnyBoyMe@gmail.com', 19, 7, 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `monitorErrorLog`
--

CREATE TABLE IF NOT EXISTS `monitorErrorLog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorErrorLog`
--


-- --------------------------------------------------------

--
-- Table structure for table `monitorHourLog`
--

CREATE TABLE IF NOT EXISTS `monitorHourLog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statisticOid1` bigint(20) DEFAULT NULL,
  `statisticOid2` bigint(20) DEFAULT NULL,
  `statisticOid3` bigint(20) DEFAULT NULL,
  `statisticOid4` bigint(20) DEFAULT NULL,
  `statisticOid5` bigint(20) DEFAULT NULL,
  `statisticOid6` bigint(20) DEFAULT NULL,
  `statisticOid7` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorHourLog`
--


-- --------------------------------------------------------

--
-- Table structure for table `monitorOidNameList`
--

CREATE TABLE IF NOT EXISTS `monitorOidNameList` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailAddress` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `needStatisticAndThreshold` enum('N','Y') COLLATE utf8_unicode_ci DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorOidNameList`
--

INSERT INTO `monitorOidNameList` (`id`, `name`, `emailAddress`, `needStatisticAndThreshold`) VALUES
(14, 'AttactedDevice_1', 'SunnyBoyMe@gmail.com', 'N'),
(13, 'SystemName', 'SunnyBingoMe@gmail.com', 'N'),
(12, 'BatteryRemaining_10ms', 'SunnyBingoMe@gmail.com', 'Y'),
(11, 'OutputVoltage', 'SunnyBingoMe@gmail.com', 'Y'),
(38, 'BatteryRemaining_percent', 'Me@SunnyBoy.Me', 'Y'),
(10, 'BatteryStatus', 'SunnyBingoMe@gmail.com', 'N'),
(9, 'SystemUpTime_10ms', 'SunnyBingoMe@gmail.com', 'N'),
(15, 'AttactedDevice_2', 'SunnyBoyMe@gmail.com', 'N'),
(16, 'AttactedDevice_3', 'SunnyBoyMe@gmail.com', 'N'),
(35, 'Temperature_C', 'Me@SunnyBoy.Me', 'Y'),
(37, 'Description', 'Me@SunnyBoy.Me', 'N'),
(39, 'InputVoltage', 'Me@SunnyBoy.Me', 'Y'),
(41, 'OutputLoad_percent', 'Me@SunnyBoy.Me', 'Y'),
(42, 'FirmwareRevision', 'Me@SunnyBoy.Me', 'N'),
(43, 'Location', 'Me@SunnyBoy.Me', 'N'),
(44, 'BasicModel', 'Me@SunnyBoy.Me', 'N'),
(45, 'UpsIdName', 'Me@SunnyBoy.Me', 'N'),
(46, 'AlarmCondition', 'Me@SunnyBoy.Me', 'N'),
(47, 'AlarmTimer', 'Me@SunnyBoy.Me', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `monitorSample`
--

CREATE TABLE IF NOT EXISTS `monitorSample` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid4` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid5` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid6` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid7` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid8` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid9` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid10` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid11` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid12` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid13` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid14` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid15` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid16` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid17` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid18` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid19` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorSample`
--


-- --------------------------------------------------------

--
-- Table structure for table `monitorThreshold`
--

CREATE TABLE IF NOT EXISTS `monitorThreshold` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `threshold1` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `threshold2` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorThreshold`
--

INSERT INTO `monitorThreshold` (`id`, `ip`, `oid`, `threshold1`, `threshold2`) VALUES
(2, '192.168.184.10', '1.3.6.1.4.1.318.1.1.1.2.2.3.0', 'min:30000:email:battary low', 'min:12000:shutdown'),
(3, '192.168.184.11', '1.3.6.1.4.1.318.1.1.1.2.2.3.0', 'min:60000:email:battary low', 'min:18000:shutdown');

-- --------------------------------------------------------

--
-- Table structure for table `monitorUserList`
--

CREATE TABLE IF NOT EXISTS `monitorUserList` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isAdmin` enum('N','Y') COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailAddress` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `monitorUserList`
--

INSERT INTO `monitorUserList` (`id`, `username`, `password`, `isAdmin`, `emailAddress`) VALUES
(1, 'admin1', '$1$Sunny_Cr$fYLD8wnghDdBQnP5vsaZq/', 'Y', 'SunnyBingoMe@gmail.com'),
(8, 'user1', '$1$Sunny_Cr$PD7jbJfcjSivOoYSTvPt70', 'N', 'BinSun@mail.com'),
(7, 'admin2', '$1$Sunny_Cr$WG0j0mcZ5aSmmC9Cy49I91', 'Y', 'SunnyBoyMe@gmail.com'),
(12, 'root', '$1$Sunny_Cr$vE9i6pRut.Wvi.cEe2Ph/1', 'Y', 'Me@SunnyBoy.Me');
