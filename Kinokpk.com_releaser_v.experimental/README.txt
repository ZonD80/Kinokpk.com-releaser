---------------------------------------------------------------------------------------------------------------
------ THIS IS AN APLHA/BETA VERSION, POST ANY COMMENTS TO http://dev.kinokpk.com/viewforum.php?f=19 ----------
---------------------------------------------------------------------------------------------------------------
Kinokpk.com relaser 4.00 WITH XBTT SUPPORT INSTALLATION NOTES
===============================================================================================================
Installation:

Upload all contents from "upload" folder to the root of your site
Import SQL-file from "update_draft/install/install.sql"

SET your site URL by SQL-query:
UPDATE cache_stats SET cache_value='http://yoursite.com' WHERE cache_name='defaultbaseurl'

Edit include/secrets.php an fill your database connection properties

Register first admin account via http://yoursite.com/signup.php

TEST, TEST, TEST

===============================================================================================================
Updating from 3.30 (it does not tested at all!)

Import SQL-file from "update_draft/update/update.sql"
Execute this script to reorder classes:

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
-----
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

Clear all caches.
===============================================================================================================

INSTALL XBTT BY MANUAL: http://xbtt.sourceforge.net/tracker/ , without executing sql-dumps

enable "use xbt" option in http://your.site/configadmin.php

===============================================================================================================
IPB 3.2+ INTEGRATION

Setup your IPB.
Verify, that "Forum enabled" in http://your.site/configadmin.php is NO.
Edit classes/ipbwi/config.inc.php, fill these parameters:
ipbwi_BOARD_PATH
ipbwi_ROOT_PATH

Execute this query:
update users set forum_id=ID_OF_YOUR_FORUM_ACCOUNT where id=ID_OF_YOUR_TRACKER_ACCOUNT

Set "Forum enabled" in http://your.site/configadmin.php to YES.