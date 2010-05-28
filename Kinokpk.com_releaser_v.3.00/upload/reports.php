<?php
/**
 * Reports viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");
dbconn ();
loggedinorreturn ();

if (get_user_class () < UC_MODERATOR) {
	stderr ( $tracker_lang ['error'], $tracker_lang ['access_denied'] );
}

//Удалить все жалобы
if ($_POST ['deleteall']) {

	sql_query ( "TRUNCATE TABLE reports" ) or sqlerr ( __FILE__, __LINE__ );
}
//


//Удалить выбранные жалобы
if ($_POST ['delete'] && $_POST ['reports']) {
	$reports = $_POST ['reports'];

	foreach ( $reports as $id ) {
		sql_query ( "DELETE FROM reports WHERE id=" . sqlesc ( ( int ) $id ) );
	}
}
//


stdhead ( "Просмотр жалоб" );

$count = get_row_count ( "reports" );
if (! $count) {
	$empty = 0;
} else {
	$empty = 1;
}

?>
<center>
<h1>Поступившие жалобы</h1>
</center>
<div align=center>
<form id="message" action="reports.php" method="post"><input
	type="hidden" name="deleteall" value="deleteall"> <input type="submit"
	value="Удалить все жалобы" onClick="return confirm('Вы уверены?')"></form>
</div>
<br />

<form id="message" action="reports.php" method="post" name="form1"><input
	type="hidden" value="moveordel" name="action" />
<table border="0" cellspacing="0" width="100%" cellpadding="3">
	<tr>
		<td class=colhead>
		<center>Дата&nbsp;поступления</center>
		</td>
		<td class=colhead>
		<center>Жалоба&nbsp;от</center>
		</td>
		<td class=colhead>
		<center>Жалоба&nbsp;на</center>
		</td>
		<td class=colhead>
		<center>Причина&nbsp;жалобы</center>
		</td>
		<td class=colhead>
		<center><INPUT id="toggle-all" type="checkbox" title="Выбрать все"
			value="Выбрать все" /></center>
		</td>
	</tr>

	<?

	if ($empty) {

		$res = sql_query ( "SELECT reports.*,users.username,users.class FROM reports LEFT JOIN users ON reports.userid=users.id ORDER BY added DESC" ) or sqlerr ( __FILE__, __LINE__ );
		$allowed_types = array ('messages' => 'message.php?action=viewmessage&id=', 'torrents' => 'details.php?id=', 'users' => 'userdetails.php?id=', 'comments' => 'comment.php?action=edit&amp;cid=', 'pollcomments' => 'pollcomment.php?action=edit&amp;cid=', 'newscomments' => 'newscomment.php?action=edit&amp;cid=', 'usercomments' => 'usercomment.php?action=edit&amp;cid=', 'reqcomments' => 'reqcomment.php?action=edit&amp;cid=', 'relgroups' => 'relgroups.php?id=', 'rgcomments' => 'rgcomment.php?action=edit&amp;cid=', 'pages' => 'pagedetails.php?id=', 'pagecomments' => 'pagecomment.php?action=edit&amp;cid=' );

		while ( $row = mysql_fetch_array ( $res ) ) {

			$reportid = $row ["id"];
			$toid = $row ["reportid"];
			$userid = $row ["userid"];
			$motive = $row ["motive"];
			$type = $row ['type'];

			$added = mkprettytime ( $row ["added"] ) . ' (' . get_elapsed_time ( $row ['added'], false ) . " {$tracker_lang['ago']})";

			$username = $row ["username"];
			$userclass = $row ["class"];


			//foreach ($allowed_types as $atype)


			print ( "<tr>
        <td align='center'>$added</td>
        <td><b><a target='_blank' href='userdetails.php?id=$userid'>" . get_user_class_color ( $userclass, $username ) . "</a></b></td>
        <td><a href=\"{$allowed_types[$type]}$toid\">$type [$toid]</a></td>
        <td>$motive</td>
        <td align='center'>
        <INPUT type=\"checkbox\" name=\"reports[]\" title=\"Выбрать\" value=\"" . $reportid . "\"></td></tr>" );

		}

	} else {
		print ( "<tr><td align='center' colspan='5'>Нет ни одной жалобы...</td></tr>" );
	}

	?>

	<tr>
		<td class=colhead colspan="5">
		<div align=right><input type="submit" name="delete"
			value="Удалить выбранное" onClick="return confirm('Вы уверены?')" /></div>
		</td>
	</tr>
</table>
</form>

	<?
	stdfoot();

	?>
