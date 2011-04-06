<?php
/**
 * Notification subscriber
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();


loggedinorreturn();




$action = (string)$_GET['action'];

$id = (int)$_GET[($action=='deny'?'c':'').'id'];
if ($id && !is_valid_id($id))

stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

$type = (string)$_GET['type'];

if ($action=='deny') { sql_query("DELETE FROM notifs WHERE id=$id");
stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('delete_notif'),'success');
} else {
	$valid_types = array('relcomments'=>'torrents',
'reqcomments'=>'requests',
'pollcomments'=>'polls',
'usercomments'=>'users',
'rgcomments'=>'relgroups',
'rgnewscomments'=>'rgnews',
'newscomments'=>'news',
'forumcomments'=>'forum_topics');

	if (!array_key_exists($type,$valid_types))
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_type'));

	$check = @mysql_result(sql_query("SELECT id FROM {$valid_types[$type]} WHERE id=$id"),0);
	if (!$check)
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_idtype'));

	sql_query("INSERT INTO notifs (checkid, userid, type) VALUES ($id, $CURUSER[id], '$type')");
	if (mysql_errno() == 1062) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('already_notified_'.$type));

	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('now_notified_'.$type),'success');
}

?>