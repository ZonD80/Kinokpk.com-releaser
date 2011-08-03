<?php
/**
 * Subnet finder for PEX & other
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();

loggedinorreturn();

$mask = "255.255.255.0";
$tmpip = explode(".",$CURUSER["ip"]);
$ip = $tmpip[0].".".$tmpip[1].".".$tmpip[2].".0";
$regex = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))(\.\b|$)){4}$/";
if (substr($mask,0,1) == "/")
{
	$n = substr($mask, 1, mb_strlen($mask) - 1);
	if (!is_numeric($n) or $n < 0 or $n > 32)
	{
		stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_subnet'));
		$REL_TPL->stdfoot();
		die();
	}
	else
	$mask = long2ip(pow(2,32) - pow(2,32-$n));
}
elseif (!preg_match($regex, $mask))
{
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_subnet'));
	$REL_TPL->stdfoot();
	die();
}
$res = sql_query("SELECT id, username, class, last_access, added, ratingsum, warned, enabled, donor FROM users WHERE enabled=1 AND confirmed=1 AND id <> $CURUSER[id] AND INET_ATON(ip) & INET_ATON('$mask') = INET_ATON('$ip') & INET_ATON('$mask')") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res)){
	$REL_TPL->stdhead($REL_LANG->say_by_key('network_neighbot') );

	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=center colspan=8>:: ".$REL_LANG->say_by_key('network_neighbot')." ::</td></tr><tr><td colspan=8><center>".$REL_LANG->say_by_key('speed_above')."</center></td></tr>");
	print("<tr><td class=colhead align=left>".$REL_LANG->say_by_key('username')."</td>
<td class=colhead>".$REL_LANG->say_by_key('ratio')."</td><td class=colhead>".$REL_LANG->say_by_key('registered')."</td>
<td class=colhead>".$REL_LANG->say_by_key('last_access')."</td><td class=colhead align=left>".$REL_LANG->say_by_key('class')."</td>
<td class=colhead>IP</td></tr>\n");
	while($arr=mysql_fetch_assoc($res)){
		print("<tr><td align=left>".make_user_link($arr)."</td>

<td>".ratearea($arr['ratingsum'],$arr['id'],'users',$CURUSER['id'])."</td>
<td>".mkprettytime($arr[added])."</td><td>".get_elapsed_time($arr[last_access])." {$REL_LANG->say_by_key('ago')}</td>
<td align=left>".get_user_class_name($arr["class"])."</td>
<td>".$tmpip[0].".".$tmpip[1].".".$tmpip[2].".*</td></tr>\n");
	}
	print("</table>");
	$REL_TPL->stdfoot();}
	else
	stderr($REL_LANG->say_by_key('information'), $REL_LANG->say_by_key('not_found_neighbot'));
	?>