DROP TABLE IF EXISTS `#__sptransfer_tables`;
CREATE TABLE IF NOT EXISTS `#__sptransfer_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `extension_name` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Name of the extension''s table',
  `category` int(11) unsigned DEFAULT NULL COMMENT 'Parent id category',
  `order` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `extension_name` (`extension_name`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__sptransfer_tables` (`id`, `extension_name`, `name`, `category`, `order`) VALUES
(1, 'com_users', 'usergroups', NULL, 1),
(2, 'com_users', 'viewlevels', NULL, 2),
(3, 'com_users', 'users', NULL, 4),
(4, 'com_content', 'categories', NULL, 7),
(5, 'com_content', 'content', 4, 8),
(6, 'com_contact', 'categories', NULL, 9),
(7, 'com_contact', 'contact_details', 6, 10),
(8, 'com_weblinks', 'categories', NULL, 11),
(9, 'com_weblinks', 'weblinks', 8, 12),
(10, 'com_newsfeeds', 'categories', NULL, 13),
(11, 'com_newsfeeds', 'newsfeeds', 10, 14),
(12, 'com_banners', 'categories', NULL, 15),
(13, 'com_banners', 'banner_clients', NULL, 16),
(14, 'com_banners', 'banners', 12, 17),
(15, 'com_menus', 'menu_types', NULL, 18),
(16, 'com_menus', 'menu', NULL, 19),
(17, 'com_modules', 'modules', NULL, 20),
(18, 'com_users', 'categories', NULL, 5),
(19, 'com_users', 'notes', 18, 6),
(20, 'com_tags', 'tags', NULL, 3);

DROP TABLE IF EXISTS `#__sptransfer_log`;
CREATE TABLE IF NOT EXISTS `#__sptransfer_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tables_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__sptransfer_tables',
  `note` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `source_id` int(10) unsigned NOT NULL DEFAULT '0',
  `destination_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;