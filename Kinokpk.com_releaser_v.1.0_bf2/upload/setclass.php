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

dbconn(false);

loggedinorreturn();

// The following line may need to be changed to UC_MODERATOR if you don't have Forum Moderators
if ($CURUSER['class'] < UC_MODERATOR) die(); // No acces to below this rank
if ($CURUSER['override_class'] != 255) die(); // No access to an overridden user class either - just in case

if ($_GET['action'] == 'editclass') //Process the querystring - No security checks are done as a temporary class higher
{                                   //than the actual class mean absoluetly nothing.
$newclass = 0+$_GET['class'];
   $returnto = $_GET['returnto'];

   sql_query("UPDATE users SET override_class = ".sqlesc($newclass)." WHERE id = ".$CURUSER['id']); // Set temporary class

   header("Location: ".$DEFAULTBASEURL."/"/*.$returnto*/);
   die();
}

// HTML Code to allow changes to current class
stdhead("Смена класса");
?>

<form method=get action='setclass.php'>
<input type=hidden name='action' value='editclass'>
<input type=hidden name='returnto' value='userdetails.php?id=<?=$CURUSER['id']?>'> <!-- Change to any page you want -->
<table width=150 border=2 cellspacing=5 cellpadding=5>
<tr><td>Класс</td><td align=left><select name=class> <!-- Populate drop down box with all lower classes -->
<?
$maxclass = get_user_class() - 1;
for ($i = 0; $i <= $maxclass; ++$i)
print("<option value=$i" .">" . get_user_class_name($i) . "\n");
?>
</select></td></tr>
</td></tr>
<tr><td colspan=3 align=center><input type=submit class=btn value='Смена класса'></td></tr></form>
</form>
</table>
<br />
<?
stdfoot();
?>