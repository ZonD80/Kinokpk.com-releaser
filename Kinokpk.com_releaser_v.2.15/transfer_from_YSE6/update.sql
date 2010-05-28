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
  `search` enum('yes','no') NOT NULL default 'no',
  `hide` enum('yes','no') default 'no',
  `mainpage` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 PACK_KEYS=0;
ALTER TABLE `descr_details` AUTO_INCREMENT = 1;

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

CREATE TABLE `descr_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(255) default NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `type` (`type`,`category`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251;
ALTER TABLE `descr_types` AUTO_INCREMENT = 1;

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

ALTER TABLE  `users`  ADD COLUMN  `last_checked` datetime NOT NULL default '0000-00-00 00:00:00'  AFTER `num_warned`;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=8 ;

INSERT INTO `categories` (`id`, `sort`, `name`, `image`) VALUES
(1, 1, 'Видео', ''),
(2, 2, 'Музыка', ''),
(3, 3, 'Игры', ''),
(4, 4, 'Программы', ''),
(5, 5, 'WEB', ''),
(6, 6, 'Библиотека', ''),
(7, 7, 'Прочее', '');

DROP TABLE IF EXISTS `descr_details`;
CREATE TABLE IF NOT EXISTS `descr_details` (
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
  `search` enum('yes','no') NOT NULL default 'no',
  `hide` enum('yes','no') default 'no',
  `mainpage` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=249 ;

INSERT INTO `descr_details` (`id`, `typeid`, `sort`, `name`, `description`, `input`, `size`, `isnumeric`, `required`, `mask`, `search`, `hide`, `mainpage`) VALUES
(39, 4, 1, 'Количество композиций', 'Количество песен в альбоме', 'text', 2, 'yes', 'yes', '', 'no', 'no', 'yes'),
(40, 4, 2, 'Год выхода альбома', 'Год, когда альбом увидел свет.', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(2, 1, 2, 'Год выхода', 'Год выхода фильма на экраны.', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes'),
(3, 1, 3, 'Режиссер', 'Режиссер фильма', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes'),
(4, 1, 4, 'В ролях', 'Актеры, учавствующие в фильме', 'text', 100, 'no', 'yes', '', 'no', 'no', 'yes'),
(5, 1, 5, 'Кем выпущено', 'Место производства/съемки фильма (страна).', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes'),
(6, 1, 6, 'Продолжительность', 'Продолжительность фильма в формате Ч:ММ:СС', 'text', 6, 'no', 'yes', '', 'no', 'no', 'no'),
(7, 1, 7, 'Перевод', 'Язык, на который переведен фильм.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes'),
(9, 1, 9, 'Рейтинг IMDB', 'Рейтинг сайта IMDB в формате: рейтинг (кол-во голосов). Пример: 9.0/10 (366) <br/><a target="_blank" href="http://www.imdb.com">Перейти на IMDb</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes'),
(10, 1, 10, 'Рейтинг кинопоиска', 'Рейтинг российского сайта Кинопоиск.ру в формате: рейтинг (кол-во голосов). Пример: 5.143 (12)<br/><a target="_blank" href="http://www.kinopoisk.ru">Перейти на Kinopoisk</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes'),
(11, 1, 11, 'Рейтинг МРАА<br /><a target="_blank" href="mpaafaq.php">подробнее тут</a>', 'G - Нет возрастных ограничений,PG - Рекомендуется присутствие родителей,PG-13 - Детям до 13 лет просмотр не желателен,R - Лицам до 17 лет обязательно присутствие взрослого,NC-17 - Лицам до 17 лет просмотр запрещен', 'option', 0, 'no', 'no', '[img][siteurl]/pic/mpaa/G.gif[/img] G - Нет возрастных ограничений,[img][siteurl]/pic/mpaa/PG.gif[/img] PG - Рекомендуется присутствие родителей,[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - Детям до 13 лет просмотр не желателен,[img][siteurl]/pic/mpaa/R.gif[/img] R - Лицам до 17 лет обязательно присутствие взрослого,[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - Лицам до 17 лет просмотр запрещен', 'no', 'no', 'yes'),
(12, 1, 13, 'Cсылки', 'Если фильм доступен для скачивания на файлообменниках, вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(13, 1, 14, 'Смотреть онлайн', 'Если фильм доступен для просмотра онлайн, то вставьте сюда соответствующую ссылку.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(14, 1, 15, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(15, 1, 16, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(16, 1, 17, 'Формат файла', 'Например, AVI', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no'),
(17, 1, 18, 'Качество исходника', '', 'option', 0, 'no', 'yes', 'Screener,SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no'),
(18, 1, 12, 'Описание фильма', '', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(19, 2, 1, 'Канал', 'Канал, который эту программу показывал', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(20, 2, 2, 'Год выхода программы', 'Год показа программы по ТВ', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes'),
(21, 2, 3, 'Ведущий', 'Ведущий программы, возможно ассистенты.', 'text', 60, 'no', 'no', '', 'no', 'no', 'no'),
(22, 2, 4, 'Описание', 'Описание сериала и серий в частности, рекомендуем использовать тег <b>spolier</b> для скрытия больших объёмов текста.', 'bbcode', 0, 'no', 'yes', '', 'yes', 'no', 'yes'),
(23, 2, 6, 'Ссылки', 'Если программа доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(24, 2, 7, 'Смотреть онлайн', 'Если программа доступна для просмотра онлайн, то вставьте сюда соответствующую ссылку.', 'links', 0, 'no', 'no', '', 'no', 'no', 'no'),
(25, 3, 1, 'Место проведения концерта', 'Место, где выступала та или иная группа', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(26, 3, 2, 'Год проведения концерта', 'Год проведения концерта.', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes'),
(27, 2, 5, 'Перевод', 'Язык, на который переведёна программа. Если программа на исходном языке, заполняется "Оригинал".', 'text', 40, 'no', 'yes', '', 'yes', 'no', 'yes'),
(28, 3, 3, 'Жанр', 'Жанр концерта, например: Punk, Hip-Hop, попса:)', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes'),
(47, 5, 3, 'Треклист/Описание', 'Треклист/описание каждого альбома. Советуем скрывать треклисты каждого альбома тегом <b>spoiler</b><br/>Например: [spoiler=Black album (2008)]<br/>1...<br/>2...<br/>[/spoiler].', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(29, 3, 4, 'Треклист/Описание', 'Треклист (или песни, которые звучали на концерте)', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(46, 5, 2, 'Количестов альбомов', 'Количество альбомов в дискографии', 'text', 2, 'yes', 'yes', '', 'no', 'no', 'yes'),
(30, 3, 5, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(31, 3, 6, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(45, 5, 1, 'Время дискографии', 'Промежуток между первым и последним альбомом дискографии. Например, 1990-2008', 'text', 9, 'no', 'yes', '', 'no', 'no', 'yes'),
(42, 4, 3, 'Треклист/Описание', 'Треклист (или песни, которые включены в альбом)', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(43, 4, 4, 'Аудио', '   Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no'),
(44, 4, 5, 'Ссылки', 'Если альбом доступен для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(32, 3, 7, 'Формат файла', 'Например, AVI', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no'),
(33, 3, 9, 'Ссылки', 'Если концерт доступен для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(34, 3, 10, 'Смотреть онлайн', 'Если программа доступна для просмотра онлайн, то вставьте сюда соответствующую ссылку.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(35, 2, 8, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(36, 2, 9, 'Аудио', '   Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(37, 2, 10, 'Формат файла', 'Формат файлов, например AVI.', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no'),
(38, 3, 8, 'Качество исходника', '', 'option', 0, 'no', 'yes', 'Screener,SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no'),
(48, 5, 4, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no'),
(49, 5, 5, 'Ссылки', '   Если альбом доступен для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(50, 6, 1, 'Год выхода игры', 'Год выхода игры на PC', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(51, 6, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows 3.11+,Windows 95+,Windows 98+,Windows Millenium+,Windows NT+,Windows 2000+,Windows XP+,Windows Vista+', 'no', 'no', 'yes'),
(52, 6, 4, 'Системные требования', 'Минимальные системные требования для игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(53, 6, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(54, 6, 2, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(55, 6, 3, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(56, 6, 7, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(57, 7, 1, 'Год выхода игры', 'Год выхода игры на PC', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(58, 7, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'PowerPC,Intel Mac', 'no', 'no', 'yes'),
(59, 7, 4, 'Системные требования', 'Минимальные системные требования для игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(60, 7, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(61, 7, 2, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(62, 7, 3, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(63, 7, 7, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(64, 8, 1, 'Год выхода игры', 'Год выхода игры на PDA', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(65, 8, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows CE+,Windows Mobile 2002+,Windows Mobile 2003+,Windows Mobile 2003 SE+,Windows Mobile 5+,Windows Mobile 6+', 'no', 'no', 'yes'),
(66, 8, 3, 'Требуется .net compact framework', '', 'option', 0, 'no', 'yes', 'Да 1.0+,Да 2.0+,Да 3.0+,Нет', 'no', 'no', 'yes'),
(67, 8, 4, 'Поддерживает VGA', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(72, 9, 1, 'Год выхода игры', 'Год выхода игры на Series60+', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(68, 8, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(69, 8, 6, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(70, 8, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(71, 9, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(73, 9, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Symbian 5,Symbian 6 (series60),Symbian 7 (series60), Symbian 8 (series80),Symbian 9 (series90)', 'no', 'no', 'yes'),
(74, 9, 3, 'Поддерживает тачскрин (UIQ)', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(75, 9, 4, 'Поддерживаемые разрешения экрана', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(76, 9, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(77, 9, 6, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(78, 9, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(79, 9, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(80, 10, 1, 'Год выхода игры', 'Год выхода игры на мобильник', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(81, 10, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'J2ME 1 (в очень старых мобильниках),J2ME 2', 'no', 'no', 'yes'),
(82, 10, 3, 'Поддерживает тач', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(83, 10, 4, 'Поддерживаемые разрешения экрана', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(84, 10, 5, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(85, 10, 6, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(86, 10, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(87, 10, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(88, 11, 1, 'Год выхода игры', 'Год выхода игры на юникс систему', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(89, 11, 2, 'Графическая среда', '', 'option', 0, 'no', 'yes', 'Gnome,KDE,Xfce,Shell', 'no', 'no', 'yes'),
(90, 11, 3, 'Версия ядра', 'Минимальная версия ядра для функционирования системы, также версия графической среды (опционально)', 'text', 60, 'no', 'no', '', 'no', 'no', 'no'),
(92, 11, 4, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(93, 11, 5, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(94, 11, 7, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(95, 11, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(96, 28, 1, 'Год выхода игры', 'Год выхода игры на PlayStation', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(99, 28, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(100, 28, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(101, 28, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(102, 28, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(103, 30, 1, 'Год выхода игры', 'Год выхода игры на XBox', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(104, 30, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(105, 30, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(106, 30, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(107, 30, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(108, 31, 1, 'Год выхода игры', 'Год выхода игры на Wii', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(109, 31, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(110, 31, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(111, 31, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(112, 31, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(113, 29, 1, 'Год выхода игры', 'Год выхода игры на PlayStationPortable', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(114, 29, 2, 'Требует пиратской прошивки', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(115, 29, 3, 'Минимальная версия прошивки', 'Минимальная версия прошивки, необходимая для запуска игры', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(116, 29, 4, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(117, 29, 5, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(118, 29, 6, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(119, 29, 7, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(120, 32, 1, 'Год выхода игры', 'Год выхода игры на консоль', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(121, 32, 2, 'Описание', 'Описание игры', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(122, 32, 3, 'Разработчик', 'Разработчик игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(123, 32, 4, 'Издатель', 'Издатель игры', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(124, 32, 5, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(127, 12, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(128, 12, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows 3.11+,Windows 95+,Windows 98+,Windows Millenium+,Windows NT+,Windows 2000+,Windows XP+,Windows Vista+', 'no', 'no', 'yes'),
(129, 12, 4, 'Системные требования', 'Минимальные системные требования для программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(130, 12, 5, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(131, 12, 6, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(132, 12, 3, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes'),
(133, 12, 7, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(134, 12, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(135, 17, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(136, 17, 2, 'Графическая среда', '', 'option', 0, 'no', 'yes', 'Gnome,KDE,Xfce,Shell', 'no', 'no', 'yes'),
(137, 17, 3, 'Версия ядра', 'Минимальная версия ядра для функционирования системы, также версия графической среды (опционально)', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes'),
(138, 17, 4, 'Системные требования', 'Минимальные системные требования для программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(139, 17, 5, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(140, 17, 6, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(141, 17, 7, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes'),
(142, 17, 8, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(143, 17, 9, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(144, 13, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(145, 13, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'PowerPC,Intel Mac', 'no', 'no', 'yes'),
(146, 13, 4, 'Системные требования', 'Минимальные системные требования для программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(147, 13, 5, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(148, 13, 6, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(149, 13, 3, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes'),
(150, 13, 7, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(151, 13, 8, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(152, 14, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(153, 14, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Windows CE+,Windows Mobile 2002+,Windows Mobile 2003+,Windows Mobile 2003 SE+,Windows Mobile 5+,Windows Mobile 6+', 'no', 'no', 'yes'),
(154, 14, 4, 'Поддерживает VGA', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(155, 14, 5, 'Требуется .net compact framework', '', 'option', 0, 'no', 'yes', 'Да 1.0+,Да 2.0+,Да 3.0+,Нет', 'no', 'no', 'yes'),
(156, 14, 6, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(157, 14, 7, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(158, 14, 8, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes'),
(159, 14, 9, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(160, 14, 10, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(165, 15, 6, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(164, 15, 5, '   Поддерживаемые разрешения экрана', '', 'text', 60, 'no', 'yes', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'no', 'no', 'yes'),
(163, 15, 4, 'Поддерживает тачскрин (UIQ)', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(162, 15, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'Symbian 5,Symbian 6 (series60),Symbian 7 (series60), Symbian 8 (series80),Symbian 9 (series90)', 'no', 'no', 'yes'),
(161, 15, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(166, 15, 7, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(167, 15, 8, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes'),
(168, 15, 9, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(169, 15, 10, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(170, 16, 1, 'Версия программы', 'Версия программы', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(171, 16, 2, 'Совместимось', '', 'option', 0, 'no', 'yes', 'J2ME 1 (в очень старых мобильниках), J2ME 2', 'no', 'no', 'yes'),
(172, 16, 4, 'Поддерживает тачскрин', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(173, 16, 5, '   Поддерживаемые разрешения экрана', '', 'text', 60, 'no', 'yes', 'Тут описываются поддерживаемые разрешения экрана, например: 240х320, 128х160', 'no', 'no', 'yes'),
(174, 16, 6, 'Описание', 'Описание программы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(175, 16, 7, 'Разработчик', 'Разработчик программы', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(176, 16, 8, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Freeware,Shareware,Trial,Demo', 'no', 'no', 'yes'),
(177, 14, 9, 'Цена программы (если платная)', 'Номинальная стоимость программного продукта', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(178, 14, 10, 'Ссылки', 'Если игра доступна для скачивания еще и на файлообменниках, то вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(179, 18, 1, 'Архитектура', 'Архитектура оборудования, для которого предназначена операционная система<br/>\r\nНапример: X86, X64,ARM', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes'),
(180, 18, 3, 'Системные требования', 'Системные требования для запуска операционной системы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'no'),
(181, 18, 4, 'Описание', 'Описание операционной системы', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(182, 18, 5, 'Ссылки', 'Если система доступна для скачивания на файлообменниках, вставьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(183, 18, 2, 'Год выхода ОС', 'Год выхода операционной системы', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(184, 19, 1, 'Язык, на котором написан движок', 'Язык, на котором написана CMS. Например: PHP, Ruby.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes'),
(185, 19, 2, 'Версия движка', 'Версия CMS', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(186, 19, 3, 'Тип лицензии', '', 'option', 0, 'no', 'yes', 'Платная (единовременный взнос), Платная (ежемесяно/ежегодно), Бесплатная', 'no', 'no', 'yes'),
(187, 19, 4, 'Цена', 'Если CMS платная, укажите ее цену.', 'text', 10, 'no', 'no', '', 'no', 'no', 'yes'),
(188, 19, 5, 'Описание CMS', 'Описание движка.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(189, 19, 6, 'Системные требования', 'Системные требования для CMS<br/>\r\nНапример: PHP 5.0+, MySQL 4.0+', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no'),
(190, 19, 7, 'Cсылки', 'Если движок доступен для скачивания с файлообменника(ов), добавьте сюда ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(191, 20, 1, 'Язык, на котором написан скрипт', 'Например: PHP, Ruby.', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(192, 20, 4, 'Описание скрипта', 'Описание скрипта, что он делает и для чего предназначен.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(193, 20, 5, 'Ссылки', 'Если скрипт можно скачать с файлообменников, добавьте сюда соответствующие ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(194, 21, 1, 'Для чего предназначен', 'Для какого движка/cms предназначен шаблон', 'text', 80, 'no', 'yes', '', 'no', 'no', 'yes'),
(195, 21, 4, 'Ссылки', 'Если шаблон возможно скачать с файлообменников, вставьте сюда соответствующие ссылки', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(196, 20, 2, 'Платный?', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(197, 20, 0, 'Цена', 'Если скрипт платный, укажите тут его цену', 'text', 10, 'no', 'no', '', 'no', 'no', 'no'),
(198, 21, 2, 'Платный?', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'yes'),
(199, 21, 3, 'Цена', 'Если шаблон платный, укажите тут его цену.', 'text', 10, 'no', 'no', '', 'no', 'no', 'no'),
(200, 3, 9, 'Язык', 'Язык, на котором поет исполнитель концерта', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(201, 4, 9, 'Язык', 'Язык, на котором поет исполнитель альбома', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(202, 5, 9, 'Язык', 'Язык, на котором поет исполнитель в дискографии', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(203, 6, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(204, 7, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(205, 8, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(206, 9, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(207, 10, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(208, 11, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(209, 12, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(210, 13, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(211, 14, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(212, 15, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(213, 16, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(214, 17, 9, 'Язык', 'Язык, на который переведена или выпущена программа', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(215, 18, 9, 'Язык', 'Язык, на который переведена или выпущена операционная система', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(216, 19, 9, 'Язык', 'Язык, на который переведен или выпущен движок', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(217, 20, 9, 'Язык', 'Язык, на который переведен или выпущен скрипт', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(218, 28, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(219, 29, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(220, 30, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(221, 31, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(222, 32, 9, 'Язык', 'Язык, на который переведена или выпущена игра', 'text', 10, 'no', 'yes', '', 'no', 'no', 'no'),
(223, 22, 1, 'Оригинальное название', 'Оригинальное название книги, если оно на русском, поле не заполняется.', 'text', 80, 'no', 'no', '', 'no', 'no', 'yes'),
(224, 22, 2, 'Автор', 'Имя автора книги, пример: Александр Пушкин / Alexander Pushkin', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(225, 22, 3, 'Год выхода книги', 'Год выхода книги. Например 1990', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(226, 22, 4, 'Язык', 'Язык, на котором написана книга, или на который переведена.', 'text', 10, 'no', 'yes', '', 'no', 'no', 'yes'),
(227, 22, 5, 'Описание', 'Ключевые особенности сюжета', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(228, 23, 1, 'Оригинальное название', 'Оригинальное название журнала или газеты, если оно на русском, то поле не заполняется.', 'text', 60, 'no', 'no', '', 'no', 'no', 'yes'),
(229, 23, 2, 'Это сборник журналов/газет', '', 'option', 0, 'no', 'yes', 'Да,Нет', 'no', 'no', 'no'),
(230, 23, 3, 'Дата выхода журнала/газеты', 'Дата выхода журнала, например 12.12.1958,<br/>если вы загружаете сборник журналов, то указывайте промежуток дат. Например 12.1.1956-12.1.1958', 'text', 60, 'no', 'yes', '', 'no', 'no', 'yes'),
(231, 23, 4, 'Описание', 'Описание журнала/газеты', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(232, 22, 6, 'Cсылки', 'Если книга доступна для скачивания по HTTP, FTP, укажите здесь соответствующие ссылки', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(233, 23, 5, 'Ссылки', 'Если журнал или газету можно скачать с файлообменников, вставьте сюда соответствующие ссылки.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(234, 24, 1, 'Область применения', 'Область применения энциклопедии или справочника.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'yes'),
(235, 24, 2, 'Год выхода', 'Год выхода справочника или энциклопедии.', 'text', 4, 'yes', 'yes', '', 'no', 'no', 'yes'),
(236, 24, 3, 'Количество томов', 'Количество томов справочника или энциклопедии.', 'text', 2, 'yes', 'yes', '', 'no', 'no', 'no'),
(237, 24, 4, 'Описание', 'Описание справочника или энциклопедии.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(238, 24, 6, 'Ссылки', 'Ссылки на файлообменники.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(239, 22, 2, 'Издательство', 'Издательство, выпустившее книгу', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no'),
(240, 23, 2, 'Издательство', 'Издательство, выпустившее журнал/газету', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no'),
(241, 24, 2, 'Издательство', 'Издательство, выпустившее справочник или энциклопедию.', 'text', 60, 'no', 'yes', '', 'no', 'no', 'no'),
(242, 25, 1, 'Описание', 'Описание релиза', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes'),
(243, 25, 2, 'Ссылки', 'Ссылки на файлообменники для скачивания материала.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'yes'),
(244, 26, 1, 'Количество изображений', 'Количество изображений в коллекции', 'text', 8, 'yes', 'yes', '', 'no', 'no', 'yes'),
(245, 26, 2, 'Размер изображений', 'Размеры изображений', 'text', 80, 'no', 'yes', '', 'no', 'no', 'yes'),
(246, 26, 3, 'Ссылки', 'Ссылки на файлообменники (если есть) для скачивания коллекции изображений.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no'),
(247, 27, 1, 'Тип материала', '', 'option', 0, 'no', 'yes', 'Трейлер,Постеры и прочие изображения', 'no', 'no', 'yes'),
(248, 27, 2, 'Ссылки', 'Ссылки на файлообменники.', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');

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

INSERT INTO `cache_stats` (`cache_name`, `cache_value`) VALUES
('siteonline', 'a:4:{s:5:"onoff";s:1:"1";s:6:"reason";s:4:"test";s:5:"class";s:1:"6";s:10:"class_name";s:21:"только для Директоров";}'),
('maxusers', '10000'),
('lastcleantime', '1232145378'),
('max_dead_torrent_time', '744'),
('minvotes', '1'),
('signup_timeout', '3'),
('announce_interval', '15'),
('max_torrent_size', '1000000'),
('defaultbaseurl', 'http://localhost'),
('siteemail', 'webmaster@localhost'),
('adminemail', 'webmaster@localhost'),
('sitename', 'Kinokpk.com releaser new installation'),
('description', 'Kinokpk.com releaser new installation'),
('keywords', 'kinokpk.com, releaser, ZonD80'),
('autoclean_interval', '900'),
('yourcopy', 'Your site copyright'),
('pm_delete_sys_days', '5'),
('pm_delete_user_days', '30'),
('pm_max', '100'),
('ttl_days', '28'),
('default_language', 'russian'),
('avatar_max_width', '120'),
('avatar_max_height', '120'),
('points_per_hour', '5'),
('default_theme', 'kinokpk'),
('nc', 'no'),
('deny_signup', '0'),
('allow_invite_signup', '0'),
('use_ttl', '0'),
('use_email_act', '0'),
('use_wait', '0'),
('use_lang', '1'),
('use_captcha', '1'),
('use_blocks', '1'),
('use_gzip', '1'),
('use_ipbans', '1'),
('use_sessions', '1'),
('smtptype', 'advanced'),
('as_timeout', '15'),
('as_check_messages', '1'),
('use_integration', '1'),
('exporttype', 'post'),
('forumurl', 'http://localhost/forum'),
('forumname', 'Integrated Forum'),
('forum_bin_id', '2'),
('defuserclass', '3'),
('not_found_export_id', '2'),
('emo_dir', 'default'),
('re_publickey', 'none'),
('re_privatekey', 'none'),
('debug_mode', '0');

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








