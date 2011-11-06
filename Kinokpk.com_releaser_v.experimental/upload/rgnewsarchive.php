<?php
/**
 * Release groups news archive
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();


loggedinorreturn();

$rgid=(int)$_GET['id'];

if (!is_valid_id($rgid)) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

$relgroup = $REL_DB->query("SELECT name,owners,private FROM relgroups WHERE id=$rgid");
$relgroup = mysql_fetch_assoc($relgroup);

if (!$relgroup) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

if (in_array($CURUSER['id'],@explode(',',$relgroup['owners'])) || (get_privilege('edit_relgroups',false))) $I_OWNER=true;

if ($relgroup['private']) {
	if (!in_array($rgid,@explode(',',$CURUSER['relgroups'])) && !$I_OWNER && !in_array($CURUSER['id'],@explode(',',$relgroup['onwers']))) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_access_priv_rg'));
}

$count = get_row_count("rgnews"," WHERE relgroup=$rgid");

$resource = $REL_DB->query("SELECT rgnews.* , SUM(1) FROM rgnews LEFT JOIN comments ON comments.toid = rgnews.id WHERE comments.type='rgnews' GROUP BY rgnews.id ORDER BY rgnews.added DESC $limit");

if ($count)
{
	print("<div id='rgnews-table'>");
	print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
			<tr><td class='colhead' align='center'><b>{$REL_LANG->_('News archive')} &quot;".$relgroup['name']."&quot;</b></td></tr>");
	
	while(list($id, $userid, $added, $body, $subject,$comments) = mysql_fetch_array($resource))
	{

		print("<tr><td>");
		print("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
            <tr><td class='colhead'>".$subject."");
		print("</td></tr><tr><td>".format_comment($body)."</td></tr>");
		print("</td></tr>");
		print("<tr><td style='background-color: #F9F9F9'>

            <div style='float:left;'><b>{$REL_LANG->_('Added')}</b>: ".mkprettytime($added)." <b>{$REL_LANG->_('Comments')}:</b> ".$comments." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$id)."#comments\">{$REL_LANG->_('Comment')}</a>]</div>");

		if ((get_privilege('edit_relgroups',false)) || $I_OWNER)
		{
			print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$rgid,'newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">{$REL_LANG->_('Edit')}</a>]
            [<a class='altlink' onClick=\"return confirm('{$REL_LANG->_('Are you sure?')}')\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$rgid,'newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">{$REL_LANG->_('Delete')}</a>]
            </font></div>");
		}
		print("</td></tr></table>");

	}
	print("</table>");
	print("</div>");
}
else
{
	$REL_TPL->stdmsg($REL_LANG->_('Sorry'),$REL_LANG->_('Nothing was found'));
}


$REL_TPL->stdfoot();
?>