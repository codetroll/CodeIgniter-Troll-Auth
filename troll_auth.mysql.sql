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
);

-- 
-- Dumping data for table `accgrants`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `accgroups`
-- 

CREATE TABLE `accgroups` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);

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
  KEY `group_id` (`group_id`)
);

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
  KEY `group_id` (`_`)
);

-- 
-- Dumping data for table `accmember_back`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `accresources`
-- 

CREATE TABLE `accresources` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

-- 
-- Dumping data for table `accresources`
-- 

