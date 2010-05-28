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
require_once("include/benc.php");

function bark($msg) {
	stderr("Ошибка", $msg);
}

if (!is_valid_id($_POST['id'])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
$id = (int) $_POST['id'];

if (!mkglobal("name:type"))
bark("missing form data");



dbconn();

loggedinorreturn();


$res = sql_query("SELECT torrents.owner, torrents.filename, torrents.save_as, torrents.images, torrents.topic_id, categories.name AS catname FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);

if (($row["filename"] == 'nofile') && (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR && !$tedit)
bark("You're not the owner! How did that happen?\n");

$updateset = array();

////////////////////////////////////////////////

$images = explode(',',$row['images']);
$deleteimgs = array();

//var_dump($images);
function delimg($x, $force = false) {
	global $images, $id, $deletedimgs, $CACHEARRAY;



	if (($_POST['delimg'.$x] == 'yes') || ($force == true)) {

		// Deleting images
		$imgarray = glob(ROOT_PATH."torrents/images/".$id.$x.".*");
		if ($imgarray) { $imgpath = array_shift($imgarray); $img = substr($imgpath,(strrpos($imgpath,"/")+1)); }
		@unlink($imgpath);
		$deletedimgs[] = $img;
		// Deleting thumbnails
		$delimgarray = glob(ROOT_PATH."cache/thumbnail/{$id}[0-".($CACHEARRAY['max_images']-1)."][a-z]*.*");
		if ($delimgarray)
		foreach ($delimgarray as $img) {
			@unlink($img);
		}

	}
}

$uploadedimgs = array();

for ($x = 0; $x < $CACHEARRAY['max_images']; $x++) {

	delimg($x);
	//////////////////////////////////////////////
	//////////////Take Image Uploads//////////////

	$maxfilesize = 512000; // 500kb

	$allowed_types = array(
"image/gif" => "gif",
"image/jpeg" => "jpg",
"image/jpg" => "jpg",
"image/png" => "png"
	// Add more types here if you like
	);
	// Where to upload?
	// Update for your own server. Make sure the folder has chmod write permissions. Remember this director
	$uploaddir = ROOT_PATH."torrents/images/";

	$y = $x + 1;
	if (($_FILES[image.$x]['name'] != "") || !empty($_POST['img'.$x])) {


		if (!($_FILES[image.$x]['name'] == "") && empty($_POST['img'.$x])) {

			$_FILES[image.$x]['type'] = strtolower($_FILES[image.$x]['type']);
			$_FILES[image.$x]['name'] = strtolower($_FILES[image.$x]['name']);

			// Is valid filetype?
			if (!array_key_exists($_FILES[image.$x]['type'], $allowed_types))
			bark("Внимание! Разрешенные форматы картинок: JPG,PNG,GIF. Ошибка при загрузке картинки $y");

			if (!preg_match('/^(.+)\.(jpg|png|gif)$/si', $_FILES[image.$x]['name']))
			bark("Неверное имя файла (не картинка или неверный формат).");

			// Is within allowed filesize?
			if ($_FILES[image.$x]['size'] > $maxfilesize)
			bark("Внимание! Картинка $y - Слишком большая. Макс. размер: 500kb");

			// What is the temporary file name?
			$ifile = $_FILES[image.$x]['tmp_name'];

			$size = @GetImageSize($ifile);
			//    var_dump($size);
			if (!$size)
			bark("Это не картинка, доступ запрещен");
			// Calculate what the next torrent id will be
			// GO UPSTAIRS //

			// By what filename should the tracker associate the image with?
			$ifilename = $id . $x . substr($_FILES[image.$x]['name'], strlen($_FILES[image.$x]['name'])-4, 4);

			//File extention
			$ext = substr($_FILES[image.$x]['name'], strlen($_FILES[image.$x]['name'])-3, 3);

			delimg($x,true);
			// Upload the file
			$copy = copy($ifile, $uploaddir.$ifilename);

			if (!$copy)
			bark("Ошибка при загрузке картинки $y");

			//adds watermark///
			/// ORIGINAL POSTED http://www.codenet.ru/webmast/php/Image-Resize-GD/ /////////////////

			$ifn=$uploaddir.$ifilename;
			$pictdest = $ifn;

		} elseif (($_FILES[image.$x]['name'] == "") && !empty($_POST['img'.$x])) {

			if (filesize($_POST['img'.$x] > $maxfilesize)) bark("Внимание! Картинка $y - Слишком большая. Макс. размер: 500kb");
			$ifn = $_POST['img'.$x];
			$size = @GetImageSize($ifn);
			$ifilename = $id . $x . substr($_POST['img'.$x], strlen($_POST['img'.$x])-4, 4);
			$ext = strtolower(substr($_POST['img'.$x], strlen($_POST['img'.$x])-3, 3));
			$pictdest = $uploaddir.$ifilename;

			delimg($x,true);
		}


		// качество jpeg по умолчанию
		if (!isset($q)) $q = 75;

		// создаём исходное изображение на основе
		// исходного файла и опеределяем его размеры
		if ($ext == "jpg")
		$src = @imagecreatefromjpeg($ifn);
		elseif ($ext == "gif")
		$src = @imagecreatefromgif($ifn);
		elseif ($ext == "png")
		$src = @imagecreatefrompng($ifn);

		//    var_dump($size);
		if (!$src || !$size) bark("Ошибка обработки картинки $y, она недоступна, либо имеет недопустимый формат");
		$w_dest = $size[0];
		$h_dest = $size[1];

		// создаём пустую картинку
		// важно именно truecolor!, иначе будем иметь 8-битный результат
		$dest = imagecreatetruecolor($w_dest,$h_dest);
		imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_dest, $h_dest);
		$str = $CACHEARRAY['watermark'];
		// определяем координаты вывода текста
		$size = 2; // размер шрифта
		$x_text = $w_dest-imagefontwidth($size)*strlen($str)-3;
		$y_text = $h_dest-imagefontheight($size)-3;

		// определяем каким цветом на каком фоне выводить текст
		$white = imagecolorallocate($dest, 255, 255, 255);
		$black = imagecolorallocate($dest, 0, 0, 0);
		$gray = imagecolorallocate($dest, 127, 127, 127);
		if (imagecolorat($dest,$x_text,$y_text)>$gray) $color = $black;
		if (imagecolorat($dest,$x_text,$y_text)<$gray) $color = $white;

		// выводим текст
		imagestring($dest, $size, $x_text-1, $y_text-1, $str,$white-$color);
		imagestring($dest, $size, $x_text+1, $y_text+1, $str,$white-$color);
		imagestring($dest, $size, $x_text+1, $y_text-1, $str,$white-$color);
		imagestring($dest, $size, $x_text-1, $y_text+1, $str,$white-$color);

		imagestring($dest, $size, $x_text-1, $y_text,   $str,$white-$color);
		imagestring($dest, $size, $x_text+1, $y_text,   $str,$white-$color);
		imagestring($dest, $size, $x_text,   $y_text-1, $str,$white-$color);
		imagestring($dest, $size, $x_text,   $y_text+1, $str,$white-$color);

		imagestring($dest, $size, $x_text,   $y_text,   $str,$color);

		if ($ext == "jpg")
		imagejpeg($dest,$pictdest,$q);
		elseif ($ext == "gif")
		imagegif($dest,$pictdest,$q);
		elseif ($ext == "png")
		imagepng($dest,$pictdest,9,PNG_ALL_FILTERS);

		imagedestroy($dest);
		imagedestroy($src);

		////////////////RESIZING END //////////////////////////////////////


		$uploadedimgs[] = $ifilename;
	}
}

$tmpimgs = array();

if ($deletedimgs) {$tmpimgs = array_diff($images,$deletedimgs); $images=$tmpimgs; }

if ($uploadedimgs) $images = array_merge($tmpimgs,$uploadedimgs);

//die(var_dump($deletedimgs));

sort($images);

if ($CACHEARRAY['use_integration']) {
	$image = $images;
	$image = array_shift($image);
}

$images = implode(',',$images);

$updateset[]= 'images = '.sqlesc($images);

////////////////////////////////////////////////

if (($_POST['nofile'] == 'yes') && (empty($_POST['nofilesize']))) bark("Вы не указали размер не торрент релиза!");

if ($_POST['nofile'] == 'yes') {$fname = 'nofile'; } else {
	$fname = $row["filename"];
	preg_match('/^(.+)\.torrent$/si', $fname, $matches);
	$shortfname = $matches[1];
	$dname = $row["save_as"];
}

if ($_POST['nofile'] == 'yes') {} else {
	if (isset($_FILES["tfile"]) && !empty($_FILES["tfile"]["name"]))
	$update_torrent = true;

	// check tags
	if (!$_POST['tags']) stderr($tracker_lang['error'],"Вы не выбрали ни одного тега");

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
			$torrent_type = "single";
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
			$torrent_type = "multi";
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

		list($info) = dict_check($dict, "info");

		$infohash = sha1($info["string"]);
		move_uploaded_file($tmpname, "torrents/$id.torrent");

		$fp = fopen("torrents/$id.torrent", "w");
		if ($fp) {
			@fwrite($fp, benc($dict), strlen(benc($dict)));
			fclose($fp);
			@chmod($fp, 0644);
		}

		$updateset[] = "info_hash = " . sqlesc($infohash);
		$updateset[] = "filename = " . sqlesc($fname);
		$updatesetp[] = "save_as = " . sqlesc($dname);
		@sql_query("DELETE FROM files WHERE torrent = $id");
		$nf = count($filelist);

		sql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($dname).",".$totallen.")");
		sql_query("UPDATE torrents SET size = ".$totallen." WHERE id = ".$id);
		sql_query("UPDATE torrents SET numfiles = ".$nf." WHERE id = ".$id);
		sql_query("UPDATE torrents SET type = '".$torrent_type."' WHERE id = ".$id);
		if ($_POST['nofile'] == 'yes') $dname = 'nofile';
		sql_query("UPDATE torrents SET save_as = '".$dname."' WHERE id =".$id);

	}
	// конец НЕ загрузки
}
$name = htmlspecialchars($name);

