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

function bark($msg) {
	global $tracker_lang;
	stdhead($tracker_lang['error']);
	stdmsg($tracker_lang['error'], $msg);
	stdfoot();
	exit;
}

function maketable($res)
{
	global $tracker_lang, $CACHEARRAY, $cats;
	$ret = "<table class=main border=1 cellspacing=0 cellpadding=5>" .
    "<tr><td class=colhead align=left>".$tracker_lang['type']."</td><td class=colhead>".$tracker_lang['name']."</td>".($CACHEARRAY['use_ttl'] ? "<td class=colhead align=center>".$tracker_lang['ttl']."</td>" : "")."<td class=colhead align=center>".$tracker_lang['size']."</td><td class=colhead align=right>".$tracker_lang['details_seeding']."</td><td class=colhead align=right>".$tracker_lang['details_leeching']."</td><td class=colhead align=center>".$tracker_lang['uploaded']."</td>\n" .
    "<td class=colhead align=center>".$tracker_lang['downloaded']."</td><td class=colhead align=center>".$tracker_lang['ratio']."</td></tr>\n";
	while ($arr = mysql_fetch_assoc($res))
	{
		if ($arr["downloaded"] > 0)
		{
			$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
			$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
		}
		else
		if ($arr["uploaded"] > 0)
		$ratio = "Inf.";
		else
		$ratio = "---";
		$rescatids= explode(',',$arr['category']);
		foreach ($rescatids AS $rescatid) $arr['cat_names'][]="<a href=\"browse.php?cat={$rescatid}\">".$cats[$rescatid]."</a>";

		$ttl = ($CACHEARRAY['ttl_days']*24) - floor((time() - $arr["added"]) / 3600);
		if ($ttl == 1) $ttl .= "&nbsp;час"; else $ttl .= "&nbsp;часов";
		$size = str_replace(" ", "<br />", mksize($arr["size"]));
		$uploaded = str_replace(" ", "<br />", mksize($arr["uploaded"]));
		$downloaded = str_replace(" ", "<br />", mksize($arr["downloaded"]));
		$seeders = number_format($arr["seeders"]);
		$leechers = number_format($arr["leechers"]);
		$ret .= "<tr><td style='padding: 0px'>".implode(',<br />',$arr['cat_names'])."</td>\n" .
		"<td><a href=details.php?id=$arr[torrent]><b>" . $arr["torrentname"] .
		"</b></a></td>".($CACHEARRAY['use_ttl'] ? "<td align=center>$ttl</td>" : "")."<td align=center>$size</td><td align=right>$seeders</td><td align=right>$leechers</td><td align=center>$uploaded</td>\n" .
		"<td align=center>$downloaded</td><td align=center>$ratio</td></tr>\n";
	}
	$ret .= "</table>\n";
	return $ret;
}

if (!is_valid_id($_GET["id"]))
bark($tracker_lang['invalid_id']);

$id = (int) $_GET["id"];
$cats=assoc_cats();
$r = @sql_query("SELECT * FROM users WHERE id=$id") or sqlerr(__FILE__, __LINE__);
$user = mysql_fetch_array($r) or bark("Нет пользователя с таким ID $id.");
if (!$user["confirmed"]) stderr($tracker_lang['error'],"Этот пользователь еще не подтвердил себя по e-mail, возможно, этот аккаунт вскоре будет удален");
$r = sql_query("SELECT torrents.id, torrents.name, torrents.seeders, torrents.added, torrents.leechers, torrents.category FROM torrents WHERE owner=$id ORDER BY name") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($r) > 0) {
	$torrents = "<table class=main border=1 cellspacing=0 cellpadding=5>\n" .
    "<tr><td class=colhead>".$tracker_lang['type']."</td><td class=colhead>".$tracker_lang['name']."</td>".($CACHEARRAY['use_ttl'] ? "<td class=colhead align=center>".$tracker_lang['ttl']."</td>" : "")."<td class=colhead>".$tracker_lang['tracker_seeders']."</td><td class=colhead>".$tracker_lang['tracker_leechers']."</td></tr>\n";
	while ($a = mysql_fetch_assoc($r)) {
		$ttl = ($CACHEARRAY['ttl_days']*24) - floor((time() - $a["added"]) / 3600);
		if ($ttl == 1) $ttl .= "&nbsp;час"; else $ttl .= "&nbsp;часов";
		$rescatids= explode(',',$a['category']);
		foreach ($rescatids AS $rescatid) $a['cat_names'][]="<a href=\"browse.php?cat={$rescatid}\">".$cats[$rescatid]."</a>";

		$cat = implode(',<br />',$a['cat_names']);
		$torrents .= "<tr><td style='padding: 0px'>$cat</td><td><a href=\"details.php?id=" . $a["id"] . "\"><b>" . $a["name"] . "</b></a></td>"
		.($CACHEARRAY['use_ttl'] ? "<td align=center>$ttl</td>" : "")
		."<td align=right>$a[seeders]</td><td align=right>$a[leechers]</td></tr>\n";
	}
	$torrents .= "</table>";
}

