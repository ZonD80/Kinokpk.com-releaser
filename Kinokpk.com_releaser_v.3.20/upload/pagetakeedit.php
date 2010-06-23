<?

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

dbconn();

loggedinorreturn();


if (!is_valid_id($_POST['id'])) stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key("invalid_id"));
$id = (int) $_POST['id'];

$res = sql_query("SELECT * FROM pages WHERE pages.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key("invalid_id"));

if (isset($_POST['delete'])) {

	if ((get_user_class() < UC_MODERATOR) && ($row['owner']<>$CURUSER['id'])) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));

	sql_query("DELETE FROM pages WHERE id=$id LIMIT 1");
	sql_query("DELETE FROM notifs WHERE checkid=$id AND type='pagecomments'");
	sql_query("DELETE FROM ratings WHERE type='pages' AND rid=$id");
	sql_query("DELETE FROM pagecomments WHERE page=$id");
	$REL_CACHE->clearGroupCache('pages');
	stderr($REL_LANG->say_by_key('success'),'Страница успешно удалена <a href="'.$REL_SEO->make_link('pagebrowse').'">К списку страниц</a>','success');
}


function bark($msg) {
	stderr("Ошибка", $msg);
}

foreach(explode(":","type:name") as $v) {
	if (!$_POST[$v])
	bark("Не все поля заполнены");
}

if (!is_array($_POST["type"]))
bark("Ошибка обработки выбранных категорий!");
else
foreach ($_POST['type'] as $cat) if (!is_valid_id($cat)) bark('Вы не выбрали категорию');


if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR)
bark("You're not the owner! How did that happen?\n");

$updateset = array();


$name = htmlspecialchars((string)($_POST['name']));

$updateset[] = "name = " . sqlesc($name);

$catsstr = implode(',',$_POST['type']);

$updateset[] = "category = " . sqlesc($catsstr);
$updateset[] = "tags = " . sqlesc(trim(strip_tags($_POST['tags'])));


if(get_user_class() >= UC_MODERATOR) {

	$updateset[] = "sticky = ".($_POST['sticky']?1:0);
	$updateset[] = "class = ".(int)$_POST['class'];
	if ($_POST['system']) $updateset[] = "owner = 0";
	$updateset[] = "indexed = ".($_POST['indexed']?1:0);
	$updateset[] = "denycomments = ".($_POST['denycomments']?1:0);
	 
}

$descr = ((string)$_POST['descr']);

$updateset[] = 'content = '.sqlesc($descr);

if ($_POST['upd']) $updateset[] = "added = " . time();

sql_query("UPDATE pages SET " . join(",", $updateset) . " WHERE id = $id");


//write_log("Торрент '$name' был отредактирован пользователем $CURUSER[username]\n","torrent");
$returl = (isset($_POST["returnto"]) ? $REL_SEO->make_link('pagedetails','id',$id,'returnto',strip_tags($_POST["returnto"])) : $REL_SEO->make_link('pagedetails','id',$id));
$REL_CACHE->clearGroupCache('pages');
safe_redirect(" $returl");

?>
