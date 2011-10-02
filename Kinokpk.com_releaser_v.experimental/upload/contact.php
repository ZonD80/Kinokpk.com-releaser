<?php
/**
 * Contact form
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");
define("IN_CONTACT",true);
INIT();

$REL_TPL->stdhead($REL_LANG->say_by_key('form_contact'));

?>
<br />
<form method="post" action="<?php print $REL_SEO->make_link('sendeail'); ?>"><?php?>
<div align="center">
<table border="0" cellspacing="0" cellpadding="3"
	style="border-collapse: collapse">
	<tr>
		<td class="colhead" colspan="2" align="center"><?php print $REL_LANG->say_by_key('form_contact_for_admin'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?php print $REL_LANG->say_by_key('your_name'); ?>&nbsp;</td>
		<?php if ($CURUSER) { ?>
		<td><input type="text" value="<?php echo $CURUSER[username] ?>"
			size="40" disabled /></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?php print $REL_LANG->say_by_key('your_email'); ?>&nbsp;</td>
		<td><input type="text" value="<?php echo $CURUSER[email] ?>" size="40"
			disabled /></td>
	</tr>
	<?php } else {  ?>
	<td><input type="text" name="visitor" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<?php print $REL_LANG->say_by_key('your_email'); ?>&nbsp;</td>
		<td><input type="text" name="visitormail" size="40" /></td>
	</tr>
	<?php  } ?>
	<tr>
		<td>&nbsp;&nbsp;<?php print $REL_LANG->say_by_key('subject'); ?>&nbsp;</td>
		<td><select size="1" name="subj">
			<option selected><?php print $REL_LANG->say_by_key('problem_activation'); ?></option>
			<option><?php print $REL_LANG->say_by_key('bugs_site'); ?></option>
			<option><?php print $REL_LANG->say_by_key('quest_realeases'); ?></option>
			<option><?php print $REL_LANG->say_by_key('suggestions'); ?></option>
			<option><?php print $REL_LANG->say_by_key('become_uploader'); ?></option>
			<option><?php print $REL_LANG->say_by_key('acc_disabled'); ?></option>
			<option><?php print $REL_LANG->say_by_key('other'); ?></option>
		</select></td>
	</tr>
	<tr>
		<td colspan="2">
		<center><font size="3"><b><?php print $REL_LANG->say_by_key('your_message'); ?></b>:</font></center>
		<textarea name="notes" rows="8" cols="80"></textarea></td>
	</tr>
	<?php	// use_captcha
	if (!$CURUSER){

		require_once('include/recaptchalib.php');
		print '<tr><td colspan="2" align="center">'.$REL_LANG->say_by_key('you_people').'</td></tr>';
		print '<tr><td colspan="2" align="center">'.recaptcha_get_html($REL_CONFIG['re_publickey']).'</td></tr>';
	}
	?>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="<?php print $REL_LANG->say_by_key('submit'); ?>" /><input type="reset"
			value="<?php print $REL_LANG->say_by_key('clean'); ?>" /></td>
	</tr>
</table>
</div>
	<?php if ($CURUSER) { ?> <input type="hidden" name="visitor"
	value="<?php echo $CURUSER[username] ?>" /> <input type="hidden"
	name="visitormail" value="<?php echo $CURUSER[email] ?>" /> <?php } ?></form>
	<?php	$REL_TPL->stdfoot();
	?>