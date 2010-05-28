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

require_once("include/benc.php");
require_once("include/bittorrent.php");

ini_set("upload_max_filesize",$max_torrent_size);

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();

loggedinorreturn();
parked();

if (get_user_class() < UC_USER)
  die;

foreach(explode(":","descr:type:name") as $v) {
	if (!isset($_POST[$v]))
		bark("missing form data");
}
if ($_POST['annonce'] == 'yes') { $_POST['nofile'] = "yes"; $_POST['nofilesize'] = "0"; }

if ($_POST['nofile'] == 'yes') {} else {
if (!isset($_FILES["tfile"]))
	bark("missing form data");

if (($_POST['nofile'] == 'yes') && (empty($_POST['nofilesize']))) bark("Вы не указали размер не торрент релиза!");

$f = $_FILES["tfile"];
$fname = unesc($f["name"]);
if (empty($fname))
	bark("Файл не загружен. Пустое имя файла!");
}

$descr = unesc($_POST["descr"]);
if (!$descr)
	bark("Вы должны ввести описание!");

$catid = (0 + $_POST["type"]);
if (!is_valid_id($catid))
	bark("Вы должны выбрать категорию, в которую поместить релиз!");

if ($_POST['source'] == 'unknown')
	bark("К сожалению, указвать качество иходника обязательно.");
	
if ( (empty($_POST['year'])) || (empty($_POST['director'])) || (empty($_POST['roles'])) || (empty($_POST['publisher'])) || (empty($_POST['time'])) || (empty($_POST['translation'])) || (empty($_POST['resolution'])) || (empty($_POST['videocodec'])) || (empty($_POST['fps'])) || (empty($_POST['videobitrate'])) || (empty($_POST['channels'])) || (empty($_POST['audiocodec'])) || (empty($_POST['frequency'])) || (empty($_POST['audiobitrate'])) || (empty($_POST['format'])) )
   bark("Все дополнительные поля о фильме, кроме рейтинга IMDB, оригинального названия и ссылок обязательны для заполнения");
	
if ($_POST['nofile'] == 'yes') {} else {

if (!validfilename($fname))
	bark("Неверное имя файла!");
if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
	bark("Неверное имя файла (не .torrent).");
$shortfname = $torrent = $matches[1];
}

if (!empty($_POST["name"]))
	$torrent = unesc($_POST["name"]);

if ($_POST['nofile'] == 'yes') {} else {
$tmpname = $f["tmp_name"];
if (!is_uploaded_file($tmpname))
	bark("eek");
if (!filesize($tmpname))
	bark("Пустой файл!");

$dict = bdec_file($tmpname, $max_torrent_size);
if (!isset($dict))
	bark("Что за хрень ты загружаешь? Это не бинарно-кодированый файл!");
  }
  
if ($_POST['free'] == 'yes' AND get_user_class() >= UC_ADMINISTRATOR) {
	$free = "yes";
} else {
	$free = "no";
};

if ($_POST['sticky'] == 'yes' AND get_user_class() >= UC_MODERATOR)
    $sticky = "yes";
else
    $sticky = "no";

function dict_check($d, $s) {
	if ($d["type"] != "dictionary")
		bark("not a dictionary");
	$a = explode(":", $s);
	$dd = $d["value"];
	$ret = array();
	foreach ($a as $k) {
		unset($t);
		if (preg_match('/^(.*)\((.*)\)$/', $k, $m)) {
			$k = $m[1];
			$t = $m[2];
		}
		if (!isset($dd[$k]))
			bark("dictionary is missing key(s)");
		if (isset($t)) {
			if ($dd[$k]["type"] != $t)
				bark("invalid entry in dictionary");
			$ret[] = $dd[$k]["value"];
		}
		else
			$ret[] = $dd[$k];
	}
	return $ret;
}

function dict_get($d, $k, $t) {
	if ($d["type"] != "dictionary")
		bark("not a dictionary");
	$dd = $d["value"];
	if (!isset($dd[$k]))
		return;
	$v = $dd[$k];
	if ($v["type"] != $t)
		bark("invalid dictionary entry type");
	return $v["value"];
}
    $ret = mysql_query("SHOW TABLE STATUS LIKE 'torrents'");
    $row = mysql_fetch_array($ret);
    $next_id = $row['Auto_increment'];
    
