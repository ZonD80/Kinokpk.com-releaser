<?php
/**
 * Reports viewer & processor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");
INIT();
loggedinorreturn();

get_privilege('is_moderator');


//Удалить все жалобы
if ($_POST['deleteall']) {

	sql_query("TRUNCATE TABLE report") or sqlerr(__FILE__,__LINE__);
}
//


//Удалить выбранные жалобы
if ($_POST['delete'] && $_POST['reports']) {
	$reports = $_POST['reports'];

	foreach ($reports as $id) {
		sql_query("DELETE FROM report WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
	}
}
//

$REL_TPL->stdhead("Просмотр жалоб на раздачи");

$count = get_row_count("report");
if (!$count) {
	$empty = 0;
} else {
	$empty = 1;
}

?>
<script language="Javascript" type="text/javascript">
        <!-- Begin
        var checkflag = "false";
        var marked_row = new Array;
        function check(field) {
                if (checkflag == "false") {
                        for (i = 0; i < field.length; i++) {
                                field[i].checked = true;}
                                checkflag = "true";
                        }
                else {
                        for (i = 0; i < field.length; i++) {
                                field[i].checked = false; }
                                checkflag = "false";
                        }
                }
                //  End -->
        </script>

<center>
<h1>Поступившие жалобы на раздачи</h1>
</center>
<div align=center>
<form action="<?=$REL_SEO->make_link('viewreport');?>" method="post"><input
	type="hidden" name="deleteall" value="deleteall"> <input type="submit"
	value="Удалить все жалобы" onClick="return confirm('Вы уверены?')"></form>
</div>
<br />

<form action="<?=$REL_SEO->make_link('viewreport');?>" method="post"
	name="form1">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
	<tr>
		<td class=colhead>
		<center>Дата&nbsp;поступления</center>
		</td>
		<td class=colhead>
		<center>Жалоба&nbsp;от</center>
		</td>
		<td class=colhead>
		<center>Жалоба&nbsp;на&nbsp;торрент</center>
		</td>
		<td class=colhead>
		<center>Причина&nbsp;жалобы</center>
		</td>
		<td class=colhead>
		<center><INPUT type="checkbox" title="Выбрать все" value="Выбрать все"
			onClick="this.value=check(document.form1.elements);"></center>
		</td>
	</tr>

	<?

	if ($empty){

		$res = sql_query("SELECT * FROM report ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);
		while ($row = mysql_fetch_array($res)) {

			$reportid = $row["id"];
			$torrentid = $row["torrentid"];
			$userid = $row["userid"];
			$motive = $row["motive"];
			$added = $row["added"];

			$res1 = sql_query("SELECT id, username, class, warned, donor, enabled FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
			$row1 = mysql_fetch_array($res1);

			$username = $row1["username"];
			$userclass = $row1["class"];

			if ($username == ""){
				$username = "<b><font color='red'>Аноним<font></b>";
			}

			$res2 = sql_query("SELECT id, name FROM torrents WHERE id = $torrentid") or sqlerr(__FILE__, __LINE__);
			$row2 = mysql_fetch_array($res2);

			if ($row2["id"]){
				$torrentname = $row2["name"];
				$torrenturl = "<a target='_blank' href='".$REL_SEO->make_link('details','id',$torrentid,'name',translit($torrentname))."'>$torrentname</a>";
			} else {
				$torrenturl = "<b><font color='red'>торрент удален<font></b>";
			}


			print ("<tr>
        <td align='center'>$added</td>
        <td>".make_user_link($row1)."</td>
        <td>$torrenturl</td>
        <td>$motive</td>
        <td align='center'>
        <INPUT type=\"checkbox\" name=\"reports[]\" title=\"Выбрать\" value=\"".$reportid."\" id=\"checkbox_tbl_".$reportid."\"></td></tr>");

		}

	}
	else
	{
		print("<tr><td align='center' colspan='5'>Нет ни одной жалобы на раздачи...</td></tr>");
	}

	?>

	<tr>
		<td class=colhead colspan="5">
		<div align=right><input type="submit" name="delete"
			value="Удалить выбранное" onClick="return confirm('Вы уверены?')"></div>
		</td>
	</tr>
</table>
</form>

	<?
	$REL_TPL->stdfoot();

	?>