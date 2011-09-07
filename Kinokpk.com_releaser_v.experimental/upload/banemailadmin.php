<?php
/**
 * Administrative tool to ban emails
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();
httpauth();

get_privilege('access_to_ban_emails');



if (isset($_GET['remove']) && is_valid_id($_GET['remove']))
{
	$remove = (int) $_GET['remove'];

	$REL_DB->query("DELETE FROM bannedemails WHERE id = '$remove'") or sqlerr(__FILE__, __LINE__);
	write_log($REL_LANG->_('Ban %s was removed by %s',$remove,make_user_link()),'emailbans');
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$email = trim($_POST["email"]);
	$comment = trim($_POST["comment"]);
	if (!$email || !$comment)
	$REL_TPL->stderr($REL_LANG->_("Error"), $REL_LANG->_("Missing form data"));
	$REL_DB->query("INSERT INTO bannedemails (added, addedby, comment, email) VALUES(".sqlesc(time()).", $CURUSER[id], ".sqlesc($comment).", ".sqlesc($email).")") or (mysql_errno() == 1062 ? stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("This email was already banned")) : sqlerr(__FILE__, __LINE__));
	safe_redirect(makesafe($_SERVER[REQUEST_URI]));
	die;
}

$res = $REL_DB->query("SELECT * FROM bannedemails ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);

$REL_TPL->stdhead($REL_LANG->_("Ban emails admin panel"));

print("<h1>{$REL_LANG->_("List of bans")}</h1>\n");

if (mysql_num_rows($res) == 0)
print("<p align=center><b>{$REL_LANG->_("Empty")}</b></p>\n");
else
{
	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead>{$REL_LANG->_("Added")}</td><td class=colhead align=left>Email</td>".
        "<td class=colhead align=left>{$REL_LANG->_("Whom")}</td><td class=colhead align=left>{$REL_LANG->_("Comment")}</td><td class=colhead>{$REL_LANG->_('Remove')}</td></tr>\n");

	while ($arr = mysql_fetch_assoc($res))
	{
		$user = get_user($arr['addedby']);
		$userlink = make_user_link($user);
		print("<tr><td>".mkprettytime($arr[added])."</td><td align=left>$arr[email]</td><td align=left>$userlink</td><td align=left>".format_comment($arr[comment])."</td><td><a href=\"".$REL_SEO->make_link('banemailadmin','remove',$arr['id'])."\">{$REL_LANG->_('Remove')}</a></td></tr>\n");
	}
	print("</table>\n");
}

print("<h2>{$REL_LANG->_('Make ban')}</h2>\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<form method=\"post\" action=\"".$REL_SEO->make_link('banemailadmin')."\">\n");
print("<tr><td class=rowhead>Email</td><td><input type=\"text\" name=\"email\" size=\"40\"></td>\n");
print("<tr><td class=rowhead>{$REL_LANG->_('Comment')}</td><td><input type=\"text\" name=\"comment\" size=\"40\"></td>\n");
print("<tr><td colspan=2>{$REL_LANG->_('Use *@email willcard to ban whole domain')}</td></tr>\n");
print("<tr><td colspan=2><input type=\"submit\" value=\"{$REL_LANG->_('Make ban')}\" class=\"btn\"></td></tr>\n");
print("</form>\n</table>\n");

$REL_TPL->stdfoot();

?>