<div id="forum">
<h1>{$REL_LANG->_('Site forum listing')}</h1>
<h2>{$REL_LANG->_('You currently in: %s',$ROUTE)}</h2>
<div class="pagetop">
<form action="{$REL_SEO->make_link('forum')}" method="get"><span
	class="textselect fleft">{$REL_LANG->_('Jump to')}:</span> {$JUMP_TO}</form>
</div>
<div class="createbotton"><a
	href="{$REL_SEO->make_link('forum','a','newtopic','cat',$curcat)}"
	class="createtopic">{$REL_LANG->_('Create new topic')}</a></div>

<table id="forumTopicTable">
	<tbody class="highlighted">
		<tr>
			<td class="colhead">{$REL_LANG->_('Topic name')}</td>
			<td class="colhead">{$REL_LANG->_('Posts')}</td>
			<td class="colhead">{$REL_LANG->_('Started at')}</td>
			<td class="colhead">{$REL_LANG->_('Author')}</td>
			<td class="colhead">{$REL_LANG->_('Last post')}</td>
			<td class="colhead">{$REL_LANG->_('Closed')}</td>
		</tr>
		{foreach from=$TOPICS item=t}
		<tr>
			<td><a
				href="{$REL_SEO->make_link('forum','a','viewtopic','id',$t.id,'subject',$t.subject|translit,'p',"{$t.lastposted_id}")}">{$t.subject}</a></td>
			<td class="center">{$t.posts}</td>
			<td>{$t.started}</td>
			<td>{$t.author}</td>
			<td>{$t.lastposted_time}<br />
			{$t.lastposted_user} <a
				href="{$REL_SEO->make_link('forum','a','viewtopic','id',$t.id,'subject',$t.subject|translit,'p',"{$t.lastposted_id}#comm{$t.lastposted_id}")}">{$REL_LANG->_('Go')}</a></td>
			<td class="center">{if
			$t.closed}{$REL_LANG->_('Yes')}{else}{$REL_LANG->_('No')}{/if}</td>
		</tr>
		{/foreach}

</table>
<div class="pagebottom">
<form action="{$REL_SEO->make_link('forum')}" method="get"><span
	class="textselect fleft">{$REL_LANG->_('Jump to')}:</span> {$JUMP_TO}</form>
</div>
</div>
