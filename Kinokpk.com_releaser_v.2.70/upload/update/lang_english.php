<?php

// language installer, english

$lang['hello'] = "<h1>If you see E_WARNING messages - it is normal. <stong>DON'T FORGET TO MAKE A BACKUP BEFORE UPDATE</strong></h1><p>Welcome back to Kinokpk.com releaser 2.40 to Kinokpk.com releaser 2.70 updater. Follow the instructions and <font color=\"red\">be careful</font> with general settings</p>";
$lang['agree'] = '<div align="center">You mast to be agreed with GNU GPL:</div>';
$lang['agree_continue'] = '<div align="center"><a href="index.php?step=1">I agree</a> | I deny, and close by browser, removing releaser from my computer</div>';

$lang['ok'] = '<font color="green">Success!</font>';
$lang['continue'] = 'Continue';
$lang['testing_database_connection'] = '<h1 align="center">Warning! Next steps may take a long time, if script doesn\'t ansers you - it\'s normal!</h1>.<hr />';
$lang['next_step_update_db'] = 'Next step will change database<br />';
$lang['mysql_error'] ='<font color="red">MySQL error:</font> ';

$lang['main_settings'] = '<h1 align="center">General configuration of Kinokpk.com releaser 2.70</h1><font color="red">Warning! Read and modify configuration carefuly, it\'s not good to ingore fields marked *.</font><hr />';

$lang['settings_saved'] = 'Settings saved ';
$lang['settings_notice'] = '<p>You configured only general settings of Kinokpk.com releaser 2.70, after setup you can configure cron, retracker etc, all settings in http://ваш_сайт/admincp.php</p>';
$lang['install_complete'] = '<p>Congratulations! Setup of Kinokpk.com releaser 2.70 finished, you can view your site.</p>';
$lang['install_notice'] = '<font color="red">Warning! For your safety, remove folders <b>install</b> and <b>update</b> from your server and set <b>permissions 644 on include/secrets.php</b></font>';
$lang['donate'] = '<p><pre>You can donate to developers, requisites:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168
Yandex.money: 41001423787643,
Paypal: zond80@gmail.com</pre></p><hr /><div align="right"><i>Best regards, Kinokpk.com releaser developers</i></div>';

$lang['step2_descr'] ='<pre>Next step will transfer tags to categories</pre>';
$lang['step3_descr'] = '<pre>Next step will transfer bbcodes to html to use with TinyMCE editor, also release\'s templates structure will be destroyed, all descriptions will be stored in field "descr" in torrents table</pre>';
$lang['step4_descr'] = '<pre>Next step will transfer enum fields to tinyint fields, where it is possible</pre>';
$lang['step5_descr'] ='<pre>Next step will transfer datetime fields to int fields</pre>';
$lang['step6_descr'] ='<pre>Next step will modify links to images</pre>';
$lang['step7_descr'] ='<pre>Next stem will transfer saved torrents to infohashes</pre>';

?>