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

require_once ("include/bittorrent.php");
dbconn ();
getlang ( 'report' );
loggedinorreturn ();

$id = ( int ) $_GET ['id'];
if (! is_valid_id ( $id ))
stderr ( $REL_LANG->say_by_key("error"), $REL_LANG->say_by_key("invalid_id") );

$type = trim ( ( string ) $_GET ['type'] );

$allowed_types = array ('messages', 'torrents', 'users', 'comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'relgroups', 'rgcomments', 'pages', 'pagecomments' );

if (! in_array ( $type, $allowed_types ))
stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_type') );

$motive = $_POST ["motive"] ? $_POST ["motive"] : $_POST ["smotive"];

$motive = htmlspecialchars ( trim ( $motive ) );

$reportform = '<form action="'.$REL_SEO->make_link('report','id',$id,'type',$type).'" method="post"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('spam') . '"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('abuse') . '"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('badwords') . '"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('hack') . '">' . $REL_LANG->say_by_key('own_reason') . '<input type="text" size="100" name="smotive"><input type="submit" value="' . $REL_LANG->say_by_key('go') . '"></form>';
//var_dump($_POST);
if (! $motive)
stderr ( $REL_LANG->say_by_key('reason'), $reportform, 'success' );

$check = @mysql_result ( sql_query ( "SELECT 1 FROM $type WHERE id=$id" ), 0 );

if (! $check)
stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );

$reason = sqlesc ( $motive );

sql_query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ($id,{$CURUSER['id']},'$type',$reason," . time () . ")" );

if (mysql_errno () == 1062)
stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('already_report') );

stderr ( $REL_LANG->say_by_key('success'), $REL_LANG->say_by_key('report_ok'), 'success' );
?>