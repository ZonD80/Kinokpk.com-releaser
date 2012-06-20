<script type="text/javascript">
    function save_classes(name) {
        var classes = [];
        var count = 0;
        $('input[name="classes-' + name + '[]"]').each(function () {
            if (this.checked) {
                classes[count] = $(this).val();
                count++;
            }
        });
        $.post('{$REL_SEO->make_link('privadmin','a','edit')}', { name:name, 'classes[]':classes }, function (data) {
            alert(data);
        });
        return false;
    }

    function deletepriv(name) {
        if (name == 'is_guest') {
            alert('{$REL_LANG->_('Privilege "%s" can not be deleted. It is system privilege.','is_guest')}');
            return false;
        }
        sure = confirm('{$REL_LANG->_('Are you sure?')}');
        if (!sure) return false;
        $.post('{$REL_SEO->make_link('privadmin','a','del')}', { name:name }, function (data) {
            $('#tr-' + name).html('<td colspan="4" align="center">' + data + '</td>');
            $('#tr-' + name).fadeOut(2000);
        });
        return false;
    }
</script>
<table border="1">
    <tr>
        <td colspan="5"><h1>{$REL_LANG->_('Current registered privileges')} | <a
                href="{$REL_SEO->make_link('privadmin','a','add')}">{$REL_LANG->_('Add')}</a></h1></td>
    </tr>
    <tr>
        <td class="colhead">{$REL_LANG->_('Name')}</td>
        <td class="colhead">{$REL_LANG->_('Classes allowed')}</td>
        <td class="colhead">{$REL_LANG->_('Description')}</td>
        <td class="colhead">{$REL_LANG->_('Actions')}</td>
    </tr>
{foreach item=pr from=$p}
    <tr id="tr-{$pr.name}">
        <td>{$pr.name}</td>
        <td>{make_classes_checkbox("classes-{$pr.name}",$pr.classes)}</td>
        <td>{$REL_LANG->_($pr.descr)}</td>
        <td><a href="{$REL_SEO->make_link('privadmin','a','edit','name',$pr.name)}"
               onclick="javascript: return save_classes('{$pr.name}');">{$REL_LANG->_('Save checked classes')}</a><br/><a
                href="{$REL_SEO->make_link('privadmin','a','edit','name',$pr.name)}">{$REL_LANG->_('Edit privilege description')}</a><br/><a
                onclick="return deletepriv('{$pr.name}');"
                href="{$REL_SEO->make_link('privadmin','a','delete','name',$pr.name)}">{$REL_LANG->_('Delete')}</a></td>
    </tr>
{/foreach}
</table>
