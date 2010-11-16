<div id="debug">
<pre>
{$query|print_r}
</pre>
{if $REL_CRON['cron_is_native']}{$REL_LANG->_("Scheduled jobs activating by native method")}
{else}{$REL_LANG->_("Scheduled jobs activating from cron")}<br/>{/if}
{if !$REL_CRON.in_cleanup} {$REL_LANG->say_by_key('cleanup_not_running')}<br />{/if}
{if $REL_CRON.remotecheck_disabled}
{$REL_LANG->say_by_key('remotecheck_disabled')}
{elseif !$REL_CRON.in_remotecheck}
{$REL_LANG->say_by_key('remotecheck_not_running')}
{else}
{$REL_LANG->say_by_key('remotecheck_is_running')}
{/if}<br/>
{sprintf($REL_LANG->say_by_key('num_cleaned'),$REL_CRON.num_cleaned)}<br />
{sprintf($REL_LANG->say_by_key('num_checked'),$REL_CRON.num_checked)}<br />
{$REL_LANG->say_by_key('last_cleanup')} {mkprettytime($REL_CRON.last_cleanup,true,true)} ({get_elapsed_time($REL_CRON.last_cleanup)} {$REL_LANG->say_by_key('ago')})<br />
{$REL_LANG->say_by_key('last_remotecheck')} {mkprettytime($REL_CRON.last_remotecheck,true,true)} ({get_elapsed_time($REL_CRON.last_remotecheck)} {$REL_LANG->say_by_key('ago')})</div><br />
<div align="center"><font color="red"><b>{$REL_LANG->say_by_key('in_debug')}</b></font>
<div class="copyright">{$PAGE_GENERATED}</div>
</div>