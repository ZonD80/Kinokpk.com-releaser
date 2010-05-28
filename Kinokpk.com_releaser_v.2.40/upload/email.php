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
getlang('email');
loggedinorreturn();
gzip();
httpauth();

if (get_user_class() < UC_ADMINISTRATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);
stdhead($tracker_lang['bulk_email']);
begin_frame($tracker_lang['bulk_email'], "70", true);

?>
<form method=post name=message action=takeemail.php>
<table>
	<TR>
		<TD colspan="2" style="border: 0">&nbsp;<?=$tracker_lang['subject']?>
		<INPUT name="subject" type="text" size="70"></TD>
	</TR>
	<tr>
		<td align="center" style="border: 0"><? textbbcode("message","msg",$body, 0); ?>
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center style="border: 0"><input type=submit
			value="<?=$tracker_lang['submit']?>" class=btn></td>
	</tr>
</table>
</form>
<?
end_frame();
stdfoot();
?>