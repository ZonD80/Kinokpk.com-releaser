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
stdhead("Администрация");
begin_main_frame();

// Get current datetime
$dt = gmtime() - 300;
$dt = sqlesc(get_date_time($dt));
// Search User Database for Moderators and above and display in alphabetical order
$res = sql_query("SELECT * FROM users WHERE class>=".UC_UPLOADER." AND status='confirmed' ORDER BY username" ) or sqlerr(__FILE__, __LINE__);

while ($arr = mysql_fetch_assoc($res))
{

	$staff_table[$arr['class']]=$staff_table[$arr['class']].
"<td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id']."><b>".
	get_user_class_color($arr['class'],$arr['username'])."</b></a></td><td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=pic/button_online.gif border=0 alt=\"online\">":"<img src=pic/button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=message.php?action=sendmessage&amp;receiver=".$arr['id'].">".
"<img src=pic/button_pm.gif border=0></a></td>".
" ";



	// Show 3 staff per row, separated by an empty column
	++ $col[$arr['class']];
	if ($col[$arr['class']]<=2)
	$staff_table[$arr['class']]=$staff_table[$arr['class']]."<td class=embedded>&nbsp;</td>";
	else
	{
		$staff_table[$arr['class']]=$staff_table[$arr['class']]."</tr><tr height=15>";
		$col[$arr['class']]=0;
	}
}
begin_frame("Администрация");
?>

<table width=100% cellspacing=0>
	<tr>
		<tr>
			<td class=embedded colspan=11>Вопросы, на которые есть ответы в
			правилах или FAQ, будут оставлены без внимания.</td>
		</tr>
		<!-- Define table column widths -->
		<td class=embedded width="125">&nbsp;</td>
		<td class=embedded width="25">&nbsp;</td>
		<td class=embedded width="35">&nbsp;</td>
		<td class=embedded width="85">&nbsp;</td>
		<td class=embedded width="125">&nbsp;</td>
		<td class=embedded width="25">&nbsp;</td>
		<td class=embedded width="35">&nbsp;</td>
		<td class=embedded width="85">&nbsp;</td>
		<td class=embedded width="125">&nbsp;</td>
		<td class=embedded width="25">&nbsp;</td>
		<td class=embedded width="35">&nbsp;</td>
	</tr>
	<tr>
		<td class=embedded colspan=11><b>Директорат трекера</b></td>
	</tr>
	<tr>
		<td class=embedded colspan=11>
		<hr color="#4040c0" size=1>
		</td>
	</tr>
	<tr height=15>
	<?=$staff_table[UC_SYSOP]?>
	</tr>
	<tr>
		<td class=embedded colspan=11>&nbsp;</td>
	</tr>
	<tr>
		<td class=embedded colspan=11><b>Администраторы</b></td>
	</tr>
	<tr>
		<td class=embedded colspan=11>
		<hr color="#4040c0" size=1>
		</td>
	</tr>
	<tr height=15>
	<?=$staff_table[UC_ADMINISTRATOR]?>
	</tr>
	<tr>
		<td class=embedded colspan=11>&nbsp;</td>
	</tr>
	<tr>
		<td class=embedded colspan=11><b>Модераторы</b></td>
	</tr>
	<tr>
		<td class=embedded colspan=11>
		<hr color="#4040c0" size=1>
		</td>
	</tr>
	<tr height=15>
	<?=$staff_table[UC_MODERATOR]?>
	</tr>
	<tr>
		<td class=embedded colspan=11>&nbsp;</td>
	</tr>
	<tr>
		<td class=embedded colspan=11><b>Аплоадеры</b></td>
	</tr>
	<tr>
		<td class=embedded colspan=11>
		<hr color="#4040c0" size=1>
		</td>
	</tr>
	<tr height=15>
	<?=$staff_table[UC_UPLOADER]?>
	</tr>
</table>
	<?
	end_frame();

	$dt = gmtime() - 180;
	$dt = sqlesc(get_date_time($dt));
	// LIST ALL FIRSTLINE SUPPORTERS
	// Search User Database for Firstline Support and display in alphabetical order
	$res = sql_query("SELECT * FROM users WHERE support='yes' AND status='confirmed' ORDER BY username LIMIT 10") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{
		$land = sql_query("SELECT name,flagpic FROM countries WHERE id=$arr[country]") or sqlerr(__FILE__, __LINE__);
		$arr2 = mysql_fetch_assoc($land);
		$firstline .= "<tr height=15><td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id'].">".$arr['username']."</a></td>
<td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=pic/button_online.gif border=0 alt=\"online\">":"<img src=pic/button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=message.php?action=sendmessage&amp;receiver=".$arr['id'].">"."<img src=pic/button_pm.gif border=0></a></td>".
"<td class=embedded><img src=pic/flag/$arr2[flagpic] title=$arr2[name] border=0 width=19 height=12></td>".
"<td class=embedded>".$arr['supportfor']."</td></tr>\n";
	}
	begin_frame("Первая линия поддержки");
	?>

<table width=100% cellspacing=0>
	<tr>
		<td class=embedded colspan=11>Общие вопросы лучше задавать этим
		пользователям. Учтите что они добровольцы, тратящие свое время и силы
		на помощь вам. Относитесь к ним подобающе.<br />
		<br />
		<br />
		</td>
	</tr>
	<!-- Define table column widths -->
	<tr>
		<td class=embedded width="30"><b>Пользователь&nbsp;</b></td>
		<td class=embedded width="5"><b>Активен&nbsp;</b></td>
		<td class=embedded width="5"><b>Контакт&nbsp;</b></td>
		<td class=embedded width="85"><b>Язык&nbsp;</b></td>
		<td class=embedded width="200"><b>Поддержка для&nbsp;</b></td>
	</tr>


	<tr>
		<tr>
			<td class=embedded colspan=11>
			<hr color="#4040c0" size=1>
			</td>
		</tr>

		<?=$firstline?>

	</tr>
</table>
<?
end_frame();

?>
<?
end_main_frame();
stdfoot();
?>