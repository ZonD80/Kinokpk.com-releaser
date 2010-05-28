<?
require_once("include/bittorrent.php");
dbconn();

if (!mkglobal("username:password"))
    die();

function bark($text)
{
  print("<title>Ошибка!</title>");
  print("<table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>");
  print("<center><h1 style='color: #CC3300;'>Ошибка:</h1><h2>$text</h2></center>");
  print("<center><INPUT TYPE='button' VALUE='Назад' onClick=\"history.go(-1)\"></center>");
  print("</td></tr></table>");
  die;
}

if (!$_POST['username'] or !$_POST['password'])
   bark("Вы не указали имя пользователя и(или) пароль!");

$res = sql_query("SELECT id, passhash, secret, enabled, status FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);

if (!$row)
    bark("Вы не зарегистрированы в системе!");

if ($row["status"] == 'pending')
    bark("Вы еще не активировали свой аккаунт! Активируйте ваш аккаунт и попробуйте снова.");

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
    bark("Имя пользователя или пароль неверны!");

if ($row["enabled"] == "no")
    bark("Этот аккаунт отключен.");

$peers = sql_query("SELECT COUNT(id) FROM peers WHERE userid = $row[id]");
$num = mysql_fetch_row($peers);
$ip = getip();
if ($num[0] > 0 && $row[ip] != $ip && $row[ip])
    bark("Этот пользователь на данный момент активен. Вход невозможен!");

logincookie($row["id"], $row["passhash"]);
header("Refresh: 0; url='$DEFAULTBASEURL'");
?>