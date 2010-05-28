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
dbconn();
getlang('take-delmp');
loggedinorreturn();

if (get_user_class() < UC_SYSOP) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

httpauth();

if(is_array($_POST["delmp"])) {
	foreach ($_POST['delmp'] as $delid)
	if (!is_valid_id($delid)) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	$do = "DELETE FROM messages WHERE id IN (".implode(", ", $_POST[delmp]).")";
	$res=sql_query($do);
	safe_redirect(" spam.php");
} else {
	stdhead($tracker_lang['error']);
	print("<div class='error'><b>".$tracker_lang['not_chosen_message']."</b></div>");
	print("<center><INPUT TYPE='button' VALUE='".$tracker_lang['back']."' onClick=\"history.go(-1)\"></center>");
	stdfoot();
	die;
}
?>