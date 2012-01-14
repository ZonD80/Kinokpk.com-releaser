<form action="{$REL_SEO->make_link('seoadmin','a',"
	save$ACTION",'id',$rule.id)}"
	method="post">
<table>
	<tr>
		<td class="rowhead">{$REL_LANG->_('Script')}:</td>
		<td><input type="text" name="arr[script]" value="{$rule.script}" />
		{$REL_LANG->_('Without .php extension')}</td>
	</tr>
	<tr>
		<td class="rowhead">{$REL_LANG->_('Parameter')}:</td>
		<td><input type="text" name="arr[parameter]" value="{$rule.parameter}" />
		{$REL_LANG->_('E.g. "id". For replacing script name with "?" define
		"{base}"')}</td>
	</tr>
	<tr>
		<td class="rowhead">{$REL_LANG->_('Replace')}:</td>
		<td><input type="text" name="arr[repl]" value="{$rule.repl}" />
		{$REL_LANG->_('E.g. "USER_IDENTIFIER=%s" or "id/%s", like in <a
			href="http://ru2.php.net/manual/en/function.sprintf.php">sprintf</a>')}</td>
	</tr>
	<tr>
		<td class="rowhead">{$REL_LANG->_('Unset params')}:</td>
		<td><input type="text" name="arr[unset_params]"
			value="{$rule.unset_params}" /> {$REL_LANG->_('Will unset some params
		already processed. E.g. "{base}". Separate by <b>commas without spaces</b>')}</td>
	</tr>
	<td class="rowhead">{$REL_LANG->_('Order')}:</td>
	<td><input type="text" name="arr[sort]" size="3" value="{$rule.sort}" />
	{$REL_LANG->_('Parametres can be (re)sorted by these values')}</td>
	</tr>
	<tr>
		<td class="rowhead">{$REL_LANG->_('Enabled')}:</td>
		<td><select name="arr[enabled]">
			<option value="1"{if $rule.enabled} selected{/if}>{$REL_LANG->_("Yes")}</option>
			<option value="0"{if !$rule.enabled} selected{/if}>{$REL_LANG->_("No")}</option>
		</select></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit"
			value="{$REL_LANG->_('Save changes')}" /></td>
	</tr>
</table>
</form>
