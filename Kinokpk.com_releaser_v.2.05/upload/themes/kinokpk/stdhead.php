<?php
if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");
?>
<script language="javascript" type="text/javascript" src="./themes/<?=$ss_uri?>/dropdown/dropdown.js"></script>
</head>
<body>
<br>
<table width="95%" class="clear" align="center" border="0" cellspacing="0" cellpadding="0" style="background: transparent;">
<tr>
<td class="embedded" width="50%" background="./themes/<?=$ss_uri;?>/images/top.jpg">
<a href="<?=$DEFAULTBASEURL?>"><img style="border: none" alt="<?=$SITENAME?>" title="<?=$SITENAME?>" src="./themes/<?=$ss_uri;?>/images/logo.jpg" /></a>


</td>
</td>
</tr>
</table>
</body>
<!-- Top Navigation Menu for unregistered-->
<table width="95%" align="center" border="0" cellspacing="0" cellpadding="0">
<tr><td class=menu style='border-style: solid none none none; padding-left: 10px;'>
<div id='topmenu'><ul>
<a href="<?=$DEFAULTBASEURL?>"><span><?=$tracker_lang['homepage'];?></span></a>
<a href="browse.php"><span><?=$tracker_lang['browse'];?></span></a>
<? if ($CURUSER) { ?>
<a href="upload.php"><span><?=$tracker_lang['upload'];?></span></a>
<? } else { ?>
<a href="login.php"><span>Вход</span></a>
<a href="signup.php"><span>Регистрация</span></a>
<? } ?>
<a href="<?=$FORUMURL?>/index.php"><span><?=$tracker_lang['forum']." ".$FORUMNAME;?></span></a>
<a href="rules.php"><span><?=$tracker_lang['rules'];?></span></a>
<a href="faq.php"><span><?=$tracker_lang['faq'];?></span></a>
<a href="copyrights.php"><span>Copyrights</span></a>
<? if ($CURUSER) { ?>
<a href="peers.php"><span>Статистика пиров</span></a>
<a href="staff.php"><span><?=$tracker_lang['staff'];?></span></a>
<div class="chromestyle" id="chromemenu">
<a href="#" rel="dropmenu1">&nbsp;&nbsp;Меню</a>
<? } ?>
<? if (get_user_class() >= UC_MODERATOR) { ?>
<a href="#" rel="dropmenu2">&nbsp;&nbsp;&nbsp;&nbsp;Админ Меню</a>
<? } ?>
<div id="dropmenu1" class="dropmenudiv">
<a href="my.php">Панель управления</a>
<a href="userdetails.php?id=<?=$CURUSER['id']?>">Профиль</a>
<a href="bookmarks.php">Закладки</a>
<a href="mybonus.php">Мой бонус</a>
<a href="mywarned.php">Мои предупреждения</a>
<a href="invite.php">Пригласить</a>
<a href="friends.php">Мои друзья</a>
<a href="subnet.php">Соседи</a>
<a href="mytorrents.php">Мои Релизы</a>
<a href="users.php">Пользователи</a>
<a href="testport.php">Преверить порт</a>
<a href="topten.php">Топ 10</a>
<a href="log.php">Журнал</a>
<a href="donate.php">Помошь трекеру</a>
<a href="formats.php">Форматы файлов</a>
</div>
<div id="dropmenu2" class="dropmenudiv">
<a href="admincp.php">Админка</a>
<a href="online.php">Ху из онлайн?!</a>
<a href="newsarchive.php">Редактировать новости</a>

<a href="viewreport.php">Жалобы на торренты</a>
<a href="users.php">Список пользователей</a>
<a href="staffmess.php">Массовое ЛС</a>
<a href="ipcheck.php">Двойники IP</a>
<a href="admincp.php?op=iUsers">Сменить пароль и мыло</a>
<a href="clearcache.php">Очистить кеш</a>
</div>
</div>
</ul></div></td></tr></table>
<script type="text/javascript">

cssdropdown.startchrome("chromemenu")

</script>
<!-- /////// Top Navigation Menu for unregistered-->

<!-- /////// some vars for the statusbar;o) //////// -->

<? if ($CURUSER) { ?>

<?

$datum = getdate();

$datum[hours] = sprintf("%02.0f", $datum[hours]);

$datum[minutes] = sprintf("%02.0f", $datum[minutes]);

$datum[seconds] = sprintf("%02.0f", $datum[seconds]);

$uped = mksize($CURUSER['uploaded']);

$downed = mksize($CURUSER['downloaded']);

if ($CURUSER["downloaded"] > 0)

{

$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];

$ratio = number_format($ratio, 3);

$color = get_ratio_color($ratio);

if ($color)

$ratio = "<font color=$color>$ratio</font>";

}

else

if ($CURUSER["uploaded"] > 0)

$ratio = "Inf.";

else

$ratio = "---";

if ($CURUSER['donor'] == "yes")
        $medaldon = "<img src=\"pic/star.gif\" alt=\"Донор\" title=\"Донор\">";
