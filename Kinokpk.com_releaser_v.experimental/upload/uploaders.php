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

$REL_TPL->stdhead($REL_LANG->_('Releasers'));

loggedinorreturn();
get_privilege('is_moderator');

$classes = init_class_array();
$level = get_class_priority($classes['uploader']);

foreach ($classes as $cid=>$class) {
	if (is_int($cid)&&$class['priority']>=$level) $to_select[] = $cid;
}
$to_select = implode(',',$to_select);

$query = "SELECT id, username, added, ratingsum, donor, warned, enabled, class, (SELECT MAX(added) FROM torrents WHERE owner=users.id) AS last_added, (SELECT SUM(1) FROM torrents WHERE owner=users.id) AS num_added FROM users WHERE class IN (".$to_select.") ORDER BY users.class ASC";
$result = $REL_DB->query($query);
$num = mysql_num_rows($result); // how many uploaders
print "<h2>{$REL_LANG->_('Releasers information')}</h2>";
print "<p>{$REL_LANG->_('Total releasers: %s',$num)}</p>";

$zerofix = $num - 1; // remove one row because mysql starts at zero

if ($num > 0)
{
	print "<table cellpadding=4 align=center border=1>";
	print "<tr>";
	print "<td class=colhead>{$REL_LANG->_('Username')}</td>";
	print "<td class=colhead>{$REL_LANG->_('Rating')}</td>";
	print "<td class=colhead>{$REL_LANG->_('Uploaded releases count')}</td>";
	print "<td class=colhead>{$REL_LANG->_('Last upload time')}</td>";
	print "<td class=colhead>{$REL_LANG->_('Send PM')}</td>";
	print "</tr>";

	while (list($id,$username,$added,$ratingsum,$donor,$warned,$enabled,$class, $lastadded,$numtorrents) = mysql_fetch_array($result)) {



		$ratio = ratearea($ratingsum,$id,'users',$CURUSER['id']);

		print "<tr>";
		print "<td><a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit($username))."\">$username</a> ".get_user_icons(array('donor'=>$donor,'warned'=>$warned,'enabled'=>$enabled))." (".get_user_class_name($class).")</td>";

		print "<td>$ratio</td>";
		print "<td>".(int)$numtorrents." {$REL_LANG->_('releases')}</td>";

		if ($lastadded)
		print "<td>" . get_elapsed_time($lastadded) . " {$REL_LANG->_('ago')} (" . mkprettytime($lastadded) . ")</td>";
		else
		print "<td>---</td>";
		print "<td align=center><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$id)."\"><img border=0 src=pic/button_pm.gif></a></td>";

		print "</tr>";


	}
	print "</table>";
}


$REL_TPL->stdfoot();

?>