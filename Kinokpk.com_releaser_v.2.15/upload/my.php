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

require_once("include/bittorrent.php");

dbconn(false);

loggedinorreturn();

stdhead($tracker_lang['my_my']);

if ($_GET["edited"]) {
	print("<h1>".$tracker_lang['my_updated']."</h1>\n");
	if ($_GET["mailsent"])
		print("<h2>".$tracker_lang['my_mail_sent']."</h2>\n");
}
elseif ($_GET["emailch"])
	print("<h1>".$tracker_lang['my_mail_updated']."</h1>\n");
/*else
	print("<h1>Добро пожаловать, <a href=userdetails.php?id=$CURUSER[id]>$CURUSER[username]</a>!</h1>\n");*/

?>
<table border="1" cellspacing="0" cellpadding="10" align="center">
<tr>
<td align="center" width="33%"><a href="logout.php"><b><?=$tracker_lang['logout'];?></b></a></td>
<td align="center" width="33%"><a href="mytorrents.php"><b><?=$tracker_lang['my_torrents'];?></b></a></td>
<td align="center" width="33%"><a href="friends.php"><b>Мои списки пользователей</b></a></td>
</tr>
<tr>
<td colspan="3">
<form method="post" action="takeprofedit.php">
<table border="1" cellspacing=0 cellpadding="5">
<?

/***********************

$res = sql_query("SELECT COUNT(*) FROM ratings WHERE user=" . $CURUSER["id"]);
$row = mysql_fetch_array($res);
tr("Ratings submitted", $row[0]);

$res = sql_query("SELECT COUNT(*) FROM comments WHERE user=" . $CURUSER["id"]);
$row = mysql_fetch_array($res);
tr("Written comments", $row[0]);

****************/

$ss_r = sql_query("SELECT * FROM stylesheets ORDER by id ASC") or die;
$ss_sa = array();
while ($ss_a = mysql_fetch_array($ss_r)) {
  $ss_id = $ss_a["id"];
  $ss_name = $ss_a["name"];
  $ss_sa[$ss_name] = $ss_id;
}
reset($ss_sa);
while (list($ss_name, $ss_id) = each($ss_sa)) {
  if ($ss_id == $CURUSER["stylesheet"]) $ss = " selected"; else $ss = "";
  $stylesheets .= "<option value=\"$ss_id\"$ss>$ss_name</option>\n";
}

