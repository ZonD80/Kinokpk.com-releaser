<?php
/**
 * Online users viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");

INIT();
loggedinorreturn();

$REL_TPL->stdhead("Где пользователь");

$secs = 1 * 300;//Время выборки (5 последних минут)
$dt = time() - $secs;


$res = sql_query("SELECT SUM(1) FROM sessions $searchs WHERE time > $dt");
$row = mysql_fetch_array($res);
$count = $row[0];
$per_list = 100;

$limit = "LIMIT 50";
$spy_res = sql_query("SELECT url, uid, username, class, ip, useragent FROM sessions WHERE time > $dt ORDER BY uid ASC $limit");

echo "<table  class=\"embedded\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr><td class=\"colhead\" align=\"center\" colspan=\"3\">Где находятся пользователи (активность за последние 5 минут)</td></tr>";

echo "<tr><td  class=\"colhead\" align=\"center\">Пользователь</td>"
."<td class=\"colhead\" align=\"center\">Звание</td>"
."<td class=\"colhead\" align=\"center\">Просматривает</td></tr>";


	if (isset($searchs) && $count < 1) {
		print("<tr><td class=\"index\" colspan=\"3\">".$REL_LANG->say_by_key('nothing_found')."</td></tr>\n");
	}



	$i=20;

	while(list($spy_url, $user_id, $user_name, $user_class, $user_ip, $user_agent, $user_time) = mysql_fetch_array($spy_res)){

		$i++;
		$spy_urlse =  basename($spy_url);
		$res_list =  explode(".php", $spy_urlse);
		$read = "";
		if($CURUSER['id'] == $user_id)
		{
			$read = "<font color=\"red\">(Вы здесь)</font>";
		}

		$slep = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Открыть</i></td></tr></table></div><div class=\"sp-body\">"
		."User_agent - ".$user_agent."<br />"
		."IP - <a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">". $user_ip."</a></div></div>";

		if($user_class != -1){
			echo "<tr><td><a target='_blank' href=\"".$REL_SEO->make_link('userdetails','id',$user_id,'username',translit($user_name))."\">".get_user_class_color($user_class, $user_name)."</a> $slep</td>";
			echo "<td><b>".get_user_class_name($user_class)."</b></td><td>";
		}else{
			echo "<tr><td><a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">Гость</a> $slep</td>";
			echo "<td>".$user_ip."</td><td>";
		}
		echo "<a target='_blank' href=\"".$spy_url."\">$spy_url</a> ".$read;
		echo "</td></tr>";



	}
		echo "</table>";

		$REL_TPL->stdfoot();

		?>