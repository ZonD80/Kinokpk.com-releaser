<?php
/**
 * Torrent download script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
require_once (ROOT_PATH."include/benc.php");

INIT();
loggedinorreturn();

$action = trim((string)$_GET['a']);
if (!$action) $action = trim((string)$_POST['a']);


if ($action=='my') {
	$r = sql_query ( "SELECT snatched.torrent AS id, torrents.name, users.username AS owner, torrents.added, torrents.owner AS userid FROM snatched LEFT JOIN torrents ON torrents.id = snatched.torrent LEFT JOIN users ON torrents.owner=users.id WHERE snatched.userid = {$CURUSER['id']} AND torrents.owner<>{$CURUSER['id']} GROUP BY id ORDER BY id" ) or sqlerr ( __FILE__, __LINE__ );
	if (!mysql_num_rows ( $r ))	stderr($REL_LANG->_("Error"),$REL_LANG->_("You have not downloaded any releases yet"));
	$fileDir = './';

	require_once(ROOT_PATH."classes/zip/Zip.php");
	if (mb_strlen($CURUSER['passkey']) != 32) {
		$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
		$REL_DB->query("UPDATE xbt_users SET torrent_pass=".sqlesc($CURUSER[passkey])." WHERE uid=".sqlesc($CURUSER[id]));
	}
	$fileTime = date("D, d M Y H:i:s T");
	$zip = new Zip();
	$zip->setComment($REL_LANG->_("Your downloaded torrents on %s",mkprettytime(time())));
	$retrackers = get_retrackers();

	$xbt_config_sql = $REL_DB->query("SELECT name,value FROM xbt_config WHERE name IN ('listen_ipa','listen_port','auto_register')");
	while ($row = mysql_fetch_assoc($xbt_config_sql)) {
		$xbt_config[$row['name']] = $row['value'];
	}

	while  ($row = mysql_fetch_assoc($r)) {
		$fn = "torrents/{$row['id']}.torrent";
		sql_query("UPDATE torrents SET hits = hits + 1 WHERE id = ".($row['id']));
			
		if ($REL_CONFIG['use_xbt']) {
			$announce_urls_list[] = ($xbt_config['listen_ipa']=='*'?$REL_CONFIG['defaultbaseurl']:"http://{$xbt_config['listen_ipa']}").":{$xbt_config['listen_port']}/".($xbt_config['auto_register']?'':"{$CURUSER['passkey']}/")."announce";
		} else {
			$announce_urls_list[] = $REL_CONFIG['defaultbaseurl']."/announce.php?passkey=".$CURUSER['passkey'];
		}
		$announce_sql = sql_query("SELECT tracker FROM trackers WHERE torrent={$row['id']} AND tracker<>'localhost'");
		while (list($announce) = mysql_fetch_array($announce_sql)) $announce_urls_list[] = $announce;

		//var_dump($retrackers);
		if ($retrackers) foreach ($retrackers as $announce)
		if (!in_array($announce,$announce_urls_list)) $announce_urls_list[] = $announce;
		put_announce_urls($dict,$announce_urls_list);

		$dict['type'] = 'dictionary';

		$dict['value']['info'] = bdec_file($fn, (1024*1024));


		$dict['value']['comment']=bdec(benc_str( $REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name'])))); // change torrent comment  to URL
		$dict['value']['created by']=bdec(benc_str( $row['owner'])); // change created by
		$dict['value']['creation date']=bdec(benc_int($row['added'])); // change created on
		$dict['value']['publisher']=bdec(benc_str( $row['owner'])); // change publisher
		$dict['value']['publisher.utf-8']=bdec(benc_str( $row['owner'])); // change publisher.utf-8
		$dict['value']['publisher-url']=bdec(benc_str( $REL_SEO->make_link('userdetails','id',$row['userid'],'username',$row['owner']))); // change publisher-url
		$dict['value']['publisher-url.utf-8']=bdec(benc_str($REL_SEO->make_link('userdetails','id',$row['userid'],'username',$row['owner']))); // change publisher-url.utf-8
		$zip->addFile(benc($dict), str_replace('/','---',translit($row['name']))."-{$row['id']}-{$_SERVER['HTTP_HOST']}.torrent",$row['added']);//,$row['name']);
		unset($dict);
		unset($announce_urls_list);
	}
	$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

	$zip->sendZip("torrents-".date('d.m.Y-H.i')."-{$_SERVER['HTTP_HOST']}.zip");
	die();
}
if (!is_valid_id($_GET['id'])) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$readed = isset($_GET['ok']);

$id = (int) $_GET["id"];


$res = sql_query("SELECT torrents.filename, torrents.info_hash, torrents.tiger_hash, torrents.size, torrents.name, torrents.added, torrents.owner AS userid, torrents.freefor, torrents.free, users.username AS owner,torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage, IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']})) AS relgroup_allowed FROM torrents LEFT JOIN relgroups ON torrents.relgroup=relgroups.id LEFT JOIN users ON users.id = torrents.owner WHERE torrents.id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);
if (!$row)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

if ($row['rgid']) $rgcontent = "<a href=\"relgroups.php?id={$row['rgid']}\">".($row['rgimage']?"<img style=\"border:none;\" title=\"Релиз группы {$row['rgname']}\" src=\"{$row['rgimage']}\"/>":'Релиз группы '.$row['rgname'])."</a>&nbsp;";

if ((!get_privilege('access_to_private_relgroups',false)) && !$row['relgroup_allowed'] && $row['rgid']) stderr($REL_LANG->say_by_key('error'),sprintf($REL_LANG->say_by_key('private_release_access_denied'),$rgcontent));



if ($row["filename"] == 'nofile') stderr ("Сообщение","Это релиз без TORRENT файла! Скачать его с нашего сервера невозможно, посмотрите ссылки в описаниии релиза <a href='details.php?id=".$id."'>К описанию релиза</a>");

if ($row['freefor']) {
	$row['freefor']=explode(',',$row['freefor']);
	if (in_array($CURUSER['id'],$row['freefor'])) $userfree=true;
}

$already_downloaded = @mysql_result(sql_query("SELECT 1 FROM snatched WHERE torrent = $id AND userid = {$CURUSER['id']}"),0);

$rating_enabled = (($REL_CRON['rating_enabled'] && ((time()-$CURUSER['added'])>($REL_CRON['rating_freetime']*86400)) && ($row['userid']<>$CURUSER['id']) && (!get_privilege('is_vip')) && !$userfree && !$row['free'] && !$already_downloaded)?true:false);


if ($rating_enabled && ($CURUSER['ratingsum']<$REL_CRON['rating_downlimit'])) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('rating_low'));

$currating = $CURUSER['ratingsum']-$REL_CRON['rating_perdownload'];
if ($currating>0) $znak='+';
/* @var $hubs Get dchubs to display or not dchubs link form */
$hubs = get_retrackers(false,'dchubs');

