<?php

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

require_once("include/bittorrent.php");

function bark($msg) {
	stderr($tracker_lang['error'], $msg);
}

dbconn();

loggedinorreturn();

foreach(explode(":","type:name") as $v) {
	if (!$_POST[$v])
	bark("Не все поля заполнены");
}

if (!is_array($_POST["type"]))
bark("Ошибка обработки выбранных категорий!");
else
foreach ($_POST['type'] as $cat) if (!is_valid_id($cat)) bark($tracker_lang['error'],$tracker_lang['invalid_id']);

$catsstr = implode(',',$_POST['type']);

if (!empty($_POST["name"]))
$torrent = unesc($_POST["name"]); else bark("Вы не ввели название страницы");

if ($_POST['sticky'] AND get_user_class() >= UC_MODERATOR)
$sticky = 1;
else
$sticky = 0;



$descr = (string) $_POST['descr'];

if (!$descr) stderr($tracker_lang['error'],'Вы не ввели текст страницы');

$denycomments = $_POST['denycomments']?1:0;
$class = (int)$_POST['class'];
$tags = htmlspecialchars(trim((string)$_POST['tags']));
if ($class>get_user_class()) bark('Выбор класса запрещен');

if ((get_user_class() >= UC_MODERATOR) && $_POST['indexed']) $indexed=1; else $indexed=0;
if ((get_user_class() >= UC_MODERATOR) && $_POST['system']) $system=0; else $system=$CURUSER['id'];

$descr = ($descr);


sql_query("INSERT INTO pages (owner,added,name,tags,category,content,indexed,denycomments,class,sticky) VALUES (".implode(",",array_map("sqlesc",array($system,time(),htmlspecialchars($_POST['name']),$tags,$catsstr,$descr,$indexed,$denycomments,$class,$sticky))).")") or die(mysql_error());
$id = mysql_insert_id();
$CACHE->clearGroupCache('pages');
sql_query("INSERT INTO notifs (checkid, userid, type) VALUES ($id, $CURUSER[id], 'pagecomments')") or sqlerr(__FILE__,__LINE__);

stderr($tracker_lang['success'],"Добавлена страница".' <a href="pagedetails.php?id='.$id.'">pagedetails.php?id='.$id.'</a>','success');

//write_log("Cnhfybwf  $id ($torrent) был залит пользователем " . $CURUSER["username"],"5DDB6E","torrent");

/* Email notifs */

?>
