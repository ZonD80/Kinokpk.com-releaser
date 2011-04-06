<?php
/**
 * User profile countries administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();

loggedinorreturn();

get_privilege('edit_countries');
httpauth();

$REL_TPL->stdhead($REL_LANG->say_by_key('country_admin'));

print("<h1>{$REL_LANG->say_by_key('country_and_flags')}</h1>\n");
print("<br /><a href=\"".$REL_SEO->make_link('countryadmin','add','')."\">{$REL_LANG->say_by_key('add_new_country')}</a><br />");
print("<table width=70% border=1 cellspacing=0 cellpadding=2><tr><td align=center>\n");

///////////////////// D E L E T E  C O U N T R Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_GET['delid'])) {
	if (!is_valid_id($_GET['delid'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	$delid = (int) $_GET['delid'];

	sql_query("DELETE FROM countries WHERE id=" .sqlesc($delid) . " LIMIT 1");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('country_success_delete'));
	print "</td></tr></table>";
	$REL_TPL->stdfoot();
	die();
}

///////////////////// E  D  I  T  A  C  O  U  N  T  R  Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\
elseif (isset($_GET['edited'])) {
	if (!is_valid_id($_GET['id'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	$country_name = sqlesc(htmlspecialchars((string)$_POST['country_name']));
	$country_id = (int)$_GET['id'];
	$country_flag = sqlesc(htmlspecialchars((string)$_POST['country_flag']));
	sql_query("UPDATE countries SET
name = $country_name,
flagpic = $country_flag WHERE id=$country_id");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('country_success_edit'));
	print "</td></tr></table>";
	$REL_TPL->stdfoot();
	die();
}

elseif (isset($_GET['editid'])) {
	if (!is_valid_id($_GET['editid'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }
	$id = (int) $_GET['editid'];
	$res = sql_query("SELECT * FROM countries WHERE id=$id");
	$row = mysql_fetch_array($res);

	print("<form name='form1' method='post' action='".$REL_SEO->make_link('countryadmin','edited','','id',$id)."'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<div align='center'>{$REL_LANG->say_by_key('you_edit')}<strong>{$row['name']}</strong></div>");
	print("<br />");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$REL_LANG->say_by_key('country')}:</td><td align='left'><input type='text' size=60 name='country_name' value='{$row['name']}'></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('flag')}:</td><td align='left'><input type='text' size=60 name='country_flag' value='{$row['flagpic']}'></td></tr>");
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$REL_LANG->say_by_key('edit')}'></div></td></tr>");
	print("</table></form>");
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}


if(isset($_GET['added'])) {
	$country_name = htmlspecialchars((string)$_POST['country_name']);
	$country_flag = htmlspecialchars((string)$_POST['country_flag']);
	sql_query("INSERT INTO countries (name,flagpic) VALUES (".sqlesc($country_name).",".sqlesc($country_flagpic).")");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('add_new_country'));
	print "</td></tr></table>";
	$REL_TPL->stdfoot();
	die();
}

if(isset($_GET['add'])) {
	print("<strong>{$REL_LANG->say_by_key('add_new_country')}</strong>");
	print("<br />");
	print("<br />");
	print("<form name='form1' method='POST' action='".$REL_SEO->make_link('countryadmin','added')."'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$REL_LANG->say_by_key('name')}: </td><td align='right'><input type='text' size=50 name='country_name'></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('flag')}: </td><td align='right'><input type='text' size=50 name='country_flag'></td></tr>");
	print("<tr><td></td><td><div align='right'><input type='submit' value='{$REL_LANG->say_by_key('create_country')}'></div></td></tr>");
	print("</table>");
	print("</form>");
}
///////////////////// E X I S T I N G  C O  U  N  T  R  I E S  \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$res = sql_query("SELECT SUM(1) FROM countries") or die(mysql_error());
$row = mysql_fetch_array($res);

print("<table width=\"100%\" class=main cellspacing=0 cellpadding=5>");
print("<td>ID</td><td>{$REL_LANG->say_by_key('name')}</td><td>{$REL_LANG->say_by_key('flag')}</td><td>{$REL_LANG->say_by_key('edit')}</td><td>{$REL_LANG->say_by_key('delete')}</td>");
$sql = sql_query("SELECT * FROM countries ORDER BY name ASC $limit");

while ($row = mysql_fetch_array($sql)) {
	$id = $row['id'];
	$name = $row['name'];
	$country_city = "<img src=\"pic/flag/{$row[flagpic]}\" alt=\"$name\" style='margin-left: 8pt'>\n";

	print("<tr><td><strong>$id</strong></td><td>$name</td><td>$country_city</td><td><a href='".$REL_SEO->make_link('countryadmin','editid',$id)."'><div align='center'><img src='pic/multipage.gif' border='0' class='special' /></a></div></td> <td><div align='center'><a onclick=\"return confirm('{$REL_LANG->say_by_key('confirmation_delete')}');\" href='".$REL_SEO->make_link('countryadmin','delid',$id)."'><img src='pic/warned2.gif' border='0' class='special' align='center' /></a></div></td></tr>");
}
print("</table></table>");
$REL_TPL->stdfoot();
?>