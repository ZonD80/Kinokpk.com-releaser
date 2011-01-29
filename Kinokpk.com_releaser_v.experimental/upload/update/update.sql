/* Alter table in target */
ALTER TABLE `addedrequests` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `bannedemails` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `bans` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `bookmarks` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `cache_stats` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `categories` 
	ADD COLUMN `seo_name` varchar(80)  COLLATE utf8_general_ci NULL after `name`, 
	CHANGE `image` `image` varchar(255)  COLLATE utf8_general_ci NOT NULL after `seo_name`, 
	CHANGE `parent_id` `parent_id` int(10)   NOT NULL DEFAULT '0' after `image`, 
	CHANGE `forum_id` `forum_id` smallint(5)   NOT NULL DEFAULT '0' after `parent_id`, 
	CHANGE `disable_export` `disable_export` tinyint(1)   NOT NULL DEFAULT '0' after `forum_id`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `censoredtorrents` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `comments` 
	ADD COLUMN `toid` int(10) unsigned   NOT NULL DEFAULT '0' after `user`;

update comments set toid=torrent;

ALTER TABLE `comments` 	 
	ADD COLUMN `type` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `toid`, 
	CHANGE `added` `added` int(10)   NOT NULL after `type`, 
	CHANGE `text` `text` text  COLLATE utf8_general_ci NOT NULL after `added`, 
	CHANGE `editedby` `editedby` int(10) unsigned   NOT NULL DEFAULT '0' after `text`, 
	CHANGE `editedat` `editedat` int(10)   NOT NULL after `editedby`, 
	CHANGE `ratingsum` `ratingsum` int(5)   NOT NULL DEFAULT '0' after `editedat`, 
	CHANGE `ip` `ip` varchar(15)  COLLATE utf8_general_ci NOT NULL after `ratingsum`, 
	DROP COLUMN `torrent`, 
	DROP COLUMN `post_id`, 
	DROP KEY `torrent`, add KEY `torrent`(`toid`), ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `countries` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `cron` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `cron_emails` 
	ADD COLUMN `emails` text  COLLATE utf8_general_ci NOT NULL first, 
	CHANGE `subject` `subject` varchar(255)  COLLATE utf8_general_ci NOT NULL after `emails`, 
	DROP COLUMN `email`, ENGINE=MyISAM; 

