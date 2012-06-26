<form action="{$REL_SEO->make_link('cronadmin')}" method="POST">
<table width="100%" border="1">

<tr>
    <td align="center" colspan="2" class="colhead">{$REL_LANG->_('Scheduled jobs activation method')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Scheduled jobs activation method')}:<br/>
        <small>
            *{$REL_LANG->_('You can use built-in functions or crontab. You must edit /etc/crontab corresponding your configuration')}</small>
    </td>
    <td><select name="cron_is_native">
        <option value="1"
                {if $REL_CRON['cron_is_native']==1}selected="selected"{/if}>{$REL_LANG->_('Native')}</option>
        <option value="0"
                {if $REL_CRON['cron_is_native']==0}selected="selected"{/if}>{$REL_LANG->_('crontab')}</option>
    </select>{if $REL_CRON['cron_is_native']==0} <a
            href="{$REL_SEO->make_link('cronadmin','a','gencrontab')}">{$REL_LANG->_("Generate crontab entries")}</a>{/if}
    </td>
</tr>
<tr>
    <td align="center" colspan="2" class="colhead">{$REL_LANG->_('Multitracker settings')} | <a
            href="{$REL_SEO->make_link('retrackeradmin')}">{$REL_LANG->_('Go to retracker settings')}</a></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Disable remote peers amout fetching')}:<br/>
        <small>
            *{$REL_LANG->_('This function runs in background mode, so you will wait for some time to disable it. You can see current state of function on the left.')}</small>
    </td>
    <td><select name="remotecheck_disabled">
        <option value="1"
                {if $REL_CRON['remotecheck_disabled']==1}selected="selected"{/if}>{$REL_LANG->_('Yes')}</option>
        <option value="0"
                {if $REL_CRON['remotecheck_disabled']==0}selected="selected"{/if}>{$REL_LANG->_('No')}</option>
    </select> {$remotecheck_state}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Remote check timeout')}:<br/>
        <small>*{$REL_LANG->_('After this time releases will be checked again')}</small>
    </td>
    <td><input type="text" name="remotepeers_cleantime" size="3" value="{$REL_CRON['remotepeers_cleantime']}">
        <b>{$REL_LANG->_('seconds')}</b></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of checking trackers')}:<br/>
        <small>
            *{$REL_LANG->_('On a big trackers/indexers check must run in several passes. If <b>zero</b>, all trackers will be checked in each run.')}</small>
    </td>
    <td><input type="text" name="remote_trackers" size="5"
               value="{$REL_CRON['remote_trackers']}"> {$REL_LANG->_('trackers')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of fail requests to delete trackers')}:<br/>
        <small>
            *{$REL_LANG->_('After this amout of fail requests tracker will be deleted from database. If <b>zero</b>, all trackers will be checked every time.')}</small>
    </td>
    <td><input type="text" name="remote_trackers_delete" size="5"
               value="{$REL_CRON['remote_trackers_delete']}"> {$REL_LANG->_('requests')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Remote check delay')}:<br/>
        <small>
            *{$REL_LANG->_('On a high load it is recommended to increase this parameter. If <b>zero</b>, script will be executed every time without delays')}
        {if $REL_CRON['cron_is_native']==0}<br/><font
                color="red">{$REL_LANG->_('You must edit /etc/crontab when changing this value.')} <a
                href="{$REL_SEO->make_link('cronadmin','a','gencrontab')}">{$REL_LANG->_("Generate crontab entries")}</a>{/if}
        </small>
    </td>
    <td><input type="text" name="remotecheck_interval" size="3"
               value="{$REL_CRON['remotecheck_interval']}"> {$REL_LANG->_('seconds')}</td>
</tr>


<tr>
    <td align="center" colspan="2" class="colhead">{$REL_LANG->_('Cleanup settings')}</td>
</tr>

<tr>
    <td>{$REL_LANG->_('Days to delete unactivated accounts')}:</td>
    <td><input type="text" name="signup_timeout" size="2"
               value="{$REL_CRON['signup_timeout']}"> {$REL_LANG->_('days')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Time in seconds to mark release as dead')}:</td>
    <td><input type="text" name="max_dead_torrent_time" size="3"
               value="{$REL_CRON['max_dead_torrent_time']}"> {$REL_LANG->_('seconds')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Database cleanup/sync delay')}:
    {if $REL_CRON['cron_is_native']==0}<br/><font
            color="red">{$REL_LANG->_('You must edit /etc/crontab when changing this value.')} <a
            href="{$REL_SEO->make_link('cronadmin','a','gencrontab')}">{$REL_LANG->_("Generate crontab entries")}</a>{/if}
    </td>
    <td><input type="text" name="autoclean_interval" size="4"
               value="{$REL_CRON['autoclean_interval']}"> {$REL_LANG->_('seconds')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Days to delete system private messages')}:</td>
    <td><input type="text" name="pm_delete_sys_days" size="2"
               value="{$REL_CRON['pm_delete_sys_days']}"> {$REL_LANG->_('days')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Days to delete user private messages')}:</td>
    <td><input type="text" name="pm_delete_user_days" size="2"
               value="{$REL_CRON['pm_delete_user_days']}"> {$REL_LANG->_('days')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Time To Live (TTL) of dead releases. Set to zero to disable release deletion.')}:</td>
    <td><input type="text" name="ttl_days" size="3" value="{$REL_CRON['ttl_days']}"> {$REL_LANG->_('days')}</td>
</tr>


<tr>
    <td align="center" colspan="2" class="colhead">{$REL_LANG->_('Multitracker rating system settings')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Is rating system enabled')}:<br/>
        <small>
            *{$REL_LANG->_('This option affects only <b>automatic</b> rating change. Users always can rate each other.')}</small>
    </td>
    <td><select name="rating_enabled">
        <option value="1"
                {if $REL_CRON['rating_enabled']==1}selected="selected"{/if}>{$REL_LANG->_('Yes')}</option>
        <option value="0"
                {if $REL_CRON['rating_enabled']==0}selected="selected"{/if}>{$REL_LANG->_('No')}</option>
    </select></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Days after registration to mark user as newbie (rating system does not affect him)')}:
    </td>
    <td><input type="text" name="rating_freetime" size="2"
               value="{$REL_CRON['rating_freetime']}"> {$REL_LANG->_('days')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Interval of user ratings recounting')}:</td>
    <td><input type="text" name="rating_checktime" size="4"
               value="{$REL_CRON['rating_checktime']}"> {$REL_LANG->_('minutes')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_("Amount of rating to promote to power user")}</td>
    <td><input type="text" size="3" name="promote_rating" value="{$REL_CRON['promote_rating']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of rating, given for release uploading')}:</td>
    <td><input type="text" size="3" name="rating_perrelease" value="{$REL_CRON['rating_perrelease']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of rating, given for invited user registration')}:</td>
    <td><input type="text" size="3" name="rating_perinvite" value="{$REL_CRON['rating_perinvite']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of rating, given for request filling')}:</td>
    <td><input type="text" size="3" name="rating_perrequest" value="{$REL_CRON['rating_perrequest']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of rating, given for seeding')}:<br/>
        <small>
            *{$REL_LANG->_('Detailed formula described on your <a href="%s">rating page</a>',$REL_SEO->make_link('myrating'))}</small>
    </td>
    <td><input type="text" size="3" name="rating_perseed" value="{$REL_CRON['rating_perseed']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of rating, substracted for leeching')}:</td>
    <td><input type="text" size="3" name="rating_perleech" value="{$REL_CRON['rating_perleech']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Amount of rating, substracted for .torrent downloading')}:</td>
    <td><input type="text" size="3" name="rating_perdownload" value="{$REL_CRON['rating_perdownload']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Limit to disable .torrent downloading')}:</td>
    <td><input type="text" size="4" name="rating_downlimit" value="{$REL_CRON['rating_downlimit']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Limit to disable user account')}:</td>
    <td><input type="text" size="4" name="rating_dislimit" value="{$REL_CRON['rating_dislimit']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('Maximal amout of rating')}:</td>
    <td><input type="text" size="4" name="rating_max" value="{$REL_CRON['rating_max']}"></td>
</tr>
<tr>
    <td>{$REL_LANG->_('How much rating points costs one amout of discount')}:</td>
    <td><input type="text" size="2" name="rating_discounttorrent" value="{$REL_CRON['rating_discounttorrent']}">
    </td>
</tr>


<tr>
    <td align="center" colspan="2" class="colhead">{$REL_LANG->_('Other parameters')}</td>
</tr>
<tr>
    <td>{$REL_LANG->_('Interval of ratings cleanup')}:<br/>
        <small>
            *{$REL_LANG->_('After this time user can rate the same instance one more time. If <b>zero</b>, users can rate something only once.')}
    </td>
    <td><input type="text" size="3" name="delete_votes"
               value="{$REL_CRON['delete_votes']}"> {$REL_LANG->_('minutes')}</td>
</tr>

<tr>
    <td align="center" colspan="2"><input type="submit" name="save"
                                          value="{$REL_LANG->_('Save changes')}"><input type="reset"
                                                                                        value="{$REL_LANG->_('Reset')}"><input
            type="submit" name="reset" value="{$REL_LANG->_('Reset cron state')}"></td>
</tr>
<tr>
    <td colspan="2">
        <small>
            *{$REL_LANG->_('Cron state reset is required when scripts unable or invalid to display cron state')}</small>
    </td>
</tr>
</table>
</form>
	