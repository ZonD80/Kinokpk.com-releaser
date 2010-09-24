TRUNCATE TABLE `cron`;

/* Alter table in target */
ALTER TABLE `cron` 
	CHANGE `cron_name` `cron_name` varchar(255)  COLLATE cp1251_general_ci NOT NULL first, COMMENT='';

/* Create table in target */
CREATE TABLE `cron_emails`(
	`email` varchar(255) COLLATE cp1251_general_ci NOT NULL  , 
	`subject` varchar(255) COLLATE cp1251_general_ci NOT NULL  , 
	`body` text COLLATE cp1251_general_ci NOT NULL  
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Alter table in target */
ALTER TABLE `news` 
	CHANGE `subject` `subject` varchar(300)  COLLATE cp1251_general_ci NOT NULL after `body`, COMMENT='';

/* Alter table in target */
ALTER TABLE `newscomments` 
	CHANGE `editedat` `editedat` int(10)   NOT NULL after `editedby`, COMMENT='';

/* Create table in target */
CREATE TABLE `notifs`(
	`id` int(11) NOT NULL  auto_increment , 
	`checkid` int(11) NOT NULL  DEFAULT '0' , 
	`type` varchar(100) COLLATE cp1251_general_ci NOT NULL  , 
	`userid` int(11) NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `checkid`(`checkid`,`type`,`userid`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Alter table in target */
ALTER TABLE `orbital_blocks` 
	CHANGE `time` `time` int(10)   NOT NULL DEFAULT '0' after `active`, 
	CHANGE `expire` `expire` int(10)   NOT NULL DEFAULT '0' after `view`, COMMENT='';

/* Create table in target */
CREATE TABLE `pagecomments`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`user` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`page` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`added` int(10) NOT NULL  , 
	`text` text COLLATE cp1251_general_ci NOT NULL  , 
	`editedby` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`editedat` int(10) NOT NULL  , 
	`ratingsum` int(5) NOT NULL  DEFAULT '0' , 
	`ip` varchar(15) COLLATE cp1251_general_ci NOT NULL  , 
	`post_id` int(10) NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) , 
	KEY `user`(`user`) , 
	KEY `torrent`(`page`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Alter table in target */
ALTER TABLE `pages` 
	ADD COLUMN `category` varchar(100)  COLLATE cp1251_general_ci NOT NULL after `id`, 
	CHANGE `owner` `owner` int(10)   NOT NULL DEFAULT '0' after `category`, 
	CHANGE `added` `added` int(10)   NOT NULL after `owner`, 
	CHANGE `name` `name` varchar(300)  COLLATE cp1251_general_ci NOT NULL after `added`, 
	ADD COLUMN `class` int(2)   NOT NULL DEFAULT '0' after `name`, 
	ADD COLUMN `tags` varchar(500)  COLLATE cp1251_general_ci NOT NULL after `class`, 
	CHANGE `content` `content` text  COLLATE cp1251_general_ci NOT NULL after `tags`, 
	ADD COLUMN `comments` int(5) unsigned   NOT NULL DEFAULT '0' after `content`, 
	CHANGE `indexed` `indexed` tinyint(1)   NOT NULL DEFAULT '0' after `comments`, 
	ADD COLUMN `sticky` tinyint(1)   NOT NULL DEFAULT '0' after `indexed`, 
	ADD COLUMN `ratingsum` int(5)   NOT NULL DEFAULT '0' after `sticky`, 
	ADD COLUMN `denycomments` tinyint(1) unsigned   NOT NULL DEFAULT '0' after `ratingsum`, 
	ADD COLUMN `views` int(10) unsigned   NOT NULL DEFAULT '0' after `denycomments`, 
	DROP COLUMN `searchwords`, COMMENT='';

/* Create table in target */
CREATE TABLE `pagescategories`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`sort` int(10) NOT NULL  DEFAULT '0' , 
	`name` varchar(255) COLLATE cp1251_general_ci NOT NULL  , 
	`image` varchar(255) COLLATE cp1251_general_ci NOT NULL  , 
	`parent_id` int(10) NOT NULL  DEFAULT '0' , 
	`class` int(2) NOT NULL  DEFAULT '0' , 
	`class_edit` int(2) NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Alter table in target */
ALTER TABLE `polls_structure` 
	DROP KEY `value`, COMMENT='';

/* Create table in target */
CREATE TABLE `relgroups`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`name` varchar(150) COLLATE cp1251_general_ci NOT NULL  , 
	`added` int(10) NOT NULL  DEFAULT '0' , 
	`spec` varchar(500) COLLATE cp1251_general_ci NOT NULL  , 
	`descr` text COLLATE cp1251_general_ci NOT NULL  , 
	`image` varchar(300) COLLATE cp1251_general_ci NOT NULL  , 
	`owners` varchar(100) COLLATE cp1251_general_ci NOT NULL  , 
	`members` varchar(255) COLLATE cp1251_general_ci NOT NULL  , 
	`ratingsum` int(5) NOT NULL  DEFAULT '0' , 
	`private` tinyint(1) NOT NULL  DEFAULT '0' , 
	`only_invites` tinyint(1) unsigned NOT NULL  DEFAULT '0' , 
	`amount` int(3) NOT NULL  DEFAULT '0' , 
	`page_pay` varchar(300) COLLATE cp1251_general_ci NOT NULL  , 
	`subscribe_length` int(2) NOT NULL  DEFAULT '31' , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `name`(`name`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `reltemplates`(
	`id` int(3) unsigned NOT NULL  auto_increment , 
	`name` varchar(100) COLLATE cp1251_general_ci NOT NULL  , 
	`content` text COLLATE cp1251_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `name`(`name`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `reports`(
	`id` int(11) NOT NULL  auto_increment , 
	`reportid` int(10) NOT NULL  DEFAULT '0' , 
	`userid` int(10) NOT NULL  DEFAULT '0' , 
	`type` varchar(100) COLLATE cp1251_general_ci NOT NULL  , 
	`motive` varchar(255) COLLATE cp1251_general_ci NOT NULL  , 
	`added` int(10) NOT NULL  , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `reportid`(`reportid`,`userid`,`type`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `rg_invites`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`inviter` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`inviteid` int(10) NOT NULL  DEFAULT '0' , 
	`rgid` int(5) NOT NULL  DEFAULT '0' , 
	`invite` varchar(32) COLLATE cp1251_general_ci NOT NULL  , 
	`time_invited` int(10) NOT NULL  , 
	`confirmed` tinyint(1) NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `rg_subscribes`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`userid` int(10) unsigned NOT NULL  , 
	`rgid` int(5) unsigned NOT NULL  , 
	`valid_until` int(10) NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `userid`(`userid`,`rgid`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `rgcomments`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`user` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`relgroup` int(5) unsigned NOT NULL  DEFAULT '0' , 
	`added` int(10) NOT NULL  , 
	`text` text COLLATE cp1251_general_ci NOT NULL  , 
	`editedby` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`editedat` int(11) NOT NULL  , 
	`ratingsum` int(5) NOT NULL  DEFAULT '0' , 
	`ip` varchar(15) COLLATE cp1251_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) , 
	KEY `user`(`user`) , 
	KEY `news`(`relgroup`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `rgnews`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`relgroup` int(5) NOT NULL  DEFAULT '0' , 
	`added` int(10) NOT NULL  , 
	`body` text COLLATE cp1251_general_ci NOT NULL  , 
	`subject` varchar(300) COLLATE cp1251_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) , 
	KEY `added`(`added`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Create table in target */
CREATE TABLE `rgnewscomments`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`user` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`rgnews` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`added` int(10) NOT NULL  , 
	`text` text COLLATE cp1251_general_ci NOT NULL  , 
	`editedby` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`editedat` int(11) NOT NULL  , 
	`ratingsum` int(5) NOT NULL  DEFAULT '0' , 
	`ip` varchar(15) COLLATE cp1251_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) , 
	KEY `user`(`user`) , 
	KEY `news`(`rgnews`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Alter table in target */
ALTER TABLE `sitelog` 
	ADD COLUMN `userid` int(10)   NOT NULL DEFAULT '0' after `added`, 
	CHANGE `txt` `txt` text  COLLATE cp1251_general_ci NULL after `userid`, 
	CHANGE `type` `type` varchar(80)  COLLATE cp1251_general_ci NOT NULL DEFAULT 'tracker' after `txt`, 
	DROP COLUMN `color`, COMMENT='';

TRUNCATE TABLE `sitelog`;
/* Alter table in target */
ALTER TABLE `snatched` 
	CHANGE `uploaded` `uploaded` bigint(20) unsigned   NOT NULL DEFAULT '0' after `torrent`, 
	CHANGE `downloaded` `downloaded` bigint(20) unsigned   NOT NULL DEFAULT '0' after `uploaded`, 
	CHANGE `startedat` `startedat` int(10)   NOT NULL after `downloaded`, 
	CHANGE `completedat` `completedat` int(10)   NOT NULL after `startedat`, 
	CHANGE `finished` `finished` tinyint(1)   NOT NULL DEFAULT '0' after `completedat`, 
	DROP COLUMN `port`, 
	DROP COLUMN `to_go`, 
	DROP COLUMN `seeder`, 
	DROP COLUMN `last_action`, 
	DROP COLUMN `connectable`, 
	DROP KEY `snatch`, add UNIQUE KEY `snatch`(`torrent`,`userid`), COMMENT='';

/* Alter table in target */
ALTER TABLE `stamps` 
	DROP KEY `image`, COMMENT='';

/* Alter table in target */
ALTER TABLE `torrents` 
	CHANGE `name` `name` varchar(255)  COLLATE cp1251_general_ci NOT NULL after `info_hash`, 
	CHANGE `descr` `descr` text  COLLATE cp1251_general_ci NOT NULL after `name`, 
	CHANGE `filename` `filename` varchar(255)  COLLATE cp1251_general_ci NOT NULL after `descr`, 
	CHANGE `images` `images` text  COLLATE cp1251_general_ci NOT NULL after `filename`, 
	CHANGE `category` `category` varchar(255)  COLLATE cp1251_general_ci NOT NULL after `images`, 
	CHANGE `size` `size` bigint(20) unsigned   NOT NULL DEFAULT '0' after `category`, 
	CHANGE `added` `added` int(10)   NOT NULL DEFAULT '0' after `size`, 
	CHANGE `ismulti` `ismulti` tinyint(1)   NOT NULL DEFAULT '0' after `added`, 
	CHANGE `numfiles` `numfiles` int(10) unsigned   NOT NULL DEFAULT '1' after `ismulti`, 
	CHANGE `comments` `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `numfiles`, 
	CHANGE `views` `views` int(10) unsigned   NOT NULL DEFAULT '0' after `comments`, 
	CHANGE `hits` `hits` int(10) unsigned   NOT NULL DEFAULT '0' after `views`, 
	CHANGE `times_completed` `times_completed` int(10) unsigned   NOT NULL DEFAULT '0' after `hits`, 
	CHANGE `last_action` `last_action` int(10)   NOT NULL DEFAULT '0' after `times_completed`, 
	CHANGE `last_reseed` `last_reseed` int(10)   NOT NULL DEFAULT '0' after `last_action`, 
	CHANGE `visible` `visible` tinyint(1)   NOT NULL DEFAULT '1' after `last_reseed`, 
	CHANGE `banned` `banned` tinyint(1)   NOT NULL DEFAULT '0' after `visible`, 
	CHANGE `owner` `owner` int(10) unsigned   NOT NULL DEFAULT '0' after `banned`, 
	CHANGE `orig_owner` `orig_owner` int(10) unsigned   NOT NULL DEFAULT '0' after `owner`, 
	CHANGE `ratingsum` `ratingsum` int(10)   NOT NULL DEFAULT '0' after `orig_owner`, 
	CHANGE `free` `free` tinyint(1)   NOT NULL DEFAULT '0' after `ratingsum`, 
	CHANGE `sticky` `sticky` tinyint(1)   NOT NULL DEFAULT '0' after `free`, 
	CHANGE `moderated` `moderated` tinyint(1)   NOT NULL DEFAULT '0' after `sticky`, 
	ADD COLUMN `modcomm` text  COLLATE cp1251_general_ci NOT NULL after `moderated`, 
	CHANGE `moderatedby` `moderatedby` int(10) unsigned   NULL DEFAULT '0' after `modcomm`, 
	ADD COLUMN `freefor` text  COLLATE cp1251_general_ci NOT NULL after `moderatedby`, 
	ADD COLUMN `relgroup` int(5)   NOT NULL after `freefor`, 
	CHANGE `topic_id` `topic_id` int(10)   NOT NULL DEFAULT '0' after `relgroup`, 
	CHANGE `online` `online` text  COLLATE cp1251_general_ci NOT NULL after `topic_id`;

/* Create table in target */
CREATE TABLE `trackers`(
	`torrent` int(10) unsigned NOT NULL  , 
	`tracker` varchar(255) COLLATE cp1251_general_ci NOT NULL  DEFAULT 'localhost' , 
	`seeders` int(5) unsigned NOT NULL  DEFAULT '0' , 
	`leechers` int(5) unsigned NOT NULL  DEFAULT '0' , 
	`lastchecked` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`state` varchar(300) COLLATE cp1251_general_ci NOT NULL  , 
	UNIQUE KEY `torrent`(`torrent`,`tracker`) 
) ENGINE=MyISAM DEFAULT CHARSET='cp1251';


/* Alter table in target */
ALTER TABLE `users` 
	ADD COLUMN `pron` tinyint(1)   NOT NULL DEFAULT '0' after `acceptpms`, 
	CHANGE `ip` `ip` varchar(15)  COLLATE cp1251_general_ci NOT NULL after `pron`, 
	CHANGE `class` `class` tinyint(3) unsigned   NOT NULL DEFAULT '0' after `ip`, 
	CHANGE `override_class` `override_class` tinyint(3) unsigned   NOT NULL DEFAULT '255' after `class`, 
	CHANGE `supportfor` `supportfor` text  COLLATE cp1251_general_ci NULL after `override_class`, 
	CHANGE `title` `title` varchar(30)  COLLATE cp1251_general_ci NOT NULL after `downloaded`, 
	CHANGE `country` `country` int(10) unsigned   NOT NULL DEFAULT '0' after `title`, 
	CHANGE `notifs` `notifs` varchar(1000)  COLLATE cp1251_general_ci NOT NULL after `country`, 
	ADD COLUMN `emailnotifs` varchar(1000)  COLLATE cp1251_general_ci NOT NULL after `notifs`, 
	CHANGE `modcomment` `modcomment` text  COLLATE cp1251_general_ci NULL after `emailnotifs`, 
	ADD COLUMN `timezone` int(2)   NOT NULL DEFAULT '0' after `dis_reason`, 
	CHANGE `avatars` `avatars` tinyint(1)   NOT NULL DEFAULT '1' after `timezone`, 
	ADD COLUMN `status` varchar(255)  COLLATE cp1251_general_ci NOT NULL after `num_warned`, 
	ADD COLUMN `last_downloaded` int(10)   NOT NULL DEFAULT '0' after `status`, 
	ADD COLUMN `last_checked` int(10)   NOT NULL DEFAULT '0' after `last_downloaded`, 
	ADD COLUMN `discount` int(5)   NOT NULL DEFAULT '0' after `last_checked`, 
	DROP COLUMN `support`, 
	DROP COLUMN `bonus`, 
	DROP COLUMN `parked`, 
	DROP KEY `endis_reason`, 
	ADD KEY `passkey`(`passkey`), COMMENT='';
	
	DELETE FROM cache_stats WHERE cache_name='announce_urls';
	DELETE FROM cache_stats WHERE cache_name='points_per_hour';
	DELETE FROM cache_stats WHERE cache_name='upload_per_invite';
	INSERT INTO `cache_stats` VALUES ('default_emailnotifs', 'unread,torrents,friends');
INSERT INTO `cache_stats` VALUES ('default_notifs', 'unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends');
 INSERT INTO `cache_stats` VALUES ('ipb_password_priority', '0');
INSERT INTO `cache_stats` VALUES ('pron_cats', '0');
INSERT INTO `cache_stats` VALUES ('register_timezone', '3');
INSERT INTO `cache_stats` VALUES ('use_kinopoisk_trailers', '1');
INSERT INTO `cron` VALUES ('announce_interval', 15);
INSERT INTO `cron` VALUES ('autoclean_interval', 900);
INSERT INTO `cron` VALUES ('delete_votes', 1440);
INSERT INTO `cron` VALUES ('in_cleanup', 0);
INSERT INTO `cron` VALUES ('in_remotecheck', 0);
INSERT INTO `cron` VALUES ('last_cleanup', 0);
INSERT INTO `cron` VALUES ('last_remotecheck', 0);
INSERT INTO `cron` VALUES ('max_dead_torrent_time', 744);
INSERT INTO `cron` VALUES ('num_checked', 0);
INSERT INTO `cron` VALUES ('num_cleaned', 0);
INSERT INTO `cron` VALUES ('pm_delete_sys_days', 3);
INSERT INTO `cron` VALUES ('pm_delete_user_days', 15);
INSERT INTO `cron` VALUES ('promote_rating', 50);
INSERT INTO `cron` VALUES ('rating_checktime', 180);
INSERT INTO `cron` VALUES ('rating_discounttorrent', 1);
INSERT INTO `cron` VALUES ('rating_dislimit', -200);
INSERT INTO `cron` VALUES ('rating_downlimit', -10);
INSERT INTO `cron` VALUES ('rating_enabled', 1);
INSERT INTO `cron` VALUES ('rating_freetime', 7);
INSERT INTO `cron` VALUES ('rating_max', 300);
INSERT INTO `cron` VALUES ('rating_perdownload', 1);
INSERT INTO `cron` VALUES ('rating_perleech', 1);
INSERT INTO `cron` VALUES ('rating_perrelease', 5);
INSERT INTO `cron` VALUES ('rating_perseed', 1);
INSERT INTO `cron` VALUES ('remotecheck_disabled', 0);
INSERT INTO `cron` VALUES ('remotepeers_cleantime', 10800);
INSERT INTO cron (cron_name,cron_value) VALUES ('remote_trackers',50);
INSERT INTO `cron` VALUES ('signup_timeout', 5);
INSERT INTO `cron` VALUES ('ttl_days', 100);
INSERT INTO `cron` VALUES ('rating_perrequest', 10);
INSERT INTO `cron` VALUES ('rating_perinvite', 5);
INSERT INTO `cron` VALUES ('remotecheck_interval', 60);
ALTER TABLE `peers`
  DROP `class`;
  ALTER TABLE `bans` ADD `user` INT( 11 ) NOT NULL ,
ADD `added` INT( 11 ) NOT NULL ;
UPDATE users SET language='ru';
ALTER table bans ADD descr VARCHAR (255) NULL ;
update users set notifs='unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends', emailnotifs='unread,torrents,friends';