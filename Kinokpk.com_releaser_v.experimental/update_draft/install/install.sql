SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `releaser`
--

-- --------------------------------------------------------

--
-- Структура таблицы `addedrequests`
--

CREATE TABLE `addedrequests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requestid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pollid` (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `addedrequests`
--


-- --------------------------------------------------------

--
-- Структура таблицы `bannedemails`
--

CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) NOT NULL,
  `addedby` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bannedemails`
--


-- --------------------------------------------------------

--
-- Структура таблицы `bans`
--

CREATE TABLE `bans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mask` varchar(60) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bans`
--


-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bookmarks`
--


-- --------------------------------------------------------

--
-- Структура таблицы `cache_stats`
--

CREATE TABLE `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` text,
  PRIMARY KEY (`cache_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cache_stats`
--

INSERT INTO `cache_stats` VALUES('adminemail', 'root@localhost');
INSERT INTO `cache_stats` VALUES('allow_invite_signup', '1');
INSERT INTO `cache_stats` VALUES('as_check_messages', '1');
INSERT INTO `cache_stats` VALUES('as_timeout', '15');
INSERT INTO `cache_stats` VALUES('autoclean_interval', '900');
INSERT INTO `cache_stats` VALUES('avatar_max_height', '100');
INSERT INTO `cache_stats` VALUES('avatar_max_width', '100');
INSERT INTO `cache_stats` VALUES('cache_template', '0');
INSERT INTO `cache_stats` VALUES('cache_template_time', '100');
INSERT INTO `cache_stats` VALUES('debug_language', '0');
INSERT INTO `cache_stats` VALUES('debug_mode', '1');
INSERT INTO `cache_stats` VALUES('debug_template', '0');
INSERT INTO `cache_stats` VALUES('defaultbaseurl', 'http://releaser.loc');
INSERT INTO `cache_stats` VALUES('default_emailnotifs', 'unread,friends');
INSERT INTO `cache_stats` VALUES('default_language', 'ru');
INSERT INTO `cache_stats` VALUES('default_notifs', 'unread,friends');
INSERT INTO `cache_stats` VALUES('default_theme', 'releaser330');
INSERT INTO `cache_stats` VALUES('deny_signup', '0');
INSERT INTO `cache_stats` VALUES('description', 'kinokpk.com releaser');
INSERT INTO `cache_stats` VALUES('forum_enabled', '0');
INSERT INTO `cache_stats` VALUES('keywords', 'kinokpk.com releaser');
INSERT INTO `cache_stats` VALUES('low_comment_hide', '-3');
INSERT INTO `cache_stats` VALUES('maxusers', '0');
INSERT INTO `cache_stats` VALUES('max_dead_torrent_time', '744');
INSERT INTO `cache_stats` VALUES('max_images', '4');
INSERT INTO `cache_stats` VALUES('max_torrent_size', '1000000');
INSERT INTO `cache_stats` VALUES('pm_max', '150');
INSERT INTO `cache_stats` VALUES('pron_cats', '');
INSERT INTO `cache_stats` VALUES('register_timezone', '3');
INSERT INTO `cache_stats` VALUES('re_privatekey', '6LfpSAQAAAAAAF-ZJtFTTPDDMVqkTkqlaj0uk11U');
INSERT INTO `cache_stats` VALUES('re_publickey', '6LfpSAQAAAAAALWdXKPDZn8fafwbTPTKCHg1rsoL');
INSERT INTO `cache_stats` VALUES('signup_timeout', '3');
INSERT INTO `cache_stats` VALUES('sign_length', '250');
INSERT INTO `cache_stats` VALUES('siteemail', 'noreply@localhost');
INSERT INTO `cache_stats` VALUES('sitename', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES('siteonline', '1');
INSERT INTO `cache_stats` VALUES('site_timezone', '3');
INSERT INTO `cache_stats` VALUES('static_language', '');
INSERT INTO `cache_stats` VALUES('torrentsperpage', '25');
INSERT INTO `cache_stats` VALUES('ttl_days', '28');
INSERT INTO `cache_stats` VALUES('use_blocks', '1');
INSERT INTO `cache_stats` VALUES('use_captcha', '0');
INSERT INTO `cache_stats` VALUES('use_dc', '1');
INSERT INTO `cache_stats` VALUES('use_email_act', '0');
INSERT INTO `cache_stats` VALUES('use_gzip', '0');
INSERT INTO `cache_stats` VALUES('use_ipbans', '1');
INSERT INTO `cache_stats` VALUES('use_kinopoisk_trailers', '1');
INSERT INTO `cache_stats` VALUES('use_ttl', '0');
INSERT INTO `cache_stats` VALUES('use_xbt', '0');
INSERT INTO `cache_stats` VALUES('yourcopy', '© {datenow} Your Copyright');
INSERT INTO `cache_stats` VALUES('forumurl', 'http://releaser.loc/forum');
INSERT INTO `cache_stats` VALUES('forumname', 'Форум');
INSERT INTO `cache_stats` VALUES('forum_signup_class', '3');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `seo_name` varchar(80) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `categories`
--


-- --------------------------------------------------------

--
-- Структура таблицы `censoredtorrents`
--

CREATE TABLE `censoredtorrents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `censoredtorrents`
--


-- --------------------------------------------------------

--
-- Структура таблицы `classes`
--

CREATE TABLE `classes` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `prior` int(5) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `remark` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prior` (`prior`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `classes`
--

INSERT INTO `classes` VALUES(1, 8, 'Super Administrator', '<span style="color:#0F6CEE" title="{clname}">{uname}</span>', 'sysop');
INSERT INTO `classes` VALUES(2, 7, 'Administrator', '<span style="color:green" title="{clname}">{uname}</span>', '');
INSERT INTO `classes` VALUES(3, 5, 'Moderator', '<span style="color:red" title="{clname}">{uname}</span>', '');
INSERT INTO `classes` VALUES(4, 3, 'Uploader', '<span style="color:orange" title="{clname}">{uname}</span>', 'uploader,staffbegin');
INSERT INTO `classes` VALUES(5, 2, 'VIP', '<span style="color:#9C2FE0" title="{clname}">{uname}</span>', 'vip');
INSERT INTO `classes` VALUES(6, 1, 'Power user', '', 'rating');
INSERT INTO `classes` VALUES(7, 0, 'User', '', 'reg');
INSERT INTO `classes` VALUES(8, -1, 'Guest', '', 'guest');
INSERT INTO `classes` VALUES(9, 4, 'Expert', '<span style="color:red" title="{clname}">{uname}</span>', '');
INSERT INTO `classes` VALUES(11, 6, 'Super Moderator', '<span style="color:red" title="{clname}">{uname}</span>', '');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `toid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) DEFAULT NULL,
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `replyto` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Comment is reply to ID',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`toid`),
  KEY `added` (`added`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `comments`
--


-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `flagpic` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` VALUES(1, 'Швеция', 'sweden.gif');
INSERT INTO `countries` VALUES(2, 'США', 'usa.gif');
INSERT INTO `countries` VALUES(3, 'Россия', 'russia.gif');
INSERT INTO `countries` VALUES(4, 'Финляндия', 'finland.gif');
INSERT INTO `countries` VALUES(5, 'Канада', 'canada.gif');
INSERT INTO `countries` VALUES(6, 'Франция', 'france.gif');
INSERT INTO `countries` VALUES(7, 'Германия', 'germany.gif');
INSERT INTO `countries` VALUES(8, 'Китай', 'china.gif');
INSERT INTO `countries` VALUES(9, 'Италия', 'italy.gif');
INSERT INTO `countries` VALUES(10, 'Denmark', 'denmark.gif');
INSERT INTO `countries` VALUES(11, 'Норвегия', 'norway.gif');
INSERT INTO `countries` VALUES(12, 'Англия', 'uk.gif');
INSERT INTO `countries` VALUES(13, 'Ирландия', 'ireland.gif');
INSERT INTO `countries` VALUES(14, 'Польша', 'poland.gif');
INSERT INTO `countries` VALUES(15, 'Нидерланды', 'netherlands.gif');
INSERT INTO `countries` VALUES(16, 'Бельгия', 'belgium.gif');
INSERT INTO `countries` VALUES(17, 'Япония', 'japan.gif');
INSERT INTO `countries` VALUES(18, 'Бразилия', 'brazil.gif');
INSERT INTO `countries` VALUES(19, 'Аргентина', 'argentina.gif');
INSERT INTO `countries` VALUES(20, 'Австралия', 'australia.gif');
INSERT INTO `countries` VALUES(21, 'Новая Зеландия', 'newzealand.gif');
INSERT INTO `countries` VALUES(22, 'Испания', 'spain.gif');
INSERT INTO `countries` VALUES(23, 'Португалия', 'portugal.gif');
INSERT INTO `countries` VALUES(24, 'Мексика', 'mexico.gif');
INSERT INTO `countries` VALUES(25, 'Сингапур', 'singapore.gif');
INSERT INTO `countries` VALUES(26, 'Индия', 'india.gif');
INSERT INTO `countries` VALUES(27, 'Албания', 'albania.gif');
INSERT INTO `countries` VALUES(28, 'Южная Африка', 'southafrica.gif');
INSERT INTO `countries` VALUES(29, 'Южная Корея', 'southkorea.gif');
INSERT INTO `countries` VALUES(30, 'Ямайка', 'jamaica.gif');
INSERT INTO `countries` VALUES(31, 'Люксембург', 'luxembourg.gif');
INSERT INTO `countries` VALUES(32, 'Гонк Конг', 'hongkong.gif');
INSERT INTO `countries` VALUES(33, 'Belize', 'belize.gif');
INSERT INTO `countries` VALUES(34, 'Алжир', 'algeria.gif');
INSERT INTO `countries` VALUES(35, 'Ангола', 'angola.gif');
INSERT INTO `countries` VALUES(36, 'Австрия', 'austria.gif');
INSERT INTO `countries` VALUES(37, 'Югославия', 'yugoslavia.gif');
INSERT INTO `countries` VALUES(38, 'Южные Самоа', 'westernsamoa.gif');
INSERT INTO `countries` VALUES(39, 'Малайзия', 'malaysia.gif');
INSERT INTO `countries` VALUES(40, 'Доминиканская Республика', 'dominicanrep.gif');
INSERT INTO `countries` VALUES(41, 'Греция', 'greece.gif');
INSERT INTO `countries` VALUES(42, 'Гуатемала', 'guatemala.gif');
INSERT INTO `countries` VALUES(43, 'Израиль', 'israel.gif');
INSERT INTO `countries` VALUES(44, 'Пакистан', 'pakistan.gif');
INSERT INTO `countries` VALUES(45, 'Чехия', 'czechrep.gif');
INSERT INTO `countries` VALUES(46, 'Сербия', 'serbia.gif');
INSERT INTO `countries` VALUES(47, 'Сейшельские Острова', 'seychelles.gif');
INSERT INTO `countries` VALUES(48, 'Тайвань', 'taiwan.gif');
INSERT INTO `countries` VALUES(49, 'Пуерто Рико', 'puertorico.gif');
INSERT INTO `countries` VALUES(50, 'Чили', 'chile.gif');
INSERT INTO `countries` VALUES(51, 'Куба', 'cuba.gif');
INSERT INTO `countries` VALUES(52, 'Кного', 'congo.gif');
INSERT INTO `countries` VALUES(53, 'Афганистан', 'afghanistan.gif');
INSERT INTO `countries` VALUES(54, 'Турция', 'turkey.gif');
INSERT INTO `countries` VALUES(55, 'Узбекистан', 'uzbekistan.gif');
INSERT INTO `countries` VALUES(56, 'Швейцария', 'switzerland.gif');
INSERT INTO `countries` VALUES(57, 'Кирибати', 'kiribati.gif');
INSERT INTO `countries` VALUES(58, 'Филиппины', 'philippines.gif');
INSERT INTO `countries` VALUES(59, 'Burkina Faso', 'burkinafaso.gif');
INSERT INTO `countries` VALUES(60, 'Нигерия', 'nigeria.gif');
INSERT INTO `countries` VALUES(61, 'Исландия', 'iceland.gif');
INSERT INTO `countries` VALUES(62, 'Науру', 'nauru.gif');
INSERT INTO `countries` VALUES(63, 'Словакия', 'slovenia.gif');
INSERT INTO `countries` VALUES(64, 'Туркменистан', 'turkmenistan.gif');
INSERT INTO `countries` VALUES(65, 'Босния', 'bosniaherzegovina.gif');
INSERT INTO `countries` VALUES(66, 'Андора', 'andorra.gif');
INSERT INTO `countries` VALUES(67, 'Литва', 'lithuania.gif');
INSERT INTO `countries` VALUES(68, 'Македония', 'macadonia.gif');
INSERT INTO `countries` VALUES(69, 'Нидерландские Антиллы', 'nethantilles.gif');
INSERT INTO `countries` VALUES(70, 'Украина', 'ukraine.gif');
INSERT INTO `countries` VALUES(71, 'Венесуела', 'venezuela.gif');
INSERT INTO `countries` VALUES(72, 'Венгрия', 'hungary.gif');
INSERT INTO `countries` VALUES(73, 'Румыния', 'romania.gif');
INSERT INTO `countries` VALUES(74, 'Вануату', 'vanuatu.gif');
INSERT INTO `countries` VALUES(75, 'Вьетнам', 'vietnam.gif');
INSERT INTO `countries` VALUES(76, 'Trinidad & Tobago', 'trinidadandtobago.gif');
INSERT INTO `countries` VALUES(77, 'Гондурас', 'honduras.gif');
INSERT INTO `countries` VALUES(78, 'Киргистан', 'kyrgyzstan.gif');
INSERT INTO `countries` VALUES(79, 'Эквадор', 'ecuador.gif');
INSERT INTO `countries` VALUES(80, 'Багамы', 'bahamas.gif');
INSERT INTO `countries` VALUES(81, 'Перу', 'peru.gif');
INSERT INTO `countries` VALUES(82, 'Камбоджа', 'cambodia.gif');
INSERT INTO `countries` VALUES(83, 'Барбадос', 'barbados.gif');
INSERT INTO `countries` VALUES(84, 'Бенгладеш', 'bangladesh.gif');
INSERT INTO `countries` VALUES(85, 'Лаос', 'laos.gif');
INSERT INTO `countries` VALUES(86, 'Уругвай', 'uruguay.gif');
INSERT INTO `countries` VALUES(87, 'Antigua Barbuda', 'antiguabarbuda.gif');
INSERT INTO `countries` VALUES(88, 'Парагвая', 'paraguay.gif');
INSERT INTO `countries` VALUES(89, 'Тайланд', 'thailand.gif');
INSERT INTO `countries` VALUES(90, 'СССР', 'ussr.gif');
INSERT INTO `countries` VALUES(91, 'Senegal', 'senegal.gif');
INSERT INTO `countries` VALUES(92, 'Того', 'togo.gif');
INSERT INTO `countries` VALUES(93, 'Северная Корея', 'northkorea.gif');
INSERT INTO `countries` VALUES(94, 'Хорватия', 'croatia.gif');
INSERT INTO `countries` VALUES(95, 'Эстония', 'estonia.gif');
INSERT INTO `countries` VALUES(96, 'Колумбия', 'colombia.gif');
INSERT INTO `countries` VALUES(97, 'Леванон', 'lebanon.gif');
INSERT INTO `countries` VALUES(98, 'Латвия', 'latvia.gif');
INSERT INTO `countries` VALUES(99, 'Коста Рика', 'costarica.gif');
INSERT INTO `countries` VALUES(100, 'Египт', 'egypt.gif');
INSERT INTO `countries` VALUES(101, 'Болгария', 'bulgaria.gif');
INSERT INTO `countries` VALUES(102, 'Исла де Муерто', 'jollyroger.gif');
INSERT INTO `countries` VALUES(103, 'Казахстан', 'kazahstan.png');
INSERT INTO `countries` VALUES(104, 'Молдова', 'moldova.gif');
INSERT INTO `countries` VALUES(105, 'Беларусь', 'belarus.gif');
INSERT INTO `countries` VALUES(106, 'Азербайджан', 'azerbaijan.gif');

-- --------------------------------------------------------

--
-- Структура таблицы `cron`
--

CREATE TABLE `cron` (
  `cron_name` varchar(255) NOT NULL,
  `cron_value` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cron_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cron`
--

INSERT INTO `cron` VALUES('remote_trackers_delete', 100);
INSERT INTO `cron` VALUES('autoclean_interval', 180);
INSERT INTO `cron` VALUES('cron_is_native', 0);
INSERT INTO `cron` VALUES('delete_votes', 1440);
INSERT INTO `cron` VALUES('in_cleanup', 0);
INSERT INTO `cron` VALUES('in_remotecheck', 0);
INSERT INTO `cron` VALUES('last_cleanup', 0);
INSERT INTO `cron` VALUES('last_remotecheck', 0);
INSERT INTO `cron` VALUES('max_dead_torrent_time', 744);
INSERT INTO `cron` VALUES('num_checked', 0);
INSERT INTO `cron` VALUES('num_cleaned', 0);
INSERT INTO `cron` VALUES('pm_delete_sys_days', 15);
INSERT INTO `cron` VALUES('pm_delete_user_days', 30);
INSERT INTO `cron` VALUES('promote_rating', 50);
INSERT INTO `cron` VALUES('rating_checktime', 180);
INSERT INTO `cron` VALUES('rating_discounttorrent', 1);
INSERT INTO `cron` VALUES('rating_dislimit', -200);
INSERT INTO `cron` VALUES('rating_downlimit', -10);
INSERT INTO `cron` VALUES('rating_enabled', 1);
INSERT INTO `cron` VALUES('rating_freetime', 7);
INSERT INTO `cron` VALUES('rating_max', 300);
INSERT INTO `cron` VALUES('rating_perdownload', 1);
INSERT INTO `cron` VALUES('rating_perinvite', 5);
INSERT INTO `cron` VALUES('rating_perleech', 1);
INSERT INTO `cron` VALUES('rating_perrelease', 5);
INSERT INTO `cron` VALUES('rating_perrequest', 10);
INSERT INTO `cron` VALUES('rating_perseed', 1);
INSERT INTO `cron` VALUES('remotecheck_disabled', 0);
INSERT INTO `cron` VALUES('remotecheck_interval', 60);
INSERT INTO `cron` VALUES('remotepeers_cleantime', 10800);
INSERT INTO `cron` VALUES('remote_trackers', 50);
INSERT INTO `cron` VALUES('signup_timeout', 5);
INSERT INTO `cron` VALUES('ttl_days', 100);

-- --------------------------------------------------------

--
-- Структура таблицы `cron_emails`
--

CREATE TABLE `cron_emails` (
  `emails` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cron_emails`
--


-- --------------------------------------------------------

--
-- Структура таблицы `dchubs`
--

CREATE TABLE `dchubs` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(3) NOT NULL DEFAULT '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dchubs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `files`
--


-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `friendid` int(10) unsigned NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`friendid`),
  UNIQUE KEY `friendid` (`friendid`,`userid`),
  KEY `userid_2` (`userid`),
  KEY `friendid_2` (`friendid`),
  KEY `confirmed` (`confirmed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `friends`
--


-- --------------------------------------------------------

--
-- Структура таблицы `invites`
--

CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  `inviteid` int(10) NOT NULL DEFAULT '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `invites`
--


-- --------------------------------------------------------

--
-- Структура таблицы `languages`
--

CREATE TABLE `languages` (
  `lkey` varchar(255) NOT NULL,
  `ltranslate` varchar(2) NOT NULL,
  `lvalue` text NOT NULL,
  UNIQUE KEY `key` (`lkey`,`ltranslate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `languages`
--

INSERT INTO `languages` VALUES('about', 'en', 'About myself:');
INSERT INTO `languages` VALUES('about', 'ru', 'Не много о себе:');
INSERT INTO `languages` VALUES('about', 'ua', 'Про себе:');
INSERT INTO `languages` VALUES('abuse', 'en', 'Abuse');
INSERT INTO `languages` VALUES('abuse', 'ru', 'Реклама');
INSERT INTO `languages` VALUES('abuse', 'ua', 'Реклама');
INSERT INTO `languages` VALUES('Access level', 'en', 'Access level');
INSERT INTO `languages` VALUES('Access level', 'ru', 'Уровень доступа');
INSERT INTO `languages` VALUES('access level', 'ua', 'Рівень доступу');
INSERT INTO `languages` VALUES('access_closed', 'en', 'Access to this section is closed.');
INSERT INTO `languages` VALUES('access_closed', 'ru', 'Доступ в этот раздел закрыт.');
INSERT INTO `languages` VALUES('access_closed', 'ua', 'Доступ у цей розділ закритий.');
INSERT INTO `languages` VALUES('access_denied', 'en', 'Access denied.');
INSERT INTO `languages` VALUES('access_denied', 'ru', 'Доступ запрещен.');
INSERT INTO `languages` VALUES('access_denied', 'ua', 'Доступ заборонений.');
INSERT INTO `languages` VALUES('account', 'en', 'Account');
INSERT INTO `languages` VALUES('account', 'ru', 'Аккаунт');
INSERT INTO `languages` VALUES('account', 'ua', 'Обліковий запис');
INSERT INTO `languages` VALUES('account_activated', 'en', 'Account is activated');
INSERT INTO `languages` VALUES('account_activated', 'ru', 'Аккаунт активирован');
INSERT INTO `languages` VALUES('account_activated', 'ua', 'Обліковий запис задіяний');
INSERT INTO `languages` VALUES('account_deleted', 'en', 'Account deleted.');
INSERT INTO `languages` VALUES('account_deleted', 'ru', 'Аккаунт удален.');
INSERT INTO `languages` VALUES('account_deleted', 'ua', 'Обліковий запис вилучено.');
INSERT INTO `languages` VALUES('account_disabled', 'en', 'Your account was disabled due reason: %s');
INSERT INTO `languages` VALUES('account_disabled', 'ru', 'Ваш аккаунт отключен, прична: %s');
INSERT INTO `languages` VALUES('account_disabled', 'ua', 'Ваш обліковий запис відключений, причина: %s');
INSERT INTO `languages` VALUES('account_settings', 'en', 'Account settings');
INSERT INTO `languages` VALUES('account_settings', 'ru', 'Настройки аккаунта');
INSERT INTO `languages` VALUES('account_settings', 'ua', 'Налаштування');
INSERT INTO `languages` VALUES('acc_disabled', 'en', 'My Account is disabled, what to do?');
INSERT INTO `languages` VALUES('acc_disabled', 'ru', 'Мой аккаунт отключен, что делать?');
INSERT INTO `languages` VALUES('acc_disabled', 'ua', 'Мій акаунт відключений, що робити?');
INSERT INTO `languages` VALUES('actions', 'en', 'Actions');
INSERT INTO `languages` VALUES('actions', 'ru', 'Действия');
INSERT INTO `languages` VALUES('actions', 'ua', 'Дії');
INSERT INTO `languages` VALUES('active', 'en', 'Active');
INSERT INTO `languages` VALUES('active', 'ru', 'Активный');
INSERT INTO `languages` VALUES('active', 'ua', 'Активний');
INSERT INTO `languages` VALUES('active_connections', 'en', 'Active connections');
INSERT INTO `languages` VALUES('active_connections', 'ru', 'Активных подключений');
INSERT INTO `languages` VALUES('active_connections', 'ua', 'Активних підключень');
INSERT INTO `languages` VALUES('activities', 'en', 'Activity:');
INSERT INTO `languages` VALUES('activities', 'ru', 'Деятельность:');
INSERT INTO `languages` VALUES('activities', 'ua', 'Діяльність:');
INSERT INTO `languages` VALUES('add', 'en', 'Add');
INSERT INTO `languages` VALUES('add', 'ru', 'Добавить');
INSERT INTO `languages` VALUES('add', 'ua', 'Додати');
INSERT INTO `languages` VALUES('add a new user', 'en', 'Add a new user');
INSERT INTO `languages` VALUES('add a new user', 'ru', 'Добавить нового пользователя');
INSERT INTO `languages` VALUES('add a new user', 'ua', 'Додати нового користувача');
INSERT INTO `languages` VALUES('Add new', 'en', 'Add new');
INSERT INTO `languages` VALUES('Add new', 'ru', 'Добавить новый');
INSERT INTO `languages` VALUES('add new', 'ua', 'Додати новий');
INSERT INTO `languages` VALUES('added', 'en', 'Created');
INSERT INTO `languages` VALUES('added', 'ru', 'Дата создания');
INSERT INTO `languages` VALUES('added', 'ua', 'Дата створення');
INSERT INTO `languages` VALUES('adduser', 'en', 'Add a new userr');
INSERT INTO `languages` VALUES('adduser', 'ru', 'Добавить пользователя');
INSERT INTO `languages` VALUES('adduser', 'ua', 'Додати користувача');
INSERT INTO `languages` VALUES('add_announce_urls', 'en', 'Add/Del tracker');
INSERT INTO `languages` VALUES('add_announce_urls', 'ru', 'Добавить/удалить трекера');
INSERT INTO `languages` VALUES('add_announce_urls', 'ua', 'Додати / видалити трекер');
INSERT INTO `languages` VALUES('add_ban', 'en', 'Add a ban');
INSERT INTO `languages` VALUES('add_ban', 'ru', 'Добавить запрет');
INSERT INTO `languages` VALUES('add_ban', 'ua', 'Додати заборону');
INSERT INTO `languages` VALUES('add_comment', 'en', 'Add comment (%s)');
INSERT INTO `languages` VALUES('add_comment', 'ru', 'Добавить комментарий (%s)');
INSERT INTO `languages` VALUES('add_comment', 'ua', 'Додати коментар (%s)');
INSERT INTO `languages` VALUES('add_dc_hub', 'en', 'Add a DC hub');
INSERT INTO `languages` VALUES('add_dc_hub', 'ru', 'Добавить DC-хаб');
INSERT INTO `languages` VALUES('add_dc_hub', 'ua', 'Додати DC-хаб');
INSERT INTO `languages` VALUES('add_friend', 'en', 'Friendship');
INSERT INTO `languages` VALUES('add_friend', 'ru', 'Дружить');
INSERT INTO `languages` VALUES('add_friend', 'ua', 'Дружити');
INSERT INTO `languages` VALUES('add_group', 'en', 'Add release groups');
INSERT INTO `languages` VALUES('add_group', 'ru', 'Добавление группы');
INSERT INTO `languages` VALUES('add_group', 'ua', 'Додавання групи');
INSERT INTO `languages` VALUES('add_news', 'en', 'Add a news');
INSERT INTO `languages` VALUES('add_news', 'ru', 'Добавить новость');
INSERT INTO `languages` VALUES('add_news', 'ua', 'Додати новину');
INSERT INTO `languages` VALUES('add_new_category', 'en', 'Adding new category');
INSERT INTO `languages` VALUES('add_new_category', 'ru', 'Добавление новой категории');
INSERT INTO `languages` VALUES('add_new_category', 'ua', 'Додавання нової категорії');
INSERT INTO `languages` VALUES('add_new_categoryb', 'en', 'Add a new category');
INSERT INTO `languages` VALUES('add_new_categoryb', 'ru', 'Добавить новую категорию');
INSERT INTO `languages` VALUES('add_new_categoryb', 'ua', 'Додати нову категорію');
INSERT INTO `languages` VALUES('add_new_categoryok', 'en', 'The category successfully added');
INSERT INTO `languages` VALUES('add_new_categoryok', 'ru', 'Категория успешно добавлена');
INSERT INTO `languages` VALUES('add_new_categoryok', 'ua', 'Категорія успішно додана');
INSERT INTO `languages` VALUES('add_new_country', 'en', 'Add a New Country');
INSERT INTO `languages` VALUES('add_new_country', 'ru', 'Добавить новую страну');
INSERT INTO `languages` VALUES('add_new_country', 'ua', 'Додати нову країну');
INSERT INTO `languages` VALUES('add_new_pagescategory', 'en', 'The category successfully added');
INSERT INTO `languages` VALUES('add_new_pagescategory', 'ru', 'Добавление новой категории');
INSERT INTO `languages` VALUES('add_new_pagescategory', 'ua', 'Додавання нової категорії');
INSERT INTO `languages` VALUES('add_retracker', 'en', 'Add retracker');
INSERT INTO `languages` VALUES('add_retracker', 'ru', 'Добавить ретрекер');
INSERT INTO `languages` VALUES('add_retracker', 'ua', 'Додати ретрекер');
INSERT INTO `languages` VALUES('add_to_frends', 'en', 'Add to my friends');
INSERT INTO `languages` VALUES('add_to_frends', 'ru', 'Добавить в мои друзья');
INSERT INTO `languages` VALUES('add_to_frends', 'ua', 'Додати в мої друзі');
INSERT INTO `languages` VALUES('add_to_friends', 'en', 'Be friends');
INSERT INTO `languages` VALUES('add_to_friends', 'ru', 'Дружить');
INSERT INTO `languages` VALUES('add_to_friends', 'ua', 'Дружити');
INSERT INTO `languages` VALUES('add_user', 'en', 'Add user');
INSERT INTO `languages` VALUES('add_user', 'ru', 'Добавить пользователя');
INSERT INTO `languages` VALUES('add_user', 'ua', 'Додати користувача');
INSERT INTO `languages` VALUES('Admin', 'en', 'Admin');
INSERT INTO `languages` VALUES('Admin', 'ru', 'Админка');
INSERT INTO `languages` VALUES('admin', 'ua', 'Адмінка');
INSERT INTO `languages` VALUES('admin_search', 'en', 'Administrative search');
INSERT INTO `languages` VALUES('admin_search', 'ru', 'Административный поиск');
INSERT INTO `languages` VALUES('admin_search', 'ua', 'Адміністративний пошук');
INSERT INTO `languages` VALUES('admin_view_profile', 'en', 'You are viewing private profile as administration member');
INSERT INTO `languages` VALUES('admin_view_profile', 'ru', 'Вы просматриваете этот приватный профиль как член администрации');
INSERT INTO `languages` VALUES('admin_view_profile', 'ua', 'Ви переглядаєте цей приватний профіль як член адміністрації');
INSERT INTO `languages` VALUES('age', 'en', 'Age');
INSERT INTO `languages` VALUES('age', 'ru', 'Возраст');
INSERT INTO `languages` VALUES('age', 'ua', 'Вік');
INSERT INTO `languages` VALUES('ago', 'en', 'ago');
INSERT INTO `languages` VALUES('ago', 'ru', 'назад');
INSERT INTO `languages` VALUES('ago', 'ua', 'назад');
INSERT INTO `languages` VALUES('agree_rules', 'en', 'I agree with this rules');
INSERT INTO `languages` VALUES('agree_rules', 'ru', 'Я согласен(а)!');
INSERT INTO `languages` VALUES('agree_rules', 'ua', 'Я згоден (а)!');
INSERT INTO `languages` VALUES('All', 'en', 'All');
INSERT INTO `languages` VALUES('All', 'ru', 'Все');
INSERT INTO `languages` VALUES('all', 'ua', 'Всі');
INSERT INTO `languages` VALUES('all_db_q', 'en', 'All requests to the database took about %s seconds');
INSERT INTO `languages` VALUES('all_db_q', 'ru', 'Все запросы к базе данных заняли %s секунд');
INSERT INTO `languages` VALUES('all_db_q', 'ua', 'Всі запити до бази даних зайняли %s секунд');
INSERT INTO `languages` VALUES('all_types', 'en', '(All Types)');
INSERT INTO `languages` VALUES('all_types', 'ru', '(Все типы)');
INSERT INTO `languages` VALUES('all_types', 'ua', '(Всі типи)');
INSERT INTO `languages` VALUES('already_bookmarked', 'en', 'already in bookmarks.');
INSERT INTO `languages` VALUES('already_bookmarked', 'ru', 'уже в закладках.');
INSERT INTO `languages` VALUES('already_bookmarked', 'ua', 'вже в закладках.');
INSERT INTO `languages` VALUES('already_in_private_group', 'en', 'This user is already in your friend list!');
INSERT INTO `languages` VALUES('already_in_private_group', 'ru', 'Этот пользователь уже находится в списке ваших друзей!');
INSERT INTO `languages` VALUES('already_in_private_group', 'ua', 'Цей користувач вже знаходиться в списку ваших друзів!');
INSERT INTO `languages` VALUES('already_notified_newscomments', 'en', 'You are already signed on notifications about comments to this news');
INSERT INTO `languages` VALUES('already_notified_newscomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этой новости');
INSERT INTO `languages` VALUES('already_notified_newscomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цієї новини');
INSERT INTO `languages` VALUES('already_notified_pagecomments', 'en', 'You are already signed on notifications about comments to this page');
INSERT INTO `languages` VALUES('already_notified_pagecomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этой странице');
INSERT INTO `languages` VALUES('already_notified_pagecomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цієї сторінки');
INSERT INTO `languages` VALUES('already_notified_pollcomments', 'en', 'You are already signed on notifications about comments to this interrogation');
INSERT INTO `languages` VALUES('already_notified_pollcomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этому опросу');
INSERT INTO `languages` VALUES('already_notified_pollcomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цього опитування');
INSERT INTO `languages` VALUES('already_notified_relcomments', 'en', 'You are already signed on notifications about comments to this release');
INSERT INTO `languages` VALUES('already_notified_relcomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этому релизу');
INSERT INTO `languages` VALUES('already_notified_relcomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цього релізу');
INSERT INTO `languages` VALUES('already_notified_reqcomments', 'en', 'You are already signed on notifications about comments to this inquiry');
INSERT INTO `languages` VALUES('already_notified_reqcomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этому запросу');
INSERT INTO `languages` VALUES('already_notified_reqcomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цього запиту');
INSERT INTO `languages` VALUES('already_notified_rgcomments', 'en', 'You are already signed on notifications about comments to this release to group');
INSERT INTO `languages` VALUES('already_notified_rgcomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этой релиз группе');
INSERT INTO `languages` VALUES('already_notified_rgcomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цієї реліз групі');
INSERT INTO `languages` VALUES('already_notified_rgnewscomments', 'en', 'You are already signed on notifications about comments to this news from group release');
INSERT INTO `languages` VALUES('already_notified_rgnewscomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этой новости от релиз группы');
INSERT INTO `languages` VALUES('already_notified_rgnewscomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цієї новини від реліз групи');
INSERT INTO `languages` VALUES('already_notified_usercomments', 'en', 'You are already signed on notifications about comments to this user');
INSERT INTO `languages` VALUES('already_notified_usercomments', 'ru', 'Вы уже подписаны на оповещения о комментариях к этому пользователю');
INSERT INTO `languages` VALUES('already_notified_usercomments', 'ua', 'Ви вже підписані на оповіщення про коментарі до цього користувачеві');
INSERT INTO `languages` VALUES('already_rated', 'en', 'You have already voted here');
INSERT INTO `languages` VALUES('already_rated', 'ru', 'Вы уже голосовали здесь');
INSERT INTO `languages` VALUES('already_rated', 'ua', 'Ви вже голосували тут');
INSERT INTO `languages` VALUES('already_report', 'en', 'You already reported');
INSERT INTO `languages` VALUES('already_report', 'ru', 'Вы уже подавали жалобу');
INSERT INTO `languages` VALUES('already_report', 'ua', 'Ви вже подавали скаргу');
INSERT INTO `languages` VALUES('amount', 'en', 'The quantity of a farmed necessary for a subscription.');
INSERT INTO `languages` VALUES('amount', 'ru', 'Количество релизов по скидке, необх. для подписки');
INSERT INTO `languages` VALUES('amount', 'ua', 'Кількість релізів за знижкою, необх. для підписки');
INSERT INTO `languages` VALUES('Amount of fails', 'en', 'Amount of fails');
INSERT INTO `languages` VALUES('Amount of fails', 'ru', 'Количество неудачных запросов');
INSERT INTO `languages` VALUES('amount of fails', 'ua', 'Кількість невдалих запитів');
INSERT INTO `languages` VALUES('amount_of_discount', 'en', 'Amount of discount');
INSERT INTO `languages` VALUES('amount_of_discount', 'ru', 'Количество релизов по скидке');
INSERT INTO `languages` VALUES('amount_of_discount', 'ua', 'Кількість релізів за знижкою');
INSERT INTO `languages` VALUES('amount_of_rating', 'en', 'Amount of rating');
INSERT INTO `languages` VALUES('amount_of_rating', 'ru', 'Количество рейтинга');
INSERT INTO `languages` VALUES('amount_of_rating', 'ua', 'Кількість рейтингу');
INSERT INTO `languages` VALUES('announce_invalid', 'en', 'Invalid');
INSERT INTO `languages` VALUES('announce_invalid', 'ru', 'Неверный');
INSERT INTO `languages` VALUES('announce_invalid', 'ua', 'Невірний');
INSERT INTO `languages` VALUES('announce_invalid_passkey', 'en', '''Unknown passkey! Re-download the torrent file (.torrent) from ''.$CACHEARRAY[''defaultbaseurl'']');
INSERT INTO `languages` VALUES('announce_invalid_passkey', 'ru', 'Неверный пасскей! Перекачайте торрент');
INSERT INTO `languages` VALUES('announce_invalid_passkey', 'ua', 'Невірний паскей! Перекачати торрент');
INSERT INTO `languages` VALUES('announce_invalid_port', 'en', 'Unknown port');
INSERT INTO `languages` VALUES('announce_invalid_port', 'ru', 'Неверный порт');
INSERT INTO `languages` VALUES('announce_invalid_port', 'ua', 'Невірний порт');
INSERT INTO `languages` VALUES('announce_missing_parameter', 'en', 'Missing parameter');
INSERT INTO `languages` VALUES('announce_missing_parameter', 'ru', 'Отсутствует параметр');
INSERT INTO `languages` VALUES('announce_missing_parameter', 'ua', 'Відсутній параметр');
INSERT INTO `languages` VALUES('announce_not_authorized', 'en', 'You are not authorized');
INSERT INTO `languages` VALUES('announce_not_authorized', 'ru', 'Не авторизированны');
INSERT INTO `languages` VALUES('announce_not_authorized', 'ua', 'Не авторизовані');
INSERT INTO `languages` VALUES('announce_read_faq', 'en', 'Read the FAQ');
INSERT INTO `languages` VALUES('announce_read_faq', 'ru', 'Читайте ЧаВо');
INSERT INTO `languages` VALUES('announce_read_faq', 'ua', 'Читайте ЧаПи');
INSERT INTO `languages` VALUES('announce_torrent_not_registered', 'en', 'Torrent not registered with this tracker');
INSERT INTO `languages` VALUES('announce_torrent_not_registered', 'ru', 'Релиз не зарегистрирован на трекере');
INSERT INTO `languages` VALUES('announce_torrent_not_registered', 'ua', 'Реліз не зареєстрований на трекері');
INSERT INTO `languages` VALUES('announce_url', 'en', 'Announce URL');
INSERT INTO `languages` VALUES('announce_url', 'ru', 'Announce URL');
INSERT INTO `languages` VALUES('announce_url', 'ua', 'Announce URL');
INSERT INTO `languages` VALUES('announce_urls', 'en', 'Addresses trackers<br /><small>If empty, then the torrent is not multitracker</small>');
INSERT INTO `languages` VALUES('announce_urls', 'ru', 'Адреса трекеров<br /><small>Если пусто, то торрент не мультитрекерный</small>');
INSERT INTO `languages` VALUES('announce_urls', 'ua', 'Адреси трекерів<br /><small>Якщо порожньо, то торрент не мультітрекерний</small>');
INSERT INTO `languages` VALUES('announce_urls_notice', 'en', 'Specify the same address on each line. After clicking on the "add" the system will check these tracker. This may take a long time. After verification you will see the results.');
INSERT INTO `languages` VALUES('announce_urls_notice', 'ru', 'Указывайте по одному адресу на каждой строчке. После нажатия на кнопку "добавить" система проверит указанные здесь трекера. Это может занять длительное время. После проверки вам будут отображены ее результаты');
INSERT INTO `languages` VALUES('announce_urls_notice', 'ua', 'Вказуйте по одній адресі на кожному рядку. Після натискання на кнопку "додати" система перевірить вказані тут трекера. Це може зайняти тривалий час. Після перевірки вам будуть відображені її результати');
INSERT INTO `languages` VALUES('announce_you_can_leech_only_from_one_place', 'en', 'The limit of connections has been reached! You can download only from one place.');
INSERT INTO `languages` VALUES('announce_you_can_leech_only_from_one_place', 'ru', 'Лимит соединений превышен! Вы можете качать только с одного места.');
INSERT INTO `languages` VALUES('announce_you_can_leech_only_from_one_place', 'ua', 'Ліміт з''єднань перевищено! Ви можете качати тільки з одного місця.');
INSERT INTO `languages` VALUES('anonymous', 'en', 'Anonymous');
INSERT INTO `languages` VALUES('anonymous', 'ru', 'Аноним');
INSERT INTO `languages` VALUES('anonymous', 'ua', 'Анонім');
INSERT INTO `languages` VALUES('anonymous_release', 'en', 'Anonymous release');
INSERT INTO `languages` VALUES('anonymous_release', 'ru', 'Анонимизация релиза');
INSERT INTO `languages` VALUES('anonymous_release', 'ua', 'Анонімізації релізу');
INSERT INTO `languages` VALUES('antirespect', 'en', 'Disrespect');
INSERT INTO `languages` VALUES('antirespect', 'ru', 'Антиреспект');
INSERT INTO `languages` VALUES('antirespect', 'ua', 'Антиреспект');
INSERT INTO `languages` VALUES('approve', 'en', 'Approve release<br /><small>If selected, release will be shown around the releaser, not in test-releaser only</small>');
INSERT INTO `languages` VALUES('approve', 'ru', 'Подтвердить оформление релиза<br /><small>В этом случае релиз станет отображаться на главной и в списке релизов, а не только на тренировочном релизере</small>');
INSERT INTO `languages` VALUES('approve', 'ua', 'Підтвердити оформлення релізу<br /><small>У цьому випадку реліз стане відображатися на головній і в списку релізів, а не тільки на тренувальному релізері</small>');
INSERT INTO `languages` VALUES('archive_of_news', 'en', 'Archive news');
INSERT INTO `languages` VALUES('archive_of_news', 'ru', 'Архив новостей');
INSERT INTO `languages` VALUES('archive_of_news', 'ua', 'Архів новин');
INSERT INTO `languages` VALUES('are_you_sure', 'en', 'Are you sure?');
INSERT INTO `languages` VALUES('are_you_sure', 'ru', 'Вы уверены?');
INSERT INTO `languages` VALUES('are_you_sure', 'ua', 'Ви впевнені?');
INSERT INTO `languages` VALUES('Assigned file', 'en', 'Assigned file');
INSERT INTO `languages` VALUES('Assigned file', 'ru', 'Назначенный файл');
INSERT INTO `languages` VALUES('assigned file', 'ua', 'Призначений файл');
INSERT INTO `languages` VALUES('as_dc_magnet', 'ru', 'Отобразить DirectConnect ссылку!');
INSERT INTO `languages` VALUES('as_dc_magnet', 'ua', 'Показати DirectConnect посилання!');
INSERT INTO `languages` VALUES('as_magnet', 'en', 'Show Magnet-link!');
INSERT INTO `languages` VALUES('as_magnet', 'ru', 'Отобразить Magnet-ссылку!');
INSERT INTO `languages` VALUES('as_magnet', 'ua', 'Показати Magnet-посилання!');
INSERT INTO `languages` VALUES('at', 'en', 'at');
INSERT INTO `languages` VALUES('at', 'ru', 'в');
INSERT INTO `languages` VALUES('at', 'ua', 'в');
INSERT INTO `languages` VALUES('attention', 'en', 'Attention');
INSERT INTO `languages` VALUES('attention', 'ru', 'Внимание');
INSERT INTO `languages` VALUES('attention', 'ua', 'Увага');
INSERT INTO `languages` VALUES('autor', 'en', 'Autor');
INSERT INTO `languages` VALUES('autor', 'ru', 'Автор');
INSERT INTO `languages` VALUES('autor', 'ua', 'Автор');
INSERT INTO `languages` VALUES('avatar', 'en', 'Avatar');
INSERT INTO `languages` VALUES('avatar', 'ru', 'Аватар');
INSERT INTO `languages` VALUES('avatar', 'ua', 'Аватар');
INSERT INTO `languages` VALUES('avatar_adress_invalid', 'en', 'The address of this avatar is not valid.');
INSERT INTO `languages` VALUES('avatar_adress_invalid', 'ru', 'Неверный адрес аватары.');
INSERT INTO `languages` VALUES('avatar_adress_invalid', 'ua', 'Невірна адреса аватари.');
INSERT INTO `languages` VALUES('avatar_is_too_big', 'en', 'The size of your avatar exceed %dx%d piskeley, reduce it in any graphics editor!');
INSERT INTO `languages` VALUES('avatar_is_too_big', 'ru', 'Размеры вашей аватары превышают %dx%d пискелей, уменьшите ее в любом графическом редакторе!');
INSERT INTO `languages` VALUES('avatar_is_too_big', 'ua', 'Розміри вашої аватари перевищують %dx%d піскелей, зменшіть її в будь-якому графічному редакторі!');
INSERT INTO `languages` VALUES('average', 'en', 'Average');
INSERT INTO `languages` VALUES('average', 'ru', 'Средняя');
INSERT INTO `languages` VALUES('average', 'ua', 'Средня');
INSERT INTO `languages` VALUES('avialable_formats', 'en', 'Available formats');
INSERT INTO `languages` VALUES('avialable_formats', 'ru', 'Допустимые форматы');
INSERT INTO `languages` VALUES('avialable_formats', 'ua', 'Допустимі формати');
INSERT INTO `languages` VALUES('back', 'en', 'Back');
INSERT INTO `languages` VALUES('back', 'ru', 'Назад');
INSERT INTO `languages` VALUES('back', 'ua', 'Назад');
INSERT INTO `languages` VALUES('Back to', 'en', 'Back to');
INSERT INTO `languages` VALUES('Back to', 'ru', 'Вернуться к');
INSERT INTO `languages` VALUES('back to', 'ua', 'Повернутися до');
INSERT INTO `languages` VALUES('back_to_details', 'en', 'Back to the description');
INSERT INTO `languages` VALUES('back_to_details', 'ru', 'Вернуться к описанию релиза');
INSERT INTO `languages` VALUES('back_to_details', 'ua', 'Вернуться к описанию релиза');
INSERT INTO `languages` VALUES('badwords', 'en', 'Bad words');
INSERT INTO `languages` VALUES('badwords', 'ru', 'Мат');
INSERT INTO `languages` VALUES('badwords', 'ua', 'Мат');
INSERT INTO `languages` VALUES('banemailadmin', 'en', 'E-mail bans');
INSERT INTO `languages` VALUES('banemailadmin', 'ru', 'Бан емайлов');
INSERT INTO `languages` VALUES('banemailadmin', 'ua', 'Бан email');
INSERT INTO `languages` VALUES('banned', 'en', 'Banned');
INSERT INTO `languages` VALUES('banned', 'ru', 'Забанен');
INSERT INTO `languages` VALUES('banned', 'ua', 'Забанений');
INSERT INTO `languages` VALUES('banned_releases', 'en', 'Banned releases');
INSERT INTO `languages` VALUES('banned_releases', 'ru', 'Запрещенные релизы');
INSERT INTO `languages` VALUES('banned_releases', 'ua', 'Заборонені релізи');
INSERT INTO `languages` VALUES('bans', 'en', 'Bans');
INSERT INTO `languages` VALUES('bans', 'ru', 'Баны');
INSERT INTO `languages` VALUES('bans', 'ua', 'Бани');
INSERT INTO `languages` VALUES('ban_releases', 'en', 'Ban releases');
INSERT INTO `languages` VALUES('ban_releases', 'ru', 'Запрещенные релизы');
INSERT INTO `languages` VALUES('ban_releases', 'ua', 'Заборонені релізи');
INSERT INTO `languages` VALUES('ban_uninstalled', 'en', 'The ban has been successfully uninstalled.<br /> <a href="viewcensoredtorrents.php">list of bans</a>');
INSERT INTO `languages` VALUES('ban_uninstalled', 'ru', 'Запрет успешно удален.<br /><a href="viewcensoredtorrents.php">К списку запретов</a>');
INSERT INTO `languages` VALUES('ban_uninstalled', 'ua', 'Заборона успішно видалена.<br /><a href="viewcensoredtorrents.php">До переліку заборон</a>');
INSERT INTO `languages` VALUES('become_uploader', 'en', 'I want to be uploader!');
INSERT INTO `languages` VALUES('become_uploader', 'ru', 'Хочу стать аплоадером!');
INSERT INTO `languages` VALUES('become_uploader', 'ua', 'Хочу стати Аплоадером!');
INSERT INTO `languages` VALUES('big_present_discount', 'en', '<img src="pic/presents/discount_big.png" alt="image" style="border: 0px;" /><br />Present farmed');
INSERT INTO `languages` VALUES('big_present_discount', 'ru', '<img src="pic/presents/discount_big.png" alt="image" style="border: 0px;" /><br />Подарить Скидку');
INSERT INTO `languages` VALUES('big_present_discount', 'ua', '<img src="pic/presents/discount_big.png" alt="image" style="border: 0px;" /><br />Подарувати Знижку');
INSERT INTO `languages` VALUES('big_present_ratingsum', 'en', '<img src="pic/presents/ratingsum_big.png" alt="image" style="border: 0px;" /><br />Present rating');
INSERT INTO `languages` VALUES('big_present_ratingsum', 'ru', '<img src="pic/presents/ratingsum_big.png" alt="image" style="border: 0px;" /><br />Подарить рейтинг');
INSERT INTO `languages` VALUES('big_present_ratingsum', 'ua', '<img src="pic/presents/ratingsum_big.png" alt="image" style="border: 0px;" /><br />Подарувати рейтинг');
INSERT INTO `languages` VALUES('big_present_torrent', 'en', '<img src="pic/presents/torrent_big.png" alt="image" style="border: 0px;" /><br />Present realese');
INSERT INTO `languages` VALUES('big_present_torrent', 'ru', '<img src="pic/presents/torrent_big.png" alt="image" style="border: 0px;" /><br />Подарить релиз');
INSERT INTO `languages` VALUES('big_present_torrent', 'ua', '<img src="pic/presents/torrent_big.png" alt="image" style="border: 0px;" /><br />Подарувати реліз');
INSERT INTO `languages` VALUES('bitbucket', 'en', 'Upload image');
INSERT INTO `languages` VALUES('bitbucket', 'ru', 'Закачать картинку');
INSERT INTO `languages` VALUES('bitbucket', 'ua', 'Закачати картинку');
INSERT INTO `languages` VALUES('blank_vote', 'en', 'Blank vote (I just wanna see the results)');
INSERT INTO `languages` VALUES('blank_vote', 'ru', 'Пустой голос (Я просто хочу увидеть результаты!)');
INSERT INTO `languages` VALUES('blank_vote', 'ua', 'Порожній голос (Я просто хочу побачити результати!)');
INSERT INTO `languages` VALUES('block', 'en', 'lock');
INSERT INTO `languages` VALUES('block', 'ru', 'блокировку');
INSERT INTO `languages` VALUES('block', 'ua', 'блокування');
INSERT INTO `languages` VALUES('Block deleted', 'en', 'Block deleted');
INSERT INTO `languages` VALUES('Block deleted', 'ru', 'Блок удален');
INSERT INTO `languages` VALUES('block deleted', 'ua', 'Блок видалений');
INSERT INTO `languages` VALUES('blocked_list', 'en', 'List of enemies');
INSERT INTO `languages` VALUES('blocked_list', 'ru', 'Список врагов');
INSERT INTO `languages` VALUES('blocked_list', 'ua', 'Список ворогів');
INSERT INTO `languages` VALUES('Blocks reordered', 'en', 'Blocks reordered');
INSERT INTO `languages` VALUES('Blocks reordered', 'ru', 'Позиции блоков сохранены');
INSERT INTO `languages` VALUES('blocks reordered', 'ua', 'Позиції блоків збережені');
INSERT INTO `languages` VALUES('blocksadmin', 'en', 'Blocks administration');
INSERT INTO `languages` VALUES('blocksadmin', 'ru', 'Управление Блоками');
INSERT INTO `languages` VALUES('blocksadmin', 'ua', 'Управління Блоками');
INSERT INTO `languages` VALUES('blocksadmin_check_classes', 'en', 'Check nothing to allow all');
INSERT INTO `languages` VALUES('blocksadmin_check_classes', 'ru', 'Если не отмечено, то показывается для всех');
INSERT INTO `languages` VALUES('blocksadmin_check_classes', 'ua', 'Якщо не зазначено, то показується для всіх');
INSERT INTO `languages` VALUES('blocksadmin_list_pages', 'en', 'Will open list of pages, if none, block will appear on any page');
INSERT INTO `languages` VALUES('blocksadmin_list_pages', 'ru', 'Откроется список страниц. Если не заполнено, блок появляется на любой странице');
INSERT INTO `languages` VALUES('blocksadmin_list_pages', 'ua', 'Відкриється список сторінок. Якщо не заповнено, блок з''являється на будь-якій сторінці');
INSERT INTO `languages` VALUES('blocksadmin_title', 'en', 'Blocks administration panel');
INSERT INTO `languages` VALUES('blocksadmin_title', 'ru', 'Администрирование блоков');
INSERT INTO `languages` VALUES('blocksadmin_title', 'ua', 'Адміністрування блоків');
INSERT INTO `languages` VALUES('block_not_saved', 'en', 'Block does not saved due MySQL error:');
INSERT INTO `languages` VALUES('block_not_saved', 'ru', 'Блок не сохранен из-за MySQL ошибки:');
INSERT INTO `languages` VALUES('block_not_saved', 'ua', 'Блок не збережений через MySQL помилки:');
INSERT INTO `languages` VALUES('bonus', 'en', 'Bonus');
INSERT INTO `languages` VALUES('bonus', 'ru', 'Скидка');
INSERT INTO `languages` VALUES('bonus', 'ua', 'Знижка');
INSERT INTO `languages` VALUES('bookmarked', 'en', 'was added to your bookmarks.');
INSERT INTO `languages` VALUES('bookmarked', 'ru', 'добавлен в закладки.');
INSERT INTO `languages` VALUES('bookmarked', 'ua', 'доданий у закладки.');
INSERT INTO `languages` VALUES('bookmarks', 'en', 'Bookmarks');
INSERT INTO `languages` VALUES('bookmarks', 'ru', 'Закладки');
INSERT INTO `languages` VALUES('bookmarks', 'ua', 'Закладки');
INSERT INTO `languages` VALUES('bookmark_this', 'en', 'Add to bookmarks');
INSERT INTO `languages` VALUES('bookmark_this', 'ru', 'В закладки');
INSERT INTO `languages` VALUES('bookmark_this', 'ua', 'У закладки');
INSERT INTO `languages` VALUES('books', 'en', 'Favorite Books:');
INSERT INTO `languages` VALUES('books', 'ru', 'Любимые книги:');
INSERT INTO `languages` VALUES('books', 'ua', 'Улюблені книги:');
INSERT INTO `languages` VALUES('boys', 'en', 'Boys');
INSERT INTO `languages` VALUES('boys', 'ru', 'Парни');
INSERT INTO `languages` VALUES('boys', 'ua', 'Хлопці');
INSERT INTO `languages` VALUES('break_attempt', 'en', 'Possible breakin attempt');
INSERT INTO `languages` VALUES('break_attempt', 'ru', 'Обнаружена попытка взлома');
INSERT INTO `languages` VALUES('break_attempt', 'ua', 'Виявлена спроба злому');
INSERT INTO `languages` VALUES('browse', 'en', 'Releases');
INSERT INTO `languages` VALUES('browse', 'ru', 'Релизы');
INSERT INTO `languages` VALUES('browse', 'ua', 'Релізи');
INSERT INTO `languages` VALUES('browse_download', 'en', 'Download releases');
INSERT INTO `languages` VALUES('browse_download', 'ru', 'Скачать Релизы');
INSERT INTO `languages` VALUES('browse_download', 'ua', 'Завантажити Релізи');
INSERT INTO `languages` VALUES('bugs_site', 'en', 'Bugs site');
INSERT INTO `languages` VALUES('bugs_site', 'ru', 'Баги на сайте');
INSERT INTO `languages` VALUES('bugs_site', 'ua', 'Баги на сайті');
INSERT INTO `languages` VALUES('bulk_email', 'en', 'Bulk E-mail');
INSERT INTO `languages` VALUES('bulk_email', 'ru', 'Массовый E-mail');
INSERT INTO `languages` VALUES('bulk_email', 'ua', 'Масовий E-mail');
INSERT INTO `languages` VALUES('bytes', 'en', 'bytes');
INSERT INTO `languages` VALUES('bytes', 'ru', 'байт');
INSERT INTO `languages` VALUES('bytes', 'ua', 'байт');
INSERT INTO `languages` VALUES('cache', 'en', 'Cache');
INSERT INTO `languages` VALUES('cache', 'ru', 'Кэш');
INSERT INTO `languages` VALUES('cache', 'ua', 'Кеш');
INSERT INTO `languages` VALUES('Cache driver', 'en', 'Cache driver');
INSERT INTO `languages` VALUES('Cache driver', 'ru', 'Метод кеширования');
INSERT INTO `languages` VALUES('Cache templates', 'en', 'Cache templates');
INSERT INTO `languages` VALUES('Cache templates', 'ru', 'Кешировать шаблоны?');
INSERT INTO `languages` VALUES('cache templates', 'ua', 'Кешувати шаблони?');
INSERT INTO `languages` VALUES('cache_cleared', 'en', 'Cache cleared');
INSERT INTO `languages` VALUES('cache_cleared', 'ru', 'Кеш очищен');
INSERT INTO `languages` VALUES('cache_cleared', 'ua', 'Кеш очищений');
INSERT INTO `languages` VALUES('cache_driver_change', 'en', 'You can change it in include/secrets.php');
INSERT INTO `languages` VALUES('cache_driver_change', 'ru', 'Вы можете поменять драйвер в include/secrets.php');
INSERT INTO `languages` VALUES('cache_templates_life', 'en', 'Templates cache lifetime');
INSERT INTO `languages` VALUES('cache_templates_life', 'ru', 'Время жизни кеша шаблонов');
INSERT INTO `languages` VALUES('cache_templates_life', 'ua', 'Час життя кешу шаблонів');
INSERT INTO `languages` VALUES('cannot_discount', 'en', 'You can not get farmed, since quantity of the releases downloaded by you and so exceeds quantity of your farmed');
INSERT INTO `languages` VALUES('cannot_discount', 'ru', 'Вы не можете получить скидку, т.к. количество скачанных вами релизов и так превышает количество вашей скидки');
INSERT INTO `languages` VALUES('cannot_discount', 'ua', 'Ви не можете отримати знижку, тому що кількість викачаних вами релізів і так перевищує кількість вашої знижки');
INSERT INTO `languages` VALUES('cannot_edit_friends', 'en', 'You cannot make action not over the friends');
INSERT INTO `languages` VALUES('cannot_edit_friends', 'ru', 'Вы не можете производить действия не над своими друзьями');
INSERT INTO `languages` VALUES('cannot_edit_friends', 'ua', 'Ви не можете проводити дії не над своїми друзями');
INSERT INTO `languages` VALUES('cant_add_myself', 'en', 'You cannot add yourselves in friends');
INSERT INTO `languages` VALUES('cant_add_myself', 'ru', 'Вы не можете добавить сами себя в друзья');
INSERT INTO `languages` VALUES('cant_add_myself', 'ua', 'Ви не можете додати самі себе в друзі');
INSERT INTO `languages` VALUES('cant_dell_acc', 'en', 'You can not delete your account.');
INSERT INTO `languages` VALUES('cant_dell_acc', 'ru', 'Невозможно удалить аккаунт.');
INSERT INTO `languages` VALUES('cant_dell_acc', 'ua', 'Неможливо видалити обліковий запис.');
INSERT INTO `languages` VALUES('cant_del_acc', 'en', 'You can not delete your account.');
INSERT INTO `languages` VALUES('cant_del_acc', 'ru', 'Невозможно удалить аккаунт.');
INSERT INTO `languages` VALUES('cant_del_acc', 'ua', 'Неможливо видалити обліковий запис.');
INSERT INTO `languages` VALUES('cant_rate_yourself', 'en', 'You cannot score yourselves.!');
INSERT INTO `languages` VALUES('cant_rate_yourself', 'ru', 'Вы не можете оценить сами себя!');
INSERT INTO `languages` VALUES('cant_rate_yourself', 'ua', 'Ви не можете оцінити самі себе!');
INSERT INTO `languages` VALUES('captcha_human', 'en', 'Are you a human?');
INSERT INTO `languages` VALUES('captcha_human', 'ru', 'Вы человек?');
INSERT INTO `languages` VALUES('captcha_human', 'ua', 'Ви людина?');
INSERT INTO `languages` VALUES('Categories', 'en', 'Categories');
INSERT INTO `languages` VALUES('Categories', 'ru', 'Категории');
INSERT INTO `languages` VALUES('categories', 'ua', 'Категорії');
INSERT INTO `languages` VALUES('category', 'en', 'Category');
INSERT INTO `languages` VALUES('category', 'ru', 'Категория');
INSERT INTO `languages` VALUES('category', 'ua', 'Категорія');
INSERT INTO `languages` VALUES('category_added', 'en', 'The category successfully added');
INSERT INTO `languages` VALUES('category_added', 'ru', 'Категория успешно добавлена');
INSERT INTO `languages` VALUES('category_added', 'ua', 'Категорія успішно додана');
INSERT INTO `languages` VALUES('category_admin', 'en', 'Management of categories');
INSERT INTO `languages` VALUES('category_admin', 'ru', 'Админка категорий');
INSERT INTO `languages` VALUES('category_admin', 'ua', 'Адмінка категорій');
INSERT INTO `languages` VALUES('category_success_delete', 'en', 'The category successfully deteted');
INSERT INTO `languages` VALUES('category_success_delete', 'ru', 'Категория успешно удалена');
INSERT INTO `languages` VALUES('category_success_delete', 'ua', 'Категорія успішно видалена');
INSERT INTO `languages` VALUES('category_success_edit', 'en', 'The category is successfully edited');
INSERT INTO `languages` VALUES('category_success_edit', 'ru', 'Категория успешно отредактирована');
INSERT INTO `languages` VALUES('category_success_edit', 'ua', 'Категорія успішно відкоригована');
INSERT INTO `languages` VALUES('chage_rating', 'en', 'Exchange Rate on farming');
INSERT INTO `languages` VALUES('chage_rating', 'ru', 'Обменять рейтинг на скидку');
INSERT INTO `languages` VALUES('chage_rating', 'ua', 'Обміняти рейтинг на знижку');
INSERT INTO `languages` VALUES('change', 'en', 'Change');
INSERT INTO `languages` VALUES('change', 'ru', 'Сменить');
INSERT INTO `languages` VALUES('change', 'ua', 'Змінити');
INSERT INTO `languages` VALUES('Change avatar', 'en', 'Change avatar');
INSERT INTO `languages` VALUES('Change avatar', 'ru', 'Сменить аватар');
INSERT INTO `languages` VALUES('change avatar', 'ua', 'Змінити аватар');
INSERT INTO `languages` VALUES('Changed', 'en', 'Changed');
INSERT INTO `languages` VALUES('Changed', 'ru', 'Изменен');
INSERT INTO `languages` VALUES('changed', 'ua', 'Змінений');
INSERT INTO `languages` VALUES('change_class', 'en', 'Change of class');
INSERT INTO `languages` VALUES('change_class', 'ru', 'Смена класса');
INSERT INTO `languages` VALUES('change_class', 'ua', 'Зміна класу');
INSERT INTO `languages` VALUES('change_password', 'en', 'Change password');
INSERT INTO `languages` VALUES('change_password', 'ru', 'Смена пароля');
INSERT INTO `languages` VALUES('change_password', 'ua', 'Зміна пароля');
INSERT INTO `languages` VALUES('change_user_pass', 'en', 'Change user password');
INSERT INTO `languages` VALUES('change_user_pass', 'ru', 'Сменить пароль пользователю');
INSERT INTO `languages` VALUES('change_user_pass', 'ua', 'Змінити пароль користувачеві');
INSERT INTO `languages` VALUES('change_usr_succ', 'en', 'Change user was successful.');
INSERT INTO `languages` VALUES('change_usr_succ', 'ru', 'Изменения пользователя прошло успешно');
INSERT INTO `languages` VALUES('change_usr_succ', 'ua', 'Зміни користувача пройшло успішно');
INSERT INTO `languages` VALUES('Characters', 'en', 'Characters');
INSERT INTO `languages` VALUES('Characters', 'ru', 'Символов');
INSERT INTO `languages` VALUES('characters', 'ua', 'Символів');
INSERT INTO `languages` VALUES('check', 'en', 'Check');
INSERT INTO `languages` VALUES('check', 'ru', 'Проверка');
INSERT INTO `languages` VALUES('check', 'ua', 'Перевірка');
INSERT INTO `languages` VALUES('Checked', 'en', 'Checked');
INSERT INTO `languages` VALUES('Checked', 'ru', 'Проверен');
INSERT INTO `languages` VALUES('checked', 'ua', 'Перевірено');
INSERT INTO `languages` VALUES('checked_by', 'en', '<span style="color: green;">This release <b>was checked</b> by </span>');
INSERT INTO `languages` VALUES('checked_by', 'ru', '<span style="color: green;">Этот релиз <b>был проверен</b> </span>');
INSERT INTO `languages` VALUES('checked_by', 'ua', '<span style="color: green;">Цей реліз <b>був перевірений</b> </span>');
INSERT INTO `languages` VALUES('check_address', 'en', 'Check whether the address entered Email!');
INSERT INTO `languages` VALUES('check_address', 'ru', 'Проверьте, верно ли введен адрес Email!');
INSERT INTO `languages` VALUES('check_address', 'ua', 'Перевірте, чи вірно введено адресу Email!');
INSERT INTO `languages` VALUES('check_ip', 'en', 'Check the IP address');
INSERT INTO `languages` VALUES('check_ip', 'ru', 'Проверить IP адрес');
INSERT INTO `languages` VALUES('check_ip', 'ua', 'Перевірити IP адресу');
INSERT INTO `languages` VALUES('check_port', 'en', 'Check port');
INSERT INTO `languages` VALUES('check_port', 'ru', 'Проверить порт');
INSERT INTO `languages` VALUES('check_port', 'ua', 'Перевірити порт');
INSERT INTO `languages` VALUES('choose', 'en', 'Choose');
INSERT INTO `languages` VALUES('choose', 'ru', 'Выберите');
INSERT INTO `languages` VALUES('choose', 'ua', 'Виберіть');
INSERT INTO `languages` VALUES('class', 'en', 'Class');
INSERT INTO `languages` VALUES('class', 'ru', 'Класс');
INSERT INTO `languages` VALUES('class', 'ua', 'Клас');
INSERT INTO `languages` VALUES('class_administrator', 'en', 'Administrator');
INSERT INTO `languages` VALUES('class_administrator', 'ru', 'Администратор');
INSERT INTO `languages` VALUES('class_administrator', 'ua', 'Адміністратор');
INSERT INTO `languages` VALUES('class_administrators', 'en', 'Administrators');
INSERT INTO `languages` VALUES('class_administrators', 'ru', 'Администраторы');
INSERT INTO `languages` VALUES('class_administrators', 'ua', 'Адміністратори');
INSERT INTO `languages` VALUES('class_moderator', 'en', 'Moderator');
INSERT INTO `languages` VALUES('class_moderator', 'ru', 'Модератор');
INSERT INTO `languages` VALUES('class_moderator', 'ua', 'Модератор');
INSERT INTO `languages` VALUES('class_override_denied', 'en', 'An attempt to change the class dismissed, your class is too low');
INSERT INTO `languages` VALUES('class_override_denied', 'ru', 'Попытка смены класса откланена, ваш класс слишком низок');
INSERT INTO `languages` VALUES('class_override_denied', 'ua', 'Спроба зміни класу відхилена, ваш клас занадто низький');
INSERT INTO `languages` VALUES('class_power_user', 'en', 'Power user');
INSERT INTO `languages` VALUES('class_power_user', 'ru', 'Опытный пользователь');
INSERT INTO `languages` VALUES('class_power_user', 'ua', 'Досвідчений користувач');
INSERT INTO `languages` VALUES('class_sysop', 'en', 'Owner');
INSERT INTO `languages` VALUES('class_sysop', 'ru', 'Директор');
INSERT INTO `languages` VALUES('class_sysop', 'ua', 'Директор');
INSERT INTO `languages` VALUES('class_uploader', 'en', 'Releaser');
INSERT INTO `languages` VALUES('class_uploader', 'ru', 'Релизер');
INSERT INTO `languages` VALUES('class_uploader', 'ua', 'Релизер');
INSERT INTO `languages` VALUES('class_user', 'en', 'User');
INSERT INTO `languages` VALUES('class_user', 'ru', 'Пользователь');
INSERT INTO `languages` VALUES('class_user', 'ua', 'Користувач');
INSERT INTO `languages` VALUES('class_users', 'en', 'Users');
INSERT INTO `languages` VALUES('class_users', 'ru', 'Пользователи');
INSERT INTO `languages` VALUES('class_users', 'ua', 'Користувачі');
INSERT INTO `languages` VALUES('class_vip', 'en', 'VIP');
INSERT INTO `languages` VALUES('class_vip', 'ru', 'VIP');
INSERT INTO `languages` VALUES('class_vip', 'ua', 'VIP');
INSERT INTO `languages` VALUES('clean', 'en', 'Clean');
INSERT INTO `languages` VALUES('clean', 'ru', 'Очистить');
INSERT INTO `languages` VALUES('clean', 'ua', 'Очистити');
INSERT INTO `languages` VALUES('cleaning_cache', 'en', 'Cleaning cache');
INSERT INTO `languages` VALUES('cleaning_cache', 'ru', 'Очистка кэшей');
INSERT INTO `languages` VALUES('cleaning_cache', 'ua', 'Очищення кешей');
INSERT INTO `languages` VALUES('cleanup_completed', 'en', 'Cleanup completed successfully. Used to clean %s request(s).');
INSERT INTO `languages` VALUES('cleanup_completed', 'ru', 'Очистка завершена успешно. На очистку использовано %s запрос(ов).');
INSERT INTO `languages` VALUES('cleanup_completed', 'ua', 'Очищення завершена успішно. На очищення використано %s запит (ів).');
INSERT INTO `languages` VALUES('cleanup_is_running', 'en', 'Now perform clean database');
INSERT INTO `languages` VALUES('cleanup_is_running', 'ru', 'В данный момент выполняется очистка БД');
INSERT INTO `languages` VALUES('cleanup_is_running', 'ua', 'У даний момент виконується очищення БД');
INSERT INTO `languages` VALUES('cleanup_not_running', 'en', 'Cleaning the database in standby mode.');
INSERT INTO `languages` VALUES('cleanup_not_running', 'ru', 'Очистка БД в режиме ожидания');
INSERT INTO `languages` VALUES('cleanup_not_running', 'ua', 'Очищення БД в режимі очікування');
INSERT INTO `languages` VALUES('Clear language cache', 'en', 'Clear language cache');
INSERT INTO `languages` VALUES('Clear language cache', 'ru', 'Очистить языковой кеш');
INSERT INTO `languages` VALUES('clear language cache', 'ua', 'Очистити мовної кеш');
INSERT INTO `languages` VALUES('clearcache', 'en', 'Clear caches');
INSERT INTO `languages` VALUES('clearcache', 'ru', 'Очистка кешей');
INSERT INTO `languages` VALUES('clearcache', 'ua', 'Очищення кешу');
INSERT INTO `languages` VALUES('client', 'en', 'Client');
INSERT INTO `languages` VALUES('client', 'ru', 'Клиент');
INSERT INTO `languages` VALUES('client', 'ua', 'Клієнт');
INSERT INTO `languages` VALUES('clients_recomened_by_us', 'en', 'Clients recomended by us');
INSERT INTO `languages` VALUES('clients_recomened_by_us', 'ru', 'Клиенты рекомендуемые нами');
INSERT INTO `languages` VALUES('clients_recomened_by_us', 'ua', 'Клієнти рекомендовані нами');
INSERT INTO `languages` VALUES('clock', 'en', 'Time');
INSERT INTO `languages` VALUES('clock', 'ru', 'Время');
INSERT INTO `languages` VALUES('clock', 'ua', 'Час');
INSERT INTO `languages` VALUES('Close date', 'en', 'Close date');
INSERT INTO `languages` VALUES('Close date', 'ru', 'Дата закрытия');
INSERT INTO `languages` VALUES('close date', 'ua', 'Дата закриття');
INSERT INTO `languages` VALUES('Closed', 'en', 'Closed');
INSERT INTO `languages` VALUES('Closed', 'ru', 'Закрыт');
INSERT INTO `languages` VALUES('closed', 'ua', 'Закрито');
INSERT INTO `languages` VALUES('close_list', 'en', 'Close list');
INSERT INTO `languages` VALUES('close_list', 'ru', 'Закрыть список');
INSERT INTO `languages` VALUES('close_list', 'ua', 'Закрити список');
INSERT INTO `languages` VALUES('close_window', 'en', 'Close window');
INSERT INTO `languages` VALUES('close_window', 'ru', 'Закрыть окно');
INSERT INTO `languages` VALUES('close_window', 'ua', 'Закрити вікно');
INSERT INTO `languages` VALUES('cloud_tags', 'en', 'A large cloud of tags');
INSERT INTO `languages` VALUES('cloud_tags', 'ru', 'Большое облако тегов');
INSERT INTO `languages` VALUES('cloud_tags', 'ua', 'Велика хмара тегів');
INSERT INTO `languages` VALUES('code_incorrect', 'en', 'Confirmation code is incorrect');
INSERT INTO `languages` VALUES('code_incorrect', 'ru', 'Код подтверждения неверен');
INSERT INTO `languages` VALUES('code_incorrect', 'ua', 'Код підтвердження невірний');
INSERT INTO `languages` VALUES('code_incorrectly', 'en', 'Code from the image is entered incorrectly or not entered!');
INSERT INTO `languages` VALUES('code_incorrectly', 'ru', 'Код с картинки введен неверно или не введен!');
INSERT INTO `languages` VALUES('code_incorrectly', 'ua', 'Код з картинки введено неправильно або не введено!');
INSERT INTO `languages` VALUES('comma_separated', 'en', 'User ID, after a comma, <b>no spaces</b>');
INSERT INTO `languages` VALUES('comma_separated', 'ru', 'ID пользователей, через запятую, <b>без пробелов</b>');
INSERT INTO `languages` VALUES('comma_separated', 'ua', 'ID користувачів, через кому, <b>без пробілів</b>');
INSERT INTO `languages` VALUES('comment', 'en', 'comment');
INSERT INTO `languages` VALUES('comment', 'ru', 'комментарий');
INSERT INTO `languages` VALUES('comment', 'ua', 'коментар');
INSERT INTO `languages` VALUES('comments', 'en', 'Comm.');
INSERT INTO `languages` VALUES('comments', 'ru', 'Комм.');
INSERT INTO `languages` VALUES('comments', 'ua', 'Ком.');
INSERT INTO `languages` VALUES('Comments list', 'en', 'Comments list');
INSERT INTO `languages` VALUES('Comments list', 'ru', 'Список комментариев');
INSERT INTO `languages` VALUES('Comments list', 'ua', 'Список коментарів');
INSERT INTO `languages` VALUES('comments_and_social', 'en', 'Comments.<br /><br />Social activity<br /><br />Filesharing');
INSERT INTO `languages` VALUES('comments_and_social', 'ru', 'Комментарии<br /><br />Соцактивность<br /><br />Файлообмен');
INSERT INTO `languages` VALUES('comments_and_social', 'ua', 'Коментарі<br /><br />Соцактівність<br /><br />Файлообмін');
INSERT INTO `languages` VALUES('comments_for', 'en', 'Comments to');
INSERT INTO `languages` VALUES('comments_for', 'ru', 'Комментарии к');
INSERT INTO `languages` VALUES('comments_for', 'ua', 'Коментарі до');
INSERT INTO `languages` VALUES('comment_cant_be_empty', 'en', 'Comment cannot be empty!');
INSERT INTO `languages` VALUES('comment_cant_be_empty', 'ru', 'Комментарий не может быть пустым!');
INSERT INTO `languages` VALUES('comment_cant_be_empty', 'ua', 'Коментар не може бути порожнім!');
INSERT INTO `languages` VALUES('comment_notice_forumcomments', 'en', 'New reply was added to topic "%s", to view it click on topic name');
INSERT INTO `languages` VALUES('comment_notice_forumcomments', 'ru', 'Добавился новый ответ к топику "%s", чтобы посмотреть его, кликните на имя топика');
INSERT INTO `languages` VALUES('comment_notice_forumcomments', 'ua', 'Додалася нова відповідь до топіку "%s", щоб подивитися його, клікніть на ім''я топіка');
INSERT INTO `languages` VALUES('comment_notice_newscomments', 'en', 'New comment was added to news %s. To discard comment notifications visit news\\'' page');
INSERT INTO `languages` VALUES('comment_notice_newscomments', 'ru', 'Добавился новый комментарий к новости %s. Отказаться от таких уведомлении можно на странице новости');
INSERT INTO `languages` VALUES('comment_notice_newscomments', 'ua', 'Додався новий коментар до новини %s. Відмовитися від таких повідомленні можна на сторінці новини');
INSERT INTO `languages` VALUES('comment_notice_pagecomments', 'en', 'New comments was added to page %s. To discard comment notifications view page');
INSERT INTO `languages` VALUES('comment_notice_pagecomments', 'ru', 'Добавился новый комментарий к странице %s. Отказаться от таких уведомлении можно при просмотре страницы');
INSERT INTO `languages` VALUES('comment_notice_pagecomments', 'ua', 'Додався новий коментар до сторінки %s. Відмовитися від таких повідомленні можна при перегляді сторінки');
INSERT INTO `languages` VALUES('comment_notice_pollcomments', 'en', 'New comment was added to poll %s. To discard comment notifications visit poll\\''s page');
INSERT INTO `languages` VALUES('comment_notice_pollcomments', 'ru', 'Добавился новый комментарий к опросу %s. Отказаться от таких уведомлении можно на странице опроса');
INSERT INTO `languages` VALUES('comment_notice_pollcomments', 'ua', 'Додався новий коментар до опитування %s. Відмовитися від таких повідомленні можна на сторінці опитування');
INSERT INTO `languages` VALUES('comment_notice_relcomments', 'en', 'New comment was added to release %s. To discard comment notifications visit release\\''s page');
INSERT INTO `languages` VALUES('comment_notice_relcomments', 'ru', 'Добавился новый комментарий к релизу %s. Отказаться от таких уведомлении можно на странице релиза');
INSERT INTO `languages` VALUES('comment_notice_relcomments', 'ua', 'Додався новий коментар до релізу %s. Відмовитися від таких повідомленні можна на сторінці релізу');
INSERT INTO `languages` VALUES('comment_notice_reqcomments', 'en', 'New comment was added to request %s. To discard comment notifications visit request\\''s page');
INSERT INTO `languages` VALUES('comment_notice_reqcomments', 'ru', 'Добавился новый комментарий к запросу %s. Отказаться от таких уведомлении можно на странице запроса');
INSERT INTO `languages` VALUES('comment_notice_reqcomments', 'ua', 'Додався новий коментар до запиту %s. Відмовитися від таких повідомленні можна на сторінці запиту');
INSERT INTO `languages` VALUES('comment_notice_rgcomments', 'en', 'New comment was added to release group %s. To discard comment notifications visit release group\\''s page');
INSERT INTO `languages` VALUES('comment_notice_rgcomments', 'ru', 'Добавился новый комментарий к релиз-группе %s. Отказаться от таких уведомлении можно на странице релиз-группы');
INSERT INTO `languages` VALUES('comment_notice_rgcomments', 'ua', 'Додався новий коментар до реліз-групі %s. Відмовитися від таких повідомленні можна на сторінці реліз-групи');
INSERT INTO `languages` VALUES('comment_notice_rgnewscomments', 'en', 'New comment was added to release group\\'' news %s. To discard comment notifications visit release group\\''s page or release group\\''s news page');
INSERT INTO `languages` VALUES('comment_notice_rgnewscomments', 'ru', 'Добавился новый комментарий к новости %s. Отказаться от таких уведомлении можно на странице релиз-группы или на странице новостей релиз группы');
INSERT INTO `languages` VALUES('comment_notice_rgnewscomments', 'ua', 'Додався новий коментар до новини %s. Відмовитися від таких повідомленні можна на сторінці реліз-групи або на сторінці новин реліз групи');
INSERT INTO `languages` VALUES('comment_notice_usercomments', 'en', 'New comment was added to user %s. To discard comment notifications visit user\\''s page (if it\\''s your profile visit <a href="userdetails.php">your profile page</a>)');
INSERT INTO `languages` VALUES('comment_notice_usercomments', 'ru', 'Добавился новый комментарий к пользователю %s. Отказаться от таких уведомлении можно на странице профиля пользователя (или если это ваш профиль, то в <a href="userdetails.php">вашем профиле</a>)');
INSERT INTO `languages` VALUES('comment_notice_usercomments', 'ua', 'Додався новий коментар до користувача %s.Відмовитися від таких повідомленні можна на сторінці профілю користувача (або якщо це ваш профіль, то в <a href="userdetails.php">вашому профілю</a>)');
INSERT INTO `languages` VALUES('comms', 'en', 'Comments: %d');
INSERT INTO `languages` VALUES('comms', 'ru', 'Комментариев: %d');
INSERT INTO `languages` VALUES('comms', 'ua', 'Коментарів: %d');
INSERT INTO `languages` VALUES('comms_2', 'en', 'Comments:');
INSERT INTO `languages` VALUES('comms_2', 'ru', 'Комментариев:');
INSERT INTO `languages` VALUES('comms_2', 'ua', 'Коментарів:');
INSERT INTO `languages` VALUES('community', 'en', 'Community');
INSERT INTO `languages` VALUES('community', 'ru', 'Сообщество');
INSERT INTO `languages` VALUES('community', 'ua', 'Спільнота');
INSERT INTO `languages` VALUES('completed', 'en', 'Completed');
INSERT INTO `languages` VALUES('completed', 'ru', 'Закончил');
INSERT INTO `languages` VALUES('completed', 'ua', 'Закінчив');
INSERT INTO `languages` VALUES('confidentiality', 'en', 'Privacy');
INSERT INTO `languages` VALUES('confidentiality', 'ru', 'Конфиденциальность');
INSERT INTO `languages` VALUES('confidentiality', 'ua', 'Конфіденційність');
INSERT INTO `languages` VALUES('configadmin', 'en', 'Global settings');
INSERT INTO `languages` VALUES('configadmin', 'ru', 'Основные настройки');
INSERT INTO `languages` VALUES('configadmin', 'ua', 'Основні налаштування');
INSERT INTO `languages` VALUES('configadmin_comment_hide', 'en', 'Comment hide rating');
INSERT INTO `languages` VALUES('configadmin_comment_hide', 'ru', 'Рейтинг скрытия комментариев');
INSERT INTO `languages` VALUES('configadmin_comment_hide', 'ua', 'Рейтинг приховування коментарів');
INSERT INTO `languages` VALUES('configadmin_comment_hide_notice', 'en', 'Points, after which post text will be replaced by "This post too bad"');
INSERT INTO `languages` VALUES('configadmin_comment_hide_notice', 'ru', 'Рейтинг, после которого текст комментария будет заменен плашкой "Этот комментарий был слишком плохим"');
INSERT INTO `languages` VALUES('configadmin_comment_hide_notice', 'ua', 'Рейтинг, після якого текст коментаря буде замінений плашкою "Цей коментар був занадто поганим"');
INSERT INTO `languages` VALUES('configadmin_dc_link', 'en', 'Go to <a href="%s">Direct Connect Hubs admincp</a>');
INSERT INTO `languages` VALUES('configadmin_dc_link', 'ru', 'Перейти к <a href="%s">администрированию DC-хабов</a>');
INSERT INTO `languages` VALUES('configadmin_dc_link', 'ua', 'Перейти до <a href="%s">адміністрування DC-хабів</a>');
INSERT INTO `languages` VALUES('configadmin_sign_length', 'en', 'Maximal users signatures length');
INSERT INTO `languages` VALUES('configadmin_sign_length', 'ru', 'Максимальное количество знаков в подписях пользователей');
INSERT INTO `languages` VALUES('configadmin_sign_length', 'ua', 'Максимальна кількість знаків у підписах користувачів');
INSERT INTO `languages` VALUES('configadmin_static_language', 'en', 'Static language system (language to load and full path to file from site root, separated by <b>commas without spaces</b>, e.g. "ru=languages/ru.lang,en=languages/en.lang"). Leave empty to disable');
INSERT INTO `languages` VALUES('configadmin_static_language', 'ru', 'Статическая система языков (укажите полные пути к файлам языка от корня сайта, разделяя <b>запятыми без пробелов</b>, например "ru=languages/ru.lang,en=languages/en.lang"). Оставьте поле пустым, чтобы отключить эту функцию');
INSERT INTO `languages` VALUES('configadmin_static_language', 'ua', 'Статична система мов (вкажіть повні шляхи до файлів мови від кореня сайту, розділяючи <b> комами без пробілів </b>, наприклад "ua = languages / ua.lang, en = languages / en.lang"). Залиште поле порожнім, щоб відключити цю функцію');
INSERT INTO `languages` VALUES('confirm', 'en', 'Confirm');
INSERT INTO `languages` VALUES('confirm', 'ru', 'Подтвердить');
INSERT INTO `languages` VALUES('confirm', 'ua', 'Підтвердити');
INSERT INTO `languages` VALUES('confirmation_delete', 'en', 'Are you sure?');
INSERT INTO `languages` VALUES('confirmation_delete', 'ru', 'Вы уверены?');
INSERT INTO `languages` VALUES('confirmation_delete', 'ua', 'Ви впевнені?');
INSERT INTO `languages` VALUES('confirmation_mail_sent', 'en', 'Confirmative e-mail was mailed to the address indicated by you (%s). You must read and react on that e-mail before you will be able to use your account. If you will not do it, your account will be automatically deleted in a few days.');
INSERT INTO `languages` VALUES('confirmation_mail_sent', 'ru', 'Подтверждающее письмо отправлено на указанный вами адрес (%s). Вам необходимо прочитать и отреагировать на письмо прежде чем вы сможете использовать ваш аккаунт. Если вы этого не сделаете, новый аккаунт будет автоматически удален через несколько дней.');
INSERT INTO `languages` VALUES('confirmation_mail_sent', 'ua', 'Підтверджуючий лист відправлено на вашу адресу (%s).Вам необхідно прочитати і відреагувати на лист перш ніж ви зможете використовувати ваш акаунт. Якщо ви цього не зробите, новий акаунт буде автоматично видалений через кілька днів.');
INSERT INTO `languages` VALUES('confirmed', 'en', 'Confirmed');
INSERT INTO `languages` VALUES('confirmed', 'ru', 'Подтвержден');
INSERT INTO `languages` VALUES('confirmed', 'ua', 'Підтверджено');
INSERT INTO `languages` VALUES('connected', 'en', 'Seeding');
INSERT INTO `languages` VALUES('connected', 'ru', 'В&nbsp;раздаче');
INSERT INTO `languages` VALUES('connected', 'ua', 'В&nbsp;роздачі');
INSERT INTO `languages` VALUES('connection_limit_exceeded', 'en', 'Connection limit has been exceeded!');
INSERT INTO `languages` VALUES('connection_limit_exceeded', 'ru', 'Лимит соединений превышен!');
INSERT INTO `languages` VALUES('connection_limit_exceeded', 'ua', 'Ліміт з''єднань перевищено!');
INSERT INTO `languages` VALUES('contact', 'en', 'Contact');
INSERT INTO `languages` VALUES('contact', 'ru', 'Обратная связь');
INSERT INTO `languages` VALUES('contact', 'ua', 'Зворотній зв''язок');
INSERT INTO `languages` VALUES('contact_admin', 'en', '<br />You can contact the site administrator via <a href="contact.php"> this page.</a>');
INSERT INTO `languages` VALUES('contact_admin', 'ru', '<br />Вы можете связаться с администрацией сайта через <a href="contact.php">эту страницу</a>');
INSERT INTO `languages` VALUES('contact_admin', 'ua', '<br />Ви можете зв''язатися з адміністрацією сайту через <a href="contact.php">цю сторінку</a>');
INSERT INTO `languages` VALUES('content', 'en', 'Content');
INSERT INTO `languages` VALUES('content', 'ru', 'Содержание');
INSERT INTO `languages` VALUES('content', 'ua', 'Зміст');
INSERT INTO `languages` VALUES('continue', 'en', 'Continue');
INSERT INTO `languages` VALUES('continue', 'ru', 'Продолжить');
INSERT INTO `languages` VALUES('continue', 'ua', 'Продовжити');
INSERT INTO `languages` VALUES('cookie_login', 'en', 'For successfull login cookies must be enabled.');
INSERT INTO `languages` VALUES('cookie_login', 'ru', 'Для успешного входа у вас должна быть включена поддержка cookies.');
INSERT INTO `languages` VALUES('cookie_login', 'ua', 'Для успішного входу у вас повинна бути включена підтримка cookies.');
INSERT INTO `languages` VALUES('country', 'en', 'Country');
INSERT INTO `languages` VALUES('country', 'ru', 'Страна');
INSERT INTO `languages` VALUES('country', 'ua', 'Країна');
INSERT INTO `languages` VALUES('countryadmin', 'en', 'Countries and flags');
INSERT INTO `languages` VALUES('countryadmin', 'ru', 'Админка стран и флагов');
INSERT INTO `languages` VALUES('countryadmin', 'ua', 'Адмінка країн і прапорів');
INSERT INTO `languages` VALUES('country_admin', 'en', 'Management of the Countries and Flags');
INSERT INTO `languages` VALUES('country_admin', 'ru', 'Управление странами и флагами');
INSERT INTO `languages` VALUES('country_admin', 'ua', 'Управління країнами і прапорами');
INSERT INTO `languages` VALUES('country_and_flags', 'en', 'Countries and Flags');
INSERT INTO `languages` VALUES('country_and_flags', 'ru', 'Страны и флаги');
INSERT INTO `languages` VALUES('country_and_flags', 'ua', 'Країни і прапори');
INSERT INTO `languages` VALUES('country_success_delete', 'en', 'Country Successfully Deleted');
INSERT INTO `languages` VALUES('country_success_delete', 'ru', 'Удалено!');
INSERT INTO `languages` VALUES('country_success_delete', 'ua', 'Видалено!');
INSERT INTO `languages` VALUES('country_success_edit', 'en', 'Country Successfully Edited');
INSERT INTO `languages` VALUES('country_success_edit', 'ru', 'Изменения приняты!');
INSERT INTO `languages` VALUES('country_success_edit', 'ua', 'Зміни прийняті!');
INSERT INTO `languages` VALUES('create', 'en', 'Create');
INSERT INTO `languages` VALUES('create', 'ru', 'Создать');
INSERT INTO `languages` VALUES('create', 'ua', 'Створити');
INSERT INTO `languages` VALUES('Create new topic', 'en', 'Create new topic');
INSERT INTO `languages` VALUES('Create new topic', 'ru', 'Создать тему');
INSERT INTO `languages` VALUES('create new topic', 'ua', 'Створити тему');
INSERT INTO `languages` VALUES('create_country', 'en', 'Create Country');
INSERT INTO `languages` VALUES('create_country', 'ru', 'Создать');
INSERT INTO `languages` VALUES('create_country', 'ua', 'Створити');
INSERT INTO `languages` VALUES('create_invite', 'en', 'Create invitation');
INSERT INTO `languages` VALUES('create_invite', 'ru', 'Создать приглашение');
INSERT INTO `languages` VALUES('create_invite', 'ua', 'Створити запрошення');
INSERT INTO `languages` VALUES('cronadmin', 'en', 'Sheduled jobs administration');
INSERT INTO `languages` VALUES('cronadmin', 'ru', 'Настройка cron-функций и рейтинга');
INSERT INTO `languages` VALUES('cronadmin', 'ua', 'Налаштування cron-функцій і рейтингу');
INSERT INTO `languages` VALUES('cronadmin_gen_do', 'en', 'Generate crontab entries');
INSERT INTO `languages` VALUES('cronadmin_gen_do', 'ru', 'Генерировать записи конфигурации cron');
INSERT INTO `languages` VALUES('cronadmin_gen_do', 'ua', 'Генерувати запису конфігурації cron');
INSERT INTO `languages` VALUES('cronadmin_gen_notice', 'en', 'This is /etc/crontab lines to add. Edit "/usr/bin/wget" corresponding to your wget location. <a href="%s">Back to cron admincp</a>.');
INSERT INTO `languages` VALUES('cronadmin_gen_notice', 'ru', 'Это строчки для добавления в /etc/crontab. Отредактируйте "/usr/bin/wget" в соответствие с расположением wget на вашей системе. <a href="%s">Вернуться к панели управления cron</a>.');
INSERT INTO `languages` VALUES('cronadmin_gen_notice', 'ua', 'Це рядки для додавання в /etc/crontab. Відредагуйте "/usr/bin/wget" у відповідність з розташуванням wget на вашій системі. <a href="%s">Повернутися до панелі управління cron</a>.');
INSERT INTO `languages` VALUES('cronadmin_method', 'en', 'Scheduled jobs activation method');
INSERT INTO `languages` VALUES('cronadmin_method', 'ru', 'Метод запуска периодических заданий');
INSERT INTO `languages` VALUES('cronadmin_method', 'ua', 'Метод запуску періодичних завдань');
INSERT INTO `languages` VALUES('cronadmin_method_notice', 'en', 'You can use built-in functions or crontab. You must edit /etc/crontab corresponding your configuration.');
INSERT INTO `languages` VALUES('cronadmin_method_notice', 'ru', 'Вы можете использовать встроенные функции релизера или Linux CRON для выполнения периодических заданий. Вы должны будете отредактировать /etc/crontab в соответствии с вашей конфигурацией');
INSERT INTO `languages` VALUES('cronadmin_method_notice', 'ua', 'Ви можете використовувати вбудовані функції релізера або Linux CRON для виконання періодичних завдань. Ви повинні будете відредагувати /etc/crontab у відповідності з вашою конфігурацією');
INSERT INTO `languages` VALUES('cronadmin_must_edit', 'en', 'You must edit /etc/crontab when changing this value.');
INSERT INTO `languages` VALUES('cronadmin_must_edit', 'ru', 'Вы должны отредактировать /etc/crontab при смене этого значения.');
INSERT INTO `languages` VALUES('cronadmin_must_edit', 'ua', 'Ви повинні відредагувати /etc/crontab при зміні цього значення.');
INSERT INTO `languages` VALUES('crontab', 'en', 'crontab');
INSERT INTO `languages` VALUES('crontab', 'ru', 'Через cron');
INSERT INTO `languages` VALUES('crontab', 'ua', 'Через cron');
INSERT INTO `languages` VALUES('cron_cron', 'en', 'Scheduled jobs activating from cron');
INSERT INTO `languages` VALUES('cron_cron', 'ru', 'Периодические задания запускаются через cron');
INSERT INTO `languages` VALUES('cron_cron', 'ua', 'Періодичні завдання запускаються через cron');
INSERT INTO `languages` VALUES('cron_native', 'en', 'Scheduled jobs activating by native method');
INSERT INTO `languages` VALUES('cron_native', 'ru', 'Периодические задания запускаются через Kinokpk.com releaser');
INSERT INTO `languages` VALUES('cron_native', 'ua', 'Періодичні завдання запускаються через Kinokpk releaser');
INSERT INTO `languages` VALUES('cron_settings_saved', 'en', 'Cron-function setting successfully saved');
INSERT INTO `languages` VALUES('cron_settings_saved', 'ru', 'Настройки cron-функций сохранены');
INSERT INTO `languages` VALUES('cron_settings_saved', 'ua', 'Установки cron-функцій збережені');
INSERT INTO `languages` VALUES('cron_state_reseted', 'en', 'Cron-function settings successfully reseted');
INSERT INTO `languages` VALUES('cron_state_reseted', 'ru', 'Сброс статистики произошел успешно');
INSERT INTO `languages` VALUES('cron_state_reseted', 'ua', 'Скидання статистики відбувся успішно');
INSERT INTO `languages` VALUES('cur_tree', 'en', 'Current section categories');
INSERT INTO `languages` VALUES('cur_tree', 'ru', 'Текущее дерево категорий страниц');
INSERT INTO `languages` VALUES('cur_tree', 'ua', 'Поточне дерево категорій сторінок');
INSERT INTO `languages` VALUES('Custom template', 'en', 'Custom template');
INSERT INTO `languages` VALUES('Custom template', 'ru', 'Особый шаблон');
INSERT INTO `languages` VALUES('custom template', 'ua', 'Особливий шаблон');
INSERT INTO `languages` VALUES('date', 'en', 'Date');
INSERT INTO `languages` VALUES('date', 'ru', 'Дата');
INSERT INTO `languages` VALUES('date', 'ua', 'Дата');
INSERT INTO `languages` VALUES('Date of change', 'en', 'Date of change');
INSERT INTO `languages` VALUES('Date of change', 'ru', 'Время изменения');
INSERT INTO `languages` VALUES('DC Hubs administration', 'en', 'DC Hubs administration');
INSERT INTO `languages` VALUES('DC Hubs administration', 'ru', 'Администрирование DC-хабов');
INSERT INTO `languages` VALUES('dc hubs administration', 'ua', 'Адміністрування DC-хабів');
INSERT INTO `languages` VALUES('dc_admin_notice', 'en', 'This is panel for Direct Connect Hubs administration, you can enable/disable DC feature <a href="%s">in main configuration</a>');
INSERT INTO `languages` VALUES('dc_admin_notice', 'ru', 'Эта панель используется для администрирования DC-хабов. Включить/отключить возможность использования DirectConnect можно в <a href="%s">общих настройках</a>');
INSERT INTO `languages` VALUES('dc_admin_notice', 'ua', 'Ця панель використовується для адміністрування DC-хабів. Включити / виключити можливість використання DirectConnect можна в <a href="%s">загальних налаштуваннях</a>');
INSERT INTO `languages` VALUES('dc_admin_title', 'en', 'Direct Connect Hubs admincp');
INSERT INTO `languages` VALUES('dc_admin_title', 'ru', 'Администрирование DC-хабов');
INSERT INTO `languages` VALUES('dc_admin_title', 'ua', 'Адміністрування DC-хабів');
INSERT INTO `languages` VALUES('dead', 'en', 'dead');
INSERT INTO `languages` VALUES('dead', 'ru', 'мертвый');
INSERT INTO `languages` VALUES('dead', 'ua', 'мертвий');
INSERT INTO `languages` VALUES('dead_releases', 'en', 'Dead releases');
INSERT INTO `languages` VALUES('dead_releases', 'ru', 'Мертвых релизов');
INSERT INTO `languages` VALUES('dead_releases', 'ua', 'Мертвих релізів');
INSERT INTO `languages` VALUES('del', 'en', 'Delete');
INSERT INTO `languages` VALUES('del', 'ru', 'Удалить');
INSERT INTO `languages` VALUES('del', 'ua', 'Видалити');
INSERT INTO `languages` VALUES('delacctadmin', 'en', 'Delete user account');
INSERT INTO `languages` VALUES('delacctadmin', 'ru', 'Удалить юзера');
INSERT INTO `languages` VALUES('delacctadmin', 'ua', 'Видалити юзера');
INSERT INTO `languages` VALUES('delete', 'en', 'Delete');
INSERT INTO `languages` VALUES('delete', 'ru', 'Удалить');
INSERT INTO `languages` VALUES('delete', 'ua', 'Видалити');
INSERT INTO `languages` VALUES('deleted', 'en', 'Deleted');
INSERT INTO `languages` VALUES('deleted', 'ru', 'Удален');
INSERT INTO `languages` VALUES('deleted', 'ua', 'Вилучений');
INSERT INTO `languages` VALUES('delete_account', 'en', 'Delete Account');
INSERT INTO `languages` VALUES('delete_account', 'ru', 'Удалить аккаунт');
INSERT INTO `languages` VALUES('delete_account', 'ua', 'Видалити обліковий запис');
INSERT INTO `languages` VALUES('delete_all_users', 'en', 'Remove all subscribers');
INSERT INTO `languages` VALUES('delete_all_users', 'ru', 'Удалить всех подписчиков');
INSERT INTO `languages` VALUES('delete_all_users', 'ua', 'Видалити всіх абонентів');
INSERT INTO `languages` VALUES('delete_comments_ok', 'en', 'Comments successfully deleted. Now you will back to previous page.');
INSERT INTO `languages` VALUES('delete_comments_ok', 'ru', 'Комментарии успешно удалены. Сейчас вы вернетесь на предыдущую страницу');
INSERT INTO `languages` VALUES('delete_comments_ok', 'ua', 'Коментарі успішно видалені. Зараз ви повернетеся на попередню сторінку');
INSERT INTO `languages` VALUES('delete_from_friends', 'en', 'Remove from Friends');
INSERT INTO `languages` VALUES('delete_from_friends', 'ru', 'Удалить из друзей');
INSERT INTO `languages` VALUES('delete_from_friends', 'ua', 'Видалити з друзів');
INSERT INTO `languages` VALUES('delete_marked_messages', 'en', 'Delete marked messages');
INSERT INTO `languages` VALUES('delete_marked_messages', 'ru', 'Удалить выделенные сообщения');
INSERT INTO `languages` VALUES('delete_marked_messages', 'ua', 'Видалити вибрані повідомлення');
INSERT INTO `languages` VALUES('delete_notif', 'en', 'You have successfully cancelled a subscription;');
INSERT INTO `languages` VALUES('delete_notif', 'ru', 'Вы успешно отменили подписку');
INSERT INTO `languages` VALUES('delete_notif', 'ua', 'Ви успішно скасували підписку');
INSERT INTO `languages` VALUES('delete_notify', 'en', 'Dear user!<br />Administrator group (site) has been discontinued your subscription to releases of "%s"');
INSERT INTO `languages` VALUES('delete_notify', 'ru', 'Уважаемый пользователь!<br />Администратором группы(сайта) была прекращена ваша подписка на релизы группы "%s"');
INSERT INTO `languages` VALUES('delete_notify', 'ua', 'Шановний користувач!<br />Адміністратором групи (сайту) була припинена вашу участь релізи групи "%s"');
INSERT INTO `languages` VALUES('delete_on_friends', 'en', 'Delete from friends');
INSERT INTO `languages` VALUES('delete_on_friends', 'ru', 'Отказать');
INSERT INTO `languages` VALUES('delete_on_friends', 'ua', 'Відмовити');
INSERT INTO `languages` VALUES('delete_user_ok', 'en', 'The user is removed from the group of subscribers');
INSERT INTO `languages` VALUES('delete_user_ok', 'ru', 'Пользователь удален из подписчиков группы');
INSERT INTO `languages` VALUES('delete_user_ok', 'ua', 'Користувача видалено з передплатників групи');
INSERT INTO `languages` VALUES('delete_with_notify', 'en', 'Remove from notifying user');
INSERT INTO `languages` VALUES('delete_with_notify', 'ru', 'Удалить с уведомлением пользователя');
INSERT INTO `languages` VALUES('delete_with_notify', 'ua', 'Видалити з повідомленням користувача');
INSERT INTO `languages` VALUES('del_friend', 'en', 'Stop friendship');
INSERT INTO `languages` VALUES('del_friend', 'ru', 'Прекратить дружбу');
INSERT INTO `languages` VALUES('del_friend', 'ua', 'Припинити дружбу');
INSERT INTO `languages` VALUES('del_peers', 'en', 'Deleted peers');
INSERT INTO `languages` VALUES('del_peers', 'ru', 'Удаленные пиры');
INSERT INTO `languages` VALUES('del_peers', 'ua', 'Віддалені піри');
INSERT INTO `languages` VALUES('deny_notifs_month', 'en', 'Disable the window for a month');
INSERT INTO `languages` VALUES('deny_notifs_month', 'ru', 'отключите окошко на месяц');
INSERT INTO `languages` VALUES('deny_notifs_month', 'ua', 'відключіть віконце на місяць');
INSERT INTO `languages` VALUES('deny_notifs_session', 'en', 'Click to turn off this window on the current session');
INSERT INTO `languages` VALUES('deny_notifs_session', 'ru', 'Кликните, для того, чтобы отключить это окошко на время текущей сессии');
INSERT INTO `languages` VALUES('deny_notifs_session', 'ua', 'Клацніть, для того, щоб відключити це віконце на час поточної сесії');
INSERT INTO `languages` VALUES('deny_success', 'en', 'You have successfully unsubscribed from the release group.');
INSERT INTO `languages` VALUES('deny_success', 'ru', 'Вы успешно отписались от релиз-группы');
INSERT INTO `languages` VALUES('deny_success', 'ua', 'Ви успішно відписалися від реліз-групи');
INSERT INTO `languages` VALUES('descr', 'en', 'Description');
INSERT INTO `languages` VALUES('descr', 'ru', 'Описание');
INSERT INTO `languages` VALUES('descr', 'ua', 'Опис');
INSERT INTO `languages` VALUES('description', 'en', 'Description');
INSERT INTO `languages` VALUES('description', 'ru', 'Описание');
INSERT INTO `languages` VALUES('description', 'ua', 'Опис');
INSERT INTO `languages` VALUES('description_notice', 'ru', 'Вы можете использовать копировать-вставить (CTRL+C CTRL+V), оформление сохраняется<br /><br />Чтобы вставить шаблон релиза, нажмите на кнопку <img src="js/tiny_mce/plugins/reltemplates/img/reltemplate.gif" title="Шаблоны релизов" alt="image" /> в редакторе<br /><br />Чтобы заполнить описание фильма, используя данные kinopoisk.ru, нажмите <img src="js/tiny_mce/plugins/kinopoisk/img/kinopoisk.gif" title="Парсер кинопоиска" alt="image" />');
INSERT INTO `languages` VALUES('description_notice', 'ua', 'Ви можете використовувати копіювати-вставити (CTRL + C CTRL + V), оформлення зберігається<br /><br />Щоб вставити шаблон релізу, натисніть на кнопку <img src="js/tiny_mce/plugins/reltemplates/img/reltemplate.gif" title="Шаблони релізів" alt="image" /> в редакторі<br /><br />Щоб заповнити опис фільму, використовуючи дані kinopoisk.ru, натисніть <img src="js/tiny_mce/plugins/kinopoisk/img/kinopoisk.gif" title="Парсер Кінопошука" alt="image" />');
INSERT INTO `languages` VALUES('design', 'en', 'Style');
INSERT INTO `languages` VALUES('design', 'ru', 'Стиль');
INSERT INTO `languages` VALUES('design', 'ua', 'Стиль');
INSERT INTO `languages` VALUES('details_10_last_snatched', 'en', '10 last snatched');
INSERT INTO `languages` VALUES('details_10_last_snatched', 'ru', '10 последних скачавших');
INSERT INTO `languages` VALUES('details_10_last_snatched', 'ua', '10 останніх завантаживших');
INSERT INTO `languages` VALUES('details_10_last_snatched_noone', 'en', 'Nobody got this torrent yet');
INSERT INTO `languages` VALUES('details_10_last_snatched_noone', 'ru', 'Еще никто не скачал этот релиз по torrent');
INSERT INTO `languages` VALUES('details_10_last_snatched_noone', 'ua', 'Ще ніхто не завантажив цей реліз за torrent');
INSERT INTO `languages` VALUES('details_leeching', 'en', 'Leeching');
INSERT INTO `languages` VALUES('details_leeching', 'ru', 'Качающие');
INSERT INTO `languages` VALUES('details_leeching', 'ua', 'Качають');
INSERT INTO `languages` VALUES('details_seeding', 'en', 'Seeding');
INSERT INTO `languages` VALUES('details_seeding', 'ru', 'Раздающие');
INSERT INTO `languages` VALUES('details_seeding', 'ua', 'Роздають');
INSERT INTO `languages` VALUES('disabled', 'en', 'Excuse your akkaunt unlocked, reason:');
INSERT INTO `languages` VALUES('disabled', 'ru', 'Извините, ваш аккаунт отключен, причина:');
INSERT INTO `languages` VALUES('disabled', 'ua', 'Вибачте, ваш обліковий запис відключений, причина:');
INSERT INTO `languages` VALUES('disabled_rating', 'en', '(Due to low ratings). You can raise your ranking by purchasing a ransom (or include your account immediately by buying VIP-privilege) <a href="donate.php"> on this page</a>');
INSERT INTO `languages` VALUES('disabled_rating', 'ru', '(из-за малого рейтинга). Вы можете поднять свой рейтинг, купив скидку (или включить аккаунт немедленно, купив VIP-привелегии) <a href="donate.php">на этой странице</a>');
INSERT INTO `languages` VALUES('disabled_rating', 'ua', '(через малий рейтинг). Ви можете підняти свій рейтинг, купивши знижку (або включити акаунт негайно, купивши VIP-привілегії) <a href="donate.php">на цій сторінці</a>');
INSERT INTO `languages` VALUES('disable_export', 'en', 'Disable export of releases on the forum from this category');
INSERT INTO `languages` VALUES('disable_export', 'ru', 'Отключить экспорт релизов на форум из этой категории');
INSERT INTO `languages` VALUES('disable_export', 'ua', 'Вимкнути експорт релізів на форум з цієї категорії');
INSERT INTO `languages` VALUES('disable_export_short', 'en', 'Export disabled');
INSERT INTO `languages` VALUES('disable_export_short', 'ru', 'Экспорт отключен');
INSERT INTO `languages` VALUES('disable_export_short', 'ua', 'Експорт відключений');
INSERT INTO `languages` VALUES('discounted', 'en', 'Bought off');
INSERT INTO `languages` VALUES('discounted', 'ru', 'По скидке прибавляется');
INSERT INTO `languages` VALUES('discounted', 'ua', 'За знижку додається');
INSERT INTO `languages` VALUES('discount_limit', 'en', 'You can not get this amount of farmed, as In this case it will exceed the number of downloaded releases, which is prohibited by the rules.');
INSERT INTO `languages` VALUES('discount_limit', 'ru', 'Вы не можете получить данное количество скидки, т.к. в данном случае оно будет превышать количество скачанных релизов, что запрещено правилами');
INSERT INTO `languages` VALUES('discount_limit', 'ua', 'Ви не можете отримати дану кількість знижки, тому що в даному випадку вона буде перевищувати кількість викачаних релізів, що заборонено правилами');
INSERT INTO `languages` VALUES('discount_link', 'en', 'You can <strong><a href="myrating.php?discount"><span style="color: red;">get farmed</span></a></strong> exchanging %s rating of 1 farming or <a href="donate.php?smszamok">Paying</a><br /><small>Tax farming is added to Seeder releases, for example, you are distributing 3 release, and gave farmed for 3 release, it turns out that you are distributing as least 6 releases :)</small>');
INSERT INTO `languages` VALUES('discount_link', 'ru', 'Вы можете <strong><a href="myrating.php?discount"><span style="color: red;">получить скидку</span></a></strong> обменяв %s рейтинга на 1 релиз или на <a href="donate.php?smszamok">платной основе</a><br /><small>Скидка прибавляется к сидирующимся релизам, например вы сидируете 3 и а скидка на 3 релиза, получается что Вы сидируете какбы 6 релизов :)</small>');
INSERT INTO `languages` VALUES('discount_link', 'ua', 'Ви можете <strong><a href="myrating.php?discount"><span style="color: red;">отримати знижку</span></a></strong> обмінявши %s рейтингу на 1 реліз або на <a href="donate.php?smszamok">платній основі</a><br /><small>Знижка додається до сідуючих релізів, наприклад ви сідуєте 3 та відкупилися від трьох, виходить що Ви сідіруете какби 6 релізів :)</small>');
INSERT INTO `languages` VALUES('distr_our_tracker', 'en', 'This torrent at the moment is heard only on our tracker.');
INSERT INTO `languages` VALUES('distr_our_tracker', 'ru', 'Этот торрент на данный момент раздается только на нашем трекере.');
INSERT INTO `languages` VALUES('distr_our_tracker', 'ua', 'Цей торрент на даний момент лунає тільки на нашому трекері.');
INSERT INTO `languages` VALUES('dl_speed', 'en', 'Download speed');
INSERT INTO `languages` VALUES('dl_speed', 'ru', 'Закачка');
INSERT INTO `languages` VALUES('dl_speed', 'ua', 'Закачка');
INSERT INTO `languages` VALUES('done', 'en', 'Done');
INSERT INTO `languages` VALUES('done', 'ru', 'Готово');
INSERT INTO `languages` VALUES('done', 'ua', 'Готово');
INSERT INTO `languages` VALUES('dont_invite', 'en', 'You do not have invitions!');
INSERT INTO `languages` VALUES('dont_invite', 'ru', 'У вас больше не осталось приглашений!');
INSERT INTO `languages` VALUES('dont_invite', 'ua', 'У вас більше не залишилося запрошень!');
INSERT INTO `languages` VALUES('dont_set_cache', 'en', 'Not selected cache cleaning');
INSERT INTO `languages` VALUES('dont_set_cache', 'ru', 'Не выбран кэш для очистки');
INSERT INTO `languages` VALUES('dont_set_cache', 'ua', 'Не вибрано кеш для очищення');
INSERT INTO `languages` VALUES('Down', 'en', 'Down');
INSERT INTO `languages` VALUES('Down', 'ru', 'Низ');
INSERT INTO `languages` VALUES('down', 'ua', 'Низ');
INSERT INTO `languages` VALUES('download', 'en', 'Download');
INSERT INTO `languages` VALUES('download', 'ru', 'Скачать');
INSERT INTO `languages` VALUES('download', 'ua', 'Завантажити');
INSERT INTO `languages` VALUES('Download again', 'en', 'Download again');
INSERT INTO `languages` VALUES('Download again', 'ru', 'Скачать еще раз');
INSERT INTO `languages` VALUES('download again', 'ua', 'Завантажити ще раз');
INSERT INTO `languages` VALUES('downloaded', 'en', 'Downloaded');
INSERT INTO `languages` VALUES('downloaded', 'ru', 'Скачал');
INSERT INTO `languages` VALUES('downloaded', 'ua', 'Завантажив');
INSERT INTO `languages` VALUES('downloaded_rel', 'en', 'Downloads');
INSERT INTO `languages` VALUES('downloaded_rel', 'ru', 'Скачали');
INSERT INTO `languages` VALUES('downloaded_rel', 'ua', 'Завантажили');
INSERT INTO `languages` VALUES('downloading', 'en', 'Downloading');
INSERT INTO `languages` VALUES('downloading', 'ru', 'В раздаче');
INSERT INTO `languages` VALUES('downloading', 'ua', 'У роздачі');
INSERT INTO `languages` VALUES('downloading_torrent', 'en', 'Downloading a torrent');
INSERT INTO `languages` VALUES('downloading_torrent', 'ru', 'Скачивание релиза');
INSERT INTO `languages` VALUES('downloading_torrent', 'ua', 'Завантаження релізу');
INSERT INTO `languages` VALUES('download_notice', 'en', 'Downloading this release you rating will decrease by %s , and will become %s. Remember, that with %s rating you can\\''t');
INSERT INTO `languages` VALUES('download_notice', 'ru', 'При скачивании этого релиза у вас отнимется %s рейтинга, и он станет равным %s. Помните, что при рейтинге %s вам будет запрещено скачивание релизов. Получить "скидку" вы можете на странице "Мой рейтинг". Если вдруг закачка торрента прервалась, вы можете скачать это торрент повторно без изменения рейтинга.');
INSERT INTO `languages` VALUES('download_notice', 'ua', 'При завантаженні цього релізу у вас заберуть %s рейтингу, і він стане рівним %s. Пам''ятайте, що при рейтингу %s вам буде заборонено скачування релізів. Отримати "знижку" ви можете на сторінці "Мій рейтинг". Якщо раптом закачування торрента перервалася, ви можете завантажити це торрент повторно без зміни рейтингу.');
INSERT INTO `languages` VALUES('download_torrent', 'en', 'Download torrent!');
INSERT INTO `languages` VALUES('download_torrent', 'ru', 'Скачать торрент!');
INSERT INTO `languages` VALUES('download_torrent', 'ua', 'Завантажити торрент!');
INSERT INTO `languages` VALUES('download_zip_again', 'en', 'You can download all previous releases in one ZIP-archive without rating decrease<br/><a href="%s">View downloaded releases</a> or <a href="%s">Download ZIP-archive with torrents</a>');
INSERT INTO `languages` VALUES('download_zip_again', 'ru', 'Вы можете скачать все скачанные ранее релизы одним ZIP-архивом без понижения рейтинга<br/><a href="%s">Посмотреть скачанные релизы</a> или <a href="%s">Скачать ZIP-архив с торрентами</a>');
INSERT INTO `languages` VALUES('download_zip_again', 'ua', 'Ви можете завантажити всі викачані раніше релізи одним ZIP-архівом без пониження рейтингу<br /><a href="%s">Переглянути викачані релізи</a> или <a href="%s">Завантажити ZIP-архів з торентами</a>');
INSERT INTO `languages` VALUES('down_formula', 'en', 'Вы раздаете %s релизов, с учетом скидки в %s релизов, что в сумме меньше, чем скачали (%s скачанных релизов), поэтому Ваш рейтинг изменяется на');
INSERT INTO `languages` VALUES('down_formula', 'ru', 'Вы раздаете %s релизов, с учетом скидки в %s релизов, что в сумме меньше, чем скачали (%s скачанных релизов), поэтому Ваш рейтинг изменяется на');
INSERT INTO `languages` VALUES('down_formula', 'ua', 'Ви роздаєте %s релізів, з урахуванням знижки в %s релізів, що в сумі менше, ніж скачали (%s викачаних релізів), тому Ваш рейтинг змінюється на');
INSERT INTO `languages` VALUES('down_levels', 'en', 'The lower level of restriction');
INSERT INTO `languages` VALUES('down_levels', 'ru', 'Пороги ограничений');
INSERT INTO `languages` VALUES('down_levels', 'ua', 'Пороги обмежень');
INSERT INTO `languages` VALUES('down_notice', 'en', 'In rating a %s , you can not download releases, and at %s Your account will be disabled');
INSERT INTO `languages` VALUES('down_notice', 'ru', 'При рейтинге в %s Вы не сможете скачивать релизы, а при %s Ваш аккаунт будет отключен');
INSERT INTO `languages` VALUES('down_notice', 'ua', 'За рейтингу в %s Ви не зможете скачувати релізи, а при %s Ваш акаунт буде відключений');
INSERT INTO `languages` VALUES('down_size', 'en', 'Down size');
INSERT INTO `languages` VALUES('down_size', 'ru', 'Скачал');
INSERT INTO `languages` VALUES('down_size', 'ua', 'Завантажив');
INSERT INTO `languages` VALUES('E-mail or password is invalid', 'en', 'E-mail or password is invalid');
INSERT INTO `languages` VALUES('E-mail or password is invalid', 'ru', 'Эта комбинация данных для входа неверная. Попробуйте еще раз');
INSERT INTO `languages` VALUES('e-mail or password is invalid', 'ua', 'Ця комбінація даних для входу невірна. Спробуйте ще раз');
INSERT INTO `languages` VALUES('edit', 'en', 'Edit');
INSERT INTO `languages` VALUES('edit', 'ru', 'Редактировать');
INSERT INTO `languages` VALUES('edit', 'ua', 'Редагувати');
INSERT INTO `languages` VALUES('edited', 'en', 'Successful editing!');
INSERT INTO `languages` VALUES('edited', 'ru', 'Успешное редактирование!');
INSERT INTO `languages` VALUES('edited', 'ua', 'Успішне редагування!');
INSERT INTO `languages` VALUES('editing_retracker', 'en', 'Edit retracker');
INSERT INTO `languages` VALUES('editing_retracker', 'ru', 'Редактирование ретрекера');
INSERT INTO `languages` VALUES('editing_retracker', 'ua', 'Редагування ретрекера');
INSERT INTO `languages` VALUES('editing_succ', 'en', 'Editing of data was successful');
INSERT INTO `languages` VALUES('editing_succ', 'ru', 'Изменение данных прошло успешно');
INSERT INTO `languages` VALUES('editing_succ', 'ua', 'Зміна даних пройшло успішно');
INSERT INTO `languages` VALUES('edit_delete', 'en', 'Edit/Delete');
INSERT INTO `languages` VALUES('edit_delete', 'ru', 'Ред/Уд');
INSERT INTO `languages` VALUES('edit_delete', 'ua', 'Ред / Вид');
INSERT INTO `languages` VALUES('edit_group', 'en', 'Edit release groups');
INSERT INTO `languages` VALUES('edit_group', 'ru', 'Редактирование группы');
INSERT INTO `languages` VALUES('edit_group', 'ua', 'Редагування групи');
INSERT INTO `languages` VALUES('email', 'en', 'E-Mail');
INSERT INTO `languages` VALUES('email', 'ru', 'eMail');
INSERT INTO `languages` VALUES('email', 'ua', 'eMail');
INSERT INTO `languages` VALUES('email_config_link', 'en', 'You can always configure your notifications in <a href="%s">notification settings</a> of your account.');
INSERT INTO `languages` VALUES('email_config_link', 'ru', 'Вы всегда можете настроить ваши уведомления в <a href="%s">настройках уведомлений</a> вашего аккаунта.');
INSERT INTO `languages` VALUES('email_config_link', 'ua', 'Ви завжди можете налаштувати ваші повідомлення у <a href="%s">налаштуваннях повідомлень</a> вашого облікового запису.');
INSERT INTO `languages` VALUES('email_nickname', 'en', 'E-mail or nickname');
INSERT INTO `languages` VALUES('email_nickname', 'ru', 'E-mail или никнейм');
INSERT INTO `languages` VALUES('email_nickname', 'ua', 'E-mail або нікнейм');
INSERT INTO `languages` VALUES('email_sender', 'en', '<b>E-Mail sender</b>:');
INSERT INTO `languages` VALUES('email_sender', 'ru', '<b>E-Mail отправителя</b>:');
INSERT INTO `languages` VALUES('email_sender', 'ua', '<b>E-Mail відправника</b>:');
INSERT INTO `languages` VALUES('en', 'en', 'English (EN-US)');
INSERT INTO `languages` VALUES('en', 'ru', 'English (EN-US)');
INSERT INTO `languages` VALUES('en', 'ua', 'English (EN-US)');
INSERT INTO `languages` VALUES('Enable WYSIWYG editor', 'en', 'Enable WYSIWYG editor');
INSERT INTO `languages` VALUES('Enable WYSIWYG editor', 'ru', 'Включить WYSIWYG редактор');
INSERT INTO `languages` VALUES('enable wysiwyg editor', 'ua', 'Включити WYSIWYG редактор');
INSERT INTO `languages` VALUES('Enabled', 'en', 'Enabled');
INSERT INTO `languages` VALUES('Enabled', 'ru', 'Включено');
INSERT INTO `languages` VALUES('enabled', 'ua', 'Включено');
INSERT INTO `languages` VALUES('enable_popup', 'en', 'Enable pop-up notification');
INSERT INTO `languages` VALUES('enable_popup', 'ru', 'Включить всплывающие уведомления');
INSERT INTO `languages` VALUES('enable_popup', 'ua', 'Включити спливаючі повідомлення');
INSERT INTO `languages` VALUES('enable_success', 'en', 'Pop-up notification successfully enabled');
INSERT INTO `languages` VALUES('enable_success', 'ru', 'Всплывающие уведомления успешно включены');
INSERT INTO `languages` VALUES('enable_success', 'ua', 'Спливаючі повідомлення успішно включені');
INSERT INTO `languages` VALUES('enter_invite_code', 'en', 'You can enter Invite code and sign up for free:');
INSERT INTO `languages` VALUES('enter_invite_code', 'ru', 'Вы можете ввести инвайт-код и подписаться безвозмездно:');
INSERT INTO `languages` VALUES('enter_invite_code', 'ua', 'Ви можете ввести інвайт-код і підписатися безоплатно:');
INSERT INTO `languages` VALUES('enter_message', 'en', 'Please enter a message!');
INSERT INTO `languages` VALUES('enter_message', 'ru', 'Пожалуста, введите сообщение!');
INSERT INTO `languages` VALUES('enter_message', 'ua', 'Будь ласка, введіть повідомлення!');
INSERT INTO `languages` VALUES('enter_reason_pr', 'en', 'You can add beautiful postcard to your present. Write some poetry, or leave postcard blank.');
INSERT INTO `languages` VALUES('enter_reason_pr', 'ru', 'Вы можете добавить замечательную открытку к вашему подарку. Напишите стихи или оставьте поле пустым.');
INSERT INTO `languages` VALUES('enter_reason_pr', 'ua', 'Вы можете добавить замечательную открытку к вашему подарку. Напишите стихи или оставьте поле пустым.');
INSERT INTO `languages` VALUES('enter_subject', 'en', 'Please enter the subject!');
INSERT INTO `languages` VALUES('enter_subject', 'ru', 'Пожалуста, введите тему!');
INSERT INTO `languages` VALUES('enter_subject', 'ua', 'Будь ласка, введіть тему!');
INSERT INTO `languages` VALUES('enter_topic', 'en', 'Please enter the topic!');
INSERT INTO `languages` VALUES('enter_topic', 'ru', 'Пожалуста, введите тему!');
INSERT INTO `languages` VALUES('enter_topic', 'ua', 'Будь ласка, введіть тему!');
INSERT INTO `languages` VALUES('error', 'en', 'Error');
INSERT INTO `languages` VALUES('error', 'ru', 'Ошибка');
INSERT INTO `languages` VALUES('error', 'ua', 'Помилка');
INSERT INTO `languages` VALUES('errors', 'en', 'Errors');
INSERT INTO `languages` VALUES('errors', 'ru', 'Ошибки');
INSERT INTO `languages` VALUES('errors', 'ua', 'Помилки');
INSERT INTO `languages` VALUES('error_calculating', 'en', 'Error calculating the confirmation code');
INSERT INTO `languages` VALUES('error_calculating', 'ru', 'Ошибка вычисления кода подтверждения');
INSERT INTO `languages` VALUES('error_calculating', 'ua', 'Помилка обчислення коду підтвердження');
INSERT INTO `languages` VALUES('error_change_address', 'en', 'Error change of address');
INSERT INTO `languages` VALUES('error_change_address', 'ru', 'Ошибка изменения адреса');
INSERT INTO `languages` VALUES('error_change_address', 'ua', 'Помилка зміни адреси');
INSERT INTO `languages` VALUES('error_no_onwers', 'en', '<h1>There is no owners of this relgroup, contact site admin.</h1>');
INSERT INTO `languages` VALUES('error_no_onwers', 'ru', '<h1>У текущей резиз группы не назначены владельцы, обратитесь к администратору ресурса</h1>');
INSERT INTO `languages` VALUES('error_no_onwers', 'ua', '<h1>У поточній резіз групи не призначені власники, зверніться до адміністратора ресурсу</h1>');
INSERT INTO `languages` VALUES('event', 'en', 'Event');
INSERT INTO `languages` VALUES('event', 'ru', 'Событие');
INSERT INTO `languages` VALUES('event', 'ua', 'Подія');
INSERT INTO `languages` VALUES('Example', 'en', 'Example');
INSERT INTO `languages` VALUES('Example', 'ru', 'Например');
INSERT INTO `languages` VALUES('example', 'ua', 'Наприклад');
INSERT INTO `languages` VALUES('exchange', 'en', 'Exchange');
INSERT INTO `languages` VALUES('exchange', 'ru', 'Обменник');
INSERT INTO `languages` VALUES('exchange', 'ua', 'Обмінник');
INSERT INTO `languages` VALUES('Expires on', 'en', 'Expires on');
INSERT INTO `languages` VALUES('Expires on', 'ru', 'Истекает');
INSERT INTO `languages` VALUES('expires on', 'ua', 'Закінчується');
INSERT INTO `languages` VALUES('Export and download', 'en', 'Export and download');
INSERT INTO `languages` VALUES('Export and download', 'ru', 'Экспортировать/скачать');
INSERT INTO `languages` VALUES('export and download', 'ua', 'Експортувати / завантажити');
INSERT INTO `languages` VALUES('Export language to file', 'en', 'Export language to file');
INSERT INTO `languages` VALUES('Export language to file', 'ru', 'Экспортировать язык в файл');
INSERT INTO `languages` VALUES('export language to file', 'ua', 'Експортувати мову в файл');
INSERT INTO `languages` VALUES('exportrelease_mname', 'en', 'Export release to another site');
INSERT INTO `languages` VALUES('exportrelease_mname', 'ru', 'Экспортировать на сайт');
INSERT INTO `languages` VALUES('exportrelease_mname', 'ua', 'Експортувати на сайт');
INSERT INTO `languages` VALUES('exportrelease_notice', 'en', 'Description');
INSERT INTO `languages` VALUES('exportrelease_notice', 'ru', 'Описание релиза<br />');
INSERT INTO `languages` VALUES('exportrelease_notice', 'ua', 'Опис релізу<br />');
INSERT INTO `languages` VALUES('exportrelease_warning', 'en', '<b>Warning!</b> At placing of our release on other sites the reference to our site is obligatory!');
INSERT INTO `languages` VALUES('exportrelease_warning', 'ru', '<b>Внимание!</b> При размещении нашего релиза на других сайтах ссылка на наш сайт обязательна!');
INSERT INTO `languages` VALUES('exportrelease_warning', 'ua', '<b>Увага!</b> При розміщенні нашого релізу на інших сайтах посилання на наш сайт обов''язкове!');
INSERT INTO `languages` VALUES('export_id', 'en', 'ID forums IPB for export releases <br /><small>Leave this field blank for auto exports, if the name of the forum and category match</small>');
INSERT INTO `languages` VALUES('export_id', 'ru', 'ID форума IPB для экспорта релизов<br /><small>Оставьте это поле пустым для автоматического экспорта, если названия форума и категории совпадают</small>');
INSERT INTO `languages` VALUES('export_id', 'ua', 'ID форуму IPB для експорту релізів<br /><small>Залиште це поле порожнім для автоматичного експорту, якщо назви форуму і категорії збігаються</small>');
INSERT INTO `languages` VALUES('fail_invite', 'en', 'You are already subscribed to the release of the group.');
INSERT INTO `languages` VALUES('fail_invite', 'ru', 'Вы уже подписаны на релизы этой группы');
INSERT INTO `languages` VALUES('fail_invite', 'ua', 'Ви вже підписані на релізи цієї групи');
INSERT INTO `languages` VALUES('faq', 'en', 'FAQ');
INSERT INTO `languages` VALUES('faq', 'ru', 'ЧаВо');
INSERT INTO `languages` VALUES('faq', 'ua', 'ЧаПи');
INSERT INTO `languages` VALUES('files', 'en', 'Files');
INSERT INTO `languages` VALUES('files', 'ru', 'Файлов');
INSERT INTO `languages` VALUES('files', 'ua', 'Файлів');
INSERT INTO `languages` VALUES('files_l', 'en', 'Files');
INSERT INTO `languages` VALUES('files_l', 'ru', 'файлов');
INSERT INTO `languages` VALUES('files_l', 'ua', 'файлів');
INSERT INTO `languages` VALUES('file_list', 'en', 'File list');
INSERT INTO `languages` VALUES('file_list', 'ru', 'Список файлов');
INSERT INTO `languages` VALUES('file_list', 'ua', 'Список файлів');
INSERT INTO `languages` VALUES('file_size', 'en', '<br />File Size: <b> %s kilobyte. </b><hr /><div style="text-align: center;">Avatar added to user profile</div>');
INSERT INTO `languages` VALUES('file_size', 'ru', '<br />Размер файла: <b> %s кб. </b><hr /><div style="text-align: center;">Аватар автоматически добавлен в профиль пользователя</div>');
INSERT INTO `languages` VALUES('file_size', 'ua', '<br />Розмір файлу: <b> %s кб. </b><hr /><div style="text-align: center;">Аватар автоматично доданий в профіль користувача</div>');
INSERT INTO `languages` VALUES('filled', 'en', 'Filled in?');
INSERT INTO `languages` VALUES('filled', 'ru', 'Выполнен?');
INSERT INTO `languages` VALUES('filled', 'ua', 'Виконано?');
INSERT INTO `languages` VALUES('filled_by', 'en', 'Filled out');
INSERT INTO `languages` VALUES('filled_by', 'ru', 'Выполнил');
INSERT INTO `languages` VALUES('filled_by', 'ua', 'Виконав');
INSERT INTO `languages` VALUES('fill_form', 'en', 'Fill out the form correctly.');
INSERT INTO `languages` VALUES('fill_form', 'ru', 'Пожалуйста заполняйте форму корректно.');
INSERT INTO `languages` VALUES('fill_form', 'ua', 'Будь ласка заповнюйте форму коректно.');
INSERT INTO `languages` VALUES('First', 'en', 'First');
INSERT INTO `languages` VALUES('First', 'ru', 'Первая');
INSERT INTO `languages` VALUES('flag', 'en', 'Flag');
INSERT INTO `languages` VALUES('flag', 'ru', 'Флаг');
INSERT INTO `languages` VALUES('flag', 'ua', 'Прапор');
INSERT INTO `languages` VALUES('footer_ratiopopup', 'en', 'Go to <a href="%s">My rating stats</a> or <a href="%s">exchange rating to discount</a>');
INSERT INTO `languages` VALUES('footer_ratiopopup', 'ru', 'Перейти к <a href="%s">Моему рейтингу</a> или <a href="%s">Обмену рейтинга на скидку</a>');
INSERT INTO `languages` VALUES('footer_ratiopopup', 'ua', 'Перейти до <a href="%s">Мого рейтингу</a> або <a href="%s">Обміну рейтингу на знижку</a>');
INSERT INTO `languages` VALUES('forced_cleaning', 'en', 'Forced Cleaning');
INSERT INTO `languages` VALUES('forced_cleaning', 'ru', 'Принудительная Очистка Релизера');
INSERT INTO `languages` VALUES('forced_cleaning', 'ua', 'Примусова Очищення релізера');
INSERT INTO `languages` VALUES('forgot_psw', 'en', '<p>If you forgot your password, try to recover it on <a href="%s">Password recovery page</a></p><p>You did not register yet? You can <a href="%s">Register now!</a></p>');
INSERT INTO `languages` VALUES('forgot_psw', 'ru', '<p>Если вы забыли пароль, вы можете восстановить его на <a href="%s">странице восстановления пароля</a><p>Вы еще не зарегестрированы? <a href="%s">Зарегестрируйтесь сейчас!</a></p>');
INSERT INTO `languages` VALUES('forgot_psw', 'ua', '<p>Якщо ви забули пароль, ви можете відновити його на <a href="%s">сторінці відновлення пароля</a></p><p>Ви ще не зареєстровані? <a href="%s">Зареєструйтесь зараз!</a></p>');
INSERT INTO `languages` VALUES('formats', 'en', 'File formats');
INSERT INTO `languages` VALUES('formats', 'ru', 'Форматы файлов');
INSERT INTO `languages` VALUES('formats', 'ua', 'Формати файлів');
INSERT INTO `languages` VALUES('form_contact', 'en', 'Form of contact');
INSERT INTO `languages` VALUES('form_contact', 'ru', 'Форма для связи');
INSERT INTO `languages` VALUES('form_contact', 'ua', 'Форма для зв''язку');
INSERT INTO `languages` VALUES('form_contact_for_admin', 'en', 'The form for communication with the Administration');
INSERT INTO `languages` VALUES('form_contact_for_admin', 'ru', 'Форма для связи с Администрацией');
INSERT INTO `languages` VALUES('form_contact_for_admin', 'ua', 'Форма для зв''язку з Адміністрацією');
INSERT INTO `languages` VALUES('forum', 'en', 'Forum');
INSERT INTO `languages` VALUES('forum', 'ru', 'Форум');
INSERT INTO `languages` VALUES('forum', 'ua', 'Форум');
INSERT INTO `languages` VALUES('Forum topic', 'en', 'Forum topic');
INSERT INTO `languages` VALUES('Forum topic', 'ru', 'Тема форума');
INSERT INTO `languages` VALUES('forum topic', 'ua', 'темі форуму');
INSERT INTO `languages` VALUES('Forumcomments', 'en', 'Forumcomments');
INSERT INTO `languages` VALUES('Forumcomments', 'ru', 'Комм.форума');
INSERT INTO `languages` VALUES('forumcomments', 'ua', 'Ком. форума');
INSERT INTO `languages` VALUES('forum_admincp_title', 'en', 'Forum categories administration');
INSERT INTO `languages` VALUES('forum_admincp_title', 'ru', 'Администрирование категорий форума');
INSERT INTO `languages` VALUES('forum_admincp_title', 'ua', 'Адміністрування категорій форуму');
INSERT INTO `languages` VALUES('forum_category_deleted', 'en', 'Forum category deleted');
INSERT INTO `languages` VALUES('forum_category_deleted', 'ru', 'Категория форума удалена');
INSERT INTO `languages` VALUES('forum_category_deleted', 'ua', 'Категорія форуму видалена');
INSERT INTO `languages` VALUES('forum_category_edited', 'en', 'Forum category successfully edited');
INSERT INTO `languages` VALUES('forum_category_edited', 'ru', 'Категория форума успешно отредактирована');
INSERT INTO `languages` VALUES('forum_category_edited', 'ua', 'Категория форума успешно отредактирована');
INSERT INTO `languages` VALUES('forum_enabled', 'en', 'Is forum enabled?');
INSERT INTO `languages` VALUES('forum_enabled', 'ru', 'Форум включен?');
INSERT INTO `languages` VALUES('forum_enabled', 'ua', 'Форум увімкнений?');
INSERT INTO `languages` VALUES('forum_home_link', 'en', '<a href="%s">Forum home</a>');
INSERT INTO `languages` VALUES('forum_home_link', 'ru', '<a href="%s">Форумы</a>');
INSERT INTO `languages` VALUES('forum_home_link', 'ua', '<a href="%s">Форуми</a>');
INSERT INTO `languages` VALUES('forum_id', 'en', 'IPB\\''s forum ID');
INSERT INTO `languages` VALUES('forum_id', 'ru', 'ID форума IPB');
INSERT INTO `languages` VALUES('forum_id', 'ua', 'ID форума IPB');
INSERT INTO `languages` VALUES('forum_no_topics', 'en', 'There is no topics in this forum yet, but you can <a href="%s">create one</a>.');
INSERT INTO `languages` VALUES('forum_no_topics', 'ru', 'В этом форуме еще нет тем, но вы можете <a href="%s">создать новую</a>.');
INSERT INTO `languages` VALUES('forum_no_topics', 'ua', 'У цьому форумі ще немає тем, але ви можете <a href="%s">створити нову</a>.');
INSERT INTO `languages` VALUES('forum_selector', 'en', '*In the rounds selected category, which will be used for automatic creation of release on the forum');
INSERT INTO `languages` VALUES('forum_selector', 'ru', '*В кружках выбирается категория, которая будет использована при автоматическом создании релиза на форуме');
INSERT INTO `languages` VALUES('forum_selector', 'ua', '*У гуртках вибирається категорія, яка буде використана при автоматичному створенні релізу на форумі');
INSERT INTO `languages` VALUES('forum_viewing_topic', 'en', 'Viewing topic: %s');
INSERT INTO `languages` VALUES('forum_viewing_topic', 'ru', 'Просмотр темы: %s');
INSERT INTO `languages` VALUES('forum_viewing_topic', 'ua', 'Перегляд теми: %s');
INSERT INTO `languages` VALUES('forum_you_in', 'en', 'You currently in: %s');
INSERT INTO `languages` VALUES('forum_you_in', 'ru', 'Вы в: %s');
INSERT INTO `languages` VALUES('forum_you_in', 'ua', 'Ви в: %s');
INSERT INTO `languages` VALUES('for_admin', 'en', 'For administrators');
INSERT INTO `languages` VALUES('for_admin', 'ru', 'Видно администраторам');
INSERT INTO `languages` VALUES('for_admin', 'ua', 'Видно адміністраторам');
INSERT INTO `languages` VALUES('for_moderators', 'en', 'For moderators');
INSERT INTO `languages` VALUES('for_moderators', 'ru', 'Видно модераторам');
INSERT INTO `languages` VALUES('for_moderators', 'ua', 'Видно модераторам');
INSERT INTO `languages` VALUES('for_owners', 'en', 'For owners');
INSERT INTO `languages` VALUES('for_owners', 'ru', 'Видно сис. администраторам');
INSERT INTO `languages` VALUES('for_owners', 'ua', 'Видно сис. адміністраторам');
INSERT INTO `languages` VALUES('friend', 'en', 'friend');
INSERT INTO `languages` VALUES('friend', 'ru', 'друга');
INSERT INTO `languages` VALUES('friend', 'ua', 'друга');
INSERT INTO `languages` VALUES('friends', 'en', 'Friends');
INSERT INTO `languages` VALUES('friends', 'ru', 'Друзья');
INSERT INTO `languages` VALUES('friends', 'ua', 'Друзі');
INSERT INTO `languages` VALUES('friendship_cancelled', 'en', 'The friendship is over');
INSERT INTO `languages` VALUES('friendship_cancelled', 'ru', 'Дружба прервана');
INSERT INTO `languages` VALUES('friendship_cancelled', 'ua', 'Дружба перервана');
INSERT INTO `languages` VALUES('friends_list', 'en', 'List of friends');
INSERT INTO `languages` VALUES('friends_list', 'ru', 'Список друзей');
INSERT INTO `languages` VALUES('friends_list', 'ua', 'Список друзів');
INSERT INTO `languages` VALUES('friend_confirmed', 'en', 'Now you are confirmed that %s is your friend, thank you');
INSERT INTO `languages` VALUES('friend_confirmed', 'ru', 'Вы подтвердили, что пользователь %s является вашем другом, спасибо');
INSERT INTO `languages` VALUES('friend_confirmed', 'ua', 'Ви підтвердили, що користувач %s є вашим другом, спасибі');
INSERT INTO `languages` VALUES('friend_deleted', 'en', 'The user has been removed from your friends');
INSERT INTO `languages` VALUES('friend_deleted', 'ru', 'Пользователь удален из ваших друзей');
INSERT INTO `languages` VALUES('friend_deleted', 'ua', 'Користувача видалено з ваших друзів');
INSERT INTO `languages` VALUES('friend_deny', 'en', 'The user миша has refused to you friendship');
INSERT INTO `languages` VALUES('friend_deny', 'ru', 'Пользователь %s отказал вам в дружбе');
INSERT INTO `languages` VALUES('friend_deny', 'ua', 'Користувач %s відмовив вам у дружбі');
INSERT INTO `languages` VALUES('friend_notice', 'en', 'User %s asks you for friendship, if he is your friend, you can [<a href="friends.php?action=confirm&amp;id=%s">Confirm</a>] or [<a href="friends.php?action=deny&amp;id=%s">Deny</a>] friendship');
INSERT INTO `languages` VALUES('friend_notice', 'ru', 'Пользователь %s изъявил желание добавить вас в свои друзья, если это так, то [<a href="friends.php?action=confirm&amp;id=%s">Подтвердите</a>] или [<a href="friends.php?action=deny&amp;id=%s">Откажите</a>] ему в этом');
INSERT INTO `languages` VALUES('friend_notice', 'ua', 'Користувач %s виявив бажання додати вас у свої друзі, якщо це так, то [<a href="friends.php?action=confirm&amp;id=%s">Підтвердіть</a>] або[<a href="friends.php?action=deny&amp;id=%s">Відмовте</a>] йому в цьому');
INSERT INTO `languages` VALUES('friend_notice_subject', 'en', 'Lets be friends?');
INSERT INTO `languages` VALUES('friend_notice_subject', 'ru', 'Давай дружить?');
INSERT INTO `languages` VALUES('friend_notice_subject', 'ua', 'Давай дружити?');
INSERT INTO `languages` VALUES('friend_pending', 'en', 'Expects confirmation');
INSERT INTO `languages` VALUES('friend_pending', 'ru', 'Ожидает подтверждения');
INSERT INTO `languages` VALUES('friend_pending', 'ua', 'Очікує підтвердження');
INSERT INTO `languages` VALUES('from', 'en', 'from');
INSERT INTO `languages` VALUES('from', 'ru', 'из');
INSERT INTO `languages` VALUES('from', 'ua', 'з');
INSERT INTO `languages` VALUES('From_present', 'en', 'From');
INSERT INTO `languages` VALUES('From_present', 'ru', 'От');
INSERT INTO `languages` VALUES('from_present', 'ua', 'Від');
INSERT INTO `languages` VALUES('from_system', 'en', 'System');
INSERT INTO `languages` VALUES('from_system', 'ru', 'Система');
INSERT INTO `languages` VALUES('from_system', 'ua', 'Система');
INSERT INTO `languages` VALUES('from_torrents', 'en', ', from them without torrents');
INSERT INTO `languages` VALUES('from_torrents', 'ru', ', из них без торрентов');
INSERT INTO `languages` VALUES('from_torrents', 'ua', ', з них без торрентів');
INSERT INTO `languages` VALUES('full_lst_rel', 'en', 'Full list of releases');
INSERT INTO `languages` VALUES('full_lst_rel', 'ru', 'Полный список релизов');
INSERT INTO `languages` VALUES('full_lst_rel', 'ua', 'Повний список релізів');
INSERT INTO `languages` VALUES('f_p', 'en', 'Friends and presents');
INSERT INTO `languages` VALUES('f_p', 'ru', 'Подарки друзьям');
INSERT INTO `languages` VALUES('f_p', 'ua', 'Подарунки друзям');
INSERT INTO `languages` VALUES('games', 'en', 'Favorite games:');
INSERT INTO `languages` VALUES('games', 'ru', 'Любимые игры:');
INSERT INTO `languages` VALUES('games', 'ua', 'Улюблені ігри:');
INSERT INTO `languages` VALUES('gender', 'en', 'Sex');
INSERT INTO `languages` VALUES('gender', 'ru', 'Пол');
INSERT INTO `languages` VALUES('gender', 'ua', 'Стать');
INSERT INTO `languages` VALUES('gender_nonselected', 'en', 'I have not selected yet:)');
INSERT INTO `languages` VALUES('gender_nonselected', 'ru', 'А я еще не определилось:)');
INSERT INTO `languages` VALUES('gender_nonselected', 'ua', 'Ще не визначено :)');
INSERT INTO `languages` VALUES('genre', 'en', 'Genre:');
INSERT INTO `languages` VALUES('genre', 'ru', 'Жанр:');
INSERT INTO `languages` VALUES('genre', 'ua', 'Жанр:');
INSERT INTO `languages` VALUES('getdox_file_not_found', 'en', 'File not found');
INSERT INTO `languages` VALUES('getdox_file_not_found', 'ru', 'Файл не найден');
INSERT INTO `languages` VALUES('getdox_file_not_found', 'ua', 'Файл не знайдено');
INSERT INTO `languages` VALUES('getdox_no_file', 'en', 'No file name');
INSERT INTO `languages` VALUES('getdox_no_file', 'ru', 'Нет имени файла');
INSERT INTO `languages` VALUES('getdox_no_file', 'ua', 'Немає імені файлу');
INSERT INTO `languages` VALUES('get_rating', 'en', 'You will receive a +%s rating for the upload release');
INSERT INTO `languages` VALUES('get_rating', 'ru', 'Вы получите +%s рейтинга за загрузку релиза');
INSERT INTO `languages` VALUES('get_rating', 'ua', 'Ви отримаєте +%s рейтингу за завантаження релізу');
INSERT INTO `languages` VALUES('gifts_friends', 'en', 'Present Friens');
INSERT INTO `languages` VALUES('gifts_friends', 'ru', 'Подарки друзьям');
INSERT INTO `languages` VALUES('gifts_friends', 'ua', 'Подарунки друзям');
INSERT INTO `languages` VALUES('girls', 'en', 'Girls');
INSERT INTO `languages` VALUES('girls', 'ru', 'Девушки');
INSERT INTO `languages` VALUES('girls', 'ua', 'Дівчата');
INSERT INTO `languages` VALUES('go', 'en', 'Go');
INSERT INTO `languages` VALUES('go', 'ru', 'Вперед');
INSERT INTO `languages` VALUES('go', 'ua', 'Вперед');
INSERT INTO `languages` VALUES('golden', 'en', 'Golden release');
INSERT INTO `languages` VALUES('golden', 'ru', 'Золотой релиз');
INSERT INTO `languages` VALUES('golden', 'ua', 'Золотий реліз');
INSERT INTO `languages` VALUES('golden_descr', 'en', 'Golden release (rating will not decrease on downloading)');
INSERT INTO `languages` VALUES('golden_descr', 'ru', 'Золотой релиз (при скачивании релиза не понижается рейтинг)');
INSERT INTO `languages` VALUES('golden_descr', 'ua', 'Золотий реліз (при скачуванні релізу не знижується рейтинг)');
INSERT INTO `languages` VALUES('golden_torrents', 'en', 'Golden releaser');
INSERT INTO `languages` VALUES('golden_torrents', 'ru', 'Золотые релизы');
INSERT INTO `languages` VALUES('golden_torrents', 'ua', 'Золоті релізи');
INSERT INTO `languages` VALUES('good', 'en', 'good!');
INSERT INTO `languages` VALUES('good', 'ru', 'good!');
INSERT INTO `languages` VALUES('good', 'ua', 'good!');
INSERT INTO `languages` VALUES('goods_new', 'en', '<span style="color: red;">You the new ( %s days left), and you learn the rating system, you do not subtract rating for downloading releases or lack seeders</span>');
INSERT INTO `languages` VALUES('goods_new', 'ru', '<span style="color: red;">Вы новичок (осталось %s дней), и Вы изучаете рейтинговую систему, у Вас не отнимается рейтинг за скачивание релизов или за отстутствие сида</span>');
INSERT INTO `languages` VALUES('goods_new', 'ua', '<span style="color: red;">Ви новачок (залишилося %s днів), і Ви вивчаєте рейтингову систему, у Вас не віднімається рейтинг за скачування релізів або за ВІДСУТНІСТЬ сіда</span>');
INSERT INTO `languages` VALUES('goods_vip', 'en', '<span style="color: red;">You the VIP, is not taken away from you rating for downloading of releases or for absence seeders</span>');
INSERT INTO `languages` VALUES('goods_vip', 'ru', '<span style="color: red;">Вы VIP, у Вас не отнимается рейтинг за скачивание релизов или за отстутствие сида</span>');
INSERT INTO `languages` VALUES('goods_vip', 'ua', '<span style="color: red;">Ви VIP, у Вас не віднімається рейтинг за скачування релізів або за відсутність сіда</span>');
INSERT INTO `languages` VALUES('go_go_go', 'en', 'Lets go');
INSERT INTO `languages` VALUES('go_go_go', 'ru', 'Поехали');
INSERT INTO `languages` VALUES('go_go_go', 'ua', 'Поїхали');
INSERT INTO `languages` VALUES('go_to', 'en', 'Go to');
INSERT INTO `languages` VALUES('go_to', 'ru', 'Перейти');
INSERT INTO `languages` VALUES('go_to', 'ua', 'Перейти');
INSERT INTO `languages` VALUES('go_to_forumadmin', 'en', '<a href="%s">Go to forum administration</a>');
INSERT INTO `languages` VALUES('go_to_forumadmin', 'ru', '<a href="%s">Перейти к администрированию форума</a>');
INSERT INTO `languages` VALUES('go_to_forumadmin', 'ua', '<a href="%s">Перейти до адміністрування форуму</a>');
INSERT INTO `languages` VALUES('group_added', 'en', 'Group successfully added. Now you can go to her page');
INSERT INTO `languages` VALUES('group_added', 'ru', 'Группа успешно добавлена. Сейчас вы перейдете к ее странице');
INSERT INTO `languages` VALUES('group_added', 'ua', 'Група успішно додана. Зараз ви перейдете до її сторінці');
INSERT INTO `languages` VALUES('group_edited', 'en', 'Group successfully edited. Now you can go to her page');
INSERT INTO `languages` VALUES('group_edited', 'ru', 'Группа успешно отредактирована. Сейчас вы перейдете к ее странице');
INSERT INTO `languages` VALUES('group_edited', 'ua', 'Група успішно відкоригована. Зараз ви перейдете до її сторінці');
INSERT INTO `languages` VALUES('group_error', 'en', 'An error occurred in the operations of the group');
INSERT INTO `languages` VALUES('group_error', 'ru', 'Произошла ошибка в операциях над группой');
INSERT INTO `languages` VALUES('group_error', 'ua', 'Сталася помилка в операціях над групою');
INSERT INTO `languages` VALUES('Guest', 'en', 'Guest');
INSERT INTO `languages` VALUES('Guest', 'ru', 'Гость');
INSERT INTO `languages` VALUES('guest', 'ua', 'Гість');
INSERT INTO `languages` VALUES('guests_online', 'en', 'Guests online');
INSERT INTO `languages` VALUES('guests_online', 'ru', 'Всего гостей');
INSERT INTO `languages` VALUES('guests_online', 'ua', 'Всього гостей');
INSERT INTO `languages` VALUES('hack', 'en', 'Hacking attempt');
INSERT INTO `languages` VALUES('hack', 'ru', 'Взлом');
INSERT INTO `languages` VALUES('hack', 'ua', 'Взлом');
INSERT INTO `languages` VALUES('has_reports', 'en', 'Filed %s reports, please understand and remove to remove this message');
INSERT INTO `languages` VALUES('has_reports', 'ru', 'Подано %s жалоб, просьба разобраться и удалить, чтобы убрать это сообщение');
INSERT INTO `languages` VALUES('has_reports', 'ua', 'Подано %s скарг, і вас просять розібратися і видалити, щоб прибрати це повідомлення');
INSERT INTO `languages` VALUES('has_unchecked', 'en', 'he site is %s untested release(s)');
INSERT INTO `languages` VALUES('has_unchecked', 'ru', 'На сайте есть %s непроверенных релиза(ов)');
INSERT INTO `languages` VALUES('has_unchecked', 'ua', 'а сайті є %s неперевірених релізів');
INSERT INTO `languages` VALUES('have_been_last_release', 'en', 'Have you been on the last release');
INSERT INTO `languages` VALUES('have_been_last_release', 'ru', 'Вы уже были на последнем релизе');
INSERT INTO `languages` VALUES('have_been_last_release', 'ua', 'Ви вже були на останньому релізі');
INSERT INTO `languages` VALUES('have_first_release', 'en', 'You were on the first release');
INSERT INTO `languages` VALUES('have_first_release', 'ru', 'Вы были на первом релизе');
INSERT INTO `languages` VALUES('have_first_release', 'ua', 'Ви були на першому релізі');
INSERT INTO `languages` VALUES('helpseed', 'en', 'Files to which are necessary to the distributing');
INSERT INTO `languages` VALUES('helpseed', 'ru', 'Файлы, которым нужны раздающим');
INSERT INTO `languages` VALUES('helpseed', 'ua', 'Файли, яким потрібні роздаюваючий');
INSERT INTO `languages` VALUES('help_seed', 'en', 'Got it done? Let others get it!');
INSERT INTO `languages` VALUES('help_seed', 'ru', 'Скачали сами, дайте скачать другому. Если вы скачали релиз не по torrent, встаньте, пожалуйста, на раздачу');
INSERT INTO `languages` VALUES('help_seed', 'ua', 'Завантажили самі, дайте завантажити іншому. Якщо ви завантажили реліз не по torrent, встаньте, будь ласка, на роздачу');
INSERT INTO `languages` VALUES('Hide', 'en', 'Hide');
INSERT INTO `languages` VALUES('Hide', 'ru', 'Скрыт');
INSERT INTO `languages` VALUES('hide', 'ua', 'Приховано');
INSERT INTO `languages` VALUES('hide_filled', 'en', 'Hide completed');
INSERT INTO `languages` VALUES('hide_filled', 'ru', 'Спрятать выполненные');
INSERT INTO `languages` VALUES('hide_filled', 'ua', 'Сховати виконання');
INSERT INTO `languages` VALUES('hint', 'en', 'Hint: Avatar must be no larger than a %d kilobyte<br />&amp; dimensions not more than %d x %d pixels');
INSERT INTO `languages` VALUES('hint', 'ru', 'Подсказка: Аватара должна быть размером не больше %d килобайт<br />и pазмером не больше %d x %d пикселей');
INSERT INTO `languages` VALUES('hint', 'ua', 'Підказка: Аватара повинна бути розміром не більше %d кілобайт<br />і розмір не більше %d x %d кселів');
INSERT INTO `languages` VALUES('history_downloaded', 'en', 'Downloaded releases');
INSERT INTO `languages` VALUES('history_downloaded', 'ru', 'Скачанные релизы');
INSERT INTO `languages` VALUES('history_downloaded', 'ua', 'Звантажені релізи');
INSERT INTO `languages` VALUES('history_friends', 'en', 'Friends');
INSERT INTO `languages` VALUES('history_friends', 'ru', 'Друзья');
INSERT INTO `languages` VALUES('history_friends', 'ua', 'Друзі');
INSERT INTO `languages` VALUES('history_leeching', 'en', 'Leeching at present');
INSERT INTO `languages` VALUES('history_leeching', 'ru', 'Качает в данный момент');
INSERT INTO `languages` VALUES('history_leeching', 'ua', 'Качає в даний момент');
INSERT INTO `languages` VALUES('history_newscomments', 'en', 'Comments to the news');
INSERT INTO `languages` VALUES('history_newscomments', 'ru', 'Комментарии к новостям');
INSERT INTO `languages` VALUES('history_newscomments', 'ua', 'Коментарі до новин');
INSERT INTO `languages` VALUES('history_nicknames', 'en', 'Nickname changes');
INSERT INTO `languages` VALUES('history_nicknames', 'ru', 'История смены ников');
INSERT INTO `languages` VALUES('history_nicknames_of', 'en', 'History of nickname changes for %s');
INSERT INTO `languages` VALUES('history_nicknames_of', 'ru', 'История смены ников для %s');
INSERT INTO `languages` VALUES('history_pagecomments', 'en', 'Comments to the pages');
INSERT INTO `languages` VALUES('history_pagecomments', 'ru', 'Комментарии страницам');
INSERT INTO `languages` VALUES('history_pagecomments', 'ua', 'Коментарі до сторінок');
INSERT INTO `languages` VALUES('history_pages', 'en', 'Created page');
INSERT INTO `languages` VALUES('history_pages', 'ru', 'Созданные страницы');
INSERT INTO `languages` VALUES('history_pages', 'ua', 'Створені сторінки');
INSERT INTO `languages` VALUES('history_pollcomments', 'en', 'Comments to the polls');
INSERT INTO `languages` VALUES('history_pollcomments', 'ru', 'Комментарии к опросам');
INSERT INTO `languages` VALUES('history_pollcomments', 'ua', 'Коментарі до опитувань');
INSERT INTO `languages` VALUES('history_presents', 'en', 'Presents for user');
INSERT INTO `languages` VALUES('history_presents', 'ru', 'Подарки для пользователя');
INSERT INTO `languages` VALUES('history_presents', 'ua', 'Подарунки для користувача');
INSERT INTO `languages` VALUES('history_presents2', 'en', 'History of user presents');
INSERT INTO `languages` VALUES('history_presents2', 'ru', 'История подарков пользователя');
INSERT INTO `languages` VALUES('history_presents2', 'ua', 'Історія подарунків користувача');
INSERT INTO `languages` VALUES('history_relcomments', 'en', 'Comments to the releases');
INSERT INTO `languages` VALUES('history_relcomments', 'ru', 'Комментарии к релизам');
INSERT INTO `languages` VALUES('history_relcomments', 'ua', 'Коментарі до релізів');
INSERT INTO `languages` VALUES('history_reqcomments', 'en', 'Comments to the request');
INSERT INTO `languages` VALUES('history_reqcomments', 'ru', 'Комментарии к запросам');
INSERT INTO `languages` VALUES('history_reqcomments', 'ua', 'Коментарі до запитів');
INSERT INTO `languages` VALUES('history_rgcomments', 'en', 'Comments to the releases groups');
INSERT INTO `languages` VALUES('history_rgcomments', 'ru', 'Комментарии к релиз-группам');
INSERT INTO `languages` VALUES('history_rgcomments', 'ua', 'Коментарі до реліз-груп');
INSERT INTO `languages` VALUES('history_seeding', 'en', 'Seeding at present');
INSERT INTO `languages` VALUES('history_seeding', 'ru', 'Раздает в данный момент');
INSERT INTO `languages` VALUES('history_seeding', 'ua', 'Роздає в даний момент');
INSERT INTO `languages` VALUES('history_uploaded', 'en', 'Uploaded releases');
INSERT INTO `languages` VALUES('history_uploaded', 'ru', 'Загруженные релизы');
INSERT INTO `languages` VALUES('history_uploaded', 'ua', 'Завантажені релізи');
INSERT INTO `languages` VALUES('history_usercomments', 'en', 'Comments to the Users');
INSERT INTO `languages` VALUES('history_usercomments', 'ru', 'Комментарии к пользователям');
INSERT INTO `languages` VALUES('history_usercomments', 'ua', 'Коментарі до користувачів');
INSERT INTO `languages` VALUES('hits', 'en', 'Hits');
INSERT INTO `languages` VALUES('hits', 'ru', 'Взят');
INSERT INTO `languages` VALUES('hits', 'ua', 'Взято');
INSERT INTO `languages` VALUES('home', 'en', 'Home ''.$REL_CONFIG[''defaultbaseurl''].''');
INSERT INTO `languages` VALUES('home', 'ru', 'На главную ''.$REL_CONFIG[''defaultbaseurl''].''');
INSERT INTO `languages` VALUES('home', 'ua', 'На головну  ''.$REL_CONFIG[''defaultbaseurl''].''');
INSERT INTO `languages` VALUES('homepage', 'en', 'Main');
INSERT INTO `languages` VALUES('homepage', 'ru', 'Главная');
INSERT INTO `languages` VALUES('homepage', 'ua', 'Головна');
INSERT INTO `languages` VALUES('hours', 'en', 'Hour(s) (Gold, and donated to you your releases not included)');
INSERT INTO `languages` VALUES('hours', 'ru', 'час(а) (Золотые, Ваши и подаренные Вам релизы не учитываются)');
INSERT INTO `languages` VALUES('hours', 'ua', 'годину (а) (Золоті, Ваші і подаровані Вам релізи не враховуються)');
INSERT INTO `languages` VALUES('how_did_get_here', 'en', 'How did you get here? <a href="\\">Back</a>');
INSERT INTO `languages` VALUES('how_did_get_here', 'ru', 'Как вы сюда попали? <a href="\\">Назад</a>');
INSERT INTO `languages` VALUES('how_did_get_here', 'ua', 'Як ви сюди потрапили? <a href="\\">Назад</a>');
INSERT INTO `languages` VALUES('how_discount', 'en', 'Farmed');
INSERT INTO `languages` VALUES('how_discount', 'ru', 'Скидки');
INSERT INTO `languages` VALUES('how_discount', 'ua', 'Знижки');
INSERT INTO `languages` VALUES('how_many_present', 'en', 'How much to present&amp;');
INSERT INTO `languages` VALUES('how_many_present', 'ru', 'Сколько дарить?');
INSERT INTO `languages` VALUES('how_many_present', 'ua', 'Скільки дарувати?');
INSERT INTO `languages` VALUES('how_present_notice_discount', 'en', 'This quantity of a farmed will be subtracted at you and added to your friend.');
INSERT INTO `languages` VALUES('how_present_notice_discount', 'ru', 'Это количество скидки будет вычтено у вас и прибавлено вашему другу');
INSERT INTO `languages` VALUES('how_present_notice_discount', 'ua', 'Ця кількість знижки відніме у вас, і додадуть вашому другові');
INSERT INTO `languages` VALUES('how_present_notice_ratingsum', 'en', 'This amount will be deducted karma you have and adding the karma of your friend.');
INSERT INTO `languages` VALUES('how_present_notice_ratingsum', 'ru', 'Это количество репутации будет вычтенно у вас и прибавлено к репутации вашего друга');
INSERT INTO `languages` VALUES('how_present_notice_ratingsum', 'ua', 'Ця кількість репутації буде вичтенно у вас, і додадуть до репутації вашого друга');
INSERT INTO `languages` VALUES('how_present_notice_torrent', 'en', 'Rating for downloading this release will be deducted from you and your friend will be able to download this torrent, not taking into account your rating');
INSERT INTO `languages` VALUES('how_present_notice_torrent', 'ru', 'Рейтинг за скачивание этого релиза будет вычтен у вас и ваш друг сможет скачать этот торрент, не учитывая свой рейтинг');
INSERT INTO `languages` VALUES('how_present_notice_torrent', 'ua', 'Рейтинг за скачування цього релізу буде вирахувано у вас і ваш друг зможе завантажити цей торрент, не враховуючи свій рейтинг');
INSERT INTO `languages` VALUES('how_ratingsum', 'en', 'Karma');
INSERT INTO `languages` VALUES('how_ratingsum', 'ru', 'Кармы');
INSERT INTO `languages` VALUES('how_ratingsum', 'ua', 'Карми');
INSERT INTO `languages` VALUES('how_torrent', 'en', 'ID torrent, you can get by details.php?id=<span style="color: red;">ID Torrent</span>');
INSERT INTO `languages` VALUES('how_torrent', 'ru', 'ID торрента, можно узнать по details.php?id=<span style="color: red;">ID торрента</span>');
INSERT INTO `languages` VALUES('how_torrent', 'ua', 'ID торрента, можна дізнатися за details.php?id=<span style="color: red;">ID торрента</span>');
INSERT INTO `languages` VALUES('how_to_ratiopopup', 'en', 'How to increase rating? Just start seeding!');
INSERT INTO `languages` VALUES('how_to_ratiopopup', 'ru', 'Как увеличить рейтинг? Просто сидируйте!');
INSERT INTO `languages` VALUES('how_to_ratiopopup', 'ua', 'Як збільшити рейтинг? Сідуйте!');
INSERT INTO `languages` VALUES('idle', 'en', 'Inactivity');
INSERT INTO `languages` VALUES('idle', 'ru', 'Бездействие');
INSERT INTO `languages` VALUES('idle', 'ua', 'Бездіяльність');
INSERT INTO `languages` VALUES('image', 'en', 'Logo');
INSERT INTO `languages` VALUES('image', 'ru', 'Логотип');
INSERT INTO `languages` VALUES('image', 'ua', 'Логотип');
INSERT INTO `languages` VALUES('images', 'en', 'Images');
INSERT INTO `languages` VALUES('images', 'ru', 'Картинки');
INSERT INTO `languages` VALUES('images', 'ua', 'Зображення');
INSERT INTO `languages` VALUES('import a langfile', 'en', 'Import a langfile');
INSERT INTO `languages` VALUES('import a langfile', 'ru', 'Импортировать языковой файл');
INSERT INTO `languages` VALUES('import a langfile', 'ua', 'Імпортувати мовний файл');
INSERT INTO `languages` VALUES('in', 'en', 'in');
INSERT INTO `languages` VALUES('in', 'ru', 'в');
INSERT INTO `languages` VALUES('in', 'ua', 'в');
INSERT INTO `languages` VALUES('inbox', 'en', 'Received');
INSERT INTO `languages` VALUES('inbox', 'ru', 'Входящие');
INSERT INTO `languages` VALUES('inbox', 'ua', 'Вхідні');
INSERT INTO `languages` VALUES('inbox_m', 'en', 'Incoming messages');
INSERT INTO `languages` VALUES('inbox_m', 'ru', 'Входящие ЛС');
INSERT INTO `languages` VALUES('inbox_m', 'ua', 'Вхідні ПП');
INSERT INTO `languages` VALUES('include_remote', 'en', ', exclude %s %s on remote trackers');
INSERT INTO `languages` VALUES('include_remote', 'ru', ', исключая %s %s на удаленных трекерах');
INSERT INTO `languages` VALUES('include_remote', 'ua', ', виключаючи %s %s на віддалених трекерах');
INSERT INTO `languages` VALUES('including_dead', 'en', 'including dead');
INSERT INTO `languages` VALUES('including_dead', 'ru', 'включая мертвяки');
INSERT INTO `languages` VALUES('including_dead', 'ua', 'включаючи мертвяки');
INSERT INTO `languages` VALUES('incorrect', 'en', 'Username or password incorrect!');
INSERT INTO `languages` VALUES('incorrect', 'ru', 'Имя пользователя или пароль неверны!');
INSERT INTO `languages` VALUES('incorrect', 'ua', 'Ім''я користувача або пароль неправильні!');
INSERT INTO `languages` VALUES('information', 'en', 'Info');
INSERT INTO `languages` VALUES('information', 'ru', 'Информация');
INSERT INTO `languages` VALUES('information', 'ua', 'Інформація');
INSERT INTO `languages` VALUES('info_hash', 'en', 'Info hash');
INSERT INTO `languages` VALUES('info_hash', 'ru', 'Хэш релиза');
INSERT INTO `languages` VALUES('info_hash', 'ua', 'Хеш релізу');
INSERT INTO `languages` VALUES('init', 'en', '<div style="text-align: right;"><small>The initiator of friendship</small></div>');
INSERT INTO `languages` VALUES('init', 'ru', '<div style="text-align: right;"><small>Инициатор дружбы</small></div>');
INSERT INTO `languages` VALUES('init', 'ua', '<div style="text-align: right;"><small>Ініціатор дружби</small></div>');
INSERT INTO `languages` VALUES('inivite_code_created', 'en', 'Successfully created Invite code <strong>%s</strong> for release group "%s", Now you go to a page created invitations');
INSERT INTO `languages` VALUES('inivite_code_created', 'ru', 'Успешно создан инвайт-код <strong>%s</strong> для релиз-группы "%s", сейчас вы перейдете на страницу созданных приглашений');
INSERT INTO `languages` VALUES('inivite_code_created', 'ua', 'Успішно створено інвайт-код <strong>%s</strong> ля реліз-групи "%s", зараз ви перейдете на сторінку створених запрошень');
INSERT INTO `languages` VALUES('interests', 'en', 'Interests:');
INSERT INTO `languages` VALUES('interests', 'ru', 'Интересы:');
INSERT INTO `languages` VALUES('interests', 'ua', 'Інтереси:');
INSERT INTO `languages` VALUES('invaled_passed', 'en', 'invalid arguments passed');
INSERT INTO `languages` VALUES('invaled_passed', 'ru', 'invalid arguments passed');
INSERT INTO `languages` VALUES('invaled_passed', 'ua', 'invalid arguments passed');
INSERT INTO `languages` VALUES('invalid_filename', 'en', 'Invalid file name (perhaps this is not a picture or invalid image).');
INSERT INTO `languages` VALUES('invalid_filename', 'ru', 'Неверное имя файла (не картинка или неверный формат).');
INSERT INTO `languages` VALUES('invalid_filename', 'ua', 'Неправильне ім''я файлу (не картинка або невірний формат).');
INSERT INTO `languages` VALUES('invalid_format', 'en', 'Invalid e-mail or username format');
INSERT INTO `languages` VALUES('invalid_format', 'ru', 'Неверный формат никнейма или e-mail');
INSERT INTO `languages` VALUES('invalid_format', 'ua', 'Невірний формат нікнейму або e-mail');
INSERT INTO `languages` VALUES('invalid_id', 'en', 'Invalid ID');
INSERT INTO `languages` VALUES('invalid_id', 'ru', 'Неверный идентификатор.');
INSERT INTO `languages` VALUES('invalid_id', 'ua', 'Невірний ідентифікатор.');
INSERT INTO `languages` VALUES('invalid_idtype', 'en', 'The System has not found that, on what you subscribe');
INSERT INTO `languages` VALUES('invalid_idtype', 'ru', 'Система не нашла то, на что вы подписываетесь');
INSERT INTO `languages` VALUES('invalid_idtype', 'ua', 'Система не нашла то, на что вы подписываетесь');
INSERT INTO `languages` VALUES('invalid_invite_code', 'en', 'Invalid or incorrect Invite code. <a href="denied:javascript:history.go(-1);">Back</a>');
INSERT INTO `languages` VALUES('invalid_invite_code', 'ru', 'Недействительный или неверный инвайт-код. <a href="denied:javascript:history.go(-1);">Назад</a>');
INSERT INTO `languages` VALUES('invalid_invite_code', 'ua', 'Недійсний або невірний інвайт-код. <a href="denied:javascript:history.go(-1);">Назад</a>');
INSERT INTO `languages` VALUES('invalid_ip', 'en', 'Invalid IP address.');
INSERT INTO `languages` VALUES('invalid_ip', 'ru', 'Неверный IP адрес.');
INSERT INTO `languages` VALUES('invalid_ip', 'ua', 'Невірний IP адреса.');
INSERT INTO `languages` VALUES('invalid_login', 'en', 'Username or password is invalid');
INSERT INTO `languages` VALUES('invalid_login', 'ru', 'Неверное имя пользователя или пароль');
INSERT INTO `languages` VALUES('invalid_login', 'ua', 'Неправильне ім''я користувача або пароль');
INSERT INTO `languages` VALUES('invalid_result', 'en', ': invalid query result');
INSERT INTO `languages` VALUES('invalid_result', 'ru', ': неверный результат запроса');
INSERT INTO `languages` VALUES('invalid_result', 'ua', ': невірний результат запиту');
INSERT INTO `languages` VALUES('invalid_subnet', 'en', 'Invalid subnet mask.');
INSERT INTO `languages` VALUES('invalid_subnet', 'ru', 'Неверная маска подсети.');
INSERT INTO `languages` VALUES('invalid_subnet', 'ua', 'Невірна маска підмережі.');
INSERT INTO `languages` VALUES('invalid_tiger_hash', 'en', 'Invalid TreeTiger hash');
INSERT INTO `languages` VALUES('invalid_tiger_hash', 'ru', 'Неверный TreeTiger хеш');
INSERT INTO `languages` VALUES('invalid_tiger_hash', 'ua', 'Невірний TreeTiger хеш');
INSERT INTO `languages` VALUES('invalid_type', 'en', 'Invalid the type history');
INSERT INTO `languages` VALUES('invalid_type', 'ru', 'Неизвестный тип истории');
INSERT INTO `languages` VALUES('invalid_type', 'ua', 'Невідомий тип історії');
INSERT INTO `languages` VALUES('invalid_username', 'en', 'Incorrect name of the user or the password. Check up the entered information..');
INSERT INTO `languages` VALUES('invalid_username', 'ru', 'Неверное имя пользователя. Проверьте введеные данные.');
INSERT INTO `languages` VALUES('invalid_username', 'ua', 'Неправильне ім''я користувача. Перевірте введені дані.');
INSERT INTO `languages` VALUES('invalid_username_pass', 'en', 'You have not registered on this site yet, or this combination of e-mail and password is invalid. You can <a href="%s">Register now</a> or <a href="javascript:history.go(-1);">Try again</a>.');
INSERT INTO `languages` VALUES('invalid_username_pass', 'ru', 'Вы еще не зарегистрировались на сайте, либо эта комбинация логина и пароля неверная. Вы можете <a href="%s">Зарегистрироваться сейчас</a> или <a href="javascript:history.go(-1);">Попробовать войти еще раз</a>.');
INSERT INTO `languages` VALUES('invalid_username_pass', 'ua', 'Ви ще не зареєструвалися на сайті, або ця комбінація логіна і пароля невірна. Ви можете <a href="%s">Зареєструватися зараз</a> або <a href="denied:javascript:history.go(-1);">Спробувати увійти ще раз</a>.');
INSERT INTO `languages` VALUES('invite', 'en', 'Invite');
INSERT INTO `languages` VALUES('invite', 'ru', 'Пригласить');
INSERT INTO `languages` VALUES('invite', 'ua', 'Запросити');
INSERT INTO `languages` VALUES('Invite code', 'en', 'Invite code');
INSERT INTO `languages` VALUES('Invite code', 'ru', 'Код приглашения');
INSERT INTO `languages` VALUES('invite code', 'ua', 'Код запрошення');
INSERT INTO `languages` VALUES('invites', 'en', 'Invites');
INSERT INTO `languages` VALUES('invites', 'ru', 'Приглашений');
INSERT INTO `languages` VALUES('invites', 'ua', 'Запрошень');
INSERT INTO `languages` VALUES('invite_added', 'en', 'Created');
INSERT INTO `languages` VALUES('invite_added', 'ru', 'Дата создания');
INSERT INTO `languages` VALUES('invite_added', 'ua', 'Дата створення');
INSERT INTO `languages` VALUES('invite_code', 'en', 'Invitation Code');
INSERT INTO `languages` VALUES('invite_code', 'ru', 'Код приглашения');
INSERT INTO `languages` VALUES('invite_code', 'ua', 'Код запрошення');
INSERT INTO `languages` VALUES('invite_code_notice', 'en', 'If you have an invite code, past it into field below');
INSERT INTO `languages` VALUES('invite_code_notice', 'ru', 'Если у вас есть код приглашения, вставьте его в поле ниже');
INSERT INTO `languages` VALUES('invite_code_notice', 'ua', 'Якщо у вас є код запрошення, вставте її у полі нижче');
INSERT INTO `languages` VALUES('invite_confirmed', 'en', 'Who invited you just podtverlil your participation in the community. You added for %s units rating. Also, you automatically become his(her) friend. View a list of friends can be <a href="friends.php">Here</a>');
INSERT INTO `languages` VALUES('invite_confirmed', 'ru', 'Пригласивший вас только что подтверлил ваше участие в сообществе. Вам прибавлено %s единиц рейтинга. Также Вы автоматически стали его(ее) другом. Посмотреть список друзей можно <a href="friends.php">Тут</a>');
INSERT INTO `languages` VALUES('invite_confirmed', 'ua', 'Запросивший вас щойно подтвердив вашу участь в спільноті. Вам додано %s одиниць рейтингу. Також Ви автоматично стали його (її) одним. Переглянути список друзів можна <a href="friends.php">Тут</a>');
INSERT INTO `languages` VALUES('invite_confirmed_title', 'en', 'Your invite confirmed');
INSERT INTO `languages` VALUES('invite_confirmed_title', 'ru', 'Ваше участие подтверждено');
INSERT INTO `languages` VALUES('invite_confirmed_title', 'ua', 'Ваша участь підтверджено');
INSERT INTO `languages` VALUES('invite_friends', 'en', 'Invite your friends');
INSERT INTO `languages` VALUES('invite_friends', 'ru', 'Пригласите ваших друзей');
INSERT INTO `languages` VALUES('invite_friends', 'ua', 'Запросіть ваших друзів');
INSERT INTO `languages` VALUES('invite_link', 'en', 'Link friends');
INSERT INTO `languages` VALUES('invite_link', 'ru', 'Ссылка для друзей');
INSERT INTO `languages` VALUES('invite_link', 'ua', 'Посилання для друзів');
INSERT INTO `languages` VALUES('invite_notice', 'en', 'Welcome! We wish you happy to spend time on our site. <br /> Attention, you have registered on the invitation to %s. As an incentive, you get a + rating is already at the start, but only after you are invited to confirm.<br /><br /><i>Good luck!</i>');
INSERT INTO `languages` VALUES('invite_notice', 'ru', 'Добро пожаловать! Желаем вам с удовольствием провести время на нашем сайте.<br /> Внимание, вы зарегистрировались по приглашению от %s. В качестве поощрения вы получите + к рейтингу уже на старте, но только после того, как пригласивший подтвердит вас.<br /><br /><i>Удачи!</i>');
INSERT INTO `languages` VALUES('invite_notice', 'ua', 'Ласкаво просимо! Бажаємо вам з задоволенням провести час на нашому сайті.<br /> Увага, ви зареєструвалися на запрошення від %s. В якості заохочення ви отримаєте + до рейтингу вже на старті, але тільки після того, як запросивший підтвердить вас.<br /><br /><i>Удачі!</i>');
INSERT INTO `languages` VALUES('invite_notice_fr', 'en', 'Divide the pleasure of using our tracker with your friends');
INSERT INTO `languages` VALUES('invite_notice_fr', 'ru', 'Разделите удовольствие от использования TorrentsBook.com с вашими друзьями');
INSERT INTO `languages` VALUES('invite_notice_fr', 'ua', 'Розділіть задоволення від використання UA-torrents.net з вашими друзями');
INSERT INTO `languages` VALUES('invite_notice_get', 'en', 'Get the opportunity to invite someone <a href="myrating.php">exchange your bonus</a>');
INSERT INTO `languages` VALUES('invite_notice_get', 'ru', 'Получить возможность пригласить кого-нибудь можно <a href="myrating.php">обменяв скидку</a>');
INSERT INTO `languages` VALUES('invite_notice_get', 'ua', 'Отримати можливість запросити кого-небудь можна <a href="myrating.php">обмінявши знижку</a>');
INSERT INTO `languages` VALUES('invite_notice_reg', 'en', 'At your invitation just registered user %s, confirm your invitation in <a href="invite.php">The system of invitations</a>');
INSERT INTO `languages` VALUES('invite_notice_reg', 'ru', 'По вашему приглашению только что зарегистрировался пользователь %s, подтвердите ваше приглашение в <a href="invite.php">системе инвайтов</a>');
INSERT INTO `languages` VALUES('invite_notice_reg', 'ua', 'На вашу запрошення тільки що зареєструвався користувач %s, підтвердіть ваше запрошення в <a href="invite.php">системі інвайтів</a>');
INSERT INTO `languages` VALUES('invite_notice_rg', 'en', 'You are going to create an invitation for the release of the band "%s". Invitations for release groups do not require confirmation and act as much, and subscribe to release a group of (%s). For the creation of an invitation you will be taken away %s <a href="myrating.php?discount">farmed</a> (сейчас у вас %s). If you want to join this group, click "Continue');
INSERT INTO `languages` VALUES('invite_notice_rg', 'ru', 'Вы собираетесь создать приглашение для релиз группы "%s". Приглашения для релиз-групп не требуют подтверждения и действуют столько же, сколько и подписка на релиз-группу (%s). За создание приглашения у вас отнимется %s <a href="myrating.php?discount">скидки</a> (сейчас у вас %s). Если вы хотите вступить в эту группу, нажмите "Продолжить"');
INSERT INTO `languages` VALUES('invite_notice_rg', 'ua', 'Ви збираєтеся створити запрошення для реліз групи "%s". Запрошення для реліз-груп не вимагають підтвердження і діють стільки ж, скільки і підписка на реліз-групу (%s). За створення запрошення у вас заберуть %s <a href="myrating.php?discount">знижки</a> (зараз у вас %s). Якщо ви хочете вступити в цю групу, натисніть "Продовжити"');
INSERT INTO `languages` VALUES('invite_per', 'en', 'Valid until');
INSERT INTO `languages` VALUES('invite_per', 'ru', 'Действует до');
INSERT INTO `languages` VALUES('invite_per', 'ua', 'Діє до');
INSERT INTO `languages` VALUES('invite_system', 'en', 'The system of invitations');
INSERT INTO `languages` VALUES('invite_system', 'ru', 'Система приглашений');
INSERT INTO `languages` VALUES('invite_system', 'ua', 'Система запрошень');
INSERT INTO `languages` VALUES('in_debug', 'en', 'Warning Enable debugging. Only the owner can see this message, and requests above.');
INSERT INTO `languages` VALUES('in_debug', 'ru', 'Внимание! Включен режим отладки. Только владелец может видеть это сообщение и запросы выше');
INSERT INTO `languages` VALUES('in_debug', 'ua', 'Увага! Включено режим відладки. Тільки власник може бачити це повідомлення і запити вище');
INSERT INTO `languages` VALUES('in_network', 'en', 'In a network');
INSERT INTO `languages` VALUES('in_network', 'ru', 'В сети');
INSERT INTO `languages` VALUES('in_network', 'ua', 'У мережі');
INSERT INTO `languages` VALUES('in_time', 'en', ', expires');
INSERT INTO `languages` VALUES('in_time', 'ru', ', истекает через');
INSERT INTO `languages` VALUES('in_time', 'ua', ', спливає через');
INSERT INTO `languages` VALUES('IP/subnet bans', 'en', 'IP/subnet bans');
INSERT INTO `languages` VALUES('IP/subnet bans', 'ru', 'Баны IP/подсетей');
INSERT INTO `languages` VALUES('ip/subnet bans', 'ua', 'Бани IP / підмереж');
INSERT INTO `languages` VALUES('ipcheck', 'en', 'Search for double IP');
INSERT INTO `languages` VALUES('ipcheck', 'ru', 'Повторные IP');
INSERT INTO `languages` VALUES('ipcheck', 'ua', 'Повторні IP');
INSERT INTO `languages` VALUES('ip_address', 'en', 'IP address');
INSERT INTO `languages` VALUES('ip_address', 'ru', 'IP адрес');
INSERT INTO `languages` VALUES('ip_address', 'ua', 'IP адреса');
INSERT INTO `languages` VALUES('ip_sender', 'en', '<b>IP sender</b>:');
INSERT INTO `languages` VALUES('ip_sender', 'ru', '<b>IP отправителя</b>:');
INSERT INTO `languages` VALUES('ip_sender', 'ua', '<b>IP відправника</b>:');
INSERT INTO `languages` VALUES('is', 'en', 'is');
INSERT INTO `languages` VALUES('is', 'ru', 'в');
INSERT INTO `languages` VALUES('is', 'ua', 'в');
INSERT INTO `languages` VALUES('is_on_the_port', 'en', 'is on the Port:');
INSERT INTO `languages` VALUES('is_on_the_port', 'ru', 'is on the Port:');
INSERT INTO `languages` VALUES('is_on_the_port', 'ua', 'is on the Port:');
INSERT INTO `languages` VALUES('ivalid_sort', 'en', 'Invalid sort option');
INSERT INTO `languages` VALUES('ivalid_sort', 'ru', 'Неверный параметр сортировки');
INSERT INTO `languages` VALUES('ivalid_sort', 'ua', 'Невірний параметр сортування');
INSERT INTO `languages` VALUES('i_can_be_notified_due_my_class', 'en', 'My class of %s, I want to be notified about:');
INSERT INTO `languages` VALUES('i_can_be_notified_due_my_class', 'ru', 'Мой класс %s, я хочу получать уведомления о:');
INSERT INTO `languages` VALUES('i_can_be_notified_due_my_class', 'ua', 'Мій клас %s, я хочу отримувати повідомлення про:');
INSERT INTO `languages` VALUES('i_change', 'en', 'You have %s of his rating (max: %s), and now your rating is %s (Tax farming can not be greater than the number of downloaded releases)');
INSERT INTO `languages` VALUES('i_change', 'ru', 'Вы меняте %s своего рейтинга (max: %s), а сейчас Ваш рейтинг равен %s (Скидка не может быть больше количества скачанных релизов)');
INSERT INTO `languages` VALUES('i_change', 'ua', 'Ви міняєте %s свого рейтингу (max: %s), а зараз Ваш рейтинг дорівнює %s (Знижка не може бути більше кількості викачаних релізів)');
INSERT INTO `languages` VALUES('join_by_invite', 'en', 'You are about to subscribe to the release of the group "%s" (the duration of the subscription  %s), using this invitation code: <strong>%s</strong>. Click "Continue" to sign up for releases group "%s".');
INSERT INTO `languages` VALUES('join_by_invite', 'ru', 'Вы собираетесь подписаться на релиз группу "%s" (продолжительность подписки %s), используя этот код приглашения: <strong>%s</strong>. Нажмите "Продолжить", чтобы подписаться на релизы группы "%s".');
INSERT INTO `languages` VALUES('join_by_invite', 'ua', 'Ви збираєтеся підписатися на реліз групу "%s" (тривалість підписки %s), використовуючи цей код запрошення: <strong>%s</strong>. Натисніть "Продовжити", щоб підписатися на релізи групи "%s".');
INSERT INTO `languages` VALUES('join_notice', 'en', 'You are about to subscribe to release private group "%s", a subscription to this release of the band are %s, to join this group you need to spend %s <a href="myrating.php?discount">farmed</a> (you have %s). If you want to join this group, click "Continue"');
INSERT INTO `languages` VALUES('join_notice', 'ru', 'Вы собираетесь подписаться на релизы приватной группы "%s", подписка для этой релиз группы действует %s, для вступления в эту группу вам необходимо потратить %s <a href="myrating.php?discount">скидки</a> (у вас %s). Если вы хотите вступить в эту группу, нажмите "Продолжить"');
INSERT INTO `languages` VALUES('join_notice', 'ua', 'Ви збираєтеся підписатися на релізи приватної групи "%s", підписка для цієї реліз групи діє %s, для вступу в цю групу вам необхідно витратити %s <a href="myrating.php?discount">знижки</a> (у вас %s). Якщо ви хочете вступити в цю групу, натисніть "Продовжити"');
INSERT INTO `languages` VALUES('Jump to', 'en', 'Jump to');
INSERT INTO `languages` VALUES('Jump to', 'ru', 'Быстрый переход к');
INSERT INTO `languages` VALUES('jump to', 'ua', 'Швидкий перехід до');
INSERT INTO `languages` VALUES('Key', 'en', 'Key');
INSERT INTO `languages` VALUES('Key', 'ru', 'Ключ');
INSERT INTO `languages` VALUES('key', 'ua', 'Ключ');
INSERT INTO `languages` VALUES('of June', 'ua', 'Июня');
INSERT INTO `languages` VALUES('of July', 'en', 'of July');
INSERT INTO `languages` VALUES('of July', 'ru', 'Июля');
INSERT INTO `languages` VALUES('of July', 'ua', 'Июля');
INSERT INTO `languages` VALUES('of August', 'en', 'of August');
INSERT INTO `languages` VALUES('langadmin_trans_for', 'en', 'Translation for %s');
INSERT INTO `languages` VALUES('langadmin_trans_for', 'ru', 'Перевод для %s');
INSERT INTO `languages` VALUES('langadmin_trans_for', 'ua', 'Перевод для %s');
INSERT INTO `languages` VALUES('of February', 'en', 'of February');
INSERT INTO `languages` VALUES('of February', 'ru', 'Февраля');
INSERT INTO `languages` VALUES('of February', 'ua', 'Февраля');
INSERT INTO `languages` VALUES('of March', 'en', 'of March');
INSERT INTO `languages` VALUES('of March', 'ru', 'Марта');
INSERT INTO `languages` VALUES('of March', 'ua', 'Марта');
INSERT INTO `languages` VALUES('of April', 'en', 'of April');
INSERT INTO `languages` VALUES('of April', 'ru', 'Апреля');
INSERT INTO `languages` VALUES('langfile_link', 'en', 'Language file as it is');
INSERT INTO `languages` VALUES('langfile_link', 'ru', 'Сам языковой файл');
INSERT INTO `languages` VALUES('langfile_link', 'ua', 'Сам мовний файл');
INSERT INTO `languages` VALUES('language administration tools', 'en', 'Language administration tools');
INSERT INTO `languages` VALUES('language administration tools', 'ru', 'Средства администрирования языка');
INSERT INTO `languages` VALUES('language administration tools', 'ua', 'Засоби адміністрування мови');
INSERT INTO `languages` VALUES('language debug', 'en', 'Language debug');
INSERT INTO `languages` VALUES('language debug', 'ru', 'Дебаг языка');
INSERT INTO `languages` VALUES('language debug', 'ua', 'Дебаг мови');
INSERT INTO `languages` VALUES('language editor', 'en', 'Language editor');
INSERT INTO `languages` VALUES('language editor', 'ru', 'Редактор языка');
INSERT INTO `languages` VALUES('language editor', 'ua', 'Редактор мови');
INSERT INTO `languages` VALUES('language tools', 'en', 'Language tools');
INSERT INTO `languages` VALUES('language tools', 'ru', 'Управление языком');
INSERT INTO `languages` VALUES('language tools', 'ua', 'Управління мовою');
INSERT INTO `languages` VALUES('language_charset', 'en', 'windows-1251');
INSERT INTO `languages` VALUES('language_charset', 'ru', 'windows-1251');
INSERT INTO `languages` VALUES('language_charset', 'ua', 'windows-1251');
INSERT INTO `languages` VALUES('of April', 'ua', 'Апреля');
INSERT INTO `languages` VALUES('of May', 'en', 'of May');
INSERT INTO `languages` VALUES('of May', 'ru', 'Мая');
INSERT INTO `languages` VALUES('of May', 'ua', 'Мая');
INSERT INTO `languages` VALUES('of June', 'en', 'of June');
INSERT INTO `languages` VALUES('of June', 'ru', 'Июня');
INSERT INTO `languages` VALUES('large_tags', 'en', 'Large tags');
INSERT INTO `languages` VALUES('large_tags', 'ru', 'Большие теги');
INSERT INTO `languages` VALUES('large_tags', 'ua', 'Великі теги');
INSERT INTO `languages` VALUES('Last', 'en', 'Last');
INSERT INTO `languages` VALUES('Last', 'ru', 'Последняя');
INSERT INTO `languages` VALUES('Last edited by', 'en', 'Last edited by');
INSERT INTO `languages` VALUES('Last edited by', 'ru', 'Последние изменение от');
INSERT INTO `languages` VALUES('last edited by', 'ua', 'Останні зміни від');
INSERT INTO `languages` VALUES('Last post', 'en', 'Last post');
INSERT INTO `languages` VALUES('Last post', 'ru', 'Последний ответ');
INSERT INTO `languages` VALUES('last post', 'ua', 'Остання відповідь');
INSERT INTO `languages` VALUES('Last post by', 'en', 'Last post by');
INSERT INTO `languages` VALUES('Last post by', 'ru', 'Последний ответ от');
INSERT INTO `languages` VALUES('last post by', 'ua', 'Остання відповідь від');
INSERT INTO `languages` VALUES('Last topic', 'en', 'Last topic');
INSERT INTO `languages` VALUES('Last topic', 'ru', 'Последняя тема');
INSERT INTO `languages` VALUES('last topic', 'ua', 'Остання тема');
INSERT INTO `languages` VALUES('last_5_pr', 'en', 'Last 5 user presents');
INSERT INTO `languages` VALUES('last_5_pr', 'ru', 'Последние 5 подарков');
INSERT INTO `languages` VALUES('last_5_pr', 'ua', 'Останні 5 подарунків');
INSERT INTO `languages` VALUES('last_access', 'en', 'Last access');
INSERT INTO `languages` VALUES('last_access', 'ru', 'Последний доступ');
INSERT INTO `languages` VALUES('last_access', 'ua', 'Останній доступ');
INSERT INTO `languages` VALUES('last_cleanup', 'en', 'The last cleaning (synchronization) of the database produced in');
INSERT INTO `languages` VALUES('last_cleanup', 'ru', 'Последняя очистка (синхронизация) базы данных произведена в');
INSERT INTO `languages` VALUES('last_cleanup', 'ua', 'Останнє очищення (синхронізація) бази даних проведена в');
INSERT INTO `languages` VALUES('last_post', 'en', 'Last Post');
INSERT INTO `languages` VALUES('last_post', 'ru', 'Посл. сообщение');
INSERT INTO `languages` VALUES('last_post', 'ua', 'Ост. повідомлення');
INSERT INTO `languages` VALUES('last_registered_user', 'en', 'Last registered user');
INSERT INTO `languages` VALUES('last_registered_user', 'ru', 'Последний зарегистрированный пользователь');
INSERT INTO `languages` VALUES('last_registered_user', 'ua', 'Останній зареєстрований користувач');
INSERT INTO `languages` VALUES('last_remotecheck', 'en', 'Last check remote trackers produced in');
INSERT INTO `languages` VALUES('last_remotecheck', 'ru', 'Последняя проверка удаленных трекеров произведена в');
INSERT INTO `languages` VALUES('last_remotecheck', 'ua', 'Остання перевірка віддалених трекерів вироблена в');
INSERT INTO `languages` VALUES('last_seen', 'en', 'Last seen');
INSERT INTO `languages` VALUES('last_seen', 'ru', 'Последний раз был в сети');
INSERT INTO `languages` VALUES('last_seen', 'ua', 'Останній раз був в мережі');
INSERT INTO `languages` VALUES('last_user', 'en', 'Last user');
INSERT INTO `languages` VALUES('last_user', 'ru', 'Последний');
INSERT INTO `languages` VALUES('last_user', 'ua', 'Останній');
INSERT INTO `languages` VALUES('Later than yesterday', 'en', 'Later than yesterday');
INSERT INTO `languages` VALUES('Later than yesterday', 'ru', 'Позже, чем вчера');
INSERT INTO `languages` VALUES('later than yesterday', 'ua', 'Пізніше, ніж вчора');
INSERT INTO `languages` VALUES('leechers', 'en', 'Leech');
INSERT INTO `languages` VALUES('leechers', 'ru', 'Личей');
INSERT INTO `languages` VALUES('leechers', 'ua', 'Лічерів');
INSERT INTO `languages` VALUES('leechers_l', 'en', 'leechers');
INSERT INTO `languages` VALUES('leechers_l', 'ru', 'качающих');
INSERT INTO `languages` VALUES('leechers_l', 'ua', 'качають');
INSERT INTO `languages` VALUES('leeching', 'en', 'Leeching');
INSERT INTO `languages` VALUES('leeching', 'ru', 'Качает');
INSERT INTO `languages` VALUES('leeching', 'ua', 'Качає');
INSERT INTO `languages` VALUES('Left', 'en', 'Left');
INSERT INTO `languages` VALUES('Left', 'ru', 'Лево');
INSERT INTO `languages` VALUES('left', 'ua', 'Ліво');
INSERT INTO `languages` VALUES('lifetime', 'en', 'Forever');
INSERT INTO `languages` VALUES('lifetime', 'ru', 'Пожизненная');
INSERT INTO `languages` VALUES('lifetime', 'ua', 'Довічна');
INSERT INTO `languages` VALUES('links_prohibited', 'en', 'Links in the message is prohibited!');
INSERT INTO `languages` VALUES('links_prohibited', 'ru', 'Ссылки в сообщении запрещены!');
INSERT INTO `languages` VALUES('links_prohibited', 'ua', 'Посилання в повідомленні заборонені!');
INSERT INTO `languages` VALUES('loading', 'en', 'Loading');
INSERT INTO `languages` VALUES('loading', 'ru', 'Загрузка');
INSERT INTO `languages` VALUES('loading', 'ua', 'Навантаження');
INSERT INTO `languages` VALUES('log', 'en', 'Log');
INSERT INTO `languages` VALUES('log', 'ru', 'Журнал');
INSERT INTO `languages` VALUES('log', 'ua', 'Журнал');
INSERT INTO `languages` VALUES('logged', 'en', 'You are already logged on');
INSERT INTO `languages` VALUES('logged', 'ru', 'Вы уже вошли на');
INSERT INTO `languages` VALUES('logged', 'ua', 'Ви вже увійшли на');
INSERT INTO `languages` VALUES('loggedinorreturn', 'en', 'Sorry, but the page you required can only be accessed by <b>logged in users</b>.<br />Please log in to the system, and we will reditect you to this page after this.');
INSERT INTO `languages` VALUES('loggedinorreturn', 'ru', 'Извините, но страница, которую вы попытались загрузить доступна  <b>только зарегистрированным пользователям</b>.<br />Пожалуйста, войдите в систему, и мы направим вас на эту страницу сразу же после входа.');
INSERT INTO `languages` VALUES('loggedinorreturn', 'ua', 'Вибачте, але сторінка, яку ви спробували завантажити доступна  <b>тільки зареєстрованим користувачам</b>.<br />Будь ласка, увійдіть в систему, і ми направимо вас на цю сторінку відразу ж після входу.');
INSERT INTO `languages` VALUES('logged_on', 'en', 'Unfortunately the page you are trying to view is available only <b>to registered</b>.<br />After successful registered you will be redirected to the requested page.');
INSERT INTO `languages` VALUES('logged_on', 'ru', 'К сожалению страница, которую вы пытаетесь посмотреть <b>доступна только вошедшим в систему</b>.<br />После успешного входа вы будете переадресованы на запрошенную страницу.');
INSERT INTO `languages` VALUES('logged_on', 'ua', 'На жаль сторінка, яку ви намагаєтеся подивитися <b>доступна тільки що ввійшли в систему</b>.<br />Після успішного входу ви будете переадресовані на запитану сторінку.');
INSERT INTO `languages` VALUES('login', 'en', 'Login');
INSERT INTO `languages` VALUES('login', 'ru', 'Вход');
INSERT INTO `languages` VALUES('login', 'ua', 'Вхід');
INSERT INTO `languages` VALUES('login_error', 'en', 'Login error');
INSERT INTO `languages` VALUES('login_error', 'ru', 'Ошибка входа');
INSERT INTO `languages` VALUES('login_error', 'ua', 'Помилка входу');
INSERT INTO `languages` VALUES('login_ok', 'en', 'Successful login');
INSERT INTO `languages` VALUES('login_ok', 'ru', 'Удачный вход');
INSERT INTO `languages` VALUES('login_ok', 'ua', 'Вдалий вхід');
INSERT INTO `languages` VALUES('logout', 'en', 'Logout');
INSERT INTO `languages` VALUES('logout', 'ru', 'Выход');
INSERT INTO `languages` VALUES('logout', 'ua', 'Вихід');
INSERT INTO `languages` VALUES('logs', 'en', 'Logs');
INSERT INTO `languages` VALUES('logs', 'ru', 'Логи');
INSERT INTO `languages` VALUES('logs', 'ua', 'Логи');
INSERT INTO `languages` VALUES('log_file_empty', 'en', 'The log file is empty');
INSERT INTO `languages` VALUES('log_file_empty', 'ru', 'Лог файл пустой');
INSERT INTO `languages` VALUES('log_file_empty', 'ua', 'Лог файл порожній');
INSERT INTO `languages` VALUES('lower_class', 'en', 'Your current status is low. Click here to go back.');
INSERT INTO `languages` VALUES('lower_class', 'ru', 'Вы работаете под более низким классом. Нажмите сюда для возврата.');
INSERT INTO `languages` VALUES('lower_class', 'ua', 'Ви працюєте під більш низьким класом. Натисніть сюди для повернення.');
INSERT INTO `languages` VALUES('magnet', 'en', 'Click on this link to download');
INSERT INTO `languages` VALUES('magnet', 'ru', 'Кликните на эту ссылку для скачивания релиза');
INSERT INTO `languages` VALUES('magnet', 'ua', 'Натисніть на це посилання для завантаження релізу');
INSERT INTO `languages` VALUES('mailer_seccessful', 'en', 'Mailer successfully. Sent');
INSERT INTO `languages` VALUES('mailer_seccessful', 'ru', 'Рассылка успешно завершена. Отправлено');
INSERT INTO `languages` VALUES('mailer_seccessful', 'ua', 'Розсилка успішно завершена. Відправлено');
INSERT INTO `languages` VALUES('mail_read', 'en', 'Read PMs');
INSERT INTO `languages` VALUES('mail_read', 'ru', 'Прочитанное');
INSERT INTO `languages` VALUES('mail_read', 'ua', 'Прочитане');
INSERT INTO `languages` VALUES('mail_read_desc', 'en', 'Read PMs');
INSERT INTO `languages` VALUES('mail_read_desc', 'ru', 'Прочитанные сообщения');
INSERT INTO `languages` VALUES('mail_read_desc', 'ua', 'Прочитані повідомлення');
INSERT INTO `languages` VALUES('mail_unread_desc', 'en', 'Unread PMs');
INSERT INTO `languages` VALUES('mail_unread_desc', 'ru', 'Непрочитанные сообщения');
INSERT INTO `languages` VALUES('mail_unread_desc', 'ua', 'Непрочитані повідомлення');
INSERT INTO `languages` VALUES('main_category', 'en', 'Main category');
INSERT INTO `languages` VALUES('main_category', 'ru', 'Главная категория');
INSERT INTO `languages` VALUES('main_category', 'ua', 'Головна категорія');
INSERT INTO `languages` VALUES('main_menu', 'en', 'Main menu');
INSERT INTO `languages` VALUES('main_menu', 'ru', 'Главное Меню');
INSERT INTO `languages` VALUES('main_menu', 'ua', 'Головне Меню');
INSERT INTO `languages` VALUES('make_anonymous', 'en', 'Make anonymous releases');
INSERT INTO `languages` VALUES('make_anonymous', 'ru', 'Анонимизация релиза');
INSERT INTO `languages` VALUES('make_anonymous', 'ua', 'Анонімізація релізу');
INSERT INTO `languages` VALUES('make_request', 'en', 'Make request');
INSERT INTO `languages` VALUES('make_request', 'ru', 'Сделать запрос');
INSERT INTO `languages` VALUES('make_request', 'ua', 'Зробити запит');
INSERT INTO `languages` VALUES('mark', 'en', 'Mark');
INSERT INTO `languages` VALUES('mark', 'ru', 'Выделить');
INSERT INTO `languages` VALUES('mark', 'ua', 'Виділити');
INSERT INTO `languages` VALUES('mark_all', 'en', 'Mark all');
INSERT INTO `languages` VALUES('mark_all', 'ru', 'Выделить все');
INSERT INTO `languages` VALUES('mark_all', 'ua', 'Виділити все');
INSERT INTO `languages` VALUES('mark_as_read', 'en', 'Mark as read');
INSERT INTO `languages` VALUES('mark_as_read', 'ru', 'Отметить выделенные сообщения как прочитанные');
INSERT INTO `languages` VALUES('mark_as_read', 'ua', 'Відзначити вибрані повідомлення як прочитані');
INSERT INTO `languages` VALUES('mark_read', 'en', 'Read');
INSERT INTO `languages` VALUES('mark_read', 'ru', 'Прочитать');
INSERT INTO `languages` VALUES('mark_read', 'ua', 'Читати');
INSERT INTO `languages` VALUES('mass_email', 'en', 'Mass e-mail');
INSERT INTO `languages` VALUES('mass_email', 'ru', 'Массовый E-mail');
INSERT INTO `languages` VALUES('mass_email', 'ua', 'Масовий E-mail');
INSERT INTO `languages` VALUES('mass_mailing', 'en', 'Mass mailing from the user');
INSERT INTO `languages` VALUES('mass_mailing', 'ru', 'Массовое сообщение от пользователя');
INSERT INTO `languages` VALUES('mass_mailing', 'ua', 'Масове повідомлення від користувача');
INSERT INTO `languages` VALUES('max_avatar_size', 'en', 'The dimensions of avatar has to be no more than %dx%d pixels.');
INSERT INTO `languages` VALUES('max_avatar_size', 'ru', 'Размеры аватары должны быть не более %dx%d пикселей.');
INSERT INTO `languages` VALUES('max_avatar_size', 'ua', 'Розміри аватари повинні бути не більше %dx%d пікселів.');
INSERT INTO `languages` VALUES('max_file_size', 'en', 'Max file size');
INSERT INTO `languages` VALUES('max_file_size', 'ru', 'Максимальный размер файла (динамические ссылки запрещены)');
INSERT INTO `languages` VALUES('max_file_size', 'ua', 'Максимальний розмір файлу (динамічні посилання заборонені)');
INSERT INTO `languages` VALUES('members', 'en', 'Member');
INSERT INTO `languages` VALUES('members', 'ru', 'Члены');
INSERT INTO `languages` VALUES('members', 'ua', 'Члени');
INSERT INTO `languages` VALUES('memcached', 'en', 'memcached');
INSERT INTO `languages` VALUES('memcached', 'ru', 'memcached');
INSERT INTO `languages` VALUES('menu', 'en', 'Menu');
INSERT INTO `languages` VALUES('menu', 'ru', 'Меню');
INSERT INTO `languages` VALUES('menu', 'ua', 'Меню');
INSERT INTO `languages` VALUES('menu_header', 'en', '<a href="reltemplatesadmin.php">List of all templates</a> / <a href="reltemplatesadmin.php?action=add">Add new</a>');
INSERT INTO `languages` VALUES('menu_header', 'ru', '<a href="reltemplatesadmin.php">Список шаблонов</a> / <a href="reltemplatesadmin.php?action=add">Добавить новый</a>');
INSERT INTO `languages` VALUES('menu_header', 'ua', '<a href="reltemplatesadmin.php">Список шаблонів</a> / <a href="reltemplatesadmin.php?action=add">Додати новий</a>');
INSERT INTO `languages` VALUES('message', 'en', 'Message');
INSERT INTO `languages` VALUES('message', 'ru', 'Сообщение');
INSERT INTO `languages` VALUES('message', 'ua', 'Сообщение');
INSERT INTO `languages` VALUES('messages', 'en', 'PM');
INSERT INTO `languages` VALUES('messages', 'ru', 'ЛС');
INSERT INTO `languages` VALUES('messages', 'ua', 'ПП');
INSERT INTO `languages` VALUES('message_from', 'en', '<b>Message from</b>:');
INSERT INTO `languages` VALUES('message_from', 'ru', '<b>Сообщение от</b>:');
INSERT INTO `languages` VALUES('message_from', 'ua', '<b>Повідомлення від</b>:');
INSERT INTO `languages` VALUES('message_sent', 'en', 'Your message has been sent to the administration.');
INSERT INTO `languages` VALUES('message_sent', 'ru', 'Ваше сообщение было отправлено администрации.');
INSERT INTO `languages` VALUES('message_sent', 'ua', 'Ваше повідомлення було надіслано адміністрації.');
INSERT INTO `languages` VALUES('Method of check', 'en', 'Method of check');
INSERT INTO `languages` VALUES('Method of check', 'ru', 'Метод проверки');
INSERT INTO `languages` VALUES('method of check', 'ua', 'Метод перевірки');
INSERT INTO `languages` VALUES('Method of request', 'en', 'Method of request');
INSERT INTO `languages` VALUES('Method of request', 'ru', 'Метод запроса');
INSERT INTO `languages` VALUES('method of request', 'ua', 'Метод запиту');
INSERT INTO `languages` VALUES('mine_torrents', 'en', 'My Releases');
INSERT INTO `languages` VALUES('mine_torrents', 'ru', 'Мои релизы');
INSERT INTO `languages` VALUES('mine_torrents', 'ua', 'Мої релізи');
INSERT INTO `languages` VALUES('missing_data', 'en', 'Missing form data');
INSERT INTO `languages` VALUES('missing_data', 'ru', 'Заполните все поля формы');
INSERT INTO `languages` VALUES('missing_data', 'ua', 'Заповніть всі поля форми');
INSERT INTO `languages` VALUES('missing_form_data', 'en', 'Please fill in all the fields in this form.');
INSERT INTO `languages` VALUES('missing_form_data', 'ru', 'Заполните все поля формы.');
INSERT INTO `languages` VALUES('missing_form_data', 'ua', 'Заповніть всі поля форми.');
INSERT INTO `languages` VALUES('moderate', 'en', 'Admin');
INSERT INTO `languages` VALUES('moderate', 'ru', 'Admin');
INSERT INTO `languages` VALUES('moderate', 'ua', 'Admin');
INSERT INTO `languages` VALUES('monitor_comments', 'en', 'Monitor this comments');
INSERT INTO `languages` VALUES('monitor_comments', 'ru', 'Следить за комментариями');
INSERT INTO `languages` VALUES('monitor_comments', 'ua', 'Слідкувати за коментарями');
INSERT INTO `languages` VALUES('monitor_comments_disable', 'en', 'Disable monitoring comments');
INSERT INTO `languages` VALUES('monitor_comments_disable', 'ru', 'Отключить слежение');
INSERT INTO `languages` VALUES('monitor_comments_disable', 'ua', 'Відключити стеження');
INSERT INTO `languages` VALUES('more', 'en', 'More');
INSERT INTO `languages` VALUES('more', 'ru', 'Подробнее');
INSERT INTO `languages` VALUES('more', 'ua', 'Детальніше');
INSERT INTO `languages` VALUES('movies', 'en', 'Favorite Releases:');
INSERT INTO `languages` VALUES('movies', 'ru', 'Любимые релизы:');
INSERT INTO `languages` VALUES('movies', 'ua', 'Улюблені релізи:');
INSERT INTO `languages` VALUES('multitracker_torrent', 'en', 'Multitracker torrent');
INSERT INTO `languages` VALUES('multitracker_torrent', 'ru', 'Мультитрекерный торрент');
INSERT INTO `languages` VALUES('multitracker_torrent', 'ua', 'Мультитрекерний торрент');
INSERT INTO `languages` VALUES('multitracker_torrent_notice', 'en', 'Check this box, if u are uploading torrent file from other tracker');
INSERT INTO `languages` VALUES('multitracker_torrent_notice', 'ru', 'Отметьте эту галочку, если вы загружаете торрент-файл другого трекера');
INSERT INTO `languages` VALUES('multitracker_torrent_notice', 'ua', 'Отметьте эту галочку, если вы загружаете торрент-файл другого трекера');
INSERT INTO `languages` VALUES('music', 'en', 'Favorite Music:');
INSERT INTO `languages` VALUES('music', 'ru', 'Любимая музыка:');
INSERT INTO `languages` VALUES('music', 'ua', 'Улюблена музика:');
INSERT INTO `languages` VALUES('my', 'en', 'Control panel');
INSERT INTO `languages` VALUES('my', 'ru', 'Панель управления');
INSERT INTO `languages` VALUES('my', 'ua', 'Налаштування');
INSERT INTO `languages` VALUES('My privacy level', 'en', 'My privacy level');
INSERT INTO `languages` VALUES('My privacy level', 'ru', 'Уровень приватности');
INSERT INTO `languages` VALUES('my privacy level', 'ua', 'Рівень приватності');
INSERT INTO `languages` VALUES('mynotifs_config_page', 'en', 'Go to configuration page of my notifications');
INSERT INTO `languages` VALUES('mynotifs_config_page', 'ru', 'Перейти на страницу конфигурации моих уведомлений');
INSERT INTO `languages` VALUES('mynotifs_config_page', 'ua', 'Перейти на сторінку конфігурації моїх повідомлень');
INSERT INTO `languages` VALUES('mynotifs_email_attention', 'en', '<b>Attention:</b> Email-based notifications are sending only when you are monitoring comments, topics, e.g., due our carefully antispam policy');
INSERT INTO `languages` VALUES('mynotifs_email_attention', 'ru', '<b>Внимание:</b> Уведомления на почтовые адреса о комментариях, топиках и т.д. отправляются только в том случае, если вы подписаны на уведомления о таких комментариях');
INSERT INTO `languages` VALUES('mynotifs_email_attention', 'ua', '<b>Увага:</b> Повідомлення на поштові адреси про коментарі, топіках і т.д. відправляються тільки в тому випадку, якщо ви підписані на повідомлення про такі коментарях');
INSERT INTO `languages` VALUES('mynotifs_not_subscribed_redirect', 'en', 'You did not subscribed to view %s notifications, please set up it in your <a href="%s">notifications configuration page</a> and try again. Redirecting you to notifications configuration');
INSERT INTO `languages` VALUES('mynotifs_not_subscribed_redirect', 'ru', 'Вы не включили просмотр уведомлений: %s, пожалуйста <a href="%s">настройте ваши уведомления</a> и попытайтесь еще раз. Перенаправляем на страницу конфигурации уведомлений');
INSERT INTO `languages` VALUES('mynotifs_not_subscribed_redirect', 'ua', 'Ви не включили перегляд повідомлень: %s, будь ласка <a href="%s">налаштуйте ваші повідомлення</a> і спробуйте ще раз. Перенаправляємо на сторінку конфігурації повідомлень');
INSERT INTO `languages` VALUES('mysqlstats', 'en', 'MySQL status');
INSERT INTO `languages` VALUES('mysqlstats', 'ru', 'Статистика MySQL');
INSERT INTO `languages` VALUES('mysqlstats', 'ua', 'Статистика MySQL');
INSERT INTO `languages` VALUES('my_allow_pm_from', 'en', 'Allow PM from');
INSERT INTO `languages` VALUES('my_allow_pm_from', 'ru', 'Разрешить ЛС от');
INSERT INTO `languages` VALUES('my_allow_pm_from', 'ua', 'Дозволити ПП від');
INSERT INTO `languages` VALUES('my_avatar_url', 'en', 'Address of your avatar');
INSERT INTO `languages` VALUES('my_avatar_url', 'ru', 'Адрес аватары');
INSERT INTO `languages` VALUES('my_avatar_url', 'ua', 'Адреса аватари');
INSERT INTO `languages` VALUES('my_birthdate', 'en', 'Date of birth');
INSERT INTO `languages` VALUES('my_birthdate', 'ru', 'Дата рождения');
INSERT INTO `languages` VALUES('my_birthdate', 'ua', 'Дата народження');
INSERT INTO `languages` VALUES('my_bonus', 'en', 'My bonus');
INSERT INTO `languages` VALUES('my_bonus', 'ru', 'Мой бонус');
INSERT INTO `languages` VALUES('my_bonus', 'ua', 'Мій бонус');
INSERT INTO `languages` VALUES('my_comments', 'en', 'Alert in PM for the comments on my profile');
INSERT INTO `languages` VALUES('my_comments', 'ru', 'Оповещать в ЛС о комментариях к моему профилю');
INSERT INTO `languages` VALUES('my_comments', 'ua', 'Сповіщати в ПП про коментарі до мого профілю');
INSERT INTO `languages` VALUES('my_contact', 'en', 'My messengers');
INSERT INTO `languages` VALUES('my_contact', 'ru', 'Система мгновенных сообщений');
INSERT INTO `languages` VALUES('my_contact', 'ua', 'Система миттєвих повідомлень');
INSERT INTO `languages` VALUES('my_contact_aim', 'en', 'Name in AIM');
INSERT INTO `languages` VALUES('my_contact_aim', 'ru', 'Имя в AIM');
INSERT INTO `languages` VALUES('my_contact_aim', 'ua', 'Ім''я в AIM');
INSERT INTO `languages` VALUES('my_contact_descr', 'en', 'If you want other users quickly contact you, specify your information in the next systems of rapid messaging');
INSERT INTO `languages` VALUES('my_contact_descr', 'ru', 'Если вы хотите, чтобы другие посетители могли быстро связаться с вами, укажите свои данные в следующих системах быстрых сообщений');
INSERT INTO `languages` VALUES('my_contact_descr', 'ua', 'Якщо ви хочете, щоб інші відвідувачі могли швидко зв''язатися з вами, вкажіть свої дані в наступних системах швидких повідомлень');
INSERT INTO `languages` VALUES('my_contact_icq', 'en', 'Your ICQ number');
INSERT INTO `languages` VALUES('my_contact_icq', 'ru', 'Номер ICQ');
INSERT INTO `languages` VALUES('my_contact_icq', 'ua', 'Номер ICQ');
INSERT INTO `languages` VALUES('my_contact_mirc', 'en', 'Name at mIRC!');
INSERT INTO `languages` VALUES('my_contact_mirc', 'ru', 'Имя в mIRC!');
INSERT INTO `languages` VALUES('my_contact_mirc', 'ua', 'Ім''я в mIRC!');
INSERT INTO `languages` VALUES('my_contact_msn', 'en', 'Name at MSN');
INSERT INTO `languages` VALUES('my_contact_msn', 'ru', 'Ваш MSN');
INSERT INTO `languages` VALUES('my_contact_msn', 'ua', 'Ваш MSN');
INSERT INTO `languages` VALUES('my_contact_skype', 'en', 'Name at Skype');
INSERT INTO `languages` VALUES('my_contact_skype', 'ru', 'Имя в Skype');
INSERT INTO `languages` VALUES('my_contact_skype', 'ua', 'Ім''я в Skype');
INSERT INTO `languages` VALUES('my_contact_yahoo', 'en', 'Name at Yahoo!');
INSERT INTO `languages` VALUES('my_contact_yahoo', 'ru', 'Имя в Yahoo!');
INSERT INTO `languages` VALUES('my_contact_yahoo', 'ua', 'Ім''я в Yahoo!');
INSERT INTO `languages` VALUES('my_country', 'en', 'Country');
INSERT INTO `languages` VALUES('my_country', 'ru', 'Страна');
INSERT INTO `languages` VALUES('my_country', 'ua', 'Країна');
INSERT INTO `languages` VALUES('my_day', 'en', 'Day');
INSERT INTO `languages` VALUES('my_day', 'ru', 'День');
INSERT INTO `languages` VALUES('my_day', 'ua', 'День');
INSERT INTO `languages` VALUES('my_default_browse', 'en', 'Default viewing categories');
INSERT INTO `languages` VALUES('my_default_browse', 'ru', 'Категории просмотра по умолчанию');
INSERT INTO `languages` VALUES('my_default_browse', 'ua', 'Категорії перегляду за замовчуванням');
INSERT INTO `languages` VALUES('my_delete_after_reply', 'en', 'Delete PMs after reply');
INSERT INTO `languages` VALUES('my_delete_after_reply', 'ru', 'Удалять ЛС при ответе');
INSERT INTO `languages` VALUES('my_delete_after_reply', 'ua', 'Видаляти ПП при відповіді');
INSERT INTO `languages` VALUES('my_discount', 'en', 'Your farmed');
INSERT INTO `languages` VALUES('my_discount', 'ru', 'Ваша скидка');
INSERT INTO `languages` VALUES('my_discount', 'ua', 'Ваша знижка');
INSERT INTO `languages` VALUES('my_email_notify', 'en', 'Notification by e-mail');
INSERT INTO `languages` VALUES('my_email_notify', 'ru', 'Уведомление по email');
INSERT INTO `languages` VALUES('my_email_notify', 'ua', 'Повідомлення по email');
INSERT INTO `languages` VALUES('my_formula', 'en', 'he formula for calculating your rating');
INSERT INTO `languages` VALUES('my_formula', 'ru', 'Формула расчета вашего рейтинга');
INSERT INTO `languages` VALUES('my_formula', 'ua', 'Формула розрахунку вашого рейтингу');
INSERT INTO `languages` VALUES('my_gender', 'en', 'My sex');
INSERT INTO `languages` VALUES('my_gender', 'ru', 'Пол');
INSERT INTO `languages` VALUES('my_gender', 'ua', 'Пол');
INSERT INTO `languages` VALUES('my_gender_female', 'en', 'Woman');
INSERT INTO `languages` VALUES('my_gender_female', 'ru', 'Девушка');
INSERT INTO `languages` VALUES('my_gender_female', 'ua', 'Дівчина');
INSERT INTO `languages` VALUES('my_gender_male', 'en', 'Man');
INSERT INTO `languages` VALUES('my_gender_male', 'ru', 'Парень');
INSERT INTO `languages` VALUES('my_gender_male', 'ua', 'Хлопець');
INSERT INTO `languages` VALUES('my_goods', 'en', 'Advantages');
INSERT INTO `languages` VALUES('my_goods', 'ru', 'Ваши преимущества');
INSERT INTO `languages` VALUES('my_goods', 'ua', 'Ваші переваги');
INSERT INTO `languages` VALUES('my_info', 'en', 'Info');
INSERT INTO `languages` VALUES('my_info', 'ru', 'Информация');
INSERT INTO `languages` VALUES('my_info', 'ua', 'Інформація');
INSERT INTO `languages` VALUES('my_language', 'en', 'Language');
INSERT INTO `languages` VALUES('my_language', 'ru', 'Язык');
INSERT INTO `languages` VALUES('my_language', 'ua', 'Мова');
INSERT INTO `languages` VALUES('my_mail', 'en', 'E-mail');
INSERT INTO `languages` VALUES('my_mail', 'ru', 'Email');
INSERT INTO `languages` VALUES('my_mail', 'ua', 'Email');
INSERT INTO `languages` VALUES('my_mail_sent', 'en', 'Confirmative letter was sent to your e-mail!');
INSERT INTO `languages` VALUES('my_mail_sent', 'ru', 'Подтверждающее письмо отправлено! Если в течение 3х дней вы не отреагируете на письмо, ваш аккаунт будет удален автоматически.');
INSERT INTO `languages` VALUES('my_mail_sent', 'ua', 'Підтверджуючий лист відправлено! Якщо протягом 3х днів ви не відреагуєте на лист, ваш акаунт буде видалено автоматично.');
INSERT INTO `languages` VALUES('my_mail_updated', 'en', 'E-mail address updated!');
INSERT INTO `languages` VALUES('my_mail_updated', 'ru', 'E-mail адрес обновлен!');
INSERT INTO `languages` VALUES('my_mail_updated', 'ua', 'E-mail адресу оновлено!');
INSERT INTO `languages` VALUES('my_messages_per_page', 'en', 'Messages on the page');
INSERT INTO `languages` VALUES('my_messages_per_page', 'ru', 'Сообщений на страницу');
INSERT INTO `languages` VALUES('my_messages_per_page', 'ua', 'Повідомлень на сторінку');
INSERT INTO `languages` VALUES('my_month', 'en', 'Month');
INSERT INTO `languages` VALUES('my_month', 'ru', 'Месяц');
INSERT INTO `languages` VALUES('my_month', 'ua', 'Місяць');
INSERT INTO `languages` VALUES('my_months_april', 'en', 'April');
INSERT INTO `languages` VALUES('my_months_april', 'ru', 'Апрель');
INSERT INTO `languages` VALUES('my_months_april', 'ua', 'Квітень');
INSERT INTO `languages` VALUES('my_months_august', 'en', 'August');
INSERT INTO `languages` VALUES('my_months_august', 'ru', 'Август');
INSERT INTO `languages` VALUES('my_months_august', 'ua', 'Серпень');
INSERT INTO `languages` VALUES('my_months_december', 'en', 'December');
INSERT INTO `languages` VALUES('my_months_december', 'ru', 'Декабрь');
INSERT INTO `languages` VALUES('my_months_december', 'ua', 'Грудень');
INSERT INTO `languages` VALUES('my_months_february', 'en', 'February');
INSERT INTO `languages` VALUES('my_months_february', 'ru', 'Февраль');
INSERT INTO `languages` VALUES('my_months_february', 'ua', 'Лютий');
INSERT INTO `languages` VALUES('my_months_january', 'en', 'January');
INSERT INTO `languages` VALUES('my_months_january', 'ru', 'Январь');
INSERT INTO `languages` VALUES('my_months_january', 'ua', 'Січень');
INSERT INTO `languages` VALUES('my_months_jule', 'en', 'July');
INSERT INTO `languages` VALUES('my_months_jule', 'ru', 'Июль');
INSERT INTO `languages` VALUES('my_months_jule', 'ua', 'Липень');
INSERT INTO `languages` VALUES('my_months_june', 'en', 'June');
INSERT INTO `languages` VALUES('my_months_june', 'ru', 'Июнь');
INSERT INTO `languages` VALUES('my_months_june', 'ua', 'Червень');
INSERT INTO `languages` VALUES('my_months_march', 'en', 'March');
INSERT INTO `languages` VALUES('my_months_march', 'ru', 'Март');
INSERT INTO `languages` VALUES('my_months_march', 'ua', 'Березень');
INSERT INTO `languages` VALUES('my_months_may', 'en', 'May');
INSERT INTO `languages` VALUES('my_months_may', 'ru', 'Май');
INSERT INTO `languages` VALUES('my_months_may', 'ua', 'Травень');
INSERT INTO `languages` VALUES('my_months_november', 'en', 'November');
INSERT INTO `languages` VALUES('my_months_november', 'ru', 'Ноябрь');
INSERT INTO `languages` VALUES('my_months_november', 'ua', 'Листопад');
INSERT INTO `languages` VALUES('my_months_october', 'en', 'October');
INSERT INTO `languages` VALUES('my_months_october', 'ru', 'Октябрь');
INSERT INTO `languages` VALUES('my_months_october', 'ua', 'Жовтень');
INSERT INTO `languages` VALUES('my_months_september', 'en', 'September');
INSERT INTO `languages` VALUES('my_months_september', 'ru', 'Сентябрь');
INSERT INTO `languages` VALUES('my_months_september', 'ua', 'Вересень');
INSERT INTO `languages` VALUES('my_my', 'en', 'Control panel');
INSERT INTO `languages` VALUES('my_my', 'ru', 'Панель управления');
INSERT INTO `languages` VALUES('my_my', 'ua', 'Налаштування облікового запису');
INSERT INTO `languages` VALUES('my_notifs', 'en', 'My notifications');
INSERT INTO `languages` VALUES('my_notifs', 'ru', 'Мои уведомления');
INSERT INTO `languages` VALUES('my_notifs', 'ua', 'Мої повідомлення');
INSERT INTO `languages` VALUES('my_notifs_settings', 'en', 'Setting my notifications');
INSERT INTO `languages` VALUES('my_notifs_settings', 'ru', 'Настройки моих уведомлений');
INSERT INTO `languages` VALUES('my_notifs_settings', 'ua', 'Установки моїх повідомлень');
INSERT INTO `languages` VALUES('my_private_groups', 'en', 'My friends');
INSERT INTO `languages` VALUES('my_private_groups', 'ru', 'Мои друзья');
INSERT INTO `languages` VALUES('my_private_groups', 'ua', 'Мої друзі');
INSERT INTO `languages` VALUES('my_rating', 'en', 'My rating');
INSERT INTO `languages` VALUES('my_rating', 'ru', 'Мой рейтинг');
INSERT INTO `languages` VALUES('my_rating', 'ua', 'Мій рейтинг');
INSERT INTO `languages` VALUES('my_releases', 'en', 'My Releases');
INSERT INTO `languages` VALUES('my_releases', 'ru', 'Мои релизы');
INSERT INTO `languages` VALUES('my_releases', 'ua', 'Мої релізи');
INSERT INTO `languages` VALUES('my_sentbox', 'en', 'Save sent PMs');
INSERT INTO `languages` VALUES('my_sentbox', 'ru', 'Сохранять отправленные ЛС');
INSERT INTO `languages` VALUES('my_sentbox', 'ua', 'Зберігати надіслані ПП');
INSERT INTO `languages` VALUES('my_show_avatars', 'en', 'Show avatars');
INSERT INTO `languages` VALUES('my_show_avatars', 'ru', 'Показывать аватары');
INSERT INTO `languages` VALUES('my_show_avatars', 'ua', 'Показувати аватари');
INSERT INTO `languages` VALUES('my_style', 'en', 'Style');
INSERT INTO `languages` VALUES('my_style', 'ru', 'Вид интерфейса');
INSERT INTO `languages` VALUES('my_style', 'ua', 'Вид інтерфейсу');
INSERT INTO `languages` VALUES('my_timezone', 'en', 'Timezone');
INSERT INTO `languages` VALUES('my_timezone', 'ru', 'Временная зона');
INSERT INTO `languages` VALUES('my_timezone', 'ua', 'Часова зона');
INSERT INTO `languages` VALUES('my_topics_per_page', 'en', 'Topics on the page');
INSERT INTO `languages` VALUES('my_topics_per_page', 'ru', 'Тем на страницу');
INSERT INTO `languages` VALUES('my_topics_per_page', 'ua', 'Тим на сторінку');
INSERT INTO `languages` VALUES('my_torrents', 'en', 'My releases');
INSERT INTO `languages` VALUES('my_torrents', 'ru', 'Мои релизы');
INSERT INTO `languages` VALUES('my_torrents', 'ua', 'Мої релізи');
INSERT INTO `languages` VALUES('my_torrents_per_page', 'en', 'Releases on the page');
INSERT INTO `languages` VALUES('my_torrents_per_page', 'ru', 'Релизов на страницу');
INSERT INTO `languages` VALUES('my_torrents_per_page', 'ua', 'Релізів на сторінку');
INSERT INTO `languages` VALUES('my_unset', 'en', 'Not selected');
INSERT INTO `languages` VALUES('my_unset', 'ru', 'Не выбрано');
INSERT INTO `languages` VALUES('my_unset', 'ua', 'Не обрано');
INSERT INTO `languages` VALUES('my_updated', 'en', 'Profile updated!');
INSERT INTO `languages` VALUES('my_updated', 'ru', 'Профиль обновлён!');
INSERT INTO `languages` VALUES('my_updated', 'ua', 'Профіль оновлений!');
INSERT INTO `languages` VALUES('my_userbar', 'en', 'Userbar');
INSERT INTO `languages` VALUES('my_userbar', 'ru', 'Юзербар');
INSERT INTO `languages` VALUES('my_userbar', 'ua', 'Юзербар');
INSERT INTO `languages` VALUES('my_userbar_descr', 'en', 'This is your userbar. You can place it as your sing on forums.<br />Forum users will see your ration on this tracker. Also if you will put a link on our tracker - they will be able to get to this traker simply by clicking on your userbar.<br /><br />This is your <b>BB-code</b> for an insertion into your signature on forums.');
INSERT INTO `languages` VALUES('my_userbar_descr', 'ru', 'Это ваш юзербар. Вы можете поставить его в подписи на форуме.<br />Пользователям форума будет виден ваш рейтинг на нашем трекере, а если вы еще поставите и ссылку на наш трекер - они смогут попасть на наш трекер просто нажав на картинку.<br /><br />Вот ваш <b>BB-код</b> для вставки в подпись на форумах');
INSERT INTO `languages` VALUES('my_userbar_descr', 'ua', 'Це ваш юзербар. Ви можете поставити його в підписі на форумі.<br />Користувачам форуму буде видно ваш рейтинг на нашому трекері, а якщо ви ще поставите і посилання на наш трекер - вони зможуть потрапити на наш трекер просто натиснувши на картинку.<br /><br />Ось ваш <b>BB-код</b> для вставки в підпис на форумах');
INSERT INTO `languages` VALUES('my_warnings', 'en', 'My warnings');
INSERT INTO `languages` VALUES('my_warnings', 'ru', 'Мои предупреждения');
INSERT INTO `languages` VALUES('my_warnings', 'ua', 'Мої попередження');
INSERT INTO `languages` VALUES('my_website', 'en', 'Website');
INSERT INTO `languages` VALUES('my_website', 'ru', 'Сайт');
INSERT INTO `languages` VALUES('my_website', 'ua', 'Сайт');
INSERT INTO `languages` VALUES('my_year', 'en', 'Year');
INSERT INTO `languages` VALUES('my_year', 'ru', 'Год');
INSERT INTO `languages` VALUES('my_year', 'ua', 'Рік');
INSERT INTO `languages` VALUES('my_you_can_park', 'en', 'You can park your accaunt in avoidance of deleting it from unactivity, for example if you go to a vacation. But when it is parked, some functions will not be accessible to you, for example viewing or downloading torrents.');
INSERT INTO `languages` VALUES('name', 'en', 'Name');
INSERT INTO `languages` VALUES('name', 'ru', 'Название');
INSERT INTO `languages` VALUES('name', 'ua', 'Назва');
INSERT INTO `languages` VALUES('name_cache', 'en', 'Name cache');
INSERT INTO `languages` VALUES('name_cache', 'ru', 'Название кэша');
INSERT INTO `languages` VALUES('name_cache', 'ua', 'Назва кешу');
INSERT INTO `languages` VALUES('Native', 'en', 'Native');
INSERT INTO `languages` VALUES('Native', 'ru', 'Встроенный');
INSERT INTO `languages` VALUES('native', 'ua', 'Вбудований');
INSERT INTO `languages` VALUES('need_seeds', 'en', 'Releases, which need seeds');
INSERT INTO `languages` VALUES('need_seeds', 'ru', 'Релизы, которым нужны раздающие');
INSERT INTO `languages` VALUES('need_seeds', 'ua', 'Релізи, яким потрібні роздають');
INSERT INTO `languages` VALUES('neighbours', 'en', 'Neighbors');
INSERT INTO `languages` VALUES('neighbours', 'ru', 'Соседи');
INSERT INTO `languages` VALUES('neighbours', 'ua', 'Сусіди');
INSERT INTO `languages` VALUES('network_neighbot', 'en', 'Network Neighborhood');
INSERT INTO `languages` VALUES('network_neighbot', 'ru', 'Сетевые соседи');
INSERT INTO `languages` VALUES('network_neighbot', 'ua', 'Мережеві сусіди');
INSERT INTO `languages` VALUES('never', 'en', 'never');
INSERT INTO `languages` VALUES('never', 'ru', 'никогда');
INSERT INTO `languages` VALUES('never', 'ua', 'ніколи');
INSERT INTO `languages` VALUES('news', 'en', 'News');
INSERT INTO `languages` VALUES('news', 'ru', 'Новости');
INSERT INTO `languages` VALUES('news', 'ua', 'Новини');
INSERT INTO `languages` VALUES('newsarchive', 'en', 'View all news');
INSERT INTO `languages` VALUES('newsarchive', 'ru', 'Все новости');
INSERT INTO `languages` VALUES('newsarchive', 'ua', 'Всі новини');
INSERT INTO `languages` VALUES('Newscomments', 'en', 'Newscomments');
INSERT INTO `languages` VALUES('Newscomments', 'ru', 'Комм.новостей');
INSERT INTO `languages` VALUES('newscomments', 'ua', 'Ком. новостей');
INSERT INTO `languages` VALUES('news_added', 'en', 'Added');
INSERT INTO `languages` VALUES('news_added', 'ru', 'Добавлена');
INSERT INTO `languages` VALUES('news_added', 'ua', 'Додана');
INSERT INTO `languages` VALUES('news_poster', 'en', 'Author');
INSERT INTO `languages` VALUES('news_poster', 'ru', 'Автор');
INSERT INTO `languages` VALUES('news_poster', 'ua', 'Автор');
INSERT INTO `languages` VALUES('newuser', 'en', 'View new users');
INSERT INTO `languages` VALUES('newuser', 'ru', 'Новые пользователи');
INSERT INTO `languages` VALUES('newuser', 'ua', 'Нові користувачі');
INSERT INTO `languages` VALUES('new_comment', 'en', 'New comment!');
INSERT INTO `languages` VALUES('new_comment', 'ru', 'Новый комментарий!');
INSERT INTO `languages` VALUES('new_comment', 'ua', 'Новий коментар!');
INSERT INTO `languages` VALUES('new_email', 'en', '<br /> New E-mail:');
INSERT INTO `languages` VALUES('new_email', 'ru', '<br /> Новая почта:');
INSERT INTO `languages` VALUES('new_email', 'ua', '<br /> Нова пошта:');
INSERT INTO `languages` VALUES('new_friends', 'en', 'New offer of friendship.!');
INSERT INTO `languages` VALUES('new_friends', 'ru', 'Новое предложение дружбы!');
INSERT INTO `languages` VALUES('new_friends', 'ua', 'Нова пропозиція дружби!');
INSERT INTO `languages` VALUES('new_offers', 'en', 'New proposals');
INSERT INTO `languages` VALUES('new_offers', 'ru', 'Новые предложения');
INSERT INTO `languages` VALUES('new_offers', 'ua', 'Нові пропозиції');
INSERT INTO `languages` VALUES('new_pages', 'en', 'New page!');
INSERT INTO `languages` VALUES('new_pages', 'ru', 'Новая страница!');
INSERT INTO `languages` VALUES('new_pages', 'ua', 'Нова сторінка!');
INSERT INTO `languages` VALUES('new_password', 'en', '<br /> New password:');
INSERT INTO `languages` VALUES('new_password', 'ru', '<br /> Новый пароль:');
INSERT INTO `languages` VALUES('new_password', 'ua', '<br /> Новий пароль:');
INSERT INTO `languages` VALUES('new_pm', 'en', '(%d new)');
INSERT INTO `languages` VALUES('new_pm', 'ru', '(%d новых)');
INSERT INTO `languages` VALUES('new_pm', 'ua', '(%d новых)');
INSERT INTO `languages` VALUES('new_pms', 'en', 'You have %d new PM(s)');
INSERT INTO `languages` VALUES('new_pms', 'ru', 'У вас %d новое(ых) сообщение(ий)!');
INSERT INTO `languages` VALUES('new_pms', 'ua', 'У вас %d нове(их) повідомлення(ь)!');
INSERT INTO `languages` VALUES('new_port_test', 'en', 'New Port test');
INSERT INTO `languages` VALUES('new_port_test', 'ru', 'New Port test');
INSERT INTO `languages` VALUES('new_port_test', 'ua', 'New Port test');
INSERT INTO `languages` VALUES('new_reports', 'en', 'The new complaint!');
INSERT INTO `languages` VALUES('new_reports', 'ru', 'Новая жалоба!');
INSERT INTO `languages` VALUES('new_reports', 'ua', 'Нова скарга!');
INSERT INTO `languages` VALUES('new_torrents', 'en', 'New releases');
INSERT INTO `languages` VALUES('new_torrents', 'ru', 'Новые релизы');
INSERT INTO `languages` VALUES('new_torrents', 'ua', 'Нові релізи');
INSERT INTO `languages` VALUES('new_torrents_stats', 'en', 'Seeding: %d, Leeching: %d');
INSERT INTO `languages` VALUES('new_torrents_stats', 'ru', 'Раздают: %d, Качают: %d');
INSERT INTO `languages` VALUES('new_torrents_stats', 'ua', 'Роздають: %d, Качають: %d');
INSERT INTO `languages` VALUES('new_unchecked', 'en', 'The new release of unverified');
INSERT INTO `languages` VALUES('new_unchecked', 'ru', 'Новый непроверенный релиз');
INSERT INTO `languages` VALUES('new_unchecked', 'ua', 'Новий неперевірений реліз');
INSERT INTO `languages` VALUES('new_unread', 'en', 'New PM!');
INSERT INTO `languages` VALUES('new_unread', 'ru', 'Новое личное сообщение!');
INSERT INTO `languages` VALUES('new_unread', 'ua', 'Нове особисте повідомлення!');
INSERT INTO `languages` VALUES('new_users', 'en', 'New user');
INSERT INTO `languages` VALUES('new_users', 'ru', 'Новый пользователь');
INSERT INTO `languages` VALUES('new_users', 'ua', 'Новий користувач');
INSERT INTO `languages` VALUES('next', 'en', 'Next');
INSERT INTO `languages` VALUES('next', 'ru', 'Вперед');
INSERT INTO `languages` VALUES('next', 'ua', 'Вперед');
INSERT INTO `languages` VALUES('Nickname', 'en', 'Nickname');
INSERT INTO `languages` VALUES('Nickname', 'ru', 'Никнейм');
INSERT INTO `languages` VALUES('nickname', 'ua', 'Никнейм');
INSERT INTO `languages` VALUES('no', 'en', 'No');
INSERT INTO `languages` VALUES('no', 'ru', 'Нет');
INSERT INTO `languages` VALUES('no', 'ua', 'Немає');
INSERT INTO `languages` VALUES('none_voted', 'en', 'No one voted');
INSERT INTO `languages` VALUES('none_voted', 'ru', 'нет голосов');
INSERT INTO `languages` VALUES('none_voted', 'ua', 'немає голосів');
INSERT INTO `languages` VALUES('none_yet', 'en', 'Nobody');
INSERT INTO `languages` VALUES('none_yet', 'ru', 'Никто');
INSERT INTO `languages` VALUES('none_yet', 'ua', 'Ніхто');
INSERT INTO `languages` VALUES('nonfree', 'en', 'Pay (For entry into the group have something to pay)');
INSERT INTO `languages` VALUES('nonfree', 'ru', 'Платная (За вступление в группу надо что-то заплатить)');
INSERT INTO `languages` VALUES('nonfree', 'ua', 'Платна (За вступ до групи треба щось заплатити)');
INSERT INTO `languages` VALUES('nothing_found', 'en', 'Nothing was found');
INSERT INTO `languages` VALUES('nothing_found', 'ru', 'Ничего не найдено');
INSERT INTO `languages` VALUES('nothing_found', 'ua', 'Нічого не знайдено');
INSERT INTO `languages` VALUES('notice_friends', 'en', 'One of the users of our site that offered you friendship! You can confirm or deny this on your "My Friends"');
INSERT INTO `languages` VALUES('notice_friends', 'ru', 'Один из пользователей нашего сайта только что предложили вам дружбу! Вы можете подтвердить или отказать ему в этом на странице "Мои Друзья"');
INSERT INTO `languages` VALUES('notice_friends', 'ua', 'Один з користувачів нашого сайту тільки що запропонували вам дружбу! Ви можете підтвердити або відмовити йому в цьому на сторінці "Мої Друзі"');
INSERT INTO `languages` VALUES('notice_reports', 'en', 'The new complaint has just been submitted. You can look it in the administrator-panel under "Complaint"');
INSERT INTO `languages` VALUES('notice_reports', 'ru', 'Только что была подана новая жалоба. Вы можете посмотреть ее в админ-панели в разделе "Жалобы"');
INSERT INTO `languages` VALUES('notice_reports', 'ua', 'Тільки що була подана нова скарга. Ви можете побачити її в адмін-панелі в розділі "Скарги"');
INSERT INTO `languages` VALUES('notice_torrents', 'en', 'The site has a new release');
INSERT INTO `languages` VALUES('notice_torrents', 'ru', 'На сайте появился новый релиз');
INSERT INTO `languages` VALUES('notice_torrents', 'ua', 'На сайті з''явився новий реліз');
INSERT INTO `languages` VALUES('notice_unread', 'en', 'You have new private message');
INSERT INTO `languages` VALUES('notice_unread', 'ru', 'У вас новое личное сообщение');
INSERT INTO `languages` VALUES('notice_unread', 'ua', 'У вас нове приватне повідомлення');
INSERT INTO `languages` VALUES('notice_users', 'en', 'The site has just registered a new user, you can view information about it on the "My Notifications"');
INSERT INTO `languages` VALUES('notice_users', 'ru', 'На сайте только что зарегистрировался новый пользователь, вы можете просмотреть информацию о нем на странице "Мои Уведомления"');
INSERT INTO `languages` VALUES('notice_users', 'ua', 'На сайті тільки що зареєструвався новий користувач, ви можете переглянути інформацію про нього на сторінці "Мої Повідомлення"');
INSERT INTO `languages` VALUES('notifications', 'en', 'Notifications');
INSERT INTO `languages` VALUES('notifications', 'ru', 'Уведомления');
INSERT INTO `languages` VALUES('notifications', 'ua', 'Повідомлення');
INSERT INTO `languages` VALUES('notifications_cp', 'en', 'You can customize your notification in the control panel account');
INSERT INTO `languages` VALUES('notifications_cp', 'ru', 'Вы можете настроить свои уведомления в панели управления аккаунтом');
INSERT INTO `languages` VALUES('notifications_cp', 'ua', 'Ви можете налаштувати свої повідомлення у панелі керування акаунтом');
INSERT INTO `languages` VALUES('notify_email', 'en', 'Notify email');
INSERT INTO `languages` VALUES('notify_email', 'ru', 'Уведомлять на email');
INSERT INTO `languages` VALUES('notify_email', 'ua', 'Повідомляти на email');
INSERT INTO `languages` VALUES('notify_forumcomments', 'en', 'Notify about new forum posts');
INSERT INTO `languages` VALUES('notify_forumcomments', 'ru', 'Уведомлять о новых постах на форуме');
INSERT INTO `languages` VALUES('notify_forumcomments', 'ua', 'Повідомляти про нові постах на форумі');
INSERT INTO `languages` VALUES('notify_friends', 'en', 'Notify new friends');
INSERT INTO `languages` VALUES('notify_friends', 'ru', 'Уведомлять о новых друзьях');
INSERT INTO `languages` VALUES('notify_friends', 'ua', 'Повідомляти про нових друзів');
INSERT INTO `languages` VALUES('notify_is_forumcomments', 'en', 'New reply to topic %s "%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_forumcomments', 'ru', 'Новый ответ к топику %s "%s"  от %s %s');
INSERT INTO `languages` VALUES('notify_is_forumcomments', 'ua', 'Нова відповідь до топіку %s "%s"  від %s %s');
INSERT INTO `languages` VALUES('notify_is_friends', 'en', '[<a href="friends.php?action=confirm&amp;id=%s">Confirm</a>] or [<a href="friends.php?action=deny&amp;id=%s">Deny</a>] friendship %s');
INSERT INTO `languages` VALUES('notify_is_friends', 'ru', '[<a href="friends.php?action=confirm&amp;id=%s">Подтвердить</a>] или [<a href="friends.php?action=deny&amp;id=%s">Отказать</a>] в дружбе %s');
INSERT INTO `languages` VALUES('notify_is_friends', 'ua', '[<a href="friends.php?action=confirm&amp;id=%s">Підтвердити</a>] або [<a href="friends.php?action=deny&amp;id=%s">Відмовити</a>] в дружбі %s');
INSERT INTO `languages` VALUES('notify_is_newscomments', 'en', 'New comment on news "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_newscomments', 'ru', 'Новый комментарий к новости "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_newscomments', 'ua', 'Новий коментар до новини "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_pagecomments', 'en', 'New comment on page "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_pagecomments', 'ru', 'Новый комментарий к странице "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_pagecomments', 'ua', 'Новий коментар до сторінки "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_pages', 'en', 'New pages "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_pages', 'ru', 'Новая страница "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_pages', 'ua', 'Нова сторінка "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_pollcomments', 'en', 'New comment on this poll "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_pollcomments', 'ru', 'Новый комментарий к опросу "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_pollcomments', 'ua', 'Новий коментар до опитування "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_relcomments', 'en', 'New comment to release "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_relcomments', 'ru', 'Новый комментарий к релизу "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_relcomments', 'ua', 'Новий коментар до релізу "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_reports', 'en', 'The new reports type %s"%s" from user %s %s');
INSERT INTO `languages` VALUES('notify_is_reports', 'ru', 'Новая жалоба типа %s"%s" от пользователя %s %s');
INSERT INTO `languages` VALUES('notify_is_reports', 'ua', 'Нова скарга типу %s"%s" від користувача %s %s');
INSERT INTO `languages` VALUES('notify_is_reqcomments', 'en', 'New comment to request "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_reqcomments', 'ru', 'Новый комментарий к запросу "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_reqcomments', 'ua', 'Новий коментар до запиту "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_rgcomments', 'en', 'New comment to release group "%s%s" from %s %s');
INSERT INTO `languages` VALUES('notify_is_rgcomments', 'ru', 'Новый комментарий к релиз-группе "%s%s" от %s %s');
INSERT INTO `languages` VALUES('notify_is_rgcomments', 'ua', 'Новий коментар до реліз-групи "%s%s" від %s %s');
INSERT INTO `languages` VALUES('notify_is_torrents', 'en', 'New releases "<a href="details.php?id=%s">%s</a>" from %s %s');
INSERT INTO `languages` VALUES('notify_is_torrents', 'ru', 'Новый релиз "<a href="details.php?id=%s">%s</a>" от %s %s');
INSERT INTO `languages` VALUES('notify_is_torrents', 'ua', 'Новий реліз "<a href="details.php?id=%s">%s</a>" від %s %s');
INSERT INTO `languages` VALUES('notify_is_unchecked', 'en', 'New releases unchecked "<a href="details.php?id=%s">%s</a>" from %s %s');
INSERT INTO `languages` VALUES('notify_is_unchecked', 'ru', 'Новый непроверенный релиз "<a href="details.php?id=%s">%s</a>" от %s %s');
INSERT INTO `languages` VALUES('notify_is_unchecked', 'ua', 'Новий неперевірений реліз "<a href="details.php?id=%s">%s</a>" від %s %s');
INSERT INTO `languages` VALUES('notify_is_unread', 'en', 'New message with the subject "%s%s" from %s come %s');
INSERT INTO `languages` VALUES('notify_is_unread', 'ru', 'Новое сообщение с темой "%s%s" от %s пришло %s');
INSERT INTO `languages` VALUES('notify_is_unread', 'ua', 'Нове повідомлення з темою "%s%s" від %s прийшло %s');
INSERT INTO `languages` VALUES('notify_is_usercomments', 'en', 'New comment to the user %s%s from %s %s');
INSERT INTO `languages` VALUES('notify_is_usercomments', 'ru', 'Новый комментарий к пользователю %s%s от %s %s');
INSERT INTO `languages` VALUES('notify_is_usercomments', 'ua', 'Новий коментар до користувача %s%s від %s %s');
INSERT INTO `languages` VALUES('notify_is_users', 'en', 'New User %s%s%s %s');
INSERT INTO `languages` VALUES('notify_is_users', 'ru', 'Новый пользователь %s%s%s %s');
INSERT INTO `languages` VALUES('notify_is_users', 'ua', 'Новий користувач %s%s%s %s');
INSERT INTO `languages` VALUES('notify_newscomments', 'en', 'Notify new comment for news');
INSERT INTO `languages` VALUES('notify_newscomments', 'ru', 'Уведомлять о новых комментариях к новостям');
INSERT INTO `languages` VALUES('notify_newscomments', 'ua', 'Повідомляти про нові коментарі до новин');
INSERT INTO `languages` VALUES('notify_pagecomments', 'en', 'Notify new comment for pages');
INSERT INTO `languages` VALUES('notify_pagecomments', 'ru', 'Уведомлять о новых комментариях к страницам');
INSERT INTO `languages` VALUES('notify_pagecomments', 'ua', 'Повідомляти про нові коментарі до сторінок');
INSERT INTO `languages` VALUES('notify_pages', 'en', 'Notify new pages');
INSERT INTO `languages` VALUES('notify_pages', 'ru', 'Уведомлять о новых страницах');
INSERT INTO `languages` VALUES('notify_pages', 'ua', 'Повідомляти про нові сторінках');
INSERT INTO `languages` VALUES('notify_pollcomments', 'en', 'Notify new comment for polls');
INSERT INTO `languages` VALUES('notify_pollcomments', 'ru', 'Уведомлять о новых комментариях к опросам');
INSERT INTO `languages` VALUES('notify_pollcomments', 'ua', 'Повідомляти про нові коментарі до опитувань');
INSERT INTO `languages` VALUES('notify_popup', 'en', 'Notify on site');
INSERT INTO `languages` VALUES('notify_popup', 'ru', 'Уведомлять на сайте');
INSERT INTO `languages` VALUES('notify_popup', 'ua', 'Повідомляти на сайті');
INSERT INTO `languages` VALUES('notify_popup_comments', 'en', 'Comments');
INSERT INTO `languages` VALUES('notify_popup_comments', 'ru', 'Комментарии');
INSERT INTO `languages` VALUES('notify_popup_comments', 'ua', 'Коментарі');
INSERT INTO `languages` VALUES('notify_relcomments', 'en', 'Notify new comment for release');
INSERT INTO `languages` VALUES('notify_relcomments', 'ru', 'Уведомлять о новых комментариях к релизам');
INSERT INTO `languages` VALUES('notify_relcomments', 'ua', 'Повідомляти про нові коментарі до релізів');
INSERT INTO `languages` VALUES('notify_reports', 'en', 'Notify of new reports');
INSERT INTO `languages` VALUES('notify_reports', 'ru', 'Уведомлять о новых жалобах');
INSERT INTO `languages` VALUES('notify_reports', 'ua', 'Повідомляти про нові скаргах');
INSERT INTO `languages` VALUES('notify_reqcomments', 'en', 'Notify new comment for requests');
INSERT INTO `languages` VALUES('notify_reqcomments', 'ru', 'Уведомлять о новых комментариях к запросам');
INSERT INTO `languages` VALUES('notify_reqcomments', 'ua', 'Повідомляти про нові коментарі до запитів');
INSERT INTO `languages` VALUES('notify_rgcomments', 'en', 'Notify new comment for release groups');
INSERT INTO `languages` VALUES('notify_rgcomments', 'ru', 'Уведомлять о новых комментариях к релиз-группам');
INSERT INTO `languages` VALUES('notify_rgcomments', 'ua', 'Повідомляти про нові коментарі до реліз-груп');
INSERT INTO `languages` VALUES('notify_send', 'en', 'Sent a notify');
INSERT INTO `languages` VALUES('notify_send', 'ru', 'Отправлено уведомление');
INSERT INTO `languages` VALUES('notify_send', 'ua', 'Відправлено повідомлення');
INSERT INTO `languages` VALUES('notify_settigs_saved', 'en', 'Notification Preferences successfully saved, now you can go to control panel account');
INSERT INTO `languages` VALUES('notify_settigs_saved', 'ru', 'Насройки уведомлений успешно сохранены, сейчас вы перейдете к панели управления аккаунтом');
INSERT INTO `languages` VALUES('notify_settigs_saved', 'ua', 'Настройки повідомлень успішно збережені, зараз ви перейдете до панелі управління аккаунтом');
INSERT INTO `languages` VALUES('notify_subject', 'en', 'Unsubscribe release group');
INSERT INTO `languages` VALUES('notify_subject', 'ru', 'Отмена подписки релиз-группы');
INSERT INTO `languages` VALUES('notify_subject', 'ua', 'Скасування підписки реліз-групи');
INSERT INTO `languages` VALUES('notify_torrents', 'en', 'Notify of new release');
INSERT INTO `languages` VALUES('notify_torrents', 'ru', 'Уведомлять о новых релизах');
INSERT INTO `languages` VALUES('notify_torrents', 'ua', 'Повідомляти про нові релізах');
INSERT INTO `languages` VALUES('notify_type', 'en', 'Type notifications');
INSERT INTO `languages` VALUES('notify_type', 'ru', 'Тип уведомления');
INSERT INTO `languages` VALUES('notify_type', 'ua', 'Тип повідомлення');
INSERT INTO `languages` VALUES('notify_unchecked', 'en', 'Notify of new untested release');
INSERT INTO `languages` VALUES('notify_unchecked', 'ru', 'Уведомлять о новых непроверенных релизах');
INSERT INTO `languages` VALUES('notify_unchecked', 'ua', 'Повідомляти про нові неперевірених релізах');
INSERT INTO `languages` VALUES('notify_unread', 'en', 'Notify unread  PM');
INSERT INTO `languages` VALUES('notify_unread', 'ru', 'Уведомлять о непрочитанных ЛС');
INSERT INTO `languages` VALUES('notify_unread', 'ua', 'Повідомляти про непрочитані ПП');
INSERT INTO `languages` VALUES('notify_usercomments', 'en', 'Notify new comment for users');
INSERT INTO `languages` VALUES('notify_usercomments', 'ru', 'Уведомлять о новых комментариях к пользователям');
INSERT INTO `languages` VALUES('notify_usercomments', 'ua', 'Повідомляти про нові коментарі до користувачів');
INSERT INTO `languages` VALUES('notify_users', 'en', 'Notify new for users');
INSERT INTO `languages` VALUES('notify_users', 'ru', 'Уведомлять о новых пользователях');
INSERT INTO `languages` VALUES('notify_users', 'ua', 'Повідомляти про нових користувачів');
INSERT INTO `languages` VALUES('not_act_account', 'en', 'You have not yet activated your account! Activate your account and try again.');
INSERT INTO `languages` VALUES('not_act_account', 'ru', 'Вы еще не активировали свой аккаунт! Активируйте ваш аккаунт и попробуйте снова.');
INSERT INTO `languages` VALUES('not_act_account', 'ua', 'Ви ще не активували свій аккаунт! Активуйте ваш аккаунт і спробуйте знову.');
INSERT INTO `languages` VALUES('not_banned', 'en', 'not banned.');
INSERT INTO `languages` VALUES('not_banned', 'ru', 'не забанен.');
INSERT INTO `languages` VALUES('not_banned', 'ua', 'не забанений.');
INSERT INTO `languages` VALUES('not_chosen_message', 'en', 'You have not chosen to delete the message!');
INSERT INTO `languages` VALUES('not_chosen_message', 'ru', 'Вы не выбрали сообщения для удаления!');
INSERT INTO `languages` VALUES('not_chosen_message', 'ua', 'Ви не вибрали повідомлення для видалення!');
INSERT INTO `languages` VALUES('not_confirmed', 'en', 'Not confirmed');
INSERT INTO `languages` VALUES('not_confirmed', 'ru', 'Не подтвержден');
INSERT INTO `languages` VALUES('not_confirmed', 'ua', 'Не підтверджений');
INSERT INTO `languages` VALUES('not_confirmed_users', 'en', 'Not confirmed users');
INSERT INTO `languages` VALUES('not_confirmed_users', 'ru', 'Не подтвержденные пользователи');
INSERT INTO `languages` VALUES('not_confirmed_users', 'ua', 'Не підтверджені користувачі');
INSERT INTO `languages` VALUES('not_email', 'en', 'You did not enter Email sender!');
INSERT INTO `languages` VALUES('not_email', 'ru', 'Вы не указали Email отправителя!');
INSERT INTO `languages` VALUES('not_email', 'ua', 'Ви не вказали Email відправника!');
INSERT INTO `languages` VALUES('not_enough_votes', 'en', 'Not yet (Has to be at least %d голосов. Already:');
INSERT INTO `languages` VALUES('not_enough_votes', 'ru', 'Еще нет (нужно хотя-бы %d голосов. Собрано:');
INSERT INTO `languages` VALUES('not_enough_votes', 'ua', 'Ще немає (потрібно хоча-б %d голосів. Зібрано:');
INSERT INTO `languages` VALUES('not_entered_number', 'en', 'You have entered no number in the box below:');
INSERT INTO `languages` VALUES('not_entered_number', 'ru', 'Вы ввели не число в следующее поле:');
INSERT INTO `languages` VALUES('not_entered_number', 'ua', 'Ви ввели не число в наступне поле:');
INSERT INTO `languages` VALUES('not_filled_fields', 'en', 'You have not filled in all fields!');
INSERT INTO `languages` VALUES('not_filled_fields', 'ru', 'Вы не заполнили все поля!');
INSERT INTO `languages` VALUES('not_filled_fields', 'ua', 'Ви не заповнили все поля!');
INSERT INTO `languages` VALUES('not_found_neighbot', 'en', 'Network Neighborhood not found.');
INSERT INTO `languages` VALUES('not_found_neighbot', 'ru', 'Сетевых соседей не обнаружено.');
INSERT INTO `languages` VALUES('not_found_neighbot', 'ua', 'Мережевих сусідів не виявлено.');
INSERT INTO `languages` VALUES('not_good', 'en', 'not good!');
INSERT INTO `languages` VALUES('not_good', 'ru', 'not good!');
INSERT INTO `languages` VALUES('not_good', 'ua', 'not good!');
INSERT INTO `languages` VALUES('not_name_sender', 'en', 'You have not specified the name of the sender!');
INSERT INTO `languages` VALUES('not_name_sender', 'ru', 'Вы не указали имя отправителя!');
INSERT INTO `languages` VALUES('not_name_sender', 'ua', 'Ви не вказали ім''я відправника!');
INSERT INTO `languages` VALUES('not_permission_prohibitions', 'en', 'You do not have permission to remove the prohibitions.');
INSERT INTO `languages` VALUES('not_permission_prohibitions', 'ru', 'У вас нет прав для удаления запретов.');
INSERT INTO `languages` VALUES('not_permission_prohibitions', 'ua', 'У вас немає прав для видалення заборон.');
INSERT INTO `languages` VALUES('not_pic', 'en', 'This is not a picture, access denied');
INSERT INTO `languages` VALUES('not_pic', 'ru', 'Это не картинка, доступ запрещен');
INSERT INTO `languages` VALUES('not_pic', 'ua', 'Це не зображення, доступ заборонений');
INSERT INTO `languages` VALUES('not_releases', 'en', 'You may not download releases on this tracker.');
INSERT INTO `languages` VALUES('not_releases', 'ru', 'Вы не загружали релизы на этот трекер.');
INSERT INTO `languages` VALUES('not_releases', 'ua', 'Ви не завантажували релізи на цей трекер.');
INSERT INTO `languages` VALUES('not_spec', 'en', 'You did not enter a user name and (or) password!');
INSERT INTO `languages` VALUES('not_spec', 'ru', 'Вы не указали имя пользователя и(или) пароль!');
INSERT INTO `languages` VALUES('not_spec', 'ua', 'Ви не вказали ім''я користувача і (або) пароль!');
INSERT INTO `languages` VALUES('not_subject', 'en', 'You did not specify a subject for your message!');
INSERT INTO `languages` VALUES('not_subject', 'ru', 'Вы не указали тему сообщения!');
INSERT INTO `languages` VALUES('not_subject', 'ua', 'Ви не вказали тему повідомлення!');
INSERT INTO `languages` VALUES('not_sysop', 'en', 'Access denied. You do not SYSOP');
INSERT INTO `languages` VALUES('not_sysop', 'ru', 'Доступ запрещен. Ты не SYSOP');
INSERT INTO `languages` VALUES('not_sysop', 'ua', 'Доступ заборонений. Ти не SYSOP');
INSERT INTO `languages` VALUES('not_text_message', 'en', 'You have not filled out the box with the text message!');
INSERT INTO `languages` VALUES('not_text_message', 'ru', 'Вы не заполнили поле с текстом сообщения!');
INSERT INTO `languages` VALUES('not_text_message', 'ua', 'Ви не заповнили поле з текстом повідомлення!');
INSERT INTO `languages` VALUES('not_try_remove', 'en', 'You are not trying to remove a bookmark!');
INSERT INTO `languages` VALUES('not_try_remove', 'ru', 'Вы пытаетесь удалить не свою закладку!');
INSERT INTO `languages` VALUES('not_try_remove', 'ua', 'Ви намагаєтеся видалити не свою закладку!');
INSERT INTO `languages` VALUES('not_yet_checked', 'en', '<span style="color: red;">This release <b>NOT</b> yet checked by moderator</span>');
INSERT INTO `languages` VALUES('not_yet_checked', 'ru', '<span style="color: red;">Этот релиз еще <b>НЕ</b> проверен модератором</span>');
INSERT INTO `languages` VALUES('not_yet_checked', 'ua', '<span style="color: red;">Цей реліз ще <b>НЕ</b> перевірений модератором</span>');
INSERT INTO `languages` VALUES('now', 'en', 'Now');
INSERT INTO `languages` VALUES('now', 'ru', 'сейчас');
INSERT INTO `languages` VALUES('now', 'ua', 'зараз');
INSERT INTO `languages` VALUES('now_i', 'en', 'Now you...');
INSERT INTO `languages` VALUES('now_i', 'ru', 'Сейчас Вы...');
INSERT INTO `languages` VALUES('now_i', 'ua', 'Зараз Ви...');
INSERT INTO `languages` VALUES('now_notified_newscomments', 'en', 'You have successfully subscribed for dispatch about comments to this news');
INSERT INTO `languages` VALUES('now_notified_newscomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этой новости');
INSERT INTO `languages` VALUES('now_notified_newscomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цієї новини');
INSERT INTO `languages` VALUES('now_notified_pagecomments', 'en', 'You have successfully subscribed for dispatch about comments to this page');
INSERT INTO `languages` VALUES('now_notified_pagecomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этой странице');
INSERT INTO `languages` VALUES('now_notified_pagecomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цієї сторінки');
INSERT INTO `languages` VALUES('now_notified_pollcomments', 'en', 'You have successfully subscribed for dispatch about comments to this interrogation');
INSERT INTO `languages` VALUES('now_notified_pollcomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этому опросу');
INSERT INTO `languages` VALUES('now_notified_pollcomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цього опитування');
INSERT INTO `languages` VALUES('now_notified_relcomments', 'en', 'You have successfully subscribed for dispatch about comments to this release');
INSERT INTO `languages` VALUES('now_notified_relcomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этому релизу');
INSERT INTO `languages` VALUES('now_notified_relcomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цього релізу');
INSERT INTO `languages` VALUES('now_notified_reqcomments', 'en', 'You have successfully subscribed for dispatch about comments to this inquiry');
INSERT INTO `languages` VALUES('now_notified_reqcomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этому запросу');
INSERT INTO `languages` VALUES('now_notified_reqcomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цього запиту');
INSERT INTO `languages` VALUES('now_notified_rgcomments', 'en', 'You have successfully subscribed for dispatch about comments to this release to group');
INSERT INTO `languages` VALUES('now_notified_rgcomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этой релиз группе');
INSERT INTO `languages` VALUES('now_notified_rgcomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цієї реліз групи');
INSERT INTO `languages` VALUES('now_notified_rgnewscomments', 'en', 'You have successfully subscribed for dispatch about comments to this news from group release');
INSERT INTO `languages` VALUES('now_notified_rgnewscomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этой новости от релиз группы');
INSERT INTO `languages` VALUES('now_notified_rgnewscomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цієї новини від реліз групи');
INSERT INTO `languages` VALUES('now_notified_usercomments', 'en', 'You have successfully subscribed for dispatch about comments to this user');
INSERT INTO `languages` VALUES('now_notified_usercomments', 'ru', 'Вы успешно подписались на рассылку о комментариях к этому пользователю');
INSERT INTO `languages` VALUES('now_notified_usercomments', 'ua', 'Ви успішно підписалися на розсилку про коментарі до цього користувачеві');
INSERT INTO `languages` VALUES('no_access', 'en', 'No Access.');
INSERT INTO `languages` VALUES('no_access', 'ru', 'Нет доступа.');
INSERT INTO `languages` VALUES('no_access', 'ua', 'Немає доступу.');
INSERT INTO `languages` VALUES('no_access_priv_rg', 'en', 'This is a private release of the group, and you are not a member of this group of subscribers.');
INSERT INTO `languages` VALUES('no_access_priv_rg', 'ru', 'Это приватная релиз группа, а вы не состоите в подписчиках этой группы');
INSERT INTO `languages` VALUES('no_access_priv_rg', 'ua', 'Це приватна реліз група, а ви не перебуваєте в передплатників цієї групи');
INSERT INTO `languages` VALUES('no_blocked', 'en', 'You have no enemies');
INSERT INTO `languages` VALUES('no_blocked', 'ru', 'У вас нет врагов');
INSERT INTO `languages` VALUES('no_blocked', 'ua', 'У вас немає ворогів');
INSERT INTO `languages` VALUES('no_choose', 'en', 'Not chosen');
INSERT INTO `languages` VALUES('no_choose', 'ru', 'Не выбрано');
INSERT INTO `languages` VALUES('no_choose', 'ua', 'Не обрано');
INSERT INTO `languages` VALUES('no_comments', 'en', 'No comments');
INSERT INTO `languages` VALUES('no_comments', 'ru', 'Нет комментариев');
INSERT INTO `languages` VALUES('no_comments', 'ua', 'Немає коментарів');
INSERT INTO `languages` VALUES('no_conf_usr', 'en', 'There is no confirmed users ...');
INSERT INTO `languages` VALUES('no_conf_usr', 'ru', 'Нет не подтвержденных пользователей ...');
INSERT INTO `languages` VALUES('no_conf_usr', 'ua', 'Немає не підтверджених користувачів...');
INSERT INTO `languages` VALUES('no_dchubs', 'en', 'For your subnet is not discovered supports DC-hubs, you can download only release using <a href="download.php?id=%s&amp;ok">.torrent</a> or <a href="download.php?id=%s&amp;ok&amp;magnet=1">magnet</a>');
INSERT INTO `languages` VALUES('no_dchubs', 'ru', 'Для вашей подсети не обнаружено поддерживаемых DC-хабов, вы можете скачать релиз только используя <a href="download.php?id=%s&amp;ok">.torrent файл</a> или <a href="download.php?id=%s&amp;ok&amp;magnet=1">magnet-ссылку</a>');
INSERT INTO `languages` VALUES('no_dchubs', 'ua', 'Для вашої підмережі не виявлено підтримуваних DC-хабів, ви можете завантажити реліз тільки використовуючи <a href="download.php?id=%s&amp;ok">.torrent файл</a> або <a href="download.php?id=%s&amp;ok&amp;magnet=1">magnet-посилання</a>');
INSERT INTO `languages` VALUES('no_discount', 'en', 'You do not have enough farmed to subscribe to the release of the group');
INSERT INTO `languages` VALUES('no_discount', 'ru', 'У вас не хватает скидки, чтобы подписаться на релизы этой группы');
INSERT INTO `languages` VALUES('no_discount', 'ua', 'У вас не вистачає знижки, щоб підписатися на релізи цієї групи');
INSERT INTO `languages` VALUES('no_discount_invite', 'en', 'You are missing a farmed, to create an invitation');
INSERT INTO `languages` VALUES('no_discount_invite', 'ru', 'У вас не хватает скидки, чтобы создать приглашение');
INSERT INTO `languages` VALUES('no_discount_invite', 'ua', 'У вас не вистачає знижки, щоб створити запрошення');
INSERT INTO `languages` VALUES('no_fields_blank', 'en', 'Do not leave any fields blank.');
INSERT INTO `languages` VALUES('no_fields_blank', 'ru', 'Не оставляйте пустых полей.');
INSERT INTO `languages` VALUES('no_fields_blank', 'ua', 'Не залишайте порожніх полів.');
INSERT INTO `languages` VALUES('no_formula', 'en', 'You have no pitching and no handed, so you get 0');
INSERT INTO `languages` VALUES('no_formula', 'ru', 'Вы еще ничего не качали и ничего не раздали, поэтому вы получаете 0');
INSERT INTO `languages` VALUES('no_formula', 'ua', 'Ви ще нічого не качали і нічого не роздали, тому ви отримуєте 0');
INSERT INTO `languages` VALUES('no_friend', 'en', 'This user has not confirmed your friendship, or it is not your friend.');
INSERT INTO `languages` VALUES('no_friend', 'ru', 'Этот человек не подтвердил вашу дружбу, либо вообще не является вашим другом');
INSERT INTO `languages` VALUES('no_friend', 'ua', 'Ця людина не підтвердив вашу дружбу, або взагалі не є вашим другом');
INSERT INTO `languages` VALUES('no_friends', 'en', 'You have no friends or no friends have been found');
INSERT INTO `languages` VALUES('no_friends', 'ru', 'У вас нет друзей, либо мы не нашли подходящих под критерии поиска');
INSERT INTO `languages` VALUES('no_friends', 'ua', 'У вас немає друзів, або ми не знайшли підходящих під критерії пошуку');
INSERT INTO `languages` VALUES('no_goods', 'en', 'You do not have privileges, but you <a href="donate.php?smszamok">can buy VIP status</a>');
INSERT INTO `languages` VALUES('no_goods', 'ru', 'У Вас нет привилегий, но Вы можете <a href="donate.php">купить VIP статус</a>');
INSERT INTO `languages` VALUES('no_goods', 'ua', 'У Вас немає привілеїв, але Ви можете <a href="donate.php">купити VIP статус</a>');
INSERT INTO `languages` VALUES('no_image', 'en', 'No logo');
INSERT INTO `languages` VALUES('no_image', 'ru', 'Нет логотипа');
INSERT INTO `languages` VALUES('no_image', 'ua', 'Немає логотипу');
INSERT INTO `languages` VALUES('no_messages', 'en', 'No messages');
INSERT INTO `languages` VALUES('no_messages', 'ru', 'Нет сообщений');
INSERT INTO `languages` VALUES('no_messages', 'ua', 'Немає повідомлень');
INSERT INTO `languages` VALUES('no_money_discount', 'en', 'You do not have enough "money"! Enter more farmed, or present a smaller amount');
INSERT INTO `languages` VALUES('no_money_discount', 'ru', 'У вас не хватает "денег"! Наберите побольше скидки, либо подарите меньшее количество');
INSERT INTO `languages` VALUES('no_money_discount', 'ua', 'У вас не вистачає "грошей"! Наберіть побільше знижки, або подаруйте меншу кількість');
INSERT INTO `languages` VALUES('no_money_ratingsum', 'en', 'You do not have enough "money"! Unfortunately, you are not respectable enough to give present');
INSERT INTO `languages` VALUES('no_money_ratingsum', 'ru', 'У вас не хватает "денег"! К сожалению, вы не настролько респектабельны, чтобы дарить такие подарки');
INSERT INTO `languages` VALUES('no_money_ratingsum', 'ua', 'У вас не вистачає "грошей"! На жаль, ви не настільки респектабельні, щоб дарувати такі подарунки');
INSERT INTO `languages` VALUES('no_money_torrent', 'en', 'You do not have enough "money"! Type rating to give this release, or select another release for a present');
INSERT INTO `languages` VALUES('no_money_torrent', 'ru', 'У вас не хватает "денег"! Наберите рейтинг, чтобы подарить этот релиз, либо выберите другой релиз для подарка');
INSERT INTO `languages` VALUES('no_money_torrent', 'ua', 'У вас не вистачає "грошей"! Наберіть рейтинг, щоб подарувати цей реліз, або виберіть інший реліз для подарунка');
INSERT INTO `languages` VALUES('no_need_seeding', 'en', 'No torrents without seeders');
INSERT INTO `languages` VALUES('no_need_seeding', 'ru', 'Все необходимые релизы сидируются:)');
INSERT INTO `languages` VALUES('no_need_seeding', 'ua', 'Всі необхідні релізи сідуються:)');
INSERT INTO `languages` VALUES('no_news', 'en', 'No news');
INSERT INTO `languages` VALUES('no_news', 'ru', 'Нет новостей');
INSERT INTO `languages` VALUES('no_news', 'ua', 'Немає новин');
INSERT INTO `languages` VALUES('no_notifs_yet', 'en', 'ou are not subscribed to any notice. <a href="mynotifs.php?settings">You can do so here</a>');
INSERT INTO `languages` VALUES('no_notifs_yet', 'ru', 'Вы еще не подписались на какие-либо уведомления. <a href="mynotifs.php?settings">Вы можете сделать это тут</a>');
INSERT INTO `languages` VALUES('no_notifs_yet', 'ua', 'Ви ще не підписалися на будь-які повідомлення. <a href="mynotifs.php?settings">Ви можете зробити це тут</a>');
INSERT INTO `languages` VALUES('no_offers', 'en', 'No suggestions');
INSERT INTO `languages` VALUES('no_offers', 'ru', 'Нет предложений');
INSERT INTO `languages` VALUES('no_offers', 'ua', 'Немає пропозицій');
INSERT INTO `languages` VALUES('no_online_users', 'en', 'No active users for the last 15 minutes.');
INSERT INTO `languages` VALUES('no_online_users', 'ru', 'Не было активных пользователей за последние 15 минут.');
INSERT INTO `languages` VALUES('no_online_users', 'ua', 'Не було активних користувачів за останні 15 хвилин.');
INSERT INTO `languages` VALUES('no_parent', 'en', 'No parent category');
INSERT INTO `languages` VALUES('no_parent', 'ru', 'Нет родителя');
INSERT INTO `languages` VALUES('no_parent', 'ua', 'Немає підгрупи');
INSERT INTO `languages` VALUES('no_pay', 'en', 'Free');
INSERT INTO `languages` VALUES('no_pay', 'ru', 'Бесплатная');
INSERT INTO `languages` VALUES('no_pay', 'ua', 'Безкоштовна');
INSERT INTO `languages` VALUES('no_polls', 'en', 'No polls');
INSERT INTO `languages` VALUES('no_polls', 'ru', 'Нет опросов');
INSERT INTO `languages` VALUES('no_polls', 'ua', 'Немає опитувань');
INSERT INTO `languages` VALUES('no_present_torrent', 'en', 'Release with this ID is not on our site or this release is presented');
INSERT INTO `languages` VALUES('no_present_torrent', 'ru', 'Релиза с этим номером нет на нашем сайте, либо этот релиз уже подарен');
INSERT INTO `languages` VALUES('no_present_torrent', 'ua', 'Релізу з цим номером немає на нашому сайті, або цей реліз вже подарований');
INSERT INTO `languages` VALUES('no_rating', 'en', 'We decided to go into minus?:) You do not have as many rankings, select a smaller value');
INSERT INTO `languages` VALUES('no_rating', 'ru', 'Решили уйти в минус?:) У Вас нет столько рейтинга, выберите меньшее значение');
INSERT INTO `languages` VALUES('no_rating', 'ua', 'Вирішили піти в мінус?:) У Вас немає стільки рейтингу, виберіть менше значення');
INSERT INTO `languages` VALUES('no_releases', 'en', 'No releases');
INSERT INTO `languages` VALUES('no_releases', 'ru', 'Релизов нет');
INSERT INTO `languages` VALUES('no_releases', 'ua', 'Релізів немає');
INSERT INTO `languages` VALUES('no_relgroup', 'en', 'Chosen release group does not exist on our site');
INSERT INTO `languages` VALUES('no_relgroup', 'ru', 'Выбранной вами релиз группы не существует на нашем сайте');
INSERT INTO `languages` VALUES('no_relgroup', 'ua', 'Обраної вами реліз групи не існує на нашому сайті');
INSERT INTO `languages` VALUES('no_relgroups', 'en', 'No release groups <a href="rgadmin.php?a=add">Add</a>');
INSERT INTO `languages` VALUES('no_relgroups', 'ru', 'Нет релиз групп <a href="rgadmin.php?a=add">Добавить</a>');
INSERT INTO `languages` VALUES('no_relgroups', 'ua', 'Немає реліз груп <a href="rgadmin.php?a=add">Додати</a>');
INSERT INTO `languages` VALUES('no_relgroup_owner', 'en', 'You are not the owner of the release of the band. Access denied');
INSERT INTO `languages` VALUES('no_relgroup_owner', 'ru', 'Вы не являетесь владельцем данной релиз группы. Доступ запрещен');
INSERT INTO `languages` VALUES('no_relgroup_owner', 'ua', 'Ви не є власником цієї реліз групи. Доступ заборонений');
INSERT INTO `languages` VALUES('no_seeds', 'en', 'No seeds');
INSERT INTO `languages` VALUES('no_seeds', 'ru', 'Без сидов');
INSERT INTO `languages` VALUES('no_seeds', 'ua', 'Без сідів');
INSERT INTO `languages` VALUES('no_selection', 'en', 'No selection');
INSERT INTO `languages` VALUES('no_selection', 'ru', 'Ничего не выбрано');
INSERT INTO `languages` VALUES('no_selection', 'ua', 'Нічого не вибрано');
INSERT INTO `languages` VALUES('no_subject', 'en', 'No subject');
INSERT INTO `languages` VALUES('no_subject', 'ru', 'Без темы');
INSERT INTO `languages` VALUES('no_subject', 'ua', 'Без теми');
INSERT INTO `languages` VALUES('no_tiger', 'en', 'TIGER-hash is not found, this release can download protocol DirectConnect');
INSERT INTO `languages` VALUES('no_tiger', 'ru', 'TIGER-хеша не обнаружено, этот релиз нельзя скачать по протоколу DirectConnect');
INSERT INTO `languages` VALUES('no_tiger', 'ua', 'TIGER-хешу не виявлено, цей реліз не можна скачати за протоколом DirectConnect');
INSERT INTO `languages` VALUES('no_torrents', 'en', 'No torrents');
INSERT INTO `languages` VALUES('no_torrents', 'ru', 'Нет релизов');
INSERT INTO `languages` VALUES('no_torrents', 'ua', 'Немає релізів');
INSERT INTO `languages` VALUES('no_torrent_with_such_id', 'en', 'No torrent with such ID.');
INSERT INTO `languages` VALUES('no_torrent_with_such_id', 'ru', 'Нет торрента с таким ID.');
INSERT INTO `languages` VALUES('no_torrent_with_such_id', 'ua', 'Немає торрента з таким ID.');
INSERT INTO `languages` VALUES('no_type', 'en', 'Incorrectly selected type of present');
INSERT INTO `languages` VALUES('no_type', 'ru', 'Неверно выбран тип подарка');
INSERT INTO `languages` VALUES('no_type', 'ua', 'Невірно вибрано тип подарунка');
INSERT INTO `languages` VALUES('no_user', 'en', 'This user does not exist');
INSERT INTO `languages` VALUES('no_user', 'ru', 'Такого пользователя не существует');
INSERT INTO `languages` VALUES('no_user', 'ua', 'Такого користувача не існує');
INSERT INTO `languages` VALUES('no_users', 'en', 'In this release group no subscribers');
INSERT INTO `languages` VALUES('no_users', 'ru', 'У этой релиз-группы нет подписчиков');
INSERT INTO `languages` VALUES('no_users', 'ua', 'У цієї реліз-групи немає передплатників');
INSERT INTO `languages` VALUES('no_user_id', 'en', 'No user with this ID');
INSERT INTO `languages` VALUES('no_user_id', 'ru', 'Нет пользователя с таким ID');
INSERT INTO `languages` VALUES('no_user_id', 'ua', 'Немає користувач з ID');
INSERT INTO `languages` VALUES('no_value', 'en', 'Not specified one of the mandatory values of the form');
INSERT INTO `languages` VALUES('no_value', 'ru', 'Не указано одно из обязательных значений формы');
INSERT INTO `languages` VALUES('no_value', 'ua', 'Не вказано одне з обов''язкових значень форми');
INSERT INTO `languages` VALUES('no_votes', 'en', 'No votes');
INSERT INTO `languages` VALUES('no_votes', 'ru', 'Нет голосов');
INSERT INTO `languages` VALUES('no_votes', 'ua', 'Немає голосів');
INSERT INTO `languages` VALUES('number_release', 'en', 'ID release:');
INSERT INTO `languages` VALUES('number_release', 'ru', 'ID релиза:');
INSERT INTO `languages` VALUES('number_release', 'ua', 'ID релізу:');
INSERT INTO `languages` VALUES('num_checked', 'en', 'Total since the last time the script checks the remote peers, he was executed %s times');
INSERT INTO `languages` VALUES('num_checked', 'ru', 'Всего с момента последнего запуска скрипта проверки удаленных пиров, он был выполнен %s раз');
INSERT INTO `languages` VALUES('num_checked', 'ua', 'Всього з моменту останнього запуску скрипта перевірки віддалених пірів, він був виконаний %s разів');
INSERT INTO `languages` VALUES('num_cleaned', 'en', 'Total since the last time the script treatment facilities cleaned %s times');
INSERT INTO `languages` VALUES('num_cleaned', 'ru', 'Всего с момента последнего запуска скрипта очистки база очищена %s раз');
INSERT INTO `languages` VALUES('num_cleaned', 'ua', 'Всього з моменту останнього запуску скрипта очищення база очищена %s разів');
INSERT INTO `languages` VALUES('offers', 'en', 'Suggestions');
INSERT INTO `languages` VALUES('offers', 'ru', 'Предложения');
INSERT INTO `languages` VALUES('offers', 'ua', 'Пропозиції');
INSERT INTO `languages` VALUES('official', 'en', 'Official');
INSERT INTO `languages` VALUES('official', 'ru', 'Официальный');
INSERT INTO `languages` VALUES('official', 'ua', 'Офіційний');
INSERT INTO `languages` VALUES('offline', 'en', 'offline');
INSERT INTO `languages` VALUES('offline', 'ru', 'оффлайне');
INSERT INTO `languages` VALUES('offline', 'ua', 'офлайні');
INSERT INTO `languages` VALUES('old_polls', 'en', 'Old polls');
INSERT INTO `languages` VALUES('old_polls', 'ru', 'Прошлые опросы');
INSERT INTO `languages` VALUES('old_polls', 'ua', 'Минулі опитування');
INSERT INTO `languages` VALUES('once', 'en', 'rated once every');
INSERT INTO `languages` VALUES('once', 'ru', 'рейтинга раз в');
INSERT INTO `languages` VALUES('once', 'ua', 'рейтингу раз на');
INSERT INTO `languages` VALUES('online', 'en', 'Online');
INSERT INTO `languages` VALUES('online', 'ru', 'В сети');
INSERT INTO `languages` VALUES('online', 'ua', 'У мережі');
INSERT INTO `languages` VALUES('online_users', 'en', 'Total online');
INSERT INTO `languages` VALUES('online_users', 'ru', 'Всего Онлайн');
INSERT INTO `languages` VALUES('online_users', 'ua', 'Всього Онлайн');
INSERT INTO `languages` VALUES('only_dead', 'en', 'Only dead');
INSERT INTO `languages` VALUES('only_dead', 'ru', 'Только мертвые');
INSERT INTO `languages` VALUES('only_dead', 'ua', 'Тільки мертві');
INSERT INTO `languages` VALUES('only_invites', 'en', 'May subscribe by invitation only');
INSERT INTO `languages` VALUES('only_invites', 'ru', 'Возможно подписаться <b>только</b> по приглашению');
INSERT INTO `languages` VALUES('only_invites', 'ua', 'Можливо підписатися <b>тільки</b> по запрошенню');
INSERT INTO `languages` VALUES('only_invites_enabled', 'en', 'Subscribe to this release the group can be accomplished only by invitation');
INSERT INTO `languages` VALUES('only_invites_enabled', 'ru', 'Подписка на эту релиз группу может быть выполнена только с помощью приглашения');
INSERT INTO `languages` VALUES('only_invites_enabled', 'ua', 'Підписка на цю реліз групу може бути виконана тільки за допомогою запрошення');
INSERT INTO `languages` VALUES('only_votes', 'en', 'only %d votes');
INSERT INTO `languages` VALUES('only_votes', 'ru', 'только %d голосов');
INSERT INTO `languages` VALUES('only_votes', 'ua', 'тільки %d голосів');
INSERT INTO `languages` VALUES('Open information', 'en', 'Open information');
INSERT INTO `languages` VALUES('Open information', 'ru', 'Показать информацию');
INSERT INTO `languages` VALUES('open information', 'ua', 'Показати інформацію');
INSERT INTO `languages` VALUES('open_list', 'en', 'Open list');
INSERT INTO `languages` VALUES('open_list', 'ru', 'Посмотреть список');
INSERT INTO `languages` VALUES('open_list', 'ua', 'Переглянути список');
INSERT INTO `languages` VALUES('option', 'en', 'Select');
INSERT INTO `languages` VALUES('option', 'ru', 'Выберите');
INSERT INTO `languages` VALUES('option', 'ua', 'Виберіть');
INSERT INTO `languages` VALUES('optional', 'en', 'Optional.');
INSERT INTO `languages` VALUES('optional', 'ru', 'Не обязательно.');
INSERT INTO `languages` VALUES('optional', 'ua', 'Не обов''язково.');
INSERT INTO `languages` VALUES('or', 'en', 'or');
INSERT INTO `languages` VALUES('or', 'ru', 'или');
INSERT INTO `languages` VALUES('or', 'ua', 'або');
INSERT INTO `languages` VALUES('order', 'en', 'Order');
INSERT INTO `languages` VALUES('order', 'ru', 'Порядок');
INSERT INTO `languages` VALUES('order', 'ua', 'Порядок');
INSERT INTO `languages` VALUES('other', 'en', 'Other');
INSERT INTO `languages` VALUES('other', 'ru', 'Прочее');
INSERT INTO `languages` VALUES('other', 'ua', 'Інше');
INSERT INTO `languages` VALUES('our_films', 'en', 'Our releases');
INSERT INTO `languages` VALUES('our_films', 'ru', 'Наши релизы');
INSERT INTO `languages` VALUES('our_films', 'ua', 'Наші релізи');
INSERT INTO `languages` VALUES('outbox', 'en', 'Sent');
INSERT INTO `languages` VALUES('outbox', 'ru', 'Отправленные');
INSERT INTO `languages` VALUES('outbox', 'ua', 'Надіслані');
INSERT INTO `languages` VALUES('outbox_m', 'en', 'Outgoing messages');
INSERT INTO `languages` VALUES('outbox_m', 'ru', 'Исходящие ЛС');
INSERT INTO `languages` VALUES('outbox_m', 'ua', 'Вихідні ПП');
INSERT INTO `languages` VALUES('Override current data', 'en', 'Override current data');
INSERT INTO `languages` VALUES('Override current data', 'ru', 'Заменить существующие значения');
INSERT INTO `languages` VALUES('override current data', 'ua', 'Замінити існуючі значення');
INSERT INTO `languages` VALUES('owners', 'en', 'Owners');
INSERT INTO `languages` VALUES('owners', 'ru', 'Владельцы');
INSERT INTO `languages` VALUES('owners', 'ua', 'Власники');
INSERT INTO `languages` VALUES('owner_release', 'en', 'Owner release');
INSERT INTO `languages` VALUES('owner_release', 'ru', 'Владелец релиза');
INSERT INTO `languages` VALUES('owner_release', 'ua', 'Власник релізу');
INSERT INTO `languages` VALUES('own_reason', 'en', 'My reason:');
INSERT INTO `languages` VALUES('own_reason', 'ru', 'Свое:');
INSERT INTO `languages` VALUES('own_reason', 'ua', 'Своє:');
INSERT INTO `languages` VALUES('Pagecomments', 'en', 'Pagecomments');
INSERT INTO `languages` VALUES('Pagecomments', 'ru', 'Комм.страниц');
INSERT INTO `languages` VALUES('pagecomments', 'ua', 'Ком. сторінок');
INSERT INTO `languages` VALUES('pager_text', 'en', 'Total: %s to %s  page to %s page (now see %s - %s)');
INSERT INTO `languages` VALUES('pager_text', 'ru', 'Всего: %s на %s стр. по %s на стр. (сейчас просматриваете %s-%s)');
INSERT INTO `languages` VALUES('pager_text', 'ua', 'Усього: %s на %s стор. по %s на стор. (зараз переглядаєте %s-%s)');
INSERT INTO `languages` VALUES('pages', 'en', 'Pages');
INSERT INTO `languages` VALUES('pages', 'ru', 'Страницы');
INSERT INTO `languages` VALUES('pages', 'ua', 'Сторінки');
INSERT INTO `languages` VALUES('Pages affected', 'en', 'Pages affected');
INSERT INTO `languages` VALUES('Pages affected', 'ru', 'Затронутые страницы');
INSERT INTO `languages` VALUES('pages affected', 'ua', 'Задіяні сторінки');
INSERT INTO `languages` VALUES('pagescategory', 'en', 'Categories for pages');
INSERT INTO `languages` VALUES('pagescategory', 'ru', 'Категории страниц');
INSERT INTO `languages` VALUES('pagescategory', 'ua', 'Категорії сторінок');
INSERT INTO `languages` VALUES('pagescategory_admin', 'en', 'Management categories pages');
INSERT INTO `languages` VALUES('pagescategory_admin', 'ru', 'Админка категорий страниц');
INSERT INTO `languages` VALUES('pagescategory_admin', 'ua', 'Адмінка категорій сторінок');
INSERT INTO `languages` VALUES('pagescategory_success_delete', 'en', 'The category successfully deteted');
INSERT INTO `languages` VALUES('pagescategory_success_delete', 'ru', 'Категория успешно удалена');
INSERT INTO `languages` VALUES('pagescategory_success_delete', 'ua', 'Категорія успішно видалена');
INSERT INTO `languages` VALUES('pagescategory_success_edit', 'en', 'The category successfully edited');
INSERT INTO `languages` VALUES('pagescategory_success_edit', 'ru', 'Категория успешно отредактирована');
INSERT INTO `languages` VALUES('pagescategory_success_edit', 'ua', 'Категорія успішно відкоригована');
INSERT INTO `languages` VALUES('page_generated', 'en', 'Page generated in %f seconds with %d queries (%s%% PHP / %s%% MySQL)');
INSERT INTO `languages` VALUES('page_generated', 'ru', 'Страница сгенерирована за %f секунд. Выполнено %d запросов (%s%% PHP / %s%% MySQL);');
INSERT INTO `languages` VALUES('page_generated', 'ua', 'Сторінка згенерована за %f секунд. Виконано %d запитів (%s%% PHP / %s%% MySQL);');
INSERT INTO `languages` VALUES('page_pay', 'en', 'Pages payment<br /><small>(If empty, the "currency" - Farmed)<br />If the value is blank, the group will automatically become Pay.</small>');
INSERT INTO `languages` VALUES('page_pay', 'ru', 'Страница оплаты<br /><small>(Если пусто, то "валюта" - скидка)<br />Если значение заполнено, группа автоматически становится платной</small>');
INSERT INTO `languages` VALUES('page_pay', 'ua', 'Сторінка оплати<br /><small>(Якщо порожньо, то "валюта " - знижка)<br />Якщо значення заповнено, група автоматично стає платною</small>');
INSERT INTO `languages` VALUES('page_title', 'en', 'Release Templates administration');
INSERT INTO `languages` VALUES('page_title', 'ru', 'Администрирование шаблонов описаний');
INSERT INTO `languages` VALUES('page_title', 'ua', 'Адміністрування шаблонів описів');
INSERT INTO `languages` VALUES('panel_admin', 'en', 'Administrator control panel');
INSERT INTO `languages` VALUES('panel_admin', 'ru', 'Панель Администратора');
INSERT INTO `languages` VALUES('panel_admin', 'ua', 'Панель Адміністратора');
INSERT INTO `languages` VALUES('panel_name', 'en', 'Management retrackers');
INSERT INTO `languages` VALUES('panel_name', 'ru', 'Администрирование опции ретрекера');
INSERT INTO `languages` VALUES('panel_name', 'ua', 'Адміністрування опції ретрекера');
INSERT INTO `languages` VALUES('panel_notice', 'en', 'This page allows you to manage retrackers<br />Warning, this <b>RETRACKERS</b>,and they will be added to the torrent file only <b> when user downloading torrent! You <b>cant</b> access any statistics of this trackers.<br /><b><span style="text-decoration: underline;">Subnet Mask can be filled only 1 IP address, or a mask in the format of CIDR </ u> </ b> <br />Leave the field blank if you wish to register retracker for ALL users.</span></b></b>');
INSERT INTO `languages` VALUES('panel_notice', 'ru', 'Данная страница служит для управления опцией ретрекера<br />');
INSERT INTO `languages` VALUES('panel_notice', 'ua', 'Дана сторінка служить для управління опцією ретрекера<br />');
INSERT INTO `languages` VALUES('Parameter', 'en', 'Parameter');
INSERT INTO `languages` VALUES('Parameter', 'ru', 'Параметр');
INSERT INTO `languages` VALUES('parameter', 'ua', 'Параметр');
INSERT INTO `languages` VALUES('parent', 'en', 'Parent categoryt');
INSERT INTO `languages` VALUES('parent', 'ru', 'Родитель');
INSERT INTO `languages` VALUES('parent', 'ua', 'Підгрупа');
INSERT INTO `languages` VALUES('password', 'en', 'Password');
INSERT INTO `languages` VALUES('password', 'ru', 'Пароль');
INSERT INTO `languages` VALUES('password', 'ua', 'Пароль');
INSERT INTO `languages` VALUES('passwordadmin', 'en', 'Change user password');
INSERT INTO `languages` VALUES('passwordadmin', 'ru', 'Сменить пароль юзверю');
INSERT INTO `languages` VALUES('passwordadmin', 'ua', 'Змінити пароль юзеру');
INSERT INTO `languages` VALUES('password_mismatch', 'en', 'Passwords do not match.');
INSERT INTO `languages` VALUES('password_mismatch', 'ru', 'Пароли не совпадают.');
INSERT INTO `languages` VALUES('password_mismatch', 'ua', 'Паролі не збігаються.');
INSERT INTO `languages` VALUES('path', 'en', 'Path');
INSERT INTO `languages` VALUES('path', 'ru', 'Путь');
INSERT INTO `languages` VALUES('path', 'ua', 'Шлях');
INSERT INTO `languages` VALUES('pay_required', 'en', 'Pay');
INSERT INTO `languages` VALUES('pay_required', 'ru', 'Платная');
INSERT INTO `languages` VALUES('pay_required', 'ua', 'Платна');
INSERT INTO `languages` VALUES('peers_l', 'en', 'peers');
INSERT INTO `languages` VALUES('peers_l', 'ru', 'пиров');
INSERT INTO `languages` VALUES('peers_l', 'ua', 'пірів');
INSERT INTO `languages` VALUES('Pending', 'en', 'Pending');
INSERT INTO `languages` VALUES('Pending', 'ru', 'Ожидает подтверждения');
INSERT INTO `languages` VALUES('pending', 'ua', 'Очікує підтвердження');
INSERT INTO `languages` VALUES('permission_denied', 'en', 'Access denied');
INSERT INTO `languages` VALUES('permission_denied', 'ru', 'В доступе отказано');
INSERT INTO `languages` VALUES('permission_denied', 'ua', 'У доступі відмовлено');
INSERT INTO `languages` VALUES('personal_lists', 'en', 'Personal list');
INSERT INTO `languages` VALUES('personal_lists', 'ru', 'Мои друзья');
INSERT INTO `languages` VALUES('personal_lists', 'ua', 'Мої друзі');
INSERT INTO `languages` VALUES('places', 'en', 'Places on tracker');
INSERT INTO `languages` VALUES('places', 'ru', 'Мест на трекере');
INSERT INTO `languages` VALUES('places', 'ua', 'Місць на трекері');
INSERT INTO `languages` VALUES('please_register', 'en', 'You have not registered on this site yet, or this combination of e-mail and password is invalid. You can <a href="%s">Register now</a> or <a href="denied:javascript:history.go(-1);">Try again</a>.');
INSERT INTO `languages` VALUES('please_register', 'ru', 'Вы еще не зарегестированы, либо такая комбинация никнейма(email) и пароля неверна. Вы можете <a href="%s">Зарегестироваться сейчас</a> или <a href="denied:javascript:history.go(-1);">Попытаться зайти еще раз</a>.');
INSERT INTO `languages` VALUES('please_register', 'ua', 'Ви ще не зареєстровані, або така комбінація нікнейму (email) і пароля невірна. Ви можете <a href="%s">Зареєструватися зараз</a> або <a href="denied:javascript:history.go(-1);">Спробувати зайти ще раз</a>.');
INSERT INTO `languages` VALUES('pm', 'en', 'PM');
INSERT INTO `languages` VALUES('pm', 'ru', 'ЛС');
INSERT INTO `languages` VALUES('pm', 'ua', 'ПП');
INSERT INTO `languages` VALUES('pm_succ_send', 'en', 'Message was successfuly sent');
INSERT INTO `languages` VALUES('pm_succ_send', 'ru', 'Сообщение успешно отправлено');
INSERT INTO `languages` VALUES('pm_succ_send', 'ua', 'Повідомлення успішно відправлено');
INSERT INTO `languages` VALUES('poll', 'en', 'Poll');
INSERT INTO `languages` VALUES('poll', 'ru', 'Опрос');
INSERT INTO `languages` VALUES('poll', 'ua', 'Опитування');
INSERT INTO `languages` VALUES('Pollcomments', 'en', 'Pollcomments');
INSERT INTO `languages` VALUES('Pollcomments', 'ru', 'Комм.опросов');
INSERT INTO `languages` VALUES('pollcomments', 'ua', 'Ком. опитувань');
INSERT INTO `languages` VALUES('pollsadmin', 'en', 'Polls administration');
INSERT INTO `languages` VALUES('pollsadmin', 'ru', 'Опросы');
INSERT INTO `languages` VALUES('pollsadmin', 'ua', 'Опитування');
INSERT INTO `languages` VALUES('port_number', 'en', 'Port number:');
INSERT INTO `languages` VALUES('port_number', 'ru', 'Номер порта:');
INSERT INTO `languages` VALUES('port_number', 'ua', 'Номер порту:');
INSERT INTO `languages` VALUES('port_open', 'en', 'NAT');
INSERT INTO `languages` VALUES('port_open', 'ru', 'NAT');
INSERT INTO `languages` VALUES('port_open', 'ua', 'NAT');
INSERT INTO `languages` VALUES('Position', 'en', 'Position');
INSERT INTO `languages` VALUES('Position', 'ru', 'Позиция');
INSERT INTO `languages` VALUES('position', 'ua', 'Позиція');
INSERT INTO `languages` VALUES('Post new topic', 'en', 'Post new topic');
INSERT INTO `languages` VALUES('Post new topic', 'ru', 'Создать тему');
INSERT INTO `languages` VALUES('post new topic', 'ua', 'Створити тему');
INSERT INTO `languages` VALUES('posted', 'en', 'Posted:');
INSERT INTO `languages` VALUES('posted', 'ru', 'Выложил:');
INSERT INTO `languages` VALUES('posted', 'ua', 'Виклав:');
INSERT INTO `languages` VALUES('poster', 'en', 'To look a poster');
INSERT INTO `languages` VALUES('poster', 'ru', 'Посмотреть постер');
INSERT INTO `languages` VALUES('poster', 'ua', 'Переглянути постер');
INSERT INTO `languages` VALUES('Posts', 'en', 'Posts');
INSERT INTO `languages` VALUES('Posts', 'ru', 'Ответов');
INSERT INTO `languages` VALUES('posts', 'ua', 'Відповідей');
INSERT INTO `languages` VALUES('present', 'en', 'Present');
INSERT INTO `languages` VALUES('present', 'ru', 'Подарки');
INSERT INTO `languages` VALUES('present', 'ua', 'Подарунки');
INSERT INTO `languages` VALUES('Present type', 'en', 'Present type');
INSERT INTO `languages` VALUES('Present type', 'ru', 'Тип подарка');
INSERT INTO `languages` VALUES('present type', 'ua', 'Тип подарка');
INSERT INTO `languages` VALUES('presented', 'en', 'You friend %s just gave you %s! Congratulations! You can also <a href="present.php?id=%s">present anything to your friend</a>');
INSERT INTO `languages` VALUES('presented', 'ru', 'Ваш друг %s только что подарил вам %s! Поздравляем! Вы также можете <a href="present.php?id=%s">подарить что-либо вашему другу</a>');
INSERT INTO `languages` VALUES('presented', 'ua', 'Ваш друг %s тільки що подарував вам %s! Вітаємо! Ви також можете <a href="present.php?id=%s">подарувати що-небудь вашому другові</a>');
INSERT INTO `languages` VALUES('presented_discount', 'en', '%s farmed');
INSERT INTO `languages` VALUES('presented_discount', 'ru', '%s скидки');
INSERT INTO `languages` VALUES('presented_discount', 'ua', '%s знижки');
INSERT INTO `languages` VALUES('presented_ratingsum', 'en', 'part of their rating (%s respekt)');
INSERT INTO `languages` VALUES('presented_ratingsum', 'ru', 'часть своего рейтинга (%s респектов)');
INSERT INTO `languages` VALUES('presented_ratingsum', 'ua', 'частину свого рейтингу (%s респектів)');
INSERT INTO `languages` VALUES('presented_torrent', 'en', 'release "%s", now that this release will show an icon <img src="pic/presents/present.gif" alt="image" />, to remind you of your friends');
INSERT INTO `languages` VALUES('presented_torrent', 'ru', 'релиз под названием "%s", теперь у этого релиза будет отображаться иконка <img src="pic/presents/present.gif" alt="image" />, чтобы напомнить вам о ваших друзьях');
INSERT INTO `languages` VALUES('presented_torrent', 'ua', 'реліз під назвою "%s", тепер у цього релізу відображатиметься ікона <img src="pic/presents/present.gif" alt="image" />, щоб нагадати вам про ваших друзів');
INSERT INTO `languages` VALUES('presents', 'en', 'Presents');
INSERT INTO `languages` VALUES('presents', 'ru', 'Подарки');
INSERT INTO `languages` VALUES('presents', 'ua', 'Подарунки');
INSERT INTO `languages` VALUES('present_bonus', 'en', 'Present farming');
INSERT INTO `languages` VALUES('present_bonus', 'ru', 'Подарить скидку');
INSERT INTO `languages` VALUES('present_bonus', 'ua', 'Подарувати знижку');
INSERT INTO `languages` VALUES('present_discount', 'en', 'Present farmed to a friend');
INSERT INTO `languages` VALUES('present_discount', 'ru', 'Подарить скидку другу');
INSERT INTO `languages` VALUES('present_discount', 'ua', 'Подарувати знижку другу');
INSERT INTO `languages` VALUES('present_for_you', 'en', 'he release you somebody present, the rating is not considered!');
INSERT INTO `languages` VALUES('present_for_you', 'ru', 'Этот релиз вам кто-то подарил, рейтинг не учитывается!');
INSERT INTO `languages` VALUES('present_for_you', 'ua', 'Цей реліз вам хтось подарував, рейтинг не враховується!');
INSERT INTO `languages` VALUES('present_ratingsum', 'en', 'Present rating to a friend');
INSERT INTO `languages` VALUES('present_ratingsum', 'ru', 'Подарить рейтинг другу');
INSERT INTO `languages` VALUES('present_ratingsum', 'ua', 'Подарувати рейтинг другу');
INSERT INTO `languages` VALUES('present_ratio', 'en', 'Present rating');
INSERT INTO `languages` VALUES('present_ratio', 'ru', 'Подарить рейтинг');
INSERT INTO `languages` VALUES('present_ratio', 'ua', 'Подарувати рейтинг');
INSERT INTO `languages` VALUES('present_torrent', 'en', 'Present Torrent!');
INSERT INTO `languages` VALUES('present_torrent', 'ru', 'Подарить торрент!');
INSERT INTO `languages` VALUES('present_torrent', 'ua', 'Подарувати торрент!');
INSERT INTO `languages` VALUES('present_torrents', 'en', 'Present torrents');
INSERT INTO `languages` VALUES('present_torrents', 'ru', 'Подарить торрент');
INSERT INTO `languages` VALUES('present_torrents', 'ua', 'Подарувати торрент');
INSERT INTO `languages` VALUES('present_to_friend', 'en', '<img src="pic/presents/present.gif" title="Present torrent to friend!" alt="image" style="border: 0px;" />&nbsp; Present torrent to friend!');
INSERT INTO `languages` VALUES('present_to_friend', 'ru', '<img src="pic/presents/present.gif" title="Подарить торрент другу!" alt="image" style="border: 0px;" />&nbsp; Подарить торрент другу!');
INSERT INTO `languages` VALUES('present_to_friend', 'ua', '<img src="pic/presents/present.gif" title="Подарувати торрент другу!" alt="image" style="border: 0px;" />&nbsp; Подарить торрент другу!');
INSERT INTO `languages` VALUES('present_upload', 'en', 'Present apload');
INSERT INTO `languages` VALUES('present_upload', 'ru', 'Подарить аплоад');
INSERT INTO `languages` VALUES('present_upload', 'ua', 'Подарувати аплоад');
INSERT INTO `languages` VALUES('prev', 'en', 'Prev');
INSERT INTO `languages` VALUES('prev', 'ru', 'Назад');
INSERT INTO `languages` VALUES('prev', 'ua', 'Назад');
INSERT INTO `languages` VALUES('preview', 'en', 'Preview');
INSERT INTO `languages` VALUES('preview', 'ru', 'Просмотреть');
INSERT INTO `languages` VALUES('preview', 'ua', 'Переглянути');
INSERT INTO `languages` VALUES('privacy_highest', 'en', 'Highest. Your profile is totally closed from users, except your friends');
INSERT INTO `languages` VALUES('privacy_highest', 'ru', 'Высочайший. Ваш профиль полностью закрыт от пользователей, кроме ваших друзей');
INSERT INTO `languages` VALUES('privacy_highest', 'ua', 'Найвищий. Ваш профіль повністю закритий від користувачів, крім ваших друзів');
INSERT INTO `languages` VALUES('privacy_level_error', 'en', 'This user uses privacy level, you need to <a href="%s">Become friend of %s</a> to view this page');
INSERT INTO `languages` VALUES('privacy_level_error', 'ru', 'Этот пользователь использует приватность, вы должны <a href="%s">Стать другом %s</a>, чтобы смотреть эту страницу');
INSERT INTO `languages` VALUES('privacy_level_error', 'ua', 'Цей користувач використовує приватність, ви повинні <a href="%s">Стати другом %s</a>, щоб дивитися цю сторінку');
INSERT INTO `languages` VALUES('privacy_normal', 'en', 'Normal. Your profile and stats can be viewed by any registered member');
INSERT INTO `languages` VALUES('privacy_normal', 'ru', 'Нормальный. Ваш профиль и статистику может просматривать любой пользователь');
INSERT INTO `languages` VALUES('privacy_normal', 'ua', 'Нормальний. Ваш профіль і статистику може переглядати будь-який користувач');
INSERT INTO `languages` VALUES('privacy_strong', 'en', 'Strong. Only your profile (NOT STATS) can be viewed by any registered member');
INSERT INTO `languages` VALUES('privacy_strong', 'ru', 'Усиленный. Ваш профиль (НЕ СТАТИСТИКУ) может просматривать любой пользователь');
INSERT INTO `languages` VALUES('privacy_strong', 'ua', 'Посилений. Ваш профіль (НЕ СТАТИСТИКУ) може переглядати будь-який користувач');
INSERT INTO `languages` VALUES('private', 'en', 'Private (Group Closed)');
INSERT INTO `languages` VALUES('private', 'ru', 'Приватная (Группа Закрытая)');
INSERT INTO `languages` VALUES('private', 'ua', 'Приватна (Група Закрита)');
INSERT INTO `languages` VALUES('private_group_friend_subscribe', 'en', 'This is a private group, you can subscribe to the releases, only received an invitation friend');
INSERT INTO `languages` VALUES('private_group_friend_subscribe', 'ru', 'Это приватная группа, вы можете подписаться на релизы, только получив приглашение друга');
INSERT INTO `languages` VALUES('private_group_friend_subscribe', 'ua', 'Це приватна група, ви можете підписатися на релізи, тільки отримавши запрошення одного');
INSERT INTO `languages` VALUES('private_release_access_denied', 'en', 'This release <strong> private </ strong> group %s, to download this release you must be in subscribers in this group. Get a subscription can be <a href="relgroups.php"> On Release Group</a></strong>');
INSERT INTO `languages` VALUES('private_release_access_denied', 'ru', 'Это релиз <strong>приватной</strong> группы %s, чтобы скачать этот релиз вы должны состоять в подписчиках этой группы. Получить подписку можно <a href="relgroups.php">На странице Релиз-Групп</a>');
INSERT INTO `languages` VALUES('private_release_access_denied', 'ua', 'Це реліз <strong>приватної</strong> групи %s, щоб скачати цей реліз ви повинні складатися в передплатників цієї групи. Отримати передплату можна <a href="relgroups.php">На сторінці Реліз-Груп</a>');
INSERT INTO `languages` VALUES('problem_activation', 'en', 'The problem with the activation');
INSERT INTO `languages` VALUES('problem_activation', 'ru', 'Проблема с активацией');
INSERT INTO `languages` VALUES('problem_activation', 'ua', 'Проблема з активацією');
INSERT INTO `languages` VALUES('profile', 'en', 'Profile');
INSERT INTO `languages` VALUES('profile', 'ru', 'Профиль');
INSERT INTO `languages` VALUES('profile', 'ua', 'Профіль');
INSERT INTO `languages` VALUES('pr_now', 'en', 'Present now!');
INSERT INTO `languages` VALUES('pr_now', 'ru', 'Подарить сейчас');
INSERT INTO `languages` VALUES('pr_now', 'ua', 'Подарувати зараз');
INSERT INTO `languages` VALUES('quest_realeases', 'en', 'Questions about the releases');
INSERT INTO `languages` VALUES('quest_realeases', 'ru', 'Вопросы о раздачах');
INSERT INTO `languages` VALUES('quest_realeases', 'ua', 'Питання про роздачах');
INSERT INTO `languages` VALUES('quote', 'en', 'Quote');
INSERT INTO `languages` VALUES('quote', 'ru', 'Цитировать');
INSERT INTO `languages` VALUES('quote', 'ua', 'Цитувати');
INSERT INTO `languages` VALUES('quotes', 'en', 'Favorite Quotes:');
INSERT INTO `languages` VALUES('quotes', 'ru', 'Любимые цитаты:');
INSERT INTO `languages` VALUES('quotes', 'ua', 'Улюблені цитати:');
INSERT INTO `languages` VALUES('quote_selected', 'en', 'Quote selected');
INSERT INTO `languages` VALUES('quote_selected', 'ru', 'Цитировать выбранное');
INSERT INTO `languages` VALUES('quote_selected', 'ua', 'Цитувати вибране');
INSERT INTO `languages` VALUES('rate_comment', 'en', 'Rate comment:');
INSERT INTO `languages` VALUES('rate_comment', 'ru', 'Оценить комментарий:');
INSERT INTO `languages` VALUES('rate_comment', 'ua', 'Оцінити коментар:');
INSERT INTO `languages` VALUES('rate_down', 'en', 'Down rating');
INSERT INTO `languages` VALUES('rate_down', 'ru', 'Уменьшить рейтинг');
INSERT INTO `languages` VALUES('rate_down', 'ua', 'Зменшити рейтинг');
INSERT INTO `languages` VALUES('rate_up', 'en', 'UP rating');
INSERT INTO `languages` VALUES('rate_up', 'ru', 'Увеличить рейтинг');
INSERT INTO `languages` VALUES('rate_up', 'ua', 'Збільшити рейтинг');
INSERT INTO `languages` VALUES('rating', 'en', 'Rating');
INSERT INTO `languages` VALUES('rating', 'ru', 'Рейтинг');
INSERT INTO `languages` VALUES('rating', 'ua', 'Рейтинг');
INSERT INTO `languages` VALUES('Rating system manual', 'en', 'Rating system manual');
INSERT INTO `languages` VALUES('Rating system manual', 'ru', 'Описание рейтинговой системы');
INSERT INTO `languages` VALUES('rating system manual', 'ua', 'Опис рейтингової системи');
INSERT INTO `languages` VALUES('rating_changed', 'en', 'Rating successfully exchanged for the farmed, now will take you to the page "Your rating"');
INSERT INTO `languages` VALUES('rating_changed', 'ru', 'Рейтинг успешно обменян на скидку, сейчас Вы перейдете к странице "Ваш рейтинг"');
INSERT INTO `languages` VALUES('rating_changed', 'ua', 'Рейтинг успішно обміняний на знижку, зараз Ви перейдете до сторінки "Ваш рейтинг"');
INSERT INTO `languages` VALUES('rating_disconnected', 'en', 'You are currently not seeding any release. Calculation of the rating has been suspended.');
INSERT INTO `languages` VALUES('rating_disconnected', 'ru', 'В данный момент вы ничего не раздаете. Расчет рейтинга приостановлен.');
INSERT INTO `languages` VALUES('rating_disconnected', 'ua', 'У даний момент ви нічого не роздаєте. Розрахунок рейтингу призупинено.');
INSERT INTO `languages` VALUES('rating_low', 'en', 'You can not download the release, your rating is too low, lift my rating is actively commenting, filling the releases, or uploading new releases. Also, you can raise my rating on a fee basis.');
INSERT INTO `languages` VALUES('rating_low', 'ru', 'Вы не можете скачать релиз, ваш рейтинг слишком мал, поднимите себе рейтинг активно комментируя, заливая релизы, либо находясь на раздаче. Также вы можете поднять себе рейтинг на платной основе.');
INSERT INTO `languages` VALUES('rating_low', 'ua', 'Ви не можете завантажити реліз, ваш рейтинг занадто малий, підніміть собі рейтинг активно коментуючи, заливаючи релізи, або перебуваючи на роздачі. Також ви можете підняти собі рейтинг на платній основі.');
INSERT INTO `languages` VALUES('rating_max_formula', 'en', 'Your rating is upper then %s, automatic rating increase disabled. You can increase your rating by active commenting, releasing and receiving ratings from another users');
INSERT INTO `languages` VALUES('rating_max_formula', 'ru', 'Ваш рейтинг больше, чем %s, автоматическое прибавление рейтинга за сидирование отключено. Вы можете увеличить свой рейтинг активно комментируя, создавая релизы и получая оценки от других пользователей');
INSERT INTO `languages` VALUES('rating_max_formula', 'ua', 'Ваш рейтинг більше, ніж %s, автоматичне збільшення рейтингу за сидирування відключено. Ви можете збільшити свій рейтинг активно коментуючи, створюючи релізи та отримуючи оцінки від інших користувачів');
INSERT INTO `languages` VALUES('rating_per_invite', 'en', 'You will receive a +%s rating for the invitation friend');
INSERT INTO `languages` VALUES('rating_per_invite', 'ru', 'Вы получите +%s рейтинга за приглашение друга');
INSERT INTO `languages` VALUES('rating_per_invite', 'ua', 'Ви отримаєте +%s рейтингу за запрошення друга');
INSERT INTO `languages` VALUES('rating_per_request', 'en', 'You will receive a +%s rating for the query');
INSERT INTO `languages` VALUES('rating_per_request', 'ru', 'Вы получите +%s рейтинга за выполнение запроса');
INSERT INTO `languages` VALUES('rating_per_request', 'ua', 'Ви отримаєте +%s рейтингу за виконання запиту');
INSERT INTO `languages` VALUES('rating_title', 'en', 'Score');
INSERT INTO `languages` VALUES('rating_title', 'ru', 'Рейтинг');
INSERT INTO `languages` VALUES('rating_title', 'ua', 'Рейтинг');
INSERT INTO `languages` VALUES('ratio', 'en', 'Torrent rating');
INSERT INTO `languages` VALUES('ratio', 'ru', 'Торрент-рейтинг');
INSERT INTO `languages` VALUES('ratio', 'ua', 'Торрент-рейтинг');
INSERT INTO `languages` VALUES('ratio_down', 'en', '<span style="color:red;">Your rating is reduced and now is %s</span>');
INSERT INTO `languages` VALUES('ratio_down', 'ru', '<span style="color:red;">Ваш рейтинг уменьшается и сейчас равен %s</span>');
INSERT INTO `languages` VALUES('ratio_down', 'ua', '<span style="color:red;">Ваш рейтинг зменшується і зараз дорівнює %s</span>');
INSERT INTO `languages` VALUES('ratio_warning', 'en', '<span style="color:red;">Warning (rating)!</span>');
INSERT INTO `languages` VALUES('ratio_warning', 'ru', '<span style="color:red;">Внимание (рейтинг)!</span>');
INSERT INTO `languages` VALUES('ratio_warning', 'ua', '<span style="color:red;">Увага (рейтинг)!</span>');
INSERT INTO `languages` VALUES('reason', 'en', 'Select report reason:');
INSERT INTO `languages` VALUES('reason', 'ru', 'Выберите причину жалобы:');
INSERT INTO `languages` VALUES('reason', 'ua', 'Виберіть причину скарги:');
INSERT INTO `languages` VALUES('reason_ban', 'en', 'The reason for the ban');
INSERT INTO `languages` VALUES('reason_ban', 'ru', 'Причина запрета');
INSERT INTO `languages` VALUES('reason_ban', 'ua', 'Причина заборони');
INSERT INTO `languages` VALUES('receiver', 'en', 'Receiver');
INSERT INTO `languages` VALUES('receiver', 'ru', 'Получатель');
INSERT INTO `languages` VALUES('receiver', 'ua', 'Одержувач');
INSERT INTO `languages` VALUES('recountadmin_comments', 'en', 'Recount comments');
INSERT INTO `languages` VALUES('recountadmin_comments', 'ru', 'Пересчитать комментарии');
INSERT INTO `languages` VALUES('recountadmin_comments', 'ua', 'Перерахувати коментарі');
INSERT INTO `languages` VALUES('recountadmin_difference', 'en', 'Difference in %s was %s<br/>');
INSERT INTO `languages` VALUES('recountadmin_difference', 'ru', 'Различие в %s составило %s<br/>');
INSERT INTO `languages` VALUES('recountadmin_difference', 'ua', 'Різниця в %s склала %s<br />');
INSERT INTO `languages` VALUES('recountadmin_link', 'en', 'Recount/sync database values');
INSERT INTO `languages` VALUES('recountadmin_link', 'ru', 'Пересчитать счетчики');
INSERT INTO `languages` VALUES('recountadmin_link', 'ua', 'Перерахувати лічильники');
INSERT INTO `languages` VALUES('recountadmin_notice', 'en', 'This page allows you to sync database values');
INSERT INTO `languages` VALUES('recountadmin_notice', 'ru', 'Эта панель позволяет синхронизировать значения Базы Данных');
INSERT INTO `languages` VALUES('recountadmin_notice', 'ua', 'Ця панель дозволяє синхронізувати значення Бази Даних');
INSERT INTO `languages` VALUES('recountadmin_torrents', 'en', 'Recount torrents and .torrent files');
INSERT INTO `languages` VALUES('recountadmin_torrents', 'ru', 'Пересчитать торренты и .torrent файлы');
INSERT INTO `languages` VALUES('recountadmin_torrents', 'ua', 'Перерахувати торренти і. torrent файли');
INSERT INTO `languages` VALUES('Recounter', 'en', 'Recounter');
INSERT INTO `languages` VALUES('Recounter', 'ru', 'Пересчитывалка');
INSERT INTO `languages` VALUES('recounter', 'ua', 'Перерахівниця');
INSERT INTO `languages` VALUES('recover', 'en', 'Restore user access');
INSERT INTO `languages` VALUES('recover', 'ru', 'Востан. юзера');
INSERT INTO `languages` VALUES('recover', 'ua', 'Відн. юзера');
INSERT INTO `languages` VALUES('registered', 'en', 'Registered');
INSERT INTO `languages` VALUES('registered', 'ru', 'Зарегестрирован');
INSERT INTO `languages` VALUES('registered', 'ua', 'Зареєстрований');
INSERT INTO `languages` VALUES('Registrer now!', 'en', 'Registrer now!');
INSERT INTO `languages` VALUES('Registrer now!', 'ru', 'Зарегистрироваться сейчас!');
INSERT INTO `languages` VALUES('registrer now!', 'ua', 'Зареєструватися зараз!');
INSERT INTO `languages` VALUES('reg_proceed', 'en', 'To proceed registration you must agreee the following terms');
INSERT INTO `languages` VALUES('reg_proceed', 'ru', 'Чтобы продолжить регистрацию, вы должны согласиться со следующим');
INSERT INTO `languages` VALUES('reg_proceed', 'ua', 'Щоб продовжити реєстрацію, ви повинні погодитися з наступним');
INSERT INTO `languages` VALUES('relationship', 'en', '- Communication with the administration');
INSERT INTO `languages` VALUES('relationship', 'ru', '- Связь с администрацией');
INSERT INTO `languages` VALUES('relationship', 'ua', '- Зв''язок з адміністрацією');
INSERT INTO `languages` VALUES('Relcomments', 'en', 'Relcomments');
INSERT INTO `languages` VALUES('Relcomments', 'ru', 'Комм. к релизам');
INSERT INTO `languages` VALUES('relcomments', 'ua', 'Ком. до релізів');
INSERT INTO `languages` VALUES('release', 'en', 'Release');
INSERT INTO `languages` VALUES('release', 'ru', 'Релиз');
INSERT INTO `languages` VALUES('release', 'ua', 'Реліз');
INSERT INTO `languages` VALUES('Release bookmarks', 'en', 'Release bookmarks');
INSERT INTO `languages` VALUES('Release bookmarks', 'ru', 'Закладки релизов');
INSERT INTO `languages` VALUES('release bookmarks', 'ua', 'Закладки релізів');
INSERT INTO `languages` VALUES('releases', 'en', 'Releases');
INSERT INTO `languages` VALUES('releases', 'ru', 'Релизы');
INSERT INTO `languages` VALUES('releases', 'ua', 'Релізі');
INSERT INTO `languages` VALUES('release_anonymous', 'en', 'The release is now anonymous');
INSERT INTO `languages` VALUES('release_anonymous', 'ru', 'Релиз успешно анонимизирован');
INSERT INTO `languages` VALUES('release_anonymous', 'ua', 'Реліз успішно анонімізований');
INSERT INTO `languages` VALUES('release_name_rule', 'en', 'Release name does not corresponding to rule, please change it and try again:');
INSERT INTO `languages` VALUES('release_name_rule', 'ru', 'Имя релиза не соответствует правилам, пожалуйста измените его и попробуйте еще раз:');
INSERT INTO `languages` VALUES('relgroup', 'en', 'Release group');
INSERT INTO `languages` VALUES('relgroup', 'ru', 'Релиз-группа');
INSERT INTO `languages` VALUES('relgroup', 'ua', 'Реліз-група');
INSERT INTO `languages` VALUES('relgroups', 'en', 'Release groups');
INSERT INTO `languages` VALUES('relgroups', 'ru', 'Релиз-группы');
INSERT INTO `languages` VALUES('relgroups', 'ua', 'Реліз-групи');
INSERT INTO `languages` VALUES('relgroupsadd', 'en', '| <a href="rgadmin.php?a=add">Add</a>');
INSERT INTO `languages` VALUES('relgroupsadd', 'ru', '| <a href="rgadmin.php?a=add">Добавить</a>');
INSERT INTO `languages` VALUES('relgroupsadd', 'ua', '| <a href="rgadmin.php?a=add">Додати</a>');
INSERT INTO `languages` VALUES('relgroup_deleted', 'en', 'Release group is removed, now you can go to the management panel release groups');
INSERT INTO `languages` VALUES('relgroup_deleted', 'ru', 'Релиз группа удалена, сейчас вы перейдете к панели администрирования релиз групп');
INSERT INTO `languages` VALUES('relgroup_deleted', 'ua', 'Реліз група вилучена, зараз ви перейдете до панелі адміністрування реліз груп');
INSERT INTO `languages` VALUES('relgroup_release', 'en', 'Release Private group');
INSERT INTO `languages` VALUES('relgroup_release', 'ru', 'Релиз приватной группы');
INSERT INTO `languages` VALUES('relgroup_release', 'ua', 'Реліз приватної групи');
INSERT INTO `languages` VALUES('relgroup_releases', 'en', 'Releases of this group');
INSERT INTO `languages` VALUES('relgroup_releases', 'ru', 'Релизы этой группы');
INSERT INTO `languages` VALUES('relgroup_releases', 'ua', 'Релізи цієї групи');
INSERT INTO `languages` VALUES('relgroup_title', 'en', 'Release group "%s", specialization for %s');
INSERT INTO `languages` VALUES('relgroup_title', 'ru', 'Релиз группа "%s", специализируется на %s');
INSERT INTO `languages` VALUES('relgroup_title', 'ua', 'Реліз група  "%s", спеціалізується на %s');
INSERT INTO `languages` VALUES('reltemplatesadmin', 'en', 'Release''s templates adminsitration');
INSERT INTO `languages` VALUES('reltemplatesadmin', 'ru', 'Настройка Шаблонов Релизов');
INSERT INTO `languages` VALUES('reltemplatesadmin', 'ua', 'Налаштування Шаблонів релізів');
INSERT INTO `languages` VALUES('remotecheck_disabled', 'en', 'Verify remote peers not running.');
INSERT INTO `languages` VALUES('remotecheck_disabled', 'ru', 'Проверка удаленных пиров отключена');
INSERT INTO `languages` VALUES('remotecheck_disabled', 'ua', 'Перевірка віддалених пірів відключена');
INSERT INTO `languages` VALUES('remotecheck_is_running', 'en', 'Now perform remote peers');
INSERT INTO `languages` VALUES('remotecheck_is_running', 'ru', 'В данный момент выполняется проверка удаленных пиров');
INSERT INTO `languages` VALUES('remotecheck_is_running', 'ua', 'У даний момент виконується перевірка віддалених бенкетів');
INSERT INTO `languages` VALUES('remotecheck_not_running', 'en', 'Verify remote peers is not running.');
INSERT INTO `languages` VALUES('remotecheck_not_running', 'ru', 'Проверка удаленных пиров не запущена');
INSERT INTO `languages` VALUES('remotecheck_not_running', 'ua', 'Перевірка віддалених бенкетів не запущена');
INSERT INTO `languages` VALUES('remove', 'en', 'Remove');
INSERT INTO `languages` VALUES('remove', 'ru', 'Удалить');
INSERT INTO `languages` VALUES('remove', 'ua', 'Видалити');
INSERT INTO `languages` VALUES('removed', 'en', 'removed.');
INSERT INTO `languages` VALUES('removed', 'ru', 'удален.');
INSERT INTO `languages` VALUES('removed', 'ua', 'видалений.');
INSERT INTO `languages` VALUES('Reorder blocks', 'en', 'Reorder blocks');
INSERT INTO `languages` VALUES('Reorder blocks', 'ru', 'Перестроить позиции блоков');
INSERT INTO `languages` VALUES('reorder blocks', 'ua', 'Перебудувати позиції блоків');
INSERT INTO `languages` VALUES('Reorder rules', 'en', 'Reorder rules');
INSERT INTO `languages` VALUES('Reorder rules', 'ru', 'Перестроить порядок параметров');
INSERT INTO `languages` VALUES('reorder rules', 'ua', 'Перебудувати порядок параметрів');
INSERT INTO `languages` VALUES('repeat_password', 'en', 'Repeat password;');
INSERT INTO `languages` VALUES('repeat_password', 'ru', 'Повтор пароля');
INSERT INTO `languages` VALUES('repeat_password', 'ua', 'Повтор пароля');
INSERT INTO `languages` VALUES('Replace', 'en', 'Replace');
INSERT INTO `languages` VALUES('Replace', 'ru', 'Замена');
INSERT INTO `languages` VALUES('replace', 'ua', 'Заміна');
INSERT INTO `languages` VALUES('replies', 'en', 'Replies');
INSERT INTO `languages` VALUES('replies', 'ru', 'Ответов');
INSERT INTO `languages` VALUES('replies', 'ua', 'Відповідей');
INSERT INTO `languages` VALUES('reports', 'en', 'View reports');
INSERT INTO `languages` VALUES('reports', 'ru', 'Жалобы');
INSERT INTO `languages` VALUES('reports', 'ua', 'Скарги');
INSERT INTO `languages` VALUES('reports_p', 'en', 'Reports');
INSERT INTO `languages` VALUES('reports_p', 'ru', 'Жалобы');
INSERT INTO `languages` VALUES('reports_p', 'ua', 'Скарги');
INSERT INTO `languages` VALUES('report_it', 'en', 'Report it!');
INSERT INTO `languages` VALUES('report_it', 'ru', 'Пожаловаться!');
INSERT INTO `languages` VALUES('report_it', 'ua', 'Поскаржитись!');
INSERT INTO `languages` VALUES('report_ok', 'en', 'Your report successfully accepted');
INSERT INTO `languages` VALUES('report_ok', 'ru', 'Ваша жалоба принята к рассмотрению');
INSERT INTO `languages` VALUES('report_ok', 'ua', 'Ваша скарга прийнята до розгляду');
INSERT INTO `languages` VALUES('report_reason', 'en', 'to cause:');
INSERT INTO `languages` VALUES('report_reason', 'ru', 'с причиной:');
INSERT INTO `languages` VALUES('report_reason', 'ua', 'з причиною:');
INSERT INTO `languages` VALUES('Reqcomments', 'en', 'Reqcomments');
INSERT INTO `languages` VALUES('Reqcomments', 'ru', 'Комм.запросов');
INSERT INTO `languages` VALUES('reqcomments', 'ua', 'Ком. запитів');
INSERT INTO `languages` VALUES('request', 'en', 'Request');
INSERT INTO `languages` VALUES('request', 'ru', 'Запрос');
INSERT INTO `languages` VALUES('request', 'ua', 'Запит');
INSERT INTO `languages` VALUES('requester', 'en', 'Requester');
INSERT INTO `languages` VALUES('requester', 'ru', 'Запросил');
INSERT INTO `languages` VALUES('requester', 'ua', 'Запросив');
INSERT INTO `languages` VALUES('requests', 'en', 'Requests');
INSERT INTO `languages` VALUES('requests_section', 'en', 'Section requests');
INSERT INTO `languages` VALUES('requests_section', 'ru', 'Секция запросов');
INSERT INTO `languages` VALUES('requests_section', 'ua', 'Секція запитів');
INSERT INTO `languages` VALUES('resend_activation', 'en', 'If you did not receive confirmation letter, you can <a href="%s">Resend</a> it.');
INSERT INTO `languages` VALUES('resend_activation', 'ru', 'Если вы не получили письмо подтверждения, мы можем <a href="%s">переслать его еще раз</a>.');
INSERT INTO `languages` VALUES('resend_activation', 'ua', 'Якщо ви не отримали лист підтвердження, ми можемо <a href="%s">переслати його ще раз</a>.');
INSERT INTO `languages` VALUES('respect', 'en', 'Respect');
INSERT INTO `languages` VALUES('respect', 'ru', 'Респект');
INSERT INTO `languages` VALUES('respect', 'ua', 'Респект');
INSERT INTO `languages` VALUES('restored', 'en', 'restored');
INSERT INTO `languages` VALUES('restored', 'ru', 'восстановлен');
INSERT INTO `languages` VALUES('restored', 'ua', 'відновлений');
INSERT INTO `languages` VALUES('result', 'en', 'Result');
INSERT INTO `languages` VALUES('result', 'ru', 'Результат');
INSERT INTO `languages` VALUES('result', 'ua', 'Результат');
INSERT INTO `languages` VALUES('retrackeradmin', 'en', 'Retracker administration');
INSERT INTO `languages` VALUES('retrackeradmin', 'ru', 'Управление ретрекером');
INSERT INTO `languages` VALUES('retrackeradmin', 'ua', 'Управління ретрекером');
INSERT INTO `languages` VALUES('rgadmin', 'en', 'Release groups');
INSERT INTO `languages` VALUES('rgadmin', 'ru', 'Релиз-группы');
INSERT INTO `languages` VALUES('rgadmin', 'ua', 'Реліз-групи');
INSERT INTO `languages` VALUES('Rgcomments', 'en', 'Rgcomments');
INSERT INTO `languages` VALUES('Rgcomments', 'ru', 'Комм.релиз-групп');
INSERT INTO `languages` VALUES('rgcomments', 'ua', 'Комм.реліз-груп');
INSERT INTO `languages` VALUES('rginvite', 'en', 'Suggest a subscription to a friend');
INSERT INTO `languages` VALUES('rginvite', 'ru', 'Предложить подписку другу');
INSERT INTO `languages` VALUES('rginvite', 'ua', 'Запропонувати підписку другу');
INSERT INTO `languages` VALUES('rginvite_deny', 'en', 'Unsubscribe');
INSERT INTO `languages` VALUES('rginvite_deny', 'ru', 'Отписаться');
INSERT INTO `languages` VALUES('rginvite_deny', 'ua', 'Відписатися');
INSERT INTO `languages` VALUES('rginvite_my', 'en', 'Subscribe');
INSERT INTO `languages` VALUES('rginvite_my', 'ru', 'Подписаться');
INSERT INTO `languages` VALUES('rginvite_my', 'ua', 'Підписатися');
INSERT INTO `languages` VALUES('rgusers', 'en', 'Subscribers');
INSERT INTO `languages` VALUES('rgusers', 'ru', 'Подписчиков');
INSERT INTO `languages` VALUES('rgusers', 'ua', 'Передплатників');
INSERT INTO `languages` VALUES('rg_faq', 'en', 'Tip: in the field of image indicates a full or relative URL of the image, in the fields of the owners and members of the specified user ID, <b>after a comma, no spaces</b>. In the "payment page" indicates a full or relative path to the payment page<br />To make the user paid subscription to the mailing list you want to run SQL-query:<br /><pre>INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES (ID_user,ID_release_group,UNIX_time+time_subscription*86400);</pre>');
INSERT INTO `languages` VALUES('rg_faq', 'ru', 'Совет: в поле картинка указывается полный или относительный URL картинки, в полях владельцы и члены указываются ID соответствующих пользователей <b>через запятую, без пробелов</b>. В поле "страница оплаты" указывается полный или относительный путь к странице оплаты<br />Для внесения пользователя при платной подписке в список подписчиков нужно выполнить SQL-запрос:<br /><pre>INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES (ID_пользователя,ID_релиз группы,UNIX_время+время_подписки*86400);</pre>');
INSERT INTO `languages` VALUES('rg_faq', 'ua', 'Совет: в поле картинка указывается полный или относительный URL картинки, в полях владельцы и члены указываются ID соответствующих пользователей <b>через запятую, без пробелов</b>. В поле "страница оплаты" указывается полный или относительный путь к странице оплаты<br />Для внесения пользователя при платной подписке в список подписчиков нужно выполнить SQL-запрос:<br /><pre>INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES (ID_пользователя,ID_релиз группы,UNIX_время+время_подписки*86400);</pre>');
INSERT INTO `languages` VALUES('rg_title', 'en', 'Management release groups');
INSERT INTO `languages` VALUES('rg_title', 'ru', 'Администрирование релиз-групп');
INSERT INTO `languages` VALUES('rg_title', 'ua', 'Адміністрування реліз-груп');
INSERT INTO `languages` VALUES('Right', 'en', 'Right');
INSERT INTO `languages` VALUES('Right', 'ru', 'Право');
INSERT INTO `languages` VALUES('right', 'ua', 'Право');
INSERT INTO `languages` VALUES('rss', 'en', 'Subscribe(RSS)');
INSERT INTO `languages` VALUES('rss', 'ru', 'Подпишись');
INSERT INTO `languages` VALUES('rss', 'ua', 'Підпишись');
INSERT INTO `languages` VALUES('rt_state_1', 'en', '<span style="color: red;">Request filed to stop, but the script still running. Please wait...</span>');
INSERT INTO `languages` VALUES('rt_state_1', 'ru', '<span style="color: red;">Запрос на остановку подан, но скрипт еще выполняется. Подождите пожалуйста</span>');
INSERT INTO `languages` VALUES('rt_state_1', 'ua', '<span style="color: red;">Запрос на остановку подан, но скрипт еще выполняется. Подождите пожалуйста</span>');
INSERT INTO `languages` VALUES('rt_state_2', 'en', '<span style="color: green;">Stopping</span>');
INSERT INTO `languages` VALUES('rt_state_2', 'ru', '<span style="color: green;">Функция остановлена</span>');
INSERT INTO `languages` VALUES('rt_state_2', 'ua', '<span style="color: green;">Функция остановлена</span>');
INSERT INTO `languages` VALUES('rt_state_3', 'en', '<span style="color: green;">Function works</span>');
INSERT INTO `languages` VALUES('rt_state_3', 'ru', '<span style="color: green;">Функция работает</span>');
INSERT INTO `languages` VALUES('rt_state_3', 'ua', '<span style="color: green;">Функция работает</span>');
INSERT INTO `languages` VALUES('rt_state_4', 'en', '<span style="color: red;">Start request was send, script is starting now</span>');
INSERT INTO `languages` VALUES('rt_state_4', 'ru', '<span style="color: red;">Запрос на запуск функции подан, но функция еще не запустилась</span>');
INSERT INTO `languages` VALUES('rt_state_4', 'ua', '<span style="color: red;">Запрос на запуск функции подан, но функция еще не запустилась</span>');
INSERT INTO `languages` VALUES('ru', 'en', 'Русский (RU)');
INSERT INTO `languages` VALUES('ru', 'ru', 'Русский (RU)');
INSERT INTO `languages` VALUES('ru', 'ua', 'Російська (RU)');
INSERT INTO `languages` VALUES('rules', 'en', 'Rules');
INSERT INTO `languages` VALUES('rules', 'ru', 'Правила');
INSERT INTO `languages` VALUES('rules', 'ua', 'Правила');
INSERT INTO `languages` VALUES('said_thanks', 'en', 'Said&nbsp;thanks');
INSERT INTO `languages` VALUES('said_thanks', 'ru', 'Сказали&nbsp;спасибо');
INSERT INTO `languages` VALUES('said_thanks', 'ua', 'Сказали&nbsp;спасибі');
INSERT INTO `languages` VALUES('Save changes', 'en', 'Save changes');
INSERT INTO `languages` VALUES('Save changes', 'ru', 'Сохранить изменения');
INSERT INTO `languages` VALUES('save changes', 'ua', 'Зберегти зміни');
INSERT INTO `languages` VALUES('save_order', 'en', 'Save order');
INSERT INTO `languages` VALUES('save_order', 'ru', 'Сохранить порядок');
INSERT INTO `languages` VALUES('save_order', 'ua', 'Зберегти порядок');
INSERT INTO `languages` VALUES('screens', 'en', 'Screenshots');
INSERT INTO `languages` VALUES('screens', 'ru', 'Скриншоты, кадры');
INSERT INTO `languages` VALUES('screens', 'ua', 'Скріншоти, кадри');
INSERT INTO `languages` VALUES('Script', 'en', 'Script');
INSERT INTO `languages` VALUES('Script', 'ru', 'Скрипт');
INSERT INTO `languages` VALUES('script', 'ua', 'Скрипт');
INSERT INTO `languages` VALUES('search', 'en', 'Search');
INSERT INTO `languages` VALUES('search', 'ru', 'Поиск');
INSERT INTO `languages` VALUES('search', 'ua', 'Пошук');
INSERT INTO `languages` VALUES('Search by key or value', 'en', 'Search by key or value');
INSERT INTO `languages` VALUES('Search by key or value', 'ru', 'Искать по ключу или значению');
INSERT INTO `languages` VALUES('search by key or value', 'ua', 'Шукати по ключу або значенням');
INSERT INTO `languages` VALUES('search_btn', 'en', 'Search!');
INSERT INTO `languages` VALUES('search_btn', 'ru', 'Искать');
INSERT INTO `languages` VALUES('search_btn', 'ua', 'Шукати');
INSERT INTO `languages` VALUES('search_google', 'en', 'Search Google');
INSERT INTO `languages` VALUES('search_google', 'ru', 'Искать в Гугле');
INSERT INTO `languages` VALUES('search_google', 'ua', 'Шукати у Гуглі');
INSERT INTO `languages` VALUES('search_requests', 'en', 'Search requests');
INSERT INTO `languages` VALUES('search_requests', 'ru', 'Искать запросы');
INSERT INTO `languages` VALUES('search_requests', 'ua', 'Шукати запити');
INSERT INTO `languages` VALUES('search_results_for', 'en', 'Search results for');
INSERT INTO `languages` VALUES('search_results_for', 'ru', 'Результаты поиска для');
INSERT INTO `languages` VALUES('search_results_for', 'ua', 'Результати пошуку для');
INSERT INTO `languages` VALUES('search_users', 'en', 'Search Friends');
INSERT INTO `languages` VALUES('search_users', 'ru', 'Найти друзей');
INSERT INTO `languages` VALUES('search_users', 'ua', 'Знайти друзів');
INSERT INTO `languages` VALUES('Seconds', 'en', 'Seconds');
INSERT INTO `languages` VALUES('Seconds', 'ru', 'Секунд');
INSERT INTO `languages` VALUES('seconds', 'ua', 'Секунд');
INSERT INTO `languages` VALUES('seeder', 'en', 'Seeder');
INSERT INTO `languages` VALUES('seeder', 'ru', 'Раздающий');
INSERT INTO `languages` VALUES('seeder', 'ua', 'Роздающий');
INSERT INTO `languages` VALUES('seeders', 'en', 'Seeders');
INSERT INTO `languages` VALUES('seeders', 'ru', 'Сидов');
INSERT INTO `languages` VALUES('seeders', 'ua', 'Сідів');
INSERT INTO `languages` VALUES('seeders_l', 'en', 'seeders');
INSERT INTO `languages` VALUES('seeders_l', 'ru', 'раздающих');
INSERT INTO `languages` VALUES('seeders_l', 'ua', 'роздають');
INSERT INTO `languages` VALUES('seeder_last_seen', 'en', 'Last time was here');
INSERT INTO `languages` VALUES('seeder_last_seen', 'ru', 'Последний раз был здесь');
INSERT INTO `languages` VALUES('seeder_last_seen', 'ua', 'Останній раз був тут');
INSERT INTO `languages` VALUES('seeding', 'en', 'Seeding');
INSERT INTO `languages` VALUES('seeding', 'ru', 'Раздаете');
INSERT INTO `languages` VALUES('seeding', 'ua', 'Роздаєте');
INSERT INTO `languages` VALUES('seeds', 'en', 'Seed');
INSERT INTO `languages` VALUES('seeds', 'ru', 'Сиды');
INSERT INTO `languages` VALUES('seeds', 'ua', 'Сіди');
INSERT INTO `languages` VALUES('Select language for export', 'en', 'Select language for export');
INSERT INTO `languages` VALUES('Select language for export', 'ru', 'Выберите язык для экспорта');
INSERT INTO `languages` VALUES('select language for export', 'ua', 'Виберіть мову для експорту');
INSERT INTO `languages` VALUES('select_all', 'en', 'Select All');
INSERT INTO `languages` VALUES('select_all', 'ru', '<strong>Выбрать все</strong>');
INSERT INTO `languages` VALUES('select_all', 'ua', '<strong>Вибрати усе</strong>');
INSERT INTO `languages` VALUES('select_avatar', 'en', 'Select Avatar');
INSERT INTO `languages` VALUES('select_avatar', 'ru', 'Выберите аватару');
INSERT INTO `languages` VALUES('select_avatar', 'ua', 'Виберіть аватару');
INSERT INTO `languages` VALUES('select_cache', 'en', 'Select to clear the cache');
INSERT INTO `languages` VALUES('select_cache', 'ru', 'Выберите кэш для очистки');
INSERT INTO `languages` VALUES('select_cache', 'ua', 'Виберіть кеш для очищення');
INSERT INTO `languages` VALUES('select_classes', 'en', 'Select 1 or more classes to send the message.');
INSERT INTO `languages` VALUES('select_classes', 'ru', 'Выберите 1 или более классов для отправки сообщения.');
INSERT INTO `languages` VALUES('select_classes', 'ua', 'Виберіть один або більше класів для відправки повідомлення.');
INSERT INTO `languages` VALUES('select_friend', 'en', 'Select friend');
INSERT INTO `languages` VALUES('select_friend', 'ru', 'Выберите друга');
INSERT INTO `languages` VALUES('select_friend', 'ua', 'Виберіть друга');
INSERT INTO `languages` VALUES('select_history_type', 'en', 'Select the type of user history');
INSERT INTO `languages` VALUES('select_history_type', 'ru', 'Выберите тип истории пользователя');
INSERT INTO `languages` VALUES('select_history_type', 'ua', 'Виберіть тип історії користувача');
INSERT INTO `languages` VALUES('select_present', 'en', '<strong>Present!</strong>');
INSERT INTO `languages` VALUES('select_present', 'ru', '<strong>Подарить!</strong>');
INSERT INTO `languages` VALUES('select_present', 'ua', '<strong>Подарувати!</strong>');
INSERT INTO `languages` VALUES('select_user', 'en', 'You should select the user for editing.');
INSERT INTO `languages` VALUES('select_user', 'ru', 'Вы должны выбрать пользователя для редактирования.');
INSERT INTO `languages` VALUES('select_user', 'ua', 'Ви повинні вибрати користувача для редагування.');
INSERT INTO `languages` VALUES('send', 'en', 'Send');
INSERT INTO `languages` VALUES('send', 'ru', 'Отправлено');
INSERT INTO `languages` VALUES('send', 'ua', 'Відправлено');
INSERT INTO `languages` VALUES('sender', 'en', 'Sender');
INSERT INTO `languages` VALUES('sender', 'ru', 'Отправитель');
INSERT INTO `languages` VALUES('sender', 'ua', 'Відправник');
INSERT INTO `languages` VALUES('send_email_admin', 'en', 'Send e-mail administration');
INSERT INTO `languages` VALUES('send_email_admin', 'ru', 'Отправка e-mail администрации');
INSERT INTO `languages` VALUES('send_email_admin', 'ua', 'Відправлення e-mail адміністрації');
INSERT INTO `languages` VALUES('seoadmin_add_rule', 'en', 'Add new rule');
INSERT INTO `languages` VALUES('seoadmin_add_rule', 'ru', 'Добавить новое правило');
INSERT INTO `languages` VALUES('seoadmin_add_rule', 'ua', 'Додати нове правило');
INSERT INTO `languages` VALUES('seoadmin_eg_param', 'en', 'E.g. "id". For replacing script name with "?" define "{base}"');
INSERT INTO `languages` VALUES('seoadmin_eg_param', 'ru', 'Например, "id". Для замены имени скрипта с "?" укажите "{base}"');
INSERT INTO `languages` VALUES('seoadmin_eg_param', 'ua', 'Наприклад, "id". Для заміни імені скрипта з "?" вкажіть "{base}"');
INSERT INTO `languages` VALUES('seoadmin_eg_replace', 'en', 'E.g. "USER_IDENTIFIER=%s" or "id/%s", like in <a href="http://ru2.php.net/manual/en/function.sprintf.php">sprintf</a>');
INSERT INTO `languages` VALUES('seoadmin_eg_replace', 'ru', 'Например "USER_IDENTIFIER=%s" или "id/%s", как в функции <a href="http://ru2.php.net/manual/en/function.sprintf.php">sprintf</a>');
INSERT INTO `languages` VALUES('seoadmin_eg_replace', 'ua', 'Наприклад "USER_IDENTIFIER=%s" або "id/%s", як у функції <a href="http://ru2.php.net/manual/en/function.sprintf.php">sprintf</a>');
INSERT INTO `languages` VALUES('seoadmin_generate_rewrites', 'en', 'Generate rewrites');
INSERT INTO `languages` VALUES('seoadmin_generate_rewrites', 'ru', 'Генерировать реврайты');
INSERT INTO `languages` VALUES('seoadmin_generate_rewrites', 'ua', 'Генерувати реврайти');
INSERT INTO `languages` VALUES('seoadmin_order_saved', 'en', 'Parametres order saved');
INSERT INTO `languages` VALUES('seoadmin_order_saved', 'ru', 'Порядок параметров сохранен');
INSERT INTO `languages` VALUES('seoadmin_order_saved', 'ua', 'Порядок параметрів збережений');
INSERT INTO `languages` VALUES('seoadmin_rewrites_help', 'en', 'This list will help you to configure rewrites.');
INSERT INTO `languages` VALUES('seoadmin_rewrites_help', 'ru', 'Этот список поможет вам сконфигурировать реврайты');
INSERT INTO `languages` VALUES('seoadmin_rewrites_help', 'ua', 'Цей список допоможе вам сконфігурувати реврайти');
INSERT INTO `languages` VALUES('seoadmin_rewrites_lisitng', 'en', 'Rewrites for %s listing');
INSERT INTO `languages` VALUES('seoadmin_rewrites_lisitng', 'ru', 'Список реврайтов для %s');
INSERT INTO `languages` VALUES('seoadmin_rewrites_lisitng', 'ua', 'Список реврайтів для %s');
INSERT INTO `languages` VALUES('seoadmin_rule_not_saved', 'en', 'Rule does not saved due MySQL error:');
INSERT INTO `languages` VALUES('seoadmin_rule_not_saved', 'ru', 'Правило не сохранено из-за MySQL ошибки:');
INSERT INTO `languages` VALUES('seoadmin_rule_not_saved', 'ua', 'Правило не збережено через MySQL помилки:');
INSERT INTO `languages` VALUES('seoadmin_select_server', 'en', 'Select server type');
INSERT INTO `languages` VALUES('seoadmin_select_server', 'ru', 'Выберите тип сервера');
INSERT INTO `languages` VALUES('seoadmin_select_server', 'ua', 'Виберіть тип сервера');
INSERT INTO `languages` VALUES('seoadmin_select_server_msg', 'en', 'Please select server type to generate rewrites:<br/><a href="%s">Apache</a> | <a href="%s">Nginx</a>');
INSERT INTO `languages` VALUES('seoadmin_select_server_msg', 'ru', 'Пожалуйста, выберите тип сервера для генерации реврайтов:<br/><a href="%s">Apache</a> | <a href="%s">Nginx</a>');
INSERT INTO `languages` VALUES('seoadmin_select_server_msg', 'ua', 'Будь ласка, виберіть тип сервера для генерації реврайтів:<br /><a href="%s">Apache</a> | <a href="%s">Nginx</a>');
INSERT INTO `languages` VALUES('seoadmin_sort_notice', 'en', 'Parametres can be (re)sorted by these values');
INSERT INTO `languages` VALUES('seoadmin_sort_notice', 'ru', 'Параметры могут быть отсортированы в соответствии с этим индексом');
INSERT INTO `languages` VALUES('seoadmin_sort_notice', 'ua', 'Параметри можуть бути відсортовані відповідно до цього індексу');
INSERT INTO `languages` VALUES('seoadmin_title', 'en', 'SEO administration panel');
INSERT INTO `languages` VALUES('seoadmin_title', 'ru', 'Администрирование ЧПУ');
INSERT INTO `languages` VALUES('seoadmin_title', 'ua', 'Адміністрування ЛЗУ');
INSERT INTO `languages` VALUES('seoadmin_unknown_server', 'en', 'Unknown server type');
INSERT INTO `languages` VALUES('seoadmin_unknown_server', 'ru', 'Неизвестный тип сервера');
INSERT INTO `languages` VALUES('seoadmin_unknown_server', 'ua', 'Невідомий тип сервера');
INSERT INTO `languages` VALUES('seoadmin_unset', 'en', 'Unset params');
INSERT INTO `languages` VALUES('seoadmin_unset', 'ru', 'Уничтожаемые параметры');
INSERT INTO `languages` VALUES('seoadmin_unset', 'ua', 'Параметри що знищуються');
INSERT INTO `languages` VALUES('seoadmin_unset_notice', 'en', 'Will unset some params already processed. E.g. "{base}". Separate by <b>commas without spaces</b>');
INSERT INTO `languages` VALUES('seoadmin_unset_notice', 'ru', 'Удаляемые параметры, например "{base}". Разделяйте <b>запятыми без пробелов</b>');
INSERT INTO `languages` VALUES('seoadmin_unset_notice', 'ua', 'Видаляються параметри, наприклад "{base}". Розділяйте <b>комами без пробілів</b>');
INSERT INTO `languages` VALUES('seoadmin_wo_php', 'en', 'Without .php extension');
INSERT INTO `languages` VALUES('seoadmin_wo_php', 'ru', 'Без расширения .php');
INSERT INTO `languages` VALUES('seoadmin_wo_php', 'ua', 'Без розширення .php');
INSERT INTO `languages` VALUES('seo_admincp_title', 'en', 'Human Readable URLs configuration (SEO)');
INSERT INTO `languages` VALUES('seo_admincp_title', 'ru', 'ЧеловекоПонятныеУрлы (ЧПУ)');
INSERT INTO `languages` VALUES('seo_admincp_title', 'ua', 'Людино-Зрозумілі URL (ЛЗУ)');
INSERT INTO `languages` VALUES('seo_name', 'en', 'SEO link');
INSERT INTO `languages` VALUES('seo_name', 'ru', 'SEO ссылка');
INSERT INTO `languages` VALUES('seo_name', 'ua', 'SEO посилання');
INSERT INTO `languages` VALUES('server_load', 'en', 'Server load');
INSERT INTO `languages` VALUES('server_load', 'ru', 'Нагрузка на сервер');
INSERT INTO `languages` VALUES('server_load', 'ua', 'Навантаження на сервер');
INSERT INTO `languages` VALUES('shop', 'en', 'Shop');
INSERT INTO `languages` VALUES('shop', 'ru', 'Магазин');
INSERT INTO `languages` VALUES('shop', 'ua', 'Магазин');
INSERT INTO `languages` VALUES('show_all', 'en', 'Show all');
INSERT INTO `languages` VALUES('show_all', 'ru', 'Показать все');
INSERT INTO `languages` VALUES('show_all', 'ua', 'Показати усе');
INSERT INTO `languages` VALUES('show_data', 'en', 'Show info');
INSERT INTO `languages` VALUES('show_data', 'ru', 'Показать данные');
INSERT INTO `languages` VALUES('show_data', 'ua', 'Показати дані');
INSERT INTO `languages` VALUES('show_my_requests', 'en', 'Show my requests');
INSERT INTO `languages` VALUES('show_my_requests', 'ru', 'Посмотреть мои запросы');
INSERT INTO `languages` VALUES('show_my_requests', 'ua', 'Переглянути мої запити');
INSERT INTO `languages` VALUES('Signature', 'en', 'Signature');
INSERT INTO `languages` VALUES('Signature', 'ru', 'Подпись');
INSERT INTO `languages` VALUES('signature', 'ua', 'Підпис');
INSERT INTO `languages` VALUES('signature_notice', 'en', 'Your signature will be shown in comments. Maximum length is %s characters (if more, text will be cutted). Pure HTML knowing is allowed!');
INSERT INTO `languages` VALUES('signature_notice', 'ru', 'Ваша подпись будет отображаться в ваших комментариях. Длина ее до %s символов (если больше, мы ее обрежем). Знания чистого HTML приветствуются!');
INSERT INTO `languages` VALUES('signature_notice', 'ua', 'Ваш підпис буде відображатися у ваших коментарях. Довжина її до %s символів (якщо більше, ми її обрізатимемо). Знання чистого HTML вітаються!');
INSERT INTO `languages` VALUES('signup', 'en', 'Registration');
INSERT INTO `languages` VALUES('signup', 'ru', 'Регистрация');
INSERT INTO `languages` VALUES('signup', 'ua', 'Реєстрація');
INSERT INTO `languages` VALUES('signup_already_registered', 'en', 'You are already registered %s!');
INSERT INTO `languages` VALUES('signup_already_registered', 'ru', 'Вы уже зарегистрированный пользователь %s!');
INSERT INTO `languages` VALUES('signup_already_registered', 'ua', 'Ви вже зареєстрований користувач %s!');
INSERT INTO `languages` VALUES('signup_contact', 'en', 'Contacts');
INSERT INTO `languages` VALUES('signup_contact', 'ru', 'Контакты');
INSERT INTO `languages` VALUES('signup_contact', 'ua', 'Контакти');
INSERT INTO `languages` VALUES('signup_email', 'en', 'E-mail');
INSERT INTO `languages` VALUES('signup_email', 'ru', 'Email');
INSERT INTO `languages` VALUES('signup_email', 'ua', 'Email');
INSERT INTO `languages` VALUES('signup_email_must_be_valid', 'en', 'E-mail has to be valid.');
INSERT INTO `languages` VALUES('signup_email_must_be_valid', 'ru', 'Email адрес должен быть верным. Вам прийдет подтверждающее письмо, на которое вы должны отреагировать. Ваш email больше не будет использован. Если в течение 3х дней вы не отреагируете на письмо, ваш аккаунт будет удален автоматически.');
INSERT INTO `languages` VALUES('signup_email_must_be_valid', 'ua', 'Email адреса повинна бути вірною. Вам прийде підтверджуючий лист, на яке ви повинні відреагувати. Ваш email більше не буде використаний. Якщо протягом 3х днів ви не відреагуєте на лист, ваш обліковий запис буде видалено автоматично.');
INSERT INTO `languages` VALUES('signup_email_notice', 'en', 'This email must be used to login this site.');
INSERT INTO `languages` VALUES('signup_email_notice', 'ru', 'Этот адрес будет использован для входа на сайт.');
INSERT INTO `languages` VALUES('signup_email_notice', 'ua', 'Ця адреса буде використаний для входу на сайт.');
INSERT INTO `languages` VALUES('signup_female', 'en', 'Woman');
INSERT INTO `languages` VALUES('signup_female', 'ru', 'Девушка');
INSERT INTO `languages` VALUES('signup_female', 'ua', 'Дівчина');
INSERT INTO `languages` VALUES('signup_gender', 'en', 'Sex');
INSERT INTO `languages` VALUES('signup_gender', 'ru', 'Пол');
INSERT INTO `languages` VALUES('signup_gender', 'ua', 'Стать');
INSERT INTO `languages` VALUES('signup_i_am_13_years_old_or_more', 'en', 'I am 13+ years old.');
INSERT INTO `languages` VALUES('signup_i_am_13_years_old_or_more', 'ru', 'Мне 13 лет или больше.');
INSERT INTO `languages` VALUES('signup_i_am_13_years_old_or_more', 'ua', 'Мені 13 років або більше.');
INSERT INTO `languages` VALUES('signup_i_have_read_rules', 'en', 'I read the <a href="rules.php" target="_blank">rules</a>.');
INSERT INTO `languages` VALUES('signup_i_have_read_rules', 'ru', 'Я прочитал(а) <a href="rules.php" target="_blank">правила</a>.');
INSERT INTO `languages` VALUES('signup_i_have_read_rules', 'ua', 'Я прочитав (а) <a href="rules.php" target="_blank">правила</a>.');
INSERT INTO `languages` VALUES('signup_i_will_read_faq', 'en', 'I will read the <a href="faq.php" target="_blank">FAQ</a> before asking questions.');
INSERT INTO `languages` VALUES('signup_i_will_read_faq', 'ru', 'Я буду читать <a href="faq.php" target="_blank">ЧаВо</a> прежде чем задавать вопросы.');
INSERT INTO `languages` VALUES('signup_i_will_read_faq', 'ua', 'Я буду читати <a href="faq.php" target="_blank">ЧаПи</a> перш ніж задавати питання.');
INSERT INTO `languages` VALUES('signup_male', 'en', 'Man');
INSERT INTO `languages` VALUES('signup_male', 'ru', 'Парень');
INSERT INTO `languages` VALUES('signup_male', 'ua', 'Хлопець');
INSERT INTO `languages` VALUES('signup_not_selected', 'en', '---- Not selected ----');
INSERT INTO `languages` VALUES('signup_not_selected', 'ru', '---- Не выбрано ----');
INSERT INTO `languages` VALUES('signup_not_selected', 'ua', '---- Не обрано ----');
INSERT INTO `languages` VALUES('signup_password', 'en', 'Password');
INSERT INTO `languages` VALUES('signup_password', 'ru', 'Пароль');
INSERT INTO `languages` VALUES('signup_password', 'ua', 'Пароль');
INSERT INTO `languages` VALUES('signup_password_again', 'en', 'Re-enter pasword');
INSERT INTO `languages` VALUES('signup_password_again', 'ru', 'Повторите пароль');
INSERT INTO `languages` VALUES('signup_password_again', 'ua', 'Повторіть пароль');
INSERT INTO `languages` VALUES('signup_pm', 'en', 'Hello dear new user. You have just registered on our site. Please check <a href="%s">Your rating stats</a> to be happy on our site.<br/><i>Best regards, site team.</i>');
INSERT INTO `languages` VALUES('signup_pm', 'ru', 'Здравствуйте. Вы только что зарегистрировались на нашем сайте. Пожалуйста, проверяйте <a href="%s">Свой рейтинг</a> чтобы счастливо использовать все преимущества сайта.<br /><i>С уважением, администрация.</i>');
INSERT INTO `languages` VALUES('signup_pm', 'ua', 'Вітаємо. Ви тільки що зареєструвалися на нашому сайті. Будь ласка, перевіряйте <a href="%s">Свій рейтинг</a> щоб щасливо використовувати всі переваги сайту.<br /><i>З повагою, адміністрація.</i>');
INSERT INTO `languages` VALUES('signup_pm_norating', 'en', 'Hello dear new user. You have just registered on our site. Feel free to be happy on our site.<br/><i>Best regards, site team.</i>');
INSERT INTO `languages` VALUES('signup_pm_norating', 'ru', 'Здравствуйте. Вы только что зарегистрировались на нашем сайте. Мы надеемся, что вы получите истинное удовольствие пользуясь им.<br /><i>С уважением, разработчики.</i>');
INSERT INTO `languages` VALUES('signup_pm_norating', 'ua', 'Вітаємо. Ви тільки що зареєструвалися на нашому сайті. Ми сподіваємося, що ви отримаєте задоволення користуючись ним.<br /><i>З повагою, розробники.</i>');
INSERT INTO `languages` VALUES('signup_signup', 'en', 'Registration');
INSERT INTO `languages` VALUES('signup_signup', 'ru', 'Регистрация');
INSERT INTO `languages` VALUES('signup_signup', 'ua', 'Реєстрація');
INSERT INTO `languages` VALUES('signup_successful', 'en', 'Successful registration');
INSERT INTO `languages` VALUES('signup_successful', 'ru', 'Успешная регистрация');
INSERT INTO `languages` VALUES('signup_successful', 'ua', 'Успішна реєстрація');
INSERT INTO `languages` VALUES('signup_username', 'en', 'User');
INSERT INTO `languages` VALUES('signup_username', 'ru', 'Пользователь');
INSERT INTO `languages` VALUES('signup_username', 'ua', 'Користувач');
INSERT INTO `languages` VALUES('signup_users_limit', 'en', 'Current user limit (%d) has been reached. Nonactive accounts are constantly deleting, please check again later...');
INSERT INTO `languages` VALUES('signup_users_limit', 'ru', 'Текущий лимит пользователей (%d) достигнут. Неактивные пользователи постоянно удаляются, пожалуйста вернитесь попозже...');
INSERT INTO `languages` VALUES('signup_users_limit', 'ua', 'Поточний ліміт користувачів (%d) досягнутий. Неактивні користувачі постійно видаляються, будь ласка ласка, пізніше...');
INSERT INTO `languages` VALUES('signup_use_cookies', 'en', 'For successful registration allow cookies.');
INSERT INTO `languages` VALUES('signup_use_cookies', 'ru', 'Для правильной регистрации активизируйте cookies.');
INSERT INTO `languages` VALUES('signup_use_cookies', 'ua', 'Для правильної реєстрації активізуйте cookies.');
INSERT INTO `languages` VALUES('since_your_last_visit', 'en', 'Since your last visit:<br />');
INSERT INTO `languages` VALUES('since_your_last_visit', 'ru', 'С момента вашего последнего визита:<br />');
INSERT INTO `languages` VALUES('since_your_last_visit', 'ua', 'З моменту вашого останнього візиту:<br />');
INSERT INTO `languages` VALUES('Site forum', 'en', 'Site forum');
INSERT INTO `languages` VALUES('Site forum', 'ru', 'Форум сайта');
INSERT INTO `languages` VALUES('site forum', 'ua', 'Форум сайта');
INSERT INTO `languages` VALUES('Site forum listing', 'en', 'Site forum listing');
INSERT INTO `languages` VALUES('Site forum listing', 'ru', 'Список форумов');
INSERT INTO `languages` VALUES('site forum listing', 'ua', 'Список форумів');
INSERT INTO `languages` VALUES('Site rules', 'en', 'Site rules');
INSERT INTO `languages` VALUES('Site rules', 'ru', 'Правила Сайта');
INSERT INTO `languages` VALUES('site rules', 'ua', 'Правила Сайту');
INSERT INTO `languages` VALUES('siteonoff', 'en', 'On/Off the site');
INSERT INTO `languages` VALUES('siteonoff', 'ru', 'Управление Отключением / Включением сайта и классами доступа');
INSERT INTO `languages` VALUES('siteonoff', 'ua', 'Управління Вимкненням / Включенням сайту і класами доступу');
INSERT INTO `languages` VALUES('site_enabled', 'en', 'Is site online?');
INSERT INTO `languages` VALUES('site_enabled', 'ru', 'Сайт включен?');
INSERT INTO `languages` VALUES('site_enabled', 'ua', 'Сайт увімкнений?');
INSERT INTO `languages` VALUES('site_timezone', 'en', 'Site timezone');
INSERT INTO `languages` VALUES('site_timezone', 'ru', 'Часовой пояс сайта');
INSERT INTO `languages` VALUES('site_timezone', 'ua', 'Часовий пояс сайту');
INSERT INTO `languages` VALUES('size', 'en', 'Size');
INSERT INTO `languages` VALUES('size', 'ru', 'Размер');
INSERT INTO `languages` VALUES('size', 'ua', 'Розмір');
INSERT INTO `languages` VALUES('size_exceeds', 'en', '<br />The size of your avatar more than %s kilobyte!');
INSERT INTO `languages` VALUES('size_exceeds', 'ru', '<br />Размер вашей аватары превышает %s килобайт!');
INSERT INTO `languages` VALUES('size_exceeds', 'ua', '<br />Розмір вашої аватари перевищує %s кілобайт!');
INSERT INTO `languages` VALUES('size_you_avatar', 'en', '<br />Size of your avatar %sx%s. Allowable size of %sx%s pixels');
INSERT INTO `languages` VALUES('size_you_avatar', 'ru', '<br />Размер вашего аватара %sx%s. Требуется размер не более %sx%s пикселей');
INSERT INTO `languages` VALUES('size_you_avatar', 'ua', '<br />Розмір вашого аватара %sx%s. Потрібно розмір не більше  %sx%s пікселів');
INSERT INTO `languages` VALUES('snatched', 'en', 'Snatched');
INSERT INTO `languages` VALUES('snatched', 'ru', 'Скачан');
INSERT INTO `languages` VALUES('snatched', 'ua', 'Завантажений');
INSERT INTO `languages` VALUES('social_downloaded', 'en', 'Download(s)');
INSERT INTO `languages` VALUES('social_downloaded', 'ru', 'Скачал(а)');
INSERT INTO `languages` VALUES('social_downloaded', 'ua', 'Завантажив (ла)');
INSERT INTO `languages` VALUES('social_friends', 'en', 'Friends');
INSERT INTO `languages` VALUES('social_friends', 'ru', 'Друзей');
INSERT INTO `languages` VALUES('social_friends', 'ua', 'Друзів');
INSERT INTO `languages` VALUES('social_leeching', 'en', 'Leeching');
INSERT INTO `languages` VALUES('social_leeching', 'ru', 'Качает');
INSERT INTO `languages` VALUES('social_leeching', 'ua', 'Качає');
INSERT INTO `languages` VALUES('social_newscomments', 'en', 'To news');
INSERT INTO `languages` VALUES('social_newscomments', 'ru', 'К новостям');
INSERT INTO `languages` VALUES('social_newscomments', 'ua', 'До новин');
INSERT INTO `languages` VALUES('social_nicknames', 'en', 'Nickname changes');
INSERT INTO `languages` VALUES('social_nicknames', 'ru', 'Менял ник, раз');
INSERT INTO `languages` VALUES('social_pagecomments', 'en', 'To pages');
INSERT INTO `languages` VALUES('social_pagecomments', 'ru', 'К страницам');
INSERT INTO `languages` VALUES('social_pagecomments', 'ua', 'До сторінкам');
INSERT INTO `languages` VALUES('social_pages', 'en', 'Created pages');
INSERT INTO `languages` VALUES('social_pages', 'ru', 'Созданных страниц');
INSERT INTO `languages` VALUES('social_pages', 'ua', 'Створених сторінок');
INSERT INTO `languages` VALUES('social_pollcomments', 'en', 'To polls');
INSERT INTO `languages` VALUES('social_pollcomments', 'ru', 'К опросам');
INSERT INTO `languages` VALUES('social_pollcomments', 'ua', 'До опитуваннь');
INSERT INTO `languages` VALUES('social_relcomments', 'en', 'To release');
INSERT INTO `languages` VALUES('social_relcomments', 'ru', 'К релизам');
INSERT INTO `languages` VALUES('social_relcomments', 'ua', 'До релізів');
INSERT INTO `languages` VALUES('social_reqcomments', 'en', 'To request');
INSERT INTO `languages` VALUES('social_reqcomments', 'ru', 'К запросам');
INSERT INTO `languages` VALUES('social_reqcomments', 'ua', 'До запитів');
INSERT INTO `languages` VALUES('social_rgcomments', 'en', 'To releases group');
INSERT INTO `languages` VALUES('social_rgcomments', 'ru', 'К релиз-группам');
INSERT INTO `languages` VALUES('social_rgcomments', 'ua', 'До реліз-груп');
INSERT INTO `languages` VALUES('social_seeding', 'en', 'Seedeng');
INSERT INTO `languages` VALUES('social_seeding', 'ru', 'Раздает');
INSERT INTO `languages` VALUES('social_seeding', 'ua', 'Роздає');
INSERT INTO `languages` VALUES('social_uploaded', 'en', 'Created releases');
INSERT INTO `languages` VALUES('social_uploaded', 'ru', 'Созданных релизов');
INSERT INTO `languages` VALUES('social_uploaded', 'ua', 'Створених релізів');
INSERT INTO `languages` VALUES('social_usercomments', 'en', 'To Users');
INSERT INTO `languages` VALUES('social_usercomments', 'ru', 'К пользователям');
INSERT INTO `languages` VALUES('social_usercomments', 'ua', 'До користувачів');
INSERT INTO `languages` VALUES('some_fields_blank', 'en', 'Some fields are blank. <a href="denied:javascript:history.go(-1);">Back</a>');
INSERT INTO `languages` VALUES('some_fields_blank', 'ru', 'Некоторые поля не заполнены. <a href="denied:javascript:history.go(-1);">Вернуться назад</a>');
INSERT INTO `languages` VALUES('some_fields_blank', 'ua', 'Деякі поля не заповнені. <a href="denied:javascript:history.go(-1);">Повернутися назад</a>');
INSERT INTO `languages` VALUES('sort', 'en', 'Display order');
INSERT INTO `languages` VALUES('sort', 'ru', 'Порядок');
INSERT INTO `languages` VALUES('sort', 'ua', 'Порядок');
INSERT INTO `languages` VALUES('spam', 'en', 'Spam');
INSERT INTO `languages` VALUES('spam', 'ru', 'Спам');
INSERT INTO `languages` VALUES('spam', 'ua', 'Спам');
INSERT INTO `languages` VALUES('spec', 'en', 'Specialization');
INSERT INTO `languages` VALUES('spec', 'ru', 'Специализация');
INSERT INTO `languages` VALUES('spec', 'ua', 'Спеціалізація');
INSERT INTO `languages` VALUES('speed_above', 'en', 'These users of your network neighbors, which means that you get from them the speed above.');
INSERT INTO `languages` VALUES('speed_above', 'ru', 'Эти пользователи ваши сетевые соседи, что означает что вы можете получить от них скорость выше.');
INSERT INTO `languages` VALUES('speed_above', 'ua', 'Ці користувачі ваші мережеві сусіди, що означає що ви можете отримати від них швидкість вище.');
INSERT INTO `languages` VALUES('spoiler', 'en', 'Hidden text');
INSERT INTO `languages` VALUES('spoiler', 'ru', 'Скрытый текст');
INSERT INTO `languages` VALUES('spoiler', 'ua', 'Прихований текст');
INSERT INTO `languages` VALUES('SQL/Cron debug', 'en', 'SQL/Cron debug');
INSERT INTO `languages` VALUES('SQL/Cron debug', 'ru', 'SQL/Cron дебаг');
INSERT INTO `languages` VALUES('sql/cron debug', 'ua', 'SQL/Cron дебаг');
INSERT INTO `languages` VALUES('staff', 'en', 'Staff');
INSERT INTO `languages` VALUES('staff', 'ru', 'Администрация');
INSERT INTO `languages` VALUES('staff', 'ua', 'Адміністрація');
INSERT INTO `languages` VALUES('staffmess', 'en', 'Mass private message');
INSERT INTO `languages` VALUES('staffmess', 'ru', 'Массовое ЛС');
INSERT INTO `languages` VALUES('staffmess', 'ua', 'Масове ПП');
INSERT INTO `languages` VALUES('staff_functions', 'en', 'Staff functions');
INSERT INTO `languages` VALUES('staff_functions', 'ru', 'Инструменты владельца');
INSERT INTO `languages` VALUES('staff_functions', 'ua', 'Інструменти власника');
INSERT INTO `languages` VALUES('stampadmin', 'en', 'Stamps');
INSERT INTO `languages` VALUES('stampadmin', 'ru', 'Штампы и печати');
INSERT INTO `languages` VALUES('stampadmin', 'ua', 'Штампи й печатки');
INSERT INTO `languages` VALUES('stamps', 'en', 'Stamps');
INSERT INTO `languages` VALUES('stamps', 'ru', 'Штампы');
INSERT INTO `languages` VALUES('stamps', 'ua', 'Штампи');
INSERT INTO `languages` VALUES('stamps_seals', 'en', 'Stamps and seals');
INSERT INTO `languages` VALUES('stamps_seals', 'ru', 'Штампы и печати');
INSERT INTO `languages` VALUES('stamps_seals', 'ua', 'Штампи й печатки');
INSERT INTO `languages` VALUES('Started at', 'en', 'Started at');
INSERT INTO `languages` VALUES('Started at', 'ru', 'Добавлено');
INSERT INTO `languages` VALUES('started at', 'ua', 'Додано');
INSERT INTO `languages` VALUES('statistic', 'en', 'Statistics');
INSERT INTO `languages` VALUES('statistic', 'ru', 'Статистика');
INSERT INTO `languages` VALUES('statistic', 'ua', 'Статистика');
INSERT INTO `languages` VALUES('statistics', 'en', 'Statistics');
INSERT INTO `languages` VALUES('statistics', 'ru', 'Статистика');
INSERT INTO `languages` VALUES('statistics', 'ua', 'Статистика');
INSERT INTO `languages` VALUES('stats', 'en', 'View statistics');
INSERT INTO `languages` VALUES('stats', 'ru', 'Статистика');
INSERT INTO `languages` VALUES('stats', 'ua', 'Статистика');
INSERT INTO `languages` VALUES('status', 'en', 'Status');
INSERT INTO `languages` VALUES('status', 'ru', 'Статус');
INSERT INTO `languages` VALUES('status', 'ua', 'Статус');
INSERT INTO `languages` VALUES('Sticky', 'en', 'Sticky');
INSERT INTO `languages` VALUES('Sticky', 'ru', 'Прикреплен');
INSERT INTO `languages` VALUES('sticky', 'ua', 'Прикріплений');
INSERT INTO `languages` VALUES('subcats', 'en', 'Extra categories');
INSERT INTO `languages` VALUES('subcats', 'ru', 'Дополнительные категории');
INSERT INTO `languages` VALUES('subcats', 'ua', 'Додаткові категорії');
INSERT INTO `languages` VALUES('subject', 'en', 'Subject');
INSERT INTO `languages` VALUES('subject', 'ru', 'Тема');
INSERT INTO `languages` VALUES('subject', 'ua', 'Тема');
INSERT INTO `languages` VALUES('submit', 'en', 'Send');
INSERT INTO `languages` VALUES('submit', 'ru', 'Отправить');
INSERT INTO `languages` VALUES('submit', 'ua', 'Надіслати');
INSERT INTO `languages` VALUES('Submit comment', 'en', 'Submit comment');
INSERT INTO `languages` VALUES('Submit comment', 'ru', 'Отправить комментарий');
INSERT INTO `languages` VALUES('Submit comment', 'ua', 'Надіслати коментар');
INSERT INTO `languages` VALUES('subnet_mask', 'en', 'Subnet masks');
INSERT INTO `languages` VALUES('subnet_mask', 'ru', 'Маски подсети');
INSERT INTO `languages` VALUES('subnet_mask', 'ua', 'Маски підмережі');
INSERT INTO `languages` VALUES('subscribe_last_comment', 'en', 'Subscribe for last comment');
INSERT INTO `languages` VALUES('subscribe_last_comment', 'ru', 'Последнее сообщение');
INSERT INTO `languages` VALUES('subscribe_last_comment', 'ua', 'Останнє повідомлення');
INSERT INTO `languages` VALUES('subscribe_length', 'en', 'Subscription Period (0 - indefinitely)');
INSERT INTO `languages` VALUES('subscribe_length', 'ru', 'Период подписки (0 - бесконечно)');
INSERT INTO `languages` VALUES('subscribe_length', 'ua', 'Період підписки (0 - нескінченно)');
INSERT INTO `languages` VALUES('subscribe_list', 'en', 'Subscribe list');
INSERT INTO `languages` VALUES('subscribe_list', 'ru', 'Лист подписки');
INSERT INTO `languages` VALUES('subscribe_list', 'ua', 'Лист підписки');
INSERT INTO `languages` VALUES('subscribe_no', 'en', 'No new comments in threads, on which you were signed up.');
INSERT INTO `languages` VALUES('subscribe_no', 'ru', 'Нет новых комментариев в темах, на которые вы подписаны.');
INSERT INTO `languages` VALUES('subscribe_no', 'ua', 'Немає нових коментарів у темах, на які ви підписані.');
INSERT INTO `languages` VALUES('subscribe_unneeded', 'en', 'This public release of the group, subscribe to its release is not necessary');
INSERT INTO `languages` VALUES('subscribe_unneeded', 'ru', 'Это публичная релиз группа, подписываться на ее релизы не надо');
INSERT INTO `languages` VALUES('subscribe_unneeded', 'ua', 'Це публічна реліз група, підписуватися на її релізи не треба');
INSERT INTO `languages` VALUES('subscribe_until', 'en', 'Subscribe to');
INSERT INTO `languages` VALUES('subscribe_until', 'ru', 'Подписка до');
INSERT INTO `languages` VALUES('subscribe_until', 'ua', 'Підписка до');
INSERT INTO `languages` VALUES('success', 'en', 'Successfully');
INSERT INTO `languages` VALUES('success', 'ru', 'Успешно');
INSERT INTO `languages` VALUES('success', 'ua', 'Успішно');
INSERT INTO `languages` VALUES('Successful', 'en', 'Successful');
INSERT INTO `languages` VALUES('Successful', 'ru', 'Успешно');
INSERT INTO `languages` VALUES('successful', 'ua', 'Успішно');
INSERT INTO `languages` VALUES('successful_upload', 'en', 'Successful upload!');
INSERT INTO `languages` VALUES('successful_upload', 'ru', 'Успешная заливка!');
INSERT INTO `languages` VALUES('successful_upload', 'ua', 'Успішна заливка!');
INSERT INTO `languages` VALUES('success_invite', 'en', 'You have successfully subscribed to the release of the group');
INSERT INTO `languages` VALUES('success_invite', 'ru', 'Вы успешно подписались на релизы этой группы');
INSERT INTO `languages` VALUES('success_invite', 'ua', 'Ви успішно підписалися на релізи цієї групи');
INSERT INTO `languages` VALUES('succes_upload', 'en', '<b>Your avatar has been successfully uploaded to the server!</b><hr /> Filename: <b></b>');
INSERT INTO `languages` VALUES('succes_upload', 'ru', '<b>Ваша аватара была успешно загружёна на сервер!</b><hr /> Название файла: <b></b>');
INSERT INTO `languages` VALUES('succes_upload', 'ua', '<b>Ваша аватара була успішно завантажена на сервер!</b><hr /> Назва файлу: <b></b>');
INSERT INTO `languages` VALUES('succ_logout', 'en', 'Successful logout');
INSERT INTO `languages` VALUES('succ_logout', 'ru', 'Успешный выход');
INSERT INTO `languages` VALUES('succ_logout', 'ua', 'Успішний вихід');
INSERT INTO `languages` VALUES('succ_purif', 'en', 'was successfully cleaned');
INSERT INTO `languages` VALUES('succ_purif', 'ru', 'был успешно очищен');
INSERT INTO `languages` VALUES('succ_purif', 'ua', 'був успішно очищений');
INSERT INTO `languages` VALUES('suggestions', 'en', 'Suggestions');
INSERT INTO `languages` VALUES('suggestions', 'ru', 'Предложения');
INSERT INTO `languages` VALUES('suggestions', 'ua', 'Пропозиції');
INSERT INTO `languages` VALUES('support', 'en', 'Support');
INSERT INTO `languages` VALUES('support', 'ru', 'Поддержка');
INSERT INTO `languages` VALUES('support', 'ua', 'Підтримка');
INSERT INTO `languages` VALUES('sure_mark_delete', 'en', 'Are you sure you want to delete marked messages?');
INSERT INTO `languages` VALUES('sure_mark_delete', 'ru', 'Вы уверены, что хотите удалить выбранные сообщения?');
INSERT INTO `languages` VALUES('sure_mark_delete', 'ua', 'Ви впевнені, що хочете видалити вибрані повідомлення?');
INSERT INTO `languages` VALUES('sure_mark_read', 'en', 'Are you sure you want to mark selected messages as read?');
INSERT INTO `languages` VALUES('sure_mark_read', 'ru', 'Вы уверены, что хотите пометить выбранные сообщения как прочитанные?');
INSERT INTO `languages` VALUES('sure_mark_read', 'ua', 'Ви впевнені, що хочете позначити вибрані повідомлення як прочитані?');
INSERT INTO `languages` VALUES('sysop_account_activated', 'en', 'Your account is activated! You are logged in. Now you can go to the <a href="%s/"><b>main</b></a> page start using your account.');
INSERT INTO `languages` VALUES('sysop_account_activated', 'ru', 'Ваш аккаунт активирован! Вы автоматически вошли. Теперь вы можете <a href="%s/"><b>перейти на главную</b></a> и начать использовать ваш уккаунт.');
INSERT INTO `languages` VALUES('sysop_account_activated', 'ua', 'Ваш обліковий запис активовано! Ви автоматично увійшли. Тепер ви можете <a href="%s/"><b>перейти на головну</b></a> і почати використовувати ваш обліковий запис.');
INSERT INTO `languages` VALUES('sysop_activated', 'en', 'Administrator`s account successfully activated');
INSERT INTO `languages` VALUES('sysop_activated', 'ru', 'Аккаунт администратора успешно активирован');
INSERT INTO `languages` VALUES('sysop_activated', 'ua', 'Акаунт адміністратора успішно активований');
INSERT INTO `languages` VALUES('taken_from_torrent', 'en', 'Undertakes from torrent. <b>Please, use an intelligible names.</b>');
INSERT INTO `languages` VALUES('taken_from_torrent', 'ru', '<b>Миссия / The Mission (2008) [DVDrip, r.g. TV-Shows]</b>, <b>Макс Пейн / Max Payne (2001) [PC]</b>, <b>Дом2 / Дом2 (2000-3000) [TVrip, 499 выпуск]</b>');
INSERT INTO `languages` VALUES('taken_from_torrent', 'ua', '<b>Доктор Хаус (Сезон 6; ПОВНИЙ cерії 1-21) 720p / House M.D. (Season 6; Full episode 1-21) (2009) 720p [HDTV Ukr/Eng]</b>, <b>Домашній арешт (2010) [TVrip]</b>, <b>С.К.А.Й. - ! (Знак оклику) (2010) [MP3] | Pop-Rock</b>');
INSERT INTO `languages` VALUES('tbhome', 'en', 'Go to home page');
INSERT INTO `languages` VALUES('tbhome', 'ru', 'Перейти на главную страницу Torrentsbook');
INSERT INTO `languages` VALUES('tbhome', 'ua', 'Перейти на головну сторінку UA-torrents.net');
INSERT INTO `languages` VALUES('Template debug', 'en', 'Template debug');
INSERT INTO `languages` VALUES('Template debug', 'ru', 'Дебаг шаблона');
INSERT INTO `languages` VALUES('template debug', 'ua', 'Дебаг шаблону');
INSERT INTO `languages` VALUES('templatesadmin', 'en', 'Skins administration');
INSERT INTO `languages` VALUES('templatesadmin', 'ru', 'Настройки шкурок');
INSERT INTO `languages` VALUES('templatesadmin', 'ua', 'Установки шкурок');
INSERT INTO `languages` VALUES('testip', 'en', 'Test that IP was banned');
INSERT INTO `languages` VALUES('testip', 'ru', 'Проверка IP');
INSERT INTO `languages` VALUES('testip', 'ua', 'Перевірка IP');
INSERT INTO `languages` VALUES('test_humanity', 'en', 'You are not tested for humanity, please try again.');
INSERT INTO `languages` VALUES('test_humanity', 'ru', 'Вы не прошли проверку на человечность, попробуйте еще раз.');
INSERT INTO `languages` VALUES('test_humanity', 'ua', 'Ви не пройшли перевірку на людяність, спробуйте ще раз.');
INSERT INTO `languages` VALUES('test_port', 'en', 'Test port');
INSERT INTO `languages` VALUES('test_port', 'ru', 'Проверить порт');
INSERT INTO `languages` VALUES('test_port', 'ua', 'Перевірити порт');
INSERT INTO `languages` VALUES('test_releaser', 'en', 'Test releaser');
INSERT INTO `languages` VALUES('test_releaser', 'ru', 'Непроверенные релизы');
INSERT INTO `languages` VALUES('test_releaser', 'ua', 'Неперевірені релізи');
INSERT INTO `languages` VALUES('thanks', 'en', 'Thanks');
INSERT INTO `languages` VALUES('thanks', 'ru', 'Спасибо');
INSERT INTO `languages` VALUES('thanks', 'ua', 'Спасибі');
INSERT INTO `languages` VALUES('thanks_added', 'en', 'Your thanks added!');
INSERT INTO `languages` VALUES('thanks_added', 'ru', 'Спасибо добавлено!');
INSERT INTO `languages` VALUES('thanks_added', 'ua', 'Спасибі додано!');
INSERT INTO `languages` VALUES('thanks_for_registering', 'en', 'Thanks for your registration on %s! Now you can <a href="login.php">login</a> into the system.');
INSERT INTO `languages` VALUES('thanks_for_registering', 'ru', 'Спасибо что зарегистрировались на %s! Теперь вы можете <a href="login.php">войти</a> в систему.');
INSERT INTO `languages` VALUES('thanks_for_registering', 'ua', 'Дякуємо що зареєструвалися на %s! Тепер ви можете<a href="login.php">увійти</a> в систему.');
INSERT INTO `languages` VALUES('the_unique_1', 'en', 'Total Unique is connected to the tracker');
INSERT INTO `languages` VALUES('the_unique_1', 'ru', 'Всего к трекеру подключено уникальных');
INSERT INTO `languages` VALUES('the_unique_1', 'ua', 'Всього до трекера підключено унікальних');
INSERT INTO `languages` VALUES('this_account_activated', 'en', 'This account already activated. You can <a href="login.php">login</a> into it.');
INSERT INTO `languages` VALUES('this_account_activated', 'ru', 'Этот аккаунт уже активирован. Вы можете <a href="login.php">войти</a> с ним.');
INSERT INTO `languages` VALUES('this_account_activated', 'ua', 'Цей профіль вже активовано. Ви можете <a href="login.php">увійти</a> с ним.');
INSERT INTO `languages` VALUES('this_acc_active', 'en', 'This user is active at the moment. Sign impossible!');
INSERT INTO `languages` VALUES('this_acc_active', 'ru', 'Этот пользователь на данный момент активен. Вход невозможен!');
INSERT INTO `languages` VALUES('this_acc_active', 'ua', 'Цей користувач на даний момент активний. Вхід неможливий!');
INSERT INTO `languages` VALUES('this_acc_disabled', 'en', 'This account has been disabled.');
INSERT INTO `languages` VALUES('this_acc_disabled', 'ru', 'Этот аккаунт отключен.');
INSERT INTO `languages` VALUES('this_acc_disabled', 'ua', 'Цей акаунт відключений.');
INSERT INTO `languages` VALUES('this_category', 'en', 'this category');
INSERT INTO `languages` VALUES('this_category', 'ru', 'этой категории');
INSERT INTO `languages` VALUES('this_category', 'ua', 'цієї категорії');
INSERT INTO `languages` VALUES('this_is_dc_magnet', 'ru', '<div style="margin: auto;">Это DirectConnect-Magnet-ссылка. С помощью этой ссылки вы можете начать скачивание в DirectConnect клиенте (например, PeLink). Для того, чтобы начать скачивание, кликните по ссылке ниже:</div>');
INSERT INTO `languages` VALUES('this_is_dc_magnet', 'ua', '<div style="margin: auto;">Це DirectConnect-Magnet-посилання. За допомогою цього посилання ви можете почати скачування в DirectConnect клієнті (наприклад, PeLink). Для того, щоб почати скачування, натисніть на посилання нижче:</div>');
INSERT INTO `languages` VALUES('this_is_magnet', 'en', '<div style="margin: auto;">This Magnet-link. With this link you can start downloading a popular torrent client without saving the torrent file on your computer. To begin downloading, click on the link below:</div>');
INSERT INTO `languages` VALUES('this_is_magnet', 'ru', '<div style="margin: auto;">Это Magnet-ссылка. С помощью этой ссылки вы можете начать скачивание в популярных торрент-клиентах без сохранения торрент-файла на вашем компьютере. Для того, чтобы начать скачивание, кликните по ссылке ниже:</div>');
INSERT INTO `languages` VALUES('this_is_magnet', 'ua', '<div style="margin: auto;">Це Magnet-посилання. За допомогою цього посилання ви можете почати скачування в популярних торрент-клієнтів без збереження торрент-файлу на вашому комп''ютері. Для того, щоб почати скачування, натисніть на посилання нижче:</div>');
INSERT INTO `languages` VALUES('this_is_magnet_title', 'en', 'Magnet-link:');
INSERT INTO `languages` VALUES('this_is_magnet_title', 'ru', 'Magnet-ссылка:');
INSERT INTO `languages` VALUES('this_is_magnet_title', 'ua', 'Magnet-посилання:');
INSERT INTO `languages` VALUES('tiger_hash', 'en', 'TreeTiger hash');
INSERT INTO `languages` VALUES('tiger_hash', 'ru', 'TreeTiger хеш');
INSERT INTO `languages` VALUES('tiger_hash', 'ua', 'TreeTiger хеш');
INSERT INTO `languages` VALUES('tiger_hash_notice', 'en', '<small>Used to create links DirectConnect. All responsibility for the accuracy of this hash falls on you. DirectConnect download is available only with the flooding torrent-file. In re pouring torrent, do not forget that this hash also changes</small>');
INSERT INTO `languages` VALUES('tiger_hash_notice', 'ru', '<small>Используется для создания DirectConnect ссылок. Вся ответственность за правильность этого хеша ложится на вас. DirectConnect загрузка доступна только вместе с заливкой torrent-файла. При перезаливке торрента, не забудьте, что этот хеш тоже изменяется</small>');
INSERT INTO `languages` VALUES('tiger_hash_notice', 'ua', '<small>Використовується для створення DirectConnect посилань. Вся відповідальність за правильність цього хешу лягає на вас. DirectConnect завантаження доступна тільки разом із заливкою torrent-файлу. При перезаливання торрента, не забудьте, що цей хеш теж змінюється</small>');
INSERT INTO `languages` VALUES('time', 'en', 'Time');
INSERT INTO `languages` VALUES('time', 'ru', 'Время');
INSERT INTO `languages` VALUES('time', 'ua', 'Час');
INSERT INTO `languages` VALUES('times', 'en', 'times');
INSERT INTO `languages` VALUES('times', 'ru', 'раз');
INSERT INTO `languages` VALUES('times', 'ua', 'разів');
INSERT INTO `languages` VALUES('title', 'en', 'Title');
INSERT INTO `languages` VALUES('title', 'ru', 'Название');
INSERT INTO `languages` VALUES('title', 'ua', 'Назва');
INSERT INTO `languages` VALUES('to', 'en', 'to');
INSERT INTO `languages` VALUES('to', 'ru', 'в');
INSERT INTO `languages` VALUES('to', 'ua', 'в');
INSERT INTO `languages` VALUES('to panel index', 'en', 'To panel index');
INSERT INTO `languages` VALUES('to panel index', 'ru', 'К главной странице этой панели');
INSERT INTO `languages` VALUES('to panel index', 'ua', 'До головної сторінки цієї панелі');
INSERT INTO `languages` VALUES('Today', 'en', 'Today');
INSERT INTO `languages` VALUES('Today', 'ru', 'Сегодня');
INSERT INTO `languages` VALUES('today', 'ua', 'Сьогодні');
INSERT INTO `languages` VALUES('Top', 'en', 'Top');
INSERT INTO `languages` VALUES('Top', 'ru', 'Верх');
INSERT INTO `languages` VALUES('top', 'ua', 'Вгору');
INSERT INTO `languages` VALUES('Topic content', 'en', 'Topic content');
INSERT INTO `languages` VALUES('Topic content', 'ru', 'Содержание');
INSERT INTO `languages` VALUES('topic content', 'ua', 'Зміст');
INSERT INTO `languages` VALUES('Topic forum', 'en', 'Topic forum');
INSERT INTO `languages` VALUES('Topic forum', 'ru', 'Форум');
INSERT INTO `languages` VALUES('topic forum', 'ua', 'Форум');
INSERT INTO `languages` VALUES('Topic name', 'en', 'Topic name');
INSERT INTO `languages` VALUES('Topic name', 'ru', 'Название');
INSERT INTO `languages` VALUES('topic name', 'ua', 'Назва');
INSERT INTO `languages` VALUES('Topic title', 'en', 'Topic title');
INSERT INTO `languages` VALUES('Topic title', 'ru', 'Заголовок темы');
INSERT INTO `languages` VALUES('topic title', 'ua', 'Заголовок теми');
INSERT INTO `languages` VALUES('Topics', 'en', 'Topics');
INSERT INTO `languages` VALUES('Topics', 'ru', 'Тем');
INSERT INTO `languages` VALUES('topics', 'ua', 'Тем');
INSERT INTO `languages` VALUES('topic_created', 'en', 'Topic with title "%s" in "%s" successfully created, you will be reditected to it in 2 seconds. If not, click <a href="%s">on this link</a>');
INSERT INTO `languages` VALUES('topic_created', 'ru', 'Тема "%s" в разделе "%s" была успешно создана, вы будете направлены в нее в течение двух секунд. Если этого вдруг не произошло, нажмите <a href="%s">на эту ссылку</a>');
INSERT INTO `languages` VALUES('topic_created', 'ua', 'Тема "%s" у розділі "%s" була успішно створена, ви будете направлені в неї протягом двох секунд. Якщо цього раптом не сталося, натисніть <a href="%s">на це посилання</a>');
INSERT INTO `languages` VALUES('topten', 'en', 'Top 10');
INSERT INTO `languages` VALUES('topten', 'ru', 'Топ 10');
INSERT INTO `languages` VALUES('topten', 'ua', 'Топ 10');
INSERT INTO `languages` VALUES('torrent', 'en', 'Torrent');
INSERT INTO `languages` VALUES('torrent', 'ru', 'Торрент');
INSERT INTO `languages` VALUES('torrent', 'ua', 'Торрент');
INSERT INTO `languages` VALUES('torrents', 'en', 'Torrents');
INSERT INTO `languages` VALUES('torrents', 'ru', 'Релизы');
INSERT INTO `languages` VALUES('torrents', 'ua', 'Релізи');
INSERT INTO `languages` VALUES('torrent_clients', 'en', 'Torrent clients');
INSERT INTO `languages` VALUES('torrent_clients', 'ru', 'Торрент Клиенты');
INSERT INTO `languages` VALUES('torrent_clients', 'ua', 'Торрент Клієнти');
INSERT INTO `languages` VALUES('torrent_details', 'en', 'Torrent details');
INSERT INTO `languages` VALUES('torrent_details', 'ru', 'Детали релиза');
INSERT INTO `languages` VALUES('torrent_details', 'ua', 'Деталі релізу');
INSERT INTO `languages` VALUES('torrent_file', 'en', 'Torrent file');
INSERT INTO `languages` VALUES('torrent_file', 'ru', 'Torrent файл');
INSERT INTO `languages` VALUES('torrent_file', 'ua', 'Torrent файл');
INSERT INTO `languages` VALUES('torrent_info', 'en', 'Torrent info');
INSERT INTO `languages` VALUES('torrent_info', 'ru', 'Данные о торренте');
INSERT INTO `languages` VALUES('torrent_info', 'ua', 'Дані про торрент');
INSERT INTO `languages` VALUES('torrent_name', 'en', 'Torrent`s name');
INSERT INTO `languages` VALUES('torrent_name', 'ru', 'Название');
INSERT INTO `languages` VALUES('torrent_name', 'ua', 'Назва');
INSERT INTO `languages` VALUES('torrent_not_selected', 'en', 'Torrent not selected!');
INSERT INTO `languages` VALUES('torrent_not_selected', 'ru', 'Релиз не выбран!');
INSERT INTO `languages` VALUES('torrent_not_selected', 'ua', 'Реліз не вибрано!');
INSERT INTO `languages` VALUES('torrent_recounted', 'en', 'Torrents files are synchronised with a database, is changed torrents: %s');
INSERT INTO `languages` VALUES('torrent_recounted', 'ru', 'Торрент файлы синхронизированы с базой данных, изменено торрентов: %s');
INSERT INTO `languages` VALUES('torrent_recounted', 'ua', 'Торрент файли синхронізовані з базою даних, змінено торрентів: %s');
INSERT INTO `languages` VALUES('total', 'en', 'Total');
INSERT INTO `languages` VALUES('total', 'ru', 'Всего');
INSERT INTO `languages` VALUES('total', 'ua', 'Всього');
INSERT INTO `languages` VALUES('Total notifications', 'en', 'Total notifications');
INSERT INTO `languages` VALUES('Total notifications', 'ru', 'Всего уведомлений');
INSERT INTO `languages` VALUES('total notifications', 'ua', 'Всього повідомлень');
INSERT INTO `languages` VALUES('to_comment', 'en', 'Comment');
INSERT INTO `languages` VALUES('to_comment', 'ru', 'Комментировать');
INSERT INTO `languages` VALUES('to_comment', 'ua', 'Коментувати');
INSERT INTO `languages` VALUES('to_details', 'en', '<p style="text-align: right;">You can also <a href="details.php?id=%s"> go into the details of release</a></p>');
INSERT INTO `languages` VALUES('to_details', 'ru', '<p style="text-align: right;">Вы также можете <a href="details.php?id=%s">вернуться в детали релиза</a></p>');
INSERT INTO `languages` VALUES('to_details', 'ua', '<p style="text-align: right;">Ви також можете <a href="details.php?id=%s">повернутися в деталі релізу</a></p>');
INSERT INTO `languages` VALUES('to_history', 'en', ' | <a href="userhistory.php?id=%s">To history %s</a>');
INSERT INTO `languages` VALUES('to_history', 'ru', ' | <a href="userhistory.php?id=%s">К истории %s</a>');
INSERT INTO `languages` VALUES('to_history', 'ua', ' | <a href="userhistory.php?id=%s">До історії %s</a>');
INSERT INTO `languages` VALUES('to_notifs_list', 'en', 'To the list of notifications');
INSERT INTO `languages` VALUES('to_notifs_list', 'ru', 'К списку уведомлений');
INSERT INTO `languages` VALUES('to_notifs_list', 'ua', 'До переліку повідомлень');
INSERT INTO `languages` VALUES('to_relgroups_list', 'en', '[<a href="relgroups.php">To the list of release groups</a>]');
INSERT INTO `languages` VALUES('to_relgroups_list', 'ru', '[<a href="relgroups.php">К списку релиз групп</a>]');
INSERT INTO `languages` VALUES('to_relgroups_list', 'ua', '[<a href="relgroups.php">До переліку реліз груп</a>]');
INSERT INTO `languages` VALUES('to_rgadmin', 'en', '| <a href="rgadmin.php">By the management of release groups</a>');
INSERT INTO `languages` VALUES('to_rgadmin', 'ru', '| <a href="rgadmin.php">К администрированию релиз групп</a>');
INSERT INTO `languages` VALUES('to_rgadmin', 'ua', '| <a href="rgadmin.php">До адміністрування реліз груп</a>');
INSERT INTO `languages` VALUES('tracker', 'en', 'Tracker');
INSERT INTO `languages` VALUES('tracker', 'ru', 'Трекер');
INSERT INTO `languages` VALUES('tracker', 'ua', 'Трекер');
INSERT INTO `languages` VALUES('tracker_added', 'en', '<span style="color: green;">Tracker added:</span>');
INSERT INTO `languages` VALUES('tracker_added', 'ru', '<span style="color: green;">Трекер добавлен:</span>');
INSERT INTO `languages` VALUES('tracker_added', 'ua', '<span style="color: green;">Трекер доданий:</span>');
INSERT INTO `languages` VALUES('tracker_dead_torrents', 'en', 'Dead torrents');
INSERT INTO `languages` VALUES('tracker_dead_torrents', 'ru', 'Мертвых Торрентов');
INSERT INTO `languages` VALUES('tracker_dead_torrents', 'ua', 'Мертвих торрентів');
INSERT INTO `languages` VALUES('tracker_deleted', 'en', '<span style="color: red;">Tracker was deleted</span>');
INSERT INTO `languages` VALUES('tracker_deleted', 'ru', '<span style="color: red;">Трекер удален</span>');
INSERT INTO `languages` VALUES('tracker_deleted', 'ua', '<span style="color: red;">Трекер видалений</span>');
INSERT INTO `languages` VALUES('tracker_failed', 'en', '<span style="color: red;">The tracker is NOT added. Reason:</span>');
INSERT INTO `languages` VALUES('tracker_failed', 'ru', '<span style="color: red;">Трекер НЕ добавлен. Причина:</span>');
INSERT INTO `languages` VALUES('tracker_failed', 'ua', '<span style="color: red;">Трекер НЕ доданий. Причина:</span>');
INSERT INTO `languages` VALUES('tracker_leechers', 'en', 'Leechers');
INSERT INTO `languages` VALUES('tracker_leechers', 'ru', 'Качающих');
INSERT INTO `languages` VALUES('tracker_leechers', 'ua', 'Качающих');
INSERT INTO `languages` VALUES('tracker_peers', 'en', 'Active connections');
INSERT INTO `languages` VALUES('tracker_peers', 'ru', 'Активных подключений');
INSERT INTO `languages` VALUES('tracker_peers', 'ua', 'Активних підключень');
INSERT INTO `languages` VALUES('tracker_seeders', 'en', 'Seeding');
INSERT INTO `languages` VALUES('tracker_seeders', 'ru', 'Раздающих');
INSERT INTO `languages` VALUES('tracker_seeders', 'ua', 'Роздають');
INSERT INTO `languages` VALUES('tracker_seed_peer', 'en', 'Seeding/Leeching (%)');
INSERT INTO `languages` VALUES('tracker_seed_peer', 'ru', 'Раздающих/Качающих (%)');
INSERT INTO `languages` VALUES('tracker_seed_peer', 'ua', 'Раздають / Качають (%)');
INSERT INTO `languages` VALUES('tracker_skipped', 'en', '<span style="color: green;">Tracker has been registered, missing</span>');
INSERT INTO `languages` VALUES('tracker_skipped', 'ru', '<span style="color: green;">Трекер уже зарегестирован, пропущено</span>');
INSERT INTO `languages` VALUES('tracker_skipped', 'ua', '<span style="color: green;">Трекер вже зареєстрований, пропущено</span>');
INSERT INTO `languages` VALUES('tracker_torrents', 'en', 'Torrents');
INSERT INTO `languages` VALUES('tracker_torrents', 'ru', 'Релизов');
INSERT INTO `languages` VALUES('tracker_torrents', 'ua', 'Релізів');
INSERT INTO `languages` VALUES('trailer', 'en', 'trailer');
INSERT INTO `languages` VALUES('trailer', 'ru', 'Посмотреть трейлер');
INSERT INTO `languages` VALUES('trailer', 'ua', 'Подивитися трейлер');
INSERT INTO `languages` VALUES('ttl', 'en', 'TTL');
INSERT INTO `languages` VALUES('ttl', 'ru', 'TTL');
INSERT INTO `languages` VALUES('ttl', 'ua', 'TTL');
INSERT INTO `languages` VALUES('tv', 'en', 'Favorite TV shows:');
INSERT INTO `languages` VALUES('tv', 'ru', 'Любимые телешоу:');
INSERT INTO `languages` VALUES('tv', 'ua', 'Улюблені телешоу:');
INSERT INTO `languages` VALUES('type', 'en', 'Type');
INSERT INTO `languages` VALUES('type', 'ru', 'Тип');
INSERT INTO `languages` VALUES('type', 'ua', 'Тип');
INSERT INTO `languages` VALUES('ua', 'en', 'Українська (UA)');
INSERT INTO `languages` VALUES('ua', 'ru', 'Українська (UA)');
INSERT INTO `languages` VALUES('ua', 'ua', 'Українська (UA)');
INSERT INTO `languages` VALUES('ul_speed', 'en', 'Upload speed');
INSERT INTO `languages` VALUES('ul_speed', 'ru', 'Раздача');
INSERT INTO `languages` VALUES('ul_speed', 'ua', 'Роздача');
INSERT INTO `languages` VALUES('unable_peers', 'en', 'Unable to obtain data on the peers from remote tracker');
INSERT INTO `languages` VALUES('unable_peers', 'ru', 'Не удалось получить данные о пирах с удаленного трекера');
INSERT INTO `languages` VALUES('unable_peers', 'ua', 'Не вдалося отримати дані про бенкети з віддаленого трекера');
INSERT INTO `languages` VALUES('unable_to_create_account', 'en', 'Unable to create an account. Possibly, the name you have chosen is already taken.');
INSERT INTO `languages` VALUES('unable_to_create_account', 'ru', 'Невозможно создать аккаунт. Возможно имя пользователя уже занято.');
INSERT INTO `languages` VALUES('unable_to_create_account', 'ua', 'Неможливо створити обліковий запис. Можливо ім''я користувача вже зайнято.');
INSERT INTO `languages` VALUES('unable_to_read_torrent', 'en', 'Unable to read the torrent file.');
INSERT INTO `languages` VALUES('unable_to_read_torrent', 'ru', 'Невозможно прочитать торрент файл.');
INSERT INTO `languages` VALUES('unable_to_read_torrent', 'ua', 'Неможливо прочитати торрент файл.');
INSERT INTO `languages` VALUES('uncheck', 'en', 'Uncheck');
INSERT INTO `languages` VALUES('uncheck', 'ru', 'Отменить проверку');
INSERT INTO `languages` VALUES('uncheck', 'ua', 'Скасувати перевірку');
INSERT INTO `languages` VALUES('Unchecked', 'en', 'Unchecked');
INSERT INTO `languages` VALUES('Unchecked', 'ru', 'Непр.релизов');
INSERT INTO `languages` VALUES('unchecked', 'ua', 'Непер. релізів');
INSERT INTO `languages` VALUES('unchecked_only_moders', 'en', 'Watch unverified releases may only moderators or above');
INSERT INTO `languages` VALUES('unchecked_only_moders', 'ru', 'Смотреть непроверенные релизы могут только модераторы и выше');
INSERT INTO `languages` VALUES('unchecked_only_moders', 'ua', 'Дивитися неперевірені релізи можуть тільки модератори і вище');
INSERT INTO `languages` VALUES('unco', 'en', 'Unconfirmed users');
INSERT INTO `languages` VALUES('unco', 'ru', 'Неподтв. юзеры');
INSERT INTO `languages` VALUES('unco', 'ua', 'Непідтв. юзери');
INSERT INTO `languages` VALUES('unknown', 'en', 'Unknown');
INSERT INTO `languages` VALUES('unknown', 'ru', 'Неизвестно');
INSERT INTO `languages` VALUES('unknown', 'ua', 'Невідомо');
INSERT INTO `languages` VALUES('unknown_action', 'en', 'Unknown action');
INSERT INTO `languages` VALUES('unknown_action', 'ru', 'Неизвестное действие');
INSERT INTO `languages` VALUES('unknown_action', 'ua', 'Невідоме дію');
INSERT INTO `languages` VALUES('unknown_passkey', 'en', 'Unknown passkey!');
INSERT INTO `languages` VALUES('unknown_passkey', 'ru', 'Неизвестный пасскей!');
INSERT INTO `languages` VALUES('unknown_passkey', 'ua', 'Невідомий паскей!');
INSERT INTO `languages` VALUES('unread', 'en', 'Unread');
INSERT INTO `languages` VALUES('unread', 'ru', 'Новые ЛС');
INSERT INTO `languages` VALUES('unread', 'ua', 'Нові ПП');
INSERT INTO `languages` VALUES('un_upd_acc', 'en', 'Unable to update account.');
INSERT INTO `languages` VALUES('un_upd_acc', 'ru', 'Не удается обновить учетную запись.');
INSERT INTO `languages` VALUES('un_upd_acc', 'ua', 'Не вдається оновити обліковий запис.');
INSERT INTO `languages` VALUES('upd_users_inv_amn', 'en', 'The Quantity of invitations is updated');
INSERT INTO `languages` VALUES('upd_users_inv_amn', 'ru', 'Кол-во приглашений обновлено');
INSERT INTO `languages` VALUES('upd_users_inv_amn', 'ua', 'Кількість запрошень оновлено');
INSERT INTO `languages` VALUES('upload', 'en', 'Upload');
INSERT INTO `languages` VALUES('upload', 'ru', 'Загрузить');
INSERT INTO `languages` VALUES('upload', 'ua', 'Завантажити');
INSERT INTO `languages` VALUES('uploaded', 'en', 'You uploaded a release');
INSERT INTO `languages` VALUES('uploaded', 'ru', 'Вы загрузили релиз');
INSERT INTO `languages` VALUES('uploaded', 'ua', 'Ви завантажили реліз');
INSERT INTO `languages` VALUES('uploadeder', 'en', 'Uploading');
INSERT INTO `languages` VALUES('uploadeder', 'ru', 'Раздает');
INSERT INTO `languages` VALUES('uploadeder', 'ua', 'Раздає');
INSERT INTO `languages` VALUES('rel_check_pm', 'en', 'Your release is checking');
INSERT INTO `languages` VALUES('rel_check_pm', 'ru', 'Ваш релиз проверяется');
INSERT INTO `languages` VALUES('rel_check_pm', 'ua', 'Ваш релиз проверяется');
INSERT INTO `languages` VALUES('new_mod_comment_rel', 'en', 'Moderator has just added new comment to your release "%s". It means that you may be able to fix some release details to get it published. Please visit <a href="%s">your release page</a> for better experience. Thanks.');
INSERT INTO `languages` VALUES('new_mod_comment_rel', 'ru', 'Модератор только что добавил комментарий к вашему релизу "%s". Это может означать то, что вам необходимо поправить его детали, для того, чтобы он был размещен на сайте. Пожалуйста, посетите <a href="%">страницу вашего релиза</a> для получения дополнительных сведений. Спасибо.');
INSERT INTO `languages` VALUES('new_mod_comment_rel', 'ua', 'Модератор только что добавил комментарий к вашему релизу "%s". Это может означать то, что вам необходимо поправить его детали, для того, чтобы он был размещен на сайте. Пожалуйста, посетите <a href="%">страницу вашего релиза</a> для получения дополнительных сведений. Спасибо.');
INSERT INTO `languages` VALUES('uploaders', 'en', 'View uploaders & stats');
INSERT INTO `languages` VALUES('uploaders', 'ru', 'Аплоадеры');
INSERT INTO `languages` VALUES('uploaders', 'ua', 'Аплоадери');
INSERT INTO `languages` VALUES('upload_avatar', 'en', 'Upload avatar');
INSERT INTO `languages` VALUES('upload_avatar', 'ru', 'Загрузка аватара');
INSERT INTO `languages` VALUES('upload_avatar', 'ua', 'Завантаження аватара');
INSERT INTO `languages` VALUES('upload_notice', 'en', 'You have just uploaded a release. Your rating will be increased by %s after moderator check your release. You must <a href="download.php?id=%s">Download .torrent file</a> and begin seeding in your torrent-client. Thank you.');
INSERT INTO `languages` VALUES('upload_notice', 'ru', 'Вы только что загрузили новый релиз на сайт, после его проверки модератором вам прибавлено %s рейтинга. Вы должны <a href="download.php?id=%s&amp;ok">Скачать torrent файл</a> или <a href="%s">Перейти по Magnet-ссылке</a> и начать раздачу в торрент-клиенте. Спасибо.');
INSERT INTO `languages` VALUES('upload_notice', 'ua', 'Ви тільки що завантажили новий реліз на сайт, після його перевірки модератором вам додано %s рейтингу. Ви повинні <a href="download.php?id=%s&amp;ok">Завантажити torrent файл</a> або <a href="%s">Перейти по Magnet-посиланню</a> і почати роздачу в торрент-клієнті. Спасибі.');
INSERT INTO `languages` VALUES('upload_notice_norating', 'en', 'You have just uploaded a release. You must <a href="download.php?id=%s">Download .torrent file</a> and begin seeding in your torrent-client. Thank you.');
INSERT INTO `languages` VALUES('upload_notice_norating', 'ru', 'Вы только что загрузили новый релиз на сайт. Вы должны <a href="download.php?id=%s">Скачать torrent файл</a> или <a href="%s">Перейти по Magnet-ссылке</a> и начать раздачу в торрент-клиенте. Спасибо.');
INSERT INTO `languages` VALUES('upload_notice_norating', 'ua', 'Ви тільки що завантажили новий реліз на сайт. Ви повинні <a href="download.php?id=%s">Завантажити torrent файл</a> або <a href="%s">Перейти по Magnet-посиланню</a> і почати роздачу в торрент-клієнті. Спасибі.');
INSERT INTO `languages` VALUES('upload_torrent', 'en', 'Upload torrent');
INSERT INTO `languages` VALUES('upload_torrent', 'ru', 'Загрузить релиз');
INSERT INTO `languages` VALUES('upload_torrent', 'ua', 'Завантажити реліз');
INSERT INTO `languages` VALUES('up_size', 'en', 'Up size');
INSERT INTO `languages` VALUES('up_size', 'ru', 'Раздал');
INSERT INTO `languages` VALUES('up_size', 'ua', 'Раздав');
INSERT INTO `languages` VALUES('user', 'en', 'User');
INSERT INTO `languages` VALUES('user', 'ru', 'Юзер');
INSERT INTO `languages` VALUES('user', 'ua', 'Юзер');
INSERT INTO `languages` VALUES('Usercomments', 'en', 'Usercomments');
INSERT INTO `languages` VALUES('Usercomments', 'ru', 'Комм. к пользователям');
INSERT INTO `languages` VALUES('usercomments', 'ua', 'Ком. до користувачів');
INSERT INTO `languages` VALUES('username', 'en', 'Username');
INSERT INTO `languages` VALUES('username', 'ru', 'Пользователь');
INSERT INTO `languages` VALUES('username', 'ua', 'Пользователь');
INSERT INTO `languages` VALUES('users', 'en', 'Users');
INSERT INTO `languages` VALUES('users', 'ru', 'Пользователи');
INSERT INTO `languages` VALUES('users', 'ua', 'Користувачі');
INSERT INTO `languages` VALUES('users_deleted', 'en', 'All subscribers to the group successfully removed');
INSERT INTO `languages` VALUES('users_deleted', 'ru', 'Все подписчики группы успешно удалены');
INSERT INTO `languages` VALUES('users_deleted', 'ua', 'Усі передплатники групи успішно видалено');
INSERT INTO `languages` VALUES('users_disabled', 'en', 'Disabled');
INSERT INTO `languages` VALUES('users_disabled', 'ru', 'Отключенных');
INSERT INTO `languages` VALUES('users_disabled', 'ua', 'Відключених');
INSERT INTO `languages` VALUES('users_registered', 'en', 'Registered');
INSERT INTO `languages` VALUES('users_registered', 'ru', 'Зарегистрированных');
INSERT INTO `languages` VALUES('users_registered', 'ua', 'Зареєстрованих');
INSERT INTO `languages` VALUES('users_sl', 'en', 'users');
INSERT INTO `languages` VALUES('users_sl', 'ru', 'пользователей');
INSERT INTO `languages` VALUES('users_sl', 'ua', 'користувачів');
INSERT INTO `languages` VALUES('users_unconfirmed', 'en', 'Unconfirmed');
INSERT INTO `languages` VALUES('users_unconfirmed', 'ru', 'Неподтвержденных');
INSERT INTO `languages` VALUES('users_unconfirmed', 'ua', 'Непідтверджених');
INSERT INTO `languages` VALUES('users_uploaders', 'en', 'Uploaders');
INSERT INTO `languages` VALUES('users_uploaders', 'ru', 'Аплоадеров');
INSERT INTO `languages` VALUES('users_uploaders', 'ua', 'Аплоадеров');
INSERT INTO `languages` VALUES('users_vips', 'en', 'VIP');
INSERT INTO `languages` VALUES('users_vips', 'ru', 'VIP');
INSERT INTO `languages` VALUES('users_vips', 'ua', 'VIP');
INSERT INTO `languages` VALUES('users_warned', 'en', 'Warned');
INSERT INTO `languages` VALUES('users_warned', 'ru', 'Предупреждённых');
INSERT INTO `languages` VALUES('users_warned', 'ua', 'Попереджених');
INSERT INTO `languages` VALUES('user_0', 'en', 'View users with rating below 0');
INSERT INTO `languages` VALUES('user_0', 'ru', 'Пользователи с рейтингом ниже 0');
INSERT INTO `languages` VALUES('user_0', 'ua', 'Користувачі з рейтингом нижче 0');
INSERT INTO `languages` VALUES('user_agent', 'en', '<b>User agent</b>:');
INSERT INTO `languages` VALUES('user_agent', 'ru', '<b>User agent</b>:');
INSERT INTO `languages` VALUES('user_agent', 'ua', '<b>User agent</b>:');
INSERT INTO `languages` VALUES('user_bans', 'en', 'View disabled users');
INSERT INTO `languages` VALUES('user_bans', 'ru', 'Отключенные пользователи');
INSERT INTO `languages` VALUES('user_bans', 'ua', 'Відключені користувачі');
INSERT INTO `languages` VALUES('user_menu', 'en', 'Personal menu');
INSERT INTO `languages` VALUES('user_menu', 'ru', 'Персональное меню');
INSERT INTO `languages` VALUES('user_menu', 'ua', 'Персональне меню');
INSERT INTO `languages` VALUES('user_name', 'en', 'User name');
INSERT INTO `languages` VALUES('user_name', 'ru', 'Имя пользователя');
INSERT INTO `languages` VALUES('user_name', 'ua', 'Ім''я користувача');
INSERT INTO `languages` VALUES('user_notice_sent', 'en', 'User %s sent a message of friendship offered for you. He can become your friend, only after you confirm a friend request');
INSERT INTO `languages` VALUES('user_notice_sent', 'ru', 'Пользователю %s отправлено сообщение о предложенной вами дружбе. Он сможет стать вашим другом, только после того, как подтвердит ваши намерения');
INSERT INTO `languages` VALUES('user_notice_sent', 'ua', 'Користувачу %s відправлено повідомлення про запропоновану вами дружбу. Він зможе стати вашим другом, тільки після того, як підтвердить ваші наміри');
INSERT INTO `languages` VALUES('user_unconfirmed', 'en', '<small>Friendship is not confirmed, you can not make a present</small>');
INSERT INTO `languages` VALUES('user_unconfirmed', 'ru', '<small>Дружба не подтверждена, вы не можете сделать подарок</small>');
INSERT INTO `languages` VALUES('user_unconfirmed', 'ua', '<small>Дружба не підтверджена, ви не можете зробити подарунок</small>');
INSERT INTO `languages` VALUES('u_presents', 'en', 'User presents');
INSERT INTO `languages` VALUES('u_presents', 'ru', 'Подарки пользователю');
INSERT INTO `languages` VALUES('u_presents', 'ua', 'Подарунки користувачеві');
INSERT INTO `languages` VALUES('Value', 'en', 'Value');
INSERT INTO `languages` VALUES('Value', 'ru', 'Значение');
INSERT INTO `languages` VALUES('value', 'ua', 'Значение');
INSERT INTO `languages` VALUES('view', 'en', 'View');
INSERT INTO `languages` VALUES('view', 'ru', 'Посмотреть');
INSERT INTO `languages` VALUES('view', 'ua', 'Переглянути');
INSERT INTO `languages` VALUES('View list of users', 'en', 'View list of users');
INSERT INTO `languages` VALUES('View list of users', 'ru', 'Список пользователей');
INSERT INTO `languages` VALUES('view list of users', 'ua', 'Список користувачів');
INSERT INTO `languages` VALUES('View private messages', 'en', 'View private messages');
INSERT INTO `languages` VALUES('View private messages', 'ru', 'Посмотреть ЛС');
INSERT INTO `languages` VALUES('view private messages', 'ua', 'Переглянути ПП');
INSERT INTO `languages` VALUES('View site log', 'en', 'View site log');
INSERT INTO `languages` VALUES('View site log', 'ru', 'Лог сайта');
INSERT INTO `languages` VALUES('view site log', 'ua', 'Логи сайту');
INSERT INTO `languages` VALUES('View uploaders & stats', 'en', 'View uploaders & stats');
INSERT INTO `languages` VALUES('View uploaders & stats', 'ru', 'Статистика аплоадеров');
INSERT INTO `languages` VALUES('view uploaders &amp; stats', 'ua', 'Статистика Аплоадер');
INSERT INTO `languages` VALUES('viewing_profile', 'en', 'You see Profile');
INSERT INTO `languages` VALUES('viewing_profile', 'ru', 'Вы смотрите профиль');
INSERT INTO `languages` VALUES('viewing_profile', 'ua', 'Ви переглядаєте профіль');
INSERT INTO `languages` VALUES('views', 'en', 'Views');
INSERT INTO `languages` VALUES('views', 'ru', 'Просмотров');
INSERT INTO `languages` VALUES('views', 'ua', 'Переглядів');
INSERT INTO `languages` VALUES('view_all', 'en', 'View all');
INSERT INTO `languages` VALUES('view_all', 'ru', 'Посмотреть все');
INSERT INTO `languages` VALUES('view_all', 'ua', 'Переглянути всі');
INSERT INTO `languages` VALUES('view_images', 'en', 'View images');
INSERT INTO `languages` VALUES('view_images', 'ru', 'Просмотр картинки');
INSERT INTO `languages` VALUES('view_images', 'ua', 'Перегляд зображення');
INSERT INTO `languages` VALUES('view_users', 'en', 'View subscribers');
INSERT INTO `languages` VALUES('view_users', 'ru', 'Посмотреть подписчиков');
INSERT INTO `languages` VALUES('view_users', 'ua', 'Переглянути передплатників');
INSERT INTO `languages` VALUES('view_xxx', 'en', 'Show XXX releases');
INSERT INTO `languages` VALUES('view_xxx', 'ru', 'Показывать XXX релизы');
INSERT INTO `languages` VALUES('view_xxx', 'ua', 'Показувати XXX релізи');
INSERT INTO `languages` VALUES('visible', 'en', 'Visible');
INSERT INTO `languages` VALUES('visible', 'ru', 'Видимый');
INSERT INTO `languages` VALUES('visible', 'ua', 'Видимий');
INSERT INTO `languages` VALUES('Visible for', 'en', 'Visible for');
INSERT INTO `languages` VALUES('Visible for', 'ru', 'Видимый для');
INSERT INTO `languages` VALUES('visible for', 'ua', 'Видимий для');
INSERT INTO `languages` VALUES('visitors', 'en', 'Visitors');
INSERT INTO `languages` VALUES('visitors', 'ru', 'Гости');
INSERT INTO `languages` VALUES('visitors', 'ua', 'Гості');
INSERT INTO `languages` VALUES('vkcom', 'en', 'We in vkontakte.ru');
INSERT INTO `languages` VALUES('vkcom', 'ru', 'Мы В Контакте');
INSERT INTO `languages` VALUES('vkcom', 'ua', 'Ми В Контакті');
INSERT INTO `languages` VALUES('vote', 'en', 'Vote');
INSERT INTO `languages` VALUES('vote', 'ru', 'Голосовать');
INSERT INTO `languages` VALUES('vote', 'ua', 'Голосувати');
INSERT INTO `languages` VALUES('voted', 'en', 'Vote added!');
INSERT INTO `languages` VALUES('voted', 'ru', 'Голос добавлен!');
INSERT INTO `languages` VALUES('voted', 'ua', 'Голос доданий!');
INSERT INTO `languages` VALUES('votes', 'en', 'Total votes');
INSERT INTO `languages` VALUES('votes', 'ru', 'Голосов');
INSERT INTO `languages` VALUES('votes', 'ua', 'Голосів');
INSERT INTO `languages` VALUES('vote_1', 'en', 'Awful!');
INSERT INTO `languages` VALUES('vote_1', 'ru', 'Ужасно!');
INSERT INTO `languages` VALUES('vote_1', 'ua', 'Жахливо!');
INSERT INTO `languages` VALUES('vote_2', 'en', 'Bad');
INSERT INTO `languages` VALUES('vote_2', 'ru', 'Плохо');
INSERT INTO `languages` VALUES('vote_2', 'ua', 'Погано');
INSERT INTO `languages` VALUES('vote_3', 'en', 'Fine');
INSERT INTO `languages` VALUES('vote_3', 'ru', 'Нормально');
INSERT INTO `languages` VALUES('vote_3', 'ua', 'Нормально');
INSERT INTO `languages` VALUES('vote_4', 'en', 'Good');
INSERT INTO `languages` VALUES('vote_4', 'ru', 'Хорошо');
INSERT INTO `languages` VALUES('vote_4', 'ua', 'Добре');
INSERT INTO `languages` VALUES('vote_5', 'en', 'Excellent!');
INSERT INTO `languages` VALUES('vote_5', 'ru', 'Отлично!');
INSERT INTO `languages` VALUES('vote_5', 'ua', 'Дуже добре!');
INSERT INTO `languages` VALUES('wait', 'en', 'Waiting..');
INSERT INTO `languages` VALUES('wait', 'ru', 'Ожидание');
INSERT INTO `languages` VALUES('wait', 'ua', 'Очікування');
INSERT INTO `languages` VALUES('warned', 'en', 'View warned users');
INSERT INTO `languages` VALUES('warned', 'ru', 'Предупр. юзеры');
INSERT INTO `languages` VALUES('warned', 'ua', 'Попереджув. юзери');
INSERT INTO `languages` VALUES('warning', 'en', 'For the publication of data files to your account will be immediately blocked without any warning. The publication of these files was officially banned by the right holder, or prohibited for any other reason, does not depend on us.<br />The ban is valid until until the file is located in <a href="viewcensoredtorrents.php">list of prohibited releases</a>.');
INSERT INTO `languages` VALUES('warning', 'ru', 'За публикацию данных релизов ваш аккаунт будет немедленно заблокирован без каких-либо предупреждений. Публикация этих фильмов была официально запрещена правообладателем, либо запрещена по любой другой причине, не зависящей от нас.<br />Запрет действует до тех пор, пока фильм находится в <a href="viewcensoredtorrents.php">списке запрещенных релизов</a>.');
INSERT INTO `languages` VALUES('warning', 'ua', 'За публікацію даних релізів ваш акаунт буде негайно заблоковано без будь-яких попереджень. Публікація цих файлів була офіційно заборонена правовласником, або заборонена з якоїсь іншої причини, не залежної від нас.<br />Заборона діє до тих пір, поки реліз знаходиться у <a href="viewcensoredtorrents.php">списку заборонених релізів</a>.');
INSERT INTO `languages` VALUES('warning_removed', 'en', '- Warning removed');
INSERT INTO `languages` VALUES('warning_removed', 'ru', '- Предупреждение снял');
INSERT INTO `languages` VALUES('warning_removed', 'ua', '- Попередження зняв');
INSERT INTO `languages` VALUES('welcome_back', 'en', 'Welcome back,');
INSERT INTO `languages` VALUES('welcome_back', 'ru', 'Добро пожаловать,');
INSERT INTO `languages` VALUES('welcome_back', 'ua', 'Ласкаво просимо,');
INSERT INTO `languages` VALUES('we_vkontakte', 'en', 'We vkontakte');
INSERT INTO `languages` VALUES('we_vkontakte', 'ru', 'Мы вконтакте');
INSERT INTO `languages` VALUES('we_vkontakte', 'ua', 'Ми вконтакті...');
INSERT INTO `languages` VALUES('what_present', 'en', 'What exactly do you wish to present?');
INSERT INTO `languages` VALUES('what_present', 'ru', 'Что именно вы хотите подарить?');
INSERT INTO `languages` VALUES('what_present', 'ua', 'Що саме ви хочете подарувати?');
INSERT INTO `languages` VALUES('whos_online', 'en', 'Who`s online');
INSERT INTO `languages` VALUES('whos_online', 'ru', 'Кто Онлайн');
INSERT INTO `languages` VALUES('whos_online', 'ua', 'Хто Онлайн');
INSERT INTO `languages` VALUES('who_online', 'en', 'Who is online');
INSERT INTO `languages` VALUES('who_online', 'ru', 'Кто онлайн');
INSERT INTO `languages` VALUES('who_online', 'ua', 'Хто онлайн');
INSERT INTO `languages` VALUES('with', 'en', 'with');
INSERT INTO `languages` VALUES('with', 'ru', 'с');
INSERT INTO `languages` VALUES('with', 'ua', 'з');
INSERT INTO `languages` VALUES('With wish of', 'en', 'With wish of');
INSERT INTO `languages` VALUES('With wish of', 'ru', 'С пожеланием');
INSERT INTO `languages` VALUES('with wish of', 'ua', 'З побажанням');
INSERT INTO `languages` VALUES('Without .tpl extention', 'en', 'Without .tpl extention');
INSERT INTO `languages` VALUES('Without .tpl extention', 'ru', 'Без расширения .tpl');
INSERT INTO `languages` VALUES('without .tpl extention', 'ua', 'Без розширення .tpl');
INSERT INTO `languages` VALUES('Word', 'en', 'Word');
INSERT INTO `languages` VALUES('Word', 'ru', 'Слово');
INSERT INTO `languages` VALUES('word', 'ua', 'Слово');
INSERT INTO `languages` VALUES('wrong_id', 'en', 'Direct access to this script not allowed, or wrong ID');
INSERT INTO `languages` VALUES('wrong_id', 'ru', 'Прямой доступ к этому скрипту запрещен, или у вас неверный ID');
INSERT INTO `languages` VALUES('wrong_id', 'ua', 'Прямий доступ до цього скрипту заборонений, або у вас невірний ID');
INSERT INTO `languages` VALUES('wrote_at', 'en', 'Added at');
INSERT INTO `languages` VALUES('wrote_at', 'ru', 'Писал в');
INSERT INTO `languages` VALUES('wrote_at', 'ua', 'Писав у');
INSERT INTO `languages` VALUES('xxx_release', 'en', 'XXX (porno) release');
INSERT INTO `languages` VALUES('xxx_release', 'ru', 'XXX (прон) релиз');
INSERT INTO `languages` VALUES('xxx_release', 'ua', 'XXX реліз');
INSERT INTO `languages` VALUES('yes', 'en', 'Yes');
INSERT INTO `languages` VALUES('yes', 'ru', 'Да');
INSERT INTO `languages` VALUES('yes', 'ua', 'Так');
INSERT INTO `languages` VALUES('Yesterday', 'en', 'Yesterday');
INSERT INTO `languages` VALUES('Yesterday', 'ru', 'Вчера');
INSERT INTO `languages` VALUES('yesterday', 'ua', 'Вчора');
INSERT INTO `languages` VALUES('yours', 'en', 'Yours');
INSERT INTO `languages` VALUES('yours', 'ru', 'Ваш');
INSERT INTO `languages` VALUES('yours', 'ua', 'Ваш');
INSERT INTO `languages` VALUES('your_class_is_lower', 'en', 'Your class is too low to change the password for this user');
INSERT INTO `languages` VALUES('your_class_is_lower', 'ru', 'Ваш класс слишком низок для измениния пароля этому пользователю');
INSERT INTO `languages` VALUES('your_class_is_lower', 'ua', 'Ваш клас занадто низький для зміни пароля цьому користувачеві');
INSERT INTO `languages` VALUES('your_email', 'en', 'Your Email:');
INSERT INTO `languages` VALUES('your_email', 'ru', 'Ваш Email:');
INSERT INTO `languages` VALUES('your_email', 'ua', 'Ваш Email:');
INSERT INTO `languages` VALUES('your_invites', 'en', 'Your invitations');
INSERT INTO `languages` VALUES('your_invites', 'ru', 'Ваши неиспользованные приглашения');
INSERT INTO `languages` VALUES('your_invites', 'ua', 'Ваші невикористані запрошення');
INSERT INTO `languages` VALUES('your_ip', 'en', 'Your IP');
INSERT INTO `languages` VALUES('your_ip', 'ru', 'Ваш IP');
INSERT INTO `languages` VALUES('your_ip', 'ua', 'Ваш IP');
INSERT INTO `languages` VALUES('your_message', 'en', 'Your message: ');
INSERT INTO `languages` VALUES('your_message', 'ru', 'Ваше сообщение: ');
INSERT INTO `languages` VALUES('your_message', 'ua', 'Ваше повідомлення: ');
INSERT INTO `languages` VALUES('your_name', 'en', 'Your name:');
INSERT INTO `languages` VALUES('your_name', 'ru', 'Ваше имя:');
INSERT INTO `languages` VALUES('your_name', 'ua', 'Ваше ім''я:');
INSERT INTO `languages` VALUES('you_already_reported', 'en', 'You have already reported');
INSERT INTO `languages` VALUES('you_already_reported', 'ru', 'Вы уже пожаловались');
INSERT INTO `languages` VALUES('you_already_reported', 'ua', 'Ви вже поскаржилися');
INSERT INTO `languages` VALUES('you_can_start_seeding', 'en', 'Now you can start seeding. <b>Keep in mind</b> that your torrent will not be visible until you will start seeding it!');
INSERT INTO `languages` VALUES('you_can_start_seeding', 'ru', 'Вы можете начинать раздачу. <b>Учтите</b> что торрент не будет виден пока вы не начнете раздавать!');
INSERT INTO `languages` VALUES('you_can_start_seeding', 'ua', 'Ви можете починати роздачу. <b>Врахуйте</b> що торрент не буде видно поки ви не почнете роздавати!');
INSERT INTO `languages` VALUES('you_edit', 'en', 'You are editing');
INSERT INTO `languages` VALUES('you_edit', 'ru', 'Вы редактируете');
INSERT INTO `languages` VALUES('you_edit', 'ua', 'Ви редагуєте');
INSERT INTO `languages` VALUES('you_have', 'en', 'You have');
INSERT INTO `languages` VALUES('you_have', 'ru', 'У вас');
INSERT INTO `languages` VALUES('you_have', 'ua', 'У вас');
INSERT INTO `languages` VALUES('you_have_friends', 'en', 'Add %s new friends');
INSERT INTO `languages` VALUES('you_have_friends', 'ru', 'Добавилось %s новых друзей');
INSERT INTO `languages` VALUES('you_have_friends', 'ua', 'Додалося %s нових друзів');
INSERT INTO `languages` VALUES('you_have_newscomments', 'en', 'Add %s new comments to the news');
INSERT INTO `languages` VALUES('you_have_newscomments', 'ru', 'Добавилось %s новых комментариев к новостям');
INSERT INTO `languages` VALUES('you_have_newscomments', 'ua', 'Додалося %s нових коментарів до новин');
INSERT INTO `languages` VALUES('you_have_no_bookmarks', 'en', 'You have no bookmarks!');
INSERT INTO `languages` VALUES('you_have_no_bookmarks', 'ru', 'У вас нет закладок!');
INSERT INTO `languages` VALUES('you_have_no_bookmarks', 'ua', 'У вас немає закладок!');
INSERT INTO `languages` VALUES('you_have_pagecomments', 'en', 'Add %s new comments to the page');
INSERT INTO `languages` VALUES('you_have_pagecomments', 'ru', 'Добавилось %s новых комментариев к страницам');
INSERT INTO `languages` VALUES('you_have_pagecomments', 'ua', 'Додалося %s нових коментарів до сторінок');
INSERT INTO `languages` VALUES('you_have_pages', 'en', 'Add %s new pages');
INSERT INTO `languages` VALUES('you_have_pages', 'ru', 'Добавилось %s новых страниц');
INSERT INTO `languages` VALUES('you_have_pages', 'ua', 'Додалося %s нових сторінок');
INSERT INTO `languages` VALUES('you_have_pollcomments', 'en', 'Add %s new comments to the polls');
INSERT INTO `languages` VALUES('you_have_pollcomments', 'ru', 'Добавилось %s новых комментариев к опросам');
INSERT INTO `languages` VALUES('you_have_pollcomments', 'ua', 'Додалося %s нових коментарів до опитувань');
INSERT INTO `languages` VALUES('you_have_relcomments', 'en', 'Add %s new comments to the releases');
INSERT INTO `languages` VALUES('you_have_relcomments', 'ru', 'Добавилось %s новых комментариев к релизам');
INSERT INTO `languages` VALUES('you_have_relcomments', 'ua', 'Додалося %s нових коментарів до релізів');
INSERT INTO `languages` VALUES('you_have_reports', 'en', 'Add %s new reports');
INSERT INTO `languages` VALUES('you_have_reports', 'ru', 'Добавилось %s новых жалоб');
INSERT INTO `languages` VALUES('you_have_reports', 'ua', 'Додалося %s нових скарг');
INSERT INTO `languages` VALUES('you_have_reqcomments', 'en', 'Add %s new comments to the requests');
INSERT INTO `languages` VALUES('you_have_reqcomments', 'ru', 'Добавилось %s новых комментариев к запросам');
INSERT INTO `languages` VALUES('you_have_reqcomments', 'ua', 'Додалося %s нових коментарів до запитів');
INSERT INTO `languages` VALUES('you_have_rgcomments', 'en', 'Add %s new comments to the release groups');
INSERT INTO `languages` VALUES('you_have_rgcomments', 'ru', 'Добавилось %s новых комментариев к релиз-группам');
INSERT INTO `languages` VALUES('you_have_rgcomments', 'ua', 'Додалося %s нових коментарів до реліз-груп');
INSERT INTO `languages` VALUES('you_have_torrents', 'en', 'Add %s new releases');
INSERT INTO `languages` VALUES('you_have_torrents', 'ru', 'Добавилось %s новых релизов');
INSERT INTO `languages` VALUES('you_have_torrents', 'ua', 'Додалося %s нових релізів');
INSERT INTO `languages` VALUES('you_have_unchecked', 'en', 'Add %s new unchecked releases');
INSERT INTO `languages` VALUES('you_have_unchecked', 'ru', 'Добавилось %s новых непроверенных релизов');
INSERT INTO `languages` VALUES('you_have_unchecked', 'ua', 'Додалося %s нових неперевірених релізів');
INSERT INTO `languages` VALUES('you_have_unread', 'en', 'Add %s unread PM');
INSERT INTO `languages` VALUES('you_have_unread', 'ru', 'Добавилось %s непрочитанных ЛС');
INSERT INTO `languages` VALUES('you_have_unread', 'ua', 'Додалося %s непрочитаних ПП');
INSERT INTO `languages` VALUES('you_have_usercomments', 'en', 'Add %s new comments to the users');
INSERT INTO `languages` VALUES('you_have_usercomments', 'ru', 'Добавилось %s новых комментариев к пользователям');
INSERT INTO `languages` VALUES('you_have_usercomments', 'ua', 'Додалося %s нових коментарів до користувачів');
INSERT INTO `languages` VALUES('you_have_users', 'en', 'Add %s new users');
INSERT INTO `languages` VALUES('you_have_users', 'ru', 'Добавилось %s новых пользователей');
INSERT INTO `languages` VALUES('you_have_users', 'ua', 'Додалося %s нових користувачів');
INSERT INTO `languages` VALUES('you_have_voted_for_this_torrent', 'en', 'You have rated this torrent as');
INSERT INTO `languages` VALUES('you_have_voted_for_this_torrent', 'ru', 'вы оценили этот релиз как');
INSERT INTO `languages` VALUES('you_have_voted_for_this_torrent', 'ua', 'ви оцінили цей реліз як');
INSERT INTO `languages` VALUES('you_not_logged', 'en', 'You are not logged in!');
INSERT INTO `languages` VALUES('you_not_logged', 'ru', 'Вы не зарегистрированы в системе!');
INSERT INTO `languages` VALUES('you_not_logged', 'ua', 'Ви не зареєстровані в системі!');
INSERT INTO `languages` VALUES('you_people', 'en', 'Are you human?');
INSERT INTO `languages` VALUES('you_people', 'ru', 'Вы человек??');
INSERT INTO `languages` VALUES('you_people', 'ua', 'Ви людина??');
INSERT INTO `languages` VALUES('you_success_presented', 'en', 'You have been given a %s present!');
INSERT INTO `languages` VALUES('you_success_presented', 'ru', 'Вы успешно подарили %s подарок!');
INSERT INTO `languages` VALUES('you_success_presented', 'ua', 'Ви успішно подарували %s подарунок!');
INSERT INTO `languages` VALUES('you_succ_logout', 'en', 'You have successfully logout!');
INSERT INTO `languages` VALUES('you_succ_logout', 'ru', 'Вы успешно вышли!');
INSERT INTO `languages` VALUES('you_succ_logout', 'ua', 'Ви успішно вийшли!');
INSERT INTO `languages` VALUES('you_want_to_delete_x_click_here', 'en', 'You want to delete %s. Click <a href="%s">here</a> to proceed.');
INSERT INTO `languages` VALUES('you_want_to_delete_x_click_here', 'ru', 'Вы хотите удалить %s. Нажмите <a href="%s">сюда</a> если вы уверены.');
INSERT INTO `languages` VALUES('you_want_to_delete_x_click_here', 'ua', 'Ви хочете видалити %s. Натисніть <a href="%s">сюди</a> якщо ви впевнені.');
INSERT INTO `languages` VALUES('you_warning_removed', 'en', 'Your warning removed');
INSERT INTO `languages` VALUES('you_warning_removed', 'ru', 'Ваше предупреждение снял');
INSERT INTO `languages` VALUES('you_warning_removed', 'ua', 'Ваше попередження зняв');
INSERT INTO `languages` VALUES('you_watching_friends', 'en', 'You are currently viewing: new friends');
INSERT INTO `languages` VALUES('you_watching_friends', 'ru', 'Вы просматриваете: новых друзей');
INSERT INTO `languages` VALUES('you_watching_friends', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_newscomments', 'en', 'You are currently viewing: the new comments to the news');
INSERT INTO `languages` VALUES('you_watching_newscomments', 'ru', 'Вы просматриваете: новые комментарии к новостям');
INSERT INTO `languages` VALUES('you_watching_newscomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_pagecomments', 'en', 'You are currently viewing: the new comments to the pages');
INSERT INTO `languages` VALUES('you_watching_pagecomments', 'ru', 'Вы просматриваете: новые комментарии к страницам');
INSERT INTO `languages` VALUES('you_watching_pagecomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_pages', 'en', 'You are currently viewing: the new page');
INSERT INTO `languages` VALUES('you_watching_pages', 'ru', 'Вы просматриваете: новые страницы');
INSERT INTO `languages` VALUES('you_watching_pages', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_pollcomments', 'en', 'You are currently viewing: the new comments to the polls');
INSERT INTO `languages` VALUES('you_watching_pollcomments', 'ru', 'Вы просматриваете: новые комментарии к опросам');
INSERT INTO `languages` VALUES('you_watching_pollcomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_relcomments', 'en', 'You are currently viewing: the new comments to the releases');
INSERT INTO `languages` VALUES('you_watching_relcomments', 'ru', 'Вы просматриваете: новые комментарии к релизам');
INSERT INTO `languages` VALUES('you_watching_relcomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_reports', 'en', 'You are currently viewing: the new reports');
INSERT INTO `languages` VALUES('you_watching_reports', 'ru', 'Вы просматриваете: новые жалобы');
INSERT INTO `languages` VALUES('you_watching_reports', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_reqcomments', 'en', 'You are currently viewing: the new comments to the request');
INSERT INTO `languages` VALUES('you_watching_reqcomments', 'ru', 'Вы просматриваете: новые комментарии к запросам');
INSERT INTO `languages` VALUES('you_watching_reqcomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_rgcomments', 'en', 'You are currently viewing: the new comments to the release groups');
INSERT INTO `languages` VALUES('you_watching_rgcomments', 'ru', 'Вы просматриваете: новые комментарии к релиз-группам');
INSERT INTO `languages` VALUES('you_watching_rgcomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_torrents', 'en', 'You are currently viewing: the new release');
INSERT INTO `languages` VALUES('you_watching_torrents', 'ru', 'Вы просматриваете: новые релизы');
INSERT INTO `languages` VALUES('you_watching_torrents', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_unchecked', 'en', 'You are currently viewing: the new untested release');
INSERT INTO `languages` VALUES('you_watching_unchecked', 'ru', 'Вы просматриваете: новые непроверенные релизы');
INSERT INTO `languages` VALUES('you_watching_unchecked', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_unread', 'en', 'You are currently viewing: unread PM');
INSERT INTO `languages` VALUES('you_watching_unread', 'ru', 'Вы просматриваете: непрочитанные ЛС');
INSERT INTO `languages` VALUES('you_watching_unread', 'ua', 'Ви переглядаєте: непрочитані ПП');
INSERT INTO `languages` VALUES('you_watching_usercomments', 'en', 'You are currently viewing: the new comments to the users');
INSERT INTO `languages` VALUES('you_watching_usercomments', 'ru', 'Вы просматриваете: новые комментарии к пользователям');
INSERT INTO `languages` VALUES('you_watching_usercomments', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('you_watching_users', 'en', 'You are currently viewing: the new user');
INSERT INTO `languages` VALUES('you_watching_users', 'ru', 'Вы просматриваете: новые пользователи');
INSERT INTO `languages` VALUES('you_watching_users', 'ua', 'Ви переглядаєте:');
INSERT INTO `languages` VALUES('zodiac', 'en', 'Zodiac');
INSERT INTO `languages` VALUES('zodiac', 'ru', 'Знак зодиака');
INSERT INTO `languages` VALUES('zodiac', 'ua', 'Знак зодіаку');
INSERT INTO `languages` VALUES('File', 'en', 'File');
INSERT INTO `languages` VALUES('File', 'ru', 'Файл');
INSERT INTO `languages` VALUES('releases_found', 'en', 'Found %s releases');
INSERT INTO `languages` VALUES('releases_found', 'ru', 'Найдено %s релизов');
INSERT INTO `languages` VALUES('releases_found', 'ua', 'Найдено %s релизов');
INSERT INTO `languages` VALUES('All levels', 'en', 'All levels');
INSERT INTO `languages` VALUES('All levels', 'ru', 'Все уровни');
INSERT INTO `languages` VALUES('All levels', 'ua', 'Все уровни');
INSERT INTO `languages` VALUES('Show more', 'ru', 'Показать больше');
INSERT INTO `languages` VALUES('Show more', 'en', 'Show more');
INSERT INTO `languages` VALUES('Junior Releaser', 'en', 'Junior Releaser');
INSERT INTO `languages` VALUES('Junior Releaser', 'ru', 'Начинающий Релизер');
INSERT INTO `languages` VALUES('Junior Releaser', 'ua', 'Начинающий Релизер');
INSERT INTO `languages` VALUES('priv_mass_pm', 'ru', 'Возможность создавать массовые рассылки личных сообщений');
INSERT INTO `languages` VALUES('priv_view_pms', 'ru', 'Возможность просматривать личные сообщения других пользователей');
INSERT INTO `languages` VALUES('priv_truncate_logs', 'ru', 'Возможность удалять логи сайта');
INSERT INTO `languages` VALUES('priv_view_logs', 'ru', 'Возможность просмативать логи сайта');
INSERT INTO `languages` VALUES('priv_langadmin', 'ru', 'Доступ к панели администрирования языков');
INSERT INTO `languages` VALUES('priv_view_duplicate_ip', 'ru', 'Возможность просматривать информацию о дублирующихся IP-адресах');
INSERT INTO `languages` VALUES('priv_add_invites', 'ru', 'Возможность добавлять инвайтов к пользователям');
INSERT INTO `languages` VALUES('priv_approve_invites', 'ru', 'Возможность подтверждать инвайты пользователей');
INSERT INTO `languages` VALUES('priv_edit_forum_settings', 'ru', 'Возможность администрирования форума в админ-панели');
INSERT INTO `languages` VALUES('priv_send_emails', 'ru', 'Возможность отсылать email-сообщения');
INSERT INTO `languages` VALUES('priv_edit_releases', 'ru', 'Возможность редактировать не свои релизы');
INSERT INTO `languages` VALUES('priv_delete_releases', 'ru', 'Возможность удаления релизов с сайта');
INSERT INTO `languages` VALUES('priv_delete_site_users', 'ru', 'Возможность удаления пользователей сайта');
INSERT INTO `languages` VALUES('priv_edit_dchubs', 'ru', 'Возможность администрирования хабов Direct-Connect');
INSERT INTO `languages` VALUES('priv_cronadmin', 'ru', 'Возможность администрирования переодических заданий');
INSERT INTO `languages` VALUES('priv_edit_countries', 'ru', 'Возможнось редактирования зарегестрированных для пользователей стран');
INSERT INTO `languages` VALUES('priv_edit_general_configuration', 'ru', 'Возможность редактирования общей конфигурации сайта');
INSERT INTO `languages` VALUES('priv_edit_comments', 'ru', 'Возможность редактирования комментариев сайта');
INSERT INTO `languages` VALUES('priv_clear_caches', 'ru', 'Возможность очистки кеша релизера');
INSERT INTO `languages` VALUES('priv_category_admin', 'ru', 'Возможность администрирования категорий релизов');
INSERT INTO `languages` VALUES('priv_blocksadmin', 'ru', 'Возможность администрирования блоков');
INSERT INTO `languages` VALUES('priv_bans_admin', 'ru', 'Возможность бана пользовательских аккаунтов');
INSERT INTO `languages` VALUES('priv_add_users', 'ru', 'Возможность добавления пользователей сайта вручную');
INSERT INTO `languages` VALUES('priv_access_to_ban_emails', 'ru', 'Доступ к бану email-адресов');
INSERT INTO `languages` VALUES('priv_access_to_admincp', 'ru', 'Доступ к панели администрирования');
INSERT INTO `languages` VALUES('priv_access_to_private_relgroups', 'ru', 'Доступ к приватным релиз-группам');
INSERT INTO `languages` VALUES('priv_view_disabled_site_notice', 'ru', 'Просмотр уведомления об отключенном сайте');
INSERT INTO `languages` VALUES('priv_view_sql_debug', 'ru', 'Просмотр дебаг-информации SQL');
INSERT INTO `languages` VALUES('priv_deny_disabled_site', 'ru', 'Доступ к форме просмотра выключенного сайта');
INSERT INTO `languages` VALUES('priv_debug_template', 'ru', 'Смотреть дебаг-информацию шаблона');
INSERT INTO `languages` VALUES('priv_is_guest', 'ru', 'Вы гость');
INSERT INTO `languages` VALUES('priv_is_power_user', 'ru', 'Вы опытный пользователь');
INSERT INTO `languages` VALUES('priv_is_vip', 'ru', 'У вас VIP аккаунт');
INSERT INTO `languages` VALUES('priv_is_releaser', 'ru', 'У вас есть права релизера');
INSERT INTO `languages` VALUES('priv_is_moderator', 'ru', 'У вас есть права модератора');
INSERT INTO `languages` VALUES('priv_is_owner', 'ru', 'Вы владелец');
INSERT INTO `languages` VALUES('priv_post_releases_approved', 'en', 'Ability to post automatically approved releases');
INSERT INTO `languages` VALUES('priv_edit_site_templates', 'en', 'Access to site templates administration panel');
INSERT INTO `languages` VALUES('priv_view_general_statistics', 'en', 'Ability to view general site statistics');
INSERT INTO `languages` VALUES('priv_stampadmin', 'en', 'Access to stamps administration panel');
INSERT INTO `languages` VALUES('priv_spamadmin', 'en', 'Access to private message viewer (administration)');
INSERT INTO `languages` VALUES('priv_seo_admincp', 'en', 'Access to SEO-friedly URL administration panel');
INSERT INTO `languages` VALUES('priv_relgroups_admin', 'en', 'Access to release groups administration panel');
INSERT INTO `languages` VALUES('priv_edit_retrackers', 'en', 'Access to retracker administration panel');
INSERT INTO `languages` VALUES('priv_polls_operation', 'en', 'Ability to administer polls');
INSERT INTO `languages` VALUES('priv_change_user_passwords', 'en', 'Ability to change user passwords');
INSERT INTO `languages` VALUES('priv_news_operation', 'en', 'Ability to preform operations with site news');
INSERT INTO `languages` VALUES('priv_view_sql_stats', 'en', 'Ability to view SQL database statistics');
INSERT INTO `languages` VALUES('priv_add_comments_to_user', 'en', 'Ability to add moderation comments to users');
INSERT INTO `languages` VALUES('priv_ownsupport', 'en', 'Ability do add yourself to support desk');
INSERT INTO `languages` VALUES('priv_edit_users', 'en', 'Ability to change user account details');
INSERT INTO `languages` VALUES('priv_truncate_logs', 'en', 'Ability to delete site logs');
INSERT INTO `languages` VALUES('priv_view_logs', 'en', 'Ability to view site logs');
INSERT INTO `languages` VALUES('priv_langadmin', 'en', 'Access to language administration panel');
INSERT INTO `languages` VALUES('priv_view_duplicate_ip', 'en', 'View dupliate ip information');
INSERT INTO `languages` VALUES('priv_add_invites', 'en', 'Add new invites count to user');
INSERT INTO `languages` VALUES('priv_approve_invites', 'en', 'Ability to approve other user''s invites');
INSERT INTO `languages` VALUES('priv_edit_forum_settings', 'en', 'Ability to administer forum in admin panel');
INSERT INTO `languages` VALUES('priv_edit_releases', 'en', 'Ability to edit non-owned releases');
INSERT INTO `languages` VALUES('priv_cronadmin', 'en', 'Edit scheduled jobs configuration');
INSERT INTO `languages` VALUES('priv_edit_countries', 'en', 'Edit registered user countries');
INSERT INTO `languages` VALUES('priv_edit_general_configuration', 'en', 'Edit general releaser configuration');
INSERT INTO `languages` VALUES('priv_edit_comments', 'en', 'Ability to edit site comments');
INSERT INTO `languages` VALUES('priv_clear_caches', 'en', 'Ability to clear releaser cache');
INSERT INTO `languages` VALUES('priv_bans_admin', 'en', 'Ability to ban user accounts');
INSERT INTO `languages` VALUES('priv_add_users', 'en', 'Ability to add new users to site manually');
INSERT INTO `languages` VALUES('priv_access_to_ban_emails', 'en', 'Access to email bans administration');
INSERT INTO `languages` VALUES('priv_access_to_admincp', 'en', 'Access to admin control panel');
INSERT INTO `languages` VALUES('priv_access_to_private_relgroups', 'en', 'Access to private relgroups');
INSERT INTO `languages` VALUES('priv_view_sql_debug', 'en', 'View SQL debug information');
INSERT INTO `languages` VALUES('priv_deny_disabled_site', 'en', 'Deny form viewing disabled site');
INSERT INTO `languages` VALUES('priv_is_user', 'en', 'You are registered user');
INSERT INTO `languages` VALUES('priv_is_moderator', 'en', 'You have moderation rights');
INSERT INTO `languages` VALUES('priv_is_user', 'ru', 'Вы зарегестрированный пользователь');
INSERT INTO `languages` VALUES('priv_is_administrator', 'ru', 'Вы администратор');
INSERT INTO `languages` VALUES('priv_upload_releases', 'en', 'Ability to upload new releases to site');
INSERT INTO `languages` VALUES('priv_censored_admin', 'en', 'Ability to administrate censored releases');
INSERT INTO `languages` VALUES('priv_view_private_user_profiles', 'en', 'Ability to view private user profiles');
INSERT INTO `languages` VALUES('priv_requests_operation', 'en', 'Ability to magange release requests');
INSERT INTO `languages` VALUES('priv_edit_release_templates', 'en', 'Access to release templates administration');
INSERT INTO `languages` VALUES('priv_edit_relgroups', 'en', 'Ability to edit release groups');
INSERT INTO `languages` VALUES('priv_recountadmin', 'en', 'Access to synchronization panel');
INSERT INTO `languages` VALUES('priv_mass_pm', 'en', 'Ability to send mass PMs');
INSERT INTO `languages` VALUES('priv_view_pms', 'en', 'Ability to view other user PMs');
INSERT INTO `languages` VALUES('priv_send_emails', 'en', 'Ability to send emails');
INSERT INTO `languages` VALUES('priv_delete_site_users', 'en', 'Ability to delete site users');
INSERT INTO `languages` VALUES('priv_delete_releases', 'en', 'Ability to delete releases from site');
INSERT INTO `languages` VALUES('priv_edit_dchubs', 'en', 'Edit Direct-Connect Hubs configuration');
INSERT INTO `languages` VALUES('priv_category_admin', 'en', 'Ability to administer release categories');
INSERT INTO `languages` VALUES('priv_blocksadmin', 'en', 'Ability to administer blocks');
INSERT INTO `languages` VALUES('priv_view_disabled_site_notice', 'en', 'View disabled site notice');
INSERT INTO `languages` VALUES('priv_is_guest', 'en', 'You are guest');
INSERT INTO `languages` VALUES('priv_debug_template', 'en', 'View teplate debugging information');
INSERT INTO `languages` VALUES('priv_is_power_user', 'en', 'You are power user');
INSERT INTO `languages` VALUES('priv_is_releaser', 'en', 'You have releaser''s rights');
INSERT INTO `languages` VALUES('priv_is_vip', 'en', 'You have a VIP account');
INSERT INTO `languages` VALUES('priv_is_owner', 'en', 'You are owner');
INSERT INTO `languages` VALUES('priv_is_administrator', 'en', 'You are administartor');
INSERT INTO `languages` VALUES('priv_edit_site_templates', 'ru', 'Доступ к панели администрирования шаблонов сайта');
INSERT INTO `languages` VALUES('priv_view_general_statistics', 'ru', 'Возможность просматривать общую статистику сайта');
INSERT INTO `languages` VALUES('priv_stampadmin', 'ru', 'Доступ к панели администрирования штампов');
INSERT INTO `languages` VALUES('priv_spamadmin', 'ru', 'Доступ к просмотрщику личных сообщений (в админ панели)');
INSERT INTO `languages` VALUES('priv_seo_admincp', 'ru', 'Доступ к панели администрирования дружественных ссылок и SEO');
INSERT INTO `languages` VALUES('priv_relgroups_admin', 'ru', 'Доступ к панели администрирования релиз групп');
INSERT INTO `languages` VALUES('priv_edit_retrackers', 'ru', 'Доступ к панели администрирования ретрекеров');
INSERT INTO `languages` VALUES('priv_requests_operation', 'ru', 'Возможность совершать операции с запросами релизов');
INSERT INTO `languages` VALUES('priv_edit_release_templates', 'ru', 'Доступ к панели администрирования шаблонов релизов');
INSERT INTO `languages` VALUES('priv_edit_relgroups', 'ru', 'Возможность редактирования релиз групп');
INSERT INTO `languages` VALUES('priv_recountadmin', 'ru', 'Доступ к панели синхронизации переменных и счетчиков');
INSERT INTO `languages` VALUES('priv_polls_operation', 'ru', 'Возможность совершать операции с опросами');
INSERT INTO `languages` VALUES('priv_change_user_passwords', 'ru', 'Возможность менять пароли другим пользователям');
INSERT INTO `languages` VALUES('priv_news_operation', 'ru', 'Возможность совершать операции с новостями сайта');
INSERT INTO `languages` VALUES('priv_view_sql_stats', 'ru', 'Возможность просматривать статистику использования базы данных');
INSERT INTO `languages` VALUES('priv_add_comments_to_user', 'ru', 'Возможность добавлять модераторские комментарии к пользователям');
INSERT INTO `languages` VALUES('priv_ownsupport', 'ru', 'Возможность добавить себя самого в стол поддержки');
INSERT INTO `languages` VALUES('priv_edit_users', 'ru', 'Возможность редактировать профили пользователей');
INSERT INTO `languages` VALUES('priv_view_private_user_profiles', 'ru', 'Возможность просмотра приватных аккаунтов пользователей');
INSERT INTO `languages` VALUES('priv_censored_admin', 'ru', 'Возможность администрирования запрещенных релизов');
INSERT INTO `languages` VALUES('priv_post_releases_approved', 'ru', 'Возможность загружать автоматически проверяемые системой релизы');
INSERT INTO `languages` VALUES('priv_upload_releases', 'ru', 'Возможность загружать новые релизы');
INSERT INTO `languages` VALUES('deny_priv_message', 'en', 'Access denied, you must to have permission to:');
INSERT INTO `languages` VALUES('deny_priv_message', 'ru', 'Доступ запрещен, у вас должен быть доступ к:');
INSERT INTO `languages` VALUES('priv_is_owner', 'ua', 'Вы владелец');
INSERT INTO `languages` VALUES('priv_is_administrator', 'ua', 'Вы администратор');
INSERT INTO `languages` VALUES('priv_is_moderator', 'ua', 'У вас есть права модератора');
INSERT INTO `languages` VALUES('priv_is_releaser', 'ua', 'У вас есть права релизера');
INSERT INTO `languages` VALUES('priv_is_vip', 'ua', 'У вас VIP аккаунт');
INSERT INTO `languages` VALUES('priv_is_power_user', 'ua', 'Вы опытный пользователь');
INSERT INTO `languages` VALUES('priv_is_user', 'ua', 'Вы зарегестрированный пользователь');
INSERT INTO `languages` VALUES('priv_is_guest', 'ua', 'Вы гость');
INSERT INTO `languages` VALUES('priv_debug_template', 'ua', 'Смотреть дебаг-информацию шаблона');
INSERT INTO `languages` VALUES('priv_deny_disabled_site', 'ua', 'Доступ к форме просмотра выключенного сайта');
INSERT INTO `languages` VALUES('priv_view_disabled_site_notice', 'ua', 'Просмотр уведомления об отключенном сайте');
INSERT INTO `languages` VALUES('priv_view_sql_debug', 'ua', 'Просмотр дебаг-информации SQL');
INSERT INTO `languages` VALUES('priv_access_to_private_relgroups', 'ua', 'Доступ к приватным релиз-группам');
INSERT INTO `languages` VALUES('priv_access_to_admincp', 'ua', 'Доступ к панели администрирования');
INSERT INTO `languages` VALUES('priv_access_to_ban_emails', 'ua', 'Доступ к бану email-адресов');
INSERT INTO `languages` VALUES('priv_add_users', 'ua', 'Возможность добавления пользователей сайта вручную');
INSERT INTO `languages` VALUES('priv_bans_admin', 'ua', 'Возможность бана пользовательских аккаунтов');
INSERT INTO `languages` VALUES('priv_blocksadmin', 'ua', 'Возможность администрирования блоков');
INSERT INTO `languages` VALUES('priv_category_admin', 'ua', 'Возможность администрирования категорий релизов');
INSERT INTO `languages` VALUES('priv_clear_caches', 'ua', 'Возможность очистки кеша релизера');
INSERT INTO `languages` VALUES('priv_edit_comments', 'ua', 'Возможность редактирования комментариев сайта');
INSERT INTO `languages` VALUES('priv_edit_general_configuration', 'ua', 'Возможность редактирования общей конфигурации сайта');
INSERT INTO `languages` VALUES('priv_edit_countries', 'ua', 'Возможнось редактирования зарегестрированных для пользователей стран');
INSERT INTO `languages` VALUES('priv_cronadmin', 'ua', 'Возможность администрирования переодических заданий');
INSERT INTO `languages` VALUES('priv_edit_dchubs', 'ua', 'Возможность администрирования хабов Direct-Connect');
INSERT INTO `languages` VALUES('priv_delete_site_users', 'ua', 'Возможность удаления пользователей сайта');
INSERT INTO `languages` VALUES('priv_delete_releases', 'ua', 'Возможность удаления релизов с сайта');
INSERT INTO `languages` VALUES('priv_edit_releases', 'ua', 'Возможность редактировать не свои релизы');
INSERT INTO `languages` VALUES('priv_send_emails', 'ua', 'Возможность отсылать email-сообщения');
INSERT INTO `languages` VALUES('priv_edit_forum_settings', 'ua', 'Возможность администрирования форума в админ-панели');
INSERT INTO `languages` VALUES('priv_approve_invites', 'ua', 'Возможность подтверждать инвайты пользователей');
INSERT INTO `languages` VALUES('priv_add_invites', 'ua', 'Возможность добавлять инвайтов к пользователям');
INSERT INTO `languages` VALUES('priv_view_duplicate_ip', 'ua', 'Возможность просматривать информацию о дублирующихся IP-адресах');
INSERT INTO `languages` VALUES('priv_langadmin', 'ua', 'Доступ к панели администрирования языков');
INSERT INTO `languages` VALUES('priv_view_logs', 'ua', 'Возможность просмативать логи сайта');
INSERT INTO `languages` VALUES('priv_truncate_logs', 'ua', 'Возможность удалять логи сайта');
INSERT INTO `languages` VALUES('priv_view_pms', 'ua', 'Возможность просматривать личные сообщения других пользователей');
INSERT INTO `languages` VALUES('priv_mass_pm', 'ua', 'Возможность создавать массовые рассылки личных сообщений');
INSERT INTO `languages` VALUES('priv_edit_users', 'ua', 'Возможность редактировать профили пользователей');
INSERT INTO `languages` VALUES('priv_ownsupport', 'ua', 'Возможность добавить себя самого в стол поддержки');
INSERT INTO `languages` VALUES('priv_add_comments_to_user', 'ua', 'Возможность добавлять модераторские комментарии к пользователям');
INSERT INTO `languages` VALUES('priv_view_sql_stats', 'ua', 'Возможность просматривать статистику использования базы данных');
INSERT INTO `languages` VALUES('priv_news_operation', 'ua', 'Возможность совершать операции с новостями сайта');
INSERT INTO `languages` VALUES('priv_change_user_passwords', 'ua', 'Возможность менять пароли другим пользователям');
INSERT INTO `languages` VALUES('priv_polls_operation', 'ua', 'Возможность совершать операции с опросами');
INSERT INTO `languages` VALUES('priv_recountadmin', 'ua', 'Доступ к панели синхронизации переменных и счетчиков');
INSERT INTO `languages` VALUES('priv_edit_relgroups', 'ua', 'Возможность редактирования релиз групп');
INSERT INTO `languages` VALUES('priv_edit_release_templates', 'ua', 'Доступ к панели администрирования шаблонов релизов');
INSERT INTO `languages` VALUES('priv_requests_operation', 'ua', 'Возможность совершать операции с запросами релизов');
INSERT INTO `languages` VALUES('priv_edit_retrackers', 'ua', 'Доступ к панели администрирования ретрекеров');
INSERT INTO `languages` VALUES('priv_relgroups_admin', 'ua', 'Доступ к панели администрирования релиз групп');
INSERT INTO `languages` VALUES('priv_seo_admincp', 'ua', 'Доступ к панели администрирования дружественных ссылок и SEO');
INSERT INTO `languages` VALUES('priv_spamadmin', 'ua', 'Доступ к просмотрщику личных сообщений (в админ панели)');
INSERT INTO `languages` VALUES('priv_stampadmin', 'ua', 'Доступ к панели администрирования штампов');
INSERT INTO `languages` VALUES('priv_view_general_statistics', 'ua', 'Возможность просматривать общую статистику сайта');
INSERT INTO `languages` VALUES('priv_edit_site_templates', 'ua', 'Доступ к панели администрирования шаблонов сайта');
INSERT INTO `languages` VALUES('priv_view_private_user_profiles', 'ua', 'Возможность просмотра приватных аккаунтов пользователей');
INSERT INTO `languages` VALUES('priv_censored_admin', 'ua', 'Возможность администрирования запрещенных релизов');
INSERT INTO `languages` VALUES('priv_post_releases_approved', 'ua', 'Возможность загружать автоматически проверяемые системой релизы');
INSERT INTO `languages` VALUES('priv_upload_releases', 'ua', 'Возможность загружать новые релизы');
INSERT INTO `languages` VALUES('edit_custom_user_privileges_title', 'en', 'Edit custom user privileges (these priveleges will be added to default for user class)');
INSERT INTO `languages` VALUES('edit_custom_user_privileges_title', 'ru', 'Редактировать привилегии пользователя (они будут добавлены к текущим привилегиям класса пользователя)');
INSERT INTO `languages` VALUES('edit_custom_user_privileges_title', 'ua', 'Редактировать привилегии пользователя (они будут добавлены к текущим привилегиям класса пользователя)');
INSERT INTO `languages` VALUES('privilege_custom_priv', 'en', 'Ability to edit privileges given to custom users');
INSERT INTO `languages` VALUES('privilege_custom_priv', 'ru', 'Возможность назначать уникальные привилегии для пользователей');
INSERT INTO `languages` VALUES('privilege_custom_priv', 'ua', 'Возможность назначать уникальные привилегии для пользователей');
INSERT INTO `languages` VALUES('Information about torrent', 'en', 'Information about torrent');
INSERT INTO `languages` VALUES('Information about torrent', 'ru', 'Информация о торренте');
INSERT INTO `languages` VALUES('Information about torrent', 'ua', 'Информация о торренте');
INSERT INTO `languages` VALUES('Successfully imported', 'en', 'Successfully imported');
INSERT INTO `languages` VALUES('Successfully imported', 'ru', 'Удачно импортировано');
INSERT INTO `languages` VALUES('Successfully imported', 'ua', 'Удачно импортировано');
INSERT INTO `languages` VALUES('Privileges configuration', 'en', 'Privileges configuration');
INSERT INTO `languages` VALUES('Privileges configuration', 'ru', 'Конфигурирование привилегий');
INSERT INTO `languages` VALUES('privadmin_cls_allowed', 'en', 'Classes allowed');
INSERT INTO `languages` VALUES('privadmin_cls_allowed', 'ru', 'Допустимые классы');
INSERT INTO `languages` VALUES('privadmin_error_no_class', 'en', 'Error. Please select at least one class');
INSERT INTO `languages` VALUES('privadmin_error_no_class', 'ru', 'Ошибка. Пожалуйста выберите хотя бы один класс');
INSERT INTO `languages` VALUES('privadmin_priv_registered', 'en', 'Privilege "%s" successfully registered');
INSERT INTO `languages` VALUES('privadmin_priv_registered', 'ru', 'Привилегия "%s" успешно зарегистрирована');
INSERT INTO `languages` VALUES('privilege_register_error', 'en', 'Privilege does not registered (it already exists)');
INSERT INTO `languages` VALUES('privilege_register_error', 'ru', 'Привилегия не зарегистрирована (она уже существует)');
INSERT INTO `languages` VALUES('privadmin_error_sys_priv', 'en', 'Privilege "%s" can not be deleted. It is system privilege.');
INSERT INTO `languages` VALUES('privadmin_error_sys_priv', 'ru', 'Привилегия "%s" не может быть удалена. Это системная привилегия.');
INSERT INTO `languages` VALUES('privadmin_priv_unregistered', 'en', 'Privilege "%s" unregistered');
INSERT INTO `languages` VALUES('privadmin_priv_unregistered', 'ru', 'Привилегия "%s" удалена');
INSERT INTO `languages` VALUES('privadmin_priv_updated', 'en', 'Privilege updated');
INSERT INTO `languages` VALUES('privadmin_priv_updated', 'ru', 'Привилегия обновлена');
INSERT INTO `languages` VALUES('privadmin_title', 'en', 'Privileges control panel');
INSERT INTO `languages` VALUES('privadmin_title', 'ru', 'Панель управления привилегиями');
INSERT INTO `languages` VALUES('privadmin_editor_title', 'en', 'Adding or editing privilege');
INSERT INTO `languages` VALUES('privadmin_editor_notice', 'en', '<b>WRITE IN ENGLISH</b>; This phrase will be parsed via language system. Do not forget to add translations for this phrase.');
INSERT INTO `languages` VALUES('privadmin_editor_title', 'ru', 'Добавление или редактирование привилегии');
INSERT INTO `languages` VALUES('privadmin_editor_notice', 'ru', '<b>ПИШИТЕ ПО-АНГЛИЙСКИ</b>; Эта фраза будет выведена через систему языков. Не забудьте добавить переводы для этой фразы в панели управления языками.');
INSERT INTO `languages` VALUES('Save', 'en', 'Save');
INSERT INTO `languages` VALUES('Save', 'ru', 'Сохранить');
INSERT INTO `languages` VALUES('privadmin_curr_registered', 'en', 'Current registered privileges');
INSERT INTO `languages` VALUES('privadmin_curr_registered', 'ru', 'Список зарегистрированных привилегий');
INSERT INTO `languages` VALUES('privadmin_save_classes', 'en', 'Save checked classes');
INSERT INTO `languages` VALUES('privadmin_save_classes', 'ru', 'Сохранить отмеченные классы');
INSERT INTO `languages` VALUES('privadmin_edit_desc', 'en', 'Edit privilege description');
INSERT INTO `languages` VALUES('privadmin_edit_desc', 'ru', 'Редактировать описание привилегии');
INSERT INTO `languages` VALUES('access_to_privadmin', 'en', 'Access to privileges administration panel');
INSERT INTO `languages` VALUES('access_to_privadmin', 'ru', 'Доступ к панели управления привилегиями');
INSERT INTO `languages` VALUES('classadmin_priv', 'en', 'Access to classes administration panel');
INSERT INTO `languages` VALUES('classadmin_priv', 'ru', 'Доступ к панели администрирования классов');
INSERT INTO `languages` VALUES('privadmin_noprivname_edit', 'en', 'Editing names of existing privileges is not possible. If you want to change it, you must create new privilege and delete this one');
INSERT INTO `languages` VALUES('privadmin_noprivname_edit', 'ru', 'Редактирование названий уже существующих привилегий запрещено. Если вы хотите его изменить, то создайте новую привилегию с другим именем и удалите эту.');
INSERT INTO `languages` VALUES('classadmin_roles_error', 'ru', 'Ошибка. Выбранные роли уже используются другим классом. Пожалуйста <a href="javascript:history.go(-1);">вернитесь назад</a> и выберите другие роли');
INSERT INTO `languages` VALUES('blocksadmin_saved', 'en', 'Block saved');
INSERT INTO `languages` VALUES('blocksadmin_saved', 'ru', 'Блок сохранен');
INSERT INTO `languages` VALUES('classadmin_roles_error', 'en', 'Error. Selected roles already associated with another classes. Please <a href="javascript:history.go(-1);">try again</a> and select another roles');
INSERT INTO `languages` VALUES('classadmin_priority_error', 'en', 'Class with this priority already exists. Please <a href="javascript:history.go(-1);">try again</a>');
INSERT INTO `languages` VALUES('classadmin_priority_error', 'ru', 'Класс с таким приоритетом уже существует. Пожалуйста <a href="javascript:history.go(-1);">вернитесь назад</a> и выберите другой приоритет');
INSERT INTO `languages` VALUES('classadmin_added', 'en', 'Class added');
INSERT INTO `languages` VALUES('classadmin_added', 'ru', 'Класс добавлен');
INSERT INTO `languages` VALUES('classadmin_error_last', 'en', 'You can not delete last class');
INSERT INTO `languages` VALUES('classadmin_error_last', 'ru', 'Вы не можете удалить последний класс');
INSERT INTO `languages` VALUES('classadmin_deleted', 'en', 'Class deleted');
INSERT INTO `languages` VALUES('classadmin_deleted', 'ru', 'Класс удален');
INSERT INTO `languages` VALUES('classadmin_updated', 'en', 'Class updated');
INSERT INTO `languages` VALUES('classadmin_updated', 'ru', 'Класс обновлен');
INSERT INTO `languages` VALUES('classadmin_roles_error_this', 'en', 'Error. Selected roles for class "%s" are alredy associated with another classes. Please <a href="javascript:history.go(-1);">try again</a> and select another roles');
INSERT INTO `languages` VALUES('classadmin_roles_error_this', 'ru', 'Ошибка. Роли, выбранные для класса "%s", уже назначены другим классам. Пожалуйста <a href="javascript:history.go(-1);">вернитесь назад</a> и выберите другие роли');
INSERT INTO `languages` VALUES('classadmin_priority_error_this', 'en', 'Prioity for class "%s" is not unique');
INSERT INTO `languages` VALUES('classadmin_priority_error_this', 'ru', 'Приоритет класса "%s" не уникален');
INSERT INTO `languages` VALUES('classadmin_saved', 'en', 'Priority and role changes saved');
INSERT INTO `languages` VALUES('classadmin_saved', 'ru', 'Изменения приоритетов и ролей сохранены');
INSERT INTO `languages` VALUES('classadmin_title', 'en', 'Classes control panel');
INSERT INTO `languages` VALUES('classadmin_title', 'ru', 'Панель управления классами');
INSERT INTO `languages` VALUES('classadmin_current', 'en', 'Current classes');
INSERT INTO `languages` VALUES('classadmin_current', 'ru', 'Список классов');
INSERT INTO `languages` VALUES('Priority', 'en', 'Priority');
INSERT INTO `languages` VALUES('Priority', 'ru', 'Приоритет');
INSERT INTO `languages` VALUES('Roles', 'en', 'Roles');
INSERT INTO `languages` VALUES('Roles', 'ru', 'Роли');
INSERT INTO `languages` VALUES('classadmin_att_priority', 'en', 'Attention: Priorities must be unique');
INSERT INTO `languages` VALUES('classadmin_att_priority', 'ru', 'Внимание: Приоритеты должны быть уникальными');
INSERT INTO `languages` VALUES('classadmin_wildcard_notice', 'en', 'You can use wildcards {clname} as class name and {uname} as user name');
INSERT INTO `languages` VALUES('classadmin_wildcard_notice', 'ru', 'Вы можете использовать замены {clname} в качестве имени класса и {uname} в качестве имени пользователя');
INSERT INTO `languages` VALUES('classadmin_add_edit', 'en', 'Adding or editing class');
INSERT INTO `languages` VALUES('classadmin_add_edit', 'ru', 'Добавление или редактирование класса');
INSERT INTO `languages` VALUES('classadmin_del_assign', 'en', 'Select class to be assigned to users, which currently have deleting class');
INSERT INTO `languages` VALUES('classadmin_del_assign', 'ru', 'Выберите класс, который будет присвоен пользователям, у которым сейчас назначен удаляемый класс');
INSERT INTO `languages` VALUES('classadmin_roles_desc_title', 'en', 'Roles description');
INSERT INTO `languages` VALUES('classadmin_roles_desc_title', 'ru', 'Описание ролей');
INSERT INTO `languages` VALUES('classadmin_roles_desc_how', 'en', 'Only one class can be associated to every single role, but one class can have many roles!');
INSERT INTO `languages` VALUES('classadmin_roles_desc_how', 'ru', 'Роль может быть присвоена только одному классу, но один класс может иметь несколько ролей!');
INSERT INTO `languages` VALUES('classadmin_sysop_desc', 'en', '<b>sysop</b> - system operator. Class with this role can access some only sysop-specific features');
INSERT INTO `languages` VALUES('classadmin_sysop_desc', 'ru', '<b>sysop</b> - суперадминистратор. Класс с этой ролью получает доступ к некоторым суперадминистраторским опциям');
INSERT INTO `languages` VALUES('classadmin_uploader_desc', 'en', '<b>uploader</b> - class with this role can add torrents to non-torrent releases');
INSERT INTO `languages` VALUES('classadmin_uploader_desc', 'ru', '<b>uploader</b> - класс с этой ролью может добавлять торренты к релизам без торрентов');
INSERT INTO `languages` VALUES('classadmin_staffbegin_desc', 'en', '<b>staffbegin</b> - class with this role and classes with higher priority will be listed on staff page (staff.php)');
INSERT INTO `languages` VALUES('classadmin_staffbegin_desc', 'ru', '<b>staffbegin</b> -  класс с этой ролью и классы, высшие по приоритету, отображаются на странице "Администрация" (staff.php)');
INSERT INTO `languages` VALUES('classadmin_vip_desc', 'en', '<b>vip</b> - class with this role has no rating downgrade');
INSERT INTO `languages` VALUES('classadmin_vip_desc', 'ru', '<b>vip</b> - для класса с этой ролью не действует понижение рейтинга при включенной рейтинговой системе');
INSERT INTO `languages` VALUES('classadmin_rating_desc', 'en', '<b>rating</b> - class with this role will be setted to users with good rating');
INSERT INTO `languages` VALUES('classadmin_reg_desc', 'en', '<b>reg</b> - class with this role will be setted to newly registered users');
INSERT INTO `languages` VALUES('classadmin_rating_desc', 'ru', '<b>rating</b> - класс с этой ролью будет присвоен пользователям с хорошим рейтингом');
INSERT INTO `languages` VALUES('classadmin_reg_desc', 'ru', '<b>reg</b> - класс с этой ролью будет присвоен новым пользователям');
INSERT INTO `languages` VALUES('classadmin_guest_desc', 'en', '<b>guest</b> - class with this role will be associated with guests');
INSERT INTO `languages` VALUES('classadmin_guest_desc', 'ru', '<b>guest</b> - класс с этой ролью - класс гостей');
INSERT INTO `languages` VALUES('admincp_classconfig', 'en', 'Classes configuration');
INSERT INTO `languages` VALUES('admincp_classconfig', 'ru', 'Конфигурирование классов');
INSERT INTO `languages` VALUES('langadmin_import_results', 'en', 'Importing results');
INSERT INTO `languages` VALUES('langadmin_import_results', 'ru', 'Результат импорта');
INSERT INTO `languages` VALUES('List of releases', 'en', 'List of releases');
INSERT INTO `languages` VALUES('List of releases', 'ru', 'Список релизов');
INSERT INTO `languages` VALUES('spam_total', 'en', 'Private messages (%s total)');
INSERT INTO `languages` VALUES('spam_total', 'ru', 'Личные сообщения (всего: %s)');
INSERT INTO `languages` VALUES('Delete selected', 'en', 'Delete selected');
INSERT INTO `languages` VALUES('Delete selected', 'ru', 'Удалить выбранное');
INSERT INTO `languages` VALUES('spam_s_r', 'en', 'Sender/Receiver');
INSERT INTO `languages` VALUES('spam_s_r', 'ru', 'Отправитель/Получатель');
INSERT INTO `languages` VALUES('Text', 'en', 'Text');
INSERT INTO `languages` VALUES('Text', 'ru', 'Текст');
INSERT INTO `languages` VALUES('View all messages', 'en', 'View all messages');
INSERT INTO `languages` VALUES('View all messages', 'ru', 'Посмотреть все сообщения');
INSERT INTO `languages` VALUES('catadmin_you_del', 'en', 'You deleting "%s"');
INSERT INTO `languages` VALUES('catadmin_you_del', 'ru', 'Вы удаляете "%s"');
INSERT INTO `languages` VALUES('catadmin_select_del', 'en', 'Select category to be associated to deleting category releases');
INSERT INTO `languages` VALUES('catadmin_select_del', 'ru', 'Выбирите категорию, которая будет присвоена релизам удаляемой категории');
INSERT INTO `languages` VALUES('of January', 'en', 'of January');
INSERT INTO `languages` VALUES('of January', 'ru', 'Января');
INSERT INTO `languages` VALUES('of August', 'ru', 'Августа');
INSERT INTO `languages` VALUES('of August', 'ua', 'Августа');
INSERT INTO `languages` VALUES('of September', 'en', 'of September');
INSERT INTO `languages` VALUES('of September', 'ru', 'Сентября');
INSERT INTO `languages` VALUES('of September', 'ua', 'Сентября');
INSERT INTO `languages` VALUES('of October', 'en', 'of October');
INSERT INTO `languages` VALUES('of October', 'ru', 'Октября');
INSERT INTO `languages` VALUES('of October', 'ua', 'Октября');
INSERT INTO `languages` VALUES('of November', 'en', 'of November');
INSERT INTO `languages` VALUES('of November', 'ru', 'Ноября');
INSERT INTO `languages` VALUES('of November', 'ua', 'Ноября');
INSERT INTO `languages` VALUES('of December', 'en', 'of December');
INSERT INTO `languages` VALUES('of December', 'ru', 'Декабря');
INSERT INTO `languages` VALUES('of December', 'ua', 'Декабря');
INSERT INTO `languages` VALUES('langadmin_add', 'en', 'Add a new word');
INSERT INTO `languages` VALUES('langadmin_add', 'ru', 'Добавить новое слово');
INSERT INTO `languages` VALUES('langadmin_add', 'ua', 'Добавить новое слово');
INSERT INTO `languages` VALUES('Uploader', 'en', 'Uploader');
INSERT INTO `languages` VALUES('Uploader', 'ru', 'Аплоадер');
INSERT INTO `languages` VALUES('Uploader', 'ua', 'Аплоадер');
INSERT INTO `languages` VALUES('check_time', 'en', 'Check time');
INSERT INTO `languages` VALUES('check_time', 'ru', 'Время проверки');
INSERT INTO `languages` VALUES('check_time', 'ua', 'Время проверки');
INSERT INTO `languages` VALUES('Super Moderator', 'en', 'Super Moderator');
INSERT INTO `languages` VALUES('Super Moderator', 'ru', 'Супермодератор');
INSERT INTO `languages` VALUES('Super Moderator', 'ua', 'Супермодератор');
INSERT INTO `languages` VALUES('Expert', 'en', 'Expert');
INSERT INTO `languages` VALUES('Expert', 'ru', 'Эксперт');
INSERT INTO `languages` VALUES('Expert', 'ua', 'Эксперт');
INSERT INTO `languages` VALUES('Super Administrator', 'en', 'Super Administrator');
INSERT INTO `languages` VALUES('Super Administrator', 'ru', 'Суперадминистратор');
INSERT INTO `languages` VALUES('Super Administrator', 'ua', 'Суперадминистратор');
INSERT INTO `languages` VALUES('Viewing', 'en', 'Viewing');
INSERT INTO `languages` VALUES('Viewing', 'ru', 'Видимый');
INSERT INTO `languages` VALUES('Viewing', 'ua', 'Видимый');
INSERT INTO `languages` VALUES('Updated', 'en', 'Updated');
INSERT INTO `languages` VALUES('Updated', 'ru', 'Обновлен');
INSERT INTO `languages` VALUES('Updated', 'ua', 'Обновлен');
INSERT INTO `languages` VALUES('priv_post_rel_mainpage', 'en', 'Ability to post releases to main page');
INSERT INTO `languages` VALUES('priv_post_rel_mainpage', 'ru', 'Возможность отправлять релизы на главную страницу');
INSERT INTO `languages` VALUES('priv_post_rel_mainpage', 'ua', 'Возможность отправлять релизы на главную страницу');
INSERT INTO `languages` VALUES('non_mainpage_release', 'en', 'Does not visible on main page');
INSERT INTO `languages` VALUES('non_mainpage_release', 'ru', 'Не показывается на главной странице');
INSERT INTO `languages` VALUES('non_mainpage_release', 'ua', 'Не показывается на главной странице');
INSERT INTO `languages` VALUES('priv_chat_admin', 'en', 'You allowed to moderate chat');
INSERT INTO `languages` VALUES('priv_chat_admin', 'ru', 'Вы являетесь модератором чата');
INSERT INTO `languages` VALUES('priv_chat_admin', 'ua', 'Вы являетесь модератором чата');
INSERT INTO `languages` VALUES('chat', 'en', 'Chat');
INSERT INTO `languages` VALUES('chat', 'ru', 'Чат');
INSERT INTO `languages` VALUES('chat', 'ua', 'Чат');
INSERT INTO `languages` VALUES('type_name', 'en', 'Type name here');
INSERT INTO `languages` VALUES('type_name', 'ru', 'Введите название');
INSERT INTO `languages` VALUES('type_name', 'ua', 'Введите название');
INSERT INTO `languages` VALUES('select_category', 'en', 'Select category');
INSERT INTO `languages` VALUES('select_category', 'ru', 'Выберите категорию');
INSERT INTO `languages` VALUES('select_category', 'ua', 'Выберите категорию');
INSERT INTO `languages` VALUES('select_relgroup', 'en', 'Select release group');
INSERT INTO `languages` VALUES('select_relgroup', 'ru', 'Выберите релиз-группу');
INSERT INTO `languages` VALUES('select_relgroup', 'ua', 'Выберите релиз-группу');
INSERT INTO `languages` VALUES('setup_notif', 'en', 'Setup notifications');
INSERT INTO `languages` VALUES('setup_notif', 'ru', 'Настроить уведомления');
INSERT INTO `languages` VALUES('setup_notif', 'ua', 'Настроить уведомления');
INSERT INTO `languages` VALUES('loading_patient', 'en', 'Loading next page, please be patient');
INSERT INTO `languages` VALUES('loading_patient', 'ru', 'Происходит загрузка следующей страницы, пожалуйста подождите');
INSERT INTO `languages` VALUES('loading_patient', 'ua', 'Происходит загрузка следующей страницы, пожалуйста подождите');
INSERT INTO `languages` VALUES('open_social_userdetails', 'en', 'Open social features');
INSERT INTO `languages` VALUES('open_social_userdetails', 'ru', 'Открыть социальную сеть');
INSERT INTO `languages` VALUES('open_social_userdetails', 'ua', 'Открыть социальную сеть');
INSERT INTO `languages` VALUES('release_del_subject', 'en', 'Your release was deleted');
INSERT INTO `languages` VALUES('release_del_subject', 'ru', 'Ваш релиз удален');
INSERT INTO `languages` VALUES('release_del_subject', 'ua', 'Ваш релиз удален');
INSERT INTO `languages` VALUES('release_del_body', 'en', 'Your release named "%s" was deleted by %s with the following reason: %s');
INSERT INTO `languages` VALUES('release_del_body', 'ru', 'Ваш релиз "%s" был удален %s по следующей причине: %s');
INSERT INTO `languages` VALUES('release_del_body', 'ua', 'Ваш релиз "%s" был удален %s по следующей причине: %s');
INSERT INTO `languages` VALUES('tags', 'en', 'Tags');
INSERT INTO `languages` VALUES('tags', 'ru', 'Жанры');
INSERT INTO `languages` VALUES('tags', 'ua', 'Жанры');
INSERT INTO `languages` VALUES('tags_begin', 'en', 'Begin to type tag');
INSERT INTO `languages` VALUES('tags_begin', 'ru', 'Начните вводить название жанра');
INSERT INTO `languages` VALUES('tags_begin', 'ua', 'Начните вводить название жанра');
INSERT INTO `languages` VALUES('tags_new', 'en', 'No results, new tag will be created');
INSERT INTO `languages` VALUES('tags_new', 'ru', 'Ничего не найдено, будет создан новый жанр');
INSERT INTO `languages` VALUES('tags_new', 'ua', 'Ничего не найдено, будет создан новый жанр');
INSERT INTO `languages` VALUES('select_upload', 'en', 'Select what to upload');
INSERT INTO `languages` VALUES('select_upload', 'ru', 'Выберите тип загружаемого релиза');
INSERT INTO `languages` VALUES('select_upload', 'ua', 'Выберите тип загружаемого релиза');
INSERT INTO `languages` VALUES('change_nick_priv', 'en', 'Ability to change nickname');
INSERT INTO `languages` VALUES('change_nick_priv', 'ru', 'Возможность изменять имя пользователя');
INSERT INTO `languages` VALUES('change_nick_priv', 'ua', 'Возможность изменять имя пользователя');
INSERT INTO `languages` VALUES('upd', 'en', 'Update');
INSERT INTO `languages` VALUES('upd', 'ru', 'Обновить');
INSERT INTO `languages` VALUES('upd', 'ua', 'Оновити');
INSERT INTO `languages` VALUES('upload_sample', 'en', 'Your language / Original language (year or range of years) [quality, etc]');
INSERT INTO `languages` VALUES('upload_sample', 'ru', 'Русский / Оригинал (год или диапазон годов) [качество, примечание]');
INSERT INTO `languages` VALUES('upload_sample', 'ua', 'Русский / Оригинал (год или диапазон годов) [качество, примечание]');
INSERT INTO `languages` VALUES('Movie', 'en', 'Movie');
INSERT INTO `languages` VALUES('Movie', 'ru', 'Фильм');
INSERT INTO `languages` VALUES('Movie', 'ua', 'Фильм');
INSERT INTO `languages` VALUES('music_upload', 'en', 'Music');
INSERT INTO `languages` VALUES('music_upload', 'ru', 'Музыка');
INSERT INTO `languages` VALUES('music_upload', 'ua', 'Музыка');
INSERT INTO `languages` VALUES('Game', 'en', 'Game');
INSERT INTO `languages` VALUES('Game', 'ru', 'Игра');
INSERT INTO `languages` VALUES('Game', 'ua', 'Игра');
INSERT INTO `languages` VALUES('Software', 'en', 'Software');
INSERT INTO `languages` VALUES('Software', 'ru', 'Программа');
INSERT INTO `languages` VALUES('Software', 'ua', 'Программа');
INSERT INTO `languages` VALUES('Without form', 'en', 'Without form');
INSERT INTO `languages` VALUES('Without form', 'ru', 'Без формы');
INSERT INTO `languages` VALUES('Without form', 'ua', 'Без формы');
INSERT INTO `languages` VALUES('external_link', 'en', 'Going to external link');
INSERT INTO `languages` VALUES('external_link', 'ru', 'Переход по внешней ссылке');
INSERT INTO `languages` VALUES('external_link', 'ua', 'Переход по внешней ссылке');
INSERT INTO `languages` VALUES('add_comment_to', 'en', 'Add comment to %s');
INSERT INTO `languages` VALUES('add_comment_to', 'ru', 'Добавить комментарий к %s');
INSERT INTO `languages` VALUES('add_comment_to', 'ua', 'Додати коментар до %s');
INSERT INTO `languages` VALUES('Show more', 'ua', 'Показати більше');
INSERT INTO `languages` VALUES('this_is_an_end', 'en', 'This is an end');
INSERT INTO `languages` VALUES('this_is_an_end', 'ru', 'Это конец');
INSERT INTO `languages` VALUES('this_is_an_end', 'ua', 'Це кінець');
INSERT INTO `languages` VALUES('Poll_number', 'en', 'Poll %s');
INSERT INTO `languages` VALUES('Poll_number', 'ru', 'Опрос %s');
INSERT INTO `languages` VALUES('Poll_number', 'ua', 'Опитування %s');
INSERT INTO `languages` VALUES('Opened', 'en', 'Opened');
INSERT INTO `languages` VALUES('Opened', 'ru', 'Открыт');
INSERT INTO `languages` VALUES('Opened', 'ua', 'Відкритий');
INSERT INTO `languages` VALUES('ends', 'en', 'ends');
INSERT INTO `languages` VALUES('ends', 'ru', 'заканчивается');
INSERT INTO `languages` VALUES('ends', 'ua', 'закінчується');
INSERT INTO `languages` VALUES('ended', 'en', 'ended');
INSERT INTO `languages` VALUES('ended', 'ru', 'закончен');
INSERT INTO `languages` VALUES('ended', 'ua', 'закінчився');
INSERT INTO `languages` VALUES('amount_of_votes', 'en', 'Amount of votes');
INSERT INTO `languages` VALUES('amount_of_votes', 'ru', 'Количество голосов');
INSERT INTO `languages` VALUES('amount_of_votes', 'ua', 'Кількість голосів');
INSERT INTO `languages` VALUES('you_already_voted_here', 'en', 'You already voted here');
INSERT INTO `languages` VALUES('you_already_voted_here', 'ru', 'Вы уже голосовали здесь');
INSERT INTO `languages` VALUES('you_already_voted_here', 'ua', 'Ви вже голосували тут');
INSERT INTO `languages` VALUES('vote_for_this_variant', 'en', 'Vote for this variant');
INSERT INTO `languages` VALUES('vote_for_this_variant', 'ru', 'Проголосовать за этот вариант');
INSERT INTO `languages` VALUES('vote_for_this_variant', 'ua', 'Проголосувати за цей варіант');
INSERT INTO `languages` VALUES('total_comments', 'en', 'Total comments');
INSERT INTO `languages` VALUES('total_comments', 'ru', 'Всего комментариев');
INSERT INTO `languages` VALUES('total_comments', 'ua', 'Всього коментарів');
INSERT INTO `languages` VALUES('more_details', 'en', 'More details');
INSERT INTO `languages` VALUES('more_details', 'ru', 'Подробнее');
INSERT INTO `languages` VALUES('more_details', 'ua', 'Детальніше');
INSERT INTO `languages` VALUES('polls_archive', 'en', 'Polls archive');
INSERT INTO `languages` VALUES('polls_archive', 'ru', 'Архив опросов');
INSERT INTO `languages` VALUES('polls_archive', 'ua', 'Архів опитувань');
INSERT INTO `languages` VALUES('poll_closed', 'en', 'Poll closed');
INSERT INTO `languages` VALUES('poll_closed', 'ru', 'Опрос закрыт');
INSERT INTO `languages` VALUES('poll_closed', 'ua', 'Опитування закрито');
INSERT INTO `languages` VALUES('polls_log_in', 'en', '<a href="%s">Log in</a> to view polls and vote');
INSERT INTO `languages` VALUES('polls_log_in', 'ru', '<a href="%s">Войдите</a>, чтобы просмотреть опросы и голосования');
INSERT INTO `languages` VALUES('polls_log_in', 'ua', '<a href="%s">Увійдіть</a>, щоб переглянути опитування і голосування');
INSERT INTO `languages` VALUES('msg_wrote', 'en', '%s wrote:');
INSERT INTO `languages` VALUES('msg_wrote', 'ru', '%s написал(а):');
INSERT INTO `languages` VALUES('msg_wrote', 'ua', '%s написал(а):');
INSERT INTO `languages` VALUES('enter_username', 'en', 'Enter username');
INSERT INTO `languages` VALUES('enter_username', 'ru', 'Введите имя пользователя');
INSERT INTO `languages` VALUES('enter_username', 'ua', 'Введите имя пользователя');
INSERT INTO `languages` VALUES('msg_sent_from', 'en', 'Will be sent from');
INSERT INTO `languages` VALUES('msg_sent_from', 'ru', 'Будет отправлено от');
INSERT INTO `languages` VALUES('msg_sent_from', 'ua', 'Будет отправлено от');
INSERT INTO `languages` VALUES('Discount', 'en', 'Discount');
INSERT INTO `languages` VALUES('Discount', 'ru', 'Скидка');
INSERT INTO `languages` VALUES('Discount', 'ua', 'Скидка');
INSERT INTO `languages` VALUES('to_last_comment', 'en', 'Scroll to last comment');
INSERT INTO `languages` VALUES('to_last_comment', 'ru', 'К последнему комментарию');
INSERT INTO `languages` VALUES('to_last_comment', 'ua', 'К последнему комментарию');
INSERT INTO `languages` VALUES('add_new_comment', 'en', 'Add new comment');
INSERT INTO `languages` VALUES('add_new_comment', 'ru', 'Добавить новый комментарий');
INSERT INTO `languages` VALUES('add_new_comment', 'ua', 'Добавить новый комментарий');
INSERT INTO `languages` VALUES('rel_wo_torrent', 'en', 'Releases without torrents');
INSERT INTO `languages` VALUES('rel_wo_torrent', 'ru', 'Релизы без торрентов');
INSERT INTO `languages` VALUES('rel_wo_torrent', 'ua', 'Релизы без торрентов');
INSERT INTO `languages` VALUES('total_size', 'en', 'Total releases size');
INSERT INTO `languages` VALUES('total_size', 'ru', 'Размер релизов');
INSERT INTO `languages` VALUES('total_size', 'ua', 'Размер релизов');
INSERT INTO `languages` VALUES('history_downloaded_notice', 'en', 'Viewing all releases (created by you, gifted to you, golden and downloaded by you)');
INSERT INTO `languages` VALUES('history_downloaded_notice', 'ru', 'Просмотр всех релизов (созданных вами, подаренных вам, золотых и скачанных вами)');
INSERT INTO `languages` VALUES('history_downloaded_notice', 'ua', 'Просмотр всех релизов (созданных вами, подаренных вам, золотых и скачанных вами)');
INSERT INTO `languages` VALUES('Guests', 'en', 'Guests');
INSERT INTO `languages` VALUES('Guests', 'ru', 'Гости');
INSERT INTO `languages` VALUES('Guests', 'ua', 'Гости');
INSERT INTO `languages` VALUES('Record', 'en', 'Record');
INSERT INTO `languages` VALUES('Record', 'ru', 'Рекорд');
INSERT INTO `languages` VALUES('Record', 'ua', 'Рекорд');
INSERT INTO `languages` VALUES('registered_at', 'en', 'registered at');
INSERT INTO `languages` VALUES('registered_at', 'ru', 'зарегестрирован в');
INSERT INTO `languages` VALUES('registered_at', 'ua', 'зарегестрирован в');
INSERT INTO `languages` VALUES('discount_notice_max', 'en', 'Your discount must be at least %s to increase rating now (%s currently seeding + %s discount = %s downloaded)');
INSERT INTO `languages` VALUES('discount_notice_max', 'ru', 'Ваша скидка на данный момент должна быть не менее %s для увеличения рейтинга (%s сейчас раздаете + %s скидки = %s скачанных релизов)');
INSERT INTO `languages` VALUES('discount_notice_max', 'ua', 'Ваша скидка на данный момент должна быть не менее %s для увеличения рейтинга (%s сейчас раздаете + %s скидки = %s скачанных релизов)');
INSERT INTO `languages` VALUES('max_discount_error', 'en', 'You already reached discount limit. <a href="%s">Tell my why?</a>');
INSERT INTO `languages` VALUES('max_discount_error', 'ru', 'Вы уже достигли лимита скидки. <a href="%s">Почему?</a>');
INSERT INTO `languages` VALUES('max_discount_error', 'ua', 'Вы уже достигли лимита скидки. <a href="%s">Почему?</a>');
INSERT INTO `languages` VALUES('registered_at_cap', 'en', 'Registered at');
INSERT INTO `languages` VALUES('registered_at_cap', 'ru', 'Зарегестрирован в');
INSERT INTO `languages` VALUES('registered_at_cap', 'ua', 'Зарегестрирован в');

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `msg` text,
  `unread` tinyint(1) NOT NULL DEFAULT '1',
  `poster` int(10) unsigned NOT NULL DEFAULT '0',
  `location` tinyint(1) NOT NULL DEFAULT '1',
  `saved` tinyint(1) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `archived_receiver` tinyint(1) NOT NULL DEFAULT '0',
  `spamid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`),
  KEY `poster` (`poster`),
  KEY `added` (`added`),
  KEY `saved` (`saved`,`sender`,`archived_receiver`,`added`),
  KEY `sender_2` (`sender`,`archived`,`archived_receiver`,`unread`,`added`),
  KEY `unread` (`unread`,`archived`,`archived_receiver`,`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `messages`
--


-- --------------------------------------------------------

--
-- Структура таблицы `mod_searched`
--

CREATE TABLE `mod_searched` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `searched` varchar(255) NOT NULL,
  `searchers` varchar(255) DEFAULT NULL,
  `num_searched` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `searched` (`searched`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mod_searched`
--


-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `news`
--


-- --------------------------------------------------------

--
-- Структура таблицы `nickhistory`
--

CREATE TABLE `nickhistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `nick` varchar(255) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `nickhistory`
--


-- --------------------------------------------------------

--
-- Структура таблицы `notifs`
--

CREATE TABLE `notifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checkid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `checkid` (`checkid`,`type`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `notifs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `orbital_blocks`
--

CREATE TABLE `orbital_blocks` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL,
  `weight` int(10) NOT NULL DEFAULT '1',
  `active` int(1) NOT NULL DEFAULT '1',
  `blockfile` varchar(255) NOT NULL,
  `view` varchar(20) DEFAULT NULL,
  `expire` int(10) NOT NULL DEFAULT '0',
  `which` varchar(255) NOT NULL,
  `custom_tpl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orbital_blocks`
--

INSERT INTO `orbital_blocks` VALUES(1, 'Новинки!', '', 't', 2, 1, 'block-indextorrents.php', '', 0, 'index,', '');
INSERT INTO `orbital_blocks` VALUES(2, 'Новости', '', 't', -5, 1, 'block-news.php', '', 0, 'index,', '');
INSERT INTO `orbital_blocks` VALUES(3, 'Serverload', '', 't', 3, 0, 'block-server_load.php', '1', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(4, 'Добро пожаловать', '', 'r', 1, 1, 'block-userpanel.php', '', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(5, 'Опросы', '', 'd', 3, 1, 'block-polls.php', '', 0, 'index,userdetails,', '');
INSERT INTO `orbital_blocks` VALUES(7, 'Пользователи Онлайн', '', 'l', 3, 1, 'block-online.php', '', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(8, 'Статистика', '<h2><span style="color: #ffcc00;">Чтобы знали, что вас ожидает, посмотрите на статистику:</span></h2>', 'd', 1, 1, 'block-stats.php', '1,2,3,4,5,6,7,8', 0, 'signup,', '');
INSERT INTO `orbital_blocks` VALUES(12, 'Запрещенные релизы', '', 'n', 8, 1, 'block-cen.php', '', 0, 'index,', '');
INSERT INTO `orbital_blocks` VALUES(14, 'Запросы', '', 'r', 6, 1, 'block-req.php', '', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(15, 'Статистика', '', 'd', 99, 1, 'block-stats.php', '', 0, 'index,', '');
INSERT INTO `orbital_blocks` VALUES(21, 'Debug/remotcheck', '', 't', -100, 0, 'block-debug.php', '1,2,11', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(24, 'Новое на сайте', '', 't', -99, 0, 'block-notifications.php', '1,2,11,3,9,4,5,6,7', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(30, 'Добро пожаловать', '', 'n', 1, 0, 'block-userpanel.php', '1,2,11,3,9,4,5,6,7', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(31, 'Главное Меню', '', 'n', 4, 0, 'block-main.php', '', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(32, 'Пользователи', '', 'n', 2, 1, 'block-online.php', '1,2,11,3,9,4,5,6,7', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(35, 'ЛС', '', 'n', 3, 0, 'block-message.php', '1,2,11,3,9,4,5,6,7', 0, '', '');
INSERT INTO `orbital_blocks` VALUES(42, 'Чат', '', 't', -98, 0, 'block-chat.php', '1,2,11,3,9,4,5,6,7', 0, 'index,', '');
INSERT INTO `orbital_blocks` VALUES(53, 'Сообщество', '', 'n', 6, 1, 'block-social.php', '', 0, '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `polls`
--

CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `start` int(10) NOT NULL,
  `exp` int(10) DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `polls`
--


-- --------------------------------------------------------

--
-- Структура таблицы `polls_structure`
--

CREATE TABLE `polls_structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollid` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `polls_structure`
--


-- --------------------------------------------------------

--
-- Структура таблицы `polls_votes`
--

CREATE TABLE `polls_votes` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL DEFAULT '0',
  `user` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY (`vid`),
  UNIQUE KEY `sid` (`sid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `polls_votes`
--


-- --------------------------------------------------------

--
-- Структура таблицы `presents`
--

CREATE TABLE `presents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `presenter` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(100) DEFAULT NULL,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `presents`
--


-- --------------------------------------------------------

--
-- Структура таблицы `privileges`
--

CREATE TABLE `privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `classes_allowed` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `privileges`
--

INSERT INTO `privileges` VALUES(1, 'is_owner', '1', 'You are owner');
INSERT INTO `privileges` VALUES(2, 'is_administrator', '2,1', 'You are administartor');
INSERT INTO `privileges` VALUES(3, 'is_moderator', '1,2,11,3', 'You have moderation rights');
INSERT INTO `privileges` VALUES(4, 'is_releaser', '1,2,11,3,4,9', 'You have releaser''s rights');
INSERT INTO `privileges` VALUES(6, 'is_power_user', '1,2,11,3,4,9,5,6', 'You are power user');
INSERT INTO `privileges` VALUES(7, 'is_user', '1,2,11,3,4,9,5,6,7', 'You are registered user');
INSERT INTO `privileges` VALUES(8, 'is_guest', '1,2,11,3,4,9,5,6,7,8', 'You are guest');
INSERT INTO `privileges` VALUES(9, 'debug_template', '1', 'View teplate debugging information');
INSERT INTO `privileges` VALUES(10, 'deny_disabled_site', '3,4,9,5,6,7,8', 'Deny form viewing disabled site');
INSERT INTO `privileges` VALUES(11, 'view_disabled_site_notice', '1,2,11,3,4,9,5,6,7', 'View disabled site notice');
INSERT INTO `privileges` VALUES(12, 'view_sql_debug', '1', 'View SQL debug information');
INSERT INTO `privileges` VALUES(13, 'access_to_private_relgroups', '1,2,11,3', 'Access to private relgroups');
INSERT INTO `privileges` VALUES(14, 'access_to_admincp', '1,2,11,3', 'Access to admin control panel');
INSERT INTO `privileges` VALUES(15, 'access_to_ban_emails', '1,2,11,3', 'Access to email bans administration');
INSERT INTO `privileges` VALUES(16, 'add_users', '1,2', 'Ability to add new users to site manually');
INSERT INTO `privileges` VALUES(17, 'bans_admin', '1,2,11,3', 'Ability to ban user accounts');
INSERT INTO `privileges` VALUES(18, 'blocksadmin', '1', 'Ability to administer blocks');
INSERT INTO `privileges` VALUES(19, 'category_admin', '1', 'Ability to administer release categories');
INSERT INTO `privileges` VALUES(20, 'clear_caches', '1,2', 'Ability to clear releaser cache');
INSERT INTO `privileges` VALUES(21, 'edit_comments', '1,2,11,3', 'Ability to edit site comments');
INSERT INTO `privileges` VALUES(22, 'edit_general_configuration', '1', 'Edit general releaser configuration');
INSERT INTO `privileges` VALUES(23, 'edit_countries', '1', 'Edit registered user countries');
INSERT INTO `privileges` VALUES(24, 'cronadmin', '1', 'Edit scheduled jobs configuration');
INSERT INTO `privileges` VALUES(25, 'edit_dchubs', '1', 'Edit Direct-Connect Hubs configuration');
INSERT INTO `privileges` VALUES(26, 'delete_site_users', '1,2,11', 'Ability to delete site users');
INSERT INTO `privileges` VALUES(27, 'delete_releases', '1,2,11,3', 'Ability to delete releases from site');
INSERT INTO `privileges` VALUES(28, 'edit_releases', '1,2,11,3,9', 'Ability to edit non-owned releases');
INSERT INTO `privileges` VALUES(29, 'send_emails', '1,2,11,3', 'Ability to send emails');
INSERT INTO `privileges` VALUES(30, 'edit_forum_settings', '1', 'Ability to administer forum in admin panel');
INSERT INTO `privileges` VALUES(31, 'approve_invites', '1,2,11,3', 'Ability to approve other user''s invites');
INSERT INTO `privileges` VALUES(32, 'add_invites', '1,2,11', 'Add new invites count to user');
INSERT INTO `privileges` VALUES(33, 'view_duplicate_ip', '1,2,11,3', 'View dupliate ip information');
INSERT INTO `privileges` VALUES(34, 'langadmin', '1', 'Access to language administration panel');
INSERT INTO `privileges` VALUES(35, 'view_logs', '1,2,11,3,9', 'Ability to view site logs');
INSERT INTO `privileges` VALUES(36, 'truncate_logs', '1', 'Ability to delete site logs');
INSERT INTO `privileges` VALUES(37, 'view_pms', '1,2,11,3', 'Ability to view other user PMs');
INSERT INTO `privileges` VALUES(38, 'mass_pm', '1,2,11', 'Ability to send mass PMs');
INSERT INTO `privileges` VALUES(39, 'edit_users', '1,2,11,3', 'Ability to change user account details');
INSERT INTO `privileges` VALUES(40, 'ownsupport', '1,2,11,3', 'Ability do add yourself to support desk');
INSERT INTO `privileges` VALUES(41, 'add_comments_to_user', '1,2,11,3', 'Ability to add moderation comments to users');
INSERT INTO `privileges` VALUES(42, 'view_sql_stats', '1', 'Ability to view SQL database statistics');
INSERT INTO `privileges` VALUES(43, 'news_operation', '1,2,11', 'Ability to preform operations with site news');
INSERT INTO `privileges` VALUES(44, 'change_user_passwords', '1,2,11', 'Ability to change user passwords');
INSERT INTO `privileges` VALUES(45, 'polls_operation', '1,2,11,3', 'Ability to administer polls');
INSERT INTO `privileges` VALUES(46, 'recountadmin', '1', 'Access to synchronization panel');
INSERT INTO `privileges` VALUES(47, 'edit_relgroups', '1,2', 'Ability to edit release groups');
INSERT INTO `privileges` VALUES(48, 'edit_release_templates', '1,2,11,3', 'Access to release templates administration');
INSERT INTO `privileges` VALUES(49, 'requests_operation', '1,2,11,3', 'Ability to magange release requests');
INSERT INTO `privileges` VALUES(50, 'edit_retrackers', '1', 'Access to retracker administration panel');
INSERT INTO `privileges` VALUES(51, 'relgroups_admin', '1,2,11,3', 'Access to release groups administration panel');
INSERT INTO `privileges` VALUES(52, 'seo_admincp', '1', 'Access to SEO-friedly URL administration panel');
INSERT INTO `privileges` VALUES(53, 'spamadmin', '1,2,11,3', 'Access to private message viewer (administration)');
INSERT INTO `privileges` VALUES(54, 'stampadmin', '1,2,11,3', 'Access to stamps administration panel');
INSERT INTO `privileges` VALUES(55, 'view_general_statistics', '1', 'Ability to view general site statistics');
INSERT INTO `privileges` VALUES(56, 'edit_site_templates', '1', 'Access to site templates administration panel');
INSERT INTO `privileges` VALUES(57, 'view_private_user_profiles', '1,2,11,3', 'Ability to view private user profiles');
INSERT INTO `privileges` VALUES(58, 'censored_admin', '1,2,11,3', 'Ability to administrate censored releases');
INSERT INTO `privileges` VALUES(59, 'post_releases_approved', '1,2,11,3,9', 'Ability to post automatically approved releases');
INSERT INTO `privileges` VALUES(60, 'upload_releases', '1,2,11,3,4,9,5,6', 'Ability to upload new releases to site');
INSERT INTO `privileges` VALUES(61, 'edit_user_privileges', '1,2,11,3', 'Ability to edit privileges given to custom users');
INSERT INTO `privileges` VALUES(62, 'access_to_privadmincp', '1', 'Access to privileges administration panel');
INSERT INTO `privileges` VALUES(69, 'access_to_classadmin', '1', 'Access to classes administration panel');
INSERT INTO `privileges` VALUES(70, 'post_releases_to_mainpage', '1,2,11,3,9', 'Ability to post releases to main page');
INSERT INTO `privileges` VALUES(71, 'is_chat_admin', '1,2,11', 'You allowed to moderate chat');
INSERT INTO `privileges` VALUES(72, 'change_nick', '1,2', 'Ability to change nickname');

-- --------------------------------------------------------

--
-- Структура таблицы `ratings`
--

CREATE TABLE `ratings` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `type` varchar(30) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid` (`rid`,`userid`,`type`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ratings`
--


-- --------------------------------------------------------

--
-- Структура таблицы `relgroups`
--

CREATE TABLE `relgroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `spec` varchar(500) NOT NULL,
  `descr` text NOT NULL,
  `image` varchar(300) NOT NULL,
  `owners` varchar(100) NOT NULL,
  `members` varchar(255) NOT NULL,
  `ratingsum` int(5) NOT NULL DEFAULT '0',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `only_invites` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `amount` int(3) NOT NULL DEFAULT '0',
  `page_pay` varchar(300) NOT NULL,
  `subscribe_length` int(2) NOT NULL DEFAULT '31',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `relgroups`
--


-- --------------------------------------------------------

--
-- Структура таблицы `reltemplates`
--

CREATE TABLE `reltemplates` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `reltemplates`
--

INSERT INTO `reltemplates` VALUES(1, 'Фильмы / Сериалы', '<span style="text-decoration: underline;">Информация о фильме</span><br /> <strong>Название: </strong><br /> <strong>Оригинальное название: </strong><br /> <strong>Год выхода: </strong><br /> <strong>Жанр: </strong><br /> <strong>Режиссер: </strong><br /> <strong>В ролях: </strong><br /> <br /> <strong>О фильме:</strong><em> </em><br /> <br /> <a class="external" href="http://www.imdb.com/" rel="ajaxified" target="_blank"><strong>IMDB</strong></a> -<em> </em><em></em><br /> <br /> <strong>Выпущено:</strong> <em></em><br /> <strong>Продолжительность: </strong><em></em><br /><strong>Субтитры:</strong> <em></em><br /> <strong>Перевод:</strong><em></em><em></em><em></em><br /><br /><span style="text-decoration: underline;"><br />Файл</span><br /> <strong>Формат: </strong>AVI | Xvid<br /> <strong>Качество: </strong><em></em><br /> <strong>Видео: </strong><em></em><br /> <strong>Аудио: </strong><em></em><br /> <strong>Скачать: </strong><span class="external"><strong>Сэмпл</strong></span><em></em><br /><br /><strong>Релиз группы:</strong>');
INSERT INTO `reltemplates` VALUES(2, 'Игры', '<strong><strong></strong> <strong>Название:</strong></strong><strong><strong> <br />Оригинальное название:</strong></strong><strong><strong></strong> <br />Жанр: </strong><br /> <strong>Год выхода: </strong><br /> <strong>Разработчик: </strong><br /> <strong>Издатель: </strong><br /> <strong>Издатель в России: </strong><br /> <strong>Язык (локализатор):</strong> <strong><br />Таблетка:</strong> <br /><br /><br /><strong>Описание:</strong> <br /><br /><strong>Особенности игры:</strong> <br /><br /><span style="text-decoration: underline;"><strong>Минимальные системные требования</strong><strong><br /></strong></span><strong>Операционная система:</strong> <br /><strong>Процессор:</strong> <br /><strong>Память:</strong> <br /><strong>Видеокарта:</strong> <br /><strong>Аудиокарта:</strong> <br /><strong>Свободное место на ЖД:</strong> <br /> <br /><strong>Установка:</strong>');
INSERT INTO `reltemplates` VALUES(3, 'Музыка', '<strong>Исполнитель:</strong><span style="color: blue;"><span style="font-family: Courier New;"><span style="font-size: 12px;"><strong><span style="color: #333333;"> </span></strong><br /> </span></span></span><strong>Альбом:</strong><span style="color: blue;"><span style="font-family: Courier New;"><span style="font-size: 12px;"><span style="color: #333333;"> </span><br /> </span></span></span><strong>Год выпуска:</strong><span style="color: blue;"><span style="font-family: Courier New;"><span style="font-size: 12px;"><strong><span style="color: #333333;"> </span></strong><br /> </span></span></span><strong>Жанр: <br />Треклист: </strong>(прятать под spoiler это такая кнопочка <img src="http://i070.radikal.ru/1001/48/5f13faaa9979.jpg" alt="image" width="22" height="22" />)<br /><br /><strong>Звук:</strong> <br /><strong>Битрейт: </strong><br /><strong>Продолжительность: </strong><span style="color: blue;"><span style="font-family: Courier New;"></span></span>');
INSERT INTO `reltemplates` VALUES(4, 'Soft / Операционные системы', '<strong>Название: </strong><br /> <strong>Год выхода: </strong><br /> <strong>Разработчик: </strong><br /> <strong>Язык интерфейса: <strong><br />Таблетка:</strong> </strong><br /> <br /> <strong>Описание:</strong><em> <strong></strong><br /><br /></em><strong></strong><span style="text-decoration: underline;"><strong>Минимальные системные требования</strong><strong><br /></strong></span><strong>Операционная система:</strong> <br /><strong>Процессор:</strong> <br /><strong>Память:</strong> <br /><strong>Видеокарта:</strong> <br /><strong>Аудиокарта:</strong> <br /><strong>Свободное место на ЖД:</strong> <br /> <br /><strong>Установка: <br /></strong>');

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reportid` int(10) NOT NULL DEFAULT '0',
  `userid` int(10) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL,
  `motive` varchar(255) NOT NULL,
  `added` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reportid` (`reportid`,`userid`,`type`),
  KEY `added` (`added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `reports`
--


-- --------------------------------------------------------

--
-- Структура таблицы `requests`
--

CREATE TABLE `requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `request` varchar(225) DEFAULT NULL,
  `descr` text NOT NULL,
  `added` int(10) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `filled` varchar(200) NOT NULL,
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `cat` int(10) unsigned NOT NULL DEFAULT '0',
  `filledby` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `requests`
--


-- --------------------------------------------------------

--
-- Структура таблицы `retrackers`
--

CREATE TABLE `retrackers` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(3) NOT NULL DEFAULT '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `retrackers`
--


-- --------------------------------------------------------

--
-- Структура таблицы `rgnews`
--

CREATE TABLE `rgnews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relgroup` int(5) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `added` (`added`),
  KEY `added_2` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rgnews`
--


-- --------------------------------------------------------

--
-- Структура таблицы `rg_invites`
--

CREATE TABLE `rg_invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  `rgid` int(5) NOT NULL DEFAULT '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rg_invites`
--


-- --------------------------------------------------------

--
-- Структура таблицы `rg_subscribes`
--

CREATE TABLE `rg_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `rgid` int(5) unsigned NOT NULL,
  `valid_until` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`rgid`),
  KEY `valid_until` (`valid_until`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rg_subscribes`
--


-- --------------------------------------------------------

--
-- Структура таблицы `seorules`
--

CREATE TABLE `seorules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `script` varchar(100) DEFAULT NULL,
  `parameter` varchar(100) DEFAULT NULL,
  `repl` varchar(255) DEFAULT NULL,
  `unset_params` varchar(255) DEFAULT NULL,
  `sort` int(2) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `script` (`script`,`parameter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `seorules`
--


-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `class` tinyint(4) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL,
  `time` bigint(30) NOT NULL DEFAULT '0',
  `url` varchar(150) NOT NULL,
  `useragent` text,
  PRIMARY KEY (`sid`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sessions`
--


-- --------------------------------------------------------

--
-- Структура таблицы `shoutbox`
--

CREATE TABLE `shoutbox` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `text` mediumtext CHARACTER SET cp1251 NOT NULL,
  `orig_text` mediumtext CHARACTER SET cp1251 NOT NULL,
  `del` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `shoutbox`
--


-- --------------------------------------------------------

--
-- Структура таблицы `sitelog`
--

CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) DEFAULT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `txt` text,
  `type` varchar(80) NOT NULL DEFAULT 'tracker',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sitelog`
--


-- --------------------------------------------------------

--
-- Структура таблицы `snatched`
--

CREATE TABLE `snatched` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `completedat` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `snatch` (`torrent`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `snatched`
--


-- --------------------------------------------------------

--
-- Структура таблицы `stamps`
--

CREATE TABLE `stamps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) NOT NULL DEFAULT '0',
  `class` tinyint(3) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `stamps`
--


-- --------------------------------------------------------

--
-- Структура таблицы `stylesheets`
--

CREATE TABLE `stylesheets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `stylesheets`
--

INSERT INTO `stylesheets` VALUES(1, 'releaser330', 'Kinokpk.com releaser 3.30');

-- --------------------------------------------------------

--
-- Структура таблицы `torrents`
--

CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `info_hash` varbinary(40) NOT NULL DEFAULT '',
  `tiger_hash` varbinary(38) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `descr` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `ismulti` tinyint(1) NOT NULL DEFAULT '0',
  `numfiles` int(10) unsigned NOT NULL DEFAULT '1',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `last_action` int(10) NOT NULL DEFAULT '0',
  `last_reseed` int(10) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `orig_owner` int(10) unsigned NOT NULL DEFAULT '0',
  `ratingsum` int(10) NOT NULL DEFAULT '0',
  `free` tinyint(1) NOT NULL DEFAULT '0',
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `moderated` tinyint(1) NOT NULL DEFAULT '0',
  `modcomm` text NOT NULL,
  `moderatedby` int(10) unsigned DEFAULT '0',
  `freefor` text NOT NULL,
  `relgroup` int(5) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `seeders` int(5) unsigned NOT NULL DEFAULT '0',
  `leechers` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`),
  KEY `added` (`added`),
  KEY `visible_2` (`visible`,`banned`,`moderatedby`),
  KEY `last_action` (`last_action`),
  KEY `added_2` (`added`,`moderatedby`),
  KEY `sticky` (`sticky`,`added`),
  KEY `tags` (`tags`),
  KEY `id` (`id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `torrents`
--


-- --------------------------------------------------------

--
-- Структура таблицы `trackers`
--

CREATE TABLE `trackers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL,
  `tracker` varchar(255) NOT NULL DEFAULT 'localhost',
  `seeders` int(5) unsigned NOT NULL DEFAULT '0',
  `leechers` int(5) unsigned NOT NULL DEFAULT '0',
  `lastchecked` int(10) unsigned NOT NULL DEFAULT '0',
  `state` varchar(300) NOT NULL,
  `method` varchar(15) NOT NULL DEFAULT 'local',
  `remote_method` varchar(10) NOT NULL DEFAULT 'N/A',
  `num_failed` int(5) unsigned NOT NULL DEFAULT '0',
  `check_start` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent` (`torrent`,`tracker`),
  KEY `lastchecked` (`lastchecked`),
  KEY `state` (`state`),
  KEY `torrent_2` (`torrent`),
  KEY `check_start_2` (`check_start`,`state`),
  KEY `state_2` (`state`),
  KEY `num_failed` (`num_failed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `trackers`
--


-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `passhash` varchar(32) NOT NULL,
  `secret` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_login` int(10) NOT NULL DEFAULT '0',
  `last_access` int(10) NOT NULL DEFAULT '0',
  `editsecret` varchar(20) NOT NULL,
  `privacy` enum('strong','normal','highest') NOT NULL DEFAULT 'normal',
  `stylesheet` int(10) NULL,
  `info` text,
  `ratingsum` int(8) NOT NULL DEFAULT '0',
  `acceptpms` enum('yes','friends','no') NOT NULL DEFAULT 'yes',
  `pron` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `supportfor` text,
  `avatar` varchar(100) NOT NULL,
  `icq` varchar(255) NOT NULL,
  `msn` varchar(255) NOT NULL,
  `aim` varchar(255) NOT NULL,
  `yahoo` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `mirc` varchar(255) NOT NULL,
  `title` varchar(30) NOT NULL,
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `notifs` varchar(1000) NOT NULL,
  `emailnotifs` varchar(1000) NOT NULL,
  `modcomment` text,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `dis_reason` varchar(255) NOT NULL,
  `timezone` int(2) NOT NULL DEFAULT '0',
  `avatars` tinyint(1) NOT NULL DEFAULT '1',
  `extra_ef` tinyint(1) NOT NULL DEFAULT '1',
  `donor` tinyint(1) DEFAULT '0',
  `warned` tinyint(1) DEFAULT '0',
  `warneduntil` int(10) NOT NULL,
  `deletepms` tinyint(1) DEFAULT '0',
  `savepms` tinyint(1) NOT NULL DEFAULT '1',
  `gender` smallint(1) NOT NULL DEFAULT '0',
  `birthday` date DEFAULT '0000-00-00',
  `language` varchar(255) DEFAULT NULL,
  `invites` int(10) NOT NULL DEFAULT '0',
  `invitedby` int(10) NOT NULL DEFAULT '0',
  `invitedroot` int(10) NOT NULL DEFAULT '0',
  `num_warned` int(2) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL,
  `last_checked` int(10) NOT NULL DEFAULT '0',
  `discount` int(5) NOT NULL DEFAULT '0',
  `viptill` int(10) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `custom_privileges` text NOT NULL,
  `forum_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of forum account',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status_added` (`confirmed`,`added`),
  KEY `ip` (`ip`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`confirmed`,`enabled`),
  KEY `added` (`added`),
  KEY `invitedby` (`invitedby`),
  KEY `invitedroot` (`invitedroot`),
  KEY `confirmed` (`confirmed`,`last_access`),
  KEY `enabled_2` (`enabled`,`ratingsum`),
  KEY `enabled_3` (`enabled`,`dis_reason`,`ratingsum`),
  KEY `warned_2` (`warned`,`warneduntil`),
  KEY `class` (`class`,`ratingsum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `users`
--


-- --------------------------------------------------------

--
-- Структура таблицы `xbt_announce_log`
--

CREATE TABLE `xbt_announce_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipa` int(10) unsigned NOT NULL,
  `port` int(11) NOT NULL,
  `event` int(11) NOT NULL,
  `info_hash` binary(20) NOT NULL,
  `peer_id` binary(20) NOT NULL,
  `downloaded` bigint(20) unsigned NOT NULL,
  `left0` bigint(20) unsigned NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_announce_log`
--


-- --------------------------------------------------------

--
-- Структура таблицы `xbt_config`
--

CREATE TABLE `xbt_config` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_config`
--

INSERT INTO `xbt_config` VALUES('announce_interval', '1800');
INSERT INTO `xbt_config` VALUES('anonymous_connect', '0');
INSERT INTO `xbt_config` VALUES('anonymous_announce', '0');
INSERT INTO `xbt_config` VALUES('anonymous_scrape', '0');
INSERT INTO `xbt_config` VALUES('auto_register', '0');
INSERT INTO `xbt_config` VALUES('clean_up_interval', '900');
INSERT INTO `xbt_config` VALUES('full_scrape', '0');
INSERT INTO `xbt_config` VALUES('gzip_scrape', '1');
INSERT INTO `xbt_config` VALUES('listen_ipa', '*');
INSERT INTO `xbt_config` VALUES('listen_port', '2710');
INSERT INTO `xbt_config` VALUES('log_announce', '1');
INSERT INTO `xbt_config` VALUES('offline_message', '');
INSERT INTO `xbt_config` VALUES('read_config_interval', '60');
INSERT INTO `xbt_config` VALUES('read_db_interval', '60');
INSERT INTO `xbt_config` VALUES('redirect_url', 'http://www.torrentsbook.com');
INSERT INTO `xbt_config` VALUES('scrape_interval', '0');
INSERT INTO `xbt_config` VALUES('write_db_interval', '15');
INSERT INTO `xbt_config` VALUES('torrent_pass_private_key', '4iWcQrCnxDoBpPcqZYzxZKr68jf');

-- --------------------------------------------------------

--
-- Структура таблицы `xbt_deny_from_hosts`
--

CREATE TABLE `xbt_deny_from_hosts` (
  `begin` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_deny_from_hosts`
--


-- --------------------------------------------------------

--
-- Структура таблицы `xbt_files`
--

CREATE TABLE `xbt_files` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `info_hash` binary(20) NOT NULL,
  `leechers` int(11) NOT NULL DEFAULT '0',
  `seeders` int(11) NOT NULL DEFAULT '0',
  `completed` int(11) NOT NULL DEFAULT '0',
  `flags` int(11) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`fid`),
  UNIQUE KEY `info_hash` (`info_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_files`
--


-- --------------------------------------------------------

--
-- Структура таблицы `xbt_files_users`
--

CREATE TABLE `xbt_files_users` (
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `announced` int(11) NOT NULL,
  `completed` int(11) NOT NULL,
  `downloaded` bigint(20) unsigned NOT NULL,
  `left` bigint(20) unsigned NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL,
  `mtime` int(11) NOT NULL,
  UNIQUE KEY `fid` (`fid`,`uid`),
  KEY `uid` (`uid`),
  KEY `uid_2` (`uid`,`active`,`left`),
  KEY `active` (`active`,`left`),
  KEY `active_2` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_files_users`
--


-- --------------------------------------------------------

--
-- Структура таблицы `xbt_scrape_log`
--

CREATE TABLE `xbt_scrape_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipa` int(10) unsigned NOT NULL,
  `info_hash` binary(20) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_scrape_log`
--


-- --------------------------------------------------------

--
-- Структура таблицы `xbt_users`
--

CREATE TABLE `xbt_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `can_leech` tinyint(4) NOT NULL DEFAULT '1',
  `wait_time` int(11) NOT NULL DEFAULT '0',
  `peers_limit` int(11) NOT NULL DEFAULT '0',
  `torrents_limit` int(11) NOT NULL DEFAULT '0',
  `torrent_pass` char(32) NOT NULL,
  `torrent_pass_version` int(11) NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `xbt_users`
--

