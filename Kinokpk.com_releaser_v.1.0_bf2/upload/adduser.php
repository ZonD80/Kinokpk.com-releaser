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
dbconn();
loggedinorreturn();
if (get_user_class() < UC_ADMINISTRATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "")
		stderr($tracker_lang['error'], $tracker_lang['missing_form_data']);
	if ($_POST["password"] != $_POST["password2"])
		stderr($tracker_lang['error'], $tracker_lang['password_mismatch']);
	$username = sqlesc(htmlspecialchars($_POST["username"]));
	$password = $_POST["password"];
	$email = sqlesc(htmlspecialchars($_POST["email"]));
	$secret = mksecret();
	$passhash = sqlesc(md5($secret . $password . $secret));
	$secret = sqlesc($secret);

	sql_query("INSERT INTO users (added, last_access, secret, username, passhash, status, email) VALUES(".sqlesc(get_date_time()).", ".sqlesc(get_date_time()).", $secret, $username, $passhash, 'confirmed', $email)") or sqlerr(__FILE__, __LINE__);
	$res = sql_query("SELECT id FROM users WHERE username=$username");
	$arr = mysql_fetch_row($res);
	if (!$arr)
		stderr($tracker_lang['error'], $tracker_lang['unable_to_create_account']);
	$wantpassword = $_POST["password"];
	$wantusername = $_POST["username"];
	$id = $arr[0];
	$timezone = '+1';
	$enabledst = 'yes';
	$ip = getip();
	unset($email);
	$email = trim($_POST["email"]);
	header("Location: $DEFAULTBASEURL/userdetails.php?id=$arr[0]");
	die;
}
stdhead($tracker_lang['add_user']);
?>
<h1><?=$tracker_lang['add_user'];?></h1>
<form method=post action=adduser.php>
<table border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead><?=$tracker_lang['username'];?></td><td><input type=text name=username size=40></td></tr>
<tr><td class=rowhead><?=$tracker_lang['password'];?></td><td><input type=password name=password size=40></td></tr>
<tr><td class=rowhead><?=$tracker_lang['repeat_password'];?></td><td><input type=password name=password2 size=40></td></tr>
<tr><td class=rowhead>E-mail</td><td><input type=text name=email size=40></td></tr>
<tr><td colspan=2 align=center><input type=submit value="OK" class=btn></td></tr>
</table>
</form>
<? stdfoot(); ?>