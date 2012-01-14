<table width="100%"><tr><td>
{if !$REL_CRON['in_cleanup']}{$REL_LANG->say_by_key('cleanup_not_running')}<br/>{/if}
{if !$REL_CRON['in_remotecheck']}{$REL_LANG->say_by_key('remotecheck_not_running')}<br/>{/if}
{sprintf($REL_LANG->say_by_key('num_cleaned'),$REL_CRON['num_cleaned'])}<br/>
{sprintf($REL_LANG->say_by_key('num_checked'),$REL_CRON['num_checked'])}<br/>
{$REL_LANG->say_by_key('last_cleanup')} {mkprettytime($REL_CRON['last_cleanup'],true,true)} ({get_elapsed_time($REL_CRON['last_cleanup'])} {$REL_LANG->say_by_key('ago')})<br />
{$REL_LANG->say_by_key('last_remotecheck')} {mkprettytime($REL_CRON['last_remotecheck'],true,true)} ({get_elapsed_time($REL_CRON['last_remotecheck'])} {$REL_LANG->say_by_key('ago')})<br />
</td></tr></table>