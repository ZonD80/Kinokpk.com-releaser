<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $CURUSER, $use_sessions;

$a = mysql_fetch_array(sql_query("SELECT id, username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1"));

if ($CURUSER)
    $latestuser = "<a href=userdetails.php?id=" . $a["id"] . " class=\"online\">" . $a["username"] . "</a>";
else
    $latestuser = $a['username'];

$title_who = array();
$gues = array();

$dt = sqlesc(time() - 300);

if ($use_sessions)
    $result = sql_query("SELECT s.uid, s.username, s.class, s.ip FROM sessions AS s WHERE s.time > $dt ORDER BY s.class DESC");
else
    $result = sql_query("SELECT u.id, u.username, u.class FROM users AS u WHERE u.last_access > ".sqlesc(get_date_time(time() - 300))." ORDER BY u.class DESC");

while ($row = mysql_fetch_array($result)) {

$uid = $row["uid"];
$uname = $row["username"];
$class = $row["class"];
$ip = $row["ip"];
$uname_new = $uname;

    if (!empty($uname) && ($uname_new != $uname_old)) {
        $title_who[] = "<a href=\"userdetails.php?id=".$uid."\" class=\"online\">".get_user_class_color($class, $uname)."</a>";
    }


    if (($uname_new != $uname_old) && ($class >= UC_MODERATOR)) {
    $staff++;
    } elseif((!empty($uname) and $uname_new != $uname_old)) {
    $users++;
    }

    if ($uid <= 0 && !in_array("$ip",$gues)) {
    $guests++;
    $gues[] = "$ip";
    }

    if (empty($uname)) {
    continue;
    } else {
    $who_online .= $title_who;
    }

$uname_old = $uname;
}

$total = $staff + $users + $guests;

if ($staff == "")  $staff = 0;
if ($guests == "") $guests = 0;
if ($users == "")  $users = 0;
if ($total == "")  $total = 0;

$content .= "<table border=\"0\" width=\"100%\">
             <tr valign=\"middle\">
             <td align=\"left\" class=\"embedded\" style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'><b>Последний: </b> $latestuser</td></tr>";


if (count($title_who)) {
    $content .= "<tr valign=\"middle\">
                    <td align=\"left\" class=\"embedded\" style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
                    <b>Кто онлайн: </b><br>".@implode(", ", $title_who)."</td></tr>";
} else {
    $content .= "<tr valign=\"middle\">
                    <td align=\"left\" class=\"embedded\" style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
                    <b>Кто онлайн: </b><br>Нет пользователей за последние 10 минут.</td></tr>";
}
$content .= "<tr valign=\"middle\">
            <td align=\"left\" class=\"embedded\" style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
            <b>В сети: </b><br>";

$content .= "<img src=\"pic/info/admin.gif\" alt='Администраторы' align=\"absmiddle\" width='16' height='16'>&nbsp;<font color='red'>Админы: $staff</font> ";
$content .= "<img src=\"pic/info/member.gif\" alt='Пользователи' align=\"absmiddle\" width='16' height='16'>&nbsp;Пользователи: $users ";
$content .= "<img src=\"pic/info/guest.gif\" alt='Гости' align=\"absmiddle\" width='16' height='16'>&nbsp;Гости: $guests ";
$content .= "<img src=\"pic/info/group.gif\" alt='Всего' align=\"absmiddle\" width='16' height='16'>&nbsp;Всего: $total</td></tr>";
$content .= "</td></tr>";

$content .= "</td></tr></table>";
?>