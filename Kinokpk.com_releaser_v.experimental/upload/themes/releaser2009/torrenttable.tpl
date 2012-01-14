{if !pagercheck()}
<div id="pager_scrollbox">
<table id="torrenttable" class="center" cellspacing="0" width="100%">
<tbody id="highlighted">
{/if}
		<tr class="top">
<td class="colhead" align="center">
<a class="altlink_white">Тип</a>
</td>
		
			<td class="colhead left">{$REL_LANG->say_by_key('name')}</td>
			{if $TABLE_VARIANT=='mytorrents'}
			<td class="colhead center">{$REL_LANG->say_by_key('visible')}</td>
			{/if}
			<td class="colhead center"><a class="altlink_white"><img title="Сортировка по количеству комментов" src="pic/browse/comments.gif" border="0"></a></td>



			{if $REL_CONFIG.use_ttl}
			<td class="colhead center">{$REL_LANG->say_by_key('ttl')}</td>
			{/if}
			<td class="colhead center"><a href=""><img title="Сортировка по размеру" src="pic/browse/size_file.gif" border="0"></a></td>
			<td class="colhead center"><a href=""><img title="Сортировка по количесту пиров" src="pic/browse/up.gif" border="0"></a><a href=""><img title="Сортировка по количесту сидов" src="pic/browse/down.gif" border="0"></a></td>
			{if $IS_MODERATOR&&$TABLE_VARIANT=='index'}
			<td class="colhead center">{$REL_LANG->say_by_key('uploadeder')}</td>
			<td class="colhead center">{$REL_LANG->_("Changed")}</td>
			<td class="colhead center">{$REL_LANG->_("Checked")}</td>
			<td class="colhead center">{$REL_LANG->_("Banned")}</td>
			<td class="colhead center">{$REL_LANG->_("Hide")}</td>
			{/if} {if $TABLE_VARIANT=='bookmarks'}
			<td class="colhead center">{$REL_LANG->say_by_key('delete')}</td>
			{/if}
<td class="colhead center">{$REL_LANG->say_by_key('added')}</td>
		</tr>






































	




{foreach item=row from=$res} {if $row.label}
<tr>
	<td colspan="{if $IS_MODERATOR}11{else}7{/if}" class="left label">{$row.label}</td>
</tr>
{/if}







<tr{if $row.sticky} class="highlight"{/if}>
 <td align="center" class="b" rowspan="2" width="2%" style="padding: 5px">
  
     {if $row.cat_names}
	{$row.cat_names}
{else} <img border="0" src="themes/releaser2009/images/no.png">
{/if}





</td>

  <td colspan="12" class="b" align="left">

  

<div class="name_browse">
	<a href="{$REL_SEO->make_link("details","id",$row.id,"name",translit($row.name))}"><b>{$row.name}</b></a>
	{if $row.new}&nbsp;<img title="{$REL_LANG->say_by_key('new')}" src="pic/new.png" />{/if}</div>
	{if $CURUSER.id == $row.owner || $IS_MODERATOR}<small><a id='descr'	href="{$REL_SEO->make_link("edit","id",$row.id)}"><img border="0" src="pic/pen.gif" title="{$REL_LANG->say_by_key('edit')}" /></a></small>{/if}
	{if $TABLE_VARIANT<>'bookmarks'&&$CURUSER} <small><a href="{$REL_SEO->make_link("bookmark","torrent",$row.id,"name",translit($row.name))}"><img	border="0" src="pic/bookmark.gif" title="{$REL_LANG->say_by_key('bookmark_this')}" /></a></small>{/if}
	{if $row.images}
	<div class="cat_pic"><small><a href="javascript:$.facebox({ image:'{$row.images}'});"><img border="0" src="pic/poster.gif" title="{$REL_LANG->say_by_key('poster')}" /></a></small></div>
	{/if}














</td>
</tr>


<tr>
  <td width="45%" class="a" align="left">
    <table cellspacing="0" cellpadding="3" width="100%">
      <tbody>
        <tr>
          <td colspan="2" class="a">
      <b> {$REL_LANG->_('Tags')}:</b>&nbsp;{$row.tags}
     
          </td>
          <td align="center" width="15%" class="a">
            <br>
           <a class="download" href="{$REL_SEO->make_link("download","id",$row.id,"name",translit($row.name))}" onclick="javascript:$.facebox({ ajax:'{$REL_SEO->make_link("download","id",$row.id,"name",translit($row.name))}'}); return false;"><img src="pic/download.gif" border="0"	title="{$REL_LANG->say_by_key('download')}" /></a>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
  	{if $TABLE_VARIANT=='mytorrents'}
	<td class="row2" align="center">{if $row.visible}<font color="green"><b>{$REL_LANG->say_by_key('yes')}</b></font>
	{else}<font color="red">"{$REL_LANG->say_by_key('no')}</font>{/if}</td>
	{/if}

	<td class="row2" align="center"><b><a href="{$REL_SEO->make_link("details","id",$row.id,"name",translit($row.name))}#comments">{$row.comments}</a></b></td>
	{if $REL_CONFIG.use_ttl}
	<td class="center">{$row.ttl}</td>
	{/if}
	<td class="row2" align="center">{$row.size}</td>
	<td class="row2" align="center">{$row.seed_col}</td>
	{if $IS_MODERATOR&&$TABLE_VARIANT=='index'}
	<td class="row2" align="center">{if $row.username}<a href="{$REL_SEO->make_link("userdetails","id",$row.owner,"username",translit($row.username))}"><b>{get_user_class_color($row.class,$row.username)}</b></a>{else}<i>{$REL_LANG->_("Anonymous")}</i>{/if}</td>
	<td class="row2" align="center">{if !$row.moderated}<font color="green"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<font color="red"><b>{$REL_LANG->say_by_key('yes')}</b></font>{/if}</td>
	<td class="row2" align="center">{if !$row.moderatedby}<font	color="red"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<a	href="{$REL_SEO->make_link("userdetails","id",$row.moderatedby,"username",translit($row.modname))}">{get_user_class_color($row.modclass,$row.modname)}</a>{/if}</td>
	<td class="row2" align="center">{if !$row.banned}<font color="green"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<font	color="red"><b>{$REL_LANG->say_by_key('yes')}</b></font>{/if}</td>
	<td class="row2" align="center">{if $row['visible']}<font color="green"><b>{$REL_LANG->say_by_key('no')}</b></font>{else}<font color="red"><b>{$REL_LANG->say_by_key('yes')}</b></font>{/if}</td>
	{/if} {if $TABLE_VARIANT=='bookmarks'}
	<td class="row2" align="center"><input type="checkbox" name="delbookmark[]" value="{$row.id}" /></td>
	{/if}




<td class="row2" align="center">{mkprettymonth($row.added)}</td>
</tr>






















{/foreach}{if !pagercheck()}
	</tbody>
</table>
{/if}</div>
