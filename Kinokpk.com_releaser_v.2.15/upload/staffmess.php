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

require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

stdhead("Общее сообщение", false);
?>
<table class=main width=100% border=0 cellspacing=0 cellpadding=0>
<tr><td class=embedded>
<div align=center>
<form method=post name=message action=takestaffmess.php>
<?

if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
{
?>
<input type=hidden name=returnto value=<?=$_GET["returnto"] ? urlencode($_GET["returnto"]) : urlencode($_SERVER["HTTP_REFERER"])?>>
<?
}
?>
<table cellspacing=0 cellpadding=5>
<tr><td class="colhead" colspan="2">Общее сообщение всем членам администрации и пользователям</td></tr>
<tr>
<td>Кому отправлять:<br />
  <table style="border: 0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_USER;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_USER);?></td>

             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_POWER_USER;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_POWER_USER);?></td>
             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_VIP;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_VIP);?></td>
             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_UPLOADER;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_UPLOADER);?></td>
             </tr><tr>
             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_MODERATOR;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_MODERATOR);?></td>
             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_ADMINISTRATOR;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_ADMINISTRATOR);?></td>
             <td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="<?=UC_SYSOP;?>">
             </td>
             <td style="border: 0"><?=get_user_class_name(UC_SYSOP);?></td>
       <td style="border: 0">&nbsp;</td>
       <td style="border: 0">&nbsp;</td>
      </tr>
    </table>
  </td>
</tr>
<TD colspan="2">Тема:
   <INPUT name="subject" type="text" size="70"></TD>
</TR>
<tr><td align="center">
<?textbbcode("message","msg",$body);?>
<!--<textarea name=msg cols=80 rows=15><?=$body?></textarea>-->
</td></tr>
<tr>
<td colspan=2><div align="center"><b>Отправитель:&nbsp;&nbsp;</b>
<?=$CURUSER['username']?>
<input name="sender" type="radio" value="self" checked>
&nbsp; Система
<input name="sender" type="radio" value="system">
</div></td></tr>
<tr><td colspan=2 align=center><input type=submit value="Отправить" class=btn></td></tr>
</table>
<input type=hidden name=receiver value=<?=$receiver?>>
</form>

 </div></td></tr></table>
<?
stdfoot();
?>