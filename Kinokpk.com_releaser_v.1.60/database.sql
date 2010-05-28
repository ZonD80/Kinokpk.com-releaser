-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Время создания: Сен 29 2008 г., 17:57
-- Версия сервера: 5.0.18
-- Версия PHP: 5.1.6
-- 
-- БД: `kinokpkcom`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблицы `addedrequests`
-- 

CREATE TABLE `addedrequests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `requestid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `addedrequests`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `avps`
-- 

CREATE TABLE `avps` (
  `arg` varchar(20) NOT NULL default '',
  `value_s` text NOT NULL,
  `value_i` int(11) NOT NULL default '0',
  `value_u` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`arg`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `avps`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `bans`
-- 

CREATE TABLE `bans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `first` int(11) default NULL,
  `last` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `first_last` (`first`,`last`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `bans`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `blocks`
-- 

CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `blockid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userfriend` (`userid`,`blockid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `blocks`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `bonus`
-- 

CREATE TABLE `bonus` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `points` decimal(5,2) NOT NULL default '0.00',
  `description` text NOT NULL,
  `type` varchar(10) NOT NULL default 'traffic',
  `quanity` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=5 ;

-- 
-- Дамп данных таблицы `bonus`
-- 

INSERT INTO `bonus` VALUES (1, '1.0GB Uploaded', 75.00, 'Прибавляет 1 Гигабайт к вашему Аплоаду, тем самым повышая ваш рейтинг', 'traffic', 1073741824);
INSERT INTO `bonus` VALUES (2, '2.5GB Uploaded', 150.00, 'Прибавляет 2.5 Гигабайта к вашему Аплоаду, тем самым повышая ваш рейтинг', 'traffic', 2684354560);
INSERT INTO `bonus` VALUES (3, '5GB Uploaded', 250.00, 'Прибавляет 5 Гигабайт в вашему Аплоаду, тем самым повышая ваш рейтинг.', 'traffic', 5368709120);
INSERT INTO `bonus` VALUES (4, '3 Invites', 20.00, 'Прибавляет вам 3 приглашения, вы можете <a href="invite.php">Пригласить</a> своих друзей на наш сайт.', 'invite', 3);

-- --------------------------------------------------------

-- 
-- Структура таблицы `bookmarks`
-- 

CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `torrentid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `bookmarks`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `captcha`
-- 

CREATE TABLE `captcha` (
  `imagehash` varchar(32) NOT NULL default '',
  `imagestring` varchar(8) NOT NULL default '',
  `dateline` bigint(30) NOT NULL default '0',
  KEY `imagehash` (`imagehash`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `captcha`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `categories`
-- 

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `categories`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `censoredtorrents`
-- 

CREATE TABLE `censoredtorrents` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`,`reason`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;

-- 
-- Дамп данных таблицы `censoredtorrents`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `checkcomm`
-- 

CREATE TABLE `checkcomm` (
  `id` int(11) NOT NULL auto_increment,
  `checkid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `offer` tinyint(4) NOT NULL default '0',
  `torrent` tinyint(4) NOT NULL default '0',
  `req` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `checkcomm`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `comments`
-- 

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `request` varchar(11) NOT NULL default '0',
  `offer` varchar(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `comments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `countries`
-- 

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `flagpic` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=103 ;

-- 
-- Дамп данных таблицы `countries`
-- 

INSERT INTO `countries` VALUES (87, 'Antigua Barbuda', 'antiguabarbuda.gif');
INSERT INTO `countries` VALUES (33, 'Belize', 'belize.gif');
INSERT INTO `countries` VALUES (59, 'Burkina Faso', 'burkinafaso.gif');
INSERT INTO `countries` VALUES (10, 'Denmark', 'denmark.gif');
INSERT INTO `countries` VALUES (91, 'Senegal', 'senegal.gif');
INSERT INTO `countries` VALUES (76, 'Trinidad & Tobago', 'trinidadandtobago.gif');
INSERT INTO `countries` VALUES (20, 'Австралия', 'australia.gif');
INSERT INTO `countries` VALUES (36, 'Австрия', 'austria.gif');
INSERT INTO `countries` VALUES (27, 'Албания', 'albania.gif');
INSERT INTO `countries` VALUES (34, 'Алжир', 'algeria.gif');
INSERT INTO `countries` VALUES (12, 'Англия', 'uk.gif');
INSERT INTO `countries` VALUES (35, 'Ангола', 'angola.gif');
INSERT INTO `countries` VALUES (66, 'Андора', 'andorra.gif');
INSERT INTO `countries` VALUES (19, 'Аргентина', 'argentina.gif');
INSERT INTO `countries` VALUES (53, 'Афганистан', 'afghanistan.gif');
INSERT INTO `countries` VALUES (80, 'Багамы', 'bahamas.gif');
INSERT INTO `countries` VALUES (83, 'Барбадос', 'barbados.gif');
INSERT INTO `countries` VALUES (16, 'Бельгия', 'belgium.gif');
INSERT INTO `countries` VALUES (84, 'Бенгладеш', 'bangladesh.gif');
INSERT INTO `countries` VALUES (101, 'Болгария', 'bulgaria.gif');
INSERT INTO `countries` VALUES (65, 'Босния', 'bosniaherzegovina.gif');
INSERT INTO `countries` VALUES (18, 'Бразилия', 'brazil.gif');
INSERT INTO `countries` VALUES (74, 'Вануату', 'vanuatu.gif');
INSERT INTO `countries` VALUES (72, 'Венгрия', 'hungary.gif');
INSERT INTO `countries` VALUES (71, 'Венесуела', 'venezuela.gif');
INSERT INTO `countries` VALUES (75, 'Вьетнам', 'vietnam.gif');
INSERT INTO `countries` VALUES (7, 'Германия', 'germany.gif');
INSERT INTO `countries` VALUES (77, 'Гондурас', 'honduras.gif');
INSERT INTO `countries` VALUES (32, 'Гонк Конг', 'hongkong.gif');
INSERT INTO `countries` VALUES (41, 'Греция', 'greece.gif');
INSERT INTO `countries` VALUES (42, 'Гуатемала', 'guatemala.gif');
INSERT INTO `countries` VALUES (40, 'Доминиканская Республика', 'dominicanrep.gif');
INSERT INTO `countries` VALUES (100, 'Египт', 'egypt.gif');
INSERT INTO `countries` VALUES (43, 'Израиль', 'israel.gif');
INSERT INTO `countries` VALUES (26, 'Индия', 'india.gif');
INSERT INTO `countries` VALUES (13, 'Ирландия', 'ireland.gif');
INSERT INTO `countries` VALUES (61, 'Ирландия', 'iceland.gif');
INSERT INTO `countries` VALUES (102, 'Исла де Муерто', 'jollyroger.gif');
INSERT INTO `countries` VALUES (22, 'Испания', 'spain.gif');
INSERT INTO `countries` VALUES (9, 'Италия', 'italy.gif');
INSERT INTO `countries` VALUES (82, 'Камбоджа', 'cambodia.gif');
INSERT INTO `countries` VALUES (5, 'Канада', 'canada.gif');
INSERT INTO `countries` VALUES (78, 'Киргистан', 'kyrgyzstan.gif');
INSERT INTO `countries` VALUES (57, 'Кирибати', 'kiribati.gif');
INSERT INTO `countries` VALUES (8, 'Китай', 'china.gif');
INSERT INTO `countries` VALUES (52, 'Кного', 'congo.gif');
INSERT INTO `countries` VALUES (96, 'Колумбия', 'colombia.gif');
INSERT INTO `countries` VALUES (99, 'Коста Рика', 'costarica.gif');
INSERT INTO `countries` VALUES (51, 'Куба', 'cuba.gif');
INSERT INTO `countries` VALUES (85, 'Лаос', 'laos.gif');
INSERT INTO `countries` VALUES (98, 'Латвия', 'latvia.gif');
INSERT INTO `countries` VALUES (97, 'Леванон', 'lebanon.gif');
INSERT INTO `countries` VALUES (67, 'Литва', 'lithuania.gif');
INSERT INTO `countries` VALUES (31, 'Люксембург', 'luxembourg.gif');
INSERT INTO `countries` VALUES (68, 'Македония', 'macedonia.gif');
INSERT INTO `countries` VALUES (39, 'Малайзия', 'malaysia.gif');
INSERT INTO `countries` VALUES (24, 'Мексика', 'mexico.gif');
INSERT INTO `countries` VALUES (62, 'Науру', 'nauru.gif');
INSERT INTO `countries` VALUES (60, 'Нигерия', 'nigeria.gif');
INSERT INTO `countries` VALUES (69, 'Нидерландские Антиллы', 'nethantilles.gif');
INSERT INTO `countries` VALUES (15, 'Нидерланды', 'netherlands.gif');
INSERT INTO `countries` VALUES (21, 'Новая Зеландия', 'newzealand.gif');
INSERT INTO `countries` VALUES (11, 'Норвегия', 'norway.gif');
INSERT INTO `countries` VALUES (44, 'Пакистан', 'pakistan.gif');
INSERT INTO `countries` VALUES (88, 'Парагвая', 'paraguay.gif');
INSERT INTO `countries` VALUES (81, 'Перу', 'peru.gif');
INSERT INTO `countries` VALUES (14, 'Польша', 'poland.gif');
INSERT INTO `countries` VALUES (23, 'Португалия', 'portugal.gif');
INSERT INTO `countries` VALUES (49, 'Пуерто Рико', 'puertorico.gif');
INSERT INTO `countries` VALUES (3, 'Россия', 'russia.gif');
INSERT INTO `countries` VALUES (73, 'Румуния', 'romania.gif');
INSERT INTO `countries` VALUES (93, 'Северная Корея', 'northkorea.gif');
INSERT INTO `countries` VALUES (47, 'Сейшельские Острова', 'seychelles.gif');
INSERT INTO `countries` VALUES (46, 'Сербия', 'serbia.gif');
INSERT INTO `countries` VALUES (25, 'Сингапур', 'singapore.gif');
INSERT INTO `countries` VALUES (63, 'Словакия', 'slovenia.gif');
INSERT INTO `countries` VALUES (90, 'СССР', 'ussr.gif');
INSERT INTO `countries` VALUES (2, 'США', 'usa.gif');
INSERT INTO `countries` VALUES (48, 'Тайвань', 'taiwan.gif');
INSERT INTO `countries` VALUES (89, 'Тайланд', 'thailand.gif');
INSERT INTO `countries` VALUES (92, 'Того', 'togo.gif');
INSERT INTO `countries` VALUES (64, 'Туркменистан', 'turkmenistan.gif');
INSERT INTO `countries` VALUES (54, 'Турция', 'turkey.gif');
INSERT INTO `countries` VALUES (55, 'Узбекистан', 'uzbekistan.gif');
INSERT INTO `countries` VALUES (70, 'Украина', 'ukraine.gif');
INSERT INTO `countries` VALUES (86, 'Уругвай', 'uruguay.gif');
INSERT INTO `countries` VALUES (58, 'Филиппины', 'philippines.gif');
INSERT INTO `countries` VALUES (4, 'Финляндия', 'finland.gif');
INSERT INTO `countries` VALUES (6, 'Франция', 'france.gif');
INSERT INTO `countries` VALUES (94, 'Хорватия', 'croatia.gif');
INSERT INTO `countries` VALUES (45, 'Чехия', 'czechrep.gif');
INSERT INTO `countries` VALUES (50, 'Чили', 'chile.gif');
INSERT INTO `countries` VALUES (56, 'Швейцария', 'switzerland.gif');
INSERT INTO `countries` VALUES (1, 'Швеция', 'sweden.gif');
INSERT INTO `countries` VALUES (79, 'Эквадор', 'ecuador.gif');
INSERT INTO `countries` VALUES (95, 'Эстония', 'estonia.gif');
INSERT INTO `countries` VALUES (37, 'Югославия', 'yugoslavia.gif');
INSERT INTO `countries` VALUES (28, 'Южная Африка', 'southafrica.gif');
INSERT INTO `countries` VALUES (29, 'Южная Корея', 'southkorea.gif');
INSERT INTO `countries` VALUES (38, 'Южные Самоа', 'westernsamoa.gif');
INSERT INTO `countries` VALUES (30, 'Ямайка', 'jamaica.gif');
INSERT INTO `countries` VALUES (17, 'Япония', 'japan.gif');

-- --------------------------------------------------------

-- 
-- Структура таблицы `descr_details`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `descr_details`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `descr_torrents`
-- 

CREATE TABLE `descr_torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) NOT NULL,
  `typeid` varchar(30) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent` (`torrent`,`typeid`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `descr_torrents`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `descr_types`
-- 

CREATE TABLE `descr_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(30) default NULL,
  `iscategory` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `descr_types`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `files`
-- 

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL default '',
  `size` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `files`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `friends`
-- 

CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `friendid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userfriend` (`userid`,`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `friends`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `invites`
-- 

CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter` int(10) unsigned NOT NULL default '0',
  `inviteid` int(10) NOT NULL default '0',
  `invite` varchar(32) NOT NULL default '',
  `time_invited` datetime NOT NULL default '0000-00-00 00:00:00',
  `confirmed` char(3) NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `inviter` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `invites`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `messages`
-- 

CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sender` int(10) unsigned NOT NULL default '0',
  `receiver` int(10) unsigned NOT NULL default '0',
  `added` datetime default NULL,
  `subject` varchar(255) NOT NULL default '',
  `msg` text,
  `unread` enum('yes','no') NOT NULL default 'yes',
  `poster` int(10) unsigned NOT NULL default '0',
  `location` tinyint(1) NOT NULL default '1',
  `saved` enum('no','yes') NOT NULL default 'no',
  `archived` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`),
  KEY `poster` (`poster`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `messages`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `news`
-- 

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `body` text NOT NULL,
  `subject` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `news`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `newscomments`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `newscomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `notconnectablepmlog`
-- 

CREATE TABLE `notconnectablepmlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `notconnectablepmlog`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `offers`
-- 

CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `name` varchar(225) default NULL,
  `descr` text NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `category` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `votes` smallint(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

-- 
-- Дамп данных таблицы `offers`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `offervotes`
-- 

CREATE TABLE `offervotes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `offerid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=12 ;

-- 
-- Дамп данных таблицы `offervotes`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `orbital_blocks`
-- 

CREATE TABLE `orbital_blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `bkey` varchar(15) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL default '',
  `weight` int(10) NOT NULL default '1',
  `active` int(1) NOT NULL default '1',
  `time` varchar(14) NOT NULL default '0',
  `blockfile` varchar(255) NOT NULL default '',
  `view` int(1) NOT NULL default '0',
  `expire` varchar(14) NOT NULL default '0',
  `action` char(1) NOT NULL default '',
  `which` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=23 ;

-- 
-- Дамп данных таблицы `orbital_blocks`
-- 

INSERT INTO `orbital_blocks` VALUES (1, '', 'Администрация', '<table border="0"><tr>\r\n<td class="block"><a href="admincp.php">Админка</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="online.php">Ху из онлайн?!</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="news.php">Редактировать новости</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="users.php">Список пользователей</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="viewreport.php">Жалобы на торренты</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="staffmess.php">Массовое ЛС</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="ipcheck.php">Двойники по IP</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="logout.php">Выйти</a></td>\r\n</tr>\r\n</table>', 'l', 3, 1, '', '', 2, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (8, '', 'Статистика', '', 'd', 2, 1, '', 'block-stats.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (9, '', 'Фильмы, которым нужны раздающие', '', 'c', 1, 1, '', 'block-helpseed.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (10, '', 'Напоминание о правилах', '<p align="jsutify">Администрация данного сайта - прирожденные садисты и кровопийцы, которые только и ищут повод помучать и поиздеваться над пользователями, используя для этого самые изощренные пытки. Единственный способ избежать этого - не попадаться нам на глаза, то есть спокойно качать и раздавать, поддерживая свой рейтинг как можно ближе к 1, и не делать глупых комментариев к торрентам. И не говорите, что мы вас не предупреждали! (шутка)</p>', 'c', 2, 1, '', '', 0, '0', 'd', 'rules,');
INSERT INTO `orbital_blocks` VALUES (2, '', 'Новости', '', 'c', 3, 1, '', 'block-news.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (3, '', 'Пользователи', '', 'd', 1, 1, '', 'block-online.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (4, '', 'Поиск', '', 'l', 4, 1, '', 'block-search.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (5, '', 'Опрос', '', 'c', 4, 1, '', 'block-polls.php', 1, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (6, '', 'Новые фильмы', '', 'c', 5, 0, '', 'block-releases.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (7, '', 'Чего там на форуме творится?)', '', 'c', 6, 0, '', 'block-forum.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (11, '', 'Загрузка сервера', '', 'c', 7, 0, '', 'block-server_load.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (12, '', 'Торренты на главной', '', 'c', 8, 1, '', 'block-indextorrents.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (13, '', 'Пожертвования', 'не определено', 'l', 5, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (14, '', 'Проблемы ?', '<center>\r\n<a href="contact.php"><font color="red"><u>Написать админу!<br><br>\r\n</u></font></a>\r\n</center><br>\r\n<i>..с регистрацией<br>\r\n..с сайтом<br>\r\n..с торрентами</i>', 'l', 6, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (15, '', 'Для друзей', 'не определено\r\n', 'l', 7, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (16, '', 'Друзья', 'не определено', 'l', 8, 0, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (17, '', 'Запросы', '', 'l', 2, 1, '', 'block-req.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (18, '', 'Вопросы ?', 'не определено', 'l', 9, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (19, '', 'Меню', '', 'l', 1, 1, '', 'block-login.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (22, '', 'Запрещенные релизы', '', 'd', 3, 1, '', 'block-cen.php', 0, '0', 'd', 'all');

-- --------------------------------------------------------

-- 
-- Структура таблицы `peers`
-- 

CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `peer_id` varchar(20) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `uploadoffset` bigint(20) unsigned NOT NULL default '0',
  `downloadoffset` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` enum('yes','no') NOT NULL default 'no',
  `started` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `prev_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `connectable` enum('yes','no') NOT NULL default 'yes',
  `userid` int(10) unsigned NOT NULL default '0',
  `agent` varchar(60) NOT NULL default '',
  `finishedat` int(10) unsigned NOT NULL default '0',
  `passkey` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`),
  KEY `torrent` (`torrent`),
  KEY `torrent_seeder` (`torrent`,`seeder`),
  KEY `last_action` (`last_action`),
  KEY `connectable` (`connectable`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `peers`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `pollanswers`
-- 

CREATE TABLE `pollanswers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `selection` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`pollid`),
  KEY `selection` (`selection`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `pollanswers`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `pollcomments`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `pollcomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `polls`
-- 

CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `question` varchar(255) NOT NULL default '',
  `option0` varchar(40) NOT NULL default '',
  `option1` varchar(40) NOT NULL default '',
  `option2` varchar(40) NOT NULL default '',
  `option3` varchar(40) NOT NULL default '',
  `option4` varchar(40) NOT NULL default '',
  `option5` varchar(40) NOT NULL default '',
  `option6` varchar(40) NOT NULL default '',
  `option7` varchar(40) NOT NULL default '',
  `option8` varchar(40) NOT NULL default '',
  `option9` varchar(40) NOT NULL default '',
  `option10` varchar(40) NOT NULL default '',
  `option11` varchar(40) NOT NULL default '',
  `option12` varchar(40) NOT NULL default '',
  `option13` varchar(40) NOT NULL default '',
  `option14` varchar(40) NOT NULL default '',
  `option15` varchar(40) NOT NULL default '',
  `option16` varchar(40) NOT NULL default '',
  `option17` varchar(40) NOT NULL default '',
  `option18` varchar(40) NOT NULL default '',
  `option19` varchar(40) NOT NULL default '',
  `sort` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `polls`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `ratings`
-- 

CREATE TABLE `ratings` (
  `id` int(6) NOT NULL auto_increment,
  `torrent` int(10) NOT NULL default '0',
  `user` int(6) NOT NULL default '0',
  `rating` int(1) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=278 ;

-- 
-- Дамп данных таблицы `ratings`
-- 

INSERT INTO `ratings` VALUES (1, 6, 1, 5, '2008-03-30 18:09:59');
INSERT INTO `ratings` VALUES (2, 28, 4, 5, '2008-04-16 23:47:48');
INSERT INTO `ratings` VALUES (3, 5, 4, 5, '2008-04-25 01:23:41');
INSERT INTO `ratings` VALUES (4, 53, 1, 4, '2008-04-27 22:43:57');
INSERT INTO `ratings` VALUES (5, 81, 4, 5, '2008-05-16 00:44:10');
INSERT INTO `ratings` VALUES (6, 81, 3, 5, '2008-05-16 21:34:49');
INSERT INTO `ratings` VALUES (7, 79, 4, 4, '2008-05-17 00:34:22');
INSERT INTO `ratings` VALUES (8, 6, 4, 5, '2008-05-18 02:56:00');
INSERT INTO `ratings` VALUES (9, 89, 4, 5, '2008-05-18 03:20:02');
INSERT INTO `ratings` VALUES (10, 89, 71, 5, '2008-05-26 23:52:34');
INSERT INTO `ratings` VALUES (11, 99, 106, 5, '2008-06-03 16:12:42');
INSERT INTO `ratings` VALUES (12, 110, 1, 5, '2008-06-04 00:29:51');
INSERT INTO `ratings` VALUES (13, 92, 106, 4, '2008-06-04 12:01:25');
INSERT INTO `ratings` VALUES (14, 112, 47, 2, '2008-06-05 21:31:14');
INSERT INTO `ratings` VALUES (15, 119, 1, 4, '2008-06-06 11:31:13');
INSERT INTO `ratings` VALUES (16, 92, 1, 5, '2008-06-06 22:25:28');
INSERT INTO `ratings` VALUES (17, 65, 125, 4, '2008-06-07 22:22:05');
INSERT INTO `ratings` VALUES (18, 124, 1, 5, '2008-06-08 00:39:17');
INSERT INTO `ratings` VALUES (19, 128, 1, 5, '2008-06-10 13:34:06');
INSERT INTO `ratings` VALUES (20, 128, 2, 3, '2008-06-10 14:17:23');
INSERT INTO `ratings` VALUES (21, 126, 1, 4, '2008-06-10 14:42:22');
INSERT INTO `ratings` VALUES (22, 97, 53, 4, '2008-06-10 14:46:04');
INSERT INTO `ratings` VALUES (23, 106, 1, 4, '2008-06-10 15:15:50');
INSERT INTO `ratings` VALUES (24, 122, 1, 5, '2008-06-10 15:17:18');
INSERT INTO `ratings` VALUES (25, 125, 1, 3, '2008-06-10 15:21:30');
INSERT INTO `ratings` VALUES (26, 102, 1, 3, '2008-06-10 15:24:38');
INSERT INTO `ratings` VALUES (27, 101, 1, 5, '2008-06-10 15:25:06');
INSERT INTO `ratings` VALUES (28, 43, 1, 5, '2008-06-10 15:36:00');
INSERT INTO `ratings` VALUES (29, 19, 1, 5, '2008-06-10 15:38:21');
INSERT INTO `ratings` VALUES (30, 21, 1, 3, '2008-06-10 15:39:09');
INSERT INTO `ratings` VALUES (31, 48, 1, 5, '2008-06-10 15:46:03');
INSERT INTO `ratings` VALUES (32, 48, 53, 3, '2008-06-10 15:49:25');
INSERT INTO `ratings` VALUES (33, 124, 2, 5, '2008-06-10 16:06:07');
INSERT INTO `ratings` VALUES (34, 130, 3, 5, '2008-06-10 21:29:15');
INSERT INTO `ratings` VALUES (35, 124, 3, 5, '2008-06-10 21:30:35');
INSERT INTO `ratings` VALUES (36, 121, 3, 4, '2008-06-10 21:30:44');
INSERT INTO `ratings` VALUES (37, 114, 3, 5, '2008-06-10 21:30:56');
INSERT INTO `ratings` VALUES (38, 110, 3, 3, '2008-06-10 21:31:12');
INSERT INTO `ratings` VALUES (39, 129, 1, 4, '2008-06-12 23:16:49');
INSERT INTO `ratings` VALUES (40, 133, 1, 3, '2008-06-12 23:17:14');
INSERT INTO `ratings` VALUES (41, 1, 1, 1, '2008-06-12 23:18:03');
INSERT INTO `ratings` VALUES (42, 124, 53, 4, '2008-06-13 16:59:01');
INSERT INTO `ratings` VALUES (43, 136, 1, 5, '2008-06-14 17:06:33');
INSERT INTO `ratings` VALUES (44, 124, 4, 5, '2008-06-14 19:23:55');
INSERT INTO `ratings` VALUES (45, 119, 4, 5, '2008-06-14 19:24:14');
INSERT INTO `ratings` VALUES (46, 134, 1, 5, '2008-06-14 20:32:44');
INSERT INTO `ratings` VALUES (47, 130, 1, 5, '2008-06-14 20:32:57');
INSERT INTO `ratings` VALUES (48, 132, 1, 5, '2008-06-14 20:33:08');
INSERT INTO `ratings` VALUES (49, 131, 1, 5, '2008-06-14 20:33:18');
INSERT INTO `ratings` VALUES (50, 138, 4, 5, '2008-06-14 23:42:50');
INSERT INTO `ratings` VALUES (51, 140, 4, 5, '2008-06-15 01:06:36');
INSERT INTO `ratings` VALUES (52, 142, 1, 5, '2008-06-15 13:39:48');
INSERT INTO `ratings` VALUES (53, 143, 1, 5, '2008-06-15 21:57:13');
INSERT INTO `ratings` VALUES (54, 147, 1, 4, '2008-06-16 12:03:52');
INSERT INTO `ratings` VALUES (55, 149, 1, 4, '2008-06-16 23:58:48');
INSERT INTO `ratings` VALUES (56, 20, 1, 5, '2008-06-16 23:59:36');
INSERT INTO `ratings` VALUES (57, 85, 1, 5, '2008-06-17 00:00:30');
INSERT INTO `ratings` VALUES (58, 148, 1, 3, '2008-06-17 12:32:19');
INSERT INTO `ratings` VALUES (59, 99, 1, 5, '2008-06-17 12:33:50');
INSERT INTO `ratings` VALUES (60, 68, 1, 5, '2008-06-17 12:35:29');
INSERT INTO `ratings` VALUES (61, 73, 1, 5, '2008-06-17 12:39:28');
INSERT INTO `ratings` VALUES (62, 69, 1, 5, '2008-06-17 12:42:54');
INSERT INTO `ratings` VALUES (63, 146, 1, 5, '2008-06-17 12:43:27');
INSERT INTO `ratings` VALUES (64, 145, 1, 4, '2008-06-17 12:44:17');
INSERT INTO `ratings` VALUES (65, 144, 1, 5, '2008-06-17 12:46:11');
INSERT INTO `ratings` VALUES (66, 67, 1, 5, '2008-06-17 12:50:35');
INSERT INTO `ratings` VALUES (67, 141, 1, 5, '2008-06-17 12:52:26');
INSERT INTO `ratings` VALUES (68, 97, 1, 5, '2008-06-17 12:54:34');
INSERT INTO `ratings` VALUES (69, 3, 1, 4, '2008-06-17 21:47:27');
INSERT INTO `ratings` VALUES (70, 152, 1, 5, '2008-06-19 23:22:42');
INSERT INTO `ratings` VALUES (71, 41, 1, 5, '2008-06-19 23:28:08');
INSERT INTO `ratings` VALUES (72, 40, 1, 5, '2008-06-19 23:28:36');
INSERT INTO `ratings` VALUES (73, 95, 1, 3, '2008-06-19 23:29:16');
INSERT INTO `ratings` VALUES (74, 54, 1, 3, '2008-06-19 23:30:20');
INSERT INTO `ratings` VALUES (75, 156, 3, 5, '2008-06-20 13:00:05');
INSERT INTO `ratings` VALUES (76, 156, 1, 5, '2008-06-20 13:02:33');
INSERT INTO `ratings` VALUES (77, 157, 1, 5, '2008-06-20 19:10:42');
INSERT INTO `ratings` VALUES (78, 155, 1, 4, '2008-06-21 00:46:45');
INSERT INTO `ratings` VALUES (79, 154, 1, 4, '2008-06-21 00:46:57');
INSERT INTO `ratings` VALUES (80, 153, 1, 5, '2008-06-21 00:47:06');
INSERT INTO `ratings` VALUES (81, 150, 1, 5, '2008-06-21 00:47:16');
INSERT INTO `ratings` VALUES (82, 137, 1, 3, '2008-06-21 00:47:40');
INSERT INTO `ratings` VALUES (83, 135, 1, 2, '2008-06-21 00:47:58');
INSERT INTO `ratings` VALUES (84, 116, 1, 3, '2008-06-21 00:51:14');
INSERT INTO `ratings` VALUES (85, 81, 23, 5, '2008-06-21 22:40:14');
INSERT INTO `ratings` VALUES (86, 158, 1, 5, '2008-06-22 00:42:22');
INSERT INTO `ratings` VALUES (87, 149, 4, 5, '2008-06-24 21:54:38');
INSERT INTO `ratings` VALUES (88, 139, 4, 5, '2008-06-24 21:54:55');
INSERT INTO `ratings` VALUES (89, 143, 4, 4, '2008-06-24 22:23:17');
INSERT INTO `ratings` VALUES (90, 158, 5, 5, '2008-06-25 01:47:32');
INSERT INTO `ratings` VALUES (91, 81, 1, 5, '2008-06-25 20:52:40');
INSERT INTO `ratings` VALUES (92, 159, 1, 5, '2008-06-25 21:00:23');
INSERT INTO `ratings` VALUES (93, 162, 1, 4, '2008-06-26 11:49:05');
INSERT INTO `ratings` VALUES (94, 17, 4, 5, '2008-06-27 22:50:59');
INSERT INTO `ratings` VALUES (95, 170, 1, 5, '2008-06-29 00:54:00');
INSERT INTO `ratings` VALUES (96, 171, 1, 4, '2008-06-29 18:20:51');
INSERT INTO `ratings` VALUES (97, 172, 1, 5, '2008-06-29 22:50:20');
INSERT INTO `ratings` VALUES (98, 169, 1, 4, '2008-06-30 19:53:59');
INSERT INTO `ratings` VALUES (99, 166, 1, 5, '2008-06-30 19:54:08');
INSERT INTO `ratings` VALUES (100, 168, 1, 5, '2008-06-30 19:54:15');
INSERT INTO `ratings` VALUES (101, 162, 53, 5, '2008-06-30 21:46:26');
INSERT INTO `ratings` VALUES (102, 174, 53, 5, '2008-06-30 21:47:36');
INSERT INTO `ratings` VALUES (103, 182, 1, 5, '2008-07-05 00:01:21');
INSERT INTO `ratings` VALUES (104, 182, 3, 5, '2008-07-05 00:40:27');
INSERT INTO `ratings` VALUES (105, 181, 1, 5, '2008-07-05 01:45:10');
INSERT INTO `ratings` VALUES (106, 182, 190, 5, '2008-07-05 21:43:43');
INSERT INTO `ratings` VALUES (107, 174, 265, 4, '2008-07-07 16:53:17');
INSERT INTO `ratings` VALUES (108, 182, 4, 5, '2008-07-07 23:13:30');
INSERT INTO `ratings` VALUES (109, 181, 4, 5, '2008-07-08 18:07:40');
INSERT INTO `ratings` VALUES (110, 187, 4, 4, '2008-07-09 01:11:38');
INSERT INTO `ratings` VALUES (111, 124, 298, 5, '2008-07-09 19:21:22');
INSERT INTO `ratings` VALUES (112, 86, 4, 4, '2008-07-10 01:49:56');
INSERT INTO `ratings` VALUES (113, 181, 309, 1, '2008-07-10 09:08:49');
INSERT INTO `ratings` VALUES (114, 117, 310, 4, '2008-07-10 11:43:47');
INSERT INTO `ratings` VALUES (115, 124, 316, 5, '2008-07-10 22:03:33');
INSERT INTO `ratings` VALUES (116, 122, 227, 3, '2008-07-11 18:56:04');
INSERT INTO `ratings` VALUES (117, 187, 53, 3, '2008-07-12 02:37:06');
INSERT INTO `ratings` VALUES (118, 182, 350, 5, '2008-07-12 22:18:08');
INSERT INTO `ratings` VALUES (119, 182, 377, 5, '2008-07-14 15:50:24');
INSERT INTO `ratings` VALUES (120, 199, 4, 5, '2008-07-15 02:13:24');
INSERT INTO `ratings` VALUES (121, 202, 1, 5, '2008-07-16 22:05:28');
INSERT INTO `ratings` VALUES (122, 181, 92, 5, '2008-07-17 12:29:37');
INSERT INTO `ratings` VALUES (123, 1, 413, 5, '2008-07-18 00:47:49');
INSERT INTO `ratings` VALUES (124, 182, 419, 5, '2008-07-18 14:35:49');
INSERT INTO `ratings` VALUES (125, 170, 422, 4, '2008-07-18 15:46:58');
INSERT INTO `ratings` VALUES (126, 124, 437, 5, '2008-07-19 14:50:24');
INSERT INTO `ratings` VALUES (127, 22, 1, 5, '2008-07-19 20:52:21');
INSERT INTO `ratings` VALUES (128, 94, 1, 3, '2008-07-19 21:00:41');
INSERT INTO `ratings` VALUES (129, 50, 1, 5, '2008-07-19 21:08:11');
INSERT INTO `ratings` VALUES (130, 61, 1, 5, '2008-07-19 21:15:21');
INSERT INTO `ratings` VALUES (131, 89, 452, 5, '2008-07-20 12:25:04');
INSERT INTO `ratings` VALUES (132, 181, 452, 5, '2008-07-20 12:43:35');
INSERT INTO `ratings` VALUES (133, 203, 452, 5, '2008-07-20 13:19:48');
INSERT INTO `ratings` VALUES (134, 189, 452, 3, '2008-07-20 13:20:41');
INSERT INTO `ratings` VALUES (135, 182, 452, 5, '2008-07-20 13:21:22');
INSERT INTO `ratings` VALUES (136, 180, 452, 4, '2008-07-20 13:21:49');
INSERT INTO `ratings` VALUES (137, 172, 452, 5, '2008-07-20 13:24:43');
INSERT INTO `ratings` VALUES (138, 134, 452, 5, '2008-07-20 14:19:10');
INSERT INTO `ratings` VALUES (139, 158, 452, 5, '2008-07-20 14:20:37');
INSERT INTO `ratings` VALUES (140, 141, 452, 5, '2008-07-20 14:22:04');
INSERT INTO `ratings` VALUES (141, 203, 112, 5, '2008-07-20 14:22:39');
INSERT INTO `ratings` VALUES (142, 139, 452, 3, '2008-07-20 14:24:09');
INSERT INTO `ratings` VALUES (143, 89, 112, 5, '2008-07-20 14:28:37');
INSERT INTO `ratings` VALUES (144, 181, 112, 4, '2008-07-20 14:29:44');
INSERT INTO `ratings` VALUES (145, 151, 112, 4, '2008-07-20 14:31:28');
INSERT INTO `ratings` VALUES (146, 202, 112, 5, '2008-07-20 14:31:48');
INSERT INTO `ratings` VALUES (147, 186, 112, 5, '2008-07-20 14:32:07');
INSERT INTO `ratings` VALUES (148, 188, 112, 5, '2008-07-20 14:32:18');
INSERT INTO `ratings` VALUES (149, 193, 112, 5, '2008-07-20 14:32:31');
INSERT INTO `ratings` VALUES (150, 192, 112, 5, '2008-07-20 14:32:46');
INSERT INTO `ratings` VALUES (151, 185, 112, 5, '2008-07-20 14:32:54');
INSERT INTO `ratings` VALUES (152, 190, 112, 5, '2008-07-20 14:33:19');
INSERT INTO `ratings` VALUES (153, 184, 112, 5, '2008-07-20 14:33:27');
INSERT INTO `ratings` VALUES (154, 183, 112, 5, '2008-07-20 14:33:33');
INSERT INTO `ratings` VALUES (155, 187, 112, 3, '2008-07-20 14:33:47');
INSERT INTO `ratings` VALUES (156, 173, 112, 5, '2008-07-20 14:34:10');
INSERT INTO `ratings` VALUES (157, 172, 112, 5, '2008-07-20 14:34:19');
INSERT INTO `ratings` VALUES (158, 167, 112, 5, '2008-07-20 14:34:40');
INSERT INTO `ratings` VALUES (159, 166, 112, 5, '2008-07-20 14:34:55');
INSERT INTO `ratings` VALUES (160, 22, 112, 5, '2008-07-20 14:47:45');
INSERT INTO `ratings` VALUES (161, 21, 112, 5, '2008-07-20 14:47:52');
INSERT INTO `ratings` VALUES (162, 18, 112, 5, '2008-07-20 14:48:03');
INSERT INTO `ratings` VALUES (163, 1, 112, 3, '2008-07-20 14:48:25');
INSERT INTO `ratings` VALUES (164, 12, 112, 4, '2008-07-20 14:48:36');
INSERT INTO `ratings` VALUES (165, 11, 112, 4, '2008-07-20 14:48:45');
INSERT INTO `ratings` VALUES (166, 7, 112, 4, '2008-07-20 14:48:56');
INSERT INTO `ratings` VALUES (167, 10, 112, 5, '2008-07-20 14:49:10');
INSERT INTO `ratings` VALUES (168, 48, 112, 4, '2008-07-20 14:49:39');
INSERT INTO `ratings` VALUES (169, 46, 112, 5, '2008-07-20 14:49:50');
INSERT INTO `ratings` VALUES (170, 40, 112, 5, '2008-07-20 14:50:09');
INSERT INTO `ratings` VALUES (171, 38, 112, 5, '2008-07-20 14:50:20');
INSERT INTO `ratings` VALUES (172, 37, 112, 3, '2008-07-20 14:50:30');
INSERT INTO `ratings` VALUES (173, 80, 112, 4, '2008-07-20 14:51:10');
INSERT INTO `ratings` VALUES (174, 79, 112, 5, '2008-07-20 14:51:23');
INSERT INTO `ratings` VALUES (175, 78, 112, 4, '2008-07-20 14:51:35');
INSERT INTO `ratings` VALUES (176, 76, 112, 5, '2008-07-20 14:51:46');
INSERT INTO `ratings` VALUES (177, 61, 112, 5, '2008-07-20 14:52:12');
INSERT INTO `ratings` VALUES (178, 170, 350, 3, '2008-07-20 18:19:28');
INSERT INTO `ratings` VALUES (179, 209, 1, 5, '2008-07-21 23:49:01');
INSERT INTO `ratings` VALUES (180, 68, 135, 5, '2008-07-22 00:09:21');
INSERT INTO `ratings` VALUES (181, 161, 397, 5, '2008-07-22 14:29:33');
INSERT INTO `ratings` VALUES (182, 124, 484, 5, '2008-07-22 15:13:18');
INSERT INTO `ratings` VALUES (183, 124, 483, 5, '2008-07-22 15:52:37');
INSERT INTO `ratings` VALUES (184, 209, 452, 5, '2008-07-22 17:37:43');
INSERT INTO `ratings` VALUES (185, 208, 452, 5, '2008-07-22 17:38:42');
INSERT INTO `ratings` VALUES (186, 151, 452, 5, '2008-07-22 17:39:12');
INSERT INTO `ratings` VALUES (187, 195, 452, 5, '2008-07-22 17:41:19');
INSERT INTO `ratings` VALUES (188, 210, 135, 5, '2008-07-22 23:11:28');
INSERT INTO `ratings` VALUES (189, 61, 4, 5, '2008-07-23 00:54:06');
INSERT INTO `ratings` VALUES (190, 202, 452, 5, '2008-07-23 01:17:06');
INSERT INTO `ratings` VALUES (191, 1, 452, 2, '2008-07-23 01:20:02');
INSERT INTO `ratings` VALUES (192, 59, 452, 5, '2008-07-23 01:22:14');
INSERT INTO `ratings` VALUES (193, 48, 452, 5, '2008-07-23 01:27:32');
INSERT INTO `ratings` VALUES (194, 44, 452, 3, '2008-07-23 01:29:47');
INSERT INTO `ratings` VALUES (195, 37, 452, 4, '2008-07-23 01:31:41');
INSERT INTO `ratings` VALUES (196, 72, 452, 5, '2008-07-23 01:33:02');
INSERT INTO `ratings` VALUES (197, 61, 452, 5, '2008-07-23 01:34:49');
INSERT INTO `ratings` VALUES (198, 66, 452, 5, '2008-07-23 01:35:54');
INSERT INTO `ratings` VALUES (199, 66, 452, 5, '2008-07-23 01:35:55');
INSERT INTO `ratings` VALUES (200, 117, 452, 4, '2008-07-23 01:38:50');
INSERT INTO `ratings` VALUES (201, 92, 452, 5, '2008-07-23 01:42:07');
INSERT INTO `ratings` VALUES (202, 114, 452, 4, '2008-07-23 01:43:08');
INSERT INTO `ratings` VALUES (203, 143, 452, 5, '2008-07-23 01:45:50');
INSERT INTO `ratings` VALUES (204, 137, 452, 4, '2008-07-23 01:52:59');
INSERT INTO `ratings` VALUES (205, 133, 452, 4, '2008-07-23 01:54:18');
INSERT INTO `ratings` VALUES (206, 124, 452, 5, '2008-07-23 02:22:15');
INSERT INTO `ratings` VALUES (207, 168, 452, 5, '2008-07-23 02:42:22');
INSERT INTO `ratings` VALUES (208, 167, 452, 5, '2008-07-23 02:43:24');
INSERT INTO `ratings` VALUES (209, 195, 4, 5, '2008-07-24 01:17:51');
INSERT INTO `ratings` VALUES (210, 181, 520, 5, '2008-07-24 10:36:31');
INSERT INTO `ratings` VALUES (211, 210, 4, 5, '2008-07-24 21:09:23');
INSERT INTO `ratings` VALUES (212, 124, 531, 5, '2008-07-24 23:45:04');
INSERT INTO `ratings` VALUES (213, 1, 4, 4, '2008-07-25 23:56:04');
INSERT INTO `ratings` VALUES (214, 161, 552, 5, '2008-07-26 22:12:08');
INSERT INTO `ratings` VALUES (215, 124, 566, 5, '2008-07-27 21:15:35');
INSERT INTO `ratings` VALUES (216, 182, 576, 5, '2008-07-28 13:58:54');
INSERT INTO `ratings` VALUES (217, 6, 23, 5, '2008-07-29 10:38:07');
INSERT INTO `ratings` VALUES (218, 89, 1, 5, '2008-07-29 16:16:50');
INSERT INTO `ratings` VALUES (219, 222, 1, 5, '2008-07-29 16:17:02');
INSERT INTO `ratings` VALUES (220, 224, 1, 4, '2008-07-29 16:17:16');
INSERT INTO `ratings` VALUES (221, 221, 1, 4, '2008-07-29 16:17:35');
INSERT INTO `ratings` VALUES (222, 217, 1, 5, '2008-07-29 16:17:51');
INSERT INTO `ratings` VALUES (223, 124, 605, 5, '2008-07-30 10:56:00');
INSERT INTO `ratings` VALUES (224, 11, 608, 5, '2008-07-30 13:37:56');
INSERT INTO `ratings` VALUES (225, 223, 4, 5, '2008-07-30 22:45:45');
INSERT INTO `ratings` VALUES (226, 226, 1, 5, '2008-07-30 23:26:57');
INSERT INTO `ratings` VALUES (227, 223, 1, 4, '2008-07-30 23:27:10');
INSERT INTO `ratings` VALUES (228, 7, 629, 5, '2008-07-31 18:29:04');
INSERT INTO `ratings` VALUES (229, 227, 452, 5, '2008-08-02 21:24:41');
INSERT INTO `ratings` VALUES (230, 170, 452, 5, '2008-08-02 21:29:32');
INSERT INTO `ratings` VALUES (231, 228, 452, 5, '2008-08-02 21:33:59');
INSERT INTO `ratings` VALUES (232, 223, 452, 5, '2008-08-02 21:34:31');
INSERT INTO `ratings` VALUES (233, 226, 452, 5, '2008-08-02 21:35:10');
INSERT INTO `ratings` VALUES (234, 221, 452, 3, '2008-08-02 21:36:59');
INSERT INTO `ratings` VALUES (235, 225, 452, 5, '2008-08-02 21:38:38');
INSERT INTO `ratings` VALUES (236, 218, 452, 5, '2008-08-02 21:42:41');
INSERT INTO `ratings` VALUES (237, 229, 4, 5, '2008-08-03 02:51:42');
INSERT INTO `ratings` VALUES (238, 230, 4, 5, '2008-08-03 20:17:51');
INSERT INTO `ratings` VALUES (239, 181, 672, 5, '2008-08-04 22:09:32');
INSERT INTO `ratings` VALUES (240, 170, 4, 5, '2008-08-05 01:40:49');
INSERT INTO `ratings` VALUES (241, 237, 4, 5, '2008-08-05 20:20:49');
INSERT INTO `ratings` VALUES (242, 237, 347, 5, '2008-08-06 08:23:19');
INSERT INTO `ratings` VALUES (243, 241, 692, 4, '2008-08-06 15:28:32');
INSERT INTO `ratings` VALUES (244, 170, 693, 5, '2008-08-06 16:56:32');
INSERT INTO `ratings` VALUES (245, 223, 692, 5, '2008-08-07 00:59:16');
INSERT INTO `ratings` VALUES (246, 1, 707, 5, '2008-08-07 17:38:38');
INSERT INTO `ratings` VALUES (247, 239, 4, 5, '2008-08-07 23:34:09');
INSERT INTO `ratings` VALUES (248, 242, 4, 5, '2008-08-08 00:17:43');
INSERT INTO `ratings` VALUES (249, 241, 4, 4, '2008-08-08 00:17:58');
INSERT INTO `ratings` VALUES (250, 236, 4, 5, '2008-08-08 00:18:13');
INSERT INTO `ratings` VALUES (251, 244, 4, 5, '2008-08-08 19:37:45');
INSERT INTO `ratings` VALUES (252, 243, 4, 5, '2008-08-08 19:37:59');
INSERT INTO `ratings` VALUES (253, 181, 23, 5, '2008-08-10 11:21:55');
INSERT INTO `ratings` VALUES (254, 230, 310, 5, '2008-08-11 15:51:59');
INSERT INTO `ratings` VALUES (255, 249, 4, 5, '2008-08-11 22:52:49');
INSERT INTO `ratings` VALUES (256, 256, 4, 5, '2008-08-18 03:26:53');
INSERT INTO `ratings` VALUES (257, 74, 819, 1, '2008-08-20 19:07:32');
INSERT INTO `ratings` VALUES (258, 124, 829, 5, '2008-08-21 17:03:12');
INSERT INTO `ratings` VALUES (259, 125, 855, 4, '2008-08-25 15:36:38');
INSERT INTO `ratings` VALUES (260, 138, 440, 5, '2008-08-25 20:53:32');
INSERT INTO `ratings` VALUES (261, 112, 889, 4, '2008-08-28 23:56:54');
INSERT INTO `ratings` VALUES (262, 284, 1, 5, '2008-09-21 20:59:41');
INSERT INTO `ratings` VALUES (263, 283, 1, 5, '2008-09-21 20:59:46');
INSERT INTO `ratings` VALUES (264, 282, 1, 4, '2008-09-21 20:59:53');
INSERT INTO `ratings` VALUES (265, 281, 1, 5, '2008-09-21 20:59:59');
INSERT INTO `ratings` VALUES (266, 280, 1, 3, '2008-09-21 21:00:43');
INSERT INTO `ratings` VALUES (267, 278, 1, 5, '2008-09-21 21:00:52');
INSERT INTO `ratings` VALUES (268, 277, 1, 5, '2008-09-21 21:01:01');
INSERT INTO `ratings` VALUES (269, 276, 1, 4, '2008-09-21 21:01:07');
INSERT INTO `ratings` VALUES (270, 275, 1, 5, '2008-09-21 21:01:13');
INSERT INTO `ratings` VALUES (271, 272, 1, 2, '2008-09-21 21:01:19');
INSERT INTO `ratings` VALUES (272, 273, 1, 3, '2008-09-21 21:01:26');
INSERT INTO `ratings` VALUES (273, 270, 1, 5, '2008-09-21 21:01:43');
INSERT INTO `ratings` VALUES (274, 267, 1, 5, '2008-09-21 21:01:51');
INSERT INTO `ratings` VALUES (275, 124, 899, 5, '2008-09-23 05:27:56');
INSERT INTO `ratings` VALUES (276, 217, 213, 5, '2008-09-25 23:56:38');
INSERT INTO `ratings` VALUES (277, 262, 925, 5, '2008-09-26 22:08:13');

-- --------------------------------------------------------

-- 
-- Структура таблицы `readtorrents`
-- 

CREATE TABLE `readtorrents` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `torrentid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `read` (`userid`,`torrentid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `readtorrents`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `report`
-- 

CREATE TABLE `report` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `motive` varchar(255) NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `report`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `requests`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `requests`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `sessions`
-- 

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `username` varchar(40) NOT NULL default '',
  `class` tinyint(4) NOT NULL default '0',
  `ip` varchar(40) NOT NULL default '',
  `time` bigint(30) NOT NULL default '0',
  `url` varchar(150) NOT NULL default '',
  `useragent` text,
  PRIMARY KEY  (`sid`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `sessions`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `simpaty`
-- 

CREATE TABLE `simpaty` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `touserid` int(10) unsigned NOT NULL default '0',
  `fromuserid` int(10) unsigned NOT NULL default '0',
  `fromusername` varchar(40) NOT NULL default '',
  `bad` tinyint(1) unsigned NOT NULL default '0',
  `good` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(60) NOT NULL default '',
  `respect_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `touserid` (`touserid`),
  KEY `fromuserid` (`fromuserid`),
  KEY `fromusername` (`fromusername`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `simpaty`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `sitelog`
-- 

CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime default NULL,
  `color` varchar(11) NOT NULL default 'transparent',
  `txt` text,
  `type` varchar(8) NOT NULL default 'tracker',
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `sitelog`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `siteonline`
-- 

CREATE TABLE `siteonline` (
  `onoff` int(1) NOT NULL default '1',
  `reason` varchar(255) NOT NULL default '',
  `class` int(2) NOT NULL default '6',
  `class_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`onoff`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `siteonline`
-- 

INSERT INTO `siteonline` VALUES (1, 'Ставим небольшое обновление. Открытие в 20:00 МСК\r\n\r\nПриносим свои извинения. Команда Kinokpk.com', 6, 'только для Директоров');

-- --------------------------------------------------------

-- 
-- Структура таблицы `snatched`
-- 

CREATE TABLE `snatched` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` enum('yes','no') NOT NULL default 'no',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `startdat` datetime NOT NULL default '0000-00-00 00:00:00',
  `completedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `connectable` enum('yes','no') NOT NULL default 'yes',
  `finished` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `snatch` (`torrent`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `snatched`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `stamps`
-- 

CREATE TABLE `stamps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `class` tinyint(3) NOT NULL default '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `image` (`image`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=9 ;

-- 
-- Дамп данных таблицы `stamps`
-- 

INSERT INTO `stamps` VALUES (1, 1, 0, 'korr_http.png');
INSERT INTO `stamps` VALUES (2, 2, 4, 'nomat.png');
INSERT INTO `stamps` VALUES (3, 3, 0, 'a1.png');
INSERT INTO `stamps` VALUES (4, 4, 4, 'noflood.png');
INSERT INTO `stamps` VALUES (5, 5, 0, 'proper.png');
INSERT INTO `stamps` VALUES (6, 6, 0, 'svr.png');
INSERT INTO `stamps` VALUES (7, 7, 0, 'spas.png');
INSERT INTO `stamps` VALUES (8, 8, 6, 'ytv3-16.png');

-- --------------------------------------------------------

-- 
-- Структура таблицы `stylesheets`
-- 

CREATE TABLE `stylesheets` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uri` varchar(255) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `stylesheets`
-- 

INSERT INTO `stylesheets` VALUES (1, 'kinokpk', 'Kinokpk');

-- --------------------------------------------------------

-- 
-- Структура таблицы `thanks`
-- 

CREATE TABLE `thanks` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrentid` (`torrentid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `thanks`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `torrents`
-- 

CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info_hash` varbinary(40) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `save_as` varchar(255) NOT NULL default '',
  `search_text` text NOT NULL,
  `descr_type` int(10) NOT NULL,
  `image1` text NOT NULL,
  `image2` text NOT NULL,
  `category` int(10) unsigned NOT NULL default '0',
  `size` bigint(20) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` enum('single','multi') NOT NULL default 'single',
  `numfiles` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `times_completed` int(10) unsigned NOT NULL default '0',
  `leechers` int(10) unsigned NOT NULL default '0',
  `seeders` int(10) unsigned NOT NULL default '0',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_reseed` datetime NOT NULL default '0000-00-00 00:00:00',
  `visible` enum('yes','no') NOT NULL default 'yes',
  `banned` enum('yes','no') NOT NULL default 'no',
  `owner` int(10) unsigned NOT NULL default '0',
  `orig_owner` int(10) unsigned NOT NULL default '0',
  `numratings` int(10) unsigned NOT NULL default '0',
  `ratingsum` int(10) unsigned NOT NULL default '0',
  `free` enum('yes','no') default 'no',
  `sticky` enum('yes','no') NOT NULL default 'no',
  `moderated` enum('yes','no') NOT NULL default 'no',
  `moderatedby` int(10) unsigned default '0',
  `topic_id` int(10) NOT NULL default '0',
  `online` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`),
  FULLTEXT KEY `ft_search` (`search_text`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `torrents`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(40) NOT NULL default '',
  `old_password` varchar(40) NOT NULL default '',
  `passhash` varchar(32) NOT NULL default '',
  `secret` varchar(20) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `status` enum('pending','confirmed') NOT NULL default 'pending',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_access` datetime NOT NULL default '0000-00-00 00:00:00',
  `editsecret` varchar(20) NOT NULL default '',
  `privacy` enum('strong','normal','low') NOT NULL default 'normal',
  `stylesheet` int(10) default '1',
  `info` text,
  `acceptpms` enum('yes','friends','no') NOT NULL default 'yes',
  `ip` varchar(15) NOT NULL default '',
  `class` tinyint(3) unsigned NOT NULL default '0',
  `override_class` tinyint(3) unsigned NOT NULL default '255',
  `support` enum('no','yes') NOT NULL default 'no',
  `supportfor` text,
  `avatar` varchar(100) NOT NULL default '',
  `icq` varchar(255) NOT NULL default '',
  `msn` varchar(255) NOT NULL default '',
  `aim` varchar(255) NOT NULL default '',
  `yahoo` varchar(255) NOT NULL default '',
  `skype` varchar(255) NOT NULL default '',
  `mirc` varchar(255) NOT NULL default '',
  `website` varchar(50) NOT NULL default '',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `bonus` decimal(5,2) NOT NULL default '0.00',
  `title` varchar(30) NOT NULL default '',
  `country` int(10) unsigned NOT NULL default '0',
  `notifs` varchar(100) NOT NULL default '',
  `modcomment` text,
  `enabled` enum('yes','no') NOT NULL default 'yes',
  `dis_reason` text NOT NULL,
  `parked` enum('yes','no') NOT NULL default 'no',
  `avatars` enum('yes','no') NOT NULL default 'yes',
  `donor` enum('yes','no') NOT NULL default 'no',
  `simpaty` int(10) unsigned NOT NULL default '0',
  `warned` enum('yes','no') NOT NULL default 'no',
  `warneduntil` datetime NOT NULL default '0000-00-00 00:00:00',
  `torrentsperpage` int(3) unsigned NOT NULL default '0',
  `topicsperpage` int(3) unsigned NOT NULL default '0',
  `postsperpage` int(3) unsigned NOT NULL default '0',
  `deletepms` enum('yes','no') NOT NULL default 'yes',
  `savepms` enum('yes','no') NOT NULL default 'no',
  `gender` enum('1','2','3') NOT NULL default '1',
  `birthday` date default '0000-00-00',
  `passkey` varchar(32) NOT NULL default '',
  `language` varchar(255) NOT NULL default 'russian',
  `invites` int(10) NOT NULL default '0',
  `invitedby` int(10) NOT NULL default '0',
  `invitedroot` int(10) NOT NULL default '0',
  `passkey_ip` varchar(15) NOT NULL default '',
  `num_warned` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`status`,`added`),
  KEY `ip` (`ip`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`status`,`enabled`),
  FULLTEXT KEY `endis_reason` (`dis_reason`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `users`
-- 

