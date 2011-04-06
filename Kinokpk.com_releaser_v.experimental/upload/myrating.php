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

INIT();

loggedinorreturn();

if ($CURUSER['ratingsum']>0) $znak='+';

$query = sql_query("SELECT (SELECT SUM(1) FROM peers WHERE seeder=1 AND userid={$CURUSER['id']}) AS seeding, (SELECT SUM(1) FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid={$CURUSER['id']} AND torrents.owner<>{$CURUSER['id']}) AS downloaded");

list($seeding,$downloaded) = mysql_fetch_array($query);
$seeding = (int)$seeding;
$downloaded = (int)$downloaded;

if (($CURUSER['ratingsum']>$REL_CRON['rating_max'])&&($downloaded<=($seeding+$CURUSER['discount']))) { $formula = $REL_LANG->_('Your rating is upper then %s, automatic rating increase disabled. You can increase your rating by active commenting, releasing and receiving ratings from another users',$REL_CRON['rating_max']); $nodetails=true; }
elseif (!$downloaded && !$seeding) { $formula = $REL_LANG->say_by_key('no_formula'); $nodetails = true; }
elseif ($downloaded && !$seeding) { $formula = $REL_LANG->say_by_key('rating_disconnected'); $nodetails = true; }
elseif ($downloaded>($seeding+$CURUSER['discount']))
$formula = sprintf($REL_LANG->say_by_key('down_formula'),$seeding,$CURUSER['discount'],$downloaded).-$REL_CRON['rating_perleech'];
else {
	$upcount = (int)@round(($seeding+$CURUSER['discount'])/$downloaded);
	if (!$upcount) { $upcount=1; $formula = "({$REL_CRON['rating_perseed']}*1) = +{$REL_CRON['rating_perseed']}"; } else {
		$rateup = ((($seeding+$CURUSER['discount'])>=$downloaded)?$REL_CRON['rating_perseed']*$upcount:(-$REL_CRON['rating_perleech']));

		$formula = "{$REL_CRON['rating_perseed']}*round(($seeding+{$CURUSER['discount']})/$downloaded) = +$rateup";

	}
}


if (isset($_GET['discount'])) {
	$max_discount = ($downloaded-$CURUSER['discount']);
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$discount = (int)$_POST['discountamount'];

		if (($discount>=$CURUSER['ratingsum']) || ($discount<=0)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_rating'));
		$devision = (($rateup>$REL_CRON['rating_discounttorrent'])?$rateup:$REL_CRON['rating_discounttorrent']);

		$to_discount = round($discount/$devision);
		if ($to_discount>$max_discount) {
			safe_redirect($REL_SEO->make_link('myrating','discount',''),3);
			stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('discount_limit'));
		}
		$to_ratingsum = $to_discount*$devision;
		sql_query("UPDATE users SET discount=discount+$to_discount, ratingsum=ratingsum-$to_ratingsum WHERE id={$CURUSER['id']}");
		safe_redirect($REL_SEO->make_link('myrating'),1);
		stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('rating_changed'),'success');

	} else {
		$REL_TPL->stdhead($REL_LANG->say_by_key('my_discount'));
		if ($CURUSER['discount']>$downloaded) {
			safe_redirect($REL_SEO->make_link('myrating'),3);
			stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('cannot_discount'),'error');
			$REL_TPL->stdfoot();
			die();
		}
		print('<form action="'.$REL_SEO->make_link('myrating','discount','').'" method="POST"><div align="center" style="display:inline;">'.sprintf($REL_LANG->say_by_key('discount_link'),(($rateup>$REL_CRON['rating_discounttorrent'])?$rateup:$REL_CRON['rating_discounttorrent'])).'<br />
   '.sprintf($REL_LANG->say_by_key('i_chage'),'<input type="text" name="discountamount" size="5">',$max_discount,$znak.$CURUSER['ratingsum']).'<br /><input type="submit" value="'.$REL_LANG->say_by_key('chage_rating').'"></div></form>');
		$REL_TPL->stdfoot();
		die();
	}
}
$REL_TPL->stdhead($REL_LANG->say_by_key('rating_title'));
$REL_TPL->begin_frame($REL_LANG->say_by_key('rating_title')." | <a href=\"{$REL_SEO->make_link('aboutrating')}\">{$REL_LANG->_('Rating system manual')}</a>");

