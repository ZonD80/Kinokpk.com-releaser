<?php
/**
 * Edit parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();

loggedinorreturn();
$REL_LANG->load('upload');

require_once("include/benc.php");

$id = (int) $_POST['id'];
if (!$id) $id = (int) $_GET['id'];
$res = sql_query("SELECT torrents.id, torrents.name, torrents.owner, torrents.info_hash, torrents.filename, torrents.images, torrents.topic_id, torrents.modcomm, torrents.moderated, torrents.moderatedby, torrents.descr FROM torrents WHERE torrents.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key("invalid_id"));


if (isset($_GET['checkonly'])) {

	headers(true);


	if (get_user_class() < UC_MODERATOR) die($REL_LANG->say_by_key('error').': '.$REL_LANG->say_by_key('invalid_id'));
	$REL_LANG->load('details');
	$id = (int) $_GET['id'];


	$clearcache = array('block-indextorrents','browse-normal','browse-cat');

	foreach ($clearcache as $cachevalue)
	$REL_CACHE->clearGroupCache($cachevalue);

	if ($row['moderatedby']) {
		sql_query("UPDATE torrents SET moderatedby=0 WHERE id=$id");
		die($REL_LANG->say_by_key('not_yet_checked').' <a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id',$id).'">'.$REL_LANG->say_by_key('check').'</a>'.$return);
	}
	else {
		sql_query("UPDATE torrents SET moderatedby={$CURUSER['id']}, moderated=1 WHERE id=$id");
		// send notifs
		if (!$row['moderated']) {
			$bfooter = <<<EOD
Чтобы посмотреть релиз, перейдите по этой ссылке:

			{$REL_CONFIG['defaultbaseurl']}/{$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))}

EOD;
			$descr = format_comment($row['descr']).nl2br($bfooter);
			send_notifs('torrents',format_comment($descr),$CURUSER['id']);
		}

		die($REL_LANG->say_by_key('checked_by').'<a href="'.$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username'])).'">'.get_user_class_color(get_user_class(),$CURUSER['username']).'</a> <a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id',$id).'">'.$REL_LANG->say_by_key('uncheck').'</a>'.$return);
	}
} elseif(isset($_POST['add_trackers'])) {
		if (get_user_class() < UC_UPLOADER) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_deined'));
	
	if (!isset($_POST['trackers'])) stderr($REL_LANG->say_by_key('error'),'Не все поля заполнены');
	$POSTtrackers = explode("\n",trim((string)$_POST['trackers']));
	if (!$POSTtrackers) stderr($REL_LANG->say_by_key('error'), 'Ошибка обработки трекеров');

	$POSTtrackers = array_map("trim",$POSTtrackers);
	$POSTtrackers = array_map("makesafe",$POSTtrackers);
	$res = sql_query("SELECT tracker FROM trackers WHERE torrent=$id AND tracker<>'localhost'") or sqlerr(__FILE__,__LINE__);
	$trackers = array();
	while (list($tracker) = mysql_fetch_array($res)) $trackers[] = $tracker;
	$trackers_to_delete = array_diff($trackers,$POSTtrackers);
	$trackers_to_add = array_diff($POSTtrackers,$trackers);
	foreach ($POSTtrackers as $tid => $tracker) {
		if ($tracker) $state[$tracker] = 'skipped'; else unset($POSTtrackers[$tid]);
	}
	if ($trackers_to_delete)
	foreach ($trackers_to_delete as $tracker) {
		if ($tracker)
		sql_query("DELETE FROM trackers WHERE tracker='$tracker' AND torrent=$id") or sqlerr(__FILE__,__LINE__);
		$state[$tracker] = 'deleted';
	}
	if ($trackers_to_add)
	foreach ($trackers_to_add as $tracker) {
		if ($tracker) {
			$peers = get_remote_peers($tracker, $row['info_hash'],'announce');
			$reason[$tracker] = makesafe($peers['state']);
			if (preg_match('/ok_/',$peers['state'])) {
				sql_query("INSERT INTO trackers (tracker,torrent) VALUES ('$tracker',$id)") or sqlerr(__FILE__,__LINE__);
				sql_query("UPDATE LOW_PRIORITY trackers SET seeders=".(int)$peers['seeders'].", leechers=".(int)$peers['leechers'].", lastchecked=".time().", state='".mysql_real_escape_string($peers['state'])."' WHERE torrent=$id AND tracker='$tracker'") or sqlerr(__FILE__,__LINE__);
				$state[$tracker] = 'added';
			} else $state[$tracker] = 'failed';
		}
	}
	stdhead($REL_LANG->say_by_key('add_announce_urls'));
	$REL_LANG->load('remotepeers');
	print ('<table width="100%"><tr><td class="colhead">'.$REL_LANG->say_by_key('tracker').'</td><td class="colhead">'.$REL_LANG->say_by_key('status').'</td></tr>');
	foreach ($state AS $tracker => $status) {
		print ("<tr><td>$tracker</td><td>{$REL_LANG->say_by_key('tracker_'.$status)}{$reason[$tracker]}</td></tr>");
	}
	print "</table>";
	stdmsg($REL_LANG->say_by_key('success'),'<h1><a href="'.$REL_SEO->make_link('details','id', $row['id'] ,'name',translit($row['name'])).'">'.$REL_LANG->say_by_key('back_to_details').'</a>');
	stdfoot();
	write_log("<a href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username']))."\">{$CURUSER['username']}</a> отредактировал трекера торрента с ID <a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."\">$id</a>",'torrent');
	die();
}


function bark($msg) {
	global $REL_LANG;
	stderr($REL_LANG->say_by_key('error'), $msg." <a href=\"javascript:history.go(-1);\">{$REL_LANG->say_by_key('ago')}</a>");
}

foreach(explode(":","type:name") as $v) {
	if (!isset($_POST[$v]))
	bark("Не все поля заполнены");
}

$name = htmlspecialchars((string)($_POST['name']));
if (!preg_match("#(.*?) \/ (.*?) \([0-9-]+\) \[(.*?)\]#si",$name))
bark ("Имя релиза оформлено не по шаблону:<br/>{$REL_LANG->say_by_key('taken_from_torrent')}");

if (!is_array($_POST["type"]))
bark("Ошибка обработки выбранных категорий!");
else
foreach ($_POST['type'] as $cat) if (!is_valid_id($cat)) bark($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));


if ($_POST['multi']) $multi=1; else $multi=0;

$updateset = array();

if ($_POST['nofile']) {} else {
	if (isset($_FILES["tfile"]) && !empty($_FILES["tfile"]["name"]))
	$update_torrent = true;
	$tiger_hash = trim((string)$_POST['tiger_hash']);
	if ((!preg_match("/[^a-zA-Z0-9]/",$tiger_hash) || (strlen($tiger_hash)<>38)) && $tiger_hash) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_tiger_hash'));
	$updateset[] = "tiger_hash = ".sqlesc($tiger_hash); 	
	}
$res = sql_query("SELECT torrents.owner, torrents.filename, torrents.images, torrents.topic_id, torrents.modcomm, torrents.moderated FROM torrents WHERE torrents.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key("invalid_id"));

if (($row["filename"] == 'nofile') && (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR && !$tedit)
bark("You're not the owner! How did that happen?\n");

////////////////////////////////////////////////

$images = explode(',',$row['images']);

//////////////////////////////////////////////
//////////////Take Image Uploads//////////////

$maxfilesize = 512000; // 500kb

for ($x=0; $x < $REL_CONFIG['max_images']; $x++) {

	if (!empty($_POST['img'.$x])) {
		$img=trim(htmlspecialchars((string)$_POST['img'.$x]));
		if (strpos($img,',') || strpos($img,'?')) stderr($REL_LANG->say_by_key('error'),'Динамические изображения запрещены');

		if (!preg_match('/^(.+)\.(gif|png|jpeg|jpg)$/si', $img))
		stderr($REL_LANG->say_by_key('error'),'Загружаемая картинка '.($x+1).' - не картинка');

		/*  $check = remote_fsize($img);
		 if (!$check) stderr($REL_LANG->say_by_key('error'),'Не удалось определить размер картинки '.$y);
		 if ($check>$maxfilesize) stderr($REL_LANG->say_by_key('error'),'Максимальный размер картинки 512kb. Ошибка при загрузке картинки '.$y);
		 */ $inames[]=$img;
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
	$dict = bdec_file($tmpname, $REL_CONFIG['max_torrent_size']);
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
	unset($dict['value']['publisher.utf-8']);
	unset($dict['value']['publisher-url']);
	unset($dict['value']['publisher-url.utf-8']);


	if (!$multi) {
		//  $dict['value']['info']['value']['private']=bdec('i1e');  // add private tracker flag
		unset($dict['value']['announce-list']);
		unset($dict['value']['announce']);

	} else $anarray = get_announce_urls($dict);

	if ($multi && !$anarray) stderr($REL_LANG->say_by_key('error'),'Этот торрент-файл не является мультитрекерным. <a href="javascript:history.go(-1);">Назад</a>');

	$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash

	list($info) = dict_check($dict, "info");

	$infohash = sha1($info["string"]);
	move_uploaded_file($tmpname, ROOT_PATH."torrents/$id.torrent");
	$fp = fopen("torrents/$id.torrent", "w");
	if ($fp) {
		@fwrite($fp, benc($dict['value']['info']), strlen(benc($dict['value']['info'])));
		fclose($fp);
		@chmod($fp, 0644);
	}
	$updateset[] = "info_hash = " . sqlesc($infohash);
	$updateset[] = "filename = " . sqlesc($fname);
	sql_query("DELETE FROM files WHERE torrent = $id");
	sql_query("DELETE FROM trackers WHERE torrent = ".$id);
	// insert localhost tracker
	if ($update_torrent) sql_query("INSERT INTO trackers (torrent,tracker) VALUES ($id,'localhost')");
	// Insert remote trackers //
	if ($anarray) {
		foreach ($anarray as $anurl) sql_query("INSERT INTO trackers (torrent,tracker) VALUES ($id,".sqlesc($anurl).")");
	}
	// trackers insert end
	$nf = count($filelist);

	sql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($dname).",".$totallen.")");
	$updateset[] = "size = ".$totallen;
	$updateset[] = "numfiles = ".$nf;
	$updateset[] = "ismulti = ".$torrent_type;
	if ($_POST['nofile']) $dname = 'nofile';

}
// конец НЕ загрузки

