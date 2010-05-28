<?

require_once("include/bittorrent.php");

dbconn();
getlang('takeinvite');
loggedinorreturn();

function bark($msg) {
	stdhead();
	stdmsg($tracker_lang['error'], $msg);
	stdfoot();
	die;
}

if (!is_numeric($_GET["id"]) || !isset($_GET["id"]))
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_GET["id"];

if ($id == 0) {
	$id = $CURUSER["id"];
}

if (get_user_class() <= UC_MODERATOR)
$id = $CURUSER["id"];

$re = sql_query("SELECT invites FROM users WHERE id = $id") or sqlerr(__FILE__,__LINE__);
$tes = mysql_fetch_assoc($re);

if ($tes[invites] <= 0)
bark($tracker_lang['dont_invite']);

$hash  = md5(mt_rand(1, 1000000));

sql_query("INSERT INTO invites (inviter, invite, time_invited) VALUES (" . implode(", ", array_map("sqlesc", array($id, $hash, time()))) . ")") or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE users SET invites = invites - 1 WHERE id = $id") or sqlerr(__FILE__, __LINE__);

header("Refresh: 0; url=invite.php?id=$id");

?>