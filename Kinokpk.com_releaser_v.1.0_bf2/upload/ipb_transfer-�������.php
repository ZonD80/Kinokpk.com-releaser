<?php
/////////////////////////////////////////////////////
/////  Integration TBDev YSe Pre 6 c IPB 2.1.7////////
/////                 By ZonD80            //////////
/////     Transfer account details          //////////
/////////////////////////////////////////////////////

require_once("include/bittorrent.php");
dbconn();
if (get_user_class() < UC_USER) die ("ACCESS DENIED");
$prefix = "ipb_";

$res = mysql_query("SELECT avatar,icq,msn,aim,yahoo,skype,website,birthday FROM users WHERE id = ".$CURUSER["id"]);
$row = mysql_fetch_array($res);

$vozrast = getdate($row['birthday']);


$updateset[] = "bday_day = " .$vozrast['mday'];
$updateset[] = "bday_month = " .$vozrast['mon'];
$updateset[] = "bday_year = " .$vozrast['year'];

 $first = mysql_query("UPDATE ".$prefix."members SET " . join(",", $updateset) . " WHERE id = ".$CURUSER["id"]);
if ($row['avatar'] != '') {$avatar_location = $row['avatar']; $avatar_type = 'url';
$size = GetImageSize($avatar_location);
$avatar_size = $size[0]."x".$size[1];
}
else { $avatar = ''; $avatar_location = ''; $avatar_size = ''; $avatar_type = 'local'; }

$updateset2[] = "aim_name = '" .$row['aim'] . "'";
$updateset2[] = "icq_number = " .$row['icq'];
$updateset2[] = "website = '" .$row['website'] ."'";
$updateset2[] = "yahoo = '" .$row['yahoo'] ."'";
$updateset2[] = "msnname = '" .$row['msn'] ."'";
$updateset2[] = "avatar_location = '" .$avatar_location ."'";
$updateset2[] = "avatar_size = '" .$avatar_size ."'";
$updateset2[] = "avatar_type = '" .$avatar_type ."'";

$second = mysql_query("UPDATE ".$prefix."member_extra SET " . join(",", $updateset2) . " WHERE id = ".$CURUSER["id"]);

echo "Ваши данные (Дата рождения, аватар, ICQ, MSN, Skype, сайт, AIM успешно перенесены с релизера на форум. <a href=\"http://kinokpk.com/forums/index.php?showuser=".$CURUSER["id"]."\">Посмотреть профиль форума</a>";

?>