if ($_POST['nofile'] == 'yes') {} else {

list($info) = dict_check($dict, "info");
list($dname, $plen, $pieces) = dict_check($info, "name(string):piece length(integer):pieces(string)");

/*if (!in_array($ann, $announce_urls, 1))
	bark("Неверный Announce URL! Должен быть ".$announce_urls[0]);*/

if (strlen($pieces) % 20 != 0)
	bark("invalid pieces");

$filelist = array();
$totallen = dict_get($info, "length", "integer");
if (isset($totallen)) {
	$filelist[] = array($dname, $totallen);
	$type = "single";
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
	$type = "multi";
}

$dict['value']['announce']=bdec(benc_str($announce_urls[0]));  // change announce url to local
//$dict['value']['info']['value']['private']=bdec('i1e');  // add private tracker flag
$dict['value']['info']['value']['source']=bdec(benc_str( "[$DEFAULTBASEURL] $SITENAME")); // add link for bitcomet users
unset($dict['value']['announce-list']); // remove multi-tracker capability
unset($dict['value']['nodes']); // remove cached peers (Bitcomet & Azareus)
unset($dict['value']['info']['value']['crc32']); // remove crc32
unset($dict['value']['info']['value']['ed2k']); // remove ed2k
unset($dict['value']['info']['value']['md5sum']); // remove md5sum
unset($dict['value']['info']['value']['sha1']); // remove sha1
unset($dict['value']['info']['value']['tiger']); // remove tiger
unset($dict['value']['azureus_properties']); // remove azureus properties
$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash
$dict['value']['comment']=bdec(benc_str( "$DEFAULTBASEURL/details.php?id=$next_id")); // change torrent comment  to URL
$dict['value']['created by']=bdec(benc_str( "$CURUSER[username]")); // change created by
$dict['value']['publisher']=bdec(benc_str( "$CURUSER[username]")); // change publisher
$dict['value']['publisher.utf-8']=bdec(benc_str( "$CURUSER[username]")); // change publisher.utf-8
$dict['value']['publisher-url']=bdec(benc_str( "$DEFAULTBASEURL/userdetails.php?id=$CURUSER[id]")); // change publisher-url
$dict['value']['publisher-url.utf-8']=bdec(benc_str( "$DEFAULTBASEURL/userdetails.php?id=$CURUSER[id]")); // change publisher-url.utf-8
list($info) = dict_check($dict, "info");

$infohash = sha1($info["string"]);

 }

//////////////////////////////////////////////
//////////////Take Image Uploads//////////////
//IPB TOPIC TRANSFER
$relimage=0;
//END, CONTINUE BELOW

$maxfilesize = 512000; // 500kb

$allowed_types = array(
"image/pjpeg" => "jpg",
"image/jpeg" => "jpg",
"image/jpg" => "jpg"
// Add more types here if you like
);

