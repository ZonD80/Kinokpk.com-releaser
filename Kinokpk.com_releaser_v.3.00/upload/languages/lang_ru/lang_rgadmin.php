<?php
/**
 * Language file for relgroups administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

$tracker_lang['rg_title'] = 'Администрирование релиз-групп';
$tracker_lang['to_rgadmin'] = ' | <a href="rgadmin.php">К администрированию релиз групп</a>';
$tracker_lang['relgroupsadd'] = ' | <a href="rgadmin.php?a=add">Добавить</a>';
$tracker_lang['spec'] = 'Специализация';
$tracker_lang['no_relgroups'] = 'Нет релиз групп <a href="rgadmin.php?a=add">Добавить</a>';
$tracker_lang['owners'] = 'Владельцы';
$tracker_lang['amount'] = 'Количество откупа, необх. для подписки';
$tracker_lang['only_invites'] = 'Возможно подписаться <b>только</b> по приглашению';
$tracker_lang['members'] = 'Члены';
$tracker_lang['private'] = 'Приватная (Группа Закрытая)';
$tracker_lang['nonfree'] = 'Платная (За вступление в группу надо что-то заплатить)';
$tracker_lang['page_pay'] = 'Страница оплаты<br/><small>(Если пусто, то "валюта" - откуп)<br/>Если значение заполнено, группа автоматически становится платной</small>';
$tracker_lang['subscribe_length'] = 'Период подписки (0 - бесконечно)';
$tracker_lang['users'] = 'Кол-во подписчиков';
$tracker_lang['actions'] = 'Действия';
$tracker_lang['descr'] = 'Описание';
$tracker_lang['delete_all_users'] = 'Удалить всех подписчиков';
$tracker_lang['are_you_sure'] = 'Вы уверены?';
$tracker_lang['view_users'] = 'Посмотреть подписчиков';
$tracker_lang['add_group'] = 'Добавление группы';
$tracker_lang['edit_group'] = 'Редактирование группы';
$tracker_lang['continue'] = 'Продолжить';
$tracker_lang['rg_faq'] = 'Совет: в поле картинка указывается полный или относительный URL картинки, в полях владельцы и члены указываются ID соответствующих пользователей <b>через запятую, без пробелов</b>. В поле "страница оплаты" указывается полный или относительный путь к странице оплаты<br/>
Для внесения пользователя при платной подписке в список подписчиков нужно выполнить SQL-запрос:<br/>
<pre>
INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES (ID_пользователя,ID_релиз группы,UNIX_время+время_подписки*86400);
</pre>';
$tracker_lang['group_added'] = 'Группа успешно добавлена. Сейчас вы перейдете к ее странице';
$tracker_lang['group_error'] = 'Произошла ошибка в операциях над группой';
$tracker_lang['no_value'] = 'Не указано одно из обязательных значений формы';
$tracker_lang['group_edited'] = 'Группа успешно отредактирована. Сейчас вы перейдете к ее странице';
$tracker_lang['unknown_action'] = 'Неизвестное действие';
$tracker_lang['users_deleted'] = 'Все подписчики группы успешно удалены';
$tracker_lang['subscribe_until'] = 'Подписка до';
$tracker_lang['in_time'] = ', истекает через ';
$tracker_lang['no_users'] = 'У этой релиз-группы нет подписчиков';
$tracker_lang['delete_user_ok'] = 'Пользователь удален из подписчиков группы';
$tracker_lang['notify_send'] = 'Отправлено уведомление';
$tracker_lang['notify_subject'] = 'Отмена подписки релиз-группы';
$tracker_lang['delete_with_notify'] = 'Удалить с уведомлением пользователя';
$tracker_lang['delete_notify'] = 'Уважаемый пользователь!<br/>Администратором группы(сайта) была прекращена ваша подписка на релизы группы "%s"';
$tracker_lang['comma_separated'] = 'ID пользователей, через запятую, <b>без пробелов</b>';
$tracker_lang['relgroup_deleted'] = 'Релиз группа удалена, сейчас вы перейдете к панели администрирования релиз групп';
?>