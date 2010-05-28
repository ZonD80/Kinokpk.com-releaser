<? require "include/bittorrent.php";

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

dbconn();
loggedinorreturn();

$res = sql_query("SELECT id, name FROM categories ORDER BY name");
while($cat = mysql_fetch_assoc($res))
$catoptions .= "<input type=\"checkbox\" name=\"cat[]\" value=\"$cat[id]\" ".(strpos($CURUSER['notifs'], "[cat$cat[id]]") !== false ? " checked" : "") . "/>$cat[name]<br />";
$category[$cat['id']] = $cat['name'];

stdhead("RSS");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
$link = "$DEFAULTBASEURL/rss.php";
if ($_POST['feed'] == "dl")
$query[] = "feed=dl";
if (isset($_POST['cat']))
$query[] = "cat=".implode(',', $_POST['cat']);
else {
/*stdmsg($tracker_lang['error'],"Вы должны выбрать категорию!");
stdfoot();
die();*/
}
if ($_POST['login'] == "passkey")
$query[] = "passkey=$CURUSER[passkey]";
$queries = implode("&", $query);
if ($queries)
$link .= "?$queries";

stdmsg($tracker_lang['success'], "Используйте этот адрес в вашей программе для чтения RSS: <br /><a href=$link>$link</a>");
stdfoot();
die();
}
?>
<FORM method="POST" action="getrss.php">
<table border="1" cellspacing="1" cellpadding="5">
<TR>
<TD class="rowhead">Категории:
</TD>
<TD><?=$catoptions?>
<span class="small">Если вы не выберете категории для просмотра,<br /> вам будет выдана ссылка на все категории.</span>
</TD>
</TR>
<TR>
<TD class="rowhead">Тип ссылки в RSS:
</TD>
<TD>
<INPUT type="radio" name="feed" value="web" checked />Ссылка на страницу<BR>
<INPUT type="radio" name="feed" value="dl" />Ссылка на скачивание
</TD>
</TR>
<TR>
<TD class="rowhead">Тип логина:
</TD>
<TD>
<INPUT type="radio" name="login" value="cookie" />Стандарт (cookies)<BR>
<INPUT type="radio" name="login" value="passkey" checked />Альтернативный (passkey)
</TD>
</TR>
<TR>
<TD colspan="2" align="center">
<BUTTON type="submit">Сгенерировать RSS ссылку</BUTTON>
</TD>
</TR>
</TABLE>
</FORM>

<?
stdfoot();
?>