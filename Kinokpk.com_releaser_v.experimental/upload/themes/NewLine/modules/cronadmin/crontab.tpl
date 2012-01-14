{$REL_LANG->_('This is /etc/crontab lines to add. Edit "/usr/bin/wget" corresponding to your wget location. <a href="%s">Back to cron admincp</a>.',$REL_SEO->make_link('cronadmin'))}<hr/><pre>
{gen_cron_min($mincl)}	*	*	*	*	root	/usr/bin/wget -O /dev/null -q {$REL_CONFIG['defaultbaseurl']}/cleanup.php > /dev/null 2>&1
{gen_cron_min($minrm)}	*	*	*	*	root	/usr/bin/wget -O /dev/null -q {$REL_CONFIG['defaultbaseurl']}/remote_check.php > /dev/null 2>&1
</pre>