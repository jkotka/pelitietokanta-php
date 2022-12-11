-- Pelitietokanta

SET foreign_key_checks = 0;
CREATE DATABASE IF NOT EXISTS `games`;
USE `games`;

DROP TABLE IF EXISTS `platform`;
CREATE TABLE `platform` (
  `platform_id` int unsigned NOT NULL AUTO_INCREMENT,
  `platform_name` varchar(50) NOT NULL,
  PRIMARY KEY (`platform_id`),
  UNIQUE KEY `platform_name` (`platform_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `mediatype`;
CREATE TABLE `mediatype` (
  `mediatype_id` int unsigned NOT NULL AUTO_INCREMENT,
  `mediatype_name` varchar(50) NOT NULL,
  PRIMARY KEY (`mediatype_id`),
  UNIQUE KEY `mediatype_name` (`mediatype_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `store`;
CREATE TABLE `store` (
  `store_id` int unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(50) NOT NULL,
  PRIMARY KEY (`store_id`),
  UNIQUE KEY `store_name` (`store_name`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `paymethod`;
CREATE TABLE `paymethod` (
  `paymethod_id` int unsigned NOT NULL AUTO_INCREMENT,
  `paymethod_name` varchar(50) NOT NULL,
  PRIMARY KEY (`paymethod_id`),
  UNIQUE KEY `paymethod_name` (`paymethod_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `title`;
CREATE TABLE `title` (
  `title_id` int unsigned NOT NULL AUTO_INCREMENT,
  `title_name` varchar(100) NOT NULL,
  `title_edition` varchar(100) DEFAULT NULL,
  `title_published` year NOT NULL,
  `platform_id` int unsigned NOT NULL,
  `mediatype_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `title_type` tinyint NOT NULL,
  `title_status` tinyint NOT NULL,
  `title_info` varchar(255) DEFAULT NULL,
  `title_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `title_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`title_id`),
  KEY `platform_id` (`platform_id`),
  KEY `mediatype_id` (`mediatype_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `title_ibfk_1` FOREIGN KEY (`platform_id`) REFERENCES `platform` (`platform_id`),
  CONSTRAINT `title_ibfk_2` FOREIGN KEY (`mediatype_id`) REFERENCES `mediatype` (`mediatype_id`),
  CONSTRAINT `title_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `title` (`title_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1788 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE `purchase` (
  `purchase_id` int unsigned NOT NULL AUTO_INCREMENT,
  `title_id` int unsigned NOT NULL,
  `paymethod_id` int unsigned DEFAULT NULL,
  `store_id` int unsigned DEFAULT NULL,
  `purchase_price` decimal(5,2) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_info` varchar(255) DEFAULT NULL,
  `purchase_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `purchase_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`purchase_id`),
  KEY `title_id` (`title_id`),
  KEY `paymethod_id` (`paymethod_id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`title_id`) REFERENCES `title` (`title_id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`paymethod_id`) REFERENCES `paymethod` (`paymethod_id`),
  CONSTRAINT `purchase_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=406 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `stat`;
CREATE TABLE `stat` (
  `stat_id` int unsigned NOT NULL AUTO_INCREMENT,
  `title_id` int unsigned NOT NULL,
  `stat_started` date DEFAULT NULL,
  `stat_stopped` date DEFAULT NULL,
  `stat_hours` smallint unsigned DEFAULT NULL,
  `stat_beaten` tinyint NOT NULL,
  `stat_info` varchar(255) DEFAULT NULL,
  `stat_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `stat_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`stat_id`),
  KEY `title_id` (`title_id`),
  CONSTRAINT `stat_ibfk_1` FOREIGN KEY (`title_id`) REFERENCES `title` (`title_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=489 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET foreign_key_checks = 1;