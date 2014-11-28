# ************************************************************
# Sequel Pro SQL dump
# Версия 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Адрес: localhost (MySQL 5.5.27)
# Схема: bootstrap
# Время создания: 2014-01-22 08:27:53 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы authAssignment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `authAssignment`;

CREATE TABLE `authAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы authItem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `authItem`;

CREATE TABLE `authItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы authItemChild
# ------------------------------------------------------------

DROP TABLE IF EXISTS `authItemChild`;

CREATE TABLE `authItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL,
  `ownerId` int(11) unsigned NOT NULL,
  `ownerType` tinyint(1) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `file` varchar(255) NOT NULL DEFAULT '',
  `size` int(11) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `ip` bigint(20) NOT NULL,
  `pos` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`ownerId`,`ownerType`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;



# Дамп таблицы news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` longtext NOT NULL,
  `preview` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateUpd` datetime NOT NULL,
  `datePub` date NOT NULL,
  `reference` varchar(255) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `pos` tinyint(1) unsigned NOT NULL,
  `languageId` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `active` (`active`),
  KEY `pos` (`pos`),
  KEY `languageId` (`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы newsToSections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `newsToSections`;

CREATE TABLE `newsToSections` (
  `newsId` int(11) unsigned NOT NULL,
  `sectionId` int(11) unsigned NOT NULL,
  PRIMARY KEY (`newsId`,`sectionId`),
  CONSTRAINT `FK_newsToSections_news` FOREIGN KEY (`newsId`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы sections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sections`;

CREATE TABLE `sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Название',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;

INSERT INTO `sections` (`id`, `title`)
VALUES
	(1,'Игры'),
	(2,'Книги'),
	(3,'Кино'),
	(4,'Музыка'),
	(5,'Новости');

/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Название',
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы tagsToModels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tagsToModels`;

CREATE TABLE `tagsToModels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tagId` int(11) unsigned NOT NULL,
  `modelId` int(11) unsigned NOT NULL,
  `sectionId` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tagsToModels_tags` (`tagId`),
  KEY `model` (`modelId`,`sectionId`),
  CONSTRAINT `FK_tagsToModels_tags` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` char(64) NOT NULL DEFAULT '',
  `salt` char(64) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `dateLogin` datetime NOT NULL,
  `ip` bigint(20) NOT NULL,
  `access` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`),
  KEY `email` (`email`),
  KEY `access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `login`, `email`, `password`, `salt`, `date`, `dateLogin`, `ip`, `access`)
VALUES
	(1,'editor','','920d2744feddebda5da8600d72435a90e8e56e86758a48c2bec43113df406614','a24bfa7a45004092c3a0aa4e212b2c04ac365551390be954e3498634f078a827','2013-11-27 10:10:10','2014-01-22 12:19:28',2130706433,2);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
