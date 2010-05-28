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

require_once("include/bittorrent.php");
dbconn();

loggedinorreturn();
parked();
if ($_SERVER['REQUEST_METHOD'] != 'POST') die("Direct access to this file not allowed.");

$userid = $CURUSER["id"];
$torrentid = (int) $_POST["torrentid"];

if (empty($torrentid)) {
	stdmsg($tracker_lang["error"], "Не пытайся меня взломать!");
}

$ajax = $_POST["ajax"];
function do_ipb_thanks()
{
         global $torrentid,$userid,$fprefix,$fmysql_db,$fmysql_host,$fmysql_pass,$fmysql_user,$fmysql_db,$mysql_db,$mysql_host,$mysql_pass,$mysql_user;
  	// IPB THANKS INTEGRATION
$topicid = mysql_query("SELECT topic_id FROM torrents WHERE id = ".$torrentid) or die(mysql_error());
$topicid = mysql_result($topicid,0);

if ($topicid != 0) {

$ipbuser = mysql_query("SELECT username FROM users WHERE id=".$userid) or die(mysql_error());
$ipbuser = mysql_result($ipbuser,0);

mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
	mysql_query("SET NAMES $mysql_charset");

$check = mysql_query("SELECT id FROM ".$fprefix."members WHERE name='".$ipbuser."'") or die(mysql_error());

if(!@mysql_result($check,0)) $ipbid = 0; else $ipbid=mysql_result($check,0);

if ($ipbid != 0)
{
  $postid = mysql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid=".$topicid) or die(mysql_error());
  $postid = mysql_result($postid,0);
  $postthanks = mysql_query("SELECT post_thanks FROM ".$fprefix."posts WHERE pid=".$postid) or die(mysql_error());
  $postthanks = mysql_result($postthanks,0);

if (strpos($postthanks,strval($ipbid)) === false) {
  if (is_null($postthanks))
     mysql_query("UPDATE ".$fprefix."posts SET post_thanks = '".strval($ipbid)."' WHERE pid=".$postid) or die(mysql_error());
  else
      mysql_query("UPDATE ".$fprefix."posts SET post_thanks = '".$postthanks.",".strval($ipbid)."' WHERE pid=".$postid) or die(mysql_error());
}
}


 // closing IPB DB connection
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
	mysql_query("SET NAMES $mysql_charset");
}
//////////////////////////////////////////////////////////
}

if ($ajax == "yes") {
	@mysql_query("INSERT INTO thanks (torrentid, userid) VALUES ($torrentid, $userid)");
  do_ipb_thanks();
  
  $count_sql = sql_query("SELECT COUNT(*) FROM thanks WHERE torrentid = $torrentid");
	$count_row = mysql_fetch_array($count_sql);
	$count = $count_row[0];

	if ($count == 0) {
		$thanksby = $tracker_lang['none_yet'];
	} else {
		$thanked_sql = sql_query("SELECT thanks.userid, users.username, users.class FROM thanks INNER JOIN users ON thanks.userid = users.id WHERE thanks.torrentid = $torrentid");
		while ($thanked_row = mysql_fetch_assoc($thanked_sql)) {
			if (($thanked_row["userid"] == $CURUSER["id"]) || ($thanked_row["userid"] == $row["owner"]))
			$can_not_thanks = true;
			//list($userid, $username) = $thanked_row;
			$userid = $thanked_row["userid"];
			$username = $thanked_row["username"];
			$class = $thanked_row["class"];
			$thanksby .= "<a href=\"userdetails.php?id=$userid\">".get_user_class_color($class, $username)."</a>, ";
		}
		if ($thanksby)
			$thanksby = substr($thanksby, 0, -2);
	}
	$thanksby = "<div id=\"ajax\"><form action=\"thanks.php\" method=\"post\">
	<input type=\"submit\" name=\"submit\" onclick=\"send(); return false;\" value=\"".$tracker_lang['thanks']."\"".($can_not_thanks ? " disabled" : "").">
	<input type=\"hidden\" name=\"torrentid\" value=\"$torrentid\">".$thanksby."
	</form></div>";

	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
	print $thanksby;
} else {
	$res = @mysql_query("INSERT INTO thanks (torrentid, userid) VALUES ($torrentid, $userid)");
  do_ipb_thanks();
  header("Location: $DEFAULTBASEURL/details.php?id=$torrentid&thanks=1");
}
?>