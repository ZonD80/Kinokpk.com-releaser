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

INIT();
loggedinorreturn();


$allowed_types_view = array ('unread', 'torrents', 'relcomments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments','forumcomments'/*,'rgnewscomments'*/,'friends');
if (get_privilege('is_moderator',false)) {
	//   $allowed_types_moderator = array('users' => 'userdetails.php?id=', 'reports' => 'reports.php?id=', 'unchecked' => 'details.php?id=');
	$allowed_types_moderator = array('users', 'reports', 'unchecked');
	$allowed_types_view = array_merge($allowed_types_view,$allowed_types_moderator);
}
if (isset($_GET['settings'])) {


	if ($_SERVER['REQUEST_METHOD']!='POST') {
		$REL_TPL->stdhead($REL_LANG->say_by_key('my_notifs_settings'));
		$notifs = $CURUSER['notifs'];
		$emailnotifs = $CURUSER['emailnotifs'];

		$REL_TPL->begin_frame(sprintf($REL_LANG->say_by_key('i_can_be_notified_due_my_class'),get_user_class_name($CURUSER['class'])));
		print('<form action="'.$REL_SEO->make_link('mynotifs','settings','').'" method="POST">
		<div id="mynotifs">');
		print("<div class=\"colhead notify_type\"><span>{$REL_LANG->say_by_key('notify_type')}</span></div><div class=\"colhead notify_popup\">{$REL_LANG->say_by_key('notify_popup')}</div><div class=\"colhead notify_ema\">{$REL_LANG->say_by_key('notify_email')}</div><div class=\"clear\"></div></div>");


		foreach ($allowed_types_view as $type) {
			print("<div id=\"mynotifs_chek\">
  	<div class=\"notify_chek\"><span>".$REL_LANG->say_by_key('notify_'.$type)."</span></div>
	<div class=\"input_chek\">
	<input type=\"checkbox\" name=\"notifs[]\" class=\"styled\" value=\"{$type}\"".(in_array($type,$notifs)?" checked=\"checked\"":'').">
	</div>
	<div class=\"input_chek\"><input type=\"checkbox\" class=\"styled\" name=\"emailnotifs[]\" value=\"{$type}\"".(in_array($type,$emailnotifs)?" checked=\"checked\"":'')."></div>
	</div>
");
		}
		print('<div align="center" colspan="3">'.$REL_LANG->_("<b>Attention:</b> Email-based notifications are sending only when you are monitoring comments, topics, e.g., due our carefully antispam policy").'<br/><input type="submit" value="'.$REL_LANG->say_by_key('go').'"/></div></div></form>');
		$REL_TPL->end_frame();
		$REL_TPL->stdfoot();
		die();
	} else
	{
		if (is_array($_POST['notifs'])) {
			foreach ($_POST['notifs'] as $notify) {
				if (in_array($notify,$allowed_types_view)) $allowed_notifs[]=$notify;
			}
		}
		if (is_array($_POST['emailnotifs'])) {
			foreach ($_POST['emailnotifs'] as $notify) {
				if (in_array($notify,$allowed_types_view)) $allowed_emailnotifs[]=$notify;
			}
		}
		//die(var_dump($allowed_notifs));
		// if ($allowed_emailnotifs || $allowed_notifs)
		sql_query("UPDATE users SET notifs = ".sqlesc(@implode(',',$allowed_notifs)).', emailnotifs = '.sqlesc(@implode(',',$allowed_emailnotifs))." WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
		safe_redirect($REL_SEO->make_link('my'));
		stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('notify_settigs_saved'),'success');
	}
}
$REL_TPL->stdhead($REL_LANG->say_by_key('my_notifs'));
//var_dump($CURUSER);

$allowed_types = $CURUSER['notifs'];

$types_diff = array_diff($allowed_types_view,$allowed_types);

$type =  (string)$_GET['type'];

$REL_TPL->begin_frame($REL_LANG->say_by_key('my_notifs')."| <a href=\"{$REL_SEO->make_link('mynotifs')}\">{$REL_LANG->say_by_key('to_notifs_list')}</a>");

if (!$type) {

	if (!$allowed_types) {
		stdmsg ($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_notifs_yet'),'error');
		$REL_TPL->stdfoot();
		die();
	}

	$array = generate_notify_array();
	print '<table width="100%"><tr><td>';
	print "<h1>{$REL_LANG->_("Total notifications")}: {$array['total']}</h1>";
	print '</td></tr>';

	foreach ($array['notifs'] as $notify => $ncount) {
		if ($notify=='relcomments') $display='comments'; else $display=$notify;
		print "<tr><td><a href=\"{$REL_SEO->make_link('mynotifs','type',$notify)}\">".$REL_LANG->_(ucfirst($display)).": $ncount</a></td></tr>";
	}
	print '</table>';

	/*$REL_TPL->end_frame();
	 $REL_TPL->stdfoot();
	 die();*/
} elseif (in_array($type,$allowed_types)) {

	if ($type=='unread') { safe_redirect($REL_SEO->make_link('message'));
	stdmsg($REL_LANG->_("Redirecting"),$REL_LANG->_("Now you will be redirected to your PM inbox"));
	$REL_TPL->stdfoot();
	die();
	}
	$allowed_links = array (/*'unread' => $REL_SEO->make_link('message','action','viewmessage','id',''), */'torrents' => $REL_SEO->make_link('details','id',''), 'relcomments' => $REL_SEO->make_link('details','id',''), 'pollcomments' => $REL_SEO->make_link('polloverview','id',''), 'newscomments' => $REL_SEO->make_link('newsoverview','id',''), 'usercomments' => $REL_SEO->make_link('userdetails','id',''), 'reqcomments' => $REL_SEO->make_link('requests','id',''), 'rgcomments' => $REL_SEO->make_link('relgroups','id',''), 'users' => $REL_SEO->make_link('userdetails','id',''), 'reports' => $REL_SEO->make_link('reports','id',''), 'unchecked' => $REL_SEO->make_link('details','id',''), 'friends' => $REL_SEO->make_link('userdetails','id',''), 'forumcomments'=> $REL_SEO->make_link('forum','a','viewtopic','id','%s','p','%s'));

	$name_fields = array('relcomments' => 'torrents.name', 'pollcomments' => 'polls.question', 'newscomments' => 'news.subject', 'usercomments' => '(SELECT username FROM users WHERE users.id = comments.toid AND comments.type=\'user\')', 'reqcomments' => 'requests.request', 'rgcomments' => 'relgroups.name', 'forumcomments'=>'forum_topics.subject');
	$leftjoin_fields = array('relcomments' => ' LEFT JOIN torrents ON comments.toid=torrents.id', 'reqcomments'=> ' LEFT JOIN requests ON comments.toid=requests.id', 'pollcomments'=> ' LEFT JOIN polls ON comments.toid=polls.id',  'newscomments' => ' LEFT JOIN news ON comments.toid=news.id', 'rgcomments' => ' LEFT JOIN relgroups ON comments.toid=relgroups.id', 'forumcomments'=>' LEFT JOIN forum_topics ON forum_topics.id=comments.toid LEFT JOIN forum_categories ON forum_topics.category=forum_categories.id');
	if ($type=='reports') {
		$addition = 'reports.id, NULL, reports.type, reports.userid, users.username, users.class, reports.added'; $from = 'FROM reports LEFT JOIN users ON reports.userid=users.id ORDER BY reports.added DESC';
	}
	elseif ($type=='torrents'){
		$addition = 'torrents.id, torrents.id, torrents.name, torrents.owner, users.username, users.class, torrents.added'; $from = 'FROM torrents LEFT JOIN users ON torrents.owner=users.id WHERE torrents.added>'.$CURUSER['last_login'].' ORDER BY torrents.added DESC';
	}
	elseif ($type=='unchecked') {
		$addition = 'torrents.id, torrents.id, torrents.name, torrents.owner, users.username, users.class, torrents.added'; $from = 'FROM torrents LEFT JOIN users ON torrents.owner=users.id WHERE torrents.moderatedby=0 ORDER BY torrents.added DESC';
	}
	// unchecked done
	/* case 'rgcomments':
	 $addition = "CONCAT_WS('#comm',$type.{$comment_fields[$type]},$type.id) AS cid, $type.id, relgroups.{$name_fields[$type]}, $type.user, users.username, users.class, $type.added FROM $type LEFT JOIN users ON $type.user = users.id LEFT JOIN relgroups ON $type.relgroup=relgroups.id WHERE $type.added>{$CURUSER['last_login']} ORDER BY $type.added DESC"; break;
	 */
	elseif ($type=='users') {
		$addition = "users.id, NULL, NULL, users.id, users.username, users.class, users.added"; $from = "FROM users WHERE users.added>{$CURUSER['last_login']} ORDER BY users.added DESC";
	}
	elseif ($type=='friends') {
		$addition = "friends.userid, friends.id, friends.id, friends.userid, users.username, users.class, NULL"; $from = "FROM friends LEFT JOIN users ON friends.userid=users.id WHERE friends.confirmed=0 AND friends.friendid={$CURUSER['id']}";
	}
	elseif (in_array($type,array('relcomments','pollcomments','newscomments','usercomments','reqcomments','rgcomments','forumcomments'))) {
		$typeq = str_replace('comments','',$type);
		$addition = ($type<>'forumcomments'?"CONCAT_WS('#comm',comments.toid,comments.id) AS cid, NULL":'comments.toid AS cid, comments.id').", {$name_fields[$type]}, comments.user, users.username, users.class, comments.added"; $from = "FROM comments LEFT JOIN users ON comments.user = users.id{$leftjoin_fields[$type]} WHERE comments.added>{$CURUSER['last_login']} AND comments.type='$typeq'".($type=='forumcomments'?" AND FIND_IN_SET(".get_user_class().",forum_categories.class)":'')." ORDER BY comments.added DESC"; 
	}
	$limited = 50;
	$count = @mysql_result(sql_query("SELECT SUM(1) $from"),0);
	$limit = "LIMIT 50";

	$query = sql_query("SELECT $addition $from $limit");
	print ('<h1>'.$REL_LANG->say_by_key("you_watching_$type").'</h1>');

	print '<div id="mynotif">';
	while ($array = mysql_fetch_array($query, MYSQL_NUM)) {
		if ($type<>'forumcomments') $disp = $array[1]; else $disp='';
		
		print ('<div class="column_notifs">
		<div class="column_left">'.sprintf($REL_LANG->say_by_key('notify_is_'.$type),$disp, $array[2], ($array[3]?
			"<a href=\"".$REL_SEO->make_link('userdetails','id',$array[3],'username',translit($array[4]))."\">".get_user_class_color($array[5],$array[4])."</a>"
			:$REL_LANG->say_by_key('from_system')),($array[6]?mkprettytime($array[6]).' ('.get_elapsed_time($array[6],false)."{$REL_LANG->say_by_key('ago')})":''))."</div>
		<div class='column_right'><strong><a href=\"".($type=='forumcomments'?sprintf($allowed_links[$type],$array[0],"{$array[1]}#comm{$array[1]}"):"{$allowed_links[$type]}{$array[0]}")."\" >{$REL_LANG->say_by_key('view')}</a></strong></div>
		</div>");
	}
	print '</div>';


}
elseif (in_array($type,$types_diff)) {
	safe_redirect($REL_SEO->make_link('mynotifs','settings',1),1);
	stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->_('You did not subscribed to view %s notifications, please set up it in your <a href="%s">notifications configuration page</a> and try again. Redirecting you to notifications configuration',ucfirst($type),$REL_SEO->make_link('mynotifs','settings',1)),'error');
}
else {

	stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'),'error');
}
$REL_TPL->end_frame();
//print_r($CURUSER);
$REL_TPL->stdfoot();

?>