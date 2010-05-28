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

dbconn();
getlang('takestaffmess');
loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] != "POST")
stderr($tracker_lang['error'], $tracker_lang['break_attempt']);

if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
$dt = sqlesc(time());
$msg = $_POST['msg'];
if (!$msg)
stderr($tracker_lang['error'],$tracker_lang['enter_message']);

$subject = htmlspecialchars((string)$_POST['subject']);
if (!$subject)
stderr($tracker_lang['error'],$tracker_lang['enter_subject']);

if (!is_array($_POST['clases']))
stderr($tracker_lang['error'],$tracker_lang['select_classes']);

/*$query = sql_query("SELECT id FROM users WHERE class IN (".implode(", ", array_map("sqlesc", $clases)).")");

while ($dat=mysql_fetch_assoc($query)) {
sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES ($sender_id, $dat[id], '" . time() . "', " . sqlesc($msg) .", " . sqlesc($subject) .")") or sqlerr(__FILE__,__LINE__);
}*/

write_log($tracker_lang['mass_mailing']." $CURUSER[username]","tracker");

sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) SELECT $sender_id, id, ".time().", ".sqlesc($msg).", ".sqlesc($subject)." FROM users WHERE class IN (".implode(", ", array_map("intval", $_POST['clases'])).")") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();

safe_redirect("staffmess.php",1);

stderr($tracker_lang['success'], "".$tracker_lang['send']." $counter ".$tracker_lang['messages']."");

?>