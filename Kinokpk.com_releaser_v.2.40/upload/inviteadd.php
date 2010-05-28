<?

require "include/bittorrent.php";

dbconn();
getlang('inviteadd');
loggedinorreturn();

if (get_user_class() < UC_SYSOP)

stderr($tracker_lang['error'], $tracker_lang['access_denied']);

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")

{

	if ($HTTP_POST_VARS["username"] == "" || $HTTP_POST_VARS["invites"] == "" || $HTTP_POST_VARS["invites"] == "")

	stderr($tracker_lang['error'], $tracker_lang['missing_data']);

	$username = sqlesc($HTTP_POST_VARS["username"]);

	$invites = sqlesc($HTTP_POST_VARS["invites"]);


	sql_query("UPDATE users SET invites=$invites WHERE username=$username") or sqlerr(__FILE__, __LINE__);

	$res = sql_query("SELECT id FROM users WHERE username=$username");

	$arr = mysql_fetch_row($res);

	if (!$arr)

	stderr($tracker_lang['error'], $tracker_lang['un_upd_acc']);

	header("Location: userdetails.php?id=$arr[0]");

	die;

}

stdhead($tracker_lang['upd_users_inv_amn']);

?>

<h1><?=$tracker_lang['upd_users_inv_amn']?></h1>

<form method=post action=inviteadd.php>

<table border=1 cellspacing=0 cellpadding=5>

	<tr>
		<td class=rowhead><?=$tracker_lang['user_name']?></td>
		<td><input type=text name=username size=40></td>
	</tr>

	<tr>
		<td class=rowhead><?=$tracker_lang['invites']?></td>
		<td><input type=uploaded name=invites size=5></td>
	</tr>

	<tr>
		<td colspan=2 align=center><input type=submit value="Okay" class=btn></td>
	</tr>

</table>

</form>

<? stdfoot(); ?>