if ($CURUSER['warned'] == "yes")
        $warn = "<img src=\"pic/warned.gif\" alt=\"Предупрежден\" title=\"Предупрежден\">";

if (get_user_class() >= UC_MODERATOR) $usrclass = "&nbsp;<a href=\"setclass.php\"><img src=\"pic/warning.gif\" title=\"".get_user_class_name($CURUSER['class'])."\" alt=\"".get_user_class_name($CURUSER['class'])."\" border=\"0\"></a>&nbsp;";
//// check for messages //////////////////
        $res1 = sql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND location=1") or print(mysql_error());
        $arr1 = mysql_fetch_row($res1);
        $messages = $arr1[0];
        /*$res1 = sql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND location=1 AND unread='yes'") or print(mysql_error());
        $arr1 = mysql_fetch_row($res1);
        $unread = $arr1[0];*/
        $res1 = sql_query("SELECT COUNT(*) FROM messages WHERE sender=" . $CURUSER["id"] . " AND saved='yes'") or print(mysql_error());
        $arr1 = mysql_fetch_row($res1);
        $outmessages = $arr1[0];
        if ($unread)
                $inboxpic = "<img height=\"16px\" style=\"border:none\" alt=\"inbox\" title=\"Есть новые сообщения\" src=\"pic/pn_inboxnew.gif\">";
        else
                $inboxpic = "<img height=\"16px\" style=\"border:none\" alt=\"inbox\" title=\"Нет новых сообщений\" src=\"pic/pn_inbox.gif\">";

$res2 = sql_query("SELECT COUNT(*) FROM peers WHERE userid=" . $CURUSER["id"] . " AND seeder='yes'") or print(mysql_error());
$row = mysql_fetch_row($res2);
$activeseed = $row[0];

$res2 = sql_query("SELECT COUNT(*) FROM peers WHERE userid=" . $CURUSER["id"] . " AND seeder='no'") or print(mysql_error());
$row = mysql_fetch_row($res2);
$activeleech = $row[0];
?>
<!-- //////// start the statusbar ///////////// -->
<table style="border-style:none" align="center"  cellpadding="0" cellspacing="0" width=95%>
<td width='800' height='32' style='background-image: url(./themes/<?=$ss_uri;?>/images/mem.png)' class="bottom" >&nbsp;&nbsp;<span class="smallfont"><?=$tracker_lang['welcome_back'];?><b><a href="userdetails.php?id=<?=$CURUSER['id']?>"><?=get_user_class_color($CURUSER['class'], $CURUSER['username'])?>
</a></b><?=$usrclass?><?=$medaldon?><?=$warn?>&nbsp;&nbsp;&nbsp;&nbsp; <font color=black><a href="logout.php">Выйти</a></font>&nbsp;&nbsp;<font color=1900D1>Рейтинг:</font> <?=$ratio?>&nbsp;&nbsp;<font color=green>Раздал:</font> <font color=black><?=$uped?></font>&nbsp;&nbsp;<font color=darkred>Скачал:</font> <font color=black><?=$downed?></font>&nbsp;&nbsp;<font color=darkblue>Бонус:</font> <a href="mybonus.php" class="online"><font color=black><?=$CURUSER["bonus"]?></font></a>&nbsp;&nbsp;<font color=1900D1>Торренты:&nbsp;</font></span> <img alt="Раздает" title="Раздает" src="./themes/<?=$ss_uri;?>/images/arrowup.gif">&nbsp;<font color=black><span class="smallfont"><?=$activeseed?></span></font>&nbsp;&nbsp;<img alt="Качает" title="Качает" src="./themes/<?=$ss_uri;?>/images/arrowdown.gif">&nbsp;<font color=black><span class="smallfont"><?=$activeleech?></span></font></td>
<td width=159 height='32' style='background-image: url(./themes/<?=$ss_uri;?>/images/mem.png)' class="bottom" ><span class="smallfont"><font color=ffffff><span id="clock">Загрузка...</span></font><br/>
<!-- clock hack -->
<script type="text/javascript">
function refrClock()
{
var d=new Date();
var s=d.getSeconds();
var m=d.getMinutes();
var h=d.getHours();
var day=d.getDay();
var date=d.getDate();
var month=d.getMonth();
var year=d.getFullYear();
var am_pm;
if (s<10) {s="0" + s}
if (m<10) {m="0" + m}
if (h>12) {h-=12;am_pm = "PM"}
else {am_pm="AM"}
if (h<10) {h="0" + h}
document.getElementById("clock").innerHTML=h + ":" + m + ":" + s + " " + am_pm;
setTimeout("refrClock()",1000);
}
refrClock();
</script>
<!-- / clock hack -->

