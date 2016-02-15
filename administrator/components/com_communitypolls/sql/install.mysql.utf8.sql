CREATE TABLE IF NOT EXISTS  `#__jcp_approval` (
  `item_id` int(10) unsigned NOT NULL,
  `item_type` int(10) unsigned NOT NULL,
  `secret` varchar(128) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`secret`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `published` tinyint(4) unsigned NOT NULL default '0',
  `locked` tinyint(4) unsigned NOT NULL default '0',
  `parent_id` int(10) unsigned NOT NULL,
  `num_polls` int(10) unsigned NOT NULL default '0',
  `num_votes` int(10) unsigned NOT NULL default '0',
  `nleft` int(10) unsigned NOT NULL,
  `nright` int(10) unsigned NOT NULL,
  `nlevel` int(10) unsigned NOT NULL DEFAULT '0',
  `norder` int(10) unsigned NOT NULL DEFAULT '0',
  `language` VARCHAR(6) NOT NULL DEFAULT '*',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text,
  `end_message` mediumtext,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `votes` int(9) unsigned NOT NULL DEFAULT '0',
  `voters` int(9) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL,
  `created_by_alias` varchar(255) DEFAULT NULL,
  `published` tinyint(3) DEFAULT NULL,
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_voted` datetime DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `close_date` datetime DEFAULT NULL,
  `results_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip_address` varchar(39) DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'radio',
  `anywhere` tinyint(3) DEFAULT '0',
  `custom_answer` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `chart_type` varchar(10) NOT NULL DEFAULT 'global',
  `pallete` varchar(10) NOT NULL DEFAULT 'default',
  `anonymous` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `private` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `secret` varchar(16) DEFAULT NULL,
  `answers_order` varchar(8) NOT NULL DEFAULT 'order',
  `min_answers` INTEGER UNSIGNED NOT NULL DEFAULT 1,
  `max_answers` INTEGER UNSIGNED NOT NULL DEFAULT 0,
  `modify_answers` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `language` varchar(6) NOT NULL DEFAULT '*',
  `version` int(10) unsigned NOT NULL DEFAULT '0',
  `attribs` varchar(5120) DEFAULT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) unsigned NOT NULL DEFAULT '0',
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  PRIMARY KEY (`id`),
  KEY `idx_polls_created_by` (`created_by`),
  KEY `idx_jcp_polls_catid` (`catid`),
  KEY `idx_jcp_polls_published` (`published`),
  KEY `idx_jcp_polls_checkout` (`checked_out`),
  KEY `idx_jcp_polls_access` (`access`),
  KEY `idx_jcp_polls_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_votes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `poll_id` int(10) unsigned NOT NULL,
  `voter_id` int(10) unsigned default NULL,
  `ip_address` varchar(39) default NULL,
  `voted_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `option_id` int(10) unsigned NOT NULL default '0',
  `column_id` int(10) unsigned NOT NULL default '0',
  `custom_answer` TEXT,
  PRIMARY KEY  (`id`),  
  KEY `FK_JCP_VOTES` (`poll_id`),
  KEY `idx_jcp_votes_optionid` (`option_id`),
  KEY `idx_jcp_votes_voter_id` (`voter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_options` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `poll_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `votes` int(6) unsigned NOT NULL default '0',
  `order` int(10) unsigned NOT NULL,
  `published` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`),
  KEY `idx_jcp_options_pollid` (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_resources` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `poll_id` int(10) unsigned NOT NULL,
  `option_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `polls` int(10) unsigned NOT NULL DEFAULT '0',
  `votes` int(10) unsigned NOT NULL DEFAULT '0',
  `last_poll` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_voted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jcp_tracking` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `post_type` varchar(6) NOT NULL DEFAULT '0',
  `ip_address` varchar(39) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `browser_name` varchar(32) DEFAULT NULL,
  `browser_version` varchar(24) DEFAULT NULL,
  `os` varchar(32) DEFAULT NULL,
  `browser_info` text,
  PRIMARY KEY (`post_id`,`post_type`) USING BTREE,
  KEY `idx_jcp_tracking_country` (`country`),
  KEY `idx_jcp_tracking_city` (`city`),
  KEY `idx_jcp_tracking_browser` (`browser_name`),
  KEY `idx_jcp_tracking_os` (`os`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;