<?php
/**
 * Requests viewer and editor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");

INIT();

loggedinorreturn();

if ($_GET["delreq"])
{
	if (get_privilege('requests_operation',false)) {
		if (empty($_GET["delreq"]))
		stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_fileds_blank'));
		sql_query("DELETE FROM requests WHERE id IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ")");
		sql_query("DELETE FROM comments WHERE toid IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ") AND type='req'");
		sql_query("DELETE FROM notifs WHERE type='reqcomments' AND checkid IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ")");
		sql_query("DELETE FROM addedrequests WHERE requestid IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ")");
		sql_query("DELETE FROM notifs WHERE checkid IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ") AND type='reqcomments'");
		$REL_CACHE->clearGroupCache('block-req');
		stderr($REL_LANG->say_by_key('success'), "Запрос успешно удален.<br /><a href=\"".$REL_SEO->make_link('viewrequests')."\">К списку запросов</a>");
	}
	else
	stderr($REL_LANG->say_by_key('error'), "У вас нет прав для удаления запросов.");
}

if ((!is_valid_id($_GET['category'])) && ($_GET['category']<>0)) stderr ($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
$REL_TPL->stdhead($REL_LANG->say_by_key('requests_section'));

$categ = (int)$_GET["category"];
$requestorid = (int)$_GET["requestorid"];
$sort = htmlspecialchars((string)$_GET["sort"]);
$search = (string) $_GET["search"];
$filter = htmlspecialchars($_GET["filter"]);

print("<table class=\"embedded\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" >");
print("<tr><td class=\"colhead\" align=\"center\" colspan=\"15\">Секция запросов</td></tr>");
print("<tr><td class=\"index\" colspan=\"15\">");


//print("<h1>".$REL_LANG->say_by_key('requests_section')."</h1>\n");
print("<p><a href=\"".$REL_SEO->make_link('requests','action','new')."\">".$REL_LANG->say_by_key('make_request')."</a></p>\n");
print("<p><a href=\"".$REL_SEO->make_link('viewrequests','requestorid',$CURUSER['id'])."\">".$REL_LANG->say_by_key('show_my_requests')."</a></p>\n");
/////***Писал поздно думать не мог - by Juster***/////
print("<p><a href=". $_SERVER[PHP_SELF] ."?".(isset($categ)?"category={$categ}&":"")."sort=" . $sort . "&filter=true>".$REL_LANG->say_by_key('hide_filled')."</a></p>\n");
/////******/////
print("<form method=get action=\"".$REL_SEO->make_link('viewrequests')."\">");
$tree= make_tree();
print gen_select_area('category',$tree,$categ,true);

print("</select>");
print("&nbsp;<input type=\"submit\" align=\"center\" value=\"Изменить\" style=\"height: 22px\">\n");
print("</form>\n<p />");

print("<form method=\"get\" action=\"".$REL_SEO->make_link('viewrequests')."\">");
print("<b>Искать запросы: </b><input type=\"text\" size=\"40\" name=\"search\">");
print("&nbsp;<input type=\"submit\" align=\"center\" value=\"Искать\" style=\"height: 22px\">\n");
print("</form><p></p>");

if ($search)
$query[] = "requests.request LIKE '%" . sqlwildcardesc($search) . "%'";

if ($sort == "votes")
$sortsql = "ORDER BY hits DESC";
elseif ($sort == "request")
$sortsql = "ORDER BY request";
elseif ($sort == "added")
$sortsql = "ORDER BY added DESC";
elseif ($sort == "comm")
$sortsql = "ORDER BY comments DESC";
else
$sortsql = "ORDER BY added DESC";