$updateset[] = "name = " . sqlesc($name);

$modcomm = (string)$_POST['modcomm'];
if ($row['modcomm'] != $modcomm) $updateset[] = "modcomm = ".sqlesc('Последнее изменение '.$CURUSER['username'].' в '.mkprettytime(time())."\n".htmlspecialchars($modcomm));

$catsstr = implode(',',$_POST['type']);

$updateset[] = "category = " . sqlesc($catsstr);

if ($_POST['nofile']) {

	$wastor = sql_query("SELECT filename FROM torrents WHERE id =".$id);
	$wastor = mysql_result($wastor,0);

	if ($wastor != 'nofile') {
		sql_query("DELETE FROM files WHERE torrent = ".$id);
		sql_query("DELETE FROM peers WHERE torrent = ".$id);
		sql_query("DELETE FROM snatched WHERE torrent = ".$id);
		sql_query("DELETE FROM trackers WHERE torrent = ".$id);
		$updateset[] = "filename = 'nofile'";

		$ff = "torrents/" . $id.".torrent";
		@unlink($ff);
	}

	$nfz = $_POST['nofilesize'];
	$nofilesize = (int)($nfz*1024*1024);
	$updateset[] = "size = " . $nofilesize;
}

// get relgroup
$relgroup = (int)$_POST['relgroup'];

