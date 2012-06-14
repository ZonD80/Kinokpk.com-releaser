<?php
/**
 * Torrent upload parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
require_once(ROOT_PATH . "include/benc.php");

ini_set("upload_max_filesize", $REL_CONFIG['max_torrent_size']);

function bark($msg)
{
    global $REL_LANG, $REL_DB, $REL_TPL;
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $msg . " <a href=\"javascript:history.go(-1);\">{$REL_LANG->say_by_key('ago')}</a>");
}

INIT();

loggedinorreturn();
get_privilege('upload_releases');

foreach (explode(":", "type:name") as $v) {
    if (!isset($_POST[$v]))
        bark($REL_LANG->_('Some of required fields does not filled'));
}


if ($_POST['annonce']) {
    $_POST['nofile'] = 1;
    $_POST['nofilesize'] = 0;
}

if ($_POST['nofile']) {
} else {
    if (!isset($_FILES["tfile"]))
        bark("missing form data");

    if (($_POST['nofile']) && (empty($_POST['nofilesize']))) bark($REL_LANG->_('You does not fill release size'));

    $f = $_FILES["tfile"];
    $fname = unesc($f["name"]);
    if (empty($fname))
        bark($REL_LANG->_('File does not uploaded. Empty filename.'));
}

if (!is_array($_POST["type"]))
    bark($REL_LANG->_('Error parsing selected categories'));
else
    foreach ($_POST['type'] as $cat) if (!is_valid_id($cat)) bark($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$catsstr = implode(',', $_POST['type']);

if ($_POST['nofile']) {
} else {

    if (!validfilename($fname))
        bark($REL_LANG->_('Invalid filename'));
    if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
        bark($REL_LANG->_('Invalid filename (not .torrent)'));
    $shortfname = $torrent = $matches[1];
    $tiger_hash = trim((string)$_POST['tiger_hash']);
    if ((!preg_match("/[^a-zA-Z0-9]/", $tiger_hash) || (mb_strlen($tiger_hash) <> 38)) && $tiger_hash) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_tiger_hash'));
}

if ($_POST['multi']) $multi = 1; else $multi = 0;

if (!empty($_POST["name"]))
    $torrent = unesc((string)$_POST["name"]); else bark($REL_LANG->_('Empty release name'));


if ($_POST['nofile']) {
} else {
    $tmpname = $f["tmp_name"];
    if (!is_uploaded_file($tmpname))
        bark($REL_LANG->_('Error moving uploaded torrent. Contact <a href="%s">site staff</a>', $REL_SEO->make_link('staff')));
    if (!filesize($tmpname))
        bark($REL_LANG->_('Empty file'));

    $dict = bdec_file($tmpname, $REL_CONFIG['max_torrent_size']);
    if (!isset($dict))
        bark($REL_LANG->_('It is not binary-encoded file'));
}

if ($_POST['free'] AND get_privilege('edit_releases', false)) {
    $free = 1;
} else {
    $free = 0;
}
;

if ($_POST['sticky'] AND get_privilege('edit_releases', false))
    $sticky = 1;
else
    $sticky = 0;

if ($_POST["visible"]) {
    get_privilege('post_releases_to_mainpage');
}
if ($_POST['nofile']) {
} else {

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

    if ($multi && !$anarray) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('This torrent is not a multitracker') . '. <a href="javascript:history.go(-1);">' . $REL_LANG->_('Go back') . '</a>');

    $dict = bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash

    list($info) = dict_check($dict, "info");

    list($dname, $plen, $pieces) = dict_check($info, "name(string):piece length(integer):pieces(string)");


    if (strlen($pieces) % 20 != 0)
        bark($REL_LANG->_('Invalid dictionary'));

    $filelist = array();
    $totallen = dict_get($info, "length", "integer");
    if (isset($totallen)) {
        $filelist[] = array($dname, $totallen);
        $type = 0;
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
                    bark("filename error");
                $ffa[] = $ffe["value"];
            }
            if (!count($ffa))
                bark($REL_LANG->_("filename error"));
            $ffe = implode("/", $ffa);
            $filelist[] = array($ffe, $ll);

        }

        $type = 1;
    }


    $infohash = sha1($info["string"]);

}

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
$uploaddir = "torrents/images/";


$ret = $REL_DB->query("SHOW TABLE STATUS LIKE 'torrents'");
$row = mysql_fetch_array($ret);
$next_id = $row['Auto_increment'];

for ($x = 0; $x < $REL_CONFIG['max_images']; $x++) {
    $y = $x + 1;

    if ($_FILES[image . $x]['name'] != "") {
        $_FILES[image . $x]['type'] = strtolower($_FILES[image . $x]['type']);
        $_FILES[image . $x]['name'] = strtolower($_FILES[image . $x]['name']);
        $_POST['img' . $x] = $uploaddir . $next_id . "-$x." . $allowed_types[$_FILES[image . $x]['type']];
        $image_upload[$x] = true;
    } else $image_upload[$x] = false;

    if (!empty($_POST['img' . $x])) {
        $img = trim(htmlspecialchars((string)$_POST['img' . $x]));
        if (strpos($img, ',') || strpos($img, '?')) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Dynamic image links are forbidden'));

        if (!preg_match('/^(.+)\.(gif|png|jpeg|jpg)$/si', $img))
            $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('File %s is not an image', ($x + 1)));

        //$check = remote_fsize($img);
        if ($image_upload[$x]) {
            $check = $_FILES[image . $x]['size'];

            if (!$check) $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Unable to check size of image %s', $y));
            if ($check > $maxfilesize) $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Image size is greather then %s. Please select smaller image and try again.', $maxfilesize));

            // Upload the file
            $copy = move_uploaded_file($_FILES[image . $x]['tmp_name'], ROOT_PATH . $img);

            if (!$copy) $REL_TPL->stderr($REL_LANG->_("Error"), $REL_LANG->_("Unable to save image, contact site administration"));
        }
        $inames[] = $img;
    }


}


$image = $inames;

$images = @implode(',', $inames);

$image = @array_shift($image);

if ($image_upload[0]) $image = $REL_CONFIG['defaultbaseurl'] . '/' . $image;

// FORUMDESC will be used in email notifs
if (!$image) $forumdesc = "<div align=\"center\"><img src=\"{$REL_CONFIG['defaultbaseurl']}/pic/noimage.gif\" border=\"0\" class=\"linked-image\" /></div><br />";
if ($image) $forumdesc = "<div align=\"center\"><a href=\"$image\" target=\"_blank\"><img alt=\"{$REL_LANG->_to(0,'Release screenshot (click to view full size)')}\" src=\"$image\" border=\"0\" class=\"linked-image\" /></a></div><br />";
$catssql = $REL_DB->query("SELECT name FROM categories WHERE id IN ($catsstr)");
while (list($catname) = mysql_fetch_array($catssql)) $forumcats[] = $catname;
$forumcats = implode(', ', $forumcats);
$forumdesc .= "<table width=\"100%\" border=\"1\"><tr><td valign=\"top\"><b>{$REL_LANG->_to(0,'Type (genre)')}:</b></td><td>" . $forumcats . "</td></tr><tr><td><b>{$REL_LANG->_('Name')}:</b></td><td>" . sqlesc($torrent) . "</td></tr>";


// DEFINE size FOR forum & email notifs
if ($_POST['nofile']) {
    $forumsize = mksize($_POST['nofilesize']);
} else {
    $forumsize = mksize($totallen / 1024 / 1024);
}


$descr = (string)$_POST['descr'];
$descrtpl = (array)$_POST['descrtpl'];

if (!$descr && !$descrtpl) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Release description is missing'));

if ($descrtpl) {
    $allowed_types = array('movie', 'music', 'soft', 'play');
    if (!in_array($descrtpl['type'], $allowed_types)) $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Unknown release type'));
    $REL_TPL->assign('descr', $descrtpl);
    $descr = $REL_TPL->fetch("modules/upload/{$descrtpl['type']}_descr.tpl");
}
//////////////////////////////////////////////

// get relgroup
$relgroup = (int)$_POST['relgroup'];

if ($relgroup) {
    $relgroup = @mysql_result($REL_DB->query("SELECT id FROM relgroups WHERE id=$relgroup"), 0);

    if (!$relgroup) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_relgroup'));
}

$tags = htmlspecialchars((string)$_POST['tags']);

// Replace punctuation characters with spaces
if ($_POST['nofile']) {
    $nofilesize = (float)$_POST['nofilesize'];
    $fname = 'nofile';
    $infohash = md5($torrent);
    $torrent = htmlspecialchars(str_replace("_", " ", $torrent));
    if ($_POST['annonce'])
        $torrent .= " | {$REL_LANG->_to(0,'ANNOUNCE')}"; else $torrent .= " - {$REL_LANG->_to(0,'Release without torrent')}";

    $totallen = (float)($nofilesize * 1024 * 1024);

    $ret = $REL_DB->query("INSERT INTO torrents (filename, owner, visible, sticky, info_hash, tiger_hash, name, descr, size, free, images, category, tags, added, last_action, relgroup" . ((get_privilege('post_releases_approved', false)) ? ', moderatedby' : '') . ") VALUES (" . implode(",", array_map("sqlesc", array($fname, $CURUSER["id"], ($_POST["visible"] ? 1 : 0), $sticky, $infohash, $tiger_hash, $torrent, $descr, $totallen, $free, $images, $catsstr, $tags))) . ", " . time() . ", " . time() . ", $relgroup" . ((get_privilege('post_releases_approved', false)) ? ', ' . $CURUSER['id'] : '') . ")");
} else {

    $torrent = htmlspecialchars(str_replace("_", " ", $torrent));

    $ret = $REL_DB->query("INSERT INTO torrents (filename, owner, visible, sticky, info_hash, name, descr, size, numfiles, ismulti, free, images, category, tags, added, last_action, relgroup" . ((get_privilege('post_releases_approved', false)) ? ', moderatedby' : '') . ") VALUES (" . implode(",", array_map("sqlesc", array($fname, $CURUSER["id"], ($_POST["visible"] ? 1 : 0), $sticky, $infohash, $torrent, $descr, $totallen, count($filelist), $type, $free, $images, $catsstr, $tags))) . ", " . time() . ", " . time() . ", $relgroup" . ((get_privilege('post_releases_approved', false)) ? ', ' . $CURUSER['id'] : '') . ")");
}
if (!$ret) {
    if (mysql_errno() == 1062)
        bark($REL_LANG->_('This torrent was already uploaded'));
    bark("mysql puked: " . mysql_error());
}
$id = mysql_insert_id();

$REL_DB->query("INSERT INTO xbt_files (fid,info_hash) VALUES ($id," . sqlesc(pack('H*', $infohash)) . ")");

//insert localhost tracker
if (!$_POST['nofile']) $REL_DB->query("INSERT INTO trackers (torrent,tracker) VALUES ($id,'localhost')");

// Insert remote trackers //
if ($anarray) {
    foreach ($anarray as $anurl) $REL_DB->query("INSERT INTO trackers (torrent,tracker) VALUES ($id," . sqlesc(strip_tags($anurl)) . ")");
}
// trackers insert end


// making forum desc
$forumdesc .= "<tr><td valign=\"top\"><b>" . $REL_LANG->say_by_key('description') . ":</b></td><td>" . format_comment($descr) . "</td></tr>";

$forumdesc .= "<tr><td valign=\"top\"><b>{$REL_LANG->_to(0,'Release size')}:</b></td><td>" . $forumsize . "</td></tr>";

$topicfooter .= "<tr><td valign=\"top\"><b>" . ("{$REL_LANG->_('Release')} {$REL_CONFIG['defaultbaseurl']}:") . "</b></td><td><div align=\"center\">[<span style=\"color:#FF0000\"><a href=\"{$REL_CONFIG['defaultbaseurl']}/{$REL_SEO->make_link('details','id',$id,'name',translit($torrent))}\">{$REL_LANG->_('View release on')} {$REL_CONFIG['defaultbaseurl']}</a></span>]</div></td></tr></table>";

$forumdesc .= $topicfooter;
// end

$REL_CACHE->clearGroupCache('block-indextorrents');

$REL_DB->query("INSERT INTO notifs (checkid, userid, type) VALUES ($id, $CURUSER[id], 'relcomments')");
@$REL_DB->query("DELETE FROM files WHERE torrent = $id");

if ($_POST['nofile']) {
} else {
    foreach ($filelist as $file) {
        @$REL_DB->query("INSERT INTO files (torrent, filename, size) VALUES ($id, " . sqlesc($file[0]) . "," . $file[1] . ")");
    }
}
if ($_POST['nofile']) {
} else {
    $fp = @file_put_contents("torrents/$id.torrent", benc($dict['value']['info']));
    if (!$fp) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Error moving uploaded torrent. Contact <a href="%s">site staff</a>', $REL_SEO->make_link('staff')));
}

$text = $REL_LANG->_('Release #%s (<a target=\"_blank\" href=\"%s\">%s</a> was uploaded by %s', $id, $REL_SEO->make_link('details', 'id', $id, 'name', translit($torrent)), $torrent, make_user_link());


write_log($text, "torrent");


$REL_DB->query("INSERT INTO shoutbox (id, userid, date, text, orig_text) VALUES ('id',0, " . time() . ", " . sqlesc($text) . ", " . sqlesc($text) . ")") or sqlerr(__FILE__, __LINE__);


/* Email notifs */

