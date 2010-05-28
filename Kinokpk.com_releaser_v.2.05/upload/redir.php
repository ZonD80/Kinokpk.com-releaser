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

if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");

include ('include/bittorrent.php');

dbconn(false);

if(!isset($CURUSER)) die();

  $url = '';
  while (list($var,$val) = each($_GET))
    $url .= "&$var=$val";
$i = strpos($url, "&url=");
if ($i !== false)
    $url = substr($url, $i + 5);
if (substr($url, 0, 4) == "www.")
    $url = "http://" . $url;
  print("<html><head><meta http-equiv=refresh content='1;url=".urlencode($url)."'></head><body>\n");
  print("<table border=0 width=100% height=100%><tr><td><h2 align=center>Перенаправлене...подождите пожалуйста<br />\n");
  print(htmlspecialchars($url)."</h2></td></tr></table></body></html>\n");
?>