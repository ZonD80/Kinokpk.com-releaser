/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
  `added` int(10) NOT NULL,
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
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
  `cache_value` text,
  PRIMARY KEY  (`cache_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `cache_stats`
-- 

INSERT INTO `cache_stats` VALUES ('adminemail', 'admin@localhost');
INSERT INTO `cache_stats` VALUES ('allow_invite_signup', '1');
INSERT INTO `cache_stats` VALUES ('announce_interval', '30');
INSERT INTO `cache_stats` VALUES ('announce_packed', '1');
INSERT INTO `cache_stats` VALUES ('as_check_messages', '1');
INSERT INTO `cache_stats` VALUES ('as_timeout', '15');
INSERT INTO `cache_stats` VALUES ('autoclean_interval', '900');
INSERT INTO `cache_stats` VALUES ('avatar_max_height', '100');
INSERT INTO `cache_stats` VALUES ('avatar_max_width', '100');
INSERT INTO `cache_stats` VALUES ('debug_mode', '0');
INSERT INTO `cache_stats` VALUES ('defaultbaseurl', 'http://releaser300.com');
INSERT INTO `cache_stats` VALUES ('default_emailnotifs', 'unread,torrents,friends');
INSERT INTO `cache_stats` VALUES ('default_language', 'ru');
INSERT INTO `cache_stats` VALUES ('default_notifs', 'unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends');
INSERT INTO `cache_stats` VALUES ('default_theme', 'kinokpk');
INSERT INTO `cache_stats` VALUES ('defuserclass', '3');
INSERT INTO `cache_stats` VALUES ('deny_signup', '0');
INSERT INTO `cache_stats` VALUES ('description', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('emo_dir', 'default');
INSERT INTO `cache_stats` VALUES ('exporttype', 'wiki');
INSERT INTO `cache_stats` VALUES ('forumname', 'Integrated forum');
INSERT INTO `cache_stats` VALUES ('forumurl', 'http://forum.pdaprime.ru');
INSERT INTO `cache_stats` VALUES ('forum_bin_id', '20');
INSERT INTO `cache_stats` VALUES ('ipb_cookie_prefix', '');
INSERT INTO `cache_stats` VALUES ('keywords', 'kinokpk, ZonD80, kinokpk.com, releaser');
INSERT INTO `cache_stats` VALUES ('maxusers', '0');
INSERT INTO `cache_stats` VALUES ('max_dead_torrent_time', '744');
INSERT INTO `cache_stats` VALUES ('max_images', '4');
INSERT INTO `cache_stats` VALUES ('max_torrent_size', '1000000');
INSERT INTO `cache_stats` VALUES ('nc', '0');
INSERT INTO `cache_stats` VALUES ('not_found_export_id', '66');
INSERT INTO `cache_stats` VALUES ('pm_max', '100');
INSERT INTO `cache_stats` VALUES ('ipb_password_priority', '0');
INSERT INTO `cache_stats` VALUES ('pron_cats', '0');
INSERT INTO `cache_stats` VALUES ('register_timezone', '3');
INSERT INTO `cache_stats` VALUES ('re_privatekey', 'none');
INSERT INTO `cache_stats` VALUES ('re_publickey', 'none');
INSERT INTO `cache_stats` VALUES ('signup_timeout', '3');
INSERT INTO `cache_stats` VALUES ('siteemail', 'admin@localhost');
INSERT INTO `cache_stats` VALUES ('sitename', 'Kinokpk.com releaser new installation');
INSERT INTO `cache_stats` VALUES ('siteonline', 'a:4:{s:5:"onoff";i:1;s:6:"reason";s:4:"test";s:5:"class";i:6;s:10:"class_name";s:40:"только для Директоров";}');
INSERT INTO `cache_stats` VALUES ('smtptype', 'advanced');
INSERT INTO `cache_stats` VALUES ('torrentsperpage', '25');
INSERT INTO `cache_stats` VALUES ('ttl_days', '28');
INSERT INTO `cache_stats` VALUES ('use_blocks', '1');
INSERT INTO `cache_stats` VALUES ('use_captcha', '0');
INSERT INTO `cache_stats` VALUES ('use_email_act', '0');
INSERT INTO `cache_stats` VALUES ('use_gzip', '0');
INSERT INTO `cache_stats` VALUES ('use_integration', '0');
INSERT INTO `cache_stats` VALUES ('use_ipbans', '1');
INSERT INTO `cache_stats` VALUES ('use_kinopoisk_trailers', '1');
INSERT INTO `cache_stats` VALUES ('use_lang', '1');
INSERT INTO `cache_stats` VALUES ('use_sessions', '1');
INSERT INTO `cache_stats` VALUES ('use_ttl', '0');
INSERT INTO `cache_stats` VALUES ('use_wait', '0');
INSERT INTO `cache_stats` VALUES ('yourcopy', '© {datenow} YOUR COPYRIGHT');

-- --------------------------------------------------------

-- 
-- Структура таблицы `categories`
-- 

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL default '0',
  `forum_id` smallint(5) NOT NULL default '0',
  `disable_export` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `censoredtorrents`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `comments`
-- 

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=105 ;

-- 
-- Дамп данных таблицы `countries`
-- 

INSERT INTO `countries` VALUES (1, 'Швеция', 'sweden.gif');
INSERT INTO `countries` VALUES (2, 'США', 'usa.gif');
INSERT INTO `countries` VALUES (3, 'Россия', 'russia.gif');
INSERT INTO `countries` VALUES (4, 'Финляндия', 'finland.gif');
INSERT INTO `countries` VALUES (5, 'Канада', 'canada.gif');
INSERT INTO `countries` VALUES (6, 'Франция', 'france.gif');
INSERT INTO `countries` VALUES (7, 'Германия', 'germany.gif');
INSERT INTO `countries` VALUES (8, 'Китай', 'china.gif');
INSERT INTO `countries` VALUES (9, 'Италия', 'italy.gif');
INSERT INTO `countries` VALUES (10, 'Denmark', 'denmark.gif');
INSERT INTO `countries` VALUES (11, 'Норвегия', 'norway.gif');
INSERT INTO `countries` VALUES (12, 'Англия', 'uk.gif');
INSERT INTO `countries` VALUES (13, 'Ирландия', 'ireland.gif');
INSERT INTO `countries` VALUES (14, 'Польша', 'poland.gif');
INSERT INTO `countries` VALUES (15, 'Нидерланды', 'netherlands.gif');
INSERT INTO `countries` VALUES (16, 'Бельгия', 'belgium.gif');
INSERT INTO `countries` VALUES (17, 'Япония', 'japan.gif');
INSERT INTO `countries` VALUES (18, 'Бразилия', 'brazil.gif');
INSERT INTO `countries` VALUES (19, 'Аргентина', 'argentina.gif');
INSERT INTO `countries` VALUES (20, 'Австралия', 'australia.gif');
INSERT INTO `countries` VALUES (21, 'Новая Зеландия', 'newzealand.gif');
INSERT INTO `countries` VALUES (22, 'Испания', 'spain.gif');
INSERT INTO `countries` VALUES (23, 'Португалия', 'portugal.gif');
INSERT INTO `countries` VALUES (24, 'Мексика', 'mexico.gif');
INSERT INTO `countries` VALUES (25, 'Сингапур', 'singapore.gif');
INSERT INTO `countries` VALUES (26, 'Индия', 'india.gif');
INSERT INTO `countries` VALUES (27, 'Албания', 'albania.gif');
INSERT INTO `countries` VALUES (28, 'Южная Африка', 'southafrica.gif');
INSERT INTO `countries` VALUES (29, 'Южная Корея', 'southkorea.gif');
INSERT INTO `countries` VALUES (30, 'Ямайка', 'jamaica.gif');
INSERT INTO `countries` VALUES (31, 'Люксембург', 'luxembourg.gif');
INSERT INTO `countries` VALUES (32, 'Гонк Конг', 'hongkong.gif');
INSERT INTO `countries` VALUES (33, 'Belize', 'belize.gif');
INSERT INTO `countries` VALUES (34, 'Алжир', 'algeria.gif');
INSERT INTO `countries` VALUES (35, 'Ангола', 'angola.gif');
INSERT INTO `countries` VALUES (36, 'Австрия', 'austria.gif');
INSERT INTO `countries` VALUES (37, 'Югославия', 'yugoslavia.gif');
INSERT INTO `countries` VALUES (38, 'Южные Самоа', 'westernsamoa.gif');
INSERT INTO `countries` VALUES (39, 'Малайзия', 'malaysia.gif');
INSERT INTO `countries` VALUES (40, 'Доминиканская Республика', 'dominicanrep.gif');
INSERT INTO `countries` VALUES (41, 'Греция', 'greece.gif');
INSERT INTO `countries` VALUES (42, 'Гуатемала', 'guatemala.gif');
INSERT INTO `countries` VALUES (43, 'Израиль', 'israel.gif');
INSERT INTO `countries` VALUES (44, 'Пакистан', 'pakistan.gif');
INSERT INTO `countries` VALUES (45, 'Чехия', 'czechrep.gif');
INSERT INTO `countries` VALUES (46, 'Сербия', 'serbia.gif');
INSERT INTO `countries` VALUES (47, 'Сейшельские Острова', 'seychelles.gif');
INSERT INTO `countries` VALUES (48, 'Тайвань', 'taiwan.gif');
INSERT INTO `countries` VALUES (49, 'Пуерто Рико', 'puertorico.gif');
INSERT INTO `countries` VALUES (50, 'Чили', 'chile.gif');
INSERT INTO `countries` VALUES (51, 'Куба', 'cuba.gif');
INSERT INTO `countries` VALUES (52, 'Кного', 'congo.gif');
INSERT INTO `countries` VALUES (53, 'Афганистан', 'afghanistan.gif');
INSERT INTO `countries` VALUES (54, 'Турция', 'turkey.gif');
INSERT INTO `countries` VALUES (55, 'Узбекистан', 'uzbekistan.gif');
INSERT INTO `countries` VALUES (56, 'Швейцария', 'switzerland.gif');
INSERT INTO `countries` VALUES (57, 'Кирибати', 'kiribati.gif');
INSERT INTO `countries` VALUES (58, 'Филиппины', 'philippines.gif');
INSERT INTO `countries` VALUES (59, 'Burkina Faso', 'burkinafaso.gif');
INSERT INTO `countries` VALUES (60, 'Нигерия', 'nigeria.gif');
INSERT INTO `countries` VALUES (61, 'Исландия', 'iceland.gif');
INSERT INTO `countries` VALUES (62, 'Науру', 'nauru.gif');
INSERT INTO `countries` VALUES (63, 'Словакия', 'slovenia.gif');
INSERT INTO `countries` VALUES (64, 'Туркменистан', 'turkmenistan.gif');
INSERT INTO `countries` VALUES (65, 'Босния', 'bosniaherzegovina.gif');
INSERT INTO `countries` VALUES (66, 'Андора', 'andorra.gif');
INSERT INTO `countries` VALUES (67, 'Литва', 'lithuania.gif');
INSERT INTO `countries` VALUES (68, 'Македония', 'macedonia.gif');
INSERT INTO `countries` VALUES (69, 'Нидерландские Антиллы', 'nethantilles.gif');
INSERT INTO `countries` VALUES (70, 'Украина', 'ukraine.gif');
INSERT INTO `countries` VALUES (71, 'Венесуела', 'venezuela.gif');
INSERT INTO `countries` VALUES (72, 'Венгрия', 'hungary.gif');
INSERT INTO `countries` VALUES (73, 'Румуния', 'romania.gif');
INSERT INTO `countries` VALUES (74, 'Вануату', 'vanuatu.gif');
INSERT INTO `countries` VALUES (75, 'Вьетнам', 'vietnam.gif');
INSERT INTO `countries` VALUES (76, 'Trinidad & Tobago', 'trinidadandtobago.gif');
INSERT INTO `countries` VALUES (77, 'Гондурас', 'honduras.gif');
INSERT INTO `countries` VALUES (78, 'Киргистан', 'kyrgyzstan.gif');
INSERT INTO `countries` VALUES (79, 'Эквадор', 'ecuador.gif');
INSERT INTO `countries` VALUES (80, 'Багамы', 'bahamas.gif');
INSERT INTO `countries` VALUES (81, 'Перу', 'peru.gif');
INSERT INTO `countries` VALUES (82, 'Камбоджа', 'cambodia.gif');
INSERT INTO `countries` VALUES (83, 'Барбадос', 'barbados.gif');
INSERT INTO `countries` VALUES (84, 'Бенгладеш', 'bangladesh.gif');
INSERT INTO `countries` VALUES (85, 'Лаос', 'laos.gif');
INSERT INTO `countries` VALUES (86, 'Уругвай', 'uruguay.gif');
INSERT INTO `countries` VALUES (87, 'Antigua Barbuda', 'antiguabarbuda.gif');
INSERT INTO `countries` VALUES (88, 'Парагвая', 'paraguay.gif');
INSERT INTO `countries` VALUES (89, 'Тайланд', 'thailand.gif');
INSERT INTO `countries` VALUES (90, 'СССР', 'ussr.gif');
INSERT INTO `countries` VALUES (91, 'Senegal', 'senegal.gif');
INSERT INTO `countries` VALUES (92, 'Того', 'togo.gif');
INSERT INTO `countries` VALUES (93, 'Северная Корея', 'northkorea.gif');
INSERT INTO `countries` VALUES (94, 'Хорватия', 'croatia.gif');
INSERT INTO `countries` VALUES (95, 'Эстония', 'estonia.gif');
INSERT INTO `countries` VALUES (96, 'Колумбия', 'colombia.gif');
INSERT INTO `countries` VALUES (97, 'Леванон', 'lebanon.gif');
INSERT INTO `countries` VALUES (98, 'Латвия', 'latvia.gif');
INSERT INTO `countries` VALUES (99, 'Коста Рика', 'costarica.gif');
INSERT INTO `countries` VALUES (100, 'Египт', 'egypt.gif');
INSERT INTO `countries` VALUES (101, 'Болгария', 'bulgaria.gif');
INSERT INTO `countries` VALUES (102, 'Исла де Муерто', 'jollyroger.gif');
INSERT INTO `countries` VALUES (103, 'Казахстан', 'kazahstan.png');
INSERT INTO `countries` VALUES (104, 'Молдова', 'moldova.gif');

-- --------------------------------------------------------

-- 
-- Структура таблицы `cron`
-- 

CREATE TABLE `cron` (
  `cron_name` varchar(255) NOT NULL,
  `cron_value` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cron_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `cron`
-- 

INSERT INTO `cron` VALUES ('announce_interval', 15);
INSERT INTO `cron` VALUES ('autoclean_interval', 900);
INSERT INTO `cron` VALUES ('delete_votes', 1440);
INSERT INTO `cron` VALUES ('in_cleanup', 0);
INSERT INTO `cron` VALUES ('in_remotecheck', 0);
INSERT INTO `cron` VALUES ('last_cleanup', 0);
INSERT INTO `cron` VALUES ('last_remotecheck', 0);
INSERT INTO `cron` VALUES ('max_dead_torrent_time', 744);
INSERT INTO `cron` VALUES ('num_checked', 0);
INSERT INTO `cron` VALUES ('num_cleaned', 0);
INSERT INTO `cron` VALUES ('pm_delete_sys_days', 3);
INSERT INTO `cron` VALUES ('pm_delete_user_days', 15);
INSERT INTO `cron` VALUES ('promote_rating', 50);
INSERT INTO `cron` VALUES ('rating_checktime', 180);
INSERT INTO `cron` VALUES ('rating_discounttorrent', 1);
INSERT INTO `cron` VALUES ('rating_dislimit', -200);
INSERT INTO `cron` VALUES ('rating_downlimit', -10);
INSERT INTO `cron` VALUES ('rating_enabled', 1);
INSERT INTO `cron` VALUES ('rating_freetime', 7);
INSERT INTO `cron` VALUES ('rating_max', 300);
INSERT INTO `cron` VALUES ('rating_perdownload', 1);
INSERT INTO `cron` VALUES ('rating_perleech', 1);
INSERT INTO `cron` VALUES ('rating_perrelease', 5);
INSERT INTO `cron` VALUES ('rating_perseed', 1);
INSERT INTO `cron` VALUES ('remotecheck_disabled', 0);
INSERT INTO `cron` VALUES ('remotepeers_cleantime', 10800);
INSERT INTO `cron` VALUES ('remote_torrents', 30);
INSERT INTO `cron` VALUES ('signup_timeout', 5);
INSERT INTO `cron` VALUES ('ttl_days', 100);
INSERT INTO `cron` VALUES ('rating_perrequest', 10);
INSERT INTO `cron` VALUES ('rating_perinvite', 5);
INSERT INTO `cron` VALUES ('remotecheck_interval', 60);

-- --------------------------------------------------------

-- 
-- Структура таблицы `cron_emails`
-- 

CREATE TABLE `cron_emails` (
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `cron_emails`
-- 


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
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=75 ;

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
INSERT INTO `faq` VALUES (10, 'item', 'Как скачивать файлы по torrent?', 'Для этого вам пригодяться эти ссылки:<br /><br /><address><a href="pagedetails.php?id=9">1.Как раздавать? Начало.</a><br /><a href="pagedetails.php?id=3">2.Как тут качать? Как закачать отдельный файл из торрента?</a><br /><a href="pagedetails.php?id=14">3.Создание торрента при помощи uTorrent</a><br /><br /></address>', 1, 1, 1);
INSERT INTO `faq` VALUES (11, 'item', 'На что расходуются деньги от пожертвований?', 'Пока, к сожалению, только на пиво администраторам.', 1, 1, 2);
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
INSERT INTO `faq` VALUES (26, 'item', 'Как мне добавить аватар в свой профиль?', 'Для начала найдите картинку, которая вам понравиться, и подходящую под <a class=altlink href=rules.php>правила</a>. Потом вам необходимо <a href="avatarup.php">перейти на эту страницу</a> для загрузки аватара.', 1, 2, 13);
INSERT INTO `faq` VALUES (27, 'item', 'Наиболее часто встречающиеся причины необновления статистики.', '<ul>\r\n<li>Юзер - читер. (ака "Быстрый Бан")</li>\r\n<li>Сервер перегружен и не отвечает. Просто постарайтесь продержать сессию открытой, пока сервер не заработает снова. (Зафлуживание сервера путём переодического ручного обновления страницы не рекомендуется.)</li>\r\n<li>Вы используете плохой/неисправный клиент. Если вы хотите использовать экспериментальную версию, используйте её на свой страх и риск.</li>\r\n</ul>', 1, 3, 1);
INSERT INTO `faq` VALUES (28, 'item', 'Полезные советы.', '<ul>\r\n<li>Если торрент, который вы скачиваете/раздаёте, не отображен в списке ваших закачек просто подождите, или обновите страницу вручную.</li>\r\n<li>Убедитесь, что вы правильно закрыли ваш клиент, и трекер получил "event=completed".</li>\r\n<li>Если сервер упал, и лежит не прекращайте раздачу. Если его подымут до того, как вы выйдете из клиента, статистика обновится автоматически.</li>\r\n</ul>', 1, 3, 2);
INSERT INTO `faq` VALUES (29, 'item', 'Можно ли использовать любые торрент-клиенты?', 'Да. На данный момент трекер обновляет статистику корректно, при использовании любого торрент-клиента. (кроме забаненных конечно) Тем не менее мы рекомендуем <b>не использовать</b> следующие клиенты:br>\r\n<ul>\r\n<li>BitTorrent++</li>\r\n<li>Nova Torrent</li>\r\n<li>TorrentStorm</li>\r\n</ul>\r\nЭти клиенты неверно обрабатывают отмену/остановку торрент-сессии. Если вы их используете, возможна ситуации, когда в деталях торренты будут перечислены даже после завершения загрузки, или закрытия клиента.<br>\r\n<br>\r\nТак же, не рекомендуется использовать клиенты альфа(alpha) или бета(beta) версий.', 1, 3, 3);
INSERT INTO `faq` VALUES (30, 'item', 'Почему торрент, который я скачиваю/раздаю, отображается несколько раз в моем профиле?', 'Если по некоторым причинам (например экстренная перезагрузка компьютера, или зависание клиента) ваш клиент завершил работу некорректно, и вы перезапустили его, вам будет выдан новый "peer_id", таким образом ваша закачка будет опознана, как новый(другой) торрент. А по старому торренту сервер так никогда и не получит "event=completed" или "event=stopped", и будет отображать его некоторое время в списке ваших активных торрентов. Не обращайте на это внимания, в конечном счете глюк пропадет.', 1, 3, 4);
INSERT INTO `faq` VALUES (31, 'item', 'Я закончил или отменил торрент. Почему в моем профиле он все ещё отображается?', 'Некоторые клиенты, особенно TorrentStorm и Nova Torrent не отправляют серверу сообщение о прекращении или отмене торрента. В таких случаях трекер будет ждать сообщения от вашего клиента, и отображать что вы скачиваете или раздаете ещё некоторое время. Не обращайте внимания, через некоторое время торрент все-таки пропадет из списка ваших активных торрентов.', 1, 3, 5);
INSERT INTO `faq` VALUES (32, 'item', 'Почему иногда в моем профайле присутствуют торренты, которые я никогда не качал!?', 'Когда запускается торрен-сессия трекер использует passkey для опознания пользователя. Возможно кто-то украл/узнал ваш пасскей. Обязательно смените его у себя профиле если вдруг обнаружите такое. Учтите, что после смены пасскея вам придется перекачать все активные торренты.', 1, 3, 6);
INSERT INTO `faq` VALUES (33, 'item', 'Несколько IP (Могу ли я логинится с разных компьютеров)?', 'Да, трекер поддерживает несколько сессий с разных IP для одного пользоателя. Торрент ассоциируется с пользователем в тот момент, когда он стартует закачку, и только в этот момент IP важен. Таким образом, если вы хотите скачивать/раздавать с компьютера А и компьютера Б используя один и тот же аккаунт, вам необходимо залогиниться на сайт с компьютера А, запустить торрент, и затем проделать то же самое с компьтера Б (2 компьютера использовано только для примера, ограничений на количество нет. Главное - выполнять оба шага на каждом из компьтеров). Вам не нужно перелогиниваться заново, когда вы закрываете клиент.\r\n', 1, 3, 7);
INSERT INTO `faq` VALUES (34, 'item', 'Как NAT/ICS может испортить картину?', 'В случае использования NAT вам необходимо настроить разные диапазоны для торрент-клиентов на разных компьютерах, и создать NAT правила в роутере. (Подробности настройки роутеров выходят за рамки данного FAQ`а, поэтому обратитесь к документации к вашему девайсу и/или на форум техподдержки). Часто в сетях нет возможности конфигурировать роутеры по своему усмотрению. Вам прийдется пользоваться трекером на свой страх и риск. За ошибки связанные с работой за NAT`ом администрация ответственности не несет.', 1, 3, 8);
INSERT INTO `faq` VALUES (36, 'item', 'Почему я не могу раздавать?', 'Вы можете раздавать, видимо вы не увидели <a href="upload.php">Форму создания релиза</a>', 1, 4, 1);
INSERT INTO `faq` VALUES (37, 'item', 'Что мне надо сделать, чтобы стать <font color="orange">Релизером</font> ?', 'Вам необходимо написать ЛС с соответствующей просьбой <a class=altlink href=staff.php>администрации</a>. После того, как администрация ознакомится с ней, мы примем решение о Вашем повышении.\r\n\r\n<br><br><b>Требования к кандидатам:</b>\r\n<li>Иметь скорость аплоада более 25 кБ/с</li>\r\n<li>Вы должны быть готовы сидировать торрент как минимум 24 часа или до появления 2-х скачавших.</li>\r\n</ul>\r\n', 1, 4, 2);
INSERT INTO `faq` VALUES (38, 'item', 'Могу ли я раздавать ваши торренты на других трекерах?', 'Да, естественно. Только укажите, что это наш релиз.', 1, 4, 3);
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
INSERT INTO `faq` VALUES (70, 'item', 'Что такое DHT ?', 'DHT - Distributed Hash Table, - функция, которая помогает вам обмениваться файлами через BitTorrent, даже если наш сайт не сможет функционировать, а также помогает быстрее находить компьютерам друг друга, тем самым снижая нагрузку на наш сервер.', 1, 5, 15);
INSERT INTO `faq` VALUES (71, 'item', 'Рекомендуемые BT-клиенты:', '<p><strong>Кроссплатформенные клиенты:</strong><br /> Azureus<br /> BitTornado<br /> <br /> <strong>Клиенты под Windows:</strong><br /> &micro;Torrent<br /> ABC<br /> <br /> <strong>Клиенты под Mac:</strong><br /> Tomato Torrent<br /> BitRocket (lastest version)<br /> rtorrent<br /> <br /> <strong>Клиент под Linux:</strong><br /> rtorrent<br /> ktorrent<br /> deluge<br /> <em><strong>transmission</strong></em></p>', 1, 1, 4);
INSERT INTO `faq` VALUES (73, 'item', 'Почему написано, что невозможно подключится, хотя я не использую NAT/Firewall?', 'Наш трекер достаточно сообразительный относительно вопроса определения вашего реального IP, однако, ему необходимо, чтобы прокси отсылал заголовок HTTP_X_FORWARDED_FOR. Если прокси вашего провайдера этого не делает - происходит следующее: трекер интерпретирует IP прокси, как ваш собственный. И когда вы пытаетесь зайти на трекер, он пытается соединится с вашим клиентом, что бы определить, сидите ли вы за NAT/firewall, однако на самом деле он коннектиться к прокси-серверу, по порту, который вы указали в своём клиенте. Т.к. прокси ничего не принимает по данному порту, следовательно соединение не будет установлено, и трекер будет думать, что вы за натом/стенкой', 1, 7, 3);
INSERT INTO `faq` VALUES (74, 'item', 'А что означает этот значок <img src="pic/freedownload.gif" border="0"> около торрента в списке?', 'Этот значок означает, что торрент "бесплатный", то есть если вы будете его качать, то у вас будет считаться только количество загруженной информации. Все что вы скачаете на этом торренте не будет записано в глобальную статистику.', 1, 2, 14);

-- --------------------------------------------------------

-- 
-- Структура таблицы `files`
-- 

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL,
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
  `confirmed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`,`friendid`),
  UNIQUE KEY `friendid` (`friendid`,`userid`)
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
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  `confirmed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
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
  `added` int(10) default NULL,
  `subject` varchar(255) NOT NULL,
  `msg` text,
  `unread` tinyint(1) NOT NULL default '1',
  `poster` int(10) unsigned NOT NULL default '0',
  `location` tinyint(1) NOT NULL default '1',
  `saved` tinyint(1) NOT NULL default '0',
  `archived` tinyint(1) NOT NULL default '0',
  `archived_receiver` tinyint(1) NOT NULL default '0',
  `spamid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`),
  KEY `poster` (`poster`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

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
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
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
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`news`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `newscomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `notifs`
-- 

CREATE TABLE `notifs` (
  `id` int(11) NOT NULL auto_increment,
  `checkid` int(11) NOT NULL default '0',
  `type` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `checkid` (`checkid`,`type`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `notifs`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `orbital_blocks`
-- 

CREATE TABLE `orbital_blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `bkey` varchar(15) NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL,
  `weight` int(10) NOT NULL default '1',
  `active` int(1) NOT NULL default '1',
  `time` int(10) NOT NULL default '0',
  `blockfile` varchar(255) NOT NULL,
  `view` int(1) NOT NULL default '0',
  `expire` int(10) NOT NULL default '0',
  `action` char(1) NOT NULL,
  `which` varchar(255) NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=17 ;

-- 
-- Дамп данных таблицы `orbital_blocks`
-- 

INSERT INTO `orbital_blocks` VALUES (1, '', 'Новинки!', '', 'c', 2, 1, 0, 'block-indextorrents.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (2, '', 'Новости', '', 'c', 1, 1, 0, 'block-news.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (3, '', 'Serverload', '', 'c', 3, 1, 0, 'block-server_load.php', 2, 0, 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (4, '', 'Добро пожаловать', '', 'l', 1, 1, 0, 'block-userpanel.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (5, '', 'Опросы', '', 'd', 2, 1, 0, 'block-polls.php', 1, 0, 'd', 'ihome,userdetails,');
INSERT INTO `orbital_blocks` VALUES (6, '', 'О проекте', 'что нибудь такое..', 'r', 1, 1, 0, '', 0, 0, 'd', 'faq,rules,signup,');
INSERT INTO `orbital_blocks` VALUES (16, '', 'Меню', '', 'r', 2, 1, 0, 'block-login.php', 0, 0, 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (7, '', 'Пользователи Онлайн', '', 'r', 3, 1, 0, 'block-online.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (8, '', 'Статистика', '<h2><span style="color: #ffcc00;">Чтобы знали, что вас ожидает, посмотрите на статистику:</span></h2>', 'd', 1, 1, 0, 'block-stats.php', 0, 0, 'd', 'signup,');
INSERT INTO `orbital_blocks` VALUES (10, '', 'Теги', '', 'l', 2, 1, 0, 'block-cloud.php', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (12, '', 'Запрещенные релизы', '', 'd', 3, 1, 0, 'block-cen.php', 1, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (13, '', 'Одной строкой', '<h1 style="text-align: center;">Уже 1 миллион пиров! И это - не предел!</h1>\r\n<h1 style="text-align: center;">Вы новенький? <a href="pagedetails.php?id=28">Ознакомьтесь</a></h1>', 'c', 4, 0, 0, '', 0, 0, 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (14, '', 'Запросы', '', 'l', 3, 1, 0, 'block-req.php', 1, 0, 'd', 'ihome,');

-- --------------------------------------------------------

-- 
-- Структура таблицы `pagecomments`
-- 

CREATE TABLE `pagecomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `page` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`page`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `pagecomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `pages`
-- 

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` varchar(100) NOT NULL,
  `owner` int(10) NOT NULL default '0',
  `added` int(10) NOT NULL,
  `name` varchar(300) NOT NULL,
  `class` int(2) NOT NULL default '0',
  `tags` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `comments` int(5) unsigned NOT NULL default '0',
  `indexed` tinyint(1) NOT NULL default '0',
  `sticky` tinyint(1) NOT NULL default '0',
  `ratingsum` int(5) NOT NULL default '0',
  `denycomments` tinyint(1) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `pages`
-- 

INSERT INTO `pages` VALUES (1, '1', 1, 1265825583, 'test', 5, 'test', 'test', 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

-- 
-- Структура таблицы `pagescategories`
-- 

CREATE TABLE `pagescategories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL default '0',
  `class` int(2) NOT NULL default '0',
  `class_edit` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `pagescategories`
-- 

INSERT INTO `pagescategories` VALUES (1, 0, 'test', '', 0, 5, 5);

-- --------------------------------------------------------

-- 
-- Структура таблицы `peers`
-- 

CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `peer_id` varchar(20) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `uploadoffset` bigint(20) unsigned NOT NULL default '0',
  `downloadoffset` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` tinyint(1) NOT NULL default '0',
  `started` int(10) NOT NULL,
  `last_action` int(10) NOT NULL,
  `prev_action` int(10) NOT NULL,
  `connectable` tinyint(1) NOT NULL default '1',
  `userid` int(10) unsigned NOT NULL default '0',
  `agent` varchar(60) NOT NULL,
  `finishedat` int(10) unsigned NOT NULL default '0',
  `passkey` varchar(32) NOT NULL,
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
-- Структура таблицы `pollcomments`
-- 

CREATE TABLE `pollcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `poll` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
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
  `question` varchar(255) NOT NULL,
  `start` int(10) NOT NULL,
  `exp` int(10) default NULL,
  `public` tinyint(1) NOT NULL default '0',
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
  PRIMARY KEY  (`id`)
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
  `rid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `type` varchar(30) NOT NULL,
  `added` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `rid` (`rid`,`userid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `ratings`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `relgroups`
-- 

CREATE TABLE `relgroups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `added` int(10) NOT NULL default '0',
  `spec` varchar(500) NOT NULL,
  `descr` text NOT NULL,
  `image` varchar(300) NOT NULL,
  `owners` varchar(100) NOT NULL,
  `members` varchar(255) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `private` tinyint(1) NOT NULL default '0',
  `only_invites` tinyint(1) unsigned NOT NULL default '0',
  `amount` int(3) NOT NULL default '0',
  `page_pay` varchar(300) NOT NULL,
  `subscribe_length` int(2) NOT NULL default '31',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `relgroups`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `reltemplates`
-- 

CREATE TABLE `reltemplates` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `reltemplates`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `reports`
-- 

CREATE TABLE `reports` (
  `id` int(11) NOT NULL auto_increment,
  `reportid` int(10) NOT NULL default '0',
  `userid` int(10) NOT NULL default '0',
  `type` varchar(100) NOT NULL,
  `motive` varchar(255) NOT NULL,
  `added` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `reportid` (`reportid`,`userid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `reports`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `reqcomments`
-- 

CREATE TABLE `reqcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `request` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(10) NOT NULL,
  `ratingsum` int(5) NOT NULL default '9',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`request`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `reqcomments`
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
  `added` int(10) NOT NULL,
  `hits` int(10) unsigned NOT NULL default '0',
  `filled` varchar(200) NOT NULL,
  `comments` int(10) unsigned NOT NULL default '0',
  `cat` int(10) unsigned NOT NULL default '0',
  `filledby` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `requests`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `retrackers`
-- 

CREATE TABLE `retrackers` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `sort` int(3) NOT NULL default '0',
  `announce_url` varchar(500) NOT NULL,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `retrackers`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `rg_invites`
-- 

CREATE TABLE `rg_invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter` int(10) unsigned NOT NULL default '0',
  `inviteid` int(10) NOT NULL default '0',
  `rgid` int(5) NOT NULL default '0',
  `invite` varchar(32) NOT NULL,
  `time_invited` int(10) NOT NULL,
  `confirmed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `rg_invites`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `rg_subscribes`
-- 

CREATE TABLE `rg_subscribes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL,
  `rgid` int(5) unsigned NOT NULL,
  `valid_until` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`,`rgid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `rg_subscribes`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `rgcomments`
-- 

CREATE TABLE `rgcomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `relgroup` int(5) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`relgroup`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `rgcomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `rgnews`
-- 

CREATE TABLE `rgnews` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `relgroup` int(5) NOT NULL default '0',
  `added` int(10) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `rgnews`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `rgnewscomments`
-- 

CREATE TABLE `rgnewscomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `rgnews` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`rgnews`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `rgnewscomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `rules`
-- 

CREATE TABLE `rules` (
  `id` int(10) NOT NULL auto_increment,
  `type` set('categ','item') NOT NULL default 'item',
  `rule` text NOT NULL,
  `flag` tinyint(1) NOT NULL default '1',
  `categ` int(10) NOT NULL default '0',
  `order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=80 ;

-- 
-- Дамп данных таблицы `rules`
-- 

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
INSERT INTO `rules` VALUES (39, 'item', 'Рекомендуемые параметры: 100 X 100 пикселей в ширину и не размером более 60 Kб.  ', 1, 4, 2);
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
INSERT INTO `rules` VALUES (72, 'item', 'Вы можете редактировать тайтлы VIP.  ', 1, 10, 4);
INSERT INTO `rules` VALUES (73, 'item', 'Вы можете видеть полную информацию о пользователях. ', 1, 10, 5);
INSERT INTO `rules` VALUES (74, 'item', 'Вы можете добавлять коментарии к пользователям (для других модераторов и администраторов). ', 1, 10, 6);
INSERT INTO `rules` VALUES (75, 'item', 'Вы можете перестать читать потому-что вы уже знаете про эти возможности. ;)  ', 1, 10, 7);
INSERT INTO `rules` VALUES (76, 'item', 'В конце концов посмотрите страничку Администрация (правый верхний угол).  ', 1, 10, 8);
INSERT INTO `rules` VALUES (77, 'categ', 'Правила для Аплодеров', 1, 0, 11);
INSERT INTO `rules` VALUES (78, 'item', 'Может удалять и редактировать релизы. ', 1, 77, 1);
INSERT INTO `rules` VALUES (79, 'item', 'Аплодер обязан выкладывать релизы не менее 4 в месяц', 1, 77, 2);

-- --------------------------------------------------------

-- 
-- Структура таблицы `sessions`
-- 

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL,
  `uid` int(10) NOT NULL default '0',
  `username` varchar(40) NOT NULL,
  `class` tinyint(4) NOT NULL default '0',
  `ip` varchar(40) NOT NULL,
  `time` bigint(30) NOT NULL default '0',
  `url` varchar(150) NOT NULL,
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
-- Структура таблицы `sitelog`
-- 

CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` int(10) default NULL,
  `userid` int(10) NOT NULL default '0',
  `txt` text,
  `type` varchar(80) NOT NULL default 'tracker',
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `sitelog`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `snatched`
-- 

CREATE TABLE `snatched` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `startedat` int(10) NOT NULL,
  `completedat` int(10) NOT NULL,
  `finished` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `snatch` (`torrent`,`userid`)
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
  PRIMARY KEY  (`id`)
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
  `uri` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

-- 
-- Дамп данных таблицы `stylesheets`
-- 

INSERT INTO `stylesheets` VALUES (1, 'kinokpk', 'Kinokpk.com releaser');

-- --------------------------------------------------------

-- 
-- Структура таблицы `torrents`
-- 

CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info_hash` varbinary(40) NOT NULL default '',
  `name` varchar(255) NOT NULL,
  `descr` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL default '0',
  `ismulti` tinyint(1) NOT NULL default '0',
  `numfiles` int(10) unsigned NOT NULL default '1',
  `comments` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `times_completed` int(10) unsigned NOT NULL default '0',
  `last_action` int(10) NOT NULL default '0',
  `last_reseed` int(10) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '1',
  `banned` tinyint(1) NOT NULL default '0',
  `owner` int(10) unsigned NOT NULL default '0',
  `orig_owner` int(10) unsigned NOT NULL default '0',
  `ratingsum` int(10) NOT NULL default '0',
  `free` tinyint(1) NOT NULL default '0',
  `sticky` tinyint(1) NOT NULL default '0',
  `moderated` tinyint(1) NOT NULL default '0',
  `modcomm` text NOT NULL,
  `moderatedby` int(10) unsigned default '0',
  `freefor` text NOT NULL,
  `relgroup` int(5) NOT NULL,
  `topic_id` int(10) NOT NULL default '0',
  `online` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `torrents`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `trackers`
-- 

CREATE TABLE IF NOT EXISTS `trackers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL,
  `tracker` varchar(255) NOT NULL DEFAULT 'localhost',
  `seeders` int(5) unsigned NOT NULL DEFAULT '0',
  `leechers` int(5) unsigned NOT NULL DEFAULT '0',
  `lastchecked` int(10) unsigned NOT NULL DEFAULT '0',
  `state` varchar(300) NOT NULL,
  `num_failed` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent` (`torrent`,`tracker`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `trackers`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `usercomments`
-- 

CREATE TABLE `usercomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `added` int(10) NOT NULL,
  `text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` int(11) NOT NULL,
  `ratingsum` int(5) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `usercomments`
-- 


-- --------------------------------------------------------

-- 
-- Структура таблицы `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(40) NOT NULL,
  `old_password` varchar(40) NOT NULL,
  `passhash` varchar(32) NOT NULL,
  `secret` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `confirmed` tinyint(1) NOT NULL default '0',
  `added` int(10) NOT NULL default '0',
  `last_login` int(10) NOT NULL default '0',
  `last_access` int(10) NOT NULL default '0',
  `editsecret` varchar(20) NOT NULL,
  `privacy` enum('strong','normal','low') NOT NULL default 'normal',
  `stylesheet` int(10) default '1',
  `info` text,
  `ratingsum` int(8) NOT NULL default '0',
  `acceptpms` enum('yes','friends','no') NOT NULL default 'yes',
  `pron` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `class` tinyint(3) unsigned NOT NULL default '0',
  `override_class` tinyint(3) unsigned NOT NULL default '255',
  `supportfor` text,
  `avatar` varchar(100) NOT NULL,
  `icq` varchar(255) NOT NULL,
  `msn` varchar(255) NOT NULL,
  `aim` varchar(255) NOT NULL,
  `yahoo` varchar(255) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `mirc` varchar(255) NOT NULL,
  `website` varchar(50) NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(30) NOT NULL,
  `country` int(10) unsigned NOT NULL default '0',
  `notifs` varchar(1000) NOT NULL,
  `emailnotifs` varchar(1000) NOT NULL,
  `modcomment` text,
  `enabled` tinyint(1) NOT NULL default '1',
  `dis_reason` text NOT NULL,
  `timezone` int(2) NOT NULL default '0',
  `avatars` tinyint(1) NOT NULL default '1',
  `extra_ef` tinyint(1) NOT NULL default '1',
  `donor` tinyint(1) default '0',
  `warned` tinyint(1) default '0',
  `warneduntil` int(10) NOT NULL,
  `deletepms` tinyint(1) default '0',
  `savepms` tinyint(1) NOT NULL default '1',
  `gender` smallint(1) NOT NULL default '0',
  `birthday` date default '0000-00-00',
  `passkey` varchar(32) NOT NULL,
  `language` varchar(255) default NULL,
  `invites` int(10) NOT NULL default '0',
  `invitedby` int(10) NOT NULL default '0',
  `invitedroot` int(10) NOT NULL default '0',
  `passkey_ip` varchar(15) NOT NULL,
  `num_warned` int(2) NOT NULL default '0',
  `status` varchar(255) NOT NULL,
  `last_downloaded` int(10) NOT NULL default '0',
  `last_checked` int(10) NOT NULL default '0',
  `discount` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`confirmed`,`added`),
  KEY `ip` (`ip`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`confirmed`,`enabled`),
  KEY `passkey` (`passkey`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- 
-- Дамп данных таблицы `users`
-- 

