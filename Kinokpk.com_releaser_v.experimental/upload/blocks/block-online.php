<?php
global $CURUSER, $REL_CONFIG, $REL_SEO, $REL_CACHE, $REL_LANG;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}
$a = mysql_fetch_array(sql_query("SELECT id, username FROM users WHERE id = (SELECT MAX(id) FROM users WHERE users.confirmed=1)"));
if ($CURUSER)
$latestuser = "<a href='".$REL_SEO->make_link('userdetails','id',$a['id'],'username',$a['username'])."' class='online'>" . $a["username"] . "</a>";
else
$latestuser = $a['username'];
$title_who = array();
$gues = array();
$dt = sqlesc(time() - 300);
$result = sql_query("SELECT DISTINCT s.uid, s.username, s.class, s.ip FROM sessions AS s WHERE s.time > $dt ORDER BY s.class DESC");
$classes = init_class_array();
while ($row = mysql_fetch_array($result)) {
	$uid = $row["uid"];
	$uname = $row["username"];
	$class = $row["class"];
	$ip = $row["ip"];
	$uname_new = $uname;
	if (!empty($uname) && ($uname_new != $uname_old)) {
		$title_who[] = "<a href='".$REL_SEO->make_link('userdetails','id',$uid,'username',$uname)."' class='online'>".get_user_class_color($class, $uname)."</a>";
	}
	if (($uname_new != $uname_old) && (get_class_priority($class) >= get_class_priority($classes['staffbegin']))) {
		$staff++;
	} elseif((!empty($uname) and $uname_new != $uname_old)) {
		$users++;
	}
	if ($uid <= 0 && !in_array("$ip",$gues)) {
		$guests++;
		$gues[] = "$ip";
	}
	if (empty($uname)) {
		continue;
	} else {
		$who_online .= $title_who;
	}
	$uname_old = $uname;
}
$total = $staff + $users + $guests;
$max_total = $REL_CACHE->get('block-online','total');
$max_time = $REL_CACHE->get('block-online','time');
if (!$max_time) $max_time=time();
if (!$max_total) {
	$REL_CACHE->set('block-online','total',$total);
	$REL_CACHE->set('block-online','time',time());
}

if ($total>$max_total) {
	$REL_CACHE->set('block-online','total',$total);
	$REL_CACHE->set('block-online','time',time());
	$max_time = time();
	$max_total = $total;
}
if ($staff == "")  $staff = 0;
if ($guests == "") $guests = 0;
if ($users == "")  $users = 0;
if ($total == "")  $total = 0;
$content .= "<table border='0' width='100%'>
             <tr valign='middle'>
             <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'><b>Последний: </b> $latestuser</td></tr>";
if (count($title_who)) {
	$content .= "<tr valign='middle'>
                    <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
                    <b>Кто онлайн: </b><br />".@implode(", ", $title_who)."</td></tr>";
} else {
	$content .= "<tr valign='middle'>
                    <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
                    <b>Кто онлайн: </b><br />Нет пользователей за последние 10 минут.</td></tr>";
}
$content .= "<tr valign='middle'>
            <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
            <b>В сети: </b><br />";
$content .= "<img src='pic/info/admin.gif' alt='Администраторы' align='middle' width='16' height='16' />&nbsp;<font color='red'>Администрация: $staff</font>&nbsp;";
$content .= "<img src='pic/info/member.gif' alt='Пользователи' align='middle' width='16' height='16' />&nbsp;Пользователи: $users&nbsp;<br />";
$content .= "<img src='pic/info/guest.gif' alt='Гости' align='middle' width='16' height='16' />&nbsp;Гости: $guests&nbsp;";
$content .= "<img src='pic/info/group.gif' alt='Всего' align='middle' width='16' height='16' />&nbsp;Всего: $total</td></tr>";
$content .= "<tr valign='middle'>
            <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
            <b>Рекорд: $max_total</b>, зарегестрирован<br/>".mkprettytime($max_time)." (".get_elapsed_time($max_time,false)." {$REL_LANG->say_by_key('ago')})</td></tr></table>";

?>