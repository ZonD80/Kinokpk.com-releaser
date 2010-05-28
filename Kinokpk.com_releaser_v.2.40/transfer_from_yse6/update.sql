/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE `addedrequests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `requestid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `addedrequests` AUTO_INCREMENT = 1;

DROP TABLE  `avps`;

CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `bannedemails` AUTO_INCREMENT = 1;

ALTER TABLE  `bans`  DROP COLUMN  `added`;

ALTER TABLE  `bans`  DROP COLUMN  `addedby`;

ALTER TABLE  `bans`  DROP COLUMN  `comment`;

ALTER TABLE  `bans`  DROP KEY  `first_last`;

ALTER TABLE  `bans`  DROP COLUMN  `first`;

ALTER TABLE  `bans`  DROP COLUMN  `last`;

ALTER TABLE  `bans`  MODIFY COLUMN  `id` int(11) unsigned NOT NULL auto_increment;

ALTER TABLE  `bans`  ADD COLUMN  `mask` varchar(60) NOT NULL  AFTER `id`;

CREATE TABLE `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` mediumtext,
  PRIMARY KEY  (`cache_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

DROP TABLE  `captcha`;

CREATE TABLE `censoredtorrents` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`,`reason`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `censoredtorrents` AUTO_INCREMENT = 1;

ALTER TABLE  `checkcomm`  ADD COLUMN  `req` tinyint(4) NOT NULL default '0'  AFTER `torrent`;

ALTER TABLE  `comments`  ADD COLUMN  `post_id` int(10) NOT NULL default '0'  AFTER `ip`;


CREATE TABLE `descr_torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) NOT NULL,
  `typeid` varchar(30) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent` (`torrent`,`typeid`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251;
ALTER TABLE `descr_torrents` AUTO_INCREMENT = 1;

DROP TABLE  `indexreleases`;

ALTER TABLE  `messages`  ADD COLUMN  `archived` enum('yes','no') NOT NULL default 'no'  AFTER `saved`;

ALTER TABLE  `messages`  ADD COLUMN  `spamid` int(10) NOT NULL default '0'  AFTER `archived`;

CREATE TABLE `newscomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `news` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`news`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;
ALTER TABLE `newscomments` AUTO_INCREMENT = 1;

DROP TABLE  `pollanswers`;

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

ALTER TABLE  `polls`  DROP COLUMN  `added`;

ALTER TABLE  `polls`  DROP COLUMN  `option0`;

ALTER TABLE  `polls`  DROP COLUMN  `option1`;

ALTER TABLE  `polls`  DROP COLUMN  `option2`;

ALTER TABLE  `polls`  DROP COLUMN  `option3`;

ALTER TABLE  `polls`  DROP COLUMN  `option4`;

ALTER TABLE  `polls`  DROP COLUMN  `option5`;

ALTER TABLE  `polls`  DROP COLUMN  `option6`;

ALTER TABLE  `polls`  DROP COLUMN  `option7`;

ALTER TABLE  `polls`  DROP COLUMN  `option8`;

ALTER TABLE  `polls`  DROP COLUMN  `option9`;

ALTER TABLE  `polls`  DROP COLUMN  `option10`;

ALTER TABLE  `polls`  DROP COLUMN  `option11`;

ALTER TABLE  `polls`  DROP COLUMN  `option12`;

ALTER TABLE  `polls`  DROP COLUMN  `option13`;

ALTER TABLE  `polls`  DROP COLUMN  `option14`;

ALTER TABLE  `polls`  DROP COLUMN  `option15`;

ALTER TABLE  `polls`  DROP COLUMN  `option16`;

ALTER TABLE  `polls`  DROP COLUMN  `option17`;

ALTER TABLE  `polls`  DROP COLUMN  `option18`;

ALTER TABLE  `polls`  DROP COLUMN  `option19`;

ALTER TABLE  `polls`  DROP COLUMN  `sort`;

ALTER TABLE  `polls`  MODIFY COLUMN  `question` varchar(255) NOT NULL  AFTER `id`;

ALTER TABLE  `polls`  ADD COLUMN  `start` int(10) NOT NULL  AFTER `question`;

ALTER TABLE  `polls`  ADD COLUMN  `exp` int(10) default NULL  AFTER `start`;

ALTER TABLE  `polls`  ADD COLUMN  `public` enum('yes','no') NOT NULL default 'no'  AFTER `exp`;

CREATE TABLE `polls_structure` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `polls_structure` AUTO_INCREMENT = 1;

CREATE TABLE `polls_votes` (
  `vid` int(10) unsigned NOT NULL auto_increment,
  `sid` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY  (`vid`),
  UNIQUE KEY `sid` (`sid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `polls_votes` AUTO_INCREMENT = 1;

DROP TABLE  `readtorrents`;

CREATE TABLE `report` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `motive` varchar(255) NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `report` AUTO_INCREMENT = 1;

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

CREATE TABLE `requests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `request` varchar(225) default NULL,
  `descr` text NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL default '0',
  `uploaded` enum('yes','no') NOT NULL default 'no',
  `filled` varchar(200) NOT NULL default '',
  `torrentid` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `cat` int(10) unsigned NOT NULL default '0',
  `filledby` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `requests` AUTO_INCREMENT = 1;

CREATE TABLE `stamps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `class` tinyint(3) NOT NULL default '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `image` (`image`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
ALTER TABLE `stamps` AUTO_INCREMENT = 1;

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `howmuch` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251;
ALTER TABLE `tags` AUTO_INCREMENT = 1;

ALTER TABLE  `thanks`  ADD UNIQUE KEY `torrentid` (`torrentid`, `userid`);

ALTER TABLE  `torrents`  ADD COLUMN  `descr_type` int(10) NOT NULL  AFTER `search_text`;

ALTER TABLE  `torrents`  ADD COLUMN  `orig_owner` int(10) unsigned NOT NULL default '0'  AFTER `owner`;

ALTER TABLE  `torrents`  ADD COLUMN  `topic_id` int(10) NOT NULL default '0'  AFTER `moderatedby`;

ALTER TABLE  `torrents`  ADD COLUMN  `online` varchar(255) NOT NULL  AFTER `topic_id`;

ALTER TABLE  `torrents`  ADD COLUMN  `tags` text NOT NULL  AFTER `online`;

ALTER TABLE  `torrents`  DROP KEY  `ft_search`;

ALTER TABLE  `users`  DROP COLUMN  `topicsperpage`;

ALTER TABLE  `users`  DROP COLUMN  `postsperpage`;

ALTER TABLE  `users`  ADD COLUMN  `dis_reason` text NOT NULL  AFTER `enabled`;

ALTER TABLE  `users`  ADD COLUMN  `extra_ef` enum('yes','no') NOT NULL default 'yes'  AFTER `avatars`;

ALTER TABLE  `users`  MODIFY COLUMN  `language` varchar(255) default NULL  AFTER `passkey`;

ALTER TABLE  `users`  ADD COLUMN  `num_warned` int(2) NOT NULL default '0'  AFTER `passkey_ip`;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=8 ;

INSERT INTO `categories` VALUES (1, 1, 'Видео', '1.png');
INSERT INTO `categories` VALUES (2, 2, 'Музыка', '2.png');
INSERT INTO `categories` VALUES (3, 3, 'Игры', '3.png');
INSERT INTO `categories` VALUES (4, 4, 'Программы', '4.png');
INSERT INTO `categories` VALUES (5, 5, 'WEB', '5.png');
INSERT INTO `categories` VALUES (6, 6, 'Библиотека', '6.png');
INSERT INTO `categories` VALUES (7, 7, 'Прочее', '7.png');

DROP TABLE IF EXISTS `descr_details`;
CREATE TABLE `descr_details` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) default NULL,
  `sort` int(3) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `description` text NOT NULL,
  `input` enum('text','bbcode','option','links') default 'text',
  `size` int(3) NOT NULL default '40',
  `isnumeric` enum('yes','no') default 'no',
  `required` enum('yes','no') NOT NULL default 'no',
  `mask` text,
  `spoiler` enum('yes','no') default 'no',
  `hide` enum('yes','no') default 'no',
  `mainpage` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM AUTO_INCREMENT=249 DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=249 ;

-- 
-- Дамп данных таблицы `descr_details`
-- 

INSERT INTO `descr_details` VALUES (39, 4, 1, 'Количество композиций', 'Количество песен в альбоме', 'text', 2, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (40, 4, 2, 'Год выхода альбома', 'Год, когда альбом увидел свет.', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (2, 1, 2, 'Год выхода', 'Год выхода фильма на экраны.', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (3, 1, 3, 'Режиссер', 'Режиссер фильма', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (4, 1, 4, 'В ролях', 'Актеры, учавствующие в фильме', 'text', 100, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (5, 1, 5, 'Кем выпущено', 'Место производства/съемки фильма (страна).', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (6, 1, 6, 'Продолжительность', 'Продолжительность фильма в формате Ч:ММ:СС', 'text', 6, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (7, 1, 7, 'Перевод', 'Язык, на который переведен фильм.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (9, 1, 9, 'Рейтинг IMDB', 'Рейтинг сайта IMDB в формате: рейтинг (кол-во голосов). Пример: 9.0/10 (366) <br/><a target="_blank" href="http://www.imdb.com">Перейти на IMDb</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (10, 1, 10, 'Рейтинг кинопоиска', 'Рейтинг российского сайта Кинопоиск.ру в формате: рейтинг (кол-во голосов). Пример: 5.143 (12)<br/><a target="_blank" href="http://www.kinopoisk.ru">Перейти на Kinopoisk</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (11, 1, 11, 'Рейтинг МРАА<br /><a target="_blank" href="mpaafaq.php">подробнее тут</a>', 'G - Нет возрастных ограничений,PG - Рекомендуется присутствие родителей,PG-13 - Детям до 13 лет просмотр не желателен,R - Лицам до 17 лет обязательно присутствие взрослого,NC-17 - Лицам до 17 лет просмотр запрещен', 'option', 0, 'no', 'no', '[img][siteurl]/pic/mpaa/G.gif[/img] G - Нет возрастных ограничений,[img][siteurl]/pic/mpaa/PG.gif[/img] PG - Рекомендуется присутствие родителей,[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - Детям до 13 лет просмотр не желателен,[img][siteurl]/pic/mpaa/R.gif[/img] R - Лицам до 17 лет обязательно присутствие взрослого,[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - Лицам до 17 лет просмотр запрещен', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (12, 1, 13, 'Cсылки', 'Если фильм доступен для скачивания на файлообменниках, вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (13, 1, 14, 'Смотреть онлайн', 'Если фильм доступен для просмотра онлайн, то вставьте сюда соответствующую ссылку.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (14, 1, 15, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (15, 1, 16, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (16, 1, 17, 'Формат файла', 'Например, AVI', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (17, 1, 18, 'Качество исходника', '', 'option', 0, 'no', 'yes', 'Screener,SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (18, 1, 12, 'Описание фильма', '', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (19, 2, 1, 'Канал', 'Канал, который эту программу показывал', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (20, 2, 2, 'Год выхода программы', 'Год показа программы по ТВ', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (21, 2, 3, 'Ведущий', 'Ведущий программы, возможно ассистенты.', 'text', 60, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (22, 2, 4, 'Описание', 'Описание сериала и серий в частности, рекомендуем использовать тег <b>spolier</b> для скрытия больших объёмов текста.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (23, 2, 6, 'Ссылки', 'Если программа доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (24, 2, 7, 'Смотреть онлайн', 'Если программа доступна для просмотра онлайн, то вставьте сюда соответствующую ссылку.', 'links', 0, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (25, 3, 1, 'Место проведения концерта', 'Место, где выступала та или иная группа', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (26, 3, 2, 'Год проведения концерта', 'Год проведения концерта.', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (27, 2, 5, 'Перевод', 'Язык, на который переведёна программа. Если программа на исходном языке, заполняется "Оригинал".', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (28, 3, 3, 'Жанр', 'Жанр концерта, например: Punk, Hip-Hop, попса:)', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (47, 5, 3, 'Треклист/Описание', 'Треклист/описание каждого альбома. Советуем скрывать треклисты каждого альбома тегом <b>spoiler</b><br/>Например: [spoiler=Black album (2008)]<br/>1...<br/>2...<br/>[/spoiler].', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (29, 3, 4, 'Треклист/Описание', 'Треклист (или песни, которые звучали на концерте)', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (46, 5, 2, 'Количестов альбомов', 'Количество альбомов в дискографии', 'text', 2, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (30, 3, 5, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (31, 3, 6, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (45, 5, 1, 'Время дискографии', 'Промежуток между первым и последним альбомом дискографии. Например, 1990-2008', 'text', 9, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (42, 4, 3, 'Треклист/Описание', 'Треклист (или песни, которые включены в альбом)', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (43, 4, 4, 'Аудио', '   Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (44, 4, 5, 'Ссылки', 'Если альбом доступен для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (32, 3, 7, 'Формат файла', 'Например, AVI', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (33, 3, 9, 'Ссылки', 'Если концерт доступен для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (34, 3, 10, 'Смотреть онлайн', 'Если программа доступна для просмотра онлайн, то вставьте сюда соответствующую ссылку.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (35, 2, 8, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (36, 2, 9, 'Аудио', '   Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (37, 2, 10, 'Формат файла', 'Формат файлов, например AVI.', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (38, 3, 8, 'Качество исходника', '', 'option', 0, 'no', 'yes', 'Screener,SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (48, 5, 4, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (49, 5, 5, 'Ссылки', '   Если альбом доступен для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (50, 6, 1, 'Год выхода игры', 'Год выхода игры на PC', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (51, 6, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows 3.11+,Windows 95+,Windows 98+,Windows Millenium+,Windows NT+,Windows 2000+,Windows XP+,Windows Vista+', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (52, 6, 4, 'Системные требования', 'Минимальные системные требования для игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (53, 6, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (54, 6, 2, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (55, 6, 3, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (56, 6, 7, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (57, 7, 1, 'Год выхода игры', 'Год выхода игры на PC', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (58, 7, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'PowerPC,Intel Mac', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (59, 7, 4, 'Системные требования', 'Минимальные системные требования для игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (60, 7, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (61, 7, 2, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (62, 7, 3, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (63, 7, 7, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (64, 8, 1, 'Год выхода игры', 'Год выхода игры на PDA', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (65, 8, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows CE+,Windows Mobile 2002+,Windows Mobile 2003+,Windows Mobile 2003 SE+,Windows Mobile 5+,Windows Mobile 6+', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (66, 8, 3, 'Требуется .net compact framework', '', 'option', 0, 'no', 'yes', 'Да 1.0+,Да 2.0+,Да 3.0+,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (67, 8, 4, 'Поддерживает VGA', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (72, 9, 1, 'Год выхода игры', 'Год выхода игры на Series60+', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (68, 8, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (69, 8, 6, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (70, 8, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (71, 9, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (73, 9, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Symbian 5,Symbian 6 (series60),Symbian 7 (series60), Symbian 8 (series80),Symbian 9 (series90)', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (74, 9, 3, 'Поддерживает тачскрин (UIQ)', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (75, 9, 4, 'Поддерживаемые разрешения экрана', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (76, 9, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (77, 9, 6, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (78, 9, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (79, 9, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (80, 10, 1, 'Год выхода игры', 'Год выхода игры на мобильник', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (81, 10, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'J2ME 1 (в очень старых мобильниках),J2ME 2', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (82, 10, 3, 'Поддерживает тач', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (83, 10, 4, 'Поддерживаемые разрешения экрана', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (84, 10, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (85, 10, 6, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (86, 10, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (87, 10, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (88, 11, 1, 'Год выхода игры', 'Год выхода игры на юникс систему', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (89, 11, 2, 'Графическая среда', '', 'option', 0, 'no', 'yes', 'Gnome,KDE,Xfce,Shell', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (90, 11, 3, 'Версия ядра', 'Минимальная версия ядра для функционирования системы, также версия графической среды (опционально)', 'text', 60, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (92, 11, 4, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (93, 11, 5, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (94, 11, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (95, 11, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (96, 28, 1, 'Год выхода игры', 'Год выхода игры на PlayStation', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (99, 28, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (100, 28, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (101, 28, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (102, 28, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (103, 30, 1, 'Год выхода игры', 'Год выхода игры на XBox', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (104, 30, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (105, 30, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (106, 30, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (107, 30, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (108, 31, 1, 'Год выхода игры', 'Год выхода игры на Wii', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (109, 31, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (110, 31, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (111, 31, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (112, 31, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (113, 29, 1, 'Год выхода игры', 'Год выхода игры на PlayStationPortable', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (114, 29, 2, 'Требует пиратской прошивки', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (115, 29, 3, 'Минимальная версия прошивки', 'Минимальная версия прошивки, необходимая для запуска игры', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (116, 29, 4, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (117, 29, 5, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (118, 29, 6, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (119, 29, 7, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (120, 32, 1, 'Год выхода игры', 'Год выхода игры на консоль', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (121, 32, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (122, 32, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (123, 32, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (124, 32, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (127, 12, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (128, 12, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows 3.11+,Windows 95+,Windows 98+,Windows Millenium+,Windows NT+,Windows 2000+,Windows XP+,Windows Vista+', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (129, 12, 4, 'Системные требования', 'Минимальные системные требования для программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (130, 12, 5, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (131, 12, 6, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (132, 12, 3, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (133, 12, 7, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (134, 12, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (135, 17, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (136, 17, 2, 'Графическая среда', '', 'option', 0, 'no', 'yes', 'Gnome,KDE,Xfce,Shell', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (137, 17, 3, 'Версия ядра', 'Минимальная версия ядра для функционирования системы, также версия графической среды (опционально)', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (138, 17, 4, 'Системные требования', 'Минимальные системные требования для программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (139, 17, 5, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (140, 17, 6, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (141, 17, 7, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (142, 17, 8, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (143, 17, 9, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (144, 13, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (145, 13, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'PowerPC,Intel Mac', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (146, 13, 4, 'Системные требования', 'Минимальные системные требования для программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (147, 13, 5, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (148, 13, 6, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (149, 13, 3, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (150, 13, 7, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (151, 13, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (152, 14, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (153, 14, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows CE+,Windows Mobile 2002+,Windows Mobile 2003+,Windows Mobile 2003 SE+,Windows Mobile 5+,Windows Mobile 6+', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (154, 14, 4, 'Поддерживает VGA', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (155, 14, 5, 'Требуется .net compact framework', '', 'option', 0, 'no', 'yes', 'Да 1.0+,Да 2.0+,Да 3.0+,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (156, 14, 6, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (157, 14, 7, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (158, 14, 8, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (159, 14, 9, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (160, 14, 10, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (165, 15, 6, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (164, 15, 5, '   Поддерживаемые разрешения экрана', '', 'text', 60, 'no', 'yes', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (163, 15, 4, 'Поддерживает тачскрин (UIQ)', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (162, 15, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Symbian 5,Symbian 6 (series60),Symbian 7 (series60), Symbian 8 (series80),Symbian 9 (series90)', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (161, 15, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (166, 15, 7, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (167, 15, 8, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (168, 15, 9, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (169, 15, 10, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (170, 16, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (171, 16, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'J2ME 1 (в очень старых мобильниках), J2ME 2', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (172, 16, 4, 'Поддерживает тачскрин', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (173, 16, 5, '   Поддерживаемые разрешения экрана', '', 'text', 60, 'no', 'yes', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (174, 16, 6, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (175, 16, 7, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (176, 16, 8, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (177, 14, 9, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (178, 14, 10, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (179, 18, 1, 'Архитектура', 'Архитектура оборудования, для которого предназначена операционная система<br/>\r\nНапример: X86, X64,ARM', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (180, 18, 3, 'Системные требования', 'Системные требования для запуска операционной системы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (181, 18, 4, 'Описание', 'Описание операционной системы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (182, 18, 5, 'Ссылки', 'Если система доступна для скачивания на файлообменниках, вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (183, 18, 2, 'Год выхода ОС', 'Год выхода операционной системы', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (184, 19, 1, 'Язык, на котором написан движок', 'Язык, на котором написана CMS. Например: PHP, Ruby.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (185, 19, 2, 'Версия движка', 'Версия CMS', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (186, 19, 3, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Платная (единовременный взнос), Платная (ежемесяно/ежегодно), Бесплатная', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (187, 19, 4, 'Цена', 'Если CMS платная, укажите ее цену.', 'text', 10, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (188, 19, 5, 'Описание CMS', 'Описание движка.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (189, 19, 6, 'Системные требования', 'Системные требования для CMS<br/>\r\nНапример: PHP 5.0+, MySQL 4.0+', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (190, 19, 7, 'Cсылки', 'Если движок доступен для скачивания с файлообменника(ов), добавьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (191, 20, 1, 'Язык, на котором написан скрипт', 'Например: PHP, Ruby.', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (192, 20, 4, 'Описание скрипта', 'Описание скрипта, что он делает и для чего предназначен.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (193, 20, 5, 'Ссылки', 'Если скрипт можно скачать с файлообменников, добавьте сюда соответствующие ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (194, 21, 1, 'Для чего предназначен', 'Для какого движка/cms предназначен шаблон', 'text', 80, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (195, 21, 4, 'Ссылки', 'Если шаблон возможно скачать с файлообменников, вставьте сюда соответствующие ссылки', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (196, 20, 2, 'Платный?', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (197, 20, 0, 'Цена', 'Если скрипт платный, укажите тут его цену', 'text', 10, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (198, 21, 2, 'Платный?', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (199, 21, 3, 'Цена', 'Если шаблон платный, укажите тут его цену.', 'text', 10, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (200, 3, 9, 'Язык', 'Язык, на котором поет исполнитель концерта', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (201, 4, 9, 'Язык', 'Язык, на котором поет исполнитель альбома', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (202, 5, 9, 'Язык', 'Язык, на котором поет исполнитель в дискографии', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (203, 6, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (204, 7, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (205, 8, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (206, 9, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (207, 10, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (208, 11, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (209, 12, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (210, 13, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (211, 14, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (212, 15, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (213, 16, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (214, 17, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (215, 18, 9, 'Язык', 'Язык, на который переведена или выпущена операционная система', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (216, 19, 9, 'Язык', 'Язык, на который переведен или выпущен движок', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (217, 20, 9, 'Язык', 'Язык, на который переведен или выпущен скрипт', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (218, 28, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (219, 29, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (220, 30, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (221, 31, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (222, 32, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (223, 22, 1, 'Оригинальное название', 'Оригинальное название книги, если оно на русском, поле не заполняется.', 'text', 80, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (224, 22, 2, 'Автор', 'Имя автора книги, пример: Александр Пушкин / Alexander Pushkin', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (225, 22, 3, 'Год выхода книги', 'Год выхода книги. Например 1990', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (226, 22, 4, 'Язык', 'Язык, на котором написана книга, или на который переведена.', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (227, 22, 5, 'Описание', 'Ключевые особенности сюжета', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (228, 23, 1, 'Оригинальное название', 'Оригинальное название журнала или газеты, если оно на русском, то поле не заполняется.', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (229, 23, 2, 'Это сборник журналов/газет', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (230, 23, 3, 'Дата выхода журнала/газеты', 'Дата выхода журнала, например 12.12.1958,<br/>если вы загружаете сборник журналов, то указывайте промежуток дат. Например 12.1.1956-12.1.1958', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (231, 23, 4, 'Описание', 'Описание журнала/газеты', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (232, 22, 6, 'Cсылки', 'Если книга доступна для скачивания по HTTP, FTP, укажите здесь соответствующие ссылки', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (233, 23, 5, 'Ссылки', 'Если журнал или газету можно скачать с файлообменников, вставьте сюда соответствующие ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (234, 24, 1, 'Область применения', 'Область применения энциклопедии или справочника.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (235, 24, 2, 'Год выхода', 'Год выхода справочника или энциклопедии.', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (236, 24, 3, 'Количество томов', 'Количество томов справочника или энциклопедии.', 'text', 2, 'yes', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (237, 24, 4, 'Описание', 'Описание справочника или энциклопедии.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (238, 24, 6, 'Ссылки', 'Ссылки на файлообменники.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (239, 22, 2, 'Издательство', 'Издательство, выпустившее книгу', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (240, 23, 2, 'Издательство', 'Издательство, выпустившее журнал/газету', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (241, 24, 2, 'Издательство', 'Издательство, выпустившее справочник или энциклопедию.', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (242, 25, 1, 'Описание', 'Описание релиза', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (243, 25, 2, 'Ссылки', 'Ссылки на файлообменники для скачивания материала.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'yes');
INSERT INTO `descr_details` VALUES (244, 26, 1, 'Количество изображений', 'Количество изображений в коллекции', 'text', 8, 'yes', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (245, 26, 2, 'Размер изображений', 'Размеры изображений', 'text', 80, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (246, 26, 3, 'Ссылки', 'Ссылки на файлообменники (если есть) для скачивания коллекции изображений.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (247, 27, 1, 'Тип материала', '', 'option', 0, 'no', 'yes', 'Трейлер,Постеры и прочие изображения', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (248, 27, 2, 'Ссылки', 'Ссылки на файлообменники.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');

DROP TABLE IF EXISTS `descr_types`;
CREATE TABLE IF NOT EXISTS `descr_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(255) default NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `type` (`type`,`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=33 ;

INSERT INTO `descr_types` (`id`, `type`, `category`) VALUES
(1, 'Фильм', 1),
(2, 'ТВ-программа', 1),
(3, 'Концерт', 1),
(4, 'Альбом', 2),
(5, 'Дискография', 2),
(6, 'Игра для WINDOWS', 3),
(7, 'Игра для Mac', 3),
(8, 'Игра для Windows Mobile', 3),
(9, 'Игра для SYMBIAN', 3),
(10, 'Игра для мобильника (JAVA)', 3),
(11, 'Игра для UNIX', 3),
(12, 'Программа для WINDOWS', 4),
(13, 'Программа для Mac', 4),
(14, 'Программа для WINDOWS MOBILE', 4),
(15, 'Программа для SYMBIAN', 4),
(16, 'Программа для мобильника (JAVA)', 4),
(17, 'Программа для UNIX', 4),
(18, 'Операционная система', 4),
(19, 'Движок/CMS', 5),
(20, 'Скрипт', 5),
(21, 'Шаблон', 5),
(22, 'Книга', 6),
(23, 'Журнал/Газета', 6),
(24, 'Справочник/Энциклопедия', 6),
(25, 'Прочее', 7),
(26, 'Коллекция картинок,аваров,обоев и т.д.', 7),
(27, 'Доп.материал к фильму', 7),
(28, 'Игра для PlayStation 1,2,3', 3),
(29, 'Игра для PSP', 3),
(30, 'Игра для XBOX', 3),
(31, 'Игра для Wii', 3),
(32, 'Игра для прочих консолей', 3);

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `howmuch` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=122 ;

INSERT INTO `tags` (`id`, `category`, `name`, `howmuch`) VALUES
(1, 1, 'Аниме', 0),
(2, 1, 'Боевик', 0),
(3, 1, 'Документальные', 0),
(4, 1, 'Драма', 0),
(5, 1, 'Исторические', 0),
(6, 1, 'Комедия', 0),
(7, 1, 'Криминал', 0),
(8, 1, 'Мистика', 0),
(9, 1, 'Молодежная комедия', 0),
(10, 1, 'Музыкальные фильмы', 0),
(11, 1, 'Мультфильмы', 0),
(12, 1, 'Отечественные', 0),
(13, 1, 'Приключения', 0),
(14, 1, 'Романтика', 0),
(15, 1, 'Триллеры', 0),
(16, 1, 'Ужасы', 0),
(17, 1, 'Фантастика', 0),
(18, 1, 'Эротика-XXX', 0),
(19, 2, 'Alternative', 0),
(20, 2, 'Ambient', 0),
(21, 2, 'Metal', 0),
(22, 2, 'Blues', 0),
(23, 2, 'Jazz', 0),
(24, 2, 'Rock', 0),
(25, 2, 'Classical', 0),
(26, 2, 'Disco', 0),
(27, 2, 'Drum''n''Bass', 0),
(28, 2, 'Electronic', 0),
(29, 2, 'Emo', 0),
(30, 2, 'Emo-Punk', 0),
(32, 2, 'Emocore', 0),
(33, 2, 'Punk', 0),
(34, 2, 'Rap', 0),
(35, 2, 'Gothic', 0),
(36, 2, 'Hip-Hop', 0),
(37, 2, 'House', 0),
(38, 2, 'Industrial', 0),
(39, 2, 'RnB', 0),
(40, 2, 'Trance', 0),
(41, 2, 'Techno', 0),
(42, 3, 'Action/Shooter', 0),
(43, 3, 'Стратегии', 0),
(44, 3, 'RPG', 0),
(45, 3, 'MMORPG', 0),
(46, 3, 'Логические', 0),
(47, 3, 'Квесты/Приключения', 0),
(48, 3, 'Аркады', 0),
(49, 3, 'Файтинги', 0),
(50, 3, 'Симуляторы', 0),
(51, 3, 'Спортивные', 0),
(52, 4, 'Portable', 0),
(53, 4, 'Система', 0),
(54, 4, 'Безопасность', 0),
(55, 4, 'Интернет', 0),
(56, 4, 'Русификаторы', 0),
(57, 4, 'Текст', 0),
(58, 4, 'Мультимедиа', 0),
(59, 4, 'Бизнес', 0),
(60, 4, 'Офис', 0),
(61, 4, 'Образование', 0),
(62, 4, 'Графика и Дизайн', 0),
(63, 4, 'Развлечения', 0),
(64, 4, 'Старые программы', 0),
(65, 4, 'Карты/GPS/Глонасс', 0),
(66, 4, 'Программы для WEB', 0),
(67, 4, 'Операционные системы', 0),
(68, 5, 'Скрипты/Движки', 0),
(69, 3, 'Эротические', 0),
(70, 6, 'Деловая/бизнес литература', 0),
(71, 6, 'Детективы', 0),
(72, 6, 'Детские', 0),
(73, 6, 'Драматические', 0),
(74, 6, 'Жанр неизвестен', 0),
(75, 6, 'Журнал/Газета', 0),
(76, 6, 'Законодательные акты', 0),
(77, 6, 'История', 0),
(78, 6, 'Классика', 0),
(79, 6, 'Компьютерная литература', 0),
(80, 6, 'Криминал', 0),
(81, 6, 'Лирика', 0),
(82, 6, 'Любовные/женские романы', 0),
(83, 6, 'Медицина', 0),
(84, 6, 'Музыка/Песни', 0),
(85, 6, 'Паранормальное/Мистика/Эзотерика/Магия', 0),
(86, 6, 'Повести', 0),
(87, 6, 'Проза', 0),
(88, 6, 'Психология', 0),
(89, 6, 'Религия', 0),
(90, 6, 'Словари', 0),
(91, 6, 'Сказки', 0),
(92, 6, 'Ужасы', 0),
(93, 6, 'Фантастика/Фентези', 0),
(94, 6, 'Философия', 0),
(95, 6, 'Эротика', 0),
(96, 7, 'Релизы без категории', 0),
(97, 7, 'Трейлеры и дополнительные материалы к фильмам', 0),
(98, 7, 'Картинки', 0),
(99, 7, 'Обои', 0),
(100, 7, 'Аватары/Иконки/Смайлы', 0),
(101, 5, 'Шаблоны', 0),
(102, 3, 'Гонки', 0),
(103, 2, 'Rock''n''Roll', 0),
(104, 2, 'Ambient Dub', 0),
(105, 2, 'PsyChill', 0),
(106, 2, 'Psy-Trance', 0),
(107, 2, 'Goa-Trance', 0),
(108, 2, 'Jumpstyle', 0),
(109, 2, 'Hardstyle', 0),
(110, 2, 'Hardcore', 0),
(111, 2, 'Breakbeat', 0),
(112, 2, 'Chillout', 0),
(113, 2, 'Lounge', 0),
(114, 2, 'Шансон', 0),
(115, 2, 'Reggae', 0),
(116, 2, 'Ska', 0),
(117, 2, 'Dub', 0),
(118, 2, 'Pop-music', 0),
(119, 6, 'Энциклопедии/Справочники', 0),
(120, 3, 'Старые игры', 0),
(121, 4, 'Связь', 0);

INSERT INTO `cache_stats` VALUES ('siteonline', 'a:4:{s:5:"onoff";s:1:"1";s:6:"reason";s:4:"test";s:5:"class";s:1:"6";s:10:"class_name";s:21:"только для Директоров";}');
INSERT INTO `cache_stats` VALUES ('maxusers', '10000');
INSERT INTO `cache_stats` VALUES ('lastcleantime', '0');
INSERT INTO `cache_stats` VALUES ('max_dead_torrent_time', '744');
INSERT INTO `cache_stats` VALUES ('minvotes', '1');
INSERT INTO `cache_stats` VALUES ('signup_timeout', '3');
INSERT INTO `cache_stats` VALUES ('announce_interval', '15');
INSERT INTO `cache_stats` VALUES ('max_torrent_size', '1000000');
INSERT INTO `cache_stats` VALUES ('max_images', '4');
INSERT INTO `cache_stats` VALUES ('defaultbaseurl', 'http://localhost');
INSERT INTO `cache_stats` VALUES ('siteemail', 'webmaster@localhost');
INSERT INTO `cache_stats` VALUES ('adminemail', 'webmaster@localhost');
INSERT INTO `cache_stats` VALUES ('sitename', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('description', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('keywords', 'kinokpk.com releaser, kinokpk, kinokpk.com, releaser, ZonD80');
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
INSERT INTO `cache_stats` VALUES ('exporttype', 'post');
INSERT INTO `cache_stats` VALUES ('forumurl', 'http://localhost/forum');
INSERT INTO `cache_stats` VALUES ('forumname', 'Integrated forum');
INSERT INTO `cache_stats` VALUES ('forum_bin_id', '2');
INSERT INTO `cache_stats` VALUES ('defuserclass', '3');
INSERT INTO `cache_stats` VALUES ('not_found_export_id', '2');
INSERT INTO `cache_stats` VALUES ('emo_dir', 'default');
INSERT INTO `cache_stats` VALUES ('re_publickey', 'none');
INSERT INTO `cache_stats` VALUES ('re_privatekey', 'none');
INSERT INTO `cache_stats` VALUES ('debug_mode', '0');
INSERT INTO `cache_stats` VALUES ('watermark', 'Kinokpk.com releaser');
INSERT INTO `cache_stats` VALUES ('torrentsperpage', '25');

UPDATE users SET stylesheet=1;

TRUNCATE TABLE stylesheets;

INSERT INTO stylesheets (uri,name) VALUES ('kinokpk','Kinokpk.com releaser v.2.0');

TRUNCATE TABLE orbital_blocks;

INSERT INTO `orbital_blocks` (`bid`, `bkey`, `title`, `content`, `bposition`, `weight`, `active`, `time`, `blockfile`, `view`, `expire`, `action`, `which`) VALUES
(1, '', 'Администрация', '<table border="0"><tr>\r\n<td class="block"><a href="admincp.php">Админка</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="online.php">Ху из онлайн?!</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="newsarchive.php">Редактировать новости</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="users.php">Список пользователей</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="viewreport.php">Жалобы на торренты</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="staffmess.php">Массовое ЛС</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="ipcheck.php">Двойники по IP</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="passwordadmin.php">Сменить пароль и мыло юзверю</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="clearcache.php">Очистить кеш</a></td>\r\n</tr>\r\n</table>', 'l', 1, 1, '', '', 2, '0', 'd', 'all'),
(8, '', 'Статистика', '', 'd', 1, 1, '', 'block-stats.php', 0, '0', 'd', 'ihome,'),
(9, '', 'Фильмы, которым нужны раздающие', '', 'c', 1, 1, '', 'block-helpseed.php', 0, '0', 'd', 'ihome,'),
(10, '', 'Напоминание о правилах', '<p align="jsutify">Администрация данного сайта - прирожденные садисты и кровопийцы, которые только и ищут повод помучать и поиздеваться над пользователями, используя для этого самые изощренные пытки. Единственный способ избежать этого - не попадаться нам на глаза, то есть спокойно качать и раздавать, поддерживая свой рейтинг как можно ближе к 1, и не делать глупых комментариев к торрентам. И не говорите, что мы вас не предупреждали! (шутка)</p>', 'c', 2, 0, '', '', 0, '0', 'd', 'rules,'),
(2, '', 'Новости', '', 'c', 3, 1, '', 'block-news.php', 0, '0', 'd', 'ihome,'),
(3, '', 'Пользователи', '', 'l', 5, 1, '', 'block-online.php', 0, '0', 'd', 'all'),
(4, '', 'Поиск', '', 'l', 4, 1, '', 'block-search.php', 0, '0', 'd', 'ihome,'),
(5, '', 'Опрос', '', 'c', 4, 1, '', 'block-polls.php', 1, '0', 'd', 'ihome,'),
(6, '', 'Новые фильмы', '', 'c', 5, 0, '', 'block-releases.php', 0, '0', 'd', 'ihome,'),
(7, '', 'Чего там на форуме творится?)', '', 'c', 6, 0, '', 'block-forum.php', 0, '0', 'd', 'ihome,'),
(11, '', 'Загрузка сервера', '', 'c', 7, 1, '', 'block-server_load.php', 2, '0', 'd', 'ihome,'),
(12, '', 'Торренты на главной', '', 'c', 8, 1, '', 'block-indextorrents.php', 0, '0', 'd', 'ihome,'),
(13, '', 'Пожертвования', '<center><a href="javascript:void(0)" title="SMS.копилка в новом маленьком окошке" onClick="javascript:window.open(''http://smskopilka.ru/?info&id=36066'', ''smskopilka'',''width=400,height=480,status=no,toolbar=no, menubar=no,scrollbars=yes,resizable=yes'');">\r\n<img src="http://img.smskopilka.ru/common/digits/target2/36/36066-101.gif" border="0" alt="SMS.копилка"></a><br>\r\nWebMoney:<br>\r\nR153898361884\r\nZ113282224168<br><hr>\r\nЗаранее спасибо!</center> ', 'l', 8, 1, '', '', 0, '0', 'd', 'ihome,'),
(14, '', 'Проблемы ?', '<center>\r\n<a href="contact.php"><font color="red"><u>Написать админу!\r\n</font>\r\n</center><br>\r\n<i>..с регистрацией<br>\r\n..с сайтом<br>\r\n..с торрентами</i>', 'l', 9, 1, '', '', 0, '0', 'd', 'all'),
(15, '', 'Vote 4 us!', 'none', 'l', 6, 1, '', '', 0, '0', 'd', 'all'),
(23, '', 'Облако тегов', '', 'l', 3, 1, '', 'block-cloud.php', 0, '0', 'd', 'all'),
(16, '', 'Друзья', '<h1><a href="http://www.kinokpk.com">Фильмы и видео для КПК - Kinokpk.com</a></h1>', 'l', 10, 1, '', '', 0, '0', 'd', 'ihome,'),
(17, '', 'Запросы', '', 'l', 2, 1, '', 'block-req.php', 0, '0', 'd', 'all'),
(18, '', 'Вопросы ?', 'none', 'l', 11, 1, '', '', 0, '0', 'd', 'all'),
(19, '', 'Меню', '', 'l', 7, 1, '', 'block-login.php', 0, '0', 'd', 'all'),
(22, '', 'Запрещенные релизы', '', 'd', 2, 1, '', 'block-cen.php', 0, '0', 'd', 'all');


TRUNCATE TABLE bans;

ALTER TABLE  `torrents`  ADD COLUMN  `images` text NOT NULL  AFTER `descr_type`;

ALTER TABLE  `users`  DROP COLUMN  `torrentsperpage`;

CREATE TABLE `rules` (
  `id` int(10) NOT NULL auto_increment,
  `type` set('categ','item') NOT NULL default 'item',
  `rule` text NOT NULL,
  `flag` tinyint(1) NOT NULL default '1',
  `categ` int(10) NOT NULL default '0',
  `order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0;

INSERT INTO `rules` VALUES (1, 'categ', 'Общие правила - За несоблюдение - бан!', 1, 0, 1);
INSERT INTO `rules` VALUES (2, 'categ', 'Правила закачек - Важнее не бывает!', 1, 0, 2);
INSERT INTO `rules` VALUES (3, 'categ', 'Правила комментирования релизов', 1, 0, 3);
INSERT INTO `rules` VALUES (4, 'categ', 'Рекомендации к аватарам - Убедительная просьба следовать нижеизложенным правилам', 1, 0, 4);
INSERT INTO `rules` VALUES (5, 'categ', 'Правила загрузок - Действуют для ВСЕХ релизов', 1, 0, 5);
INSERT INTO `rules` VALUES (6, 'categ', 'Правила запросов - Если чего-то вы не нашли на сайте', 1, 0, 6);
INSERT INTO `rules` VALUES (7, 'categ', 'Правила предложений - Если хочется чего-то предложить', 1, 0, 7);
INSERT INTO `rules` VALUES (8, 'categ', 'Звания на трекере', 1, 0, 8);
INSERT INTO `rules` VALUES (9, 'categ', 'Правила модерирования', 1, 0, 9);
INSERT INTO `rules` VALUES (10, 'categ', 'Возможности модераторов - Какие мои привелегии как модератора?', 1, 0, 10);
INSERT INTO `rules` VALUES (11, 'item', 'Данные правила не подлежат обсуждению и обязательны для выполнения всеми без исключения пользователями (Пишем свой сайт) рангом от простого пользователя до модератора (Администраторы и Дирекция - как лица, эти правила устанавливающие - поступают по своему усмотрению). Если вам не нравятся эти правила и вы хотите для себя другие правила - вы всегда можете создать свой собственный сайт и делать там все, что вам нравится. ', 1, 1, 1);
INSERT INTO `rules` VALUES (12, 'item', 'Слово Администрации - закон для пользователей релизера! В этом правиле нет исключений - (Пишем название вашего сайта) является частным битторрент трекером / релизером, и его политика определяется исключительно владельцами ресурса.', 1, 1, 2);
INSERT INTO `rules` VALUES (21, 'item', 'Злостный нарушитель правил трекера получает предупреждение ( !). Вы можете получить максиум 5 (пять) предупреждений. После 6 придупреждения вас автоматически банит система. От предупреждений можно откупиться рейтингом (посмотрите свой профиль).', 1, 1, 3);
INSERT INTO `rules` VALUES (22, 'item', 'В случае постоянного нарушения правил, рецидивист будет забанен - доступ к трекеру с его IP адреса будет закрыт. ', 1, 1, 4);
INSERT INTO `rules` VALUES (23, 'item', 'Не регистрируйте для себя несколько аккаунтов - мы регулярно проверяем нашу базу данных и без труда вычисляем умельцев такого рода.', 1, 1, 5);
INSERT INTO `rules` VALUES (24, 'item', 'Общение на (Пишем название вашего сайта) ведётся на литературном русском языке. Другие языки вы можете использовать только в случае крайней необходимости. В случае отсутствия русской раскладки клавитуры, используйте транслит или виртуальную клавитуру.', 1, 1, 6);
INSERT INTO `rules` VALUES (25, 'item', 'Мы рады любым предложениям и замечаниям, направленным на улучшение работы нашего трекера. Мы также просим вас сообщать Дирекции о любых ошибках и неточностях в работе сайте. Общие вопросы работы релизера вы можете обсужать в открытой дискуссии в этой теме на форуме (Пишем название вашего сайта), все частные проблемы рекомендуется решать в частной переписке, используя систему личных сообщений. ', 1, 1, 7);
INSERT INTO `rules` VALUES (26, 'item', 'Используйте для регистрации только рабочий email адрес. Вы также всегда можете изменить его в вашем профиле пользователя. Мы не рекомендуем вам бесплатные почтовые службы Mail.ru или hotmail.com из-за используемых на них агрессивных системах анти-спама. Если вы долго не получаете письмо с подтверждением о регистрации/перерегистрации, смотрите ЧаВо или используйте другую почтовую службу. В свою очередь мы заверяем вас, что ваш почтовый адрес, равно как любая другая ваша персональная информация, не будет использована ни в каких других целях и никогда не будет передана третьим лицам. Мы рекомендуем вам также указать дополнительный способ для связи (номер ICQ, имя в Skype или в MSN) с вами в том случае, если возникнет такая необходимость.Самая рабочая почтовая служба, на которую 120% придут все письма - GMail.com. ', 1, 1, 8);
INSERT INTO `rules` VALUES (27, 'item', 'Доступ к файлам нашего релизера ничем не ограничен - для вашего удобства мы отключили удаление аккаунтов из-за ниского рейтинга или неактивности. Но это не значит, что мы не можем включить их вновь. ', 1, 2, 1);
INSERT INTO `rules` VALUES (28, 'item', 'По умолчанию, вы можете быть пиром одновременно на 4 различных раздачах (VIP - неограничено). Если ваш канал позволяет большее, вы можете обратится с соответствующей просьбой к одному из администраторов. ', 1, 2, 2);
INSERT INTO `rules` VALUES (29, 'item', 'Администрация сайта относится с уважением к пользователям и не намерена ограничивать возможности использовать трекер "по умолчанию". Мы надеемся что вы так же будете относится к другим пользователям, раздающим и к Администрации.', 1, 2, 3);
INSERT INTO `rules` VALUES (30, 'item', 'Оптимальное соотношение "Скачал/Отдал" равно 1, то есть сколько скачал - столько и отдал. Стремитесь поддерживать его, если хотите долгой и безоблачной жизни на нашем релизере, как врочем, и на любом другом: не закрывайте ваш БитТоррент клиент как можно дольше! В случае, если вы остались единственных сидом (владельцем полной копии) на раздаче, пожалуйста оставайтесь на раздаче, если даже ваш рейтинг выше 1 - сегодня вы помогли кому-то, завтра он поможет вам. Свой рейтинг можно поднимать также с помощью бонусов ', 1, 2, 4);
INSERT INTO `rules` VALUES (31, 'item', 'Если у вас возникают общие проблемы со скачиванием (трекер неправильно отображает вашу статистику, вы не можете подключится к трекеру, скорость ваших закачек очень низкая), прочтите наши ЧаВо - вы найдёте в них всю необходимую вам информацию. В случае возникновения каких-либо вопросов по конкретной раздаче, просьба обращаться не к администрации, а к раздающему, используя систему Личных Сообщений или Комментариев к релизу.', 1, 2, 5);
INSERT INTO `rules` VALUES (32, 'item', 'Комментарии, содержащие интересные сведения и/или доброжелательные, остроумные и веселые - флудом не являются, и мы им только рады!', 1, 3, 1);
INSERT INTO `rules` VALUES (33, 'item', 'Система комментариев торрентов создана для того чтобы: (1) высказать свое уважение и благодарность раздающему, (2) задать интересующий вас конкретный технический вопрос относительно релиза,(3) сообщить интересную информацию, относящуюся к раздаче.  ', 1, 3, 2);
INSERT INTO `rules` VALUES (35, 'item', 'Никакого другого языка кроме литературного русского в комментариях.  ', 1, 3, 3);
INSERT INTO `rules` VALUES (36, 'item', 'Запрещены флейм и флуд.  ', 1, 3, 4);
INSERT INTO `rules` VALUES (37, 'item', 'Запрещены ссылки на варез-порталы.  ', 1, 3, 5);
INSERT INTO `rules` VALUES (38, 'item', 'Разрешены форматы .gif, .jpg и .png.  ', 1, 4, 1);
INSERT INTO `rules` VALUES (39, 'item', 'Рекомендуемые параметры: 150 X 150 пикселей в ширину и не размером более 60 Kб.  ', 1, 4, 2);
INSERT INTO `rules` VALUES (40, 'item', 'Не используйте оскорбительные материалы (а именно: религиозные и политические материалы, материалы, изображающие жестокость, насилие и порнографию). Сомневаетесь? Спросите Администрацию.  ', 1, 4, 3);
INSERT INTO `rules` VALUES (41, 'item', 'Все релизы должны сидироваться [кроме HTTP, FTP, eDonkey] (т.е. Вы должны находиться в сети в определенное время для раздачи), если вы не можете сидировать сразу после своего релиза, оставьте соответствующий комментарий в своем релизе.', 1, 5, 1);
INSERT INTO `rules` VALUES (42, 'item', 'Мы просто ненавидим фильмы, разделенные на части! Выкладывайте их одним файлом на файлообменнике!  ', 1, 5, 2);
INSERT INTO `rules` VALUES (43, 'item', 'Мы рекомендуем файлообменники со скоростью скачки от 100 кбит/сек. Например, Megaupload.com , Народ.Диск (Yandex) | Rapidshare.com - Если размер вашего релиза менее 100 мб  ', 1, 5, 3);
INSERT INTO `rules` VALUES (44, 'item', 'Мы рекомендуем оставлять подробное описание релиза. Вы можете найти информацию о файле в Рунете, используя Google.ru.  ', 1, 5, 4);
INSERT INTO `rules` VALUES (45, 'item', 'Постеры к фильмам обязательны! Кадры крайне желательны ', 1, 5, 5);
INSERT INTO `rules` VALUES (46, 'item', 'Мы ненавидим экранки и фильмы с "любительскими" переводами. Мы бережем свои уши и глаза!', 1, 5, 6);
INSERT INTO `rules` VALUES (47, 'item', 'Не забывайте говорить пасибки тем людям, которые реально вам помогли - это повысит ваш рейтинг!  ', 1, 5, 7);
INSERT INTO `rules` VALUES (48, 'item', 'Спасибо за понимание :)   ', 1, 5, 8);
INSERT INTO `rules` VALUES (49, 'item', 'Конкретно укажите категорию запроса.', 1, 6, 1);
INSERT INTO `rules` VALUES (50, 'item', 'Если запрос не попадает ни в одну категорию, его категория - Прочее  ', 1, 6, 2);
INSERT INTO `rules` VALUES (51, 'item', 'Подробно напишите информацию о релизе (описание, ссылку на картинку/сайт и т.д.)  ', 1, 6, 3);
INSERT INTO `rules` VALUES (52, 'item', 'По желанию, можете указать важность запроса или дату (срочно!, до четверга, к 28.09.2034 и т.д.)  ', 1, 6, 4);
INSERT INTO `rules` VALUES (53, 'item', 'Помните, что запрос - это запрос. Вы запросили - мы его увидим. Не стоит писать в личку аплоадерам, модераторам, администрации, а также на форум.  ', 1, 6, 5);
INSERT INTO `rules` VALUES (54, 'item', 'За требования типа "пошевеливайтесь с раздачей" "аплоадер, я хАчу этот фильм немедленно!!!" и "че все такие уроды, так медленно все работает" - бан навсегда.', 1, 6, 6);
INSERT INTO `rules` VALUES (55, 'item', 'Конкретно укажите категорию предложения, если предложение по сайту - его категория Прочее.', 1, 7, 1);
INSERT INTO `rules` VALUES (56, 'item', 'Помните, что предложение - это предложение. Вы предложили - мы увидим. Не стоит писать в личку аплоадерам, модераторам, администрации, а также на форум.  ', 1, 7, 2);
INSERT INTO `rules` VALUES (57, 'item', 'За требования типа "пошевеливайтесь с выполнением" "аплоадер, я хАчу это немедленно!!!" и "че все такие уроды, так медленно все делаете" - бан навсегда. ', 1, 7, 3);
INSERT INTO `rules` VALUES (58, 'item', 'Пользователь      ------------------------>      Обычный, нормальный пользователь трекера', 1, 8, 1);
INSERT INTO `rules` VALUES (59, 'item', ' Опытный пользователь      ------------------------>      Трекер автоматически присваивает (и отбирает) это звание у пользователей, чей аккаунт активен не менее 4 недель и имеет рейтинг 1.05 и выше. Модератор может вручную присвоить этот статус до следующего автоматического исполнения скрипта.  ', 1, 8, 2);
INSERT INTO `rules` VALUES (60, 'item', 'VIP      ------------------------>      Эти "любимчики" админов ;)  ', 1, 8, 3);
INSERT INTO `rules` VALUES (61, 'item', 'Аплоадер      ------------------------>      Человек, который активно выкладывает фильмы  ', 1, 8, 4);
INSERT INTO `rules` VALUES (62, 'item', 'Модератор      ------------------------>      Назначаются Администрацией и имеют функции модераторов.  ', 1, 8, 5);
INSERT INTO `rules` VALUES (63, 'item', 'Администратор      ------------------------>      Ваше непосредственное начальство.', 1, 8, 6);
INSERT INTO `rules` VALUES (64, 'item', 'Директор/Владелец      ------------------------>      Основатели и тех.администраторы проекта.', 1, 8, 7);
INSERT INTO `rules` VALUES (65, 'item', 'Не бойтесь сказать НЕТ! ', 1, 9, 1);
INSERT INTO `rules` VALUES (66, 'item', 'Будьте толерантными! Дайте пользователю(ям) шанс на реабилитацию. ', 1, 9, 2);
INSERT INTO `rules` VALUES (67, 'item', 'Прежде чем отключить аккаунт, напишите ему/ей ЛС и если они ответят, установите им испытательный срок на 2 недели.  ', 1, 9, 3);
INSERT INTO `rules` VALUES (68, 'item', 'Всегда указывайте причину (в поле комментария) почему вы забанили / предупредили пользователя.  ', 1, 9, 4);
INSERT INTO `rules` VALUES (69, 'item', 'Вы можете удалять и редактировать релизы. ', 1, 10, 1);
INSERT INTO `rules` VALUES (70, 'item', 'Вы можете удалять и редактировать аватары пользователей. ', 1, 10, 2);
INSERT INTO `rules` VALUES (71, 'item', 'Вы можете отключать пользователей.  ', 1, 10, 3);
INSERT INTO `rules` VALUES (72, 'item', 'Вы можете редактировать тайтлы VIP.  ', 1, 10, 4);
INSERT INTO `rules` VALUES (73, 'item', 'Вы можете видеть полную информацию о пользователях. ', 1, 10, 5);
INSERT INTO `rules` VALUES (74, 'item', 'Вы можете добавлять коментарии к пользователям (для других модераторов и администраторов). ', 1, 10, 6);
INSERT INTO `rules` VALUES (75, 'item', 'Вы можете перестать читать потому-что вы уже знаете про эти возможности. ;)  ', 1, 10, 7);
INSERT INTO `rules` VALUES (76, 'item', 'В конце концов посмотрите страничку Администрация (правый верхний угол).  ', 1, 10, 8);

ALTER TABLE `ratings` ADD UNIQUE (
`torrent` ,
`user`
);