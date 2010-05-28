<?

require_once("include/bittorrent.php");

        //////////////////// Array ////////////////////


        $arrSystem['Windows 3.1'] = "Windows 3.1";
        $arrSystem['Win16'] = "Windows 3.1";
        $arrSystem['16bit'] = "Windows 3.1";
        $arrSystem['Win32'] = "Windows 95";
        $arrSystem['32bit'] = "Windows 95";
        $arrSystem['Win 32'] = "Windows 95";
        $arrSystem['Win95'] = "Windows 95";
        $arrSystem['Windows 95/NT'] = "Windows 95";
        $arrSystem['Win98'] = "Windows 98";
        $arrSystem['Windows 95'] = "Windows 95";
        $arrSystem['Windows 98'] = "Windows 98";
        $arrSystem['Windows NT 5.0'] = "Windows 2000";
        $arrSystem['Windows NT 5.1'] = "Windows XP";
        $arrSystem['Windows NT'] = "Windows NT";
        $arrSystem['WinNT'] = "Windows NT";
        $arrSystem['Windows ME'] = "Windows ME";
        $arrSystem['Windows CE'] = "Windows CE";
        $arrSystem['Windows'] = "Windows 95";
        $arrSystem['Mac_68000'] = "Macintosh";
        $arrSystem['Mac_PowerPC'] = "Macintosh";
        $arrSystem['Mac_68K'] = "Macintosh";
        $arrSystem['Mac_PPC'] = "Macintosh";
        $arrSystem['Macintosh'] = "Macintosh";
        $arrSystem['IRIX'] = "Unix";
        $arrSystem['SunOS'] = "Unix";
        $arrSystem['AIX'] = "Unix";
        $arrSystem['Linux'] = "Unix";
        $arrSystem['HP-UX'] = "Unix";
        $arrSystem['SCO_SV'] = "Unix";
        $arrSystem['FreeBSD'] = "Unix";
        $arrSystem['BSD/OS'] = "Unix";
        $arrSystem['OS/2'] = "OS/2";
        $arrSystem['WebTV/1.0'] = "WebTV/1.0";
        $arrSystem['WebTV/1.2'] = "WebTV/1.2";

        $arrBrowser['Lynx'] = "Lynx";
        $arrBrowser['libwww-perl'] = "Lynx";
        $arrBrowser['ia_archiver'] = "Crawler";
        $arrBrowser['ArchitextSpider'] = "Crawler";
        $arrBrowser['Lycos_Spider_(T-Rex)'] = "Crawler";
        $arrBrowser['Scooter'] = "Crawler";
        $arrBrowser['InfoSeek'] = "Crawler";
        $arrBrowser['AltaVista'] = "Crawler";
        $arrBrowser['Eule-Robot'] = "Crawler";
        $arrBrowser['SwissSearch'] = "Crawler";
        $arrBrowser['Checkbot'] = "Crawler";
        $arrBrowser['Crescent Internet ToolPak'] = "Crawler";
        $arrBrowser['Slurp'] = "Crawler";
        $arrBrowser['WiseWire-Widow'] = "Crawler";
        $arrBrowser['NetAttache'] = "Crawler";
        $arrBrowser['Web21 CustomCrawl'] = "Crawler";
        $arrBrowser['CheckUrl'] = "Crawler";
        $arrBrowser['LinkLint-checkonly'] = "Crawler";
        $arrBrowser['Namecrawler'] = "Crawler";
        $arrBrowser['ZyBorg'] = "Crawler";
        $arrBrowser['Googlebot'] = "Crawler";
        $arrBrowser['WebCrawler'] = "Crawler";
        $arrBrowser['WebCopier'] = "Crawler";
        $arrBrowser['JBH Agent 2.0'] = "Crawler";

        ///////////// Array end ///////////////////->


dbconn();
loggedinorreturn();

