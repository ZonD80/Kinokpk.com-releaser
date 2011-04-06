<?php
/**
 * Delete users messages for administrator
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

if(is_array($_POST["delmp"])) {
	foreach ($_POST['delmp'] as $delid)
	if (!is_valid_id($delid)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	$do = "DELETE FROM messages WHERE id IN (".implode(", ", $_POST[delmp]).")";
	$res=sql_query($do);
	safe_redirect($REL_SEO->make_link('spam'));
} else {
	$REL_TPL->stdhead($REL_LANG->say_by_key('error'));
	print("<div class='error'><b>".$REL_LANG->say_by_key('not_chosen_message')."</b></div>");
	print("<center><INPUT TYPE='button' VALUE='".$REL_LANG->say_by_key('back')."' onClick=\"history.go(-1)\"></center>");
	$REL_TPL->stdfoot();
	die;
}
?>