for ($x=0; $x < 2; $x++) {

if (!($_FILES[image.$x]['name'] == "")) {
    $y = $x + 1;

    // Is valid filetype?
    if (!array_key_exists($_FILES[image.$x]['type'], $allowed_types))
        bark("Внимание! Разрешенные форматы картинок: JPEG,JPG,PJPEG.Ошибка при загрузке картинки $y");

    if (!preg_match('/^(.+)\.(jpg|jpeg)$/si', $_FILES[image.$x]['name']))
        bark("Неверное имя файла (не картинка или неверный формат).");

    // Is within allowed filesize?
    if ($_FILES[image.$x]['size'] > $maxfilesize)
        bark("Внимание! Картинка $y - Слишком большая. Макс. размер: 500kb");

    // Where to upload?
    // Update for your own server. Make sure the folder has chmod write permissions. Remember this director
    $uploaddir = "torrents/images/";

    // What is the temporary file name?
    $ifile = $_FILES[image.$x]['tmp_name'];

    // Calculate what the next torrent id will be
   // GO UPSTAIRS //

    // By what filename should the tracker associate the image with?
    $ifilename = $next_id . $x . substr($_FILES[image.$x]['name'], strlen($_FILES[image.$x]['name'])-4, 4);

    // Upload the file
    $copy = copy($ifile, $uploaddir.$ifilename);

    if (!$copy)
    bark("Ошибка при загрузке картинки $y");

    //adds watermark///
/// ORIGINAL POSTED http://www.codenet.ru/webmast/php/Image-Resize-GD/ /////////////////

$ifn=$uploaddir.$ifilename;


// качество jpeg по умолчанию
if (!isset($q)) $q = 75;

// создаём исходное изображение на основе
// исходного файла и опеределяем его размеры
$src = imagecreatefromjpeg($ifn);
$w_dest = imagesx($src);
$h_dest = imagesy($src);

       // создаём пустую картинку
       // важно именно truecolor!, иначе будем иметь 8-битный результат
       $dest = imagecreatetruecolor($w_dest,$h_dest);
       imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_dest, $h_dest);
        $str = "Kinokpk.com releaser";
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

        imagejpeg($dest,$ifn,$q);
        imagedestroy($dest);
        imagedestroy($src);

////////////////RESIZING END //////////////////////////////////////


    $inames[] = $ifilename;
    //IPB TOPIC TRENSFER
  $relimage=1;
  // END, CONTINUE BELOW
}

}
$totaldesc = "[b]Название:[/b] " . $torrent ."\n";
if (!empty($_POST['origname'])) $torrent .= " / " . $_POST['origname'];
// IPB TOPIC TRANSFER
if ($relimage == 0) $forumdesc = "<div align=\"center\"><img src=\"$DEFAULTBASEURL/pic/noimage.gif\" border=\"0\" class=\"linked-image\" /></div><br />";
if ($relimage == 1) $forumdesc = "<div align=\"center\"><a href=\"$DEFAULTBASEURL/viewimage.php?image=".$inames[0]."\"><img alt=\"Постер для фильма (кликните для просмотра полного изображения)\" src=\"$DEFAULTBASEURL/thumbnail.php?image=".$inames[0]."&for=forum\" border=\"0\" class=\"linked-image\" /></a></div><br />";

$topicname = sqlforum($torrent);
// END, CONTINUE BELOW

if (!empty($_POST['httplinks'])) $torrent .= " + HTTP";
if (!empty($_POST['ftplinks'])) $torrent .= " + FTP";
if (!empty($_POST['ed2klinks'])) $torrent .= " + ed2k";

// DETAILED DESCRIPTION MOD BY ZonD80

if (!empty($_POST['origname']))
$totaldesc .= "[b]Оригинальное название:[/b] " . $_POST['origname'] . "\n";

$totaldesc .= "[b]Год выхода:[/b] ".$_POST['year']."\n[b]Режиссер:[/b] ".$_POST['director']."\n[b]В ролях:[/b] ".$_POST['roles']."\n";

if (!empty($_POST['imdbrating']))
$totaldesc .= "\n[b]IMDB:[/b] ".$_POST['imdbrating']."\n";

if (!empty($_POST['kinopoiskrating']))
$totaldesc .= "[b]Kinopoisk:[/b] ".$_POST['kinopoiskrating']."\n";

if ($_POST['mpaarating'] != 'unknown') {
$mpaaarray = mpaalist();
foreach ($mpaaarray as &$row){
  if ($row['name'] == $_POST['mpaarating']) $mpaadescr = $row['descr'];
}

$totaldesc .= "[b]MPAA rating:[/b] [img]$DEFAULTBASEURL/pic/mpaa/".$_POST['mpaarating'].".gif[/img] - ".$mpaadescr."\n";
}
if (!empty($_POST['kinofilmscomua'])) $totaldesc .= "\n[b]Рецензия Kinofilms.com.ua: [/b]".$_POST['kinofilmscomua']."\n";
$totaldesc .= "\n[b]Продолжительность:[/b] ".$_POST['time']."\n[b]Издатель:[/b] ".$_POST['publisher']."\n[b]Перевод:[/b] ".$_POST['translation']."\n\n[b]О фильме:[/b]\n".$descr."\n\n[b]Формат файла:[/b] ".$_POST['format']."\n\n";

// IPB TOPIC TRANSFER
if ($_POST['nofile'] == 'yes') {
$forumsize = $_POST['nofilesize']; } else { $forumsize = round($totallen/1024/1024);    }
// END, CONTINUE BELOW

