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
dbconn();
loggedinorreturn();
function ratios($up,$down, $color = True)
{
if ($down > 0)
{
$r = number_format($up / $down, 2);
if ($color)
$r = "<font color=".get_ratio_color($r).">$r</font>";
}
else
if ($up > 0)
$r = "Inf.";
else
$r = "---";
return $r;
}
$mask = "255.255.255.0";
$tmpip = explode(".",$CURUSER["ip"]);
$ip = $tmpip[0].".".$tmpip[1].".".$tmpip[2].".0";
$regex = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))(\.\b|$)){4}$/";
if (substr($mask,0,1) == "/")
{
$n = substr($mask, 1, strlen($mask) - 1);
if (!is_numeric($n) or $n < 0 or $n > 32)
{
stdmsg($tracker_lang['error'], "Неверная маска подсети.");
stdfoot();
die();
}
else
$mask = long2ip(pow(2,32) - pow(2,32-$n));
}
elseif (!preg_match($regex, $mask))
{
stdmsg("Оишбка", "Неверная маска подсети.");
stdfoot();
die();
}
$res = sql_query("SELECT id, username, class, last_access, added, uploaded, downloaded FROM users WHERE enabled='yes' AND status='confirmed' AND id <> $CURUSER[id] AND INET_ATON(ip) & INET_ATON('$mask') = INET_ATON('$ip') & INET_ATON('$mask')") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res)){
stdhead("Сетевые соседи");

print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead align=center colspan=8>:: Сетевые соседи ::</td></tr><tr><td colspan=8>Эти пользователи ваши сетевые соседи, что означает что вы получите от них скорость выше.</td></tr>");
print("<tr><td class=colhead align=left>Пользователь</td>
<td class=colhead>Раздал</td><td class=colhead>Скачал</td>
<td class=colhead>Рейтинг</td><td class=colhead>Зарегистрирован</td>
<td class=colhead>Последний доступ</td><td class=colhead align=left>Класс</td>
<td class=colhead>IP</td></tr>\n");
while($arr=mysql_fetch_assoc($res)){
print("<tr><td align=left><b><a href=userdetails.php?id=$arr[id]>".get_user_class_color($arr["class"], $arr["username"])."</a></b></td>
<td>".mksize($arr["uploaded"])."</td>
<td>".mksize($arr["downloaded"])."</td>
<td>".ratios($arr["uploaded"],$arr["downloaded"])."</td>
<td>$arr[added]</td><td>$arr[last_access]</td>
<td align=left>".get_user_class_name($arr["class"])."</td>
<td>".$tmpip[0].".".$tmpip[1].".".$tmpip[2].".*</td></tr>\n");
}
print("</table>");
stdfoot();}
else
stderr("Информация","Сетевых соседей не обнаружено.");
?>