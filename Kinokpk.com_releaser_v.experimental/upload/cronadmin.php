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

$REL_TPL->stdhead('Настройка cron-функций');

$action = trim((string)$_GET['a']);

/**
 * Generates time-part of crontab line
 * @param int $minval Minutes value
 * @return string Generated part of string
 * @todo Hours, weeks, days.. etc.
 */
function gen_cron_min($minval) {
	//$day = 86400;
	//$hour = 3600;
	if (!$minval) return '*';
	$min = 60;
	$mincount = 0;
	$return = '0';
	if ($minval<$min) {
		while ($mincount<$min) {
			$mincount = $mincount+$minval;
			if ($mincount==60) break;
			$return = $return.",$mincount";
		}
	}
	return $return;
}
if ($action == 'gencrontab') {
	print $REL_LANG->_('This is /etc/crontab lines to add. Edit "/usr/bin/wget" corresponding to your wget location. <a href="%s">Back to cron admincp</a>.',$REL_SEO->make_link('cronadmin')).'<hr/><pre>';
	$mincl = floor($REL_CRON['autoclean_interval']/60);
	$minrm = floor($REL_CRON['remotecheck_interval']/60);
	print gen_cron_min($mincl).'	*	*	*	*	root	/usr/bin/wget -O /dev/null -q '.$REL_CONFIG['defaultbaseurl'].'/cleanup.php > /dev/null 2>&1
	'.gen_cron_min($minrm).'	*	*	*	*	root	/usr/bin/wget -O /dev/null -q '.$REL_CONFIG['defaultbaseurl'].'/remote_check.php > /dev/null 2>&1
	</pre>';
	$REL_TPL->stdfoot();
	die();
}
if (!isset($_POST['save']) && !isset($_POST['reset'])){

	$REL_TPL->begin_frame("Настройка cron-функций");
	print('<form action="'.$REL_SEO->make_link('cronadmin').'" method="POST">');
	print('<table width="100%" border="1">');

	if ($REL_CRON['in_remotecheck'] && $REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="red">Запрос на остановку подан, но скрипт еще выполняется. Подождите пожалуйста</font>';
	if (!$REL_CRON['in_remotecheck'] && $REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">Функция остановлена</font>';
	if ($REL_CRON['in_remotecheck'] && !$REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">Функция работает</font>';
	if (!$REL_CRON['in_remotecheck'] && !$REL_CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">Функция в режиме ожидания</font>';
	if ($REL_CRON['cron_is_native']==0) $cron_warn = "<br/><font color=\"red\">{$REL_LANG->_('You must edit /etc/crontab when changing this value.')} <a href=\"{$REL_SEO->make_link('cronadmin','a','gencrontab')}\">{$REL_LANG->_("Generate crontab entries")}</a>";
	print('<tr><td align="center" colspan="2" class="colhead">'.$REL_LANG->_('Scheduled jobs activation method').'</td></tr>');
	print ('<tr><td>'.$REL_LANG->_('Scheduled jobs activation method').':<br /><small>*'.$REL_LANG->_('You can use built-in functions or crontab. You must edit /etc/crontab corresponding your configuration.').'</small></td><td><select name="cron_is_native"><option value="1" '.($REL_CRON['cron_is_native']==1?"selected":"").'>'.$REL_LANG->_('Native').'</option><option value="0" '.($REL_CRON['cron_is_native']==0?"selected":"").'>'.$REL_LANG->_('crontab').'</option></select>'.($REL_CRON['cron_is_native']==0?" <a href=\"{$REL_SEO->make_link('cronadmin','a','gencrontab')}\">{$REL_LANG->_("Generate crontab entries")}</a>":"").'</td></tr>');
	print('<tr><td align="center" colspan="2" class="colhead">Настройки мультитрекерной части | »» <a href="'.$REL_SEO->make_link('retrackeradmin').'">К управлению ретрекером</a></td></tr>');
	print('<tr><td>Отключить функцию получения удаленных пиров:<br /><small>*Так как эта функция выполняется в фоновом режиме, на ее отключение может потребоваться некоторое время. Слева от значения указано текущее состояние функии.</small></td><td><select name="remotecheck_disabled"><option value="1" '.($REL_CRON['remotecheck_disabled']==1?"selected":"").'>Да</option><option value="0" '.($REL_CRON['remotecheck_disabled']==0?"selected":"").'>Нет</option></select> '.$remotecheck_state .'</td></tr>');
	print('<tr><td>Время перепроверки удаленных пиров:<br /><small>*После N секунд торренты ставятся на проверку заново.</small></td><td><input type="text" name="remotepeers_cleantime" size="3" value="'.$REL_CRON['remotepeers_cleantime'].'"> <b>секунд</b></td></tr>');
	print('<tr><td>Сколько трекеров проверять за раз:<br/><small>*На больших трекерах, таких как torrentsbook.com, необходимо ограничить количество проверяемых трекеров. При <b>нуле</b> будут проверены все мульитрекерные трекера</small></td><td><input type="text" name="remote_trackers" size="5" value="'.$REL_CRON['remote_trackers'].'">трекеров</td></tr>');
	print('<tr><td>Интервал между проверками:<br/><small>*При сильной нагрузке желательно увеличить этот параметр. При <b>нуле</b> скрипт будет выполняться постоянно'.$cron_warn.'</small></td><td><input type="text" name="remotecheck_interval" size="3" value="'.$REL_CRON['remotecheck_interval'].'">секунд</td></tr>');


	print('<tr><td align="center" colspan="2" class="colhead">Настройки очистки</td></tr>');

	print('<tr><td>Количество дней, по прошествии которых удаляются неактивированные аккаунты:</td><td><input type="text" name="signup_timeout" size="2" value="'.$REL_CRON['signup_timeout'].'">дней</td></tr>');
	print('<tr><td>Время в сек, после которых торрент считается мертвым:</td><td><input type="text" name="max_dead_torrent_time" size="3" value="'.$REL_CRON['max_dead_torrent_time'].'">секунд</td></tr>');
	print('<tr><td>Время очистки БД в секундах:'.($cron_warn?'<br/><small>'.$cron_warn.'</small>':'').'</td><td><input type="text" name="autoclean_interval" size="4" value="'.$REL_CRON['autoclean_interval'].'">секунд</td></tr>');
	print('<tr><td>Количество дней для очистки личных сообщений от системы:</td><td><input type="text" name="pm_delete_sys_days" size="2" value="'.$REL_CRON['pm_delete_sys_days'].'">дней</td></tr>');
	print('<tr><td>Количество дней для очистки личных сообщений от пользователя:</td><td><input type="text" name="pm_delete_user_days" size="2" value="'.$REL_CRON['pm_delete_user_days'].'">дней</td></tr>');
	print('<tr><td>Время жизни мертвого торрента в днях:</td><td><input type="text" name="ttl_days" size="3" value="'.$REL_CRON['ttl_days'].'">дней</td></tr>');


	print('<tr><td align="center" colspan="2" class="colhead">Параметры мульитрекерной рейтинговой системы</td></tr>');
	print('<tr><td>Рейтинговая система включена:<br /><small>*Эта опция отвечает только <b>автоматическое</b> изменение рейтинга системой и ограничения, связанные с ним. Пользователи в любом случае смогут оценивать действия друг друга, но эти оценки не будут влиять ни на что.</small></td><td><select name="rating_enabled"><option value="1" '.($REL_CRON['rating_enabled']==1?"selected":"").'>Да</option><option value="0" '.($REL_CRON['rating_enabled']==0?"selected":"").'>Нет</option></select></td></tr>');
	print('<tr><td>Время, в течении которого пользователь считается новичком (рейтинговая система на него не действует):</td><td><input type="text" name="rating_freetime" size="2" value="'.$REL_CRON['rating_freetime'].'">дней</td></tr>');
	print('<tr><td>Интервал менжу пересчетом рейтинга для пользователей:</td><td><input type="text" name="rating_checktime" size="4" value="'.$REL_CRON['rating_checktime'].'">минут</td></tr>');
	print('<tr><td>'.$REL_LANG->_("Amount of rating to promote to power user").'</td><td><input type="text" size="3" name="promote_rating" value="'.$REL_CRON['promote_rating'].'"></td></tr>');
	print('<tr><td>Количество рейтинга, даваемое пользователю за заливку релиза:</td><td><input type="text" size="3" name="rating_perrelease" value="'.$REL_CRON['rating_perrelease'].'"></td></tr>');
	print('<tr><td>Количество рейтинга, даваемое пользователю за регистрацию приглашенного пользователя:</td><td><input type="text" size="3" name="rating_perinvite" value="'.$REL_CRON['rating_perinvite'].'"></td></tr>');
	print('<tr><td>Количество рейтинга, даваемое пользователю за выполнение запроса:</td><td><input type="text" size="3" name="rating_perrequest" value="'.$REL_CRON['rating_perrequest'].'"></td></tr>');
	print('<tr><td>Количество рейтинга, даваемое пользователю за сидирование:<br /><small>*Точная формула для конкретного пользователя указана в '.$REL_SEO->make_link('myrating').'</small></td><td><input type="text" size="3" name="rating_perseed" value="'.$REL_CRON['rating_perseed'].'"></td></tr>');
	print('<tr><td>Количество рейтинга, отнимаемое у пользователя за отсуствие раздач:</td><td><input type="text" size="3" name="rating_perleech" value="'.$REL_CRON['rating_perleech'].'"></td></tr>');
	print('<tr><td>Количество рейтинга, отнимаемое у пользователя за скачивание релиза:</td><td><input type="text" size="3" name="rating_perdownload" value="'.$REL_CRON['rating_perdownload'].'"></td></tr>');
	print('<tr><td>Лимит запрета скачивания торрентов:</td><td><input type="text" size="4" name="rating_downlimit" value="'.$REL_CRON['rating_downlimit'].'"></td></tr>');
	print('<tr><td>Лимит отключения аккаунта:</td><td><input type="text" size="4" name="rating_dislimit" value="'.$REL_CRON['rating_dislimit'].'"></td></tr>');
	print('<tr><td>Максимальное количество рейтинга:</td><td><input type="text" size="4" name="rating_max" value="'.$REL_CRON['rating_max'].'"></td></tr>');
	print('<tr><td>Сколько единиц рейтинга стоит 1 единица откупа:</td><td><input type="text" size="2" name="rating_discounttorrent" value="'.$REL_CRON['rating_discounttorrent'].'"></td></tr>');


	print('<tr><td align="center" colspan="2" class="colhead">Прочие параметры</td></tr>');
	print('<tr><td>Интервал реанонса (обновления статистики в клиентах):</td><td><input type="text" size="5" name="announce_interval" value="'.$REL_CRON['announce_interval'].'">минут</td></tr>');
	print('<tr><td>Интервал очистки данных о изменениии кармы/рейтинга:<br /><small>*После указанного времени пользователь сможет изменять рейтинг еще раз.<br />*Данное значение не может быть меньше интервала очистки, желательно, чтобы оно было кратно ему.<br />*Оставьте поле пустым, или 0, если хотите, чтобы рейтинг запоминался навсегда</td><td><input type="text" size="3" name="delete_votes" value="'.$REL_CRON['delete_votes'].'">минут</td></tr>');

	print('<tr><td align="center" colspan="2"><input type="submit" name="save" value="Сохранить изменения"><input type="reset" value="Сбросить"><input type="submit" name="reset" value="Сбросить статистику cron"></td></tr>
<tr><td colspan="2"><small>*Сброс статистики cron необходим, если скрипты неверно отображают состояние cron-функций, следить за выполнением скриптов cron удобно через <a href="http://httpd.apache.org/docs/2.0/mod/mod_status.html">mod_status</a> для apache</small></td></tr></table></form>');
	$REL_TPL->end_frame();
}
elseif (isset($_POST['reset'])) {
	sql_query("UPDATE cron SET cron_value=0 WHERE cron_name IN ('last_cleanup','last_remotecheck','in_cleanup','in_remotecheck','num_cleaned','num_checked')");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('cron_state_reseted'));
}
elseif (isset($_POST['save'])) {

	$reqparametres = array('cron_is_native','max_dead_torrent_time','signup_timeout','autoclean_interval','pm_delete_sys_days','pm_delete_user_days','ttl_days','remotecheck_disabled','announce_interval','delete_votes','remote_trackers','rating_enabled','remotecheck_interval');

	$multi_param = array('remotepeers_cleantime');

	$rating_param = array('rating_freetime','promote_rating','rating_perseed','rating_perinvite','rating_perrequest','rating_checktime','rating_perrelease','rating_dislimit','rating_downlimit', 'rating_perleech', 'rating_perdownload', 'rating_discounttorrent','rating_max');
	$updateset = array();

	foreach ($reqparametres as $param) {
		if (!isset($_POST[$param]) && (($param != 'rating_enabled') || ($param != 'delete_votes') || ($param != 'remote_trackers')))  { stdmsg($REL_LANG->say_by_key('error'),"Некоторые поля не заполнены ($param)",'error'); $REL_TPL->stdfoot(); die; }
		$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
	}

	if ($_POST['remotecheck_disabled'] == 0) {
		foreach ($multi_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($REL_LANG->say_by_key('error'),"Некоторые поля для мультитрекерности не заполнены",'error'); $REL_TPL->stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}
	}

	if ($_POST['rating_enabled']) {
		foreach ($rating_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($REL_LANG->say_by_key('error'),"Некоторые поля для рейтинговой системы не заполнены",'error'); $REL_TPL->stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}
	}

	foreach ($updateset as $query) sql_query($query);
	safe_redirect($REL_SEO->make_link('cronadmin'),3);
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('cron_settings_saved'));
}
$REL_TPL->begin_frame('Текущее состояние cron:');
print ('<table width="100%"><tr><td>');
if (!$REL_CRON['in_cleanup']) print $REL_LANG->say_by_key('cleanup_not_running').'<br />';
if (!$REL_CRON['in_remotecheck']) print $REL_LANG->say_by_key('remotecheck_not_running').'<br />';
print sprintf($REL_LANG->say_by_key('num_cleaned'),$REL_CRON['num_cleaned'])."<br />";
print sprintf($REL_LANG->say_by_key('num_checked'),$REL_CRON['num_checked'])."<br />";
print $REL_LANG->say_by_key('last_cleanup').' '.mkprettytime($REL_CRON['last_cleanup'],true,true)." (".get_elapsed_time($REL_CRON['last_cleanup'])." {$REL_LANG->say_by_key('ago')})<br />";
print $REL_LANG->say_by_key('last_remotecheck').' '.mkprettytime($REL_CRON['last_remotecheck'],true,true)." (".get_elapsed_time($REL_CRON['last_remotecheck'])." {$REL_LANG->say_by_key('ago')})<br />";
print ('</td></tr></table>');
$REL_TPL->end_frame();
$REL_TPL->stdfoot();

?>