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

require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	header("Content-Type: text/html; charset=".$tracker_lang['language_charset']);
	if (empty($_POST["id"])) {
		stdmsg($tracker_lang['error'], "Вы не выбрали тип бонуса!");
		die();
	}
	$id = (int) $_POST["id"];
	if (!is_valid_id($id)) {
		stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
		die();
	}
	$res = sql_query("SELECT * FROM bonus WHERE id = $id") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	$points = $arr["points"];
	$type = $arr["type"];
	if ($CURUSER["bonus"] < $points) {
		stdmsg($tracker_lang['error'], "У вас недостаточно бонусов!");
		die();
	}
	switch ($type) {
		case "traffic":
			$traffic = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, uploaded = uploaded + $traffic WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			stdmsg($tracker_lang['success'], "Бонус обменян на траффик!");
			break;
		case "invite":
			$invites = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, invites = invites + $invites WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			stdmsg($tracker_lang['success'], "Бонус обменян на приглашения!");
			break;
		default:
			stdmsg($tracker_lang['error'], "Unknown bonus type!");
	}
} else {
stdhead($tracker_lang['my_bonus']);
?>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
function send(){

    var frm = document.mybonus;
	var bonus_type = '';

    for (var i=0;i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ bonus_type = elmnt.value; break;}
        }
    }

	var ajax = new tbdev_ajax();
	ajax.onShow ('');
	var varsString = "";
	ajax.requestFile = "mybonus.php";
	ajax.setVar("id", bonus_type);
	ajax.method = 'POST';
	ajax.element = 'ajax';
	ajax.sendAJAX(varsString);
}
</script>
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
	<div style="font-weight:bold" id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div><br />
	<img src="pic/loading.gif" border="0" />
</div>
<div id="ajax">
<table class="embedded" width="550" border="1" cellspacing="0" cellpadding="5">
<?
	$my_points = $CURUSER["bonus"];
	$res = sql_query("SELECT * FROM bonus") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_assoc($res)) {
		$id = $arr["id"];
		$bonus = $arr["name"];
		$points = $arr["points"];
		$descr = $arr["description"];
		$output .= "<tr><td><b>$bonus</b><br />$descr</td><td><center>$points&nbsp;/&nbsp;$my_points</center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id\" /></center></td></tr>\n";
	}
?>
	<tr><td class="colhead" colspan="3">Мой бонус (<?=$CURUSER["bonus"];?> бонусов в наличии / <?=$points_per_hour;?> бонусов в час)</td></tr>
	<tr><td class="colhead">Тип бонуса</td><td class="colhead">Очки</td><td class="colhead">Выбор</td></tr>
	<form action="mybonus.php" name="mybonus" method="post">
<?=$output;?>
		<tr><td colspan="3"><input type="submit" onClick="send(); return false;" value="Обменять" /></td></tr>
	</form>
</table>
</div>
<?
stdfoot();
}
?>