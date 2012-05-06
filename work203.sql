-- Adminer 3.3.4 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `work203`;
CREATE DATABASE `work203` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `work203`;

DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_id` int(11) NOT NULL,
  `search_result` varchar(300) NOT NULL,
  `times` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search_id` (`search_id`),
  CONSTRAINT `results_ibfk_1` FOREIGN KEY (`search_id`) REFERENCES `search` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `search`;
CREATE TABLE `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_string` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2012-05-06 22:38:36
