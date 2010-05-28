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

if (get_user_class() < UC_SYSOP) {
	die;
}

require_once($rootpath . 'include/cleanup.php');

$s_s = $queries;
docleanup();
$s_e = $queries;

stdhead("Очистка трекера");
stdmsg("Готово", "Очистка завершена успешно. На очистку использовано ".($s_e - $s_s)." запрос(ов).");
?>
<style type="text/css" media="all">
#newpm {
	position: absolute;
	height: 250px;
	width: 400px;
	padding: 4px;
	background-color: #fdfdfd;
	border: 1px solid #bbbbbb;
	font-family: verdana;
	line-height: 135%;
	filter: progid:DXImageTransform.Microsoft.Shadow(color=#cacaca, direction=135, strength=4);
}
#newpmheader {
	padding-left: 5px;
	padding-right: 5px;
	padding-top: 2px;
	height: 20px;
	color: #636363;
	font-weight: bold;
	background-color: #b2c7f1;
	font-family: verdana;
	cursor: move;
}
#newpm a {
	background: transparent;
	color: #4b73d4;
	text-decoration: none;
}
#newpm a:hover {
	background: transparent;
	color: #4b73d4;
	text-decoration: underline;
}
fieldset {
	border: 1px solid #e0e0e0;
	padding: 5px;
	text-align: left;
	font-size: 11px;		/* ! */
	font-family: tahoma;	/* ! */
}

</style>
<? global $unread; ?>
<div align="left">
<div id="newpm" style='display:none;'><div id="newpmheader">
<div style='float:right'><a href="#" onclick='document.getElementById("newpm").style.display="none"; return false;'>[X]</a></div>
<div title="Нажмите и удерживайте, для перемещения окна">Получены персональные сообщения</div></div>
<br /><img src="themes/<?=$ss_uri;?>/images/email.png" align="left" border=0>Уважаемый <b><?=$CURUSER["username"];?></b> с момента вашего отсутствия на сайте вам было прислано <b><?=$unread;?></b> новых персональных сообщений.

<br /><br />
Тема: <b>123 ...</b>
<fieldset><legend>Отправитель <b>Yuna</b></legend><div style="overflow: auto; width: 380px; height: 110px;">test</div></fieldset>

<div align="right"><a href="message.php">Прочитать сообщения</a> · <a href="#" onclick='document.getElementById("newpm").style.display="none"'>Закрыть окно</a></div>
</div>
</div>
<script type="text/javascript" src="http://127.0.0.16/engine/ajax/drag_window.js"></script>
<?
stdfoot();

?>