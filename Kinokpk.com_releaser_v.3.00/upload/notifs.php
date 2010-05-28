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


loggedinorreturn();
getlang('notifs');



if ($_GET['id'] && !is_valid_id($_GET['id']))

stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

$action = (string)$_GET['action'];

if ($action && !is_valid_id($_GET['cid']))

stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

$id = (int)$_GET['id'];

$cid= (int)$_GET['cid'];

$type = (string)$_GET['type'];

if ($action=='deny') { sql_query("DELETE FROM notifs WHERE id=$cid");
stderr($tracker_lang['success'],$tracker_lang['delete_notif'],'success');
} else {
	$valid_types = array('comments'=>'torrents',
'reqcomments'=>'requests',
'pollcomments'=>'polls',
'usercomments'=>'users',
'rgcomments'=>'relgroups',
'rgnewscomments'=>'rgnews',
'newscomments'=>'news',
'pagecomments'=>'pages');

	if (!array_key_exists($type,$valid_types))
	stderr($tracker_lang['error'],$tracker_lang['invalid_type']);

	$check = @mysql_result(sql_query("SELECT id FROM {$valid_types[$type]} WHERE id=$id"),0);
	if (!$check)
	stderr($tracker_lang['error'],$tracker_lang['invalid_idtype']);

	sql_query("INSERT INTO notifs (checkid, userid, type) VALUES ($id, $CURUSER[id], '$type')");
	if (mysql_errno() == 1062) stderr($tracker_lang['error'],$tracker_lang['already_notified_'.$type]);

	stderr($tracker_lang['success'],$tracker_lang['now_notified_'.$type],'success');
}

?>