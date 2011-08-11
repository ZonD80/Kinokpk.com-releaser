<form method="post" action="{$REL_SEO->make_link('classadmin','a',$a,'id',$c.id)}">
<table border="1">
<tr><td colspan="2"><h1>{$REL_LANG->_('Adding or editing class')} | <a href="{$REL_SEO->make_link('classadmin')}">{$REL_LANG->_('Back')}</a></h1></td></tr>
<tr><td class="rowhead">{$REL_LANG->_('Name')}</td><td><input type="text" name="c[name]" value="{$c.name}"><br/><br/><small>*{$REL_LANG->_('<b>WRITE IN ENGLISH</b>; This phrase will be parsed via language system. Do not forget to add translations for this phrase.')}</small></td></tr>
<tr><td class="rowhead">{$REL_LANG->_('Priority')}</td><td><input type="text" name="c[prior]" value="{$c.prior}"> {$REL_LANG->_('Attention: Priorities must be unique')}</td></tr>
<tr><td class="rowhead">{$REL_LANG->_('Style')}</td><td><input type="text" name="c[style]" value="{$c.style}"><br/><small>*{$REL_LANG->_('You can use wildcards {clname} as class name and {uname} as user name')}</small></td></tr>
<tr><td class="rowhead">{$REL_LANG->_('Roles')}</td><td>{make_role_checkbox($c.remark,'c[remark][]')}<br/><small>{rolesdesc()}</small></td></tr>
<tr><td colspan="2"><input type="submit" value="{$REL_LANG->_('Save')}"></td></tr>
</table>
</form>