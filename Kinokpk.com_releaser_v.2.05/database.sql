-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Время создания: Дек 24 2008 г., 22:57
-- Версия сервера: 5.0.18
-- Версия PHP: 5.1.6
-- 
-- БД: `releaser200`
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
-- Структура таблицы `bannedemails`
-- 

CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `bannedemails`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `bans`
-- 

CREATE TABLE `bans` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `bonus`
-- 
INSERT INTO `bonus` (`id`, `name`, `points`, `description`, `type`, `quanity`) VALUES
(1, '1.0GB Uploaded', 75.00, 'Прибавляет 1 Гигабайт к вашему Аплоаду, тем самым повышая ваш рейтинг', 'traffic', 1073741824),
(2, '2.5GB Uploaded', 150.00, 'Прибавляет 2.5 Гигабайта к вашему Аплоаду, тем самым повышая ваш рейтинг', 'traffic', 2684354560),
(3, '5GB Uploaded', 250.00, 'Прибавляет 5 Гигабайт в вашему Аплоаду, тем самым повышая ваш рейтинг.', 'traffic', 5368709120),
(4, '3 Invites', 20.00, 'Прибавляет вам 3 приглашения, вы можете <a href="invite.php">Пригласить</a> своих друзей на наш сайт.', 'invite', 3);


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
-- Структура таблицы `cache_stats`
-- 

