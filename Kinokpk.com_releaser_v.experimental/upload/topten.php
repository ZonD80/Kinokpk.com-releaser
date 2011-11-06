<?php
/**
 * Top ten
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once "include/bittorrent.php";

INIT();

loggedinorreturn();

if (!pagercheck()) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('topten'));
}
$res = $REL_DB->query("SELECT SUM(1) FROM users");
$count = mysql_result($res,0);
if (!$count) { $REL_TPL->stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'),'error'); $REL_TPL->stdfoot(); die(); }

$limit = ajaxpager(10, $count, array('topten'), 'userst > tbody:last');



$res = $REL_DB->query("SELECT u.*, c.name, c.flagpic FROM users AS u LEFT JOIN countries AS c ON c.id = u.country ORDER BY ratingsum DESC $limit");
$num = mysql_num_rows($res);


if (!pagercheck()) {
	print("<div id=\"pager_scrollbox\"><table id=\"userst\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" align=\"left\">{$REL_LANG->_('Username')}</td><td class=\"colhead\">{$REL_LANG->_('Registered at')}</td><td class=\"colhead\">{$REL_LANG->_('Last login')}</td><td class=\"colhead\">{$REL_LANG->_('Rating')}</td><td class=\"colhead\">{$REL_LANG->_('Gender')}</td><td class=\"colhead\" align=\"left\">{$REL_LANG->_('Class')}</td><td class=\"colhead\">{$REL_LANG->_('Country')}</td></tr>\n");
}
while ($arr = mysql_fetch_assoc($res)) {
	if ($arr['country'] > 0) {
		$country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
	}
	else
	$country = "<td align=\"center\">---</td>";
	$ratio = ratearea($arr['ratingsum'],$arr['id'],'users',$CURUSER['id']);

	if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" title=\"{$REL_LANG->_('Male')}\" style=\"margin-left: 4pt\">";
	elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" title=\"{$REL_LANG->_('Female')}\" style=\"margin-left: 4pt\">";
	else $gender = "<div align=\"center\"><b>?</b></div>";

	print("<tr><td align=\"left\">".make_user_link($arr)."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])." (".get_elapsed_time($arr["last_access"],false)." {$REL_LANG->say_by_key('ago')})</td><td>$ratio</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country</tr>\n");
}

if (pagercheck()) die();
print("</table></div>\n");

$REL_TPL->stdfoot();

?>
