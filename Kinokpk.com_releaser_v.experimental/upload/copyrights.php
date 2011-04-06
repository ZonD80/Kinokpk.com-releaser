<?

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

require "include/bittorrent.php";

INIT();



//loggedinorreturn();

$REL_TPL->stdhead("Правила");

$REL_TPL->begin_main_frame();
$REL_TPL->begin_frame("Для правообладателей");
print(nl2br('Для того, чтобы сообщить о размещении нелицензионного контента на нашем сайте, отправьте соответствующее письмо на
<div align="center"><img src="pic/abusemail.gif"/></div>
<b>ВНИМАНИЕ!</b> Письма, направленные на какие-либо другие ящики внутри домена не рассматриваются.
Не забудьте указать, как с вами связаться, представителем какой компании вы являетесь, основание, по которому наш контент считается нелицензионным и т.д. и т.п.
Мы с удовольствием не ждем ваших писем, но все же перед отправкой хорошо все обдумайте и прочитайте это:

Предупреждение! Информация, расположенная на данном сервере, предназначена исключительно для частного использования в образовательных целях и не может быть загружена/перенесена на другой компьютер. Ни владелец сайта, ни хостинг-провайдер, ни любые другие физические или юридические лица не могут нести никакой отвественности за любое использование материалов данного сайта. Входя на сайт, Вы, как пользователь, тем самым подтверждаете полное и безоговорочное согласие со всеми условиями использования. Авторы проекта относятся особо негативно к нелегальному использованию информации, полученной на сайте. Все фильмы, представленные на FTP и HTTP серверах имеют худшее качество и являются рекламой. За фильмы, представленные с помощью Torrent-сети ответственность несут выложившие релиз. Если вам понравился фильм, то вы можете преобрести оригинальную копию в HD качестве у правообладателей.
Если вы являетесь правообладателем фильма и не хотите, чтобы рекламный фильм был размещен на сайте, напишите администрации сайта. Тоже самое правило применимо к остальным файлам сервера.

<div align="center">
Релизер был переписан из <b>TBDev YSE PRE RC 6</b>. Конечная версия движка является собственностью команды Kinokpk.com
Публичная версия данного релизера обсуждается и разрабатывается в <a target="_blank" href="http://dev.kinokpk.com">Уголке разработчика релизера Kinokpk.com</a></div>
'));
$REL_TPL->end_frame();
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();
?>