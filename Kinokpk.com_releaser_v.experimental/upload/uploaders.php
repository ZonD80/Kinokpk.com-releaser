<?php
/**
 * Uploader statistics
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once "include/bittorrent.php";
INIT();

$REL_TPL->stdhead("Аплоадеры");

loggedinorreturn();
get_privilege('is_moderator');

$classes = init_class_array();
$level = get_class_priority($classes['uploader']);

foreach ($classes as $cid=>$class) {
	if (is_int($cid)&&$class['priority']>=$level) $to_select[] = $cid;
}
$to_select = implode(',',$to_select);

$query = "SELECT id, username, added, ratingsum, donor, warned, enabled, (SELECT MAX(added) FROM torrents WHERE owner=users.id) AS last_added, (SELECT SUM(1) FROM torrents WHERE owner=users.id) AS num_added FROM users WHERE class IN (".$to_select.")";
$result = sql_query($query);
$num = mysql_num_rows($result); // how many uploaders
print "<h2>Информация о аплоадерах</h2>";
print "<p>У нас " . $num . " аплоадер" . ($num > 1 ? "ов" : "") . "</p>";

$zerofix = $num - 1; // remove one row because mysql starts at zero

if ($num > 0)
{
	print "<table cellpadding=4 align=center border=1>";
	print "<tr>";
	print "<td class=colhead>Пользователь</td>";
	print "<td class=colhead>Рейтинг</td>";
	print "<td class=colhead>Залил&nbsp;релизов</td>";
	print "<td class=colhead>Последняя&nbsp;заливка</td>";
	print "<td class=colhead>Отправить ЛС</td>";
	print "</tr>";

	while (list($id,$username,$added,$ratingsum,$donor,$warned,$enabled,$lastadded,$numtorrents) = mysql_fetch_array($result)) {



		$ratio = ratearea($ratingsum,$id,'users',$CURUSER['id']);

		print "<tr>";
		print "<td><a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit($username))."\">$username</a> ".get_user_icons(array('donor'=>$donor,'warned'=>$warned,'enabled'=>$enabled))."</td>";

		print "<td>$ratio</td>";
		print "<td>$numtorrents торрентов</td>";

		if ($lastadded)
		print "<td>" . get_elapsed_time($lastadded) . " назад (" . mkprettytime($lastadded) . ")</td>";
		else
		print "<td>---</td>";
		print "<td align=center><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$id)."\"><img border=0 src=pic/button_pm.gif></a></td>";

		print "</tr>";


	}
	print "</table>";
}


$REL_TPL->stdfoot();

?>