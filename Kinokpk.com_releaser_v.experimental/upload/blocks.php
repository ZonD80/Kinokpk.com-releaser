<?php
/**
 * Blocks hide/unhide processor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if (($_SERVER["REQUEST_METHOD"] == "GET") && @($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

	@$hidebid = $_COOKIE ["hidebid"];
	if (empty ( $_GET ["bid"] ))
	die ( "<b><font color=\"red\" size=\"10\">Ты куда лезешь???</font></b>" );
	$bid = "b" . intval ( str_replace ( "b", "", $_GET ["bid"] ) );
	$bidpos = strrpos ( $hidebid, "$bid." );
	if ($_GET["type"] == "hide" && ! $bidpos) {
		@setcookie ( "hidebid", "$hidebid$bid." );
	} elseif ($_GET["type"] == "show") {
		@setcookie ( "hidebid", str_replace ( "$bid.", "", $hidebid ) );
	}
}
?>