if (!$readed) stderr($REL_LANG->say_by_key('downloading_torrent'),($rating_enabled?sprintf($REL_LANG->say_by_key('download_notice'),$REL_CRON['rating_perdownload'],$znak.$currating,$REL_CRON['rating_downlimit']).'<br />':'').'<div align="center"><form action="download.php"><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="ok" value=""><input type="submit" value="'.$REL_LANG->say_by_key('download_torrent').'">&nbsp;<input type="submit" name="magnet" value="'.$REL_LANG->say_by_key('as_magnet').'">'.(($REL_CONFIG['use_dc']&&$hubs&&$row['tiger_hash'])?'&nbsp;<input type="submit" name="dc_magnet" value="'.$REL_LANG->say_by_key('as_dc_magnet').'">':'').'</form></div>'.(!REL_AJAX?sprintf($REL_LANG->say_by_key('to_details'),$id):''),'success');
if ($_GET['magnet']) $magnet = true; else $magnet=false;
if ($_GET['dc_magnet']) $dc_magnet = true; else $dc_magnet=false;

if (!$dc_magnet && !$magnet && (@ini_get('output_handler') == 'ob_gzhandler') && (@ob_get_length() !== false))
{	// if output_handler = ob_gzhandler, turn it off and remove the header sent by PHP
@ob_end_clean();
header('Content-Encoding:');
}



if (!$magnet && !$dc_magnet) {
	$fn = "torrents/$id.torrent";

	if (!$row || !is_file($fn) || !is_readable($fn))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('unable_to_read_torrent'));
}

sql_query("UPDATE torrents SET hits = hits + 1 WHERE id = ".sqlesc($id));
if ($rating_enabled) sql_query("UPDATE users SET ratingsum = ratingsum-{$REL_CRON['rating_perdownload']} WHERE id={$CURUSER['id']}");

