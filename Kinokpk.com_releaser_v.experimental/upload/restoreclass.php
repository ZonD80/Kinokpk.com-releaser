<?php
/**
 * Admin class restore of setclass.php
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");

INIT();
loggedinorreturn();

setcookie('override_class', '', 0x7fffffff, "/");

safe_redirect($REL_SEO->make_link('index'));

?>