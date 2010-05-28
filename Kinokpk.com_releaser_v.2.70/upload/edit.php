<?

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

require_once("include/bittorrent.php");

dbconn();
getlang('upload');

loggedinorreturn();

if (!is_valid_id($_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_GET['id'];
$tree = make_tree();

$res = sql_query("SELECT id,name,descr,images,category,size,visible,banned,free,sticky,moderatedby,topic_id,online,owner FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

stdhead("Редактирование Релиза \"" . makesafe($row["name"]) . "\"");

?>
<script type="text/javascript" language="javascript">

function openwindow(location)
{
 window.open("<?=$CACHEARRAY['defaultbaseurl']?>/"+location+".php?id=<?=$id?>","mywindow","width=250,height=250");
}
</script>
<?php

if (($row["filename"] == 'nofile') || (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if (($CURUSER["id"] != $row["owner"]) && (get_user_class() < UC_MODERATOR) && !$tedit) {
	stdmsg($tracker_lang['error'],"Вы не можете редактировать этот торрент.");
} else {
	print("<form name=\"edit\" method=post action=takeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\">Редактировать торрент</td></tr>");
	if ((get_user_class() >= UC_MODERATOR) || $tedit) tr($tracker_lang['check'],"<input type=\"checkbox\" name=\"approve\" value=\"1\"".($row['moderatedby']?' checked':'')."> {$tracker_lang['approve']}",1);
	tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80><br /><input type=\"checkbox\" name=\"multi\" value=\"1\">&nbsp;{$tracker_lang['multitracker_torrent']}<br /><small>{$tracker_lang['multitracker_torrent_notice']}</small>\n", 1);
	tr($tracker_lang['torrent_name']."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" value=\"" . makesafe($row["name"]) . "\" size=\"80\" />", 1);

	$row['images'] = explode(',',$row['images']);
	$imgcontent = '';
	// die(var_dump($images));

	for ($i = 0; $i < $CACHEARRAY['max_images']; $i++) {
		$imgcontent .= "<b>Картинка №".($i+1).":&nbsp&nbsp<input type=\"text\" size=\"61\" name=\"img$i\" value=\"{$row['images'][$i]}\"><hr />";
	}
	tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 500kb<br />".$tracker_lang['avialable_formats'].": .jpg .png .gif<br />$imgcontent", 1);

	print '<tr><td align="left"><b>'.$tracker_lang['description'].'</b></td><td>'.textbbcode('descr',$row['descr'],1).'</td></tr>';
	// make main category an childs
	$cats = explode(',',$row['category']);
	$cat= array_shift($cats);
	$cat = get_cur_branch($tree,$cat);
	$childs = get_childs($tree,$cat['parent_id']);
	if ($childs) {
		$chsel='<table width="100%" border="1">';
		foreach($childs as $child)
		if ($cat['id'] != $child['id']) $chsel.="<tr><td><input type=\"checkbox\" name=\"type[]\" value=\"{$child['id']}\"".(in_array($child['id'],$cats)?' checked':'').">&nbsp;{$child['name']}</td></tr>";
		$chsel.="</table>";
	}
	tr ($tracker_lang['main_category'],gen_select_area('type[]',$tree,$cat['id']),1);
	tr ($tracker_lang['subcats'],$chsel,1);

	tr("Видимый", "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Видимый на главной<br /><table border=0 cellspacing=0 cellpadding=0 width=420><tr><td class=embedded>Обратите внимание, что торрент автоматически станет видмым когда появиться раздающий и автоматически перестанет быть видимым (станет мертвяком) когда не будет раздающего некоторое время. Используйте этот переключатель для ускорения процеса. Также учтите что невидимые торренты (мертвяки) все-равно могут быть просмотрены и найдены, это просто не по-умолчанию.</td></tr></table>", 1);
	if((get_user_class() >= UC_MODERATOR) || $tedit)
	tr("\"Обновлен\"", "<input type=\"checkbox\" name=\"upd\" value=\"1\" />Сделать первым на главной", 1);
	if(get_user_class() >= UC_MODERATOR)
	tr("Забанен", "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" />", 1);
}
if((get_user_class() >= UC_MODERATOR) || $tedit)
tr("Золотая раздача", "<input type=\"checkbox\" name=\"free\"" . (($row["free"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Золотая раздача (считается только раздача, скачка не учитывается)", 1);
if (get_user_class() >= UC_MODERATOR)
tr("Важный", "<input type=\"checkbox\" name=\"sticky\"" . (($row["sticky"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Прикрепить этот торрент (всегда наверху)", 1);
if ($row['filename'] != 'nofile') $word = ''; else $word = 'checked=\"checked\"';
$nofsize = $row['size'] / 1024 / 1024;
tr("Релиз без торрента", "<input type=\"checkbox\" name=\"nofile\" ".$word." value=\"1\">Релиз без торрента ; Размер (МБ) <input type=\"text\" name=\"nofilesize\" value=\"" . $nofsize . "\" size=\"20\" />", 1);

if (get_user_class() >= UC_UPLOADER)
tr("Защита от правообладателей","<a href=\"javascript:openwindow('takean')\">Анонимизировать / восстановить обладателя релиза</a> (откроется новое окошко)",1,1);
if ((get_user_class() >= UC_MODERATOR) && $CACHEARRAY['use_integration']) {
	tr("ID темы<br />форума {$CACHEARRAY['forumname']}<br /><br /><font color=\"red\">Будьте внимательны!</font><br /><br />Текущий ID: <font color=\"red\"><b>".$row['topic_id']."</b></font>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.edit.topic.disabled = false;\" /> - отметить, чтобы ввести ID темы. <input type=\"text\" size=\"7\" name=\"source\"/> - описание топика (опц.,обычно - кач-во исходника)<hr />Пример: {$CACHEARRAY['forumurl']}/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - это ID темы<hr /><b>Данная функция используется, когда тему с фильмом нужно переэкспортировать/восстановить на форуме.</b><br />Данные будут записаны в WIKI секцию топика, название в шапке будет изменено в соответствии с названием релиза.<br />Если поле пустое, операция реэкспорта игнорируется.\n",1);
}
tr("Внимание!", "<font color='red'><b>Вам вручную придется стереть \"<i>+HTTP,+FTP,+ed2k,не торрент релиз</i> \" из названия релиза в соответствии с вашим редактированием</b></font>", 1);

print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"{$tracker_lang['edit']}\" style=\"height: 25px; width: 100px\"> <input type=reset value=\"Обратить изменения\" style=\"height: 25px; width: 100px\"></td></tr>\n");
print("</table>\n");
print("</form>\n");
if(get_user_class() >= UC_MODERATOR) {
	print("<p>\n");
	print("<form method=\"post\" action=\"delete.php\">\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=embedded style='background-color: #F5F4EA;padding-bottom: 5px' colspan=\"2\"><b>Удалить торрент</b> Причина:</td></tr>");
	print("<td><input name=\"reasontype\" type=\"radio\" value=\"1\">&nbsp;Мертвяк </td><td> 0 раздающих, 0 качающих = 0 соединений</td></tr>\n");
	print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"2\">&nbsp;Дупликат</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
	print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"3\">&nbsp;Nuked</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
	print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"4\">&nbsp;Правила</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">(Обязательно)</td></tr>");
	print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"5\" checked>&nbsp;Другое:</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">(Обязательно)</td></tr>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<td colspan=\"2\" align=\"center\"><input type=submit value='Удалить' style='height: 25px'></td></tr>\n");
	print("</table>");
	print("</form>\n");
	print("</p>\n");
}
stdfoot();

?>