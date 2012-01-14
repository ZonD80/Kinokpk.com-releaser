<script type="text/javascript">
function delete_nick_history(id) {
	conf = confirm("{$REL_LANG->_('Are you sure?')}");
	if (!conf) return false;

	$.post("{$REL_SEO->make_link('modtask')}",{ action: 'delnick', id : id});
	$('#nick-'+id).slideUp();
	return true;
}
</script>
<table width="100%">
	<tr>
		<td class="colhead">{$REL_LANG->_('Nickname')}</td>
		<td class="colhead">{$REL_LANG->_('Date of change')}</td>
		{if $IS_MODERATOR}
		<td class="colhead">{$REL_LANG->_('Delete')}</td>
		{/if}
	</tr>
	{foreach from=$nick item=n}
	<tr id="nick-{$n.id}">
		<td>{$n.nick}</td>
		<td>{$n.date|mkprettytime} ({$n.date|get_elapsed_time}
		{$REL_LANG->_('ago')})</td>
		{if $IS_MODERATOR}
		<td><a href="javascript:delete_nick_history({$n.id});">{$REL_LANG->_('Delete')}</a></td>
		{/if}
	</tr>
	{/foreach}
</table>
