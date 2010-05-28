<?php 
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

$condition = '';
$countcond = '';

if ($_GET['view'] == 'leechers') {$condition = "AND p.seeder = 'no'"; $countcond = " WHERE seeder = 'no'";}
if ($_GET['view'] == 'seeders') {$condition = "AND p.seeder = 'yes'"; $countcond = " WHERE seeder = 'yes'"; }

if (get_user_class() < UC_USER)
    die($tracker_lang['access_denied']);

$res = sql_query("SELECT COUNT(*) FROM peers".$countcond) or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];

$torrentsperpage = $CURUSER["torrentsperpage"];
if (!$torrentsperpage)
        $torrentsperpage = 25;

$addparam = $_SERVER['QUERY_STRING'];

   if ($addparam != "")
    $addparam = $addparam . "&" . $pagerlink;
 else
    $addparam = $pagerlink;

        list($pagertop, $pagerbottom, $limit) = pager($torrentsperpage, $count, "peers.php?" . $addparam);
        
        
$cheaters = sql_query("SELECT p.torrent AS tid, t.name AS tname, p.ip, p.port, s.uploaded, s.downloaded, s.to_go, p.seeder, p.agent, p.peer_id, p.userid, u.username, u.class, u.enabled, u.warned, u.donor, (p.uploadoffset / (UNIX_TIMESTAMP(p.last_action) - UNIX_TIMESTAMP(p.prev_action))) AS upspeed, (p.downloadoffset / (UNIX_TIMESTAMP(p.last_action) - UNIX_TIMESTAMP(p.prev_action))) AS downspeed FROM peers AS p INNER JOIN users AS u ON u.id = p.userid INNER JOIN torrents AS t ON t.id = p.torrent INNER JOIN snatched AS s ON (s.userid = p.userid AND s.torrent = p.torrent) WHERE u.enabled = 'yes' ".$condition." ORDER BY upspeed DESC $limit") or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($cheaters) > 0) {
  stdhead("Статистика пиров");
        print($pagertop);
        
    print("<table cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">");
if (get_user_class() >= UC_MODERATOR)
    print("<tr><td class=\"colhead\">Юзер</td><td class=\"colhead\">Торрент</td><td class=\"colhead\">IP&nbsp;/&nbsp;Порт</td><td class=\"colhead\">Раздал</td><td class=\"colhead\">Скачал</td><td class=\"colhead\">Осталось</td><td class=\"colhead\">Раздача</td><td class=\"colhead\">Закачка</td><td class=\"colhead\">Сид</td><td class=\"colhead\">Агент</td><td class=\"colhead\">Peer_id</td></tr>");
    else
    print("<tr><td class=\"colhead\">Юзер</td><td class=\"colhead\">Торрент</td><td class=\"colhead\">Раздал</td><td class=\"colhead\">Скачал</td><td class=\"colhead\">Осталось</td><td class=\"colhead\">Раздача</td><td class=\"colhead\">Закачка</td><td class=\"colhead\">Сид</td><td class=\"colhead\">Агент</td></tr>");
    while ($cheater = mysql_fetch_array($cheaters)) { 
        list($tid, $tname, $ip, $port, $uploaded, $downloaded, $left, $seeder, $agent, $peer_id, $userid, $username, $class, $enabled, $warned, $donor, $upspeed, $downspeed) = $cheater;
        list($uploaded, $downloaded, $left, $upspeed, $downspeed) = array_map("mksize", array($uploaded, $downloaded, $left, $upspeed, $downspeed));
        if ($seeder == "yes")
            $is_seed = "<span style=\"color: green\">Да</span>";
        else
            $is_seed = "<span style=\"color: red\">Нет</span>";
        if (strlen($tname) > 50) 
            $tname = substr($tname, 0, 50)."..."; 
        $peer_id = substr($peer_id, 0, 7);

        if (get_user_class() >= UC_MODERATOR)
        print("<tr><td><nobr><a href=\"userdetails.php?id=$userid\">".get_user_class_color($class, $username)."</a>".get_user_icons(array("enabled" => $enabled, "donor" => $donor, "warned" => $warned))."</nobr></td><td><nobr><a href=\"details.php?id=$tid\">$tname</a></nobr></td><td>$ip:$port</td><td><nobr>$uploaded</nobr></td><td><nobr>$downloaded</nobr></td><td><nobr>$left</nobr></td><td><nobr>$upspeed/s</nobr></td><td><nobr>$downspeed/s</nobr></td><td align=\"center\">$is_seed</td><td><nobr>$agent</nobr></td><td>$peer_id</td></tr>");
        else
        print("<tr><td><nobr><a href=\"userdetails.php?id=$userid\">".get_user_class_color($class, $username)."</a>".get_user_icons(array("enabled" => $enabled, "donor" => $donor, "warned" => $warned))."</nobr></td><td><nobr><a href=\"details.php?id=$tid\">$tname</a></nobr></td><td><nobr>$uploaded</nobr></td><td><nobr>$downloaded</nobr></td><td><nobr>$left</nobr></td><td><nobr>$upspeed/s</nobr></td><td><nobr>$downspeed/s</nobr></td><td align=\"center\">$is_seed</td><td><nobr>$agent</nobr></td></tr>");
    } 
    print("</table>");

        print($pagerbottom);
} else stderr($tracker_lang['error'], "Нет статистики, удовлетворяющей выбранным фильтрам.");

stdfoot(); 

?>