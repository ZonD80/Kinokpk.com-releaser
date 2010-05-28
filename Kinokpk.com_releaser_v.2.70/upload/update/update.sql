/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE  `blocks`;

ALTER TABLE  `categories`  MODIFY COLUMN  `name` varchar(255) NOT NULL  AFTER `sort`;

ALTER TABLE  `categories`  ADD COLUMN  `parent_id` int(10) NOT NULL default '0'  AFTER `image`;

ALTER TABLE  `categories`  ADD COLUMN  `forum_id` smallint(5) NOT NULL default '0'  AFTER `parent_id`;

ALTER TABLE  `categories`  ADD COLUMN  `disable_export` tinyint(1) NOT NULL default '0'  AFTER `forum_id`;

ALTER TABLE  `comments`  DROP COLUMN  `ori_text`;

ALTER TABLE  `comments`  ADD COLUMN  `ratingsum` int(5) NOT NULL default '0'  AFTER `editedat`;

CREATE TABLE `cron` (
  `cron_name` varchar(300) NOT NULL,
  `cron_value` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cron_name`)
) ENGINE=MyISAM ;

INSERT INTO `cron` VALUES ('last_cleanup', 0);
INSERT INTO `cron` VALUES ('last_remotecheck', 0);
INSERT INTO `cron` VALUES ('in_cleanup', 0);
INSERT INTO `cron` VALUES ('in_remotecheck', 0);
INSERT INTO `cron` VALUES ('num_cleaned', 0);
INSERT INTO `cron` VALUES ('num_checked', 0);
INSERT INTO `cron` VALUES ('cleanup_restart', 0);
INSERT INTO `cron` VALUES ('remotecheck_disabled', 0);
INSERT INTO `cron` VALUES ('remotepeers_cleantime', 1);
INSERT INTO `cron` VALUES ('autoclean_interval', 900);
INSERT INTO `cron` VALUES ('points_per_hour', 1);
INSERT INTO `cron` VALUES ('max_dead_torrent_time', 744);
INSERT INTO `cron` VALUES ('pm_delete_sys_days', 5);
INSERT INTO `cron` VALUES ('pm_delete_user_days', 30);
INSERT INTO `cron` VALUES ('signup_timeout', 3);
INSERT INTO `cron` VALUES ('ttl_days', 30);
INSERT INTO `cron` VALUES ('announce_interval', 30);
INSERT INTO `cron` VALUES ('delete_votes', 30);
INSERT INTO `cron` VALUES ('remote_torrents', 100);
INSERT INTO `cron` VALUES ('remote_lastchecked', 0);

ALTER TABLE  `friends`  ADD COLUMN  `confirmed` tinyint(1) NOT NULL default '0'  AFTER `friendid`;
ALTER TABLE  `friends`  ADD UNIQUE KEY `friendid` (`friendid`, `userid`);
ALTER TABLE  `friends`  ADD UNIQUE KEY `userid` (`userid`, `friendid`);

ALTER TABLE  `newscomments`  DROP COLUMN  `ori_text`;
ALTER TABLE  `newscomments`  ADD COLUMN  `ratingsum` int(5) NOT NULL default '0'  AFTER `editedat`;

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) NOT NULL default '0',
  `added` int(10) NOT NULL,
  `name` varchar(300) NOT NULL,
  `searchwords` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `indexed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `searchwords` (`searchwords`)
) ENGINE=MyISAM ;
ALTER TABLE `pages` AUTO_INCREMENT = 1;

TRUNCATE TABLE `peers`;

ALTER TABLE  `peers`  MODIFY COLUMN  `seeder` tinyint(1) NOT NULL default '0'  AFTER `to_go`;

ALTER TABLE  `peers`  MODIFY COLUMN  `started` int(10) NOT NULL  AFTER `seeder`;

ALTER TABLE  `peers`  MODIFY COLUMN  `last_action` int(10) NOT NULL  AFTER `started`;

ALTER TABLE  `peers`  MODIFY COLUMN  `prev_action` int(10) NOT NULL  AFTER `last_action`;

ALTER TABLE  `peers`  MODIFY COLUMN  `connectable` tinyint(1) NOT NULL default '1'  AFTER `prev_action`;

ALTER TABLE  `peers`  ADD COLUMN  `class` smallint(2) NOT NULL default '0'  AFTER `userid`;

