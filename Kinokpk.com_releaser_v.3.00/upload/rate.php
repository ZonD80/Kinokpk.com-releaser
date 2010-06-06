<?php
/**
 * Script that really making your rating:)
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");

dbconn();


loggedinorreturn();
getlang('rate');

$rid = (int)$_GET['id'];
$type = (string)$_GET['type'];


$allowed_types = array('torrents','users','comments','pollcomments','newscomments','usercomments','reqcomments','relgroups','rgcomments','pages','pagecomments');

/**
 * Checks that user cannot change himself ratings
 * @param int $id ID of element to change rating
 * @param string $type Type of rating
 * @return boolean Can user change rating or not
 */
function check_myself($id,$type) {
	global $CURUSER;
	if (preg_match('/comments/',$type)) $check = @mysql_result(sql_query("SELECT 1 FROM $type WHERE user = {$CURUSER['id']} AND id = $id"),0);
	elseif($type=='users') $check = (($id==$CURUSER['id'])?1:0);
	elseif (($type=='torrents')||($type=='pages')) $check = @mysql_result(sql_query("SELECT 1 FROM $type WHERE owner = {$CURUSER['id']} AND id = $id"),0);
	elseif ($type=='relgroups') $check = @mysql_result(sql_query("SELECT 1 FROM relgroups WHERE FIND_IN_SET({$CURUSER['id']},owners) OR FIND_IN_SET({$CURUSER['id']},members)"));
	if ($check) return false; else return true;
}
if ($_GET['act']=='up') $act='+1'; else $act='-1';

if (!in_array($type,$allowed_types)) $invalid=true;

if (!$rid || !$type || $invalid || !is_valid_id($rid)) die($tracker_lang['invalid_id']);

$myself = check_myself($rid,$type);
if (!$myself) stderr($tracker_lang['error'],$tracker_lang['cant_rate_yourself']);

$voted = @mysql_result(sql_query("SELECT id FROM ratings WHERE userid={$CURUSER['id']} AND rid=$rid AND type='$type'"),0);

if (!$voted) {
	sql_query("INSERT INTO ratings (rid,userid,type,added) VALUES ($rid,{$CURUSER['id']},'$type',".time().")");
	sql_query("UPDATE $type SET ratingsum=ratingsum$act WHERE id=$rid");
	sql_query("UPDATE users SET ratingsum=ratingsum$act WHERE id=(SELECT user FROM $type WHERE id=$rid)");
	stderr($tracker_lang['rating'],$tracker_lang['voted'],'success');
} else {

	stderr($tracker_lang['error'],$tracker_lang['already_rated']);
}
?>