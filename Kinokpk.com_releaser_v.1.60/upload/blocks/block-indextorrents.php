<?php 
if (!defined('BLOCK_FILE')) { 
 Header("Location: ../index.php"); 
 exit; 
} 
//выбираем сидов 
function dltable($arr, $torrent) 
{ 
        global $CURUSER, $tracker_lang; 
        $s = "<b>" . count($arr) . " $name</b>\n"; 
        if (!count($arr)) 
                return $s; 
        $s .= "\n"; 
        $now = time(); 
        $moderator = (isset($CURUSER) && get_user_class() >= UC_USER); 
        $mod = get_user_class() >= UC_USER; 
        foreach ($arr as $e) { 
                // user/ip/port 
                // check if anyone has this ip 
                if ($e["username"]) 
                  $s .= "<a href=\"userdetails.php?id=$e[userid]\"><b>".get_user_class_color($e["class"], $e["username"])."</b></a>".($mod ? "&nbsp;[<span title=\"{$e["ip"]}\" style=\"cursor: pointer\">IP</span>]" : "").",\n";
                else 
                  $s .= "" . ($mod ? $e["ip"] : preg_replace('/\.\d+$/', ".xxx", $e["ip"])) . ",\n"; 
                $secs = max(5, ($e["la"]) - $e["pa"]); 
                $revived = $e["revived"] == "yes"; 
        } 
        return $s; 
} 
//конец отбора
$res1 = sql_query("SELECT COUNT(*) FROM torrents WHERE banned = 'no' AND visible = 'yes'");
$row1 = mysql_fetch_array($res1); 
$count = $row1[0]; 
$blocktitle = "Релизы".(get_user_class() >= UC_USER ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"upload.php\"><b>Залить</b></a>]  </font>" : "<font class=\"small\"> - (новые поступления)</font>");
$content .= "<table cellspacing=\"0\" cellpadding=\"5\" width=\"100%\"><tr><td id=\"centerCcolumn\">"; 
if (!$count) { 
    $content .= "Нет релизов ...";
} else { 
    $perpage = 5; 
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?" ); 
    $res = sql_query("SELECT torrents.*, categories.id AS catid, categories.name AS catname, categories.image AS catimage, users.username FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id  WHERE banned = 'no' AND visible = 'yes' ORDER BY added DESC $limit") or sqlerr(__FILE__, __LINE__);
    $content .= $pagertop; 
    $content .= "</td></tr>"; 
    while ($release = mysql_fetch_array($res)) {
       $torid = $release['id'];
        $catid = $release["catid"]; 
        $catname = $release["catname"]; 
        $catimage = $release["catimage"]; 
                $torname = $release["name"];
    $detid = mysql_query("SELECT descr_torrents.value, descr_details.name, descr_details.description, descr_details.input FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$torid." AND descr_details.mainpage = 'yes' ORDER BY descr_details.sort ASC");
    $descr = '<table width="100%" border="1">';
while ($did = mysql_fetch_array($detid))  {
   if ($did['value'] != '') $descr .= "<tr><td><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
  }
   $descr .="</table>";
                $uprow = (isset($release["username"]) ? ("<a href=userdetails.php?id=" . $release["owner"] . ">" . htmlspecialchars($release["username"]) . "</a>") : "<i>Аноним</i>");

                $img1 = "<a href=\"details.php?id=$release[id]&hit=1\"><img src=\"pic/noimage.gif\" width=\"160\" border=\"0\" /></a>";
                $img2 = ''; 
$seeders = array(); 
$subres = sql_query("SELECT seeder, finishedat, downloadoffset, uploadoffset, peers.ip, port, peers.uploaded, peers.downloaded, to_go, connectable, agent, peer_id, userid, users.username, users.class FROM peers INNER JOIN users ON peers.userid = users.id WHERE torrent = $release[id]") or sqlerr(__FILE__, __LINE__); 
   while ($subrow = mysql_fetch_array($subres)) { 
   if ($subrow["seeder"] == "yes") 
       $seeders[] = $subrow; 
   } 
        $content .= "<tr><td>"; 
        $content .= "<table width=\"100%\" class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">"; 
        $content .= "<tr>"; 
        $content .= "<td class=\"colhead\" colspan=\"2\" align=center>"; 
        $content .= $release["name"];
        $content .= "<a class=\"altlink_white\" href=\"bookmark.php?torrent=$release[id]\">   "; 
        $content .= "</font></td>"; 
        $content .= "</tr>"; 
                  if ($release["image1"] != "") 
                    $img1 = "<a href=\"details.php?id=$release[id]&hit=1\"><img width=\"160\" border='0' src=\"thumbnail.php?image=$release[image1]&for=index\" /></a>";
        $content .= "<tr valign=\"top\"><td align=\"center\" width=\"160\">"; 
            $content .= $img1;
        if ($release["image2"] != ""){
           $img2 = "<a href=\"details.php?id=$release[id]&hit=1\"><img width=\"160\" border='0' src=\"thumbnail.php?image=$release[image2]&for=index\" /></a>";
            $content .= "<br /><br />$img2"; }
        $content .= "</td>"; 
        $content .= "<td><div align=\"left\">".$descr."</div>
            <table width=\"100%\"><tr><td>
            <hr />
            <b>Выложил: </b>$uprow<br>
            <b>Размер: </b>".mksize($release["size"])."<br>";
            $sql = sql_query("SELECT filename FROM torrents WHERE id = ".$release[id]);
            $sql = mysql_result($sql,0);
            if ($sql == 'nofile') {} else $content .= "
                        <b>Раздают: </b>".dltable($seeders, $release)." <b>Качают: </b>$release[leechers] <b>Скачиваний: </b>$release[times_completed]<br>";
            $content .= "<b>Добавлен: </b>$release[added]
            <hr />
                        <b>Коментарии: </b>$release[comments]</b><br></td><td>
            <div align=\"right\">".(!empty($catname) ? "<a href=\"browse.php?cat=$catid\">
            <img src=\"pic/cats/$catimage\" alt=\"$catname\" title=\"$catname\" border=\"0\" /></a>" : "")."<br/>
            [<a href=\"details.php?id=$release[id]&hit=1\" alt=\"$release[name]\" title=\"$release[name]\"><b>Просмотреть</b></a>]</div></td></table></td>";
        $content .= "</tr>"; 
        $content .= "</table>"; 
        $content .= "</td></tr>"; 
    }  
    $content .= "<tr><td>"; 
    $content .= $pagerbottom; 
    $content .= "</td></tr>"; 
} 
$content .= "</table>"; 
?>