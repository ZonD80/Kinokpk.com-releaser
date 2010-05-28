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

gzip();

if (isset($_GET['checkonly'])) {
	if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
		$ajax = 1;

		header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

		$page = (int) $_GET["page"];

	} else $ajax=0;

	if (get_user_class() < UC_MODERATOR) die($tracker_lang['error'].': '.$tracker_lang['invalid_id']);
	getlang('details');
	$id = (int) $_GET['id'];
	if (!$ajax) $return = ' | <a href="details.php?id='.$id.'">'.$tracker_lang['back'].'</a>';

	$res = sql_query("SELECT id, moderatedby FROM torrents WHERE id =$id");
	$row = mysql_fetch_assoc($res);
	if (!$row) die($tracker_lang['error'].': '.$tracker_lang['invalid_id'].$return);

	$clearcache = array('block-indextorrents','browse-normal','browse-cat');

	foreach ($clearcache as $cachevalue)
	$CACHE->clearGroupCache($cachevalue);

	if ($row['moderatedby']) {
		sql_query("UPDATE torrents SET moderatedby=0 WHERE id=$id");
		die($tracker_lang['not_yet_checked'].' <a onclick="return ajaxcheck();" href="takeedit.php?checkonly&id='.$id.'">'.$tracker_lang['check'].'</a>'.$return);
	}
	else {
		sql_query("UPDATE torrents SET moderatedby={$CURUSER['id']}, moderated=0 WHERE id=$id");
		die($tracker_lang['checked_by'].'<a href="userdetails.php?id='.$CURUSER['id'].'">'.get_user_class_color(get_user_class(),$CURUSER['username']).'</a> <a onclick="return ajaxcheck();" href="takeedit.php?checkonly&id='.$id.'">'.$tracker_lang['uncheck'].'</a>'.$return);
	}
}


require_once("include/benc.php");


function bark($msg) {
	stderr("Ошибка", $msg);
}

foreach(explode(":","type:name") as $v) {
	if (!isset($_POST[$v]))
	bark("Не все поля заполнены");
}

if (!is_array($_POST["type"]))
bark("Ошибка обработки выбранных категорий!");
else
foreach ($_POST['type'] as $cat) if (!is_valid_id($cat)) bark($tracker_lang['error'],$tracker_lang['invalid_id']);

