<?php
global  $REL_LANG, $fprefix, $REL_CONFIG, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}

forumconn();
//connection opened


$buffer = "";
$result = sql_query("SELECT tid, posts, title, icon_id, description, starter_name, last_poster_name, views FROM ".$fprefix."topics WHERE (forum_id NOT IN (1)) ORDER BY last_post DESC LIMIT 0, 5");
while (list($tid, $posts, $title, $icon_id, $description, $starter_name, $last_poster_name, $views) = mysql_fetch_array($result)) {
	$post_text = ($description) ? "".$title." - ".$description."" : $title;
	$buffer .= "<tr class=\"bgcolor1\"><td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>$icon</td><td width=\"100%\"><a href=\"ipb/index.php?showtopic=$tid&view=getnewpost\" title=\"$post_text\">".$title."</a></td></tr></table>"
	."<td align=\"center\"><a href=\"userdetails.php?id=$starter_name\">$starter_name</a></td>"
	."<td align=\"center\">$views</td><td align=\"center\">$posts</td>"
	."<td align=\"center\"><a href=\"index.php?name=Account&op=userinfo&user_name=$last_poster_name\">$last_poster_name</a></td></tr>";
}
$content .= "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\" class=\"sort\" id=\"sort_id\"><tr><td align=\"center\" class=\"colhead\">".$REL_LANG->say_by_key('subject')."</td><td align=\"center\" class=\"colhead\">".$REL_LANG->say_by_key('autor')."</td><td align=\"center\" class=\"colhead\">".$REL_LANG->say_by_key('views')."</td><td align=\"center\" class=\"colhead\">".$REL_LANG->say_by_key('replies')."</td><td align=\"center\" class=\"colhead\">".$REL_LANG->say_by_key('last_post')."</td></tr>$buffer</table></br>";

// closing IPB DB connection
relconn();

?>