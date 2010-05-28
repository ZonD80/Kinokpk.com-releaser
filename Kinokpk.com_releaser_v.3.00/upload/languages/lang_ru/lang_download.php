<?php
/**
 * Russian language file for download script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

$tracker_lang['download_notice'] = 'При скачивании этого релиза у вас отнимется %s рейтинга, и он станет равным %s. Помните, что при рейтинге %s вам будет запрещено скачивание релизов. Получить "откуп" вы можете на странице "Мой рейтинг". Если вдруг закачка торрента прервалась, вы можете скачать это торрент повторно без изменения рейтинга.';$tracker_lang['download_torrent'] = 'Скачать торрент!';
$tracker_lang['downloading_torrent'] = 'Скачивание релиза';
$tracker_lang['as_magnet'] = 'Отобразить Magnet-ссылку!';
$tracker_lang['as_dc_magnet'] = 'Отобразить DirectConnect ссылку!';
$tracker_lang['magnet'] = 'Кликните на эту ссылку для скачивания релиза';
$tracker_lang['this_is_magnet_title'] = 'Magnet-ссылка:';
$tracker_lang['this_is_magnet'] = '<div align="center">Это Magnet-ссылка. С помощью этой ссылки вы можете начать скачивание в популярных торрент-клиентах без сохранения торрент-файла на вашем компьютере. Для того, чтобы начать скачивание, кликните по ссылке ниже:</div>';
$tracker_lang['this_is_dc_magnet'] = '<div align="center">Это DirectConnect-Magnet-ссылка. С помощью этой ссылки вы можете начать скачивание в DirectConnect клиенте (например, PeLink). Для того, чтобы начать скачивание, кликните по ссылке ниже:</div>';
$tracker_lang['rating_low'] = 'Вы не можете скачать релиз, ваш рейтинг слишком мал, поднимите себе рейтинг активно комментируя, заливая релизы, либо находясь на раздаче. Также вы можете поднять себе рейтинг на платной основе.';
$tracker_lang['to_details'] = '<p align="right">Вы также можете <a href="details.php?id=%s">вернуться в детали релиза</a></p>';
$tracker_lang['private_release_access_denied'] = 'Это релиз <strong>приватной</strong> группы %s, чтобы скачать этот релиз вы должны состоять в подписчиках этой группы. Получить подписку можно <a href="relgroups.php">На странице Релиз-Групп</a>';
$tracker_lang['no_dchubs'] = 'Для вашей подсети не обнаружено поддерживаемых DC-хабов, вы можете скачать релиз только используя <a href="download.php?id=%s&ok">.torrent файл</a> или <a href="download.php?id=%s&ok&magnet=1">magnet-ссылку</a>';
$tracker_lang['no_tiger'] = 'TIGER-хеша не обнаружено, этот релиз нельзя скачать по протоколу DirectConnect';
?>