<table class="center" cellspacing="0" cellpadding="5" width="100%">
<tbody id="highlighted">
<tr>
	<td class="colhead center">{$REL_LANG->say_by_key('added')}</td>
	<td class="colhead left" >{$REL_LANG->say_by_key('name')}</td>
	{if $TABLE_VARIANT=='mytorrents'}
	<td class="colhead center" >{$REL_LANG->say_by_key('visible')}</td>
	{/if}
	<td class="colhead center" >{$REL_LANG->say_by_key('comments')}</td>
	{if $REL_CONFIG.use_ttl}
	<td class="colhead center" >{$REL_LANG->say_by_key('ttl')}</td>
	{/if}
	<td class="colhead center" >{$REL_LANG->say_by_key('size')}</td>
	<td class="colhead center" >{$REL_LANG->say_by_key('seeders')}|{$REL_LANG->say_by_key('leechers')}</td>
	{if $IS_MODERATOR&&$TABLE_VARIANT=='index'}
	<td class="colhead center" >{$REL_LANG->say_by_key('uploadeder')}</td>
	<td class="colhead center"  style="width: 50px;">{$REL_LANG->_("Changed")}</td>
	<td class="colhead center"  style="width: 60px;">{$REL_LANG->_("Checked")}</td>
	<td class="colhead center"  style="width: 50px;">{$REL_LANG->_("Banned")}</td>
	<td class="colhead center"  style="width: 15px;">{$REL_LANG->_("Hide")}</td>
	{/if} {if $TABLE_VARIANT=='bookmarks'}
	<td class="colhead center" >{$REL_LANG->say_by_key('delete')}</td>
	{/if}
</tr>
{foreach item=row from=$res} {if $row.label}
<tr>
	<td colspan="{if $IS_MODERATOR}10{else}6{/if}" class="left label">{$row.label}</td>
</tr>
{/if}
<tr{if $row.sticky} class="highlight"{/if}>
	<td  style="padding: 0pc" class='releas center'>
	<div class='drel'><i>{mkprettymonth($row.added)}</i></div>
	&nbsp;&nbsp;
	<td class="left">{if $row.sticky}{$REL_LANG->_("Sticky")}: {/if}
	<div class="name_browse"><a class="download" href="{$REL_SEO->make_link("download","id",$row.id,"name",translit($row.name))}" onclick="javascript:$.facebox({ ajax:'{$REL_SEO->make_link("download","id",$row.id,"name",translit($row.name))}'}); return false;"><img src="pic/download.gif" border="0"	title="{$REL_LANG->say_by_key('download')}" /></a>
	<a href="{$REL_SEO->make_link("details","id",$row.id,"name",translit($row.name))}"><b>{$row.name}</b></a>
	{if $row.new}&nbsp;<img title="{$REL_LANG->say_by_key('new')}" src="pic/new.png" />{/if}</div>
	{if $CURUSER.id == $row.owner || $IS_MODERATOR}<small><a id='descr'	href="{$REL_SEO->make_link("edit","id",$row.id)}"><img border="0" src="pic/pen.gif" title="{$REL_LANG->say_by_key('edit')}" /></a></small>{/if}
	{if $TABLE_VARIANT<>'bookmarks'&&$CURUSER} <small><a href="{$REL_SEO->make_link("bookmark","torrent",$row.id,"name",translit($row.name))}"><img	border="0" src="pic/bookmark.gif" title="{$REL_LANG->say_by_key('bookmark_this')}" /></a></small>{/if}
	{if $row.images}
	<div class="cat_pic"><small><a href="javascript:$.facebox({ image:'{$row.images}'});"><img border="0" src="pic/poster.gif" title="{$REL_LANG->say_by_key('poster')}" /></a></small></div>
	{/if} {if $row.cat_names}
	<div class="cat_name"><small>{$row.cat_names}</small></div>
	{/if}</td>
	{if $TABLE_VARIANT=='mytorrents'}
	<td class="right">{if $row.visible}<font color="green"><b>{$REL_LANG->say_by_key('yes')}</b></font>
	{else}<font color="red">"{$REL_LANG->say_by_key('no')}</font>{/if}</td>
	{/if}
	<td class="right"><b><a href="{$REL_SEO->make_link("details","id",$row.id,"name",translit($row.name))}#comments">{$row.comments}</a></b></td>
	{if $REL_CONFIG.use_ttl}
	<td class="center">{$row.ttl}</td>
	{/if}
	<td class="center">{$row.size}</td>
	<td class="center" nowrap>{$row.seed_col}</td>
	{if $IS_MODERATOR&&$TABLE_VARIANT=='index'}
	<td class="center">{if $row.username}<a href="{$REL_SEO->make_link("userdetails","id",$row.owner,"username",translit($row.username))}"><b>{get_user_class_color($row.class,$row.username)}</b></a>{else}<i>{$REL_LANG->_("Anonymous")}</i>{/if}</td>
	<td class="center" style="width: 50px;">{if !$row.moderated}<font color="green"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<font color="red"><b>{$REL_LANG->say_by_key('yes')}</b></font>{/if}</td>
	<td class="center" style="width: 60px;">{if !$row.moderatedby}<font	color="red"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<a	href="{$REL_SEO->make_link("userdetails","id",$row.moderatedby,"username",translit($row.modname))}">{get_user_class_color($row.modclass,$row.modname)}</a>{/if}</td>
	<td class="center" style="width: 50px;">{if !$row.banned}<font color="green"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<font	color="red"><b>{$REL_LANG->say_by_key('yes')}</b></font>{/if}</td>
	<td class="center" style="width: 15px;">{if $row['visible']}<font color="green"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<font color="red"><b>{$REL_LANG->say_by_key('yes')}</b></font>{/if}</td>
	{/if} {if $TABLE_VARIANT=='bookmarks'}
	<td class="center"><input type="checkbox" name="delbookmark[]" value="{$row.bookmarkid}" /></td>
	{/if}
</tr>
{/foreach}
</tbody>
</table>