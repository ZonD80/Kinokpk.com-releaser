<?php

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

# IMPORTANT: Do not edit below unless you know what you are doing!

// DEFINE IMPORTANT CONSTANTS
define('IN_TRACKER', true);

// SET PHP ENVIRONMENT
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '0');
@ini_set('ignore_repeated_errors', '1');
@session_start();
define ('ROOT_PATH', str_replace("include","",dirname(__FILE__)));
date_default_timezone_set('UTC');

require_once(ROOT_PATH . 'include/classes.php');
require_once(ROOT_PATH . 'include/functions.php');
// Variables for Start Time
$tstart = microtime(); // Start time

require_once(ROOT_PATH . 'include/secrets.php');
require_once(ROOT_PATH . 'classes/cache/cache.class.php');
require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
$CACHE=new Cache();
$CACHE->addDriver(NULL, new FileCacheDriver());
// TinyMCE sequrity
require_once(ROOT_PATH . 'include/htmLawed.php');
// Ban system
require_once(ROOT_PATH.'classes/bans/ipcheck.class.php');

require_once(ROOT_PATH . 'include/blocks.php');

// IPB Integration functions
require_once(ROOT_PATH . 'include/functions_integration.php');

// INCLUDE SECURITY BACK-END
require_once(ROOT_PATH . 'include/ctracker.php');
?>