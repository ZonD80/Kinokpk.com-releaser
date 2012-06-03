<?php
/**
 * Comments processor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

$action = (string)$_GET["action"];

INIT();

loggedinorreturn();

$allowed_types = array('rel'=>'torrents','poll'=>'polls','news'=>'news','user'=>'users','req'=>'requests','rg'=>'relgroups','rgnews'=>'rgnews');
$names = array('rel'=>'name','poll'=>'question','news'=>'subject','user'=>'username','req'=>'request','rg'=>'name','rgnews'=>'subject');
$returnto = strip_tags((string)($_POST['returnto']?$_POST['returnto']:$_SERVER['HTTP_REFERER']));
if ($action == "add")
{
	$type = trim((string)$_GET['type']);
	if (!in_array($type,array_keys($allowed_types))) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown comment type'));

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (!is_valid_id($_POST["to_id"]))
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
			
		$to_id = (int) $_POST["to_id"];

		$res = $REL_DB->query("SELECT {$names[$type]} FROM {$allowed_types[$type]} WHERE id = $to_id");
		$name = @mysql_result($res,0);
		if (!$name)
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("What do you want to comment? We don't know"));

		$text = trim((string)$_POST["text"]);
		if (!$text)
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('comment_cant_be_empty'));
		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = $REL_DB->query("SELECT ".time()."-added AS seconds, text AS msg, id, toid FROM comments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
		while ($last_pmresrow = mysql_fetch_array($last_pmres)){
			$last_pmrow[] = $last_pmresrow;
			$msgids[] = $last_pmresrow['id'];
			$torids[] = $last_pmresrow['toid'];
		}
		//   print_r($last_pmrow);
		if ($last_pmrow[0]){
			if (($REL_CONFIG['as_timeout'] > round($last_pmrow[0]['seconds'])) && $REL_CONFIG['as_timeout']) {
				$seconds =  $REL_CONFIG['as_timeout'] - round($last_pmrow[0]['seconds']);
				$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('We have anti-flood system. Please try to send comment again in %s seconds',$seconds).' '."<a href=\"javascript: history.go(-1)\">{$REL_LANG->_('Go back')}</a>");
			}

			if ($REL_CONFIG['as_check_messages'] && ($last_pmrow[0]['msg'] == $text) && ($last_pmrow[1]['msg'] == $text) && ($last_pmrow[2]['msg'] == $text) && ($last_pmrow[3]['msg'] == $text)) {
				$msgview='';
				foreach ($msgids as $key => $msgid){
					$msgview.= "\n<a href=\"".$REL_SEO->make_link('details','id',$torids[$key])."#comm$msgid\">{$REL_LANG->_('View comment')} ID={$msgid}</a> (".$CURUSER['username'].")";
				}
				$modcomment = $REL_DB->query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in comments") === false) {
					$reason = sqlesc($REL_LANG->_('User %s can be spammer because his last 5 comments are the same',make_user_link())." ".$msgview);
					$REL_DB->query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
					$modcomment .= "\n".time()." - Maybe spammer in comments";
					$REL_DB->query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					$REL_DB->query("UPDATE users SET enabled=0, dis_reason='Spam in comments' WHERE id=".$CURUSER['id']);

					$reason = sqlesc($REL_LANG->_('User %s banned by system due spam. His IP address is %',make_user_link(),$CURUSER['ip']));
					$REL_DB->query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
					
					$REL_TPL->stderr($REL_LANG->_('Congratulations'),$REL_LANG->_('You are automatically banned due to spam in comments. You can report this issue to <a href="%s">Administrators</a>',$REL_SEO->make_link('contact')));
						}
				$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('We have anti-spam system. Last 5 comments from you are the same. Please, write something else:) <b><u>ATTENTION! IF YOU TRY TO SEND THE SAME COMMENT AGAIN, YOU WILL BE AUTOMATICALLY BANNED BY SYSTEM!!!</u></b>')." <a href=\"javascript: history.go(-1)\">{$REL_LANG->_('Go back')}</a>");

			}
		}

		// ANITSPAM SYSTEM END
		$REL_DB->query("INSERT INTO comments (user, toid, added, text, ip, type) VALUES (" .
		$CURUSER["id"] . ",$to_id, '" . time() . "', " . sqlesc($text) .
	       "," . sqlesc(getip()) . ", '$type')");

		$newid = mysql_insert_id();

		$clearcache = array('block-indextorrents','block-comments');
		foreach ($clearcache as $cachevalue) $REL_CACHE->clearGroupCache($cachevalue);

		$REL_DB->query("UPDATE {$allowed_types[$type]} SET comments = comments + 1 WHERE id = $to_id");
		clear_comment_caches($type);
		
		if ($type=='rel'&&get_privilege('edit_releases',false)) {
			$release = $REL_DB->query_row("SELECT id,name,owner FROM torrents WHERE id=$to_id AND moderatedby=0");
			if ($release&&$release['owner']) {
				$msg = $REL_LANG->_to($release['owner'],'Moderator has just added new comment to your release "%s". It means that you may be able to fix some release details to get it published. Please visit <a href="%s">your release page</a> for better experience. Thanks.',$release['name'],$REL_SEO->make_link('details','id',$to_id,'name',translit($release['name'])));
				write_sys_msg($release['owner'], $msg, $REL_LANG->_to($release['owner'],'Your release is checking'));
			}
		}
		send_comment_notifs($to_id,"<a href=\"$returnto\">$name</a>","{$type}comments");
		
		set_visited("{$type}comments",$newid);
		if (!REL_AJAX) {
			safe_redirect($returnto);
			$REL_TPL->stderr($REL_LANG->_('Successfull'),$REL_LANG->_('Comment added'),'success'); }
			else {
				$subres = $REL_DB->query ( "SELECT c.id, c.type, c.ip, c.ratingsum, c.text, c.user, c.added, c.toid, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.info, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN sessions ON c.user=sessions.uid LEFT JOIN users AS e ON c.editedby = e.id WHERE c.id=$newid AND c.type='$type'" );
				//$link = $allowed_links[$type].$allrows[0]['toid'];
				$allrows = prepare_for_commenttable($subres,$name,$returnto);
				$IS_MODERATOR = (get_privilege('edit_comments',false));
				$REL_TPL->assignByRef('IS_MODERATOR',$IS_MODERATOR);
				headers(REL_AJAX);
				$REL_TPL->assignByRef('row',$allrows[0]);
				$REL_TPL->display('commenttable_entry.tpl');
				die();
			}
	}
}
elseif ($action == "quote")
{

	if (!is_valid_id($_GET["cid"]))
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	$commentid = (int) $_GET["cid"];

	$res = $REL_DB->query("SELECT c.*, u.username FROM comments AS c LEFT JOIN users AS u ON c.user = u.id WHERE c.id=$commentid");
	$arr = mysql_fetch_array($res);
	if (!$arr)
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	$REL_TPL->stdhead($REL_LANG->_("Quoting comment"));

	$text = "<blockquote><p>" . format_comment($arr["text"]) . "</p></blockquote><cite>$arr[username]</cite><hr /><br /><br />\n";

	print("<form method=\"post\" name=\"comment\" action=\"".$REL_SEO->make_link('comments','action','add','type',$arr[type])."\">\n");
	print("<input type=\"hidden\" name=\"to_id\" value=\"$arr[toid]\" />\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"".strip_tags($_SERVER['HTTP_REFERER'])."\" />\n");
	?>

<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?php		print($REL_LANG->_("Quoting comment"));
		?></td>
	</tr>
	<tr>
		<td><?php		print textbbcode("text",$text);
		?></td>
	</tr>
</table>

		<?php
		print("<p><input type=\"submit\" value=\"{$REL_LANG->_('Submit comment')}\" /></p></form>\n");

		$REL_TPL->stdfoot();

}
elseif ($action == "edit")
{
	if (!is_valid_id($_GET["cid"]))
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$commentid = (int) $_GET["cid"];

	$res = $REL_DB->query("SELECT * FROM comments WHERE id=$commentid");
	$arr = mysql_fetch_array($res);
	if (!$arr)
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	if ($arr["user"] != $CURUSER["id"] && !get_privilege('edit_comments',false))
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["text"]);

		if ($text == "")
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('comment_cant_be_empty'));

		$text = sqlesc(($text));

		$editedat = sqlesc(time());
			
		$REL_DB->query("UPDATE comments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid");
		if (!REL_AJAX)
		safe_redirect($returnto);
		$REL_TPL->stderr($REL_LANG->_('Successfull'),$REL_LANG->_('Comment edited'),'success');
	}

	$REL_TPL->stdhead($REL_LANG->_("Editing comment"));

	print("<form method=\"post\" name=\"comment\" action=\"".$REL_SEO->make_link('comments','action','edit','cid',$commentid)."\">\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"".strip_tags($_SERVER['HTTP_REFERER'])."\" />\n");

	?>

<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?php		print($REL_LANG->_("Editing comment"));
		?></td>
	</tr>
	<tr>
		<td><?php		print textbbcode("text",$arr["text"]);
		?></td>
	</tr>
</table>

		<?php
		print("<p><input type=\"submit\" value=\"{$REL_LANG->_("Submit comment")}\" /></p></form>\n");

		$REL_TPL->stdfoot();
		die;
}
elseif ($action == "delete")
{
	get_privilege('edit_comments');

	if (!is_array($_GET["cid"])||!$_GET["cid"])
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$cids = array_map("intval",$_GET["cid"]);
	foreach ($cids AS $commentid) {


		$res = $REL_DB->query("SELECT toid,type FROM comments WHERE id=$commentid") ;
		$arr = mysql_fetch_array($res);
		if ($arr) {
		$to_id = $arr["toid"];
		$type = $arr['type'];
		}
		else
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

		$REL_DB->query("DELETE FROM comments WHERE id=$commentid");
		if ($to_id && mysql_affected_rows() > 0) {
		$REL_DB->query("UPDATE {$allowed_types[$type]} SET comments = comments - 1 WHERE id = $to_id");

		clear_comment_caches($type);
		}
	}
	if (!REL_AJAX)
	safe_redirect(strip_tags($_SERVER['HTTP_REFERER']),1);
	$REL_TPL->stderr($REL_LANG->_("Successful"),$REL_LANG->_("Comments successfully deleted. Now you will back to previous page."),'success');
}
else
$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Unknown action"));
?>