<?
if ($messages){
print("<span class=smallfont><a href=message.php>$inboxpic</a> $messages ($unread)</span>");
if ($outmessages)
print("<span class=smallfont>&nbsp;&nbsp;<a href=message.php?action=viewmailbox&box=-1><img height=16px style=border:none alt=Отправленые title=Отправленые src=pic/pn_sentbox.gif></a> $outmessages</span>");
else
print("<span class=smallfont>&nbsp;&nbsp;<a href=message.php?action=viewmailbox&box=-1><img height=16px style=border:none alt=Отправленые title=Отправленые src=pic/pn_sentbox.gif></a> 0</span>");
}
else
{
print("<span class=smallfont><a href=message.php><img height=16px style=border:none alt=Полученные title=Полученные src=pic/pn_inbox.gif></a> 0</span>");
if ($outmessages)
print("<span class=smallfont>&nbsp;&nbsp;<a href=message.php?action=viewmailbox&box=-1><img height=16px style=border:none alt=Отправленые title=Отправленые src=pic/pn_sentbox.gif></a> $outmessages</span>");
else
print("<span class=smallfont>&nbsp;&nbsp;<a href=message.php?action=viewmailbox&box=-1><img height=16px style=border:none alt=Отправленые title=Отправленые src=pic/pn_sentbox.gif></a> 0</span>");
}
print("&nbsp;<a href=friends.php><img style=border:none alt=Друзья title=Друзья src=pic/buddylist.gif></a>");
print("&nbsp;<a href=rss.php><img style=border:none alt=RSS title=RSS src=pic/rss.gif></a>");
print("&nbsp;<a href=atom.php><img style=border:none alt=ATOM title=ATOM src=pic/atom.gif></a>");

?>
</span></td></table>

<? } ?>

<?php

$w = "width=\"95%\"";
//if ($_SERVER["REMOTE_ADDR"] == $_SERVER["SERVER_ADDR"]) $w = "width=984";

?>
<table class="mainouter" align="center" <?=$w; ?> border="0" cellspacing="0" cellpadding="5">

<!------------- MENU ------------------------------------------------------------------------>

<? $fn = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/") + 1); ?>

<td valign="top" width="155">
<?

show_blocks("l");

if ($messages) {
                $message_in = "<span class=\"smallfont\">&nbsp;<a href=\"message.php\">$inboxpic</a> $messages " . sprintf($tracker_lang["new_pm"], $unread) . "</span>";
                if ($outmessages)
                        $message_out = "<span class=\"smallfont\">&nbsp;<a href=\"message.php?action=viewmailbox&box=-1\"><img height=\"16px\" style=\"border:none\" alt=\"" . $tracker_lang['outbox'] . "\" title=\"" . $tracker_lang['outbox'] . "\" src=\"pic/pn_sentbox.gif\"></a> $outmessages</span>";
                else
                        $message_out = "<span class=\"smallfont\">&nbsp;<a href=\"message.php?action=viewmailbox&box=-1\"><img height=\"16px\" style=\"border:none\" alt=\"" . $tracker_lang['outbox'] . "\" title=\"" . $tracker_lang['outbox'] . "\" src=\"pic/pn_sentbox.gif\"></a> 0</span>";
        }
        else {
                $message_in = "<span class=\"smallfont\">&nbsp;<a href=\"message.php\"><img height=\"16px\" style=\"border:none\" alt=\"".$tracker_lang['inbox']."\" title=\"".$tracker_lang['inbox']."\" src=\"pic/pn_inbox.gif\"></a> 0</span>";
                if ($outmessages)
                        $message_out = "<span class=\"smallfont\">&nbsp;<a href=\"message.php?action=viewmailbox&box=-1\"><img height=\"16px\" style=\"border:none\" alt=\"" . $tracker_lang['outbox'] . "\" title=\"" . $tracker_lang['outbox'] . "\" src=\"pic/pn_sentbox.gif\"></a> $outmessages</span>";
                else
                        $message_out = "<span class=\"smallfont\">&nbsp;<a href=\"message.php?action=viewmailbox&box=-1\"><img height=\"16px\" style=\"border:none\" alt=\"" . $tracker_lang['outbox'] . "\" title=\"" . $tracker_lang['outbox'] . "\" src=\"pic/pn_sentbox.gif\"></a> 0</span>";
        }
?>
</td>
<td align="center" valign="top" class="outer" style="padding-top: 5px; padding-bottom: 5px">
<?
if ($CURUSER) {
    if ($unread)
    {
        print("<p><table border=0 cellspacing=0 cellpadding=10 bgcolor=red><tr><td style='padding: 10px; background: red'>\n");
        print("<b><a href=\"message.php\"><font color=white>".sprintf($tracker_lang['new_pms'],$unread)."</font></a></b>");
        print("</td></tr></table></p>\n");
    }
}
if ($CURUSER['override_class'] != 255 && $CURUSER) // Second condition needed so that this box isn't displayed for non members/logged out members.
{
                print("<p><table border=0 cellspacing=0 cellpadding=10 bgcolor=green><tr><td style='padding: 5px; background: green'>\n");
                print("<b><a href=\"$DEFAULTBASEURL/restoreclass.php\"><font color=white>".$tracker_lang['lower_class']."</font></a></b>");
                print("</td></tr></table></p>\n");
}


 show_blocks('c');
?>


