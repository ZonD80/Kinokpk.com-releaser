<?php
/**
 * Release details
* @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
* @package Kinokpk.com releaser
* @author ZonD80 <admin@kinokpk.com>
* @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
* @link http://dev.kinokpk.com
*/

require_once ("include/bittorrent.php");

INIT();

loggedinorreturn();

$REL_TPL->stdhead($REL_LANG->_('Chat'));
print '<iframe src="'.$REL_SEO->make_link('chat_backend').'" width="100%" height="700px" frameborder="0">Browser not compatible. </iframe>';
$REL_TPL->stdfoot();

?>