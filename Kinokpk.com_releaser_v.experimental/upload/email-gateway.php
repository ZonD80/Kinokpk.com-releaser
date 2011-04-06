<?php
/**
 * Email sender to administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();

if (!is_valid_id($_GET["id"]))
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int) $_GET["id"];

$res = sql_query("SELECT username, class, email FROM users WHERE id=$id");
$arr = mysql_fetch_assoc($res) or stderr($REL_LANG->say_by_key('error'), "Нет такого пользователя.");
$username = $arr["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$to = $arr["email"];

	$from = substr(trim($_POST["from"]), 0, 80);
	if ($from == "") $from = "Анонимно";

	$from_email = substr(trim($_POST["from_email"]), 0, 80);
	if ($from_email == "") $from_email = $REL_CONFIG['siteemail'];
	if (!strpos($from_email, "@")) stderr($REL_LANG->say_by_key('error'), "Введеный e-mail адрес не похож на верный.");

	$from = "$from <$from_email>";

	$subject = substr(trim($_POST["subject"]), 0, 80);
	if ($subject == "") $subject = "(Без темы)";

	$message = trim($_POST["message"]);
	if ($message == "") stderr($REL_LANG->say_by_key('error'), "Вы не ввели сообщение!");

	$message = "Сообщение отправлено от пользователя ".$CURUSER['username']." в " . date("Y-m-d H:i:s") . " GMT.\n" .
		"Внимание: Отвечая на это письмо, вы раскроете ваш e-mail адрес.\n" .
		"---------------------------------------------------------------------\n\n" .
	$message . "\n\n" .
		"---------------------------------------------------------------------\n{$REL_CONFIG['sitename']}\n";

	$success = sent_mail($to, $REL_CONFIG['sitename'], $REL_CONFIG['siteemail'], $subject, $message);

	if ($success)
	stderr($REL_LANG->say_by_key('success'), "E-mail успешно отправлен.");
	else
	stderr($REL_LANG->say_by_key('error'), "Письмо не может быть отправлено. Пожалуйтса, попробуйте позже.");
}

$REL_TPL->stdhead("Отправить e-mail");
?>
<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<td class=colhead colspan=2>Отправить e-mail пользователю <?=$username;?></td>
	</tr>
	<form method=post
		action="<?=$REL_SEO->make_link('email-gateway','id',$id)?>">
	<tr>
		<td class=rowhead>Ваше имя</td>
		<td><input type=text name=from size=80 value=<?=$CURUSER["username"]?>
			disabled></td>
	</tr>
	<tr>
		<td class=rowhead>Ваш e-mail</td>
		<td><input type=text name=from_email size=80
			value=<?=$CURUSER["email"]?> disabled></td>
	</tr>
	<tr>
		<td class=rowhead>Тема</td>
		<td><input type=text name=subject size=80></td>
	</tr>
	<tr>
		<td class=rowhead>Сообщение</td>
		<td><textarea name=message cols=80 rows=20></textarea></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value="Отправить"
			class=btn></td>
	</tr>
	</form>
</table>
<p><font class=small><b>Внимание:</b> Ваш IP-адрес будет записан и будет
виден получателю, для предотвращния обмана.<br />
Убедитесь что вы ввели правильны e-mail адрес если вы ожидаете ответа.</font>
</p>
<? $REL_TPL->stdfoot(); ?>