ALTER TABLE  `pollcomments`  DROP COLUMN  `ori_text`;
ALTER TABLE  `pollcomments`  ADD COLUMN  `ratingsum` int(5) NOT NULL default '0'  AFTER `editedat`;

TRUNCATE TABLE `ratings`;

ALTER TABLE  `ratings`  DROP COLUMN  `torrent`;

ALTER TABLE  `ratings`  DROP COLUMN  `user`; 

ALTER TABLE  `ratings`  ADD COLUMN  `rid` int(10) NOT NULL  AFTER `id`;

ALTER TABLE  `ratings`  ADD COLUMN  `userid` int(10) NOT NULL  AFTER `rid`;

ALTER TABLE  `ratings`  ADD COLUMN  `type` varchar(30) NOT NULL  AFTER `userid`;

ALTER TABLE  `ratings`  MODIFY COLUMN  `added` int(10) NOT NULL default '0'  AFTER `type`;


ALTER TABLE  `ratings`  ADD UNIQUE KEY `rid` (`rid`, `userid`, `type`); 

ALTER TABLE  `reqcomments`  DROP COLUMN  `ori_text`;

ALTER TABLE  `reqcomments`  ADD COLUMN  `ratingsum` int(5) NOT NULL default '9'  AFTER `editedat`;

ALTER TABLE  `requests`  DROP COLUMN  `uploaded`;

ALTER TABLE  `requests`  DROP COLUMN  `torrentid`;

CREATE TABLE `retrackers` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `sort` int(3) NOT NULL default '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;
ALTER TABLE `retrackers` AUTO_INCREMENT = 1;

DROP TABLE  `simpaty`;

DROP TABLE  `thanks`;

ALTER TABLE  `torrents`  DROP COLUMN  `save_as`;

ALTER TABLE  `torrents`  DROP COLUMN  `search_text`;
                                              
ALTER TABLE  `torrents`  DROP COLUMN  `descr_type`;
                                                  

ALTER TABLE  `torrents`  DROP COLUMN  `numratings`;


ALTER TABLE  `torrents`  ADD COLUMN  `announce_urls` text NOT NULL  AFTER `info_hash`;

ALTER TABLE  `torrents`  ADD COLUMN  `descr` text NOT NULL  AFTER `name`;

ALTER TABLE  `torrents`  MODIFY COLUMN  `category` varchar(255) NOT NULL  AFTER `images`;

ALTER TABLE  `torrents`  MODIFY COLUMN  `numfiles` int(10) unsigned NOT NULL default '1'  AFTER `type`;

ALTER TABLE  `torrents`  ADD COLUMN  `remote_leechers` int(10) NOT NULL default '0'  AFTER `seeders`;

ALTER TABLE  `torrents`  ADD COLUMN  `remote_seeders` int(10) NOT NULL default '0'  AFTER `remote_leechers`;

ALTER TABLE  `torrents`  MODIFY COLUMN  `ratingsum` int(10) default '0'  AFTER `orig_owner`;


CREATE TABLE `usercomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`userid`)
) ENGINE=MyISAM  ROW_FORMAT=DYNAMIC;
ALTER TABLE `usercomments` AUTO_INCREMENT = 1;

ALTER TABLE  `users`  DROP COLUMN  `simpaty`;

ALTER TABLE  `users`  ADD COLUMN  `confirmed` tinyint(1) NOT NULL default '0'  AFTER `email`;

ALTER TABLE  `users`  ADD COLUMN  `ratingsum` int(8) NOT NULL default '0'  AFTER `info`;

ALTER TABLE  `users`  DROP KEY  `status_added`;

ALTER TABLE  `users`  DROP KEY  `user`; 

ALTER TABLE  `users`  ADD KEY `status_added` (`confirmed`, `added`);

ALTER TABLE  `users`  ADD KEY `user` (`id`, `confirmed`, `enabled`);

TRUNCATE TABLE `sitelog`;

ALTER TABLE  `sitelog`  MODIFY COLUMN  `added` int(10) default NULL  AFTER `id`;

ALTER TABLE  `snatched`  ADD COLUMN  `startedat` int(10) default NULL  AFTER `last_action`;

ALTER TABLE  `ratings`  DROP COLUMN  `rating`;


ALTER TABLE `messages` ADD `archived_receiver` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `archived` ;