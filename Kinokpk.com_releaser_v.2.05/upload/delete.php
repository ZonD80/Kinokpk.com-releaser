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

require_once("include/bittorrent.php");


function bark($msg) {
  stdhead($tracker_lang['error']);
  stdmsg($tracker_lang['error'], $msg);
  stdfoot();
  exit;
}

dbconn();

if (!is_valid_id($_POST["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = 0 + $_POST["id"];

loggedinorreturn();

$res = sql_query("SELECT name,owner,seeders,image1,image2,tags FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	stderr($tracker_lang['error'],"Такого торрента не существует.");

if (get_user_class() < UC_MODERATOR)
	bark("Вы не модератор! Как такое могло произойти?\n");

$rt = 0 + $_POST["reasontype"];

if (!is_int($rt) || $rt < 1 || $rt > 5)
	bark("Неверная причина $rt.");

$r = $_POST["r"];
$reason = $_POST["reason"];

if ($rt == 1)
	$reasonstr = "Мертвый: 0 раздающих, 0 качающих = 0 пиров";
elseif ($rt == 2)
	$reasonstr = "Двойник" . ($reason[0] ? (": " . trim($reason[0])) : "!");
elseif ($rt == 3)
	$reasonstr = "Nuked" . ($reason[1] ? (": " . trim($reason[1])) : "!");
elseif ($rt == 4)
{
	if (!$reason[2])
		bark("Вы не написали пукт правил, которые этот торрент нарушил.");
  $reasonstr = "Нарушение правил: " . trim($reason[2]);
}
else
{
	if (!$reason[3])
		bark("Вы не написали причину, почему удаляете торрент.");
  $reasonstr = trim($reason[3]);
}

deletetorrent($id);

if ($row["image1"]) {
 $img1 = "torrents/images/$row[image1]";
 $del = unlink($img1);
}
if ($row["image2"]) {
 $img2 = "torrents/images/$row[image2]";
 $del = unlink($img2);
}

$tags = explode(",", $row["tags"]);

foreach ($tags as $tag) {
		@sql_query("UPDATE tags SET howmuch=howmuch-1 WHERE name LIKE ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
	}


write_log("Торрент $id ($row[name]) был удален пользователем $CURUSER[username] ($reasonstr)\n","F25B61","torrent");

stdhead("Торрент удален!");

if (isset($_POST["returnto"]))
	$ret = "<a href=\"" . htmlspecialchars($_POST["returnto"]) . "\">Назад</a>";
else
	$ret = "<a href=\"$DEFAULTBASEURL/\">На главную</a>";

?>
<h2>Торрент удален!</h2>
<p><?= $ret ?></p>
<?

stdfoot();

?>