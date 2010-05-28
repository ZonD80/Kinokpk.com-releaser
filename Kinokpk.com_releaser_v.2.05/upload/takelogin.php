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

if (!mkglobal("username:password"))
	die();

dbconn();

function bark($text = "Имя пользователя или пароль неверны")
{
  stderr("Ошибка входа", $text);
}

$res = sql_query("SELECT id, passhash, secret, enabled, dis_reason, status, language FROM users WHERE username = " . sqlesc($username));
$row = @mysql_fetch_array($res);



if (!$row) {
  			if ($use_integration) {
  //////////////////////////////////////////////////////////////////////////////////////
// TRANSFER ACCOUNT FROM IPB
mysql_close();

// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
	sql_query("SET NAMES $fmysql_charset");
//connection opened

$ipbuser = sql_query("SELECT id, email, bday_day, bday_month, bday_year FROM ".$fprefix."members WHERE name=".sqlesc($username));
$ipbuser = @mysql_fetch_array($ipbuser);

if (!$ipbuser) die("<title>".$SITENAME." - Ошибка входа</title>Вы не зарегестрированны ни на форуме $FORUMNAME, ни на релизере $DEFAULTBASEURL");

$pwd = sql_query("SELECT converge_pass_hash,  converge_pass_salt FROM ".$fprefix."members_converge WHERE converge_email='".$ipbuser['email']."'");
$pwd = mysql_fetch_array($pwd);

$passhash = md5( md5($pwd['converge_pass_salt']) . md5($password) );


if ($passhash != $pwd['converge_pass_hash']) die ("<title>".$SITENAME." - Ошибка входа</title>Логин принят, но это неверный пароль для форума $FORUMNAME (перенос вашего аккаунта невозможен)");

$ipbusere = sql_query("SELECT aim_name, icq_number, website, yahoo, msnname, avatar_location, avatar_type FROM ".$fprefix."member_extra WHERE id=".$ipbuser['id']);
$ipbusere = mysql_fetch_array($ipbusere);

$forumgender = sql_query("SELECT pp_gender FROM ".$fprefix."profile_portal WHERE pp_member_id=".$ipbuser['id']);
if (!@mysql_result($forumgender,0)) $forumgender = ''; else $forumgender = mysql_result($forumgender,0);
//converting values

if ($forumgender == 'male') $forumgender = 1;
if ($forumgender == 'female') $forumgender = 2;
if ($forumgender == '') $forumgender = 3;

$year = $ipbuser['bday_year'];
$month = $ipbuser['bday_month'];
$day =  $ipbuser['bday_day'];

$birthday = date("$year.$month.$day");

$ipbusere['icq'] = strval($ipbusere['icq']);
if ($ipbusere['avatar_type'] == 'upload') $uavatar = "$FORUMURL/uploads/".$ipbusere['avatar_location'];
if (($ipbusere['avatar_type'] == 'local') && ($ipbusere['avatar_location'] <> '')) $uavatar = "$FORUMURL/style_avatars/".$ipbusere['avatar_location'];
if ($ipbuser['avatar_type'] == 'url') $uavatar = $ipbusere['avatar_location'];
// conv end
mysql_close();

// CREATE TRACKER ACCOUNT
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
	sql_query("SET NAMES $mysql_charset");

$secret = mksecret();
$wantpasshash = md5($secret . $password . $secret);

$users = get_row_count("users");

if ($users >= $maxusers)
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_users_limit'], number_format($maxusers)));
	
$editsecret = (!$users?"":mksecret());

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, gender, country, icq, msn, aim, yahoo, website, email, status, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot, avatar) VALUES (" .
		implode(",", array_map("sqlesc", array($username, $wantpasshash, $secret, $editsecret, $forumgender, 0, $ipbusere['icq'], $ipbusere['msnname'], $ipbusere['aim_name'], $ipbusere['yahoo'], $ipbusere['website'], $ipbuser['email'], confirmed))).
		", ". (!$users?UC_SYSOP.", ":""). "'". get_date_time() ."', '$birthday', '$default_language', 0, 0, '$uavatar')");// or sqlerr(__FILE__, __LINE__);

$res = sql_query("SELECT id, passhash, secret, enabled, status, language FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);


} else bark("Вы не зарегистрированы в системе.");
/////////////////////////////////////////////////////////////////////////////////////
// TRANSFER ACCOUNT END, NOW - LOGGING IN:
}


if ($row["status"] == 'pending')
	bark("Вы еще не активировали свой аккаунт! Активируйте ваш аккаунт и попробуйте снова.");

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
	bark();

$peers = sql_query("SELECT COUNT(id) FROM peers WHERE userid = $row[id]");
$num = mysql_fetch_row($peers);
$ip = getip();
if ($num[0] > 0 && $row[ip] != $ip && $row[ip])
	bark("Этот пользователь на данный момент активен. Вход невозможен.");

logincookie($row["id"], $row["passhash"], $row["language"]);

if ($row["enabled"] == "no")
	bark("Ваш аккаунт отключен. Причина: $row[dis_reason]");

if (!empty($_POST["returnto"]))
	header("Location: $DEFAULTBASEURL/$_POST[returnto]");
else
	header("Location: $DEFAULTBASEURL/");

?>