stdhead("Где пользователь");
if (get_user_class() < UC_MODERATOR)
{
stdmsg(Ошибка, "Доступ запрещен!", error);
stdfoot();
die();
}


  $search = unesc($_GET["search"]);
  $search = htmlspecialchars($search);

  $search_cat = unesc($_GET["cat"]);
  $search_cat = intval($search_cat);

   switch ($search_cat){

     default:
     $sql_r = "username";
     $check1 = "checked";
     break;

     case 1:
     $sql_r = "username";
     $check1 = "checked";
     break;

     case 2:
     $sql_r = "url";
     $check2 = "checked";
     break;

     case 3:
     $check3 = "checked";
     break;

   }

  $secs = 1 * 300;//Время выборки (5 последних минут)
  $dt = time() - $secs;

  if($search_cat != 3){
     if($search)
    $searchs = "  WHERE $sql_r LIKE '%" . sqlwildcardesc($search) . "%' ";
   } else {
    $searchs = "  WHERE uid LIKE '%" . sqlwildcardesc("-1") . "%' ";
   }

  $res = sql_query("SELECT COUNT(*) FROM sessions $searchs WHERE time > $dt");
  $row = mysql_fetch_array($res);
  $count = $row[0];
  $per_list = 50;

  list($pagertop, $pagerbottom, $limit) = pager($per_list, $count, "online.php?");
  $spy_res = sql_query("SELECT url, uid, username, class, ip, useragent FROM sessions $searchs WHERE time > $dt ORDER BY uid ASC $limit");

  echo "<table  class=\"embedded\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr><td class=\"colhead\" align=\"center\" colspan=\"3\">Где находятся пользователи (активность за последние 5 минут)</td></tr>";

  echo "<tr><td width=\"100%\" colspan=\"3\">";

  echo "<table class=\"embedded\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\">"
      ."<form method=\"get\" action=\"online.php\">"
      ."<tr>"
      ."<td class=\"embedded\">"
      ."<input type=\"text\" name=\"search\" size=\"40\"  value=\"".htmlspecialchars($search)."\" />"
      ."&nbsp;&nbsp;&nbsp;По имени:"
      ."<input name=\"cat\" type=\"radio\" value=\"1\" ".$check1.">"
      ."&nbsp;&nbsp;&nbsp;По адресу:"
      ."<input name=\"cat\" type=\"radio\" value=\"2\" ".$check2.">"
      ."&nbsp;&nbsp;&nbsp;Анонимные:"
      ."<input name=\"cat\" type=\"radio\" value=\"3\" ".$check3.">"
      ."</td></tr>"
      ."<tr>"
      ."<td class=\"embedded\"><p align=\"center\"><input class=\"btn\" type=\"submit\" value=\"Найти и отсортировать\" /><br></p></td>"
      ."</form>"
      ."</tr>"
      ."</table>";

  echo "</td></tr>";

  echo "<script language=\"javascript\" type=\"text/javascript\" src=\"js/show_hide.js\"></script>"
      ."<tr><td  class=\"colhead\" align=\"center\">Пользователь</td>"
      ."<td class=\"colhead\" align=\"center\">Звание</td>"
      ."<td class=\"colhead\" align=\"center\">Просматривает</td></tr>";

   if($per_list < $count){
  echo "<tr><td class=\"index\" colspan=\"3\">"
       .$pagertop."</td></tr>";}


   if (isset($searchs) && $count < 1) {
      print("<tr><td class=\"index\" colspan=\"3\">".$tracker_lang['nothing_found']."</td></tr>\n");
      }



$i=20;

