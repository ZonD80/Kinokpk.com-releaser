<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}
include_once("forums/conf_global.php");
$prefix_ipb = $INFO['sql_tbl_prefix'];

$content .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\"><tr><td align=\"center\" class=\"colhead\">Тема</td><td align=\"center\" class=\"colhead\">Автор</td><td align=\"center\" class=\"colhead\">Просмотров</td><td align=\"center\" class=\"colhead\">Ответов</td><td align=\"center\" class=\"colhead\">Посл. сообщение</td></tr>";

$topics = sql_query("SELECT * FROM ".$prefix_ipb."topics WHERE approved = 1 ORDER BY last_post DESC LIMIT 5");
while ($topic = mysql_fetch_array($topics)) {
	$topic_id = $topic["tid"];
	$forum_id = $topic["forum_id"];
	$topic_subject = $topic["title"];
	$topic_starter_uid = $topic["starter_id"];
	$topic_starter_name = $topic["starter_name"];
	$topic_started = $topic["start_date"];
//	$topic_firstpost = $topic["firstpost"];
//	$lastpost = $topic["lastpost"];
	$lastposter = $topic["last_poster_name"];
	$lastposter_uid = $topic["last_poster_id"];
	$topic_views = $topic["views"];
	$topic_replies = $topic["posts"];
if ($forum_id != 24){
	$content .= "<tr><td><a href=\"/forums/index.php?showtopic=$topic_id&hl=\">$topic_subject</a></td><td align=\"center\"><a href=\"/forums/index.php?showuser=$topic_starter_uid\" title=\"".get_date_time($topic_started)."\">$topic_starter_name</a></td><td align=\"center\">$topic_views</td><td align=\"center\">$topic_replies</td><td align=\"center\"><a href=\"/forums/index.php?showtopic=$topic_id&view=getlastpost\" title=\"".get_date_time($lastpost)."\">$lastposter</a></td></tr>";
}
}

$content .= "</table>";

$blocktitle = "Форум <font size=\"-2\"> - [<a class=\"altlink\" href=\"/forums/index.php?act=Search&CODE=getnew\">Новые сообщения</a>]</font> <font size=\"-2\">[<a class=\"altlink\" href=\"/forums/index.php?act=Search&f=\">Поиск</a>]</font>";

?>