if ($filter == "true")
$query[] = "requests.filledby = 0";
if ($requestorid <> NULL) {
	if (($categ <> NULL) && ($categ <> 0)) {
		$cats = get_full_childs_ids($tree,$categ);
		if (!$cats) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
		else {
			foreach ($cats as $catid) $catq[] = " FIND_IN_SET($catid,requests.cat) ";

			if ($catq) $catq = "(".implode('OR',$catq).")";
			$query[] = "$catq AND requests.userid = " . sqlesc($requestorid);

		}
	}
	else
	$query[] = "requests.userid = " . sqlesc($requestorid);
} elseif (($categ <> NULL) && ($categ <> 0)) {
	$cats = get_full_childs_ids($tree,$categ);
	if (!$cats) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	else {
		foreach ($cats as $catid) $catq[] = " FIND_IN_SET($catid,requests.cat) ";

		if ($catq) $catq = "(".implode('OR',$catq).")";
		$query[] = $catq;

	}}

	if ($query) $query = 'WHERE '.implode(' AND ',$query);

	$res = sql_query("SELECT SUM(1) FROM requests INNER JOIN categories ON requests.cat = categories.id INNER JOIN users ON requests.userid = users.id $query GROUP BY requests.id") or sqlerr(__FILE__,__LINE__);
	list($count) = mysql_fetch_array($res);

	if (!$count) {
		print("<tr><td class=\"colhead\" align=\"center\" colSpan=\"15\" >Нет запросов</td></tr>");
		print("<tr><td class=\"index\" colspan=\"15\">");
		print("<p>Нет запросов. Желаете <a href=\"".$REL_SEO->make_link('requests','action','new')."\">добавить</a>?</p>");
		print("</td></tr>");
	} else {

		$perpage = 50;

		$limit = "LIMIT 50";

		$res = sql_query("SELECT (SELECT username FROM users WHERE id = filledby) AS filledname, (SELECT class FROM users WHERE id = filledby) AS filledclass, users.class, users.ratingsum, users.username, users.warned, users.donor, users.enabled, requests.filled, requests.filledby, requests.id, requests.userid, requests.request, requests.added, requests.hits, requests.comments, categories.id AS cat_id FROM requests INNER JOIN categories ON requests.cat = categories.id INNER JOIN users ON requests.userid = users.id $query $filtersql $limit") or sqlerr(__FILE__, __LINE__);
		$num = mysql_num_rows($res);

		print("<form method=get OnSubmit=\"return confirm('Вы уверены?')\" action=\"".$REL_SEO->make_link('viewrequests')."\">\n");
		print("<tr><td class=\"colhead\" align=\"center\">".$REL_LANG->say_by_key('type')."</td><td class=colhead align=left><a href=\"".$REL_SEO->make_link('viewrequests','category',$categ,'filter',$filter,'sort','request')."\" class=altlink_white>".$REL_LANG->say_by_key('request')."</a></td><td class=colhead align=center width=150><a href=\"".$REL_SEO->make_link('viewrequests','category',$categ,'filter',$filter,'sort','added')."\" class=altlink_white>".$REL_LANG->say_by_key('added')."</a></td><td class=colhead align=center>".$REL_LANG->say_by_key('requester')."</td><td class=colhead align=center>".$REL_LANG->say_by_key('filled')."</td><td class=colhead align=center>".$REL_LANG->say_by_key('filled_by')."</td><td class=colhead align=center><a href=" . $_SERVER[PHP_SELF] . "?category=" . $categ . "&filter=" . $filter . "&sort=votes class=altlink_white>".$REL_LANG->say_by_key('votes')."</a></td><td class=colhead align=center><a href=" . $_SERVER[PHP_SELF] . "?category=" . $categ . "&filter=" . $filter . "&sort=comm class=altlink_white>".$REL_LANG->say_by_key('comments')."</a></td>" . (get_privilege('requests_operation',false)?"<td class=colhead align=center>".$REL_LANG->say_by_key('delete')."</td>": "") . "</tr>\n");
		for ($i = 0; $i < $num; ++$i)
		{

			$arr = mysql_fetch_assoc($res);

			$ratio = ratearea($arr['ratingsum'],$arr['userid'],'users',$CURUSER['id']);

			if ($arr['filledname'])
			$filledby = $arr['filledname'];
			else
			$filledby = " ";
			$user = $arr;
			$user['id'] = $user['userid'];
			$addedby = "<td style='padding: 0px' align=center nowrap>".make_user_link($user)." $ratio</td>\n";
			$filled = $arr[filled];
			if ($filled != '')
			$filled = "<a href=$arr[filled]><font color=green><b>".$REL_LANG->say_by_key('yes')."</b></font></a>\n";
			else
			$filled = "<a href=\"".$REL_SEO->make_link('requests','id',$arr['id'])."\"><font color=red><b>".$REL_LANG->say_by_key('no')."</b></font></a>\n";

			if ($arr[comments] == 0)
			$comment = "0";
			else
			$comment = "<a href=\"".$REL_SEO->make_link('requests','id',$arr['id'])."#startcomments\"><b>$arr[comments]</b></a>";
			print("<tr><td style='padding: 0px'><small>".get_cur_position_str($tree,$arr['cat_id'],'viewrequests')."</small></td>\n<td align=left><a href=\"".$REL_SEO->make_link('requests','id',$arr['id'])."\"><b>$arr[request]</b></a>".(get_privilege('requests_operation',false) ? "<a href=\"".$REL_SEO->make_link('requests','action','edit','id',$arr['id'])."\" class=\"sublink\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$REL_LANG->say_by_key('edit')."\" title=\"".$REL_LANG->say_by_key('edit')."\" /></a>" : "")."</td>\n<td align=center>".mkprettytime($arr[added])."</td>$addedby<td align=center>$filled</td>\n<td align=center><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['filledby'],'username',translit($arr["filledname"]))."\"><b>".get_user_class_color($arr["filledclass"],$arr["filledname"])."</b></a></td>\n<td align=center><a href=\"".$REL_SEO->make_link('votesview','requestid',$arr['id'])."\"><b>$arr[hits]</b></a></td>\n<td align=center>$comment</td>" .  (get_privilege('requests_operation',false)?"<td align=center><input type=\"checkbox\" name=\"delreq[]\" value=\"" . $arr[id] . "\" /></td>": "") . "</tr>\n");
		}

		if (get_privilege('requests_operation',false)) {
			print("<tr><td class=\"index\" align=\"right\" colspan=\"15\">");
			print("<input type=submit value=\"".$REL_LANG->say_by_key('delete')."\">");
			print("</form>");
			print("</td></tr>");
		}

	}


	print("</table>");
	$REL_TPL->stdfoot();
	//die;

	?>