<?
require_once("include/bittorrent.php");
$id = 0 + $id;
if (!$id)
die();
dbconn();

loggedinorreturn();

$userid = $CURUSER["id"];
$torrentid = 0+$_POST["torrentid"];

if (!is_valid_id($torrentid))
  bark("Неправильный ID");

$motive = unesc ($_POST["motive"]);
$reason = sqlesc("".$_POST["motive"]."");
$subject = sqlesc("Подана жалоба");
$now = sqlesc(get_date_time());
$msg = sqlesc("Пользователем [b][url=".$DEFAULTBASEURL."/details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url][/b] была подана жалоба на торрент [url]".$DEFAULTBASEURL."/details.php?id=".$id."[/url]\n\nПричина: ".$motive."");
if (isset($userid) && isset($torrentid))
{
    $owntorrentquery = mysql_query("SELECT NULL FROM torrents WHERE id = '$torrentid' and owner = '$userid'") or die(mysql_error());

    $owntorrentrow = mysql_fetch_object($owntorrentquery);

    if($owntorrentrow)
    {
        header("Location: $BASEURL/details.php?id=$torrentid&ownreport=1");
                die();
    }

    $alreadythankquery = mysql_query("SELECT NULL FROM report WHERE torrentid = '$torrentid' and userid = '$userid'") or die(mysql_error());
    $alreadythankrow = mysql_fetch_object($alreadythankquery);

    if (!$alreadythankrow)
    {
        mysql_query("INSERT INTO report (torrentid, userid, motive, added) VALUES ($torrentid, $userid, $reason, NOW())") or sqlerr(__FILE__,__LINE__);
        mysql_query("INSERT INTO messages (sender, receiver, added, msg, subject, poster) SELECT 0, id, $now, $msg, $subject, 0 FROM users WHERE class > ".UC_MODERATOR."") or sqlerr(__FILE__,__LINE__);
        header("Location: $DEFAULTBASEURL/details.php?id=$torrentid&report=1");
                die();
    }
    else
    {
        header("Location: $BASEURL/details.php?id=$torrentid&alreadyreport=1");
                die();
    }
}
?>