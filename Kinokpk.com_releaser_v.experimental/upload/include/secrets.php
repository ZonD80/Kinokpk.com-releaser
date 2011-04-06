<?php

/**
* Passwords. Just for fun
* @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
* @package Kinokpk.com releaser
* @author ZonD80 <admin@kinokpk.com>
* @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
* @link http://dev.kinokpk.com
*/


if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE')) die("Direct access to this page not allowed");

$db['host'] = 'localhost';
$db['user'] = '';
$db['pass'] = '';
$db['db'] = '';
$db['charset'] = 'utf8';

define("COOKIE_SECRET",'');

/**
 * Set cache driver, available "native" and "memcached" now
 * @var string
 */
define("REL_CACHEDRIVER",'native');
?>
