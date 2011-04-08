<?php
/**
 * Forum processor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";
INIT();

if (!$REL_CONFIG['forum_enabled']) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Sorry, but forum disabled for now'));

require_once (ROOT_PATH.'/include/functions_forum.php');

$tree = make_forum_tree(get_user_class());
$cats = assoc_full_forum_cats($tree,get_user_class());
//print '<pre>'; print_r($cats);
$curcat = (int)$_GET['cat'];
if (!$curcat) $curcat=(int)$_POST['cat'];

/**
 * Makes values for forum routing and template
 * @param int $curcat ID of category
 */
function forum_routing($curcat) {
	global $tree,$cats,$REL_SEO,$REL_TPL,$REL_LANG,$CURUSER,$curcat,$view_topics, $CAT;//, $route;


	$REL_TPL->assignByRef('JUMP_TO', gen_select_area('cat',$tree,$curcat,true));
	$REL_TPL->assignByRef('CAT_SELECTOR', gen_select_area('cat',$tree,$curcat));
	$REL_TPL->assignByRef('curcat', $curcat);
	$REL_TPL->assign('IS_MODERATOR',get_privilege('edit_comments',false));

	$route = $REL_LANG->_('<a href="%s">Forum home</a>',$REL_SEO->make_link('forum'));
	//var_dump(in_array($curcat,array_keys($cats)));
	if (in_array($curcat,array_keys($cats))) {
		$CAT = get_cur_branch($tree, $curcat);
		if ($CAT['nodes']) $REL_TPL->assign('no_posting',true);
		$route .= ' / '.get_cur_position_str($tree, $curcat,'forum');

		$childs = get_full_childs_ids($tree, $curcat, 'forum_categories');
		$CAT['full_childs'] = $childs;
		//$childs[] = $curcat;
		$tree = get_childs($tree,$curcat);
		if ($tree) {
			foreach ($childs as $child) {
				$cats[$child]['route'] = get_cur_position_str($tree, $child, 'forum');
				$catss[$child] = $cats[$child];
			}
			$cats = $catss;
			unset($catss);
		} else {
			$view_topics = true;
		}
	} else $curcat=0;

	$REL_TPL->assignByRef('ROUTE',$route);
}

forum_routing($curcat);
//var_dump($CAT);

$action = (string)$_GET['a'];

