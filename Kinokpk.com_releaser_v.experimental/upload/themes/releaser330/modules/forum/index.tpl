<div id="forum">
<h1>{$REL_LANG->_('Site forum listing')}</h1>
<h2>{$REL_LANG->_('You currently in: %s',$ROUTE)}</h2>
<div class="pagetop">
<form action="{$REL_SEO->make_link('forum')}" method="get">
<span class="textselect fleft">{$REL_LANG->_('Jump to')}:</span> {$JUMP_TO}
</form>
</div>
{if !$no_posting}<a href="{$REL_SEO->make_link('forum','a','newtopic','cat',$curcat)}">{$REL_LANG->_('Create new topic')}</a>{/if}
<table id="forumtable">
<tbody id="highlighted">
	<tr class="top">
		<td class="colhead">{$REL_LANG->_('Forum')}</td>
		<td class="colhead">{$REL_LANG->_('Topics')}</td>
		<td class="colhead">{$REL_LANG->_('Posts')}</td>
		<td class="colhead">{$REL_LANG->_('Last topic')}</td>
		<td class="colhead">{$REL_LANG->_('Last post by')}</td>
	</tr>
{foreach item=catname key=catid from=$CATEGORIES}
<tr>
	<td colspan="5" class="label">{$REL_LANG->_('Category')}: <a href="{$REL_SEO->make_link('forum','cat',$catid,'name',$catname)}">{$catname}</a></td>
</tr>
	{foreach from=$FORUMS.$catid key=fid item=forum}
<tr>
	<td>{$REL_LANG->_('Forum')}: {$forum.route}</td>
{if ($FORUMSDATA.$fid)}
	<td>{$FORUMSDATA.$fid.topics}</td>
	<td>{$FORUMSDATA.$fid.posts}</td>
	<td>{$FORUMSDATA.$fid.subject}</td>
	<td>{$FORUMSDATA.$fid.added|mkprettytime} {$REL_LANG->_('From')} {$FORUMSDATA.$fid.user}<br/><a href="{$REL_SEO->make_link('forum','a','viewtopic','id',$FORUMSDATA.$fid.id,'name',$FORUMSDATA.$fid.name,'p',"{$FORUMSDATA.$fid.lastposted_id}#comm{$FORUMSDATA.$fid.lastposted_id}")}">{$REL_LANG->_('Go')}</a></td></tr>
{else}
<td>0</td><td>0</td><td>---</td><td>---</td>
{/if}
{/foreach}
{/foreach}
</tbody>
</table>
	<div class="pagebottom">
		<form action="{$REL_SEO->make_link('forum')}" method="get">
			<span class="textselect fleft">{$REL_LANG->_('Jump to')}:</span> {$JUMP_TO} 
		</form>
	</div>
</div>
<script language="javascript">
<!--
$(document).ready(function(){
	
	$('#torrenttable').evParseTable();
});
	
//-->
</script>