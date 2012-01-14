{if $IS_MODERATOR}
<form method="get" action="{$REL_SEO->make_link('comments')}">{/if}
{foreach name=fcomment key=key item=row from=$rows} {include
file='modules/forum/commenttable_entry.tpl'} {/foreach} {if
$IS_MODERATOR}
<div align="right"><input type="hidden" name="action" value="delete"><input	type="submit" value="{$REL_LANG->_('Delete')}" onClick="return confirm('{$REL_LANG->_("Are you sure?")}')"></div>
</form>
{/if}
