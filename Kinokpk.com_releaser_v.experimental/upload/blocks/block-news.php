<?php
global $REL_LANG, $REL_CACHE, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}

$blocktitle = $REL_LANG->say_by_key('news').(get_privilege('news_operation',false) ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"".$REL_SEO->make_link('news')."\"><b>".$REL_LANG->say_by_key('create')."</b></a>]</font>" : "");

$resource = $REL_CACHE->get('block-news', 'query');

if ($resource===false) {

	$resource = array();
	$resourcerow = sql_query("SELECT * FROM news GROUP BY news.id ORDER BY news.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);
	while ($res = mysql_fetch_array($resourcerow))
	$resource[] = $res;

	$REL_CACHE->set('block-news', 'query', $resource);
}

if ($resource) {
	$content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">\n";
	foreach($resource as $array) {
		if ($news_flag == 0) {
			$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable unfolded\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div style=\"display: block;\" class=\"sp-body\">".format_comment($array['body']);
			$content .="<hr/><div align=\"right\">";
			if (get_privilege('news_operation',false)) {
				$content .= "[<a href=\"".$REL_SEO->make_link('news','action','edit','newsid',$array['id'],'returno',urlencode($_SERVER['PHP_SELF']))."\"><b>E</b></a>]";
				$content .= "[<a onclick=\"return confirm('{$REL_LANG->_('Are you sure?')}');\" href=\"".$REL_SEO->make_link('news','action','delete','newsid',$array['id'],'returno',urlencode($_SERVER['PHP_SELF']))."\"><b>D</b></a>] ";
			}
			$content .= "".$REL_LANG->say_by_key('comms_2')."".$array['comments']." [<a href=\"".$REL_SEO->make_link('newsoverview','id',$array['id'])."#comments\">".$REL_LANG->say_by_key('to_comment')."</a>]</div>";
			$content .= "</div></div>";
			$news_flag = 1;
		} else {
			$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div class=\"sp-body\">".format_comment($array['body']);
			$content .="<hr/><div align=\"right\">";
			if (get_privilege('news_operation',false)) {
				$content .= "[<a href=\"".$REL_SEO->make_link('news','action','edit','newsid',$array['id'],'returno',urlencode($_SERVER['PHP_SELF']))."\"><b>E</b></a>]";
				$content .= "[<a onclick=\"return confirm('{$REL_LANG->_('Are you sure?')}');\" href=\"".$REL_SEO->make_link('news','action','delete','newsid',$array['id'],'returno',urlencode($_SERVER['PHP_SELF']))."\"><b>D</b></a>] ";
			}
			$content .= "".$REL_LANG->say_by_key('comms_2')."".$array['comments']." [<a href=\"".$REL_SEO->make_link('newsoverview','id',$array['id'])."\">".$REL_LANG->say_by_key('to_comment')."</a>]</div>";
			$content .= "</div></div>";
		}
	}
	$content .= "<p align=\"right\">[<a href=\"".$REL_SEO->make_link('newsarchive')."\">".$REL_LANG->say_by_key('archive_of_news')."</a>]</p></td></tr></table>\n";
} else {
	$content .= "<table class=\"main\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
	$content .= "<div align=\"center\"><h3>".$REL_LANG->say_by_key('no_news')."</h3></div>\n";
	$content .= "</td></tr></table>";
}

?>