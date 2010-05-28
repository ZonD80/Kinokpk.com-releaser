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
<div align=center>
<p><span style="color: green; font-weight: bold;">Если ваш фильм еще конвертируется, вы можете сделать анонс фильма, отметив чекбокс внизу</span></p>
<p><span style="color: red; font-weight: bold;">Разрешенные символы для ссылок: a-z, A-Z, 0-9 и _/-:.?= , не забудьте переименовать файл перед загрузкой на файлообменник!</span></p>
<script language="JavaScript">
<!--

required = new Array("name", "year", "director", "roles", "publisher", "time", "translation", "descr", "resolution", "videocodec", "fps", "videobitrate", "channels", "audiocodec", "frequency", "audiobitrate", "format");
required_show = new Array("название фильма", "год выхода фильма на экраны", "информацию о режиссере", "актеров", "издателя", "продолжительность фильма", "информацию о переводе", "описание", "разрешение видео", "кодек видео", "частоту кадров", "битрейт видео","качество аудио", "кодек аудио", "частоту аудио", "битрейт аудио", "формат файла");

links = new Array("httplinks", "ftplinks");
url = new Array("HTTP", "FTP");
var allowedchars = "ABCDEFGHIKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_/-:.?=";

function SendForm () {

var reg=/^[A-Z_a-z_0-9\/-:.?=\n]+$/;
var k,l;

for(l=0; l<links.length; l++) {
    for (k=0; k<document.upload.length; k++) {
      if ((document.upload.elements[k].name == links[l]) && (!reg.test(document.upload.elements[k].value)) && (document.upload.elements[k].value != '')) {
       alert ('В ' + url[l] + ' ссылке есть недопустимые символы, разрешенные: ' + allowedchars);
   document.upload.elements[k].focus();
   return false;
      }
}
}

var i, j;

for(j=0; j<required.length; j++) {
    for (i=0; i<document.upload.length; i++) {
        if (document.upload.elements[i].name == required[j] &&
  document.forms[0].elements[i].value == "" ) {
            alert('Пожалуйста, введите ' + required_show[j]);
            document.upload.elements[i].focus();
            return false;
        }
    }
}

if (document.upload.source.value == "unknown") {
  alert ('Пожалуйста, выберите тип исходника');
  document.upload.source.focus();
  return false;
}

if (document.upload.type.value == 0) {
  alert ('Пожалуйста, выберите жанр видео');
  document.upload.type.focus();
  return false;
}

return true;
}

//-->

</script>

<form name="upload" enctype="multipart/form-data" action="takeupload.php" method="post" onsubmit="return SendForm();">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_torrent_size?>" />
<table border="1" cellspacing="0" cellpadding="5">
<tr><td class="colhead" colspan="2"><?=$tracker_lang['upload_torrent'];?></td></tr>
<?
//tr($tracker_lang['announce_url'], $announce_urls[0], 1);
tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80>\n", 1);
tr($tracker_lang['torrent_name'], "<input type=\"text\" name=\"name\" size=\"80\" /><br />(".$tracker_lang['taken_from_torrent'].")\n", 1);
tr("Иностранное (ориг) название", "<input type=\"text\" name=\"origname\" size=\"80\" /><br />(".$tracker_lang['taken_from_torrent'].")\n", 1);
tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 500kb<br />".$tracker_lang['avialable_formats'].": .jpg .jpeg .pjpeg<br /><b>".$tracker_lang['image']." (постер):</b>&nbsp&nbsp<input type=file name=image0 size=80><br /><b>".$tracker_lang['image']." (кадр):</b>&nbsp&nbsp<input type=file name=image1 size=80>\n", 1);
tr("Год выхода", "<input type=\"text\" name=\"year\" size=\"4\" />\n", 1);
tr("Режиссер", "<input type=\"text\" name=\"director\" size=\"40\" />\n", 1);
tr("В ролях", "<input type=\"text\" name=\"roles\" size=\"100\" />\n", 1);
tr("Кем выпущено", "<input type=\"text\" name=\"publisher\" size=\"40\" />\n", 1);
tr("Продолжительность", "<input type=\"text\" name=\"time\" size=\"40\" />\n", 1);
tr("Перевод", "<input type=\"text\" name=\"translation\" size=\"40\" />\n", 1);
tr("Рецензия (ссылка)<br /><a target=\"_blank\" href=\"http://www.kinofilms.com.ua\">Kinofilms.com.ua</a>", "<input type=\"text\" name=\"kinofilmscomua\" size=\"100\" /><br />Пример: http://www.kinofilms.com.ua/movie/7258_Sex_and_the_City_The_Movie/\n", 1);
tr("Рейтинг IMDB<br /><a target=\"_blank\" href=\"http://www.imdb.com\">Перейти на IMDb</a>", "<input type=\"text\" name=\"imdbrating\" size=\"40\" /><br />Пример: 9.0/10 (960 votes)\n", 1);
tr("Рейтинг Кинопоиска<br /><a target=\"_blank\" href=\"http://www.kinopoisk.ru\">Перейти на Kinopoisk</a>", "<input type=\"text\" name=\"kinopoiskrating\" size=\"40\" /><br />Пример: 5.941 (17 голосов)\n", 1);
$s = "<select name=\"mpaarating\">\n<option value=\"unknown\">(".$tracker_lang['choose'].")</option>\n";

