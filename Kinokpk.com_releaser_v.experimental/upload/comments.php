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

$allowed_types = array('rel'=>'torrents','poll'=>'polls','news'=>'news','user'=>'users','req'=>'requests','rg'=>'relgroups','rgnews'=>'rgnews','forum'=>'forum_topics');
$names = array('rel'=>'name','poll'=>'question','news'=>'subject','user'=>'username','req'=>'request','rg'=>'name','rgnews'=>'subject','forum'=>'subject');
$returnto = strip_tags((string)($_POST['returnto']?$_POST['returnto']:$_SERVER['HTTP_REFERER']));
if ($action == "add")
{
	$type = trim((string)$_GET['type']);
	if (!in_array($type,array_keys($allowed_types))) stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown comment type'));

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (!is_valid_id($_POST["to_id"]))
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
			
		$to_id = (int) $_POST["to_id"];

		$res = sql_query("SELECT {$names[$type]} FROM {$allowed_types[$type]} WHERE id = $to_id") or sqlerr(__FILE__,__LINE__);
		$name = @mysql_result($res,0);
		if (!$name)
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("What do you want to comment? We don't know"));

		$text = trim((string)$_POST["text"]);
		if (!$text)
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('comment_cant_be_empty'));
		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, toid FROM comments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
		while ($last_pmresrow = mysql_fetch_array($last_pmres)){
			$last_pmrow[] = $last_pmresrow;
			$msgids[] = $last_pmresrow['id'];
			$torids[] = $last_pmresrow['toid'];
		}
		//   print_r($last_pmrow);
		if ($last_pmrow[0]){
			if (($REL_CONFIG['as_timeout'] > round($last_pmrow[0]['seconds'])) && $REL_CONFIG['as_timeout']) {
				$seconds =  $REL_CONFIG['as_timeout'] - round($last_pmrow[0]['seconds']);
				stderr($REL_LANG->say_by_key('error'),"На нашем сайте стоит защита от флуда, пожалуйста, повторите попытку через $seconds секунд. <a href=\"javascript: history.go(-1)\">Назад</a>");
			}

			if ($REL_CONFIG['as_check_messages'] && ($last_pmrow[0]['msg'] == $text) && ($last_pmrow[1]['msg'] == $text) && ($last_pmrow[2]['msg'] == $text) && ($last_pmrow[3]['msg'] == $text)) {
				$msgview='';
				foreach ($msgids as $key => $msgid){
					$msgview.= "\n<a href=\"".$REL_SEO->make_link('details','id',$torids[$key])."#comm$msgid\">Комментарий ID={$msgid}</a> от пользователя ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in comments") === false) {
					$reason = sqlesc("Пользователь ".make_user_link()." может быть спамером, т.к. его 5 последних посланных комментариев полностью совпадают.$msgview");
					sql_query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
					$modcomment .= "\n".time()." - Maybe spammer in comments";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in comments' WHERE id=".$CURUSER['id']);

					$reason = sqlesc("Пользователь ".make_user_link()." забанен системой за спам, его IP адрес (".$CURUSER['ip'].")");
					sql_query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
					
					stderr("Поздравляем!","Вы успешно забанены системой за спам в комментариях! Если вы не согласны с решением системы, <a href=\"".$REL_SEO->make_link('contact')."\">подайте жалобу админам</a>.");
						}
				stderr($REL_LANG->say_by_key('error'),"На нашем сайте стоит защита от спама, ваши 5 последних комментариев совпадают. В отсылке комментария отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>");

			}
		}

		// ANITSPAM SYSTEM END
		sql_query("INSERT INTO comments (user, toid, added, text, ip, type) VALUES (" .
		$CURUSER["id"] . ",$to_id, '" . time() . "', " . sqlesc($text) .
	       "," . sqlesc(getip()) . ", '$type')") or sqlerr(__FILE__,__LINE__);

		$newid = mysql_insert_id();

		$clearcache = array('block-indextorrents','block-comments');
		foreach ($clearcache as $cachevalue) $REL_CACHE->clearGroupCache($cachevalue);

		sql_query("UPDATE {$allowed_types[$type]} SET comments = comments + 1".($type=='forum'?", lastposted_id=$newid":'')." WHERE id = $to_id") or sqlerr(__FILE__,__LINE__);
		clear_comment_caches($type);
		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
		
		send_comment_notifs($to_id,"<a href=\"$returnto\">$name</a>","{$type}comments");
		
		set_visited("{$type}comments",$newid);
		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
		if (!REL_AJAX) {
			safe_redirect($returnto);
			stderr($REL_LANG->_('Successfull'),$REL_LANG->_('Comment added'),'success'); }
			else {
				$subres = sql_query ( "SELECT c.id, c.type, c.ip, c.ratingsum, c.text, c.user, c.added, c.toid, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.info, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN sessions ON c.user=sessions.uid LEFT JOIN users AS e ON c.editedby = e.id WHERE c.id=$newid AND c.type='$type'" ) or sqlerr ( __FILE__, __LINE__ );
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
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	$commentid = (int) $_GET["cid"];

	$res = sql_query("SELECT c.*, u.username FROM comments AS c LEFT JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	$REL_TPL->stdhead($REL_LANG->_("Quoting comment"));

	$text = "<blockquote><p>" . format_comment($arr["text"]) . "</p></blockquote><cite>$arr[username]</cite><hr /><br /><br />\n";

	print("<form method=\"post\" name=\"comment\" action=\"".$REL_SEO->make_link('comments','action','add','type',$arr[type])."\">\n");
	print("<input type=\"hidden\" name=\"to_id\" value=\"$arr[toid]\" />\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"".strip_tags($_SERVER['HTTP_REFERER'])."\" />\n");
	?>

<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?
		print($REL_LANG->_("Quoting comment"));
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text",$text);
		?></td>
	</tr>
</table>

		<?

		print("<p><input type=\"submit\" value=\"{$REL_LANG->_('Submit comment')}\" /></p></form>\n");

		$REL_TPL->stdfoot();

}
elseif ($action == "edit")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$commentid = (int) $_GET["cid"];

	$res = sql_query("SELECT * FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	if ($arr["user"] != $CURUSER["id"] && !get_privilege('edit_comments',false))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["text"]);

		if ($text == "")
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('comment_cant_be_empty'));

		$text = sqlesc(($text));

		$editedat = sqlesc(time());
			
		sql_query("UPDATE comments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);
		if (!REL_AJAX)
		safe_redirect($returnto);
		stderr($REL_LANG->_('Successfull'),$REL_LANG->_('Comment edited'),'success');
	}

	$REL_TPL->stdhead($REL_LANG->_("Editing comment"));

	print("<form method=\"post\" name=\"comment\" action=\"".$REL_SEO->make_link('comments','action','edit','cid',$commentid)."\">\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"".strip_tags($_SERVER['HTTP_REFERER'])."\" />\n");

	?>

<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?
		print($REL_LANG->_("Editing comment"));
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text",$arr["text"]);
		?></td>
	</tr>
</table>

		<?

		print("<p><input type=\"submit\" value=\"{$REL_LANG->_("Submit comment")}\" /></p></form>\n");

		$REL_TPL->stdfoot();
		die;
}
elseif ($action == "delete")
{
	get_privilege('edit_comments');

	if (!is_array($_GET["cid"])||!$_GET["cid"])
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$cids = array_map("intval",$_GET["cid"]);
	foreach ($cids AS $commentid) {


		$res = sql_query("SELECT toid,type FROM comments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if ($arr) {
		$to_id = $arr["toid"];
		$type = $arr['type'];
		}
		else
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

		sql_query("DELETE FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
		if ($to_id && mysql_affected_rows() > 0) {
		sql_query("UPDATE {$allowed_types[$type]} SET comments = comments - 1 WHERE id = $to_id") or sqlerr(__FILE__,__LINE__);
		if ($type=='forum') {
			
			$check = mysql_fetch_assoc($REL_DB->query("SELECT (SELECT comments.id FROM comments WHERE toid=$to_id AND type='forum' AND comments.id>$commentid ORDER BY id ASC LIMIT 1) AS next, (SELECT comments.id FROM comments WHERE toid=$to_id AND type='forum' AND comments.id<$commentid ORDER BY id DESC LIMIT 1) AS prev"));
			//die(var_dump($check));
			if (!$check['next']&&!$check['prev']) $REL_DB->query("DELETE FROM forum_topics WHERE id=$to_id") or sqlerr(__FILE__,__LINE__);
			elseif (!$check['next']) {
				$REL_DB->query("UPDATE forum_topics SET lastposted_id={$check['prev']} WHERE id=$to_id") or sqlerr(__FILE__,__LINE__);
			}
		}
		clear_comment_caches($type);
		}
	}
	if (!REL_AJAX)
	safe_redirect(strip_tags($_SERVER['HTTP_REFERER']),1);
	stderr($REL_LANG->_("Successful"),$REL_LANG->_("Comments successfully deleted. Now you will back to previous page."),'success');
}
else
stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Unknown action"));
?>
