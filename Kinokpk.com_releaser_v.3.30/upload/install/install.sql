SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `releaser330`
--

-- --------------------------------------------------------

--
-- Table structure for table `addedrequests`
--

CREATE TABLE IF NOT EXISTS `addedrequests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requestid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pollid` (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `addedrequests`
--


-- --------------------------------------------------------

--
-- Table structure for table `bannedemails`
--

CREATE TABLE IF NOT EXISTS `bannedemails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) NOT NULL,
  `addedby` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bannedemails`
--


-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mask` varchar(60) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bans`
--


-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bookmarks`
--


-- --------------------------------------------------------

--
-- Table structure for table `cache_stats`
--

CREATE TABLE IF NOT EXISTS `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` text,
  PRIMARY KEY (`cache_name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `cache_stats`
--

INSERT INTO `cache_stats` (`cache_name`, `cache_value`) VALUES
('adminemail', 'admin@windows.lox'),
('allow_invite_signup', '1'),
('announce_interval', '30'),
('announce_packed', '1'),
('as_check_messages', '1'),
('as_timeout', '15'),
('autoclean_interval', '900'),
('avatar_max_height', '100'),
('avatar_max_width', '100'),
('cache_template', '0'),
('cache_template_time', '100'),
('debug_language', '0'),
('debug_mode', '1'),
('debug_template', '0'),
('defaultbaseurl', 'http://releaser330.com'),
('default_emailnotifs', 'unread,torrents,friends'),
('default_language', 'ru'),
('static_language', ''),
('default_notifs', 'unread,torrents,relcomments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends'),
('default_theme', 'releaser330'),
('deny_signup', '0'),
('description', 'Kinokpk.com releaser 3.30 new installation'),
('forum_enabled', '1'),
('keywords', 'kinokpk.com, releaser, 3.30, 330, ZonD80, TorrentsBook.com'),
('low_comment_hide', '-3'),
('maxusers', '0'),
('max_dead_torrent_time', '744'),
('max_images', '4'),
('max_torrent_size', '1000000'),
('pm_max', '150'),
('pron_cats', '0'),
('register_timezone', '3'),
('site_timezone', '3'),
('re_privatekey', 'none'),
('re_publickey', 'non'),
('signup_timeout', '3'),
('sign_length', '250'),
('siteemail', 'bot@kinokpk.com'),
('sitename', 'Kinokpk.com releaser 3.30 new installation'),
('siteonline', '1'),
('torrentsperpage', '25'),
('ttl_days', '28'),
('use_blocks', '1'),
('use_captcha', '0'),
('use_dc', '1'),
('use_email_act', '0'),
('use_gzip', '0'),
('use_ipbans', '1'),
('use_kinopoisk_trailers', '1'),
('use_ttl', '0'),
('yourcopy', '© {datenow} Your Copyright');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `seo_name` varchar(80) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `forum_id` smallint(5) NOT NULL DEFAULT '0',
  `disable_export` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `censoredtorrents`
--

CREATE TABLE IF NOT EXISTS `censoredtorrents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `censoredtorrents`
--


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `toid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`toid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `flagpic` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=106 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `flagpic`) VALUES
(1, 'Швеция', 'sweden.gif'),
(2, 'США', 'usa.gif'),
(3, 'Россия', 'russia.gif'),
(4, 'Финляндия', 'finland.gif'),
(5, 'Канада', 'canada.gif'),
(6, 'Франция', 'france.gif'),
(7, 'Германия', 'germany.gif'),
(8, 'Китай', 'china.gif'),
(9, 'Италия', 'italy.gif'),
(10, 'Denmark', 'denmark.gif'),
(11, 'Норвегия', 'norway.gif'),
(12, 'Англия', 'uk.gif'),
(13, 'Ирландия', 'ireland.gif'),
(14, 'Польша', 'poland.gif'),
(15, 'Нидерланды', 'netherlands.gif'),
(16, 'Бельгия', 'belgium.gif'),
(17, 'Япония', 'japan.gif'),
(18, 'Бразилия', 'brazil.gif'),
(19, 'Аргентина', 'argentina.gif'),
(20, 'Австралия', 'australia.gif'),
(21, 'Новая Зеландия', 'newzealand.gif'),
(22, 'Испания', 'spain.gif'),
(23, 'Португалия', 'portugal.gif'),
(24, 'Мексика', 'mexico.gif'),
(25, 'Сингапур', 'singapore.gif'),
(26, 'Индия', 'india.gif'),
(27, 'Албания', 'albania.gif'),
(28, 'Южная Африка', 'southafrica.gif'),
(29, 'Южная Корея', 'southkorea.gif'),
(30, 'Ямайка', 'jamaica.gif'),
(31, 'Люксембург', 'luxembourg.gif'),
(32, 'Гонк Конг', 'hongkong.gif'),
(33, 'Belize', 'belize.gif'),
(34, 'Алжир', 'algeria.gif'),
(35, 'Ангола', 'angola.gif'),
(36, 'Австрия', 'austria.gif'),
(37, 'Югославия', 'yugoslavia.gif'),
(38, 'Южные Самоа', 'westernsamoa.gif'),
(39, 'Малайзия', 'malaysia.gif'),
(40, 'Доминиканская Республика', 'dominicanrep.gif'),
(41, 'Греция', 'greece.gif'),
(42, 'Гуатемала', 'guatemala.gif'),
(43, 'Израиль', 'israel.gif'),
(44, 'Пакистан', 'pakistan.gif'),
(45, 'Чехия', 'czechrep.gif'),
(46, 'Сербия', 'serbia.gif'),
(47, 'Сейшельские Острова', 'seychelles.gif'),
(48, 'Тайвань', 'taiwan.gif'),
(49, 'Пуерто Рико', 'puertorico.gif'),
(50, 'Чили', 'chile.gif'),
(51, 'Куба', 'cuba.gif'),
(52, 'Кного', 'congo.gif'),
(53, 'Афганистан', 'afghanistan.gif'),
(54, 'Турция', 'turkey.gif'),
(55, 'Узбекистан', 'uzbekistan.gif'),
(56, 'Швейцария', 'switzerland.gif'),
(57, 'Кирибати', 'kiribati.gif'),
(58, 'Филиппины', 'philippines.gif'),
(59, 'Burkina Faso', 'burkinafaso.gif'),
(60, 'Нигерия', 'nigeria.gif'),
(61, 'Исландия', 'iceland.gif'),
(62, 'Науру', 'nauru.gif'),
(63, 'Словакия', 'slovenia.gif'),
(64, 'Туркменистан', 'turkmenistan.gif'),
(65, 'Босния', 'bosniaherzegovina.gif'),
(66, 'Андора', 'andorra.gif'),
(67, 'Литва', 'lithuania.gif'),
(68, 'Македония', 'macedonia.gif'),
(69, 'Нидерландские Антиллы', 'nethantilles.gif'),
(70, 'Украина', 'ukraine.gif'),
(71, 'Венесуела', 'venezuela.gif'),
(72, 'Венгрия', 'hungary.gif'),
(73, 'Румыния', 'romania.gif'),
(74, 'Вануату', 'vanuatu.gif'),
(75, 'Вьетнам', 'vietnam.gif'),
(76, 'Trinidad & Tobago', 'trinidadandtobago.gif'),
(77, 'Гондурас', 'honduras.gif'),
(78, 'Киргистан', 'kyrgyzstan.gif'),
(79, 'Эквадор', 'ecuador.gif'),
(80, 'Багамы', 'bahamas.gif'),
(81, 'Перу', 'peru.gif'),
(82, 'Камбоджа', 'cambodia.gif'),
(83, 'Барбадос', 'barbados.gif'),
(84, 'Бенгладеш', 'bangladesh.gif'),
(85, 'Лаос', 'laos.gif'),
(86, 'Уругвай', 'uruguay.gif'),
(87, 'Antigua Barbuda', 'antiguabarbuda.gif'),
(88, 'Парагвая', 'paraguay.gif'),
(89, 'Тайланд', 'thailand.gif'),
(90, 'СССР', 'ussr.gif'),
(91, 'Senegal', 'senegal.gif'),
(92, 'Того', 'togo.gif'),
(93, 'Северная Корея', 'northkorea.gif'),
(94, 'Хорватия', 'croatia.gif'),
(95, 'Эстония', 'estonia.gif'),
(96, 'Колумбия', 'colombia.gif'),
(97, 'Леванон', 'lebanon.gif'),
(98, 'Латвия', 'latvia.gif'),
(99, 'Коста Рика', 'costarica.gif'),
(100, 'Египт', 'egypt.gif'),
(101, 'Болгария', 'bulgaria.gif'),
(102, 'Исла де Муерто', 'jollyroger.gif'),
(103, 'Казахстан', 'kazahstan.png'),
(104, 'Молдова', 'moldova.gif'),
(105, 'Беларусь', '');

-- --------------------------------------------------------

--
-- Table structure for table `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `cron_name` varchar(255) NOT NULL,
  `cron_value` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cron_name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `cron`
--

INSERT INTO `cron` (`cron_name`, `cron_value`) VALUES
('announce_interval', 15),
('autoclean_interval', 900),
('cron_is_native', 1),
('delete_votes', 1440),
('in_cleanup', 0),
('in_remotecheck', 0),
('last_cleanup', 0),
('last_remotecheck', 0),
('max_dead_torrent_time', 744),
('num_checked', 0),
('num_cleaned', 0),
('pm_delete_sys_days', 15),
('pm_delete_user_days', 30),
('promote_rating', 50),
('rating_checktime', 180),
('rating_discounttorrent', 1),
('rating_dislimit', -200),
('rating_downlimit', -10),
('rating_enabled', 1),
('rating_freetime', 7),
('rating_max', 300),
('rating_perdownload', 1),
('rating_perinvite', 5),
('rating_perleech', 1),
('rating_perrelease', 5),
('rating_perrequest', 10),
('rating_perseed', 1),
('remotecheck_disabled', 0),
('remotecheck_interval', 2),
('remotepeers_cleantime', 10800),
('remote_trackers', 500),
('signup_timeout', 5),
('ttl_days', 100);

-- --------------------------------------------------------

--
-- Table structure for table `cron_emails`
--

CREATE TABLE IF NOT EXISTS `cron_emails` (
  `emails` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `cron_emails`
--


-- --------------------------------------------------------

--
-- Table structure for table `dchubs`
--

CREATE TABLE IF NOT EXISTS `dchubs` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(3) NOT NULL DEFAULT '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dchubs`
--


-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `files`
--


-- --------------------------------------------------------

--
-- Table structure for table `forum_categories`
--

CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `seo_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `class` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forum_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `comments` int(5) unsigned NOT NULL DEFAULT '0',
  `author` int(10) unsigned NOT NULL DEFAULT '0',
  `started` int(10) NOT NULL DEFAULT '0',
  `lastposted_id` int(10) unsigned NOT NULL DEFAULT '0',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `closedate` int(10) NOT NULL DEFAULT '0',
  `category` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forum_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `friendid` int(10) unsigned NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`friendid`),
  UNIQUE KEY `friendid` (`friendid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `friends`
--


-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  `inviteid` int(10) NOT NULL DEFAULT '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invites`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `lkey` varchar(255) NOT NULL,
  `ltranslate` varchar(2) NOT NULL,
  `lvalue` text NOT NULL,
  UNIQUE KEY `key` (`lkey`,`ltranslate`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
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
  KEY `poster` (`poster`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `news`
--


-- --------------------------------------------------------

--
-- Table structure for table `notifs`
--

CREATE TABLE IF NOT EXISTS `notifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checkid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `checkid` (`checkid`,`type`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `orbital_blocks` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=18 ;


INSERT INTO `orbital_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `expire`, `which`, `custom_tpl`) VALUES
(1, 'Новинки!', '', 't', 2, 1, 'block-indextorrents.php', '-1,0,1,2,3,4,5,6', 0, 'index,', ''),
(2, 'Новости', '', 'r', 2, 1, 'block-news.php', '', 0, 'index,', ''),
(3, 'Serverload', '', 't', 3, 1, 'block-server_load.php', '6', 0, '', ''),
(4, 'Добро пожаловать', '', 'r', 1, 1, 'block-userpanel.php', '', 0, 'index,search,', ''),
(5, 'Опросы', '', 'd', 3, 1, 'block-polls.php', '0', 0, 'index,userdetails,', NULL),
(6, 'О проекте', 'content', 'r', 5, 1, '', '', 0, 'faq,rules,signup,', ''),
(7, 'Пользователи Онлайн', '', 'r', 3, 1, 'block-online.php', '0', 0, 'index,', NULL),
(8, 'Статистика', '<h2><span style="color: #ffcc00;">Чтобы знали, что вас ожидает, посмотрите на статистику:</span></h2>', 'd', 1, 1, 'block-stats.php', '-1', 0, 'signup,', NULL),
(9, 'Наша социальная сеть растет с каждым днем:', '', 'd', 2, 1, 'block-stats.php', '', 0, 'index,', ''),
(12, 'Запрещенные релизы', '', 'r', 7, 1, 'block-cen.php', '0', 0, 'index,', NULL),
(14, 'Последние комментарии', '', 'r', 6, 1, 'block-req.php', '0', 0, 'index,', NULL),
(15, 'DISCLAIMER', '<span class="small" style="font-weight: normal;">Русский:<br />Предупреждение! Информация, расположенная на данном сервере, предназначена исключительно для частного использования в образовательных целях и не может быть загружена/перенесена на другой компьютер. Ни владелец сайта, ни хостинг-провайдер, ни любые другие физические или юридические лица не могут нести никакой ответственности за любое использование материалов данного сайта. Входя на сайт, Вы, как пользователь, тем самым подтверждаете полное и безоговорочное согласие со всеми условиями использования. Авторы проекта относятся особо негативно к нелегальному использованию информации, полученной на сайте.<br /><br /></span><strong></strong>[spoiler=Информация для правообладателей]<strong>ВНИМАНИЕ!</strong> Мы не осуществляем контроль за действиями пользователей, которые могут повторно размещать ссылки на информацию, являющуюся объектом вашего исключительного права. Любая информация на ресурсе размещается пользователем самостоятельно, без какого-либо контроля с чьей-либо стороны, что соответствует общепринятой мировой практике размещения информации в сети Интернет. Однако мы в любом случае рассмотрим все Ваши корректно сформулированные запросы относительно ссылок на информацию, нарушающую Ваши права. Запросы на удаление НЕПОСРЕДСТВЕННО информации, нарушающей права, будут возвращены отправителю, так как на серверах torrentsbook.com подобная информация не содержится.\r\n<p><strong>Необходимо предоставить следующую информацию:</strong></p>\r\n<p>1. Данные о продукте:</p>\r\n<p>1.1. Название продукта - русское и английское (в случае наличия английской версии).</p>\r\n<p>1.2. Официальная страница продукта в Интернете (в случае наличия).</p>\r\n<p>1.3. Номер, присвоенный продукту по государственному реестру.</p>\r\n<p>1.4. Для Юридического Лица / Правообладателя электронных изданий/программ для ЭВМ/баз данных - Копия документа о государственной регистрации. Для Юридического Лица / Правообладателя кино- и видеоматериалов - Прокатное удостоверение (копия).</p>\r\n<p>2. Данные о правообладателе:</p>\r\n<p>2.1. Полное наименование юридического лица.</p>\r\n<p>2.2. Почтовый адрес (в случае несовпадения юридического и почтового адреса – обязательное указание юридического адреса).</p>\r\n<p>2.3. Сайт правообладателя в сети Интернет.</p>\r\n<p>2.4. Лицензия на право деятельности (если таковая деятельность лицензируется в установленном законом порядке).</p>\r\n<p>2.5. Контактное лицо правообладателя (ФИО, должность, телефон, email).</p>\r\n<p>3. Данные лица, подающего жалобу.</p>\r\n<p>3.1. ФИО.</p>\r\n<p>3.2. Должность.</p>\r\n<p>3.3. Телефон.</p>\r\n<p>3.4. email.</p>\r\n<p>3.5. Копия доверенности на действия от лица Правообладателя (не требуется в случае если лицо подающее жалобу – руководитель компании Правообладателя). Если жалобу подает не правообладатель, а его уполномоченный доверенностью представитель - юридическое лицо, следует предоставить копию доверенности на действия физического лица от лица компании, уполномоченной доверенностью Правообладателя (не требуется в случае, если лицо подающее жалобу - руководитель компании представителя).</p>\r\n<p>4. Претензионные данные.</p>\r\n<p>4.1. Адрес страницы сайта, которые содержат ссылки на данные, нарушающие права. Ссылка должна иметь вид http://www.torrentsbook.com/xxx/название</p>\r\n<p>4.2. Полное описание сути нарушения прав (почему распространение данной информации запрещено Правообладателем).</p>\r\n<p>5. Подписка о правомерности действий (заполняется от руки и высылается в отсканированном варианте). Обязательна для каждой жалобы.</p>\r\n<hr />\r\n<p><strong>Форма запроса</strong></p>\r\n<p><em><strong>Я, «ФИО», действующий от лица «Юридическое наименование правообладателя» на основании доверенности «данные доверенности» свидетельствую о том, что все данные, указанные в данном обращении верны, «Наименование лица» (Правообладатель) – является обладателем исключительных имущественных прав, включая:</strong></em></p>\r\n<ul>\r\n<li><em>исключительное право на воспроизведение;</em></li>\r\n<li><em>исключительное право на распространение;</em></li>\r\n<li><em>исключительное право на публичный показ;</em></li>\r\n<li><em>исключительное право на доведение до всеобщего сведения;</em></li>\r\n</ul>\r\n<p><em>Все вышеперечисленные права действуют на территории Российской Федерации, все вопросы, связанные, с выплатой вознаграждений авторам произведения урегулированы Правообладателем, Правообладателю неизвестно о претензиях третьих лиц, в отношении указанных прав. В случае возникновения претензий к ресурсу torrentsbook.com со стороны третьих лиц, связанных с нарушением их прав (в том числе потребительских прав) в отношении удаленной/блокированной ссылки «ССЫЛКА», Правообладатель принимает все необходимые меры по урегулированию претензий, а также возможных споров, в том числе судебных.</em></p>\r\n<em> </em>\r\n<p><em>Правообладатель обязуется урегулировать требования, претензии, либо иски третьих лиц, а также полностью возместить ресурсу torrentsbook.com расходы и убытки (включая упущенную выгоду, оплату услуг юриста и т.п.), связанные с компенсацией требований, претензий, исков третьих лиц по факту нарушения их прав, а также иными претензиями, связанными с незаконным или ошибочным блокированием, либо удалением ссылки по требованию Правообладателя.</em></p>\r\n<em> </em>\r\n<p><em>«Дата. Подпись.»</em></p>\r\n<hr />\r\nОбращаем ваше внимание на то, что ваши письма принимаются только по адресу <a href="mailto:copyrights@torrentsbook.com"><strong>copyrights@torrentsbook.com</strong></a>, и только в соответствии с этим шаблоном и перечнем документов, указанных выше. В противном случае ваши письма будут<span style="text-decoration: underline;"> проигнорированы</span>.[/spoiler]<br /><span class="small" style="font-weight: normal;">English:<br /></span><span class="small" style="font-weight: normal;">No files you see here are hosted on the server. Links available are provided by site users and administation is not responsible for them. It is strictly prohibited to upload any copyrighted material without explicit permission from copyright holders. If you find that some content is abusing you feel free to contact administation.</span>', 'd', 5, 1, '', '', 0, 'index,', ''),
(17, 'Мы вернулись!', '<h1><span style="font-family: ''arial black'', ''avant garde''; color: #ff0000;">WE STILL ONLINE *</span></h1>\r\n<div style="text-align: right;"><strong>* мы все еще онлайн</strong></div>\r\n<div style="text-align: left;"><strong><a href="newsoverview.php?id=78">Подробности тут</a></strong><br /><br /></div>', 't', 0, 0, '', '0', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `peers`
--

CREATE TABLE IF NOT EXISTS `peers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `peer_id` varchar(40) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seeder` tinyint(1) NOT NULL DEFAULT '0',
  `started` int(10) NOT NULL,
  `last_action` int(10) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `finishedat` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`),
  KEY `torrent` (`torrent`),
  KEY `torrent_seeder` (`torrent`,`seeder`),
  KEY `last_action` (`last_action`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `peers`
--


-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `start` int(10) NOT NULL,
  `exp` int(10) DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `polls`
--


-- --------------------------------------------------------

--
-- Table structure for table `polls_structure`
--

CREATE TABLE IF NOT EXISTS `polls_structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollid` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `polls_structure`
--


-- --------------------------------------------------------

--
-- Table structure for table `polls_votes`
--

CREATE TABLE IF NOT EXISTS `polls_votes` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL DEFAULT '0',
  `user` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY (`vid`),
  UNIQUE KEY `sid` (`sid`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `polls_votes`
--


-- --------------------------------------------------------

--
-- Table structure for table `presents`
--

CREATE TABLE IF NOT EXISTS `presents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `presenter` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(100) DEFAULT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `presents`
--


-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `type` varchar(30) NOT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid` (`rid`,`userid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ratings`
--


-- --------------------------------------------------------

--
-- Table structure for table `relgroups`
--

CREATE TABLE IF NOT EXISTS `relgroups` (
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
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `relgroups`
--


-- --------------------------------------------------------

--
-- Table structure for table `reltemplates`
--

CREATE TABLE IF NOT EXISTS `reltemplates` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `reltemplates`
--


-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reportid` int(10) NOT NULL DEFAULT '0',
  `userid` int(10) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL,
  `motive` varchar(255) NOT NULL,
  `added` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reportid` (`reportid`,`userid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `reports`
--


-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
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
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `requests`
--


-- --------------------------------------------------------

--
-- Table structure for table `retrackers`
--

CREATE TABLE IF NOT EXISTS `retrackers` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(3) NOT NULL DEFAULT '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `retrackers`
--


-- --------------------------------------------------------

--
-- Table structure for table `rgnews`
--

CREATE TABLE IF NOT EXISTS `rgnews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relgroup` int(5) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rgnews`
--


-- --------------------------------------------------------

--
-- Table structure for table `rg_invites`
--

CREATE TABLE IF NOT EXISTS `rg_invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  `rgid` int(5) NOT NULL DEFAULT '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rg_invites`
--


-- --------------------------------------------------------

--
-- Table structure for table `rg_subscribes`
--

CREATE TABLE IF NOT EXISTS `rg_subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `rgid` int(5) unsigned NOT NULL,
  `valid_until` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`rgid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rg_subscribes`
--


-- --------------------------------------------------------

--
-- Table structure for table `seorules`
--

CREATE TABLE IF NOT EXISTS `seorules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `script` varchar(255) DEFAULT NULL,
  `parameter` varchar(255) DEFAULT NULL,
  `repl` varchar(255) DEFAULT NULL,
  `unset_params` varchar(255) DEFAULT NULL,
  `sort` int(2) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `script` (`script`,`parameter`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `seorules`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
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
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `sitelog`
--

CREATE TABLE IF NOT EXISTS `sitelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) DEFAULT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `txt` text,
  `type` varchar(80) NOT NULL DEFAULT 'tracker',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sitelog`
--


-- --------------------------------------------------------

--
-- Table structure for table `snatched`
--

CREATE TABLE IF NOT EXISTS `snatched` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `startedat` int(10) NOT NULL,
  `completedat` int(10) NOT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `snatch` (`torrent`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `snatched`
--


-- --------------------------------------------------------

--
-- Table structure for table `stamps`
--

CREATE TABLE IF NOT EXISTS `stamps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) NOT NULL DEFAULT '0',
  `class` tinyint(3) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `stamps`
--


-- --------------------------------------------------------

--
-- Table structure for table `stylesheets`
--

CREATE TABLE IF NOT EXISTS `stylesheets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uri` (`uri`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stylesheets`
--

INSERT INTO `stylesheets` (`id`, `uri`, `name`) VALUES
(1, 'releaser330', 'Kinokpk.com releaser 3.30');

-- --------------------------------------------------------

--
-- Table structure for table `torrents`
--

CREATE TABLE IF NOT EXISTS `torrents` (
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
  `times_completed` int(10) unsigned NOT NULL DEFAULT '0',
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
  `online` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `torrents`
--


-- --------------------------------------------------------

--
-- Table structure for table `trackers`
--

CREATE TABLE IF NOT EXISTS `trackers` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent` (`torrent`,`tracker`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `trackers`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `old_password` varchar(40) NOT NULL,
  `passhash` varchar(32) NOT NULL,
  `secret` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `added` int(10) NOT NULL DEFAULT '0',
  `last_login` int(10) NOT NULL DEFAULT '0',
  `last_access` int(10) NOT NULL DEFAULT '0',
  `editsecret` varchar(20) NOT NULL,
  `privacy` enum('strong','normal','highest') NOT NULL DEFAULT 'normal',
  `stylesheet` int(10) DEFAULT '1',
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
  `website` varchar(50) NOT NULL,
  `title` varchar(30) NOT NULL,
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `notifs` varchar(1000) NOT NULL,
  `emailnotifs` varchar(1000) NOT NULL,
  `modcomment` text,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `dis_reason` text NOT NULL,
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
  `passkey` varchar(32) NOT NULL,
  `language` varchar(255) DEFAULT NULL,
  `invites` int(10) NOT NULL DEFAULT '0',
  `invitedby` int(10) NOT NULL DEFAULT '0',
  `invitedroot` int(10) NOT NULL DEFAULT '0',
  `passkey_ip` varchar(15) NOT NULL,
  `num_warned` int(2) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL,
  `last_downloaded` int(10) NOT NULL DEFAULT '0',
  `last_checked` int(10) NOT NULL DEFAULT '0',
  `last_announced` int(10) NOT NULL DEFAULT '0',
  `discount` int(5) NOT NULL DEFAULT '0',
  `viptill` int(10) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`confirmed`,`added`),
  KEY `ip` (`ip`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`confirmed`,`enabled`),
  KEY `passkey` (`passkey`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

