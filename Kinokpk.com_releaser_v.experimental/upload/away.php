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
INIT(false);

$url = strip_tags(trim((string)preg_replace("#(.*?)url=#si", "", getenv("REQUEST_URI"))));
print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="'.$REL_CONFIG['description'].'" />
<meta name="Keywords" content="'.$REL_CONFIG['keywords'].'" />
<title>'.$REL_CONFIG['sitename'].' - Переход по внешней ссылке</title>
</head>
<body  style="padding:20px 180px; font-size:12px; font-family:Tahoma; line-height:200%">
<h2>'.$REL_CONFIG['sitename'].' - Переход по внешней ссылке</h2>

Вы покидаете сайт '.$REL_CONFIG['sitename'].' по внешней ссылке <b>'.urldecode($url).'</b>, предоставленной одним из участников. <br/>
<a href="'.$REL_SEO->make_link('staff').'">Администрация</a> '.$REL_CONFIG['sitename'].' не несет ответственности за содержимое сайта <b>'.urldecode($url).'</b> и настоятельно рекомендует <b>не указывать</b> никаких своих данных, имеющих отношение к '.$REL_CONFIG['sitename'].' (особенно <b>e-mail</b>, <b>пароль</b> и <b>cookies</b>), на сторонних сайтах. <br/><br/>
Кроме того, сайт <b>'.urldecode($url).'</b> может содержать вирусы, трояны и другие вредоносные программы, опасные для Вашего компьютера. <br/>
Если у Вас нет серьезных оснований доверять этому сайту, лучше всего на него не переходить, даже если Вы якобы получили эту ссылку от одного из Ваших <a href="'.$REL_SEO->make_link('friends').'">друзей</a>. <br/><br/>
Если Вы еще не передумали, нажмите на <a href="'.$url.'" id="to_go">'.urldecode($url).'</a>. <br/>
Если Вы не хотите рисковать безопасностью Вашего аккаунта и компьютера, нажмите <a href="javascript:history.go(-1);">отмена</a>.
</body>
</html>
');
?>