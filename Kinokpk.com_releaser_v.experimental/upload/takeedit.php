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

INIT();

loggedinorreturn();


require_once("include/benc.php");

$id = (int) $_POST['id'];
if (!$id) $id = (int) $_GET['id'];
$res = $REL_DB->query("SELECT torrents.id, torrents.name, torrents.owner, torrents.info_hash, torrents.filename, torrents.images, torrents.modcomm, torrents.moderated, torrents.moderatedby, torrents.descr FROM torrents WHERE torrents.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
$REL_TPL->stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key("invalid_id"));


if (isset($_GET['checkonly'])) {

	get_privilege('edit_releases');

	headers(true);


	$id = (int) $_GET['id'];

	$REL_CACHE->clearGroupCache('block-indextorrents');

	if ($row['moderatedby']) {
		$REL_DB->query("UPDATE torrents SET moderatedby=0 WHERE id=$id");
		die($REL_LANG->say_by_key('not_yet_checked').' <a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id',$id).'">'.$REL_LANG->say_by_key('check').'</a>'.$return);
	}
	else {
		$REL_DB->query("UPDATE torrents SET moderatedby={$CURUSER['id']}, moderated=0 WHERE id=$id");
		// send notifs
		if (!$row['moderated']&&$REL_CRON['rating_enabled']) {
			$REL_DB->query("UPDATE users SET ratingsum = ratingsum + {$REL_CRON['rating_perrelease']} WHERE id={$row['owner']}");
			$bfooter = <<<EOD
{$REL_LANG->_('To view release, follow this link')}:

			{$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))}

EOD;
			$descr = format_comment($row['descr']).nl2br($bfooter);
			send_notifs('torrents',format_comment($descr));
		}

		die($REL_LANG->say_by_key('checked_by').make_user_link().' <a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id',$id).'">'.$REL_LANG->say_by_key('uncheck').'</a>'.$return);
	}
} elseif(isset($_POST['add_trackers'])) {
	get_privilege('edit_releases');

	if (!isset($_POST['trackers'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Missing form data'));
	$POSTtrackers = explode("\n",trim((string)$_POST['trackers']));
	if (!$POSTtrackers) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Unable to get tracker list'));

	$POSTtrackers = array_map("trim",$POSTtrackers);
	$POSTtrackers = array_map("makesafe",$POSTtrackers);
	$res = $REL_DB->query("SELECT tracker FROM trackers WHERE torrent=$id AND tracker<>'localhost'");
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
		$REL_DB->query("DELETE FROM trackers WHERE tracker=".sqlesc($tracker)." AND torrent=$id");
		$state[$tracker] = 'deleted';
	}
	if ($trackers_to_add)
	foreach ($trackers_to_add as $tracker) {
		if ($tracker) {
			$peers = get_remote_peers($tracker, $row['info_hash']);
			$reason[$tracker] = makesafe($peers['state']);
			if ($peers['state']=='ok') {
				$REL_DB->query("INSERT INTO trackers (tracker,torrent) VALUES (".sqlesc(strip_tags($tracker)).",$id)");//;
				$REL_DB->query("UPDATE LOW_PRIORITY trackers SET seeders=".(int)$peers['seeders'].", leechers=".(int)$peers['leechers'].", lastchecked=".time().", state=".sqlesc($peers['state']).", method='{$peers['method']}', remote_method='{$peers['remote_method']}', state=".sqlesc($peers['state'])." WHERE torrent=$id AND tracker=".sqlesc($tracker));
				$state[$tracker] = 'added';
			} else $state[$tracker] = 'failed';
		}
	}
	$REL_TPL->stdhead($REL_LANG->say_by_key('add_announce_urls'));

	print ('<table width="100%"><tr><td class="colhead">'.$REL_LANG->say_by_key('tracker').'</td><td class="colhead">'.$REL_LANG->say_by_key('status').'</td></tr>');
	foreach ($state AS $tracker => $status) {
		print ("<tr><td>$tracker</td><td>{$REL_LANG->say_by_key('tracker_'.$status)}{$reason[$tracker]}</td></tr>");
	}
	print "</table>";
	$REL_TPL->stdmsg($REL_LANG->say_by_key('success'),'<h1><a href="'.$REL_SEO->make_link('details','id', $row['id'] ,'name',translit($row['name'])).'">'.$REL_LANG->say_by_key('back_to_details').'</a>');
	$REL_TPL->stdfoot();
	write_log($REL_LANG->_to(0,'%s edited torrent with ID <a href="%s">%s</a>',make_user_link(),$REL_SEO->make_link('details','id',$id,'name',translit($row['name'])),$id),'torrent');
	die();
}


function bark($msg) {
	global  $REL_LANG, $REL_DB, $REL_TPL;
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $msg." <a href=\"javascript:history.go(-1);\">{$REL_LANG->say_by_key('ago')}</a>");
}

foreach(explode(":","type:name") as $v) {
	if (!isset($_POST[$v]))
	bark($REL_LANG->_('Missing form data'));
}

$name = htmlspecialchars((string)($_POST['name']));
if (!preg_match("#(.*?) \([0-9-]+\) \[(.*?)\]#si",$name))
bark ("{$REL_LANG->_("Release name does not corresponding to rule, please change it and try again:")}<br/>{$REL_LANG->say_by_key('taken_from_torrent')}");

if (!is_array($_POST["type"]))
bark($REL_LANG->_('Error parsing selected categories'));
else
foreach ($_POST['type'] as $cat) if (!is_valid_id($cat)) bark($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));


