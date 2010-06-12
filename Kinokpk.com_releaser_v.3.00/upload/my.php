<?php
/**
 * User settings
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
getlang('my');
loggedinorreturn();

stdhead($tracker_lang['my_my']);


if ($_GET["emailch"])
print("<h1>".$tracker_lang['my_mail_updated']."</h1>\n");
/*else
 print("<h1>Добро пожаловать, <a href=userdetails.php?id=$CURUSER[id]>$CURUSER[username]</a>!</h1>\n");*/
/*
 $country = $_POST["country"];
 print($country.'a1');
 $stylesheet = $_POST["stylesheet"];
 print($stylesheet.'b2');
 $lang = $_POST["language"];
 print($lang.'c3');
 $timezone = (int)$_POST['timezone'];
 print($timezone.'d4');
 */
?>


<div id="my_table">
<h2><?=$tracker_lang['account_settings']?></h2>
<link rel="stylesheet"
	href="css/link/jquery.linkselect.style.select.css" type="text/css" />
<script language="javascript" type="text/javascript"
	src="js/jquery.bgiframe.js"></script> <script language="javascript"
	type="text/javascript" src="js/jquery.linkselect.js"></script> <script
	type="text/javascript">
	<!--//
	// on DOM ready
	
	$(document).ready(function (){
		$("#country").linkselect({
			change: function (li, value, text){
				$('<div>' + value + ' | ' + text + '</div>').appendTo("#change_log");
			}
		});
	
		$("select.linkselect").linkselect({
			change: function(li, value, text){
				if( window.console ) console.log(value);
	  	}
		});

				
	});

	function switchCSS(style){
		var bFound = false, bMatch, style = style.toLowerCase();
		$("link[title]").each(function (i){
			this.disabled = true;
			
			if( this.title.toLowerCase() == style ){
				this.disabled = false;
				bFound = true;
			}
		});
		
		if( !bFound ) $("link[title='default']")[0].disabled = false;
	}
	//-->
	</script>


<div id="my_table_1"><?php


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

