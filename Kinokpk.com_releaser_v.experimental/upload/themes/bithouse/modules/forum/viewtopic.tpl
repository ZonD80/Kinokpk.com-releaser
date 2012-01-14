<div id="forum" class="viewsForum">
<h1>{$REL_LANG->_('Viewing topic: %s',$topic.subject)}
{$REL_LANG->_('in')} {$ROUTE}</h1>
<div id="pager_scrollbox">
<div id="newcomment_placeholder" style="display: none;"></div>
<div id="forumcomments">{$commenttable}</div>
</div>
<div class="pagebottom">
<form action="{$REL_SEO->make_link('forum')}" method="get"><span
	class="textselect fleft">{$REL_LANG->_('Jump to')}:</span> {$JUMP_TO}</form>
</div>

<div class="createbotton"><a
	href="{$REL_SEO->make_link('forum','a','newtopic','cat',$curcat)}"
	class="createtopic">{$REL_LANG->_('Create new topic')}</a></div>
</div>
<div class="clear"></div>
