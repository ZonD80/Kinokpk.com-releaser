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
INIT();
loggedinorreturn ();

get_privilege('is_moderator');

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


$REL_TPL->stdhead ( "Просмотр жалоб" );

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
<form id="message" action="<?=$REL_SEO->make_link('reports');?>"
	method="post"><input type="hidden" name="deleteall" value="deleteall">
<input type="submit" value="Удалить все жалобы"
	onClick="return confirm('Вы уверены?')"></form>
</div>
<br />

<form id="message" action="<?=$REL_SEO->make_link('reports');?>"
	method="post" name="form1"><input type="hidden" value="moveordel"
	name="action" />
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

		$res = sql_query ( "SELECT reports.*,users.username,users.class,users.donor,users.warned, users.enabled FROM reports LEFT JOIN users ON reports.userid=users.id ORDER BY added DESC" ) or sqlerr ( __FILE__, __LINE__ );
		$allowed_types = array ('messages' => $REL_SEO->make_link('message','action','viewmessage','id',''), 'torrents' => $REL_SEO->make_link('details','id',''), 'users' => $REL_SEO->make_link('userdetails','id',''), 'rel' => $REL_SEO->make_link('comments','action','edit','cid',''), 'poll' => $REL_SEO->make_link('comments','action','edit','cid',''), 'news' => $REL_SEO->make_link('comments','action','edit','cid',''), 'user' => $REL_SEO->make_link('comments','action','edit','cid', ''), 'req' => $REL_SEO->make_link('comments','action','edit','cid',''), 'relgroups' => $REL_SEO->make_link('relgroups','id',''), 'rg' => $REL_SEO->make_link('comments','action','edit','cid',''), 'forum' => $REL_SEO->make_link('comments','action','edit','cid',''));
		$display_types = array ('messages' => $REL_LANG->_('PM'), 'torrents' => $REL_LANG->_('Release'), 'users' => $REL_LANG->_('Users'), 'rel' => $REL_LANG->_('Comments'), 'poll' => $REL_LANG->_('Pollcomments'), 'news' => $REL_LANG->_('Newscomments'), 'user' => $REL_LANG->_('Usercomments'), 'req' => $REL_LANG->_('Reqcomments'), 'relgroups' => $REL_LANG->_('Release Groups'), 'rg' => $REL_LANG->_('Rgcomments'), 'forum' => $REL_LANG->_('Forumcomments'));

		while ( $row = mysql_fetch_array ( $res ) ) {

			$reportid = $row ["id"];
			$toid = $row ["reportid"];
			$userid = $row ["userid"];
			$motive = $row ["motive"];
			$type = $display_types[$row ['type']];

			$added = mkprettytime ( $row ["added"] ) . ' (' . get_elapsed_time ( $row ['added'], false ) . " {$REL_LANG->say_by_key('ago')})";
			$userclass = $row ["class"];

			$user = $row;
			$user['id'] = $row['userid'];
			//foreach ($allowed_types as $atype)


			print ( "<tr>
        <td align='center'>$added</td>
        <td><b>".make_user_link($user)."</b></td>
        <td><a href=\"{$allowed_types[$row ['type']]}$toid\">$type [$toid]</a></td>
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
	$REL_TPL->stdfoot();

	?>