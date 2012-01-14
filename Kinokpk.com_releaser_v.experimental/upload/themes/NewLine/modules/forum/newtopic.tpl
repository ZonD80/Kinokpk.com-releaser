<div id="forum">
<h1>{$REL_LANG->_('Post new topic')} {$REL_LANG->_('to')} {$ROUTE}</h1>
<form action="{$postto}" method="post">
<table>
	<tr>
		<td>{$REL_LANG->_('Topic title')}:</td>
		<td><input name="title" type="text" /></td>
	</tr>
	<tr>
		<td>{$REL_LANG->_('Topic content')}:</td>
		<td>{$textbbcode}</td>
	</tr>
	<tr>
		<td>{$REL_LANG->_('Topic forum')}:</td>
		<td>{$CAT_SELECTOR}</td>
	</tr>
	{if $IS_MODERATOR}
	<tr>
		<td>{$REL_LANG->_('Close date')}:</td>
		<td><input type="text" name="closedate"> {$REL_LANG->_('Example')}:
		{$smarty.now|date_format:"%T %D"}</td>
	</tr>
	{/if}
	<tr>
		<td colspan="2"><input type="submit"
			value="{$REL_LANG->_('Post new topic')}" /></td>
	</tr>
</table>
</form>
</div>
