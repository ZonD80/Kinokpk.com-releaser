<?php
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../index.php");
	exit;
}

global $tracker_lang, $CACHE;

$blocktitle = $tracker_lang['news'].(get_user_class() >= UC_ADMINISTRATOR ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"news.php\"><b>".$tracker_lang['create']."</b></a>]</font>" : "");

$resource = $CACHE->get('block-news', 'query');

if ($resource===false) {

	$resource = array();
	$resourcerow = sql_query("SELECT news.* , COUNT(newscomments.id) AS numcomm FROM news LEFT JOIN newscomments ON news.id = newscomments.news GROUP BY news.id ORDER BY news.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);
	while ($res = mysql_fetch_array($resourcerow))
	$resource[] = $res;

	$CACHE->set('block-news', 'query', $resource);
}

if ($resource) {
	$content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">\n";
	foreach($resource as $array) {
		if ($news_flag == 0) {
			$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable unfolded\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div style=\"display: block;\" class=\"sp-body\">".format_comment($array['body']);
			$content .="<hr/><div align=\"right\">";
			if (get_user_class() >= UC_ADMINISTRATOR) {
				$content .= "[<a href=\"news.php?action=edit&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>E</b></a>]";
				$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"news.php?action=delete&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>D</b></a>] ";
			}
			$content .= "".$tracker_lang['comms_2']."".$array['numcomm']." [<a href=\"newsoverview.php?id=".$array['id']."#comments\">".$tracker_lang['to_comment']."</a>]</div>";
			$content .= "</div></div>";
			$news_flag = 1;
		} else {
			$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div class=\"sp-body\">".format_comment($array['body']);
			$content .="<hr/><div align=\"right\">";
			if (get_user_class() >= UC_ADMINISTRATOR) {
				$content .= "[<a href=\"news.php?action=edit&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>E</b></a>]";
				$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"news.php?action=delete&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>D</b></a>] ";
			}
			$content .= "".$tracker_lang['comms_2']."".$array['numcomm']." [<a href=\"newsoverview.php?id=".$array['id']."\">".$tracker_lang['to_comment']."</a>]</div>";
			$content .= "</div></div>";
		}
	}
	$content .= "<p align=\"right\">[<a href=\"newsarchive.php\">".$tracker_lang['archive_of_news']."</a>]</p></td></tr></table>\n";
} else {
	$content .= "<table class=\"main\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
	$content .= "<div align=\"center\"><h3>".$tracker_lang['no_news']."</h3></div>\n";
	$content .= "</td></tr></table>";
}

?>