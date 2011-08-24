<form action="{$REL_SEO->make_link('configadmin','action','save')}" method="POST">
	<table width="100%" border="1">

	<tr><td align="center" colspan="2" class="colhead">{$REL_LANG->_('General settings')}</td></tr>

	<tr><td>{$REL_LANG->_("Is site online?")}:</td><td><select name="siteonline"><option value="1"{if $REL_CONFIG['siteonline']==1} selected="selected"{/if}>{$REL_LANG->_("Yes")}</option><option value="0"{if $REL_CONFIG['siteonline']==0} selected="selected"{/if}>{$REL_LANG->_("No")}</option></select></td></tr>
	<tr><td>{$REL_LANG->_("Is forum enabled?")}:</td><td><select name="forum_enabled"><option value="1"{if $REL_CONFIG['forum_enabled']==1} selected="selected"{/if}>{$REL_LANG->_("Yes")}</option><option value="0"{if $REL_CONFIG['forum_enabled']==0} selected="selected"{/if}>{$REL_LANG->_("No")}</option></select>{$REL_LANG->_('<a href="%s">Go to forum administration</a>',$REL_SEO->make_link('forumadmin'))}</td></tr>

	<tr><td>{$REL_LANG->_('Site address (without / ending slash)')}:</td><td><input type="text" name="defaultbaseurl" size="30" value="{$REL_CONFIG['defaultbaseurl']}"> <br/>{$REL_LANG->_('For example')}, "http://www.kinokpk.com"</td></tr>
	<tr><td>{$REL_LANG->_('Site name (title)')}:</td><td><input type="text" name="sitename" size="80" value="{$REL_CONFIG['sitename']}"> <br/>{$REL_LANG->_('E.g., "Releaser of japanese jerboas')}</td></tr>
	<tr><td>{$REL_LANG->_('Site description (meta description)')}:</td><td><input type="text" name="description" size="80" value="{$REL_CONFIG['description']}"> <br/>{$REL_LANG->_('E.g., "The fastest japanese jerboas download without SMS here!"')}</td></tr>
	<tr><td>{$REL_LANG->_('Site keywords (meta keywords)')}:</td><td><input type="text" name="keywords" size="80" value="{$REL_CONFIG['keywords']}"> <br/>{$REL_LANG->_('E.g., "download, japan, jerboas, lol"')}</td></tr>
	<tr><td>{$REL_LANG->_('Site email')}:</td><td><input type="text" name="siteemail" size="30" value="{$REL_CONFIG['siteemail']}"> <br/>{$REL_LANG->_('E.g., "noreply@localhost"')}</td></tr>
	<tr><td>{$REL_LANG->_('Admin email')}:</td><td><input type="text" name="adminemail" size="30" value="{$REL_CONFIG['adminemail']}"> <br/>{$REL_LANG->_('E.g., "admin@windows.lol"')}</td></tr>
	<tr><td>{$REL_LANG->_('Default language')}:</td><td><input type="text" name="default_language" size="2" value="{$REL_CONFIG['default_language']}">{$REL_LANG->_('Static language system (language to load and full path to file from site root, separated by <b>commas without spaces</b>, e.g. "ru=languages/ru.lang,en=languages/en.lang"). Leave empty to disable')}<br/><input type="text" name="static_language" size="100" value="{$REL_CONFIG['static_language']}"></td></tr>
	<tr><td>{$REL_LANG->_('Default skin for guests (themes/%theme_dir%)')}:</td><td><input type="text" name="default_theme" size="10" value="{$REL_CONFIG['default_theme']}"> {$REL_LANG->_('Default is "kinokpk"')}</td></tr>
	<tr><td>{$REL_LANG->_('Your copyright')}:<br /><small>*{$REL_LANG->_('You can use wildcard {datenow} to display current year')}</small></td><td><input type="text" name="yourcopy" size="60" value="{$REL_CONFIG['yourcopy']}"> <br/>{$REL_LANG->_('E.g., &copy; 1980-{datenow} My perfect brain')}</td></tr>
	<tr><td>{$REL_LANG->_("Site timezone")}:</td><td>{list_timezones('site_timezone',$REL_CONFIG['site_timezone'])}</td></tr>

	<tr><td>{$REL_LANG->_('Use block system (disabling is not recommended)')}:</td><td><select name="use_blocks"><option value="1"{if $REL_CONFIG['use_blocks']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_blocks']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_('Use gzip compression')}:</td><td><select name="use_gzip"><option value="1"{if $REL_CONFIG['use_gzip']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_gzip']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_("Cache templates")}:</td><td><select name="cache_template"><option value="1"{if $REL_CONFIG['cache_template']==1} selected="selected"{/if}>{$REL_LANG->_("Yes")}</option><option value="0"{if $REL_CONFIG['cache_template']==0} selected="selected"{/if}>{$REL_LANG->_("No")}</option></select></td></tr>
	<tr><td>{$REL_LANG->_("Templates cache lifetime")}:</td><td><input name="cache_template_time" size="3" value="{$REL_CONFIG['cache_template_time']}">{$REL_LANG->_("Seconds")}</td></tr>
	<tr><td>{$REL_LANG->_("Cache driver")}:</td><td>{$REL_LANG->_($REL_CACHEDRIVER)}, {$REL_LANG->_('You can change it in include/secrets.php')}</td></tr>
	
	<tr><td>{$REL_LANG->_('Use IP/subnet bans')}:</td><td><select name="use_ipbans"><option value="1"{if $REL_CONFIG['use_ipbans']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_ipbans']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_('Use external XBT tracker')}:</td><td><select name="use_xbt"><option value="1"{if $REL_CONFIG['use_xbt']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_xbt']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select> {$REL_LANG->_('If not, native announcer&scraper will emulate XBT functionality')}</td></tr>

	<tr><td align="center" colspan="2" class="colhead">{$REL_LANG->_('Registration settings')}</td></tr>

	<tr><td>{$REL_LANG->_('Disable registration')}:</td><td><select name="deny_signup"><option value="1"{if $REL_CONFIG['deny_signup']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['deny_signup']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_('Allow registration by invites')}:</td><td><select name="allow_invite_signup"><option value="1"{if $REL_CONFIG['allow_invite_signup']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['allow_invite_signup']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select> {$REL_LANG->_('If general registration disabled, this rule allows ONLY invite registration')}</td></tr>
	<tr><td>{$REL_LANG->_('Site timezone')}:<br /><small>*{$REL_LANG->_('Will be setted to newly registered users')}<br />*{$REL_LANG->_('Displaying for guests')}</small></td><td>{list_timezones('register_timezone',$REL_CONFIG['register_timezone'])}</td></tr>
	<tr><td>{$REL_LANG->_('Verify user emails (aka account activation)')}:</td><td><select name="use_email_act"><option value="1"{if $REL_CONFIG['use_email_act']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_email_act']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_('Use CAPTCHA')}:<br /><small>*{$REL_LANG->_('You must register <a target="_blank" href="http://www.google.com/recaptcha">Google Recaptcha</a> account and get public and private keys to use this option')}</small></td><td><select name="use_captcha"><option  value="1"{if $REL_CONFIG['use_captcha']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_captcha']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_('Recaptcha public key')}:</td><td><input type="text" name="re_publickey" size="80" value="{$REL_CONFIG['re_publickey']}"></td></tr>
	<tr><td>{$REL_LANG->_('Recaptcha private key')}:</td><td><input type="text" name="re_privatekey" size="80" value="{$REL_CONFIG['re_privatekey']}"></td></tr>
	<tr><td>{$REL_LANG->_('Default user notifications')}:<br /><small>*{$REL_LANG->_('Will be setted to newly registered users')}</small></td><td><input type="text" name="default_notifs" size="120" value="{$REL_CONFIG['default_notifs']}"></td></tr>
	<tr><td>{$REL_LANG->_('Default user email notifications')}:<br /><small>*{$REL_LANG->_('Will be setted to newly registered users')}</small></td><td><input type="text" name="default_emailnotifs" size="120" value="{$REL_CONFIG['default_emailnotifs']}"></td></tr>
	<tr><td colspan="2"><small>*{$REL_LANG->_('These types are allowed')}:<br/>
	{$REL_LANG->_('<b>unread</b> - unread private messages')},<br/>
	{$REL_LANG->_('<b>torrents</b> - new releases since last visit')},<br/>
	{$REL_LANG->_('<b>relcomments</b> - new release comments since last visit')},<br/>
	{$REL_LANG->_('<b>pollcomments</b> - new polls comments since last visit')},<br/>
	{$REL_LANG->_('<b>newscomments</b> - new news comments since last visit')},<br/>
	{$REL_LANG->_('<b>usercomments</b> - new user comments since last visit')},<br/>
	{$REL_LANG->_('<b>reqcomments</b> - new requests comments since last visit')},<br/>
	{$REL_LANG->_('<b>rgcomments</b> - new release groups comments since last visit')},<br/>
	{$REL_LANG->_('<b>forumcomments</b> - new forum posts since last visit')},<br/>
	{$REL_LANG->_('<b>friends</b> - new friends requests since last visit')},<br/>
	{$REL_LANG->_('<b>users</b> - new registered users since last visit (with privilege only)')},<br/>
	{$REL_LANG->_('<b>reports</b> - new reports since last visit (with privilege only)')},<br/>
	{$REL_LANG->_('<b>unchecked</b> - new unchecked releases since last visit (with privilege only)')}</small></td></tr>


	<tr><td align="center" colspan="2" class="colhead">{$REL_LANG->_('Restrictions settings')}</td></tr>

	<tr><td>{$REL_LANG->_("Comment hide rating")}:</td><td><input type="text" name="low_comment_hide" size="4" value="{$REL_CONFIG['low_comment_hide']}">{$REL_LANG->_('Points, after which post text will be replaced by "This post too bad"')}</td></tr>
	<tr><td>{$REL_LANG->_("Maximal users signatures length")}:</td><td><input type="text" name="sign_length" size="4" value="{$REL_CONFIG['sign_length']}">{$REL_LANG->_("Characters")}</td></tr>
	<tr><td>{$REL_LANG->_('Maximal user accounts')}:</td><td><input type="text" name="maxusers" size="6" value="{$REL_CONFIG['maxusers']}">{$REL_LANG->_('users (specify 0 to disable limit)')}</td></tr>
	<tr><td>{$REL_LANG->_('Maximal private messages')}:</td><td><input type="text" name="pm_max" size="4" value="{$REL_CONFIG['pm_max']}">{$REL_LANG->_('messages')}</td></tr>
	<tr><td>{$REL_LANG->_('Maximal avatar height')}:</td><td><input type="text" name="avatar_max_width" size="3" value="{$REL_CONFIG['avatar_max_width']}">{$REL_LANG->_('pixels')}</td></tr>
	<tr><td>{$REL_LANG->_('Maximal avatar length')}:</td><td><input type="text" name="avatar_max_height" size="3" value="{$REL_CONFIG['avatar_max_height']}">{$REL_LANG->_('pixels')}</td></tr>
	<tr><td>{$REL_LANG->_('Allow usage of DirectConnect magnets')}:</td><td><select name="use_dc"><option value="1"{if $REL_CONFIG['use_dc']} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0" {if !$REL_CONFIG['use_dc']} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select>{$REL_LANG->_('Go to <a href="%s">Direct Connect Hubs admincp</a>',$REL_SEO->make_link('dchubsadmin'))}</td></tr>
	<tr><td>{$REL_LANG->_('Maximal .torrent size')}:</td><td><input type="text" name="max_torrent_size" size="10" value="{$REL_CONFIG['max_torrent_size']}">{$REL_LANG->_('bytes')}</td></tr>
	<tr><td>{$REL_LANG->_('Maximal amount of images for releases')}:</td><td><input type="text" name="max_images" size="2" value="{$REL_CONFIG['max_images']}"><br/>{$REL_LANG->_('E.g., "2"')}</td></tr>
	<tr><td>{$REL_LANG->_('XXX-categories')}:<br /><small>*{$REL_LANG->_('Releases of this categories will be replaced by XXX-dummy. Users can disable dummies in their account settings')}</small></td><td>{$pron_selector}<br/>*{$REL_LANG->_('You can select more than one by holding CTRL on Linux, CMD on Mac')}</td></tr>

	<tr><td align="center" colspan="2" class="colhead">{$REL_LANG->_('Security settings')}</td></tr>

	<tr><td>{$REL_LANG->_('Flood interval')}:</td><td><input type="text" name="as_timeout" size="10" value="{$REL_CONFIG['as_timeout']}">{$REL_LANG->_('seconds')}</td></tr>
	<tr><td>{$REL_LANG->_('Check last 5 user comments (antispam)')}:</td><td><select name="as_check_messages"><option value="1"{if $REL_CONFIG['as_check_messages']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['as_check_messages']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_("SQL/Cron debug")}:</td><td><select name="debug_mode"><option value="1"{if $REL_CONFIG['debug_mode']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['debug_mode']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_("Language debug")}:</td><td><select name="debug_language"><option value="1"{if $REL_CONFIG['debug_language']==1} selected="selected"{/if}>{$REL_LANG->_("Yes")}</option><option value="0"{if $REL_CONFIG['debug_language']==0} selected="selected"{/if}>{$REL_LANG->_("No")}</option></select> <a href="{$REL_SEO->make_link('langadmin')}">{$REL_LANG->_("Language administration tools")}</a></td></tr>
	<tr><td>{$REL_LANG->_("Template debug")}:</td><td><select name="debug_template"><option value="1"{if $REL_CONFIG['debug_template']==1} selected="selected"{/if}>{$REL_LANG->_("Yes")}</option><option value="0"{if $REL_CONFIG['debug_template']==0} selected="selected"{/if}>{$REL_LANG->_("No")}</option></select></td></tr>

	<tr><td align="center" colspan="2" class="colhead">{$REL_LANG->_('Extras')}</td></tr>

	<tr><td>{$REL_LANG->_('Try to automatically get Kinopoisk.ru trailer for films')}:<br/><small>*{$REL_LANG->_('Works only if release description contains link http://www.kinopoisk.ru/level/1/film/ID_of_movie')}</small></td><td><select name="use_kinopoisk_trailers"><option value="1"{if $REL_CONFIG['use_kinopoisk_trailers']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_kinopoisk_trailers']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>
	<tr><td>{$REL_LANG->_('Amout of releases per page')}:<br /></td><td><input type="text" name="torrentsperpage" size="3" value="{$REL_CONFIG['torrentsperpage']}">{$REL_LANG->_('releases')}</td></tr>
	<tr><td>{$REL_LANG->_('Use TTL (automatically delete dead releases')}:</td><td><select name="use_ttl"><option value="1"{if $REL_CONFIG['use_ttl']==1} selected="selected"{/if}>{$REL_LANG->_('Yes')}</option><option value="0"{if $REL_CONFIG['use_ttl']==0} selected="selected"{/if}>{$REL_LANG->_('No')}</option></select></td></tr>

	<tr><td align="center" colspan="2"><input type="submit" value="{$REL_LANG->_('Save changes')}"><input type="reset" value="{$REL_LANG->_('Reset')}"></td></tr></table></form>
