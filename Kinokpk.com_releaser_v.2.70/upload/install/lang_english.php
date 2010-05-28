<?php

// language installer, english

$lang['hello'] = "<p>Welcome to Kinokpk.com releaser 2.70 installer. Follow the instructions and <font color=\"red\">be careful</font> with general settings</p>";
$lang['agree'] = '<div align="center">You mast to be agreed with GNU GPL:</div>';
$lang['agree_continue'] = '<div align="center"><a href="index.php?step=1">I agree</a> | I deny, and close by browser, removing releaser from my computer</div>';
$lang['check_settings'] = 'Preparing for setup, checking server settings';
$lang['version'] = 'Version';
$lang['rights'] = 'File permissons(CHMOD)';
$lang['invalid_rights'] = '<font color="red">Cat write to file</font>, set permissions to 666 for files, or 777 for folders';

$lang['not_support'] = '<font color="red">Not supported</font>, please install required software';
$lang['support'] = 'Support';
$lang['ok_support'] = '<font color="green">Supports</font>';
$lang['ok'] = '<font color="green">Success!</font>';

$lang['chmod_check'] = 'Checking file permissions';
$lang['continue'] = 'Continue';

$lang['safe_mode_off'] = '<font color="green">Disabled</font>';
$lang['safe_mode_on'] = '<font color="red">Enabled</font>, please turn it off in php.ini';

$lang['fail_notice'] = '<h3>If one or more values have <font color="red">red</font> status, it\'s recommended to fix it. If not, successful operating of Kinokpk.com releaser 2.70 doesn\'t guaranteed</h3><hr/>';

$lang['mysql'] = 'MySQL configuration';

$lang['mysql_host'] = 'Host';
$lang['mysql_db'] = 'Database';
$lang['mysql_user'] = 'User';
$lang['mysql_pass'] = 'Password';
$lang['mysql_charset'] = 'Charset';
$lang['mysql_forum_table_prefix'] = 'Forum tables prefix';
$lang['mysql_notice'] = 'Recommended charset is <b>cp1251</b>, collation - <b>cp1251_general_ci</b>';
$lang['forum_mysql_notice'] = '<p>If you <b>don\'t want to be</b> integrated with Invision Power Board, don\'t fill form below. If not, <b>setup Invision Power Board first</b>, and then Kinokpk.com releaser, filling form below. After releaser setup (if integrated), you must <b>login</b> with forum\'s administrator account, if you do not use integration, <b>first registered account</b> will become a sysop.</p>';

$lang['testing_database_connection'] = '<h1 align="center">Checking database connection and adding structure.</h1>On error plaese <a href="javascript:history.go(-1);">come back</a> and verify input parametres.<hr />';
$lang['mysql_error'] ='<font color="red">MySQL error:</font> ';
$lang['config_to_file'] = 'Writing configuration to file ';
$lang['write_config_ok'] = 'Passwords saved, now we will go to general configuration<hr/>';

$lang['main_settings'] = '<h1 align="center">General configuration of Kinokpk.com releaser 2.70</h1><font color="red">Warning! Read and modify configuration carefuly, it\'s not good to ingore fields marked *.</font><hr />';

$lang['settings_saved'] = 'Settings saved ';
$lang['settings_notice'] = '<p>You configured only general settings of Kinokpk.com releaser 2.70, after setup you can configure cron, retracker etc, all settings in http://ваш_сайт/admincp.php</p>';
$lang['install_complete'] = '<p>Congratulations! Setup of Kinokpk.com releaser 2.70 finished, you can view your site.</p>';
$lang['install_notice'] = '<font color="red">Warning! For your safety, remove folders <b>install</b> and <b>update</b> from your server and set <b>permissions 644 on include/secrets.php</b></font>';
$lang['donate'] = '<p><pre>You can donate to developers, requisites:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168,
Yandex.money: 41001423787643,
Paypal: zond80@gmail.com</pre></p><hr /><div align="right"><i>Best regards, Kinokpk.com releaser developers</i></div>';

?>