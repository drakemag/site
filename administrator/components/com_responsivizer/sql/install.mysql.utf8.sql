-- Basic table from 2.0
CREATE TABLE IF NOT EXISTS `#__rvzr_offline_urls` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`itemid` INT NOT NULL ,
	`description` TEXT NULL ,
	`checked_out` INT UNSIGNED NOT NULL DEFAULT  '0',
	`checked_out_time` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
	`published` TINYINT NOT NULL DEFAULT  '1',
	`deleted` TINYINT NOT NULL DEFAULT  '0',
	`process_css` TINYINT NOT NULL DEFAULT  '1',
	`process_js` TINYINT NOT NULL DEFAULT  '1',
	`process_img` TINYINT NOT NULL DEFAULT  '1',
	`resources` TEXT NULL
) ENGINE=InnoDB CHARACTER SET `utf8` ;

CREATE TABLE IF NOT EXISTS `#__rvzr_modules` (
	`id` INT NOT NULL PRIMARY KEY ,
	`mobile_exclude` TINYINT NOT NULL DEFAULT  '0'
) ENGINE=InnoDB CHARACTER SET `utf8` ;


-- Exceptions queries in reverse versioning order 10.0 -> 1.0
ALTER TABLE `#__rvzr_modules` ADD `show_title` TINYINT NOT NULL DEFAULT  '1'; -- Version 2.1
ALTER TABLE `#__rvzr_modules` ADD `show_bg` TINYINT NOT NULL DEFAULT  '1'; -- Version 2.1