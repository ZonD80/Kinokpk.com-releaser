<?php

// language installer, russian

$lang['hello'] = "<h1>Если вы видите предупреждения типа E_WARNING - это нормально. <stong>СДЕЛАЙТЕ BACKUP ПЕРЕД ОБНОВЛЕНИЕМ</strong></h1><p>Добро пожаловать в мастер обновления Kinokpk.com releaser 2.40 до Kinokpk.com releaser 2.70. Следуйте инстукциям и <font color=\"red\">будьте внимательны</font> при настройке начальных параметров</p>";
$lang['agree'] = '<div align="center">Для продолжения установки вы должны принять условия лицензии:</div>';
$lang['agree_continue'] = '<div align="center"><a href="index.php?step=1">Я принимаю, продолжить установку</a> | Я не принимаю, и сейчас же закрываю браузер и удаляю скрипт с компьютера</div>';

$lang['ok'] = '<font color="green">Успешно</font>';

$lang['continue'] = 'Продолжить';

$lang['testing_database_connection'] = '<h1 align="center">Внимание! Последующие этапы обновления могут потребовать много времени, поэтому если скрипт долго не отвечает - это нормально!</h1>.<hr />';
$lang['next_step_update_db'] = 'Следующий шаг проведет операции с базой данных<br />';
$lang['mysql_error'] ='<font color="red">Ошибка MySQL:</font> ';

$lang['main_settings'] = '<h1 align="center">Основные настройки Kinokpk.com releaser 2.70</h1><font color="red">Внимание!!! Внимательно отнеситесь к этим настройкам, не стоит пропускать то, что отмечено звездочкой.</font><hr />';

$lang['install_complete'] = '<p>Поздравляем! Установка Kinokpk.com releaser 2.70 завершена, вы можете зайти на свой сайт.</p>';
$lang['install_notice'] = '<font color="red">Внимание! Ради безопасности удалите папки <b>install</b> и <b>update</b> с сервера и поставьте <b>права 644 на файл include/secrets.php</b></font>';
$lang['donate'] = '<p><pre>Вы всегда можете помочь материально создателю движка (по вашему желанию), реквизиты:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168,
Yandex.деньги: 41001423787643,
Paypal: zond80@gmail.com</pre></p><hr /><div align="right"><i>С уважением, разработчики Kinokpk.com releaser</i></div>';

$lang['step2_descr'] ='<pre>Следующий шаг перенесет теги в категории</pre>';
$lang['step3_descr'] = '<pre>Следующий шаг преобразует bbcodes в html для использования с редактором TinyMCE, в том числе уничтожит структуру шаблонов и перенесет все шаблонные описания в поле descr для торрентов</pre>';
$lang['step4_descr'] = '<pre>Следующий шаг преобразует все поля enum в tinyint, где это возможно</pre>';
$lang['step5_descr'] ='<pre>Следующий шаг преобразует все поля datetime в int</pre>';
$lang['step6_descr'] ='<pre>Следующий шаг заменит ссылки на картинки</pre>';
$lang['step7_descr'] ='<pre>Следующий шаг преобразует сохраненные торренты в инфохеши</pre>';

?>