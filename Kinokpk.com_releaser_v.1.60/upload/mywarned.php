<?
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();
$STOIMOST = 30*1024*1024*1024;

if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    header("Content-Type: text/html; charset=".$tracker_lang['language_charset']);
    if (empty($_POST["id"])) {
        stdmsg($tracker_lang['error'], "Вы не выбрали кол-во предупреждений!");
        die();
 }
    $id = (int) $_POST["id"];
    if (!is_valid_id($id))
     {
        stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
        die();
    }

    if ($CURUSER["uploaded"] < ($id*$STOIMOST))
     {
        stdmsg($tracker_lang['error'], "У вас недостаточно уплоада!");
        die();
    }

    $modcomment = sqlesc(gmdate("Y-m-d") . " - Пользователь обменял " .$id. " предупреждений на ".mksize($id*$STOIMOST)."\n " . $CURUSER['modcomment']);
    if (!sql_query("UPDATE users SET num_warned = num_warned - $id, uploaded = uploaded - ($id*$STOIMOST), modcomment = ".$modcomment." WHERE id = ".sqlesc($CURUSER["id"])))
   {
     stdmsg($tracker_lang['error'], "Не могу обновить предупреждения!");
     die();
    }
    $zalet = mksize($id*$STOIMOST);
    stdmsg($tracker_lang['success'], "$id предупреждение(я) обменяны на $zalet аплоада!");
    //break;
    die();

    }
else
{
stdhead("Мои предупреждения");
?>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
function send(){

   var frm = document.mywarned;
    var bonus_type = '';

    for (var i=0; i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ bonus_type = elmnt.value; break;}
        }
    }

    var ajax = new tbdev_ajax();
    ajax.onShow ('');
    var varsString = "";
    ajax.requestFile = "mywarned.php";
    ajax.setVar("id", bonus_type);
    ajax.method = 'POST';
    ajax.element = 'ajax';
    ajax.sendAJAX(varsString);
}
</script>
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
    <div style="font-weight:bold" id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div><br />
    <img src="pic/loading.gif" border="0" />
</div>
<div id="ajax">
<table class="embedded" width="100%" border="1" cellspacing="0" cellpadding="5">
<?

    $myupl = mksize($CURUSER[uploaded]);
    for($i = 1; $i <= 5; $i++)
    {    $id = $i;
        $upl = mksize($STOIMOST*$i);
        $img.="<img src=\"".$pic_base_url."star_warned.gif\" alt=\"Уровень предупреждений\" title=\"Уровень предупреждений\">";
        $descr ="Обменять ".$i." предупреждение(я)";

        if ($CURUSER["num_warned"]>=$i)
        {$distup = enable; $chec = checked;}
          else
         {$distup = disabled; $chec = "";}


        $output .= "<tr><td><b>$img</b><br />$descr</td><td><center>$upl&nbsp;/&nbsp;$myupl</center></td><td><center><input type=\"radio\" name=\"warned_id\" value=\"$id\" $chec $distup /></center></td></tr>\n";
    }
?>
    <tr align="center"><td class="colhead" colspan="3">Мои предупреждения <?=$CURUSER["num_warned"];?>, аплоад в наличии <?=mksize($CURUSER["uploaded"]);?></td></tr>
    <tr align="center"><td class="colhead">Кол-во предупреждений</td><td class="colhead">Стоимость</td><td class="colhead">Выбор</td></tr>
    <form action="mywarned.php" name="mywarned" method="post">
<?=$output;?>
        <tr align="right"><td colspan="3"><input type="submit" onClick="send(); return false;" value="Обменять" /></td></tr>
    </form>
</table>
</div>
<?
stdfoot();
}
?>