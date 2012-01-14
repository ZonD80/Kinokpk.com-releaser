{if $IS_MODERATOR && !pagercheck()}<form method="get" action="{$REL_SEO->make_link('comments')}">{/if}
{if !pagercheck()}<div id="newcomment_placeholder"></div>{/if}
{foreach item=row from=$rows} {include file='commenttable_entry.tpl'}
{/foreach} {if $IS_MODERATOR}
<div align="right"><input type="hidden" name="action" value="delete"><input	type="submit" value="{$REL_LANG->_('Delete')}" onClick="return confirm('{$REL_LANG->_("Are you sure?")}')"></div>
</form>
{/if}
