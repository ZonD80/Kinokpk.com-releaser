<?php
/**
 * Forum functions
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

function make_forum_tree($class) {
	global $CURUSER;
	return make_tree('forum_categories',"WHERE class <= $class");
}

function assoc_full_forum_cats($tree,$class) {
	if (!$tree) $tree = make_forum_tree($class);
	global $CURUSER;
	$return = assoc_full_cats('forum_categories');
	foreach ($return as $id=>$cat) {
		if ($cat['class']>$class) unset($return[$id]);
		else $return[$id]['route'] = get_cur_position_str($tree,$cat['id'],'forum'); 
	}
	return $return;
}

// curcat is branch
function prepare_for_forumtable($cats,$curcat = array('id'=>0)) {
	global $tree,$CURUSER, $REL_DB, $REL_CACHE, $REL_SEO;
	// making forums structure
	
	$return = $REL_CACHE->get('forums',"forum_structure_{$curcat['id']}-".get_user_class());
	
	if ($return===false) {
	if (!$tree) $tree = make_forum_tree(get_user_class());
	if ($curcat) $return['categories'][$curcat['id']] = $curcat['name'];
	foreach ($cats as $cat) {
		if (!$cat['parent_id'] && !$curcat) {
			$return['categories'][$cat['id']] = $cat['name'];
			$childs[$cat['id']] = get_full_childs_ids($tree, $cat['id'], 'forum_categories');
			if (!$cat['nodes']) $forums[$cat['id']] = $cat;
		}
		else {
		$forums[$cat['id']] = $cat;
	}
				if (!$curcat) {
		
					if ($childs)
		foreach ($childs as $cat=>$child) {
			foreach ($child as $id) {
				if ($forums[$id]) $return['forums'][$cat][$id] = $forums[$id];
			}
		}
			} else
			$return['forums'][$curcat['id']][$cat['id']] = $cat;
		}
	$REL_CACHE->set('forums', "forum_structure_{$curcat['id']}-".get_user_class(), $return);
	}
	// end, now making post counts and another
	
	$res = $REL_DB->query("SELECT forum_topics.category, SUM(forum_topics.comments) AS posts, SUM(1) AS topics, forum_topics.id, forum_topics.lastposted_id, forum_topics.subject, comments.user, comments.added, users.username, users.class FROM forum_topics LEFT JOIN comments ON forum_topics.lastposted_id=comments.id LEFT JOIN users ON comments.user = users.id WHERE comments.type='forum' GROUP BY forum_topics.category");
	while ($row = mysql_fetch_assoc($res)) {
		$return['forumsdata'][$row['category']] = array('posts'=>$row['posts'],'topics'=>$row['topics'] ,'user'=>"<a href=\"{$REL_SEO->make_link('userdetails','id',$row['user'],'name',$row['username'])}\">".get_user_class_color($row['class'], $row['username'])."</a>", 'added'=>$row['added'],'lastposted_id'=>$row['lastposted_id'],'subject'=>$row['subject'],'id'=>$row['id']);
	}
	return $return;
}

?>