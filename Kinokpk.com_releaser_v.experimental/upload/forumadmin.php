<?php
/**
 * Forum forum_categories administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 * @todo Something else than cat admin
 */

require_once("include/bittorrent.php");

INIT();
loggedinorreturn();

get_privilege('edit_forum_settings');

httpauth();


$tree = make_tree('forum_categories');

$REL_TPL->stdhead($REL_LANG->_("Forum categories administration"));
$catsrow = sql_query("SELECT id,name,seo_name,class FROM forum_categories ORDER BY sort ASC");
while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=get_cur_position_str($tree,$catres['id']);

print("<h1><a href=\"forumadmin.php\">{$REL_LANG->_("Forum categories administration")}</a></h1>\n");
print("<br /><a href=\"forumadmin.php?add\">{$REL_LANG->say_by_key('add_new_category')}</a><br />");

///////////////////// D E L E T E  CATEGORY \\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_GET['delid'])) {
	if (!is_valid_id($_GET['delid'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	$delid = (int) $_GET['delid'];

	sql_query("DELETE FROM forum_categories WHERE id=" .sqlesc($delid) . " LIMIT 1");
	$REL_CACHE->clearGroupCache('trees');
	$REL_CACHE->clearGroupCache('forums');
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->_("Forum category deleted"));
	$REL_TPL->stdfoot();
	die();
}

///////////////////// E  D  I  T  A  CATEGORY \\\\\\\\\\\\\\\\\\\\\\\\\\\\
elseif (isset($_GET['edited'])) {
	if (!is_valid_id($_GET['id'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }

	$category_name = sqlesc(htmlspecialchars((string)$_POST['category_name']));
	$category_id = sqlesc((int)$_GET['id']);
	$category_parent = sqlesc((int)$_POST['category_parent']);
	$category_pic = sqlesc(htmlspecialchars((string)$_POST['category_pic']));
	$category_class = sqlesc(implode(',',(array)$_POST['category_class']));
	$category_sort = sqlesc((int)$_POST['category_sort']);
	$category_seo = sqlesc(htmlspecialchars((string)$_POST['category_seo']));
	sql_query("UPDATE forum_categories SET
name = $category_name,
parent_id = $category_parent, class=$category_class, image=$category_pic, sort=$category_sort,seo_name=$category_seo WHERE id=$category_id") or sqlerr(__FILE__,__LINE__);
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->_("Forum category successfully edited"));
	$REL_CACHE->clearGroupCache('trees');
	$REL_CACHE->clearGroupCache('forums');
	$REL_TPL->stdfoot();
	die();
}

elseif (isset($_GET['editid'])) {
	if (!is_valid_id($_GET['editid'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); $REL_TPL->stdfoot(); die(); }
	$cid = (int) $_GET['editid'];
	$res = sql_query("SELECT * FROM forum_categories WHERE id=$cid");
	$row = mysql_fetch_array($res);

	$catselect='<select name="category_parent"><option value="0">'.$REL_LANG->say_by_key('no_parent').'</option>';
	foreach ($cats as $id=>$cat) {
		$catselect.='<option value="'.$id.'"'.(($row['parent_id']==$id)?' selected':'').'>'.$cat.'</option>';
	}
	$catselect .='</select>';
	print("<form name='form1' method='post' action='forumadmin.php?edited&id=$cid'>");
	print("<div align='center'>{$REL_LANG->say_by_key('you_edit')} <strong>{$row['name']}</strong></div>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$REL_LANG->say_by_key('name')}:</td><td align='left'><input type='text' size=60 name='category_name' value='{$row['name']}'></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('seo_name')}:</td><td align='left'><input type='text' size=60 name='category_seo' value='{$row['seo_name']}'>{$REL_LANG->say_by_key('seo_notice')}</td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('parent')}:</td><td align='left'>$catselect</td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('image')}:</td><td align='left'><input type='text' size=60 name='category_pic' value='{$row['image']}'></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('sort')}</td><td align='left'><input type='text' size=60 name='category_sort' value='{$row['sort']}'></td></tr>");
	print("<tr><td>{$REL_LANG->_("Access level")}</td>");
	// class selection
	$classsel = '<td>'.make_classes_checkbox("category_class",$row['class']).'</td>';
	print $classsel."</tr>";
	// class selection end
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$REL_LANG->say_by_key('edit')}'></div></td></tr>");
	print("</table></form>");
	$REL_TPL->stdfoot();
	die();
}


if(isset($_GET['added'])) {
	$category_name = sqlesc(htmlspecialchars((string)$_POST['category_name']));
	$category_parent = sqlesc((int)$_POST['category_parent']);
	$category_pic = sqlesc(htmlspecialchars((string)$_POST['category_pic']));
	$category_class = sqlesc(implode(',',(array)$_POST['category_class']));
	$category_sort = sqlesc((int)$_POST['category_sort']);
	$category_seo = sqlesc(htmlspecialchars((string)$_POST['category_seo']));
	sql_query("INSERT INTO forum_categories (name,image,parent_id,class,sort,seo_name) VALUES ($category_name,$category_pic,$category_parent,$category_class,$category_sort,$category_seo)") or sqlerr(__FILE__,__LINE__);
	stdmsg($REL_LANG->_("Successful"),$REL_LANG->_("The category successfully added"));
	$REL_CACHE->clearGroupCache('trees');
	$REL_CACHE->clearGroupCache('forums');
	$REL_TPL->stdfoot();
	die();
}

if(isset($_GET['add'])) {

	$catselect='<select name="category_parent"><option value="0">'.$REL_LANG->say_by_key('no_parent').'</option>';
	if ($cats) {
		foreach ($cats as $id=>$cat) {
			$catselect.='<option value="'.$id.'">'.$cat.'</option>';
		}
	}
	$catselect .='</select>';
	print("<div align='center'>{$REL_LANG->say_by_key('add_new_category')}</div>");
	print("<form method='post' action='forumadmin.php?added'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$REL_LANG->say_by_key('name')}:</td><td align='left'><input type='text' size=60 name='category_name'/></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('seo_name')}:</td><td align='left'><input type='text' size=60 name='category_seo'>{$REL_LANG->say_by_key('seo_notice')}</td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('parent')}:</td><td align='left'>$catselect</td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('image')}:</td><td align='left'><input type='text' size=60 name='category_pic'/></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('sort')}</td><td align='left'><input type='text' size=60 name='category_sort'/></td></tr>");
	print("<tr><td>{$REL_LANG->_("Access level")}</td>");
	// class selection

	$classsel = '<tr><td>'.make_classes_checkbox('category_class').'</td></tr>';
	print $classsel;
	// class selection end
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$REL_LANG->_("Add a new category")}'/></div></td></tr>");
	print("</table></form>");
	$REL_TPL->stdfoot();
	die();
}
///////////////////// E X I S T I N G  forum_categories  \\\\\\\\\\\\\\\\\\\\\\\\\\\\

print('<div align="center">'.$REL_LANG->say_by_key('cur_tree').': '.gen_select_area('',$tree).'</div>');
print("<table width=\"100%\" class=main>");
print("<tr><td>ID</td><td>{$REL_LANG->say_by_key('sort')}</td><td>{$REL_LANG->say_by_key('name')}</td><td>{$REL_LANG->say_by_key('seo_name')}</td><td>{$REL_LANG->say_by_key('image')}</td><td>{$REL_LANG->say_by_key('parent')}</td><td>{$REL_LANG->_('Access level')}</td><td>{$REL_LANG->say_by_key('edit')}</td><td>{$REL_LANG->say_by_key('delete')}</td></tr>");
$sql = sql_query("SELECT * FROM forum_categories ORDER BY id ASC");

while ($row = mysql_fetch_assoc($sql)) {
	$catseo = ($row['seo_name']?$row['seo_name']:translit($row['name']));
	$id = $row['id'];
	$name = $row['name'];
	$parent = $row['parent_id'];
	$forumid = $row['forum_id'];
	$image = $row['image'];
	$row['class'] = explode(',', $row['class']);
	//var_dump($row['class']);
	foreach ($row['class'] AS $cl) {
	$class[] = get_user_class_name($cl);
	}
	$class = @implode(',',$class);
	
	$sort = $row['sort'];

	print("<tr><td><strong>$id</strong></td><td>$sort</td><td>$name</td><td>{$catseo}</td><td>".($image?'<img src="pic/cats/'.$image.'"/>':$REL_LANG->say_by_key('no'))."</td><td>".($cats[$parent]?$cats[$parent]:$REL_LANG->say_by_key('no'))."</td><td>$class</td><td><a href='forumadmin.php?editid=$id'><div align='center'><img src='pic/multipage.gif' border='0' class='special' /></a></div></td> <td><div align='center'><a onclick=\"return confirm('{$REL_LANG->say_by_key('confirmation_delete')}');\" href='forumadmin.php?delid=$id'><img src='pic/warned2.gif' border='0' class='special' align='center' /></a></div></td></tr>");
	unset($class);
}
print("</table>");
$REL_TPL->stdfoot();
?>