$totaldesc .= "[b][Видео][/b]\nРазрешение: ".$_POST['resolution']."\nКодек: ".$_POST['videocodec']."\nЧастота кадров: ".$_POST['fps']." fps\nБитрейт: ".$_POST['videobitrate']." Kb/s\n\n[b][Аудио][/b]\nКоличество каналов: ".$_POST['channels']."\nКодек: ".$_POST['audiocodec']."\nЧастота: ".$_POST['frequency']." Hz\nБитрейт: ".$_POST['audiobitrate']." Kb/s\n\n[b]Исходник:[/b] ".$_POST['source']."\n\n";

if (!empty($_POST['httplinks'])) {
$totaldesc .="HTTP:\n".$_POST['httplinks']."\n\n";
}
if (!empty($_POST['ftplinks'])) {
$totaldesc .="FTP:\n".$_POST['ftplinks']."\n\n";
}
if (!empty($_POST['ed2klinks'])) {
$totaldesc .="eDonkey:\n".$_POST['ed2klinks']."\n\n";
}

$descr = $totaldesc;
//////////////////////////////////////////////

// Replace punctuation characters with spaces
 if ($_POST['nofile'] == 'yes') {
   $nofilesize = $_POST['nofilesize'];
   $fname = 'nofile';
   $infohash = md5($torrent);
   $torrent = htmlspecialchars(str_replace("_", " ", $torrent));
 if ($_POST['annonce'] == 'yes')
   $torrent .= " | АНОНС"; else $torrent .= " - релиз без торрента";

   $totallen = 0+($nofilesize*1024*1024);
   $filelist = 1;
   $type = 'single';
   $dname = 'nofile';

$ret = mysql_query("INSERT INTO torrents (search_text, filename, owner, visible, sticky, info_hash, name, size, numfiles, type, descr, ori_descr, free, image1, image2, category, save_as, added, last_action) VALUES (" . implode(",", array_map("sqlesc", array(searchfield("$shortfname $dname $torrent"), $fname, $CURUSER["id"], "yes", $sticky, $infohash, $torrent, $totallen, $filelist, $type, $descr, $descr, $free, $inames[0], $inames[1], 0 + $_POST["type"], $dname))) . ", '" . get_date_time() . "', '" . get_date_time() . "')");
} else {

$torrent = htmlspecialchars(str_replace("_", " ", $torrent));

$ret = mysql_query("INSERT INTO torrents (search_text, filename, owner, visible, sticky, info_hash, name, size, numfiles, type, descr, ori_descr, free, image1, image2, category, save_as, added, last_action) VALUES (" . implode(",", array_map("sqlesc", array(searchfield("$shortfname $dname $torrent"), $fname, $CURUSER["id"], "yes", $sticky, $infohash, $torrent, $totallen, count($filelist), $type, $descr, $descr, $free, $inames[0], $inames[1], 0 + $_POST["type"], $dname))) . ", '" . get_date_time() . "', '" . get_date_time() . "')");
}
if (!$ret) {
      if (mysql_errno() == 1062)
		bark("$id torrent already uploaded!"); 
	bark("mysql puked: ".mysql_error());
}
$id = mysql_insert_id();

    mysql_query("UPDATE users SET bonus=bonus+25 WHERE id =".$CURUSER['id']);


// IPB TOPIC TRANSFER

$forumdesc .= format_comment($descr);

$topicfooter = "<b>Размер файла:</b> ".$forumsize." МБ<br /><br />";

if ($_POST['nofile'] != 'yes') $topicfooter .= "Torrent:<br />Нажмите на ссылку ниже";

$topicfooter .= "<br /><br /><br /><div align=\"center\">[<span style=\"color:#FF0000\"><a href=\"$DEFAULTBASEURL/details.php?id=".$id."&hit=1\">Этот фильм на $DEFAULTBASEURL</a></span>]</div>";

$forumdesc .=$topicfooter;

$ipbuser = mysql_query("SELECT username FROM users WHERE id=".$CURUSER['id']);
$ipbuser = mysql_result($ipbuser,0);

$cid = 0 + $_POST['type'];
$forumcat = mysql_query("SELECT name FROM categories WHERE id=".$cid);
$forumcat = mysql_result($forumcat,0);

// define categories for forum.$FORUMNAME
// if ($forumcat == 'Comedy') $forumcat = 'Comedy and humor';


   
mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);

$forumdesc = sqlforum($forumdesc);

