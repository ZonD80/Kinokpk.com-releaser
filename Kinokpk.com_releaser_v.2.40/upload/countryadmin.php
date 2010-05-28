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

require_once("include/bittorrent.php");

dbconn();
getlang('countryadmin');
loggedinorreturn();
gzip();
httpauth();
if (get_user_class() < UC_SYSOP) {
	stderr($tracker_lang['error'],$tracker_lang['access_denied']);
}
stdhead($tracker_lang['country_admin']);

print("<h1>{$tracker_lang['country_and_flags']}</h1>\n");
print("<br/><a href=\"countryadmin.php?add\">{$tracker_lang['add_new_country']}</a><br/>");
print("<table width=70% border=1 cellspacing=0 cellpadding=2><tr><td align=center>\n");

///////////////////// D E L E T E  C O U N T R Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_GET['delid'])) {
	if (!is_valid_id($_GET['delid'])) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	$delid = (int) $_GET['delid'];

	sql_query("DELETE FROM countries WHERE id=" .sqlesc($delid) . " LIMIT 1");
	stdmsg($tracker_lang['success'],$tracker_lang['country_success_delete']);
	print "</td></tr></table>";
	stdfoot();
	die();
}

///////////////////// E  D  I  T  A  C  O  U  N  T  R  Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\
elseif (isset($_GET['edited'])) {
	if (!is_valid_id($_GET['id'])) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	$country_name = sqlesc($_POST['country_name']);
	$country_id = sqlesc($_GET['id']);
	$country_flag = sqlesc($_POST['country_flag']);
	sql_query("UPDATE countries SET
name = $country_name,
flagpic = $country_flag WHERE id=$country_id");
	stdmsg($tracker_lang['success'],$tracker_lang['country_success_edit']);
	print "</td></tr></table>";
	stdfoot();
	die();
}

elseif (isset($_GET['editid'])) {
	if (!is_valid_id($_GET['editid'])) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }
	$id = (int) $_GET['editid'];
	$res = sql_query("SELECT * FROM countries WHERE id=$id");
	$row = mysql_fetch_array($res);

	print("<form name='form1' method='post' action='countryadmin.php?edited&id=$id'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<div align='center'>{$tracker_lang['you_edit']}<strong>{$row['name']}</strong></div>");
	print("<br/>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$tracker_lang['country']}:</td><td align='left'><input type='text' size=60 name='country_name' value='{$row['name']}'></td></tr>");
	print("<tr><td>{$tracker_lang['flag']}:</td><td align='left'><input type='text' size=60 name='country_flag' value='{$row['flagpic']}'></td></tr>");
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$tracker_lang['edit']}'></div></td></tr>");
	print("</table></form>");
	end_frame();
	stdfoot();
	die();
}


if(isset($_GET['added'])) {
	$country_name = htmlspecialchars($_POST['country_name']);
	$country_flag = htmlspecialchars($_POST['country_flag']);
	sql_query("INSERT INTO countries (name,flagpic) VALUES (".sqlesc($country_name).",".sqlesc($country_flagpic).")");
	stdmsg($tracker_lang['success'],$tracker_lang['add_new_country']);
	print "</td></tr></table>";
	stdfoot();
	die();
}

if(isset($_GET['add'])) {
	print("<strong>{$tracker_lang['add_new_country']}</strong>");
	print("<br/>");
	print("<br/>");
	print("<form name='form1' method='POST' action='countryadmin.php?added'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$tracker_lang['name']}: </td><td align='right'><input type='text' size=50 name='country_name'></td></tr>");
	print("<tr><td>{$tracker_lang['flag']}: </td><td align='right'><input type='text' size=50 name='country_flag'></td></tr>");
	print("<tr><td></td><td><div align='right'><input type='submit' value='{$tracker_lang['create_country']}'></div></td></tr>");
	print("</table>");
	print("</form>");
}
///////////////////// E X I S T I N G  C O  U  N  T  R  I E S  \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$res = sql_query("SELECT COUNT(*) FROM countries") or die(mysql_error());
$row = mysql_fetch_array($res);

print($pagertop);
print("<table width=\"100%\" class=main cellspacing=0 cellpadding=5>");
print("<td>ID</td><td>{$tracker_lang['name']}</td><td>{$tracker_lang['flag']}</td><td>{$tracker_lang['edit']}</td><td>{$tracker_lang['delete']}</td>");
$sql = sql_query("SELECT * FROM countries ORDER BY name ASC $limit");

while ($row = mysql_fetch_array($sql)) {
	$id = $row['id'];
	$name = $row['name'];
	$country_city = "<img src=\"pic/flag/{$row[flagpic]}\" alt=\"$name\" style='margin-left: 8pt'>\n";

	print("<tr><td><strong>$id</strong></td><td>$name</td><td>$country_city</td><td><a href='countryadmin.php?editid=$id'><div align='center'><img src='pic/multipage.gif' border='0' class='special' /></a></div></td> <td><div align='center'><a onclick=\"return confirm('{$tracker_lang['confirmation_delete']}');\" href='countryadmin.php?delid=$id'><img src='pic/warned2.gif' border='0' class='special' align='center' /></a></div></td></tr>");
}
print("</table></table>");
stdfoot();
?>