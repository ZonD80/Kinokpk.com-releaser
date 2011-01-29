<?php

//Блок "Последние комментарии к торрентам"

global $REL_LANG, $REL_CACHE, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}

$content .= "<small><table cellpadding=\"2\" cellspacing=\"0\"><tr>";
$content .= "<td align=\"center\" class=\"colhead\">Название</td><td align=\"center\" class=\"colhead\">Сообщил(а)</td></tr>";

$comarray = $REL_CACHE->get('block-comments','query');
if ($comarray===false) {
	$res = sql_query("SELECT comments.id, comments.toid AS torrent, torrents.name, comments.user, users.class, users.username FROM comments LEFT JOIN torrents ON comments.torrent = torrents.id LEFT JOIN users ON comments.user = users.id WHERE comments.type='' ORDER BY comments.id DESC LIMIT 5");
	$comarray=array();
	while( $row = mysql_fetch_assoc($res) ) {
		$comarray[] = $row;
	}
	$REL_CACHE->set('block-comments','query',$comarray);

}

if (!$comarray) $content.='<tr><td>'.$REL_LANG->say_by_key('no_comments').'</td></tr>'; else {
	foreach ($comarray as $row) {
		$content .= "<tr>";
		$content .= "<td align=\"left\"><a href=\"".$REL_SEO->make_link('details','id',$row['torrent'],'name',translit($row['name']))."#comm$row[id]\"><b>".substr($row['name'],0,50).'...'."</b></a></td>";
		$content .= "<td align=\"left\"><a href=\"".$REL_SEO->make_link('userdetails','id',$row['user'],'username',translit($row['username']))."\">".get_user_class_color($row["class"],$row["username"])."</a>";
		$content .= "</td></tr>";
	}
}
$content .= "</table></small>";
?>