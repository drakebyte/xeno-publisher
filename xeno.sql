-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `xeno`;
CREATE DATABASE `xeno` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `xeno`;

DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE `eventlog` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique event log ID.',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'The user.uid of the user who triggered the event.',
  `log_type` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Type of log message, for example "user" or "page not found."',
  `log_severity` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The severity level of the event; ranges from 0 (Emergency) to 7 (Debug)',
  `log_referer` text COLLATE utf8_bin COMMENT 'URL of referring page.',
  `log_message` longtext COLLATE utf8_bin NOT NULL COMMENT 'Text of log message to be passed into the t() function.',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `eventlog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `eventlog`;

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'File ID.',
  `user_id` int(11) NOT NULL COMMENT 'The id of the user who is associated with the file.',
  `file_name` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Name of the file with no path components. This may differ from the basename of the URI if the file is renamed to avoid overwriting an existing file.',
  `file_uri` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The URI to access the file (either local or remote).',
  `file_mime` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'The file’s MIME type.',
  `file_size` int(11) NOT NULL COMMENT 'The size of the file in bytes.',
  `file_status` tinyint(4) NOT NULL COMMENT 'A field indicating the status of the file. Two status are defined in core: temporary (0) and permanent (1). Temporary files older than DRUPAL_MAXIMUM_TEMP_FILE_AGE will be removed during a cron run.',
  `file_created` int(11) NOT NULL COMMENT 'UNIX timestamp for when the file was added.',
  `file_type` varchar(11) COLLATE utf8_bin NOT NULL COMMENT 'The type of this file.',
  PRIMARY KEY (`file_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `files`;

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `lang_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Language ID.',
  `lang_code` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Machine readable language code.',
  `lang_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Human readable language name.',
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `languages`;
INSERT INTO `languages` (`lang_id`, `lang_code`, `lang_name`) VALUES
(1,	'en',	'english');

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `user_name` varchar(32) COLLATE utf8_bin NOT NULL,
  `invalid_time` int(11) NOT NULL,
  KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `login_attempts`;
INSERT INTO `login_attempts` (`user_name`, `invalid_time`) VALUES
('admino',	1445381642),
('admino',	1445381661),
('admino',	1445381664),
('admino',	1445381667),
('admino',	1445381669),
('admino',	1445381674),
('admino',	1445381677),
('admino',	1445381736),
('admin <a>',	1445447966);

DROP TABLE IF EXISTS `pod`;
CREATE TABLE `pod` (
  `pod_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a pod.',
  `pod_type` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'The machine-readable name of the pod type.',
  `user_id` int(11) NOT NULL COMMENT 'The author''s user id.',
  `pod_status` int(11) NOT NULL COMMENT 'Boolean indicating whether the node is published (visible to non-administrators).',
  `pod_created` int(11) NOT NULL COMMENT 'The Unix timestamp when the pod was created.',
  `pod_modified` int(11) NOT NULL COMMENT 'The Unix timestamp when the pod was most recently saved.',
  PRIMARY KEY (`pod_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pod_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `pod`;

DROP TABLE IF EXISTS `podfields`;
CREATE TABLE `podfields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Field data id.',
  `pod_id` int(10) NOT NULL COMMENT 'Parent Pod id.',
  `field_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Field name.',
  `lang_name` int(11) NOT NULL COMMENT 'Pod content language.',
  `field_content` longtext COLLATE utf8_bin NOT NULL COMMENT 'The field data to be displayed in the front end.',
  `lang_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Pod field language.',
  PRIMARY KEY (`field_id`),
  KEY `pod_id` (`pod_id`),
  KEY `lang_name` (`lang_name`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `podfields_ibfk_1` FOREIGN KEY (`pod_id`) REFERENCES `pod` (`pod_id`),
  CONSTRAINT `podfields_ibfk_2` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `podfields`;

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `role`;

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  KEY `role_id` (`role_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `role_permissions`;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_name` varchar(128) COLLATE utf8_bin NOT NULL COMMENT 'The name of the setting.',
  `setting_value` varchar(256) COLLATE utf8_bin NOT NULL COMMENT 'The value of the setting.',
  PRIMARY KEY (`setting_name`),
  UNIQUE KEY `unique` (`setting_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `settings`;
INSERT INTO `settings` (`setting_name`, `setting_value`) VALUES
('admin_theme',	'queen'),
('current_theme',	'leonidas'),
('language',	'en'),
('secret',	'coelacantsaretastyfishies');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique user ID.',
  `user_name` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'Unique user name.',
  `user_password` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'User’s password (hashed).',
  `user_email` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'User’s e-mail address.',
  `user_created` int(11) NOT NULL COMMENT 'Timestamp for when user was created.',
  `user_login` int(11) NOT NULL COMMENT 'Timestamp for user’s last login.',
  `user_status` int(11) NOT NULL COMMENT 'Whether the user is active(1) or blocked(0).',
  `lang_id` int(11) NOT NULL DEFAULT '1' COMMENT 'User’s default language.',
  PRIMARY KEY (`user_id`),
  KEY `lang_id` (`lang_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `languages` (`lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `user`;
INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_email`, `user_created`, `user_login`, `user_status`, `lang_id`) VALUES
(1,	'admin',	'21232f297a57a5a743894a0e4a801fc3',	'admin@admin.com',	0,	0,	1,	1);

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL COMMENT 'Primary Key: user.user_id for user.',
  `role_id` int(11) NOT NULL COMMENT 'Primary Key: role.role_id for role.',
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

TRUNCATE `user_roles`;

-- 2015-10-23 19:53:02
