#
# SQL Export
# Created by Querious (936)
# Created: 10 октября 2014 г., 10:21:41 GMT+7
# Encoding: Unicode (UTF-8)
#


DROP TABLE IF EXISTS `usersToSocials`;
DROP TABLE IF EXISTS `usersProfiles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `tbl_migration`;
DROP TABLE IF EXISTS `tagsToModels`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `socials`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `sections`;
DROP TABLE IF EXISTS `regions`;
DROP TABLE IF EXISTS `newsToSections`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `logs`;
DROP TABLE IF EXISTS `gallery`;
DROP TABLE IF EXISTS `files`;
DROP TABLE IF EXISTS `cities`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `authItemChild`;
DROP TABLE IF EXISTS `authAssignment`;
DROP TABLE IF EXISTS `authItem`;


CREATE TABLE `authItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `authAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `authItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `banners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `size` char(8) NOT NULL,
  `file` varchar(255) NOT NULL DEFAULT '',
  `dateCreate` datetime NOT NULL,
  `dateUpd` datetime NOT NULL,
  `dateEnd` date NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_get` (`dateEnd`,`active`,`size`),
  KEY `idx_actual` (`active`,`dateEnd`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `countries` (
  `countryId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`countryId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `cities` (
  `cityId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `countryId` int(11) unsigned NOT NULL,
  `important` tinyint(1) unsigned NOT NULL COMMENT 'Крупный город',
  `regionId` int(11) unsigned NOT NULL,
  `title` varchar(150) NOT NULL DEFAULT '',
  `area` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`cityId`),
  KEY `countryId` (`countryId`),
  KEY `country_region` (`countryId`,`regionId`),
  KEY `title` (`title`),
  KEY `FK_cities_regions` (`regionId`),
  KEY `country_important` (`countryId`,`important`),
  CONSTRAINT `FK_cities_countries` FOREIGN KEY (`countryId`) REFERENCES `countries` (`countryId`) ON DELETE CASCADE,
  CONSTRAINT `FK_cities_regions` FOREIGN KEY (`regionId`) REFERENCES `regions` (`regionId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL,
  `ownerId` int(11) NOT NULL,
  `ownerType` tinyint(1) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `file` varchar(255) NOT NULL DEFAULT '',
  `size` int(11) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `info` text NOT NULL,
  `ip` bigint(20) NOT NULL,
  `pos` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`ownerId`,`ownerType`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;


CREATE TABLE `gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(128) NOT NULL,
  `category` varchar(128) NOT NULL,
  `logtime` int(11) NOT NULL,
  `message` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `idx_category_title` (`category`,`title`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;


CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` longtext NOT NULL,
  `preview` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateUpd` datetime NOT NULL,
  `datePub` datetime NOT NULL,
  `reference` varchar(255) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `pos` int(11) unsigned NOT NULL,
  `languageId` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `active` (`active`),
  KEY `pos` (`pos`),
  KEY `languageId` (`languageId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `newsToSections` (
  `newsId` int(11) unsigned NOT NULL,
  `sectionId` int(11) unsigned NOT NULL,
  PRIMARY KEY (`newsId`,`sectionId`),
  CONSTRAINT `FK_newsToSections_news` FOREIGN KEY (`newsId`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `regions` (
  `regionId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `countryId` int(11) unsigned NOT NULL,
  `title` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`regionId`),
  KEY `countryId` (`countryId`),
  KEY `title` (`title`),
  CONSTRAINT `FK_regions_countries` FOREIGN KEY (`countryId`) REFERENCES `countries` (`countryId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


CREATE TABLE `settings` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `socials` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `key` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


CREATE TABLE `tagsToModels` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tagId` int(11) unsigned NOT NULL,
  `ownerId` int(11) unsigned NOT NULL,
  `ownerType` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tagsToModels_tags` (`tagId`),
  KEY `owner` (`ownerId`,`ownerType`),
  CONSTRAINT `FK_tagsToModels_tags` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


CREATE TABLE `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` char(64) NOT NULL DEFAULT '',
  `salt` char(64) NOT NULL DEFAULT '',
  `dateReg` datetime NOT NULL,
  `dateLogin` datetime NOT NULL,
  `ip` bigint(20) NOT NULL,
  `role` varchar(64) NOT NULL DEFAULT '',
  `access` tinyint(1) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  KEY `access` (`access`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;


CREATE TABLE `usersProfiles` (
  `userId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL DEFAULT '',
  `lastName` varchar(255) NOT NULL DEFAULT '',
  `photo` varchar(255) NOT NULL DEFAULT '',
  `birthDay` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`userId`),
  CONSTRAINT `FK_usersProfiles_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;


CREATE TABLE `usersToSocials` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL DEFAULT '0',
  `socialId` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `identifier` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL DEFAULT '',
  `atoken` varchar(255) NOT NULL,
  `asecret` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userId`),
  KEY `user` (`socialId`,`userId`) USING BTREE,
  CONSTRAINT `FK_usersToSocials_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `authItem` WRITE;
ALTER TABLE `authItem` DISABLE KEYS;
INSERT INTO `authItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES 
	('AdminBanners*',0,'Админка / Баннеры',NULL,NULL),
	('AdminCities*',0,'Админка / Города',NULL,NULL),
	('AdminCountries*',0,'Админка / Страны',NULL,NULL),
	('AdminFiles-All',0,'Админка / Редакт. чужих файлов',NULL,NULL),
	('AdminGallery*',0,'Админка / Галереи',NULL,NULL),
	('AdminModule',0,'Админка / Разрешить вход',NULL,NULL),
	('AdminNews*',0,'Админка / Новости',NULL,NULL),
	('AdminRegions*',0,'Админка / Регионы',NULL,NULL),
	('AdminRoles*',0,'Админка / Роли пользователей',NULL,NULL),
	('AdminSections*',0,'Админка / Главные разделы',NULL,NULL),
	('AdminSettingsCache',0,'Админка / Управление кэшем',NULL,NULL),
	('AdminSettingsChangePassword',0,'Админка / Смена пароля',NULL,NULL),
	('AdminSettingsMain',0,'Админка / Основные настройки',NULL,NULL),
	('AdminTags*',0,'Админка / Теги',NULL,NULL),
	('AdminUsers*',0,'Админка / Пользователи',NULL,NULL),
	('editor',2,'Редактор',NULL,NULL),
	('moderator',2,'Модератор',NULL,NULL);
ALTER TABLE `authItem` ENABLE KEYS;
UNLOCK TABLES;

LOCK TABLES `sections` WRITE;
ALTER TABLE `sections` DISABLE KEYS;
INSERT INTO `sections` (`id`, `title`) VALUES 
	(1,'Игры'),
	(2,'Книги'),
	(3,'Фильмы'),
	(4,'Музыка'),
	(5,'Новости');
ALTER TABLE `sections` ENABLE KEYS;
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;

