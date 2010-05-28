<?php

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
dbconn(true);

stdhead("Аплоадеры");

loggedinorreturn;

if ($CURUSER['class'] >= UC_MODERATOR)

{

$query = "SELECT id, username, added, uploaded, downloaded, donor, warned FROM users WHERE class = 3";
$result = sql_query($query);
$num = mysql_num_rows($result); // how many uploaders
echo "<h2>Информация о аплоадерах</h2>";
echo "<p>У нас " . $num . " аплоадер" . ($num > 1 ? "ов" : "") . "</p>";

$zerofix = $num - 1; // remove one row because mysql starts at zero

if ($num > 0)
{
echo "<table cellpadding=4 align=center border=1>";
echo "<tr>";
echo "<td class=colhead>Номер</td>";
echo "<td class=colhead>Пользователь</td>";
echo "<td class=colhead>Раздал&nbsp;/&nbsp;Скачал</td>";
echo "<td class=colhead>Рейтинг</td>";
echo "<td class=colhead>Залил&nbsp;торрентов</td>";
echo "<td class=colhead>Последняя&nbsp;заливка</td>";
echo "<td class=colhead>Отправить ЛС</td>";
echo "</tr>";

for ($i = 0; $i <= $zerofix; $i++)
{
$id = mysql_result($result, $i, "id");
$username = mysql_result($result, $i, "username");
$added = mysql_result($result, $i, "added");
$uploaded = mksize(mysql_result($result, $i, "uploaded"));
$downloaded = mksize(mysql_result($result, $i, "downloaded"));
$uploadedratio = mysql_result($result, $i, "uploaded");
$downloadedratio = mysql_result($result, $i, "downloaded");
$donor = mysql_result($result, $i, "donor");
$warned = mysql_result($result, $i, "warned");

// get uploader torrents activity
$upperquery = "SELECT added FROM torrents WHERE owner = $id";
$upperresult = sql_query($upperquery);

$torrentinfo = mysql_fetch_array($upperresult);

$numtorrents = mysql_num_rows($upperresult);

if ($downloaded > 0)
{
$ratio = $uploadedratio / $downloadedratio;
$ratio = number_format($ratio, 3);
$color = get_ratio_color($ratio);
if ($color)
$ratio = "<font color=$color>$ratio</font>";
}
else
if ($uploaded > 0)
$ratio = "Inf.";
else
$ratio = "---";

// get donor
if ($donor == "yes")
$star = "<img src=pic/star.gif>";
else
$star = "";

// get warned
if ($warned == "yes")
$klicaj = "<img src=pic/warned8.gif>";
else
$klicaj = "";

$counter = $i + 1;

echo "<tr>";
echo "<td align=center>$counter</td>";
echo "<td><a href=userdetails.php?id=$id>$username</a> $star $klicaj</td>";
echo "<td>$uploaded / $downloaded</td>";
echo "<td>$ratio</td>";
echo "<td>$numtorrents торрентов</td>";
if ($numtorrents > 0)
{
$lastadded = mysql_result($upperresult, $numtorrents - 1, "added");
echo "<td>" . get_elapsed_time(sql_timestamp_to_unix_timestamp($lastadded)) . " назад (" . date("d. M Y",strtotime($lastadded)) . ")</td>";
}
else
echo "<td>---</td>";
echo "<td align=center><a href=message.php?action=sendmessage&amp;receiver=$id><img border=0 src=pic/button_pm.gif></a></td>";

echo "</tr>";


}
echo "</table>";
}

}

else
stdmsg($tracker_lang['error'],$tracker_lang['access_denied']);

stdfoot();

?>