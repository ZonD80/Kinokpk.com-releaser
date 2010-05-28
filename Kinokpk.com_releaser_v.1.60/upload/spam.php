<?
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_SYSOP)
    stderr("Ошибка", "Доступ запрещен.");

$where = "";
$from = "";

if ($_GET["w"]){

$w = $_GET["w"];

   if ($w == "s"){ //Где отправитель
   $userid = $_GET["id"];
   $where = "WHERE sender = ".$userid."";
   $from = "w=s&amp;id=".$userid."&amp;";
   } elseif ($w == "r"){ // Где получатель
   $userid = $_GET["id"];
   $where = "WHERE receiver = ".$userid."";
   $from = "w=r&amp;id=".$userid."&amp;";
   } elseif ($w == "sr"){  //Где отправитель = отправитель, получатель = получатель
   $sender = $_GET["sid"];
   $receiver = $_GET["rid"];
   $where = "WHERE sender = ".$sender." AND receiver = ".$receiver."";
   $from = "sr&amp;sid=".$sender."&amp;rid=".$receiver."&amp;";
   }elseif ($w == "rs"){ //Где отправитель = получатель, получатель= отправитель
   $sender = $_GET["sid"];
   $receiver = $_GET["rid"];
   $where = "WHERE sender = ".$receiver." AND receiver = ".$sender."";
   $from = "sr&amp;sid=".$sender."&amp;rid=".$receiver."&amp;";
   }elseif ($w == "sandr"){ // Где отправитель И получатель
   $sender = $_GET["sid"];
   $receiver = $_GET["rid"];
   $where = "WHERE (sender = ".$receiver." AND receiver = ".$sender.") OR (sender = ".$sender." AND receiver = ".$receiver.")";
   $from = "w=sandr&amp;sid=".$sender."&amp;rid=".$receiver."&amp;";
   }elseif ($w == "sorr"){ // Где отправитель или получатель
   $sender = $_GET["sid"];
   $receiver = $_GET["rid"];
   $where = "WHERE sender = ".$receiver." OR receiver = ".$sender." OR sender = ".$sender." OR receiver = ".$receiver."";
   $from = "w=sorr&amp;sid=".$sender."&amp;rid=".$receiver."&amp;";
   }elseif ($w == "notr"){ // Где получателя нет
   $where = "WHERE receiver = ''";
   $from = "w=notr&amp;";
   } else {
   stderr("Ошибка", "Неизвестная акция!");
   }

}

$res2 = mysql_query("SELECT COUNT(*) FROM messages $where") or stderr("Неверные параметры поиска!");
$row = mysql_fetch_array($res2);
$count = $row[0];

if (!$count){
stderr("Извините, но сообщения не найдены.");
}

$perpage = 10;

list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?".$from."" );

stdhead("Шпион");

?>
<form method="GET" action="spion.php">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
<tr><td class="colhead" align="center">Поиск среди сообщений по ID</td>
</tr><tr>
<td align="center">
<nobr>
<b>Искать</b>:
<select name="w">
<option selected value="s">Отправителя</option>
<option value="r">Получателя</option>
</select>
&nbsp;&nbsp;
<b>Укажите ID пользователя</b>:
<input type="text" name="id" size="15">
&nbsp;&nbsp;
<input type="submit" value="Искать">
</nobr>
</td></tr></table></form>
<br>

<script language="Javascript" type="text/javascript">
<!-- Begin
var checkflag = "false";
var marked_row = new Array;
function check(field) {
if (checkflag == "false") {
   for (i = 0; i < field.length; i++) {
   field[i].checked = true;}
   checkflag = "true";
   } else {
   for (i = 0; i < field.length; i++) {
   field[i].checked = false;
   }
   checkflag = "false";
   }
}
//  End -->
</script>

<form method="post" action="take-delmp.php" name="form1">

<table border="1" cellspacing="1" cellpadding="1" width="100%">
<tr><td colspan="5" class=colhead align=center>
Просмотр сообщений (всего <font color="red"><?=$count?></font>)
</td></tr>
<tr><td colspan="5">
<div style="float:left;">
<?=$pagertop?>
</div>
<div style="float:right;">
<input type="submit" value="Удалить выбранное!" onClick="return confirm('Вы уверены?')">
</div>
</td></tr>
<tr>
<td class=colhead align=center>Отправитель/Получатель</td>
<td class=colhead align=center>ID</td>
<td class=colhead align=center>Содержание</td>
<td class=colhead align=center>Дата</td>
<td class=colhead>
<center><INPUT type="checkbox" title="Выбрать все" value="Выбрать все" onClick="this.value=check(document.form1.elements);"></center></td>
</tr>
<tr>
<?
$res = mysql_query("SELECT * FROM messages $where ORDER BY id DESC $limit") or sqlerr(__FILE__, __LINE__);
  while ($arr = mysql_fetch_assoc($res))
  {
    $res2 = mysql_query("SELECT username, class FROM users WHERE id=".$arr["receiver"]) or sqlerr(__FILE__, __LINE__);
    $arr2 = mysql_fetch_assoc($res2);

    if($arr["receiver"] == 0 or !$arr["receiver"]){
    $receiver = "<strike><b>Неизвестен</b></strike>";
    } else {
    $receiver = "<a href=userdetails.php?id=".$arr["receiver"].">".get_user_class_color($arr2["class"], $arr2["username"])."</a>";
    }

    $res3 = mysql_query("SELECT username, class FROM users WHERE id=".$arr["sender"]) or sqlerr(__FILE__, __LINE__);
    $arr3 = mysql_fetch_assoc($res3);

    if($arr["sender"] == 0){
    $sender = "<font color=red><b>Системное</b></font>";
    } else {
    $sender = "<a href=userdetails.php?id=".$arr['sender'].">".get_user_class_color($arr3["class"], $arr3["username"])."</a>";
    }
    $msg = format_comment($arr['msg']);
    $added = $arr['added'];

  print("<td align='left'>
        <div style='padding-top:5px; padding-bottom:10px;'>Отправитель:&nbsp;".$sender."</div>
        <div style='padding-top:10px; padding-bottom:5px;'>Получатель:&nbsp;".$receiver."</div>
        </td><td align=center>".$arr["id"]."</td>
        <td>$msg</td>
        <td align=center>$added</td>");
  print("<TD align=center><INPUT type=\"checkbox\" name=\"delmp[]\" value=\"".$arr['id']."\" id=\"checkbox_tbl_".$arr['id']."\">
          </TD></tr>");
}
?>
<tr>
<td class=colhead colspan="4"></td>
<td class=colhead>
<center><INPUT type="checkbox" title="Выбрать все" value="Выбрать все" onClick="this.value=check(document.form1.elements);"></center></td>
</tr>

<?
if ($where && $count){
?>
<tr><td colspan="5">
<a href="spion.php">Вернуться к общему списку сообщений</a>
</td></tr>
<?}?>

<tr><td colspan="5">
<div style="float:left;">
<?=$pagertop?>
</div>
<div style="float:right;">
<input type="submit" value="Удалить выбранное!" onClick="return confirm('Вы уверены?')">
</div>
</td></tr>
</table>
</form>
<br>
<?
stdfoot();
?>