<?php
/**
 * Test that ip was banned
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
INIT();

loggedinorreturn();
get_privilege('bans_admin');

if ($_SERVER["REQUEST_METHOD"] == "POST")
$ip = htmlspecialchars(trim((string)$_POST["ip"]));
else
$ip = htmlspecialchars(trim((string)$_GET["ip"]));
if ($ip)
{
	$res = $REL_DB->query("SELECT mask FROM bans");
	while (list($mask) = mysql_fetch_array($res))
	$maskres[] = $mask;
	$ipsniff = new IPAddressSubnetSniffer($maskres);
	if (!$ipsniff->ip_is_allowed($ip) )
	$REL_TPL->stderr($REL_LANG->say_by_key('result'), "".$REL_LANG->say_by_key('ip_address')." <b>$ip</b> ".$REL_LANG->say_by_key('not_banned')."");
	else
	{
		$REL_TPL->stderr($REL_LANG->say_by_key('result'), "".$REL_LANG->say_by_key('ip_address')." <b>$ip ".$REL_LANG->say_by_key('banned')."</b>");
	}
}
$REL_TPL->stdhead($REL_LANG->say_by_key('check_ip'));

?>
<h1><?php print $REL_LANG->say_by_key('check_ip'); ?></h1>
<form method=post action="<?php print $REL_SEO->make_link('testip'); ?>">
<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<td class=rowhead><?php print $REL_LANG->say_by_key('ip_address'); ?></td>
		<td><input type=text name=ip></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit class=btn value='OK'></td>
	</tr>
	</form>
</table>

<?php
$REL_TPL->stdfoot();
?>