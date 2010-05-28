/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

ALTER TABLE  `checkcomm`  DROP COLUMN  `offer`;

ALTER TABLE  `comments`  DROP COLUMN  `offer`;

ALTER TABLE  `descr_types`  MODIFY COLUMN  `type` varchar(255) default NULL  AFTER `id`;

CREATE TABLE `pollcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `poll` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `poll` (`poll`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `pollcomments` AUTO_INCREMENT = 1;

CREATE TABLE `reqcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `request` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`request`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;
ALTER TABLE `reqcomments` AUTO_INCREMENT = 1;

ALTER TABLE  `users`  ADD COLUMN  `extra_ef` enum('yes','no') NOT NULL default 'yes'  AFTER `avatars`;

INSERT INTO `cache_stats` VALUES ('maxusers', '10000');

INSERT INTO `cache_stats` VALUES ('max_dead_torrent_time', '744');
INSERT INTO `cache_stats` VALUES ('minvotes', '1');
INSERT INTO `cache_stats` VALUES ('signup_timeout', '3');
INSERT INTO `cache_stats` VALUES ('announce_interval', '15');
INSERT INTO `cache_stats` VALUES ('max_torrent_size', '1000000');
INSERT INTO `cache_stats` VALUES ('defaultbaseurl', 'http://localhost');
INSERT INTO `cache_stats` VALUES ('siteemail', 'webmaster@localhost');
INSERT INTO `cache_stats` VALUES ('adminemail', 'webmaster@localhost');
INSERT INTO `cache_stats` VALUES ('sitename', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('description', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('keywords', 'Kinokpk.com, releaser, ZonD80');
INSERT INTO `cache_stats` VALUES ('autoclean_interval', '900');
INSERT INTO `cache_stats` VALUES ('yourcopy', '© {datenow} Your copyright');
INSERT INTO `cache_stats` VALUES ('pm_delete_sys_days', '5');
INSERT INTO `cache_stats` VALUES ('pm_delete_user_days', '30');
INSERT INTO `cache_stats` VALUES ('pm_max', '100');
INSERT INTO `cache_stats` VALUES ('ttl_days', '28');
INSERT INTO `cache_stats` VALUES ('default_language', 'russian');
INSERT INTO `cache_stats` VALUES ('avatar_max_width', '120');
INSERT INTO `cache_stats` VALUES ('avatar_max_height', '120');
INSERT INTO `cache_stats` VALUES ('points_per_hour', '5');
INSERT INTO `cache_stats` VALUES ('default_theme', 'kinokpk');
INSERT INTO `cache_stats` VALUES ('nc', 'no');
INSERT INTO `cache_stats` VALUES ('deny_signup', '0');
INSERT INTO `cache_stats` VALUES ('allow_invite_signup', '0');
INSERT INTO `cache_stats` VALUES ('use_ttl', '0');
INSERT INTO `cache_stats` VALUES ('use_email_act', '0');
INSERT INTO `cache_stats` VALUES ('use_wait', '0');
INSERT INTO `cache_stats` VALUES ('use_lang', '1');
INSERT INTO `cache_stats` VALUES ('use_captcha', '1');
INSERT INTO `cache_stats` VALUES ('use_blocks', '1');
INSERT INTO `cache_stats` VALUES ('use_gzip', '1');
INSERT INTO `cache_stats` VALUES ('use_ipbans', '1');
INSERT INTO `cache_stats` VALUES ('use_sessions', '1');
INSERT INTO `cache_stats` VALUES ('smtptype', 'advanced');
INSERT INTO `cache_stats` VALUES ('as_timeout', '15');
INSERT INTO `cache_stats` VALUES ('as_check_messages', '1');
INSERT INTO `cache_stats` VALUES ('use_integration', '1');
INSERT INTO `cache_stats` VALUES ('exporttype', 'wiki');
INSERT INTO `cache_stats` VALUES ('forumurl', 'http://localhost/forum');
INSERT INTO `cache_stats` VALUES ('forumname', 'Integrated Forum');
INSERT INTO `cache_stats` VALUES ('forum_bin_id', '2');
INSERT INTO `cache_stats` VALUES ('defuserclass', '3');
INSERT INTO `cache_stats` VALUES ('not_found_export_id', '2');
INSERT INTO `cache_stats` VALUES ('emo_dir', 'default');
INSERT INTO `cache_stats` VALUES ('re_publickey', 'none');
INSERT INTO `cache_stats` VALUES ('re_privatekey', 'none');
INSERT INTO `cache_stats` VALUES ('debug_mode', '0');