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
getlang('takesiteofflogin');
if (!mkglobal("username:password"))
die();

function bark($text)
{
	print("<title>".$tracker_lang['error']."!</title>");
	print("<table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>");
	print("<center><h1 style='color: #CC3300;'>".$tracker_lang['error'].":</h1><h2>$text</h2></center>");
	print("<center><INPUT TYPE='button' VALUE='".$tracker_lang['back']."' onClick=\"history.go(-1)\"></center>");
	print("</td></tr></table>");
	die;
}

if (!$_POST['username'] or !$_POST['password'])
bark($tracker_lang['not_spec']);

$res = sql_query("SELECT id, passhash, secret, enabled, confirmed FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);

if (!$row)
bark($tracker_lang['you_not_logged']);

if (!$row["confirmed"])
bark($tracker_lang['not_act_account']);

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
bark($tracker_lang['incorrect']);

if (!$row["enabled"])
bark($tracker_lang['this_acc_disabled']);

$peers = sql_query("SELECT COUNT(id) FROM peers WHERE userid = $row[id]");
$num = mysql_fetch_row($peers);
$ip = getip();
if ($num[0] > 0 && $row[ip] != $ip && $row[ip])
bark($tracker_lang['this_acc_active']);

logincookie($row["id"], $row["passhash"],$row['language']);
header("Refresh: 0; url='{$CACHEARRAY['defaultbaseurl']}'");
?>