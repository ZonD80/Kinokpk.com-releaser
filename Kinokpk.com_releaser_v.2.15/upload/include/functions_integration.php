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

function forumconn($close = TRUE){
  global $fmysql_host, $fmysql_user, $fmysql_pass, $fmysql_db, $fmysql_charset;

  if ($close) {
  @mysql_close();
  }
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
	mysql_set_charset($fmysql_charset);
//connection opened
}

function relconn($close = TRUE){
  global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset;
 // closing IPB DB connection
if ($close){
@mysql_close();
}
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
	mysql_set_charset($mysql_charset);
}

function generate_password_salt($len=5)
    {
        $salt = '';

        for ( $i = 0; $i < $len; $i++ )
        {
            $num   = rand(33, 126);

            if ( $num == '92' )
            {
                $num = 93;
            }

            $salt .= chr( $num );
        }

        return $salt;
}

function generate_compiled_passhash($sol, $wantpassword)
    {
        return md5( md5( $sol ) . $wantpassword );
    }

        function generate_auto_log_in_key($len=60)
    {
        $pass = generate_password_salt( 60 );

        return md5($pass);
}

function do_ipb_thanks()
{
         global $torrentid,$userid,$CACHEARRAY,$fmysql_db,$fmysql_host,$fmysql_pass,$fmysql_user,$fmysql_db,$fprefix,$mysql_db,$mysql_host,$mysql_pass,$mysql_user;
  	// IPB THANKS INTEGRATION
$topicid = sql_query("SELECT topic_id FROM torrents WHERE id = ".$torrentid) or die(mysql_error());
$topicid = mysql_result($topicid,0);

if ($topicid != 0) {

$ipbuser = sql_query("SELECT username FROM users WHERE id=".$userid) or die(mysql_error());
$ipbuser = mysql_result($ipbuser,0);


// connecting to IPB DB
forumconn();

$check = sql_query("SELECT id FROM ".$fprefix."members WHERE name='".$ipbuser."'") or die(mysql_error());

if(!@mysql_result($check,0)) $ipbid = 0; else $ipbid=mysql_result($check,0);

if ($ipbid != 0)
{
  $postid = sql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid=".$topicid) or die(mysql_error());
  $postid = mysql_result($postid,0);
  $postthanks = sql_query("SELECT post_thanks FROM ".$fprefix."posts WHERE pid=".$postid) or die(mysql_error());
  $postthanks = mysql_result($postthanks,0);

if (strpos($postthanks,strval($ipbid)) === false) {
  if (is_null($postthanks))
     sql_query("UPDATE ".$fprefix."posts SET post_thanks = '".strval($ipbid)."' WHERE pid=".$postid) or die(mysql_error());
  else
      sql_query("UPDATE ".$fprefix."posts SET post_thanks = '".$postthanks.",".strval($ipbid)."' WHERE pid=".$postid) or die(mysql_error());
}
}


 // closing IPB DB connection
relconn();
 // connection closed
}
//////////////////////////////////////////////////////////
}

?>