print('<table width="100%">');
tr($REL_LANG->say_by_key('rating_title'),"<h1>{$REL_LANG->say_by_key('rating_title')}: $znak{$CURUSER['ratingsum']}".($REL_CRON['rating_enabled']?", {$REL_LANG->say_by_key('my_discount')}: {$CURUSER['discount']}":'')."</h1>",1);

// if ratings enabled
if ($REL_CRON['rating_enabled']) {

	if (get_privilege('is_vip',false))
	$goods = $REL_LANG->say_by_key('goods_vip');
	elseif ((time()-$CURUSER['added'])<($REL_CRON['rating_freetime']*86400)) $goods = sprintf($REL_LANG->say_by_key('goods_new'),($REL_CRON['rating_freetime']-round((time()-$CURUSER['added'])/86400)));
	else $goods = $REL_LANG->say_by_key('no_goods');

	tr($REL_LANG->say_by_key('my_goods'),$goods,1);

	//print "<h1>$seeding $downloaded fuck!</h1>";
	tr($REL_LANG->say_by_key('now_i'),"<h1><a href=\"".$REL_SEO->make_link('userhistory','id',$CURUSER['id'],'type','seeding')."\">{$REL_LANG->say_by_key('seeding')}</a>&nbsp;<img title=\"{$REL_LANG->say_by_key('seedeing')}\" src=\"pic/arrowup.gif\"/>: $seeding, <a href=\"".$REL_SEO->make_link('userhistory','id',$CURUSER['id'],'type','downloaded')."\">{$REL_LANG->say_by_key('downloaded_rel')}</a>&nbsp;<img title=\"{$REL_LANG->say_by_key('downloaded_rel')}\" src=\"pic/download.gif\"/>: $downloaded, {$REL_LANG->say_by_key('discounted')}&nbsp;<img title=\"{$REL_LANG->say_by_key('discounted')}\" src=\"pic/freedownload.gif\"/>: {$CURUSER['discount']}</h1>",1);
	tr($REL_LANG->_("Download again"),$REL_LANG->_('You can download all previous releases in one ZIP-archive without rating decrease<br/><a href="%s">View downloaded releases</a> or <a href="%s">Download ZIP-archive with torrents</a>',$REL_SEO->make_link('userhistory','id',$CURUSER['id'],'type','downloaded'),$REL_SEO->make_link('download','a','my')),1);
	tr($REL_LANG->say_by_key('my_formula'),"<strong>$formula".(!$nodetails?" {$REL_LANG->say_by_key('once')} ".($REL_CRON['rating_checktime']/60)." {$REL_LANG->say_by_key('hours')}":'')."</strong>",1);
	print ('<tr><td align="center" colspan="2"><h1>'.sprintf($REL_LANG->say_by_key('get_rating'),$REL_CRON['rating_perrelease']).'</h1></td></tr>');
	print ('<tr><td align="center" colspan="2"><h1>'.sprintf($REL_LANG->say_by_key('rating_per_invite'),$REL_CRON['rating_perinvite']).'</h1></td></tr>');
	print ('<tr><td align="center" colspan="2"><h1>'.sprintf($REL_LANG->say_by_key('rating_per_request'),$REL_CRON['rating_perrequest']).'</h1></td></tr>');

	tr($REL_LANG->say_by_key('down_levels'),sprintf($REL_LANG->say_by_key('down_notice'),$REL_CRON['rating_downlimit'],$REL_CRON['rating_dislimit']),1);
	tr($REL_LANG->say_by_key('my_discount'),sprintf($REL_LANG->say_by_key('discount_link'),(($rateup>$REL_CRON['rating_discounttorrent'])?$rateup:$REL_CRON['rating_discounttorrent'])),1);
}
print ('</table>');
$REL_TPL->end_frame();

$REL_TPL->stdfoot();
?>