<?php
/**
 * Signup script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
dbconn();
$REL_LANG->load('my');

//echo "<body><META http-equiv=\"refresh\" content=\"3; URL=$REL_CONFIG['forumurl']/index.php?act=Reg&CODE=00\"></body>";
//stderr($REL_LANG->say_by_key('error'), "Наш проект осуществляется совместно с форумом $REL_CONFIG['forumname'], пройдите регистрацию на нем, а затем просто войдите под своим логином и паролем. Через 3 секунды вы будете перенаправлены.");

if ($REL_CONFIG['deny_signup'] && !$REL_CONFIG['allow_invite_signup'])
stderr($REL_LANG->say_by_key('error'), "Извините, но регистрация отключена администрацией.");

if ($CURUSER)
stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_already_registered'), $REL_CONFIG['sitename']));

if ($REL_CONFIG['maxusers']) {$users = get_row_count("users");
if ($users >= $REL_CONFIG['maxusers'])
stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_users_limit'), number_format($REL_CONFIG['maxusers'])));
}
if (!$_POST["agree"]) {
	stdhead("Правила трекера");
	?>
<form method="post" action="<?=$REL_SEO->make_link('signup')?>">
<div align="center">
<fieldset class="fieldset"><legend>Правила сайта</legend>
<table cellpadding="4" cellspacing="0" border="0" style="width: 100%"
	class="tableinborder">
	<tr>
		<td class="tablea">Для продолжения регистрации, Вы должны согласиться
		со следующими правилами: <?php
		if ($REL_CONFIG['use_integration']) {
			?> <br />
		Если уже у вас есть аккаунт на форуме <?=$REL_CONFIG['forumname']?>,
		то войдите под своим логином и паролем.</td>
	</tr>
	<?php } ?>
	<tr>
		<td class="tablea"
			style="font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif">
		<div class="page"
			style="border-right: thin inset; padding-right: 6px; border-top: thin inset; padding-left: 6px; padding-bottom: 6px; overflow: auto; border-left: thin inset; padding-top: 1px; border-bottom: thin inset; height: 275px">
		<p><strong>Правила <?=$REL_CONFIG['sitename']?></strong></p>
		<p>Регистрация на трекере абсолютно бесплатна! Настоятельно
		рекомендуем ознакомиться с правилами нашего проекта. Если вы согласны
		со всеми условиями, поставьте галочку рядом с 'Я согласен' и нажмите
		'Регистрация'. Если вы передумали регистрироваться, нажмите <a
			href="<?=$REL_CONFIG['defaultbaseurl'];?>">здесь</a>, чтобы вернуться
		на главную страницу.</p>
		<p>Хотя модераторы и администраторы, обслуживающие сайт, стараются
		удалять все оскорбительные и некорректные сообщения из трекера, все
		равно все сообщения просмотреть невозможно. Сообщения отражают точку
		зрения только автора, но не администрации трекера, соответственно
		только автор несет ответственность за содержание сообщения.</p>
		<p>Соглашаясь с нашими правилами, вы обязуетесь выполнять требования
		трекера в целом, а также требования законодательства РФ.</p>
		<p>Администрация трекера оставляет за собой право удалять, изменять,
		переносить или закрывать любую тему или сообщение по своему
		усмотрению.</p>
		</div>
		</td>
	</tr>
	<tr>
		<td class="tablea">
		<div><label> <input class="tablea" type="checkbox" name="agree"
			value="1"> <input type="hidden" name="do" value="register"> <strong>Я
		согласен исполнять установленные правила.</strong> </label></div>
		</td>
	</tr>
</table>
</fieldset>
<p>
<input class="tableinborder" type="submit" value="Регистрация">
</p>
</div>
</form>
	<?
	stdfoot();
	die;
}

stdhead($REL_LANG->say_by_key('signup_signup'));

$countries = "<option value=\"0\">".$REL_LANG->say_by_key('signup_not_selected')."</option>\n";
$ct_r = sql_query("SELECT id, name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
$countries .= "<option value=\"$ct_a[id]\">$ct_a[name]</option>\n";

?>
<span style="color: red; font-weight: bold;"><?=$REL_LANG->say_by_key('signup_use_cookies');?></span>

<?
if ($REL_CONFIG['deny_signup'] && $REL_CONFIG['allow_invite_signup'])
stdmsg("Внимание", "Регистрация доступна только тем у кого есть код приглашения!");
?>
<form method="post" action="<?=$REL_SEO->make_link('takesignup');?>">
<p>
<table border="1" cellspacing=0 cellpadding="10">
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_username');?></td>
		<td align=left><input type="text" size="40" name="wantusername" /></td>
	</tr>
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_password');?></td>
		<td align=left><input type="password" size="40" name="wantpassword" /></td>
	</tr>
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_password_again');?></td>
		<td align=left><input type="password" size="40" name="passagain" /></td>
	</tr>
	<tr valign=top>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_email');?></td>
		<td align=left><input type="text" size="40" name="email" />
		<table width=250 border=0 cellspacing=0 cellpadding=0>
			<tr>
				<td class=embedded><font class=small><?=$REL_LANG->say_by_key('signup_email_must_be_valid');?></font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_gender');?></td>
		<td align=left><input type=radio name=gender value=1><?=$REL_LANG->say_by_key('signup_male');?><input
			type=radio name=gender value=2><?=$REL_LANG->say_by_key('signup_female');?></td>
	</tr>
	<?
	$year .= "<select name=year><option value=\"0000\">".$REL_LANG->say_by_key('my_year')."</option>\n";
	$i = "1920";
	while ($i <= (date('Y',time())-13)) {
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
	foreach ($birthmonths as $month_no => $show_month) {
		$month .= "<option value=$month_no>$show_month</option>\n";
	}
	$month .= "</select>\n";
	$day .= "<select name=day><option value=\"00\">".$REL_LANG->say_by_key('my_day')."</option>\n";
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
	tr($REL_LANG->say_by_key('my_birthdate'), $year.$month.$day ,1);
	tr($REL_LANG->say_by_key('my_country'), "<select name=country>\n$countries\n</select>",1);
	tr($REL_LANG->say_by_key('my_timezone'), list_timezones('timezone',$REL_CONFIG['register_timezone']),1);
	tr($REL_LANG->say_by_key('signup_contact'), "<table cellSpacing=\"3\" cellPadding=\"0\" width=\"100%\" border=\"0\">
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$REL_LANG->say_by_key('my_contact_icq')."<br />
        <img alt src=pic/contact/icq.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"icq\"></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$REL_LANG->say_by_key('my_contact_aim')."<br />
        <img alt src=pic/contact/aim.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"aim\"></td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$REL_LANG->say_by_key('my_contact_msn')."<br />
        <img alt src=pic/contact/msn.gif width=\"17\" height=\"17\">
        <input maxLength=\"50\" size=\"25\" name=\"msn\"></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$REL_LANG->say_by_key('my_contact_yahoo')."<br />
        <img alt src=pic/contact/yahoo.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"yahoo\"></td>
      </tr>
      <tr>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$REL_LANG->say_by_key('my_contact_skype')."<br />
        <img alt src=pic/contact/skype.gif width=\"17\" height=\"17\">
        <input maxLength=\"32\" size=\"25\" name=\"skype\"></td>
        <td style=\"font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif\">
        ".$REL_LANG->say_by_key('my_contact_mirc')."<br />
        <img alt src=pic/contact/mirc.gif width=\"17\" height=\"17\">
        <input maxLength=\"30\" size=\"25\" name=\"mirc\"></td>
      </tr>
    </table>",1);
	tr($REL_LANG->say_by_key('my_website'), "<input type=\"text\" name=\"website\" size=\"40\" value=\"\" />", 1);
	tr($REL_LANG->say_by_key('view_xxx'),"<input type=\"radio\" name=\"pron\" value=\"0\">".$REL_LANG->say_by_key('no')."
<input type=\"radio\" name=\"pron\" value=\"1\">".$REL_LANG->say_by_key('yes'),1);
	if ($REL_CONFIG['use_captcha']) {

		require_once('include/recaptchalib.php');
		tr("Вы человек?",recaptcha_get_html($REL_CONFIG['re_publickey']),1,1);

	}

	if ($REL_CONFIG['allow_invite_signup']) {
		tr("Код приглашения", "<p>Если у вас есть код приглашения от пригласившего то введите его ниже.</p><input type=\"text\" name=\"invite\" maxlength=\"32\" size=\"32\" />", 1);
	}

	$returnto = $_GET['returnto'];
	if (isset($returnto))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($returnto) . "\" />\n");

	tr('Это так?','<input type="checkbox" name="rulesverify" value="1"> '.$REL_LANG->say_by_key('signup_i_have_read_rules').'<br />
<input type="checkbox" name="faqverify" value="1"> '.$REL_LANG->say_by_key('signup_i_will_read_faq').'<br />
<input type="checkbox" name="ageverify" value="1"> '.$REL_LANG->say_by_key('signup_i_am_13_years_old_or_more'),1);
?>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="<?=$REL_LANG->say_by_key('signup_signup');?>" style='height: 25px' /></td>
	</tr>
</table>
</form>

<?php
stdfoot();

?>