if (!$action) {
	//var_dump($CAT['class']);
	if ($CAT&&!in_array(get_user_class(),$CAT['class'])) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Access denied'));
	$REL_TPL->stdhead($REL_LANG->_('Site forum'));

	if (!$view_topics) {
		$tabledata = prepare_for_forumtable($cats,$CAT);


		$REL_TPL->assignByRef('CATEGORIES',$tabledata['categories']);
		$REL_TPL->assignByRef('FORUMS',$tabledata['forums']);
		$REL_TPL->assignByRef('FORUMSDATA',$tabledata['forumsdata']);
		$REL_TPL->output();
	} else {

		$count = get_row_count('forum_topics',"WHERE category=$curcat");
		if ($count) {
			$res = $REL_DB->query("SELECT forum_topics.*,(SELECT users.username FROM users WHERE users.id=forum_topics.author) AS aname,(SELECT users.class FROM users WHERE users.id=forum_topics.author) AS aclass, users.username,users.class,comments.added,comments.user FROM forum_topics LEFT JOIN comments ON forum_topics.lastposted_id=comments.id LEFT JOIN users ON comments.user=users.id WHERE category=$curcat AND comments.type='forum' ORDER BY started DESC") or sqlerr(__FILE__,__LINE__);
			while ($row = mysql_fetch_assoc($res)) {
				$tabledata[] = array('id'=>$row['id'],'subject'=>$row['subject'],'posts'=>$row['comments'],'started'=>mkprettytime($row['started']),'author'=>"<a href=\"{$REL_SEO->make_link('userdetails','id',$row['author'],'name',$row['aname'])}\">".get_user_class_color($row['aclass'],$row['aname'])."</a>",'lastposted_time'=>mkprettytime($row['added']),'lastposted_user'=>"<a href=\"{$REL_SEO->make_link('userdetails','id',$row['user'],'name',$row['username'])}\">".get_user_class_color($row['class'],$row['username'])."</a>", 'lastposted_id'=>$row['lastposted_id']);
			}

			$REL_TPL->assignByRef('TOPICS',$tabledata);
			$REL_TPL->output('topicmode');
		}
		else $REL_TPL->stdmsg($REL_LANG->_('Notification'),$REL_LANG->_('There is no topics in this forum yet, but you can <a href="%s">create one</a>.',$REL_SEO->make_link('forum','a','newtopic','cat',$curcat)));
	}

	$REL_TPL->stdfoot();
}
elseif ($action=='newtopic') {
	loggedinorreturn();
	if ($_SERVER['REQUEST_METHOD']<>'POST') {
		$pagetitle = $REL_LANG->_('Post new topic');
		if ($curcat) $pagetitle.=" {$REL_LANG->_('to')} {$CAT['name']}";
		$REL_TPL->stdhead($pagetitle);
		$REL_TPL->assignByRef('postto',strip_tags($_SERVER['REQUEST_URI']));
		$REL_TPL->assignByRef('textbbcode',textbbcode('content'));
		$REL_TPL->output($action);
		$REL_TPL->stdfoot();
	} else {
		if (!$curcat) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No forum defined to post to, <a href="javascript.history.go(-1);">try again</a>'));
		if (!in_array(get_user_class(),$CAT['class'])) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Access denied'));

		if ($CAT['nodes']) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('You can not post to categories. Only forums enabled. Please <a href="javascript.history.go(-1);">try again</a>'));
		$topictitle = makesafe((string)$_POST['title']);
		if (!$topictitle) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No topic title defined, <a href="javascript.history.go(-1);">try again</a>'));
		$topiccontent = trim((string)$_POST['content']);
		if (!$topiccontent) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No topic content defined, <a href="javascript.history.go(-1);">try again</a>'));
		if (get_privilege('is_moderator')) {
			$closedate = (int)strtotime((string)$_POST['closedate']);
		} else $closedate = 0;
		sql_query("INSERT INTO forum_topics (subject,comments,author,started,closedate,category) VALUES (".sqlesc($topictitle).", 1, {$CURUSER['id']}, ".time().", $closedate, $curcat)") or sqlerr(__FILE__,__LINE__);
		$to_id= mysql_insert_id();
		sql_query("INSERT INTO comments (user, toid, added, text, ip, type) VALUES (" .
		$CURUSER["id"] . ",$to_id, '" . time() . "', " . sqlesc($topiccontent) .
	       "," . sqlesc(getip()) . ", 'forum')") or sqlerr(__FILE__,__LINE__);
		$newid = mysql_insert_id();
		sql_query("UPDATE forum_topics SET lastposted_id=$newid WHERE id=$to_id") or sqlerr(__FILE__,__LINE__);
		$topiclink = $REL_SEO->make_link('forum','a','viewtopic','id',$to_id,'subject',translit($topictitle));
		safe_redirect($topiclink,2);
		$REL_TPL->stderr($REL_LANG->_('Successfully'),$REL_LANG->_('Topic with title "%s" in "%s" successfully created, you will be reditected to it in 2 seconds. If not, click <a href="%s">on this link</a>',$topictitle,$CAT['name'],$topiclink),'success');
	}
}
elseif ($action=='viewtopic') {
	$tid = (int)$_GET['id'];
	$tname = htmlspecialchars(trim((string)$_GET['subject']));
	$post = (int)$_GET['p'];
	if (!$tid && !$tname) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No topic found'));
	$res = $REL_DB->query("SELECT * FROM forum_topics WHERE ".($tid?"id = $tid":"subject = ".sqlesc($tname))." LIMIT 1") or sqlerr(__FILE__,__LINE__);
	$topic = mysql_fetch_assoc($res);
	if (!$topic) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No topic found'));

	if (!pagercheck()) {
		//var_dump($topic['category']);
		$curcat = $topic['category'];
		forum_routing($curcat);
		//var_dump($CAT);
		if (!$CAT || !in_array(get_user_class(),$CAT['class'])) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Access denied'));

		$REL_TPL->assignByRef('topic', $topic);

		$res = $REL_DB->query("SELECT GROUP_CONCAT(id) AS posts FROM comments WHERE toid={$topic['id']} AND type='forum' ORDER BY id ASC") or sqlerr(__FILE__,__LINE__);
		$postids = @mysql_result($res,0);
		if (!$postids) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No posts found in this topic. Contact <a href="%s">Site administrators</a>',$REL_SEO->make_link('staff')));

		$REL_TPL->stdhead($REL_LANG->_('Viewing topic: %s',$topic['subject']));
		$limited = 25;
		$postids = explode(',',$postids);
		$count = count($postids);
	}
	$limit = ajaxpager(25, $count, array('forum','a','viewtopic','id',$topic['id'],'subject',translit($topic['subject'])), "forumcomments");
	$subres = sql_query ( "SELECT c.id, c.ip, c.ratingsum, c.text, c.type, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.info, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN sessions ON c.user=sessions.uid LEFT JOIN users AS e ON c.editedby = e.id WHERE c.toid = {$topic['id']} AND c.type='forum' GROUP BY c.id ORDER BY c.id DESC $limit" ) or sqlerr ( __FILE__, __LINE__ );
	$allrows = prepare_for_commenttable($subres,$topic['subject'],$REL_SEO->make_link('forum','a','viewtopic','id',$topic['id'],'subject',translit($topic['subject'])));

	if (pagercheck()) { print commenttable($allrows,true,'modules/forum/commenttable.tpl'); die(); }
	if ($CURUSER) {
		$REL_TPL->assignByRef('to_id',$topic['id']);
		$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $topic['id'], 'forumcomments' ));
		$REL_TPL->assign('textbbcode',textbbcode('text'));
		$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('Forum topic'));
		$FORM_TYPE = 'forum';
		$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
		$REL_TPL->display('commenttable_form.tpl');
		//print '<pre>';
		//print_r($_SESSION);
	}
	$REL_TPL->assignByRef('commenttable', commenttable($allrows,true,'modules/forum/commenttable.tpl'));
	$REL_TPL->output($action);

	$REL_TPL->stdfoot();
}
else $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown action'));

?>