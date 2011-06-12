<?php
/**
 * View votes for requests
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

INIT();

loggedinorreturn();

if ($_GET[requestid])
{
	$requestid = (int) $_GET[requestid];
	$res2 = sql_query("SELECT SUM(1) FROM addedrequests INNER JOIN users ON addedrequests.userid = users.id INNER JOIN requests ON addedrequests.requestid = requests.id WHERE addedrequests.requestid = ".sqlesc($requestid)." GROUP BY requests.id") or die(mysql_error());
	$row = mysql_fetch_array($res2);
	$count = $row[0];
	$perpage = 50;
	$limit = pager($perpage, $count, array('votesview') );
	$res = sql_query("SELECT users.id as userid,users.username, users.ratingsum, users.class, users.enabled, users.warned, users.donor requests.id as requestid, requests.request FROM addedrequests INNER JOIN users ON addedrequests.userid = users.id INNER JOIN requests ON addedrequests.requestid = requests.id WHERE addedrequests.requestid =$requestid $limit") or sqlerr(__FILE__, __LINE__);
	$REL_TPL->stdhead("Голосовавшие");
	$res2 = sql_query("SELECT request FROM requests WHERE id=$requestid");
	$arr2 = mysql_fetch_assoc($res2);

	print("<h1>Голосовавшие для <a href=\"".$REL_SEO->make_link('requests','id',$requestid)."\"><b>$arr2[request]</b></a></h1>");
	print("<p>Голосовать за этот <a href=\"".$REL_SEO->make_link('requests','action','vote','voteid',$requestid)."\"><b>запрос</b></a></p>");

	if (mysql_num_rows($res) == 0)
	print("<p align=center><b>Ничего не найдено</b></p>\n");
	else
	{
		print("<table border=1 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=colhead>Имя</td><td class=colhead>Рейтинг</td></tr>\n");
		while ($arr = mysql_fetch_assoc($res))
		{
			$ratio = ratearea($arr['ratingsum'],$arr['userid'],'users',$CURUSER['id']);
			$user = $arr;
			$user['id'] = $arr['userid'];
			print("<tr><td>".make_user_link($user)."</td><td nowrap>$ratio</td></tr>\n");
		}
		print("</table>\n");
	}
	$REL_TPL->stdfoot();
}


die("Direct access to this file not allowed.");

?>