<?php
if (!defined("IN_TRACKER")) die("Direct acces to this file not allowed");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ru">
<head>
<meta name="Description" content="<?=$DESCRIPTION?>" />
<meta name="Keywords" content="<?=$KEYWORDS?>" />
<!--Тоже любишь смотреть исходники HTML? Знаешь еще и PHP/MySQL? обратись к админам, наверняка для тебя есть местечко в нашей команде! -->
<title><?= $title ?></title>
<link rel="stylesheet" href="./themes/<?=$ss_uri;?>/style.css" type="text/css" />
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>
<script language="javascript" type="text/javascript" src="js/tooltips.js"></script>
<script language="javascript" type="text/javascript" src="js/blankwin.js"></script>
<script language="javascript" type="text/javascript" src="js/spoiler.js"></script>
<script type="text/javascript">
$(document).ready(
function(){
  $('div.news-head')
  .click(function() {
    $(this).toggleClass('unfolded');
    $(this).next('div.news-body').slideToggle('slow');
  });
});
</script>

<link rel="alternate" type="application/rss+xml" title="Последние торренты" href="<?=$DEFAULTBASEURL?>/rss.php" />
<link rel="shortcut icon" href="<?=$DEFAULTBASEURL;?>/favicon.ico" type="image/x-icon" />
<meta name="verify-v1" content="7DgoCvTfzBKsPEooSMtET1HMC0Q9XIyw2UDJQbvNymY=" />
</head>
<body bgcolor="#505050" background="./themes/<?=$ss_uri;?>/images/rep_total.jpg" text="#000000" link="#363636" vlink="#363636" alink="#d5ae83">
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="./themes/<?=$ss_uri;?>/images/rep_top.jpg">
<tr>
<td align="center">
<a href="<?=$DEFAULTBASEURL;?>/"><img alt="<?=$SITENAME?>" title="<?=$SITENAME?>" style="border: none" src="./themes/<?=$ss_uri;?>/images/logo.gif" /></a>
</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="./themes/<?=$ss_uri;?>/images/top_2.jpg">
<tr>
<td>
<b>&nbsp;&nbsp;<a href="<?=$DEFAULTBASEURL;?>/"><font color="white"><?=$tracker_lang['homepage'];?></font></a>
<? if ($CURUSER) { ?>
&nbsp;&#8226;&nbsp;
<a href="browse.php"><font color="white"><?=$tracker_lang['browse'];?></font></a>
&nbsp;&#8226;&nbsp;
<a href="upload.php"><font color="white"><?=$tracker_lang['upload'];?></font></a>
&nbsp;&#8226;&nbsp;
<a href="bookmarks.php"><font color="white"><?=$tracker_lang['bookmarks'];?></font></a>
<? } ?>
&nbsp;&#8226;&nbsp;
<a href="<?=$FORUMURL?>/index.php"><font color="white"><?=$tracker_lang['forum']." ".$FORUMNAME;?></font></a>
<?php if ($CURUSER) print ("<br />"); else print("&nbsp;&#8226;&nbsp;");?>
&nbsp;
<a href="rules.php"><font color="white"><?=$tracker_lang['rules'];?></font></a>
&nbsp;&#8226;&nbsp;
<a href="faq.php"><font color="white"><?=$tracker_lang['faq'];?></font></a>
&nbsp;&#8226;&nbsp;
<? if ($CURUSER) { ?>
<a href="staff.php"><font color="white"><?=$tracker_lang['staff'];?></font></a>
&nbsp;&#8226;&nbsp;
<? } ?>
<a href="copyrights.php"><font color="white">Copyrights</font></a>
&nbsp;&#8226;&nbsp;
<a href="getrss.php"><font color="white">RSS</font></a>
<? if ($CURUSER) { ?>
&nbsp;&#8226;&nbsp;
<a href="peers.php"><font color="white">Статистика пиров</font></a></b>
<? } ?>
<!--Этот скрипт отвечает за показ ваших сообщений и часов-->

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

//// end

