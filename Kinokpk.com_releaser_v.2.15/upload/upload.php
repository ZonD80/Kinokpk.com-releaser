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


dbconn(false);

loggedinorreturn();
parked();

stdhead($tracker_lang['upload_torrent']);

if (!isset($_GET['type']) && !isset($_GET['descr'])) {

begin_frame("Выберите категорию релиза");
?>
<div align="center">
<form name="upload" action="upload.php" method="GET">
<table border="1" cellspacing="0" cellpadding="5">
<?

$s = "<select name=\"type\">\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
print $s;

?>
<tr><td align="center" colspan="2" style="border:0;"><input type="submit" class=btn value="Далее" /></td></tr>
</table>
</form>
</div>
<?php
end_frame();
stdfoot();
die();
}

elseif (is_valid_id($_GET['type']) && !isset($_GET['descr'])) {

begin_frame("Выберите шаблон");
?>
<div align="center">
<form name="upload" action="upload.php" method="GET">
<input type="hidden" name="type" value="<?=$_GET['type'];?>"/>
<table border="1" cellspacing="0" cellpadding="5">
<?

$s = "<select name=\"descr\">\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = descrlist($_GET['type']);
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
echo $s;

?>
<tr><td align="center" colspan="2" style="border:0;"><input type="submit" class=btn value="Далее" /></td></tr>
</table>
</form>

<?php
end_frame();
stdfoot();
die();
}
elseif (!is_valid_id($_GET["type"]) || !is_valid_id($_GET["descr"])) {			stdmsg($tracker_lang['error'],$tracker_lang['invalid_id']); stdfoot();   exit;}

$type = $_GET['type'];
$descrtype = $_GET['descr'];

if (get_user_class() < UC_USER)
{
  stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
  stdfoot();
  exit;
}

if (strlen($CURUSER['passkey']) != 32) {
$CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
sql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
}

?>
<script type="text/javascript" language="javascript">
function kinopoiskparser()
{
windop = window.open("parser.php","mywin","height=600,width=400,resizable=no,scrollbars=yes");
}

function tagcounter(checked)
{
  var i=document.forms['upload'].elements['tagcount'].value;
 if (checked) {
 document.forms['upload'].elements['tagcount'].value = parseInt(i)+1;
 return i;
 }
 else {
 document.forms['upload'].elements['tagcount'].value = parseInt(i)-1;
 return '';

}
}
</script>
<form name="upload" enctype="multipart/form-data" action="takeupload.php" method="post" >
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$CACHEARRAY['max_torrent_size']?>" />
<input type="hidden" name="reltype" value="<?=$descrtype?>" />
<table border="1" cellspacing="0" cellpadding="5">
<tr><td class="colhead" colspan="2"><?print $tracker_lang['upload_torrent'].(($descrtype == (1 or 2))?' [<a href="javascript:kinopoiskparser(\'parser\');">Заполнить форму, используя данные Kinopoisk.ru</a>]':'')?></td></tr>
<?
//tr($tracker_lang['announce_url'], $announce_urls[0], 1);
tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80>\n", 1);
tr($tracker_lang['torrent_name']."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" size=\"80\" /><br />(".$tracker_lang['taken_from_torrent'].")\n", 1);
tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 500kb<br />".$tracker_lang['avialable_formats'].": .jpg .png .gif<br /><b>".$tracker_lang['image']." (постер):</b>&nbsp&nbsp<input type=file name=image0 size=80><br /><b>".$tracker_lang['image']." (кадр):</b>&nbsp&nbsp<input type=file name=image1 size=80><hr/><div align=\"center\"><b>Или вы можете указать URL картинок</a></div><b>".$tracker_lang['image']." (постер):</b>&nbsp&nbsp<input type=\"text\" size=\"60\" name=\"img0\"><br/><b>".$tracker_lang['image']." (кадр):</b>&nbsp&nbsp<input type=\"text\" size=\"60\" name=\"img1\">\n", 1);

$descrtarray = mysql_query("SELECT * FROM descr_details WHERE typeid = ".intval($descrtype)." ORDER BY sort ASC");

