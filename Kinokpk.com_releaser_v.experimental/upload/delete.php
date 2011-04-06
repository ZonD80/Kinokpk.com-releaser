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


function bark($msg) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('error'));
	stdmsg($REL_LANG->say_by_key('error'), $msg);
	$REL_TPL->stdfoot();
	exit;
}

INIT();

get_privilege('delete_releases');

if (!is_valid_id($_POST["id"])) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int) $_POST["id"];

loggedinorreturn();

$res = sql_query("SELECT name,owner,images FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($REL_LANG->say_by_key('error'),"Такого торрента не существует.");


$rt = (int) $_POST["reasontype"];

if ( $rt < 1 || $rt > 5)
bark("Неверная причина $rt.");

$reason = $_POST["reason"];

if ($rt == 1)
$reasonstr = "Мертвый: 0 раздающих, 0 качающих = 0 пиров";
elseif ($rt == 2)
$reasonstr = "Двойник" . ($reason[0] ? (": " . trim($reason[0])) : "!");
elseif ($rt == 3)
$reasonstr = "Nuked" . ($reason[1] ? (": " . trim($reason[1])) : "!");
elseif ($rt == 4)
{
	if (!$reason[2])
	bark("Вы не написали пукт правил, которые этот торрент нарушил.");
	$reasonstr = "Нарушение правил: " . trim($reason[2]);
}
else
{
	if (!$reason[3])
	bark("Вы не написали причину, почему удаляете торрент.");
	$reasonstr = trim($reason[3]);
}

deletetorrent($id);

$REL_CACHE->clearGroupCache('block-indextorrents');

$reasonstr = htmlspecialchars($reasonstr);
write_log("Торрент $id ($row[name]) был удален пользователем $CURUSER[username] ($reasonstr)\n","torrent");

$REL_TPL->stdhead("Торрент удален!");

if (isset($_POST["returnto"]))
$ret = "<a href=\"" . htmlspecialchars($_POST["returnto"]) . "\">Назад</a>";
else
$ret = "<a href=\"{$REL_CONFIG['defaultbaseurl']}/\">На главную</a>";

?>
<h2>Торрент удален!</h2>
<p><?= $ret ?></p>
<?

$REL_TPL->stdfoot();

?>