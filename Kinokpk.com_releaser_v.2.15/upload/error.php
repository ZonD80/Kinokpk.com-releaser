<?php
// CUSTOM ERROR MESSAGES
require_once("include/bittorrent.php");
$errors = array(
400 => '<h1>400 - Bad Request</h><br/>Сервер не понял запрос',
401 => '<h1>401 - Unauthorized</h1><br/>Пользователь неизвестен',
403 => '<h1>403 - Forbidden</h1><br/>Доступ запрещен',
404 => '<h1>404 - Not Found</h1><br/>Ссылка неверна',
);
function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}
$error = intval($_GET['id']);

if (isset($errors["$error"])) bark ($errors["$error"]); else bark("Незивестная ошибка HTTP");