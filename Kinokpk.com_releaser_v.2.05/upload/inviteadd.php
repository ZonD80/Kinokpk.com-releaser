<?

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

if (get_user_class() < UC_SYSOP)

stderr("Error", "Access denied.");

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")

{

if ($HTTP_POST_VARS["username"] == "" || $HTTP_POST_VARS["invites"] == "" || $HTTP_POST_VARS["invites"] == "")

stderr("Error", "Missing form data.");

$username = sqlesc($HTTP_POST_VARS["username"]);

$invites = sqlesc($HTTP_POST_VARS["invites"]);


sql_query("UPDATE users SET invites=$invites WHERE username=$username") or sqlerr(__FILE__, __LINE__);

$res = sql_query("SELECT id FROM users WHERE username=$username");

$arr = mysql_fetch_row($res);

if (!$arr)

stderr("Error", "Unable to update account.");

header("Location: $DEFAULTBASEURL/userdetails.php?id=$arr[0]");

die;

}

stdhead("Update Users Invite Amounts");

?>

<h1>Update Users Invite Amounts</h1>

<form method=post action=inviteadd.php>

<table border=1 cellspacing=0 cellpadding=5>

<tr><td class=rowhead>User name</td><td><input type=text name=username size=40></td></tr>

<tr><td class=rowhead>Invites</td><td><input type=uploaded name=invites size=5></td></tr>

<tr><td colspan=2 align=center><input type=submit value="Okay" class=btn></td></tr>

</table>

</form>

<? stdfoot(); ?>