$it = sql_query("SELECT u.id, u.username, u.class, i.id AS invitedid, i.username AS invitedname, i.class AS invitedclass FROM users AS u LEFT JOIN users AS i ON i.id = u.invitedby WHERE u.invitedroot = $id OR u.invitedby = $id ORDER BY u.invitedby");
if (mysql_num_rows($it) >= 1) {
	while ($inviter = mysql_fetch_array($it))
	$invitetree .= "<a href=\"userdetails.php?id=$inviter[id]\">".get_user_class_color($inviter["class"], $inviter["username"])."</a> приглашен <a href=\"userdetails.php?id=$inviter[invitedid]\">".get_user_class_color($inviter["invitedclass"], $inviter["invitedname"])."</a><br />";

}

if ($user["ip"] && (get_user_class() >= UC_MODERATOR || $user["id"] == $CURUSER["id"])) {
	$ip = $user["ip"];
	$dom = @gethostbyaddr($user["ip"]);
	if ($dom == $user["ip"] || @gethostbyname($dom) != $user["ip"])
	$addr = $ip;
	else
	{
		$dom = strtoupper($dom);
		$domparts = explode(".", $dom);
		$domain = $domparts[count($domparts) - 2];
		if ($domain == "COM" || $domain == "CO" || $domain == "NET" || $domain == "NE" || $domain == "ORG" || $domain == "OR" )
		$l = 2;
		else
		$l = 1;
		$addr = "$ip ($dom)";
	}
}

$r = mysql_query("SELECT snatched.torrent AS id, snatched.uploaded, snatched.seeder, snatched.downloaded, snatched.startedat, snatched.completedat, snatched.last_action, torrents.name, torrents.seeders+torrents.remote_seeders AS seeders, torrents.leechers+torrents.remote_leechers AS leechers, torrents.category FROM snatched JOIN torrents ON torrents.id = snatched.torrent WHERE snatched.finished=1 AND userid = $id ORDER BY torrent") or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($r) > 0) {
	$completed = "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" .
  "<tr><td class=\"colhead\">Тип</td><td class=\"colhead\">Название</td><td class=\"colhead\">Раздающих</td><td class=\"colhead\">Качающих</td><td class=\"colhead\">Раздал</td><td class=\"colhead\">Скачал</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Начал / Закончил</td><td class=\"colhead\">Действие</td><td class=\"colhead\">Сидирует</td></tr>\n";
	while ($a = mysql_fetch_array($r)) {
		$rescatids= explode(',',$a['category']);
		foreach ($rescatids AS $rescatid) $a['cat_names'][]="<a href=\"browse.php?cat={$rescatid}\">".$cats[$rescatid]."</a>";
		if ($a["downloaded"] > 0) {
			$ratio = number_format($a["uploaded"] / $a["downloaded"], 3);
			$ratio = "<font color=\"" . get_ratio_color($ratio) . "\">$ratio</font>";
		} else
		if ($a["uploaded"] > 0)
		$ratio = "Inf.";
		else
		$ratio = "---";
		$uploaded = mksize($a["uploaded"]);
		$downloaded = mksize($a["downloaded"]);
		if ($a["seeder"])
		$seeder = "<font color=\"green\">Да</font>";
		else
		$seeder = "<font color=\"red\">Нет</font>";
		$cat = implode(',<br />',$a['cat_names']);    $completed .= "<tr><td style=\"padding: 0px\">$cat</td><td><nobr><a href=\"details.php?id=" . $a["id"] . "\"><b>" . $a["name"] . "</b></a></nobr></td>" .
      "<td align=\"right\">$a[seeders]</td><td align=\"right\">$a[leechers]</td><td align=\"right\">$uploaded</td><td align=\"right\">$downloaded</td><td align=\"center\">$ratio</td><td align=\"center\"><nobr>".mkprettytime($a[startedat])."<br />".mkprettytime($a[completedat])."</nobr></td><td align=\"center\"><nobr>".mkprettytime($a[last_action])."</nobr></td><td align=\"center\">$seeder</td>\n";
	}
	$completed .= "</table>";
}

