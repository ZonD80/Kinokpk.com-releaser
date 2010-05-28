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

require_once "include/bittorrent.php";

dbconn();

loggedinorreturn();

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

} else $ajax=0;

$page = (int) $_GET["page"];

$search = (string) $_GET['search'];
if ($ajax) $search = base64_decode($search);
$search = htmlspecialchars(trim($search));

$class = (int) $_GET['class'];
if ($class == '-' || !is_valid_user_class($class))
$class = '';

if ($search != '' || $class) {
	$query = "username LIKE '%" . sqlwildcardesc($search) . "%' AND confirmed=1";
	if ($search)
	$q = "search=" . $search;
}

if (is_valid_user_class($class)) {
	$query .= " AND class = $class";
	$q .= ($q ? "&amp;" : "") . "class=$class";
}

if ($query) $query = " WHERE ".$query;

if (!$ajax) {
	stdhead($tracker_lang['users']);
	print '<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;
var switched = 0;
function pageswitcher(page) {
     (function($){
    if ($) no_ajax = false;
    $("#users-table").empty();
    $("#users-table").append(\'<div align="center"><img src="pic/loading.gif" border="0" alt="loading"/></div>\');
    $.get("users.php", { ajax: 1, page: page, search: "'.base64_encode($search).'", class: "'.$class.'" }, function(data){
    $("#users-table").empty();
    $("#users-table").append(data);
});
})(jQuery);

if (!switched){
window.location.href = window.location.href+"#users-table";
switched++;
}
else window.location.href = window.location.href;

return no_ajax;
}
//]]>
</script>
';
}

if ((get_user_class () >= UC_MODERATOR) && $_GET['act']) {
	if ($_GET['act'] == "users") {
		begin_frame("Пользователи с рейтингом ниже 0.20");

		echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
		echo "<tr><td class=colhead align=left>Пользователь</td><td class=colhead>Рейтинг</td><td class=colhead>IP</td><td class=colhead>Зарегистрирован</td><td class=colhead>Последний раз был на трекере</td><td class=colhead>Скачал</td><td class=colhead>Раздал</td></tr>";


		$result = sql_query ("SELECT * FROM users WHERE uploaded / downloaded <= 0.20 AND enabled = 1 ORDER BY downloaded DESC");
		if ($row = mysql_fetch_array($result)) {
			do {
				if ($row["uploaded"] == "0") { $ratio = "inf"; }
				elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
				$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
				echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".mkprettytime($row["added"])."</td><td>".mkprettytime($row["last_access"])."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


			} while($row = mysql_fetch_array($result));
		} else {print "<tr><td colspan=7>Извините, записей не обнаружено!</td></tr>";}
		echo "</table>";
		end_frame(); }

		elseif ($_GET['act'] == "last") {
			begin_frame("Последние пользователи");

			echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
			echo "<tr><td class=colhead align=left>Пользователь</td><td class=colhead>Рейтинг</td><td class=colhead>IP</td><td class=colhead>Зарегистрирован</td><td class=colhead>Последний&nbsp;раз&nbsp;был&nbsp;на&nbsp;трекере</td><td class=colhead>Скачал</td><td class=colhead>Раздал</td></tr>";

			$result = sql_query ("SELECT * FROM users WHERE enabled = 1 AND confirmed=1 ORDER BY added DESC LIMIT 100");
			if ($row = mysql_fetch_array($result)) {
				do {
					if ($row["uploaded"] == "0") { $ratio = "inf"; }
					elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
					else {
						$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
						$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
					}
					echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".mkprettytime($row["added"])."</td><td>".mkprettytime($row["last_access"])."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


				} while($row = mysql_fetch_array($result));
			} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
			echo "</table>";
			end_frame(); }

			elseif ($_GET['act'] == "banned") {
				begin_frame("Забаненые пользователи");

				echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
				echo "<tr><td class=colhead align=left>Пользователь</td><td class=colhead>Рейтинг</td><td class=colhead>IP</td><td class=colhead>Зарегистрирован</td><td class=colhead>Последний раз был</td><td class=colhead>Скачал</td><td class=colhead>Раздал</td></tr>";
				$result = sql_query ("SELECT * FROM users WHERE enabled = 0 ORDER BY last_access DESC ");
				if ($row = mysql_fetch_array($result)) {
					do {
						if ($row["uploaded"] == "0") { $ratio = "inf"; }
						elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
						else {
							$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
							$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
						}
						echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".mkprettytime($row["added"])."</td><td>".mkprettytime($row["last_access"])."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


					} while($row = mysql_fetch_array($result));
				} else {print "<tr><td colspan=7>Извините, записей не обнаружено!</td></tr>";}
				echo "</table>";
				end_frame(); }

}
elseif (!isset($_GET['act'])) {
	if (!$ajax) {
		print("<h1>Пользователи</h1>\n");

		print("<form method=\"get\" action=\"users.php\">\n");
		print($tracker_lang['search']." <input type=\"text\" size=\"30\" name=\"search\" value=\"".$search."\">\n");
		print("<select name=\"class\">\n");
		print("<option value=\"-\">(Все уровни)</option>\n");
		for ($i = 0;;++$i) {
			if ($c = get_user_class_name($i))
			print("<option value=\"$i\"" . (is_valid_user_class($class) && $class == $i ? " selected" : "") . ">$c</option>\n");
			else
			break;
		}
		print("</select>\n");
		print("<input type=\"submit\" value=\"{$tracker_lang['go']}\">\n");
		print("</form>\n");

	}
	$res = sql_query("SELECT COUNT(*) FROM users$query") or sqlerr(__FILE__, __LINE__);
	$count = mysql_result($res,0);
	if (!$count) { stdmsg($tracker_lang['error'],$tracker_lang['nothing_found'],'error'); stdfoot(); die(); }
	$perpage = 50;
	list($pagertop, $pagerbottom, $limit) = browsepager($perpage, $count, $_SERVER['PHP_SELF'] . "?".$q , "#users-table" );



	$res = sql_query("SELECT u.*, c.name, c.flagpic FROM users AS u LEFT JOIN countries AS c ON c.id = u.country$query ORDER BY username $limit") or sqlerr(__FILE__, __LINE__);
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
		if ($arr["downloaded"] > 0) {
			$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
			if (($arr["uploaded"] / $arr["downloaded"]) > 100)
			$ratio = "10(int)";
			$ratio = "<font color=\"" . get_ratio_color($ratio) . "\">$ratio</font>";
		}
		else
		if ($arr["uploaded"] > 0)
		$ratio = "Inf.";
		else
		$ratio = "------";

		if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
		elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
		else $gender = "<div align=\"center\"><b>?</b></div>";

		print("<tr><td align=\"left\"><a href=\"userdetails.php?id=$arr[id]\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .($arr["donated"] > 0 ? "<img src=\"pic/star.gif\" border=\"0\" alt=\"Donor\">" : "")."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])."</td><td>$ratio</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country</tr>\n");
	}
	print("</table>\n");
	print ("<p>$pagerbottom</p>");
	print('</div>');

}
if (!$ajax) stdfoot();

?>