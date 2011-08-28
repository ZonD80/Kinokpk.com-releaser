<?php
/**
 * Request viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

INIT();
loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] == 'POST')
$action = $_POST["action"];
else
$action = $_GET["action"];

$tree=make_tree();
if ($action == 'new') {
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$requesttitle = htmlspecialchars($_POST["requesttitle"]);
		if (!$requesttitle)
		$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Please specify name of request'));
		$request = $requesttitle;
		$descr = unesc($_POST["descr"]);
		if (!$descr)
		$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Please specify description of request'));
		if (!is_valid_id($_POST["category"]))
		$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Please specify request category'));
		$cat = (int) $_POST["category"];
		$request = $REL_DB->sqlesc($request);
		$descr = $REL_DB->sqlesc($descr);
		$cat = $REL_DB->sqlesc($cat);
		sql_query("INSERT INTO requests (hits,userid, cat, request, descr, added) VALUES(1,$CURUSER[id], $cat, $request, $descr, '" . time() . "')") or sqlerr(__FILE__,__LINE__);
		$id = mysql_insert_id();


		$REL_CACHE->clearGroupCache("block-req");

		@sql_query("INSERT INTO addedrequests VALUES(0, $id, $CURUSER[id])") or sqlerr(__FILE__, __LINE__);
		safe_redirect($REL_SEO->make_link('requests','id',$id));
		die;
	}
	$REL_TPL->stdhead($REL_LANG->_('Make new request'));

	print($REL_LANG->_('<h1>Make new request</h1><p>To view you request visit <a href="%s">this link</a></p><br />',$REL_SEO->make_link('viewrequests','requestorid',$CURUSER['id'])));
	?>
<table border=1 width=550 cellspacing=0 cellpadding=5>
	<tr>
		<td class=colhead align=left><?php print $REL_LANG->_('Search for releases (to verify, that there is no release you want)');?></td>
	</tr>
	<tr>
		<td align=left>
		<form method="get" action="<?=$REL_SEO->make_link('browse');?>"><input
			type="text" name="search" size="40"
			value="<?= htmlspecialchars($searchstr) ?>" />&nbsp;в&nbsp<?php
			print(gen_select_area('cat',$tree,(int)$_GET['cat'])."<input type=\"submit\" value=\"{$REL_LANG->_('Search')}!\">");
			print("</form>");
			print("</td></tr></table>");
			print("<form method=post action=\"".$REL_SEO->make_link('requests')."\" name=request>\n");
			print("<table border=1 cellspacing=0 cellpadding=5>\n");
			print("<tr><td class=colhead align=left colspan=2>{$REL_LANG->_('Fill requested fields')}</a></td><tr>\n");
			print("<tr><td align=left><b>{$REL_LANG->_('Name')}: </b><br /><input type=text size=80 name=requesttitle></td>");
			print("<td align=center><b>{$REL_LANG->_('Category')}: </b><br />");
			print(gen_select_area('category',$tree));
			print("</td></tr>");
			print("<br />\n");
			print("<tr><td align=center colspan=2><b>{$REL_LANG->_('Description')}: </b><br />\n");
			print textbbcode("descr");
			print ("<input type=hidden name=action value=new>");
			print("<tr><td align=center colspan=2><input type=submit value=\"{$REL_LANG->_('Go')}!\">\n");
			print("</form>\n");
			print("</table>\n");
			$REL_TPL->stdfoot();
			die;
}
if ($action == 'edit') {
	if ($_SERVER['REQUEST_METHOD']=='POST') {

		if (!is_valid_id($_POST["id"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
		$id = (int) $_POST["id"];
		$name = htmlspecialchars($_POST["requesttitle"]);
		$descr = $_POST["msg"];

		if (!is_valid_id($_POST["category"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
		$cat = (int) $_POST["category"];
		$name = $REL_DB->sqlesc($name);
		$descr = $REL_DB->sqlesc($descr);
		$cat = $REL_DB->sqlesc($cat);

		sql_query("UPDATE requests SET cat=$cat, request=$name, descr=$descr WHERE id=$id") or sqlerr(__FILE__, __LINE__);


		$REL_CACHE->clearGroupCache("block-req");

		safe_redirect($REL_SEO->make_link('requests','id',$id));
		$REL_TPL->stderr($REL_LANG->_('Successfully'),$REL_LANG->_('Request edited'),'success');
	}
	if (!is_valid_id($_GET["id"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$id = (int) $_GET["id"];

	$res = sql_query("SELECT * FROM requests WHERE id = $id");
	$row = mysql_fetch_array($res);
	if ($CURUSER["id"] != $row["userid"])
	{
		if (get_privilege('is_moderator'))
		$REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('You are not the owner of this request'));
	}
	$REL_TPL->stdhead($REL_LANG->_('Editing request "%s"',$row["request"]));
	if (!$row)
	die();
	$where = "WHERE userid = " . $CURUSER["id"] . "";
	$res2 = sql_query("SELECT * FROM requests $where") or sqlerr(__FILE__, __LINE__);
	$num2 = mysql_num_rows($res2);
	print("<form method=post name=form action=\"".$REL_SEO->make_link('requests')."\"></a>\n");
	print("<table border=1 width=560 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left>{$REL_LANG->_('Editing request "%s"',$row["request"])}</td><tr>\n");
	print("<tr><td align=left>{$REL_LANG->_('Name')}: <input type=text size=40 name=requesttitle value=\"" . htmlspecialchars($row["request"]) . "\">");

	print("&nbsp;{$REL_LANG->_('Category')}: ".gen_select_area("category",$tree,$row['cat'])."<p /><b>{$REL_LANG->_('Description')}</b>:<br />\n");
	print textbbcode("msg",$row["descr"]);
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	print("<input type=\"hidden\" name=\"action\" value=\"edit\">\n");
	print("<tr><td align=center><input type=submit value=\"{$REL_LANG->_('Save changes')}!\">\n");
	print("</form>\n");
	print("</table>\n");

	$REL_TPL->stdfoot();

	die;
}

if ($action=='reset')
{
	if (!is_valid_id($_GET["requestid"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$requestid = (int) $_GET["requestid"];
	$res = sql_query("SELECT userid, filledby FROM requests WHERE id =$requestid") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);
	if (($CURUSER[id] == $arr[userid]) || (get_privilege('requests_operation',false)) || ($CURUSER[id] == $arr[filledby]))
	{
		@sql_query("UPDATE requests SET filled='', filledby=0 WHERE id=$requestid") or sqlerr(__FILE__, __LINE__);


		$REL_CACHE->clearGroupCache("block-req");

		$REL_TPL->stderr($REL_LANG->say_by_key('success'),$REL_LANG->_('Request number %s was successfully resetted',$requestid),'success');
	}
	else
	$REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Sorry, but you can not reset this request'));

}

if ($action=='filled')
{
	$filledurl = (string)$_POST["filledurl"];
	if (!is_valid_id($_POST["requestid"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$requestid = (int) $_POST["requestid"];
	if (substr($filledurl, 0, mb_strlen($REL_CONFIG['defaultbaseurl'])) != $REL_CONFIG['defaultbaseurl'])
	{
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	}

	$res = sql_query("SELECT users.username, users.id, users.warned, users.donor, users.class, users.enabled, requests.userid, requests.request FROM requests INNER JOIN users ON requests.userid = users.id WHERE requests.id = " . $REL_DB->sqlesc($requestid)) or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);
	$filledurl = htmlspecialchars($filledurl);
	$msg = $REL_LANG->_to($arr['userid'],'Your request, <a href="%s"><b>%s</b></a> was done by %s. You can view it by <a href="%s"><b>visiting this link</b></a>. Please do not forget to rate this user and release. If this is not what you requested, or there are some reasons why this does not satisfy you, <a href="%s">visit this link</a>',$REL_SEO->make_link('requests','id',$requestid),$arr['request'],make_user_link(),$filledurl,$REL_SEO->make_link('requests','action','reset','requestid',$requestid));
	$subject = $REL_LANG->_to($arr['userid'],'Your request is done');
	sql_query ("UPDATE requests SET filled = " . $REL_DB->sqlesc($filledurl) . ", filledby = $CURUSER[id] WHERE id = " . $REL_DB->sqlesc($requestid)) or sqlerr(__FILE__, __LINE__);

	if ($REL_CRON['rating_enabled']) sql_query("UPDATE users SET ratingsum=ratingsum+{$REL_CRON['rating_perrequest']} WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);

	$REL_CACHE->clearGroupCache("block-req");

	write_sys_msg($arr['userid'], $msg, $subject);
	$REL_TPL->stderr($REL_LANG->say_by_key('success'),$REL_LANG->_('Request number %s was successfully done on <a href="%s">this link</a>. User %s will automatically receive message about it. If you entered invalid URL, please click <a href="%s">here</a>',$requestid,$filledurl,make_user_link($arr),$REL_SEO->make_link('requests','action','reset','requestid',$requestid)),'success');
}

if ($action == 'vote')
{
	if (!is_valid_id($_GET["voteid"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$requestid = (int) $_GET["voteid"];
	$userid = $CURUSER["id"];
	$res = sql_query("SELECT * FROM addedrequests WHERE requestid=$requestid AND userid = $userid") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);
	$voted = $arr;
	if ($voted) {
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('<p>You already woted for this request. Only one vote per request is allowed.</p><p>Go back to <a href="%s"><b>requests</b></a></p>',$REL_SEO->make_link('viewrequests')));
	} else {
		sql_query("UPDATE requests SET hits = hits + 1 WHERE id=$requestid") or sqlerr(__FILE__, __LINE__);
		@sql_query("INSERT INTO addedrequests VALUES(0, $requestid, $userid)") or sqlerr(__FILE__, __LINE__);


		$REL_CACHE->clearGroupCache("block-req");

		$REL_TPL->stderr($REL_LANG->_('Successfully'), $REL_LANG->_('<p>Your vote accepted.</p><p>Go back to <a href="%s"><b>requests</b></a></p>',$REL_SEO->make_link('viewrequests')),'success');
	}
}

if (!is_valid_id($_GET["id"])) 			$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
$id = (int) $_GET["id"];

$res = sql_query("SELECT * FROM requests WHERE id = $id") or sqlerr(__FILE__, __LINE__);
$num = mysql_fetch_array($res);

if (mysql_num_rows($res) == 0)
$REL_TPL->stderr ($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));


$s = $num["request"];
if (!pagercheck()) {
	$REL_TPL->stdhead($REL_LANG->_('Details of request "%s"',$s));

	print("<table width=\"600\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\">{$REL_LANG->_('Details of request "%s"',$s)}</td></tr>");
	print("<tr><td align=left>{$REL_LANG->_('Request')}</td><td width=90% align=left>$num[request]</td></tr>");
	print("<tr><td align=left>{$REL_LANG->_('Description')}</td><td width=90% align=left>" . format_comment($num["descr"]) . "</td></tr>");
	print("<tr><td align=left>{$REL_LANG->_('Added')}</td><td width=90% align=left>".mkprettytime($num[added])."</td></tr>");

	$cres = sql_query("SELECT username, id, class, warned, donor, enabled FROM users WHERE id=$num[userid]");

	$carr = mysql_fetch_assoc($cres);
	$username = $carr['username'];
	$user_req_id = $carr["id"];
	print("<tr><td align=left>{$REL_LANG->_('Requested by')}</td><td width=90% align=left>".make_user_link($carr)."</td></tr>");
	print("<tr><td align=left>{$REL_LANG->_('Vote for this request')}</td><td width=50% align=left><a href=\"".$REL_SEO->make_link('requests','action','vote','voteid',$id)."\"><b>{$REL_LANG->_('Vote!')}</b></a></td></tr></tr>");

	if ($num["filled"] == '')
	{
		print("<form method=post action=\"".$REL_SEO->make_link('requests')."\">");
		print("<tr><td align=left>{$REL_LANG->_('Satisfy request')}</td><td>{$REL_LANG->_('Enter <b>full</b> URL of release, e.g. "%s" (just copy&paste from another browser window/tab)',$REL_SEO->make_link('details','id',11,'name','lol'))}");
		print("<input type=text size=80 name=filledurl>\n");
		print("<input type=hidden value=$id name=requestid>");
		print("<input type=hidden name=action value=filled>");
		print("<input type=submit value=\"{$REL_LANG->_('Satisfy request')}\">\n</form></td></tr>");
	}
	if (get_privilege('requests_operation',false) || $CURUSER["id"] == $num["userid"])
	print("<tr><td align=left>{$REL_LANG->_('Options')}</td><td width=50% align=left><a OnClick=\"return confirm('{$REL_LANG->_('Are you sure?')}')\" href=\"".$REL_SEO->make_link('viewrequests','delreq[]',$id)."\">".$REL_LANG->say_by_key('delete')."</a> <b>|</b> <a href=\"".$REL_SEO->make_link('requests','action','reset','requestid',$id)."\">{$REL_LANG->_('Reset request')}</a>  <b>|</b>  <a href=\"".$REL_SEO->make_link('requests','action','edit','id',$id)."\">".$REL_LANG->say_by_key('edit')."</a></center></td></tr>");

	print("</table>");

	print("<p><a name=\"startcomments\"></a></p>\n");
}

$REL_TPL->assignByRef('to_id',$id);
$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $id, 'reqcomments' ));
$REL_TPL->assign('textbbcode',textbbcode('text'));
$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('Request'));
$FORM_TYPE = 'req';
$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
$REL_TPL->display('commenttable_form.tpl');

$count = get_row_count('comments', "WHERE toid = $id AND type='req'");

if (!$count) {
	print('<div id="newcomment_placeholder">'."<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: {$REL_LANG->_('List of comments')} ".is_i_notified($id,'reqcomments')."</div>");
	print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('requests','id',$id)."#comments\">{$REL_LANG->_('Add comment (%s)',$REL_LANG->_('Request'))}</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("{$REL_LANG->_('There are no comments')}. <a href=\"".$REL_SEO->make_link('requests','id',$id)."#comments\">{$REL_LANG->_('Do you want to add?')}</a>");
	print("</td></tr></table><br /></div>");

} else {
	$limit = ajaxpager(25, $count, array('requests','id',$id), 'comments-table > tbody:last');
	$subres = sql_query("SELECT c.type, c.id, c.ip, c.text, c.ratingsum, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
		"u.username, u.title, u.info, u.class, u.donor, u.ratingsum AS urating, u.enabled, s.time AS last_access, e.username AS editedbyname FROM comments c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id  LEFT JOIN sessions AS s ON s.uid=u.id WHERE c.toid = " .
		"$id AND c.type='req' GROUP BY c.id ORDER BY c.id DESC $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = prepare_for_commenttable($subres, $s,$REL_SEO->make_link('requests','id',$id));
	if (!pagercheck()) {
		print("<div id=\"pager_scrollbox\"><table id=\"comments-table\" class=main cellSpacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
		print("<tr><td class=\"colhead\" align=\"center\" >");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: {$REL_LANG->_('List of comments')}</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('requests','id',$id)."#comments\" class=altlink_white>{$REL_LANG->_('Add comment (%s)',$REL_LANG->_('Request'))}</a></div>");
		print("</td></tr>");
		

		print("<tr><td>");
		commenttable($allrows);
		print("</td></tr>");

		print("</table></div>");
	} else {
		print("<tr><td>");
		commenttable($allrows);
		print("</td></tr>");
		die();
	}
}

//print($commentbar);
$REL_TPL->stdfoot();

?>