<?php

//Блок "Последние комментарии к торрентам"

global $tracker_lang, $CACHE;
if (!defined('BLOCK_FILE')) {
	Header("Location: ../index.php");
	exit;
}
$blocktitle = "Последние комментарии к релизам";

$content .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\"><tr>";
$content .= "<td align=\"center\" class=\"colhead\">Название</td><td align=\"center\" class=\"colhead\">Когда</td><td align=\"center\" class=\"colhead\">Сообщил(а)</td></tr>";

$comarray = $CACHE->get('block-comments','query');
if ($comarray===false) {
	$res = sql_query("SELECT comments.added AS dcom, comments.id, comments.torrent, torrents.name, comments.user, users.class, users.username FROM comments LEFT JOIN torrents ON comments.torrent = torrents.id LEFT JOIN users ON comments.user = users.id ORDER BY comments.id DESC LIMIT 5");
	$comarray=array();
	while( $row = mysql_fetch_assoc($res) ) {
		$comarray[] = $row;
	}
	$CACHE->set('block-comments','query',$comarray);

}

if (!$comarray) $content.='<tr><td>'.$tracker_lang['no_comments'].'</td></tr>'; else {
	foreach ($comarray as $row) {
		$content .= "<tr>";
		$content .= "<td align=\"left\"><a href=\"details.php?id=$row[torrent]&amp;viewcomm=$row[id]#comm$row[id]\"><b>$row[name]</b></a></td>";
		$content .= "<td align=\"left\">".mkprettytime($row['dcom'])."</td>";
		$content .= "<td align=\"left\"><a href=\"userdetails.php?id=$row[user]\">".get_user_class_color($row["class"],$row["username"])."</a>";
		$content .= "</td></tr>";
	}
}
$content .= "</table>";
?>