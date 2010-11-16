<?php
/**
 * Index
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");
//die('fuck');
dbconn();
$REL_TPL->stdhead($REL_LANG->say_by_key('homepage'));

//print("<table width=\"100%\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"embedded\">");
/*if (get_user_class()==UC_SYSOP) {
 print '<pre>';
 print_r($CURUSER);
 YEAH BABY IT'S COOL!
 }*/
$REL_TPL->stdfoot();
?>