if ($_POST['multi']) $multi=1; else $multi=0;

$updateset = array();

if ($_POST['nofile']) {} else {
	if (isset($_FILES["tfile"]) && !empty($_FILES["tfile"]["name"]))
	$update_torrent = true;
	$tiger_hash = trim((string)$_POST['tiger_hash']);
	if ((!preg_match("/[^a-zA-Z0-9]/",$tiger_hash) || (mb_strlen($tiger_hash)<>38)) && $tiger_hash) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_tiger_hash'));
	$updateset[] = "tiger_hash = ".sqlesc($tiger_hash);
}


$updateset[] = "tags = ".$REL_DB->sqlesc(htmlspecialchars((string)$_POST['tags']));

if ($CURUSER["id"] != $row["owner"] && !get_privilege('edit_releases',false))
bark("You're not the owner! How did that happen?\n");

// IMAGE UPLOADS

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
$uploaddir = "torrents/images/";

for ($x=0; $x < $REL_CONFIG['max_images']; $x++) {
	$y=$x+1;

	if ($_FILES[image.$x]['name'] != "") {
		$_FILES[image.$x]['type'] = strtolower($_FILES[image.$x]['type']);
		$_FILES[image.$x]['name'] = strtolower($_FILES[image.$x]['name']);
		$_POST['img'.$x] = $uploaddir.$id."-$x.".$allowed_types[$_FILES[image.$x]['type']];
		$image_upload[$x] = true;
	} else $image_upload[$x] = false;

	if (!empty($_POST['img'.$x])) {
		$img=trim(htmlspecialchars((string)$_POST['img'.$x]));
		if (strpos($img,',') || strpos($img,'?')) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Dynamic image links are forbidden'));

		if (!preg_match('/^(.+)\.(gif|png|jpeg|jpg)$/si', $img))
		$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('File %s is not an image',($x+1)));

		//$check = remote_fsize($img);
		if ($image_upload[$x]) {
		 $check = $_FILES[image.$x]['size'];
		 	
		 if (!$check) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unable to check size of image %s',$y));
		 if ($check>$maxfilesize) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Image size is greather then %s. Please select smaller image and try again.',$maxfilesize));

		 // Upload the file
		 $copy = move_uploaded_file($_FILES[image.$x]['tmp_name'], ROOT_PATH.$img);

		 if (!$copy) $REL_TPL->stderr($REL_LANG->_("Error"),$REL_LANG->_("Unable to save image, contact site administration"));
		}
		$inames[]=$img;
	}



}

$images = @implode(',',$inames);

