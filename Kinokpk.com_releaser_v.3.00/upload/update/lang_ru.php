<?php

// language installer, russian

$lang['hello'] = "<h1>Если вы видите предупреждения типа E_WARNING - это нормально. <stong>СДЕЛАЙТЕ BACKUP ПЕРЕД ОБНОВЛЕНИЕМ</strong></h1><p>Добро пожаловать в мастер обновления Kinokpk.com releaser 2.70 до Kinokpk.com releaser 3.00. Следуйте инстукциям и <font color=\"red\">будьте внимательны</font> при настройке параметров<br/><br/>Эта версия совместима с PHP 5.3, по этому вы должны выключить все magic_quotes в конфигурации PHP</p>";
$lang['agree'] = '<div align="center">Для продолжения установки вы должны принять условия лицензии:</div>';
$lang['agree_continue'] = '<div align="center"><a href="index.php?step=1">Я принимаю, продолжить установку</a> | Я не принимаю, и сейчас же закрываю браузер и удаляю скрипт с компьютера</div>';

$lang['ok'] = '<font color="green">Успешно</font>';

$lang['continue'] = 'Продолжить';

$lang['next_step_update_db'] = 'Следующий шаг проведет операции с базой данных<br />';
$lang['mysql_error'] ='<font color="red">Ошибка MySQL:</font> ';

$lang['install_complete'] = '<p>Поздравляем! Установка Kinokpk.com releaser 3.00 завершена, вы можете зайти на свой сайт.</p>';
$lang['install_notice'] = '<font color="red">Внимание! Ради безопасности удалите папки <b>install</b> и <b>update</b> с сервера и поставьте <b>права 644 на файл include/secrets.php</b></font>';
$lang['donate'] = '<p><pre>Вы всегда можете помочь материально создателю движка (по вашему желанию), реквизиты:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168,
Yandex.деньги: 41001423787643,
Paypal: zond80@gmail.com</pre></p><hr /><div align="right"><i>С уважением, разработчики Kinokpk.com releaser</i></div>';
$lang['secret_notice'] = 'Произвольный набор символов';
$lang['write_config_error'] = '<font color="red">Произошла ошибка при записи конфиграционного файла. Попробуйте еще раз</font> <a href="javascript:history.go(-1);">Назад</a>';
$lang['wrong_chmod'] = '<font color="red">Вы не установили необходимый chmod на файлы</font> <a href="javascript:history.go(-1);">Назад</a>';
$lang['step2_descr'] ='<pre>Следующий шаг перестроит систему учета трекеров</pre>';
$lang['step3_descr'] = '<pre>Следующий шаг перестроит спойлер. Это может занять длительное время</pre>';
$lang['step4_descr'] = '<pre>Следующий шаг - конфигурация релизера</pre>';
$lang['step5_descr'] ='<pre>Следующий шаг  - конфигуация крона и рейтинговой системы</pre>';
$lang['step6_descr'] ='<pre>Следующий шаг - установка секрета для cookies <i>авторизация на трекере сбросится у всех пользователей</i><br/><font color="red">Установите chmod 666 на файл include/secrets.php</font> на время этого шага</pre>';
?>