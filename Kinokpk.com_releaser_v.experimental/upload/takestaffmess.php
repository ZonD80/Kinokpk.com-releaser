<?php
/**
 * Mass pm parser/sender
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

INIT();

loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] != "POST")
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('break_attempt'));

get_privilege('mass_pm');

$sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
$dt = sqlesc(time());
$msg = $_POST['msg'];
if (!$msg)
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('enter_message'));

$subject = htmlspecialchars((string)$_POST['subject']);
if (!$subject)
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('enter_subject'));

if (!is_array($_POST['classes']))
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('select_classes'));

/*$query = sql_query("SELECT id FROM users WHERE class IN (".implode(", ", array_map("sqlesc", $clases)).")");

while ($dat=mysql_fetch_assoc($query)) {
sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES ($sender_id, $dat[id], '" . time() . "', " . sqlesc($msg) .", " . sqlesc($subject) .")") or sqlerr(__FILE__,__LINE__);
}*/

write_log($REL_LANG->say_by_key('mass_mailing')." $CURUSER[username]","tracker");

sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) SELECT $sender_id, id, ".time().", ".sqlesc($msg).", ".sqlesc($subject)." FROM users WHERE class IN (".implode(", ", array_map("intval", $_POST['classes'])).")") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();

safe_redirect($REL_SEO->make_link('staffmess'),1);

stderr($REL_LANG->say_by_key('success'), "".$REL_LANG->say_by_key('send')." $counter ".$REL_LANG->say_by_key('messages'),'success');

?>