<?php
/**
 * Retrackers administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

INIT();

loggedinorreturn();
get_privilege('edit_dchubs');
httpauth();

if (!isset($_GET['action'])) {
	$REL_TPL->stdhead($REL_LANG->_('Direct Connect Hubs admincp'));
	print("<div algin=\"center\"><h1>{$REL_LANG->_('Direct Connect Hubs admincp')}</h1></div>");
	print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"".$REL_SEO->make_link('dchubsadmin','action','add')."\">{$REL_LANG->_('Add a DC hub')}</a></td></tr></table>");
	$rtarray = sql_query("SELECT * FROM dchubs ORDER BY sort ASC");
	print("<form name=\"saveids\" action=\"".$REL_SEO->make_link('dchubsadmin','action','saveids')."\" method=\"post\"><table width=\"100%\" border=\"1\"><tr><td align=\"center\" colspan=\"5\">{$REL_LANG->_('This is panel for Direct Connect Hubs administration, you can enable/disable DC feature <a href="%s">in main configuration</a>',$REL_SEO->make_link('configadmin'))}</td></tr><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$REL_LANG->say_by_key('order')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('announce_url')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('subnet_mask')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('edit_delete')}</td></tr>");
	while($rt = mysql_fetch_array($rtarray)) {
		print("<tr><td>".$rt['id']."</td><td><input type=\"text\" name=\"sort[".$rt['id']."]\" size=\"4\" value=\"".$rt['sort']."\"></td><td>{$rt['announce_url']}</td><td>{$rt['mask']}</td><td><a href=\"".$REL_SEO->make_link('dchubsadmin','action','edit','id',$rt['id'])."\">E</a> | <a onClick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}')\" href=\"".$REL_SEO->make_link('dchubsadmin','action','delete','id',$rt['id'])."\">D</a></td></tr>");
	}
	print("</table><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->say_by_key('save_order')}\"></form>");
	$REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'saveids') {

	if (is_array($_POST['sort'])) {

		foreach ($_POST['sort'] as $id => $s) {

			sql_query("UPDATE dchubs SET sort = ".(int)$s."  WHERE id = " . (int)$id);
		}
		safe_redirect($REL_SEO->make_link('dchubsadmin'));
		exit();
	}
	else bark("Missing form data");
}

elseif ($_GET['action'] == 'add') {
	$REL_TPL->stdhead($REL_LANG->say_by_key('add_retracker'));
	print("<form action=\"".$REL_SEO->make_link('dchubsadmin','action','saveadd')."\" name=\"savearray\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->say_by_key('announce_url')}</td></tr><tr><td><input type=\"text\" name=\"retracker\" size=\"80\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('subnet_mask')}</td></tr><tr><td><input type=\"text\" name=\"mask\" size=\"15\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('order')}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->_('Add a DC hub')}\"></td></tr></table></form>");
	$REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'delete') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
	sql_query("DELETE FROM dchubs WHERE id = ".(int) $_GET['id']);
	safe_redirect($REL_SEO->make_link('dchubsadmin'));
	exit();

}

elseif ($_GET['action'] == 'edit') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

	$rtarray = sql_query("SELECT * FROM dchubs WHERE id=".(int)$_GET['id']);
	list($id,$sort,$announce_url,$mask) = mysql_fetch_array($rtarray);

	$REL_TPL->stdhead($REL_LANG->say_by_key('editing_retracker'));
	print("<form name=\"save\" action=\"".$REL_SEO->make_link('dchubsadmin','action','saveedit')."\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->say_by_key('announce_url')}</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"text\" name=\"retracker\" size=\"80\" value=\"".$announce_url."\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('subnet_mask')}</td></tr><tr><td><input type=\"text\" name=\"mask\" size=\"15\" value=\"$mask\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('order')}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\" value=\"".$sort."\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->say_by_key('edit')}\"></td></tr></table></form>");
	$REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
	sql_query("UPDATE dchubs SET announce_url = ".sqlesc(htmlspecialchars((string)$_POST['retracker'])).", mask = ".sqlesc(htmlspecialchars((string)$_POST['mask'])).", sort = ".intval($_POST['sort'])." WHERE id = ".intval($_POST['id']));
	safe_redirect($REL_SEO->make_link('dchubsadmin'));
	exit();
}
elseif ($_GET['action'] == 'saveadd') {

	sql_query("INSERT INTO dchubs (announce_url,sort,mask) VALUES (".sqlesc(htmlspecialchars((string)$_POST['retracker'])).", ".intval($_POST['sort']).", ".sqlesc(htmlspecialchars((string)$_POST['mask'])).")");
	safe_redirect($REL_SEO->make_link('dchubsadmin'));
	exit();
}


