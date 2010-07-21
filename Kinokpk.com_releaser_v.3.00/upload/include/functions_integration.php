<?php
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

if (!defined("IN_TRACKER")) die('Direct access to this file not allowed.');

/**
 * Opens connection to forum database
 * @param boolean $close Close tracker connection? Default true
 * @return void
 */
function forumconn($close = TRUE){
	global $fmysql_host, $fmysql_user, $fmysql_pass, $fmysql_db, $fmysql_charset;

	if ($close) {
		@mysql_close();
	}
	// connecting to IPB DB

	$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
	or die ('Not connected : ' . mysql_error());
	mysql_select_db ($fmysql_db, $fdb);
	mysql_set_charset($fmysql_charset);
	//connection opened
	return;
}

/**
 * Opens connection to releaser database
 * @param boolean $close Close forum connection? Default true
 * @return void
 */
function relconn($close = TRUE){
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset;
	// closing IPB DB connection
	if ($close){
		@mysql_close();
	}
	// connection closed
	$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
	or die ('Not connected : ' . mysql_error());
	mysql_select_db ($mysql_db, $db);
	mysql_set_charset($mysql_charset);
	return;
}

/**
 * Generates ipb's password's salt
 * @param int $len Length of salt. Default 5
 * @return string Salt as it is
 */
function generate_password_salt($len=5)
{
	$salt = '';

	for ( $i = 0; $i < $len; $i++ )
	{
		$num   = rand(33, 126);

		if ( $num == '92' )
		{
			$num = 93;
		}

		$salt .= chr( $num );
	}

	return $salt;
}

/**
 * Generates complete IPB password hash
 * @param string $sol Password salt
 * @param string $wantpassword Password to hash
 * @return string Hashed password
 */
function generate_compiled_passhash($sol, $wantpassword)
{
	return md5( md5( $sol ) . $wantpassword );
}

/**
 * Generates IPB auto login key
 * @param int $len Length of key. Default 60
 * @return string Login key as it is
 */
function generate_auto_log_in_key($len=60)
{
	$pass = generate_password_salt( 60 );

	return md5($pass);
}


/**
 * Logins user to IPB
 * @param string $username username to login
 * @param boolean $forumconn Connect to forum database first? Default true
 * @return string Iframe code to set IPB cookes or empty string on fail
 * @see to_forum_root/releaser_setcookie.php
 */
function ipb_login($username, $forumconn=true)
{
	global $CACHEARRAY, $fprefix;

	if ($CACHEARRAY['use_integration']) {
		if ($forumconn) forumconn();
		$userrow = sql_query("SELECT id, member_login_key, members_display_name, mgroup FROM {$fprefix}members WHERE name = ".sqlesc($username));
		list($id,$passhash, $dispname, $group) = mysql_fetch_array($userrow);
		if (!$id) return '';
			
		sql_query("UPDATE {$fprefix}members SET member_login_key_expire = ".(time()+604800)." WHERE id=$id");
		$session_id = md5(uniqid(microtime()));
			
		sql_query("INSERT INTO {$fprefix}sessions (ip_address, member_name, member_id, running_time, member_group, login_type, id, browser, location, location_1_id, location_2_id, location_3_id) VALUES ('".getip()."','{$dispname}',$id,".time().",$group,0,'$session_id','".substr($_SERVER['HTTP_USER_AGENT'],0,50)."','idx,0,',0,0,0)");


		$s = ("<iframe frameborder=\"0\" src=\"{$CACHEARRAY['forumurl']}/releaser_setcookie.php?m=$id&p=$passhash&s=$session_id&c={$CACHEARRAY['ipb_cookie_prefix']}\" width=\"0\" height=\"0\"></iframe>");
			
		relconn();
		return $s;
	} else return '';

}

/**
 * Logouts user from IPB
 * @param string $username Username to logout
 * @return string Iframe code to unset IPB cookies or empty line on fail
 * @see to_forum_root/releaser_setcookie.php
 */
function ipb_logout($username)
{
	global $CACHEARRAY;

	if ($CACHEARRAY['use_integration']) {
		$s = ("<iframe frameborder=\"0\" src=\"{$CACHEARRAY['forumurl']}/releaser_setcookie.php?unset\" width=\"0\" height=\"0\"></iframe>");
		return $s;
	} else return '';


}

function register_ipb_user($wantusername,$password, $email, $gender, $year, $month, $day, $aim, $icq, $website, $yahoo, $msn, $time = 0, $relconn = true)

