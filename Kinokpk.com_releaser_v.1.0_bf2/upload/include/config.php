<?

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
if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE'))
  die('Hacking attempt!');

$SITE_ONLINE = true;  // is site online (simple offline message)

$max_torrent_size = 1000000;
$announce_interval = 60 * 15;
$signup_timeout = 86400 * 3;
$minvotes = 1;
$max_dead_torrent_time = 744 * 3600;

// Max users on site
$maxusers = 10000;

// ONLY USE ONE OF THE FOLLOWING DEPENDING ON YOUR O/S!!!
$torrent_dir = "torrents";    # FOR UNIX ONLY - must be writable for httpd user (777)
//$torrent_dir = "C:/web/Apache2/htdocs/tbsource/torrents";    # FOR WINDOWS ONLY - must be writable for httpd user (777)

$doxpath = "dox"; // id do not know what is it yet

$DEFAULTBASEURL = "kinokpk";

// Email for sender/return path.
$SITEEMAIL = "bot@" . $_SERVER["HTTP_HOST"];
// Admin email
$ADMINEMAIL = "localhost@localhost";

$SITENAME = "Kinokpk.com releaser - Ultimate filesharing! http://www.kinokpk.com - http://dev.kinokpk.com"; // Your site title

$DESCRIPTION = "Kinokpk.com releaser - Ultimate filesharing! http://www.kinokpk.com - http://dev.kinokpk.com"; // Meta description
$KEYWORDS = "kinokpk, kinokpk.com, releaser, релизер, кинокпк, dev.kinokpk.com"; // Meta keywords, separate by commas

$autoclean_interval = 900; // autoclean interval (cleanup.php)
$pic_base_url = "./pic/"; // Image folder

define("YOURCOPY",""); // Your custom copyright (will be in footer)

// Custom config
$pm_delete_sys_days = 5; // Days system message to dead
$pm_delete_user_days = 30; // Days user message to dead
$ttl_days = 28; // TTL (autodelete releases)
$default_language = "russian"; // Default language
$avatar_max_width = 120; // Maximal avatar witdh
$avatar_max_height = 120; // Maximal avatar heigth
$ctracker = 1; // Use CrackerTracker - anti-cracking system
$points_per_hour = 5; // Bonus per hour
$points_per_cleanup = $points_per_hour*($autoclean_interval/3600); // Don't change it!
$default_theme = "kinokpk"; // Guests theme
$nc = "no"; // Disallow closed ports users
$deny_signup = 0; // Deny signup
$allow_invite_signup = 1; // Allow invites
$use_ttl = 0; // Allow TTL
$use_email_act = 0; // Allow email activation
$use_wait = 0; // Allow torrent waiting
$use_lang = 1; // Allow multilanguage system
$use_captcha = 1; // Allow captcha
$use_blocks = 1; // Allow blocks
$use_gzip = 1; // Allow gzip
$use_ipbans = 1; // Allow ip-bans
$use_sessions = 1; // Allow sessions
$smtptype = "advanced"; // SMTP type

// Integrated forum config
$fprefix = "ib_"; // Prefix of forum tables
$FORUMURL = "forum"; // Fourm URL (e.g. http://www.forum.ru, WITHOUT / )
$FORUMNAME = "forum_name"; // Name of your forum

// Full path to www folder, WITHOUT /
define("FULL_PATH", "/home/virtual/kinokpk");

define ("BETA", 0); // Allow "beta" message

define ("DEBUG_MODE", 0); // Debug mode: shows the queries at the bottom of the page.

?>