$countries = "<option value=0>---- ".$tracker_lang['my_unset']." ----</option>\n";
$ct_r = sql_query("SELECT id,name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
  $countries .= "<option value=$ct_a[id]" . ($CURUSER["country"] == $ct_a['id'] ? " selected" : "") . ">$ct_a[name]</option>\n";

$dir = opendir('languages');
        $lang = array();
        while ( $file = readdir($dir) ) {
                if (preg_match('#^lang_#i', $file) && !is_file($dir . '/' . $file) && !is_link($dir . '/' . $file)) {
                        $filename = trim(str_replace("lang_","", $file));
                        $displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
                        $displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
                        $lang[$displayname] = $filename;
                }
        }
        closedir($dir);
        @asort($lang);
        @reset($lang);

        $lang_select = '<select name="language">';
        while ( list($displayname, $filename) = @each($lang) ) {
                $selected = ((strtolower($CURUSER["language"]) == strtolower($filename) ) ? ' selected="selected"' : '');
                $lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
        }
        $lang_select .= '</select>';

function format_tz($a)
{
	$h = floor($a);
	$m = ($a - floor($a)) * 60;
	return ($a >= 0?"+":"-") . (strlen(abs($h)) > 1?"":"0") . abs($h) .
		":" . ($m==0?"00":$m);
}

tr($tracker_lang['my_allow_pm_from'],
"<input type=radio name=acceptpms" . ($CURUSER["acceptpms"] == "yes" ? " checked" : "") . " value=\"yes\">Все (исключая блокированных)
<br /><input type=radio name=acceptpms" .  ($CURUSER["acceptpms"] == "friends" ? " checked" : "") . " value=\"friends\">Только друзей
<br /><input type=radio name=acceptpms" .  ($CURUSER["acceptpms"] == "no" ? " checked" : "") . " value=\"no\">Только администрации"
,1);

tr($tracker_lang['my_parked'],
"<input type=\"radio\" name=\"parked\"" . ($CURUSER["parked"] == "yes" ? " checked" : "") . " value=\"yes\">".$tracker_lang['yes']."
<input type=\"radio\" name=\"parked\"" . ($CURUSER["parked"] == "no" ? " checked" : "") . " value=\"no\">".$tracker_lang['no']."
<br /><font class=\"small_text\">".$tracker_lang['my_you_can_park'].".</font>"
,1);

tr($tracker_lang['my_delete_after_reply'], "<input type=checkbox name=deletepms" . ($CURUSER["deletepms"] == "yes" ? " checked" : "") . ">",1);
tr($tracker_lang['my_sentbox'], "<input type=checkbox name=savepms" . ($CURUSER["savepms"] == "yes" ? " checked" : "") . ">",1);

$r = sql_query("SELECT id,name FROM categories ORDER BY sort") or sqlerr(__FILE__, __LINE__);
//$categories = "Default browsing categories:<br />\n";
if (mysql_num_rows($r) > 0)
{
	$categories .= "<table><tr>\n";
	$i = 0;
	while ($a = mysql_fetch_assoc($r))
	{
	  $categories .=  ($i && $i % 2 == 0) ? "</tr><tr>" : "";
	  $categories .= "<td class=bottom style='padding-right: 5px'><input name=cat$a[id] type=\"checkbox\" " . (strpos($CURUSER['notifs'], "[cat$a[id]]") !== false ? " checked" : "") . " value='yes'>&nbsp;" . htmlspecialchars($a["name"]) . "</td>\n";
	  ++$i;
	}
	$categories .= "</tr></table>\n";
}

tr($tracker_lang['my_email_notify'], "<input type=checkbox name=pmnotif" . (strpos($CURUSER['notifs'], "[pm]") !== false ? " checked" : "") . " value=yes> Уведомить меня при получении ЛС<br />\n" .
	 "<input type=checkbox name=emailnotif" . (strpos($CURUSER['notifs'], "[email]") !== false ? " checked" : "") . " value=yes> Уведомить меня при размещении торрента в одной <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; из следующих предпочитаемых категорий.\n"
   , 1);
tr($tracker_lang['my_default_browse'],$categories,1);
tr($tracker_lang['my_style'], "<select name=stylesheet>\n$stylesheets\n</select>",1);

//мод предупреждений
print("<tr><td class=\"rowhead\">Уровень<br>предупреждений</td><td align=\"left\">");
for($i = 0; $i < $user["num_warned"]; $i++)
{
$img .= "<a href=\"mywarned.php\"  target=\"_blank\"><img src=\"pic/star_warned.gif\" alt=\"Уровень предупреждений\" title=\"Уровень предупреждений\"></a>";
}
if (!$img)
$img = "Нет предупреждений";
print("$img <a href=\"mywarned.php\">Купить помилование за аплоад</a></td></tr>\n");

tr($tracker_lang['my_country'], "<select name=country>\n$countries\n</select>",1);
tr($tracker_lang['my_language'], $lang_select ,1);
tr($tracker_lang['my_avatar_url'],$CURUSER['avatar']?"<img src=\"".$CURUSER['avatar']."\">":"Нет аватара",1);
tr("Загрузить аватар", "<a href=avatarup.php>Кликните сюда для загрузки</a><br/>\n".sprintf($tracker_lang['max_avatar_size'], $CACHEARRAY['avatar_max_width'], $CACHEARRAY['avatar_max_height']), 1);


tr($tracker_lang['my_gender'],
"<input type=radio name=gender" . ($CURUSER["gender"] == "1" ? " checked" : "") . " value=1>".$tracker_lang['my_gender_male']."
<input type=radio name=gender" .  ($CURUSER["gender"] == "2" ? " checked" : "") . " value=2>".$tracker_lang['my_gender_female']
,1);

///////////////// BIRTHDAY MOD /////////////////////
$birthday = $CURUSER["birthday"];
$birthday = date("Y-m-d", strtotime($birthday));
list($year1, $month1, $day1) = split('-', $birthday);
if ($CURUSER[birthday] == "0000-00-00") {
        $year .= "<select name=year><option value=\"0000\">".$tracker_lang['my_year']."</option>\n";
        $i = "1920";
        while($i <= (date('Y',time())-13)) {
                $year .= "<option value=" .$i. ">".$i."</option>\n";
                $i++;
        }
        $year .= "</select>\n";
        $birthmonths = array(
        "01" => $tracker_lang['my_months_january'],
        "02" => $tracker_lang['my_months_february'],
        "03" => $tracker_lang['my_months_march'],
        "04" => $tracker_lang['my_months_april'],
        "05" => $tracker_lang['my_months_may'],
        "06" => $tracker_lang['my_months_june'],
        "07" => $tracker_lang['my_months_jule'],
        "08" => $tracker_lang['my_months_august'],
        "09" => $tracker_lang['my_months_september'],
        "10" => $tracker_lang['my_months_october'],
        "11" => $tracker_lang['my_months_november'],
        "12" => $tracker_lang['my_months_december'],
        );
        $month = "<select name=\"month\"><option value=\"00\">".$tracker_lang['my_month']."</option>\n";
        foreach ($birthmonths as $month_no => $show_month)
        {
                $month .= "<option value=$month_no>$show_month</option>\n";
        }
        $month .= "</select>\n";
        $day .= "<select name=day><option value=\"00\">".$tracker_lang['my_day']."</option>\n";
        $i = 1;
        while ($i <= 31) {
                if($i < 10) {
                        $day .= "<option value=0".$i. ">0".$i."</option>\n";
                } else {
                        $day .= "<option value=".$i.">".$i."</option>\n";
                }
                $i++;
        }
        $day .="</select>\n";
        tr($tracker_lang['my_birthdate'], $year . $month . $day ,1);
}
if($CURUSER[birthday] != "0000-00-00") {
        tr($tracker_lang['my_birthdate'],"<b><input type=hidden name=year value=$year1>$year1<input type=hidden name=month value=$month1>.$month1<input type=hidden name=day value=$day1>.$day1</b>",1);
}
///////////////// BIRTHDAY MOD /////////////////////

print("<tr><td class=\"tablecat\" colspan=\"2\" align=left><b>".$tracker_lang['my_contact']."</b></td></tr>\n");

tr(" ", "    <table cellspacing=\"3\" cellpadding=\"0\" width=\"100%\" border=\"0\">
            <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\" colspan=2>
        ".$tracker_lang['my_contact_descr']."</td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_icq']."<br />
        <img alt src=pic/contact/icq.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"icq\" value=\"" . $CURUSER["icq"] . "\" ></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_aim']."<br />
        <img alt src=pic/contact/aim.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"aim\" value=\"" . $CURUSER["aim"] . "\" ></td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_msn']."<br />
        <img alt src=pic/contact/msn.gif width=\"17\" height=\"17\">
        <input maxLength=\"50\" size=\"25\" name=\"msn\" value=\"" . $CURUSER["msn"] . "\" ></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_yahoo']."<br />
        <img alt src=pic/contact/yahoo.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"yahoo\" value=\"" . $CURUSER["yahoo"] . "\" ></td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_skype']."<br />
        <img alt src=pic/contact/skype.gif width=\"17\" height=\"17\">
        <input maxLength=\"32\" size=\"25\" name=\"skype\" value=\"" . $CURUSER["skype"] . "\" ></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_mirc']."<br />
        <img alt src=pic/contact/mirc.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"mirc\" value=\"" . $CURUSER["mirc"] . "\" ></td>
      </tr>
    </table>",1);
tr($tracker_lang['my_website'], "<input type=\"text\" name=\"website\" size=50 value=\"" . htmlspecialchars($CURUSER["website"]) . "\" /> ", 1);
tr($tracker_lang['my_torrents_per_page'], "<input type=text size=10 name=torrentsperpage value=$CURUSER[torrentsperpage]> (0 = установки по умолчанию)",1);
tr($tracker_lang['my_show_avatars'], "<input type=checkbox name=avatars" . ($CURUSER["avatars"] == "yes" ? " checked" : "") . "> (Пользователи с маленькими каналами могут отключить эту опцию)",1);
tr("Отображать экстра-эффекты<br/>Например, снег", "<input type=checkbox name=extra_ef" . ($CURUSER["extra_ef"] == "yes" ? " checked" : "") . "> (Пользователи со слабыми процессорами или видеокартами могут отключить эту опцию)",1);
tr($tracker_lang['my_info'], "<textarea name=info cols=50 rows=4>" . $CURUSER["info"] . "</textarea><br />Показывается на вашей публичной странице. Может содержать <a href=tags.php target=_new>BB коды</a>.", 1);
tr($tracker_lang['my_mail'], "<input type=\"text\" name=\"email\" size=50 value=\"" . htmlspecialchars($CURUSER["email"]) . "\" />", 1);
print("<tr><td colspan=\"2\" align=left><b>Примечание:</b> Если вы смените ваш Email адрес, то вам придет запрос о подтверждении на ваш новый Email-адрес. Если вы не подтвердите письмо, то Email адрес не будет изменен.</td></tr>\n");
tr("Сменить пасскей","<input type=checkbox name=resetpasskey value=1 /> (Вы должны перекачать все активные торренты после смены пасскея)", 1);

if (strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
	sql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
}
tr("Мой пасскей","<b>$CURUSER[passkey]</b>", 1);
tr("Привязать IP к пасскею", "<input type=checkbox name=passkey_ip" . ($CURUSER["passkey_ip"] != "" ? " checked" : "") . "> Включив эту опцию вы можете защитить себя от неавторизованной закакачки по вашему пасскею привязав его к IP. Если ваш IP динамический - не включайте эту опцию.<br />На данный момент ваш IP: <b>".getip()."</b>", 1);
tr("Старый пароль", "<input type=\"password\" name=\"oldpassword\" size=\"50\" />", 1);
tr("Сменить пароль", "<input type=\"password\" name=\"chpassword\" size=\"50\" />", 1);
tr("Пароль еще раз", "<input type=\"password\" name=\"passagain\" size=\"50\" />", 1);

function priv($name, $descr) {
	global $CURUSER;
	if ($CURUSER["privacy"] == $name)
		return "<input type=\"radio\" name=\"privacy\" value=\"$name\" checked=\"checked\" /> $descr";
	return "<input type=\"radio\" name=\"privacy\" value=\"$name\" /> $descr";
}

/* tr("Privacy level",  priv("normal", "Normal") . " " . priv("low", "Low (email address will be shown)") . " " . priv("strong", "Strong (no info will be made available)"), 1); */

?>
<tr><td colspan="2" align="center"><input type="submit" value="Обновить профиль" style='height: 25px'> <input type="reset" value="Сбросить изменения" style='height: 25px'></td></tr>
</table>
</form>
</td>
</tr>
</table>
<?
print("<p><a href=users.php><b>Найти пользователя/Список пользователей</b></a></p>");
stdfoot();

?>