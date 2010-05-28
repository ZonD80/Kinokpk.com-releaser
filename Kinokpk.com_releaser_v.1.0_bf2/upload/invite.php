<?

require "include/bittorrent.php";

gzip();

dbconn();
loggedinorreturn();

$id = 0 + $_GET["id"];
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

$res = sql_query("SELECT invites FROM users WHERE id = $id") or sqlerr(__FILE__,__LINE__);

$inv = mysql_fetch_assoc($res);

if ($inv["invites"] != 1) {
	$_s = "ний";
} else {
	$_s = "ие";
}

if ($type == 'new') {
	print("<form method=get action=takeinvite.php>".
	"<input type=hidden name=id value=$id />".
	"<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=2><b>Создать пригласительный код (осталось $inv[invites] приглаше$_s)</b></td></tr>".
	"<tr class=tableb><td align=center colspan=2><input type=submit value=\"Создать\"></td></tr>".
	"</form></table>");
} elseif ($type == 'del') {
	$ret = sql_query("SELECT * FROM invites WHERE invite = ".sqlesc($invite)) or sqlerr(__FILE__,__LINE__);
	$num = mysql_fetch_assoc($ret);
	if ($num[inviter]==$id) {
		sql_query("DELETE FROM invites WHERE invite = ".sqlesc($invite)) or sqlerr(__FILE__,__LINE__);
		sql_query("UPDATE users SET invites = invites + 1 WHERE id = $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
		stdmsg("Успешно", "Приглашение удалено. Сейчас мы вас переадресуем на страницу приглашений...");
	} else
		stdmsg("Ошибка", "Вам не разрешено удалять приглашения.");
	header("Refresh: 3; url=invite.php?id=$id");
} else {
	if (get_user_class() <= UC_UPLOADER && !($id == $CURUSER["id"])) {
		bark("У вас нет права видеть приглашения этого пользователя.");
	}

	$rel = sql_query("SELECT COUNT(*) FROM users WHERE invitedby = $id") or sqlerr(__FILE__,__LINE__);
	$arro = mysql_fetch_row($rel);
	$number = $arro[0];

	$ret = sql_query("SELECT id, username, class, email, uploaded, downloaded, status, warned, enabled, donor, email FROM users WHERE invitedby = $id") or sqlerr(__FILE__,__LINE__);
	$num = mysql_num_rows($ret);

	print("<form method=post action=takeconfirm.php?id=$id><table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=7><b>Статус приглашенных вами</b> ($number)</td></tr>");

	if(!$num) {
		print("<tr class=tableb><td colspan=7>Еще никто вами не приглашен.</tr>");
	} else {
		print("<tr class=tableb><td><b>Пользователь</b></td><td><b>Email</b></td><td><b>Раздал</b></td><td><b>Скачал</b></td><td><b>Рейтинг</b></td><td><b>Статус</b></td>");
		if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP)
			print("<td align=center><b>Подтвердить</b></td>");
		print("</tr>");
		for ($i = 0; $i < $num; ++$i) {
			$arr = mysql_fetch_assoc($ret);
			if ($arr[status] == 'pending')
				$user = "<td align=left>$arr[username]</td>";
			else
		  		$user = "<td align=left><a href=userdetails.php?id=$arr[id]>" . get_user_class_color($arr["class"], "$arr[username]") . "</a>" . ($arr["warned"]  == "yes" ? "&nbsp;<img src=pic/warned.gif border=0 alt='Warned'>" : "") . ($arr["enabled"] == "no" ? "&nbsp;<img src=pic/disabled.gif border=0 alt='Disabled'>" : "") . ($arr["donor"]  == "yes" ? "&nbsp;<img src=pic/star.gif border=0 alt='Donor'>" : "")."</td>";
			if ($arr["downloaded"] > 0) {
				$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
				$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
			} else {
				if ($arr["uploaded"] > 0) {
					$ratio = "Inf.";
				} else {
					$ratio = "---";
				}
			}
			if ($arr["status"] == 'confirmed')
				$status = "<a href=userdetails.php?id=$arr[id]><font color=green>Подтвержден</font></a>";
			else
				$status = "<font color=red>Не подтвержден</font>";

			print("<tr class=tableb>$user<td>$arr[email]</td><td>" . mksize($arr[uploaded]) . "</td><td>" . mksize($arr[downloaded]) . "</td><td>$ratio</td><td>$status</td>");

			if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP) {
				print("<td align=center>");
				if ($arr[status] == 'pending')
					print("<input type=\"checkbox\" name=\"conusr[]\" value=\"" . $arr[id] . "\" />");
				print("</td>");
			}
			print("</tr>");
		}
	}
	if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP) {
		print("<input type=hidden name=email value=$arr[email]>");
		print("<tr class=tableb><td colspan=7 align=right><input type=submit value=\"Подтвердить пользователей\"></form></td></tr>");
	}
	print("</table><br>");

	$rul = sql_query("SELECT COUNT(*) FROM invites WHERE inviter = $id") or sqlerr(__FILE__,__LINE__);
	$arre = mysql_fetch_row($rul);
	$number1 = $arre[0];
	$rer = sql_query("SELECT inviteid, invite, time_invited FROM invites WHERE inviter = $id AND confirmed='no'") or sqlerr(__FILE__,__LINE__);
	$num1 = mysql_num_rows($rer);

	print("<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=6><b>Статус созданых приглашений</b> ($number1)</td></tr>");

	if(!$num1) {
		print("<tr class=tableb><td colspan=6>На данный момент вами не создано ниодного приглашения.</tr>");
	} else {
		print("<tr class=tableb><td><b>Код приглашения</b></td><td><b>Дата создания</b></td><td></td></tr>");
		for ($i = 0; $i < $num1; ++$i) {
			$arr1 = mysql_fetch_assoc($rer);
			print("<tr class=tableb><td>$arr1[invite]</td><td>$arr1[time_invited]</td>");
			print ("<td><a href=\"invite.php?invite=$arr1[invite]&type=del\">Удалить приглашение</a></td></tr>");
		}
	}

	print("<tr class=tableb><td colspan=7 align=center><form method=get action=invite.php?id=$id&type=new><input type='hidden' name='id' value='$id' /><input type='hidden' name='type' value='new' /><input type=submit value=\"Создать приглашение\"></form></td></tr>");
	print("</table>");
}
stdfoot();

?>