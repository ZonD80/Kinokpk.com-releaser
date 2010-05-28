<?
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
dbconn();
getlang('take-delmp');
loggedinorreturn();

if (get_user_class() < UC_SYSOP) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

httpauth();

if((array)$_POST["delmp"]) {
	foreach ($_POST['delmp'] as $delid)
	if (!is_valid_id($delid)) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	$do = "DELETE FROM messages WHERE id IN (".implode(", ", $_POST[delmp]).")";
	$res=sql_query($do);
	header("Location: spam.php");
} else {
	stdhead($tracker_lang['error']);
	print("<div class='error'><b>".$tracker_lang['not_chosen_message']."</b></div>");
	print("<center><INPUT TYPE='button' VALUE='".$tracker_lang['back']."' onClick=\"history.go(-1)\"></center>");
	stdfoot();
	die;
}
?>