?>
<td class="bottom" align="left"><span class="smallfont"><?=$tracker_lang['welcome_back'];?><b><a href="userdetails.php?id=<?=$CURUSER['id']?>"><?=get_user_class_color($CURUSER['class'], $CURUSER['username'])?></a></b><?=$usrclass?><?=$medaldon?><?=$warn?>&nbsp; [<a href="bookmarks.php">Закладки</a>] [<a href="viewrequests.php?requestorid=<?=$CURUSER["id"];?>">Мои запросы</a>] [<a href="mybonus.php">Мой бонус</a>] [<a href="logout.php">Выйти</a>]<br/>
<font color=1900D1>Рейтинг:</font> <?=$ratio?>&nbsp;&nbsp;<font color=green>U:</font> <font color=black><?=$uped?></font>&nbsp;&nbsp;<font color=darkred>D:</font> <font color=black><?=$downed?></font>&nbsp;&nbsp;<font color=darkblue>Бонус:</font> <a href="mybonus.php" class="online"><font color=black><?=$CURUSER["bonus"]?></font></a>&nbsp;&nbsp;<font color=1900D1>Фильмы:&nbsp;</font></span> <img alt="Раздаю" title="Раздаю" src="./themes/<?=$ss_uri;?>/images/arrowup.gif">&nbsp;<font color=black><span class="smallfont"><?=$activeseed?></span></font>&nbsp;&nbsp;<img alt="Качаю" title="Качаю" src="./themes/<?=$ss_uri;?>/images/arrowdown.gif">&nbsp;<font color=black><span class="smallfont"><?=$activeleech?></span></font></td>
<td class="bottom" align="right">

<?
if ($messages){
print("<span class=smallfont><a href=message.php>$inboxpic</a> $messages ($unread новых)</span>");
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
print("&nbsp;<a href=getrss.php><img style=border:none alt=RSS title=RSS src=pic/rss.gif></a>&nbsp;<br />");
?>
<span class="smallfont">Время: <span id="clock">Загрузка...</span>&nbsp;

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
if (s<10) {s="0" + s}
if (m<10) {m="0" + m}
if (h<10) {h="0" + h}
document.getElementById("clock").innerHTML=h + ":" + m + ":" + s;
setTimeout("refrClock()",1000);
}
refrClock();
</script>
<!-- / clock hack -->
</span>
<? } else {?>
<br />
<? } ?>
<!-- \ Конец скрипта-->
</tr>
</table>

<?php

$w = "width=\"100%\"";
//if ($_SERVER["REMOTE_ADDR"] == $_SERVER["SERVER_ADDR"]) $w = "width=984";

?>
<table class="mainouter" width="100%"  border="0" cellspacing="0" cellpadding="5"><tr valign="top">
<? $fn = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/") + 1); ?>
<td valign="top" width="165">
<?

show_blocks("l");

	$bt_clients = '&nbsp;&nbsp;<a href="http://bitconjurer.org/BitTorrent/download.html" target="_blank"><font class=small color=green>'.$tracker_lang['official'].'</font></a><br />'
  			.'&nbsp;&nbsp;<a href="http://azureus.sourceforge.net/" target="_blank"><font class=small color=green>Azureus (Java)</font></a><br />'
  			.'&nbsp;&nbsp;<a href="http://www.bittornado.com/" target="_blank"><font class=small color=green>BitTornado</font></a><br />'
  			.'&nbsp;&nbsp;<a href="http://www.bitcomet.com/" target="_blank"><font class=small color=green>BitComet</font></a><br />'
  			.'&nbsp;&nbsp;<a href="http://www.bitlord.com/" target="_blank"><font class=small color=green>BitLord</font></a><br />'
  			.'&nbsp;&nbsp;<a href="http://www.macupdate.com/info.php/id/7170" target="_blank"><font class="small" color=green>Acquisition (Mac)</font></a><br />'
  			.'&nbsp;&nbsp;<a href="http://www.167bt.com/intl/" target="_blank"><font class=small color=green>BitSpirit</font></a><br />'
  			.'<hr width=100% color=#ffc58c size=1>'
			.'<font class=small color=red>&nbsp;&nbsp;'.$tracker_lang['clients_recomened_by_us'].'</font>';

	blok_menu("<center>".$tracker_lang['torrent_clients']."</center>", $bt_clients , "155");

?>
</td><td align="center" valign="top" style="padding-top: 5px; padding-bottom: 5px">
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0"></table></td></tr><tr><td>

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
		print("<p><table border=0 cellspacing=0 cellpadding=10 bgcolor=green><tr><td style='padding: 10px; background: green'>\n");
		print("<b><a href=\"$DEFAULTBASEURL/restoreclass.php\"><font color=white>".$tracker_lang['lower_class']."</font></a></b>");
		print("</td></tr></table></p>\n");
}

 show_blocks('c');

 ?>