if (!$user[added])
$joindate = 'N/A';
else
$joindate = mkprettytime($user['added'])." (" . get_elapsed_time($user["added"]) . " ".$tracker_lang['ago'].")";
$lastseen = $user["last_access"];
if (!$lastseen)
$lastseen = $tracker_lang['never'];
else {
	$lastseen = mkprettytime($lastseen). " (" . get_elapsed_time($lastseen) . " ".$tracker_lang['ago'].")";
}
$res = mysql_query("SELECT COUNT(*) FROM comments WHERE user = " . $user[id]);
$torrentcomments = mysql_result($res,0);
$res = mysql_query("SELECT COUNT(*) FROM newscomments WHERE user = " . $user[id]);
$newscomments = mysql_result($res,0);


//if ($user['donated'] > 0)
//  $don = "<img src=pic/starbig.gif>";

$res = sql_query("SELECT name, flagpic FROM countries WHERE id = $user[country] LIMIT 1") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) == 1)
{
	$arr = mysql_fetch_assoc($res);
	$country = "<img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" style=\"margin-left: 8pt\">";
}

//if ($user["donor"] == "yes") $donor = "<td class=embedded><img src=pic/starbig.gif alt='Donor' style='margin-left: 4pt'></td>";
//if ($user["warned"] == "yes") $warned = "<td class=embedded><img src=pic/warnedbig.gif alt='Warned' style='margin-left: 4pt'></td>";