$updateset[]="images=".sqlesc($images);

////////////////////////////////////////////////

if (($_POST['nofile']) && (empty($_POST['nofilesize']))) bark($REL_LANG->_('Missing size for release without torrent'));

if ($_POST['nofile']) {$fname = 'nofile'; } else {
	$fname = $row["filename"];
	preg_match('/^(.+)\.torrent$/si', $fname, $matches);
	$shortfname = $matches[1];
}

if ($update_torrent) {

	$f = $_FILES["tfile"];
	$fname = unesc($f["name"]);

	if (empty($fname))
	bark($REL_LANG->_('Empty filename'));
	if (!validfilename($fname))
	bark($REL_LANG->_('Invalid filename'));
	if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
	bark($REL_LANG->_('Invalid filename (not .torrent)'));
	$tmpname = $f["tmp_name"];
	if (!is_uploaded_file($tmpname))
	bark($REL_LANG->_('Error moving uploaded torrent. Contact <a href="%s">site staff</a>',$REL_SEO->make_link('staff')));
	if (!filesize($tmpname))
	bark($REL_LANG->_('Empty file'));
	$dict = bdec_file($tmpname, $REL_CONFIG['max_torrent_size']);
	if (!isset($dict))
	bark($REL_LANG->_('It is not binary-encoded file'));
	list($info) = dict_check($dict, "info");
	list($dname, $plen, $pieces) = dict_check($info, "name(string):piece length(integer):pieces(string)");
	if (strlen($pieces) % 20 != 0)
	bark($REL_LANG->_('Invalid dictionary'));

	$filelist = array();
	$totallen = dict_get($info, "length", "integer");
	if (isset($totallen)) {
		$filelist[] = array($dname, $totallen);
		$torrent_type = 0;
	} else {
		$flist = dict_get($info, "files", "list");
		if (!isset($flist))
		bark($REL_LANG->_("missing both length and files"));
		if (!count($flist))
		bark($REL_LANG->_("no files"));
		$totallen = 0;
		foreach ($flist as $fn) {
			list($ll, $ff) = dict_check($fn, "length(integer):path(list)");
			$totallen += $ll;
			$ffa = array();
			foreach ($ff as $ffe) {
				if ($ffe["type"] != "string")
				bark($REL_LANG->_("filename error"));
				$ffa[] = $ffe["value"];
			}
			if (!count($ffa))
			bark($REL_LANG->_("filename error"));
			$ffe = implode("/", $ffa);
			$filelist[] = array($ffe, $ll);
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

	if ($multi && !$anarray) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('This torrent is not a multitracker').'. <a href="javascript:history.go(-1);">'.$REL_LANG->_('Go back').'</a>');

	$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash

	list($info) = dict_check($dict, "info");

	$infohash = sha1($info["string"]);
	//move_uploaded_file($tmpname, ROOT_PATH."torrents/$id.torrent");
	@file_put_contents(ROOT_PATH."torrents/$id.torrent",benc($dict['value']['info']));

	$updateset[] = "info_hash = " . sqlesc($infohash);
	$update_xbt_query = "UPDATE xbt_files SET info_hash=".sqlesc(pack('H*', $infohash))." WHERE fid=$id";
	$updateset[] = "filename = " . sqlesc($fname);
	$REL_DB->query("DELETE FROM files WHERE torrent = $id");
	$REL_DB->query("DELETE FROM trackers WHERE torrent = ".$id);
	// insert localhost tracker
	if ($update_torrent) $REL_DB->query("INSERT INTO trackers (torrent,tracker) VALUES ($id,'localhost')");
	// Insert remote trackers //
	if ($anarray) {
		foreach ($anarray as $anurl) $REL_DB->query("INSERT INTO trackers (torrent,tracker) VALUES ($id,".sqlesc(strip_tags($anurl)).")");
	}
	// trackers insert end
	$nf = count($filelist);

	$REL_DB->query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($dname).",".$totallen.")");
	$updateset[] = "size = ".$totallen;
	$updateset[] = "numfiles = ".$nf;
	$updateset[] = "ismulti = ".$torrent_type;
	if ($_POST['nofile']) $dname = 'nofile';

}

