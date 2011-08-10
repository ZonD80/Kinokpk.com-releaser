<?php
/**
 * Userdetails
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

INIT();

loggedinorreturn ();


function bark($msg) {
	global $REL_LANG, $REL_TPL;
	$REL_TPL->stdhead( $REL_LANG->say_by_key('error') );
	$REL_TPL->stdmsg ( $REL_LANG->say_by_key('error'), $msg );
	$REL_TPL->stdfoot();
	exit ();
}

$id = ( int ) $_GET ["id"];

if (! is_valid_id ( $id )) $id = $CURUSER['id'];

$r = @sql_query ( "SELECT * FROM users WHERE id=$id" ) or sqlerr ( __FILE__, __LINE__ );
$user = mysql_fetch_array ( $r ) or bark ( "Нет пользователя с таким ID $id." );
if (! $user ["confirmed"])
stderr ( $REL_LANG->say_by_key('error'), "Этот пользователь еще не подтвердил себя по e-mail, возможно, этот аккаунт вскоре будет удален" );

$user['custom_privileges'] = explode(',',$user['custom_privileges']);

if (!pagercheck()) {
	$cats = assoc_cats ();
	$am_i_friend = ($id==$CURUSER['id']?true:@mysql_result(sql_query("SELECT 1 FROM friends WHERE (userid={$CURUSER['id']} AND friendid=$id) OR (friendid={$CURUSER['id']} AND userid=$id) AND confirmed=1"),0));
	$disallow_view = ($user['privacy']=='highest'&&!$am_i_friend);
	if ($disallow_view&&!get_privilege('view_private_user_profiles',false)) stderr($REL_LANG->_("Error"),$REL_LANG->_('This user uses privacy level, you need to <a href="%s">Become friend of %s</a> to view this page',$REL_SEO->make_link('friends','action','add','id',$id),get_user_class_color($user['class'],$user['username'])));

	$it = sql_query ( "SELECT u.id, u.username, u.class, i.id AS invitedid, i.username AS invitedname, i.class AS invitedclass FROM users AS u LEFT JOIN users AS i ON i.id = u.invitedby WHERE u.invitedroot = $id OR u.invitedby = $id ORDER BY u.invitedby" );
	if (mysql_num_rows ( $it ) >= 1) {
		while ( $inviter = mysql_fetch_array ( $it ) )
		$invitetree .= "<a href=\"".$REL_SEO->make_link('userdetails','id',$inviter['id'],'username',translit($inviter ["username"]))."\">" . get_user_class_color ( $inviter ["class"], $inviter ["username"] ) . "</a> приглашен <a href=\"".$REL_SEO->make_link('userdetails','id',$inviter['invitedid'],'username',translit($inviter ["invitedname"]))."\">" . get_user_class_color ( $inviter ["invitedclass"], $inviter ["invitedname"] ) . "</a><br />";

	}

	if ($user ["ip"] && (get_privilege('is_moderator',false) || $user ["id"] == $CURUSER ["id"])) {
		$ip = $user ["ip"];
		$dom = @gethostbyaddr ( $user ["ip"] );
		if ($dom == $user ["ip"] || @gethostbyname ( $dom ) != $user ["ip"])
		$addr = $ip;
		else {
			$dom = strtoupper ( $dom );
			$domparts = explode ( ".", $dom );
			$domain = $domparts [count ( $domparts ) - 2];
			if ($domain == "COM" || $domain == "CO" || $domain == "NET" || $domain == "NE" || $domain == "ORG" || $domain == "OR")
			$l = 2;
			else
			$l = 1;
			$addr = "$ip ($dom)";
		}
	}

	if (! $user [added])
	$joindate = 'N/A';
	else
	$joindate = mkprettytime ( $user ['added'] ) . " (" . get_elapsed_time ( $user ["added"] ) . " " . $REL_LANG->say_by_key('ago') . ")";
	$lastseen = $user ["last_access"];
	if (! $lastseen)
	$lastseen = $REL_LANG->say_by_key('never');
	else {
		$lastseen = mkprettytime ( $lastseen ) . " (" . get_elapsed_time ( $lastseen ) . " " . $REL_LANG->say_by_key('ago') . ")";
	}
	// social activity
	$presents = array();
	$presentres = sql_query("SELECT presents.*, users.username, users.class FROM presents LEFT JOIN users ON presents.presenter=users.id WHERE userid=$id ORDER BY id DESC LIMIT 5");
	while ($prrow = mysql_fetch_assoc($presentres)) {
		$presents[] = $prrow;
	}
	$allowed_types = array ('relcomments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments', 'friends','seeding','leeching','downloaded','uploaded','presents','nicknames');

	foreach ($allowed_types as $type) {
		switch ($type) {
			case 'friends' : $addition = "(friendid={$id} OR userid={$id}) AND confirmed=1"; break;
			case 'seeding' : $sql_query[] = "(SELECT SUM(1) FROM peers WHERE seeder=1 AND userid=$id) AS seeding"; $noq=true; break;
			case 'leeching' : $sql_query[] = "(SELECT SUM(1) FROM peers WHERE seeder=0 AND userid=$id) AS leeching"; $noq=true; break;
			case 'downloaded' : $sql_query[] = "(SELECT SUM(1) FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE userid=$id AND torrents.owner<>$id) AS downloaded"; $noq=true; break;
			case 'uploaded' : $sql_query[] = "(SELECT SUM(1) FROM torrents WHERE owner=$id) AS uploaded"; $noq=true; break;
			case 'presents' : $sql_query[] = "(SELECT SUM(1) FROM presents WHERE userid=$id) AS presents"; $noq=true; break;
			case 'nicknames' : $sql_query[] = "(SELECT SUM(1) FROM nickhistory WHERE userid=$id) AS nicknames"; $noq=true; break;
		}
		if (!$noq) {
			$string = (($type!='friends')?"user = $id AND type='".preg_replace('/comments/','',$type)."'":'').$addition;

			$sql_query[]="(SELECT SUM(1) FROM ".(preg_match('/comments/',$type)?'comments':$type)." WHERE $string) AS $type";
		}
		unset($addition);
		unset($string);
	}
	$sql_query = "SELECT ".implode(', ', $sql_query);

	//die($sql_query);
	$socialsql = sql_query($sql_query);
	$social = mysql_fetch_assoc($socialsql);
	foreach ($social as $type => $value)
	{
		if ($type=='presents') $soctable.= "{$REL_LANG->_("User presents")}: ".($value?"<a href=\"{$REL_SEO->make_link('userhistory','id',$id,'type','presents')}\">$value</a>":$REL_LANG->say_by_key('no')).'<br/>';
		else
		$soctable .= "{$REL_LANG->say_by_key('social_'.$type)}: ".($value?"<a href=\"".$REL_SEO->make_link('userhistory','id',$id,'type',$type)."\">$value</a>":$REL_LANG->say_by_key('no')).'<br/>';
	}

	// social activity end

	//if ($user['donated'] > 0)
	//  $don = "<img src=pic/starbig.gif>";


	$res = sql_query ( "SELECT name, flagpic FROM countries WHERE id = $user[country] LIMIT 1" ) or sqlerr ( __FILE__, __LINE__ );
	if (mysql_num_rows ( $res ) == 1) {
		$arr = mysql_fetch_assoc ( $res );
		$country = "<img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" style=\"margin-left: 8pt\">";
	}

	//if ($user["donor"] == "yes") $donor = "<td class=embedded><img src=pic/starbig.gif alt='Donor' style='margin-left: 4pt'></td>";
	//if ($user["warned"] == "yes") $warned = "<td class=embedded><img src=pic/warnedbig.gif alt='Warned' style='margin-left: 4pt'></td>";


	if ($user ["gender"] == "1")
	$gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\">";
	elseif ($user ["gender"] == "2")
	$gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\">";
	elseif ($user ["gender"] == "3")
	$gender = "N/A";

	///////////////// BIRTHDAY MOD /////////////////////
	if ($user [birthday] != "0000-00-00") {
		//$current = date("Y-m-d", time());
		$current = date ( "Y-m-d", time () + $CURUSER ['tzoffset'] * 60 );
		list ( $year2, $month2, $day2 ) = explode ( '-', $current );
		$birthday = $user ["birthday"];
		$birthday = date ( "Y-m-d", strtotime ( $birthday ) );
		list ( $year1, $month1, $day1 ) = explode ( '-', $birthday );
		if ($month2 < $month1) {
			$age = $year2 - $year1 - 1;
		}
		if ($month2 == $month1) {
			if ($day2 < $day1) {
				$age = $year2 - $year1 - 1;
			} else {
				$age = $year2 - $year1;
			}
		}
		if ($month2 > $month1) {
			$age = $year2 - $year1;
		}

	}
	///////////////// BIRTHDAY MOD /////////////////////


	$REL_TPL->stdhead( "Просмотр профиля " . $user ["username"] );
	$enabled = $user ["enabled"] == 1;
	if ($disallow_view&&get_privilege('view_private_user_profiles',false)) print "<p>".$REL_LANG->_("You are viewing private profile as administration member")."</p>";
	print ( '<table width="100%"><tr><td width="100%" style="vertical-align: top;">' );

	$REL_TPL->begin_main_frame ();

	print ( "<tr><td colspan=\"2\" align=\"center\">".($user['avatar']&&$CURUSER['avatars']?"<br/><img src=\"{$user['avatar']}\" title=\"{$REL_LANG->_("Avatar of %s",$user['username'])}\"/><br/>":'')."<p><h1 style=\"margin:0px\">$user[username]" . get_user_icons ( $user, true ) . "</h1>" . reportarea ( $id, 'users' ) . "</p>\n" );

	if (! $enabled)
	print ( "<p><b>Этот аккаунт отключен</b><br/>Причина: " . $user ['dis_reason'] . "</p>\n" );
	elseif ($CURUSER ["id"] != $user ["id"]) {
		$r = sql_query ( "SELECT id FROM friends WHERE (userid=$id AND friendid={$CURUSER['id']}) OR (friendid = $id AND userid={$CURUSER['id']})" ) or sqlerr ( __FILE__, __LINE__ );
		list ( $friend ) = mysql_fetch_array ( $r );
		if ($friend)
		print ( "<p>(<a href=\"".$REL_SEO->make_link('friends','action','deny','id',$friend)."\">Убрать из друзей</a>)<br />(<a href=\"".$REL_SEO->make_link('present','id',$id)."\">Подарить подарок</a>)</p>\n" );
		else {
			print ( "<p>(<a href=\"".$REL_SEO->make_link('friends','action','add','id',$id)."\">Добавить в друзья</a>)</p>\n" );
		}
	}
	print ( "<p>" . ratearea ( $user ['ratingsum'], $user ['id'], 'users', $CURUSER['id'] ) . "$country</p>" );

	print ( '<div class="sp-wrap"><div class="sp-head folded clickable">'.$REL_LANG->_("Open information").'</div><div class="sp-body"><table width=100% border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead width=1%>Зарегистрирован</td><td align=left width=99%>' . $joindate . '</td></tr>
<tr><td class=rowhead>Последний раз был на трекере</td><td align=left>' . $lastseen . '</td></tr>' );


	print ( "<tr><td class=\"rowhead\">{$REL_LANG->say_by_key('comments_and_social')}</td>" );

	print ( "<td align=\"left\">$soctable</td></tr>\n" );

	if (get_privilege('send_emails',false))
	print ( "<tr><td class=\"rowhead\">Email</td><td align=\"left\"><a href=\"mailto:$user[email]\">$user[email]</a></td></tr>\n" );
	if ($addr)
	print ( "<tr><td class=\"rowhead\">IP</td><td align=\"left\">$addr</td></tr>\n" );

	if (get_privilege('add_invites',false))
	print ( "<tr><td class=\"rowhead\">Приглашений</td><td align=left><a href=\"".$REL_SEO->make_link('invite','id',$id)."\">" . $user ["invites"] . "</a></td></tr>" );
	if ($user ["invitedby"] != 0) {
		$inviter = mysql_fetch_assoc ( sql_query ( "SELECT username FROM users WHERE id = " . sqlesc ( $user ["invitedby"] ) ) );
		print ( "<tr><td class=\"rowhead\">Пригласил</td><td align=\"left\"><a href=\"".$REL_SEO->make_link('userdetails','id',$user['invitedby'],'username',translit($inviter['username']))."\">$inviter[username]</a></td></tr>" );
	}
	//}
	if ($user ["icq"] || $user ["msn"] || $user ["aim"] || $user ["yahoo"] || $user ["skype"]) {
		?>
<tr>
	<td class=rowhead><b>Связь</b></td>
	<td align=left><?
	if ($user ["icq"])
	print ( "<img src=\"http://web.icq.com/whitepages/online?icq=" . ( int ) $user [icq] . "&amp;img=5\" alt=\"icq\" border=\"0\" /> " . ( int ) $user [icq] . " <br />\n" );
	if ($user ["msn"])
	print ( "<img src=\"pic/contact/msn.gif\" alt=\"msn\" border=\"0\" /> " . makesafe ( $user [msn] ) . "<br />\n" );
	if ($user ["aim"])
	print ( "<img src=\"pic/contact/aim.gif\" alt=\"aim\" border=\"0\" /> " . makesafe ( $user [aim] ) . "<br />\n" );
	if ($user ["yahoo"])
	print ( "<img src=\"pic/contact/yahoo.gif\" alt=\"yahoo\" border=\"0\" /> " . makesafe ( $user [yahoo] ) . "<br />\n" );
	if ($user ["skype"])
	print ( "<img src=\"pic/contact/skype.gif\" alt=\"skype\" border=\"0\" /> " . makesafe ( $user [skype] ) . "<br />\n" );
	if ($user ["mirc"])
	print ( "<img src=\"pic/contact/mirc.gif\" alt=\"mirc\" border=\"0\" /> " . makesafe ( $user [mirc] ) . "\n" );
	?></td>
</tr>
	<?
	}
	if ($user ["website"])
	print ( "<tr><td class=\"rowhead\">Сайт</td><td align=\"left\">" . makesafe ( $user [website] ) . "</a></td></tr>\n" );
	print ( "<tr><td class=\"rowhead\">Класс</td><td align=\"left\"><b>" . get_user_class_color ( $user ["class"], get_user_class_name ( $user ["class"] ) ) . ($user ["title"] != "" ? " / <span style=\"color: purple;\">{$user["title"]}</span>" : "") . "</b></td></tr>\n" );
	print ( "<tr><td class=\"rowhead\">Пол</td><td align=\"left\">$gender</td></tr>\n" );
	//мод предупреждений
	print ( "<tr><td class=\"rowhead\">Уровень<br />предупреждений</td><td align=\"left\">" );
	for($i = 0; $i < $user ["num_warned"]; $i ++) {
		$img .= "<img src=\"pic/star_warned.gif\" alt=\"Уровень предупреждений\" title=\"Уровень предупреждений\">";
	}
	if (! $img)
	$img = "Нет предупреждений";
	print ( $img . ((($CURUSER ['id'] == $id) && ($CURUSER ['num_warned'] != 0)) ? " <a href=\"".$REL_SEO->make_link('mywarned')."\">Купить помилование за аплоад</a>" : "") . "</td></tr>\n" );

	if ($user ["birthday"] != '0000-00-00') {
		print ( "<tr><td class=\"rowhead\">Возраст</td><td align=\"left\">" . AgeToStr ( $age ) . "</td></tr>\n" );
		$birthday = date ( "d.m.Y", strtotime ( $birthday ) );
		print ( "<tr><td class=\"rowhead\">Дата Рождения</td><td align=\"left\">$birthday</td></tr>\n" );

		$month_of_birth = substr ( $user ["birthday"], 5, 2 );
		$day_of_birth = substr ( $user ["birthday"], 8, 2 );
		for($i = 0; $i < count ( $zodiac ); $i ++) {
			if (($month_of_birth == substr ( $zodiac [$i] [2], 3, 2 ))) {
				if ($day_of_birth >= substr ( $zodiac [$i] [2], 0, 2 )) {
					$zodiac_img = $zodiac [$i] [1];
					$zodiac_name = $zodiac [$i] [0];
				} else {
					if ($i == 11) {
						$zodiac_img = $zodiac [0] [1];
						$zodiac_name = $zodiac [0] [0];
					} else {
						$zodiac_img = $zodiac [$i + 1] [1];
						$zodiac_name = $zodiac [$i + 1] [0];
					}
				}
			}

		}

		print ( "<tr><td class=\"rowhead\">Знак зодиака</td><td align=\"left\"><img src=\"pic/zodiac/" . $zodiac_img . "\" alt=\"" . $zodiac_name . "\" title=\"" . $zodiac_name . "\"></td></tr>\n" );

	}


	if ($invitetree)
	print ( "<tr valign=\"top\"><td colspan=\"2\"><div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">Приглашенные</div><div class=\"sp-body\">$invitetree</div></div></td></tr>\n" );

	if ($user ["info"])
	print ( "<tr valign=\"top\"><td align=\"left\" colspan=\"2\" class=\"text\" bgcolor=\"#F4F4F0\">" . format_comment ( $user ["info"] ) . "</td></tr>\n" );

	if ($user ["acceptpms"] == "friends") {
		$r = sql_query ( "SELECT id FROM friends WHERE userid = $user[id] AND friendid = $CURUSER[id]" ) or sqlerr ( __FILE__, __LINE__ );
		$showpmbutton = (mysql_num_rows ( $r ) == 1 ? 1 : 0);
	} elseif ($CURUSER ["id"] != $user ["id"])
	$showpmbutton = 1;

	if ($showpmbutton)
	print ( "<tr><td align=right><b>Связь</b></td><td align=center><form method=\"get\" action=\"".$REL_SEO->make_link('message')."\">
        <input type=\"hidden\" name=\"receiver\" value=" . $user ["id"] . "> 
        <input type=\"hidden\" name=\"action\" value=\"sendmessage\"> 
        <input type=submit value=\"Послать ЛС\" style=\"height: 23px\"> 
        </form>" . ((get_privilege('send_emails',false)) ? "<form method=\"get\" action=\"".$REL_SEO->make_link('email-gateway')."\">
        <input type=\"hidden\" name=\"id\" value=\"" . $user ["id"] . "\">
        <input type=submit value=\"Послать e-mail\" style=\"height: 23px\">
        </form>" : '') . "</td></tr>" );

	print ( "</table></div>\n" );
	print ( '</td><td>' );

	$REL_TPL->begin_frame ();

	if ($presents) {
		$switch_pr = array('torrent'=>'Release','ratingsum'=>"Amount of rating",'discount'=>"Amount of discount");
		print ( "<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
		print ( "<tr><td class=\"colhead\" align=\"center\">" );
		print ( "<div style=\"float: left; width: auto;\" align=\"left\">{$REL_LANG->_("Last 5 user presents")}</div>" );
		print ( "<div align=\"right\"><a href=\"{$REL_SEO->make_link('userhistory','id',$id,'type','presents')}\">{$REL_LANG->_("View all")}</a></div>" );
		print ( "</td></tr>" );
		print ( "<tr><td>" );
		print ('<table width="100%"><tr>');
		foreach ($presents as $present) {
			$prtext = strip_tags($present['msg']);
			if (mb_strlen($prtext>30)) $prtext = substr($prtext,0,30).'...';
			print '<td align="center"><small>'.$REL_LANG->_($switch_pr[$present['type']]).'</small><br/><a href="'.$REL_SEO->make_link('present','a','viewpresent','id',$present['id']).'"><img style="border:none;" src="pic/presents/'.$present['type'].'_small.png" titie="'.$REL_LANG->_('Present').'"/></a><br/><small>'.($present['presenter']<>$CURUSER['id']?$REL_LANG->_("From").' <a href="'.$REL_SEO->make_link('userdetails','id',$present['presenter'],'username',$present['username']).'">'.get_user_class_color($present['class'],$present['username']).'</a><br/>':$REL_LANG->_("Yours").'<br/>').$REL_LANG->_("With wish of").': '.($prtext?$prtext:$REL_LANG->_("None")).'</small></td>';

		}
		print ('</tr>');
		print ('</td></tr></table>');
	}

}


$REL_TPL->assignByRef('to_id',$id);
$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $id, 'usercomments' ));
$REL_TPL->assign('textbbcode',textbbcode('text'));
$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('User'));
$FORM_TYPE = 'user';
$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
$REL_TPL->display('commenttable_form.tpl');

$subres = sql_query ( "SELECT SUM(1) FROM comments WHERE toid = $id AND type='user'" );
$subrow = mysql_fetch_array ( $subres );
$count = $subrow [0];

if (! $count) {

	print ('<div id="newcomment_placeholder">'. "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
	print ( "<tr><td class=colhead align=\"left\" colspan=\"2\">" );
	print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: {$REL_LANG->_("Comments list")}</div>" );
	print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit($user['username']))."#comments\" class=altlink_white>Добавить комментарий</a></div>" );
	print ( "</td></tr><tr><td align=\"center\">" );
	print ( "Комментариев нет. <a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit($user['username']))."#comments\">Желаете добавить?</a>" );
	print ( "</td></tr></table><br /></div>" );

} else {
	$limit = ajaxpager(25, $count, array('userdetails','id',$id,'username',translit($user['username'])), 'comments-table > tbody:last');
	//var_dump($count);
	$subres = sql_query ( "SELECT c.type, c.id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.info, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, s.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id  LEFT JOIN sessions AS s ON s.uid=u.id WHERE c.toid = " . "$id AND c.type='user' GROUP BY (c.id) ORDER BY c.id DESC $limit" ) or sqlerr ( __FILE__, __LINE__ );
	$allrows = prepare_for_commenttable($subres, $user['username'],$REL_SEO->make_link('userdetails','id',$id,'username',translit($user['username'])));
	if (!pagercheck()) {
		print ( "<div id=\"pager_scrollbox\"><table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
		print ( "<tr><td class=\"colhead\" align=\"center\">" );
		print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>" );
		print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit($user['username']))."#comments\" class=\"altlink_white\">{$REL_LANG->_('Add comment (%s)',$REL_LANG->_('User'))}</a></div>" );
		print ( "</td></tr>" );
		
		print ( "<tr><td>" );
		commenttable ( $allrows);
		print ( "</td></tr>" );

		print ( "</table></div>" );
	} else { 	print ( "<tr><td>" );
	commenttable ( $allrows);
	print ( "</td></tr>" ); die(); }
}

print '</table>';
$REL_TPL->end_frame ();

if (get_privilege('edit_users',false) && get_class_priority($user ["class"]) < get_class_priority(get_user_class ())) {
	$REL_TPL->begin_frame ( "Редактирование пользователя", true );
	print ( "<form method=\"post\" action=\"".$REL_SEO->make_link('modtask')."\">\n" );
	print ( "<input type=\"hidden\" name=\"action\" value=\"edituser\">\n" );
	print ( "<input type=\"hidden\" name=\"userid\" value=\"$id\">\n" );
	print ( "<input type=\"hidden\" name=\"returnto\" value=\"".$REL_SEO->make_link('userdetails','id',$id,'username',$user['username'])."\">\n" );
	print ( "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" );
	print ( "<tr><td class=\"rowhead\">Заголовок</td><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"title\" value=\"" . htmlspecialchars ( $user [title] ) . "\"></td></tr>\n" );
	print ( "<tr><td class=\"rowhead\">Удалить аватар</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"avatar\" value=\"1\"></td></tr>\n" );
	// we do not want mods to be able to change user classes or amount donated...
	if (!get_privilege('is_administrator',false))
	print ( "<input type=\"hidden\" name=\"donor\" value=\"$user[donor]\">\n" );
	else {
		print ( "<tr><td class=\"rowhead\">Донор</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"donor\" value=\"1\"" . ($user ["donor"] ? " checked" : "") . ">Да <input type=\"radio\" name=\"donor\" value=\"0\"" . (! $user ["donor"] ? " checked" : "") . ">Нет</td></tr>\n" );
	}

	if (get_privilege('edit_users',false) && get_class_priority($user["class"]) > get_class_priority(get_user_class ()))
	print ( "<input type=\"hidden\" name=\"class\" value=\"$user[class]\">\n" );
	else {

		print ( "<tr><td class=\"rowhead\">Класс</td><td colspan=\"2\" align=\"left\">".make_classes_select('class',$user['class'],get_user_class())."</td></tr>\n" );
	}
	print ( "<tr><td class=\"rowhead\">Сбросить день рождения</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"resetb\" value=\"1\">Да<input type=\"radio\" name=\"resetb\" value=\"0\" checked>Нет</td></tr>\n" );
	$modcomment = makesafe ( $user ["modcomment"] );
	$supportfor = makesafe ( $user ["supportfor"] );
	print ( "<tr><td class=rowhead>Поддержка</td><td colspan=2 align=left><input type=radio name=support value=\"1\"" . ($user ["supportfor"] ? " checked" : "") . ">Да <input type=radio name=support value=\"0\"" . (! $user ["supportfor"] ? " checked" : "") . ">Нет</td></tr>\n" );
	print ( "<tr><td class=rowhead>Поддержка для:</td><td colspan=2 align=left><textarea cols=60 rows=6 name=supportfor>$supportfor</textarea></td></tr>\n" );
	print ( "<tr><td class=rowhead>История пользователя</td><td colspan=2 align=left><textarea cols=60 rows=6" . (get_privilege('add_comments_to_user',false) ? " readonly" : " name=modcomment") . ">$modcomment</textarea></td></tr>\n" );
	$warned = $user ["warned"] == 1;

	print ( "<tr><td class=\"rowhead\"" . (! $warned ? " rowspan=\"2\"" : "") . ">Предупреждение</td>
 	<td align=\"left\" width=\"20%\">" . ($warned ? "<input name=\"warned\" value=\"1\" type=\"radio\" checked>Да<input name=\"warned\" value=\"0\" type=\"radio\">Нет" : "Нет") . "</td>" );

	if ($warned) {
		$warneduntil = $user ['warneduntil'];
		if (! $warneduntil)
		print ( "<td align=\"center\">На неограниченый срок</td></tr>\n" );
		else {
			print ( "<td align=\"center\">До " . mkprettytime ( $warneduntil ) );
			print ( " (" . get_elapsed_time ( $warneduntil ) . " осталось)</td></tr>\n" );
		}
	} else {
		print ( "<td>Предупредить на <select name=\"warnlength\">\n" );
		print ( "<option value=\"0\">------</option>\n" );
		print ( "<option value=\"1\">1 неделю</option>\n" );
		print ( "<option value=\"2\">2 недели</option>\n" );
		print ( "<option value=\"4\">4 недели</option>\n" );
		print ( "<option value=\"8\">8 недель</option>\n" );
		print ( "<option value=\"255\">Неограничено</option>\n" );
		print ( "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Комментарий в ЛС:</td></tr>\n" );
		print ( "<tr><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"warnpm\"></td></tr>" );
	}
	if (get_privilege('edit_users',false) && get_class_priority($user ["class"]) < get_class_priority(get_user_class ())) {
		print ( "<tr><td class=\"rowhead\" rowspan=\"2\">Включен</td><td colspan=\"2\" align=\"left\"><input name=\"enabled\" value=\"1\" type=\"radio\"" . ($enabled ? " checked" : "") . ">Да <input name=\"enabled\" value=\"0\" type=\"radio\"" . (! $enabled ? " checked" : "") . ">Нет</td></tr>\n" );
		if ($enabled)
		print ( "<tr><td colspan=\"2\" align=\"left\">Причина отключения:&nbsp;<input type=\"text\" name=\"disreason\" size=\"60\" /></td></tr>" );
		else
		print ( "<tr><td colspan=\"2\" align=\"left\">Причина включения:&nbsp;<input type=\"text\" name=\"enareason\" size=\"60\" /></td></tr>" );}
		?>
<script type="text/javascript">

function togglepic(bu, picid, formid)
{
    var pic = document.getElementById(picid);
    var form = document.getElementById(formid);
    
    if(pic.src == bu + "/pic/plus.gif")
    {
        pic.src = bu + "/pic/minus.gif";
        form.value = "minus";
    }else{
        pic.src = bu + "/pic/plus.gif";
        form.value = "plus";
    }
}

</script>
		<?php
		print ( "<tr><td class=\"rowhead\">Изменить рейтинг</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"ratingpic\" onClick=\"togglepic('{$REL_CONFIG['defaultbaseurl']}','ratingpic','ratingchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountrating\" size=\"10\" /><td>Сейчас рейтинга у пользователя: {$user['ratingsum']}</td></tr>" );
		print ( "<tr><td class=\"rowhead\">Изменить откуп</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"discountpic\" onClick=\"togglepic('{$REL_CONFIG['defaultbaseurl']}','discountpic','discountchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountdiscount\" size=\"10\" /><td>Сейчас откупа у пользователя: {$user['discount']}</td></tr>" );
		print ( "<tr><td class=\"rowhead\">Сбросить passkey</td><td colspan=\"2\" align=\"left\"><input name=\"resetkey\" value=\"1\" type=\"checkbox\"></td></tr>\n" );
		if (!get_privilege('delete_site_users',false))
		print ( "<input type=\"hidden\" name=\"deluser\">" );
		else
		print ( "<tr><td class=\"rowhead\">Удалить</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"deluser\"></td></tr>" );
		print ( "</td></tr>" );
		if (get_privilege('edit_user_privileges',false)) {
			print ( "<tr><td colspan=\"3\" align=\"center\">{$REL_LANG->_("Edit custom user privileges (these priveleges will be added to default for user class)")}</td></tr>\n" );
				$priority = get_class_priority();
				$priority2 = get_class_priority($user['class']);
				$classes = init_class_array();
				foreach ($classes as $cid=>$cl) 
					if ($cl['priority']<=$priority2||$cl['priority']>=$priority||!is_int($cid)) unset($classes[$cid]); else $classes[$cid] = "FIND_IN_SET($cid,classes_allowed)";
					
				$privs = $REL_DB->query_return("SELECT name,description,classes_allowed FROM privileges WHERE ".implode(' OR ',$classes));
				if ($privs) {
					print ("<tr><td colspan=\"3\"><div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">{$REL_LANG->_("Open information")}</div><div class=\"sp-body\"><table border=\"1\"><tr><td class=\"colhead\">{$REL_LANG->_("Check")}<!-- | {$REL_LANG->_("All")}: <input type=\"checkbox\" name=\"allprivs\" value=\"1\"/>--></td><td class=\"colhead\">{$REL_LANG->_("Description")}</td></tr>");
				foreach ($privs as $p) {
					if (!in_array($user['class'],explode(',',$p['classes_allowed'])))
					print "<tr><td><input type=\"checkbox\" name=\"privileges[]\" value=\"{$p['name']}\"".(in_array($p['name'],$user['custom_privileges'])?' checked':'')."></td><td>{$REL_LANG->_($p['description'])}</td></tr>";
				}
				print "</table></div></td></tr>";
				}
		print ( "<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" class=\"btn\" value=\"ОК\"></td></tr>\n" );
		print ( "</table>\n" );
		print ( "<input type=\"hidden\" id=\"ratingchange\" name=\"ratingchange\" value=\"plus\"><input type=\"hidden\" id=\"discountchange\" name=\"discountchange\" value=\"plus\"><input type=\"hidden\" id=\"upchange\" name=\"upchange\" value=\"plus\"><input type=\"hidden\" id=\"downchange\" name=\"downchange\" value=\"plus\">\n" );
		print ( "</form>\n" );
		$REL_TPL->end_frame ();
				//$privs = 
}
}

set_visited('users',$id);
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();
?>