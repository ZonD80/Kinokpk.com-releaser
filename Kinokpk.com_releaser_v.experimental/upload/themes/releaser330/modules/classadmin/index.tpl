{literal}
<script type="text/javascript">


    $(document).ready(function () {
        $(":checkbox").click(function () {
            cl = $(this).attr('class');
            //alert(cl);
            $(this).attr('class', 'current');
            $('.' + cl).each(function () {
                //alert('test');
                $(this).attr('checked', false);
            });
            $(this).attr('class', cl);
        });
    });
</script>
{/literal}
<form method="post" action="{$REL_SEO->make_link('classadmin','a','reorder')}">
    <table border="1" width="100%">
        <tr>
            <td colspan="6">{rolesdesc()}</td>
        </tr>
        <tr>
            <td colspan="6"><h1>{$REL_LANG->_('Current classes')} | <a
                    href="{$REL_SEO->make_link('classadmin','a','add')}">{$REL_LANG->_('Add')}</a></h1></td>
        </tr>
        <tr>
            <td class="colhead">ID</td>
            <td class="colhead">{$REL_LANG->_('Name')}</td>
            <td class="colhead">{$REL_LANG->_('Priority')}</td>
            <td class="colhead">{$REL_LANG->_('Style')}</td>
            <td class="colhead">{$REL_LANG->_('Roles')}</td>
            <td class="colhead">{$REL_LANG->_('Actions')}</td>
        </tr>
    {foreach item=cl from=$c}
        <tr id="tr-{$cl.id}">
            <td>{$cl.id}</td>
            <td>{$REL_LANG->_($cl.name)}</td>
            <td><input type="text" name="prior[{$cl.id}]" size="3" value="{$cl.prior}"/></td>
            <td>{if $cl.style}{$cl.style|htmlentities}{else}{$REL_LANG->_('No')}{/if}</td>
            <td>{make_role_checkbox($cl.remark,"role[{$cl.id}][]")}</td>
            <td><a href="{$REL_SEO->make_link('classadmin','a','edit','id',$cl.id)}">{$REL_LANG->_('Edit')}</a> / <a
                    href="{$REL_SEO->make_link('classadmin','a','del','id',$cl.id)}">{$REL_LANG->_('Delete')}</a></td>
        </tr>
    {/foreach}
        <tr>
            <td colspan="6" align="right">{$REL_LANG->_('Attention: Priorities must be unique')} <input type="submit"
                                                                                                        value="{$REL_LANG->_('Save')}"/>
            </td>
        </tr>
    </table>
</form>