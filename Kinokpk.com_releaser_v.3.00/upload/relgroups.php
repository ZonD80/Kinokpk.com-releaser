<?php
/**
 * Release groups listing and viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();

getlang('relgroups');

$id = (int)$_GET['id'];
$sort = (string) $_GET['sort'];
$action=(string) $_GET['action'];

$allowedsort = array('ratingsum','users','added','private');

if ($sort && !in_array($sort,$allowedsort)) stderr($tracker_lang['error'],$tracker_lang['ivalid_sort']);

if (!$sort) $sort = 'ratingsum';
foreach ($allowedsort as $asort) {
	$up[$asort] = (isset($_GET['up']) && ($sort==$asort))?'':'&amp;up';
}

if (!$id) {
	if ($action=='invite') {
		die ('TODO: listing invites to all relgroups');
	}
	$res = sql_query("SELECT relgroups.id,name,added,spec,image,owners,members,ratingsum,private,page_pay,subscribe_length, COUNT(rg_subscribes.id) AS users FROM relgroups LEFT JOIN rg_subscribes ON relgroups.id=rg_subscribes.rgid GROUP BY relgroups.id ORDER BY $sort ".($up[$sort]?"DESC":"ASC")) or sqlerr(__FILE__,__LINE__);
	$uidsarray = array();
	while ($row=mysql_fetch_array($res)) {
		$rgarray[] = $row;
		$uidsarray[$row['id']] = $row['owners'];
		if ($row['members']) $memarray[$row['id']] = $row['members'];
	}
	if (!$rgarray) stderr($tracker_lang['error'],$tracker_lang['no_relgroups']);
	//var_dump($memarray);
	/* $ownres = sql_query("SELECT id,username,class FROM users WHERE id IN(".implode(',',($memarray?array_merge($uidsarray,$memarray):$uidsarray)).")") or sqlerr(__FILE__,__LINE__);
	while ($owner = mysql_fetch_array($ownres)) {
	if (in_array($owner['id'],$uidsarray))
	$owners[$owner['id']] = "<a href=\"userdetails.php?id={$owner['id']}\">".get_user_class_color($owner['class'],$owner['username'])."</a>";
	else
	$members[$owner['id']] = "<a href=\"userdetails.php?id={$owner['id']}\">".get_user_class_color($owner['class'],$owner['username'])."</a>";

	}*/
	stdhead($tracker_lang['relgroups']);
	begin_frame($tracker_lang['relgroups']);
	print("<table width=\"100%\">");
	foreach ($rgarray as $row) {
		/*   $rgown=array();
		 $rgmemb=array();

		 $row['owners']=explode(',',$row['owners']);
		 foreach ($row['owners'] as $owner){
		 $rgown[] = $owners[$owner];
		 }

		 $rgown=implode(', ',$rgown);

		 if ($row['members']) {
		 $row['members']=explode(',',$row['members']);

		 foreach ($row['members'] as $member){
		 $rgmemb[] = $members[$member];
		 }

		 $rgmemb=implode(', ',$rgmemb);
		 } else $rgmemb = $tracker_lang['no'];

		 */
		?>

<div id="relgroups" class="relgroups_table">
<div id="relgroups_image" class="relgroups_image"><img
	src="<?=$row['image']?>" title="<?=makesafe($row['name'])?>" /><? $tracker_lang['no_image']?>
</div>
<div class="relgroups_name">
<dl class="clearfix">
	<dt>Название</dt>
	<dd class="result_name"><a href="relgroups.php?id=<?=$row['id']?>"><?=makesafe($row['name']).($row['private']?' (Приватная)':'') ?></a></dd>
	<?php if ($row['page_pay'] || $row['private']) {?>
	<dt>Подписано</dt>
	<dd><?=(int)$row['users']?> человек</dd>
	<dt>Информация о подписке</dt>
	<dd><?=($row['page_pay']?$tracker_lang['pay_required']:$tracker_lang['no_pay']).' ('.($row['subscribe_length']?$row['subscribe_length'].' дней':$tracker_lang['lifetime']).')'?></dd>
	<?php }?>
	<dt>Специализация</dt>
	<dd><?=makesafe($row['spec'])?></dd>
</dl>
</div>
<div id="input" class="relgroups_input">

<ul class="relgroups_input">
	<li><a href="relgroups.php?id=<?=$row['id']?>">Просмотр</a></li>
	<?php
	if ($row['private']) {
		$i_subscribed = mysql_fetch_row(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid={$row['id']}"));

		$open_relgroup=false;
	}
	else $open_relgroup= true;
	if ($open_relgroup) {}
	elseif ($i_subscribed) print ("<li><a href=\"relgroups.php?id={$row['id']}&amp;action=deny\">Отписаться от группы</a></li><li><a href=\"relgroups.php?id={$row['id']}&action=invite\">{$tracker_lang['create_invite']}</a></li>");
	else print ("<li>".(($row['private']&&$row['only_invites'])?$tracker_lang['private_group_friend_subscribe']:"<a href=\"".($row['page_pay']?$row['page_pay']:"relgroups.php?id={$row['id']}&action=suggest")."\">Подписаться на релизы</a>")."</li>");
	?>
</ul>
</div>


</div>
	<?

	}
	print('</table>');
	end_frame();
	stdfoot();
}
else {
	$res = sql_query("SELECT relgroups.*, (SELECT SUM(1) FROM rg_subscribes WHERE rgid=$id) AS users, (SELECT 1 FROM rg_subscribes WHERE rgid=$id AND userid={$CURUSER['id']}) AS relgroup_allowed, (SELECT SUM(1) FROM torrents WHERE relgroup=$id) AS releases FROM relgroups WHERE id=$id GROUP BY id") or sqlerr(__FILE__,__LINE__);
	$row = mysql_fetch_array($res);
	if (!$row) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);


	if (!$row['relgroup_allowed'] && $row['private'] && (get_user_class() < UC_MODERATOR)) stderr($tracker_lang['error'],$tracker_lang['no_access_priv_rg']);

	if(!$action) {

		stdhead(sprintf($tracker_lang['relgroup_title'],$row['name'],$row['spec']));
			
		$ownersres = sql_query("SELECT id, username, class FROM users WHERE id IN(".$row['owners'].($row['members']?','.$row['members']:'').")") or sqlerr(__FILE__,__LINE__);
		if ($row['members']) $row['members'] = explode(',',$row['members']);
			
		while($ownersrow = mysql_fetch_assoc($ownersres)) if ($row['members'] && in_array($ownersrow['id'],$row['members'])) $members[$ownersrow['id']] = "<a href=\"userdetails.php?id={$ownersrow['id']}\">".get_user_class_color($ownersrow['class'],$ownersrow['username'])."</a>"; else $owners[$ownersrow['id']] = "<a href=\"userdetails.php?id={$ownersrow['id']}\">".get_user_class_color($ownersrow['class'],$ownersrow['username'])."</a>";

		$I_OWNER = in_array($CURUSER['id'],explode(',',$row['owners']));
		if (!$owners) die($tracker_lang['error_no_onwers']);
			
		$ownersview = implode(', ',$owners);
		if ($members) $membersview = implode(', ',$members); else $membersview=$tracker_lang['no'];
		// print('<table width="100%">');
		?>

<div id="relgroups_header" class="relgroups_header">
<div align="center"><?=makesafe($row['name']) ?>&nbsp;&nbsp;<?   print(ratearea($row['ratingsum'],$row['id'],'relgroups',($I_OWNER?$row['id']:0))."");?></div>
<div align="right" style="margin-top: -22px;"><a href="relgroups.php"><img
	src="/themes/kinokpk/images/strelka.gif" border="0"
	title="Вернуться к просмотру групп"
	style="margin-top: 5px; margin-right: 5px;" /></a></div>

</div>
<div id="relgroups_table_right">
<div id="relgroups_image" class="relgroups_image_right">
<div class="relgroups_avatar_right"><img src="<?=$row['image']?>"
	title="<?=makesafe($row['name'])?>" /><? $tracker_lang['no_image']?>

<div id="input_right" class="relgroups_input_right"><? //print (int)$row['users'].'&nbsp;';
		//ПЕРЕДЕЛАТЬ
		//$i_subscribed = @mysql_result(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid=$id"),0);
		if ($row['private'])
		$i_subscribed = mysql_fetch_row(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid={$row['id']}"));
		if ($i_subscribed) print ("<li><a href=\"relgroups.php?id={$row['id']}&amp;action=deny\">Отписаться от группы</a></li><li><a href=\"relgroups.php?id={$row['id']}&action=invite\">{$tracker_lang['create_invite']}</a></li>");
		else print ("<li>".(($row['private']&&$row['only_invites'])?$tracker_lang['private_group_friend_subscribe']:"<a href=\"".($row['page_pay']?$row['page_pay']:"relgroups.php?id=$id&action=suggest")."\">Подписаться на релизы</a>")."</li>");
		?></div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right">
<h3 class="box_right_block"><span>Директор</span><?print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd><?=$ownersview?></dd>

</div>
</div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right_adm">
<h3 class="box_right_block"><span>Состав Группы</span><? print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd><?=$membersview?></dd>

</div>
</div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right_adm">
<h3 class="box_right_block"><span>Тип Группы</span><? print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd><?php
	if ($row['page_pay']) print $tracker_lang['pay_required'];
	elseif ($row['private']) print $tracker_lang['private'];
	else print $tracker_lang['no_pay'];?></dd>

</div>
</div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right_adm">
<h3 class="box_right_block"><span>Группы Друзья</span><?print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd>Нет</dd>

</div>
</div>
</div>
</div>
</div>


<div id="relgroups_table_left">
<div id="boxes_left" class="box_left">
<div id="box_left" style="margin-top: 9px;">
<h3 class="box_right_left"><span>Информация о группе</span><?print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="basic_infor_summary_list box_left_page">
<dl class="infor_left">
	<dt>Название</dt>
	<dd><b><?=makesafe($row['name']) ?></b></dd>
	<dt>Специализация</dt>
	<dd><?=$row['spec']?></dd>
	<dt><?=$tracker_lang['description']?></dt>
	<dd><?=$row['descr']?></dd>
	<dt><?=$tracker_lang['releases']?></dt>
	<dd><?=($row['releases']?$row['releases'].' [<a href="browse.php?relgroup='.$row['id'].'">'.$tracker_lang['view'].'</a>]':'0')?></dd>


</dl>
</div>
</div>
	<?php if ($row['private']) {
		$subscribessql = sql_query("SELECT rg_subscribes.userid, users.class, users.username FROM rg_subscribes LEFT JOIN users ON rg_subscribes.userid=users.id WHERE rg_subscribes.rgid=$id ORDER BY rg_subscribes.id DESC LIMIT 10");
		while ($rgsubs = mysql_fetch_assoc($subscribessql)) {
			$rgusers[] = "<a href=\"userdetails.php?id={$rgsubs['userid']}\">".get_user_class_color($rgsubs['class'],$rgsubs['username'])."</a>";
		}
		if ($rgusers) { $rgusers = implode(', ',$rgusers);
		?>
<div id="box_left" class="box_left" style="margin-top: 9px;">
<h3 class="box_right_left"><span>Подписчики (последние 10)</span> <?print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="basic_infor_summary_list box_left_page">
<dl class="infor_left">
<?=$rgusers?>
</dl>
</div>
</div>
<?php }
	}?>
<div id="box_left" class="box_left" style="margin-top: 9px;">
<h3 class="box_right_left"><span>Обсуждение последних релизов</span></h3>
	<?print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"rgnews.php?id=$id\">{$tracker_lang['create']}</a>" : "").'</div>');

	$resource = $CACHE->get('relgroups-'.$id, 'newsquery');

	if ($resource===false) {

		$resource = array();
		$resourcerow = sql_query("SELECT rgnews.* , COUNT(rgnewscomments.id) AS numcomm FROM rgnews LEFT JOIN rgnewscomments ON rgnewscomments.rgnews = rgnews.id WHERE rgnews.relgroup=$id GROUP BY rgnews.id ORDER BY rgnews.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);
		while ($res = mysql_fetch_array($resourcerow))
		$resource[] = $res;

		$CACHE->set('relgroups-'.$id, 'newsquery', $resource);
	}

	if ($resource) {
		$content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">\n";
		foreach($resource as $array) {
			if ($news_flag == 0) {
				$content .=
										  "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable unfolded\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div style=\"display: block;\" class=\"sp-body\">".format_comment($array['body']);
				$content .="<hr/><div align=\"right\">";
				if ((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) {
					$content .= "[<a href=\"rgnews.php?action=edit&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']. "?id=$id")."\"><b>E</b></a>]";
					$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"rgnews.php?action=delete&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$id")."\"><b>D</b></a>] ";
				}
				$content .= "Комментариев: ".$array['numcomm']." [<a href=\"rgnewsoverview.php?id=".$array['id']."#comments\">Комментировать</a>]</div>";
				$content .= "</div></div>";
				$news_flag = 1;
			} else {
				$content .=
										  "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div class=\"sp-body\">".format_comment($array['body']);
				$content .="<hr/><div align=\"right\">";
				if ((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) {
					$content .= "[<a href=\"rgnews.php?action=edit&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$id")."\"><b>E</b></a>]";
					$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"rgnews.php?action=delete&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$id")."\"><b>D</b></a>] ";
				}
				$content .= "Комментариев: ".$array['numcomm']." [<a href=\"rgnewsoverview.php?id=".$array['id']."\">Комментировать</a>]</div>";
				$content .= "</div></div>";
			}
		}
		$content .= "<p align=\"right\">[<a href=\"rgnewsarchive.php?id=$id\">Архив новостей</a>]</p></td></tr></table>\n";
	} else {
		$content .= "<table class=\"main\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\" border=\"0\">";
		$content .= "<div align=\"center\"><h3>".$tracker_lang['no_news']."</h3></div>\n";
		$content .= "</td></tr></table>";
	}
	print $content;


	?></div>
