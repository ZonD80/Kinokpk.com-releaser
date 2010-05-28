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
dbconn();

//echo "<body><META http-equiv=\"refresh\" content=\"3; URL=$FORUMURL/index.php?act=Reg&CODE=00\"></body>";
	//stderr($tracker_lang['error'], "Наш проект осуществляется совместно с форумом $FORUMNAME, пройдите регистрацию на нем, а затем просто войдите под своим логином и паролем. Через 3 секунды вы будете перенаправлены.");

if ($deny_signup && !$allow_invite_signup)
	stderr($tracker_lang['error'], "Извините, но регистрация отключена администрацией.");

if ($CURUSER)
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_already_registered'], $SITENAME));

list($users) = mysql_fetch_array(sql_query("SELECT COUNT(id) FROM users"));
if ($users >= $maxusers)
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_users_limit'], number_format($maxusers)));

if ($_POST["agree"] != "yes") {
stdhead("Правила трекера");
?>
<div style="width:80%" align="center">
<fieldset class="fieldset">
<legend>Правила релизера Kinokpk.com</legend>
<form method="post" action="<?=$PHP_SELF?>">
<table cellpadding="4" cellspacing="0" border="0" style="width:100%" class="tableinborder">
<tr>
<td class="tablea">Для продолжения регистрации, Вы должны согласиться со следующими правилами:<br />
<font color="red">ВНИМАНИЕ! ВЫ ТАКЖЕ ЗАРЕГЕСТРИРУЕТЕСЬ НА ФОРУМЕ <?=$FORUMNAME?>!!!<br />
Если уже у вас есть аккаунт на форуме <?=$FORUMNAME?>, то войдите под своим логином и паролем.</font></td></tr>
<tr>
  <td class="tablea" style="font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif">
<div class="page" style="border-right: thin inset; padding-right: 6px; border-top: thin inset; padding-left: 6px; padding-bottom: 6px; overflow: auto; border-left: thin inset; padding-top: 1px; border-bottom: thin inset; height: 170px">
<p><strong>Правила Kinokpk.com и <u><a target="_blank" href="<?=$FORUMURL?>/index.php?act=boardrules">форума <?=$FORUMNAME?> (перейти)</a></u></strong></p>
<p>Регистрация на трекере абсолютно бесплатна! Настоятельно рекомендуем ознакомиться с правилами нашего проекта.
Если вы согласны со всеми условиями, поставьте галочку рядом с 'Я согласен' и нажмите 'Регистрация'.
Если вы передумали регистрироваться, нажмите <a href="<?=$DEFAULTBASEURL;?>">здесь</a>, чтобы вернуться на главную страницу.</p>
<p>Хотя модераторы и администраторы, обслуживающие <?=$SITENAME;?>, стараются удалять все оскорбительные и некорректные
сообщения из трекера, все равно все сообщения просмотреть невозможно. Сообщения отражают точку зрения только
автора, но не администрации трекера, соответственно только автор несет ответственность за содержание сообщения.</p>
<p>Соглашаясь с нашими правилами, вы обязуетесь выполнять требования трекера в целом, а также требования законодательства РФ.</p>
<p>Администрация трекера оставляет за собой право удалять, изменять, переносить или закрывать любую тему или сообщение по своему усмотрению.</p>
</div>
</td></tr>
<tr><td class="tablea">
<div>
<label>
<input class="tablea" type="checkbox" name="agree" value="yes">
<input type="hidden" name="do" value="register">
  <strong>Я согласен исполнять установленные правила, посещая <?=$SITENAME;?>.</strong>
</label>
</div>
</td></tr>
</table>
</fieldset><p>
<center>
<input class="tableinborder" type="submit" value="Регистрация">
</center>
</form>
<?
stdfoot();
die;
}

stdhead($tracker_lang['signup_signup']);

