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
getlang('testip');
loggedinorreturn();
if (get_user_class() < UC_MODERATOR) stderr($tracker_lang['error'], $tracker_lang['permission_denied']);

if ($_SERVER["REQUEST_METHOD"] == "POST")
$ip = $_POST["ip"];
else
$ip = $_GET["ip"];
if ($ip)
{
	$res = sql_query("SELECT mask FROM bans");
	while (list($mask) = mysql_fetch_array($res))
	$maskres[] = $mask;
	$ipsniff = new IPAddressSubnetSniffer($maskres);
	if (!$ipsniff->ip_is_allowed($ip) )
	stderr($tracker_lang['result'], "".$tracker_lang['ip_address']." <b>$ip</b> ".$tracker_lang['not_banned']."");
	else
	{
		stderr($tracker_lang['result'], "".$tracker_lang['ip_address']." <b>$ip ".$tracker_lang['banned']."</b>");
	}
}
stdhead($tracker_lang['check_ip']);

?>
<h1><?=$tracker_lang['check_ip']?></h1>
<form method=post action=testip.php>
<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<td class=rowhead><?=$tracker_lang['ip_address']?></td>
		<td><input type=text name=ip></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit class=btn value='OK'></td>
	</tr>
	</form>
</table>

<?
stdfoot();
?>