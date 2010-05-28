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

$search = trim($_GET['search']);
$class = $_GET['class'];
if ($class == '-' || !is_valid_user_class($class))
	$class = '';

if ($search != '' || $class) {
	$query = "username LIKE '%" . sqlwildcardesc("$search") . "%' AND status='confirmed'";
	if ($search)
		$q = "search=" . htmlspecialchars($search);
} else {
	$letter = trim($_GET["letter"]);
	if (strlen($letter) > 1)
		die;

	if ($letter != "" && strpos("abcdefghijklmnopqrstuvwxyz", $letter) === false)
		$letter = "a";
	$query = ( $letter != "" ? "username LIKE '$letter%' AND " : "") . "status='confirmed'";
	if ($letter == "")
		$letter = "a";
	$q = "letter=$letter";
}

if (is_valid_user_class($class)) {
	$query .= " AND class = $class";
	$q .= ($q ? "&amp;" : "") . "class=$class";
}

stdhead("Пользователи");

print("<h1>Пользователи</h1>\n");

print("<form method=\"get\" action=\"users.php\">\n");
print("Поиск: <input type=\"text\" size=\"30\" name=\"search\" value=\"".htmlspecialchars($search)."\">\n");
print("<select name=\"class\">\n");
print("<option value=\"-\">(Все уровни)</option>\n");
for ($i = 0;;++$i) {
if ($c = get_user_class_name($i))
	print("<option value=\"$i\"" . (is_valid_user_class($class) && $class == $i ? " selected" : "") . ">$c</option>\n");
else
	break;
}
print("</select>\n");
print("<input type=\"submit\" value=\"Вперед\">\n");
print("</form>\n");

print("<p>\n");

for ($i = 97; $i < 123; ++$i)
{
$l = chr($i);
$L = chr($i - 32);
if ($l == $letter)
print("<b>$L</b>\n");
else
print("<a href=\"users.php?letter=$l\"><b>$L</b></a>\n");
}

print("</p>\n");

$page = $_GET['page'];
$perpage = 100;

$res = sql_query("SELECT COUNT(*) FROM users WHERE $query") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);
$pages = floor($arr[0] / $perpage);
if ($pages * $perpage < $arr[0])
++$pages;

if ($page < 1)
$page = 1;
else
if ($page > $pages)
$page = $pages;

for ($i = 1; $i <= $pages; ++$i)
if ($i == $page)
$pagemenu .= "<b>$i</b>\n";
else
$pagemenu .= "<a href=\"users.php?$q&page=$i\"><b>$i</b></a>\n";

if ($page == 1)
$browsemenu .= "<b>&lt;&lt; Пред</b>";
else
$browsemenu .= "<a href=\"users.php?$q&page=" . ($page - 1) . "\"><b>&lt;&lt; Пред</b></a>";

$browsemenu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($page == $pages)
$browsemenu .= "<b>След &gt;&gt;</b>";
else
$browsemenu .= "<a href=\"users.php?$q&page=" . ($page + 1) . "\"><b>След &gt;&gt;</b></a>";

print("<p>$browsemenu<br />$pagemenu</p>");

$offset = ($page * $perpage) - $perpage;

$res = sql_query("SELECT u.*, c.name, c.flagpic FROM users AS u LEFT JOIN countries AS c ON c.id = u.country WHERE $query ORDER BY username LIMIT $offset,$perpage") or sqlerr(__FILE__, __LINE__);
$num = mysql_num_rows($res);

print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" align=\"left\">Имя</td><td class=\"colhead\">Зарегестрирован</td><td class=\"colhead\">Последний вход</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Пол</td><td class=\"colhead\" align=\"left\">Уровень</td><td class=\"colhead\">Страна</td></tr>\n");
for ($i = 0; $i < $num; ++$i)
{
$arr = mysql_fetch_assoc($res);
if ($arr['country'] > 0) {
$country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
}
else
	$country = "<td align=\"center\">---</td>";
if ($arr['added'] == '0000-00-00 00:00:00')
	$arr['added'] = '-';
if ($arr['last_access'] == '0000-00-00 00:00:00')
	$arr['last_access'] = '-';
if ($arr["downloaded"] > 0) {
	$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
if (($arr["uploaded"] / $arr["downloaded"]) > 100)
	$ratio = "100+";
$ratio = "<font color=\"" . get_ratio_color($ratio) . "\">$ratio</font>";
}
else
	if ($arr["uploaded"] > 0)
		$ratio = "Inf.";
	else
		$ratio = "------";

if ($arr["gender"] == "1") $gender = "<img src=\"".$pic_base_url."male.gif\" alt=\"Парень\" style=\"margin-left: 4pt\">";
elseif ($arr["gender"] == "2") $gender = "<img src=\"".$pic_base_url."female.gif\" alt=\"Девушка\" style=\"margin-left: 4pt\">";

print("<tr><td align=\"left\"><a href=\"userdetails.php?id=$arr[id]\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .($arr["donated"] > 0 ? "<img src=\"pic/star.gif\" border=\"0\" alt=\"Donor\">" : "")."</td>" .
"<td>$arr[added]</td><td>$arr[last_access]</td><td>$ratio</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country</tr>\n");
}
print("</table>\n");

print("<p>$pagemenu<br />$browsemenu</p>");

stdfoot();
die;

?>