$body = <<<EOD
{$REL_LANG->_('New unchecked release')} {$REL_CONFIG['sitename']}!

{$REL_LANG->_('Attention! This message sent automatically. Release may be deleted by moderator')}!
{$REL_LANG->_('Name')}: $torrent
{$REL_LANG->_('Size')}: $forumsize
{$REL_LANG->_('Category')}: {$forumcats}
{$REL_LANG->_('Uploader')}: {$CURUSER['username']}

{$REL_LANG->_('Release information')}:
-------------------------------------------------------------------------------
$forumdesc
-------------------------------------------------------------------------------
EOD;

$bfooter = <<<EOD
{$REL_LANG->_('To view this release follow this link')}:

{$REL_SEO->make_link('details', 'id', $id, 'name', translit($torrent))}

EOD;

$body .= $bfooter;
$descr .= nl2br($bfooter);


if (!get_privilege('post_releases_approved', false)) {
    send_notifs('unchecked', nl2br($body));
} else {
    send_notifs('torrents', format_comment($descr));
}


$announce_urls_list[] = $REL_CONFIG['defaultbaseurl'] . "/" . $REL_SEO->make_link('announce', 'passkey', $CURUSER['passkey']);
$announce_sql = $REL_DB->query("SELECT tracker FROM trackers WHERE torrent=$id AND tracker<>'localhost'");
while (list($announce) = mysql_fetch_array($announce_sql)) $announce_urls_list[] = $announce;

$retrackers = get_retrackers();
//var_dump($retrackers);
if ($retrackers) foreach ($retrackers as $announce)
    if (!in_array($announce, $announce_urls_list)) $announce_urls_list[] = $announce;

$link = make_magnet($infohash, makesafe($torrent), $announce_urls_list);

if ($REL_CRON['rating_enabled']) {
    $msg = sprintf($REL_LANG->say_by_key('upload_notice'), $REL_CRON['rating_perrelease'], $id, $link);
} else $msg = sprintf($REL_LANG->say_by_key('upload_notice_norating'), $id, $link);


safe_redirect($REL_SEO->make_link('details', 'id', $id, 'name', $torrent), 3);
$REL_TPL->stderr($REL_LANG->say_by_key('uploaded'), $msg . ($anarray ? "<img src=\"" . $REL_SEO->make_link('remote_check', 'id', $id) . "\" width=\"0px\" height=\"0px\" border=\"0\"/>" : ''), 'success');

?>