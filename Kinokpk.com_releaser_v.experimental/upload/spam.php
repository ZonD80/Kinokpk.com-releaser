<?php
/**
 * Private messages admin viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
INIT();
loggedinorreturn();
get_privilege('spamadmin');
httpauth();


$res2 = sql_query("SELECT SUM(1) FROM messages");
$row = mysql_fetch_array($res2);
$count = $row[0];

if (!$count){
	stderr("Извините, но сообщения не найдены.");
}

$limit = "LIMIT 50";
$REL_TPL->stdhead("ЛС пользователей");

?>

<form method="post" action="<?=$REL_SEO->make_link('take-delmp');?>"
	name="form1" id="message">

<table border="1" cellspacing="1" cellpadding="1" width="100%">
	<tr>
		<td colspan="5" class=colhead align=center>Просмотр сообщений (всего <font
			color="red"><?=$count?></font>)</td>
	</tr>
	<tr>
		<td colspan="5">
		<div style="float: right;"><input type="submit"
			value="Удалить выбранное!" onClick="return confirm('Вы уверены?')"></div>
		</td>
	</tr>
	<tr>
		<td class=colhead align=center>Отправитель/Получатель</td>
		<td class=colhead align=center>ID</td>
		<td class=colhead align=center>Содержание</td>
		<td class=colhead align=center>Дата</td>
		<td class=colhead>
		<center><INPUT type="checkbox" title="Выбрать все" value="Выбрать все"
			id="toggle-all"></center>
		</td>
	</tr>
	<tr>
	<?
	$res = sql_query("SELECT * FROM messages $where ORDER BY id DESC $limit") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{
		$res2 = sql_query("SELECT username, class, id, warned, donor, enabled FROM users WHERE id=".$arr["receiver"]) or sqlerr(__FILE__, __LINE__);
		$arr2 = mysql_fetch_assoc($res2);

		if($arr["receiver"] == 0 or !$arr["receiver"]){
			$receiver = "<strike><b>{$REL_LANG->_('Unknown')}</b></strike>";
		} else {
			$receiver = make_user_link($arr2);
		}

		$res3 = sql_query("SELECT username, class, id, warned, donor, enabled FROM users WHERE id=".$arr["sender"]) or sqlerr(__FILE__, __LINE__);
		$arr3 = mysql_fetch_assoc($res3);

		if($arr["sender"] == 0){
			$sender = "<font color=red><b>Системное</b></font>";
		} else {
			$sender = make_user_link($arr2);
		}
		$msg = format_comment($arr['msg']);
		$added = mkprettytime($arr['added']);

		print("<td align='left'>
        <div style='padding-top:5px; padding-bottom:10px;'>Отправитель:&nbsp;".$sender."</div>
        <div style='padding-top:10px; padding-bottom:5px;'>Получатель:&nbsp;".$receiver."</div>
        </td><td align=center><a href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$arr["id"])."\">".$arr["id"]."</a></td>
        <td>$msg</td>
        <td align=center>$added</td>");
		print("<TD align=center><INPUT type=\"checkbox\" name=\"delmp[]\" value=\"".$arr['id']."\" id=\"checkbox_tbl_".$arr['id']."\">
          </TD></tr>");
	}
	?>
		<tr>
			<td class=colhead colspan="4"></td>
			<td class=colhead>
			<center><INPUT type="checkbox" title="Выбрать все"
				value="Выбрать все" id="toggle-all"></center>
			</td>
		</tr>

		<?
		if ($where && $count){
			?>
		<tr>
			<td colspan="5"><a href="<?=$REL_SEO->make_link('spam');?>">Вернуться
			к общему списку сообщений</a></td>
		</tr>
		<?}?>

		<tr>
			<td colspan="5">
			<div style="float: right;"><input type="submit"
				value="Удалить выбранное!" onClick="return confirm('Вы уверены?')">
			</div>
			</td>
		</tr>

</table>
</form>
<br />
		<?
		$REL_TPL->stdfoot();
		?>