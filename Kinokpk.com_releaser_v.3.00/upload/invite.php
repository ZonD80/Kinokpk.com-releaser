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

require_once "include/bittorrent.php";

dbconn();
getlang('invite');

loggedinorreturn();

if (isset($_GET['id']) && !is_valid_id($_GET['id'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

$id = (int) $_GET["id"];
$type = unesc($_GET["type"]);
$invite = $_GET["invite"];

stdhead("Приглашения");

function bark($msg) {
	stdmsg("Ошибка", $msg);
	stdfoot();
}

if ($id == 0) {
	$id = $CURUSER["id"];
}

if ($type == 'new') {
	print("<form method=post action=takeinvite.php>".
	"<input type=hidden name=id value=$id />".
	"<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=2><b>Создать пригласительный код</b></td></tr>".
	"<tr class=tableb><td align=center colspan=2>{$tracker_lang['invite_notice']}</td></tr>");
	tr("Введите email, куда будет выслано приглашение:",'<input type="text" size="40" name="email">',1,1);
	if ($CACHEARRAY['use_captcha']) {

		require_once('include/recaptchalib.php');
		tr ("Введите слова с картинки:",recaptcha_get_html($CACHEARRAY['re_publickey']),1,1);

	}

	print (	"<tr class=tableb><td align=center colspan=2><input type=submit value=\"Создать\"></td></tr>".
	"</form></table>");
} elseif ($type == 'del') {
	$ret = sql_query("SELECT * FROM invites WHERE invite = ".sqlesc($invite)) or sqlerr(__FILE__,__LINE__);
	$num = mysql_fetch_assoc($ret);
	if ($num[inviter]==$id) {
		sql_query("DELETE FROM invites WHERE invite = ".sqlesc($invite)) or sqlerr(__FILE__,__LINE__);
		stdmsg("Успешно", "Приглашение удалено.");
	} else
	stdmsg("Ошибка", "Вам не разрешено удалять приглашения.");
} else {
	if (get_user_class() <= UC_UPLOADER && !($id == $CURUSER["id"])) {
		bark("У вас нет права видеть приглашения этого пользователя.");
	}


	$ret = sql_query("SELECT u.id, u.username, u.class, u.email, u.ratingsum, u.warned, u.enabled, u.donor, u.email, invites.confirmed FROM invites LEFT JOIN users AS u ON u.id=invites.inviteid WHERE invitedby = $id") or sqlerr(__FILE__,__LINE__);
	$num = mysql_num_rows($ret);
	print("<form method=post action=takeconfirm.php?id=$id><table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=7><b>Статус приглашенных вами</b> (".(int)$num.")</td></tr>");

	if(!$num) {
		print("<tr class=tableb><td colspan=7>Еще никто вами не приглашен.</tr>");
	} else {
		print("<tr class=tableb><td><b>Пользователь</b></td><td><b>Email</b></td><td><b>Раздал</b></td><td><b>Скачал</b></td><td><b>Рейтинг</b></td><td><b>Статус</b></td>");
		if ($CURUSER[id] == $id || get_user_class() >= UC_MODERATOR)
		print("<td align=center><b>Подтвердить</b></td>");
		print("</tr>");
		for ($i = 0; $i < $num; ++$i) {
			$arr = mysql_fetch_assoc($ret);
			if (!$arr[confirmed])
			$user = "<td align=left>$arr[username]</td>";
			else
			$user = "<td align=left><a href=userdetails.php?id=$arr[id]>" . get_user_class_color($arr["class"], "$arr[username]") . "</a>" . ($arr["warned"] ? "&nbsp;<img src=pic/warned.gif border=0 alt='Warned'>" : "") . (!$arr["enabled"] ? "&nbsp;<img src=pic/disabled.gif border=0 alt='Disabled'>" : "") . ($arr["donor"] ? "&nbsp;<img src=pic/star.gif border=0 alt='Donor'>" : "")."</td>";

			$ratio = (($arr['ratingsum']>0)?"+{$arr['ratingsum']}":$arr['ratingsum']);

			if ($arr["confirmed"])
			$status = "<a href=userdetails.php?id=$arr[id]><font color=green>Подтвержден</font></a>";
			else
			$status = "<font color=red>Не подтвержден</font>";

			print("<tr class=tableb>$user<td>$arr[email]</td><td>" . mksize($arr[uploaded]) . "</td><td>" . mksize($arr[downloaded]) . "</td><td>$ratio</td><td>$status</td>");

			if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP) {
				print("<td align=center>");
				if (!$arr[confirmed])
				print("<input type=\"checkbox\" name=\"conusr[]\" value=\"" . $arr[id] . "\" />");
				print("</td>");
			}
			print("</tr>");
		}
	}
	if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP) {
		print("<input type=hidden name=email value=$arr[email]>");
		print("<tr class=tableb><td colspan=7 align=right><input type=submit value=\"Подтвердить пользователей, получить рейтинг и добавить их в друзья!\"></form></td></tr>");
	}
	print("</table><br />");

	$rul = sql_query("SELECT SUM(1) FROM invites WHERE inviter = $id") or sqlerr(__FILE__,__LINE__);
	$arre = mysql_fetch_row($rul);
	$number1 = $arre[0];
	$rer = sql_query("SELECT invite, time_invited FROM invites WHERE inviter = $id AND confirmed=0") or sqlerr(__FILE__,__LINE__);
	$num1 = mysql_num_rows($rer);

	print("<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=6><b>Статус созданых приглашений</b> ($number1)</td></tr>");

	if(!$num1) {
		print("<tr class=tableb><td colspan=6>На данный момент вами не создано ниодного приглашения.</tr>");
	} else {
		print("<tr class=tableb><td><b>Код приглашения</b></td><td><b>Дата создания</b></td><td>Удалить</td></tr>");
		for ($i = 0; $i < $num1; ++$i) {
			$arr1 = mysql_fetch_assoc($rer);
			print("<tr class=tableb><td>$arr1[invite]</td><td>".mkprettytime($arr1['time_invited'])."</td>");
			print ("<td><a href=\"invite.php?invite=$arr1[invite]&type=del\" onClick=\"return confirm('Вы уверены?')\">Удалить приглашение</a></td></tr>");
		}
	}

	print("<tr class=tableb><td colspan=7 align=center><form method=get action=invite.php?id=$id&type=new><input type='hidden' name='id' value='$id' /><input type='hidden' name='type' value='new' /><input type=submit value=\"Создать приглашение\"></form></td></tr>");
	print("</table>");
}
stdfoot();

?>