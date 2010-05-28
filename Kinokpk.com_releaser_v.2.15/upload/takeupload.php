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
require_once(ROOT_PATH."include/benc.php");

ini_set("upload_max_filesize",$CACHEARRAY['max_torrent_size']);

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();

loggedinorreturn();
parked();

if (get_user_class() < UC_USER) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

foreach(explode(":","type:name:tags") as $v) {
	if (!isset($_POST[$v]))
		bark("не все поля заполнены");
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


if (!is_valid_id($_POST["type"]))
	bark("Вы должны выбрать категорию, в которую поместить релиз!");

$catid = (int) $_POST["type"];
	
if ($_POST['nofile'] == 'yes') {} else {

if (!validfilename($fname))
	bark("Неверное имя файла!");
if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
	bark("Неверное имя файла (не .torrent).");
$shortfname = $torrent = $matches[1];
}

if (!empty($_POST["name"]))
	$torrent = unesc($_POST["name"]); else bark("Вы не ввели название релиза");

if ($_POST['nofile'] == 'yes') {} else {
$tmpname = $f["tmp_name"];
if (!is_uploaded_file($tmpname))
	bark("eek");
if (!filesize($tmpname))
	bark("Пустой файл!");

$dict = bdec_file($tmpname, $CACHEARRAY['max_torrent_size']);
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

if (!is_array($_POST['val'])) stderr($tracker_lang['error'],'Ошибка обработки шаблона');

foreach($_POST['val'] as $typeid => $content) {
  $erow = sql_query("SELECT name,isnumeric,required,input,mask FROM descr_details WHERE id =".$typeid);
  $eres = mysql_fetch_array($erow);
  if (($eres['isnumeric'] == 'yes') && !is_numeric($content)) stderr("Не заполнен шаблон: Неверно введенные данные \"".$eres['name']."\" <a href=\"javascript: history.go(-1)\">Назад</a>");
  if (($eres['required'] == 'yes') && (($content == '') || ($content == 'unknown'))) stderr("Не заполнен шаблон: Пуcтое поле \"".$eres['name']."\" | <a href=\"javascript: history.go(-1)\">Назад</a>");
  }

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
    $ret = sql_query("SHOW TABLE STATUS LIKE 'torrents'");
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

$dict['value']['announce']=bdec(benc_str(''));  // change announce url to local
//$dict['value']['info']['value']['private']=bdec('i1e');  // add private tracker flag
$dict['value']['info']['value']['source']=bdec(benc_str( "[{$CACHEARRAY['defaultbaseurl']}] {$CACHEARRAY['sitename']}")); // add link for bitcomet users
unset($dict['value']['announce-list']); // remove multi-tracker capability
unset($dict['value']['nodes']); // remove cached peers (Bitcomet & Azareus)
unset($dict['value']['info']['value']['crc32']); // remove crc32
unset($dict['value']['info']['value']['ed2k']); // remove ed2k
unset($dict['value']['info']['value']['md5sum']); // remove md5sum
unset($dict['value']['info']['value']['sha1']); // remove sha1
unset($dict['value']['info']['value']['tiger']); // remove tiger
unset($dict['value']['azureus_properties']); // remove azureus properties
$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash
$dict['value']['comment']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/details.php?id=$next_id")); // change torrent comment  to URL
$dict['value']['created by']=bdec(benc_str( "$CURUSER[username]")); // change created by
$dict['value']['publisher']=bdec(benc_str( "$CURUSER[username]")); // change publisher
$dict['value']['publisher.utf-8']=bdec(benc_str( "$CURUSER[username]")); // change publisher.utf-8
$dict['value']['publisher-url']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id=$CURUSER[id]")); // change publisher-url
$dict['value']['publisher-url.utf-8']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id=$CURUSER[id]")); // change publisher-url.utf-8
list($info) = dict_check($dict, "info");

$infohash = sha1($info["string"]);

 }

//////////////////////////////////////////////
//////////////Take Image Uploads//////////////
			if ($CACHEARRAY['use_integration']) {
//IPB TOPIC TRANSFER
$relimage=0;
//END, CONTINUE BELOW
}

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
    
for ($x=0; $x < 2; $x++) {
    $y = $x + 1;
if (($_FILES[image.$x]['name'] != "") || !empty($_POST['img'.$x])) {

if (!($_FILES[image.$x]['name'] == "") && empty($_POST['img'.$x])) {


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
    $ifilename = $next_id . $x . substr($_FILES[image.$x]['name'], strlen($_FILES[image.$x]['name'])-4, 4);

    //File extention
    $ext = substr($_FILES[image.$x]['name'], strlen($_FILES[image.$x]['name'])-3, 3);
          
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
      $ifilename = $next_id . $x . substr($_POST['img'.$x], strlen($_POST['img'.$x])-4, 4);
      $ext = substr($_POST['img'.$x], strlen($_POST['img'.$x])-3, 3);
  $pictdest = $uploaddir.$ifilename;
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

if ($ext == "jpg")
        imagejpeg($dest,$pictdest,$q);
elseif ($ext == "gif")
        imagegif($dest,$pictdest,$q);
elseif ($ext == "png")
        imagepng($dest,$pictdest,9,PNG_ALL_FILTERS);
        
        imagedestroy($dest);
        imagedestroy($src);

////////////////RESIZING END //////////////////////////////////////


    $inames[] = $ifilename;
    			if ($CACHEARRAY['use_integration']) {
    //IPB TOPIC TRENSFER
  $relimage=1;
  // END, CONTINUE BELOW
  }
}
}
ksort($_POST['tags']);
reset($_POST['tags']);

$tags = implode(",",$_POST['tags']);

			if ($CACHEARRAY['use_integration']) {
// IPB TOPIC TRANSFER

if ($relimage == 0) $forumdesc = "<div align=\"center\"><img src=\"{$CACHEARRAY['defaultbaseurl']}/pic/noimage.gif\" border=\"0\" class=\"linked-image\" /></div><br />";
if ($relimage == 1) $forumdesc = "<div align=\"center\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/viewimage.php?image=".$inames[0]."\"><img alt=\"Постер для фильма (кликните для просмотра полного изображения)\" src=\"{$CACHEARRAY['defaultbaseurl']}/thumbnail.php?image=".$inames[0]."&for=forum\" border=\"0\" class=\"linked-image\" /></a></div><br />";

$forumdesc .= "<table width=\"100%\" border=\"1\"><tr><td valign=\"top\"><b>Тип (жанр):</b></td><td>".$tags."</td></tr><tr><td><b>Название:</b></td><td>" . sqlforum($torrent) ."</td></tr>";
$topicname = $torrent;

if ($_POST['nofile'] == 'yes') {
$forumsize = $_POST['nofilesize']; } else { $forumsize = round($totallen/1024/1024);    }
// END, CONTINUE BELOW
 }
 
 // DETAILED DESCRIPTION MOD BY ZonD80 v.2.1
$descr = $_POST['reltype'];

// tags

foreach ($_POST['tags'] as $tag) {
		sql_query("UPDATE tags SET howmuch=howmuch+1 WHERE name = ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
	}
// tags end

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


$ret = sql_query("INSERT INTO torrents (search_text, filename, owner, visible, sticky, info_hash, name, size, numfiles, type, tags, descr_type, free, image1, image2, category, save_as, added, last_action) VALUES (" . implode(",", array_map("sqlesc", array(searchfield("$shortfname $dname $torrent"), $fname, $CURUSER["id"], "yes", $sticky, $infohash, $torrent, $totallen, $filelist, $type, $tags, $descr, $free, $inames[0], $inames[1], 0 + $_POST["type"], $dname))) . ", '" . get_date_time() . "', '" . get_date_time() . "')");
} else {

$torrent = htmlspecialchars(str_replace("_", " ", $torrent));

$ret = sql_query("INSERT INTO torrents (search_text, filename, owner, visible, sticky, info_hash, name, size, numfiles, type, tags, descr_type, free, image1, image2, category, save_as, added, last_action) VALUES (" . implode(",", array_map("sqlesc", array(searchfield("$shortfname $dname $torrent"), $fname, $CURUSER["id"], "yes", $sticky, $infohash, $torrent, $totallen, count($filelist), $type, $tags, $descr, $free, $inames[0], $inames[1], 0 + $_POST["type"], $dname))) . ", '" . get_date_time() . "', '" . get_date_time() . "')");
}
if (!$ret) {
      if (mysql_errno() == 1062)
		bark("$id torrent already uploaded!"); 
	bark("mysql puked: ".mysql_error());
}
$id = mysql_insert_id();

foreach($_POST['val'] as $typeid => $content) {
     sql_query("INSERT INTO descr_torrents (torrent,typeid,value) VALUES ($id,$typeid,".sqlesc($content).")");


}
    sql_query("UPDATE users SET bonus=bonus+25 WHERE id =".$CURUSER['id']);

			if ($CACHEARRAY['use_integration']) {
// IPB TOPIC TRANSFER
//tags
$forumcat = array_shift($_POST['tags']);
// tags end
$detid = sql_query("SELECT descr_torrents.value, descr_details.name, descr_details.input FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." ORDER BY descr_details.sort ASC");
while ($did = mysql_fetch_array($detid))  {
  if (!empty($did['value'])){
  if ($did['input'] == 'bbcode')
  $forumdesc .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
  else
  $forumdesc .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
  }
}

$forumdesc .= "<tr><td valign=\"top\"><b>Размер файла:</b></td><td>".$forumsize." МБ</td></tr>";

$topicfooter .= "<tr><td valign=\"top\"><b>".(($_POST['nofile'] != 'yes')?"Торрент {$CACHEARRAY['defaultbaseurl']}:":"Релиз {$CACHEARRAY['defaultbaseurl']}:")."</b></td><td><div align=\"center\">[<span style=\"color:#FF0000\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/details.php?id=".$id."&hit=1\">Посмотреть этот фильм на {$CACHEARRAY['defaultbaseurl']}</a></span>]</div></td></tr></table>";

$forumdesc .=$topicfooter;

$ipbuser = $CURUSER['username'];

// connecting to IPB DB
forumconn();
//connection opened
  if (!empty($_POST['topic'])) {
    if (!is_valid_id($_POST['topic'])) die("Неверный ID темы");
    $topicid =  0+$_POST['topic'];
    
    $topic = sql_query("UPDATE ".$fprefix."topics SET title = ".sqlesc($topicname).", description = ".sqlesc($_POST['source'])." WHERE tid =".$topicid) or die(mysql_error());
    $postid = sql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid =".$topicid)  or die(mysql_error());
    $postid = mysql_result($postid,0);
    if ($CACHEARRAY['exporttype'] == "wiki")
    $post = sql_query("UPDATE ".$fprefix."posts SET post = '---', wiki = ".sqlesc($forumdesc)." WHERE pid = ".$postid);
    else $post = sql_query("UPDATE ".$fprefix."posts SET post = ".sqlesc($forumdesc)." WHERE pid = ".$postid);
       } else {

$check = sql_query("SELECT id FROM ".$fprefix."members WHERE name=".sqlesc($ipbuser))  or die(mysql_error());

if(!@mysql_result($check,0)) $ipbid = 66958; else $ipbid=mysql_result($check,0);

   $forumid = sql_query ("SELECT id FROM ".$fprefix."forums WHERE name=".sqlesc($forumcat));
   $forumid = @mysql_result ($forumid,0);
   if (!$forumid) $forumid = $CACHEARRAY['not_found_export_id'];

  $topic = sql_query("INSERT INTO ".$fprefix."topics (title, description, state, posts, starter_id, start_date, last_poster_id, last_post, icon_id, starter_name, last_poster_name, poll_state, last_vote, views, forum_id, approved, author_mode, pinned, moved_to, total_votes, topic_hasattach, topic_firstpost,	topic_queuedposts, topic_open_time,	topic_close_time,	topic_rating_total,	topic_rating_hits) VALUES (".sqlesc($topicname).", ".sqlesc($_POST['source']).", 'open', 0, ".$ipbid.", ".time().", ".$ipbid.", ".time().", 0, ".sqlesc($ipbuser).", ".sqlesc($ipbuser).", 0, 0, 0, ".$forumid.", 1, 1, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0)")  or die(mysql_error());
  $topicid = mysql_insert_id();

  if ($CACHEARRAY['exporttype'] == "wiki")
  $post = sql_query("INSERT INTO ".$fprefix."posts (append_edit, edit_time, author_id, author_name, use_sig, use_emo, ip_address, post_date, icon_id, wiki, post, queued, topic_id, post_title, new_topic, edit_name, post_key, post_parent, post_htmlstate) VALUES
  (0, NULL, ".$ipbid.", ".sqlesc($ipbuser).", 1, 1, ".sqlesc(getip()).", ".time().", 0, ".sqlesc($forumdesc).", '---', 0, ".$topicid.", NULL, 1, NULL, '".md5(microtime())."', 0, 0)") or die(mysql_error());
  else
  $post = sql_query("INSERT INTO ".$fprefix."posts (append_edit, edit_time, author_id, author_name, use_sig, use_emo, ip_address, post_date, icon_id, post, queued, topic_id, post_title, new_topic, edit_name, post_key, post_parent, post_htmlstate) VALUES
  (0, NULL, ".$ipbid.", ".sqlesc($ipbuser).", 1, 1, ".sqlesc(getip()).", ".time().", 0, ".sqlesc($forumdesc).", 0, ".$topicid.", NULL, 1, NULL, '".md5(microtime())."', 0, 0)") or die(mysql_error());

 $postid = mysql_insert_id();

 $updtopic = sql_query ("UPDATE ".$fprefix."topics SET topic_firstpost =".$postid." WHERE tid =".$topicid)  or die(mysql_error());
 $updateforum = sql_query("UPDATE ".$fprefix."forums SET topics =topics+1, posts =posts+1, last_post =".time().", last_poster_id =".$ipbid.", last_poster_name =".sqlesc($ipbuser).", last_title=".sqlesc($topicname).", last_id =".$topicid." WHERE id =".$forumid)  or die(mysql_error());
 $updateuser = sql_query("UPDATE ".$fprefix."members SET posts =posts+1, last_post =".time().", last_activity =".time()." WHERE id=".$ipbid)  or die(mysql_error());
 // updating forum caches
 $statcache = sql_query("SELECT cs_value FROM ".$fprefix."cache_store WHERE cs_key = 'stats'");
 $statcache = mysql_result($statcache,0);
 $statcache = unserialize($statcache);
 $statcache['total_topics']++;
 $statcache = serialize($statcache);
 sql_query("UPDATE ".$fprefix."cache_store SET cs_value=".sqlesc($statcache)." WHERE cs_key='stats'");
 
 $forumcache = sql_query("SELECT cs_value FROM ".$fprefix."cache_store WHERE cs_key = 'forum_cache'");
 $forumcache = mysql_result($forumcache,0);
 $forumcache = unserialize($forumcache);
 $forumcache[$forumid]['topics']++;
 $forumcache[$forumid]['last_post'] = time();
 $forumcache[$forumid]['last_poster_id'] = $ipbid;
 $forumcache[$forumid]['last_poster_name'] = $ipbuser;
 $forumcache[$forumid]['last_title'] = $topicname;
 $forumcache[$forumid]['last_id'] = $topicid;
 //$forumcache[$forumid]['newest_title'] = $topicname;
 //$forumcache[$forumid]['newest_id'] = $topicid;
 $forumcache = serialize($forumcache);
 sql_query("UPDATE ".$fprefix."cache_store SET cs_value=".sqlesc($forumcache)." WHERE cs_key='forum_cache'") or die(mysql_error());
 
 // updating caches end
 
}
 // closing IPB DB connection
relconn();
 // connection closed


 sql_query("UPDATE torrents SET topic_id = ".$topicid." WHERE id=".$id)  or die(mysql_error());
 
// IPB TOPIC TRANSFER END
 }

   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-indextorrents");
  
sql_query("INSERT INTO checkcomm (checkid, userid, torrent) VALUES ($id, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);
@sql_query("DELETE FROM files WHERE torrent = $id");

if ($_POST['nofile'] == 'yes') {
   	} else   {
foreach ($filelist as $file) {
	@sql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($file[0]).",".$file[1].")");
}
}
if ($_POST['nofile'] == 'yes') {} else {
move_uploaded_file($tmpname, "torrents/$id.torrent");

$fp = fopen("torrents/$id.torrent", "w");
if ($fp)
{
    @fwrite($fp, benc($dict), strlen(benc($dict)));
    fclose($fp);
    @chmod($fp, 0644);
}
}

write_log("Торрент номер $id ($torrent) был залит пользователем " . $CURUSER["username"],"5DDB6E","torrent");

/* Email notifs */


$res = sql_query("SELECT name FROM categories WHERE id=$catid") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_assoc($res);
$cat = $arr["name"];
$res = sql_query("SELECT email FROM users WHERE enabled='yes' AND notifs LIKE '%[cat$catid]%'") or sqlerr(__FILE__, __LINE__);
$uploader = $CURUSER['username'];

$size = mksize($totallen);
$CACHEARRAY['description'] = format_comment($descr);

$body = <<<EOD
<html>
Новый фильм на {$CACHEARRAY['sitename']}!

Название: $torrent
Размер файла: $size
Категория: $cat
Залил: $uploader

Информация о фильме:
-------------------------------------------------------------------------------
$forumdesc
-------------------------------------------------------------------------------

Чтобы посмотреть фильм, перейдите по этой ссылке:

{$CACHEARRAY['defaultbaseurl']}/details.php?id=$id&hit=1

-- 
{$CACHEARRAY['sitename']}
</html>
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
    mail("Рассылка <{$CACHEARRAY['siteemail']}>", "Новый фильм - $torrent", $body,
    "From: {$CACHEARRAY['siteemail']}\r\nBcc: $to", "-f{$CACHEARRAY['siteemail']}");
    $nthis = 0;
  }
}


// header("Location: $CACHEARRAY['defaultbaseurl']/details.php?id=$id");

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
