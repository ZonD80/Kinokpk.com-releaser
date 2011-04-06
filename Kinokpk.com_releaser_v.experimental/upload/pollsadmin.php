<?php

/**
 * Polls admin panel
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();
get_privilege('polls_operation');
httpauth();


if (!isset($_GET['action']))  {
	$REL_TPL->stdhead("Опросы");
	print('<table width="100%" border="1"><tr><td><a href="'.$REL_SEO->make_link('pollsadmin','action','add').'">Добавить опрос</a></td><td>Опросы v.2 by ZonD80</td></tr></table>');
	print('<table width="100%" border="1"><tr><td>Опрос</td><td>Создан</td><td>Заканчивается</td><td>Ред / Уд</td></tr>');
	$pollsrow = sql_query("SELECT * FROM polls ORDER BY id DESC");
	while ($poll = mysql_fetch_array($pollsrow)) {

		print('<tr><td><a href="'.$REL_SEO->make_link('polloverview','id',$poll['id']).'">'.$poll['question'].'</a></td><td>'.mkprettytime($poll['start']).'</td><td>'.(!is_null($poll['exp'])?(($poll['exp']< time())?mkprettytime($poll['exp'])." (закрыт)":mkprettytime($poll['exp'])):"Бесконечен")."</td><td><a href=\"".$REL_SEO->make_link('pollsadmin','action','edit','id',$poll['id'])."\">E</a> / <a onClick=\"return confirm('Вы уверены?')\" href=\"".$REL_SEO->make_link('pollsadmin','action','delete','id',$poll['id'])."\">D</a></td></tr>");
	}
	print("</table>");

	$REL_TPL->stdfoot();
	// if (!is_valid_id($_GET['pollid'])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	// $pollid = $_GET["pollid"];

}
elseif ($_GET['action'] == 'add') {

	$REL_TPL->stdhead("Добавление опроса");
	print('<form name="add" action="'.$REL_SEO->make_link('pollsadmin','action','add2').'" method="post"><table width="100%" border="1">
  <tr><td>Количество вариантов ответов: <input type="text" name="howq" size="2"></td></tr><tr><td><input type="submit" value="Дальше"></td></tr></table></form>');
	$REL_TPL->stdfoot();
}
elseif ($_GET['action'] == 'add2') {
	get_privilege('polls_operation');

	if (!isset($_POST['howq'])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$howq = intval($_POST['howq']);

	$REL_TPL->stdhead("Добавление опроса Шаг 2");

	print('<table width="100%" border="1"><form name="add2" action="'.$REL_SEO->make_link('pollsadmin','action','saveadd').'" method="post">
   <tr><td>Вопрос:</td><td><input type="text" name="question"></td></tr>
   <tr><td>Продолжительность опроса:</td><td><input type="text" name="exp" size="2"> дней | 0 - бесконечно</td></tr>
   <tr><td><input type="hidden" name="type" value="'.$type.'"></td></tr>
   ');

	print('<tr><td>Публичный?</td><td><input type="checkbox" name="public" value="1"> (пользователи смогут видеть, кто и как голосовал)</td></tr>');


	for ($i=1;$i<=$howq;$i++)
	print('<tr><td>Опция '.$i.':</td><td><input type="text" name="option['.$i.']"></td></tr>');
	print('<tr><td><input type="submit" value="Создать опрос"></td></tr></table>');
	$REL_TPL->stdfoot();
}

elseif (($_GET['action'] == 'saveadd') && ($_SERVER['REQUEST_METHOD'] == 'POST')) {

	if (!is_numeric($_POST['exp'])) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	if ($_POST['exp'] != 0)
	$exp = time()+86400*intval($_POST['exp']);
	else $exp = 'NULL';
	if ($_POST['public']) $public = 1; else $public = 0;


	$question = htmlspecialchars(trim($_POST['question']));


	sql_query("INSERT INTO polls (question,start,exp,public) VALUES (".sqlesc($question).",".time().",".$exp.",'".$public."')");
	$pollid = mysql_insert_id();

	if (!$pollid) die('MySQL error');

	foreach($_POST['option'] as $key => $option) {
		$option = htmlspecialchars(trim($option));
		sql_query("INSERT INTO polls_structure (pollid,value) VALUES ($pollid,".sqlesc($option).")");

		$REL_CACHE->clearGroupCache("block-polls");
	}

	safe_redirect($REL_SEO->make_link('polloverview','id',$pollid));
}

elseif ($_GET['action'] == 'delete') {
	if (!is_valid_id($_GET['id'])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$id = $_GET['id'];

	sql_query("DELETE FROM polls WHERE id=$id");
	sql_query("DELETE FROM polls_structure WHERE pollid=$id");
	sql_query("DELETE FROM polls_votes WHERE pid=$id");
	sql_query("DELETE FROM comments WHERE toid=$id AND type='poll'") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM notifs WHERE type='pollcomments' AND checkid=$id") or sqlerr(__FILE__, __LINE__);


	$REL_CACHE->clearGroupCache("block-polls");
	safe_redirect($REL_SEO->make_link('pollsadmin'));
}

elseif ($_GET['action'] == 'edit') {
	if (!is_valid_id($_GET['id'])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$id = $_GET['id'];
	$REL_TPL->stdhead("Редактирование опроса");

	$pollrow = sql_query("SELECT id,question,exp,public FROM polls WHERE id=$id");
	$pollres = mysql_fetch_array($pollrow);

	print('<table width="100%" border="1"><form action="'.$REL_SEO->make_link('pollsadmin','action','saveedit','id',$id).'" method="post"><tr><td>Вопрос:</td><td><input type="text" name="question" value="'.$pollres['question'].'"></td><tr><td>Истекает через:</td><td><input type="text" name="exp" value="'.(!is_null($pollres['exp'])?round(($pollres['exp']-time())/86400):"0").'" size="2"> дней 0 - бесконечно</td>');

	print('<tr><td>Публичный?</td><td><input type="checkbox" name="public" value="1" '.(($pollres['public'])?"checked":"")."></td></tr>");
	$srow = sql_query("SELECT id,value FROM polls_structure WHERE pollid=$id");
	$i = 0;
	while ($sres = mysql_fetch_array($srow)) {
		$i++;
		print("<tr><td>Опция $i:</td><td><input type=\"text\" name=\"option[".$sres['id']."]\" value=\"".$sres['value']."\"></td></tr>");
	}
	print('<tr><td><input type="hidden" name="type" value="'.$pollres['type'].'"><input type="submit" value="Отредактировать"</td></tr></form></table>');
	$REL_TPL->stdfoot();
}

elseif (($_GET['action'] == 'saveedit') && ($_SERVER['REQUEST_METHOD'] == 'POST')) {


	if ((!is_numeric($_POST['exp'])) || (!is_valid_id($_GET['id']))) stderr ($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	$id = $_GET['id'];

	if ($_POST['exp'] != 0)
	$exp = time()+86400*intval($_POST['exp']);
	else $exp = 'NULL';

	if ($_POST['public']) $public = 1; else $public = 0;


	$question = htmlspecialchars(trim($_POST['question']));

	foreach($_POST['option'] as $key => $option) {
		$option = htmlspecialchars(trim($option));
		sql_query("UPDATE polls_structure SET value = ".sqlesc($option)." WHERE id=$key") or die(mysql_error());

	}
	sql_query("UPDATE polls SET question=".sqlesc($question)." , exp=$exp, public='$public' WHERE id=$id") or die(mysql_error());


	$REL_CACHE->clearGroupCache("block-polls");
	safe_redirect($REL_SEO->make_link('polloverview','id',$id));
}

else stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));