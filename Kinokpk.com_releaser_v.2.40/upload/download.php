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
parked();


if (!is_valid_id($_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);


$id = (int) $_GET["id"];

$res = sql_query("SELECT torrents.filename, torrents.owner as userid, users.username as owner FROM torrents LEFT JOIN users ON users.id = torrents.owner WHERE torrents.id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);


if ($row["filename"] == 'nofile') stderr ("Сообщение","Это релиз без TORRENT файла! Скачать его с нашего сервера невозможно, посмотрите ссылки в описаниии релиза <a href='details.php?id=".$id."'>К описанию релиза</a>");
if (@ini_get('output_handler') == 'ob_gzhandler' AND @ob_get_length() !== false)
{	// if output_handler = ob_gzhandler, turn it off and remove the header sent by PHP
@ob_end_clean();
header('Content-Encoding:');
}



$fn = "torrents/$id.torrent";

if (!$row || !is_file($fn) || !is_readable($fn))
stderr($tracker_lang['error'], $tracker_lang['unable_to_read_torrent']);

sql_query("UPDATE torrents SET hits = hits + 1 WHERE id = ".sqlesc($id));

require_once "include/benc.php";

if (strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
	sql_query("UPDATE users SET passkey=".sqlesc($CURUSER[passkey])." WHERE id=".sqlesc($CURUSER[id]));
}

$dict = bdec_file($fn, (1024*1024));

$announce_url = $CACHEARRAY['defaultbaseurl']."/announce.php?passkey=".$CURUSER['passkey'];
$announce_url_li =  "l".$announce_url."e";


if ($dict['value']['announce']) { $oldannounce = $dict['value']['announce'];
$oldannounce_li = "l".$dict['value']['announce']['value']."e";

$announce_old_value = array('type' => 'list', 'value' => array($oldannounce), 'strlen' => strlen($oldannounce_li), 'string' => $oldannounce_li);

}
$dict['value']['announce'] = bdec(benc_str($announce_url));

$announce_orig_value = array('type' => 'list', 'value' => array(bdec(benc_str($announce_url))), 'strlen' => strlen($announce_url_li), 'string' => $announce_url_li);

if ($dict['value']['announce-list']['value']) {
	$dict['value']['announce-list']['value'] = array_pad($dict['value']['announce-list']['value'],-count($dict['value']['announce-list']['value'])-1,$announce_orig_value);
}
else {
	$dict['value']['announce-list']['value'][0] = $announce_orig_value;
	$dict['value']['announce-list']['value'][1] = $announce_old_value;
}
$dict['value']['announce-list']['type'] = 'list';

$liststring = '';
foreach ($dict['value']['announce-list']['value'] as $listarray) $liststring .= "l".$listarray['value'][0]['string']."e";

$dict['value']['announce-list']['string'] = "l".$liststring."e";
$dict['value']['announce-list']['strlen'] = strlen($dict['value']['announce-list']['string']);

$dict['value']['comment']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/details.php?id=$id")); // change torrent comment  to URL
$dict['value']['created by']=bdec(benc_str( $row['owner'])); // change created by
$dict['value']['publisher']=bdec(benc_str( $row['owner'])); // change publisher
$dict['value']['publisher.utf-8']=bdec(benc_str( $row['owner'])); // change publisher.utf-8
$dict['value']['publisher-url']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id={$row['userid']}")); // change publisher-url
$dict['value']['publisher-url.utf-8']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id={$row['userid']}")); // change publisher-url.utf-8

//die(var_dump($dict));

header ("Expires: Tue, 1 Jan 1980 00:00:00 GMT");
header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header ("Cache-Control: no-store, no-cache, must-revalidate");
header ("Cache-Control: post-check=0, pre-check=0", false);
header ("Pragma: no-cache");
header ("X-Powered-by: Kinokpk.com releaser - http://www.kinokpk.com - http://dev.kinokpk.com");
header ("Accept-Ranges: bytes");
header ("Connection: close");
header ("Content-Transfer-Encoding: binary");
header ("Content-Disposition: attachment; filename=\"".$row['filename']."\"");
header ("Content-Type: application/x-bittorrent");
ob_implicit_flush(true);


print(benc($dict));

?>