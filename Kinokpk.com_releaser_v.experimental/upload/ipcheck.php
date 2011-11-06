<?php
/**
 * IP duplicates finder
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

INIT();

loggedinorreturn();

get_privilege('view_duplicate_ip');

$REL_TPL->stdhead($REL_LANG->_('User IP duplicates'));
$REL_TPL->begin_frame($REL_LANG->_('User IP duplicates'),true);

$res = $REL_DB->query("SELECT SUM(1) AS dupl, ip FROM users WHERE enabled = 1 AND ip <> '' AND ip <> '127.0.0.0' GROUP BY ip ORDER BY dupl DESC, ip");
print("<table width=\"100%\"><tr align=center><td class=colhead width=90>{$REL_LANG->_('Username')}</td>
 <td class=colhead width=70>Email</td>
 <td class=colhead width=70>{$REL_LANG->_('Registered at')}</td>
 <td class=colhead width=75>{$REL_LANG->_('Last activity')}</td>
 <td class=colhead width=45>{$REL_LANG->_('Ratring')}</td>
 <td class=colhead width=125>IP</td>
 <td class=colhead width=40>{$REL_LANG->_('Peer')}</td></tr>\n");
$uc = 0;
while($ras = mysql_fetch_assoc($res)) {
	if ($ras["dupl"] <= 1)
	break;
	if ($ip <> $ras['ip']) {
		$ros = $REL_DB->query("SELECT id, ratingsum, username, class, email, added, last_access, ip, warned, donor, enabled, confirmed, (SELECT SUM(1) FROM peers WHERE peers.ip = users.ip AND users.id = peers.userid) AS peer_count FROM users WHERE ip='".$ras['ip']."' GROUP BY id ORDER BY id");
		$num2 = mysql_num_rows($ros);
		if ($num2 > 1) {
			$uc++;
			while($arr = mysql_fetch_assoc($ros)) {
				$ratio = ratearea($arr['ratingsum'],$arr['id'],'users',$CURUSER['id']);
				$last_access = mkprettytime($arr['last_access']).' ('.get_elapsed_time($arr['last_access'])." {$REL_LANG->say_by_key('ago')})";
				if ($uc%2 == 0)
				$utc = "";
				else
				$utc = " bgcolor=\"ECE9D8\"";

				/*$peer_res = $REL_DB->query("SELECT count(*) FROM peers WHERE ip = " . sqlesc($ras['ip']) . " AND userid = " . $arr['id']);
				 $peer_row = mysql_fetch_row($peer_res);*/
				print("<tr$utc><td align=left>".make_user_link($arr). "</td>
                                  <td align=center>$arr[email]</td>
                                  <td align=center>$added</td>
                                  <td align=center>$last_access</td>
                                  <td align=center>$ratio</td>
                                  <td align=center><span style=\"font-weight: bold;\">$arr[ip]</span></td>\n<td align=center>" .
				($arr['peer_count'] > 0 ? "<span style=\"color: red; font-weight: bold;\">{$REL_LANG->_('Yes')}</span>" : "<span style=\"color: green; font-weight: bold;\">{$REL_LANG->_('No')}</span>") . "</td></tr>\n");
				$ip = $arr["ip"];
			}
		}
	}
}

print ('</table>');
$REL_TPL->end_frame();
$REL_TPL->stdfoot();
?>