$updateset[] = "name = " . sqlesc($name);

$modcomm = (string)$_POST['modcomm'];
if ($row['modcomm'] != $modcomm) $updateset[] = "modcomm = ".sqlesc($REL_LANG->_to(0,'Last edit by %s at %s',$CURUSER['username'],mkprettytime(time()))."\n".htmlspecialchars($modcomm));

$catsstr = implode(',',$_POST['type']);

$updateset[] = "category = " . sqlesc($catsstr);

if ($_POST['nofile']) {

	$wastor = $REL_DB->query("SELECT filename FROM torrents WHERE id =".$id);
	$wastor = mysql_result($wastor,0);

	if ($wastor != 'nofile') {
		$REL_DB->query("DELETE FROM files WHERE torrent = ".$id);
		$REL_DB->query("DELETE FROM xbt_files_users WHERE fid = ".$id);
        $REL_DB->query("DELETE FROM xbt_files WHERE fid = ".$id);
		$REL_DB->query("DELETE FROM snatched WHERE torrent = ".$id);
		$REL_DB->query("DELETE FROM trackers WHERE torrent = ".$id);
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
	$relgroup = @mysql_result($REL_DB->query("SELECT id FROM relgroups WHERE id=$relgroup"),0);

	if (!$relgroup) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_relgroup'));

}
$updateset[] = "relgroup = $relgroup";

if(get_privilege('edit_releases',false)) {
	$updateset[] = "free = '".($_POST["free"]? 1 : 0)."'";

	$updateset[] = "banned = ".($_POST["banned"]?1:0);
	$updateset[] = "sticky = ".($_POST['sticky']?1:0);

	if ($_POST["visible"]) {
		get_privilege('post_releases_to_mainpage');
	}
	$updateset[] = "visible = '" . ($_POST["visible"] ? 1 : 0) . "'";
}


if ((get_privilege('edit_releases',false)) && isset($_POST['approve'])) {
	if (!$row['moderated']) {
		$REL_DB->query("UPDATE users SET ratingsum = ratingsum + {$REL_CRON['rating_perrelease']} WHERE id={$row['owner']}");
	}
	$updateset[] = "moderated = 1";
	$updateset[] = "moderatedby = ".$CURUSER["id"];
	// send notifs
	if (!$row['moderated']) {
		$bfooter = <<<EOD
{$REL_LANG->_('To view release follow this link')}:

		{$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))}

EOD;
		$descr = "<hr/>".format_comment($row['descr']).nl2br($bfooter);
		send_notifs('torrents',format_comment($descr));
	}
} else $updateset[] = "moderatedby = 0";


$descr = ((string)$_POST['descr']);

$updateset[] = 'descr = '.sqlesc($descr);
/// get kinopoisk.ru trailer!


if ($_POST['upd']) $updateset[] = "added = '" . time() . "'";

$REL_DB->query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");
if (mysql_errno() == 1062) $REL_TPL->stderr($REL_LANG->say_by_key('error'),'Torrent already uploaded!');

if ($update_xbt_query)
$REL_DB->query($update_xbt_query);

$REL_CACHE->clearGroupCache('block-indextorrents');

write_log($REL_LANG->_to(0,'%s edited torrent with ID <a href="%s">%s</a>',make_user_link(),$REL_SEO->make_link('details','id',$id,'name',translit($row['name'])),$id),"torrent");

$returl = $REL_SEO->make_link('details','id',$id,'name',translit($row['name']));
if (isset($_POST["returnto"]))
$returl .= "&returnto=" . strip_tags($_POST["returnto"]);


safe_redirect($returl,1);

$REL_TPL->stderr($REL_LANG->say_by_key('success'),$REL_LANG->_("Release successfully edited. Now you will be redirected to it's details").($anarray?"<img src=\"".$REL_SEO->make_link('remote_check','id',$id)."\" width=\"0px\" height=\"0px\" border=\"0\"/>":''),'success');

?>