/* Create table in target */
CREATE TABLE `dchubs`(
	`id` int(3) unsigned NOT NULL  auto_increment , 
	`sort` int(3) NOT NULL  DEFAULT '0' , 
	`announce_url` varchar(500) COLLATE utf8_general_ci NOT NULL  , 
	`mask` varchar(60) COLLATE utf8_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='utf8';


/* Drop in Second database */
DROP TABLE `faq`; 

/* Alter table in target */
ALTER TABLE `files` ENGINE=MyISAM; 

RENAME TABLE  `pagescategories` TO  `forum_categories` ;

/* Alter table in target */
ALTER TABLE `forum_categories` 
	ADD COLUMN `seo_name` varchar(255)  COLLATE utf8_general_ci NULL after `name`, 
	CHANGE `image` `image` varchar(255)  COLLATE utf8_general_ci NOT NULL after `seo_name`, 
	CHANGE `parent_id` `parent_id` int(10)   NOT NULL DEFAULT '0' after `image`, 
	CHANGE `class` `class` int(2)   NOT NULL DEFAULT '0' after `parent_id`, 
	DROP COLUMN `class_edit`, ENGINE=MyISAM; 

/* Create table in target */
CREATE TABLE `forum_topics`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`subject` varchar(255) COLLATE utf8_general_ci NULL  , 
	`comments` int(5) unsigned NOT NULL  DEFAULT '0' , 
	`author` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`started` int(10) NOT NULL  DEFAULT '0' , 
	`lastposted_id` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`closed` tinyint(1) unsigned NOT NULL  DEFAULT '0' , 
	`closedate` int(10) NOT NULL  DEFAULT '0' , 
	`category` int(5) unsigned NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='utf8';

insert into forum_topics (id,subject,comments,author,started,category) SELECT id,name,comments,owner,added,category FROM pages;
 
/* Alter table in target */
ALTER TABLE `friends` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `invites` ENGINE=MyISAM; 

/* Create table in target */
CREATE TABLE `languages`(
	`lkey` varchar(255) COLLATE utf8_general_ci NOT NULL  , 
	`ltranslate` varchar(2) COLLATE utf8_general_ci NOT NULL  , 
	`lvalue` text COLLATE utf8_general_ci NOT NULL  , 
	UNIQUE KEY `key`(`lkey`,`ltranslate`) 
) ENGINE=MyISAM DEFAULT CHARSET='utf8';


/* Alter table in target */
ALTER TABLE `messages` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `news` 
	ADD COLUMN `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `subject`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `notifs` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `orbital_blocks` 
	CHANGE `title` `title` varchar(60)  COLLATE utf8_general_ci NOT NULL after `bid`, 
	CHANGE `content` `content` text  COLLATE utf8_general_ci NOT NULL after `title`, 
	CHANGE `bposition` `bposition` char(1)  COLLATE utf8_general_ci NOT NULL after `content`, 
	CHANGE `weight` `weight` int(10)   NOT NULL DEFAULT '1' after `bposition`, 
	CHANGE `active` `active` int(1)   NOT NULL DEFAULT '1' after `weight`, 
	CHANGE `blockfile` `blockfile` varchar(255)  COLLATE utf8_general_ci NOT NULL after `active`, 
	CHANGE `view` `view` varchar(20)  COLLATE utf8_general_ci NULL after `blockfile`, 
	CHANGE `expire` `expire` int(10)   NOT NULL DEFAULT '0' after `view`, 
	CHANGE `which` `which` varchar(255)  COLLATE utf8_general_ci NOT NULL after `expire`, 
	ADD COLUMN `custom_tpl` varchar(255)  COLLATE utf8_general_ci NULL after `which`, 
	DROP COLUMN `bkey`, 
	DROP COLUMN `time`, 
	DROP COLUMN `action`, ENGINE=MyISAM; 

/* Drop in Second database */
DROP TABLE `pages`; 

/* Alter table in target */
ALTER TABLE `peers` 
	CHANGE `peer_id` `peer_id` varchar(40)  COLLATE utf8_general_ci NOT NULL after `torrent`, 
	CHANGE `seeder` `seeder` tinyint(1)   NOT NULL DEFAULT '0' after `port`, 
	CHANGE `started` `started` int(10)   NOT NULL after `seeder`, 
	CHANGE `last_action` `last_action` int(10)   NOT NULL after `started`, 
	CHANGE `userid` `userid` int(10) unsigned   NOT NULL DEFAULT '0' after `last_action`, 
	CHANGE `finishedat` `finishedat` int(10) unsigned   NOT NULL DEFAULT '0' after `userid`, 
	DROP COLUMN `uploaded`, 
	DROP COLUMN `downloaded`, 
	DROP COLUMN `uploadoffset`, 
	DROP COLUMN `downloadoffset`, 
	DROP COLUMN `to_go`, 
	DROP COLUMN `prev_action`, 
	DROP COLUMN `connectable`, 
	DROP COLUMN `agent`, 
	DROP COLUMN `passkey`, 
	DROP KEY `connectable`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `polls` 
	ADD COLUMN `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `public`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `polls_structure` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `polls_votes` ENGINE=MyISAM; 

/* Create table in target */
CREATE TABLE `presents`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`userid` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`presenter` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`type` varchar(100) COLLATE utf8_general_ci NULL  , 
	`msg` varchar(255) COLLATE utf8_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='utf8';


/* Alter table in target */
ALTER TABLE `ratings` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `relgroups` 
	ADD COLUMN `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `subscribe_length`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `reltemplates` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `reports` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `requests` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `retrackers` 
	CHANGE `mask` `mask` text  COLLATE utf8_general_ci NOT NULL after `announce_url`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `rg_invites` 
	CHANGE `rgid` `rgid` int(5)   NOT NULL DEFAULT '0' after `inviter`, 
	CHANGE `invite` `invite` varchar(32)  COLLATE utf8_general_ci NOT NULL after `rgid`, 
	CHANGE `time_invited` `time_invited` int(10)   NOT NULL after `invite`, 
	DROP COLUMN `inviteid`, 
	DROP COLUMN `confirmed`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `rg_subscribes` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `rgnews` 
	ADD COLUMN `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `subject`, ENGINE=MyISAM; 

/* Drop in Second database */
DROP TABLE `rules`; 

/* Create table in target */
CREATE TABLE `seorules`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`script` varchar(100) COLLATE utf8_general_ci NULL  , 
	`parameter` varchar(100) COLLATE utf8_general_ci NULL  , 
	`repl` varchar(255) COLLATE utf8_general_ci NULL  , 
	`unset_params` varchar(255) COLLATE utf8_general_ci NULL  , 
	`sort` int(2) NOT NULL  DEFAULT '0' , 
	`enabled` tinyint(1) NOT NULL  DEFAULT '1' , 
	PRIMARY KEY (`id`) , 
	UNIQUE KEY `script`(`script`,`parameter`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8';


/* Alter table in target */
ALTER TABLE `sessions` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `sitelog` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `snatched` 
	CHANGE `startedat` `startedat` int(10)   NOT NULL after `torrent`, 
	CHANGE `completedat` `completedat` int(10)   NOT NULL after `startedat`, 
	CHANGE `finished` `finished` tinyint(1)   NOT NULL DEFAULT '0' after `completedat`, 
	DROP COLUMN `uploaded`, 
	DROP COLUMN `downloaded`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `stamps` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `stylesheets` ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `torrents` 
	ADD COLUMN `tiger_hash` varbinary(38)   NULL after `info_hash`, 
	CHANGE `name` `name` varchar(255)  COLLATE utf8_general_ci NOT NULL after `tiger_hash`, 
	CHANGE `descr` `descr` text  COLLATE utf8_general_ci NOT NULL after `name`, 
	CHANGE `filename` `filename` varchar(255)  COLLATE utf8_general_ci NOT NULL after `descr`, 
	CHANGE `images` `images` text  COLLATE utf8_general_ci NOT NULL after `filename`, 
	CHANGE `category` `category` varchar(255)  COLLATE utf8_general_ci NOT NULL after `images`, 
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
	CHANGE `modcomm` `modcomm` text  COLLATE utf8_general_ci NOT NULL after `moderated`, 
	CHANGE `moderatedby` `moderatedby` int(10) unsigned   NULL DEFAULT '0' after `modcomm`, 
	CHANGE `freefor` `freefor` text  COLLATE utf8_general_ci NOT NULL after `moderatedby`, 
	CHANGE `relgroup` `relgroup` int(5)   NOT NULL after `freefor`, 
	CHANGE `online` `online` text  COLLATE utf8_general_ci NOT NULL after `relgroup`, 
	DROP COLUMN `topic_id`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `trackers` 
	ADD COLUMN `method` varchar(10)  COLLATE utf8_general_ci NOT NULL DEFAULT 'local' after `state`, 
	ADD COLUMN `remote_method` varchar(10)  COLLATE utf8_general_ci NOT NULL DEFAULT 'N/A' after `method`, 
	CHANGE `num_failed` `num_failed` int(5) unsigned   NOT NULL DEFAULT '0' after `remote_method`, ENGINE=MyISAM; 

/* Alter table in target */
ALTER TABLE `users` 
	CHANGE `privacy` `privacy` enum('strong','normal','highest')  COLLATE utf8_general_ci NOT NULL DEFAULT 'normal' after `editsecret`, 
	CHANGE `supportfor` `supportfor` text  COLLATE utf8_general_ci NULL after `class`, 
	CHANGE `avatar` `avatar` varchar(100)  COLLATE utf8_general_ci NOT NULL after `supportfor`, 
	CHANGE `icq` `icq` varchar(255)  COLLATE utf8_general_ci NOT NULL after `avatar`, 
	CHANGE `msn` `msn` varchar(255)  COLLATE utf8_general_ci NOT NULL after `icq`, 
	CHANGE `aim` `aim` varchar(255)  COLLATE utf8_general_ci NOT NULL after `msn`, 
	CHANGE `yahoo` `yahoo` varchar(255)  COLLATE utf8_general_ci NOT NULL after `aim`, 
	CHANGE `skype` `skype` varchar(255)  COLLATE utf8_general_ci NOT NULL after `yahoo`, 
	CHANGE `mirc` `mirc` varchar(255)  COLLATE utf8_general_ci NOT NULL after `skype`, 
	CHANGE `website` `website` varchar(50)  COLLATE utf8_general_ci NOT NULL after `mirc`, 
	CHANGE `title` `title` varchar(30)  COLLATE utf8_general_ci NOT NULL after `website`, 
	CHANGE `country` `country` int(10) unsigned   NOT NULL DEFAULT '0' after `title`, 
	CHANGE `notifs` `notifs` varchar(1000)  COLLATE utf8_general_ci NOT NULL after `country`, 
	CHANGE `emailnotifs` `emailnotifs` varchar(1000)  COLLATE utf8_general_ci NOT NULL after `notifs`, 
	CHANGE `modcomment` `modcomment` text  COLLATE utf8_general_ci NULL after `emailnotifs`, 
	CHANGE `enabled` `enabled` tinyint(1)   NOT NULL DEFAULT '1' after `modcomment`, 
	CHANGE `dis_reason` `dis_reason` text  COLLATE utf8_general_ci NOT NULL after `enabled`, 
	CHANGE `timezone` `timezone` int(2)   NOT NULL DEFAULT '0' after `dis_reason`, 
	CHANGE `avatars` `avatars` tinyint(1)   NOT NULL DEFAULT '1' after `timezone`, 
	CHANGE `extra_ef` `extra_ef` tinyint(1)   NOT NULL DEFAULT '1' after `avatars`, 
	CHANGE `donor` `donor` tinyint(1)   NULL DEFAULT '0' after `extra_ef`, 
	CHANGE `warned` `warned` tinyint(1)   NULL DEFAULT '0' after `donor`, 
	CHANGE `warneduntil` `warneduntil` int(10)   NOT NULL after `warned`, 
	CHANGE `deletepms` `deletepms` tinyint(1)   NULL DEFAULT '0' after `warneduntil`, 
	CHANGE `savepms` `savepms` tinyint(1)   NOT NULL DEFAULT '1' after `deletepms`, 
	CHANGE `gender` `gender` smallint(1)   NOT NULL DEFAULT '0' after `savepms`, 
	CHANGE `birthday` `birthday` date   NULL DEFAULT '0000-00-00' after `gender`, 
	CHANGE `passkey` `passkey` varchar(32)  COLLATE utf8_general_ci NOT NULL after `birthday`, 
	CHANGE `language` `language` varchar(255)  COLLATE utf8_general_ci NULL after `passkey`, 
	CHANGE `invites` `invites` int(10)   NOT NULL DEFAULT '0' after `language`, 
	CHANGE `invitedby` `invitedby` int(10)   NOT NULL DEFAULT '0' after `invites`, 
	CHANGE `invitedroot` `invitedroot` int(10)   NOT NULL DEFAULT '0' after `invitedby`, 
	CHANGE `passkey_ip` `passkey_ip` varchar(15)  COLLATE utf8_general_ci NOT NULL after `invitedroot`, 
	CHANGE `num_warned` `num_warned` int(2)   NOT NULL DEFAULT '0' after `passkey_ip`, 
	CHANGE `status` `status` varchar(255)  COLLATE utf8_general_ci NOT NULL after `num_warned`, 
	CHANGE `last_downloaded` `last_downloaded` int(10)   NOT NULL DEFAULT '0' after `status`, 
	CHANGE `last_checked` `last_checked` int(10)   NOT NULL DEFAULT '0' after `last_downloaded`, 
	ADD COLUMN `last_announced` int(10)   NOT NULL DEFAULT '0' after `last_checked`, 
	CHANGE `discount` `discount` int(5)   NOT NULL DEFAULT '0' after `last_announced`, 
	ADD COLUMN `viptill` int(10)   NOT NULL DEFAULT '0' after `discount`, 
	ADD COLUMN `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `viptill`, 
	DROP COLUMN `override_class`, 
	DROP COLUMN `uploaded`, 
	DROP COLUMN `downloaded`, 
	DROP KEY `downloaded`, 
	DROP KEY `uploaded`, ENGINE=MyISAM; 
	
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'defuserclass';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'emo_dir';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'exporttype';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'forumname';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'forumurl';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'forum_bin_id';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'ipb_cookie_prefix';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'ipb_password_priority';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'not_found_export_id';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'use_integration';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` = 'use_lang';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` =  'use_sessions';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` =  'use_wait';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` =  'smtptype';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` =  'default_emailnotifs';
DELETE FROM `cache_stats` WHERE `cache_stats`.`cache_name` =  'default_notifs';

update orbital_blocks set bposition='t' where bposition='c';
update news set comments=(SELECT COUNT(*) FROM comments WHERE type='news' AND toid=news.id) where id=id;
update comments set type='rel' where type='' or type=null;
update notifs set type='relcomments' where type='comments';
insert into cache_stats (cache_name,cache_value) VALUES ('site_timezone',3);
insert into cache_stats (cache_name,cache_value) VALUES ('static_language','');
insert into cron (cron_name,cron_value) VALUES ('cron_is_native',1);
truncate table stylesheets;
insert into stylesheets (uri,name) VALUES ('releaser330','Kinokpk.com releaser 3.30 original');
update users set stylesheet=1;
update cache_stats set cache_value='releaser330' where cache_name='default_theme';
update orbital_blocks set which='' where which='all';
INSERT INTO `cache_stats` (`cache_name`, `cache_value`) VALUES
('cache_template', '0'),
('cache_template_time', '100'),
('debug_language', '0'),
('debug_template', '0'),
('default_emailnotifs', 'unread,torrents,friends'),
('default_notifs', 'unread,torrents,relcomments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends'),
('forum_enabled', '1'),
('low_comment_hide', '-3'),
('sign_length', '250'),
('use_dc',0);