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
require_once (ROOT_PATH."include/benc.php");

dbconn();
loggedinorreturn();
parked();


if (!is_valid_id($_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);


$id = (int) $_GET["id"];

$res = sql_query("SELECT torrents.filename, torrents.announce_urls, torrents.owner as userid, users.username as owner FROM torrents LEFT JOIN users ON users.id = torrents.owner WHERE torrents.id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
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


if (strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
	sql_query("UPDATE users SET passkey=".sqlesc($CURUSER[passkey])." WHERE id=".sqlesc($CURUSER[id]));
}

$dict['type'] = 'dictionary';

$announce_url = $CACHEARRAY['defaultbaseurl']."/announce.php?passkey=".$CURUSER['passkey'];

if ($row['announce_urls']) $announce_urls[0] = $row['announce_urls'];
$retrackers = get_retrackers();
if ($retrackers) $announce_urls[1] = $retrackers;


if ($announce_urls) $announce_urls_list = explode(",",implode(",",$announce_urls));

put_announce_urls($dict,$announce_urls_list,$announce_url);

$dict['value']['info'] = bdec_file($fn, (1024*1024));


$dict['value']['comment']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/details.php?id=$id")); // change torrent comment  to URL
$dict['value']['created by']=bdec(benc_str( $row['owner'])); // change created by
$dict['value']['publisher']=bdec(benc_str( $row['owner'])); // change publisher
$dict['value']['publisher.windows-1251']=bdec(benc_str( $row['owner'])); // change publisher.windows-1251
$dict['value']['publisher-url']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id={$row['userid']}")); // change publisher-url
$dict['value']['publisher-url.windows-1251']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id={$row['userid']}")); // change publisher-url.windows-1251

//print('BENCODED: <hr />'.(($row['info_blob'])));
//die($row['info_blob']);
header ("Expires: Tue, 1 Jan 1980 00:00:00 GMT");
header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header ("Cache-Control: no-store, no-cache, must-revalidate");
header ("Cache-Control: post-check=0, pre-check=0", false);
header ("Pragma: no-cache");
header ("X-Powered-by: Kinokpk.com releaser - http://www.kinokpk.com - http://dev.kinokpk.com");
header ("Accept-Ranges: bytes");
header ("Connection: close");
header ("Content-Transfer-Encoding: binary");
header ("Content-Disposition: attachment; filename=\"{$CACHEARRAY['defaultbaseurl']}-$id-".$row['filename']."\"");
header ("Content-Type: application/x-bittorrent");
ob_implicit_flush(true);


print(benc($dict));

?>