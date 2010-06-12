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

require "include/bittorrent.php";
dbconn();
getlang('pass_on');
$cat = sqlwildcardesc((string)$_GET['cat']);
if (!is_valid_id($_GET["from"])) stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$from = (int)$_GET["from"];

if(!$from || $_GET["to"] <> "next" && $_GET["to"] <> "pre")
stderr($tracker_lang['error'],$tracker_lang['how_did_get_here']);


$pass = ($_GET["to"] == "next" ? "> " .$from. " ".($cat ? "AND category LIKE '%" .$cat."%'" : "")." ORDER BY id ASC" : "< " .$from. " ".($cat ? "AND category LIKE '%" .$cat."%'" : "")." ORDER BY id DESC");
$err = ($_GET["to"] == "next" ? "".$tracker_lang['have_been_last_release']."" .($cat ? " ".$tracker_lang['this_category']."" : "")."".$tracker_lang['back']."" : "".$tracker_lang['have_first_release']."" .($cat ? " ".$tracker_lang['this_category']."" : "")."".$tracker_lang['back']."");


$to = sql_query("SELECT id FROM torrents WHERE id " .$pass. " LIMIT 1");
$to = @mysql_fetch_assoc($to);

$to = $to["id"];

if(!$to)
stderr($tracker_lang['error'], $err);

safe_redirect(" details.php?id=" .$to);

?>