ksort($_POST['tags']);
reset($_POST['tags']);

$tags = $_POST["tags"];
$oldtags = $_POST['oldtags'];
$un = @array_diff($tags, explode(",", $oldtags));
$un2 = @array_diff(explode(",", $oldtags),$tags);

if ($un)
foreach ($un as $tag) {
	@sql_query("UPDATE tags SET howmuch=howmuch+1 WHERE name LIKE ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
}
if ($un2)
foreach ($un2 as $tag) {
	@sql_query("UPDATE tags SET howmuch=howmuch-1 WHERE name LIKE ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
}

$updateset[] = "name = " . sqlesc($name);
if ($tags)
$updateset[] = "tags = " . sqlesc(implode(",",$tags));
$updateset[] = "search_text = " . sqlesc(htmlspecialchars("$shortfname $dname $torrent"));
if (!is_valid_id($_POST['type'])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
$updateset[] = "category = " . ((int) $_POST['type']);

if ($_POST['nofile'] == 'yes') {

	$wastor = sql_query("SELECT filename FROM torrents WHERE id =".$id);
	$wastor = mysql_result($wastor,0);

	if ($wastor != 'nofile') {
		sql_query("DELETE FROM files WHERE torrent = ".$id);
		sql_query("DELETE FROM peers WHERE torrent = ".$id);
		sql_query("DELETE FROM snatched WHERE torrent = ".$id);
		sql_query("UPDATE torrents SET leechers = 0, seeders = 0");
		$updateset[] = "filename = 'nofile'";
		$updateset[] = "save_as = 'nofile'";
		$ff = "torrents/" . strval($id).".torrent";
		@unlink($ff);
	}

	$nfz = $_POST['nofilesize'];
	$nofilesize = 0+($nfz*1024*1024);
	$updateset[] = "size = " . $nofilesize;
}

if (get_user_class() >= UC_ADMINISTRATOR) {
	if ($_POST["banned"]) {
		$updateset[] = "banned = 'yes'";
		$_POST["visible"] = 0;
	} else
	$updateset[] = "banned = 'no'";
	if ($_POST["sticky"] == "yes")
	$updateset[] = "sticky = 'yes'";
	else
	$updateset[] = "sticky = 'no'";
}
if(get_user_class() >= UC_MODERATOR) {
	$updateset[] = "free = '".($_POST["free"]==1 ? 'yes' : 'no')."'";

	$updateset[] = "visible = '" . ($_POST["visible"] ? "yes" : "no") . "'";
}
$updateset[] = "moderated = 'yes'";
$updateset[] = "moderatedby = ".sqlesc($CURUSER["id"]);

if ($_POST['upd'] == 'yes') $updateset[] = "added = '" . get_date_time() . "'";

sql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");


if (!defined("CACHE_REQUIRED")){
	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
}
$cache=new Cache();
$cache->addDriver('file', new FileCacheDriver());

$clearcache = array('block-indextorrents','browse-normal','browse-tags','browse-cat');

foreach ($clearcache as $cachevalue)
$cache->clearGroupCache($cachevalue);

foreach($_POST['val'] as $descrid => $content) {
	sql_query("UPDATE descr_torrents SET value = ".sqlesc($content)." WHERE id =".$descrid);
}

if ($CACHEARRAY['use_integration']) {
	/// IPB INTEGRATION ///// EDIT WIKI CONTAINER ////////////

	if ($image <> '') $image = "<div align=\"center\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/viewimage.php?image=".$image."\"><img alt=\"Постер для фильма (кликните для просмотра полного изображения)\" src=\"{$CACHEARRAY['defaultbaseurl']}/thumbnail.php?image=".$image."&for=forum\" border=\"0\" class=\"linked-image\" /></a></div><br />"; else
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

		$cid = (int) $_POST['type'];

		$forumdesc = $image;
		$forumdesc .= "<table width=\"100%\" border=\"1\"><tr><td valign=\"top\"><b>Тип (жанр):</b></td><td>".implode(", ",$_POST['tags'])."</td></tr><tr><td><b>Название:</b></td><td>$name</td></tr>";
		$detid = sql_query("SELECT descr_torrents.value, descr_details.name, descr_details.input FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." AND descr_torrents.value <> '' ORDER BY descr_details.sort ASC");
		while ($did = mysql_fetch_array($detid))  {
			if ($did['input'] == 'bbcode')
			$forumdesc .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
			else
			$forumdesc .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
		}

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
			if (!empty($_POST['source'])) $dsql = ", description = ".sqlesc($_POST['source']); else $dsql = '';
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
