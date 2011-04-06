<?php
/**
 * Logouts user
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();

logoutcookie();

unset($CURUSER);

$REL_TPL->stdhead($REL_LANG->say_by_key('succ_logout'));
stdmsg($REL_LANG->say_by_key('you_succ_logout'),"<a href=\"".$REL_CONFIG['defaultbaseurl']."\">".$REL_LANG->say_by_key('continue')."</a>");
$REL_TPL->stdfoot();

?>