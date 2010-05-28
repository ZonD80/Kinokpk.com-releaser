<?php
/**
 * Upload torrent script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");


dbconn();

getlang('upload');

loggedinorreturn();


//stderr('Загрузка релизов временно отключена','Загрузка новых релизов временно отключена администрацией');
stdhead($tracker_lang['upload_torrent']);
$tree = make_tree();

if (!isset($_GET['type'])) {
	begin_frame("Выберите категорию релиза");
	print '<div align="center">
<form name="upload" action="upload.php" method="GET">
<table border="0" cellspacing="0" cellpadding="5">
<tr><td align="center" style="border: 0px;">'.gen_select_area('type',$tree).'</td></tr>
<tr><td align="center" colspan="2" style="border:0;"><input type="submit" class="btn button" value="Далее" /></td></tr>
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


if (strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
	sql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
}

?>
<form name="upload" enctype="multipart/form-data"
	action="takeupload.php" method="post">
<table border="1" cellspacing="0" cellpadding="5">
	<input type="hidden" name="type[]" value="<?=$type?>" />
	<tr>
		<td class="colhead" colspan="2"><?print $tracker_lang['upload_torrent']?></td>
	</tr>
	<?
	//tr($tracker_lang['announce_url'], $announce_urls[0], 1);
	tr($tracker_lang['torrent_file'], "<input  type=file name=tfile  size=80><br /><input type=\"checkbox\"  name=\"multi\" value=\"1\">&nbsp;{$tracker_lang['multitracker_torrent']}<br /><small>{$tracker_lang['multitracker_torrent_notice']}</small>\n", 1);
	tr($tracker_lang['torrent_name']."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" size=\"80\" /><br />(".$tracker_lang['taken_from_torrent'].")\n", 1);

	$imagecontent = '<br />';

	for ($i = 0; $i < $CACHEARRAY['max_images']; $i++) {
		$imagecontent.="<b>".$tracker_lang['image']." ".($i+1)." (URL):</b>&nbsp&nbsp<input type=\"text\" size=\"63\" name=\"img$i\"><hr />";
	}

	tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 500kb<br />".$tracker_lang['avialable_formats'].": .jpg .png .gif$imagecontent\n", 1);
	tr($tracker_lang['description'],textbbcode("descr"),1);

	/// RELEASE group
	$rgarrayres = sql_query("SELECT id,name FROM relgroups ORDER BY added DESC");
	while($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
		$rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
	}

	if ($rgarray) {
		$rgselect = '<select name="relgroup" style="width: 150px;"><option value="0">('.$tracker_lang['choose'].')</option>';
		foreach ($rgarray as $rgid=>$rgname) $rgselect.='<option value="'.$rgid.'">'.$rgname."</option>\n";
		$rgselect.='</select>';
	}
	if ($rgselect)
	tr($tracker_lang['relgroup'],$rgselect,1);

	$childs = get_childs($tree,$cat['parent_id']);
	if ($childs) {
		$chsel='<table width="100%" border="1">';
		foreach($childs as $child)
		if ($cat['id'] != $child['id']) $chsel.="<tr><td><input type=\"checkbox\" name=\"type[]\" value=\"{$child['id']}\">&nbsp;{$child['name']}".($CACHEARRAY['use_integration']?"&nbsp;&nbsp;<input type=\"radio\" name=\"forumcat\" value=\"{$child['id']}\">":'')."</td></tr>";
		$chsel.="</table>".($CACHEARRAY['use_integration']?"<small>{$tracker_lang['forum_selector']}</small>":'');
	}
	tr ($tracker_lang['main_category'],get_cur_position_str($tree,$cat['id']).($CACHEARRAY['use_integration']?"&nbsp;&nbsp;<input type=\"radio\" name=\"forumcat\" value=\"{$cat['id']}\" checked>":''),1);
	if ($chsel)
	tr ($tracker_lang['subcats'],$chsel,1);


	if (get_user_class() >= UC_MODERATOR) {
		tr($tracker_lang['golden'], "<input type=checkbox name=free value=\"1\"> ".$tracker_lang['golden_descr'], 1);
		tr("Важный", "<input type=\"checkbox\" name=\"sticky\" value=\"1\">Прикрепить этот торрент (всегда наверху)", 1);
	}
	tr("Релиз без торрента", "<input type=\"checkbox\" name=\"nofile\" value=\"1\">Этот релиз без торрента ; Размер: <input type=\"text\" name=\"nofilesize\" size=\"20\" /> МБ", 1);
	tr("<font color=\"red\">АНОНС</font>", "<input type=\"checkbox\" name=\"annonce\" value=\"1\">Это всего-лишь анонс фильма. Ссылки указывать не обязательно,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;но обязательно выложить фильм после анонсирования!", 1);
	if ($CACHEARRAY['use_integration']) tr("ID темы<br />форума {$CACHEARRAY['forumname']}<br /><br /><font color=\"red\">Будьте внимательны!</font><br /><br /><a href=\"{$CACHEARRAY['forumurl']}/index.php?act=Search\">Искать релиз<br />на форуме</a>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.upload.topic.disabled = false;\" /> - отметить, чтобы ввести ID темы.<hr />Пример: {$CACHEARRAY['forumurl']}/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - это ID темы<hr /><b>Данная функция используется, когда тема с фильмом <u>уже есть</u> на форуме.</b><br />Данные будут записаны в WIKI секцию топика, название в шапке будет изменено в соответствии с названием релиза.<br />Если поле пустое, то тема создается автоматически.\n",1);
	?>
	<tr>
		<td align="center" colspan="2"><input type="submit" class="btn button"
			value="<?=$tracker_lang['upload'];?>" /></td>
	</tr>
</table>
</form>
	<?

	stdfoot();

	?>