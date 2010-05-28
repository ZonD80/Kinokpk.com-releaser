<?php
require "include/bittorrent.php";

dbconn();
loggedinorreturn();

if (get_user_class() < UC_SYSOP) {
        stdhead("Ошибка!");
        $iduser= ($CURUSER["id"]); $addusername = $CURUSER['username']; $link_touser = "<a target='_blank' href='userdetails.php?id=$iduser'>$addusername</a>";//Получаем данные пользователя
        write_log("$link_touser получил сообщение:<br>Доступ в этот раздел закрыт! (Попытка входа в Вкл/Откл сайта).","FF9900","error");//Пишем в лог
    stdmsg($tracker_lang['error'],"Доступ в этот раздел закрыт!", error);
        stdfoot();
        die();
        }

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST") 
{ 
    if (!$_POST["reason"]){ 
    stdhead("Ошибка!");
    stdmsg("Ошибка", "Вы не указали причину закрытия!", error); 
    stdfoot();
    die;
    }

$reason = sqlesc("".$_POST['reason']."");//Причина отключения
$class = $_POST["class"];                //Номер класса, меньше которого - доступ закрыт
$onoff = $_POST["onoff"];
$cname = $class;                         //Присваиваем переменной значение из выборки НОМЕР_КЛАССА

//Определяем имена классов, меньше которого - доступ закрыт
 switch ($cname) {
        case '0':
        $cname = "для Пользователей и выше";
        break;
        case '1':
        $cname = "для Продвинутых и выше";
        break;
        case '2':
        $cname = "для VIP'ов и выше";
        break;
        case '3':
        $cname = "для Аплоадеров и выше";
        break;
        case '4':
        $cname = "для Модераторов и выше";
        break;
        case '5':
        $cname = "для Администраторов и выше";
        break;
        case '6':
        $cname = "только для Директоров";
        break;
        //имя не найдено?
        default:
           $cname = "для всех";
        }

$class_name = sqlesc("$cname");          //Имя класса, меньше которого - доступ закрыт

sql_query("UPDATE siteonline SET onoff = $onoff, reason = $reason, class = $class, class_name = $class_name")
 or sqlerr(__FILE__, __LINE__);//Записываем новые значения в базу
 
 header("Location: $DEFAULTBASEURL/siteonoff.php");

}    

stdhead("Включение / Отключение сайта");

$res = sql_query("SELECT * FROM siteonline") or sqlerr(__FILE__, __LINE__);//Выбираем значения из базы
$row = mysql_fetch_array($res);                                            //

if ($row["onoff"] !=1){
$stroka = ("<td colspan='2' class=myhighlight style='padding:4px; background-color: #FF0000; color:#FFFFFF'>&nbsp;
          <b>Сайт&nbsp;ЗАКРЫТ</b>!&nbsp;Класс доступа:&nbsp;<b>".$row['class']."</b>&nbsp;(Доступ&nbsp;".$row['class_name'].").&nbsp;
          Ваш класс:&nbsp;<b>".$CURUSER['class']."</b>.</td>");
}
else {
$stroka = ("<td colspan='2' class=myhighlight style='padding:4px; background-color: #EAFFD5; color:#008000'>&nbsp;
          <b>Сайт&nbsp;сейчас&nbsp;ОТКРЫТ</b>!&nbsp;Доступ имеют все классы.&nbsp;
          Ваш класс:&nbsp;<b>".$CURUSER['class']."</b>.</td>");
}

?>
<form method="POST" action="siteonoff.php">
<table border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse">
<tr>
<td class=colhead><center><font size='3'>::&nbsp;&nbsp;Закрытие&nbsp;&nbsp;:&nbsp;&nbsp;Открытие сайта&nbsp;&nbsp;::</font></center></td>
</tr><tr><td><table border="0" cellspacing="1">
<tr><td class=embedded>
<table border="0" cellspacing="2"><tr><?=$stroka?></tr><tr>
<td  class=embedded colspan="2" height="3"></td></tr><tr>
<td class=colhead>&nbsp;Cообщение о закрытии сайта (допустим HTML):</td>
<td class=colhead>&nbsp;Значения и действия:</td></tr><tr>
<td class=embedded valign="top">
<textarea rows="9" name="reason" cols="60"><?=($row["reason"])?></textarea></td>
<td class=embedded align="left" valign="top">
<table border="0" cellspacing="1" id="table1" align="left">
<tr><td  class=embedded height="2" colspan="2"></td></tr>
<tr><td class=colhead colspan="2">&nbsp;Сайт сейчас:</td></tr><tr>
<td class=myhighlight width="50%"><b><font color=green>&nbsp;&nbsp;ВКЛ.</font></b><input type="radio" name="onoff" <?=($row["onoff"] == "1" ? "checked" : "")?> value="1"></td>
<td class=myhighlight width="50%"><b><font color=red>&nbsp;&nbsp;ВЫКЛ.</font></b><input type="radio" name="onoff" <?=($row["onoff"] == "0" ? "checked" : "")?> value="0"></td>
</tr><tr><td class=embedded height="5" colspan="2"></td></tr><tr>
<td class=colhead colspan="2">&nbsp;Имеют доступ:</td></tr><tr>
<td class=myhighlight colspan="2">
<select size="1" name="class" " style="<?=($row["onoff"] != 1 ? "color: #FFFFFF; background-color: #FF0000;" : "")?>">
<option <?=($row["class"] == "6" ? "selected" : "")?> value="6">Только Директорат</option>
<option <?=($row["class"] == "5" ? "selected" : "")?> value="5">Администраторы и выше</option>
<option <?=($row["class"] == "4" ? "selected" : "")?> value="4">Модераторы и выше</option>
<option <?=($row["class"] == "3" ? "selected" : "")?> value="3">Аплоадеры и выше</option>
<option <?=($row["class"] == "2" ? "selected" : "")?> value="2">VIP'ы и выше</option>
<option <?=($row["class"] == "1" ? "selected" : "")?> value="1">Продвинутые и выше</option>
<option <?=($row["class"] == "0" ? "selected" : "")?> value="0">Пользователи и выше</option>
</select>
</td></tr><tr><td class=embedded height="5" colspan="2"></td></tr><tr>
<td class=embedded colspan="2">
<p align="center"><input type="submit" value="Сохранить"></p></td></tr></table></td></tr>
</table></td></tr></table></td></tr></table>
</form>

<?
stdfoot();
?>