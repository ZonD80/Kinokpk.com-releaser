<div id="forum" class="viewsForum">
<h1>{$REL_LANG->_('Viewing topic: %s',$topic.subject)} {$REL_LANG->_('in')} {$ROUTE}</h1>
{$commenttable}
<div class="pagebottom">
		<form action="{$REL_SEO->make_link('forum')}" method="get">
			<span class="textselect fleft">{$REL_LANG->_('Jump to')}:</span> {$JUMP_TO}
		</form>
	</div>

<a href="{$REL_SEO->make_link('forum','a','newtopic','cat',$curcat)}">{$REL_LANG->_('Create new topic')}</a>
</div>
<div class="clear"></div>