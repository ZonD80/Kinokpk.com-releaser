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
httpauth();

if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], "Нет доступа.");

if (!$_GET['action']) {


	stdhead("Peers that are unconnectable");
	print("<a href=findnotconnectable.php?action=sendpm><h3>Послать всем несоединябельным пирам массовое ПМ</h3></a>");
	print("<h1>Пиры с которыми нельзя соединиться</h1>");
	print("Это только те пользователи которые сейчас активны на торрентах.");

	print("<br /><font color=red>*</font> означает что пользователь сидирует.<p>");
	$result = sql_query("SELECT DISTINCT userid FROM peers WHERE connectable = 0");
	$count = mysql_num_rows($result);
	print ("$count уникальных пиров с которыми нельзя соединиться.");
	@mysql_free_result($result);
	list($pagertop, $pagerbottom, $limit) = pager(100, $count, "findnotconnectable.php?".$addparam);

	$res2 = sql_query("SELECT peers.userid, peers.seeder, peers.torrent, peers.agent, users.username FROM peers LEFT JOIN users ON peers.userid=users.id WHERE connectable=0 ORDER BY userid DESC $limit") or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res2) == 0)
	print("<p align=center><b>Со всеми пирами можно соединится!</b></p>\n");
	else
	{
		print("<table border=1 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=\"index\" colspan=\"3\">");
		print($pagertop);
		print("</td></tr>");
		print("<tr><td class=colhead>Пользователь</td><td class=colhead>Торрент</td><td class=colhead>Клиент</td></tr>\n");
		while($arr2 = mysql_fetch_assoc($res2))
		{
			print("<tr><td><a href=userdetails.php?id=$arr2[userid]>$arr2[username]</a></td><td align=left><a href=details.php?id=$arr2[torrent]&dllist=1#seeders>$arr2[torrent]");
			if ($arr2[seeder])
			print("<font color=red>*</font>");
			print("</a></td><td align=left>$arr2[agent]</td></tr>\n");
		}
		print("<tr><td class=\"index\" colspan=\"3\">");
		print($pagerbottom);
		print("</td></tr>");
		print("</table>\n");
	}
}

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST"){
	$dt = sqlesc(time());
	$msg = $_POST['msg'];
	if (!$msg)
	stderr($tracker_lang['error'],"Введите текст сообщения");

	$query = sql_query("SELECT distinct userid FROM peers WHERE connectable=0");
	while($dat=mysql_fetch_assoc($query)){
		$subject = sqlesc("Трекер определил вас несоединябельного");
		sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0,$dat[userid] , '" . time() . "', " . sqlesc($msg) . ", " . $subject .")") or sqlerr(__FILE__,__LINE__);
	}
	safe_redirect("findnotconnectable.php");


}

if ($_GET['action'] == "sendpm") {
	stdhead("Пиры с которыми нельзя соединиться");
	?>
<table class=main width=750 border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<div align=center>
		<h1>Общее сообщение для пользователей с которыми нельзя соединиться</a></h1>
		<form method=post action=findnotconnectable.php><?

		if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
		{
			?> <input type=hidden name=returnto
			value=<?=$_GET["returnto"] ? $_GET["returnto"] : $_SERVER["HTTP_REFERER"]?>>
			<?
		}
		//default message
		$body = "The tracker has determined that you are firewalled or NATed and cannot accept incoming connections. \n\nThis means that other peers in the swarm will be unable to connect to you, only you to them. Even worse, if two peers are both in this state they will not be able to connect at all. This has obviously a detrimental effect on the overall speed. \n\nThe way to solve the problem involves opening the ports used for incoming connections (the same range you defined in your client) on the firewall and/or configuring your NAT server to use a basic form of NAT for that range instead of NAPT (the actual process differs widely between different router models. Check your router documentation and/or support forum. You will also find lots of information on the subject at PortForward). \n\nAlso if you need help please come into our IRC chat room or post in the forums your problems. We are always glad to help out.\n\nThank You";
		?>
		<table cellspacing=0 cellpadding=5>
			<tr>
				<td>Send Mass Messege To All Non Connectable Users<br />
				<table style="border: 0" width="100%" cellpadding="0"
					cellspacing="0">
					<tr>
						<td style="border: 0">&nbsp;</td>
						<td style="border: 0">&nbsp;</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td><textarea name=msg cols=120 rows=15><?=$body?></textarea></td>
			</tr>
			<tr>
				<tr>
					<td colspan=2 align=center><input type=submit value="Отправить"
						class=btn></td>
				</tr>
		
		</table>
		<input type=hidden name=receiver value=<?=$receiver?>></form>

		</div>
		</td>
	</tr>
</table>
		<?
}
print("</table>");


stdfoot();

?>