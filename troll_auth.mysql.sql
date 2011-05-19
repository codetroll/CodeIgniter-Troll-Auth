-- phpMyAdmin SQL Dump
-- version 2.8.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 16, 2011 at 10:49 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6
-- 
-- Database: `sb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `accgrants`
-- 

CREATE TABLE `accgrants` (
  `user_id` int(9) default '-1',
  `group_id` int(9) default '-1',
  `resource_id` int(9) NOT NULL default '0',
  `access_level` tinyint(2) NOT NULL default '0',
  KEY `user_id` (`group_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- 
-- Dumping data for table `accgrants`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `accgroups`
-- 

CREATE TABLE `accgroups` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_danish_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `accgroups`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `accmember`
-- 

CREATE TABLE `accmember` (
  `user_id` int(9) NOT NULL default '0',
  `group_id` int(9) NOT NULL default '0',
  UNIQUE KEY `LiegeVassalCombo` (`user_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- 
-- Dumping data for table `accmember`
-- 

INSERT INTO `accmember` VALUES (1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `accmember_back`
-- 

CREATE TABLE `accmember_back` (
  `user_id` int(9) NOT NULL default '0',
  `group_id` int(9) NOT NULL default '0',
  UNIQUE KEY `LiegeVassalCombo` (`user_id`,`group_id`),
  KEY `group_id` (`numAccGrpId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- 
-- Dumping data for table `accmember_back`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `accresources`
-- 

CREATE TABLE `accresources` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_danish_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `accresources`
-- 

