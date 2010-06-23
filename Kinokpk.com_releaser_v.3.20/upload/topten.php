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

require_once "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead($REL_LANG->say_by_key('topten'));
$res = sql_query("SELECT SUM(1) FROM users") or sqlerr(__FILE__, __LINE__);
$count = mysql_result($res,0);
if (!$count) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'),'error'); stdfoot(); die(); }
$perpage = 10;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER['PHP_SELF'] . "?".$q);



$res = sql_query("SELECT u.*, c.name, c.flagpic FROM users AS u LEFT JOIN countries AS c ON c.id = u.country ORDER BY ratingsum DESC $limit") or sqlerr(__FILE__, __LINE__);
$num = mysql_num_rows($res);

print ('<div id="users-table">');
print ("<p>$pagertop</p>");
print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" align=\"left\">Имя</td><td class=\"colhead\">Зарегестрирован</td><td class=\"colhead\">Последний вход</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Пол</td><td class=\"colhead\" align=\"left\">Уровень</td><td class=\"colhead\">Страна</td></tr>\n");
while ($arr = mysql_fetch_assoc($res)) {
	if ($arr['country'] > 0) {
		$country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
	}
	else
	$country = "<td align=\"center\">---</td>";
	$ratio = ratearea($arr['ratingsum'],$arr['id'],'users',$CURUSER['id']);

	if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
	elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
	else $gender = "<div align=\"center\"><b>?</b></div>";

	print("<tr><td align=\"left\"><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['id'],'username',translit($arr["username"]))."\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .($arr["donated"] > 0 ? "<img src=\"pic/star.gif\" border=\"0\" alt=\"Donor\">" : "")."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])." (".get_elapsed_time($arr["last_access"],false)." {$REL_LANG->say_by_key('ago')})</td><td>$ratio</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country</tr>\n");
}
print("</table>\n");
print ("<p>$pagerbottom</p>");
print('</div>');

stdfoot();

?>
