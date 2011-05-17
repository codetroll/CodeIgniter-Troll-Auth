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
-- Table structure for table `sb_accgrants`
-- 

CREATE TABLE `sb_accgrants` (
  `user_id` int(18) default '-1',
  `group_id` int(18) default '-1',
  `resource_id` int(18) NOT NULL default '0',
  `access_level` tinyint(2) NOT NULL default '0',
  KEY `user_id` (`group_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- 
-- Dumping data for table `sb_accgrants`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sb_accgroups`
-- 

CREATE TABLE `sb_accgroups` (
  `id` int(18) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_danish_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  `watch` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=173 ;

-- 
-- Dumping data for table `sb_accgroups`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sb_accmember`
-- 

CREATE TABLE `sb_accmember` (
  `user_id` int(18) NOT NULL default '0',
  `group_id` int(18) NOT NULL default '0',
  UNIQUE KEY `LiegeVassalCombo` (`user_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- 
-- Dumping data for table `sb_accmember`
-- 

INSERT INTO `sb_accmember` VALUES (1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `sb_accmember_back`
-- 

CREATE TABLE `sb_accmember_back` (
  `user_id` int(18) NOT NULL default '0',
  `group_id` int(18) NOT NULL default '0',
  UNIQUE KEY `LiegeVassalCombo` (`user_id`,`group_id`),
  KEY `group_id` (`numAccGrpId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;

-- 
-- Dumping data for table `sb_accmember_back`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sb_accresources`
-- 

CREATE TABLE `sb_accresources` (
  `id` int(18) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_danish_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sb_accresources`
-- 

