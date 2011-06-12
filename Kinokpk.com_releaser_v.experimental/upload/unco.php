<?php
/**
 * Displays unconfirmed users
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require "include/bittorrent.php";
INIT();

loggedinorreturn();
get_privilege('edit_users');
httpauth();

$REL_TPL->stdhead($REL_LANG->say_by_key('not_confirmed_users'));
$REL_TPL->begin_main_frame();
$REL_TPL->begin_frame($REL_LANG->say_by_key('not_confirmed_users'));


$res = sql_query("SELECT * FROM users WHERE confirmed=0 ORDER BY username" ) or sqlerr(__FILE__, __LINE__);
if( mysql_num_rows($res) != 0 )
{
	print'<br /><table width=100% border=1 cellspacing=0 cellpadding=5>';
	print'<tr>';
	print'<td class=rowhead><center>'.$REL_LANG->say_by_key('username').'</center></td>';
	print'<td class=rowhead><center>'.$REL_LANG->say_by_key('email').'</center></td>';
	print'<td class=rowhead><center>'.$REL_LANG->say_by_key('registered').'</center></td>';
	print'<td class=rowhead><center>'.$REL_LANG->say_by_key('status').'</center></td>';
	print'<td class=rowhead><center>'.$REL_LANG->say_by_key('confirm').'</center></td>';
	print'</tr>';
	while( $row = mysql_fetch_assoc($res) )
	{
		$id = $row['id'];
		print'<tr><form method=post action="'.$REL_SEO->make_link('modtask').'">';
		print'<input type=hidden name=\'action\' value=\'confirmuser\'>';
		print("<input type=hidden name='userid' value='$id'>");
		print("<input type=hidden name='returnto' value='".$REL_SEO->make_link('unco')."'>");
		print make_user_link($row);
		print'<td align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['email'] . '</td>';
		print'<td align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . mkprettytime($row['added']) . '</td>';
		print'<td align=center><select name="confirm"><option value="0">'.$REL_LANG->say_by_key('not_confirmed').'</option><option value="1">'.$REL_LANG->say_by_key('confirmed').'</option></select></td>';
		print'<td align=center><input type="submit" value="OK" style=\'height: 20px; width: 40px\'>';
		print'</form></tr>';
	}
	print '</table>';
}
else
{
	print $REL_LANG->say_by_key('no_conf_usr');
}

$REL_TPL->end_frame();
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();
?>