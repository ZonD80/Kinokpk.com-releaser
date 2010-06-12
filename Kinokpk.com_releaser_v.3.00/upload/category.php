<?php
/**
 * Category administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
getlang('category');
loggedinorreturn();
httpauth();
if (get_user_class() < UC_SYSOP) {
	stderr($tracker_lang['error'],$tracker_lang['access_denied']);
}


$tree = make_tree();

stdhead($tracker_lang['category_admin']);
$catsrow = sql_query("SELECT id,name FROM categories ORDER BY sort ASC");
while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=get_cur_position_str($tree,$catres['id']);

print("<h1><a href=\"category.php\">{$tracker_lang['category_admin']}</a></h1>\n");
print("<br /><a href=\"category.php?add\">{$tracker_lang['add_new_category']}</a><br />");

///////////////////// D E L E T E  CATEGORY \\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_GET['delid'])) {
	if (!is_valid_id($_GET['delid'])) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	$delid = (int) $_GET['delid'];

	sql_query("DELETE FROM categories WHERE id=" .sqlesc($delid) . " LIMIT 1");
	$CACHE->clearGroupCache('trees');
	stdmsg($tracker_lang['success'],$tracker_lang['category_success_delete']);
	stdfoot();
	die();
}

///////////////////// E  D  I  T  A  CATEGORY \\\\\\\\\\\\\\\\\\\\\\\\\\\\
elseif (isset($_GET['edited'])) {
	if (!is_valid_id($_GET['id'])) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

	$category_name = sqlesc(htmlspecialchars((string)$_POST['category_name']));
	$category_id = sqlesc((int)$_GET['id']);
	$category_parent = sqlesc((int)$_POST['category_parent']);
	$category_forumid = sqlesc((int)$_POST['category_forumid']);
	$category_pic = sqlesc(htmlspecialchars((string)$_POST['category_pic']));
	$category_export = sqlesc((int)$_POST['category_export']);
	$category_sort = sqlesc((int)$_POST['category_sort']);
	sql_query("UPDATE categories SET
name = $category_name,
parent_id = $category_parent, forum_id=$category_forumid, image=$category_pic, disable_export=$category_export,sort=$category_sort WHERE id=$category_id") or sqlerr(__FILE__,__LINE__);
	stdmsg($tracker_lang['success'],$tracker_lang['category_success_edit']);
	$CACHE->clearGroupCache('trees');
	stdfoot();
	die();
}

elseif (isset($_GET['editid'])) {
	if (!is_valid_id($_GET['editid'])) { stdmsg($tracker_lang['error'],$tracker_lang['invalid_id'],'error'); stdfoot(); die(); }
	$cid = (int) $_GET['editid'];
	$res = sql_query("SELECT * FROM categories WHERE id=$cid");
	$row = mysql_fetch_array($res);

	$catselect='<select name="category_parent"><option value="0">'.$tracker_lang['no_parent'].'</option>';
	foreach ($cats as $id=>$cat) {
		$catselect.='<option value="'.$id.'"'.(($row['parent_id']==$id)?' selected':'').'>'.$cat.'</option>';
	}
	$catselect .='</select>';
	print("<form name='form1' method='post' action='category.php?edited&id=$cid'>");
	print("<div align='center'>{$tracker_lang['you_edit']} <strong>{$row['name']}</strong></div>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$tracker_lang['name']}:</td><td align='left'><input type='text' size=60 name='category_name' value='{$row['name']}'></td></tr>");
	print("<tr><td>{$tracker_lang['parent']}:</td><td align='left'>$catselect</td></tr>");
	print("<tr><td>{$tracker_lang['image']}:</td><td align='left'><input type='text' size=60 name='category_pic' value='{$row['image']}'></td></tr>");
	print("<tr><td>{$tracker_lang['sort']}</td><td align='left'><input type='text' size=60 name='category_sort' value='{$row['sort']}'></td></tr>");
	print("<tr><td>{$tracker_lang['export_id']}</td><td align='left'><input type='text' size=60 name='category_forumid' value='{$row['forum_id']}'></td></tr>");
	print("<tr><td>{$tracker_lang['disable_export']}</td><td align='left'><select name=\"category_export\"><option value=\"0\"".(!$row['disable_export']?' selected':'').">{$tracker_lang['no']}</option><option value=\"1\"".($row['disable_export']?' selected':'').">{$tracker_lang['yes']}</option></select></td></tr>");

	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$tracker_lang['edit']}'></div></td></tr>");
	print("</table></form>");
	stdfoot();
	die();
}


if(isset($_GET['added'])) {
	$category_name = sqlesc(htmlspecialchars((string)$_POST['category_name']));
	$category_parent = sqlesc((int)$_POST['category_parent']);
	$category_forumid = sqlesc((int)$_POST['category_forumid']);
	$category_pic = sqlesc(htmlspecialchars((string)$_POST['category_pic']));
	$category_export = sqlesc((int)$_POST['category_export']);
	$category_sort = sqlesc((int)$_POST['category_sort']);
	sql_query("INSERT INTO categories (name,image,parent_id,forum_id,disable_export,sort) VALUES ($category_name,$category_pic,$category_parent,$category_forumid,$category_export,$category_sort)") or sqlerr(__FILE__,__LINE__);
	stdmsg($tracker_lang['success'],$tracker_lang['add_new_category']);
	$CACHE->clearGroupCache('trees');
	stdfoot();
	die();
}

if(isset($_GET['add'])) {

	$catselect='<select name="category_parent"><option value="0">'.$tracker_lang['no_parent'].'</option>';
	if ($cats) {
		foreach ($cats as $id=>$cat) {
			$catselect.='<option value="'.$id.'">'.$cat.'</option>';
		}
	}
	$catselect .='</select>';
	print("<div align='center'>{$tracker_lang['add_new_category']}</div>");
	print("<form method='post' action='category.php?added'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$tracker_lang['name']}:</td><td align='left'><input type='text' size=60 name='category_name'/></td></tr>");
	print("<tr><td>{$tracker_lang['parent']}:</td><td align='left'>$catselect</td></tr>");
	print("<tr><td>{$tracker_lang['image']}:</td><td align='left'><input type='text' size=60 name='category_pic'/></td></tr>");
	print("<tr><td>{$tracker_lang['sort']}</td><td align='left'><input type='text' size=60 name='category_sort'/></td></tr>");
	print("<tr><td>{$tracker_lang['export_id']}</td><td align='left'><input type='text' size=60 name='category_forumid'/></td></tr>");
	print("<tr><td>{$tracker_lang['disable_export']}</td><td align='left'><select name=\"category_export\"><option value=\"0\">{$tracker_lang['no']}</option><option value=\"1\">{$tracker_lang['yes']}</option></select></td></tr>");
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$tracker_lang['add_new_category']}'/></div></td></tr>");
	print("</table></form>");
	stdfoot();
	die();
}
///////////////////// E X I S T I N G  categories  \\\\\\\\\\\\\\\\\\\\\\\\\\\\

print('<div align="center">'.$tracker_lang['cur_tree'].': '.gen_select_area('',$tree).'</div>');
print("<table width=\"100%\" class=main>");
print("<tr><td>ID</td><td>{$tracker_lang['sort']}</td><td>{$tracker_lang['name']}</td><td>{$tracker_lang['image']}</td><td>{$tracker_lang['parent']}</td><td>{$tracker_lang['forum_id']}</td><td>{$tracker_lang['disable_export_short']}</td><td>{$tracker_lang['edit']}</td><td>{$tracker_lang['delete']}</td></tr>");
$sql = sql_query("SELECT * FROM categories ORDER BY id ASC");

while ($row = mysql_fetch_assoc($sql)) {
	$id = $row['id'];
	$name = $row['name'];
	$parent = $row['parent_id'];
	$forumid = $row['forum_id'];
	$image = $row['image'];
	$disexp = $row['disable_export'];
	$sort = $row['sort'];

	print("<tr><td><strong>$id</strong></td><td>$sort</td><td>$name</td><td>".($image?'<img src="pic/cats/'.$image.'"/>':$tracker_lang['no'])."</td><td>".($cats[$parent]?$cats[$parent]:$tracker_lang['no'])."</td><td>$forumid</td><td>".($disexp?$tracker_lang['yes']:$tracker_lang['no'])."</td><td><a href='category.php?editid=$id'><div align='center'><img src='pic/multipage.gif' border='0' class='special' /></a></div></td> <td><div align='center'><a onclick=\"return confirm('{$tracker_lang['confirmation_delete']}');\" href='category.php?delid=$id'><img src='pic/warned2.gif' border='0' class='special' align='center' /></a></div></td></tr>");
}
print("</table>");
stdfoot();
?>