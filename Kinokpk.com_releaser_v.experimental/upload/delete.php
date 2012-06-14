<?php

/**
 * Releases deleter
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();

get_privilege('delete_releases');

if (!is_valid_id($_POST["id"])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int)$_POST["id"];

loggedinorreturn();

$res = $REL_DB->query("SELECT name,owner,images FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid ID'));


$rt = (int)$_POST["reasontype"];

if ($rt < 1 || $rt > 5)
    $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Invalid reason'));

$reason = (string)$_POST["reason"];

if ($rt == 1)
    $reasonstr = $REL_LANG->_('Dead (0 seeders, 0 leechers)');
elseif ($rt == 2)
    $reasonstr = $REL_LANG->_('Duplicated') . ($reason[0] ? (": " . trim($reason[0])) : "!");
elseif ($rt == 3)
    $reasonstr = $REL_LANG->_('Nuked') . ($reason[1] ? (": " . trim($reason[1])) : "!");
elseif ($rt == 4) {
    if (!$reason[2])
        $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Please specify rule'));
    $reasonstr = "{$REL_LANG->_('Infringement of rules')}: " . trim($reason[2]);
}
else {
    if (!$reason[3])
        $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Please specify reason of release deletion'));
    $reasonstr = trim($reason[3]);
}

deletetorrent($id, $reasonstr);

$REL_CACHE->clearGroupCache('block-indextorrents');

$reasonstr = htmlspecialchars($reasonstr);
write_log($REL_LANG->_('Release with id %s named %s was removed by %s due to %s', $id, $row[name], make_user_link(), $reasonstr), "torrent");

$REL_TPL->stdhead($REL_LANG->_('Release deleted'));

if (isset($_POST["returnto"]))
    $ret = "<a href=\"" . htmlspecialchars($_POST["returnto"]) . "\">{$REL_LANG->_('Go back')}</a>";
else
    $ret = "<a href=\"{$REL_CONFIG['defaultbaseurl']}/\">{$REL_LANG->_('Go to mainpage')}</a>";

?>
<h2><?php print $REL_LANG->_('Release deleted');?></h2>
<p><?php print  $ret; ?></p>
<?php
$REL_TPL->stdfoot();

?>