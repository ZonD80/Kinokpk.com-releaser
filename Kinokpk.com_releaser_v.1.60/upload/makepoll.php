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
dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
  stderr($tracker_lang['error'],$tracker_lang['access_denied']);

$action = $_GET["action"];
$pollid = (int)$_GET["pollid"];

if ($action == "edit")
{
	if (!is_valid_id($pollid))
		stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
	$res = sql_query("SELECT * FROM polls WHERE id = $pollid")
			or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) == 0)
		stderr($tracker_lang['error'],"No poll found with ID.");
	$poll = mysql_fetch_array($res);
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($action=='edit' && !is_valid_id($pollid))
		stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
  $question = htmlspecialchars($_POST["question"]);
  $option0 = htmlspecialchars($_POST["option0"]);
  $option1 = htmlspecialchars($_POST["option1"]);
  $option2 = htmlspecialchars($_POST["option2"]);
  $option3 = htmlspecialchars($_POST["option3"]);
  $option4 = htmlspecialchars($_POST["option4"]);
  $option5 = htmlspecialchars($_POST["option5"]);
  $option6 = htmlspecialchars($_POST["option6"]);
  $option7 = htmlspecialchars($_POST["option7"]);
  $option8 = htmlspecialchars($_POST["option8"]);
  $option9 = htmlspecialchars($_POST["option9"]);
  $option10 = htmlspecialchars($_POST["option10"]);
  $option11 = htmlspecialchars($_POST["option11"]);
  $option12 = htmlspecialchars($_POST["option12"]);
  $option13 = htmlspecialchars($_POST["option13"]);
  $option14 = htmlspecialchars($_POST["option14"]);
  $option15 = htmlspecialchars($_POST["option15"]);
  $option16 = htmlspecialchars($_POST["option16"]);
  $option17 = htmlspecialchars($_POST["option17"]);
  $option18 = htmlspecialchars($_POST["option18"]);
  $option19 = htmlspecialchars($_POST["option19"]);
  $sort = (int) $_POST["sort"];
  $returnto = htmlentities($_POST["returnto"]);

  if (!$question || !$option0 || !$option1)
    stderr($tracker_lang['error'], "Заполните все поля формы!");

  if ($pollid)
		sql_query("UPDATE polls SET " .
		"question = " . sqlesc($question) . ", " .
		"option0 = " . sqlesc($option0) . ", " .
		"option1 = " . sqlesc($option1) . ", " .
		"option2 = " . sqlesc($option2) . ", " .
		"option3 = " . sqlesc($option3) . ", " .
		"option4 = " . sqlesc($option4) . ", " .
		"option5 = " . sqlesc($option5) . ", " .
		"option6 = " . sqlesc($option6) . ", " .
		"option7 = " . sqlesc($option7) . ", " .
		"option8 = " . sqlesc($option8) . ", " .
		"option9 = " . sqlesc($option9) . ", " .
		"option10 = " . sqlesc($option10) . ", " .
		"option11 = " . sqlesc($option11) . ", " .
		"option12 = " . sqlesc($option12) . ", " .
		"option13 = " . sqlesc($option13) . ", " .
		"option14 = " . sqlesc($option14) . ", " .
		"option15 = " . sqlesc($option15) . ", " .
		"option16 = " . sqlesc($option16) . ", " .
		"option17 = " . sqlesc($option17) . ", " .
		"option18 = " . sqlesc($option18) . ", " .
		"option19 = " . sqlesc($option19) . ", " .
		"sort = " . sqlesc($sort) . " " .
    "WHERE id = $pollid") or sqlerr(__FILE__, __LINE__);
  else
  	sql_query("INSERT INTO polls VALUES(0" .
		", '" . get_date_time() . "'" .
    ", " . sqlesc($question) .
    ", " . sqlesc($option0) .
    ", " . sqlesc($option1) .
    ", " . sqlesc($option2) .
    ", " . sqlesc($option3) .
    ", " . sqlesc($option4) .
    ", " . sqlesc($option5) .
    ", " . sqlesc($option6) .
    ", " . sqlesc($option7) .
    ", " . sqlesc($option8) .
    ", " . sqlesc($option9) .
 		", " . sqlesc($option10) .
		", " . sqlesc($option11) .
		", " . sqlesc($option12) .
		", " . sqlesc($option13) .
		", " . sqlesc($option14) .
		", " . sqlesc($option15) .
		", " . sqlesc($option16) .
		", " . sqlesc($option17) .
		", " . sqlesc($option18) .
		", " . sqlesc($option19) . 
    ", " . sqlesc($sort) .
  	")") or sqlerr(__FILE__, __LINE__);

  if ($returnto == "main")
		header("Location: $DEFAULTBASEURL");
  elseif ($pollid)
		header("Location: $DEFAULTBASEURL/polls.php#$pollid");
	else
		header("Location: $DEFAULTBASEURL");
	die;
}

