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

function bark($msg) {
	stderr($REL_LANG->say_by_key('error'), $msg);
}

INIT();

loggedinorreturn();
get_privilege('edit_retrackers');
httpauth();

if (!isset($_GET['action'])) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('panel_name'));
	print("<div algin=\"center\"><h1>{$REL_LANG->say_by_key('panel_name')}</h1></div>");
	print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"".$REL_SEO->make_link('retrackeradmin','action','add')."\">{$REL_LANG->say_by_key('add_retracker')}</a></td><td><a href=\"".$REL_SEO->make_link('retrackeradmin','action','truncate')."\" onclick=\"return confirm('{$REL_LANG->_('Are you sure?')}');\">{$REL_LANG->_('Clear all retrackers')}</a></td></tr></table>");
	$rtarray = sql_query("SELECT * FROM retrackers ORDER BY sort ASC");
	print("<form name=\"saveids\" action=\"".$REL_SEO->make_link('retrackeradmin','action','saveids')."\" method=\"post\"><table width=\"100%\" border=\"1\"><tr><td align=\"center\" colspan=\"5\">{$REL_LANG->say_by_key('panel_notice')}</td></tr><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$REL_LANG->say_by_key('order')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('announce_url')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('subnet_mask')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('edit_delete')}</td></tr>");
	while($rt = mysql_fetch_array($rtarray)) {
		print("<tr><td>".$rt['id']."</td><td><input type=\"text\" name=\"sort[".$rt['id']."]\" size=\"4\" value=\"".$rt['sort']."\"></td><td>{$rt['announce_url']}</td><td>".str_replace(',',"<br/>",$rt['mask'])."</td><td><a href=\"".$REL_SEO->make_link('retrackeradmin','action','edit','id',$rt['id'])."\">E</a> | <a onClick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}')\" href=\"".$REL_SEO->make_link('retrackeradmin','action','delete','id',$rt['id'])."\">D</a></td></tr>");
	}
	print("</table><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->say_by_key('save_order')}\"></form>");
	$REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'saveids') {

	if (is_array($_POST['sort'])) {

		foreach ($_POST['sort'] as $id => $s) {

			sql_query("UPDATE retrackers SET sort = ".(int)$s."  WHERE id = " . (int)$id);
		}
		safe_redirect($REL_SEO->make_link('retrackeradmin'));
		exit();
	}
	else bark("Missing form data");
}

elseif ($_GET['action'] == 'add') {
	$REL_TPL->stdhead($REL_LANG->say_by_key('add_retracker'));
	print("<form action=\"".$REL_SEO->make_link('retrackeradmin','action','saveadd')."\" name=\"savearray\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->say_by_key('announce_url')}</td></tr><tr><td><input type=\"text\" name=\"retracker\" size=\"80\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('subnet_mask')}</td></tr><tr><td><textarea name=\"mask\" rows=\"10\" cols=\"20\"></textarea>{$REL_LANG->_('One CIDR mask per line')}</td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('order')}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->say_by_key('add_retracker')}\"></td></tr></table></form>");
	$REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'truncate') {
	sql_query("TRUNCATE TABLE retrackers");
	safe_redirect($REL_SEO->make_link('retrackeradmin'));
	exit();
}

elseif ($_GET['action'] == 'delete') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
	sql_query("DELETE FROM retrackers WHERE id = ".(int) $_GET['id']);
	safe_redirect($REL_SEO->make_link('retrackeradmin'));
	exit();

}

elseif ($_GET['action'] == 'edit') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

	$rtarray = sql_query("SELECT * FROM retrackers WHERE id=".(int)$_GET['id']);
	list($id,$sort,$announce_url,$mask) = mysql_fetch_array($rtarray);

	$REL_TPL->stdhead($REL_LANG->say_by_key('editing_retracker'));
	print("<form name=\"save\" action=\"".$REL_SEO->make_link('retrackeradmin','action','saveedit')."\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->say_by_key('announce_url')}</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"text\" name=\"retracker\" size=\"80\" value=\"".$announce_url."\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('subnet_mask')}</td></tr><tr><td><textarea name=\"mask\" rows=\"10\" cols=\"20\">".str_replace(',',"\n",$mask)."</textarea>{$REL_LANG->_('One CDIR mask per line')}</td></tr><tr><td class=\"colhead\">{$REL_LANG->say_by_key('order')}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\" value=\"".$sort."\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->say_by_key('edit')}\"></td></tr></table></form>");
	$REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
	sql_query("UPDATE retrackers SET announce_url = ".sqlesc(htmlspecialchars((string)$_POST['retracker'])).", mask = ".sqlesc(str_replace("\n",',',htmlspecialchars((string)$_POST['mask']))).", sort = ".intval($_POST['sort'])." WHERE id = ".intval($_POST['id']));
	safe_redirect($REL_SEO->make_link('retrackeradmin'));
	exit();
}
elseif ($_GET['action'] == 'saveadd') {

	sql_query("INSERT INTO retrackers (announce_url,sort,mask) VALUES (".sqlesc(htmlspecialchars((string)$_POST['retracker'])).", ".intval($_POST['sort']).", ".sqlesc(str_replace("\n",',',htmlspecialchars((string)$_POST['mask']))).")");
	safe_redirect($REL_SEO->make_link('retrackeradmin'));
	exit();
}


