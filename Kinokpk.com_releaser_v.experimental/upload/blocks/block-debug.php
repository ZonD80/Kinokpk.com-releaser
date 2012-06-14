<?php
global $REL_DB, $REL_SEO, $REL_DB;
if (!defined('BLOCK_FILE')) {
    safe_redirect($REL_SEO->make_link('index'));
    exit;
}

$ret = $REL_DB->query_return("select (select count(*) from trackers where lastchecked<UNIX_TIMESTAMP()-10800 and tracker<>'localhost') as unchecked, (select count(*) from trackers where tracker<>'localhost') as total_remote, (select count(*) from trackers where state='in_check') as checking");
$unchecked = $ret[0]['unchecked'];
$total = $ret[0]['total_remote'];
$checking = $ret[0]['checking'];
$checked = $total - $unchecked;

$cronrow = $REL_DB->query("SELECT * FROM cron WHERE cron_name IN ('remotepeers_cleantime','in_remotecheck','remote_trackers')");
while ($cronres = mysql_fetch_array($cronrow)) $REL_CRON[$cronres['cron_name']] = $cronres['cron_value'];

$content .= "<pre>";
$content .= "
Trackers:
total $total
unchecked $unchecked
checked $checked
---
checking $checking
rate " . ($checked / $REL_CRON['remote_trackers']) . "
rate unchecked " . ($unchecked / $REL_CRON['remote_trackers']);

$ret = $REL_DB->query_row("select (select count(*) from torrents) as total, (select count(*) from torrents where seeders=0) as dead");
$content .= "
--------------
Torrents:
total torrents {$ret['total']}
dead torrents (0 seeders) {$ret['dead']}";
$content .= "</pre>";
?>