while ($dd = mysql_fetch_array($descrtarray)) {
  if ($dd['input'] == 'text')
  tr($dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),'<input type="text" size="'.$dd['size'].'" name="val['.$dd['id'].']"><hr/>'.$dd['description'],1);

  elseif ($dd['input'] == 'option') {
  unset($optdescr);
  $optvalues = explode(",",$dd['mask']);
  if (!empty($dd['description'])) $optdescr = explode(",",$dd['description']);
$s = "<select name=\"val[" .$dd['id']. "]\">\n<option value=\"\">(".$tracker_lang['choose'].")</option>\n";


foreach($optvalues as $i => $opt) {

	$s .= "<option value=\"".$opt."\">" . htmlspecialchars((is_array($optdescr)?$optdescr[$i]:$opt)) . "</option>\n";

}

$s .= "</select>\n";
tr($dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),$s,1,1);
  }

  elseif ($dd['input'] == 'links') {
   tr($dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),"<textarea name=\"val[".$dd['id']."]\" rows=\"3\" cols=\"60\" wrap=\"soft\"></textarea><hr/>".$dd['description']."\n",1);
  }

  elseif ($dd['input'] == 'bbcode') {
  print("<tr><td class=rowhead style='padding: 3px'>".$dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":"")."</td><td>");
  textbbcode("upload","val[".$dd['id']."]");
  print("<hr/>".$dd['description']."</td></tr>\n");
  }

}

$s = "<table width=\"100%\"><tr><td>Выбрано тегов: <input type=\"text\" name=\"tagcount\" value=\"0\" readonly/></td></tr>";
$tags = taggenrelist($type);
if (!$tags)
$s .= "Нет тегов для данной категории. Вы можете добавить собственные.";
else
  {

   foreach ($tags as $row) {


   $tag = htmlspecialchars($row["name"]);
   $s .= "<tr><td><input type=\"checkbox\" name=\"tags[]\" value=\"$tag\" onClick=\"this.name='tags['+tagcounter(this.checked)+']';\"> $tag</td></tr>";


   }
  }
$s .= "</table>\n";
tr("Тэги (жанры)<font color=\"red\">*</font>", $s, 1);

if(get_user_class() >= UC_ADMINISTRATOR)
	tr($tracker_lang['golden'], "<input type=checkbox name=free value=yes> ".$tracker_lang['golden_descr'], 1);

if (get_user_class() >= UC_MODERATOR)
    tr("Важный", "<input type=\"checkbox\" name=\"sticky\" value=\"yes\">Прикрепить этот торрент (всегда наверху)", 1);
    tr("Релиз без торрента", "<input type=\"checkbox\" name=\"nofile\" value=\"yes\">Этот релиз без торрента ; Размер: <input type=\"text\" name=\"nofilesize\" size=\"20\" /> МБ", 1);
    tr("<font color=\"red\">АНОНС</font>", "<input type=\"checkbox\" name=\"annonce\" value=\"yes\">Это всего-лишь анонс фильма. Ссылки указывать не обязательно,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;но обязательно выложить фильм после анонсирования!", 1);
    tr("ID темы<br />форума {$CACHEARRAY['forumname']}<br/><br/><font color=\"red\">Будьте внимательны!</font><br/><br/><a href=\"{$CACHEARRAY['forumurl']}/index.php?act=Search\">Искать релиз<br/>на форуме</a>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.upload.topic.disabled = false;\" /> - отметить, чтобы ввести ID темы.<hr />Пример: {$CACHEARRAY['forumurl']}/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - это ID темы<hr /><b>Данная функция используется, когда тема с фильмом <u>уже есть</u> на форуме.</b><br />Данные будут записаны в WIKI секцию топика, название в шапке будет изменено в соответствии с названием релиза.<br />Если поле пустое, то тема создается автоматически.\n",1);

?>
<tr><td align="center" colspan="2"><input type="hidden" name="type" value="<?=$type?>"><input type="submit" class=btn value="<?=$tracker_lang['upload'];?>" /></td></tr>
</table>
</form>
<?

stdfoot();

?>