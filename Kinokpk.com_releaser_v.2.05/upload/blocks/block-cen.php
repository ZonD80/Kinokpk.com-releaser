<?php
if (!defined('BLOCK_FILE')) {
header("Location: ../index.php");
exit;
}

global $CACHEARRAY;

$content .= "<table border=\"1\" width=\"100%\"><tr><td align=\"center\"><a href=\"viewcensoredtorrents.php\">[ѕосмотреть все]</a></div><hr><table border=\"1\" class=\"main\" width=\"100%\">";

 if (!defined("CACHE_REQUIRED")){
 	require_once($rootpath . 'classes/cache/cache.class.php');
	require_once($rootpath .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

			$ctorrents = $cache->get('block-cen', 'query', $CACHEARRAY['censoredtorrents_lastupdate']);

if ($ctorrents===false) {
$ctorrentsrow=sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC LIMIT 3");
$ctorrents = array();
while ($ctres = mysql_fetch_array($ctorrentsrow))
$ctorrents[] = $ctres;

$time = time();
$cache->set('block-cen', 'query', $ctorrents);
               sql_query("UPDATE cache_stats SET cache_value=".$time." WHERE cache_name='censoredtorrents_lastupdate'");
               
}

if ($ctorrents)
foreach ($ctorrents as $ct) {

if (strlen($ct['reason']) > 500) $reason = format_comment(substr($ct['reason'],0,500)."..."); else $reason = format_comment($ct['reason']);

$content .= "<tr><td><b>".$ct['name']."</b><br>".$reason."</tr>";
}

$content .= "</table>";
$content .= "<td width=\"200\">«а публикацию данных фильмов ваш аккаунт будет немедленно заблокирован без каких-либо предупреждений. ѕубликаци€ этих фильмов была официально запрещена правообладателем, либо запрещена по любой другой причине, не завис€щей от нас.<br/>«апрет действует до тех пор, пока фильм находитс€ в <a href=\"viewcensoredtorrents.php\">списке запрещенных релизов</a>.</td></tr></table>";


$blocktitle = "<font color=\"red\">«апрещенные релизы</font>";
?>