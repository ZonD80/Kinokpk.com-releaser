<?php

// language installer, russian

$lang['hello'] = "<p>Добро пожаловать в мастер установки Kinokpk.com releaser 2.70. Следуйте инстукциям и <font color=\"red\">будьте внимательны</font> при настройке начальных параметров</p>";
$lang['agree'] = '<div align="center">Для продолжения установки вы должны принять условия лицензии:</div>';
$lang['agree_continue'] = '<div align="center"><a href="index.php?step=1">Я принимаю, продолжить установку</a> | Я не принимаю, и сейчас же закрываю браузер и удаляю скрипт с компьютера</div>';
$lang['check_settings'] = 'Подготовка к установке и проверка параметров';
$lang['version'] = 'Версия';
$lang['rights'] = 'Права на файлы(CHMOD)';
$lang['invalid_rights'] = '<font color="red">Файл или папка не имеет прав на запись</font>, поставьте права для записи 666 для файла, или 777 для папки';

$lang['not_support'] = '<font color="red">Не поддерживается</font>, установите соответсвующее программное обеспечение на ваш сервер';
$lang['support'] = 'Поддержка';
$lang['ok_support'] = '<font color="green">Поддерживается</font>';
$lang['ok'] = '<font color="green">Успешно</font>';

$lang['chmod_check'] = 'Проверка прав на запись';
$lang['continue'] = 'Продолжить';

$lang['safe_mode_off'] = '<font color="green">Отключен</font>';
$lang['safe_mode_on'] = '<font color="red">Включен</font>, отключите safe mode в php.ini';

$lang['fail_notice'] = '<h3>Если хоть одно из значений отображается <font color="red">красным</font>, рекомендуется исправить его. В противном случае правильное функционирование Kinokpk.com releaser 2.70 не гарантируется</h3><hr/>';

$lang['mysql'] = 'Конфигурация MySQL';

$lang['mysql_host'] = 'Хост';
$lang['mysql_db'] = 'База данных';
$lang['mysql_user'] = 'Пользователь';
$lang['mysql_pass'] = 'Пароль';
$lang['mysql_charset'] = 'Кодировка';
$lang['mysql_forum_table_prefix'] = 'Префикс таблиц форума';
$lang['mysql_notice'] = 'Рекомендуемая кодировка <b>cp1251</b>, сравнение - <b>cp1251_general_ci</b>';
$lang['forum_mysql_notice'] = '<p>Если вы <b>не используете</b> интеграцию с форумом Invision Power Board, то не заполняйте форму ниже. Если вы используете интеграцию с форумом Invision Power Board, то <b>сначала установите форум</b>, а затем Kinokpk.com releaser, указав ниже данные для доступа к БД форума. После установки релизера (интегрированного с форумом), вам надо будет <b>войти</b> с логином и паролем администратора форума, если вы не интегрировали форум, то <b>первый созданный аккаунт</b> станет аккаунтом администратора.</p>';

$lang['testing_database_connection'] = '<h1 align="center">Проверка подключения к базе данных и создание структуры.</h1>Если скрипт вызвал ошибку, то <a href="javascript:history.go(-1);">вернитесь назад</a> и проверьте введенные параметры.<hr />';
$lang['mysql_error'] ='<font color="red">Ошибка MySQL:</font> ';
$lang['config_to_file'] = 'Запись конфигурации в файл';
$lang['write_config_ok'] = 'Пароли сохранены, в следующем шаге вы перейдете к основным настройкам<hr/>';

$lang['main_settings'] = '<h1 align="center">Основные настройки Kinokpk.com releaser 2.70</h1><font color="red">Внимание!!! Внимательно отнеситесь к этим настройкам, не стоит пропускать то, что отмечено звездочкой.</font><hr />';

$lang['settings_saved'] = 'Настройки сохранены ';
$lang['settings_notice'] = '<p>Вы настроили только основную часть Kinokpk.com releaser 2.70, после процесса установки вы можете также настроить фунции cron, ретрекера и т.д., все настроики доступны из http://ваш_сайт/admincp.php</p>';
$lang['install_complete'] = '<p>Поздравляем! Установка Kinokpk.com releaser 2.70 завершена, вы можете зайти на свой сайт.</p>';
$lang['install_notice'] = '<font color="red">Внимание! Ради безопасности удалите папки <b>install</b> и <b>update</b> с сервера и поставьте <b>права 644 на файл include/secrets.php</b></font>';
$lang['donate'] = '<p><pre>Вы всегда можете помочь материально создателю движка (по вашему желанию), реквизиты:
Webmoney: U361584411086 E326225084100 R153898361884 Z113282224168,
Yandex.деньги: 41001423787643,
Paypal: zond80@gmail.com</pre></p><hr /><div align="right"><i>С уважением, разработчики Kinokpk.com releaser</i></div>';

?>