$REL_DB->query("INSERT INTO snatched (userid,torrent,completedat) VALUES ({$CURUSER['id']},$id,".time().")");

if ($dc_magnet) {
	if (!$row['tiger_hash']) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_tiger'));
	// look upstairs
	if (!$hubs) stderr($REL_LANG->say_by_key('error'),sprintf($REL_LANG->say_by_key('no_dchubs'),$id,$id));
	$link = make_dc_magnet($row['tiger_hash'],$row['name'],$row['size'],$hubs);
	$message = $REL_LANG->say_by_key('this_is_dc_magnet').'<h1><a href="'.$link.'">'.$REL_LANG->say_by_key('magnet').'</a></h1>'.(!REL_AJAX?sprintf($REL_LANG->say_by_key('to_details'),$id):'');
	stderr($REL_LANG->say_by_key('this_is_magnet_title'),$message,'success');
}

if (mb_strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
	$REL_DB->query("UPDATE xbt_users SET torrent_pass=".sqlesc($CURUSER[passkey])." WHERE uid=".sqlesc($CURUSER[id]));
}

$xbt_config_sql = $REL_DB->query("SELECT name,value FROM xbt_config WHERE name IN ('listen_ipa','listen_port','auto_register')");
while ($xbtrow = mysql_fetch_assoc($xbt_config_sql)) {
	$xbt_config[$xbtrow['name']] = $xbtrow['value'];
}
if ($REL_CONFIG['use_xbt']) {
	$announce_urls_list[] = ($xbt_config['listen_ipa']=='*'?$REL_CONFIG['defaultbaseurl']:"http://{$xbt_config['listen_ipa']}").":{$xbt_config['listen_port']}/".($xbt_config['auto_register']?'':"{$CURUSER['passkey']}/")."announce";
} else {
	$announce_urls_list[] = $REL_CONFIG['defaultbaseurl']."/announce.php?passkey=".$CURUSER['passkey'];
}

$announce_sql = sql_query("SELECT tracker FROM trackers WHERE torrent=$id AND tracker<>'localhost'");
while (list($announce) = mysql_fetch_array($announce_sql)) $announce_urls_list[] = $announce;

$retrackers = get_retrackers();
//var_dump($retrackers);
if ($retrackers) foreach ($retrackers as $announce)
if (!in_array($announce,$announce_urls_list)) $announce_urls_list[] = $announce;

if ($magnet) {
	$link = make_magnet($row['info_hash'],$row['name'],$announce_urls_list);
	$message = $REL_LANG->say_by_key('this_is_magnet').'<h1><a href="'.$link.'">'.$REL_LANG->say_by_key('magnet').'</a></h1>'.(!REL_AJAX?sprintf($REL_LANG->say_by_key('to_details'),$id):'');
	stderr($REL_LANG->say_by_key('this_is_magnet_title'),$message,'success');
}
put_announce_urls($dict,$announce_urls_list);

$dict['type'] = 'dictionary';

$dict['value']['info'] = bdec_file($fn, (1024*1024));


$dict['value']['comment']=bdec(benc_str( $REL_SEO->make_link('details','id',$id,'name',translit($row['name'])))); // change torrent comment  to URL
$dict['value']['created by']=bdec(benc_str( $row['owner'])); // change created by
$dict['value']['creation date']=bdec(benc_int($row['added'])); // change created on
$dict['value']['publisher']=bdec(benc_str( $row['owner'])); // change publisher
$dict['value']['publisher.utf-8']=bdec(benc_str( $row['owner'])); // change publisher.utf-8
$dict['value']['publisher-url']=bdec(benc_str( $REL_SEO->make_link('userdetails','id',$row['userid'],'username',$row['owner']))); // change publisher-url
$dict['value']['publisher-url.utf-8']=bdec(benc_str($REL_SEO->make_link('userdetails','id',$row['userid'],'username',$row['owner']))); // change publisher-url.utf-8

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
header ("Content-Disposition: attachment; filename=\"".str_replace('/','---',translit($row['name']))."-{$id}-{$_SERVER['HTTP_HOST']}.torrent\"");
header ("Content-Type: application/x-bittorrent");
ob_implicit_flush(true);


print(benc($dict));

?>