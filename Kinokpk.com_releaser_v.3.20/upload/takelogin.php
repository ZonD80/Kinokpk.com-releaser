<?php
/**
 * Login parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

if (!mkglobal("username:password"))
die();

dbconn();

function bark($text = '')
{
	global $REL_LANG;
	if (!$text) $text = $REL_LANG->_("Username or password is invalid");
	stderr($REL_LANG->_("Login error"), $text);
}

function barkdb($text = '')
{
	global $REL_LANG;
	relconn();
	if (!$text) $text = $REL_LANG->_("Username or password is invalid");
	stderr($REL_LANG->_("Login error"), $text);
}
if (!validusername($username)) bark('Некорректное имя пользователя');

$res = sql_query("SELECT * FROM users WHERE username = " . sqlesc($username));
$row = @mysql_fetch_array($res);



if (!$row) {
	if ($REL_CONFIG['use_integration']) {

		//////////////////////////////////////////////////////////////////////////////////////
		// TRANSFER ACCOUNT FROM IPB


		// connecting to IPB DB
		forumconn();
		//connection opened

		$ipbuser = sql_query("SELECT id, email, bday_day, bday_month, bday_year FROM ".$fprefix."members WHERE name=".sqlesc($username));
		$ipbuser = @mysql_fetch_array($ipbuser);

		if (!$ipbuser) barkdb("Вы не зарегестрированны ни на форуме {$REL_CONFIG['forumname']}, ни на релизере {$REL_CONFIG['defaultbaseurl']}. <a href=\"javascript: history.go(-1)\">Назад</a>");

		$pwd = sql_query("SELECT converge_pass_hash,  converge_pass_salt FROM ".$fprefix."members_converge WHERE converge_email='".$ipbuser['email']."'");
		$pwd = mysql_fetch_array($pwd);

		$passhash = md5( md5($pwd['converge_pass_salt']) . md5($password) );


		if ($passhash != $pwd['converge_pass_hash']) barkdb("Логин принят, но это неверный пароль для форума {$REL_CONFIG['forumname']} (перенос вашего аккаунта невозможен) <a href=\"javascript: history.go(-1)\">Назад</a>");

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
		if ($ipbusere['avatar_type'] == 'upload') $uavatar = "{$REL_CONFIG['forumurl']}/uploads/".$ipbusere['avatar_location'];
		if (($ipbusere['avatar_type'] == 'local') && ($ipbusere['avatar_location'] <> '')) $uavatar = "{$REL_CONFIG['forumurl']}/style_avatars/".$ipbusere['avatar_location'];
		if ($ipbuser['avatar_type'] == 'url') $uavatar = $ipbusere['avatar_location'];
		// conv end


		// CREATE TRACKER ACCOUNT
		relconn();

		$secret = mksecret();
		$wantpasshash = md5($secret . $password . $secret);

		$users = get_row_count("users");

		if ($REL_CONFIG['maxusers'] && ($users >= $REL_CONFIG['maxusers']))
		stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_users_limit'), number_format($REL_CONFIG['maxusers'])));

		$editsecret = (!$users?"":mksecret());

		$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, gender, country, icq, msn, aim, yahoo, website, email, confirmed, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot, avatar) VALUES (" .
		implode(",", array_map("sqlesc", array($username, $wantpasshash, $secret, $editsecret, $forumgender, 0, $ipbusere['icq'], $ipbusere['msnname'], $ipbusere['aim_name'], $ipbusere['yahoo'], $ipbusere['website'], $ipbuser['email'], 1))).
		", ". (!$users?UC_SYSOP.", ":""). "'". time() ."', '$birthday', '{$REL_CONFIG['default_language']}', 0, 0, '$uavatar')");// or sqlerr(__FILE__, __LINE__);

		if (mysql_errno()==1062) {
			barkdb("Пользователь с ником $username уже зарегистрирован на форуме {$CACHEARRAY['forumname']}.");
		}
		$res = sql_query("SELECT * FROM users WHERE username = " . sqlesc($username));
		$row = mysql_fetch_array($res);

		$transfered = 1;
		/////////////////////////////////////////////////////////////////////////////////////
		// TRANSFER ACCOUNT END, NOW - Login
	}
	if (!$transfered) stderr($REL_LANG->say_by_key('error'),'Вы не зарегестрированы на сайте, но вы еще можете <a href="'.$REL_SEO->make_link('signup').'">зарегестрироваться</a>');
}

if ($REL_CONFIG['use_integration']) {

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
		if (!$ipbuser) barkdb($REL_LANG->say_by_key('error'), "Ошибка обрабоки данных входа, ошибка интеграции. Обратитесь к администратору ресурса");

		$pwdrow = sql_query("SELECT converge_pass_hash, converge_pass_salt FROM ".$fprefix."members_converge WHERE converge_email='".$ipbuser['email']."'");
		$pwd = mysql_fetch_array($pwdrow);

		$passhash = md5( md5($pwd['converge_pass_salt']) . md5($password) );


		if ($passhash != $pwd['converge_pass_hash'])
		if ($REL_CONFIG['ipb_password_priority']) barkdb("Введен неверный пароль. Возможно вы поменяли пароль на форуме {$REL_CONFIG['forumname']}. Для логина используйте именно его."); else {
			$s = change_ipb_password($password,$row['username']);
			//relconn();
		}
		else {


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

if (!$s)
$s = ipb_login($username);

$CURUSER = $row;

stdhead('Успешный вход');
if (!empty($_POST["returnto"]))
stdmsg("Вы успешно вошли!","$s<a href=\"".makesafe($_POST['returnto'])."\">Продолжить</a>");
else
stdmsg("Вы успешно вошли!","$s<a href=\"".$REL_CONFIG['defaultbaseurl']."\">Продолжить</a>");
stdfoot();

?>