stdhead();

if ($pollid)
	print("<h1>Редактировать опрос</h1>");
else
{
	// Warn if current poll is less than 3 days old
	$res = sql_query("SELECT question,added FROM polls ORDER BY added DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);
	if ($arr)
	{
	  $hours = floor((gmtime() - sql_timestamp_to_unix_timestamp($arr["added"])) / 3600);
	  $days = floor($hours / 24);
	  if ($days < 3)
	  {
	    $hours -= $days * 24;
	    if ($days)
	      $t = "$days day" . ($days > 1 ? "s" : "");
	    else
	      $t = "$hours hour" . ($hours > 1 ? "s" : "");
	    print("<p><font color=red><b>Note: The current poll (<i>" . $arr["question"] . "</i>) is only $t old.</b></font></p>");
	  }
	}
	print("<h1>Создать опрос</h1>");
}
?>

<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=makepoll.php>
<tr><td class=rowhead>Вопрос <font color=red>*</font></td><td align=left><input name=question size=80 maxlength=255 value="<?=$poll['question']?>"></td></tr>
<tr><td class=rowhead>Вопрос 1 <font color=red>*</font></td><td align=left><input name=option0 size=80 maxlength=40 value="<?=$poll['option0']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 2 <font color=red>*</font></td><td align=left><input name=option1 size=80 maxlength=40 value="<?=$poll['option1']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 3</td><td align=left><input name=option2 size=80 maxlength=40 value="<?=$poll['option2']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 4</td><td align=left><input name=option3 size=80 maxlength=40 value="<?=$poll['option3']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 5</td><td align=left><input name=option4 size=80 maxlength=40 value="<?=$poll['option4']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 6</td><td align=left><input name=option5 size=80 maxlength=40 value="<?=$poll['option5']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 7</td><td align=left><input name=option6 size=80 maxlength=40 value="<?=$poll['option6']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 8</td><td align=left><input name=option7 size=80 maxlength=40 value="<?=$poll['option7']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 9</td><td align=left><input name=option8 size=80 maxlength=40 value="<?=$poll['option8']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 10</td><td align=left><input name=option9 size=80 maxlength=40 value="<?=$poll['option9']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 11</td><td align=left><input name=option10 size=80 maxlength=40 value="<?=$poll['option10']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 12</td><td align=left><input name=option11 size=80 maxlength=40 value="<?=$poll['option11']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 13</td><td align=left><input name=option12 size=80 maxlength=40 value="<?=$poll['option12']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 14</td><td align=left><input name=option13 size=80 maxlength=40 value="<?=$poll['option13']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 15</td><td align=left><input name=option14 size=80 maxlength=40 value="<?=$poll['option14']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 16</td><td align=left><input name=option15 size=80 maxlength=40 value="<?=$poll['option15']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 17</td><td align=left><input name=option16 size=80 maxlength=40 value="<?=$poll['option16']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 18</td><td align=left><input name=option17 size=80 maxlength=40 value="<?=$poll['option17']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 19</td><td align=left><input name=option18 size=80 maxlength=40 value="<?=$poll['option18']?>"><br /></td></tr>
<tr><td class=rowhead>Вопрос 20</td><td align=left><input name=option19 size=80 maxlength=40 value="<?=$poll['option19']?>"><br /></td></tr>
<tr><td class=rowhead>Сортировать</td><td>
<input type=radio name=sort value=yes <?=$poll["sort"] != "no" ? " checked" : "" ?>>Да
<input type=radio name=sort value=no <?=$poll["sort"] == "no" ? " checked" : "" ?>> Нет
</td></tr>
<tr><td colspan=2 align=center><input type=submit value=<?=$pollid?"'Редактировать'":"'Создать'"?> style='height: 20pt'></td></tr>
</table>
<p><font color=red>*</font> обязательно</p>
<input type=hidden name=pollid value=<?=$poll["id"]?>>
<input type=hidden name=action value=<?=$pollid?'edit':'create'?>
<input type=hidden name=returnto value=<?=$_GET["returnto"]?>>
</form>

<? stdfoot(); ?>