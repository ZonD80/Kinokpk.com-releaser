<script type="text/javascript">
function reactivate(bid) {
	$.get('{$REL_SEO->make_link('blocksadmin')}',{ a: 'react',id:bid },function(data){
	$("#act-"+bid).html(data);
	});
}
function deleteb(bid) {
	sure = confirm('{$REL_LANG->_("Are you sure?")}');
	if (!sure) return false;
	
	$.get('{$REL_SEO->make_link('blocksadmin')}',{ a: 'delete',id:bid },function(data){
		$("#block-"+bid).html('<td colspan="10" align="center"><h1>'+data+'</h1></td>');
		$("#block-"+bid).fadeOut('slow');
		});
	return false;
}
</script>
<form action="{$REL_SEO->make_link('blocksadmin','a','reorder')}" method="post">
<table width="100%">
	<tr>
		<td class="colhead">{$REL_LANG->_('ID')}</td>
		<td class="colhead">{$REL_LANG->_('Title')}</td>
		<td class="colhead">{$REL_LANG->_('Order')}</td>
		<td class="colhead">{$REL_LANG->_('Active')}</td>
		<td class="colhead">{$REL_LANG->_('Assigned file')}</td>
		<td class="colhead">{$REL_LANG->_('Visible for')}</td>
		<td class="colhead">{$REL_LANG->_('Expires on')}</td>
		<td class="colhead">{$REL_LANG->_('Pages affected')}</td>
		<td class="colhead">{$REL_LANG->_('Custom template')}</td>
		<td class="colhead">{$REL_LANG->_('Actions')}</td>
	</tr>
	{foreach key=position item=blockpos from=$blocks}
	<tr>
		<td colspan="10">{$REL_LANG->_("Position")}: {$position}</td>
	</tr>
	{foreach item=block from=$blockpos}
	<tr id="block-{$block.bid}">
		<td>{$block.bid}</td>
		<td>{$block.title}</td>
		<td><input type="text" name="order[{$block.bid}]" size="3" value="{$block.weight}"/></td>
		<td><a href="javascript:reactivate({$block.bid});"><div id="act-{$block.bid}">{if
		$block.active==1}{$REL_LANG->_('Yes')}{else}{$REL_LANG->_('No')}{/if}</div></a></td>
		<td>{if
		$block.blockfile<>''}{$block.blockfile}{else}{$REL_LANG->_('No')}{/if}</td>
		<td>{$block.view}</td>
		<td>{if $block.expire}{$block.expire}{else}{$REL_LANG->_('No')}{/if}</td>
		<td>{$block.which}</td>
		<td>{if
		$block.custom_tpl<>''}{$block.custom_tpl}{else}{$REL_LANG->_('No')}{/if}</td>
		<td><a
			href="{$REL_SEO->make_link('blocksadmin','a','edit','id',$block.bid)}">{$REL_LANG->_("Edit")}</a>/<a
			href="{$REL_SEO->make_link('blocksadmin','a','delete','id',$block.bid)}"
			onclick="javascript: return deleteb({$block.bid});">{$REL_LANG->_("Delete")}</a></td>
	</tr>
	{/foreach} {/foreach}
	<tr><td colspan="10" align="right"><input type="submit" value="{$REL_LANG->_('Reorder blocks')}"/></td></tr>
</table>
</form>