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

$REL_TPL->stdhead("Общее сообщение", false);
?>
<table class=main width=100% border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<div align=center>
		<form method=post name=message
			action="<?=$REL_SEO->make_link('takestaffmess');?>"><?

			if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
			{
				?> <input type=hidden name=returnto
			value=<?=$_GET["returnto"] ? urlencode($_GET["returnto"]) : urlencode($_SERVER["HTTP_REFERER"])?>>
			<?
			}
			?>
		<table cellspacing=0 cellpadding=5>
			<tr>
				<td class="colhead" colspan="2">Общее сообщение всем членам
				администрации и пользователям</td>
			</tr>
			<tr>
				<td>Кому отправлять:<br />
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
			<TD colspan="2">Тема: <INPUT name="subject" type="text" size="70"></TD>
			</TR>
			<tr>
				<td align="center"><?print textbbcode("msg",$body);?> <!--<textarea name=msg cols=80 rows=15><?=$body?></textarea>-->
				</td>
			</tr>
			<tr>
				<td colspan=2>
				<div align="center"><b>Отправитель:&nbsp;&nbsp;</b> <?=$CURUSER['username']?>
				<input name="sender" type="radio" value="self" checked> &nbsp;
				Система <input name="sender" type="radio" value="system"></div>
				</td>
			</tr>
			<tr>
				<td colspan=2 align=center><input type=submit value="Отправить"
					class=btn></td>
			</tr>
		</table>
		<input type=hidden name=receiver value=<?=$receiver?>></form>

		</div>
		</td>
	</tr>
</table>
						<?
						$REL_TPL->stdfoot();
						?>