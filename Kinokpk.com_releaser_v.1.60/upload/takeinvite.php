<?

require_once("include/bittorrent.php");

dbconn();
loggedinorreturn();

function bark($msg) {
	stdhead();
	stdmsg("Ошибка", $msg);
	stdfoot();
	die;
}

$id = 0 + $_GET["id"];

if ($id == 0) {
	$id = $CURUSER["id"];
}

if (get_user_class() <= UC_MODERATOR)
	$id = $CURUSER["id"];

$re = sql_query("SELECT invites FROM users WHERE id = $id") or sqlerr(__FILE__,__LINE__);
$tes = mysql_fetch_assoc($re);

if ($tes[invites] <= 0)
	bark("У вас больше не осталось приглашений!");

$hash  = md5(mt_rand(1, 1000000));

sql_query("INSERT INTO invites (inviter, invite, time_invited) VALUES (" . implode(", ", array_map("sqlesc", array($id, $hash, get_date_time()))) . ")") or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE users SET invites = invites - 1 WHERE id = $id") or sqlerr(__FILE__, __LINE__);

header("Refresh: 0; url=invite.php?id=$id");

?>