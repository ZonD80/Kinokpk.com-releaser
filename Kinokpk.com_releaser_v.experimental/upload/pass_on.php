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
INIT();

$cat = sqlwildcardesc((string)$_GET['cat']);
if (!is_valid_id($_GET["from"])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
$from = (int)$_GET["from"];

if(!$from || $_GET["to"] <> "next" && $_GET["to"] <> "pre")
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('how_did_get_here'));


$pass = ($_GET["to"] == "next" ? "> " .$from. " ".($cat ? "AND category LIKE '%" .$cat."%'" : "")." ORDER BY id ASC" : "< " .$from. " ".($cat ? "AND category LIKE '%" .$cat."%'" : "")." ORDER BY id DESC");
$err = ($_GET["to"] == "next" ? "".$REL_LANG->say_by_key('have_been_last_release')."" .($cat ? " ".$REL_LANG->say_by_key('this_category')."" : "")."".$REL_LANG->say_by_key('back')."" : "".$REL_LANG->say_by_key('have_first_release')."" .($cat ? " ".$REL_LANG->say_by_key('this_category')."" : "")."".$REL_LANG->say_by_key('back')."");


$to = sql_query("SELECT id FROM torrents WHERE id " .$pass. " LIMIT 1");
$to = @mysql_fetch_assoc($to);

$to = $to["id"];

if(!$to)
stderr($REL_LANG->say_by_key('error'), $err);

safe_redirect($REL_SEO->make_link('details','id',$to));

?>