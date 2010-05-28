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

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

$filename = substr($HTTP_SERVER_VARS["PATH_INFO"], strrpos($HTTP_SERVER_VARS["PATH_INFO"], "/") + 1);
if (!$filename)
die($tracker_lang['getdox_no_file']."\n");
$filename = sqlesc($filename);
$res = sql_query("SELECT * FROM dox WHERE filename=$filename") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_assoc($res);
if (!$arr)
 die($tracker_lang['getdox_file_not_found']."\n");
sql_query("UPDATE dox SET hits=hits+1 WHERE id=$arr[id]") or sqlerr(__FILE__, __LINE__);
$file = "$doxpath/$arr[filename]";
header("Content-Length: " . filesize($file));
header("Content-Type: application/octet-stream");
readfile($file);
?>