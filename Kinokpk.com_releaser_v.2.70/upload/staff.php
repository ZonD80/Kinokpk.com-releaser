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
$dt = time() - 300;

$res = sql_query("SELECT id,username,class, (SELECT SUM(1) FROM sessions WHERE uid=users.id AND time>$dt) AS online FROM users WHERE class>=".UC_UPLOADER." AND confirmed=1 ORDER BY class DESC, username ASC" ) or sqlerr(__FILE__, __LINE__);

while ($arr = mysql_fetch_assoc($res))
{

	$staff_table[$arr['class']]=$staff_table[$arr['class']].
"<td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id']."><b>".
	get_user_class_color($arr['class'],$arr['username'])."</b></a></td><td class=embedded> ".($arr['online']?"<img src=pic/button_online.gif border=0 alt=\"online\">":"<img src=pic/button_offline.gif border=0 alt=\"offline\">" )."</td>".
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
	<?php
	//var_dump($staff_table);
	foreach ($staff_table as $class => $data) {?>
	<tr>
		<td class=embedded colspan=11><b><?=get_user_class_name($class)?></b></td>
	</tr>
	<tr>
		<td class=embedded colspan=11>
		<hr color="#4040c0" size=1>
		</td>
	</tr>
	<tr height=15>
	<?=$data?>
	</tr>
	<tr>
		<td class=embedded colspan=11>&nbsp;</td>
	</tr>
	<?php } ?>
</table>
	<?
	end_frame();

	// LIST ALL FIRSTLINE SUPPORTERS
	// Search User Database for Firstline Support and display in alphabetical order
	$res = sql_query("SELECT users.id AS ids, users.last_access, users.username, users.supportfor, users.country, countries.name AS name, countries.flagpic AS flagpic FROM users LEFT JOIN countries ON users.country = countries.id WHERE support=1 AND confirmed=1 ORDER BY username LIMIT 10") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{

		$firstline .= "<tr height=15><td class=embedded><a class=altlink href=userdetails.php?id=".$arr['ids'].">".$arr['username']."</a></td>
<td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=pic/button_online.gif border=0 alt=\"online\">":"<img src=pic/button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=message.php?action=sendmessage&amp;receiver=".$arr['ids'].">"."<img src=pic/button_pm.gif border=0></a></td>".
"<td class=embedded><img src=pic/flag/$arr[flagpic] title=$arr[name] border=0 width=19 height=12></td>".
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