<?php

// language installer, russian

$lang['hello'] = "<h1>If you see E_WARNING - this is normal. <stong>DO BACKUP BEFORE AN UPDATE</strong></h1><p>Welcome to Kinokpk.com releaser 2.70 to Kinokpk.com releaser 3.00 updater. Follow instructions and <font color=\"red\">draw your attention</font> to setting up releaser<br/><br/>This version is compatible with PHP 5.3, you must to turn off all magic_quotes in PHP config</p>";
$lang['agree'] = '<div align="center">To continue you must agree with following:</div>';
$lang['agree_continue'] = '<div align="center"><a href="index.php?step=1">I agree, continue installing</a> | I disagree, close my browser and remove this script for my computer</div>';

$lang['ok'] = '<font color="green">Success</font>';

$lang['continue'] = 'Continue';

$lang['next_step_update_db'] = 'Next step will update database<br />';
$lang['mysql_error'] ='<font color="red">Error in MySQL:</font> ';

$lang['install_complete'] = '<p>Congratulations! Install of Kinokpk.com releaser 3.00 completed, now you can access your site.</p>';
$lang['install_notice'] = '<font color="red">WARNING! For your safety, delete folders <b>install</b> and <b>update</b> from server and set <b>chmod 644 on include/secrets.php</b></font>';
$lang['donate'] = '<p><pre>You always can donate:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168,
Yandex.money: 41001423787643,
Paypal: zond80@gmail.com</pre></p><hr /><div align="right"><i>Best regards, Kinokpk.com releaser developers</i></div>';
$lang['secret_notice'] = 'Something random symbols';
$lang['write_config_error'] = '<font color="red">Configuration file write error, try again</font> <a href="javascript:history.go(-1);">Go back</a>';
$lang['wrong_chmod'] = '<font color="red">You did not sed required chmod to files</font> <a href="javascript:history.go(-1);">Go back</a>';
$lang['step2_descr'] ='<pre>Next step will rebuild tracker system</pre>';
$lang['step3_descr'] = '<pre>Next step will rebuild spoiler, it may take a long time</pre>';
$lang['step4_descr'] = '<pre>Next step is general configuration</pre>';
$lang['step5_descr'] ='<pre>Next step is cron and rating system configuration</pre>';
$lang['step6_descr'] ='<pre>Nex step is setting up cookie secret <i>users must relogin after this action</i><br/><font color="red">Set chmod 666 include/secrets.php during this step</font></pre>';
?>