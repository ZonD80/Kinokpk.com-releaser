<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $tracker_lang, $ss_uri, $maxusers;

if (!defined("CACHE_REQUIRED")) {
  	require_once 'classes/cache/cache.class.php';
	require_once 'classes/cache/fileCacheDriver.class.php';
}

  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());
		
 $block_online=$cache->get('block-stats', 'queries');
 
// var_dump($block_online);
$registered = $block_online['users'];
$unverified = $block_online['users_pending'];
$male = $block_online['males'];
$female = $block_online['females'];
$torrents = $block_online['torrents'];
$nofiler = $block_online['torrents_nofile'];
$dead = $block_online['torrents_dead'];
$peersrow = sql_query("(SELECT COUNT(*) AS peers FROM peers WHERE seeder='yes') UNION (SELECT COUNT(*) AS peers FROM peers WHERE seeder='no')");
while (list($peersarray) = mysql_fetch_array($peersrow))
$peers[] = $peersarray;
$seeders = $peers[0];
$leechers = $peers[1];
$warned_users = $block_online['users_warned'];
$disabled = $block_online['users_disabled'];
$uploaders = $block_online['uploaders'];
$vip = $block_online['vips'];
if ($leechers == 0)
  $ratio = 0;
else
  $ratio = round($seeders / $leechers * 100);
$peers = number_format($seeders + $leechers);
$seeders = number_format($seeders);
$leechers = number_format($leechers);

$content .= "<table width=\"100%\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"><td align=\"center\">
<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">

<table width=\"100%\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\">
  <tr>
    <td width=\"50%\" align=\"center\" style=\"border: none;\"><table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">
<tr><td class=\"rowhead\">".$tracker_lang['users_registered']."</td><td align=right><img src=\"pic/male.gif\" alt=\"Парни\">$male<img src=\"pic/female.gif\" alt=\"Девушки\">$female<br />".$tracker_lang['total'].": $registered</td></tr>
<tr><td colspan=\"2\" class=\"rowhead\"><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr><td style=\"text-align: right; font-weight: bold; vertical-align: top;\">Мест на трекере</td><td align=\"right\">$maxusers</td></tr></table></td></tr>
<tr><td class=\"rowhead\">".$tracker_lang['users_unconfirmed']."</td><td align=right>$unverified</td></tr>
<tr><td class=\"rowhead\">".$tracker_lang['users_warned']."&nbsp;<img src=\"pic/warned.gif\" border=0 align=absbottom></td><td align=right>$warned_users</td></tr>
<tr><td class=\"rowhead\">".$tracker_lang['users_disabled']."&nbsp;<img src=\"pic/disabled.gif\" border=0 align=absbottom></td><td align=right>$disabled</td></tr>
<tr><td class=\"rowhead\"><font color=\"orange\">".$tracker_lang['users_uploaders']."</font></td><td align=right>$uploaders</td></tr>
<tr><td class=\"rowhead\"><font color=\"#9C2FE0\">".$tracker_lang['users_vips']."</font></td><td align=right>$vip</td></tr>

</table></td>
<td width=\"50%\" align=\"center\" style=\"border: none;\"><table class=main border=1 cellspacing=0 cellpadding=5>
<tr><td class=\"rowhead\"><a href=\"browse.php\">Релизов</a></td><td align=right>$torrents</td></tr>
<tr><td class=\"rowhead\"><a href=\"browse.php?search=&incldead=5&cat=0\">, из них без торрентов</a></td><td align=right>$nofiler</td></tr>
<tr><td class=\"rowhead\"><a href=\"browse.php?search=&incldead=2&cat=0\">Мертвых релизов</a></td><td align=right>$dead</td></tr>
<tr><td class=\"rowhead\"><a href= \"peers.php\"><u>".$tracker_lang['tracker_peers']."</u></a></td><td align=right>$peers</td></tr>";
if (isset($peers)) {
$content .= "<tr><td class=\"rowhead\"><a href=\"peers.php?view=seeders\"><u>".$tracker_lang['tracker_seeders']."</u></a>&nbsp;&nbsp;<img src=\"./themes/$ss_uri/images/arrowup.gif\" border=0 align=absbottom></td><td align=right>$seeders</td></tr>
<tr><td class=\"rowhead\"><a href=\"peers.php?view=leechers\"><u>".$tracker_lang['tracker_leechers']."</u></a>&nbsp;&nbsp;<img src=\"./themes/$ss_uri/images/arrowdown.gif\" border=0 align=absbottom></td><td align=right>$leechers</td></tr>
<tr><td class=\"rowhead\">".$tracker_lang['tracker_seed_peer']."</td><td align=right>$ratio</td></tr>";
}

$content .= "</table></td>

</table>
</td></tr></table>";

?>