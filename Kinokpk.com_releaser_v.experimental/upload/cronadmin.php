<?php
/**
 * CRONJOB administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();
get_privilege('cronadmin');
httpauth();

$REL_TPL->stdhead($REL_LANG->_('Sheduled jobs administration'));

$action = trim((string)$_GET['a']);

$REL_TPL->assignByRef('REL_CRON', $REL_CRON);
/**
 * Generates time-part of crontab line
 * @param int $minval Minutes value
 * @return string Generated part of string
 * @todo Hours, weeks, days.. etc.
 */
function gen_cron_min($minval)
{
    //$day = 86400;
    //$hour = 3600;
    if (!$minval) return '*';
    $min = 60;
    $mincount = 0;
    $return = '0';
    if ($minval < $min) {
        while ($mincount < $min) {
            $mincount = $mincount + $minval;
            if ($mincount == 60) break;
            $return = $return . ",$mincount";
        }
    }
    return $return;
}

if ($action == 'gencrontab') {
    $mincl = floor($REL_CRON['autoclean_interval'] / 60);
    $minrm = floor($REL_CRON['remotecheck_interval'] / 60);
    $REL_TPL->assign('mincl', $mincl);
    $REL_TPL->assign('minrm', $minrm);
    $REL_TPL->output('crontab');
    $REL_TPL->stdfoot();
    die();
}
if (!isset($_POST['save']) && !isset($_POST['reset'])) {

    $REL_TPL->begin_frame($REL_LANG->_('Scheduled jobs settings'));

    if ($REL_CRON['in_remotecheck'] && $REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="red">' . $REL_LANG->_('Terminate request sent, but script is still alive. Wait please...') . '</font>';
    if (!$REL_CRON['in_remotecheck'] && $REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">' . $REL_LANG->_('Function stopped') . '</font>';
    if ($REL_CRON['in_remotecheck'] && !$REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">' . $REL_LANG->_('Function is working') . '</font>';
    if (!$REL_CRON['in_remotecheck'] && !$REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">' . $REL_LANG->_('Function is waiting') . '</font>';

    $REL_TPL->assign('remotecheck_state', $remotecheck_state);
    $REL_TPL->output();
    $REL_TPL->end_frame();
} elseif (isset($_POST['reset'])) {
    $REL_DB->query("UPDATE cron SET cron_value=0 WHERE cron_name IN ('last_cleanup','last_remotecheck','in_cleanup','in_remotecheck','num_cleaned','num_checked')");
    $REL_TPL->stdmsg($REL_LANG->say_by_key('success'), $REL_LANG->say_by_key('cron_state_reseted'));
}
elseif (isset($_POST['save'])) {

    $reqparametres = array('cron_is_native', 'max_dead_torrent_time', 'signup_timeout', 'autoclean_interval', 'pm_delete_sys_days', 'pm_delete_user_days', 'ttl_days', 'remotecheck_disabled', 'delete_votes', 'remote_trackers', 'rating_enabled', 'remotecheck_interval', 'remote_trackers_delete');

    $multi_param = array('remotepeers_cleantime');

    $rating_param = array('rating_freetime', 'promote_rating', 'rating_perseed', 'rating_perinvite', 'rating_perrequest', 'rating_checktime', 'rating_perrelease', 'rating_dislimit', 'rating_downlimit', 'rating_perleech', 'rating_perdownload', 'rating_discounttorrent', 'rating_max');
    $updateset = array();

    foreach ($reqparametres as $param) {
        if (!isset($_POST[$param]) && (($param != 'rating_enabled') || ($param != 'delete_votes') || ($param != 'remote_trackers'))) {
            $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->_('Field %s does not filled', $param), 'error');
            $REL_TPL->stdfoot();
            die;
        }
        $updateset[] = "UPDATE cron SET cron_value=" . $REL_DB->sqlesc($_POST[$param]) . " WHERE cron_name='$param'";
    }

    if ($_POST['remotecheck_disabled'] == 0) {
        foreach ($multi_param as $param) {
            if (!$_POST[$param] || !isset($_POST[$param])) {
                $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->_('Some fields of multitracker settings does not filled'), 'error');
                $REL_TPL->stdfoot();
                die;
            }
            $updateset[] = "UPDATE cron SET cron_value=" . $REL_DB->sqlesc($_POST[$param]) . " WHERE cron_name='$param'";
        }
    }

    if ($_POST['rating_enabled']) {
        foreach ($rating_param as $param) {
            if (!isset($_POST[$param])) {
                $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->_('Some fields of rating system settings does not filled'), 'error');
                $REL_TPL->stdfoot();
                die;
            }
            $updateset[] = "UPDATE cron SET cron_value=" . $REL_DB->sqlesc($_POST[$param]) . " WHERE cron_name='$param'";
        }
    }

    foreach ($updateset as $query) $REL_DB->query($query);
    safe_redirect($REL_SEO->make_link('cronadmin'), 3);
    $REL_TPL->stdmsg($REL_LANG->say_by_key('success'), $REL_LANG->say_by_key('cron_settings_saved'));
}
$REL_TPL->begin_frame($REL_LANG->_('Current cron state:'));
$REL_TPL->output('cronstate');
$REL_TPL->end_frame();
$REL_TPL->stdfoot();

?>
