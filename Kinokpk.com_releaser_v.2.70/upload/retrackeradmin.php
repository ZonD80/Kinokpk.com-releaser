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

require_once "include/bittorrent.php";

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();
getlang('retrackeradmin');
loggedinorreturn();
gzip();
httpauth();

if (get_user_class() < UC_SYSOP) bark("Access denied. You're not SYSOP.");
if (!isset($_GET['action'])) {
	stdhead($tracker_lang['panel_name']);
	print("<div algin=\"center\"><h1>{$tracker_lang['panel_name']}</h1></div>");
	print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"retrackeradmin.php?action=add\">{$tracker_lang['add_retracker']}</a></td></tr></table>");
	$rtarray = sql_query("SELECT * FROM retrackers ORDER BY sort ASC");
	print("<table width=\"100%\" border=\"1\"><tr><td align=\"center\" colspan=\"5\">{$tracker_lang['panel_notice']}</td></tr><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$tracker_lang['order']}</td><td class=\"colhead\">{$tracker_lang['announce_url']}</td><td class=\"colhead\">{$tracker_lang['subnet_mask']}</td><td class=\"colhead\">{$tracker_lang['edit_delete']}</td></tr><form name=\"saveids\" action=\"retrackeradmin.php?action=saveids\" method=\"post\">");
	while($rt = mysql_fetch_array($rtarray)) {
		print("<tr><td>".$rt['id']."</td><td><input type=\"text\" name=\"sort[".$rt['id']."]\" size=\"4\" value=\"".$rt['sort']."\"></td><td>{$rt['announce_url']}</td><td>{$rt['mask']}</td><td><a href=\"retrackeradmin.php?action=edit&id=".$rt['id']."\">E</a> | <a onClick=\"return confirm('{$tracker_lang['are_you_sure']}')\" href=\"retrackeradmin.php?action=delete&id=".$rt['id']."\">D</a></td></tr>");
	}
	print("</table><input type=\"submit\" class=\"btn\" value=\"{$tracker_lang['save_order']}\"></form>");
	stdfoot();
}

elseif ($_GET['action'] == 'saveids') {

	if (is_array($_POST['sort'])) {

		foreach ($_POST['sort'] as $id => $s) {

			sql_query("UPDATE retrackers SET sort = ".(int)$s."  WHERE id = " . (int)$id);
		}
		header("Location: retrackeradmin.php");
		exit();
	}
	else bark("Missing form data");
}

elseif ($_GET['action'] == 'add') {
	stdhead($tracker_lang['add_retracker']);
	print("<table width=\"100%\"><form action=\"retrackeradmin.php?action=saveadd\" enctype=\"multipart/form-data\" name=\"savearray\" method=\"post\"><tr><td class=\"colhead\">{$tracker_lang['announce_url']}</td></tr><tr><td><input type=\"text\" name=\"retracker\" size=\"80\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['subnet_mask']}</td></tr><tr><td><input type=\"text\" name=\"mask\" size=\"15\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['order']}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$tracker_lang['add_retracker']}\"></form></td></tr></table>");
	stdfoot();
}

elseif ($_GET['action'] == 'delete') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
	sql_query("DELETE FROM retrackers WHERE id = ".(int) $_GET['id']);
	header("Location: retrackeradmin.php");
	exit();

}

elseif ($_GET['action'] == 'edit') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

	$rtarray = sql_query("SELECT * FROM retrackers WHERE id=".(int)$_GET['id']);
	list($id,$sort,$announce_url,$mask) = mysql_fetch_array($rtarray);

	stdhead($tracker_lang['editing_retracker']);
	print("<table width=\"100%\"><form name=\"save\" enctype=\"multipart/form-data\" action=\"retrackeradmin.php?action=saveedit\" method=\"post\"><tr><td class=\"colhead\">{$tracker_lang['announce_url']}</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"text\" name=\"retracker\" size=\"80\" value=\"".$announce_url."\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['subnet_mask']}</td></tr><tr><td><input type=\"text\" name=\"mask\" size=\"15\" value=\"$mask\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['order']}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\" value=\"".$sort."\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$tracker_lang['edit']}\"></form></td></tr></table>");
	stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
	sql_query("UPDATE retrackers SET announce_url=".sqlesc($_POST['retracker']).",mask=".sqlesc($_POST['mask']).",sort=".intval($_POST['sort'])." WHERE id=".intval($_POST['id']));
	header("Location: retrackeradmin.php");
	exit();
}
elseif ($_GET['action'] == 'saveadd') {

	sql_query("INSERT INTO retrackers (announce_url,sort,mask) VALUES (".sqlesc($_POST['retracker']).",".intval($_POST['sort']).",".sqlesc($_POST['mask']).")");
	header("Location: retrackeradmin.php");
	exit();
}


