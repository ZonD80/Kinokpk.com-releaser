<?php
/**
 * Pages categories administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
$REL_LANG->load('pagescategory');
loggedinorreturn();
httpauth();
if (get_user_class() < UC_SYSOP) {
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));
}


$tree = make_pages_tree(UC_SYSOP);

stdhead($REL_LANG->say_by_key('pagescategory_admin'));
$catsrow = sql_query("SELECT id,name FROM pagescategories ORDER BY sort ASC");
while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=get_cur_position_str($tree,$catres['id'],'pagebrowse');

print("<h1><a href=\"".$REL_SEO->make_link('pagescategory')."\">{$REL_LANG->say_by_key('pagescategory_admin')}</a></h1>\n");
print("<br /><a href=\"".$REL_SEO->make_link('pagescategory','add','')."\">{$REL_LANG->say_by_key('add_new_pagescategory')}</a><br />");

///////////////////// D E L E T E  pagescategory \\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_GET['delid'])) {
	if (!is_valid_id($_GET['delid'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); stdfoot(); die(); }

	$delid = (int) $_GET['delid'];

	sql_query("DELETE FROM pagescategories WHERE id=" .$delid . " LIMIT 1");
	$REL_CACHE->clearGroupCache('trees');
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('pagescategory_success_delete'));
	stdfoot();
	die();
}

///////////////////// E  D  I  T  A  pagescategory \\\\\\\\\\\\\\\\\\\\\\\\\\\\
elseif (isset($_GET['edited'])) {
	if (!is_valid_id($_GET['id'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); stdfoot(); die(); }

	$pagescategory_name = sqlesc(htmlspecialchars((string)$_POST['pagescategory_name']));
	$pagescategory_id = ((int)$_GET['id']);
	$pagescategory_pic = sqlesc(htmlspecialchars((string)$_POST['pagescategory_pic']));
	$pagescategory_sort = ((int)$_POST['pagescategory_sort']);
	$pagescategory_class = ((int)$_POST['pagescategory_class']);
	$pagescategory_class_edit = ((int)$_POST['pagescategory_class_edit']);
	$pagescategory_parent = (int)$_POST['pagescategory_parent'];
	sql_query("UPDATE pagescategories SET
name = $pagescategory_name,
parent_id = $pagescategory_parent, image=$pagescategory_pic, class=$pagescategory_class, class_edit=$pagescategory_class_edit, sort=$pagescategory_sort WHERE id=$pagescategory_id") or sqlerr(__FILE__,__LINE__);
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('pagescategory_success_edit'));
	$REL_CACHE->clearGroupCache('trees');
	stdfoot();
	die();
}

elseif (isset($_GET['editid'])) {
	if (!is_valid_id($_GET['editid'])) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'),'error'); stdfoot(); die(); }
	$cid = (int) $_GET['editid'];
	$res = sql_query("SELECT * FROM pagescategories WHERE id=$cid");
	$row = mysql_fetch_array($res);

	$catselect='<select name="pagescategory_parent"><option value="0">'.$REL_LANG->say_by_key('no_parent').'</option>';
	foreach ($cats as $id=>$cat) {
		$catselect.='<option value="'.$id.'"'.(($row['parent_id']==$id)?' selected':'').'>'.$cat.'</option>';
	}
	$catselect .='</select>';
	print("<form name='form1' method='post' action='".$REL_SEO->make_link('pagescategory','edited','','id',$cid)."'>");
	print("<div align='center'>{$REL_LANG->say_by_key('you_edit')} <strong>{$row['name']}</strong></div>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$REL_LANG->say_by_key('name')}:</td><td align='left'><input type='text' size=60 name='pagescategory_name' value='{$row['name']}'></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('parent')}:</td><td align='left'>$catselect</td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('image')}:</td><td align='left'><input type='text' size=60 name='pagescategory_pic' value='{$row['image']}'></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('sort')}</td><td align='left'><input type='text' size=60 name='pagescategory_sort' value='{$row['sort']}'></td></tr>");
	// class selection
	$classsel = '<select name="pagescategory_class">';
	for ($i=UC_SYSOP;$i--;$i<=0){
		$classsel.= "<option value=\"$i\"".(($i==$row['class'])?" selected=\"1\"":'').">".get_user_class_name($i)."</option>\n";
	}
	$classsel .='</select>';
	// class selection end
	// class selection
	$classsel2 = '<select name="pagescategory_class_edit">';
	for ($i=UC_SYSOP;$i--;$i<=0){
		$classsel2.= "<option value=\"$i\"".(($i==$row['class_edit'])?" selected=\"1\"":'').">".get_user_class_name($i)."</option>\n";
	}
	$classsel2 .='</select>';
	// class selection end
	tr('Класс доступа',$classsel,1);
	tr('Класс создания',$classsel2,1);
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$REL_LANG->say_by_key('edit')}'></div></td></tr>");
	print("</table></form>");
	stdfoot();
	die();
}


if(isset($_GET['added'])) {
	$pagescategory_name = sqlesc(htmlspecialchars((string)$_POST['pagescategory_name']));
	$pagescategory_parent = ((int)$_POST['pagescategory_parent']);
	$pagescategory_class = ((int)$_POST['pagescategory_class']);
	$pagescategory_class_edit = ((int)$_POST['pagescategory_class_edit']);
	$pagescategory_pic = sqlesc(htmlspecialchars((string)$_POST['pagescategory_pic']));
	$pagescategory_sort = ((int)$_POST['pagescategory_sort']);
	sql_query("INSERT INTO pagescategories (name,image,class,class_edit,sort,parent_id) VALUES ($pagescategory_name,$pagescategory_pic,$pagescategory_class,$pagescategory_class_edit,$pagescategory_sort,$pagescategory_parent)") or sqlerr(__FILE__,__LINE__);
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('add_new_pagescategory'));
	$REL_CACHE->clearGroupCache('trees');
	stdfoot();
	die();
}

if(isset($_GET['add'])) {

	$catselect='<select name="pagescategory_parent"><option value="0">'.$REL_LANG->say_by_key('no_parent').'</option>';
	if ($cats) {
		foreach ($cats as $id=>$cat) {
			$catselect.='<option value="'.$id.'">'.$cat.'</option>';
		}
	}
	$catselect .='</select>';

	print("<div align='center'>{$REL_LANG->say_by_key('add_new_pagescategory')}</div>");
	print("<form method='post' action='".$REL_SEO->make_link('pagescategory','added','')."'>");
	print("<table class=main cellspacing=0 cellpadding=5 width=50%>");
	print("<tr><td>{$REL_LANG->say_by_key('name')}:</td><td align='left'><input type='text' size=60 name='pagescategory_name'/></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('parent')}:</td><td align='left'>$catselect</td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('image')}:</td><td align='left'><input type='text' size=60 name='pagescategory_pic'/></td></tr>");
	print("<tr><td>{$REL_LANG->say_by_key('sort')}</td><td align='left'><input type='text' size=60 name='pagescategory_sort'/></td></tr>");
	// class selection
	$classsel = '<select name="pagescategory_class">';
	for ($i=UC_SYSOP;$i--;$i<=0){
		$classsel.= "<option value=\"$i\">".get_user_class_name($i)."</option>\n";
	}
	$classsel .='</select>';
	// class selection end
	// class selection
	$classsel2 = '<select name="pagescategory_class_edit">';
	for ($i=UC_SYSOP;$i--;$i<=0){
		$classsel2.= "<option value=\"$i\">".get_user_class_name($i)."</option>\n";
	}
	$classsel2 .='</select>';
	// class selection end
	tr('Класс доступа',$classsel,1);
	tr('Класс создания',$classsel2,1);
	print("<tr><td colspan=\"2\"><div align='center'><input type='submit' value='{$REL_LANG->say_by_key('add_new_pagescategory')}'/></div></td></tr>");
	print("</table></form>");
	stdfoot();
	die();
}
///////////////////// E X I S T I N G  categories  \\\\\\\\\\\\\\\\\\\\\\\\\\\\

print('<div align="center">'.$REL_LANG->say_by_key('cur_tree').': '.gen_select_area('',$tree).'</div>');
print("<table width=\"100%\" class=main>");
print("<tr><td>ID</td><td>{$REL_LANG->say_by_key('sort')}</td><td>{$REL_LANG->say_by_key('name')}</td><td>{$REL_LANG->say_by_key('image')}</td><td>{$REL_LANG->say_by_key('parent')}</td><td>{$REL_LANG->say_by_key('class')}</td><td>{$REL_LANG->say_by_key('class')} - редактор</td><td>{$REL_LANG->say_by_key('edit')}</td><td>{$REL_LANG->say_by_key('delete')}</td></tr>");
$sql = sql_query("SELECT * FROM pagescategories ORDER BY id ASC");

while ($row = mysql_fetch_assoc($sql)) {
	$id = $row['id'];
	$name = $row['name'];
	$parent = $row['parent_id'];
	$image = $row['image'];
	$class = get_user_class_name($row['class']);
	$class_edit = get_user_class_name($row['class_edit']);
	$sort = $row['sort'];

	print("<tr><td><strong>$id</strong></td><td>$sort</td><td>$name</td><td>".($image?'<img src="pic/cats/'.$image.'"/>':$REL_LANG->say_by_key('no'))."</td><td>".($cats[$parent]?$cats[$parent]:$REL_LANG->say_by_key('no'))."</td><td>$class</td><td>$class_edit</td><td><a href='".$REL_SEO->make_link('pagescategory','editid',$id)."'><div align='center'><img src='pic/multipage.gif' border='0' class='special' /></a></div></td> <td><div align='center'><a onclick=\"return confirm('{$REL_LANG->say_by_key('confirmation_delete')}');\" href='".$REL_SEO->make_link('pagescategory','delid',$id)."'><img src='pic/warned2.gif' border='0' class='special' align='center' /></a></div></td></tr>");
}
print("</table>");
stdfoot();
?>