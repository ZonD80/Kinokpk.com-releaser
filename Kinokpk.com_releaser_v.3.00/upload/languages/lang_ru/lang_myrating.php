<?php
/**
 * Language file for rating page
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

$tracker_lang['rating_title'] = 'Ваш рейтинг';
$tracker_lang['my_formula'] = 'Формула расчета вашего рейтинга';
$tracker_lang['my_discount'] = 'Ваш откуп';
$tracker_lang['once'] = ' рейтинга раз в';
$tracker_lang['hours'] = 'час(а) (Золотые, Ваши и подаренные Вам релизы не учитываются)';
$tracker_lang['my_goods'] = 'Ваши преимущества';
$tracker_lang['goods_vip'] = '<font color="red">Вы VIP, у Вас не отнимается рейтинг за скачивание релизов или за отстутствие сида</font>';
$tracker_lang['goods_new'] = '<font color="red">Вы новичок (осталось %s дней), и Вы изучаете рейтинговую систему, у Вас не отнимается рейтинг за скачивание релизов или за отстутствие сида</font>';
$tracker_lang['no_goods'] = 'У Вас нет привилегий, но Вы можете <a href="donate.php">купить VIP статус</a>';
$tracker_lang['no_formula'] = 'Вы еще ничего не качали и ничего не раздали, поэтому вы получаете 0';
$tracker_lang['down_formula'] = 'Вы раздаете %s релизов, откупились от %s, что в сумме меньше, чем скачали (%s скачанных релизов), поэтому Ваш рейтинг изменяется на ';
$tracker_lang['down_levels'] = 'Пороги ограничений';
$tracker_lang['down_notice'] = 'При рейтинге в %s Вы не сможете скачивать релизы, а при %s Ваш аккаунт будет отключен';
$tracker_lang['get_rating'] = 'Вы получите +%s рейтинга за загрузку релиза';
$tracker_lang['rating_per_request'] = 'Вы получите +%s рейтинга за выполнение запроса';
$tracker_lang['rating_per_invite'] = 'Вы получите +%s рейтинга за приглашение друга';
$tracker_lang['discount_link'] = 'Вы можете <strong><a href="myrating.php?discount"><font color="red">получить откуп</font></a></strong> обменяв %s рейтинга на 1 откуп или на <a href="donate.php?smszamok">платной основе</a><br /><small>Откуп прибавляется к сидирующимся релизам, например вы сидируете 3 и откупились от трех, получается что Вы сидируете какбы 6 релизов :)</small>';
$tracker_lang['i_chage'] = 'Вы меняте %s своего рейтинга (max: %s), а сейчас Ваш рейтинг равен %s (Откуп не может быть больше количества скачанных релизов)';
$tracker_lang['chage_rating'] = 'Обменять рейтинг на откуп';
$tracker_lang['no_rating'] = 'Решили уйти в минус?:) У Вас нет столько рейтинга, выберите меньшее значение';
$tracker_lang['rating_changed'] = 'Рейтинг успешно обменян на откуп, сейчас Вы перейдете к странице "Ваш рейтинг"';
$tracker_lang['rating_disconnected'] = 'В данный момент вы не подключены к трекеру. Расчет рейтинга приостановлен.';
$tracker_lang['now_i'] = 'Сейчас Вы...';
$tracker_lang['downloaded_rel'] = 'Скачали';
$tracker_lang['discounted'] = 'Откупились от';
$tracker_lang['seeding'] = 'Раздаете';
$tracker_lang['cannot_discount'] = 'Вы не можете получить откуп, т.к. количество скачанных вами релизов и так превышает количество вашего откупа';
$tracker_lang['discount_limit'] = 'Вы не можете получить данное количество откупа, т.к. в данном случае оно будет превышать количество скачанных релизов, что запрещено правилами';
?>