<?php

$op = (!isset($_REQUEST['op'])) ? "Main" : $_REQUEST['op'];

/*if (get_magic_quotes_gpc()) {
	if (!empty($_GET))    { $_GET    = strip_magic_quotes($_GET);    }
	if (!empty($_POST))   { $_POST   = strip_magic_quotes($_POST);   }
	if (!empty($_COOKIE)) { $_COOKIE = strip_magic_quotes($_COOKIE); }
}*/

foreach ($_GET as $key => $value)
	$GLOBALS[$key] = $value;
foreach ($_POST as $key => $value)
	$GLOBALS[$key] = $value;
foreach ($_COOKIE as $key => $value)
	$GLOBALS[$key] = $value;

require_once($rootpath . 'admin/functions.php');

?>