$countries = "<option value=\"0\">".$tracker_lang['signup_not_selected']."</option>\n";
$ct_r = sql_query("SELECT id, name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
  $countries .= "<option value=\"$ct_a[id]\">$ct_a[name]</option>\n";

?>
<span style="color: red; font-weight: bold;"><?=$tracker_lang['signup_use_cookies'];?></span>

<?
if ($deny_signup && $allow_invite_signup)
	stdmsg("Внимание", "Регистрация доступна только тем у кого есть код приглашения!");
?>

<p>
<form method="post" action="takesignup.php">
<table border="1" cellspacing=0 cellpadding="10">
<tr><td align="right" class="heading"><?=$tracker_lang['signup_username'];?></td><td align=left><input type="text" size="40" name="wantusername" /></td></tr>
<tr><td align="right" class="heading"><?=$tracker_lang['signup_password'];?></td><td align=left><input type="password" size="40" name="wantpassword" /></td></tr>
<tr><td align="right" class="heading"><?=$tracker_lang['signup_password_again'];?></td><td align=left><input type="password" size="40" name="passagain" /></td></tr>
<tr valign=top><td align="right" class="heading"><?=$tracker_lang['signup_email'];?></td><td align=left><input type="text" size="40" name="email" />
<table width=250 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded><font class=small><?=$tracker_lang['signup_email_must_be_valid'];?></td></tr>
</font></td></tr></table>
</td></tr>
<tr><td align="right" class="heading"><?=$tracker_lang['signup_gender'];?></td><td align=left><input type=radio name=gender value=1><?=$tracker_lang['signup_male'];?><input type=radio name=gender value=2><?=$tracker_lang['signup_female'];?></td></tr>
<?
$year .= "<select name=year><option value=\"0000\">".$tracker_lang['my_year']."</option>\n";
$i = "1920";
while ($i <= (date('Y',time())-13)) {
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
foreach ($birthmonths as $month_no => $show_month) {
	$month .= "<option value=$month_no>$show_month</option>\n";
}
$month .= "</select>\n";
$day .= "<select name=day><option value=\"00\">".$tracker_lang['my_day']."</option>\n";
$i = 1;
while ($i <= 31) {
	if ($i < 10) {
		$day .= "<option value=0".$i.">0".$i."</option>\n";
	} else {
		$day .= "<option value=".$i.">".$i."</option>\n";
	}
	$i++;
}
$day .="</select>\n";
tr($tracker_lang['my_birthdate'], $year.$month.$day ,1);
tr($tracker_lang['my_country'], "<select name=country>\n$countries\n</select>",1);
tr($tracker_lang['signup_contact'], "<table cellSpacing=\"3\" cellPadding=\"0\" width=\"100%\" border=\"0\">
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_icq']."<br />
        <img alt src=pic/contact/icq.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"icq\"></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_aim']."<br />
        <img alt src=pic/contact/aim.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"aim\"></td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_msn']."<br />
        <img alt src=pic/contact/msn.gif width=\"17\" height=\"17\">
        <input maxLength=\"50\" size=\"25\" name=\"msn\"></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_yahoo']."<br />
        <img alt src=pic/contact/yahoo.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"yahoo\"></td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_skype']."<br />
        <img alt src=pic/contact/skype.gif width=\"17\" height=\"17\">
        <input maxLength=\"32\" size=\"25\" name=\"skype\"></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$tracker_lang['my_contact_mirc']."<br />
        <img alt src=pic/contact/mirc.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"mirc\"></td>
      </tr>
    </table>",1);
tr($tracker_lang['my_website'], "<input type=\"text\" name=\"website\" size=\"40\" value=\"\" />", 1);

if ($use_captcha) {

require_once('include/recaptchalib.php');
tr("Вы человек?",recaptcha_get_html($re_publickey),1,1);

}

if ($allow_invite_signup) {
	tr("Код приглашения", "<p>Если у вас есть код приглашения от пригласившего то введите его ниже.</p><input type=\"text\" name=\"invite\" maxlength=\"32\" size=\"32\" />", 1);
}

$returnto = $_GET['returnto'];
if (isset($returnto))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars_uni($returnto) . "\" />\n");

tr('Это так?','<input type="checkbox" name="rulesverify" value="yes"> '.$tracker_lang['signup_i_have_read_rules'].'<br />
<input type="checkbox" name="faqverify" value="yes"> '.$tracker_lang['signup_i_will_read_faq'].'<br />
<input type="checkbox" name="ageverify" value="yes"> '.$tracker_lang['signup_i_am_13_years_old_or_more'],1);
?>
<tr><td colspan="2" align="center"><input type="submit" value="<?=$tracker_lang['signup_signup'];?>" style='height: 25px'/></td></tr>
</table>
</form>

<?php
stdfoot();

?>