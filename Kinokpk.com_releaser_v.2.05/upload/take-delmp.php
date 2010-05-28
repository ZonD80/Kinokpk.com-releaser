<?
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_SYSOP){
die;
}

if(isset($_POST["delmp"])) {
    $do = "DELETE FROM messages WHERE id IN (".implode(", ", $_POST[delmp]).")";
    $res=sql_query($do);
    } else {
    stdhead("Ошибка");
    print("<div class='error'><b>Вы не выбрали сообщения для удаления!</b></div>");
    print("<center><INPUT TYPE='button' VALUE='Назад' onClick=\"history.go(-1)\"></center>");
    stdfoot();
    die;
    }
echo "<script>history.go(-1);</script>";
?>