{
	global $CACHEARRAY, $fprefix;

	if ($CACHEARRAY['use_integration']) {
		// REGISTERING IPB USER /////////////////////////////////////////////////////////////////////////////////////////////////

		// connecting to IPB DB
		forumconn();
		//connection opened

		$ip = getip();

		if (!$time) $time = time();

		$salt = generate_password_salt();

		$passhash  =  generate_compiled_passhash( $salt, md5($password) );
		$gs = generate_auto_log_in_key();
		/////END OF PASSWORD GENERATOR/////
		/*function insert_db($table_name, $arr){
		sql_query("INSERT INTO ".$prefix.$table_name.$arr."");
		*/
		////register////

		$first = sql_query("INSERT INTO ".$fprefix."members_converge (converge_email,converge_joined,converge_pass_hash,converge_pass_salt)
            VALUES (" .implode(",", array_map("sqlesc", array($email,$time,$passhash,$salt))).")") or die(mysql_error());

		$idf = mysql_insert_id();

		$second = sql_query("INSERT INTO ".$fprefix."members (id,name,email,mgroup,joined,ip_address,members_display_name,members_l_display_name,members_l_username,member_login_key,bday_day,bday_month,bday_year)
            VALUES (" .implode(",", array_map("sqlesc", array($idf,$wantusername,$email,$CACHEARRAY['defuserclass'],$time,$ip,$wantusername,$wantusername,$wantusername,$gs,$day,$month,$year))).")");

		$icqint = intval($icq);
		$third = sql_query("INSERT INTO ".$fprefix."member_extra (id,notes,links,bio,ta_size,photo_type,photo_location,photo_dimensions,aim_name,icq_number,website,yahoo,interests,msnname,vdirs,location,signature,avatar_location,avatar_size,avatar_type) VALUES (".sqlesc($idf).", NULL, NULL, NULL, NULL, '', '', '', ".sqlesc($aim).", ".sqlesc($icqint).", ".sqlesc($website).", ".sqlesc($yahoo).", '', ".sqlesc($msn).", '', '', '', '', '', 'local')");

		if ($gender == 1) $forumgender = 'male';
		if ($gender == 2) $forumgender = 'female';
		if ($gender == 3) $forumgender = '';

		$fourth = sql_query("INSERT INTO ".$fprefix."profile_portal (pp_member_id,pp_gender) VALUES (".$idf.",'".$forumgender."')");

		// updating forum caches
		$statcache = sql_query("SELECT cs_value FROM ".$fprefix."cache_store WHERE cs_key = 'stats'");
		$statcache = mysql_result($statcache,0);
		$statcache = unserialize($statcache);
		$statcache['mem_count']++;
		$statcache['last_mem_name'] = $wantusername;
		$statcache['last_mem_id'] = $idf;
		$statcache = serialize($statcache);
		sql_query("UPDATE ".$fprefix."cache_store SET cs_value='".$statcache."' WHERE cs_key='stats'");
	 // closing IPB DB connection
		if ($relconn) relconn();
		// connection closed

		return array ('id' => $idf, 'email' => $email, 'bday_day' => $day, 'bday_month' => $month, 'bday_year' => $year);

		//////////END IPB REGISTRATION! //////////////////////////////////////////////////////////////////////////////////////
	} else return false;
}

/**
 * Convets birthday date to IPB format
 * @param string $date Date from releaser
 * @return array Date for IPB
 */
function ipb_bdate($date)
{
	return array('year' => substr($date,0,4), 'month' => substr($date,5,7), 'day' => substr($date,8,10));
}

/**
 * Deletes IPB user from IPB
 * @param string $name Username to be deleted
 * @return boolean True on success False on fail
 */
function delete_ipb_user ($name){
	global $CACHEARRAY, $fprefix;

	if ($CACHEARRAY['use_integration']) {
		forumconn();
		$useridrow = sql_query("SELECT id FROM {$fprefix}members WHERE name=".sqlesc($name));
		$userid = @mysql_result($useridrow,0);
		if (!$userid) { relconn(); return false; }

		sql_query('DELETE FROM '.$fprefix.'contacts WHERE member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'dnames_change WHERE dname_member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'members WHERE id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'members_converge WHERE converge_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'member_extra WHERE id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'message_topics WHERE mt_owner_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'pfields_content WHERE member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'profile_comments WHERE comment_for_member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'profile_friends WHERE friends_member_id ='.$userid);
		sql_query('DELETE FROM '.$fprefix.'profile_portal WHERE pp_member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'warn_logs WHERE wlog_mid='.$userid);
		relconn();
		return true;
	}
}

/**
 * Changes IPB user password
 * @param string $password New password
 * @param string $wantusername Username whitch password will be changed
 * @param boolean $forumconn Connect to forum database? Default true
 * @return boolean True on success False on fail
 * @see generate_password_salt()
 * @see generate_compiled_passhash()
 * @see ipb_login()
 */
function change_ipb_password($password, $wantusername, $forumconn = true)

{
	global $CACHEARRAY, $fprefix;

	if ($CACHEARRAY['use_integration']) {
		// REGISTERING IPB USER /////////////////////////////////////////////////////////////////////////////////////////////////

		// connecting to IPB DB
		if ($forumconn)
		forumconn();
		//connection opened

		$salt = generate_password_salt();

		$passhash  =  generate_compiled_passhash( $salt, md5($password) );
		$gs = generate_auto_log_in_key();
		/////END OF PASSWORD GENERATOR/////
		////change password////
		$idf = @mysql_result(sql_query("SELECT id FROM ".$fprefix."members WHERE name=".sqlesc($wantusername)),0);
		if (!$idf) { relconn(); return false; }

		sql_query("UPDATE ".$fprefix."members_converge SET converge_pass_hash=".sqlesc($passhash).", converge_pass_salt=".sqlesc($salt)." WHERE converge_id=$idf");
		sql_query("UPDATE ".$fprefix."members SET member_login_key=".sqlesc($gs)." WHERE id=$idf");

	 // closing IPB DB connection
		return ipb_login($wantusername,false);


		//////////END IPB REGISTRATION! //////////////////////////////////////////////////////////////////////////////////////
	} else return false;
}

?>