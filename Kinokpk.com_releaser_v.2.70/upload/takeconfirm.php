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
getlang('takeconfirm');
loggedinorreturn();

if (!is_valid_id($_GET["id"]))
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_GET["id"];
if (is_array($_POST["conusr"]))                            {
	$ids = @implode(",", array_map("sqlesc", $_POST["conusr"]));
	sql_query("UPDATE invites SET confirmed=1 WHERE inviteid IN (" . $ids . ") AND confirmed=0".( get_user_class() < UC_SYSOP ? " AND inviter = $CURUSER[id]" : "")) or sqlerr(__FILE__,__LINE__);
	$upload = $CACHEARRAY['upload_per_invite']*1024*1024;

	sql_query("UPDATE users SET uploaded=uploaded+$upload WHERE id IN($ids)") or sqlerr(__FILE__,__LINE__);

	$ids = explode(',',$ids);
	if ($ids)
	foreach ($ids as $id) write_sys_msg($id,sprintf($tracker_lang['invite_confirmed'],$CACHEARRAY['upload_per_invite']),$tracker_lang['invite_confirmed_title']);
}
header("Location: invite.php?id=$id");

?>