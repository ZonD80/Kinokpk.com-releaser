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

gzip();

dbconn();

//loggedinorreturn();

stdhead("ѕравила");

begin_main_frame();

?>

<? begin_frame("ƒл€ правообладателей"); ?>
<ul>
ѕредупреждение! »нформаци€, расположенна€ на данном сервере, предназначена исключительно дл€ частного использовани€ в образовательных цел€х и не может быть загружена/перенесена на другой компьютер. Ќи владелец сайта, ни хостинг-провайдер, ни любые другие физические или юридические лица не могут нести никакой отвественности за любое использование материалов данного сайта. ¬ход€ на сайт, ¬ы, как пользователь, тем самым подтверждаете полное и безоговорочное согласие со всеми услови€ми использовани€. јвторы проекта относ€тс€ особо негативно к нелегальному использованию информации, полученной на сайте.
¬се фильмы, представленные на FTP и HTTP серверах имеют худшее качество и €вл€ютс€ рекламой. «а фильмы, представленные с помощью Torrent-сети ответственность несут выложившие релиз. ≈сли вам понравилс€ фильм, то вы можете преобрести оригинальную копию в HD качестве у правообладателей.
≈сли вы €вл€етесь правообладателем фильма и не хотите, чтобы рекламный фильм был размещен на сайте, напишите администрации сайта. “оже самое правило применимо к остальным файлам сервера.<br /><br />
<center>
–елизер был переписан из <b>TBDev YSE PRE RC 6</b>.  онечна€ верси€ движка €вл€етс€ собственностью команды Kinokpk.com<br/>
ѕублична€ верси€ данного релизера обсуждаетс€ и разрабатываетс€ в <a target="_blank" href="http://dev.kinokpk.com">”голке разработчика релизера Kinokpk.com</a></center>
<? end_frame(); ?>

<?
end_main_frame();
stdfoot(); ?>