<?php
/**
 * Just sets user language
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
dbconn();
getlang('setlang');

if (empty($_GET["l"])) stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$_GET['l'] = (string)$_GET['l'];

setcookie("lang", (string)$_GET["l"], 0x7fffffff, "/");
if ($CURUSER) sql_query("UPDATE users SET language = ".sqlesc($_GET["l"])." WHERE id = ".$CURUSER['id']);

if (isset($_GET['returnto']))
safe_redirect(" ".htmlspecialchars($_GET["returnto"]));
else
safe_redirect(" index.php");

?>