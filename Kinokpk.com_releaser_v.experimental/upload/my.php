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

INIT();

loggedinorreturn();

$REL_TPL->stdhead($REL_LANG->say_by_key('my_my'));


if ($_GET["emailch"])
print("<h1>".$REL_LANG->say_by_key('my_mail_updated')."</h1>\n");
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
<h2><?=$REL_LANG->say_by_key('account_settings')?></h2>

	<script	type="text/javascript">
	<!--//
	// on DOM ready
	
	
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

$countries = "<option value=0>---- ".$REL_LANG->say_by_key('my_unset')." ----</option>\n";
$ct_r = sql_query("SELECT id,name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
$countries .= "<option value=$ct_a[id]" . ($CURUSER["country"] == $ct_a['id'] ? " selected" : "") . ">$ct_a[name]</option>\n";

$lang = array('ru'=>'Русский (RU)','en'=>'English (EN-US)','ua'=>'Українська (UA)');

$lang_select = '<select class="linkselect" id="language" name="language">';
foreach ( $lang as $l=>$ldesc ) {
	$selected = ((strtolower($CURUSER["language"]) == strtolower($l) ) ? ' selected="selected"' : '');
	$lang_select .= '<option value="' . $l . '"' . $selected . '>' . $ldesc . '</option>';
}
$lang_select .= '</select>';
/*основное*/
if (get_privilege('ownsupport',false)) {
	print('<div class="sp-wrap"><div class="sp-head folded clickable">');
	print('<div class="clickable">Добавить себя в поддержку</div></div><div class="sp-body" style="background: none;">');
	//print('<div class="clickable">Добавить себя в поддержку (для администраторов и выше)</div>');
	print('<div width="100%" id="category" style="margin:10px 0px;">');


	$supportfor = makesafe ( $CURUSER ["supportfor"] );

	print ('<div style="padding: 5px;">
				<form action="'.$REL_SEO->make_link('modtask').'" method="POST">');
	print ( "<div id='my_title'>Поддержка</div>
						<div colspan=2 class='up_avatar' style='margin-bottom: 15px;'>
							<input type=radio name=support value=\"1\"" . ($CURUSER ["supportfor"] ? " checked" : "") . ">{$REL_LANG->say_by_key('yes')}
							<input type=radio name=support value=\"0\"" . (!$CURUSER["supportfor"] ? " checked" : "") . ">{$REL_LANG->say_by_key('no')}
						</div>
			\n" );
	print ( "
				<div id='my_title'>Поддержка для:</div>
				<div colspan=2 class='up_avatar' style='margin-bottom: 15px;'><input type=text size=50 name=supportfor value=\"$supportfor\"></div>
				<div colspan=\"2\" align=\"right\"><input type=\"hidden\" name=\"action\" value=\"ownsupport\"><input type=\"submit\" value=\"Добавить себя в поддержку (удалить)\"></div>
			
		</div>
			</form>
		</div>\n" );
	//	$spend = "</div></div>";
	print('</div></div>');
}
print ('<form id="myform" method="post" action="'.$REL_SEO->make_link('takeprofedit').'">');
div($REL_LANG->_('Nickname'),"<input type=\"text\"  name=\"username\" size=\"40\" maxlength=\"40\" value=\"" . htmlspecialchars($CURUSER["username"]) . "\" /> ", 1,'my_title','my_web');
div($REL_LANG->say_by_key('my_avatar_url'),$CURUSER['avatar']?"<img src=\"".$CURUSER['avatar']."\">":"Нет аватара",1,'my_title','my_avatar');
div(!$CURUSER['avatar'] ? $REL_LANG->_('Upload avatar') : $REL_LANG->_('Change avatar'), "<a class=\"index\" href=\"".$REL_SEO->make_link('avatarup')."\" onclick=\"javascript:$.facebox({ajax:'".$REL_SEO->make_link('avatarup')."'}); return false;\">Кликните сюда для загрузки</a><br />\n".sprintf($REL_LANG->say_by_key('max_avatar_size'), $REL_CONFIG['avatar_max_width'], $REL_CONFIG['avatar_max_height']), 1,'my_title','up_avatar');
div($REL_LANG->say_by_key('my_gender'),
"<p><input type=radio class=\"styled\"  name=gender" . ($CURUSER["gender"] == "1" ? " checked" : "") . " value=1><span>".$REL_LANG->say_by_key('my_gender_male')."</span></p><p>
<input type=radio class=\"styled\"  name=gender" . ($CURUSER["gender"] == "2" ? " checked" : "") . " value=2><span>".$REL_LANG->say_by_key('my_gender_female')."</span></p><p>
<input type=radio class=\"styled\" name=gender" . ($CURUSER["gender"] == "0" ? " checked" : "") . " value=0><span>{$REL_LANG->_('I have not selected yet:)')}</span></p>"
,1,'my_title','my_gender');
print('<div class="clear"></div>');

div($REL_LANG->say_by_key('my_allow_pm_from'),
"<p><input type=radio class=\"styled\"  name=acceptpms" . ($CURUSER["acceptpms"] == "yes" ? " checked" : "") . " value=\"yes\"><span>Все (исключая блокированных)</span></p><p>
<input type=radio class=\"styled\" name=acceptpms" .  ($CURUSER["acceptpms"] == "friends" ? " checked" : "") . " value=\"friends\"><span>Только друзей</span></p><p>
<input type=radio class=\"styled\" name=acceptpms" .  ($CURUSER["acceptpms"] == "no" ? " checked" : "") . " value=\"no\"><span>Только администрации</span></p>"
,1 ,'my_title', 'pm_from');
div($REL_LANG->_("My privacy level"),
"<p><input type=radio class=\"styled\"  name=privacy" . ($CURUSER["privacy"] == "normal" ? " checked" : "") . " value=\"normal\"><span class=\"small\">{$REL_LANG->_("Normal. Your profile and stats can be viewed by any registered member")}</span></p><p>
<input type=radio class=\"styled\" name=privacy" .  ($CURUSER["privacy"] == "strong" ? " checked" : "") . " value=\"strong\"><span class=\"small\">{$REL_LANG->_("Strong. Only your profile (NOT STATS) can be viewed by any registered member")}</span></p><p>
<input type=radio class=\"styled\" name=privacy" .  ($CURUSER["privacy"] == "highest" ? " checked" : "") . " value=\"highest\"><span class=\"small\">{$REL_LANG->_("Highest. Your profile is totally closed from users, except your friends")}</span></p>"
,1 ,'my_title', 'pm_from');
print('<div class="clear"></div>');

div($REL_LANG->say_by_key('my_country'), "<select id='country' name=country>\n$countries\n</select>",1,'my_title','my_count');
div($REL_LANG->say_by_key('my_timezone'), list_timezones('timezone',$CURUSER['timezone']),1,'my_title','my_count');
div($REL_LANG->say_by_key('my_language'), $lang_select ,1,'my_title','my_count');
div($REL_LANG->say_by_key('my_website'), "<input type=\"text\"  name=\"website\" size=50 value=\"" . htmlspecialchars($CURUSER["website"]) . "\" /> ", 1,'my_title','my_web');
div($REL_LANG->_("Signature"), $REL_LANG->_("Your signature will be shown in comments. Maximum length is %s characters (if more, text will be cutted). Pure HTML knowing is allowed!",$REL_CONFIG['sign_length'])."<br /><textarea name=info cols=57 rows=4>" . $CURUSER["info"] . "</textarea><br />", 1,'my_title','my_area');
print('<div class="clear"></div>');


div($REL_LANG->say_by_key('my_delete_after_reply'), "<input type=checkbox class=\"styled\" name=deletepms" . ($CURUSER["deletepms"] ? " checked" : "") . ">",1 ,'my_title', 'my_delete');
print('<div class="clear"></div>');
div($REL_LANG->say_by_key('my_sentbox'), "<input type=checkbox class=\"styled\" name=savepms" . ($CURUSER["savepms"] ? " checked" : "") . ">",1,'my_title','my_sentbox');
print('<div class="clear"></div>');
//                   $spbegin = "<div style=\"position: static;\" class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>Открыть список</i></td></tr></table></div><div class=\"sp-body\">";
//              $spend = "</div></div>";
div($REL_LANG->_("My notifications"),'<a href="'.$REL_SEO->make_link('mynotifs','settings','').'">'.$REL_LANG->_("Go to configuration page of my notifications").'</a>',1,'my_title','my_sent');
div($REL_LANG->say_by_key('my_style'), "<select class=\"linkselect\" id=\"stylesheet\" name=stylesheet>\n$stylesheets\n</select>",1,'my_title','my_style');
div($REL_LANG->say_by_key('view_xxx'),"<p><input type=radio class=\"styled\" name=pron" . (!$CURUSER["pron"] ? " checked" : "") . " value=0><span>".$REL_LANG->say_by_key('no')."</span></p><p>
<input type=radio class=\"styled\" name=pron" . ($CURUSER["pron"]? " checked" : "") . " value=1><span>".$REL_LANG->say_by_key('yes')."</span></p>",1,'my_title','my_xxx');
print('<div class="clear"></div>');

div($REL_LANG->say_by_key('my_comments'),is_i_notified($CURUSER['id'],'usercomments'),1,'my_title','my_comment');

//мод предупреждений
print("<div id=\"nam_warner\"><div id=\"my_title\">Уровень предупреждений</div><div align=\"left\" style=\"padding-top: 22px;\">");
for($i = 0; $i < $CURUSER["num_warned"]; $i++)
{
	$img .= "<a href=\"".$REL_SEO->make_link('mywarned')."\"  target=\"_blank\"><img src=\"pic/star_warned.gif\" alt=\"Уровень предупреждений\" title=\"Уровень предупреждений\"></a>";
}
if (!$img)
$img = "Нет предупреждений!&nbsp;";




///////////////// BIRTHDAY MOD /////////////////////
$birthday = $CURUSER["birthday"];
$birthday = date("Y-m-d", strtotime($birthday));
list($year1, $month1, $day1) = explode('-', $birthday);
if ($CURUSER[birthday] == "0000-00-00") {
	$year .= "<select name=year><option value=\"0000\">".$REL_LANG->say_by_key('my_year')."</option>\n";
	$i = "1920";
	while($i <= (date('Y',time())-13)) {
		$year .= "<option value=" .$i. ">".$i."</option>\n";
		$i++;
	}
	$year .= "</select>\n";
	$birthmonths = array(
        "01" => $REL_LANG->say_by_key('my_months_january'),
        "02" => $REL_LANG->say_by_key('my_months_february'),
        "03" => $REL_LANG->say_by_key('my_months_march'),
        "04" => $REL_LANG->say_by_key('my_months_april'),
        "05" => $REL_LANG->say_by_key('my_months_may'),
        "06" => $REL_LANG->say_by_key('my_months_june'),
        "07" => $REL_LANG->say_by_key('my_months_jule'),
        "08" => $REL_LANG->say_by_key('my_months_august'),
        "09" => $REL_LANG->say_by_key('my_months_september'),
        "10" => $REL_LANG->say_by_key('my_months_october'),
        "11" => $REL_LANG->say_by_key('my_months_november'),
        "12" => $REL_LANG->say_by_key('my_months_december'),
	);
	$month = "<select name=\"month\"><option value=\"00\">".$REL_LANG->say_by_key('my_month')."</option>\n";
	foreach ($birthmonths as $month_no => $show_month)
	{
		$month .= "<option value=$month_no>$show_month</option>\n";
	}
	$month .= "</select>\n";
	$day .= "<select name=day><option value=\"00\">".$REL_LANG->say_by_key('my_day')."</option>\n";
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
	div($REL_LANG->say_by_key('my_birthdate'), $year . $month . $day ,1,'my_title','my_birthday');
}
if($CURUSER[birthday] != "0000-00-00") {
	div($REL_LANG->say_by_key('my_birthdate'),"<b><input type=hidden name=year value=$year1>$year1<input type=hidden name=month value=$month1>.$month1<input type=hidden name=day value=$day1>.$day1</b>",1,'my_title','my_birthday');
}
print('<div class="clear"></div>');
///////////////// BIRTHDAY MOD /////////////////////

//print("<div id=\"my_title\">".$REL_LANG->say_by_key('my_contact')."</div>\n");

div($REL_LANG->say_by_key('my_contact'),"
<div class='message_contakt'>
    <div class='message' >
        ".$REL_LANG->say_by_key('my_contact_descr')."
	</div>
    <div class='message' >
        ".$REL_LANG->say_by_key('my_contact_icq')."<br />
        <img alt src=pic/contact/icq.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"icq\" value=\"" . $CURUSER["icq"] . "\" >
	</div>
    <div class='message' >  
        ".$REL_LANG->say_by_key('my_contact_aim')."<br />
        <img alt='' src=pic/contact/aim.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"aim\" value=\"" . $CURUSER["aim"] . "\" >
   </div>
   <div class='message' >
        ".$REL_LANG->say_by_key('my_contact_msn')."<br />
        <img alt='' src=pic/contact/msn.gif width=\"17\" height=\"17\">
        <input maxLength=\"50\" size=\"25\" name=\"msn\" value=\"" . $CURUSER["msn"] . "\" >
   </div>
   <div class='message' >
        ".$REL_LANG->say_by_key('my_contact_yahoo')."<br />
        <img alt='' src=pic/contact/yahoo.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"yahoo\" value=\"" . $CURUSER["yahoo"] . "\" >
	</div>
    <div class='message' >
         ".$REL_LANG->say_by_key('my_contact_skype')."<br />
        <img alt='' src=pic/contact/skype.gif width=\"17\" height=\"17\">
        <input maxLength=\"32\" size=\"25\" name=\"skype\" value=\"" . $CURUSER["skype"] . "\" >
	</div>
    <div class='message' >
        ".$REL_LANG->say_by_key('my_contact_mirc')."<br />
        <img alt='' src=pic/contact/mirc.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"mirc\" value=\"" . $CURUSER["mirc"] . "\" >
	</div>
  </div>
    ",1,'my_title','my_contact');

print('<div class="clear"></div>');


div($REL_LANG->say_by_key('my_show_avatars'), "<input type=checkbox class=\"styled\" name=avatars" . ($CURUSER["avatars"] ? " checked" : "") . "> (Пользователи с маленькими каналами могут отключить эту опцию)",1,'my_title','my_show_ava');
div("Отображать экстра-эффекты<br />Например, снег", "<input type=checkbox class=\"styled\" name=extra_ef" . ($CURUSER["extra_ef"] ? " checked" : "") . "> (Пользователи со слабыми процессорами или видеокартами могут отключить эту опцию)",1,'my_title','my_check');

div($REL_LANG->say_by_key('my_mail'), "<input type=\"text\" name=\"email\" size=50 value=\"" . htmlspecialchars($CURUSER["email"]) . "\" />", 1,'my_title','my_name');
print("<div><div align=\"left\" colspan=\"2\" style=\"padding-left: 250px;\"><b>Примечание:</b> Если вы смените ваш Email адрес, то вам придет запрос о подтверждении на ваш новый Email-адрес. Если вы не подтвердите письмо, то Email адрес не будет изменен.</div></div>\n");
div("Сменить пасскей","<input type=checkbox class=\"styled\" name=resetpasskey value=1 /> (Вы должны перекачать все активные торренты после смены пасскея)", 1,'my_title','my_name');

if (mb_strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
	$REL_DB->query("UPDATE xbt_users SET torrent_pass=".sqlesc($CURUSER[passkey])." WHERE uid=".sqlesc($CURUSER[id]));
}
div("Мой пасскей","<b>$CURUSER[passkey]</b>", 1,'my_title','my_name');
div("Старый пароль", "<input type=\"password\" name=\"oldpassword\" size=\"50\" />", 1,'my_title','my_name');
div("Сменить пароль", "<input type=\"password\" name=\"chpassword\" size=\"50\" />", 1,'my_title','my_name');
div("Пароль еще раз", "<input type=\"password\" name=\"passagain\" size=\"50\" />", 1,'my_title','my_name');

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
$REL_TPL->stdfoot();

?>