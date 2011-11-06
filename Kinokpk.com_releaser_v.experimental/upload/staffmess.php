<?php
/**
 * Mass-PM to users
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once "include/bittorrent.php";
INIT();
loggedinorreturn();
get_privilege('mass_pm');
httpauth();

$REL_TPL->stdhead($REL_LANG->_('Mass PM'), false);
?>
<table class=main width=100% border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<div align=center>
		<form method=post name=message
			action="<?php print $REL_SEO->make_link('takestaffmess'); ?>"><?php
			if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
			{
				?> <input type=hidden name=returnto
			value=<?php print $_GET["returnto"] ? urlencode($_GET["returnto"]) : urlencode($_SERVER["HTTP_REFERER"]); ?>>
			<?php			}
			?>
		<table cellspacing=0 cellpadding=5>
			<tr>
				<td class="colhead" colspan="2"><?php print $REL_LANG->_('Mass PM');?></td>
			</tr>
			<tr>
				<td><?php print $REL_LANG->_('Receivers');?>:<br />
				<table style="border: 0" width="100%" cellpadding="0"
					cellspacing="0">
					<tr>
						<td><?php
						print make_classes_checkbox('classes');
						?></td>
					</tr>
				</table>
				</td>
			</tr>
			<TD colspan="2"><?php print $REL_LANG->_('Subject');?>: <INPUT name="subject" type="text" size="70"></TD>
			</TR>
			<tr>
				<td align="center"><?print textbbcode("msg",$body);?> <!--<textarea name=msg cols=80 rows=15><?php print $body; ?></textarea>-->
				</td>
			</tr>
			<tr>
				<td colspan=2>
				<div align="center"><b><?php print $REL_LANG->_('Sender');?>:&nbsp;&nbsp;</b> <?php print $CURUSER['username']; ?>
				<input name="sender" type="radio" value="self" checked> &nbsp;
				<?php print $REL_LANG->_('From system');?> <input name="sender" type="radio" value="system"></div>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center><input type=submit value="<?php print $REL_LANG->_('Send');?>"
					class=btn></td>
			</tr>
		</table>
		<input type=hidden name=receiver value=<?php print $receiver; ?>></form>

		</div>
		</td>
	</tr>
</table>
						<?php						$REL_TPL->stdfoot();
						?>