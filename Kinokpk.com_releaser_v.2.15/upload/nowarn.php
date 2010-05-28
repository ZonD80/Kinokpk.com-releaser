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
function bark($msg) {
stdhead();
stdmsg($tracker_lang['error'], $msg);
stdfoot();
exit;
}
dbconn();
loggedinorreturn();
if(isset($_POST["nowarned"])&&($_POST["nowarned"]=="nowarned")){
//if (get_user_class() >= UC_SYSOP) {
if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], "Отказано в доступе.");
{
if (empty($_POST["usernw"]) && empty($_POST["desact"]) && empty($_POST["delete"]))
bark("Вы должны выбрать пользователя для редактирования.");

if (!empty($_POST["usernw"]))
{
$msg = sqlesc("Ваше предупреждение снял " . $CURUSER['username'] . ".");
$added = sqlesc(get_date_time());
$userid = implode(", ", array_map('sqlesc', $_POST['usernw']));
//sql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);

$r = sql_query("SELECT modcomment FROM users WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['usernw'])) . ")")or sqlerr(__FILE__, __LINE__);
$user = mysql_fetch_array($r);
$exmodcomment = $user["modcomment"];
$modcomment = date("Y-m-d") . " - Предупреждение снял " . $CURUSER['username'] . ".\n". $modcomment . $exmodcomment;
sql_query("UPDATE users SET modcomment=" . sqlesc($modcomment) . " WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['usernw'])) . ")") or sqlerr(__FILE__, __LINE__);


$do="UPDATE users SET warned='no', warneduntil='0000-00-00 00:00:00' WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['usernw'])) . ")";
$res=sql_query($do);}

if (!empty($_POST["desact"])){
$do="UPDATE users SET enabled='no' WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['desact']) ). ")";
$res=sql_query($do);}
}
}
header("Refresh: 0; url=warned.php");
?>