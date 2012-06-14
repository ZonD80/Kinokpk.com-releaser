<?php
if (!defined('BLOCK_FILE')) {
    Header("Location: ../index.php");
    exit;
}
global $CACHE;
$content = $CACHE->get('block-birth', 'content', 3600);

if ($content === false) {
///////////////// BIRTHDAY MOD /////////////////////  
    $b = 0;
    $currentdate = date("Y-m-d", time() + $CURUSER['tzoffset'] * 60);
    list($year1, $month1, $day1) = split('-', $currentdate);
    $res = mysql_query("SELECT birthday, id, username, class, donor, warned, gender FROM users WHERE  birthday != '0000-00-00'") or sqlerr();
    while ($arr = mysql_fetch_assoc($res)) {
        $birthday = date($arr["birthday"]);
        $username = get_user_class_color($arr["class"], $arr["username"]);
        $id = $arr["id"];
        list($year2, $month2, $day2) = split('-', $birthday);
        if (($month1 == $month2) && ($day1 == $day2)) {
            if ($b > 0)
                $content .= ", ";
            $donator = $arr["donor"] == "yes";
            if ($donator) {
                $username .= "<img border=\"0\" alt=\"�����������\" src=\"pic/star.gif\">";
            }
            $female = $arr["gender"] == "2";
            if ($female) {
                $username .= "<img border=\"0\" alt=\"�������\" src=\"pic/ico_f.gif\">";
            }
            $male = $arr["gender"] == "1";
            if ($male) {
                $username .= "<img border=\"0\" alt=\"������\" src=\"pic/ico_m.gif\">";
            }
            $warned = $arr["warned"] == "yes";
            if ($warned) {
                $username .= "<img border=\"0\" alt=\"������������\" src=\"pic/warned.gif\">";
            }
            $female = $arr["gender"] == "2";
            if ($female) {
                $username .= "<img border=\"0\" alt=\"�������\" src=\"pic/brt.gif\">";
            }
            $male = $arr["gender"] == "1";
            if ($male) {
                $username .= "<img border=\"0\" alt=\"������\" src=\"pic/brt.gif\">";
            }
            $content .= "<a href=userdetails.php?id=$id><b>$username</b></a>";
            $b = $b + 1;
        }
    }
    if ($b == 0)
        $content .= "��� ������� ���� �����...";
///////////////// BIRTHDAY MOD /////////////////////  
    $CACHE->set('block-birth', 'content', $content);
}
?>
