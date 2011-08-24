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
	CHANGE `class` `class` varchar(255)  NOT NULL DEFAULT '' after `parent_id`, 
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

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `prior` int(5) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `remark` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `prior`, `name`, `style`, `remark`) VALUES
(1, 6, 'Owner', '', 'sysop'),
(2, 5, 'Administrator', '', ''),
(3, 4, 'Moderator', '', ''),
(4, 3, 'Releaser', '', 'uploader'),
(5, 2, 'VIP', '', 'vip'),
(6, 1, 'Power user', '', 'rating'),
(7, 0, 'User', '', 'reg'),
(8, -1, 'Guest', '', 'guest');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE IF NOT EXISTS `privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `classes_allowed` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `name`, `classes_allowed`, `description`) VALUES
(1, 'is_owner', '1', 'You are owner'),
(2, 'is_administrator', '2,1', 'You are administartor'),
(3, 'is_moderator', '3,2,1', 'You have moderation rights'),
(4, 'is_releaser', '4,3,2,1', 'You have releaser''s rights'),
(5, 'is_vip', '5,4,3,2,1', 'You have a VIP account'),
(6, 'is_power_user', '6,5,4,3,2,1', 'You are power user'),
(7, 'is_user', '7,6,5,4,3,2,1', 'You are registered user'),
(8, 'is_guest', '8,7,6,5,4,3,2,1', 'You are guest'),
(9, 'debug_template', '1', 'View teplate debugging information'),
(10, 'deny_disabled_site', '3,4,5,6,7', 'Deny form viewing disabled site'),
(11, 'view_disabled_site_notice', '1,2,3,4,5,6,7', 'View disabled site notice'),
(12, 'view_sql_debug', '1', 'View SQL debug information'),
(13, 'access_to_private_relgroups', '1,2,3', 'Access to private relgroups'),
(14, 'access_to_admincp', '1,2,3', 'Access to admin control panel'),
(15, 'access_to_ban_emails', '1,2', 'Access to email bans administration'),
(16, 'add_users', '1,2', 'Ability to add new users to site manually'),
(17, 'bans_admin', '1,2,3,4', 'Ability to ban user accounts'),
(18, 'blocksadmin', '1', 'Ability to administer blocks'),
(19, 'category_admin', '1', 'Ability to administer release categories'),
(20, 'clear_caches', '1,2', 'Ability to clear releaser cache'),
(21, 'edit_comments', '1,2,3', 'Ability to edit site comments'),
(22, 'edit_general_configuration', '1', 'Edit general releaser configuration'),
(23, 'edit_countries', '1', 'Edit registered user countries'),
(24, 'cronadmin', '1', 'Edit scheduled jobs configuration'),
(25, 'edit_dchubs', '1', 'Edit Direct-Connect Hubs configuration'),
(26, 'delete_site_users', '1,2', 'Ability to delete site users'),
(27, 'delete_releases', '1,2,3,4', 'Ability to delete releases from site'),
(28, 'edit_releases', '1,2,3,4', 'Ability to edit releases'),
(29, 'send_emails', '1,2,3', 'Ability to send emails'),
(30, 'edit_forum_settings', '1', 'Ability to administer forum in admin panel'),
(31, 'approve_invites', '1,2,3', 'Ability to approve other user''s invites'),
(32, 'add_invites', '1,2', 'Add new invites count to user'),
(33, 'view_duplicate_ip', '1,2,3', 'View dupliate ip information'),
(34, 'langadmin', '1', 'Access to language administration panel'),
(35, 'view_logs', '1,2,3', 'Ability to view site logs'),
(36, 'truncate_logs', '1', 'Ability to delete site logs'),
(37, 'view_pms', '1', 'Ability to view other user PMs'),
(38, 'mass_pm', '1,2', 'Ability to send mass PMs'),
(39, 'edit_users', '1,2,3', 'Ability to change user account details'),
(40, 'ownsupport', '1,2,3', 'Ability do add yourself to support desk'),
(41, 'add_comments_to_user', '1,2,3', 'Ability to add moderation comments to users'),
(42, 'view_sql_stats', '1', 'Ability to view SQL database statistics'),
(43, 'news_operation', '1,2', 'Ability to preform operations with site news'),
(44, 'change_user_passwords', '1,2', 'Ability to change user passwords'),
(45, 'polls_operation', '1,2', 'Ability to administer polls'),
(46, 'recountadmin', '1', 'Access to synchronization panel'),
(47, 'edit_relgroups', '1,2', 'Ability to edit release groups'),
(48, 'edit_release_templates', '1,2,3', 'Access to release templates administration'),
(49, 'requests_operation', '1,2,3', 'Ability to magange release requests'),
(50, 'edit_retrackers', '1', 'Access to retracker administration panel'),
(51, 'relgroups_admin', '1,2,3', 'Access to release groups administration panel'),
(52, 'seo_admincp', '1', 'Access to SEO-friedly URL administration panel'),
(53, 'spamadmin', '1', 'Access to private message viewer (administration)'),
(54, 'stampadmin', '1,2,3', 'Access to stamps administration panel'),
(55, 'view_general_statistics', '1', 'Ability to view general site statistics'),
(56, 'edit_site_templates', '1', 'Access to site templates administration panel'),
(57, 'view_private_user_profiles', '1,2,3', 'Ability to view private user profiles'),
(58, 'censored_admin', '1,2,3', 'Ability to administrate censored releases'),
(59, 'post_releases_approved', '1,2,3,4', 'Ability to post automatically approved releases'),
(60, 'upload_releases', '1,2,3,4,5,6,7', 'Ability to upload new releases to site'),
(61, 'edit_user_privileges', '1,2,3', 'Ability to edit privileges given to custom users'),
(62, 'access_to_privadmincp', '1' , 'Access to privileges administration panel'),
(63, 'access_to_classadmin', '1' , 'Access to classes administration panel');

CREATE TABLE IF NOT EXISTS `nickhistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `nick` varchar(255) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

ALTER TABLE  `users` ADD  `custom_privileges` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `users` ADD UNIQUE (
`email`
);


ALTER TABLE  `classes` ADD UNIQUE (
`prior`
);

DELETE FROM `snt_tracker`.`cache_stats` WHERE `cache_stats`.`cache_name` = 'announce_interval';
DELETE FROM `snt_tracker`.`cache_stats` WHERE `cache_stats`.`cache_name` = 'announce_packed';