if ($user["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\">";
elseif ($user["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\">";
elseif ($user["gender"] == "3") $gender = "N/A";

$res = sql_query("SELECT torrent, added, uploaded, downloaded, torrents.name AS torrentname, size, category, seeders+remote_seeders AS seeders, leechers+remote_leechers AS leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id WHERE userid = $id AND seeder=0") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0)
$leeching = maketable($res);
$res = sql_query("SELECT torrent, added, uploaded, downloaded, torrents.name AS torrentname, size, category, seeders+remote_seeders AS seeders, leechers+remote_leechers AS leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id WHERE userid = $id AND seeder=1") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0)
$seeding = maketable($res);

///////////////// BIRTHDAY MOD /////////////////////
if ($user[birthday] != "0000-00-00")
{
	//$current = date("Y-m-d", time());
	$current = date("Y-m-d", time() + $CURUSER['tzoffset'] * 60);
	list($year2, $month2, $day2) = explode('-', $current);
	$birthday = $user["birthday"];
	$birthday = date("Y-m-d", strtotime($birthday));
	list($year1, $month1, $day1) = explode('-', $birthday);
	if($month2 < $month1)
	{
		$age = $year2 - $year1 - 1;
	}
	if($month2 == $month1)
	{
		if($day2 < $day1)
		{
			$age = $year2 - $year1 - 1;
		}
		else
		{
			$age = $year2 - $year1;
		}
	}
	if($month2 > $month1)
	{
		$age = $year2 - $year1;
	}

}
///////////////// BIRTHDAY MOD /////////////////////

stdhead("Просмотр профиля " . $user["username"]);
$enabled = $user["enabled"] == 1;

print('<table width="100%"><tr><td width="100%" style="vertical-align: top;">');

begin_main_frame();

print("<tr><td colspan=\"2\" align=\"center\"><p><h1 style=\"margin:0px\">$user[username]" . get_user_icons($user, true) . "</h1></p>\n");


if (!$enabled)
print("<p><b>Этот аккаунт отключен</b> Причина: ".$user['dis_reason']."</p>\n");
elseif ($CURUSER["id"] <> $user["id"]) {
	$r = sql_query("SELECT id FROM friends WHERE (userid=$id AND friendid={$CURUSER['id']}) OR (friendid = $id AND userid={$CURUSER['id']})") or sqlerr(__FILE__, __LINE__);
	list($friend) = mysql_fetch_array($r);
	if ($friend)
	print("<p>(<a href=\"friends.php?action=deny&id=$friend\">Убрать из друзей</a>)</p>\n");
	else
	{
		print("<p>(<a href=\"friends.php?action=add&id=$id\">Добавить в друзья</a>)</p>\n");
	}
}
print("<p>".ratearea($user['ratingsum'],$user['id'],'users')."$country</p>");

print('<table width=100% border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead width=1%>Зарегистрирован</td><td align=left width=99%>'.$joindate.'</td></tr>
<tr><td class=rowhead>Последний раз был на трекере</td><td align=left>'.$lastseen.'</td></tr>');

if (get_user_class() >= UC_MODERATOR)
print("<tr><td class=\"rowhead\">Email</td><td align=\"left\"><a href=\"mailto:$user[email]\">$user[email]</a></td></tr>\n");
if ($addr)
print("<tr><td class=\"rowhead\">IP</td><td align=\"left\">$addr</td></tr>\n");

//  if ($user["id"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR)
//	{
?>
<tr>
	<td class=rowhead>Раздал</td>
	<td align=left><?=mksize($user["uploaded"])?></td>
</tr>
<tr>
	<td class=rowhead>Скачал</td>
	<td align=left><?=mksize($user["downloaded"])?></td>
</tr>
<?
if (get_user_class() >= UC_MODERATOR)
print("<tr><td class=\"rowhead\">Приглашений</td><td align=left><a href=\"invite.php?id=$id\">".$user["invites"]."</a></td></tr>");
if ($user["invitedby"] != 0) {
	$inviter = mysql_fetch_assoc(sql_query("SELECT username FROM users WHERE id = ".sqlesc($user["invitedby"])));
	print("<tr><td class=\"rowhead\">Пригласил</td><td align=\"left\"><a href=\"userdetails.php?id=$user[invitedby]\">$inviter[username]</a></td></tr>");
}
if ($user["downloaded"] > 0) {
	$sr = $user["uploaded"] / $user["downloaded"];
	$sr = floor($sr * 1000) / 1000;
	print("<tr><td class=\"rowhead\" style=\"vertical-align: middle\">Рейтинг</td><td align=\"left\" valign=\"center\" style=\"padding-top: 1px; padding-bottom: 0px\"><font color=\"" . get_ratio_color($sr) . "\">" . number_format($sr, 3) . "</font></td></tr>\n");
}
//}
if ($user["icq"] || $user["msn"] || $user["aim"] || $user["yahoo"] || $user["skype"])
{
	?>
<tr>
	<td class=rowhead><b>Связь</b></td>
	<td align=left><?
	if ($user["icq"])
	print("<img src=\"http://web.icq.com/whitepages/online?icq=".(int)$user[icq]."&amp;img=5\" alt=\"icq\" border=\"0\" /> ".(int) $user[icq]." <br />\n");
	if ($user["msn"])
	print("<img src=\"pic/contact/msn.gif\" alt=\"msn\" border=\"0\" /> ".makesafe($user[msn])."<br />\n");
	if ($user["aim"])
	print("<img src=\"pic/contact/aim.gif\" alt=\"aim\" border=\"0\" /> ".makesafe($user[aim])."<br />\n");
	if ($user["yahoo"])
	print("<img src=\"pic/contact/yahoo.gif\" alt=\"yahoo\" border=\"0\" /> ".makesafe($user[yahoo])."<br />\n");
	if ($user["skype"])
	print("<img src=\"pic/contact/skype.gif\" alt=\"skype\" border=\"0\" /> ".makesafe($user[skype])."<br />\n");
	if ($user["mirc"])
	print("<img src=\"pic/contact/mirc.gif\" alt=\"mirc\" border=\"0\" /> ".makesafe($user[mirc])."\n");
	?></td>
</tr>
	<?
}
if ($user["website"])
print("<tr><td class=\"rowhead\">Сайт</td><td align=\"left\">".makesafe($user[website])."</a></td></tr>\n");
//if ($user['donated'] > 0 && (get_user_class() >= UC_MODERATOR || $CURUSER["id"] == $user["id"]))
//  print("<tr><td class=rowhead>Donated</td><td align=left>$$user[donated]</td></tr>\n");
if ($user["avatar"])
print("<tr><td class=\"rowhead\">Аватар</td><td align=left><img src=\"" . $user["avatar"] . "\"></td></tr>\n");
print("<tr><td class=\"rowhead\">Класс</td><td align=\"left\"><b>" . get_user_class_color($user["class"], get_user_class_name($user["class"])) . ($user["title"] != "" ? " / <span style=\"color: purple;\">{$user["title"]}</span>" : "") . "</b></td></tr>\n");
print("<tr><td class=\"rowhead\">Пол</td><td align=\"left\">$gender</td></tr>\n");
//мод предупреждений
print("<tr><td class=\"rowhead\">Уровень<br />предупреждений</td><td align=\"left\">");
for($i = 0; $i < $user["num_warned"]; $i++)
{
	$img .= "<img src=\"pic/star_warned.gif\" alt=\"Уровень предупреждений\" title=\"Уровень предупреждений\">";
}
if (!$img)
$img = "Нет предупреждений";
print($img.((($CURUSER['id'] == $id) && ($CURUSER['num_warned'] != 0))?" <a href=\"mywarned.php\">Купить помилование за аплоад</a>":"")."</td></tr>\n");

if($user["birthday"]!='0000-00-00') {
	print("<tr><td class=\"rowhead\">Возраст</td><td align=\"left\">".AgeToStr($age)."</td></tr>\n");
	$birthday = date("d.m.Y", strtotime($birthday));
	print("<tr><td class=\"rowhead\">Дата Рождения</td><td align=\"left\">$birthday</td></tr>\n");

	$month_of_birth = substr($user["birthday"], 5, 2);
	$day_of_birth = substr($user["birthday"], 8, 2);
	for($i = 0; $i < count($zodiac); $i++) {
		if (($month_of_birth == substr($zodiac[$i][2], 3, 2)))  {
			if ($day_of_birth >= substr($zodiac[$i][2], 0, 2)) {
				$zodiac_img = $zodiac[$i][1];
				$zodiac_name = $zodiac[$i][0];
			}
			else {
				if ($i == 11) {
					$zodiac_img = $zodiac[0][1];
					$zodiac_name = $zodiac[0][0];
				}
				else {
					$zodiac_img = $zodiac[$i+1][1];
					$zodiac_name = $zodiac[$i+1][0];
				}
			}
		}

	}

	print("<tr><td class=\"rowhead\">Знак зодиака</td><td align=\"left\"><img src=\"pic/zodiac/" . $zodiac_img . "\" alt=\"" . $zodiac_name . "\" title=\"" . $zodiac_name . "\"></td></tr>\n");

}

print("<tr><td class=\"rowhead\">Комментариев к релизам</td>");
if ($torrentcomments)
print("<td align=\"left\"><a href=\"userhistory.php?action=viewcomments&type=torrents&id=$id\">$torrentcomments</a></td></tr>\n");
else
print("<td align=\"left\">$torrentcomments</td></tr>\n");

print("<tr><td class=\"rowhead\">Комментариев к новостям</td>");
if ($newscomments)
print("<td align=\"left\"><a href=\"userhistory.php?action=viewcomments&type=news&id=$id\">$newscomments</a></td></tr>\n");
else
print("<td align=\"left\">$newscomments</td></tr>\n");


if ($torrents)
print("<tr valign=\"top\"><td class=\"rowhead\">Залитые&nbsp;торренты</td><td align=\"left\"><div class=\"news-wrap\"><div class=\"news-head folded clickable\">Показать</div><div class=\"news-body\">$torrents</div></td></tr>\n");
if ($seeding)
print("<tr valign=\"top\"><td class=\"rowhead\">Сейчас&nbsp;раздает</td><td align=\"left\"><div class=\"news-wrap\"><div class=\"news-head folded clickable\">Показать</div><div class=\"news-body\">$seeding</div></td></tr>\n");
if ($leeching)
print("<tr valign=\"top\"><td class=\"rowhead\">Сейчас&nbsp;качает</td><td align=\"left\"><div class=\"news-wrap\"><div class=\"news-head folded clickable\">Показать</div><div class=\"news-body\">$leeching</div></td></tr>\n");
if ($completed)
print("<tr valign=\"top\"><td class=\"rowhead\">Скачаные&nbsp;торренты</td><td align=\"left\"><div class=\"news-wrap\"><div class=\"news-head folded clickable\">Показать</div><div class=\"news-body\">$completed</div></td></tr>\n");
if ($invitetree)
print("<tr valign=\"top\"><td colspan=\"2\"><div class=\"news-wrap\"><div class=\"news-head folded clickable\">Приглашенные</div><div class=\"news-body\">$invitetree</div></div></td></tr>\n");

if ($user["info"])
print("<tr valign=\"top\"><td align=\"left\" colspan=\"2\" class=\"text\" bgcolor=\"#F4F4F0\">" . format_comment($user["info"]) . "</td></tr>\n");

if ($CURUSER["id"] != $user["id"])
$showpmbutton = 1;
elseif ($user["acceptpms"] == "friends")
{
	$r = sql_query("SELECT id FROM friends WHERE userid = $user[id] AND friendid = $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
	$showpmbutton = (mysql_num_rows($r) == 1 ? 1 : 0);
}
if ($showpmbutton)
print("<tr><td align=right><b>Связь</b></td><td align=center><form method=\"get\" action=\"message.php\">
        <input type=\"hidden\" name=\"receiver\" value=" .$user["id"] . "> 
        <input type=\"hidden\" name=\"action\" value=\"sendmessage\"> 
        <input type=submit value=\"Послать ЛС\" style=\"height: 23px\"> 
        </form>".((get_user_class() >= UC_MODERATOR)?"<form method=\"get\" action=\"email-gateway.php\">
        <input type=\"hidden\" name=\"id\" value=\"" .$user["id"] . "\">
        <input type=submit value=\"Послать e-mail\" style=\"height: 23px\">
        </form>":'')."</td></tr>");

print("</table>\n");
print('</td><td>');

begin_frame();
$subres = sql_query("SELECT COUNT(*) FROM usercomments WHERE userid = $id");
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];

$limited = 10;

if (!$count) {

	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
	print("<div align=\"right\"><a href=userdetails.php?id=$id#comments class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("Комментариев нет. <a href=userdetails.php?id=$id#comments>Желаете добавить?</a>");
	print("</td></tr></table><br />");

}
else {
	list($pagertop, $pagerbottom, $limit) = browsepager($limited, $count, "userdetails.php?id=$id&", "#comments-table", array('lastpagedefault' => 1));

	$subres = sql_query("SELECT c.id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.gender, u.last_access, e.username AS editedbyname FROM usercomments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id WHERE userid = " .
                  "$id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = array();
	while ($subrow = mysql_fetch_array($subres))
	$allrows[] = $subrow;


	print("<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
	print("<tr><td class=\"colhead\" align=\"center\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
	print("<div align=\"right\"><a href=\"userdetails.php?id=$id#comments\" class=\"altlink_white\">{$tracker_lang['add_comment']}</a></div>");
	print("</td></tr>");

	print("<tr><td>");
	print($pagertop);
	print("</td></tr>");
	print("<tr><td>");
	commenttable($allrows,'usercomment');
	print("</td></tr>");
	print("<tr><td>");
	print($pagerbottom);
	print("</td></tr>");
	print("</table>");
}

if (!$ajax) {
	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>:: {$tracker_lang['add_comment']} к пользователю</b></td></tr>");
	print("<tr><td width=\"100%\" align=\"center\" >");
	//print("Ваше имя: ");
	//print("".$CURUSER['username']."<p>");
	print("<form name=comment method=\"post\" action=\"usercomment.php?action=add\">");
	print("<center><table border=\"0\"><tr><td class=\"clear\">");
	print("<div align=\"center\">". textbbcode("text","") ."</div>");
	print("</td></tr></table></center>");
	print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
	print("<input type=\"hidden\" name=\"uid\" value=\"$id\"/>");
	print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
	print("</td></tr></form></table>");
}
end_frame();

print('</td></tr></table>');
if (get_user_class() >= UC_MODERATOR && $user["class"] < get_user_class())
{
	begin_frame("Редактирование пользователя", true);
	print("<form method=\"post\" action=\"modtask.php\">\n");
	print("<input type=\"hidden\" name=\"action\" value=\"edituser\">\n");
	print("<input type=\"hidden\" name=\"userid\" value=\"$id\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"userdetails.php?id=$id\">\n");
	print("<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"rowhead\">Заголовок</td><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"title\" value=\"" . htmlspecialchars($user[title]) . "\"></td></tr>\n");
	print("<tr><td class=\"rowhead\">Удалить аватар</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"avatar\" value=\"1\"></td></tr>\n");
	// we do not want mods to be able to change user classes or amount donated...
	if ($CURUSER["class"] < UC_ADMINISTRATOR)
	print("<input type=\"hidden\" name=\"donor\" value=\"$user[donor]\">\n");
	else {
		print("<tr><td class=\"rowhead\">Донор</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"donor\" value=\"1\"" .($user["donor"] == "yes" ? " checked" : "").">Да <input type=\"radio\" name=\"donor\" value=\"0\"" .(!$user["donor"] ? " checked" : "").">Нет</td></tr>\n");
	}

	if (get_user_class() == UC_MODERATOR && $user["class"] > UC_VIP)
	print("<input type=\"hidden\" name=\"class\" value=\"$user[class]\">\n");
	else
	{
		print("<tr><td class=\"rowhead\">Класс</td><td colspan=\"2\" align=\"left\"><select name=\"class\">\n");
		if (get_user_class() == UC_SYSOP)
		$maxclass = UC_SYSOP;
		elseif (get_user_class() == UC_MODERATOR)
		$maxclass = UC_VIP;
		else
		$maxclass = get_user_class() - 1;
		for ($i = 0; $i <= $maxclass; ++$i)
		print("<option value=\"$i\"" . ($user["class"] == $i ? " selected" : "") . ">$prefix" . get_user_class_name($i) . "\n");
		print("</select></td></tr>\n");
	}
	print("<tr><td class=\"rowhead\">Сбросить день рождения</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"resetb\" value=\"1\">Да<input type=\"radio\" name=\"resetb\" value=\"0\" checked>Нет</td></tr>\n");
	$modcomment = htmlspecialchars($user["modcomment"]);
	$supportfor = htmlspecialchars($user["supportfor"]);
	print("<tr><td class=rowhead>Поддержка</td><td colspan=2 align=left><input type=radio name=support value=\"1\"" .($user["support"] ? " checked" : "").">Да <input type=radio name=support value=\"0\"" .(!$user["support"] ? " checked" : "").">Нет</td></tr>\n");
	print("<tr><td class=rowhead>Поддержка для:</td><td colspan=2 align=left><textarea cols=60 rows=6 name=supportfor>$supportfor</textarea></td></tr>\n");
	print("<tr><td class=rowhead>История пользователя</td><td colspan=2 align=left><textarea cols=60 rows=6".(get_user_class() < UC_SYSOP ? " readonly" : " name=modcomment").">$modcomment</textarea></td></tr>\n");
	print("<tr><td class=rowhead>Добавить заметку</td><td colspan=2 align=left><textarea cols=60 rows=3 name=modcomm></textarea></td></tr>\n");
	$warned = $user["warned"] == "yes";

	print("<tr><td class=\"rowhead\"" . (!$warned ? " rowspan=\"2\"": "") . ">Предупреждение</td>
 	<td align=\"left\" width=\"20%\">" .
	( $warned
	? "<input name=\"warned\" value=\"1\" type=\"radio\" checked>Да<input name=\"warned\" value=\"0\" type=\"radio\">Нет"
	: "Нет" ) ."</td>");

	if ($warned) {
		$warneduntil = $user['warneduntil'];
		if (!$warneduntil)
		print("<td align=\"center\">На неограниченый срок</td></tr>\n");
		else {
			print("<td align=\"center\">До ".mkprettytime($warneduntil));
			print(" (" . get_elapsed_time($warneduntil) . " осталось)</td></tr>\n");
		}
	} else {
		print("<td>Предупредить на <select name=\"warnlength\">\n");
		print("<option value=\"0\">------</option>\n");
		print("<option value=\"1\">1 неделю</option>\n");
		print("<option value=\"2\">2 недели</option>\n");
		print("<option value=\"4\">4 недели</option>\n");
		print("<option value=\"8\">8 недель</option>\n");
		print("<option value=\"255\">Неограничено</option>\n");
		print("</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Комментарий в ЛС:</td></tr>\n");
		print("<tr><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"warnpm\"></td></tr>");
	}
	print("<tr><td class=\"rowhead\" rowspan=\"2\">Включен</td><td colspan=\"2\" align=\"left\"><input name=\"enabled\" value=\"1\" type=\"radio\"" . ($enabled ? " checked" : "") . ">Да <input name=\"enabled\" value=\"0\" type=\"radio\"" . (!$enabled ? " checked" : "") . ">Нет</td></tr>\n");
	if ($enabled)
	print("<tr><td colspan=\"2\" align=\"left\">Причина отключения:&nbsp;<input type=\"text\" name=\"disreason\" size=\"60\" /></td></tr>");
	else
	print("<tr><td colspan=\"2\" align=\"left\">Причина включения:&nbsp;<input type=\"text\" name=\"enareason\" size=\"60\" /></td></tr>");
	?>
<script type="text/javascript">

function togglepic(bu, picid, formid)
{
    var pic = document.getElementById(picid);
    var form = document.getElementById(formid);
    
    if(pic.src == bu + "/pic/plus.gif")
    {
        pic.src = bu + "/pic/minus.gif";
        form.value = "minus";
    }else{
        pic.src = bu + "/pic/plus.gif";
        form.value = "plus";
    }
}

</script>
	<?
	print("<tr><td class=\"rowhead\">Изменить раздачу</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"uppic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','uppic','upchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountup\" size=\"10\" /><td>\n<select name=\"formatup\">\n<option value=\"mb\">MB</option>\n<option value=\"gb\">GB</option></select></td></tr>");
	print("<tr><td class=\"rowhead\">Изменить скачку</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"downpic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','downpic','downchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountdown\" size=\"10\" /><td>\n<select name=\"formatdown\">\n<option value=\"mb\">MB</option>\n<option value=\"gb\">GB</option></select></td></tr>");
	print("<tr><td class=\"rowhead\">Изменить бонусы</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"bonuspic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','bonuspic','bonuschange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountbonus\" size=\"10\" /><td>Сейчас бонусов у пользователя: {$user['bonus']}</td></tr>");
	print("<tr><td class=\"rowhead\">Сбросить passkey</td><td colspan=\"2\" align=\"left\"><input name=\"resetkey\" value=\"1\" type=\"checkbox\"></td></tr>\n");
	if ($CURUSER["class"] < UC_ADMINISTRATOR)
	print("<input type=\"hidden\" name=\"deluser\">");
	else
	print("<tr><td class=\"rowhead\">Удалить</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"deluser\"></td></tr>");
	print("</td></tr>");
	print("<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" class=\"btn\" value=\"ОК\"></td></tr>\n");
	print("</table>\n");
	print("<input type=\"hidden\" id=\"bonuschange\" name=\"bonuschange\" value=\"plus\"><input type=\"hidden\" id=\"upchange\" name=\"upchange\" value=\"plus\"><input type=\"hidden\" id=\"downchange\" name=\"downchange\" value=\"plus\">\n");
	print("</form>\n");
	end_frame();
}
end_main_frame();
stdfoot();
?>