</div>
<div id="box_left_wall" class="box_left"
	style="margin-top: 9px; margin-bottom: 5px;">
<h3 class="box_right_left"><span>Комментарии к Группе</span><? print('<div align="center">'.(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></a></h3>
	<?php
	begin_frame();

	$count = get_row_count("rgcomments","WHERE relgroup=$id");

	$limited = 10;

	if (!$count) {
		print("<table id=\"comments-table\" class=\"rgcomm\" cellspacing=\"0\" cellPadding=\"5\">");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев | <b><u>Новости комментируются отдельно</u></b></div>");
		print("<div align=\"right\">Добавить комментарий</div>");
		print("</td></tr><tr><td align=\"center\">");
		print("Комментариев нет. Желаете добавить?");
		print("</td></tr></table>");

	}
	else {
		list($pagertop, $pagerbottom, $limit) = pager($limited, $count, "relgroups.php?id=$id&",array('lastpagedefault' => 1));

		$subres = sql_query("SELECT c.id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
												  "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, s.time AS last_access, e.username AS editedbyname FROM rgcomments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id  LEFT JOIN sessions AS s ON s.uid=u.id WHERE relgroup = " .
												  "$id GROUP BY c.id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
		$allrows = array();
		while ($subrow = mysql_fetch_array($subres)) {
			$subrow['subject'] = $row['name'];
			$subrow['link'] = "relgroups.php?id=$id#comm{$subrow['id']}";
			$allrows[] = $subrow;
		}


		print("<table id=\"comments-table\" class=\"rgcomm\" cellspacing=\"0\" cellPadding=\"5\">");
		print("<tr><td class=\"colhead_rgcomm\" align=\"center\">");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев | <b><u>Новости комментируются отдельно</u></b></div>");
		print("<div align=\"right\"><b>{$tracker_lang['add_comment']}</b></div>");
		print("</td></tr>");

		print("<tr><td>");
		print($pagertop);
		print("</td></tr>");
		print("<tr><td>");
		commenttable($allrows,'rgcomment');
		print("</td></tr>");
		print("<tr><td>");

		print($pagerbottom);
		print("</td></tr>");
		print("</table>");

	}

	print ( "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>::{$tracker_lang['add_comment']} к релиз группе | ".is_i_notified($id,'rgcomments')."</b></td></tr>");
	print ( "<tr><td width=\"100%\" align=\"center\" >" );
	//print("Ваше имя: ");
	//print("".$CURUSER['username']."<p>");
	print ( "<form name=comment method=\"post\" action=\"rgcomment.php?action=add\">" );
	print ( "<table width=\"100%\"><tr><td align=\"center\">" . textbbcode ( "text") . "</td></tr>" );

	print ( "<tr><td  align=\"center\">" );
	print ( "<input type=\"hidden\" name=\"uid\" value=\"$id\"/>" );
	print ( "<input type=\"submit\" class=\"btn button\" style=\"margin-top:5px;\" value=\"Разместить комментарий\" />" );
	print ( "</td></tr></table></form>" );


	/*
	 print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	 print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>::{$tracker_lang['add_comment']} к релиз группе | ".is_i_notified($id,'rgcomments')."</b></td></tr>");
	 print("<tr><td id=\"comments1\"  class=\"edittd\" align=\"center\" style=\" overflow:hidden; height:385px;\"");
	 print ( "<form name=comment method=\"post\" action=\"rgcomment.php?action=add\">" );
	 print ( "<table width=\"100%\"><tr><td align=\"center\">" . textbbcode ( "text") . "</td></tr>" );

	 print ( "<tr><td  align=\"center\">" );
	 print ( "<input type=\"hidden\" name=\"uid\" value=\"$id\"/>" );
	 print ( "<input type=\"submit\" class=\"btn button\" style=\"margin-top:5px;\" value=\"Разместить комментарий\" />" );
	 print ( "</td></tr></table></form>" );

	 print('</td></tr></table>');
	 //print("</tr></table>");
	 */
	print("</table>");

	?></div>
</div>
</div>

	<?

	if (array_key_exists($CURUSER['id'],$owners)) $I_OWNER=true;

	//print('<div align="center">'.$tracker_lang['news'].' '.$tracker_lang['relgroups'].(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"rgnews.php?id=$id\"><b>".$tracker_lang['create']."</b></a>]</font>" : "").'</div>');

	$resource = $CACHE->get('relgroups-'.$id, 'newsquery');

	if ($resource===false) {

		$resource = array();
		$resourcerow = sql_query("SELECT rgnews.* , SUM(1) AS numcomm FROM rgnews LEFT JOIN rgnewscomments ON rgnewscomments.rgnews = rgnews.id WHERE rgnews.relgroup=$id GROUP BY rgnews.id ORDER BY rgnews.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);
		while ($res = mysql_fetch_array($resourcerow))
		$resource[] = $res;

		$CACHE->set('relgroups-'.$id, 'newsquery', $resource);
	}

	if ($resource) {
		$content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">\n";
		foreach($resource as $array) {
			if ($news_flag == 0) {
				$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable unfolded\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div style=\"display: block;\" class=\"sp-body\">".format_comment($array['body']);
				$content .="<hr/><div align=\"right\">";
				if ((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) {
					$content .= "[<a href=\"rgnews.php?action=edit&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF']. "?id=$id")."\"><b>E</b></a>]";
					$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"rgnews.php?action=delete&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$id")."\"><b>D</b></a>] ";
				}
				$content .= "Комментариев: ".$array['numcomm']." [<a href=\"rgnewsoverview.php?id=".$array['id']."#comments\">Комментировать</a>]</div>";
				$content .= "</div></div>";
				$news_flag = 1;
			} else {
				$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div class=\"sp-body\">".format_comment($array['body']);
				$content .="<hr/><div align=\"right\">";
				if ((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) {
					$content .= "[<a href=\"rgnews.php?action=edit&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$id")."\"><b>E</b></a>]";
					$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"rgnews.php?action=delete&amp;id=$id&amp;newsid=" . $array['id'] . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$id")."\"><b>D</b></a>] ";
				}
				$content .= "Комментариев: ".$array['numcomm']." [<a href=\"rgnewsoverview.php?id=".$array['id']."\">Комментировать</a>]</div>";
				$content .= "</div></div>";
			}
		}
		$content .= "<p align=\"right\">[<a href=\"rgnewsarchive.php?id=$id\">Архив новостей</a>]</p></td></tr></table>\n";
	} else {
		$content .= "<table class=\"main\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">";
		$content .= "<div align=\"center\"><h3>".$tracker_lang['no_news']."</h3></div>\n";
		$content .= "</td></tr></table>";

		end_frame();
	}
	print '</table>';
	stdfoot();
	}
	elseif ($action=='suggest') {
		//die('fuck!');
		$invitecode = htmlspecialchars(trim((string)($_GET['invitecode']?$_GET['invitecode']:$_POST['invitecode'])));

		if (!$row) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
		if ($row['page_pay']) safe_redirect($row['page_pay'],0);
		//if ($row['only_invites'] && !$invitecode) stderr($tracker_lang['error'],$tracker_lang['only_invites_enabled']);
		if (!$row['private']) stderr($tracker_lang['error'],$tracker_lang['subscribe_unneeded']);

		if ($_SERVER['REQUEST_METHOD']<>'POST') {
			$message = '';
			if (!$row['only_invites'])
			$message .= sprintf($tracker_lang['join_notice'],$row['name'],($row['subscribe_length']?"{$row['subscribe_length']} дней":$tracker_lang['lifetime']),$row['amount'],$CURUSER['discount']);
			else
			$message .= sprintf($tracker_lang['join_by_invite'],$row['name'],($row['subscribe_length']?"{$row['subscribe_length']} дней":$tracker_lang['lifetime']),$invitecode,$row['name']);
			$message .= "<br/><div align=\"center\"><form action=\"relgroups.php?id=$id&action=suggest\" method=\"POST\"><input type=\"hidden\" name=\"invitecode\" value=\"$invitecode\"><input type=\"submit\" value=\"{$tracker_lang['continue']}\"></form><hr/><form action=\"relgroups.php?id=$id&action=suggest\" method=\"POST\">{$tracker_lang['enter_invite_code']}<input type=\"text\" name=\"invitecode\" size=\"32\" maxlength=\"32\" value=\"$invitecode\">&nbsp;<input type=\"submit\" value=\"{$tracker_lang['continue']}\"></form></div>";
			stderr($tracker_lang['rginvite_my'],$message,'success');
		}

		else {
			if ($invitecode) {
				$check = @mysql_result(sql_query("SELECT 1 FROM rg_invites WHERE invite=".sqlesc($invitecode)));
				if (!$check)
				stderr($tracker_lang['error'],$tracker_lang['invalid_invite_code']);
				sql_query("DELETE FROM invites WHERE invite=".sqlesc($invitecode));
			}
			else {
				if ($CURUSER['discount']<$row['amount']) stderr($tracker_lang['error'],$tracker_lang['no_discount']);
				sql_query("UPDATE users SET discount=discount-{$row['amount']} WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
			}
			sql_query("INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES ({$CURUSER['id']},$id,{$row['subscribe_length']}*86400)");
			if (mysql_errno()==1062) stderr($tracker_lang['error'],$tracker_lang['fail_invite']);

			safe_redirect("relgroups.php?id=$id",1);
			stderr($tracker_lang['success'],$tracker_lang['success_invite'],'success');

		}
	}
	elseif ($action=='deny') {
		sql_query("DELETE FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid=$id");
		safe_redirect('relgroups.php',1);
		stderr($tracker_lang['rginvite_deny'],$tracker_lang['deny_success'],'success');
	}
	elseif ($action=='invite') {

		if (!$row) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
		if ($row['page_pay']) safe_redirect($row['page_pay'],0);
		if (!$row['private']) stderr($tracker_lang['error'],$tracker_lang['subscribe_unneeded']);
		if ($_SERVER['REQUEST_METHOD']<>'POST') {

			stdhead($tracker_lang['invite_code']." \"{$row['name']}\"");
			begin_frame($tracker_lang['invite_code']." \"{$row['name']}\"");
			$invsql = sql_query("SELECT invite,time_invited FROM rg_invites WHERE rgid=$id AND inviter={$CURUSER['id']}");
			print ("<table width=\"100%\"><tr><td class=\"colhead\">{$tracker_lang['invite_code']}</td><td class=\"colhead\">{$tracker_lang['invite_added']}</td><td class=\"colhead\">{$tracker_lang['invite_per']}</td><td class=\"colhead\">{$tracker_lang['invite_link']}</td></tr>");
			while ($invite = mysql_fetch_assoc($invsql)) {
				print ("<tr><td><strong>{$invite['invite']}</strong></td><td>".mkprettytime($invite['time_invited'])."</td><td>".mkprettytime($invite['time_invited']+($row['subscribe_length']*86400))."</td><td><input type=\"text\" value=\"{$REL_CONFIG['defaultbaseurl']}/relgroups.php?id=$id&action=suggest&invitecode={$invite['invite']}\" onclick=\"javascript:this.select();\"></td></tr>");
			}
			print ('</table><div align="center"><form action="relgroups.php?id='.$id.'&action=invite" method="POST"><input type="submit" value="'.$tracker_lang['create_invite'].'"></form></div>');
			end_frame();
			stdfoot();
			die();
		} else {
			if (!isset($_GET['ok'])) {
				stderr($tracker_lang['invite_code'],sprintf($tracker_lang['invite_notice'],$row['name'],($row['subscribe_length']?"{$row['subscribe_length']} дней":$tracker_lang['lifetime']),$row['amount'],$CURUSER['discount'])
				. "<br/><div align=\"center\"><form action=\"relgroups.php?id=$id&action=invite&ok\" method=\"POST\"><input type=\"submit\" value=\"{$tracker_lang['continue']}\"></form></div>",'success');
			} else {
				if ($CURUSER['discount']<$row['amount']) stderr($tracker_lang['error'],$tracker_lang['no_discount_invite']);
				$code = md5(microtime()+rand(0,100));
				sql_query("INSERT INTO rg_invites (inviter,rgid,invite,time_invited) VALUES ({$CURUSER['id']},$id,'$code',".time().")") or sqlerr(__FILE__,__LINE__);
				sql_query("UPDATE users SET discount=discount-{$row['amount']} WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
				safe_redirect("relgroups.php?id=$id&action=invite",1);
				stderr($tracker_lang['success'],sprintf($tracker_lang['inivite_code_created'],$code,$row['name']),'success');

			}
		}

	}

	else stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
}

?>