<script type="text/javascript">
    function add_value(value, div) {
        if (div != 'where')
            $('#' + div).val($('#' + div).val() + value + ',');
        else
            $('#' + div).val(value);
//return false;
    }
</script>
<form action="{$REL_SEO->make_link('blocksadmin','a',"
	save$ACTION",'id',$block.bid)}"
      method="post">
    <table>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Title')}:</td>
            <td><input type="text" name="arr[title]" value="{$block.title}"/></td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Assigned file')}:</td>
            <td><select name="arr[blockfile]">
                <option value="">{$REL_LANG->_("No")}</option>
            {foreach key=bdisplay item=bfile from=$blockfiles}
                <option value="{$bfile}" {if $bfile==$block.blockfile}selected{/if}>{$bdisplay}</option>
            {/foreach}
            </select></td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Pages affected')}:</td>
            <td><input type="text" name="arr[which]" id="what" size="100"
                       value="{$block.which}"/>

                <div class="sp-wrap">
                    <div class="sp-head folded clickable">{$REL_LANG->_("More")}
                        ({$REL_LANG->_("Will open list of pages, if none, block will appear on any page")})
                    </div>
                    <div class="sp-body">{foreach item=displayfile from=$filelist} <a
                            href="javascript:add_value('{$displayfile}','what');">{$displayfile}</a><br/>
                    {/foreach}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_("Position")}:</td>
            <td><input type="text" id="where" name="arr[bposition]"
                       value="{$block.bposition}"/> <a
                    href="javascript:add_value('t','where');">{$REL_LANG->_("Top")}</a>,
                <a href="javascript:add_value('d','where');">{$REL_LANG->_("Down")}</a>,
                <a href="javascript:add_value('l','where');">{$REL_LANG->_("Left")}</a>,
                <a href="javascript:add_value('r','where');">{$REL_LANG->_("Right")}</a></td>

        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Visible for')}<br/>
            {$REL_LANG->_('Check nothing to allow all')}:
            </td>
            <td>{$user_classes}</td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Content')}:<br/>
                <small><a href="javascript:wysiwygjs();">{$REL_LANG->_('Enable WYSIWYG editor')}</a></small>
            </td>
            <td>{$block.content}</td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Order')}:</td>
            <td><input type="text" name="arr[weight]" size="3"
                       value="{$block.weight}"/></td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Active')}:</td>
            <td><select name="arr[active]">
                <option value="1"{if $block.active} selected{/if}>{$REL_LANG->_("Yes")}</option>
                <option value="0"{if !$block.active} selected{/if}>{$REL_LANG->_("No")}</option>
            </select></td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Expires on')}:</td>
            <td><input type="text" size="20" name="arr[expire]"
                       value="{if $block.expire}{$block.expire|date_format:" %T %D"}{/if}"/>
            {$REL_LANG->_("Example")}: {$smarty.now|date_format:"%T %D"}</td>
        </tr>
        <tr>
            <td class="rowhead">{$REL_LANG->_('Custom template')}:</td>
            <td><input type="text" size="20" name="arr[custom_tpl]"
                       value="{$block.custom_tpl}"/> {$REL_LANG->_('Without .tpl extention')}</td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input type="submit"
                                                 value="{$REL_LANG->_('Save changes')}"/></td>
        </tr>
    </table>
</form>
