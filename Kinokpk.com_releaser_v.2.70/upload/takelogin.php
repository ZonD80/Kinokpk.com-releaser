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

function barkdb($text = "Имя пользователя или пароль неверны")
{
	relconn();
	stderr("Ошибка входа", $text);
}

if (!validusername($username)) bark('Некорректное имя пользователя');

$res = sql_query("SELECT * FROM users WHERE username = " . sqlesc($username));
$row = @mysql_fetch_array($res);



if (!$row) {
	if ($CACHEARRAY['use_integration']) {

		//////////////////////////////////////////////////////////////////////////////////////
		// TRANSFER ACCOUNT FROM IPB


		// connecting to IPB DB
		forumconn();
		//connection opened

		$ipbuser = sql_query("SELECT id, email, bday_day, bday_month, bday_year FROM ".$fprefix."members WHERE name=".sqlesc($username));
		$ipbuser = @mysql_fetch_array($ipbuser);

		if (!$ipbuser) barkdb("Вы не зарегестрированны ни на форуме {$CACHEARRAY['forumname']}, ни на релизере {$CACHEARRAY['defaultbaseurl']}. <a href=\"javascript: history.go(-1)\">Назад</a>");

		$pwd = sql_query("SELECT converge_pass_hash,  converge_pass_salt FROM ".$fprefix."members_converge WHERE converge_email='".$ipbuser['email']."'");
		$pwd = mysql_fetch_array($pwd);

		$passhash = md5( md5($pwd['converge_pass_salt']) . md5($password) );


		if ($passhash != $pwd['converge_pass_hash']) barkdb("Логин принят, но это неверный пароль для форума {$CACHEARRAY['forumname']} (перенос вашего аккаунта невозможен) <a href=\"javascript: history.go(-1)\">Назад</a>");

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
		if ($ipbusere['avatar_type'] == 'upload') $uavatar = "{$CACHEARRAY['forumurl']}/uploads/".$ipbusere['avatar_location'];
		if (($ipbusere['avatar_type'] == 'local') && ($ipbusere['avatar_location'] <> '')) $uavatar = "{$CACHEARRAY['forumurl']}/style_avatars/".$ipbusere['avatar_location'];
		if ($ipbuser['avatar_type'] == 'url') $uavatar = $ipbusere['avatar_location'];
		// conv end


		// CREATE TRACKER ACCOUNT
		relconn();

		$secret = mksecret();
		$wantpasshash = md5($secret . $password . $secret);

		$users = get_row_count("users");

		if ($users >= $CACHEARRAY['maxusers'])
		stderr($tracker_lang['error'], sprintf($tracker_lang['signup_users_limit'], number_format($CACHEARRAY['maxusers'])));

		$editsecret = (!$users?"":mksecret());

		$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, gender, country, icq, msn, aim, yahoo, website, email, confirmed, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot, avatar) VALUES (" .
		implode(",", array_map("sqlesc", array($username, $wantpasshash, $secret, $editsecret, $forumgender, 0, $ipbusere['icq'], $ipbusere['msnname'], $ipbusere['aim_name'], $ipbusere['yahoo'], $ipbusere['website'], $ipbuser['email'], 1))).
		", ". (!$users?UC_SYSOP.", ":""). "'". time() ."', '$birthday', '{$CACHEARRAY['default_language']}', 0, 0, '$uavatar')");// or sqlerr(__FILE__, __LINE__);

		if (mysql_errno()==1062) bark();
		$res = sql_query("SELECT * FROM users WHERE username = " . sqlesc($username));
		$row = mysql_fetch_array($res);

		$transfered = 1;
		/////////////////////////////////////////////////////////////////////////////////////
		// TRANSFER ACCOUNT END, NOW - Login
	}
}

if ($CACHEARRAY['use_integration']) {

	// Compare IPB and Releaser passwords ////////////////////// (if releaser account present)
	if (!$transfered){
		$checkpasshash = $row['passhash'];
		$uid = $row['id'];
		$secret = $row['secret'];
		// connecting to IPB DB
		forumconn();
		//connection opened

		$ipbuser = sql_query("SELECT id, email, bday_day, bday_month, bday_year FROM ".$fprefix."members WHERE name=".sqlesc($username));
		$ipbuser = @mysql_fetch_array($ipbuser);

		if (!$ipbuser) {
			$bdate = ipb_bdate($row['birthday']);

			$ipbuser = register_ipb_user($row['username'],$password, $row['email'], $row['gender'], $bdate['year'], $bdate['month'], $bdate['day'], $row['aim'], $row['icq'], $row['website'], $row['yahoo'], $row['msn'], $row['added'], false);
		}
		if (!$ipbuser) barkdb($tracker_lang['error'], "Ошибка обрабоки данных входа, ошибка интеграции. Обратитесь к администратору ресурса");

		$pwdrow = sql_query("SELECT converge_pass_hash, converge_pass_salt FROM ".$fprefix."members_converge WHERE converge_email='".$ipbuser['email']."'");
		$pwd = mysql_fetch_array($pwdrow);

		$passhash = md5( md5($pwd['converge_pass_salt']) . md5($password) );


		if ($passhash != $pwd['converge_pass_hash']) barkdb("Введен неверный пароль. Возможно вы поменяли пароль на форуме {$CACHEARRAY['forumname']}. Для логина используйте именно его."); else {


			$wantpasshash = md5($secret . $password . $secret);
			relconn();
			if ($checkpasshash != $wantpasshash) {
				$newsecret = mksecret();
				$wantpasshash = md5($newsecret . $password . $newsecret);

				//print('BLALALAALAL');
				sql_query("UPDATE users SET passhash = ".sqlesc($wantpasshash).", secret = ".sqlesc($newsecret)."  WHERE id = $uid");
				$row['passhash'] = $wantpasshash;
				$row['secret'] = $newsecret;
			}
		}

	}

}


if (!$row["confirmed"])
bark("Вы еще не активировали свой аккаунт! Активируйте ваш аккаунт и попробуйте снова.");

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
bark();

logincookie($row["id"], $row["passhash"], $row["language"]);

if (!$row["enabled"])
bark("Ваш аккаунт отключен. Причина: $row[dis_reason]");

$s = ipb_login($username);

$CURUSER = $row;

stdhead('Успешный вход');
if (!empty($_POST["returnto"]))
stdmsg("Вы успешно вошли!","$s<a href=\"".makesafe($_POST['returnto'])."\">Продолжить</a>");
else
stdmsg("Вы успешно вошли!","$s<a href=\"".$CACHEARRAY['defaultbaseurl']."\">Продолжить</a>");
stdfoot();

?>