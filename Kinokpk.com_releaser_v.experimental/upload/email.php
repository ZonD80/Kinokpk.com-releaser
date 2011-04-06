<?php

/**
 * Email sender interface
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();

loggedinorreturn();
get_privilege('send_emails');
httpauth();

$REL_TPL->stdhead($REL_LANG->say_by_key('bulk_email'));
$REL_TPL->begin_frame($REL_LANG->say_by_key('bulk_email'), "70", true);

?>
<form method=post name=message
	action="<?=$REL_SEO->make_link('takeemail')?>">
<table>
	<TR>
		<TD colspan="2" style="border: 0">&nbsp;<?=$REL_LANG->say_by_key('subject')?>
		<INPUT name="subject" type="text" size="70"></TD>
	</TR>
	<tr>
		<td align="center" style="border: 0"><? print textbbcode("msg",$body); ?>
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center style="border: 0"><input type=submit
			value="<?=$REL_LANG->say_by_key('submit')?>" class=btn></td>
	</tr>
</table>
</form>
<?
$REL_TPL->end_frame();
$REL_TPL->stdfoot();
?>