if ($relgroup) {
	$relgroup = @mysql_result(sql_query("SELECT id FROM relgroups WHERE id=$relgroup"),0);

	if (!$relgroup) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_relgroup'));

}
$updateset[] = "relgroup = $relgroup";

if(get_user_class() >= UC_MODERATOR) {
	$updateset[] = "free = '".($_POST["free"]? 1 : 0)."'";

	$updateset[] = "banned = ".($_POST["banned"]?1:0);
	$updateset[] = "sticky = ".($_POST['sticky']?1:0);

	$updateset[] = "visible = '" . ($_POST["visible"] ? 1 : 0) . "'";
}


if ((get_user_class() >= UC_UPLOADER) && isset($_POST['approve'])) {
	$updateset[] = "moderated = 1";
	$updateset[] = "moderatedby = ".$CURUSER["id"];
	// send notifs
	if (!$row['moderated']) {
		$bfooter = <<<EOD
Чтобы посмотреть релиз, перейдите по этой ссылке:

		{$REL_CONFIG['defaultbaseurl']}/{$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))}

EOD;
		$descr = format_comment($row['descr']).nl2br($bfooter);
		send_notifs('torrents',format_comment($descr),$CURUSER['id']);
	}
} else $updateset[] = "moderatedby = 0";


$descr = ((string)$_POST['descr']);