//connection opened
  if (!empty($_POST['topic'])) {
    $topicid =  $_POST['topic'];
    if (is_numeric($topicid)) $topicid = 0 + $topicid; else die("Неверный ID темы");
    
    $topic = mysql_query("UPDATE ".$fprefix."topics SET title = '".$topicname."', description = '".$_POST['source']."' WHERE tid =".$topicid);
    $postid = mysql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid =".$topicid);
    $postid = mysql_result($postid,0);
    // WIKI VERSION: $post = mysql_query("UPDATE ".$fprefix."posts SET post = '---', wiki = '".$forumdesc."' WHERE pid = ".$postid);
    $post = mysql_query("UPDATE ".$fprefix."posts SET post = '".$forumdesc."' WHERE pid = ".$postid);
       } else {

$check = mysql_query("SELECT id FROM ".$fprefix."members WHERE name='".$ipbuser."'");

if(!@mysql_result($check,0)) $ipbid = 66958; else $ipbid=mysql_result($check,0);

   $forumid = mysql_query ("SELECT id FROM ".$fprefix."forums WHERE name='".$forumcat."'");
   $forumid = mysql_result ($forumid,0);

  $topic = mysql_query("INSERT INTO ".$fprefix."topics (title, description, state, posts, starter_id, start_date, last_poster_id, last_post, icon_id, starter_name, last_poster_name, poll_state, last_vote, views, forum_id, approved, author_mode, pinned, moved_to, total_votes, topic_hasattach, topic_firstpost,	topic_queuedposts, topic_open_time,	topic_close_time,	topic_rating_total,	topic_rating_hits) VALUES ('".$topicname."', '".$_POST['source']."', 'open', 0, ".$ipbid.", ".time().", ".$ipbid.", ".time().", 0, '".$ipbuser."', '".$ipbuser."', 0, 0, 0, ".$forumid.", 1, 1, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0)");
  $topicid = mysql_insert_id();
  /* WIKI VERSION:
  $post = mysql_query("INSERT INTO ".$fprefix."posts (append_edit, edit_time, author_id, author_name, use_sig, use_emo, ip_address, post_date, icon_id, wiki, post, queued, topic_id, post_title, new_topic, edit_name, post_key, post_parent, post_htmlstate) VALUES
  (0, NULL, ".$ipbid.", '".$ipbuser."', 1, 1, '".getip()."', ".time().", 0, '".$forumdesc."', '---', 0, ".$topicid.", NULL, 1, NULL, '".md5(microtime())."', 0, 0)");
 */
 $post = mysql_query("INSERT INTO ".$fprefix."posts (append_edit, edit_time, author_id, author_name, use_sig, use_emo, ip_address, post_date, icon_id, post, queued, topic_id, post_title, new_topic, edit_name, post_key, post_parent, post_htmlstate) VALUES
  (0, NULL, ".$ipbid.", '".$ipbuser."', 1, 1, '".getip()."', ".time().", 0, '".$forumdesc."', 0, ".$topicid.", NULL, 1, NULL, '".md5(microtime())."', 0, 0)");

 $postid = mysql_insert_id();

 $updtopic = mysql_query ("UPDATE ".$fprefix."topics SET topic_firstpost =".$postid." WHERE tid =".$topicid);
 $updateforum = mysql_query("UPDATE ".$fprefix."forums SET topics =topics+1, posts =posts+1, last_post =".time().", last_poster_id =".$ipbid.", last_poster_name ='".$ipbuser."', last_title='".$topicname."', last_id =".$topicid." WHERE id =".$forumid);
 $updateuser = mysql_query("UPDATE ".$fprefix."members SET posts =posts+1, last_post =".time().", last_activity =".time()." WHERE id=".$ipbid);
}
$forumlink = "\n[center][url=$FORUMURL/index.php?showtopic=".$topicid."][ [color=red][b]Обсуждение на форуме ".$FORUMNAME."[/b] [/color] ][/url][/center]\n";

 // closing IPB DB connection
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
 
 $adddescr = mysql_query("SELECT descr FROM torrents WHERE id=".$id);
 $adddescr = mysql_result($adddescr,0);
 $adddescr .= $forumlink;
 mysql_query("UPDATE torrents SET descr = '".$adddescr."',ori_descr = '".$adddescr."',topic_id = ".$topicid." WHERE id=".$id);
 
// IPB TOPIC TRANSFER END

mysql_query("INSERT INTO checkcomm (checkid, userid, torrent) VALUES ($id, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);
@mysql_query("DELETE FROM files WHERE torrent = $id");

if ($_POST['nofile'] == 'yes') {
   	} else   {
foreach ($filelist as $file) {
	@mysql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($file[0]).",".$file[1].")");
}
}
if ($_POST['nofile'] == 'yes') {} else {
move_uploaded_file($tmpname, "$torrent_dir/$id.torrent");

$fp = fopen("$torrent_dir/$id.torrent", "w");
if ($fp)
{
    @fwrite($fp, benc($dict), strlen(benc($dict)));
    fclose($fp);
    @chmod($fp, 0644);
}
}
write_log("Торрент номер $id ($torrent) был залит пользователем " . $CURUSER["username"],"5DDB6E","torrent");

/* Email notifs */


$res = mysql_query("SELECT name FROM categories WHERE id=$catid") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_assoc($res);
$cat = $arr["name"];
$res = mysql_query("SELECT email FROM users WHERE enabled='yes' AND notifs LIKE '%[cat$catid]%'") or sqlerr(__FILE__, __LINE__);
$uploader = $CURUSER['username'];

$size = mksize($totallen);
$description = format_comment($descr);

$body = <<<EOD
Новый фильм на $SITENAME!

Название: $torrent
Размер файла: $size
Категория: $cat
Залил: $uploader

Информация о фильме:
-------------------------------------------------------------------------------
$description
-------------------------------------------------------------------------------

Чтобы посмотреть фильм, перейдите по этой ссылке:

$DEFAULTBASEURL/details.php?id=$id&hit=1

-- 
$SITENAME
EOD;
$to = "";
$nmax = 100; // Max recipients per message
$nthis = 0;
$ntotal = 0;
$total = mysql_num_rows($res);
while ($arr = mysql_fetch_row($res))
{
  if ($nthis == 0)
    $to = $arr[0];
  else
    $to .= "," . $arr[0];
  ++$nthis;
  ++$ntotal;
  if ($nthis == $nmax || $ntotal == $total)
  {
    if (!mail("Рассылка <$SITEEMAIL>", "Новый фильм - $torrent", $body,
    "От: $SITEEMAIL\r\nBcc: $to", "-f$SITEEMAIL"))
	  stderr($tracker_lang['error'], "Your torrent has been been uploaded. DO NOT RELOAD THE PAGE!\n" .
	    "There was however a problem delivering the e-mail notifcations.\n" .
	    "Please let an administrator know about this error!\n");
    $nthis = 0;
  }
}


// header("Location: $DEFAULTBASEURL/details.php?id=$id");

stdhead("Файл загружен");

$downlink = "<a title=\"Скачать\" href=\"download.php?id=$id&amp;name=$fname\"><span style=\"color: red; cursor: help;\" title=\"Скачать торрент-файл.\">СКАЧАТЬ ФАЙЛ</span></a>"; 

print ("<div style='width: 100%; border: 1px dashed #008000; padding: 10px; background-color: #D6F3CC'>
<b><font size=2px>Спасибо, Ваша раздача почти готова. Торрент-файл размещен на сервере.<hr>
Теперь нужно $downlink и начать раздачу в клиенте, с его помощью.</font></b></div>");
print ("<br>");


$detalistorr = "torrent_info.php?id=$id";
$url = "edit.php?id=$id";
$gettorrent = "details.php?id=$id";

$editlink = "<center><table class=my_table width=\"100%\" border='0' cellspacing='0' cellpadding='0'>
             <tr>
             <td class=bottom><center><form method=post action=\"$url\"><input type=submit value=\"Редактировать торрент\" style='height: 20px; width: 160px;'></center></form></td>
             <td class=bottom><center><form method=post action=\"$gettorrent\"><input type=submit value=\"Перейти к деталям\" style='height: 20px; width: 160px;'></center></form></td>
             <td class=bottom><center><form method=post action=\"$detalistorr\"><input type=submit value=\"Данные торрента\" style='height: 20px; width: 160px;'></center></form></td>
             </tr>
             </table></center>";

print ("<div style='width: 100%; border: 1px dashed #008000; padding: 10px; background-color: #D6F3CC'>
<b><font size=2px>Дополнительные действия:</font></b><hr>
$editlink</div>");

stdfoot();  

?>