$lang_select = '<select class="linkselect" id="language" name="language">';
while ( list($displayname, $filename) = @each($lang) ) {
	$selected = ((strtolower($CURUSER["language"]) == strtolower($filename) ) ? ' selected="selected"' : '');
	$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
}
$lang_select .= '</select>';
/*основное*/
if (get_user_class() >= UC_ADMINISTRATOR) {
	print('<div class="clickable">Добавить себя в поддержку (для администраторов и выше)</div>');
	print('<div width="100%" id="category" style="margin:10px 0px;">');
	//	$spbegin = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"></div><div class=\"sp-body\">";
	//	$spend = "</div></div>";
	$supportfor = makesafe ( $CURUSER ["supportfor"] );

	print ('<div style="border: 1px dashed rgb(255, 0, 0); padding: 5px;">
				<form action="modtask.php" method="POST">');
	print ( "<div id='my_title'>Поддержка</div>
						<div colspan=2 class='up_avatar' style='margin-bottom: 15px;'>
							<input type=radio name=support value=\"1\"" . ($CURUSER ["supportfor"] ? " checked" : "") . ">{$tracker_lang['yes']}
							<input type=radio name=support value=\"0\"" . (!$CURUSER["supportfor"] ? " checked" : "") . ">{$tracker_lang['no']}
						</div>
			\n" );
	print ( "
				<div id='my_title'>Поддержка для:</div>
				<div colspan=2 class='up_avatar' style='margin-bottom: 15px;'><input type=text size=50 name=supportfor value=\"$supportfor\"></div>
				<div colspan=\"2\" align=\"right\"><input type=\"hidden\" name=\"action\" value=\"ownsupport\"><input type=\"submit\" value=\"Добавить себя в поддержку (удалить)\"></div>
			
		</div>
			</form>
		</div>\n" );
	//	print('</div>');
}
print ('<form id="myform" method="post" action="takeprofedit.php">');
div($tracker_lang['my_avatar_url'],$CURUSER['avatar']?"<img src=\"".$CURUSER['avatar']."\">":"Нет аватара",1,'my_title','my_avatar');
if(!$CURUSER['avatar']){
	div("Загрузить аватар", "<a class=\"index\" href=\"/avatarup.php\" onclick=\"javascript:$.facebox({ajax:'avatarup.php'}); return false;\">Кликните сюда для загрузки</a><br />\n".sprintf($tracker_lang['max_avatar_size'], $CACHEARRAY['avatar_max_width'], $CACHEARRAY['avatar_max_height']), 1,'my_title','up_avatar');
}else{
	div("Поменять аватар", "<a class=\"index\" href=\"/avatarup.php\" onclick=\"javascript:$.facebox({ajax:'avatarup.php'}); return false;\">Кликните сюда для загрузки</a><br />\n".sprintf($tracker_lang['max_avatar_size'], $CACHEARRAY['avatar_max_width'], $CACHEARRAY['avatar_max_height']), 1,'my_title','up_avatar');
}
div($tracker_lang['my_gender'],
"<p><input type=radio class=\"styled\"  name=gender" . ($CURUSER["gender"] == "1" ? " checked" : "") . " value=1><span>".$tracker_lang['my_gender_male']."</span></p><p>
<input type=radio class=\"styled\"  name=gender" . ($CURUSER["gender"] == "2" ? " checked" : "") . " value=2><span>".$tracker_lang['my_gender_female']."</span></p><p>
<input type=radio class=\"styled\" name=gender" . ($CURUSER["gender"] == "0" ? " checked" : "") . " value=0><span>БОТ</span></p>"
,1,'my_title','my_gender');
print('<div class="clear"></div>');

div($tracker_lang['my_allow_pm_from'],
"<p><input type=radio class=\"styled\"  name=acceptpms" . ($CURUSER["acceptpms"] == "yes" ? " checked" : "") . " value=\"yes\"><span>Все (исключая блокированных)</span></p><p>
<input type=radio class=\"styled\" name=acceptpms" .  ($CURUSER["acceptpms"] == "friends" ? " checked" : "") . " value=\"friends\"><span>Только друзей</span></p><p>
<input type=radio class=\"styled\" name=acceptpms" .  ($CURUSER["acceptpms"] == "no" ? " checked" : "") . " value=\"no\"><span>Только администрации</span></p>"
,1 ,'my_title', 'pm_from');
print('<div class="clear"></div>');

div($tracker_lang['my_country'], "<select id='country' name=country>\n$countries\n</select>",1,'my_title','my_count');
div($tracker_lang['my_timezone'], list_timezones('timezone',$CURUSER['timezone']),1,'my_title','my_count');
div($tracker_lang['my_language'], $lang_select ,1,'my_title','my_count');
div($tracker_lang['my_website'], "<input type=\"text\"  name=\"website\" size=50 value=\"" . htmlspecialchars($CURUSER["website"]) . "\" /> ", 1,'my_title','my_web');
div($tracker_lang['my_info'], "Показывается на вашей публичной странице<br /><textarea name=info cols=57 rows=4>" . $CURUSER["info"] . "</textarea><br />", 1,'my_title','my_area');
print('<div class="clear"></div>');


div($tracker_lang['my_delete_after_reply'], "<input type=checkbox class=\"styled\" name=deletepms" . ($CURUSER["deletepms"] ? " checked" : "") . ">",1 ,'my_title', 'my_delete');
print('<div class="clear"></div>');
div($tracker_lang['my_sentbox'], "<input type=checkbox class=\"styled\" name=savepms" . ($CURUSER["savepms"] ? " checked" : "") . ">",1,'my_title','my_sentbox');
print('<div class="clear"></div>');
//                   $spbegin = "<div style=\"position: static;\" class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>Открыть список</i></td></tr></table></div><div class=\"sp-body\">";
//              $spend = "</div></div>";
div('Мои уведомления','<a href="mynotifs.php?settings">Перейти на страницу конфигурации моих уведомлений</a>',1,'my_title','my_sent');
div($tracker_lang['my_style'], "<select class=\"linkselect\" id=\"stylesheet\" name=stylesheet>\n$stylesheets\n</select>",1,'my_title','my_style');
div($tracker_lang['view_xxx'],"<p><input type=radio class=\"styled\" name=pron" . (!$CURUSER["pron"] ? " checked" : "") . " value=0><span>".$tracker_lang['no']."</span></p><p>
<input type=radio class=\"styled\" name=pron" . ($CURUSER["pron"]? " checked" : "") . " value=1><span>".$tracker_lang['yes']."</span></p>",1,'my_title','my_xxx');
print('<div class="clear"></div>');

div($tracker_lang['my_comments'],is_i_notified($CURUSER['id'],'usercomments'),1,'my_title','my_comment');

//мод предупреждений
print("<div id=\"nam_warner\"><div id=\"my_title\">Уровень предупреждений</div><div align=\"left\" style=\"padding-top: 22px;\">");
for($i = 0; $i < $CURUSER["num_warned"]; $i++)
{
	$img .= "<a href=\"mywarned.php\"  target=\"_blank\"><img src=\"pic/star_warned.gif\" alt=\"Уровень предупреждений\" title=\"Уровень предупреждений\"></a>";
}
if (!$img)
$img = "Нет предупреждений!&nbsp;";




///////////////// BIRTHDAY MOD /////////////////////
$birthday = $CURUSER["birthday"];
$birthday = date("Y-m-d", strtotime($birthday));
list($year1, $month1, $day1) = explode('-', $birthday);
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
	div($tracker_lang['my_birthdate'], $year . $month . $day ,1,'my_title','my_birthday');
}
if($CURUSER[birthday] != "0000-00-00") {
	div($tracker_lang['my_birthdate'],"<b><input type=hidden name=year value=$year1>$year1<input type=hidden name=month value=$month1>.$month1<input type=hidden name=day value=$day1>.$day1</b>",1,'my_title','my_birthday');
}
print('<div class="clear"></div>');
///////////////// BIRTHDAY MOD /////////////////////

//print("<div id=\"my_title\">".$tracker_lang['my_contact']."</div>\n");

div($tracker_lang['my_contact'],"
<div class='message_contakt'>
    <div class='message' >
        ".$tracker_lang['my_contact_descr']."
	</div>
    <div class='message' >
        ".$tracker_lang['my_contact_icq']."<br />
        <img alt src=pic/contact/icq.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"icq\" value=\"" . $CURUSER["icq"] . "\" >
	</div>
    <div class='message' >  
        ".$tracker_lang['my_contact_aim']."<br />
        <img alt='' src=pic/contact/aim.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"aim\" value=\"" . $CURUSER["aim"] . "\" >
   </div>
   <div class='message' >
        ".$tracker_lang['my_contact_msn']."<br />
        <img alt='' src=pic/contact/msn.gif width=\"17\" height=\"17\">
        <input maxLength=\"50\" size=\"25\" name=\"msn\" value=\"" . $CURUSER["msn"] . "\" >
   </div>
   <div class='message' >
        ".$tracker_lang['my_contact_yahoo']."<br />
        <img alt='' src=pic/contact/yahoo.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"yahoo\" value=\"" . $CURUSER["yahoo"] . "\" >
	</div>
    <div class='message' >
         ".$tracker_lang['my_contact_skype']."<br />
        <img alt='' src=pic/contact/skype.gif width=\"17\" height=\"17\">
        <input maxLength=\"32\" size=\"25\" name=\"skype\" value=\"" . $CURUSER["skype"] . "\" >
	</div>
    <div class='message' >
        ".$tracker_lang['my_contact_mirc']."<br />
        <img alt='' src=pic/contact/mirc.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"mirc\" value=\"" . $CURUSER["mirc"] . "\" >
	</div>
  </div>
    ",1,'my_title','my_contact');

print('<div class="clear"></div>');


div($tracker_lang['my_show_avatars'], "<input type=checkbox class=\"styled\" name=avatars" . ($CURUSER["avatars"] ? " checked" : "") . "> (Пользователи с маленькими каналами могут отключить эту опцию)",1,'my_title','my_show_ava');
div("Отображать экстра-эффекты<br />Например, снег", "<input type=checkbox class=\"styled\" name=extra_ef" . ($CURUSER["extra_ef"] ? " checked" : "") . "> (Пользователи со слабыми процессорами или видеокартами могут отключить эту опцию)",1,'my_title','my_check');

div($tracker_lang['my_mail'], "<input type=\"text\" name=\"email\" size=50 value=\"" . htmlspecialchars($CURUSER["email"]) . "\" />", 1,'my_title','my_name');
print("<div><div align=\"left\" colspan=\"2\" style=\"padding-left: 250px;\"><b>Примечание:</b> Если вы смените ваш Email адрес, то вам придет запрос о подтверждении на ваш новый Email-адрес. Если вы не подтвердите письмо, то Email адрес не будет изменен.</div></div>\n");
div("Сменить пасскей","<input type=checkbox class=\"styled\" name=resetpasskey value=1 /> (Вы должны перекачать все активные торренты после смены пасскея)", 1,'my_title','my_name');

if (strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
	sql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
}
div("Мой пасскей","<b>$CURUSER[passkey]</b>", 1,'my_title','my_name');
div("Привязать IP к пасскею", "<input type=checkbox class=\"styled\" name=passkey_ip" . ($CURUSER["passkey_ip"] != "" ? " checked" : "") . "> Включив эту опцию вы можете защитить себя от неавторизованной закакачки по вашему пасскею привязав его к IP. Если ваш IP динамический - не включайте эту опцию.<br />На данный момент ваш IP: <b>".getip()."</b>", 1,'my_title','my_name');
div("Старый пароль", "<input type=\"password\" name=\"oldpassword\" size=\"50\" />", 1,'my_title','my_name');
div("Сменить пароль", "<input type=\"password\" name=\"chpassword\" size=\"50\" />", 1,'my_title','my_name');
div("Пароль еще раз", "<input type=\"password\" name=\"passagain\" size=\"50\" />", 1,'my_title','my_name');

function priv($name, $descr) {
	global $CURUSER;
	if ($CURUSER["privacy"] == $name)
	return "<input type=\"radio\" name=\"privacy\" value=\"$name\" checked=\"checked\" /> $descr";
	return "<input type=\"radio\" name=\"privacy\" value=\"$name\" /> $descr";
}

/* tr("Privacy level",  priv("normal", "Normal") . " " . priv("low", "Low (email address will be shown)") . " " . priv("strong", "Strong (no info will be made available)"), 1); */

?>

<div class="my_name" style="padding-bottom: 15px;"><input type="submit"
	value="Обновить профиль" style='height: 25px'> <input type="reset"
	value="Сбросить изменения" style='height: 25px'></div>
</div>
</form>
</div>
</div>
</div>
<?
//print("<p><a href=users.php><b>Найти пользователя/Список пользователей</b></a></p>");
stdfoot();

?>