<?php
/**
 * Bookmark delete parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
function bark($msg)
{
    $REL_TPL->stdhead();
    $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $msg);
    $REL_TPL->stdfoot();
    exit;
}

INIT();

loggedinorreturn();

if (!isset($_POST[delbookmark]))
    bark($REL_LANG->say_by_key('no_selection'));

$res2 = $REL_DB->query("SELECT id FROM bookmarks WHERE torrentid IN (" . implode(", ", array_map("sqlesc", (array)$_POST[delbookmark])) . ") AND userid={$CURUSER['id']}");

while ($arr = mysql_fetch_assoc($res2)) {
    $REL_DB->query("DELETE FROM bookmarks WHERE id = $arr[id]");
}

safe_redirect(strip_tags($_SERVER['HTTP_REFERER']));
?>