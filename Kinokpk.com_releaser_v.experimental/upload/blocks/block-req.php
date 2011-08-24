<?php

global $REL_CACHE, $REL_SEO, $REL_LANG;

if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}

$content .= "<table  width=\"100%\"><tr><td  valign=\"top\" align=\"center\">";

$content .= "<small>[<a href=\"".$REL_SEO->make_link('viewrequests')."\">{$REL_LANG->_('All')}</a>] [<a href=\"".$REL_SEO->make_link('requests','action','new')."\">{$REL_LANG->_('Make request')}</a>]</small><hr /><table border=\"1\"><tr><td align=\"center\">";

$reqarray = $REL_CACHE->get('block-req', 'query');

if ($reqarray===false) {

	$reqarray = array();
	$req=sql_query("SELECT requests.* FROM requests INNER JOIN categories ON requests.cat = categories.id WHERE requests.filled = '' ORDER BY added DESC LIMIT 3");

	while ($reqres = @mysql_fetch_array($req))
	$reqarray[]=$reqres;

	$REL_CACHE->set('block-req', 'query', $reqarray);
}

if (!$reqarray) {$content .= '<b>'.$REL_LANG->_('There are no requests').'</b>'; } else
foreach ($reqarray as $requests) {
	if ($requests[filledby]!=0) {
		$done = "<a href=".$REL_SEO->make_link(addslashes($requests['filled']))."><img border=\"0\" src=\"pic/chk.gif\" alt=\"{$REL_LANG->_('Done')}\"/></a>";
	}
	else {
		$done = "";
	}

	$content .= "<a href=\"".$REL_SEO->make_link('requests','id',$requests['id'])."\"><b>$requests[request]</b></a>&nbsp;&nbsp;&nbsp;$done<br /><small> [{$REL_LANG->_('Comments')}:  $requests[comments], {$REL_LANG->_('people need this')}: $requests[hits]]<br /><a href=\"".$REL_SEO->make_link('requests','action','vote','voteid',$requests['id'])."\">{$REL_LANG->_('I need this too!')}</a></small><br />";

}

$content .= "</td></tr></table></td></tr></table>";

?>