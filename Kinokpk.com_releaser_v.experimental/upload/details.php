<?php
/**
 * Release details
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");

INIT();
define("NO_WYSIWYG",true);

if (! is_valid_id ( $_GET ['id'] ))
$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
$id = ( int ) $_GET ["id"];

$res = $REL_DB->query ("SELECT torrents.category, torrents.free, torrents.ratingsum, torrents.descr, torrents.seeders, torrents.leechers, torrents.banned, torrents.info_hash, torrents.tiger_hash, torrents.filename, torrents.last_action AS lastseed, torrents.name, torrents.owner, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.id, torrents.ismulti, torrents.numfiles, torrents.images, torrents.tags, torrents.moderatedby, torrents.freefor, (SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, (SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, users.username, users.ratingsum AS userrating, users.class, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage,".($CURUSER?" IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']}))":' IF((torrents.relgroup=0) OR (relgroups.private=0),1,0)')." AS relgroup_allowed FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN relgroups ON torrents.relgroup=relgroups.id WHERE torrents.id = $id" );
$row = mysql_fetch_array ( $res );
$owned = $moderator = 0;
if (get_privilege('edit_releases',false))
$owned = $moderator = 1;
elseif ($CURUSER ["id"] == $row ["owner"])
$owned = 1;

if (! $row || ($row ["banned"] && ! $moderator))
$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_torrent_with_such_id') );
else {

		if ($row['rgid']) $rgcontent = "<a href=\"".$REL_SEO->make_link('relgroups','id',$row['rgid'],'name',translit($row['rgname']))."\">".($row['rgimage']?"<img style=\"border:none;\" title=\"{$REL_LANG->_('Release of group %s',$row['rgname'])}\" src=\"{$row['rgimage']}\"/>":$REL_LANG->_('Release of group %s',$row['rgname']))."</a>&nbsp;";

		if ((!get_privilege('access_to_private_relgroups',false)) && !$row['relgroup_allowed'] && $row['rgid']) $REL_TPL->stderr($REL_LANG->say_by_key('error'),sprintf($REL_LANG->say_by_key('private_release_access_denied'),$rgcontent));

		$REL_TPL->stdhead( $row ["name"]." - {$REL_LANG->say_by_key('torrent_details')}" );
$REL_TPL->begin_frame($REL_LANG->say_by_key('torrent_details'));
		$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		print("<div id=\"tabs\"><ul>
	<li class=\"tab1\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->_('Description')}</span></a></li>
	<li nowrap=\"\" class=\"tab2\"><a href=\"".$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('torrent_info')}</span></a></li>
	<li nowrap=\"\" class=\"tab2\"><a href=\"".$REL_SEO->make_link('exportrelease','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('exportrelease_mname')}</span></a></li>
	</ul></div>\n");
		print ( "<table style=\"width:100%; border:1px; float:left;\" cellspacing=\"0\" cellpadding=\"5\">\n" );
		$url = $REL_SEO->make_link('edit','id',$row ["id"],'name',translit($row['name']));
		if (isset ( $_GET ["returnto"] )) {
			$addthis = "&amp;returnto=" . urlencode ( $_GET ["returnto"] );
			$url .= $addthis;
			$keepget .= $addthis;
		}
		$editlink = "a href=\"$url\" class=\"sublink\"";
		//present
		if ($row ['freefor']) {
			$row ['freefor'] = explode ( ',', $row ['freefor'] );
			$thisispresent = (in_array ( $CURUSER ['id'], $row ['freefor'] ) ? '<img src="pic/presents/present.gif" title="' . $REL_LANG->say_by_key('present_for_you') . '" alt="' . $REL_LANG->say_by_key('present_for_you') . '"/>&nbsp;' : '');
		} else
		unset ( $thisispresent );
		//present end
		if ($owned) {
			$s = "<br />";
			$s .= "<$editlink>[" . $REL_LANG->say_by_key('edit') . "]</a>";
		}
		// AddThis button language
		$button_lang = substr((string)$_COOKIE['lang'],0,2);
		if (!$button_lang) $button_lang = $REL_CONFIG['default_language'];
		elseif ($button_lang=='ua') $button_lang = 'uk';
		
		tr ($rgcontent."<br/>".$REL_LANG->say_by_key('name') . '<br /><b>' . $REL_LANG->say_by_key('download') . '</b><br/>'.($CURUSER?reportarea ( $id, 'torrents' ):'') . $s, "<h1>$thisispresent" . (($row ['free']) ? "<img src=\"pic/freedownload.gif\" title=\"" . $REL_LANG->say_by_key('golden') . "\" alt=\"" . $REL_LANG->say_by_key('golden') . "\"/>&nbsp;" : '') . "<a class=\"index\" href=\"".$REL_SEO->make_link("download","id",$id,"name",translit($row['name']))."\" onclick=\"javascript:$.facebox({ajax:'".$REL_SEO->make_link("download","id",$id,"name",translit($row['name']))."'}); return false;\">{$REL_LANG->_("Download")} " . $row ["name"] . "</a>&nbsp;<a href=\"".$REL_SEO->make_link("bookmark","torrent",$row['id'],"name",translit($row['name']))."\"><img border=\"0\" src=\"pic/bookmark.gif\" alt=\"".$REL_LANG->say_by_key('bookmark_this')."\" title=\"".$REL_LANG->say_by_key('bookmark_this')."\" /></a></h1>
<div style=\"text-align:right;\"><!-- AddThis Button BEGIN -->
<a class=\"addthis_button\" href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4b9d38957e4015b5\"><img src=\"http://s7.addthis.com/static/btn/v2/lg-share-$button_lang.gif\" width=\"125\" height=\"16\" alt=\"{$REL_LANG->say_by_key('bookmark_this')}\" style=\"border:0\"/></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4beeed1d66d87035\"></script>
<!-- AddThis Button END -->
	</div>", 1, 1, "10%" );

		// make main category and childs
		$cats = assoc_full_cats();
		$OUT .= "<strong>{$REL_LANG->say_by_key('type')}:</strong> ". get_full_position_str ( $cats, $row ['category'] ) . "<br/>";
		$OUT .= "<strong>{$REL_LANG->_('Tags')}:</strong> ". str_replace(',',', ',$row['tags']) . "<br/>";
		
		$OUT .= "<strong>{$REL_LANG->say_by_key('info_hash')}:</strong> BTIH:<a title=\"Google it!\" href=\"http://www.google.com/search?q=".$row ["info_hash"]."\">{$row["info_hash"]}</a>".($row['tiger_hash']?", TTH:<a title=\"Google it!\" href=\"http://www.google.com/search?q=".$row ["tiger_hash"]."\">{$row["tiger_hash"]}</a>":'')."<b>,&nbsp;".$REL_LANG->say_by_key('number_release')."</b>&nbsp;&nbsp;<font style=\"color:red\">".$id."</font><br/>";
		if ($CURUSER)
		$OUT .= "<strong>{$REL_LANG->say_by_key('check')}:</strong>".'<div id="checkfield">' . ($row ['moderatedby'] ? $REL_LANG->say_by_key('checked_by') . ' <a href="'.$REL_SEO->make_link('userdetails','id',$row ['moderatedby'],'username',translit($row['modname'])).'">' . get_user_class_color ( $row ['modclass'], $row ['modname'] ) . '</a> ' . ((get_privilege('edit_releases',false)) ? '<a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id', $id).'">' . $REL_LANG->say_by_key('uncheck') . '</a>' : '') : $REL_LANG->say_by_key('not_yet_checked') . ((get_privilege('edit_releases',false)) ? ' <a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id',$id).'">' . $REL_LANG->say_by_key('check') . '</a>' : '')) . '</div><br/>';
		$spbegin = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">{$REL_LANG->say_by_key('screens')} ({$REL_LANG->say_by_key('view')})</div><div class=\"sp-body\"><textarea>";
		$spend = "</textarea></div></div>";

		if ($CURUSER) {
			if (! $row ["visible"])
			$OUT .= "<strong>{$REL_LANG->say_by_key('visible')}:</strong> <b>" . $REL_LANG->say_by_key('no') . "</b> ({$REL_LANG->_('Does not visible on main page')})<br/>";
			if ($row ['filename'] != 'nofile')
			$OUT .= "<strong>{$REL_LANG->say_by_key('seeder')}:</strong> {$REL_LANG->say_by_key('seeder_last_seen')} " . get_elapsed_time ( $row ["lastseed"] ) . " " . $REL_LANG->say_by_key('ago')."<br/>";

			$OUT .= "<strong>{$REL_LANG->say_by_key('size')}:</strong> ". mksize ( $row ["size"] ) . " (" . number_format ( $row ["size"] ) . " " . $REL_LANG->say_by_key('bytes') . ")"."<br/>";

			$OUT .= "<strong>{$REL_LANG->say_by_key('added')}:</strong> ". mkprettytime ( $row ["added"] )."<br/>";
			$OUT .= "<strong>{$REL_LANG->say_by_key('views')}:</strong> ". $row ["views"]."<br/>";

			if ($row ['filename'] != 'nofile') {
				$OUT .= "<strong>{$REL_LANG->say_by_key('snatched')}:</strong> {$row ["hits"]} " . $REL_LANG->say_by_key('times')."<br/>";
			}
			$keepget = "";
			$uprow = (isset ( $row ["username"] ) ? ("<a href='{$REL_SEO->make_link('userdetails','id',$row ["owner"],'name',$row['username'])}'>" . get_user_class_color ( $row ['class'], $row ["username"] ) . "</a>") : "<i>{$REL_LANG->_('Anonymous')}</i>");


			$OUT .= "<strong>{$REL_LANG->_('Uploader')}:</strong>  $uprow $spacer ". ratearea ( $row ['userrating'], $row ['owner'], 'users' ,$CURUSER['id'])."<br/>";
			$OUT .= "<strong>{$REL_LANG->say_by_key('vote')}:</strong> $spacer".ratearea ( $row ['ratingsum'], $id, 'torrents',(($row['owner']==$CURUSER['id'])?$id:0) )."<br/>";
		}

		if ($row ['images']) {
			$images = explode ( ',', $row ['images'] );

			$k = 0;
			foreach ( $images as $img ) {
				$k ++;

					$img = "<a href=\"$img\" onclick=\"javascript: $.facebox({image:'$img'}); return false;\"><img  title='{$REL_LANG->_('Image for %s',$row ["name"])} ({$REL_LANG->_('Click to view full-size image')})' width=\"180\" class=\"corners\" src=\"$img\" /></a>&nbsp;";
				if ($k <= 1)
				$imgcontent .= $img;
				else
				$imgspoiler .= $img;

			}
		}

		print ( '<tr><td colspan="2"><table width="100%"><tr><td style="vertical-align: top;">' . ($imgcontent ? $imgcontent : '<img src="pic/noimage.gif"/>') .  '</td><td style="vertical-align: top; text-align:left; width:100%">' .$OUT.'<hr/>'. format_comment ( $row ['descr'] ) .'</td></tr></table></td></tr>' );

		if (! $CURUSER) {
			print ( "</table>\n" );
$REL_TPL->end_frame();
			$REL_TPL->stdfoot();
			die ();
		}
		if ($moderator)
		tr ( $REL_LANG->say_by_key('banned'), (! $row ["banned"] ? $REL_LANG->say_by_key('no') : $REL_LANG->say_by_key('yes')) );

		if ($row["ismulti"]) {
			if (!$_GET["filelist"])
			tr($REL_LANG->say_by_key('files')."<br /><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']),'filelist',1)."$keepget#filelist\" class=\"sublink\">[".$REL_LANG->say_by_key('open_list')."]</a>", $row["numfiles"] . " ".$REL_LANG->say_by_key('files_l'), 1);
			else {
				tr($REL_LANG->say_by_key('files'), $row["numfiles"] . " ".$REL_LANG->say_by_key('files_l'), 1);

				$s = "<table class=main border=\"1\" cellspacing=0 cellpadding=\"5\">\n";

				$subres = $REL_DB->query("SELECT * FROM files WHERE torrent = $id ORDER BY id");
				$s.="<tr><td class=colhead>".$REL_LANG->say_by_key('path')."</td><td class=colhead align=right>".$REL_LANG->say_by_key('size')."</td></tr>\n";
				while ($subrow = mysql_fetch_array($subres)) {
					$s .= "<tr><td>" .$subrow["filename"] .
                            			"</td><td align=\"right\">" . mksize($subrow["size"]) . "</td></tr>\n";
				}

				$s .= "</table>\n";
				tr("<a name=\"filelist\">".$REL_LANG->say_by_key('file_list')."</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[".$REL_LANG->say_by_key('close_list')."]</a>", $s, 1);
			}
		}
		if ($row ['filename'] != 'nofile')
		tr ( $REL_LANG->say_by_key('downloading') . "<br /><a href=\"".$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name']),'dllist',1)."$keepget#seeders\" class=\"sublink\">[" . $REL_LANG->say_by_key('open_list') . "]</a>", $row ["seeders"] . " " . $REL_LANG->say_by_key('seeders_l') . ", " . $row ["leechers"] . " " . $REL_LANG->say_by_key('leechers_l') . " = " . ($row ["seeders"] + $row ["leechers"]) . " " . $REL_LANG->say_by_key('peers_l').'<br/><small>'.$REL_LANG->_('If it is multitracker release, may be we do not recevied peers from remote trackers yet').'. <a href="'.$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name'])).'">'.$REL_LANG->_('View detailed tracker data').'</a></small>', 1 );

	tr ( $REL_LANG->say_by_key('screens'), $imgspoiler, 1 );














                  
    $csto=array(" - ","%","&","?","\"","'","*","$","^","#","@","!",">","<","(","!)","=","+","/","|");
    $name = strlen($row["name"])>8?(substr($row["name"],0,8).""):$row["name"];
    $name = preg_replace("#\([0-9]{4}\)#is","",preg_replace("#\[(.+?)\]#is","",$name ));
    $name = str_replace($csto, " ", preg_replace("# ([a-zA-Z0-9]{1,5})Rip#is","",$name));
    $name = preg_replace("#\([0-9]{1,4}.+[0-9]{1,4}\)#is","",preg_replace("#by (.+?)$#is","",$name));
    $name = trim(sqlwildcardesc(htmlspecialchars(preg_replace("#[\.,\\/\?\(\)\!\`\~]#is","",$name))));
    $name = str_replace(" ","%",$name);

    $sql = $REL_DB->query("SELECT name, id FROM torrents WHERE name LIKE ('%".$name."%')  AND id <>'".$id."' ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__,__LINE__);
    
    $num_p=0;
   $ono=""; 

 while($t = mysql_fetch_array($sql)) {    
      if (!empty($ono))
    $ono.="<br>";     
 $ono.="<a href=\"{$REL_SEO->make_link('details','id',$t['id'],'name',translit($t['name']))}\">{$t['name']}</a>";

++$num_p;
}
 if ($num_p<>0)
tr($REL_LANG->_('Related releases'), $ono,1);

print ( '<tr><td colspan="2" align="center"><a href="'.$REL_SEO->make_link('present','type','torrent','to',$id).'">' . $REL_LANG->say_by_key('present_to_friend') . '</a></td></tr>' );


		?>
<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;
var switched = 0;

function ajaxcheck() {

      (function($){
      if ($) no_ajax = false;
   $("#checkfield").empty();
   $("#checkfield").append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    $.get("<?php print $REL_SEO->make_link('takeedit'); ?>", { ajax: 1, checkonly: "", id: <?php print $id; ?> }, function(data){
   $("#checkfield").empty();
   $("#checkfield").append(data);
});
})(jQuery);

return no_ajax;

}

//]]>
</script>
		<?php
		print ( "</table>\n" );

		print ( "<div align=\"center\"><a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','pre','from',$id)."'; return false;\">
<< {$REL_LANG->_('Previous release')}</a>&nbsp;
<a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','pre','from',$id,'cat',$row ['category'])."'; return false;\">[{$REL_LANG->_('from this category')}]</a>
&nbsp; | &nbsp;
<a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','next','from',$id,'cat',$row ['category'])."'; return false;\">[{$REL_LANG->_('from this category')}]</a>&nbsp;
<a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','next','from',$id)."'; return false;\">
{$REL_LANG->_('Next release')} >></a><br />
<a href=\"".$REL_SEO->make_link('browse')."\">{$REL_LANG->_('View all releases')}</a>
&nbsp; | &nbsp;");
        $linkar[] = 'browse';
        foreach (explode(',',$row['category']) as $sc) {
            $linkar[] = 'cat';
            $linkar[] = $sc;
        }
print ("<a href=\"".$REL_SEO->make_link($linkar)."\">{$REL_LANG->_('View all releases of this category')}</a></div>" );



	
	$subres = $REL_DB->query ( "SELECT SUM(1) FROM comments WHERE toid = $id AND type='rel'" );
	$subrow = mysql_fetch_array ( $subres );
	$count = $subrow [0];

	if (! $count) {

		print ('<div id="newcomment_placeholder">'. "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
		print ( "<tr><td class=\"colhead\" align=\"left\" colspan=\"2\">" );
		print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: {$REL_LANG->_('Comments list')}</div>" );
		print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."#comments\" class=\"altlink_white\">{$REL_LANG->_('Add comment (%s)',$REL_LANG->_('Release'))}</a></div>" );
		print ( "</td></tr><tr><td align=\"center\">" );
		print ( "{$REL_LANG->_('No comments')}. <a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."#comments\">{$REL_LANG->_('Add new comment')}</a>" );
		print ( "</td></tr></table><br /></div>");

	} else {
        $subres = $REL_DB->query ( "SELECT c.id, c.type, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.info, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN sessions ON c.user=sessions.uid LEFT JOIN users AS e ON c.editedby = e.id WHERE c.toid = " . "$id AND c.type='rel' GROUP BY c.id ORDER BY c.id ASC" );
		$allrows = prepare_for_commenttable($subres,$row['name'],$REL_SEO->make_link('details','id',$id,'name',translit($row['name'])));

			print ( "<div id=\"pager_scrollbox\"><table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
			print ( "<tr><td class=\"colhead\" align=\"center\" >" );
			print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: {$REL_LANG->_('Comments list')}</div>" );
			print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."#comments\" class=\"altlink_white\">{$REL_LANG->_('Add comment (%s)',$REL_LANG->_('Release'))}</a></div>" );
			print ( "</td></tr>" );

			print ( "<tr><td>" );
			commenttable ( $allrows );
			print ( "</td></tr>" );
			print ( "</table></div>" );
	}




	$REL_TPL->assignByRef('to_id',$id);
	$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $id, 'relcomments' ));
	$REL_TPL->assign('textbbcode',textbbcode('text'));
	$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('Release'));
	$FORM_TYPE = 'rel';
	$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
	$REL_TPL->display('commenttable_form.tpl');




	$REL_DB->query ( "UPDATE torrents SET views = views + 1 WHERE id = $id" );
	set_visited('torrents',$id);
$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
}
?>
