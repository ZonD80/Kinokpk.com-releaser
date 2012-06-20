<div class="rightCol fr">
    <div class="blStandart">
        <div class="pad9">
            <div class="clear10"></div>
            <center><b style="font-size: 12px;">{$REL_LANG->_($name)}</b></center>
            </br>
            <div class="lineTitle"></div>
            <div class="clear10"></div>

            <table width="100%">
                <tr>
                    <td style="vertical-align: top;"><a href="{$links['present_more']}"><img style="border:none;"
                                                                                             src="pic/presents/{$present['type']}_big.png"
                                                                                             titie="{$REL_LANG->_('Present')}"/></a>
                    </td>
                    <td style="vertical-align: top; text-align:left; width:100%">
                    {$REL_LANG->_("Sender")} <a
                            href="{$REL_SEO->make_link('userdetails','id',$present['presenter'],'username',$presenter['username'])}">{get_user_class_color($presenter['class'],$presenter['username'])}</a>
                        <hr/>{$present['msg']}</td>
                </tr>
            </table>

            <div class="clear10"></div>
        </div>
    </div>
    <div class="clear10"></div>
</div>
<div class="clear10"></div>
