<?
require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_SYSOP)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);
stdhead("Массовый E-mail");
begin_frame("Массовый E-mail", "70", true);

?>
<form method=post name=message action=takeemail.php>
<table>
<TR><TD colspan="2" style="border: 0">&nbsp;Тема:
   <INPUT name="subject" type="text" size="70"></TD>
</TR>
<tr><td align="center" style="border: 0">
<? textbbcode("message","msg",$body, 0); ?>
</td></tr>
<tr><td colspan=2 align=center style="border: 0"><input type=submit value="Отправить" class=btn>
</td></tr>
</table>
</form>
<?
end_frame();
stdfoot();
?>