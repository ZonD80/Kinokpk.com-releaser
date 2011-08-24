<?php

global $REL_LANG, $REL_CACHE, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}

$content .= "<small><table cellpadding=\"2\" cellspacing=\"0\"><tr>";
$content .= "<td align=\"center\" class=\"colhead\">{$REL_LANG->_('Name')}</td><td align=\"center\" class=\"colhead\">{$REL_LANG->_('Submitted by')}</td></tr>";

$comarray = $REL_CACHE->get('block-comments','query');
if ($comarray===false) {
	$res = sql_query("SELECT comments.id, comments.toid AS torrent, torrents.name, comments.user, users.class, users.username, users.donor, users.warned, users.enabled FROM comments LEFT JOIN torrents ON comments.toid = torrents.id LEFT JOIN users ON comments.user = users.id WHERE comments.type='rel' ORDER BY comments.id DESC LIMIT 5");
	$comarray=array();
	while( $row = mysql_fetch_assoc($res) ) {
		$comarray[] = $row;
	}
	$REL_CACHE->set('block-comments','query',$comarray);

}

if (!$comarray) $content.='<tr><td>'.$REL_LANG->say_by_key('no_comments').'</td></tr>'; else {
	foreach ($comarray as $row) {
		$content .= "<tr>";
		$user = $row;
		$user['id'] = $user['user'];
		$content .= "<td align=\"left\"><a href=\"".$REL_SEO->make_link('details','id',$row['torrent'],'name',translit($row['name']))."#comm$row[id]\"><b>".mb_substr($row['name'],0,50).'...'."</b></a></td>";
		$content .= "<td align=\"left\">".make_user_link($user);
		$content .= "</td></tr>";
	}
}
$content .= "</table></small>";
?>