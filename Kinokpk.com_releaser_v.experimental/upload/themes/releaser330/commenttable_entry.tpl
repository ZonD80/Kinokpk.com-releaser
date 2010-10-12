<div id="comm{$row.id}"><table class="maibaugrand" width="100%" border="1" cellspacing="0" cellpadding="3">
<tr><td class=colhead align="left" colspan="2" height="24">
{if $row.user}<img src="pic/button_{$row.userstate}.gif" title="{$row.userstate|capitalize}" style="position: relative; top: 2px;" border="0" height="14">
<a href="{$REL_SEO->make_link('userdetails','id',$row.user,'username',translit($row.username))}" class="altlink_white">
<b>{get_user_class_color($row.class, $row.username)}</b></a>{get_user_icons($row)}{$row.ratearea.user}:&nbsp;Re (<a href="{$row.link}">{$row.id}</a>): {$row.subject}<span style="float: right"><small>{$REL_LANG->say_by_key('rate_comment')}</small> {$row.ratearea.comment}</span>
{else}
<i>{$REL_LANG->_("Anonym")}</i>:&nbsp;Re (<a href="{$row.link}">{$row.id}</a>): {$row.subject}
{/if}
</td></tr>
<tr valign="top">
<td style="padding: 0px; width: 5%;" align="center"><img src="{$row.avatar}" width="50px" title="{$REL_LANG->_("Avatar")}"></td>
<td width="100%" class="text">
{if $row.ratingsum<$REL_CONFIG.low_comment_hide}
<div align="center"><i>{$REL_LANG->_("This comment is too bad to show it to you")}</i></div>
{if $IS_MODERATOR}
{$REL_LANG->_("You are viewing as moderator")}:<br/>
{$row.text}{/if}{else}{$row.text}
{/if}
{if $row.editedby}<p><font size=1 class=small>{$REL_LANG->_("Last edited by")} <a href="{$REL_SEO->make_link('userdetails','id',$row.editedby,'username', $row.editedbyname)}"><b>{$row.editedbyname}</b></a> {mkprettytime($row.editedat)} ({get_elapsed_time($row.editedat,false)} {$REL_LANG->say_by_key('ago')})</font></p>{/if}
</td></tr><tr><td class="colhead" align="center" colspan="2">
<div style="float: left; width: auto;">
{if $CURUSER}[<a href="javascript:quote_comment('{$row.username}');" class="altlink_white">{$REL_LANG->_("Quote selected")}</a>] [<a href="{$REL_SEO->make_link('comments','action','quote','cid',$row.id)}" class="altlink_white">{$REL_LANG->_("Quote")}</a>]{/if}
{if $row.user == $CURUSER.id || $IS_MODERATOR}[<a href="{$REL_SEO->make_link($row.type, 'action', 'edit','cid',$row.id)}" class="altlink_white">{$REL_LANG->_("Edit")}</a>]{/if}
{if $IS_MODERATOR} [<a href="{$REL_SEO->make_link($row.type, 'action','delete','cid[]',$row.id)}" onClick="return delete_comment({$row.id});" class="altlink_white">{$REL_LANG->_("Delete")}</a>]
{$row.reportarea}
IP: {if $row.ip}<a href="{$REL_SEO->make_link('usersearch','ip',$row.ip)}" class="altlink_white">{$row.ip}</a>{else}{$REL_LANG->_("Unknown")}{/if}{/if}
</div>
<div align="right" nowrap><small>{mkprettytime($row.added)}</small>{if $IS_MODERATOR}<input type="checkbox" name="cid[]" value="{$row.id}">{/if}</td></tr>
</table><br/></div>