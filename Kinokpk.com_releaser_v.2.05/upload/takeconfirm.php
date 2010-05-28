<?

require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

    	  if (!is_valid_id($_GET["id"]))
			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$id = 0 + $_GET["id"];
if (isset($_POST["conusr"]))
	sql_query("UPDATE users SET status = 'confirmed' WHERE id IN (" . implode(", ", array_map("sqlesc", $_POST["conusr"])) . ") AND status = 'pending'".( get_user_class() < UC_SYSOP ? " AND invitedby = $CURUSER[id]" : "")) or sqlerr(__FILE__,__LINE__);
else
	header("Location: invite.php?id=$id");

header("Refresh: 0; url=invite.php?id=$id");

?>