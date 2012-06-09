<?php
/**
 * Invites
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

INIT();


loggedinorreturn();

if (isset($_GET['id']) && !is_valid_id($_GET['id'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

$id = (int) $_GET["id"];
$type = unesc($_GET["type"]);
$invite = (string)$_GET["invite"];

$REL_TPL->stdhead($REL_LANG->_('Invitations'));

if ($id == 0) {
	$id = $CURUSER["id"];
}

if ($type == 'new') {
	print("<form method=post action=\"".$REL_SEO->make_link('takeinvite')."\">".
	"<input type=hidden name=id value=$id />".
	"<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=2><b>{$REL_LANG->_('Create new invitation')}</b></td></tr>".
	"<tr class=tableb><td align=center colspan=2>{$REL_LANG->say_by_key('invite_notice_get')}</td></tr>");
	tr($REL_LANG->_('Enter email invitation will be sent to'),'<input type="text" size="40" name="email">',1,1);
	if ($REL_CONFIG['use_captcha']) {

		require_once('include/recaptchalib.php');
		tr ($REL_LANG->_('Are you human?'),recaptcha_get_html($REL_CONFIG['re_publickey']),1,1);

	}

	print (	"<tr class=tableb><td align=center colspan=2><input type=submit value=\"{$REL_LANG->_('Create')}\"></td></tr>".
	"</form></table>");
} elseif ($type == 'del') {
	$ret = $REL_DB->query("SELECT * FROM invites WHERE invite = ".sqlesc($invite));
	$num = mysql_fetch_assoc($ret);
	if ($num[inviter]==$id) {
		$REL_DB->query("DELETE FROM invites WHERE invite = ".sqlesc($invite));
		$REL_TPL->stdmsg($REL_LANG->_('Successful'), $REL_LANG->_('Invitation deleted'));
	} else
	$REL_TPL->stdmsg($REL_LANG->_('Error'), $REL_LANG->_('You are not allowed to delete invitations'));
} else {
	if (!get_privilege('is_moderator') && !($id == $CURUSER["id"])) {
		$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('You are not allowed to view invitations of this user'));
	}


	$ret = $REL_DB->query("SELECT u.id, u.username, u.class, u.email, u.ratingsum, u.warned, u.enabled, u.donor, invites.confirmed FROM invites LEFT JOIN users AS u ON u.id=invites.inviteid WHERE invitedby = $id");
	$num = mysql_num_rows($ret);
	print("<form method=post action=\"".$REL_SEO->make_link('takeconfirm','id',$id)."\"><table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=7><b>{$REL_LANG->_('Invitations history')}</b> (".(int)$num.")</td></tr>");

	if(!$num) {
		print("<tr class=tableb><td colspan=7>{$REL_LANG->_('Looks like you invited nobody')}</tr>");
	} else {
		print("<tr class=tableb><td><b>{$REL_LANG->_('Username')}</b></td><td><b>Email</b></td><td><b>{$REL_LANG->_('Rating')}</b></td><td><b>{$REL_LANG->_('Class')}</b></td>");
		if ($CURUSER[id] == $id || get_privilege('approve_invites',false))
		print("<td align=center><b>{$REL_LANG->_('Confirm')}</b></td>");
		print("</tr>");
		for ($i = 0; $i < $num; ++$i) {
			$arr = mysql_fetch_assoc($ret);
			if (!$arr[confirmed])
			$user = "<td align=left>$arr[username]</td>";
			else
			$user = "<td align=left>".make_user_link($arr)."</td>";

			$ratio = (($arr['ratingsum']>0)?"+{$arr['ratingsum']}":$arr['ratingsum']);

			if ($arr["confirmed"])
			$status = "<a href=\"".$REL_SEO->make_link('userdetails','id',$arr['id'],'username',translit($arr['username']))."\"><font color=green>{$REL_LANG->_('Confirmed')}</font></a>";
			else
			$status = "<font color=red>{$REL_LANG->_('Unconfirmed')}</font>";

			print("<tr class=tableb>$user<td>$arr[email]</td><td>$ratio</td><td>$status</td>");

			if ($CURUSER[id] == $id || get_privilege('approve_invites',false)) {
				print("<td align=center>");
				if (!$arr[confirmed])
				print("<input type=\"checkbox\" name=\"conusr[]\" value=\"" . $arr[id] . "\" />");
				print("</td>");
			}
			print("</tr>");
		}
	}
	if ($CURUSER[id] == $id || get_privilege('approve_invites',false)) {
		print("<input type=hidden name=email value=$arr[email]>");
		print("<tr class=tableb><td colspan=7 align=right><input type=submit value=\"{$REL_LANG->_('Confirm users, add rating and add them to friends')}!\"></form></td></tr>");
	}
	print("</table><br />");

	$rul = $REL_DB->query("SELECT SUM(1) FROM invites WHERE inviter = $id");
	$arre = mysql_fetch_row($rul);
	$number1 = $arre[0];
	$rer = $REL_DB->query("SELECT invite, time_invited FROM invites WHERE inviter = $id AND confirmed=0");
	$num1 = mysql_num_rows($rer);

	print("<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=6><b>{$REL_LANG->_('Current invitations')}</b> ($number1)</td></tr>");

	if(!$num1) {
		print("<tr class=tableb><td colspan=6>{$REL_LANG->_('Looks like you did not create any invitation yet')}</tr>");
	} else {
		print("<tr class=tableb><td><b>{$REL_LANG->_('Invitation code')}</b></td><td><b>{$REL_LANG->_('Created at')}</b></td><td>{$REL_LANG->_('Delete')}</td></tr>");
		for ($i = 0; $i < $num1; ++$i) {
			$arr1 = mysql_fetch_assoc($rer);
			print("<tr class=tableb><td>$arr1[invite]</td><td>".mkprettytime($arr1['time_invited'])."</td>");
			print ("<td><a href=\"".$REL_SEO->make_link('invite','invite',$arr1['invite'],'type','del')."\" onClick=\"return confirm('{$REL_LANG->_('Are you sure?')}')\">{$REL_LANG->_('Delete')}</a></td></tr>");
		}
	}

	print("<tr class=tableb><td colspan=7 align=center><form method=get action=\"".$REL_SEO->make_link('invite','id',$id,'type','new')."\"><input type='hidden' name='id' value='$id' /><input type='hidden' name='type' value='new' /><input type=submit value=\"{$REL_LANG->_('Create new invitation')}\"></form></td></tr>");
	print("</table>");
}
$REL_TPL->stdfoot();

?>
