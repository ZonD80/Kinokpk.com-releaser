<?php
/**
 * Just describes rating system
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once('include/bittorrent.php');
INIT();
$REL_TPL->stdhead($REL_LANG->_('Rating system manual'));
$REL_TPL->output('index_'.getlang());
$REL_TPL->stdfoot();
?>