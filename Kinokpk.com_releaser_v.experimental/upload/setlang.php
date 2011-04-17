<?php
/**
 * Just sets user language
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once('include/bittorrent.php');
INIT(true);
$lang = trim((string)$_GET['l']);
$lang = substr($lang,0,2);
setcookie("lang", $lang, 0x7fffffff);
if (isset($_GET['returnto']))
safe_redirect(strip_tags($_GET["returnto"]));
else
safe_redirect($REL_SEO->make_link('index'));

?>