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


if (get_user_class() < UC_ADMINISTRATOR) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

httpauth();

getlang('reltemplatesadmin');

stdhead($tracker_lang['page_title']);

begin_frame($tracker_lang['menu_header']);

$action = (string)$_GET['action'];


if (!$action) {
	print('<table width="100%">');
	print('<tr><td class="colhead">ID</td><td class="colhead">'.$tracker_lang['name'].'</td><td class="colhead">'.$tracker_lang['content'].'</td><td class="colhead">'.$tracker_lang['actions'].'</td></tr>');
	$reltemplatesq = sql_query("SELECT * FROM reltemplates");
	while ($reltemplate = mysql_fetch_assoc($reltemplatesq))
	print ("<tr><td>{$reltemplate['id']}</td><td>{$reltemplate['name']}</td><td>".format_comment($reltemplate['content'])."</td><td><a href=\"reltemplatesadmin.php?action=edit&amp;id={$reltemplate['id']}\">{$tracker_lang['edit']}</a><br /><a onclick=\"return confirm('{$tracker_lang['are_you_sure']}');\" href=\"reltemplatesadmin.php?action=delete&amp;id={$reltemplate['id']}\">{$tracker_lang['del']}</a></td></tr>");
	print('</table>');
}
elseif ($action=='add') {
	print('<form action="reltemplatesadmin.php?action=saveadd" method="POST"><table width="100%">');
	tr($tracker_lang['name'],'<input type="text" name="tname" size="100"/>',1);
	print('<tr><td align="right"><b>'.$tracker_lang['content'].'</b></td><td>'.textbbcode("tcontent").'</td></tr>');

	tr('','<input type="submit" value="'.$tracker_lang['add'].'"/>',1);
	print('</table></form>');
} elseif ($action=='saveadd') {
	if (!$_POST['tname'] || !$_POST['tcontent']) {stdmsg($tracker_lang['error'],$tracker_lang['some_fields_blank'],'error'); stdfoot(); die(); }

	sql_query("INSERT INTO reltemplates (name,content) VALUES (".sqlesc(htmlspecialchars($_POST['tname'])).",".sqlesc($_POST['tcontent']).")");
	stdmsg($tracker_lang['success'],$tracker_lang['editing_succ']);
}

elseif ($action=='edit') {
	$id=(int)$_GET['id'];
	if (!$id) {stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	$res = sql_query("SELECT name,content FROM reltemplates WHERE id=$id");
	$row = mysql_fetch_assoc($res);

	if (!$row){stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	print('<form action="reltemplatesadmin.php?action=saveedit&amp;id='.$id.'" method="POST"><table width="100%">');
	tr($tracker_lang['name'],'<input type="text" name="tname" size="100" value="'.$row['name'].'"/>',1);

	print('<tr><td align="right"><b>'.$tracker_lang['content'].'</b></td><td>'.textbbcode("tcontent",format_comment($row['content'])).'</td></tr>');
	tr('','<input type="submit" value="'.$tracker_lang['edit'].'"/>',1);
	print('</table></form>');
} elseif ($action=='saveedit') {
	$id=(int)$_GET['id'];
	if (!$id) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	if (!$_POST['tname'] || !$_POST['tcontent']) {stdmsg($tracker_lang['error'],$tracker_lang['some_fields_blank'],'error'); stdfoot(); die(); }

	sql_query("UPDATE reltemplates SET name=".sqlesc(htmlspecialchars($_POST['tname'])).", content=".sqlesc($_POST['tcontent'])." WHERE id=$id");
	stdmsg($tracker_lang['success'],$tracker_lang['editing_succ']);
} elseif ($action=='delete') {
	$id=(int)$_GET['id'];
	if (!$id) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }
	sql_query("DELETE FROM reltemplates WHERE id=$id");
	stdmsg($tracker_lang['success'],$tracker_lang['editing_succ']);
}
end_frame();
stdfoot();
?>