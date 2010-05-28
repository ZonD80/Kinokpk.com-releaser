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

loggedinorreturn();

stdhead("Форматы");

if ($_GET["form"]=='mov')
{
?>


<table width=100% cellspacing=0 cellpadding=2><tr>
<td class=colhead>
<font color="#000000">Описание различных видео форматов</font>
</td></tr>
<td align="left">
<ul>
<br />
<b>CamRip</b> (<b>CAM</b>): Самое низкое качество. Фильм записывают камерой с экрана кинотеатра. Качество обычно нормальное до хорошего. В некоторых фильмах видны головы других кинозрителей и.т.д. Качество звука бывает разное, возможны помехи типа смеха публики.
<br /><br />
<b>Telesync</b> (<b>TS</b>): Записывается професcиональной (цифровой) камерой установленной на штатив в пустом кинотеатре с экрана. Качество видео намного лучше, чем с простой камеры (Cam). Звук записывается на прямую с проектора или с другого отдельного выхода, например гнездо для наушников в кресле (как в самолёте). Звук таким образом получается очень хороший и без помех. Как правило звук в режиме стерео.


<br /><br />

<b>Screener</b> (<b>SCR</b>): Второе место по качеству. Для этого используется професcиональная видеокассета для прессы. Качество изображения сравнимо с очень хорошим VHS. Звук тоже отличный, обычно стерео или Dolby Surround.
<br /><br />

<b>DVDScreener</b> (<b>SCR</b>): Тот же принцип, что и в просто <b>Screener</b>, но на ДВД-носителе. Качество - как DVDRip, но картинка обычто "испорчена" водяными знаками и черно-белыми вставками ("пропадающая цветность").
<br /><br />

<b>Workprint</b> (<b>WP</b>):  Особая конфетка для любителей фильмов. Это так называемая "Бета-версия" фильма. Обычно выходит в формате VCD и намного раньше до начала показа в кинотеатрах мира. Это предварительная версия фильма. Из-за этого можно ожидать всё. От супер качества, до полного отстоя. Часто отсутствуют некоторые сцены. Однако может быть и такое, что есть все сцены, а потом их вырежут... Узнать такие версии можно по таймеру в верху или в низу экрана - он нужен для последующего монтажа.
<br /><br />

<b>Telecine</b> (<b>TC</b>):  Очень популярные в рунете версии фильмов, которые часто путают с DVD-рипами. Качество - прекрасное, но часты проблемы с естественностью цветов ("желтизна" картинки). Источником является проектор с выходами для аудио и видео. Фильм записывают прямо с проектора.
<br /><br />

<b>VHSrip</b> - Источник материала кассета формата VHS, обычно довольно среднего качества.


<br /><br />
<b>TVrip</b> - Материал записан с телевизионного сигнала, обычно кабельного (но попадаются и с простой антенны). Почти все телесериалы первично раздаются именно в этом или SATrip формате.
<br /><br />
<b>SATrip</b> - Материал записан напрямую со спутника, обычно лучшего качества чем TVrip.
<br /><br />
<b>DVDRip и LDRip</b>: Это версия делается из DVD или Laserdisc. Качество - самое лучшее, хотя и зависит от мастерства создателя ("риппера")
<br /><br />

<u>Дополнительная терминология</u>
<br /><br />
<b>PS</b>:  Pan and Scan: Фильмы сделанные для проверки реакции зрителей. Показывают их на квадратном экране. Если такой фильм переписывать для домашнего тв, то нужно переделывать формат. Большинство фильмов в США, после 1955 года снятые, были записаны в формате 1,85:1 ( европейские записываются в 1,66:1 ). Исключением является Cinemascope-формат (2,35:1) для анаморфических линз. Обычный телевизор имеет формат 1,33:1. Если перегонять на видео, то нужно уменьшить картинку. Это делатся так: видео урезается в ширине. Если Вы купили DVD и там нет информации о "Оригинал киноформат", то вы можете исходить из того, что фильм урезали методом Pan and Scan. Если Вы хотите весь фильм, то покупайте DVD с пометкой "Widescreen".


<br /><br />
<b>STV</b>: Straight To Video - фильм сразу вышел на DVD/кассете минуя кинотеатры. Качество - соответственно DVDrip или VHSrip.
<br /><br />
<b>Dubbed</b>: Оригинальный звук убрали (Например взяли дорожку из русского кинотеатра и наложили на американский релиз)
<br /><br />
<b>Line.Dubbed</b>: Тоже самое как и Dubbed, только в этом случае звук был взят из "кресла" или "проектора" (Line).
<br /><br />
<b>Mic.Dubbed</b>: Тоже самое как и Dubbed, только звук был записан микрофоном в кинотеатре.
<br /><br /><br />


<u>Другие сокращения:</u><br />

<br />
<b>TS</b> = <b>Telesync</b> (описано выше)<br />
<b>TC</b> =<b> Telecine </b>(описано выше)<br />
<b>SCR</b> = <b>Screener</b> (описано выше)   <br />

<b>WS</b> = <b>Widescreen</b>                     <br />
<b>LETTERBOX</b> = другой термин для <b>Widescreen</b><br />
<b>LIMITED</b> = фильм показывал в менее, чем 500 кинотеатрах<br />
<b>DC</b> = "Director's Cut"<br />

<b>SE</b> = "Special Edition"   <br />
<b>FS</b> = релиз в Fullscreen, т.е. полный <br />
<b>PROPER</b> = предыдущий релиз этого фильма был отстойный по сравнению с этим<br />
<b>RECODE</b> = релиз переделанный в другой формат или заново кодированный <br />

<b>DUPE</b> = второй релиз того же фильма другой релизной группой (обычно краденный у первой)<br />

<b>RERIP</b> = новый рип фильма<br />
<b>Subbed</b> = фильм с титрами <br />
<b>WATERMARKED</b> = Маленькие логотипы тв-канала или релизера.<br /><br />

<br />
</ul>
</td>
</table>
<br />


<?
}
if ($_GET["form"]=='all')
{
?>


<table width=100% cellspacing=0 cellpadding=2><tr>
<td class=colhead>
<font color="#000000"><center>Описание некоторых форматов файлов</center></font>
</td></tr>
<td align="left">

Здесь представлено описание некоторых форматов файлов, которые вы можете скачать с интернета, их назначение и программы, которыми их можно открыть. Если вы не знаете как открыть скачанный файл, то прочитайте эту статью, возможно вы найдёте ответ здесь. Если вы не найдёте ответ на свой вопрос, то задайте его на форуме.
<br />

<br />

<tr><td class=colhead>
<font color="#000000"><center>Архивы</center></font>
</td></tr>
<td align="left">

<b>.rar .zip .ace .r01 .001</b><br />
<br />
Это самые распространённые расширения архивов.<br />
Файлы упаковываются в архивы для уменьшения объёма и чтобы их было удобнее скачивать.<br />
<br />

Чтобы открыть эти архивы вы иожете использовать <a href="http://www.rarsoft.com/download.htm">WinRAR</a> или <a href="http://www.powerarchiver.com/download/">PowerArchiver</a>.<br />
<br />
Если эти программы не помогли вам открыть .zip файл, попробуйте 
<a href="http://www.winzip.com/download.htm">WinZip</a> (Демо версия).<br />
<br />
Если предыдущие программы не помогли вам открыть .ace или .001 файл, попробуйте <a href="http://www.winace.com/">Winace</a> (Демо версия).<br />

<br />
<br /> 
<b>.cbr .cbz</b><br />
<br />
Обычно это заархивированные комиксы. Файлы с расширением .cbr аналогичны файлам с расширением .rar, а файлы с расширением .cbz - файлам с расширением .zip . Не смотря на это WinRAR или WinZip могут не корректно открыть эти файлы. Если такое произошло, попробуйте программу <a href="http://www.geocities.com/davidayton/CDisplay">
CDisplay</a>.<br />
<br />
<br />
<tr><td class=colhead>
<font color="#000000"><center>Мультимедийные файлы</center></font>
</td></tr>
<td align="left">

<b>.avi .mpg. .mpeg .divx .xvid .wmv</b><br />
<br />
Это обычно видео файлы. Их можно открыть любым видео плеером, но мы рекомендуем использовать следующие программы:
<a href="http://www.inmatrix.com/files/zoomplayer_download.shtml">Zoomplayer</a>,
<a href="http://www.bsplayer.org/">BSPlayer</a>, <a href="http://www.videolan.org/vlc/">VLC media player</a>, <a href="http://softella.com/la/index.ru.htm">Light Alloy</a>
 или <a href="http://www.microsoft.com/windows/windowsmedia/default.aspx">Windows Media Player</a>. Также вам понадобятся кодеки, для открыия соответствующих файлов. Очень часто бывает, что фильм не открывается, из-за отсутствия нужного кодека. Для определения необходимого кодека используйте программу <a href="http://www.headbands.com/
gspot/download.html">GSpot</a>. Ниже перечислены самые распространённые кодеки:<br />

<br />
• <a href="http://sourceforge.net/project/showfiles.php?group_id=53761&release_id=95213">ffdshow</a> (Рекомендуемый! (открывает многие форматы: XviD, DivX, 3ivX, mpeg-4))<br />
• <a href="http://nic.dnsalias.com/xvid.html">XviD codec</a><br />
• <a href="http://www.divx.com/divx/">DivX codec</a><br />
• <a href="http://sourceforge.net/project/showfiles.php?group_id=66022&release_id=178906">ac3filter</a> (для звука)<br />

• <a href="http://tobias.everwicked.com/oggds.htm">Ogg media codec</a> (для .OGM файлов и для звука)<br />
<br />
<br />
<b>.mov</b><br />
<br />
Это видео файлы от <a href="http://www.apple.com/quicktime/">QuickTime</a>. Оригинальную программу для их открытия можно скачать с сайта <a href="http://www.apple.com/quicktime/download/">QuickTime</a>.
Есть также альтернативная програма, скачать можно 
 <a href="http://download2.times.lv/master/files/0/Multimedia/Video/quicktimealt140.exe">отсюда</a>.<br />

<br />
<br />
<b>.ra .rm .ram</b><br />
<br />
Это видео файлы от <a href="http://www.real.com">Real.com</a>. Для их открытия рекомендуется использовать альтернативную программу - <a href=" http://download2.times.lv/master/files/0/Multimedia/Video/realalt130[www.free-codecs.com].exe">Real Alternative</a>.<br />
<br />
<br />
<b>.mp3 .mp2</b><br />
<br />

Музыкальные файлы. Открываются с помощью программы <a href="http://www.winamp.com/">WinAmp</a>.<br />
<br />
<br />
<b>.ogm .ogg</b><br />
<br />
Музыкальные или видео файлы. Если у вас установлен нужный кодек, то для их открытия подойдёт 
<a href="http://www.winamp.com">WinAmp</a> или <a href="http://softella.com/la/index.ru.htm">Light Alloy</a>.<br />
<br />

<br />
<tr><td class=colhead>
<font color="#000000"><center>Образы дисков</center></font>
</td></tr>
<td align="left">

<b>.bin .cue .iso</b><br />
<br />
Это стандартные образы CD-дисков. Образ диска - это точная копия CD-диска. Есть несколько вариантов открытия этих файлов. Можно записать их на CD, с помощью <a href="http://www.ahead.de">Nero</a>
(демо версия) или использовать программу для эмулирования cd-rom, <a href="http://www.daemon-tools.cc/portal/portal.php">Daemon Tools</a>.
<br />

<br />
<b>.ccd .img .sub</b><br />
<br />
Это образы программы <a href="http://www.elby.ch/english/products/clone_cd/index.html"> CloneCD</a>. Смысл тот же, что и .bin .cue .iso.<br />
<br />

<br />
<tr><td class=colhead>
<font color="#000000"><center>Другие файлы</center></font>
</td></tr>
<td align="left">


<b>.txt .doc</b><br />
<br />
Текстовые файлы. Файлы с расширением .txt можно открыть в любом текстовом редакторе. Файлы с расширением .doc можно открыть с помощью Microsoft Word.<br />
<br />
<br />
<b>.nfo</b><br />
<br />
Файлы с этим расширением содержат информацию о файлах, которые вы скачали. Рекомендуется их читать! Это текстовые файлы, часто содержащие ascii-art. Открыть можно с помощью Notepad, Wordpad, <a href="http://www.damn.to/software/nfoviewer.html">DAMN NFO Viewer</a>
или <a href="http://www.ultraedit.com/">UltraEdit</a>.<br />

<br />
<br />
<b>.pdf</b><br />
<br />
Открываются с помощью <a href="http://www.adobe.com/products/acrobat/main.html">Adobe Acrobat Reader</a>.<br />
<br />
<br />
<b>.jpg .gif .tga .psd</b><br />
<br />
Графические файлы. В основном содержат картинки, открыть можно с помощью Adobe
Photoshop или любым другим графическим редактором.<br />
<br />

<br />
<b>.sfv</b><br />
<br />
Служат для проверки целостности скаченных файлов. Для проверки используйте программу <a href="http://www.traction-software.co.uk/SFVChecker/">
SFVChecker</a> (Демо версия) или <a href="http://www.big-o-software.com/products/hksfv/">hkSFV</a>.<br />
<br />

<br />

<tr><td class=colhead>
<font color="#000000"><center>Ошибки, неточности</center></font>
</td></tr>
<td align="left">

<b><h2 align="center">Если заметите ошибки или неточности, обратитесь к <a href=staff.php><u>администрации</u></a><h2></b>

                </td>
            </table>

<?
}

stdfoot();

?>