$updateset[] = 'descr = '.sqlesc($descr);
/// get kinopoisk.ru trailer!

$online = get_trailer($descr);

// end get kinopoisk.ru trailer
if ($online) $updateset[] = 'online = '.sqlesc($online);

if ($_POST['upd']) $updateset[] = "added = '" . time() . "'";

sql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");
if (mysql_errno() == 1062) stderr($REL_LANG->say_by_key('error'),'Torrent already uploaded!'); elseif (mysql_errno()) sqlerr(__FILE__,__LINE__);

$clearcache = array('block-indextorrents','browse-normal','browse-cat');

foreach ($clearcache as $cachevalue)
$REL_CACHE->clearGroupCache($cachevalue);


if ($REL_CONFIG['use_integration']) {
	/// IPB INTEGRATION ///// EDIT WIKI CONTAINER ////////////

	if ($image <> '') $image = "<div align=\"center\"><a href=\"$image\" target=\"_blank\"><img alt=\"Постер для фильма (кликните для просмотра полного изображения)\" src=\"$image\" width=\"240\" border=\"0\" class=\"linked-image\" /></a></div><br />"; else
	$image = "<div align=\"center\"><img src=\"{$REL_CONFIG['defaultbaseurl']}/pic/noimage.gif\" border=\"0\" class=\"linked-image\" /></div><br />";

	if (!empty($_POST['topic'])) {
		if (is_valid_id($_POST['topic'])) {
			$topicid =  (int) $_POST['topic'];
			sql_query("UPDATE torrents SET topic_id =".$topicid." WHERE id =".$id);
			$topicedit = 1;
		} else stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key("invalid_id"));
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
		$forumdesc .= "<tr><td valign=\"top\"><b>".$REL_LANG->say_by_key('description').":</b></td><td>".format_comment($descr)."</td></tr>";


		$isnofilesize = sql_query("SELECT filename,size FROM torrents WHERE id = $id");
		$isnofilesize = mysql_fetch_array($isnofilesize);
		$topicfooter = "<tr><td valign=\"top\"><b>Размер файла:</b></td><td>".round($isnofilesize['size']/1024/1024)." МБ</td></tr>";

		$topicfooter .= "<tr><td valign=\"top\"><b>".(($isnofilesize['filename'] != 'nofile')?"Торрент {$REL_CONFIG['defaultbaseurl']}:":"Релиз {$REL_CONFIG['defaultbaseurl']}:")."</b></td><td><div align=\"center\">[<span style=\"color:#FF0000\"><a href=\"{$REL_CONFIG['defaultbaseurl']}/".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."\">Посмотреть этот релиз на {$REL_CONFIG['defaultbaseurl']}</a></span>]</div></td></tr></table>";

		$forumdesc .= $topicfooter;

		// connecting to IPB DB
		forumconn();
		//connection opened

		$postid = sql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid=".$topicid);
		$postid = mysql_result($postid,0);

		sql_query("UPDATE ".$fprefix."topics SET title = ".sqlesc($name)." WHERE tid=".$topicid);


		if ($REL_CONFIG['exporttype'] == "wiki")
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

write_log("Торрент '$name' был отредактирован пользователем $CURUSER[username]\n","torrent");

$returl = $REL_SEO->make_link('details','id',$id,'name',translit($row['name']));
if (isset($_POST["returnto"]))
$returl .= "&returnto=" . strip_tags($_POST["returnto"]);


safe_redirect($returl,1);

stderr($REL_LANG->say_by_key('success'),"Релиз успешно обновлен, сейчас вы перейдете к его деталям".($anarray?"<img src=\"".$REL_SEO->make_link('remote_check','id',$id)."\" width=\"0px\" height=\"0px\" border=\"0\"/>":''),'success');

?>
