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
dbconn();
loggedinorreturn();
getlang('pages');

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) $ajax=1; else $ajax=0;

if (isset($_GET['add'])) {

	if (!$CURUSER) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

	stdhead($tracker_lang['adding_page']);
	begin_frame($tracker_lang['adding_page']);
	print ("<table width=\"100%\"><form action=\"pages.php?saveadd\" method=\"post\"><tr><td class=\"colhead\">{$tracker_lang['page_name']}</td></tr>
     <tr><td><input type=\"text\" size=\"80\" name=\"name\"></td></tr>
     <tr><td class=\"colhead\">{$tracker_lang['tags']}</td></tr>
     <tr><td><input type=\"text\" size=\"80\" name=\"tags\"><br />{$tracker_lang['tags_notice']}</td></tr>
     <tr><td class=\"colhead\">{$tracker_lang['page_content']}</td></tr>
     <tr><td>".textbbcode("content")."</td></tr>");
	if (get_user_class() >= UC_MODERATOR) {
		print ("<tr><td class=\"colhead\">{$tracker_lang['news_poster']} - {$tracker_lang['from_system']} <input type=\"checkbox\" name=\"system\" value=\"1\"></td></tr>");
		print ("<tr><td class=\"colhead\">{$tracker_lang['indexed']} <input type=\"checkbox\" name=\"indexed\" value=\"1\"></td></tr>");
	}
	print("<tr><td><input type=\"submit\" value=\"{$tracker_lang['adding_page']}\"></form></td></tr></table>");
	end_frame();

} elseif (isset($_GET['saveadd'])) {
	if (!$CURUSER) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

	$reqfields = array($_POST['name'],$_POST['tags'],$_POST['content']);
	foreach ($reqfields as $field) if (empty($field)) stderr($tracker_lang['error'],$tracker_lang['missing_form_data']);
	if ((get_user_class() >= UC_MODERATOR) && $_POST['indexed']) $indexed=1; else $indexed=0;
	if ((get_user_class() >= UC_MODERATOR) && $_POST['system']) $system=0; else $system=$CURUSER['id'];
	sql_query("INSERT INTO pages (owner,added,name,searchwords,content,indexed) VALUES (".implode(",",array_map("sqlesc",array($system,time(),$_POST['name'],$_POST['tags'],$_POST['content'],$indexed))).")");
	$id = mysql_insert_id();
	$CACHE->clearGroupCache('pages');
	stderr($tracker_lang['success'],$tracker_lang['adding_page'].' <a href="pages.php?id='.$id.'">pages.php?id='.$id.'</a>','success');
}

elseif (!is_valid_id($_GET['id'])) {

	$_GET['q'] = (string) $_GET['q'];

	if ($ajax) $_GET['q'] = base64_decode($_GET['q']);
	$q = sqlwildcardesc($_GET['q']);
	if (!empty($_GET['q'])) { $where = "WHERE name LIKE '%" . $q . "%' OR searchwords LIKE '%" . $q . "%' "; $search=1; }

	if ($ajax) header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
	$addparam='q='.urlencode($_GET['q']).'&';
	$totalpages = get_row_count("pages");
	list($pagertop, $pagerbottom, $limit) = browsepager($CACHEARRAY['torrentsperpage'], $totalpages, "pages.php?".$addparam , "#pages-table");

	$row = sql_query('SELECT pages.id,pages.name,pages.owner,pages.added,pages.indexed,users.username,users.class FROM pages LEFT JOIN users ON pages.owner=users.id '.$where.'ORDER BY added DESC '.$limit);
	if (!$ajax) { stdhead($tracker_lang['our_pages']);
	print ('<script language="javascript" type="text/javascript">

var no_ajax = true;

function pageswitcher(page) {

   (function($){
     if ($) no_ajax = false;
   $("#pages-table").empty();
   $("#pages-table").append(\'<div align="center"><img src="pic/loading.gif" border="0"/></div>\');
   $.get("pages.php", { ajax: 1, q: "'.base64_encode($_GET['q']).'", page: page }, function(data){
   $("#pages-table").empty();
   $("#pages-table").append(data);

});
})(jQuery);

window.location.href = window.location.href+"#pages-table";

return no_ajax;
}
</script>');

	begin_frame($tracker_lang['our_pages'].($CURUSER?"&nbsp;<small>[<a href=\"pages.php?add\">{$tracker_lang['adding_page']}</a>]</small>":''));
	}
	if (!$ajax) print('<div align="center"><form action="pages.php" method="get"><input type="text" name="q" value="'.strip_tags($_GET['q']).'"><input type="submit" value="'.$tracker_lang['search'].'"></form></div>');

	while (list($id,$name,$owner,$added,$indexed,$ownername,$ownerclass) = mysql_fetch_array($row)) {
		$s.=("<tr><td class=\"colhead\"><a href=\"pages.php?id=$id\">".makesafe($name)."</a>".(((get_user_class() >= UC_MODERATOR) || ($owner==$CURUSER['id']))?"&nbsp;<small>[<a href=\"pages.php?edit&id=$id\">{$tracker_lang['edit']}</a>]&nbsp[<a href=\"pages.php?delete&id=$id\" onclick=\"return confirm ('{$tracker_lang['delete']}?');\">{$tracker_lang['delete']}</a>]</small>":'')."<div align=\"right\">".(((get_user_class() >= UC_MODERATOR) && $indexed)?$tracker_lang['this_page_is_indexed'].'&nbsp;':'').(($owner!=0)?"<a href=\"userdetails.php?id=$owner\">".get_user_class_color($ownerclass,$ownername)."</a>":$tracker_lang['from_system']).", ".mkprettytime($added,true,true)."</div></td></tr>");
	}
	if (!$s) stdmsg($tracker_lang['error'],($search?$tracker_lang['nothing_found']:$tracker_lang['no_pages'])); else {
		print('<div id="pages-table"><table width="100%">');
		print("<tr><td class=\"index\">");
		print($pagertop);
		print("</td></tr>");
		print ($s);
		print("<tr><td class=\"index\">");
		print($pagerbottom);
		print("</td></tr>");
		print('</table></div>');
	}

	if ($ajax) die();
	end_frame();

} else {
	$id = (int) $_GET['id'];
	$row = sql_query("SELECT pages.id,pages.content,pages.searchwords,pages.name,pages.owner,pages.added,pages.indexed,users.username,users.class FROM pages LEFT JOIN users ON pages.owner=users.id WHERE pages.id=$id");
	$res = mysql_fetch_assoc($row);
	if (!$res) stderr($tracker_lang['error'],$tracker_lang['no_page_with_this_id']);

	$res['name'] = makesafe($res['name']);
	$res['searchwords'] = makesafe($res['searchwords']);

	if (isset($_GET['delete'])) {

		if ((get_user_class() < UC_MODERATOR) && ($res['owner']<>$CURUSER['id'])) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

		sql_query("DELETE FROM pages WHERE id=$id LIMIT 1");
		$CACHE->clearGroupCache('pages');
		stderr($tracker_lang['success'],$tracker_lang['page_deleted'].$tracker_lang['to_list_of_pages'],'success');
	}
	elseif (isset($_GET['edit'])) {
		if ((get_user_class() < UC_MODERATOR) && ($res['owner']<>$CURUSER['id'])) stderr($tracker_lang['error'],$tracker_lang['access_denied']);


		stdhead($tracker_lang['editing_page'].' '.$res['name']);
		begin_frame($tracker_lang['editing_page'].' '.$res['name']);
		print ("<table width=\"100%\"><form action=\"pages.php?saveedit&id=$id\" method=\"post\"><tr><td class=\"colhead\">{$tracker_lang['page_name']}</td></tr>
     <tr><td><input type=\"text\" size=\"80\" name=\"name\" value=\"{$res['name']}\"></td></tr>
     <tr><td class=\"colhead\">{$tracker_lang['tags']}</td></tr>
     <tr><td><input type=\"text\" size=\"80\" name=\"tags\" value=\"{$res['searchwords']}\"><br />{$tracker_lang['tags_notice']}</td></tr>
     <tr><td class=\"colhead\">{$tracker_lang['page_content']}</td></tr>
     <tr><td>".textbbcode("content",$res['content'])."</td></tr>");
		if (get_user_class() >= UC_MODERATOR) {
			print ("<tr><td class=\"colhead\">{$tracker_lang['news_poster']} - {$tracker_lang['from_system']} <input type=\"checkbox\" name=\"system\" value=\"1\"".(!$res['owner']?" checked":'')."></td></tr>");
			print ("<tr><td class=\"colhead\">{$tracker_lang['indexed']} <input type=\"checkbox\" name=\"indexed\" value=\"1\"".($res['indexed']?" checked":'')."></td></tr>");
		}
		print("<tr><td><input type=\"submit\" value=\"{$tracker_lang['edit']}\"></form></td></tr></table>");
		end_frame();

	} elseif (isset($_GET['saveedit'])) {
		if ((get_user_class() < UC_MODERATOR) && ($res['owner']<>$CURUSER['id'])) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

		if ((get_user_class() >= UC_MODERATOR) && $_POST['indexed']) $indexed=1; else $indexed=0;
		if ((get_user_class() >= UC_MODERATOR) && $_POST['system']) $system=0; else $system=$CURUSER['id'];
		$reqfields = array($_POST['name'],$_POST['tags'],$_POST['content']);
		foreach ($reqfields as $field) if (empty($field)) stderr($tracker_lang['error'],$tracker_lang['missing_form_data']);
		sql_query("UPDATE pages SET ".($system?'':"owner=$system,")." name=".sqlesc($_POST['name']).", searchwords=".sqlesc($_POST['tags']).", content=".sqlesc($_POST['content']).", indexed=$indexed, added=".time()." WHERE id=$id");
		$CACHE->clearGroupCache('pages');
		stderr($tracker_lang['success'],$tracker_lang['editing_page'].' <a href="pages.php?id='.$id.'">pages.php?id='.$id.'</a>','success');
	} else {

		stdhead($tracker_lang['page'].' '.$res['name']);
		begin_frame($tracker_lang['page'].' '.$res['name'].(((get_user_class() >= UC_MODERATOR) || ($res['owner']==$CURUSER['id']))?"&nbsp;<small>[<a href=\"pages.php?edit&id=$id\">{$tracker_lang['edit']}</a>]&nbsp[<a href=\"pages.php?delete&id=$id\" onclick=\"return confirm ('{$tracker_lang['delete']}?');\">{$tracker_lang['delete']}</a>]</small>":''));
		print('<div align="right">'.(($res['owner']!=0)?"<a href=\"userdetails.php?id={$res['owner']}\">".get_user_class_color($res['class'],$res['username'])."</a>":$tracker_lang['from_system']).", ".mkprettytime($res['added'],true,true).(((get_user_class() >= UC_MODERATOR) && $res['indexed'])?'&nbsp;'.$tracker_lang['this_page_is_indexed']:'')."</div><hr />");
		print(cleanhtml($res['content']).'<hr />');
		print($tracker_lang['page_tags'].$res['searchwords']);
		end_frame();
	}
}

stdfoot();

?>