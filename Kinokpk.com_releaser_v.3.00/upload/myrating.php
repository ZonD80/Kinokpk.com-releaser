<?php
/**
 * MyRating page
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
getlang('myrating');
loggedinorreturn();
// getting cron array about rating system
$cronrow = sql_query("SELECT * FROM cron WHERE cron_name LIKE 'rating_%'");

while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

if ($CURUSER['ratingsum']>0) $znak='+';

$query = sql_query("SELECT (SELECT SUM(1) FROM peers WHERE seeder=1 AND userid={$CURUSER['id']}) AS seeding, (SELECT SUM(1) FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE snatched.finished=1 AND torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid={$CURUSER['id']} AND torrents.owner<>{$CURUSER['id']}) AS downloaded");

list($seeding,$downloaded) = mysql_fetch_array($query);
$seeding = (int)$seeding;
$downloaded = (int)$downloaded;

if (!$downloaded && !$seeding) { $formula = $tracker_lang['no_formula']; $nodetails = true; }
elseif ($downloaded && !$seeding) { $formula = $tracker_lang['rating_disconnected']; $nodetails = true; }
elseif ($downloaded>($seeding+$CURUSER['discount']))
$formula = sprintf($tracker_lang['down_formula'],$seeding,$CURUSER['discount'],$downloaded).-$CRON['rating_perleech'];
else {
	$upcount = @round(($seeding+$CURUSER['discount'])/$downloaded);
	if (!$upcount) { $upcount=1; $formula = "({$CRON['rating_perseed']}*1) = +{$CRON['rating_perseed']}"; } else {
		$rateup = ((($seeding+$CURUSER['discount'])>=$downloaded)?$CRON['rating_perseed']*$upcount:(-$CRON['rating_perleech']));

		$formula = "{$CRON['rating_perseed']}*round(($seeding+{$CURUSER['discount']})/$downloaded) = +$rateup";

	}
}


if (isset($_GET['discount'])) {
	$max_discount = ($downloaded-$CURUSER['discount']);
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$discount = (int)$_POST['discountamount'];

		if (($discount>=$CURUSER['ratingsum']) || ($discount<=0)) stderr($tracker_lang['error'],$tracker_lang['no_rating']);
		$devision = (($rateup>$CRON['rating_discounttorrent'])?$rateup:$CRON['rating_discounttorrent']);

		$to_discount = round($discount/$devision);
		if ($to_discount>$max_discount) {
			safe_redirect("myrating.php?discount",3);
			stderr($tracker_lang['error'],$tracker_lang['discount_limit']);
		}
		$to_ratingsum = $to_discount*$devision;
		sql_query("UPDATE users SET discount=discount+$to_discount, ratingsum=ratingsum-$to_ratingsum WHERE id={$CURUSER['id']}");
		safe_redirect("myrating.php",1);
		stderr($tracker_lang['success'],$tracker_lang['rating_changed'],'success');

	} else {
		stdhead($tracker_lang['my_discount']);
		if ($CURUSER['discount']>$downloaded) {
			safe_redirect("myrating.php",3);
			stdmsg($tracker_lang['error'],$tracker_lang['cannot_discount'],'error');
			stdfoot();
			die();
		}
		print('<form action="myrating.php?discount" method="POST"><div align="center" style="display:inline;">'.sprintf($tracker_lang['discount_link'],(($rateup>$CRON['rating_discounttorrent'])?$rateup:$CRON['rating_discounttorrent'])).'<br />
   '.sprintf($tracker_lang['i_chage'],'<input type="text" name="discountamount" size="5">',$max_discount,$znak.$CURUSER['ratingsum']).'<br /><input type="submit" value="'.$tracker_lang['chage_rating'].'"></div></form>');
		stdfoot();
		die();
	}
}
stdhead($tracker_lang['rating_title']);
begin_frame($tracker_lang['rating_title']);

print('<table width="100%">');
tr($tracker_lang['rating_title'],"<h1>{$tracker_lang['rating_title']}: $znak{$CURUSER['ratingsum']}".($CRON['rating_enabled']?", {$tracker_lang['my_discount']}: {$CURUSER['discount']}":'')."</h1>",1);

// if ratings enabled
if ($CRON['rating_enabled']) {

	if (get_user_class()==UC_VIP)
	$goods = $tracker_lang['goods_vip'];
	elseif ((time()-$CURUSER['added'])<($CRON['rating_freetime']*86400)) $goods = sprintf($tracker_lang['goods_new'],($CRON['rating_freetime']-round((time()-$CURUSER['added'])/86400)));
	else $goods = $tracker_lang['no_goods'];

	tr($tracker_lang['my_goods'],$goods,1);

	//print "<h1>$seeding $downloaded fuck!</h1>";
	tr($tracker_lang['now_i'],"<h1><a href=\"userhistory.php?id={$CURUSER['id']}&amp;type=seeding\">{$tracker_lang['seeding']}</a>&nbsp;<img title=\"{$tracker_lang['seedeing']}\" src=\"pic/arrowup.gif\"/>: $seeding, <a href=\"userhistory.php?id={$CURUSER['id']}&amp;type=downloaded\">{$tracker_lang['downloaded_rel']}</a>&nbsp;<img title=\"{$tracker_lang['downloaded_rel']}\" src=\"pic/download.gif\"/>: $downloaded, {$tracker_lang['discounted']}&nbsp;<img title=\"{$tracker_lang['discounted']}\" src=\"pic/freedownload.gif\"/>: {$CURUSER['discount']}</h1>",1);

	tr($tracker_lang['my_formula'],"<strong>$formula".(!$nodetails?" {$tracker_lang['once']} ".($CRON['rating_checktime']/60)." {$tracker_lang['hours']}":'')."</strong>",1);
	print ('<tr><td align="center" colspan="2"><h1>'.sprintf($tracker_lang['get_rating'],$CRON['rating_perrelease']).'</h1></td></tr>');
	print ('<tr><td align="center" colspan="2"><h1>'.sprintf($tracker_lang['rating_per_invite'],$CRON['rating_perinvite']).'</h1></td></tr>');
	print ('<tr><td align="center" colspan="2"><h1>'.sprintf($tracker_lang['rating_per_request'],$CRON['rating_perrequest']).'</h1></td></tr>');

	tr($tracker_lang['down_levels'],sprintf($tracker_lang['down_notice'],$CRON['rating_downlimit'],$CRON['rating_dislimit']),1);
	tr($tracker_lang['my_discount'],sprintf($tracker_lang['discount_link'],(($rateup>$CRON['rating_discounttorrent'])?$rateup:$CRON['rating_discounttorrent'])),1);
}
print ('</table>');
end_frame();

stdfoot();
?>