if (!is_valid_id($_POST['id'])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
$id = (int) $_POST['id'];

if ($_POST['multi']) $multi=1; else $multi=0;


$res = sql_query("SELECT torrents.owner, torrents.filename, torrents.images, torrents.topic_id, categories.name AS catname FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);

if (($row["filename"] == 'nofile') && (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR && !$tedit)
bark("You're not the owner! How did that happen?\n");

$updateset = array();

////////////////////////////////////////////////

$images = explode(',',$row['images']);

//////////////////////////////////////////////
//////////////Take Image Uploads//////////////

$maxfilesize = 512000; // 500kb

for ($x=0; $x < $CACHEARRAY['max_images']; $x++) {

	if (!empty($_POST['img'.$x])) {
		$img=trim((string)$_POST['img'.$x]);
		if (strpos($img,',') || strpos($img,'?')) stderr($tracker_lang['error'],'Динамические изображения запрещены');

		$check = @getimagesize($img);
		if (!$check) stderr($tracker_lang['error'],'Загружаемый картинка - не картинка');
		$check = remote_fsize($img);
		if (!$check) stderr($tracker_lang['error'],'Не удалось определить размер картинки');
		if ($check>$maxfilesize) stderr($tracker_lang['error'],'Максимальный размер картинок 512kb');
		$inames[]=$img;
	} else unset($images[$x]);
}

$image = $inames;

$image = @array_shift($image);
$images = @implode(',',$inames);

$updateset[]="images=".sqlesc($images);

////////////////////////////////////////////////

if (($_POST['nofile']) && (empty($_POST['nofilesize']))) bark("Вы не указали размер не торрент релиза!");

if ($_POST['nofile']) {$fname = 'nofile'; } else {
	$fname = $row["filename"];
	preg_match('/^(.+)\.torrent$/si', $fname, $matches);
	$shortfname = $matches[1];
}

if ($_POST['nofile']) {} else {
	if (isset($_FILES["tfile"]) && !empty($_FILES["tfile"]["name"]))
	$update_torrent = true;


	if ($update_torrent) {

		$f = $_FILES["tfile"];
		$fname = unesc($f["name"]);

		if (empty($fname))
		bark("Файл не загружен. Пустое имя файла!");
		if (!validfilename($fname))
		bark("Неверное имя файла!");
		if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
		bark("Неверное имя файла (не .torrent).");
		$tmpname = $f["tmp_name"];
		if (!is_uploaded_file($tmpname))
		bark("eek");
		if (!filesize($tmpname))
		bark("Пустой файл!");
		$dict = bdec_file($tmpname, $CACHEARRAY['max_torrent_size']);
		if (!isset($dict))
		bark("Что за хрень ты загружаешь? Это не бинарно-кодированый файл!");
		list($info) = dict_check($dict, "info");
		list($dname, $plen, $pieces) = dict_check($info, "name(string):piece length(integer):pieces(string)");
		if (strlen($pieces) % 20 != 0)
		bark("invalid pieces");

		$filelist = array();
		$totallen = dict_get($info, "length", "integer");
		if (isset($totallen)) {
			$filelist[] = array($dname, $totallen);
			$torrent_type = 0;
		} else {
			$flist = dict_get($info, "files", "list");
			if (!isset($flist))
			bark("missing both length and files");
			if (!count($flist))
			bark("no files");
			$totallen = 0;
			foreach ($flist as $fn) {
				list($ll, $ff) = dict_check($fn, "length(integer):path(list)");
				$totallen += $ll;
				$ffa = array();
				foreach ($ff as $ffe) {
					if ($ffe["type"] != "string")
					bark("filename error");
					$ffa[] = $ffe["value"];
				}
				if (!count($ffa))
				bark("filename error");
				$ffe = implode("/", $ffa);
				$filelist[] = array($ffe, $ll);
				if ($ffe == 'Thumbs.db')
				{
					stderr("Ошибка", "В торрентах запрещено держать файлы Thumbs.db!");
					die;
				}
			}
			$torrent_type = 1;
		}

		$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash

		unset($dict['value']['nodes']); // remove cached peers (Bitcomet & Azareus)
		unset($dict['value']['azureus_properties']); // remove azureus properties
		unset($dict['value']['comment']);
		unset($dict['value']['created by']);
		unset($dict['value']['publisher']);
		unset($dict['value']['publisher.windows-1251']);
		unset($dict['value']['publisher-url']);
		unset($dict['value']['publisher-url.windows-1251']);


		if (!$multi) {
			//  $dict['value']['info']['value']['private']=bdec('i1e');  // add private tracker flag
			unset($dict['value']['announce-list']);
			unset($dict['value']['announce']);

		} else $anarray = get_announce_urls($dict);

		if ($multi && !$anarray) stderr($tracker_lang['error'],'Этот торрент-файл не является мультитрекерным. <a href="javascript:history.go(-1);">Назад</a>');


		$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash

		list($info) = dict_check($dict, "info");

		$infohash = sha1($info["string"]);
		move_uploaded_file($tmpname, "torrents/$id.torrent");

		$updateset[] = "announce_urls = " . sqlesc(@implode(",",$anarray));
		$fp = fopen("torrents/$id.torrent", "w");
		if ($fp) {
			@fwrite($fp, benc($dict['value']['info']), strlen(benc($dict['value']['info'])));
			fclose($fp);
			@chmod($fp, 0644);
		}
		$updateset[] = "info_hash = " . sqlesc($infohash);
		$updateset[] = "filename = " . sqlesc($fname);
		@sql_query("DELETE FROM files WHERE torrent = $id");
		$nf = count($filelist);

		sql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($dname).",".$totallen.")");
		$updateset[] = "size = ".$totallen;
		$updateset[] = "numfiles = ".$nf;
		$updateset[] = "ismulti = ".$torrent_type;
		if ($_POST['nofile']) $dname = 'nofile';

	}
	// конец НЕ загрузки
}
$name = htmlspecialchars((string)($_POST['name']));

$updateset[] = "name = " . sqlesc($name);

$catsstr = implode(',',$_POST['type']);

$updateset[] = "category = " . sqlesc($catsstr);

if ($_POST['nofile']) {

	$wastor = sql_query("SELECT filename FROM torrents WHERE id =".$id);
	$wastor = mysql_result($wastor,0);

	if ($wastor != 'nofile') {
		sql_query("DELETE FROM files WHERE torrent = ".$id);
		sql_query("DELETE FROM peers WHERE torrent = ".$id);
		sql_query("DELETE FROM snatched WHERE torrent = ".$id);
		$updateset[] = "leechers = 0";
		$updateset[] = "seeders = 0";
		$updateset[] = "remote_seeders=0";
		$updateset[] = "remote_leechers=0";
		$updateset[] = "filename = 'nofile'";

		$ff = "torrents/" . $id.".torrent";
		@unlink($ff);
	}

	$nfz = $_POST['nofilesize'];
	$nofilesize = (int)($nfz*1024*1024);
	$updateset[] = "size = " . $nofilesize;
}

if (get_user_class() >= UC_ADMINISTRATOR) {
	if ($_POST["banned"]) {
		$updateset[] = "banned = 1";
		$_POST["visible"] = 0;
	} else
	$updateset[] = "banned = 0";

}
if(get_user_class() >= UC_MODERATOR) {
	$updateset[] = "free = '".($_POST["free"]? 1 : 0)."'";
	if ($_POST["sticky"])
	$updateset[] = "sticky = 1";
	else
	$updateset[] = "sticky = 0";
	$updateset[] = "visible = '" . ($_POST["visible"] ? 1 : 0) . "'";
}


if ((get_user_class() >= UC_UPLOADER) && isset($_POST['approve'])) {
	$updateset[] = "moderated = 0";
	$updateset[] = "moderatedby = ".$CURUSER["id"];
} else { $updateset[] = "moderated = 1"; $updateset[]= "moderatedby = 0"; }

$descr = ((string)$_POST['descr']);

$updateset[] = 'descr = '.sqlesc($descr);

if ($_POST['upd']) $updateset[] = "added = '" . time() . "'";

sql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");

if (mysql_errno() == 1062) stderr($tracker_lang['error'],'Torrent already uploaded!');

$clearcache = array('block-indextorrents','browse-normal','browse-cat');

foreach ($clearcache as $cachevalue)
$CACHE->clearGroupCache($cachevalue);


if ($CACHEARRAY['use_integration']) {
	/// IPB INTEGRATION ///// EDIT WIKI CONTAINER ////////////

	if ($image <> '') $image = "<div align=\"center\"><a href=\"$image\" target=\"_blank\"><img alt=\"Постер для фильма (кликните для просмотра полного изображения)\" src=\"$image\" width=\"240\" border=\"0\" class=\"linked-image\" /></a></div><br />"; else
	$image = "<div align=\"center\"><img src=\"{$CACHEARRAY['defaultbaseurl']}/pic/noimage.gif\" border=\"0\" class=\"linked-image\" /></div><br />";

	if (!empty($_POST['topic'])) {
		if (is_valid_id($_POST['topic'])) {
			$topicid =  (int) $_POST['topic'];
			sql_query("UPDATE torrents SET topic_id =".$topicid." WHERE id =".$id);
			$topicedit = 1;
		} else stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
	}  else {
		$topicid = $row['topic_id'];
	}


	if ($topicid <> 0) {
		$forumdesc = $image;
		$tree=make_tree();
		$cats = explode(',',$_POST['type']);
		$cat= array_shift($cats);
		$cat = get_cur_branch($tree,$cat);
		$childs = get_childs($tree,$cat['parent_id']);
		if ($childs) {
			foreach($childs as $child)
			if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]=makesafe($child['name']);
		}

		$forumdesc .= "<table width=\"100%\" border=\"1\"><tr><td valign=\"top\"><b>Тип (жанр):</b></td><td>".get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):'')."</td></tr><tr><td><b>Название:</b></td><td>$name</td></tr>";
		$forumdesc .= "<tr><td valign=\"top\"><b>".$tracker_lang['description'].":</b></td><td>".format_comment($descr)."</td></tr>";


		$isnofilesize = sql_query("SELECT filename,size FROM torrents WHERE id = $id");
		$isnofilesize = mysql_fetch_array($isnofilesize);
		$topicfooter = "<tr><td valign=\"top\"><b>Размер файла:</b></td><td>".round($isnofilesize['size']/1024/1024)." МБ</td></tr>";

		$topicfooter .= "<tr><td valign=\"top\"><b>".(($isnofilesize['filename'] != 'nofile')?"Торрент {$CACHEARRAY['defaultbaseurl']}:":"Релиз {$CACHEARRAY['defaultbaseurl']}:")."</b></td><td><div align=\"center\">[<span style=\"color:#FF0000\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/details.php?id=".$id."\">Посмотреть этот релиз на {$CACHEARRAY['defaultbaseurl']}</a></span>]</div></td></tr></table>";

		$forumdesc .= $topicfooter;

		// connecting to IPB DB
		forumconn();
		//connection opened

		$postid = sql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid=".$topicid);
		$postid = mysql_result($postid,0);

		sql_query("UPDATE ".$fprefix."topics SET title = ".sqlesc($name)." WHERE tid=".$topicid);


		if ($CACHEARRAY['exporttype'] == "wiki")
		sql_query("UPDATE ".$fprefix."posts SET wiki = ".sqlesc($forumdesc).", post = '---' WHERE pid=".$postid);
		else
		sql_query("UPDATE ".$fprefix."posts SET post = ".sqlesc($forumdesc)." WHERE pid=".$postid);

		if ($topicedit) {
			$cutplus = strpos($name,"+");
			if ($cutplus === false)
			$topicname = $name;
			else $topicname = substr($name,0,$cutplus);
			if (!empty($_POST['source'])) $dsql = ", description = ".sqlesc(htmlspecialchars($_POST['source'])); else $dsql = '';
			$topic = sql_query("UPDATE ".$fprefix."topics SET title = ".sqlesc($topicname).$dsql." WHERE tid =".$topicid);

		}


		// closing IPB DB connection
		relconn();
		// connection closed

	}
	//////////////////////END/////////////////////////////////////
}

write_log("Торрент '$name' был отредактирован пользователем $CURUSER[username]\n","F25B61","torrent");

$returl = "details.php?id=$id";
if (isset($_POST["returnto"]))
$returl .= "&returnto=" . urlencode($_POST["returnto"]);

header("Refresh: 0; url=$returl");

?>