CREATE TABLE `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` mediumtext,
  PRIMARY KEY  (`cache_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `cache_stats`
-- 

INSERT INTO `cache_stats` VALUES ('siteonline', 'a:4:{s:5:"onoff";s:1:"1";s:6:"reason";s:4:"test";s:5:"class";s:1:"6";s:10:"class_name";s:21:"только для Директоров";}');
INSERT INTO `cache_stats` VALUES ('bans_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('lastcleantime', '1');
INSERT INTO `cache_stats` VALUES ('censoredtorrents_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('requests_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('polls_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('news_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('torrents_lastupdate', '1');

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

-- 
-- Дамп данных таблицы `categories`
-- 

INSERT INTO `categories` VALUES (1, 10, 'Фильмы', '1.png');
INSERT INTO `categories` VALUES (2, 20, 'Сериалы', '2.png');

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=39 ;

-- 
-- Дамп данных таблицы `descr_details`
-- 

INSERT INTO `descr_details` VALUES (1, 1, 1, 'Оригинальное название', 'Оригинальное (иностранное) название фильма, если оно на русском, то поле не заполняется.', 'text', 80, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (2, 1, 2, 'Год выхода', 'Год выхода фильма на экраны.', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (3, 1, 3, 'Режиссер', 'Режиссер фильма', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (4, 1, 4, 'В ролях', 'Актеры, учавствующие в фильме', 'text', 100, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (5, 1, 5, 'Кем выпущено', 'Место производства/съемки фильма (страна).', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (6, 1, 6, 'Продолжительность', 'Продолжительность фильма в формате Ч:ММ:СС', 'text', 6, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (7, 1, 7, 'Перевод', 'Язык, на который переведен фильм.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (8, 1, 8, 'Рецензия', 'Рецензия (ссылка на нее), на <a target="_blank" href="http://www.kinofilms.com.ua">Kinofilms.com.ua</a>', 'text', 100, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (9, 1, 9, 'Рейтинг IMDB', 'Рейтинг сайта IMDB в формате: рейтинг (кол-во голосов). Пример: 9.0/10 (366) <br/><a target="_blank" href="http://www.imdb.com">Перейти на IMDb</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (10, 1, 10, 'Рейтинг кинопоиска', 'Рейтинг российского сайта Кинопоиск.ру в формате: рейтинг (кол-во голосов). Пример: 5.143 (12)<br/><a target="_blank" href="http://www.kinopoisk.ru">Перейти на Kinopoisk</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (11, 1, 11, 'Рейтинг МРАА<br /><a target="_blank" href="mpaafaq.php">подробнее тут</a>', 'G - Нет возрастных ограничений,PG - Рекомендуется присутствие родителей,PG-13 - Детям до 13 лет просмотр не желателен,R - Лицам до 17 лет обязательно присутствие взрослого,NC-17 - Лицам до 17 лет просмотр запрещен', 'option', 0, 'no', 'no', '[img][siteurl]/pic/mpaa/G.gif[/img] G - Нет возрастных ограничений,[img][siteurl]/pic/mpaa/PG.gif[/img] PG - Рекомендуется присутствие родителей,[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - Детям до 13 лет просмотр не желателен,[img][siteurl]/pic/mpaa/R.gif[/img] R - Лицам до 17 лет обязательно присутствие взрослого,[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - Лицам до 17 лет просмотр запрещен', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (12, 1, 13, 'HTTP ссылки', 'HTTP ссылки на файлообменники', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (13, 1, 14, 'FTP ссылки', 'FTP ссылки на файлообменники', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (14, 1, 15, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (15, 1, 16, 'Аудио', 'Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (16, 1, 17, 'Формат файла', 'Например, AVI', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (17, 1, 18, 'Качество исходника', '', 'option', 0, 'no', 'yes', 'SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (18, 1, 12, 'Описание фильма', '', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (19, 2, 1, 'Оригинальное название', 'Оригинальное (иностранное) название сериала, если оно на русском, то поле не заполняется.', 'text', 80, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (20, 2, 2, 'Год начала съёмок', 'Год начала съёмок фильма', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (21, 2, 3, 'Год окончания съёмок', 'Год окончания съёмок сериала, если съёмки сериала не закончены, то заполняется "не закончен". ', 'text', 11, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (22, 2, 4, 'Сезон', 'Номер сезона.', 'text', 2, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (23, 2, 5, 'Режиссёр', 'Режиссёр(ы) сериала.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (24, 2, 6, 'В ролях', 'Актеры, участвующие в сериале.', 'text', 100, 'no', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (25, 2, 7, 'Кем выпущено', 'Место производства/съёмки сериала (страна).', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (26, 2, 9, 'Количество серий, доступных сейчас', 'Количество серий, доступных для скачивания в данном релизе.', 'text', 2, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (27, 2, 10, 'Перевод', 'Язык, на который переведён сериал. Если сериал на исходном языке, заполняется "Оригинал".', 'text', 40, 'no', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (28, 2, 11, 'Рецензия', 'Рецензия (ссылка на нее), на <a target="_blank" href="http://www.kinofilms.com.ua">Kinofilms.com.ua</a>', 'text', 100, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (29, 2, 12, 'Рейтинг IMDB', 'Рейтинг сайта IMDB в формате: рейтинг (кол-во голосов). Пример: 9.0/10 (366) <br/><a target="_blank" href="http://www.imdb.com">Перейти на IMDb</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (30, 2, 13, 'Рейтинг кинопоиска', 'Рейтинг российского сайта Кинопоиск.ру в формате: рейтинг (кол-во голосов). Пример: 5.143 (12)<br/><a target="_blank" href="http://www.kinopoisk.ru">Перейти на Kinopoisk</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (31, 2, 14, 'Рейтинг МРАА<br /><a target="_blank" href="mpaafaq.php">подробнее тут</a>', 'G - Нет возрастных ограничений,PG - Рекомендуется присутствие родителей,PG-13 - Детям до 13 лет просмотр не желателен,R - Лицам до 17 лет обязательно присутствие взрослого,NC-17 - Лицам до 17 лет просмотр запрещен', 'option', 0, 'no', 'no', '[img][siteurl]/pic/mpaa/G.gif[/img] G - Нет возрастных ограничений,[img][siteurl]/pic/mpaa/PG.gif[/img] PG - Рекомендуется присутствие родителей,[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - Детям до 13 лет просмотр не желателен,[img][siteurl]/pic/mpaa/R.gif[/img] R - Лицам до 17 лет обязательно присутствие взрослого,[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - Лицам до 17 лет просмотр запрещен', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (32, 2, 15, 'Описание сериала', 'Описание сериала и серий в частности, рекомендуем использовать тег <b>spolier</b> для скрытия больших объёмов текста.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (33, 2, 15, 'HTTP ссылки', 'HTTP ссылки на файлообменники', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (34, 2, 16, 'FTP ссылки', 'FTP ссылки на файлообменники', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (35, 2, 17, 'Видео', 'Информация о видео: разрешение, кодек, частота кадров, битрейт, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (36, 2, 18, 'Аудио', '	Информация об аудио: количество каналов, кодек, частота дискретизации, битрейт.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (37, 2, 19, 'Формат файла', 'Формат файлов, например AVI.', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (38, 2, 20, 'Качество исходника', '', 'option', 0, 'no', 'yes', 'SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no');

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
  `category` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`type`,`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

-- 
-- Дамп данных таблицы `descr_types`
-- 

INSERT INTO `descr_types` VALUES (1, 'Фильмы', 1);
INSERT INTO `descr_types` VALUES (2, 'Сериалы', 2);

-- --------------------------------------------------------

-- 
-- Структура таблицы `faq`
-- 

CREATE TABLE `faq` (
  `id` int(10) NOT NULL auto_increment,
  `type` set('categ','item') NOT NULL default 'item',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `flag` tinyint(1) NOT NULL default '1',
  `categ` int(10) NOT NULL default '0',
  `order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=75 ;

-- 
-- Дамп данных таблицы `faq`
-- 

INSERT INTO `faq` VALUES (1, 'categ', 'О сайте', '', 1, 0, 1);
INSERT INTO `faq` VALUES (2, 'categ', 'Информация для пользователей', '', 1, 0, 3);
INSERT INTO `faq` VALUES (3, 'categ', 'Статистика', '', 1, 0, 4);
INSERT INTO `faq` VALUES (4, 'categ', 'Заливка', '', 1, 0, 2);
INSERT INTO `faq` VALUES (5, 'categ', 'Закачка', '', 1, 0, 5);
INSERT INTO `faq` VALUES (6, 'categ', 'Как я могу увеличить свою скорость загрузки?', '', 1, 0, 6);
INSERT INTO `faq` VALUES (7, 'categ', 'Мой провайдер использует прокси. Что мне делать?', '', 1, 0, 7);
INSERT INTO `faq` VALUES (8, 'categ', 'Почему я не могу залогиниться? Меня заблокировали?', '', 1, 0, 8);
INSERT INTO `faq` VALUES (9, 'categ', 'Если ответа на мой вопрос здесь нет..? ', '', 1, 0, 9);
INSERT INTO `faq` VALUES (10, 'item', 'Что такое торрент (bittorrent)? Как скачивать файлы?', 'Check out <a class=altlink href="http://www.btfaq.com/">Brian''s BitTorrent FAQ and Guide</a>', 1, 1, 1);
INSERT INTO `faq` VALUES (11, 'item', 'На что расходуются деньги от пожертвований?', 'Мы хотим приобрести выделенный сервер для комфортных и очень быстрых раздач. На данный момент деньги идут на оплату хостинга.', 1, 1, 2);
INSERT INTO `faq` VALUES (12, 'item', 'Где я могу скачать исходники этого движка?', 'Вы можете взять их на <a href="http://dev.kinokpk.com" class=altlink_white>Dev.Kinokpk.com</a>.', 1, 1, 3);
INSERT INTO `faq` VALUES (13, 'item', 'Я зарегистрировал аккаунт, но не получил письмо с подтверждением по e-mail!', 'Воспользуйтесь <a class=altlink href=delacct.php>этой формой</a>, чтобы удалить аккаунт и перерегистрируйтесь.\r\nОбратите внимание, если в первый раз подтверждение на e-mail не пришло, то, вероятно, во второй раз оно тоже не прийдёт. Попробуйте использовать другой e-mail адрес.', 1, 2, 1);
INSERT INTO `faq` VALUES (14, 'item', 'Я забыл имя своего аккаунта или пароль! Не могли бы вы прислать их мне?', 'Пожалуйста, воспользуйтесь <a class=altlink href=recover.php>этой формой</a>, чтобы детали регистрации были высланы вам на E-mail.', 1, 2, 2);
INSERT INTO `faq` VALUES (15, 'item', 'Не могли бы вы переименовать мою учетную запись?', 'Мы не переименовываем аккаунты. Пожалуйста, создайте новый. (Воспользуйтесь <a href=delacct.php class=altlink>этой формой</a>), чтобы удалить предыдущий аккаунт.', 1, 2, 3);
INSERT INTO `faq` VALUES (16, 'item', 'Мог ли бы Вы удалить мой подтвержденный аккаунт?', 'Вы можете сделать это сами, используя <a href=delacct.php class=altlink>эту форму</a>.', 1, 2, 4);
INSERT INTO `faq` VALUES (17, 'item', 'А что такое мой рейтинг (ratio)?', 'Суть протокола <b>BitTorrent</b> заключается в том, что <b>каждый</b> пользователь <b>не только скачивает, но и одновременно раздает другим пользователям то, что уже успел скачать</b>. Если он не раздает или раздает очень мало, это препятствует быстрому файлообмену и ни он, ни другие участники раздачи не смогут быстро получить весь раздаваемый материал целиком.\r\nЕсли он равен 1, то вы отдали ровно столько, сколько скачали.<br>\r\nНе путайте индивидуалный рейтинг на каждой закачке с общим ретингом в вашем <a class=altlink href=my.php>Профиле</a>.<br>\r\n<br>\r\nРейтинг – очень важная вещь. Люди с маленьким рейтингом и с нежеланием его исправить удаляются с трекера, так, как они мешают нормальному файлобмену между пользователями. Ваш рейтинг должен быть не ниже 1.<br>\r\n<br>\r\nНа странице закачек в графе рейтинга, помимо отношения выраженного в цифрах, вы можете увидеть два символа:\r\n<br>Первый (---), означающий, что ваш рейтинг не определён или равен 0.<br> Второй (INF), означающий, что вы только отдаёте и подсчет рейтинга не возможен т.к. он равен бесконечности. \r\n', 1, 2, 5);
INSERT INTO `faq` VALUES (18, 'item', 'Почему мой IP отображается на странице с деталями?', 'Только вы и модераторы могут смотреть ваш IP и email. Обычные пользователи не могут видеть эту информацию.', 1, 2, 6);
INSERT INTO `faq` VALUES (19, 'item', 'Помогите! Я не могу войти (залогиниться)!?', 'Иногда эта проблема возникает из-за глюков Internet Explorer. Закройте все окна Internet Explorer`а и откройте Internet Options в панели управления. Кликните на кнопку Delete Cookies. Это должно помочь.\r\n', 1, 2, 7);
INSERT INTO `faq` VALUES (20, 'item', 'Мой IP-адрес динамический. Что мне сделать, чтобы быть подключенным?', 'Вам не нужно ничего делать. Всё что вам нужно - это убедиться, что вы вошли (залогинились) с текущим IP- адресом, когда стартуете новую торрент-сессию (начинаете новую загрузку/раздачу). После этого, даже если ваш IP поменяется в течении сессии, скачивание или раздача продолжатся, и статистика обновится автоматически', 1, 2, 8);
INSERT INTO `faq` VALUES (21, 'item', 'Почему написано, что я не могу подключиться? (В графе Порт мой порт красного цвета)(И о чем мне надо позаботиться?)', 'Трекер обнаружил, что у вас файрвол (firewall) или NAT, и вы не можете принимать подключения.\r\n<br>\r\n<br>\r\nЭто означает, что другие участники не смогут коннектиться к вам, лишь только вы к ним. Особенно плохо, что два пира, оба с закрытыми портами не могу соединиться друг с другом, пока кто-либо из них не откроет порт.\r\n<br>\r\n<br>\r\nДля решения данной проблемы откройте порты, используемые для входящих соединений (такие же, как и в установках вашего клиента) в файрволе и/или настройте ваш NAT сервер. (Обратитесь к документации к вашему роутеру или на форум производителя). Так же вы можете найти нужную информацию на ресурсе <a class=altlink href="http://portforward.com/">PortForward</a>).', 1, 2, 9);
INSERT INTO `faq` VALUES (22, 'item', 'Какие классы пользователей представлены на Вашем ресурсе?', '<table cellspacing=3 cellpadding=0>\r\n<tr>\r\n<td class=embedded width=100 bgcolor="#F5F4EA">&nbsp; <b><font color="#000000">Пользователь</b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Стандартный класс пользователя. Низшая каста.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color=''#996699''>Продвинутый</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Опытный пользователь. Может просмативать NFO-файлы, а также качать до 10 торрентов одновременно.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><img src="pic/star.gif" alt="Star"></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Особо отличившийся пользователь</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded valign=top bgcolor="#F5F4EA">&nbsp; <b><font color=''green''>VIP</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>Элитный пользователь. Не подлежит автоматическому понижению в ранге.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="orange">Релизер</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Пользователь с правами заливки.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded valign=top bgcolor="#F5F4EA">&nbsp; <b><font color="purple">Модератор</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>Представитель администрации ресурса. Может редактировать и удалять раздачи и комментарии, повышать и понижать в ранге, выдавать предупреждения.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="red">Администратор</color></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Могут все, что угодно. Благодаря этим пользователям, был создан и поддерживается этот ресурс.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="#0F6CEE">SysOp</color></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Архитектор матрицы.</td>\r\n</tr>\r\n</table>', 1, 2, 10);
INSERT INTO `faq` VALUES (23, 'item', 'Как работают эти классы?', '<table cellspacing=3 cellpadding=0>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA" valign=top width=100>&nbsp; <b><font color="#996699">Продвинутый</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>Должен быть зарегестрирован, как минимум, 4 недели, загрузить не менее 25Гб и иметь рейтинг более 1.05.<br>\r\nПри выполнении поставленных требований, будет автоматически поднят в ранге. Подлежит автоматическому понижению при падении рейтинга до отметки менее 0,95.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><img src="pic/star.gif" alt="Star"></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Если администрация сочтет нужным, Вы получите этот отличительный знак</a></td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA" valign=top>&nbsp; <b><font color=''green''>VIP</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>Выдается за особые заслуги перед ресурсом.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="orange">Релизер</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Вы можете попросить модератора о возведении в этот статус только при соблюдении следующих условий: Вы зарегестрированы более 8 недель, Вы загрузили более 25 Гб, Ваш рейтинг выше отметки 1,05.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="purple">Модератор</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>Назначается администрацией. Не стоит просить нас, мы сами вас попросим.</td>\r\n</tr>\r\n</table>', 1, 2, 11);
INSERT INTO `faq` VALUES (25, 'item', 'Почему мои знакомые не могут зарегистрироваться?', 'Значит превышен лимит пользователей. Аккаунты, неактивные в течении более 28 дней, автоматически удаляются, так что пусть попробуют позже. (У нас нет системы резервирования мест, или очереди, не спрашивайте нас об этом!)\r\n', 1, 2, 12);
INSERT INTO `faq` VALUES (26, 'item', 'Как мне добавить аватар в свой профиль?', 'Для начала найдите картинку, которая вам понравиться, и подходящую под <a class=altlink href=rules.php>правила</a>. Потом вам необходимо найти место, чтобы разместить ее, например <a class=altlink href=bitbucket-upload.php>BitBucket</a>, <a class="altlink" href="http://photobucket.com/">Photobucket</a>,\r\n<a class="altlink" href="http://uploadit.org/">Upload-It!</a> или\r\n<a class="altlink" href="http://www.imageshack.us/">ImageShack</a>).\r\nПосле того как вы разместили свою картинку, вам надо просто скопировать URL, который вам выдали при размещении файла в поле аватар в <a class="altlink" href="usercp.php">профиле</a>.', 1, 2, 13);
INSERT INTO `faq` VALUES (27, 'item', 'Наиболее часто встречающиеся причины необновления статистики.', '<ul>\r\n<li>Юзер - читер. (ака "Быстрый Бан")</li>\r\n<li>Сервер перегружен и не отвечает. Просто постарайтесь продержать сессию открытой, пока сервер не заработает снова. (Зафлуживание сервера путём переодического ручного обновления страницы не рекомендуется.)</li>\r\n<li>Вы используете плохой/неисправный клиент. Если вы хотите использовать экспериментальную версию, используйте её на свой страх и риск.</li>\r\n</ul>', 1, 3, 1);
INSERT INTO `faq` VALUES (28, 'item', 'Полезные советы.', '<ul>\r\n<li>Если торрент, который вы скачиваете/раздаёте, не отображен в списке ваших закачек просто подождите, или обновите страницу вручную.</li>\r\n<li>Убедитесь, что вы правильно закрыли ваш клиент, и трекер получил "event=completed".</li>\r\n<li>Если сервер упал, и лежит не прекращайте раздачу. Если его подымут до того, как вы выйдете из клиента, статистика обновится автоматически.</li>\r\n</ul>', 1, 3, 2);
INSERT INTO `faq` VALUES (29, 'item', 'Можно ли использовать любые торрент-клиенты?', 'Да. На данный момент трекер обновляет статистику корректно, при использовании любого торрент-клиента. (кроме забаненных конечно) Тем не менее мы рекомендуем <b>не использовать</b> следующие клиенты:br>\r\n<ul>\r\n<li>BitTorrent++</li>\r\n<li>Nova Torrent</li>\r\n<li>TorrentStorm</li>\r\n</ul>\r\nЭти клиенты неверно обрабатывают отмену/остановку торрент-сессии. Если вы их используете, возможна ситуации, когда в деталях торренты будут перечислены даже после завершения загрузки, или закрытия клиента.<br>\r\n<br>\r\nТак же, не рекомендуется использовать клиенты альфа(alpha) или бета(beta) версий.', 1, 3, 3);
INSERT INTO `faq` VALUES (30, 'item', 'Почему торрент, который я скачиваю/раздаю, отображается несколько раз в моем профиле?', 'Если по некоторым причинам (например экстренная перезагрузка компьютера, или зависание клиента) ваш клиент завершил работу некорректно, и вы перезапустили его, вам будет выдан новый "peer_id", таким образом ваша закачка будет опознана, как новый(другой) торрент. А по старому торренту сервер так никогда и не получит "event=completed" или "event=stopped", и будет отображать его некоторое время в списке ваших активных торрентов. Не обращайте на это внимания, в конечном счете глюк пропадет.', 1, 3, 4);
INSERT INTO `faq` VALUES (31, 'item', 'Я закончил или отменил торрент. Почему в моем профиле он все ещё отображается?', 'Некоторые клиенты, особенно TorrentStorm и Nova Torrent не отправляют серверу сообщение о прекращении или отмене торрента. В таких случаях трекер будет ждать сообщения от вашего клиента, и отображать что вы скачиваете или раздаете ещё некоторое время. Не обращайте внимания, через некоторое время торрент все-таки пропадет из списка ваших активных торрентов.', 1, 3, 5);
INSERT INTO `faq` VALUES (32, 'item', 'Почему иногда в моем профайле присутствуют торренты, которые я никогда не качал!?', 'Когда запускается торрен-сессия трекер использует passkey для опознания пользователя. Возможно кто-то украл/узнал ваш пасскей. Обязательно смените его у себя профиле если вдруг обнаружите такое. Учтите, что после смены пасскея вам придется перекачать все активные торренты.', 1, 3, 6);
INSERT INTO `faq` VALUES (33, 'item', 'Несколько IP (Могу ли я логинится с разных компьютеров)?', 'Да, трекер поддерживает несколько сессий с разных IP для одного пользоателя. Торрент ассоциируется с пользователем в тот момент, когда он стартует закачку, и только в этот момент IP важен. Таким образом, если вы хотите скачивать/раздавать с компьютера А и компьютера Б используя один и тот же аккаунт, вам необходимо залогиниться на сайт с компьютера А, запустить торрент, и затем проделать то же самое с компьтера Б (2 компьютера использовано только для примера, ограничений на количество нет. Главное - выполнять оба шага на каждом из компьтеров). Вам не нужно перелогиниваться заново, когда вы закрываете клиент.\r\n', 1, 3, 7);
INSERT INTO `faq` VALUES (34, 'item', 'Как NAT/ICS может испортить картину?', 'В случае использования NAT вам необходимо настроить разные диапазоны для торрент-клиентов на разных компьютерах, и создать NAT правила в роутере. (Подробности настройки роутеров выходят за рамки данного FAQ`а, поэтому обратитесь к документации к вашему девайсу и/или на форум техподдержки). Часто в сетях нет возможности конфигурировать роутеры по своему усмотрению. Вам прийдется пользоваться трекером на свой страх и риск. За ошибки связанные с работой за NAT`ом администрация ответственности не несет.', 1, 3, 8);
INSERT INTO `faq` VALUES (73, 'item', 'Почему написано, что невозможно подключится, хотя я не использую NAT/Firewall?', 'Наш трекер достаточно сообразительный относительно вопроса определения вашего реального IP, однако, ему необходимо, чтобы прокси отсылал заголовок HTTP_X_FORWARDED_FOR. Если прокси вашего провайдера этого не делает - происходит следующее: трекер интерпретирует IP прокси, как ваш собственный. И когда вы пытаетесь зайти на трекер, он пытается соединится с вашим клиентом, что бы определить, сидите ли вы за NAT/firewall, однако на самом деле он коннектиться к прокси-серверу, по порту, который вы указали в своём клиенте. Т.к. прокси ничего не принимает по данному порту, следовательно соединение не будет установлено, и трекер будет думать, что вы за натом/стенкой', 1, 7, 3);
INSERT INTO `faq` VALUES (36, 'item', 'Почему я не могу раздавать?', 'Только специальные пользователи (<font color="orange"><b>Релизеры</b></font>) имеют право заливать торренты.', 1, 4, 1);
INSERT INTO `faq` VALUES (37, 'item', 'Что мне надо сделать, чтобы стать <font color="orange">Релизером</font> ?', 'Вам необходимо написать ЛС с соответствующей просьбой <a class=altlink href=staff.php>администрации</a>. После того, как администрация ознакомится с ней, мы примем решение о Вашем повышении.\r\n\r\n<br><br><b>Требования к кандидатам:</b>\r\n<li>Иметь скорость аплоада более 25 кБ/с</li>\r\n<li>Вы должны быть готовы сидировать торрент как минимум 24 часа или до появления 2-х скачавших.</li>\r\n</ul>\r\n', 1, 4, 2);
INSERT INTO `faq` VALUES (38, 'item', 'Могу ли я раздавать ваши торренты на других трекерах?', 'Нет. У нас закрытое, с ограниченным количеством пользователей сообщество. Только зарегестрированные пользователи имеют право использовать данный трекер. Размещение наших торрентов на других трекерах приведёт к тому, что пользователи, которые скачают торрент-файл (на другом трекере), не смогут соединиться с нашим сервером.<br>\r\n<br>\r\n(Однако вы можете пользоваться скачанным контентом как вам заблагорассудиться. Вы в любой момент можете создать торрент-файл, разместить его на другом трекере и раздать его).\r\n', 1, 4, 3);
INSERT INTO `faq` VALUES (39, 'item', 'Как мне использовать файлы, которые я загрузил?', 'Скорее всего ответ вы найдёте в этом <a class=altlink href=formatss.php?form=mov>описании</a>.', 1, 5, 1);
INSERT INTO `faq` VALUES (40, 'item', 'Я скачал фильм, но не понимаю, что означают эти CAM/TS/TC/SCR ?', 'Посмотрите <a class=altlink href=formatss.php?form=mov>здесь</a>.', 1, 5, 2);
INSERT INTO `faq` VALUES (41, 'item', 'Почему торрент, только что бывший активным, вдруг исчез!?', 'На это может быть несколько причин:<br>\r\n(<b>1</b>) Торрент не соответствовал <a class=altlink href=rules.php>правилам</a>.<br>\r\n(<b>2</b>) Аплоадер мог удалить его, т.к. выяснилось, что это был плохой релиз. Скорее всего он будет заменён на другой.<br>\r\n(<b>3</b>) Торренты автоматически удаляются по истечении 28 дней.', 1, 5, 3);
INSERT INTO `faq` VALUES (42, 'item', 'Как можно продолжить скачивание/раздачу, если торрента нет в списке в моём клиенте (из-за глюка, или из-за смены клиента, и т.д.)?', 'Откройте *.torrent файл. Когда ваш клиент спросит куда сохранять - выберите путь к уже существующим файлам. Скачивание продолжиться дальше.\r\n', 1, 5, 4);
INSERT INTO `faq` VALUES (43, 'item', 'Почему мои загрузки иногда останавливаются на 99%?', 'У вас уже скачано достаточно большое количество частей, и клиент пытается найти пользователей, у которых есть части, которые у вас не скачаны, или скачаны с ошибками. Поэтому загрузка иногда может останавливаться в тот момент, когда до завершения осталось всего несколько процентов. Потерпите немножко, и в скором (ну или не очень :) ) времени клиент докачает все недостающие части. Так же такой глюк может возникать при использовании некоторых клиентов.', 1, 5, 5);
INSERT INTO `faq` VALUES (44, 'item', 'Что значит сообщение "a piece has failed an hash check"?', 'Торрент-клиенты проверяют принятые данные на целостность. Когда часть закачана с ошибками она автоматически загружается заново. Это происходит практически у всех, так что не беспокойтесь.<br>\r\n<br>\r\nВ некоторых клиентах есть возможность автоматически игнорировать пользователей, которые присылают вам части с ошибками. Если вы хотите, чтобы в дальнейшем вы не принимали частей от этого пользователя, вам необходимо активизировать данную функцию в вашем клиенте.', 1, 5, 6);
INSERT INTO `faq` VALUES (45, 'item', 'Размер торрента 100Мб. Как я мог скачать 120Мб?', 'Смотрите предыдущий пункт. Если ваш клиент получил часть с ошибками, он перезакачает её. Таким образом, общее количество скачанного может быть больше, чем размер торрента.', 1, 5, 7);
INSERT INTO `faq` VALUES (46, 'item', 'Почему мне выдается ошибка "Запрещено! Будет доступно через xxx часов"?', 'Когда на трекере появляется <b>новый</b> торрент, некоторые пользователи должны подождать какое-то количество времени прежде чем они смогут его скачать. Это касается только пользователей с низким рейтингом, и пользователей с небольшим количеством отданного трафика.<br>\r\n<br>\r\nThis applies to new users as well, so opening a new account will not help. Note also that this\r\nworks at tracker level, you will be able to grab the .torrent file itself at any time.<br>\r\n<br>\r\n<!--The delay applies only to leeching, not to seeding. If you got the files from any other source and\r\nwish to seed them you may do so at any time irrespectively of your ratio or total uploaded.<br>-->\r\nN.B. Due to some users exploiting the ''no-delay-for-seeders'' policy we had to change it. The delay\r\nnow applies to both seeding and leeching. So if you are subject to a delay and get the files from\r\nsome other source you will not be able to seed them until the delay has elapsed.', 0, 5, 8);
INSERT INTO `faq` VALUES (47, 'item', 'Почему выскакивает ошибка "rejected by tracker - Port xxxx is blacklisted', 'Ваш торрент-клиент сообщил серверу, что он использует порты по умолчанию для торрента (6881-6889), или порты связанные с другими p2p программами.<br>\r\n<br>\r\nВаш торрент-клиент сообщил серверу, что он использует порты по умолчанию для торрента (6881-6889), или порты связанные с другими p2p программами. <br>\r\n<br>\r\nПорты, которые обычно блокируются вы можете посмотреть в списке ниже, но не факт, что ваш провайдер блокирует только их:<br>\r\n<br>\r\n<table cellspacing=3 cellpadding=0>\r\n  <tr>\r\n    <td class=embedded width="80">Direct Connect</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">411 - 413</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">Kazaa</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">1214</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">eDonkey</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">4662</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">Gnutella</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">6346 - 6347</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">BitTorrent</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">6881 - 6889</div></td>\r\n </tr>\r\n</table>\r\n<br>\r\nЧтобы использовать наш трекер вы должны настроить ваш клиент на использование других портов (желательно в диапазоне 49152-65535).  Помните, что такие клиенты, как Azureus используют один порт на все торренты, в то время, как большинство других используют разные порты на каждый торрент. Эти порты используются для соединения между пользователями, а не между клиентом и сервером! Вот почему изменение никак не отразится на вашей возможности использовать другие трекеры, и во многих случаях эта мера позволит вам повысить скорость между вами и другими участниками. Ваш клиент так же сможет подключатся к другим пользователям, у которых в настройках установлены стандартные порты.<br>\r\n<br>\r\nНе спрашивайте у нас и на форуме, какие порты вам необходимо выбрать. Чем больше разброс портов, которые используют пользователи, тем меньше шансов у провайдеров вычислить и закрыть порты.\r\n', 1, 5, 9);
INSERT INTO `faq` VALUES (48, 'item', 'Что такое "IOError - [Errno13] Permission denied"?', 'Если вы просто хотите решить эту проблему - перезагрузите компьютер, это должно помочь. Любопытным читать дальше.<br>\r\n<br>\r\nIOError означает ошибку Ввод-Вывода, и это ошибка вашей системы(компьютера) а не трекера. Она выскакивает, когда клиент по некоторым причинам не может открыть скачанные файлы. Наиболее вероятная причина - запущенные одновременно 2 клиента: это может происходить, например, если вы закрыли клиент, но на самом деле он не закрылся, и продолжал работать в фоне, затем вы запустили вторую копию клиента, но первый всё ещё блокирует файлы, второй не может получить к ним доступ, и выкидывает вам эту ошибку.<br>\r\n<br>\r\nНаиболее редкий случай - это нарушение FAT-таблицы вашей файловой системы, что может привести к нечитабельности загруженных файлов. Соответственно будет выскакивать такая ошибка. (Это может произойти если вы используете Windows 9x - которые поддерживают только FAT, или в вашей NT/2000/XP диск отформатирован именно в FAT. NTFS более надёжная файловая система, и не должна приводить к таким ошибкам).', 1, 5, 10);
INSERT INTO `faq` VALUES (49, 'item', 'Что такое "TTL" на страницах?', 'Это время жизни конкретного торрента. Показывает через какое время торрент будет удалён с сервера (да, даже если он будет активным!). Помните, что это максимальное значение, торрент может быть удалён в любое время, если он неактивен.', 1, 5, 11);
INSERT INTO `faq` VALUES (50, 'item', 'Не набрасывайтесь на новые торренты', 'Особенно, если у вас медленная скорость. Дайте скачать в первую очередь людям с широкими каналами, которые потом будут раздавать это, в том числе и вам.<br>\r\n<br>\r\nНаилучшее время для скачивания находится примерно в середине жизни торрента, именно в этот момент SLR достигает своего апогея, и вы можете качать с максимальной скоростью. (Однако в этом случае у вас не будет возможности раздавать так долго, как если бы вы скачали данный файл с самого начала. Вот вам задачка: необходимо балансировать между этими 2мя критериями.', 1, 6, 1);
INSERT INTO `faq` VALUES (51, 'item', 'Ограничьте свою скорость раздачи', 'Скорость раздачи может негативно сказываться на скорости загрузки в двух случаях:<br>\r\n<ul>\r\n      <li>Торрент участники имеют тенденции к тому, чтобы поощрять тех, кто им раздаёт. Это означает что если А и Б скачивают один и тот же файл, и А отсылает данные Б с высокой скоростью, тогда Б будет стараться поделиться тоже. Таким образом высокая скорость раздачи ведёт к высокой скорости загрузки.</li>\r\n      <li>Однако, когда А загрузил что-то с Б, он должен сказать Б, что посланные данные были успешно получены. (Это называется подтверждения(acknowledgements) - ACKs - это один из видов сообщения "получено!"). Если А не смог отправить такой ответ Б, тогда он (Б) приостановит раздачу ему (А) и будет ждать. Если А раздаёт на полной скорости может случиться так, что подтверждения (ACKs) будут задерживаться. Таким образом раздача на полной скорости приведёт к снижению скорости загрузки.</li>\r\n</ul>\r\n\r\nНаилучшего результата вы достигнете, балансируя между этими 2мя пунктами. Скорость аплоада должна быть максимально высокой, при которой ACKs проходят без задержек. <b>Наилучший вариант - это ограничить вашу скорость аплоада до 80% от теоретически возможной.</b> Однако вам может понадобиться более точная настройка, которая будет наилучшим вариантом именно в вашем случае. (Помните, что поддержание высокй скорости раздачи положительно сказывается на вашем рейтинге(ratio)). <br>\r\n<br>\r\nНекоторые клиенты (напр. Azureus) ограничивают общую скорость раздачи, другие (напр. Shad0w`s) позволяют ограничить каждый торрент. Изучите ваш клиент, и задумывайтесь о скорости аплоада, в то время, как вы используете свой канал для чего-то ещё (серфинг, фтп и т.д.).', 1, 6, 2);
INSERT INTO `faq` VALUES (52, 'item', 'Ограничьте количество одновременных соединений', 'Некоторые операционные системы (такие как Windows 9x) не очень хорошо переваривают большое количество соединений, и даже могут повиснуть. Так же, некоторые домашние роутеры (особенно когда запущен NAT и/или файрвол в режиме он-лайн сканирования) могут снижать скорость или зависать когда слишком много соединений. Не существует единого работоспособного значения, однако вы можете попробовать установить, экспериментальным путём, количество одновременных соединений в пределах от 60 до 100. Необходимо учитывать, что это значение суммируемое, т.е. если у вас запущено несколько клиентов, количество соединений всего будет равно сумме соединений каждого из них.', 1, 6, 3);
INSERT INTO `faq` VALUES (53, 'item', 'Ограничьте количество одновременных раздач', 'А разве это не то же самое, о чем сказано выше? Нет. Ограничение количества соединений означает ограничение количества участников, с которыми ваш клиент контактирует и/или с которых скачивает. Ограничение раздачь подразумевает ограничение количества участников, которым вы раздаёте. Идеальное значение этого параметра обычно намного ниже, чем количество одновременных соединений, и непосредственно зависит от скорости вашего (физического) подключения.', 1, 6, 4);
INSERT INTO `faq` VALUES (54, 'item', 'Подождите немного', 'Как описано выше, другие участники стараются в первую очередь делиться с теми, кто им раздаёт. Когда вы только начинаете скачивать новый файл, у вас нечего предложить другим участникам, и они будут игнорировать вас. Это приведёт к тому, что в начале загрузки скорость будет достаточно низкой, особенно, если не соеденены ни с одним или с очень малым количеством сидеров. Скорость загрузки должна увеличиться как только у вас появятся несколько частей для раздачи.', 1, 6, 5);
INSERT INTO `faq` VALUES (55, 'item', 'Почему страницы так медленно открываются, когда я качаю что-то?', 'Ваша скорость загрузки имеет конечное значение (зависит от вашего провайдера, тарифа и т.д. и т.п.). Если вы участник быстрого торрента это привидёт к тому, что ваш канал будет загружен по максимуму, и соответсвенно серфинг будет очень медленным. Однако, вы можете ограничить скорость загрузки в вашем клиенте. А так же вы можете использовать другие программы для ограничения загрузки канала определённой программой, например при помощи <a class=altlink href="redirector.php?url=http://www.netlimiter.com/">NetLimiter</a>.<br>\r\n<br>\r\n(Серфинг взят просто для примера, та же самая картина будет наблюдаться при игре через интернет, скачивании чего-либо по http/ftp, и т.д.)', 1, 6, 6);
INSERT INTO `faq` VALUES (56, 'item', 'Что такое прокси (proxy)?', 'Можно сказать, что это посредник. Когда вы серфите по интернету, прокси-сервер получает ваш запрос, и перенаправляет его на сайт, к которому вы хотите подключиться. Бывает несколько классов прокси (терминология далека от стандартной):<br>\r\n<br>\r\n\r\n\r\n<table cellspacing=3 cellpadding=0>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA" width="100">&nbsp;Прозрачные</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">Прозрачные прокси не требуют настроек клиентов. Они работают путём автоматического перенаправления траффика с 80го порта на прокси. (Иногда используется как синоним не анонимных прокси.)</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp;Явные/Бесплатные</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">Вы должны настроить свой браузер, что бы использовать их.</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp; Анонимные</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">Данный тип прокси не отсылает данные по клиенту на сервер (заголовок HTTP_X_FORWARDED_FOR не отсылается; и сервер не видит ваш IP.)</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp; Очень анонимные</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">Прокси не посылает на сервер ни информации о клиенте, ни инофрмации о прокси (заголовки HTTP_X_FORWARDED_FOR, HTTP_VIA и HTTP_PROXY_CONNECTION не отсылаются; сервер не видит ваш IP и не знает что вы используете прокси).</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp;Public</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">(Self explanatory)</td>\r\n </tr>\r\n</table>\r\n<br>\r\nПрозрачные прокси могут быть, а могут и не быть анонимными. В свою очередь анонимные прокси имеют несколько уровней анонимности.\r\n', 1, 7, 1);
INSERT INTO `faq` VALUES (57, 'item', 'Как обнаружить, что я сижу за прокси?', 'Попробуйте <a href=http://proxyjudge.org class="altlink">ProxyJudge</a>. Он выдаст вам HTTP заголовки, которые получил сервер от вас. Самые важные - это HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR и REMOTE_ADDR.<br>\r\n', 1, 7, 2);
INSERT INTO `faq` VALUES (58, 'item', 'Можно ли обойти прокси моего провайдера?', 'Если ваш провайдер разрешает только HTTP траффик через 80й порт, или блокирует стандартные прокси-порты, тогда попробуйте что-то вроде этого <a href=http://www.socks.permeo.com>socks</a>. Данный вопрос выходит далеко за пределы данного FAQ.', 1, 7, 4);
INSERT INTO `faq` VALUES (74, 'item', 'А что означает этот значок <img src="pic/freedownload.gif" border="0"> около торрента в списке?', 'Этот значок означает, что торрент "бесплатный", то есть если вы будете его качать, то у вас будет считаться только количество загруженной информации. Все что вы скачаете на этом торренте не будет записано в глобальную статистику.', 1, 2, 14);
INSERT INTO `faq` VALUES (59, 'item', 'Как сделать, чтоб мой торрент-клиент использовал прокси?', 'Когда вы настраиваете прокси для Internet Explorer`a, вы фактически настраиваете прокси для всего http-траффика (скажите спасибо Microsoft, и за то что их IE является частью операционной системы). С другой стороны, если вы используете другой броузер (Opera/Mozilla/Firefox и т.д.) и настраиваете в нем прокси - эти настройки будут действовать только на этот броузер. Мы не знаем торрент клиенты, позволяющие настраивать прокси только для себя.', 1, 7, 5);
INSERT INTO `faq` VALUES (60, 'item', 'Почему я не могу зарегистрироваться из под прокси?', 'У нас такие правила - мы не позволяем создавать новые аккаунты из под прокси.', 1, 7, 6);
INSERT INTO `faq` VALUES (61, 'item', 'Применимы ли эти установки к другим торрент-сайтам?', 'Эта секция была написана специально для нашего трекера. Другие трекеры могут быть открытими или закрытыми, и могут работать совершенно на других портах (например 6868 или 6969). Всё вышесказанное не обязательно применимо к другим трекерам.', 1, 7, 7);
INSERT INTO `faq` VALUES (62, 'item', 'Может мой IP занесён в черный список?', 'Наш сайт блокирует все IP, перечисленные в базе данных <a class=altlink href="http://methlabs.org/">PeerGuardian</a>\r\nтак же, как и адреса забаненных пользователей. Это работает на уровне Apache/PHP и представляет из себя обычный скрипт, который блокирует <i>логины</i> с этих адресов. Хотя не блокирует низкоуровневых протоколов, вам необходимо попытаться сделать ping/traceroute. Если они не проходят, значит причина не в бане.<br>\r\n<br>\r\nЕсли всё-таки причина в бане, и ваш IP адрес действительно находиться в базе данных PG, не просите нас о том, чтоб мы исключили вас из него. Это не в наших правилах. Вам необходимо очистить свой IP связавшись с разработчиками данной базы данных.', 1, 8, 1);
INSERT INTO `faq` VALUES (63, 'item', 'Ваш провайдер блокирует адрес нашего сайта', '(Прежде всего, маловероятно, что ваш провайдер делает это. Чаще бывают виноваты DNS-сервера, и/или временные (или постоянные) проблемы с вашей сетью/настройками). \r\n<br>\r\nОднако, если это правда, мы ничем не сможем вам помочь. Вам необходимо связатся с вашим провайдером (или подключиться к другому).<br>\r\n<br>\r\nПомните, что вы будете всё время отображены, как не подключенный, потому что трекер не сможет проверить принимает ли ваш клиент входящие соединения.', 1, 8, 2);
INSERT INTO `faq` VALUES (64, 'item', 'Alternate port (81)', 'Some of our torrents use ports other than the usual HTTP port 80. This may cause problems for some users,\r\nfor instance those behind some firewall or proxy configurations.\r\n\r\nYou can easily solve this by editing the .torrent file yourself with any torrent editor, e.g.\r\n<a href="http://sourceforge.net/projects/burst/" class="altlink">MakeTorrent</a>,\r\nand replacing the announce url bit-torrent.kiev.ua:81 with bit-torrent.kiev.ua:80 or just templateshares.net.<br>\r\n<br>\r\nEditing the .torrent with Notepad is not recommended. It may look like a text file, but it is in fact\r\na bencoded file. If for some reason you must use a plain text editor, change the announce url to\r\nbit-torrent.kiev.ua:80, not bit-torrent.kiev.ua. (If you''re thinking about changing the number before the\r\nannounce url instead, you know too much to be reading this.)', 0, 8, 3);
INSERT INTO `faq` VALUES (65, 'item', 'В можете попробывать это:', 'Если Вы не нашли решение своей проблемы здесь, напишите на <a class="altlink" href="http://forum.pdaprime.ru/index.php?showtopic=44987">Форум</a>, либо спросить о проблеме у <a href="staff.php">администрации</a> через ЛС или через <a href="contact.php">форму быстрой связи</a>. Вы легко найдете необходимую Вам помощь, если будете следовать нескольким основным правилам:\r\n<ul>\r\n<li>Вы легко найдете необходимую Вам помощь, если будете следовать нескольким основным правилам.</li>\r\n<li>Перед тем, как задавать вопрос, прочтите важные темы (находящиеся на верху веток форума). Часто новая информация, не успевшая попасть в FAQ, может быть найдена в этих темах.</li>\r\n<li>Помогите нам помочь Вам. Не ограничивайтесь фразами типа "ничего не работает!". Сообщите детали проблемы, так чтобы нам не пришлось догадываться о том, что Вы хотели сказать, или терять время на встречные вопросы.\r\nКакой клиент Вы используете? Какая у Вас ОС? Какие настройки у Вашей сети? Какое конкретно сообщение об ошибке Вы получили, если получили? С какими торрентами у Вас возникли проблемы?\r\nЧем больше подробностей Вы нам расскажете, тем легче нам будет Вам помочь, и больше будет шансов на то, что Ваш вопрос не останется без ответа.</li>\r\n<li><b>И нет нужды говорить: будьте вежливы. Требования помощи редко срабатывают, в то время как просьбы о помощи обычно не остаются без ответа.</b>', 1, 9, 1);
INSERT INTO `faq` VALUES (67, 'item', 'Что такое Пасскей и зачем он нужен?', '<b>Пасскей</b> - это ваш пароль, используемый БитТоррент-клиентом для того, чтобы трекер узнавал, с какого аккаунта качают.<br>\r\n<br>\r\nИспользование пасскея позволяет вне зависимости от того, какое количество пользователей используют один IP-адрес всегда точно записывать и учитывать в профиле пользователя то, что сообщает БитТоррент-клиент трекеру.<br>\r\n<br>\r\nПасскей записывается в торрент-файл при скачивании его с трекера. Это значит, что каждый ваш торрент содержит пасскей, зная который любой человек может скачивать что-либо от вашего имени. А это в свою очередь может пагубно отразиться на вашем рейтинге.<br>\r\nЗаписывается Пасскей в Announce-URL в таком виде: http://merdox.ints.ru/announce.php?passkey=<passkey>, где <passkey> - набор из 32 латинских букв и арабских цифр. Пjэтому когда делаете скриншот вашего БитТоррент-клиента, следите, чтобы пасскей не был виден (замазывайте его или вырезайте).\r\n', 1, 5, 13);
INSERT INTO `faq` VALUES (68, 'item', 'Why do i get a "Unknown Passkey" error? ', 'You will get this error, firstly if you are not registered on our tracker, or if you havent downloaded the torrent to use from our webpage, when you were logged in. In this case, just register or log in and redownload the torrent.\r\n\r\nThere is a chance to get this error also, at the first time you download anything as a new user, or at the first download after you reset your passkey. The reason is simply that the tracker reviews the changes in the passkeys every few minutes and not instantly. For that reason just leave the torrent running for a few minutes, and you will get eventually an OK message from the tracker.', 0, 5, 14);
INSERT INTO `faq` VALUES (69, 'item', 'When do i need to reset my passkey? ', '<ul><li> If your passkey has been leeched and other user(s) uses it to download torrents using your account. In this case, you will see torrents stated in your account that you are not leeching or seeding .</li>\r\n<li> When your clients hangs up or your connection is terminated without pressing the stop button of your client. In this case, in your account you will see that you are still leeching/seeding the torrents even that your client has been closed. Normally these "ghost peers" will be cleaned automatically within 30 minutes, but if you want to resume your downloads and the tracker denied that due to the fact that you "already are downloading the same torrents - Connection limit error" then you should reset your passkey and redownload the torrent, then resume it. </li></ul>', 0, 5, 15);
INSERT INTO `faq` VALUES (70, 'item', 'Что такое DHT и почему должно быть выключено?', 'DHT должно быть выключено в Вашем клиенте, DHT может быть причиной некорректного отображения отображения вашего рейтинга и будет расценено как читерство. Любой, использующий DHT будет забанен за обмен системы. Проверяйте свою статистику регулярно, чтобы убедиться, что подсчитвается правильно; каждые 30 минут трекер обновляет вашу статистику.', 1, 5, 15);
INSERT INTO `faq` VALUES (71, 'item', 'Рекомендуемые BT-клиенты:', '<b>Кроссплатформенные клиенты:</b><br>\r\nAzureus<br>\r\nBitTornado<br>\r\n<br>\r\n<b>Клиенты под Win:</b><br>\r\nµTorrent<br>\r\nABC<br>\r\n<br>\r\n<b>Клиенты под Mac:</b><br>\r\nTomato Torrent<br>\r\nBitRocket (lastest version)<br>\r\nrtorrent<br>\r\n<br>\r\n<b>Клиент под Linux:</b><br>\r\nrtorrent<br>\r\nktorrent<br>\r\ndeluge<br>', 1, 1, 4);

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
  `spamid` int(10) NOT NULL default '0',
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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=24 ;

-- 
-- Дамп данных таблицы `orbital_blocks`
-- 

INSERT INTO `orbital_blocks` VALUES (1, '', 'Администрация', '<table border="0"><tr>\r\n<td class="block"><a href="admincp.php">Админка</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="online.php">Ху из онлайн?!</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="newsarchive.php">Редактировать новости</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="users.php">Список пользователей</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="viewreport.php">Жалобы на торренты</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="staffmess.php">Массовое ЛС</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="ipcheck.php">Двойники по IP</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="admincp.php?op=iUsers">Сменить пароль и мыло юзверю</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="clearcache.php">Очистить кеш</a></td>\r\n</tr>\r\n</table>', 'l', 3, 1, '', '', 2, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (8, '', 'Статистика', '', 'd', 2, 1, '', 'block-stats.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (9, '', 'Фильмы, которым нужны раздающие', '', 'c', 1, 1, '', 'block-helpseed.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (10, '', 'Напоминание о правилах', '<p align="jsutify">Администрация данного сайта - прирожденные садисты и кровопийцы, которые только и ищут повод помучать и поиздеваться над пользователями, используя для этого самые изощренные пытки. Единственный способ избежать этого - не попадаться нам на глаза, то есть спокойно качать и раздавать, поддерживая свой рейтинг как можно ближе к 1, и не делать глупых комментариев к торрентам. И не говорите, что мы вас не предупреждали! (шутка)</p>', 'c', 2, 0, '', '', 0, '0', 'd', 'rules,');
INSERT INTO `orbital_blocks` VALUES (2, '', 'Новости', '', 'c', 3, 1, '', 'block-news.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (3, '', 'Пользователи', '', 'd', 1, 1, '', 'block-online.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (4, '', 'Поиск', '', 'l', 4, 1, '', 'block-search.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (5, '', 'Опрос', '', 'c', 4, 1, '', 'block-polls.php', 1, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (6, '', 'Новые фильмы', '', 'c', 5, 0, '', 'block-releases.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (7, '', 'Чего там на форуме творится?)', '', 'c', 6, 0, '', 'block-forum.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (11, '', 'Загрузка сервера', '', 'c', 7, 1, '', 'block-server_load.php', 2, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (12, '', 'Торренты на главной', '', 'c', 8, 1, '', 'block-indextorrents.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (13, '', 'Пожертвования', '<center><a href="javascript:void(0)" title="SMS.копилка в новом маленьком окошке" onClick="javascript:window.open(''http://smskopilka.ru/?info&id=36066'', ''smskopilka'',''width=400,height=480,status=no,toolbar=no, menubar=no,scrollbars=yes,resizable=yes'');">\r\n<img src="http://img.smskopilka.ru/common/digits/target2/36/36066-101.gif" border="0" alt="SMS.копилка"></a><br>\r\nWebMoney:<br>\r\nR153898361884\r\nZ113282224168<br><hr>\r\nЗаранее спасибо!</center> ', 'l', 5, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (14, '', 'Проблемы ?', '<center>\r\n<a href="contact.php"><font color="red"><u>Написать админу!</u></font></a>\r\n</center><br>\r\n<i>..с регистрацией<br>\r\n..с сайтом<br>\r\n..с торрентами</i>', 'l', 6, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (15, '', 'Vote 4 us!', 'none', 'l', 7, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (23, '', 'Облако тегов', '', 'l', 1, 1, '', 'block-cloud.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (16, '', 'Друзья', 'none', 'l', 8, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (17, '', 'Запросы', '', 'l', 9, 1, '', 'block-req.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (18, '', 'Вопросы ?', 'none', 'l', 10, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (19, '', 'Меню', '', 'l', 2, 1, '', 'block-login.php', 0, '0', 'd', 'ihome,');
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
-- Структура таблицы `polls`
-- 

CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `question` varchar(255) NOT NULL,
  `start` int(10) NOT NULL,
  `exp` int(10) default NULL,
  `public` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `polls`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `polls_structure`
-- 

CREATE TABLE `polls_structure` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `polls_structure`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `polls_votes`
-- 

CREATE TABLE `polls_votes` (
  `vid` int(10) unsigned NOT NULL auto_increment,
  `sid` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY  (`vid`),
  UNIQUE KEY `sid` (`sid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `polls_votes`
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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `ratings`
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

INSERT INTO `sessions` VALUES ('cb437926985bda184ab1bfb4e7d3e3f4', -1, '', -1, '127.0.0.1', 1229461823, '/', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4');

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `sitelog`
-- 

INSERT INTO `sitelog` VALUES (1, '2008-12-17 00:05:35', '', 'Отключено 0 пользователей (5 и более предупреждений)', 'admin');

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `stamps`
-- 


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
-- Структура таблицы `tags`
-- 

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `howmuch` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=17 ;

-- 
-- Дамп данных таблицы `tags`
-- 

INSERT INTO `tags` VALUES (1, 1, 'Комедия', 0);
INSERT INTO `tags` VALUES (2, 1, 'Сериалы', 0);
INSERT INTO `tags` VALUES (3, 1, 'Анимация', 0);
INSERT INTO `tags` VALUES (4, 1, 'Триллеры и ужасы', 0);
INSERT INTO `tags` VALUES (5, 1, 'Драма и мелодрама', 0);
INSERT INTO `tags` VALUES (6, 1, 'Фантастика и фэнтези', 0);
INSERT INTO `tags` VALUES (7, 1, 'Музыкальные фильмы', 0);
INSERT INTO `tags` VALUES (8, 1, 'Боевики и приключения', 0);
INSERT INTO `tags` VALUES (9, 1, 'Документальные и научно-познавательные фильмы', 0);
INSERT INTO `tags` VALUES (10, 1, 'Индийское кино', 0);
INSERT INTO `tags` VALUES (11, 1, 'Юмор', 0);
INSERT INTO `tags` VALUES (12, 1, 'Семейные', 0);
INSERT INTO `tags` VALUES (13, 1, 'Исторические', 0);
INSERT INTO `tags` VALUES (14, 1, 'Детективы', 0);
INSERT INTO `tags` VALUES (15, 2, 'Фантастика и фэнтези', 0);
INSERT INTO `tags` VALUES (16, 2, 'Сериалы', 0);

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
  `tags` text NOT NULL,
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
  `deletepms` enum('yes','no') NOT NULL default 'yes',
  `savepms` enum('yes','no') NOT NULL default 'no',
  `gender` enum('1','2','3') NOT NULL default '1',
  `birthday` date default '0000-00-00',
  `passkey` varchar(32) NOT NULL default '',
  `language` varchar(255) default NULL,
  `invites` int(10) NOT NULL default '0',
  `invitedby` int(10) NOT NULL default '0',
  `invitedroot` int(10) NOT NULL default '0',
  `passkey_ip` varchar(15) NOT NULL default '',
  `num_warned` int(2) NOT NULL default '0',
  `last_checked` datetime NOT NULL default '0000-00-00 00:00:00',
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

