<?php
if (!defined('BLOCK_FILE')) {
header("Location: ../index.php");
exit;
}

global $CACHEARRAY;
			
$content .= "<table  width=100%><tr><td  valign=top align=center>";

$content .= "<small>[<a href=viewrequests.php>Всё</a>] [<a href=requests.php?action=new>Заказать<a>]</small><hr><table border=1 class=bottom>";

 if (!defined("CACHE_REQUIRED")){
 	require_once($rootpath . 'classes/cache/cache.class.php');
	require_once($rootpath .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

			$reqarray = $cache->get('block-req', 'query', $CACHEARRAY['requests_lastupdate']);
			
if ($reqarray===false) {
$req=sql_query("SELECT requests.* FROM requests INNER JOIN categories ON requests.cat = categories.id WHERE requests.filled = '' ORDER BY added DESC LIMIT 3;");

while ($reqres = @mysql_fetch_array($req))
$reqarray[]=$reqres;

$cache->set('block-req', 'query', $reqarray);
               sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='requests_lastupdate'");
}

    if (!$reqarray) $content = '<table><tr><td><div align="center">Нет запросов</div><table>'; else
foreach ($reqarray as $requests) {
if ($requests[filledby]!=0) {
$done = "<a href=".addslashes($requests[filled])."><img border=\"0\" src=\"pic/chk.gif\" alt=\"Выполнен\"/></a>";
}
else {
$done = "";
}

$content .= "<tr><td class='req'><b><a id='reqq' href=requests.php?id=$requests[id]>$requests[request]</b></a>&nbsp;&nbsp;&nbsp;$done<br> [комм:  $requests[comments], нуждающихся: $requests[hits]]<br><small><a href=requests.php?action=vote&voteid=$requests[id]>Присоединиться к запросу</a></small></td></tr>";

}

$content .= "</table>";
$content .= "</td></tr></table>";

$blocktitle = "<font color=\"red\">Стол заказов</font>";
?>