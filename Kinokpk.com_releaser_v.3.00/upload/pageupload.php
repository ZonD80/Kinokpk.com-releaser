<?php
/**
 * Page upload form
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");


dbconn();

//getlang('pageupload');

loggedinorreturn();
//stderr('Загрузка релизов временно отключена','Загрузка новых релизов временно отключена администрацией');
stdhead('Создать страницу');
$tree = make_pages_tree(get_user_class(),get_user_class());

if (!isset($_GET['type'])) {
	begin_frame("Выберите категорию создаваемой страницы");
	print '<div align="center">
<form name="upload" action="pageupload.php" method="GET">
<table border="1" cellspacing="0" cellpadding="5">
'.gen_select_area('type',$tree).'
<tr><td align="center" colspan="2" style="border:0;"><input type="submit" class=btn value="Далее" /></td></tr>
</table>
</form>
</div>
';
	end_frame();
	stdfoot();
	die();
}

elseif (!is_valid_id($_GET["type"])) {			stdmsg($tracker_lang['error'],$tracker_lang['invalid_id']); stdfoot();   exit;}

$type = (int) $_GET['type'];


$cat = get_cur_branch($tree,$type);
if (!$cat) {			stdmsg($tracker_lang['error'],$tracker_lang['invalid_id']); stdfoot();   exit;}

?><form name="upload" enctype="multipart/form-data"	action="pagetakeupload.php" method="post"><input type="hidden"	name="type[]" value="<?=$type?>" /><table border="1" cellspacing="0" cellpadding="5">	<tr>		<td class="colhead" colspan="2"><?print 'Создать страницу'?></td>	</tr>	<?
	tr('Название страницы'."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" size=\"80\" />\n", 1);
	tr('Теги страницы'."<br /><small>Заполняются через запятую, <b>без пробелов</b></small>", "<input type=\"text\" name=\"tags\" size=\"80\" />\n", 1);
	tr($tracker_lang['description'],textbbcode("descr"),1);

	$childs = get_childs($tree,$cat['parent_id']);
	if ($childs) {
		$chsel='<table width="100%" border="1">';
		foreach($childs as $child)
		if ($cat['id'] != $child['id']) $chsel.="<tr><td><input type=\"checkbox\" name=\"type[]\" value=\"{$child['id']}\">&nbsp;{$child['name']}</td></tr>";
		$chsel.="</table>";
	}
	tr ('Главный раздел',get_cur_position_str($tree,$cat['id'],'pagebrowse'),1);
	tr ('Подразделы',$chsel,1);

	if (get_user_class() >= UC_MODERATOR) {
		// class selection
		$classsel = '<select name="class">';
		for ($i=get_user_class();$i--;$i<=0){
			$classsel.= "<option value=\"$i\">".get_user_class_name($i)."</option>\n";
		}
		$classsel .='</select>';
		// class selection end
		tr('Класс доступа',$classsel,1);
		tr('Запретить комментарии',"<input type=\"checkbox\" name=\"denycomments\" value=\"1\">",1);
		tr("Важная", "<input type=\"checkbox\" name=\"sticky\" value=\"1\">Прикрепить эту страницу (всегда наверху)", 1);
		tr("Создатель - ".$tracker_lang['from_system'],"<input type=\"checkbox\" name=\"system\" value=\"1\">",1);
		tr("Индексируется","<input type=\"checkbox\" name=\"indexed\" value=\"1\">",1);
	}

	?>	<tr>		<td align="center" colspan="2"><input type="submit" class=btn			value="Создать" /></td>	</tr></table></form>	<?

	stdfoot();

	?>