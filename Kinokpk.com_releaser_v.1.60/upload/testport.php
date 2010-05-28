<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("Проверка порта");
if ($CURUSER) {
	$ip = $CURUSER['ip'];

	if ($_SERVER["REQUEST_METHOD"] == "POST")
		$port = $_POST["port"];
	else
		$port = $_GET['port'];
	if ($port) {
		$fp = @fsockopen ($ip, $port, $errno, $errstr, 10);
		if (!$fp) {
			print ("<table width=40% class=main cellspacing=1 cellpadding=5><br><tr>".
			"<td class=colhead align=center><b>Port test</b></td></tr><tr><td class=tableb><font color=darkred><br><center><b>IP: $ip is on the Port: $port not good !</b></center><br></font></td></tr><tr><td class=tableb><center><form><INPUT TYPE=\"BUTTON\" VALUE=\"New Port test\" ONCLICK=\"window.location.href='/testport.php'\"></form></center></td></tr></table");
		} else {
			print ("<table width=40% class=main cellspacing=1 cellpadding=5><br><tr>".
			"<td class=colhead align=center><b>Port test</b></td></tr><tr><td class=tableb><font color=darkgreen><br><center><b>IP: $ip is on the Port: $port good !</b></center><br></font></td></tr><tr><td class=tableb><center><form><INPUT TYPE=\"BUTTON\" VALUE=\"New Port test\" ONCLICK=\"window.location.href='/testport.php'\"></form></center></td></tr></table>");
		}
	}

	else
	{
	print("<table width=40% class=main cellspacing=1 cellpadding=5><br><tr>".
	"<td class=colhead align=center colspan=2><b>Port test</b></td>".
	"</tr>");
	print ("<form method=post action=testport.php>");
	print ("<tr><td class=tableb><center>Port number:<center></td><td class=tableb><center><input type=text name=port></center></td></tr>");
	print ("<tr><td class=tableb></td><td class=tableb><center><input type=submit class=btn value='GO'></center></td></tr>");
	print ("</form>");
	print ("</table>");
	}
}
stdfoot ();
?>