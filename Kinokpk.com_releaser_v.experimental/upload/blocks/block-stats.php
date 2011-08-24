<?php
global $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}

$block_online=$REL_CACHE->get('block-stats', 'queries',300);
$classes =init_class_array();
// UPDATE CACHES:
if ($block_online===false) {
	$res=sql_query("(SELECT SUM(1) FROM users) UNION ALL
     (SELECT SUM(1) FROM users WHERE confirmed=0) UNION ALL
      (SELECT SUM(1) FROM users WHERE gender=1) UNION ALL
       (SELECT SUM(1) FROM users WHERE gender=2) UNION ALL
        (SELECT SUM(1) FROM torrents) UNION ALL
         (SELECT SUM(1) FROM torrents WHERE filename = 'nofile') UNION ALL
          (SELECT SUM(1) FROM torrents WHERE visible=0) UNION ALL
           (SELECT SUM(1) FROM users WHERE warned = 1) UNION ALL
            (SELECT SUM(1) FROM users WHERE enabled = 0) UNION ALL
             (SELECT SUM(1) FROM users WHERE class = ".$classes['vip'].") UNION ALL
               (SELECT SUM(size) FROM torrents) UNION ALL
                 (SELECT SUM(seeders) FROM trackers WHERE tracker<>'localhost') UNION ALL
                 (SELECT SUM(leechers) FROM trackers WHERE tracker<>'localhost')");

	$params = array(
'users',
'users_pending',
'males',
'females',
'torrents',
'torrents_nofile',
'torrents_dead',
'users_warned',
'users_disabled',
'vips',
'size',
'seeders',
'leechers');
	foreach ($params as $param) {
		list($value) = mysql_fetch_array($res);
		$block_online[$param] = $value;
	}


	$REL_CACHE->set('block-stats', 'queries', $block_online);

}


// var_dump($block_online);
$registered = $block_online['users'];
$unverified = $block_online['users_pending'];
$male = $block_online['males'];
$female = $block_online['females'];
$torrents = $block_online['torrents'];
$nofiler = $block_online['torrents_nofile'];
$dead = $block_online['torrents_dead'];
$peersrow = sql_query("(SELECT SUM(1) AS peers FROM peers WHERE seeder=1) UNION (SELECT SUM(1) AS peers FROM peers WHERE seeder=0)");
while (list($peersarray) = mysql_fetch_array($peersrow))
$peers[] = $peersarray;
$seeders = $peers[0];
$leechers = $peers[1];
$warned_users = $block_online['users_warned'];
$disabled = $block_online['users_disabled'];
$uploaders = $block_online['uploaders'];
$vip = $block_online['vips'];
$total_size = mksize($block_online['size']);
if ($leechers == 0)
$ratio = 0;
else
$ratio = round((($block_online['seeders']+$seeders) / ($block_online['leechers']+$leechers)*100),2);
$peers = ($seeders + $leechers + $block_online['seeders']+$block_online['leechers']);
$seeders = number_format($seeders);
$leechers = number_format($leechers);

$content .= "<table width=\"100%\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"><tr><td align=\"center\">
<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr><td>

<table width=\"100%\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\">
  <tr>
    <td width=\"50%\" align=\"center\" style=\"border: none;\"><table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">
<tr><td class=\"rowhead\">".$REL_LANG->say_by_key('users_registered')."</td><td align=\"right\"><img src=\"pic/male.gif\" alt=\"{$REL_LANG->_('Males')}\"/>".number_format($male)."<img src=\"pic/female.gif\" alt=\"{$REL_LANG->_('Females')}\"/>".number_format($female)."<br />".$REL_LANG->say_by_key('total').": ".number_format($registered)."</td></tr>
".($REL_CONFIG['maxusers']?"<tr><td colspan=\"2\" class=\"rowhead\"><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr><td style=\"text-align: right; font-weight: bold; vertical-align: top;\">{$REL_LANG->_('Account limit')}</td><td align=\"right\">".number_format($REL_CONFIG['maxusers'])."</td></tr></table></td></tr>":'')."
<tr><td class=\"rowhead\">".$REL_LANG->say_by_key('users_unconfirmed')."</td><td align=\"right\">".($unverified?$unverified:$REL_LANG->say_by_key('no'))."</td></tr>
<tr><td class=\"rowhead\">".$REL_LANG->say_by_key('users_warned')."&nbsp;<img src=\"pic/warned.gif\" alt=\"{$REL_LANG->say_by_key('users_warned')}\" border=\"0\" align=\"bottom\"/></td><td align=\"right\">".($warned_users?number_format($warned_users):$REL_LANG->say_by_key('no'))."</td></tr>
<tr><td class=\"rowhead\">".$REL_LANG->say_by_key('users_disabled')."&nbsp;<img src=\"pic/disabled.gif\" alt=\"{$REL_LANG->say_by_key('users_disabled')}\" border=\"0\" align=\"bottom\"/></td><td align=\"right\">".($disabled?number_format($disabled):$REL_LANG->say_by_key('no'))."</td></tr>
<tr><td class=\"rowhead\"><font color=\"#9C2FE0\">".$REL_LANG->say_by_key('users_vips')."</font></td><td align=\"right\">".($vip?number_format($vip):$REL_LANG->say_by_key('no'))."</td></tr>