while(list($spy_url, $user_id, $user_name, $user_class, $user_ip, $user_agent, $user_time) = mysql_fetch_array($spy_res)){

$i++;
$spy_urlse =  basename($spy_url);
$res_list =  explode(".php", $spy_urlse);


$brawser = getBrowser($arrBrowser,$user_agent);
$read = "";
if($CURUSER['id'] == $user_id)
{
$read = "<font color=\"red\">(Вы здесь)</font>";
}

$slep = "<span style=\"cursor: pointer;\" onclick=\"javascript: show_hide('s$i')\">"
       ."<img title=\"рacкрыть\" tooltip=\"рacкрыть\" src=\"pic/plus.gif\" id=\"pics$i\" border=\"0\"></span>"
       ."<span id=\"ss$i\" style=\"display: none;\"><br>"
       ."Браузер - ".$brawser['browser']." V.".$brawser['version']."<br>"
       ."Ос - ".getSystem($arrSystem,$user_agent)."<br>"
       ."IP - <a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">". $user_ip."</a><br>"
       ."</span>";

if($user_class != -1){
        echo "<tr><td><a target='_blank' href=\"userdetails.php?id=".$user_id."\">".get_user_class_color($user_class, $user_name)."</a> $slep</td>";
        echo "<td><b>".get_user_class_name($user_class)."</b></td><td>";
}else{
        echo "<tr><td><a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">Гость</a> $slep</td>";
        echo "<td>".$user_ip."</td><td>";
        }
        echo "<a target='_blank' href=\"".$spy_url."\">".Spy_lang($res_list[0].".php")."</a> ".$read;
        echo "</td></tr>";



}
         if($per_list < $count){
        echo "<tr><td class=\"index\" colspan=\"3\">"
             .$pagerbottom."</td></tr>"; }
        echo "</table>";

 stdfoot();





  /////////////////////////////////////////////
  /////////////////////////////////////////////
  /////////// -- Functions -- /////////////////
  /////////////////////////////////////////////
  //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//

        function getSystem($arrSystem,$userAgent)
        {
                $system = 'Other';
                foreach($arrSystem as $key => $value)
                {
                        if (strpos($userAgent, $key) !== false)
                        {
                                $system = $value;
                                break;
                        }
                }
                return $system;
        }


        function getBrowser($arrBrowser,$userAgent)
        {
                $version = "";
                $browser = 'Other';
                if (($pos = strpos($userAgent, 'Opera')) !== false)
                {
                        $browser = 'Opera';
                        $pos += 6;
                        if ((($posEnd = strpos($userAgent, ';', $pos)) !== false) || (($posEnd = strpos($userAgent, ' ', $pos)) !== false))
                                $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                }
                elseif (($pos = strpos($userAgent, 'MSIE')) !== false)
                {
                        $browser = 'Internet Explorer';
                        $posEnd = strpos($userAgent, ';', $pos);
                        if ($posEnd !== false)
                        {
                                $pos += 4;
                                $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                        }
                }
                elseif (((strpos($userAgent, 'Gecko')) !== false) && ((strpos($userAgent, 'Netscape')) === false))
                {
                        $browser = 'Mozila';
                        if (($pos = strpos($userAgent, 'rv:')) !== false)
                        {
                                $posEnd = strpos($userAgent, ')', $pos);
                                if ($posEnd !== false)
                                {
                                        $pos += 3;
                                        $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                                }
                        }
                }
                elseif ((strpos($userAgent, ' I;') !== false) || (strpos($userAgent, ' U;') !== false) || (strpos($userAgent, ' U ;') !== false) || (strpos($userAgent, ' I)') !== false) || (strpos($userAgent, ' U)') !== false))
                {
                        $browser = 'Netscape Navigator';
                        if (($pos = strpos($userAgent, 'Netscape6')) !== false)
                        {
                                $pos += 10;
                                $version = trim(substr($userAgent, $pos, strlen($userAgent) - $pos));
                        }
                        else
                        {
                                if (($pos = strpos($userAgent, 'Mozilla/')) !== false)
                                {
                                        if (($posEnd = strpos($userAgent, ' ', $pos)) !== false)
                                        {
                                                $pos += 8;
                                                $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                                        }
                                }
                        }
                }
                else
                {
                        foreach($arrBrowser as $key => $value)
                        {
                                if (strpos($userAgent, $key) !== false)
                                {
                                        $browser = $value;
                                        break;
                                }
                        }
                }
                $userAgentArr['browser'] = $browser;
                $userAgentArr['version'] = $version;
                return $userAgentArr;
        }



