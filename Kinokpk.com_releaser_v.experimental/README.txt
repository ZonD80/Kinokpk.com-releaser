---------------------------------------------------------------------------------------------------------------
-------THIS IS AN APLHA/BETA VERSION, POST ANY COMMENTS TO http://dev.kinokpk.com/viewforum.php?f=19-----------
---------------------------------------------------------------------------------------------------------------
Kinokpk.com relaser 3.40 WITH XBTT SUPPORT INSTALLATION NOTES

Installation (UPDATE FROM 3.00 MAY BE UNCOMPATIBLE SINCE 3.40 ALPHA):

Upload all contents from "upload" folder to the root of your site
Import SQL-file from "update_draft/install/install.sql"

SET your site URL by SQL-query:
UPDATE cache_stats SET cache_value='http://yoursite.com' WHERE cache_name='defaultbaseurl'

Import language files from "update_draft/install/lang"

IF YOU PREFORMED AN UPDATE, execute this script to reorder classes:
------
<pre>
<?php
require_once('include/bittorrent.php');
INIT();
$sysopssql = $REL_DB->query("select id from users where class=6");
while (list($id) = mysql_fetch_array($sysopssql)) {
$sysops[] = $id;
}
$sysops = implode(',',$sysops);

$adminssql = $REL_DB->query("select id from users where class=5");
while (list($id) = mysql_fetch_array($adminssql)) {
$admins[] = $id;
}
$admins = implode(',',$admins);

$modssql = $REL_DB->query("select id from users where class=4");
while (list($id) = mysql_fetch_array($modssql)) {
$mods[] = $id;
}
$mods = implode(',',$mods);

$uplssql = $REL_DB->query("select id from users where class=3");
while (list($id) = mysql_fetch_array($uplssql)) {
$upls[] = $id;
}
$upls = implode(',',$upls);

$vipssql = $REL_DB->query("select id from users where class=2");
while (list($id) = mysql_fetch_array($vipsssql)) {
$vips[] = $id;
}
$vips = implode(',',$vips);

$REL_DB->query("UPDATE users SET class=7 where class in (0,1)");
$REL_DB->query("UPDATE users SET class=1 where id in ($sysops)");
$REL_DB->query("UPDATE users SET class=2 where id in ($admins)");
$REL_DB->query("UPDATE users SET class=3 where id in ($mods)");
$REL_DB->query("UPDATE users SET class=4 where id in ($upls)");
$REL_DB->query("UPDATE users SET class=5 where id in ($vips)");
?>
------
---------------------------------------------------------------------------

INSTALL XBTT BY MANUAL: http://xbtt.sourceforge.net/tracker/

---------------------------------------------------------------------------

Execute this SQL-code:

--
-- Dumping data for table `xbt_config`
--

INSERT INTO `xbt_config` (`name`, `value`) VALUES
('announce_interval', '1800'),
('anonymous_connect', '0'),
('anonymous_announce', '0'),
('anonymous_scrape', '0'),
('auto_register', '0'),
('clean_up_interval', '60'),
('daemon', '1'),
('debug', '0'),
('full_scrape', '0'),
('gzip_debug', '1'),
('gzip_scrape', '1'),
('listen_ipa', '*'),
('listen_port', '2710'),
('log_access', '0'),
('log_announce', '1'),
('log_scrape', '0'),
('offline_message', ''),
('pid_file', 'xbt_tracker.pid'),
('read_config_interval', '60'),
('read_db_interval', '60'),
('redirect_url', 'YOUR_SITE_URI_WITHOUT_ENDING_SLASH'),
('scrape_interval', '0'),
('write_db_interval', '15'),
('torrent_pass_private_key', 'some_key');

insert into xbt_users (uid,torrent_pass) select id,passkey from users;

insert into cache_stats (cache_name,cahce_value) values ('use_xbt',1);


ALTER TABLE `users`
  DROP `passkey`,
  DROP `passkey_ip`,
  DROP `last_downloaded`,
  DROP `last_announced`;

ALTER TABLE  `torrents` ADD  `seeders` INT( 5 ) UNSIGNED NOT NULL DEFAULT  '0',
ADD  `leechers` INT( 5 ) UNSIGNED NOT NULL DEFAULT  '0';

---------------------------------------------------------------------------------------------

Execute this PHP script ONCE!!!:
<?php

require_once('include/bittorrent.php');
INIT();

$res = $REL_DB->query("SELECT id, info_hash FROM torrents ORDER BY id ASC");
while ($row=mysql_fetch_assoc($res)) {
$REL_DB->query("INSERT INTO xbt_files (fid,info_hash,completed,ctime) VALUES ({$row['id']},".sqlesc(pack('H*', $row['info_hash'])).",{$row['times_completed']},{$row[added]})") or sqlerr(__FILE__,__LINE__);
print "{$row['id']} processed<br/>";
}
?>

Preform code modifications described in commits
---------------------------------------------------------------------------------------------
Clear cache named "system"
----------------------------------------------------------------------------------------------
Start xbtt, try to download/upload files to your tracker
------------------
WARNING: No configuration files are written yet for xbtt at all or "use_xbt" parameter in cache_stats. Please edit them manually, clearing cache "system" after modification.
Also, php scaper/announcer is disabled due incompatibility. (it need to be rewritten for xbtt for pure noob-admins)

No configuration files are wiritten yet for class system. Please edit table "classes" manually
