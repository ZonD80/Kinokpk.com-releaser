<?php
/**
 * Bans administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");

INIT();
loggedinorreturn();

get_privilege('bans_admin');

httpauth();



if (is_valid_id($_GET['remove']))
{
	$remove = (int) $_GET['remove'];
	sql_query("DELETE FROM bans WHERE id=$remove") or sqlerr(__FILE__, __LINE__);
	write_log("Бан номер '$remove' был снят пользователем $CURUSER[username]","bans");

	$REL_CACHE->clearGroupCache("bans");
	safe_redirect($REL_SEO->make_link('bans'),0);
	die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$mask = trim($_POST['mask']);
	$descr = trim($_POST['descr']);
	if (!$mask)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('missing_form_data'));
	$mask = sqlesc(htmlspecialchars($mask));
	$descr = sqlesc(htmlspecialchars($descr));
	$userid = $CURUSER['id'];
	$added = time();
	sql_query("INSERT INTO bans (mask,descr,user,added) VALUES($mask,$descr,$userid,$added)") or sqlerr(__FILE__, __LINE__);
	write_log("Маска '$mask' была забанена пользователем $CURUSER[username]","bans");

	$REL_CACHE->clearGroupCache("bans");
	safe_redirect($REL_SEO->make_link('bans'),0);
	die;
}

$res = sql_query("SELECT bans.*, users.username, users.class, users.warned, users.donor, users.enabled FROM bans LEFT JOIN users ON bans.user = users.id ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);

$REL_TPL->stdhead("Баны по IP");

if (mysql_num_rows($res) == 0)
print("<p align=\"center\"><b>".$REL_LANG->say_by_key('nothing_found')."</b></p>\n");

else
{
	//print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print('<table width="100%" border="1">');
	print("<h1>Баны по IP</h1>\n");
	print("<tr><td class=\"colhead\" align=\"center\">Добавлен</td><td class=\"colhead\" align=\"center\">IP-адрес</td><td class=\"colhead\" align=\"center\">Причина</td><td class=\"colhead\" align=\"center\">Забанен</td><td class=\"colhead\" align=\"center\">Управление</td></tr>\n");

	while ($arr = mysql_fetch_assoc($res))
	{
		$user = $arr;
		$user['id'] = $user['user'];
		print("<tr><td  class=\"row1\" align=\"center\">".mkprettytime($arr['added'])."</td>".
	  "<td  class=\"row1\" align=\"center\">$arr[mask]</td>".
	  "<td  class=\"row1\" align=\"center\">$arr[descr]</td>".
	  "<td  class=\"row1\" align=\"center\">".make_user_link($user)."</td>".
 	    "<td  class=\"row1\" align=\"center\"><a href=\"".$REL_SEO->make_link('bans','remove',$arr['id'])."\">D</a></td></tr>\n");
	}
	print('</table>');
}

print("<br />\n");
print("<form method=\"post\" action=\"".$REL_SEO->make_link('bans')."\">\n");
print('<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">');
print("<tr><td class=\"colhead\" colspan=\"2\">Добавить бан</td></tr>");
print("<tr><td class=\"rowhead\">Маска</td><td class=\"row1\"><input type=\"text\" name=\"mask\" size=\"40\"/></td></tr>\n");
print("<tr><td class=\"rowhead\">Причина</td><td class=\"row1\"><input type=\"text\" name=\"descr\" size=\"40\"/></td></tr>\n");
print("<tr><td class=\"row1\" align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"Добавить\" class=\"btn\"/></td></tr>\n");
print('</table>');
print("</form>\n");

$REL_TPL->stdfoot();

?>