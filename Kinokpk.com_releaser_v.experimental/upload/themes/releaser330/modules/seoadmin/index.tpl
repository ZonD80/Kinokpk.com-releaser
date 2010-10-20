<script type="text/javascript">
function reactivate(id) {
	$.get('{$REL_SEO->make_link('seoadmin')}',{ a: 'react',id:id },function(data){
	$("#act-"+id).html(data);
	});
}
function deleterule(id) {
	sure = confirm('{$REL_LANG->_("Are you sure?")}');
	if (!sure) return false;
	
	$.get('{$REL_SEO->make_link('seoadmin')}',{ a: 'delete',id:id },function(data){
		$("#rule-"+id).html('<td colspan="10" align="center"><h1>'+data+'</h1></td>');
		$("#rule-"+id).fadeOut('slow');
		});
	return false;
}
</script>
<form action="{$REL_SEO->make_link('seoadmin','a','reorder')}" method="post">
<table width="100%">
	<tr>
		<td class="colhead">{$REL_LANG->_('ID')}</td>
		<td class="colhead">{$REL_LANG->_('Order')}</td>
		<td class="colhead">{$REL_LANG->_('Enabled')}</td>
		<td class="colhead">{$REL_LANG->_('Parameter')}</td>
		<td class="colhead">{$REL_LANG->_('Replace')}</td>
		<td class="colhead">{$REL_LANG->_('Unset params')}</td>
		<td class="colhead">{$REL_LANG->_('Actions')}</td>
	</tr>
	{foreach key=position item=rulepos from=$rules}
	<tr>
		<td colspan="10">{$REL_LANG->_("Script")}: {$position}</td>
	</tr>
	{foreach item=rule from=$rulepos}
	<tr id="rule-{$rule.id}">
		<td>{$rule.id}</td>
		<td><input type="text" name="order[{$rule.id}]" size="3" value="{$rule.sort}"/></td>
		<td><a href="javascript:reactivate({$rule.id});"><div id="act-{$rule.id}">{if
		$rule.enabled==1}{$REL_LANG->_('Yes')}{else}{$REL_LANG->_('No')}{/if}</div></a></td>
		<td>{$rule.parameter}</td>
		<td>{$rule.repl}</td>
		<td>{$rule.unset_params}</td>
		<td><a
			href="{$REL_SEO->make_link('seoadmin','a','edit','id',$rule.id)}">{$REL_LANG->_("Edit")}</a>/<a
			href="{$REL_SEO->make_link('seoadmin','a','delete','id',$rule.id)}"
			onclick="javascript: return deleterule({$rule.id});">{$REL_LANG->_("Delete")}</a></td>
	</tr>
	{/foreach} {/foreach}
	<tr><td colspan="10" align="right"><input type="submit" value="{$REL_LANG->_('Reorder rules')}"/></td></tr>
</table>
</form>