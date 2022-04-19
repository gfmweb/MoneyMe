-- Adminer 4.8.1 MySQL 5.5.5-10.7.3-MariaDB-1:10.7.3+maria~focal dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `debug` /*!40100 DEFAULT CHARACTER SET utf8mb3 */;
USE `debug`;

DROP TABLE IF EXISTS `telegram_users`;
CREATE TABLE `telegram_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telegram_id` bigint(16) NOT NULL,
  `user_name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- 2022-04-18 11:18:11
