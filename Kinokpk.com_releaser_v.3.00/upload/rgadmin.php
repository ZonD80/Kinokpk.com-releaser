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
dbconn();
loggedinorreturn();
if (get_user_class() < UC_ADMINISTRATOR) stderr($tracker_lang['error'],$tracker_lang['access_denied']);
httpauth();
getlang('rgadmin');

$fields = explode(',','name,spec,image,owners,members,private,page_pay,amount,only_invites,subscribe_length,descr');

function process_values() {
	global $fields, $tracker_lang;
	$fields_to_strip = explode(',','name,spec,image,owners,members,page_pay');
	$fields_to_bool = explode(',','private,only_invites');
	$fields_to_int = array('subscribe_length','amount');
	foreach ($fields as $field) {
		if (in_array($field,$fields_to_strip)) {
			if (!isset($_POST[$field])) stderr($tracker_lang['error'],$tracker_lang['no_value'].$tracker_lang['to_rgadmin']);
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

if ($id && (!is_valid_id($id) || !@mysql_result(sql_query("SELECT 1 FROM relgroups WHERE id = $id"),0))) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);


if (!$a) {

	stdhead($tracker_lang['rg_title']);
	$res = sql_query("SELECT relgroups.*, COUNT(rg_subscribes.id) AS users FROM relgroups LEFT JOIN rg_subscribes ON relgroups.id=rg_subscribes.rgid GROUP BY relgroups.id ORDER BY relgroups.added DESC");
	begin_frame($tracker_lang['relgroups'].$tracker_lang['relgroupsadd']);

	print ("<table with=\"100%\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$tracker_lang['name']}</td><td class=\"colhead\">{$tracker_lang['added']}</td><td class=\"colhead\">{$tracker_lang['spec']}</td><td class=\"colhead\">{$tracker_lang['descr']}</td><td class=\"colhead\">{$tracker_lang['image']}</td><td class=\"colhead\">{$tracker_lang['owners']}</td><td class=\"colhead\">{$tracker_lang['members']}</td><td class=\"colhead\">{$tracker_lang['private']}</td><td class=\"colhead\">{$tracker_lang['nonfree']}</td><td class=\"colhead\">{$tracker_lang['page_pay']}</td><td class=\"colhead\">{$tracker_lang['subscribe_length']}</td><td class=\"colhead\">{$tracker_lang['users']}</td><td class=\"colhead\">{$tracker_lang['actions']}</td></tr>");
	while ($row = mysql_fetch_assoc($res)) {
		$groups=true;
		$rgarray[] = $row;
		$uidsarray[$row['id']] = $row['owners'];
		if ($row['members']) $memarray[$row['id']] = $row['members'];
	}

	if (!$rgarray) { print '<tr><td colspan="14">'.$tracker_lang['no_relgroups'].'</td></tr>'; end_frame(); stdfoot(); die(); }


	$ownres = sql_query("SELECT id,username,class FROM users WHERE id IN(".implode(',',($memarray?array_merge($uidsarray,$memarray):$uidsarray)).")") or sqlerr(__FILE__,__LINE__);

	while ($owner = mysql_fetch_array($ownres)) {
		foreach ($uidsarray as $uar)
		if (in_array($owner['id'],explode(',',$uar)))
		$owners[$owner['id']] = "<a href=\"userdetails.php?id={$owner['id']}\">".get_user_class_color($owner['class'],$owner['username'])."</a>";
		else
		$members[$owner['id']] = "<a href=\"userdetails.php?id={$owner['id']}\">".get_user_class_color($owner['class'],$owner['username'])."</a>";

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
		} else $rgmemb = $tracker_lang['no'];

		print ("<tr><td>{$row['id']}</td><td><a href=\"relgroups.php?id={$row['id']}\">{$row['name']}</a></td><td>".mkprettytime($row['added'])."</td><td>{$row['spec']}</td><td>".format_comment($row['descr'])."</td><td>".($row['image']?"<img src=\"{$row['image']}\" title=\"{$row['name']}\"/>":$tracker_lang['no'])."</td><td>$rgown</td><td>$rgmemb</td><td>".($row['private']?$tracker_lang['yes']:$tracker_lang['no'])."</td><td>".($row['page_pay']?$tracker_lang['yes']:$tracker_lang['no'])."</td><td>".($row['page_pay']?$row['page_pay']:$tracker_lang['no'])."</td><td>{$row['subscribe_length']}</td><td><a href=\"rgadmin.php?id={$row['id']}&a=users\">{$row['users']}</a></td><td><a href=\"rgadmin.php?id={$row['id']}&a=edit\">E</a> / <a href=\"rgadmin.php?id={$row['id']}&a=delete\" onclick=\"return confirm('{$tracker_lang['are_you_sure']}');\">D</a> | <a href=\"rgadmin.php?id={$row['id']}&a=users\">{$tracker_lang['view_users']}</a> / <a href=\"rgadmin.php?id={$row['id']}&a=deleteusers\" onclick=\"return confirm('{$tracker_lang['are_you_sure']}');\">{$tracker_lang['delete_all_users']}</a></td></tr>");
	}
	print ('</table>');
	end_frame();
	stdfoot();
	die();
}
elseif ($a == 'add' || $a == 'edit') {

	stdhead($tracker_lang['rg_title']);
	if ($a == 'edit') {
		$groupsql = sql_query("SELECT * FROM relgroups WHERE id=$id");
		$group = mysql_fetch_assoc($groupsql);
	}
	begin_frame($tracker_lang[$a.'_group'].$tracker_lang['to_rgadmin']);
	print ('<form method="post" action="rgadmin.php?a=save'.$a.'"><p>'.$tracker_lang['rg_faq'].'</p><table width="100%"><input type="hidden" name="id" value="'.$id.'">');

	foreach ($fields as $key) {

		if ($key=='private'||$key=='only_invites')
		print("<tr><td>".$tracker_lang[$key]."</td><td><input type=\"checkbox\" name=\"$key\"".(($a=='edit' && $group[$key])?" checked=\"1\"":'')."></td></tr>");
		elseif($key=='descr')
		print("<tr><td>".$tracker_lang[$key]."</td><td>".textbbcode($key,$group[$key])."</td></tr>");
		else
		print("<tr><td>".$tracker_lang[$key]."</td><td><input type=\"text\" name=\"$key\"".(($a=='edit')?" value=\"{$group[$key]}\"":'').">".((($key=='members')||($key=='owners'))?$tracker_lang['comma_separated']:'')."</td></tr>");

	}
	print '<tr><td colspan="2" align="center"><input type="submit" value="'.$tracker_lang['continue'].'"></td></tr></table></form>';
	end_frame();
	stdfoot();
	die();

}
elseif ($a == 'saveadd') {
	$array = process_values();
	$array = array_map("sqlesc",$array);

	sql_query("INSERT INTO relgroups (".implode(',',$fields).",added) VALUES (".implode(',',$array).",".time().")");// or sqlerr(__FILE__,__LINE__);
	if (mysql_errno()) stderr($tracker_lang['error'],$tracker_lang['group_error'].$tracker_lang['to_rgadmin']);
	else {
		$id = mysql_insert_id();
		safe_redirect("relgroups.php?id=$id",3);
		stderr($tracker_lang['success'],$tracker_lang['group_added'],'success');
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
	safe_redirect("relgroups.php?id=$id",3);
	stderr($tracker_lang['success'],$tracker_lang['group_edited'],'success');
}
elseif ($a == 'deleteusers') {
	sql_query("DELETE FROM rg_subscribes WHERE rgid = $id") or sqlerr(__FILE__,__LINE__);
	safe_redirect('rgadmin.php',3);
	stderr($tracker_lang['success'],$tracker_lang['users_deleted'],'success');
}
elseif ($a == 'users') {
	stdhead($tracker_lang['view_users']);
	$count = get_row_count('rg_subscribes',"WHERE rgid=$id");
	list($pagertop, $pagerbottom, $limit) = pager(30, $count, "rgadmin.php?id=$id&amp;".$addparam);

	$res = sql_query("SELECT rg_subscribes.*, users.username, users.class FROM rg_subscribes LEFT JOIN users ON rg_subscribes.userid=users.id WHERE rgid = $id ORDER BY valid_until ASC $LIMIT") or sqlerr(__FILE__,__LINE__);
	begin_frame($tracker_lang['view_users']. ' '.@mysql_result(sql_query("SELECT name FROM relgroups WHERE id = $id"),0).$tracker_lang['to_rgadmin']);
	begin_table();

	print ('<tr><td class="colhead">'.$tracker_lang['signup_username'].'</td><td class="colhead">'.$tracker_lang['subscribe_until'].'</td><td class="colhead">'.$tracker_lang['actions'].'</td></tr>');
	print("<tr><td class=\"index\" colspan=\"4\">");
	print($pagertop);
	print("</td></tr>");
	while ($row = mysql_fetch_assoc($res)) {
		$has_users = true;
		print ("<tr><td><a href=\"userdetails.php?id={$row['userid']}\">".get_user_class_color($row['class'],$row['username'])."</a></td><td>".($row['valid_until']?mkprettytime($row['valid_until'])."{$tracker_lang['in_time']}".get_elapsed_time($row['valid_until']):$tracker_lang['never'])."</td><td><a href=\"rgadmin.php?id=$id&a=deleteuser&userid={$row['userid']}\" onclick=\"return confirm('{$tracker_lang['are_you_sure']}');\">D</a> / <a href=\"rgadmin.php?id=$id&a=deleteuser&userid={$row['userid']}&notify\" onclick=\"return confirm('{$tracker_lang['are_you_sure']}');\">{$tracker_lang['delete_with_notify']}</a></td></tr>");
	}
	if (!$has_users) print '<tr><td colspan="4" align="center">'.$tracker_lang['no_users'].'</td></tr>';
	print("<tr><td class=\"index\" colspan=\"4\">");
	print($pagerbottom);
	print("</td></tr>");
	end_table();
	end_frame();
}
elseif ($a == 'deleteuser') {
	$userid = (int)$_GET['userid'];
	$groupsql = sql_query("SELECT name FROM relgroups WHERE id=$id");
	$group = mysql_fetch_assoc($groupsql);
	if (!$userid) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
	$notify=isset($_GET['notify']);
	sql_query("DELETE FROM rg_subscribes WHERE userid=$userid AND rgid=$id") or sqlerr(__FILE__,__LINE__);
	if ($notify) write_sys_msg($userid,sprintf($tracker_lang['delete_notify'],"<a href=\"relgroups.php?id=$id\">{$group['name']}</a>"),$tracker_lang['notify_subject']);
	stderr($tracker_lang['success'],$tracker_lang['delete_user_ok'].($notify?" {$tracker_lang['notify_send']}":''),'success');

}
elseif ($a == 'delete') {
	sql_query("DELETE FROM relgroups WHERE id=$id") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE torrents SET relgroup=0 WHERE relgroup=$id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM rg_subscribes WHERE rgid=$id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM rgcomments WHERE relgroup=$id") or sqlerr(__FILE__,__LINE__);
	$newstodeletesql = sql_query("SELECT rgnewscomments.id FROM rgnewscomments LEFT JOIN rgnews ON rgnewscomments.rgnews=rgnews.id WHERE rgnews.relgroup=$id") or sqlerr(__FILE__,__LINE__);
	while(list($nid) = mysql_fetch_array($newstodeletesql)) $nids[] = $id;
	sql_query("DELETE FROM rgnews WHERE relgroup=$id") or sqlerr(__FILE__,__LINE__);
	if ($nids) sql_query("DELETE FROM rgnewscomments WHERE rgnews IN (".implode(',',$nids).")") or sqlerr(__FILE__,__LINE__);
	safe_redirect('rgadmin.php',1);
	stderr($tracker_lang['success'],$tracker_lang['relgroup_deleted'],'success');
}
else stderr($tracker_lang['error'],$tracker_lang['unknown_action']);

?>