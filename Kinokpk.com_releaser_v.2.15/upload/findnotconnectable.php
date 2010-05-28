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

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();
if (get_user_class() < UC_UPLOADER)
stderr($tracker_lang['error'], "Нет доступа.");

if ($_GET['action'] == "list") {

$res2 = sql_query("SELECT userid, seeder, torrent, agent FROM peers WHERE connectable='no' ORDER BY userid DESC") or sqlerr(__FILE__, __LINE__);

stdhead("Peers that are unconnectable");
print("<a href=findnotconnectable.php?action=sendpm><h3>Послать всем несоединябельным пирам массовое ПМ</h3></a>");
print("<a href=findnotconnectable.php><h3>Просмотреть лог (Проверьте это прежде чем отправлять ЛС пользователям)</h3></a>");
print("<h1>Пиры с которыми нельзя соединиться</h1>");
print("Это только те пользователи которые сейчас активны на торрентах.");

print("<br /><font color=red>*</font> означает что пользователь сидирует.<p>");
$result = sql_query("SELECT DISTINCT userid FROM peers WHERE connectable = 'no'");
$count = mysql_num_rows($result);
print ("$count уникальных пиров с которыми нельзя соединиться.");
@mysql_free_result($result);

if (mysql_num_rows($res2) == 0)
print("<p align=center><b>Со всеми пирами можно соединится!</b></p>\n");
else
{
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>Пользователь</td><td class=colhead>Торрент</td><td class=colhead>Клиент</td></tr>\n");
while($arr2 = mysql_fetch_assoc($res2))
{
$r2 = sql_query("SELECT username FROM users WHERE id=$arr2[userid]") or sqlerr(__FILE__, __LINE__);
$a2 = mysql_fetch_assoc($r2);
print("<tr><td><a href=userdetails.php?id=$arr2[userid]>$a2[username]</a></td><td align=left><a href=details.php?id=$arr2[torrent]&dllist=1#seeders>$arr2[torrent]");
if ($arr2[seeder] == 'yes')
print("<font color=red>*</font>");
print("</a></td><td align=left>$arr2[agent]</td></tr>\n");
}
print("</table>\n");
}
}

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST"){
$dt = sqlesc(get_date_time());
$msg = $_POST['msg'];
if (!$msg)
stderr($tracker_lang['error'],"Введите текст сообщения");

$query = sql_query("SELECT distinct userid FROM peers WHERE connectable='no'");
while($dat=mysql_fetch_assoc($query)){
$subject = sqlesc("Трекер определил вас несоединябельного");
sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,$dat[userid] , '" . get_date_time() . "', " . sqlesc($msg) . ", " . $subject .")") or sqlerr(__FILE__,__LINE__);
}
sql_query("INSERT INTO notconnectablepmlog ( user , date ) VALUES ( $CURUSER[id], $dt)") or sqlerr(__FILE__,__LINE__);
header("Refresh: 0; url=findnotconnectable.php");


}

if ($_GET['action'] == "sendpm") {
stdhead("Пиры с которыми нельзя соединиться");
?>
<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<div align=center>
<h1>Общее сообщение для пользователей с которыми нельзя соединиться</a></h1>
<form method=post action=findnotconnectable.php>
<?

if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
{
?>
<input type=hidden name=returnto value=<?=$_GET["returnto"] ? $_GET["returnto"] : $_SERVER["HTTP_REFERER"]?>>
<?
}
//default message
$body = "The tracker has determined that you are firewalled or NATed and cannot accept incoming connections. \n\nThis means that other peers in the swarm will be unable to connect to you, only you to them. Even worse, if two peers are both in this state they will not be able to connect at all. This has obviously a detrimental effect on the overall speed. \n\nThe way to solve the problem involves opening the ports used for incoming connections (the same range you defined in your client) on the firewall and/or configuring your NAT server to use a basic form of NAT for that range instead of NAPT (the actual process differs widely between different router models. Check your router documentation and/or support forum. You will also find lots of information on the subject at PortForward). \n\nAlso if you need help please come into our IRC chat room or post in the forums your problems. We are always glad to help out.\n\nThank You";
?>
<table cellspacing=0 cellpadding=5>
<tr>
<td>Send Mass Messege To All Non Connectable Users<br />
<table style="border: 0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="border: 0">&nbsp;</td>
<td style="border: 0">&nbsp;</td>
</tr>
</table>
</td>
</tr>
<tr><td><textarea name=msg cols=120 rows=15><?=$body?></textarea></td></tr>
<tr>
<tr><td colspan=2 align=center><input type=submit value="Отправить" class=btn></td></tr>
</table>
<input type=hidden name=receiver value=<?=$receiver?>>
</form>

</div></td></tr></table>
<?
}
if ($_GET['action'] == ""){
stdhead("Лог общих сообщений для файрволеных");
$getlog = sql_query("SELECT * FROM `notconnectablepmlog` LIMIT 10");
print("<h1>Лог общих сообщений для файрволеных</h1>");
print("<a href=findnotconnectable.php?action=sendpm><h3>Послать общее сообщение для пользователей с которыми нельзя соединиться</h3></a>");
print("<a href=findnotconnectable.php?action=list><h3>Показать пользователей с которыми нельзя соединиться</h3></a>");
print("<br />Пожалуста не отправляйте ЛС слишком часто. Мы не хотим спамить пользователей, только дадим знать что с ними нельзя соединиться.<p>");
print("<br />Кажду неделю будет нормально.<p>");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>Пользователь</td><td class=colhead>Дата</td><td class=colhead>Прошло</td></tr>");
while($arr2 = mysql_fetch_assoc($getlog)){
$r2 = sql_query("SELECT username FROM users WHERE id=$arr2[user]") or sqlerr(__FILE__, __LINE__);
$a2 = mysql_fetch_assoc($r2);
$elapsed = get_elapsed_time(sql_timestamp_to_unix_timestamp($arr2[date]));
print("<tr><td class=colhead><a href=userdetails.php?id=$arr2[user]>$a2[username]</a></td><td class=colhead>$arr2[date]</td><td>$elapsed назад</td></tr>");
}
print("</table>");

}

stdfoot();

?>