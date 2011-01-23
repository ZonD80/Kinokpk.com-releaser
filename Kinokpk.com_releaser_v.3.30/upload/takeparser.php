<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

function getrealcat( $cat )
{
	/*if ( $cat == 1 )
	 {
		return "11";
		}
		if ( $cat == 2 )
		{
		return "3";
		}

		if ( $cat == 3 )
		{
		return "6";
		}
		if ( $cat == 4 )
		{
		return "18";
		}
		if ( $cat == 6 )
		{
		return "2";
		}
		if ( $cat == 7 )
		{
		return "8";
		}
		if ( $cat == 8 )
		{
		return "4";
		}
		if ( $cat == 9 )
		{
		return "5";
		}
		if ( $cat == 10 )
		{
		return "11";
		}
		if ( $cat == 11 )
		{
		return "5";
		}
		*/


	if ( $cat == "kino" || $cat==1)
	{
		return 24;
	}
	if ( $cat == "audio" || $cat==2 )
	{
		return 38;
	}
	if ( $cat == "other" || $cat==3 )
	{
		return 106;
	}
	if ( $cat == "seriali" || $cat==4 )
	{
		return 116;
	}
	if ( $cat == "tv" || $cat==6 )
	{
		return 139;
	}
	if ( $cat == "multiki" || $cat==7 )
	{
		return 26;
	}
	if ( $cat == "games" || $cat==8 )
	{
		return 85;
	}
	if ( $cat == "soft" || $cat==9 )
	{
		return 69;
	}
	if ( $cat == "anime" || $cat==10 )
	{
		return 136;
	}
	if ( $cat == "knigi" || $cat==11 )
	{
		return 103;
	}
}
/**
 * Constant to deny direct access to inclusion scripts
 * @var boolean
 */
define('IN_TRACKER', true);

// SET PHP ENVIRONMENT
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '0');
@ini_set('ignore_repeated_errors', '1');
//@session_start();
date_default_timezone_set('UTC');
/**
 * Full path to releaser sources
 * @var string
 */
define ('ROOT_PATH', dirname(__FILE__).'/');

require_once(ROOT_PATH . 'include/classes.php');
require_once(ROOT_PATH . 'include/functions.php');

// Variables for Start Time
/**
 * Script start time for debug
 * @var float
 */
$tstart = microtime(true); // Start time

require_once(ROOT_PATH . 'include/secrets.php');
/* @var object general cache object */
require_once(ROOT_PATH . 'classes/cache/cache.class.php');
$REL_CACHE=new Cache();
if (REL_CACHEDRIVER=='native') {
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	$REL_CACHE->addDriver(NULL, new FileCacheDriver());
}
elseif (REL_CACHEDRIVER=='memcached') {
	require_once(ROOT_PATH .  'classes/cache/MemCacheDriver.class.php');
	$REL_CACHE->addDriver(NULL, new MemCacheDriver());
}
// TinyMCE security
require_once(ROOT_PATH . 'include/htmLawed.php');
// Ban system
require_once(ROOT_PATH.'classes/bans/ipcheck.class.php');

require_once(ROOT_PATH . 'include/blocks.php');

// IN AJAX MODE?

ajaxcheck();

require_once("include/benc.php");
require_once( "parsers/functions.php" );
dbconn( );
loggedinorreturn( );
if (get_user_class()<UC_SYSOP) stderr($REL_LANG->_("Error"),$REL_LANG->_("Access denied"));
//$cookie="
$owner = 0; //aka system
$id = (int)$_GET['id'];
	$cat = (int)$_GET['cat'];
	
if (!$id) {
	$page = (int)$_GET['page'];
	$torrent = file_get_contents( "http://rutor.org/browse/".$page."/".$cat."/0/0" );
	$x = preg_match_all( "/<a href=\"\\/torrent\\/(.*)\\/.*\">.*<\\/a>/", $torrent, $matches16 );
	$all = $matches16[1];
	if ($all)
	foreach ($all as $t) {
		print "ID: ".(int)$t.",  RESULT: <img src=\"takeparser.php?id=$t&cat=$cat\"><br/>";
	} else die('no torrents');
} else {
	header("Content-type: image/png");
$im = @imagecreate(700, 20)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 233, 14, 91);
$text = uploadtorrent($id,getrealcat($cat));
imagestring($im, 1, 5, 5,  $text, $text_color);
imagepng($im);
imagedestroy($im);
	
}
?>