$cats = mpaalist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["name"] . "\">" . htmlspecialchars($row["name"]." - ".$row['descr']) . "</option>\n";

$s .= "</select>\n";
tr("Рейтинг МРАА<br /><a target=\"_blank\" href=\"mpaafaq.php\">подробнее тут</a>", $s, 1);

print("<tr><td class=rowhead style='padding: 3px'>".$tracker_lang['description']."</td><td>");
textbbcode("upload","descr");
print("</td></tr>\n");
tr("HTTP ссылки", "<textarea name=\"httplinks\" rows=\"3\" cols=\"60\" wrap=\"soft\"></textarea>\n", 1);
tr("FTP ссылки", "<textarea name=\"ftplinks\" rows=\"3\" cols=\"60\" wrap=\"soft\"></textarea>\n", 1);
tr("ed2k ссылки", "<textarea name=\"ed2klinks\" rows=\"3\" cols=\"60\" wrap=\"soft\"></textarea>\n", 1);
tr("Видео", "Разрешение: <input type=\"text\" name=\"resolution\" size=\"9\" /> Кодек: <input type=\"text\" name=\"videocodec\" size=\"6\" /> FPS: <input type=\"text\" name=\"fps\" size=\"5\" /> Битрейт: <input type=\"text\" name=\"videobitrate\" size=\"6\" />Кб/с\n", 1);
tr("Аудио", "Количество каналов: <input type=\"text\" name=\"channels\" size=\"5\" /> Кодек: <input type=\"text\" name=\"audiocodec\" size=\"6\" /> Частота дискретизации: <input type=\"text\" name=\"frequency\" size=\"10\" /> Гц; Битрейт: <input type=\"text\" name=\"audiobitrate\" size=\"6\" />Кб/с\n", 1);
tr("Формат файла", "<input type=\"text\" name=\"format\" size=\"4\" /> Например, AVI\n",1);
$s = "<select name=\"source\">\n<option value=\"unknown\">(".$tracker_lang['choose'].")</option>\n";

$cats = sourcelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["name"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
tr("Тип исходника", $s, 1);

$s = "<select name=\"type\">\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
tr($tracker_lang['type']." (жанр)", $s, 1);

if(get_user_class() >= UC_ADMINISTRATOR)
	tr($tracker_lang['golden'], "<input type=checkbox name=free value=yes> ".$tracker_lang['golden_descr'], 1);

if (get_user_class() >= UC_MODERATOR)
    tr("Важный", "<input type=\"checkbox\" name=\"sticky\" value=\"yes\">Прикрепить этот торрент (всегда наверху)", 1);
    tr("Релиз без торрента", "<input type=\"checkbox\" name=\"nofile\" value=\"yes\">Этот релиз без торрента ; Размер: <input type=\"text\" name=\"nofilesize\" size=\"20\" /> МБ", 1);
    tr("<font color=\"red\">АНОНС</font>", "<input type=\"checkbox\" name=\"annonce\" value=\"yes\">Это всего-лишь анонс фильма. Ссылки указывать не обязательно,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;но обязательно выложить фильм после анонсирования!", 1);
    tr("ID темы<br />форума $FORUMNAME<br/><br/><font color=\"red\">Будьте внимательны!</font><br/><br/><a href=\"$FORUMURL/index.php?act=Search\">Искать релиз<br/>на форуме</a>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.upload.topic.disabled = false;\" /> - отметить, чтобы ввести ID темы.<hr />Пример: $FORUMURL/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - это ID темы<hr /><b>Данная функция используется, когда тема с фильмом <u>уже есть</u> на форуме.</b><br />Данные будут записаны в WIKI секцию топика, название в шапке будет изменено в соответствии с названием релиза.<br />Если поле пустое, то тема создается автоматически.\n",1);

?>
<tr><td align="center" colspan="2"><input type="submit" class=btn value="<?=$tracker_lang['upload'];?>" /></td></tr>
</table>
</form>
<?

stdfoot();

?>