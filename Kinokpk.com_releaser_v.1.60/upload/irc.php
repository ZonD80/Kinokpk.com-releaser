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

stdhead("IRC чат (как подключиться)");

begin_main_frame();

?>

<? begin_frame("Наш IRC |<font color=#004E98> параметры</font>"); ?>
<ul><center><h3>
Server:</h3> <h1>dalnetru.ircd.com.ru</h1>
<h3>Port:</h3> <h1>6667</h1>
<h3>Channel:</h3> <h1>#kinokpk</h1></center>
<? end_frame(); ?>
<? begin_frame("IRC чат |<font color=#004E98> Как к нему подключиться?</font>"); ?>
<li>Для начала - выбрать IRC клиент. Рекомендуем MIRC - он же мирк, мирка и т.д.<br><br><center><img src="files/irc/mirc.jpg"></center><br><br>Ссылка для загрузки (в арихве сам клиент + кейген):<br><a href="files/irc/mirc621.zip">Скачать (версия 6.21)</a></li>
<li>Подключиться к нашему IRC каналу. Это можно сделать так:<br><br>
<center><b>Обязательное выделено <font color="red">красным</font>, необязательное - <font color="green">зеленым</font></b></center><br><br>
Запускаем MIRC:<br><br>
<center><img src="files/irc/irc1.jpg"></center><br><br>
Нажимаем <i>File</i> -> <i>Select Server</i>
<center><img src="files/irc/irc2.jpg">  <img src="files/irc/irc3.jpg"></center><br><br>
В появившемся окошке нажимаем <i>Add.</i><br><br>
<center><img src="files/irc/irc4.jpg"></center><br><br>
В появившемся окошке вводим название сервера (как он будет отображаться в программе)<br>
Адрес сервера - <b>dalnetru.ircd.com.ru</b><br>
Порт - <b>6667</b><br>
И нажимаем на <i>Add.</i><br><br>
<center><img src="files/irc/irc5.jpg"></center><br><br>
Далее, вы получите вот такое вот окошко:<br><br>
<center><img src="files/irc/irc6.jpg"></center><br><br>
В нем нажимаете на <i>Select.</i> И переходите к окну конфигурации ников:<br><br>
<center><img src="files/irc/irc7.jpg"></center><br><br>
Полное имя - Ваше имя для регистрации на IRC сервере (сама регистрация необязательна, а вот имя обязательно)<br>
E-mail адрес - Ваш е-майл для регистрации на IRC сервере (сама регистрация необязательна, а вот емайл обязателен)<br>
Ваш ник - собственно ваш ник<br>
Альтернативный ник - таким будет ваш ник, если первый указанный ник занят<br>
Теперь кликаем на <i>ОК.</i><br>
Дальше нажимаем эту кнопку:<img src="files/irc/irc8.jpg"><br><br>
<center>Вы подключились к серверу! Теперь необходимо зайти на наш канал:<br><br>
<img src="files/irc/irc9.jpg"></center><br><br>
Вводите имя канала - <b>#kinokpk</b><br>
И нажимаете <i>Join.</i><br>
Также по желанию можете добавить наш канал в список (чтобы не вводить каждый раз), нажав <i>Add.</i><br><br>
<center><b>В итоге вы получите это:</b><br><br><img src="files/irc/irc10.jpg"><br><br><font color="red">ПОЗДРАВЛЯЕМ! ВЫ НА НАШЕМ КАНАЛЕ IRC И МОЖЕТЕ НАЧИНАТЬ ЧАТИТЬСЯ!!!</font></center>


</li>
<? end_frame();

end_main_frame();
stdfoot(); ?>