</table></td>
<td width=\"50%\" align=\"center\" style=\"border: none;\"><table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">
<tr><td class=\"rowhead\"><a href=\"".$REL_SEO->make_link('browse')."\">{$REL_LANG->_('Releases')}</a></td><td align=\"right\">".number_format($torrents)."</td></tr>
<tr><td class=\"rowhead\"><a href=\"".$REL_SEO->make_link('browse','nofile','')."\">{$REL_LANG->_('Releases without torrents')}</a></td><td align=\"right\">".($nofiler?number_format($nofiler):$REL_LANG->say_by_key('no'))."</td></tr>
<tr><td class=\"rowhead\"><a href=\"".$REL_SEO->make_link('browse','dead','')."\">{$REL_LANG->_('Dead releases')}</a></td><td align=\"right\">".($dead?number_format($dead):$REL_LANG->say_by_key('no'))."</td></tr>
<tr><td class=\"rowhead\"><a href= \"".$REL_SEO->make_link('peers')."\">".$REL_LANG->say_by_key('tracker_peers')."</a></td><td align=\"right\">".number_format($peers)."</td></tr>";
if (isset($peers)) {
	$content .= "<tr><td class=\"rowhead\"><a href=\"".$REL_SEO->make_link('peers','view','seeders')."\">".$REL_LANG->say_by_key('tracker_seeders')."</a>&nbsp;&nbsp; <img src=\"./themes/{$REL_CONFIG['ss_uri']}/images/arrowup.gif\" alt=\"{$REL_LANG->_('Seeding')}\" border=\"0\" align=\"bottom\"/></td><td align=\"right\">".number_format($seeders+$block_online['seeders'])."</td></tr>
<tr><td class=\"rowhead\"><a href=\"".$REL_SEO->make_link('peers','view','leechers')."\">".$REL_LANG->say_by_key('tracker_leechers')."</a>&nbsp;&nbsp;<img src=\"./themes/{$REL_CONFIG['ss_uri']}/images/arrowdown.gif\" alt=\"{$REL_LANG->_('Leeching')}\" border=\"0\" align=\"bottom\"/></td><td align=\"right\">".number_format($leechers+$block_online['leechers'])."</td></tr>
<tr><td class=\"rowhead\">{$REL_LANG->_('Total releases size')}</td><td align=\"right\">$total_size</td></tr>
<tr><td class=\"rowhead\">".$REL_LANG->say_by_key('tracker_seed_peer')."</td><td align=\"right\">$ratio</td></tr>";
}

$content .= "</table></td></tr>
</table>
</td></tr></table>
</td></tr></table>
";

?>