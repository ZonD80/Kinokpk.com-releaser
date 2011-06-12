<?php
/**
 * This is release groups admin interface.
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();
loggedinorreturn();
get_privilege('relgroups_admin');
httpauth();


$fields = explode(',','name,spec,image,owners,members,private,page_pay,amount,only_invites,subscribe_length,descr');

function process_values() {
	global $fields, $REL_LANG;
	$fields_to_strip = explode(',','name,spec,image,owners,members,page_pay');
	$fields_to_bool = explode(',','private,only_invites');
	$fields_to_int = array('subscribe_length','amount');
	foreach ($fields as $field) {
		if (in_array($field,$fields_to_strip)) {
			if (!isset($_POST[$field])) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_value').$REL_LANG->say_by_key('to_rgadmin'));
			$return[$field] = htmlspecialchars(trim($_POST[$field]));
		}
		elseif (in_array($field,$fields_to_bool)) $return[$field] = $_POST[$field]?1:0;
		elseif (in_array($field,$fields_to_int)) $return[$field] = (int)$_POST[$field];
	}
	$return['descr'] = (string)$_POST['descr'];
	return $return;
}

$a = (string)$_GET['a'];
$id = (int)$_GET['id'];
if (!$id) $id = (int)$_POST['id'];

if ($id && (!is_valid_id($id) || !@mysql_result(sql_query("SELECT 1 FROM relgroups WHERE id = $id"),0))) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));


if (!$a) {

	$REL_TPL->stdhead($REL_LANG->say_by_key('rg_title'));
	$res = sql_query("SELECT relgroups.*, COUNT(rg_subscribes.id) AS users FROM relgroups LEFT JOIN rg_subscribes ON relgroups.id=rg_subscribes.rgid GROUP BY relgroups.id ORDER BY relgroups.added DESC");
	$REL_TPL->begin_frame($REL_LANG->say_by_key('relgroups').$REL_LANG->say_by_key('relgroupsadd'));

	print ("<table with=\"100%\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$REL_LANG->say_by_key('name')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('added')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('spec')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('descr')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('image')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('owners')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('members')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('private')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('nonfree')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('page_pay')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('subscribe_length')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('users')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('actions')}</td></tr>");
	while ($row = mysql_fetch_assoc($res)) {
		$groups=true;
		$rgarray[] = $row;
		if ($row['owners']) $uidsarray[$row['id']] = $row['owners'];
		if ($row['members']) $memarray[$row['id']] = $row['members'];
	}

	if (!$rgarray) { print '<tr><td colspan="14">'.$REL_LANG->say_by_key('no_relgroups').'</td></tr>'; $REL_TPL->end_frame(); $REL_TPL->stdfoot(); die(); }


	$ownres = sql_query("SELECT id,username,class, warned, donor, enabled FROM users WHERE id IN(".implode(',',($memarray?array_merge($uidsarray,$memarray):$uidsarray)).")") or sqlerr(__FILE__,__LINE__);

	while ($owner = mysql_fetch_array($ownres)) {
		foreach ($uidsarray as $uar)
		if (in_array($owner['id'],explode(',',$uar)))
		$owners[$owner['id']] = make_user_link($owner);
		else
		$members[$owner['id']] = make_user_link($owner);

	}

	foreach ($rgarray as $row) {
		$rgown=array();
		$rgmemb=array();

		$row['owners']=explode(',',$row['owners']);
		foreach ($row['owners'] as $owner){
			$rgown[] = $owners[$owner];
		}
		$rgown=implode(', ',$rgown);

		if ($row['members']) {
			$row['members']=explode(',',$row['members']);

			foreach ($row['members'] as $member){
				$rgmemb[] = $members[$member];
			}

			$rgmemb=implode(', ',$rgmemb);
		} else $rgmemb = $REL_LANG->say_by_key('no');

		print ("<tr><td>{$row['id']}</td><td><a href=\"".$REL_SEO->make_link('relgroups','id',$row['id'],'name',translit($row['name']))."\">{$row['name']}</a></td><td>".mkprettytime($row['added'])."</td><td>{$row['spec']}</td><td>".format_comment($row['descr'])."</td><td>".($row['image']?"<img src=\"{$row['image']}\" title=\"{$row['name']}\"/>":$REL_LANG->say_by_key('no'))."</td><td>$rgown</td><td>$rgmemb</td><td>".($row['private']?$REL_LANG->say_by_key('yes'):$REL_LANG->say_by_key('no'))."</td><td>".($row['page_pay']?$REL_LANG->say_by_key('yes'):$REL_LANG->say_by_key('no'))."</td><td>".($row['page_pay']?$row['page_pay']:$REL_LANG->say_by_key('no'))."</td><td>{$row['subscribe_length']}</td><td><a href=\"".$REL_SEO->make_link('rgadmin','id',$row['id'],'a','users')."\">{$row['users']}</a></td><td><a href=\"".$REL_SEO->make_link('rgadmin','id',$row['id'],'a','edit')."\">E</a> / <a href=\"".$REL_SEO->make_link('rgadmin','id',$row['id'],'a','delete')."\" onclick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}');\">D</a> | <a href=\"".$REL_SEO->make_link('rgadmin','id',$row['id'],'a','users')."\">{$REL_LANG->say_by_key('view_users')}</a> / <a href=\"".$REL_SEO->make_link('rgadmin','id',$row['id'],'a','deleteusers')."\" onclick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}');\">{$REL_LANG->say_by_key('delete_all_users')}</a></td></tr>");
	}
	print ('</table>');
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}
elseif ($a == 'add' || $a == 'edit') {

	$REL_TPL->stdhead($REL_LANG->say_by_key('rg_title'));
	if ($a == 'edit') {
		$groupsql = sql_query("SELECT * FROM relgroups WHERE id=$id");
		$group = mysql_fetch_assoc($groupsql);
	}
	$REL_TPL->begin_frame($REL_LANG->say_by_key($a.'_group').$REL_LANG->say_by_key('to_rgadmin'));
	print ('<form method="post" action="'.$REL_SEO->make_link('rgadmin','a',"save$a").'"><p>'.$REL_LANG->say_by_key('rg_faq').'</p><table width="100%"><input type="hidden" name="id" value="'.$id.'">');

	foreach ($fields as $key) {

		if ($key=='private'||$key=='only_invites')
		print("<tr><td>".$REL_LANG->say_by_key($key)."</td><td><input type=\"checkbox\" name=\"$key\"".(($a=='edit' && $group[$key])?" checked=\"1\"":'')."></td></tr>");
		elseif($key=='descr')
		print("<tr><td>".$REL_LANG->say_by_key($key)."</td><td>".textbbcode($key,$group[$key])."</td></tr>");
		else
		print("<tr><td>".$REL_LANG->say_by_key($key)."</td><td><input type=\"text\" name=\"$key\"".(($a=='edit')?" value=\"{$group[$key]}\"":'').">".((($key=='members')||($key=='owners'))?$REL_LANG->say_by_key('comma_separated'):'')."</td></tr>");

	}
	print '<tr><td colspan="2" align="center"><input type="submit" value="'.$REL_LANG->say_by_key('continue').'"></td></tr></table></form>';
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();

}
elseif ($a == 'saveadd') {
	$array = process_values();
	$array = array_map("sqlesc",$array);

	sql_query("INSERT INTO relgroups (".implode(',',$fields).",added) VALUES (".implode(',',$array).",".time().")");// or sqlerr(__FILE__,__LINE__);
	if (mysql_errno()) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('group_error').$REL_LANG->say_by_key('to_rgadmin'));
	else {
		$id = mysql_insert_id();
		safe_redirect($REL_SEO->make_link('relgroups','id',$id),3);
		stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('group_added'),'success');
	}

}
elseif ($a == 'saveedit') {
	$array = process_values();
	$array = array_map("sqlesc",$array);
	foreach ($array as $key => $value) {
		$string[] ="$key = $value";
	}
	$string = implode(',',$string);
	sql_query("UPDATE relgroups SET $string WHERE id = $id") or sqlerr(__FILE__,__LINE__);
	safe_redirect($REL_SEO->make_link('relgroups','id',$id),3);
	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('group_edited'),'success');
}
elseif ($a == 'deleteusers') {
	sql_query("DELETE FROM rg_subscribes WHERE rgid = $id") or sqlerr(__FILE__,__LINE__);
	safe_redirect($REL_SEO->make_link('rgadmin'),3);
	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('users_deleted'),'success');
}
elseif ($a == 'users') {
	$REL_TPL->stdhead($REL_LANG->say_by_key('view_users'));
	$count = get_row_count('rg_subscribes',"WHERE rgid=$id");

	$res = sql_query("SELECT rg_subscribes.*, users.username, users.class, users.warned, users.donor, users.enabled FROM rg_subscribes LEFT JOIN users ON rg_subscribes.userid=users.id WHERE rgid = $id ORDER BY valid_until ASC $LIMIT") or sqlerr(__FILE__,__LINE__);
	$REL_TPL->begin_frame($REL_LANG->say_by_key('view_users'). ' '.@mysql_result(sql_query("SELECT name FROM relgroups WHERE id = $id"),0).$REL_LANG->say_by_key('to_rgadmin'));
	begin_table();

	print ('<tr><td class="colhead">'.$REL_LANG->say_by_key('signup_username').'</td><td class="colhead">'.$REL_LANG->say_by_key('subscribe_until').'</td><td class="colhead">'.$REL_LANG->say_by_key('actions').'</td></tr>');

	while ($row = mysql_fetch_assoc($res)) {
		$has_users = true;
		$user = $row;
		$user['id'] = $user['userid'];
		print ("<tr><td>".make_user_link($user)."</td><td>".($row['valid_until']?mkprettytime($row['valid_until'])."{$REL_LANG->say_by_key('in_time')}".get_elapsed_time($row['valid_until']):$REL_LANG->say_by_key('never'))."</td><td><a href=\"".$REL_SEO->make_link('rgadmin','id',$id,'a','deleteuser','userid',$row['userid'])."\" onclick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}');\">D</a> / <a href=\"".$REL_SEO->make_link('rgadmin','id',$id,'a','deleteuser','userid',$row['userid'],'notify','')."\" onclick=\"return confirm('{$REL_LANG->say_by_key('are_you_sure')}');\">{$REL_LANG->say_by_key('delete_with_notify')}</a></td></tr>");
	}
	if (!$has_users) print '<tr><td colspan="4" align="center">'.$REL_LANG->say_by_key('no_users').'</td></tr>';
	print("<tr><td class=\"index\" colspan=\"4\">");
	print("</td></tr>");
	$REL_TPL->end_table();
	$REL_TPL->end_frame();
}
elseif ($a == 'deleteuser') {
	$userid = (int)$_GET['userid'];
	$groupsql = sql_query("SELECT name FROM relgroups WHERE id=$id");
	$group = mysql_fetch_assoc($groupsql);
	if (!$userid) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	$notify=isset($_GET['notify']);
	sql_query("DELETE FROM rg_subscribes WHERE userid=$userid AND rgid=$id") or sqlerr(__FILE__,__LINE__);
	if ($notify) write_sys_msg($userid,sprintf($REL_LANG->say_by_key_to($userid,'delete_notify'),"<a href=\"".$REL_SEO->make_link('relgroups','id',$id,'name',translit($group['name']))."\">{$group['name']}</a>"),$REL_LANG->say_by_key_to($userid,'notify_subject'));
	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('delete_user_ok').($notify?" {$REL_LANG->say_by_key('notify_send')}":''),'success');

}
elseif ($a == 'delete') {
	sql_query("DELETE FROM relgroups WHERE id=$id") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE torrents SET relgroup=0 WHERE relgroup=$id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM rg_subscribes WHERE rgid=$id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM comments WHERE toid=$id AND type='rg'") or sqlerr(__FILE__,__LINE__);
	$newstodeletesql = sql_query("SELECT comments.id FROM comments LEFT JOIN rgnews ON comments.toid=rgnews.id WHERE rgnews.relgroup=$id AND comments.type='rgnews'") or sqlerr(__FILE__,__LINE__);
	while(list($nid) = mysql_fetch_array($newstodeletesql)) $nids[] = $id;
	sql_query("DELETE FROM rgnews WHERE relgroup=$id") or sqlerr(__FILE__,__LINE__);
	if ($nids) sql_query("DELETE FROM comments WHERE toid IN (".implode(',',$nids).") AND type='rgnews'") or sqlerr(__FILE__,__LINE__);
	safe_redirect($REL_SEO->make_link('rgadmin'),1);
	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('relgroup_deleted'),'success');
}
else stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('unknown_action'));

?>