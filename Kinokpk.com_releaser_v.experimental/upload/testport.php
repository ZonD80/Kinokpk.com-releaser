<?
require "include/bittorrent.php";
INIT();

loggedinorreturn();
$REL_TPL->stdhead($REL_LANG->say_by_key('test_port'));
if ($CURUSER) {
	$ip = $CURUSER['ip'];

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	$port = (int)$_POST["port"];
	else
	$port = (int)$_GET['port'];
	if ($port) {
		$fp = @fsockopen ($ip, $port, $errno, $errstr, 10);
		if (!$fp) {
			print ("<table width=40% class=main cellspacing=1 cellpadding=5><br /><tr>".
			"<td class=colhead align=center><b>Port test</b></td></tr><tr><td class=tableb><font color=darkred><br /><center><b>IP: $ip ".$REL_LANG->say_by_key('is_on_the_port')." $port ".$REL_LANG->say_by_key('not_good')."</b></center><br /></font></td></tr><tr><td class=tableb><center><form><INPUT TYPE=\"BUTTON\" VALUE=\"".$REL_LANG->say_by_key('new_port_test')."\" ONCLICK=\"window.location.href='/".$REL_SEO->make_link('testport')."'\"></form></center></td></tr></table");
		} else {
			print ("<table width=40% class=main cellspacing=1 cellpadding=5><br /><tr>".
			"<td class=colhead align=center><b>Port test</b></td></tr><tr><td class=tableb><font color=darkgreen><br /><center><b>IP: $ip ".$REL_LANG->say_by_key('is_on_the_port')." $port ".$REL_LANG->say_by_key('good')."</b></center><br /></font></td></tr><tr><td class=tableb><center><form><INPUT TYPE=\"BUTTON\" VALUE=\"".$REL_LANG->say_by_key('new_port_test')."\" ONCLICK=\"window.location.href='/".$REL_SEO->make_link('testport')."'\"></form></center></td></tr></table>");
		}
	}

	else
	{
		print("<table width=40% class=main cellspacing=1 cellpadding=5><br /><tr>".
	"<td class=colhead align=center colspan=2><b>".$REL_LANG->say_by_key('test_port')."</b></td>".
	"</tr>");
		print ("<form method=post action=\"".$REL_SEO->make_link('testport')."\">");
		print ("<tr><td class=tableb><center>".$REL_LANG->say_by_key('port_number')."<center></td><td class=tableb><center><input type=text name=port></center></td></tr>");
		print ("<tr><td class=tableb></td><td class=tableb><center><input type=submit class=btn value='GO'></center></td></tr>");
		print ("</form>");
		print ("</table>");
	}
}
$REL_TPL->stdfoot();
?>