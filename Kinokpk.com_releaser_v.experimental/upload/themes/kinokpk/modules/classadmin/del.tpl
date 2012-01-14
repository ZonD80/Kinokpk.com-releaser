<form method="post" action="{$REL_SEO->make_link('classadmin','a','del','id',$id)}">
<p>{$REL_LANG->_('Select class to be assigned to users, which currently have deleting class')}</p>
<select name="cl">
{foreach from=$c item=cl}
<option value="{$cl.id}">{$REL_LANG->_($cl.name)}</option>
{/foreach}
</select>
<input type="submit" value="{$REL_LANG->_('Continue')}"/>
</form>