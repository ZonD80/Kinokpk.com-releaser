<?php
/**
 * Notifies collector and displayer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
loggedinorreturn();
getlang('mynotifs');

//		$allowed_types = array ('unread' => 'message.php?action=viewmessage&id=', 'saved' => 'message.php?action=viewmessage&id=', 'torrents' => 'details.php?id=', 'comments' => 'comment.php?action=edit&amp;cid=', 'pollcomments' => 'pollcommennt.php?action=edit&amp;cid=', 'newscomments' => 'newscomment.php?action=edit&amp;cid=', 'usercomments' => 'usercomment.php?action=edit&amp;cid=', 'reqcomments' => 'reqcomment.php?action=edit&amp;cid=', 'rgcomments' => 'rgcomment.php?action=edit&amp;cid=', 'pages' => 'pagedetails.php?id=', 'pagecomments' => 'pagecomment.php?action=edit&amp;cid=');
if (isset($_GET['settings'])) {
	$allowed_types = array ('unread', 'torrents', 'comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments', 'pages', 'pagecomments','friends');
	if (get_user_class() >= UC_MODERATOR) {
		//   $allowed_types_moderator = array('users' => 'userdetails.php?id=', 'reports' => 'reports.php?id=', 'unchecked' => 'details.php?id=');
		$allowed_types_moderator = array('users', 'reports', 'unchecked');
		$allowed_types = array_merge($allowed_types,$allowed_types_moderator);
	}

	if ($_SERVER['REQUEST_METHOD']!='POST') {
		stdhead($tracker_lang['my_notifs_settings']);
		$notifs = explode(',',$CURUSER['notifs']);
		$emailnotifs = explode(',',$CURUSER['emailnotifs']);

		begin_frame(sprintf($tracker_lang['i_can_be_notified_due_my_class'],get_user_class_name($CURUSER['class'])));
		print('<form action="mynotifs.php?settings" method="POST">
		<div id="mynotifs">');
		print("<div class=\"colhead notify_type\"><span>{$tracker_lang['notify_type']}</span></div><div class=\"colhead notify_popup\">{$tracker_lang['notify_popup']}</div><div class=\"colhead notify_ema\">{$tracker_lang['notify_email']}</div><div class=\"clear\"></div></div>");


		foreach ($allowed_types as $type) {
			print("<div id=\"mynotifs_chek\">
  	<div class=\"notify_chek\"><span>".$tracker_lang['notify_'.$type]."</span></div>
	<div class=\"input_chek\">
	<input type=\"checkbox\" name=\"notifs[]\" class=\"styled\" value=\"{$type}\"".(in_array($type,$notifs)?" checked=\"checked\"":'').">
	</div>
	<div class=\"input_chek\"><input type=\"checkbox\" class=\"styled\" name=\"emailnotifs[]\" value=\"{$type}\"".(in_array($type,$emailnotifs)?" checked=\"checked\"":'')."></div>
	</div>
");
		}
		print('<div align="center" colspan="3"><input type="submit" value="'.$tracker_lang['go'].'"</div></div></form>');
		end_frame();
		stdfoot();
		die();
	} else
	{
		if (is_array($_POST['notifs'])) {
			foreach ($_POST['notifs'] as $notify) {
				if (in_array($notify,$allowed_types)) $allowed_notifs[]=$notify;
			}
		}
		if (is_array($_POST['emailnotifs'])) {
			foreach ($_POST['emailnotifs'] as $notify) {
				if (in_array($notify,$allowed_types)) $allowed_emailnotifs[]=$notify;
			}
		}
		//die(var_dump($allowed_notifs));
		// if ($allowed_emailnotifs || $allowed_notifs)
		sql_query("UPDATE users SET notifs = ".sqlesc(@implode(',',$allowed_notifs)).', emailnotifs = '.sqlesc(@implode(',',$allowed_emailnotifs))." WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
		header("Refresh: 1; url=my.php");
		stderr($tracker_lang['success'],$tracker_lang['notify_settigs_saved'],'success');
	}
}
stdhead($tracker_lang['my_notifs']);
//var_dump($CURUSER);

$allowed_types = explode(',',$CURUSER['notifs']);
$type =  (string)$_GET['type'];

begin_frame($tracker_lang['my_notifs'].$tracker_lang['to_notifs_list']);

if (!$type) {

	if (!$allowed_types) {
		stdmsg ($tracker_lang['error'],$tracker_lang['no_notifs_yet'],'error');
		stdfoot();
		die();
	}

	print '<div class="notifs">'.
	generate_notify_popup(true).'</div>';
	/*end_frame();
	 stdfoot();
	 die();*/
} elseif (in_array($type,$allowed_types)) {
	$allowed_links = array ('unread' => 'message.php?action=viewmessage&id=', 'torrents' => 'details.php?id=', 'comments' => 'details.php?id=', 'pollcomments' => 'polloverview.php?id=', 'newscomments' => 'newsoverview.php?id=', 'usercomments' => 'userdetails.php?id=', 'reqcomments' => 'requests.php?id=', 'rgcomments' => 'relgroups.php?id=', 'pages' => 'pagedetails.php?id=', 'pagecomments' => 'pagedetails.php?id=', 'users' => 'userdetails.php?id=', 'reports' => 'reports.php?id=', 'unchecked' => 'details.php?id=', 'friends' => 'userdetails.php?id=');

	$comment_fields = array('comments' => 'torrent', 'pollcomments' => 'poll', 'newscomments' => 'news', 'usercomments' => 'userid', 'reqcomments' => 'request', 'rgcomments' => 'relgroup', 'pagecomments' => 'page');
	$name_fields = array('comments' => 'torrents.name', 'pollcomments' => 'polls.question', 'newscomments' => 'news.subject', 'usercomments' => '(SELECT username FROM users WHERE users.id = usercomments.userid)', 'reqcomments' => 'requests.request', 'rgcomments' => 'relgroups.name', 'pagecomments' => 'pages.name');
	$leftjoin_fields = array('comments' => ' LEFT JOIN torrents ON comments.torrent=torrents.id', 'reqcomments'=> ' LEFT JOIN requests ON reqcomments.request=requests.id', 'pollcomments'=> ' LEFT JOIN polls ON pollcomments.poll=polls.id',  'newscomments' => ' LEFT JOIN news ON newscomments.news=news.id', 'rgcomments' => ' LEFT JOIN relgroups ON rgcomments.relgroup=relgroups.id', 'pagecomments' => ' LEFT JOIN pages ON pagecomments.page=pages.id');
	switch ($type) {
		case 'unread': $addition = 'messages.id, messages.subject, NULL, messages.sender, users.username, users.class, messages.added FROM messages LEFT JOIN users ON messages.sender=users.id WHERE messages.unread=1 AND messages.receiver = '.$CURUSER['id'].' ORDER BY messages.added DESC'; break;
		// unread done
		case 'reports': $addition = 'reports.id, NULL, reports.type, reports.userid, users.username, users.class, reports.added FROM reports LEFT JOIN users ON reports.userid=users.id ORDER BY reports.added DESC'; break;
		// reports done
		case 'torrents': $addition = 'torrents.id, torrents.id, torrents.name, torrents.owner, users.username, users.class, torrents.added FROM torrents LEFT JOIN users ON torrents.owner=users.id WHERE torrents.added>'.$CURUSER['last_login'].' ORDER BY torrents.added DESC'; break;
		// torrents done
		case 'unchecked': $addition = 'torrents.id, torrents.id, torrents.name, torrents.owner, users.username, users.class, torrents.added FROM torrents LEFT JOIN users ON torrents.owner=users.id WHERE torrents.moderatedby=0 ORDER BY torrents.added DESC'; break;
		// unchecked done
		/* case 'rgcomments':
		 $addition = "CONCAT_WS('#comm',$type.{$comment_fields[$type]},$type.id) AS cid, $type.id, relgroups.{$name_fields[$type]}, $type.user, users.username, users.class, $type.added FROM $type LEFT JOIN users ON $type.user = users.id LEFT JOIN relgroups ON $type.relgroup=relgroups.id WHERE $type.added>{$CURUSER['last_login']} ORDER BY $type.added DESC"; break;
		 */
		case 'pages': $addition = 'pages.id, pages.name, NULL, pages.owner, users.username, users.class, pages.added FROM pages LEFT JOIN users ON pages.owner=users.id WHERE pages.added>'.$CURUSER['last_login'].' AND pages.class <= '.get_user_class().' ORDER BY pages.added DESC'; break;
		// pages done
		case 'users': $addition = "users.id, NULL, NULL, users.id, users.username, users.class, users.added FROM users WHERE users.added>{$CURUSER['last_login']} ORDER BY users.added DESC"; break;
		// users done
		case 'friends': $addition = "friends.userid, friends.id, friends.id, friends.userid, users.username, users.class, NULL FROM friends LEFT JOIN users ON friends.userid=users.id WHERE friends.confirmed=0 AND friends.friendid={$CURUSER['id']}"; break;
		// friends done
		case 'comments' or 'pollcomments' or 'newscomments' or 'usercomments' or 'reqcomments' or 'pagecomments' or 'rgcomments':
			$addition = "CONCAT_WS('#comm',$type.{$comment_fields[$type]},$type.id) AS cid, NULL, {$name_fields[$type]}, $type.user, users.username, users.class, $type.added FROM $type LEFT JOIN users ON $type.user = users.id{$leftjoin_fields[$type]} WHERE $type.added>{$CURUSER['last_login']} ORDER BY $type.added DESC"; break;
	}
	$query = sql_query("SELECT $addition");
	print ('<h1>'.$tracker_lang["you_watching_$type"].'</h1>');
	while ($array = mysql_fetch_array($query, MYSQL_NUM)) {
		// var_Dump($array);
		print (sprintf($tracker_lang['notify_is_'.$type],$array[1], $array[2], ($array[3]?"<a href=\"userdetails.php?id={$array[3]}\">".get_user_class_color($array[5],$array[4])."</a>":$tracker_lang['from_system']),($array[6]?mkprettytime($array[6]).' ('.get_elapsed_time($array[6],false)."{$tracker_lang['ago']})":''))." <strong><a href=\"{$allowed_links[$type]}{$array[0]}\" >{$tracker_lang['view']}</a></strong><br />");
	}

} else {
	stdmsg($tracker_lang['error'],$tracker_lang['access_denied'],'error');
}
end_frame();
//print_r($CURUSER);
stdfoot();

?>