<?php
/**
 * Release templates administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();

loggedinorreturn();
get_privilege('edit_release_templates');
httpauth();



$REL_TPL->stdhead($REL_LANG->say_by_key('page_title'));

$REL_TPL->begin_frame($REL_LANG->say_by_key('menu_header'));

$action = (string)$_GET['action'];


if (!$action) {
	print('<table width="100%">');
	print('<tr><td class="colhead">ID</td><td class="colhead">'.$REL_LANG->say_by_key('name').'</td><td class="colhead">'.$REL_LANG->say_by_key('content').'</td><td class="colhead">'.$REL_LANG->say_by_key('actions').'</td></tr>');
	$reltemplatesq = sql_query("SELECT * FROM reltemplates");
	while ($reltemplate = mysql_fetch_assoc($reltemplatesq))
	print ("<tr><td>{$reltemplate['id']}</td><td>{$reltemplate['name']}</td><td>".format_comment($reltemplate['content'])."</td><td><a href=\"".$REL_SEO->make_link('reltemplatesadmin','action','edit','id',$reltemplate['id'])."\">{$REL_LANG->say_by_key('edit')}</a><br /><a onclick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}');\" href=\"".$REL_SEO->make_link('reltemplatesadmin','action','delete','id',$reltemplate['id'])."\">{$REL_LANG->say_by_key('del')}</a></td></tr>");
	print('</table>');
}
elseif ($action=='add') {
	print('<form action="'.$REL_SEO->make_link('reltemplatesadmin','action','saveadd').'" method="POST"><table width="100%">');
	tr($REL_LANG->say_by_key('name'),'<input type="text" name="tname" size="100"/>',1);
	print('<tr><td align="right"><b>'.$REL_LANG->say_by_key('content').'</b></td><td>'.textbbcode("tcontent").'</td></tr>');

	tr('','<input type="submit" value="'.$REL_LANG->say_by_key('add').'"/>',1);
	print('</table></form>');
} elseif ($action=='saveadd') {
	if (!$_POST['tname'] || !$_POST['tcontent']) {stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('some_fields_blank'),'error'); $REL_TPL->stdfoot(); die(); }

	sql_query("INSERT INTO reltemplates (name,content) VALUES (".sqlesc(htmlspecialchars($_POST['tname'])).",".sqlesc($_POST['tcontent']).")");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('editing_succ'));
}

elseif ($action=='edit') {
	$id=(int)$_GET['id'];
	if (!$id) {stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	$res = sql_query("SELECT name,content FROM reltemplates WHERE id=$id");
	$row = mysql_fetch_assoc($res);

	if (!$row){stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	print('<form action="'.$REL_SEO->make_link('reltemplatesadmin','action','saveedit','id',$id).'" method="POST"><table width="100%">');
	tr($REL_LANG->say_by_key('name'),'<input type="text" name="tname" size="100" value="'.$row['name'].'"/>',1);

	print('<tr><td align="right"><b>'.$REL_LANG->say_by_key('content').'</b></td><td>'.textbbcode("tcontent",format_comment($row['content'])).'</td></tr>');
	tr('','<input type="submit" value="'.$REL_LANG->say_by_key('edit').'"/>',1);
	print('</table></form>');
} elseif ($action=='saveedit') {
	$id=(int)$_GET['id'];
	if (!$id) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	if (!$_POST['tname'] || !$_POST['tcontent']) {stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('some_fields_blank'),'error'); $REL_TPL->stdfoot(); die(); }

	sql_query("UPDATE reltemplates SET name=".sqlesc(htmlspecialchars($_POST['tname'])).", content=".sqlesc($_POST['tcontent'])." WHERE id=$id");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('editing_succ'));
} elseif ($action=='delete') {
	$id=(int)$_GET['id'];
	if (!$id) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }
	sql_query("DELETE FROM reltemplates WHERE id=$id");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('editing_succ'));
}
$REL_TPL->end_frame();
$REL_TPL->stdfoot();
?>