function Spy_lang($op){
 switch ($op) {

        default:
        return "страница не опознана";
        break;
        case 'adduser.php':
        $sd = "добавление юзера";
        break;
         case 'admincp.php':
        $sd = "панель админа";
        break;
         case 'anatomy.php':
        $sd = "анатомия сессии";
        break;
         case 'bans.php':
        $sd = "баны";
        break;
         case 'bookmark.php':
        $sd = "торрент в закладки";
        break;
         case 'bookmarks.php':
        $sd = "закладки";
        break;
        case 'browse.php':
        $sd = "раздачи";
        break;
        case 'category.php':
        $sd = "категории";
        break;
        case 'comment.php':
        $sd = "комментарии";
        break;
        case 'contact.php':
        $sd = "связь";
        break;
        case 'delacctadmin.php':
        $sd = "удаление юзера";
        break;
        case 'delacct.php':
        $sd = "удаление юзера";
        break;
        case 'details.php':
        $sd = "детали торрента";
        break;
        case 'docleanup.php':
        $sd = "очистка трекера";
        break;
        case 'download.php':
        $sd = "скачивает торрент";
        break;
        case 'edit.php':
        $sd = "редактирование торрента";
        break;
        case 'faq.php':
        $sd = "ЧаВо";
        break;
        case 'findnotconnectable.php':
        $sd = "юзеры за NAT";
        break;
        case 'formats.php':
        $sd = "форматы файлов";
        break;
        case 'forums.php':
        $sd = "форум";
        break;
        case 'friends.php':
        $sd = "друзья";
        break;
        case 'getrss.php':
        $sd = "RSS Feed";
        break;
        case 'rss.php':
        $sd = "RSS Feed";
        break;
        case 'index.php':
        $sd = "главная сайта";
        break;
        case 'indexadd.php':
        $sd = "новый релиз";
        break;
        case 'indexdelete.php':
        $sd = "удаление релиза";
        break;
        case 'indexedit.php':
        $sd = "редактирование релиза";
        break;
        case 'invite.php':
        $sd = "приглашения";
        break;
        case 'inviteadd.php':
        $sd = "приглашения";
        break;
        case 'ipcheck.php':
        $sd = "двойники по IP";
        break;
        case 'links.php':
        $sd = "ссылки";
        break;
        case 'log.php':
        $sd = "логи сайта";
        break;
        case 'login.php':
        $sd = "авторизация";
        break;
        case 'logout.php':
        $sd = "выход с сайта";
        break;
        case 'makepoll.php':
        $sd = "создание опроса";
        break;
        case 'message.php':
        $sd = "личный ящик";
        break;
        case 'my.php':
        $sd = "личная панель";
        break;
        case 'mybonus.php':
        $sd = "мои бонусы";
        break;
        case 'myinvite.php':
        $sd = "мои инвайты";
        break;
        case 'mysimpaty.php':
        $sd = "мои респекты";
        break;
        case 'mytorrents.php':
        $sd = "мои раздачи";
        break;
        case 'news.php':
        $sd = "добавление новостей";
        break;
        case 'nowarn.php':
        $sd = "снятие предупреждений";
        break;
        case 'offers.php':
        $sd = "предложения";
        break;
        case 'online.php':
        $sd = "кто онлайн";
        break;
        case 'polloverview.php':
        $sd = "обзор опросов";
        break;
        case 'polls.php':
        $sd = "опросы";
        break;
        case 'recover.php':
        $sd = "восстановление пароля";
        break;
        case 'requests.php':
        $sd = "запросы";
        break;
        case 'restoreclass.php':
        $sd = "восстановление класса";
        break;
        case 'rules.php':
        $sd = "правила сайта";
        break;
        case 'setclass.php':
        $sd = "смена класса";
        break;
        case 'simpaty.php':
        $sd = "сказать спасибо";
        break;
        case 'sitestat.php':
        $sd = "статистика сайта";
        break;
        case 'stats.php':
        $sd = "статистика сайта";
        break;
        case 'smilies.php':
        $sd = "смайлы";
        break;
        case 'staff.php':
        $sd = "администрация";
        break;
        case 'staffmess.php':
        $sd = "массовое лс";
        break;
        case 'subnet.php':
        $sd = "соседи";
        break;
        case 'tags.php':
        $sd = "bb-теги";
        break;
        case 'testip.php':
        $sd = "проверка IP";
        break;
        case 'testport.php':
        $sd = "тест портов";
        break;
        case 'thanks.php':
        $sd = "благодарит";
        break;
        case 'topten.php':
        $sd = "топ 10";
        break;
        case 'unco.php':
        $sd = "неподтв. юзеры";
        break;
        case 'upload.php':
        $sd = "загрузка торрента";
        break;
        case 'uploaders.php':
        $sd = "список аплоадеров";
        break;
        case 'userdetails.php':
        $sd = "профайл юзера";
        break;
        case 'userhistory.php':
        $sd = "история постов";
        break;
        case 'users.php':
        $sd = "список пользователей";
        break;
        case 'usersearch.php':
        $sd = "поиск пользователей";
        break;
        case 'videoformats.php':
        $sd = "форматы видео";
        break;
        case 'viewoffers.php':
        $sd = "предложения";
        break;
        case 'viewrequests.php':
        $sd = "запросы";
        break;
        case 'votesview.php':
        $sd = "голосования";
        break;
        case 'warned.php':
        $sd = "предупрежденные юзеры";
        break;
        case "spyline.php":
        $sd = "Шпион";
        break;
        break;
        case "signup.php":
        $sd = "страница регистрации";
        break;
        case "allnews.php":
        $sd = "все новости сайта";
        break;
        //имя не найдено?
        default:
           $sd = "корень сайта или n/a";
 }

  return $sd;
}

?>