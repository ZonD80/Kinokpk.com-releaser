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
INIT();

$REL_TPL->stdhead($REL_LANG->say_by_key('form_contact'));

?>
<br />
<form method="post" action="<?=$REL_SEO->make_link('sendeail')?>"><?
?>
<div align="center">
<table border="0" cellspacing="0" cellpadding="3"
	style="border-collapse: collapse">
	<tr>
		<td class="colhead" colspan="2" align="center"><?=$REL_LANG->say_by_key('form_contact_for_admin')?></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?=$REL_LANG->say_by_key('your_name')?>&nbsp;</td>
		<? if ($CURUSER) { ?>
		<td><input type="text" value="<?php echo $CURUSER[username] ?>"
			size="40" disabled /></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?=$REL_LANG->say_by_key('your_email')?>&nbsp;</td>
		<td><input type="text" value="<?php echo $CURUSER[email] ?>" size="40"
			disabled /></td>
	</tr>
	<? } else {  ?>
	<td><input type="text" name="visitor" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?=$REL_LANG->say_by_key('your_email')?>&nbsp;</td>
		<td><input type="text" name="visitormail" size="40" /></td>
	</tr>
	<?  } ?>
	<tr>
		<td>&nbsp;&nbsp;<?=$REL_LANG->say_by_key('subject')?>&nbsp;</td>
		<td><select size="1" name="subj">
			<option selected><?=$REL_LANG->say_by_key('problem_activation')?></option>
			<option><?=$REL_LANG->say_by_key('bugs_site')?></option>
			<option><?=$REL_LANG->say_by_key('quest_realeases')?></option>
			<option><?=$REL_LANG->say_by_key('suggestions')?></option>
			<option><?=$REL_LANG->say_by_key('become_uploader')?></option>
			<option><?=$REL_LANG->say_by_key('acc_disabled')?></option>
			<option><?=$REL_LANG->say_by_key('other')?></option>
		</select></td>
	</tr>
	<tr>
		<td colspan="2">
		<center><font size="3"><b><?=$REL_LANG->say_by_key('your_message')?></b>:</font></center>
		<textarea name="notes" rows="8" cols="80"></textarea></td>
	</tr>
	<?
	// use_captcha
	if (!$CURUSER){

		require_once('include/recaptchalib.php');
		print '<tr><td colspan="2" align="center">'.$REL_LANG->say_by_key('you_people').'</td></tr>';
		print '<tr><td colspan="2" align="center">'.recaptcha_get_html($REL_CONFIG['re_publickey']).'</td></tr>';
	}
	?>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="<?=$REL_LANG->say_by_key('submit')?>" /><input type="reset"
			value="<?=$REL_LANG->say_by_key('clean')?>" /></td>
	</tr>
</table>
</div>
	<? if ($CURUSER) { ?> <input type="hidden" name="visitor"
	value="<?php echo $CURUSER[username] ?>" /> <input type="hidden"
	name="visitormail" value="<?php echo $CURUSER[email] ?>" /> <? } ?></form>
	<?
	$REL_TPL->stdfoot();
	?>