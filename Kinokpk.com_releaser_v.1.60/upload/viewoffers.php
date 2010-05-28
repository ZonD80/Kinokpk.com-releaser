<?php

/*
Project: Kinokpk.com releaser
This file is part of Kinokpk.com releaser.
Kinokpk.com releaser is based on TBDev,
originally by RedBeard of TorrentBits, extensively modified by
Gartenzwerg and Yuna Scatari.
Kinokpk.com releaser is free software;
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
Kinokpk.com is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Kinokpk.com releaser; if not, write to the
Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
MA  02111-1307  USA
Do not remove above lines!
*/

require_once("include/bittorrent.php");

gzip();

dbconn();

loggedinorreturn();
//curlocation($PHP_SELF);

stdhead("Предложения");

print("<table class=\"embedded\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" >");
print("<tr><td class=\"colhead\" align=\"center\" colspan=\"15\">Секция предложений</td></tr>");
print("<tr><td class=\"index\" colspan=\"15\">");

//begin_main_frame();

//print("<h1 align=center>Предложения</h1>\n");
if (get_user_class() >= UC_USER) {
print("<p><a href=offers.php?action=new>Предложить</a></p>");
}

$categ = intval($_GET["category"]);
$offerorid = intval($_GET["offerorid"]);
$sort = $_GET["sort"];
$search = $_GET["search"];
$filter = $_GET["filter"];

if ($search)
	$search = "AND offers.name LIKE '%" . sqlwildcardesc($search) . "%'";

if ($sort == "votes")
	$sort = "ORDER BY votes DESC";
else if ($sort == "name")
	$sort = "ORDER BY name";
else if ($sort == "comments")
	$sort = "ORDER BY comments DESC";
else
	$sort = "ORDER BY added DESC";


if ($offerorid <> NULL)
{
if (($categ <> NULL) && ($categ <> 0))
$categ = "WHERE offers.category = " . sqlesc($categ) . " AND offers.userid = " . sqlesc($offerorid);
else
$categ = "WHERE offers.userid = " . sqlesc($offerorid);
}

else if ($categ == 0)
$categ = '';
else
$categ = "WHERE offers.category = " . sqlesc($categ);

$res = sql_query("SELECT COUNT(offers.id) FROM offers INNER JOIN categories ON offers.category = categories.id INNER JOIN users ON offers.userid = users.id $categ $filter") or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
         print("<tr><td class=\"colhead\" align=\"center\" colSpan=\"15\">Нет предложений</td></tr>");
         print("<tr><td class=\"index\" colspan=\"15\">");
         print("<p>Нет предложений. Желаете <a href=\"offers.php?action=new\">предложить</a>?</p>");
         print("</td></tr>");
} else {

$perpage = 50;

list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" . "category=" . $_GET["category"] . "&sort=" . $_GET["sort"] . "&" );

$res = sql_query("SELECT users.downloaded, users.uploaded, users.class, users.username, offers.id, offers.userid, offers.name, offers.added, offers.votes, offers.comments, categories.name AS cat_name, categories.id AS cat_id, categories.image AS cat_img FROM offers INNER JOIN categories ON offers.category = categories.id INNER JOIN users ON offers.userid = users.id $categ $filter $search $sort $limit") or sqlerr(__FILE__, __LINE__);
$num = mysql_num_rows($res);

print("<p><form method=\"get\" action=\"viewoffers.php\">");
?>
<select name="category">
<option value="0">(Показать все)</option>
<?

$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
$catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
}

?>
<?= $catdropdown ?>
</select>
<?
print("<input type=\"submit\" align=\"center\" value=\"Изменить\">");
print("</form></p>");

print("<p><form method=\"get\" action=\"viewoffers.php\">");
print("<b>Искать предложения: </b><input type=\"text\" size=\"40\" name=\"search\">");
print("&nbsp;<input type=\"submit\" align=\"center\" value=\"Искать\">\n");
print("</form></p>");

print("<tr><td class=\"index\" colSpan=\"15\">");
print($pagertop);
print("</td></tr>");

//print("<table border=\"1\" width=\"750\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" align=\"center\">Тип</td><td class=\"colhead\" align=\"left\"><a href=". $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&filter=" . $_GET[filter] . "&sort=name class=altlink_white>Предложения</a></td><td class=colhead align=center width=150><a href=" . $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&filter=" . $_GET[filter] . "&sort=added class=altlink_white>Добавлено</a></td><td class=colhead align=center><a href=" . $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&filter=" . $_GET[filter] . "&sort=comments class=altlink_white>Коментариев</a></td><td class=colhead align=center>Предлагает</td><td class=colhead align=center><a href=" . $_SERVER[PHP_SELF] ."?category=" . $_GET[category] . "&filter=" . $_GET[filter] . "&sort=votes class=altlink_white>Голосов</a></td></tr>\n");

for ($i = 0; $i < $num; ++$i)
{
$arr = mysql_fetch_assoc($res);
if ($arr["downloaded"] > 0)
{
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
$ratio = "<font color=\"" . get_ratio_color($ratio) . "\"><b>$ratio</b></font>";
}
else if ($arr["uploaded"] > 0)
$ratio = "Inf.";
else
$ratio = "---";
if ($arr["votes"] == 0) $zvote = "$arr[votes]"; else $zvote = "<b>$arr[votes]</b>";


$url = "offers.php?action=edit&id=$arr[id]";
if (isset($_GET["returnto"])) {
$addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
$url .= $addthis;
$keepget .= $addthis;
}
$editlink = "a href=\"$url\" class=\"sublink\"";

if ($CURUSER["id"] != $num["userid"] && get_user_class() < UC_MODERATOR){
$zedit = "";
} else {
$zedit = "&nbsp;<$editlink><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>";
}

$addedby = "<td style='padding: 0px' align=center ><a href=userdetails.php?id=$arr[userid]><b>".get_user_class_color($arr["class"], $arr["username"])." (<font color=\"".get_ratio_color($ratio)."\">$ratio</font>)</b></a></td>";
if ($arr["comments"] == 0)
$comment = "0";
else
$comment = "<a href=offers.php?id=$arr[id]#startcomments><b>$arr[comments]</b></a>";

print("<tr><td align=center style='padding: 0px'>");
if (isset($arr["cat_name"])) {
if (isset($arr["cat_img"]) && $arr["cat_img"] != "")
print("<a href=\"viewoffers.php?category=$arr[cat_id]\"><img border=\"0\" src=\"$pic_base_url/cats/" . $arr["cat_img"] . "\" alt=\"" . $arr["cat_name"] . "\" title=\"" . $arr["cat_name"] . "\" /></a>");
else
print($arr["cat_name"]);
print("</a>");
}
else
print("-");
print("</td>\n");
print("<td align=left><a href=offers.php?id=$arr[id]><b>$arr[name]</b></a>$zedit</td>" .
"<td align=center >$arr[added]</td><td align=center >$comment</td>$addedby<td align=center><a href=votesview.php?offerid=$arr[id]>$zvote<br /><a href=offers.php?action=vote&voteid=$arr[id]>Голосовать</td></tr>\n");
}

//print("</table>\n");
print("<tr><td class=\"index\" colspan=\"12\">");
print($pagerbottom);
print("</td></tr>");

}

//end_main_frame();
print("</table>");
stdfoot();

?>