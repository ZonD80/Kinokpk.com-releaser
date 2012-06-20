<form method="post" action="{$REL_SEO->make_link('privadmin','a',$a)}">
{if $a=='edit'}<input type="hidden" name="name" value="{$p.name}"/>{/if}
    <table border="1">
        <tr>
            <td colspan="2"><h1>{$REL_LANG->_('Adding or editing privilege')} | <a
                    href="{$REL_SEO->make_link('privadmin')}">{$REL_LANG->_('Back')}</a></h1></td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Name')}</td>
            <td><input type="text"{if $a=='add'} name="name"{/if} value="{$p.name}"{if $a=='edit'} disabled="disabled">
                <small>
                    *{$REL_LANG->_('Editing names of existing privileges is not possible. If you want to change it, you must create new privilege and delete this one')}</small>{else}
                >{/if}</td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Classes allowed')}</td>
            <td>{make_classes_checkbox("classes",$p.classes_allowed)}</td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Description')}</td>
            <td><textarea rows="4" cols="40" name="descr">{$p.description}</textarea><br/>
                <small>
                    *{$REL_LANG->_('<b>WRITE IN ENGLISH</b>; This phrase will be parsed via language system. Do not forget to add translations for this phrase.')}</small>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="{$REL_LANG->_('Save')}"></td>
        </tr>
    </table>
</form>