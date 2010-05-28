<?php
/**
 * Just a redirect script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");
dbconn(false);

$url = strip_tags(trim(((string)preg_replace("#/away.php\?url=#i", "", getenv("REQUEST_URI")))));
print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$tracker_lang['language_charset'].'" />
<meta name="Description" content="'.$CACHEARRAY['description'].'" />
<meta name="Keywords" content="'.$CACHEARRAY['keywords'].'" />
<title>'.$CACHEARRAY['sitename'].' - Переход по внешней ссылке</title>
</head>
<body  style="padding:20px 180px; font-size:12px; font-family:Tahoma; line-height:200%">
<h2>'.$CACHEARRAY['sitename'].' - Переход по внешней ссылке</h2>

Вы покидаете сайт '.$CACHEARRAY['sitename'].' по внешней ссылке <b>'.$url.'</b>, предоставленной одним из участников. <br/>
<a href="'.$CACHEARRAY['defaultbaseurl'].'/staff.php">Администрация</a> '.$CACHEARRAY['sitename'].' не несет ответственности за содержимое сайта <b>'.$url.'</b> и настоятельно рекомендует <b>не указывать</b> никаких своих данных, имеющих отношение к '.$CACHEARRAY['sitename'].' (особенно <b>e-mail</b>, <b>пароль</b> и <b>cookies</b>), на сторонних сайтах. <br/><br/>
Кроме того, сайт <b>'.$url.'</b> может содержать вирусы, трояны и другие вредоносные программы, опасные для Вашего компьютера. <br/>
Если у Вас нет серьезных оснований доверять этому сайту, лучше всего на него не переходить, даже если Вы якобы получили эту ссылку от одного из Ваших <a href="'.$CACHEARRAY['defaultbaseurl'].'/friends.php">друзей.</a> <br/><br/>
Если Вы еще не передумали, нажмите на <a href="'.$url.'" id="to_go">'.$url.'</a>. <br/>
Если Вы не хотите рисковать безопасностью Вашего аккаунта и компьютера, нажмите <a href="javascript:history.go(-1);">отмена</a>.
</body>
</html>
');
?>