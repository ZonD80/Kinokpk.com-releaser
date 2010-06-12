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

if ($CURUSER)
stderr($tracker_lang['error'], "Вы уже вошли на {$CACHEARRAY['sitename']}!");

stdhead("Вход");

unset($returnto);
if (!empty($_GET["returnto"])) {
	$returnto = $_GET["returnto"];
	if (!$_GET["nowarn"]) {
		$error = "<table style=\"margin: 0 auto\"><tr class=\"error_login\"><td colspan=\"2\" style=\"border:none\"><img src=\"pic/attention_login.gif\" alt=\"attention\"/></td><td colspan=\"2\" style=\"border:none; vertical-align: middle;\">К сожалению страница, которую вы пытаетесь посмотреть <b>доступна только вошедшим в систему</b>.<br />После успешного входа вы будете переадресованы на запрошеную страницу.</td></tr></table>";
		//print("<h1>Не авторизированы!</h1>\n");
		//print("<p><b>Ошибка:</b> Страница, которую вы пытаетесь посмотреть, доступна только зарегистрированым.</p>\n");
	}
}

if (isset($error)) {
	echo $error;
}
/*
 //old login table begin
 <table border="0" cellpadding="5" width="450px" style="border:none; background:url(./pic/login.gif) no-repeat center; height:150px;">
 <tr style="height:30px"><td style="border:none;" class="rowhead"></td></tr>
 <tr style="height:30px"><td style="border:none; vertical-align:bottom;" class="rowhead">Пользователь:</td><td align="left" style="border:none; vertical-align:bottom;" width="275px"><input type="text" size="40" name="username" style="width: 200px; border: 1px solid gray" /></td></tr>
 <tr style="height:30px"><td class="rowhead" style="border:none;">Пароль:</td><td align="left" style="border:none;"><input type="password" size="40" name="password" style="width: 200px; border: 1px solid gray" /></td></tr>
 <tr><td colspan="2" align="center" style="border:none; vertical-align:top;"><input type="submit" value="Войти" class="btn" /></td></tr>
 </table>
 //old login table end
 */
?>
<div align="center">
<form method="post" action="takelogin.php">
<p><b>Внимание</b>: для успешного входа активизируйте cookies.</p>
<table border="0" cellpadding="5" width="450px"
	style="border: none; background: url(./pic/login.gif) no-repeat center; height: 150px;">
	<tr style="height: 30px">
		<td style="border: none;" class="rowhead"></td>
	</tr>
	<tr style="height: 30px">
		<td style="border: none; vertical-align: middle;" class="rowhead">Пользователь:</td>
		<td align="left" style="border: none; vertical-align: bottom;"
			width="275px"><input style="border: 1px solid gray" name="username"
			value="Username" type="text" class="searchtextbox"
			onblur="if(this.value=='') this.value='Username';"
			onfocus="if(this.value=='Username') this.value='';" /></td>
	</tr>
	<tr style="height: 30px">
		<td class="rowhead" style="border: none; vertical-align: middle;">Пароль:</td>
		<td align="left" style="border: none;"><input
			style="border: 1px solid gray" name="password" value="password"
			type="password" class="searchtextbox"
			onblur="if(this.value=='') this.value='password';"
			onfocus="if(this.value=='password') this.value='';" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"
			style="border: none; vertical-align: top;"><input type="submit"
			value="Войти" class="btn" /></td>
	</tr>
</table>
<?

if (isset($returnto))
print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($returnto) . "\" />\n");

?></form>
<p>Если Вы забыли пароль или Вы не можете зайти - попытайтесь
воспользоваться формой <a href="recover.php">восстановления паролей</a></p>
<p>Ещё не зарегистрированы ? Вы можете <a href="signup.php">зарегистрироваться</a>
прямо сейчас!</p>
</div>
<?

stdfoot();

?>