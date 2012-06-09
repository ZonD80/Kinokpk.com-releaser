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

INIT();
loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] == 'POST')
$action = $_POST["action"];
else
$action = $_GET["action"];

if ($action == 'new') {
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$tname = htmlspecialchars($_POST["tname"]);
		if (!$tname)
		$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('You forgot to fill name'));
		$reason = unesc($_POST["reason"]);
		if (!$reason)
		$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('You forgot to fill reason of prohibition'));
		$tname = sqlesc($tname);
		$reason = sqlesc($reason);
		$REL_DB->query("INSERT INTO censoredtorrents (name,reason) VALUES($tname,$reason)");
		$id = mysql_insert_id();

		$REL_CACHE->clearGroupCache("block-cen");

		safe_redirect($REL_SEO->make_link('censoredtorrents','id',$id));
		die;
	}
	$REL_TPL->stdhead($REL_LANG->_('Release prohibition'));

	print("<h1>{$REL_LANG->_('Release prohibition')}</h1><p>{$REL_LANG->_('Release prohibition often happens due to copyrighter request')}</p>\n<br />\n");

	print("<form method=post action=\"".$REL_SEO->make_link('censoredtorrents')."\" name=reason>\n");
	print("<table border=1 cellspacing=0 cellpadding=5 width=100%>\n");
	print("<tr><td class=colhead align=left colspan=2>{$REL_LANG->_('Fill requested fields')}</a></td><tr>\n");
	print("<tr><td align=center><b>{$REL_LANG->_('Name')}: </b><br /><input type=text size=80 name=tname></td>");
	print("<tr><td align=center colspan=2><b>{$REL_LANG->_('Reason')}: </b><br />\n");
	print textbbcode("reason");
	print ("<input type=hidden name=action value=new>");
	print("<tr><td align=center colspan=2><input type=submit value=\"{$REL_LANG->_('Go')}\">\n");
	print("</form>\n");
	print("</table>\n");
	$REL_TPL->stdfoot();
	die;
}

if (!is_valid_id($_GET['id'])) 		$REL_TPL->stderr ($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
$id = (int) $_GET["id"];

$res = $REL_DB->query("SELECT * FROM censoredtorrents WHERE id = $id");
$num = mysql_fetch_array($res);

$s = $num["name"];

$REL_TPL->stdhead($REL_LANG->_('Details of prohibition of %s',$s));

print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" colspan=\"2\">{$REL_LANG->_('Details of prohibition of %s',$s)}</td></tr>");
print("<tr><td align=center>{$REL_LANG->_('Name')}:</td><td width=90% align=left>$num[name]</td></tr>");
print("<tr><td align=center>{$REL_LANG->_('Reason')}:</td><td width=90% align=left>" . format_comment($num["reason"]) . "</td></tr>");

if (get_privilege('is_moderator',false) || $CURUSER["id"] == $num["userid"])
print("<tr><td align=left>{$REL_LANG->_('Options')}</td><td width=50% align=left><a href=\"".$REL_SEO->make_link('viewcensoredtorrents','delt[]',$id)."\">".$REL_LANG->say_by_key('delete')."</a> | <a href=\"".$REL_SEO->make_link('viewcensoredtorrents')."\">{$REL_LANG->_('View all prohibitions')}</a></td></tr>");
print("</table>");

$REL_TPL->stdfoot();

?>