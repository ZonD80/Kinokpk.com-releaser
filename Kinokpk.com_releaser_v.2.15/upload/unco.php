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
stdhead("Не подтвержденные пользователи");
begin_main_frame();
begin_frame("Не подтвержденные пользователи");


if (get_user_class() < UC_ADMINISTRATOR)
die;
$res = sql_query("SELECT * FROM users WHERE status='pending' ORDER BY username" ) or sqlerr(__FILE__, __LINE__);
if( mysql_num_rows($res) != 0 )
{
print'<br /><table width=100% border=1 cellspacing=0 cellpadding=5>';
print'<tr>';
print'<td class=rowhead><center>Пользователь</center></td>';
print'<td class=rowhead><center>eMail</center></td>';
print'<td class=rowhead><center>Зарегистрирован</center></td>';
print'<td class=rowhead><center>Статус</center></td>';
print'<td class=rowhead><center>Подтвердить</center></td>';
print'</tr>';
while( $row = mysql_fetch_assoc($res) )
{
$id = $row['id'];
print'<tr><form method=post action=modtask.php>';
print'<input type=hidden name=\'action\' value=\'confirmuser\'>';
print("<input type=hidden name='userid' value='$id'>");
print("<input type=hidden name='returnto' value='unco.php'>");
print'<a href="userdetails.php?id=' . $row['id'] . '"><td><center>' . $row['username'] . '</center></td></a>';
print'<td align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['email'] . '</td>';
print'<td align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['added'] . '</td>';
print'<td align=center><select name=confirm><option value=pending>Не подтвержден</option><option value=confirmed>Подтвержден</option></select></td>';
print'<td align=center><input type=submit value="OK" style=\'height: 20px; width: 40px\'>';
print'</form></tr>';
}
print '</table>';
}
else
{
	print 'Нет не подтвержденных пользователей...';
}

end_frame();
end_main_frame();
stdfoot();
?>