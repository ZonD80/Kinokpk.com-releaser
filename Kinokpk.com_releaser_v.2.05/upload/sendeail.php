<?
require_once("include/bittorrent.php");
define("IN_CONTACT",true);
dbconn(false);
if (!$CURUSER) {
require_once('include/recaptchalib.php');
$resp = recaptcha_check_answer ($re_privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	stderr($tracker_lang['error'], "Вы не прошли проверку на человечность, попробуйте еще раз.");
}
}

stdhead("Отправка e-mail администрации");
?>

<?php
$ip = $_POST['ip'];
$httpagent = $_POST['httpagent'];
$visitor = $_POST['visitor'];
$visitormail = $_POST['visitormail'];
$notes = $_POST['notes'];
$subj = $_POST['subj'];

if(!$visitormail == "" && (!strstr($visitormail,"@") || !strstr($visitormail,".")))
{
stdmsg(Ошибка, "Проверьте, верно ли введен адрес Email!", error);
stdfoot();
die();
}

if (eregi('http:', $notes)) {
stdmsg(Ошибка, "Ссылки в сообщении запрещены!", error);
stdfoot();
die();
}

if(empty($subj))
{
stdmsg(Ошибка, "Вы не указали тему сообщения!", error);
stdfoot();
die();
}

if(empty($visitor))
{
stdmsg(Ошибка, "Вы не указали имя отправителя!", error);
stdfoot();
die();
}

if(empty($visitormail))
{
stdmsg(Ошибка, "Вы не указали Email отправителя!", error);
stdfoot();
die();
}

if(empty($notes))
{
stdmsg(Ошибка, "Вы не заполнили поле с текстом сообщения!", error);
stdfoot();
die();

}
// use_captcha
if (!$CURUSER) {
$b = get_row_count("captcha", "WHERE imagehash = ".sqlesc($_POST["imagehash"])." AND imagestring = ".sqlesc($_POST["imagestring"]));
sql_query("DELETE FROM captcha WHERE imagehash = ".sqlesc($_POST["imagehash"])) or die(mysql_error());

if ($b == 0)
{
    stdmsg(Ошибка, "Код с картинки введен неверно или не введен!", error);
    stdfoot();
die();
}
}

$to = $ADMINEMAIL;
$notes = stripcslashes($notes);
$subject .= " $DEFAULTBASEURL - Связь с администрацией";

$msg .="<html><head><meta http-equiv='Content-Type' content='text/html; charset=windows-1251'></head>\n";
$msg .="<body>\n";
$msg .= "<b>Сообщение от</b>:                       $visitor<br>";
$msg .= "<b>IP отправителя</b>:                     $ip<br>";
$msg .= "<b>E-Mail отправителя</b>:                 $visitormail<br>";
$msg .= "<b>Тема сообщения</b>:                     $subj<br>";
$msg .= "<b>Сообщение</b>:                          $notes<br><br>";
$msg .= "<b>User agent</b>:                         $httpagent";
$msg .="</body>\n";
$msg .="</html>\n";

$subject = "$subj";
$mailheaders = "From: $visitor <$visitormail>\n";
$mailheaders .= "Reply-To: $visitormail\n";
$mailheaders .= "MIME-Version: 1.0\nContent-Type: text/html; charset=windows-1251";
mail("$to", "$subject", $msg, "$mailheaders");

?>

<?
stdmsg(Спасибо, "Ваше сообщение было отправлено администрации.");
?>
<br /><br />
<center><a href="index.php"> На главную Kinokpk.com </a></center>
<?
stdfoot();
?>