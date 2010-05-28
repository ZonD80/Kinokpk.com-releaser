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
define("IN_CONTACT",true);
dbconn(false);
getlang('contact');
stdhead($tracker_lang['form_contact']);

?>
<br />
<form method="post" action="sendeail.php"><?
require_once("include/bittorrent.php");
?>
<div align="center">
<table border="0" cellspacing="0" cellpadding="3"
	style="border-collapse: collapse">
	<tr>
		<td class="colhead" colspan="2" align="center"><?=$tracker_lang['form_contact_for_admin']?></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?=$tracker_lang['your_name']?>&nbsp;</td>
		<? if ($CURUSER) { ?>
		<td><input type="text" value="<?php echo $CURUSER[username] ?>"
			size="40" disabled /></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?=$tracker_lang['your_email']?>&nbsp;</td>
		<td><input type="text" value="<?php echo $CURUSER[email] ?>" size="40"
			disabled /></td>
	</tr>
	<? } else {  ?>
	<td><input type="text" name="visitor" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?=$tracker_lang['your_email']?>&nbsp;</td>
		<td><input type="text" name="visitormail" size="40" /></td>
	</tr>
	<?  } ?>
	<tr>
		<td>&nbsp;&nbsp;<?=$tracker_lang['subject']?>&nbsp;</td>
		<td><select size="1" name="subj">
			<option selected><?=$tracker_lang['problem_activation']?></option>
			<option><?=$tracker_lang['bugs_site']?></option>
			<option><?=$tracker_lang['quest_realeases']?></option>
			<option><?=$tracker_lang['suggestions']?></option>
			<option><?=$tracker_lang['become_uploader']?></option>
			<option><?=$tracker_lang['acc_disabled']?></option>
			<option><?=$tracker_lang['other']?></option>
		</select></td>
	</tr>
	<tr>
		<td colspan="2">
		<center><font size="3"><b><?=$tracker_lang['your_message']?></b>:</font></center>
		<textarea name="notes" rows="8" cols="80"></textarea></td>
	</tr>
	<?
	// use_captcha
	if (!$CURUSER){

		require_once('include/recaptchalib.php');
		print '<tr><td colspan="2" align="center">'.$tracker_lang['you_people'].'</td></tr>';
		print '<tr><td colspan="2" align="center">'.recaptcha_get_html($CACHEARRAY['re_publickey']).'</td></tr>';
	}
	?>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="<?=$tracker_lang['submit']?>" /><input type="reset"
			value="<?=$tracker_lang['clean']?>" /></td>
	</tr>
</table>
</div>
	<? if ($CURUSER) { ?> <input type="hidden" name="visitor"
	value="<?php echo $CURUSER[username] ?>" /> <input type="hidden"
	name="visitormail" value="<?php echo $CURUSER[email] ?>" /> <? } ?></form>
	<?
	stdfoot();
	?>