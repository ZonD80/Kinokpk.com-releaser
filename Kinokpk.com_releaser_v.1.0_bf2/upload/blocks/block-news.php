<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $tracker_lang;

$blocktitle = $tracker_lang['news'].(get_user_class() >= UC_ADMINISTRATOR ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"news.php\"><b>".$tracker_lang['create']."</b></a>]</font>" : "");

$resource = sql_query("SELECT news.* , COUNT(newscomments.id) AS numcomm FROM news LEFT JOIN newscomments ON newscomments.news = news.id WHERE ADDDATE(news.added, INTERVAL 45 DAY) > NOW() GROUP BY news.id ORDER BY news.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);

$content .= "<script language=\"javascript\" type=\"text/javascript\" src=\"js/show_hide.js\"></script>";
//<a href=\"javascript: show_hide('s1')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pics1\" title=\"Показать\"></a>
if (mysql_num_rows($resource)) {
    $content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">\n<ul>";
    while($array = mysql_fetch_array($resource)) {
		if ($news_flag == 0) {
			$content .=
			"<span style=\"cursor: pointer;\" onclick=\"javascript: show_hide('s".$array["id"]."')\"><img border=\"0\" src=\"pic/minus.gif\" id=\"pics".$array["id"]."\" title=\"Скрыть\"></span>&nbsp;"
			."<span style=\"cursor: pointer;\" onclick=\"javascript: show_hide('s".$array["id"]."')\">".date("d.m.Y",strtotime($array['added']))." - \n"
			."<b>".$array['subject']."</b></span>\n"
			."<span id=\"ss".$array["id"]."\" style=\"display: block;\">".format_comment($array['body'])."</span>";
	    	if (get_user_class() >= UC_ADMINISTRATOR) {
	            $content .= " <font size=\"-2\">[<a class=\"altlink\" href=\"news.php?action=edit&newsid=" . $array['id'] . "&returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>E</b></a>]</font>";
	            $content .= " <font size=\"-2\">[<a class=\"altlink\" href=\"news.php?action=delete&newsid=" . $array['id'] . "&returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>D</b></a>]</font>";
	    	}
      $content .= "<div align=\"right\">Комментариев: ".$array['numcomm']." [<a href=\"newsoverview.php?id=".$array['id']."\">Комментировать</a>]</div>";
	    	$content .= "<br /><hr />";
	    	$news_flag = 1;
    	} else {
		$content .=
			"<span style=\"cursor: pointer;\" onclick=\"javascript: show_hide('s".$array["id"]."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pics".$array["id"]."\" title=\"Показать\"></span>&nbsp;"
			."<span style=\"cursor: pointer;\" onclick=\"javascript: show_hide('s".$array["id"]."')\">".date("d.m.Y",strtotime($array['added']))." - \n"
			."<b>".$array['subject']."</b></span>\n"
			."<span id=\"ss".$array["id"]."\" style=\"display: none;\">".format_comment($array['body'])."</span>";
			if (get_user_class() >= UC_ADMINISTRATOR) {
		        $content .= " <font size=\"-2\">[<a class=\"altlink\" href=\"news.php?action=edit&newsid=" . $array['id'] . "&returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>E</b></a>]</font>";
		        $content .= " <font size=\"-2\">[<a class=\"altlink\" href=\"news.php?action=delete&newsid=" . $array['id'] . "&returnto=" . urlencode($_SERVER['PHP_SELF']) . "\"><b>D</b></a>]</font>";
			}

			$content .= "<div align=\"right\">Комментариев: ".$array['numcomm']." [<a href=\"newsoverview.php?id=".$array['id']."\">Комментировать</a>]</div>";
			$content .= "<br /><hr />";
    	}
	}
	$content .= "<p align=\"right\">[<a href=\"newsarhive.php\">Архив новостей</a>]</p></ul></td></tr></table>\n";
} else {
	$content .= "<table class=\"main\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
	$content .= "<div align=\"center\"><h3>".$tracker_lang['no_news']."</h3></div>\n";
	$content .= "</td></tr></table>";
}

?>