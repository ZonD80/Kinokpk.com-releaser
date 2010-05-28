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

dbconn(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $choice = $_POST["choice"];
  if ($CURUSER && $choice != "" && $choice < 256 && $choice == floor($choice)) {
    $res = sql_query("SELECT * FROM polls ORDER BY added DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);
    $arr = mysql_fetch_assoc($res) or die("Нет опроса");
    $pollid = $arr["id"];
    $userid = $CURUSER["id"];
    $res = sql_query("SELECT * FROM pollanswers WHERE pollid=$pollid && userid=$userid") or sqlerr(__FILE__, __LINE__);
    $arr = mysql_fetch_assoc($res);
    if ($arr) die("Двойной голос");
    sql_query("INSERT INTO pollanswers VALUES(0, $pollid, $userid, $choice)") or sqlerr(__FILE__, __LINE__);
    if (mysql_affected_rows() != 1)
      stderr($tracker_lang['error'], "Произошла ошибка. Ваш голос не был принят.");
    header("Location: $DEFAULTBASEURL/");
    die;
  } else
    stderr($tracker_lang['error'], "Пожалуйста, выберите вариант ответа.");
}

stdhead($tracker_lang['homepage']);

//print("<table width=\"100%\" class=\"main\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"embedded\">");

stdfoot();
?>