<?

/*
Project: Kinokpk.com releaser
This file is part of Kinokpk.com releaser.
Kinokpk.com releaser is based on TBDev,
originally by RedBeard of TorrentBits, extensively modified by
Gartenzwerg and Yuna Scatari.
Kinokpk.com releaser is free software;
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
Kinokpk.com is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Kinokpk.com releaser; if not, write to the
Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
MA  02111-1307  USA
Do not remove above lines!
*/

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Повторяющиеся IP пользователей");
begin_frame("Повторяющиеся IP пользователей:", true);
begin_table();

if (get_user_class() >= UC_MODERATOR)
{
 $res = sql_query("SELECT count(*) AS dupl, ip FROM users WHERE enabled = 'yes' AND ip <> '' AND ip <> '127.0.0.0' GROUP BY ip ORDER BY dupl DESC, ip") or sqlerr(__FILE__, __LINE__);
  print("<tr align=center><td class=colhead width=90>Пользователь</td>
 <td class=colhead width=70>Email</td>
 <td class=colhead width=70>Регистрация</td>
 <td class=colhead width=75>Посл.&nbsp;Активность</td>
 <td class=colhead width=70>Скачал</td>
 <td class=colhead width=70>Раздал</td>
 <td class=colhead width=45>Рейтинг</td>
 <td class=colhead width=125>IP</td>
 <td class=colhead width=40>Пир</td></tr>\n");
 $uc = 0;
  while($ras = mysql_fetch_assoc($res)) {
        if ($ras["dupl"] <= 1)
          break;
        if ($ip <> $ras['ip']) {
          $ros = sql_query("SELECT id, username, class, email, added, last_access, downloaded, uploaded, ip, warned, donor, enabled, (SELECT COUNT(*) FROM peers WHERE peers.ip = users.ip AND users.id = peers.userid) AS peer_count FROM users WHERE ip='".$ras['ip']."' ORDER BY id") or sqlerr(__FILE__, __LINE__);
          $num2 = mysql_num_rows($ros);
          if ($num2 > 1) {
                $uc++;
            while($arr = mysql_fetch_assoc($ros)) {
                  if ($arr['added'] == '0000-00-00 00:00:00')
                        $arr['added'] = '-';
                  if ($arr['last_access'] == '0000-00-00 00:00:00')
                        $arr['last_access'] = '-';
                  if($arr["downloaded"] != 0)
                        $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
                  else
                        $ratio="---";

                  $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
                  $uploaded = mksize($arr["uploaded"]);
                  $downloaded = mksize($arr["downloaded"]);
                  $added = substr($arr['added'], 0, 10);
                  $last_access = substr($arr['last_access'], 0, 10);
                  if ($uc%2 == 0)
                        $utc = "";
                  else
                        $utc = " bgcolor=\"ECE9D8\"";

                        /*$peer_res = sql_query("SELECT count(*) FROM peers WHERE ip = " . sqlesc($ras['ip']) . " AND userid = " . $arr['id']);
                        $peer_row = mysql_fetch_row($peer_res);*/
                  print("<tr$utc><td align=left><b><a href='userdetails.php?id=" . $arr['id'] . "'>" . get_user_class_color($arr['class'], $arr['username'])."</b></a>" . get_user_icons($arr) . "</td>
                                  <td align=center>$arr[email]</td>
                                  <td align=center>$added</td>
                                  <td align=center>$last_access</td>
                                  <td align=center>$downloaded</td>
                                  <td align=center>$uploaded</td>
                                  <td align=center>$ratio</td>
                                  <td align=center><span style=\"font-weight: bold;\">$arr[ip]</span></td>\n<td align=center>" .
                                  ($arr['peer_count'] > 0 ? "<span style=\"color: red; font-weight: bold;\">Да</span>" : "<span style=\"color: green; font-weight: bold;\">Нет</span>") . "</td></tr>\n");
                  $ip = $arr["ip"];
                }
          }
        }
  }
} else {
 print("<br /><table width=60% border=1 cellspacing=0 cellpadding=9><tr><td align=center>");
 print("<h2>Извините, только для администрации</h2></table></td></tr>");
}
end_frame();
end_table();

stdfoot();
?>
