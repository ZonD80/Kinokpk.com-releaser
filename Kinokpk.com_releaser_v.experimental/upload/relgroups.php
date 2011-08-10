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
INIT();
loggedinorreturn();



$id = (int)$_GET['id'];
$sort = (string) $_GET['sort'];
$action=(string) $_GET['action'];

$allowedsort = array('ratingsum','users','added','private');

if ($sort && !in_array($sort,$allowedsort)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('ivalid_sort'));

if (!$sort) $sort = 'ratingsum';
foreach ($allowedsort as $asort) {
	$up[$asort] = (isset($_GET['up']) && ($sort==$asort))?'':'&amp;up';
}

if (!$id) {
	if ($action=='invite') {
		die ('TODO: listing invites to all relgroups');
	}
	$res = sql_query("SELECT relgroups.id,name,added,spec,image,"/*owners,members,*/."ratingsum,private,page_pay,subscribe_length, COUNT(rg_subscribes.id) AS users FROM relgroups LEFT JOIN rg_subscribes ON relgroups.id=rg_subscribes.rgid GROUP BY relgroups.id ORDER BY $sort ".($up[$sort]?"DESC":"ASC")) or sqlerr(__FILE__,__LINE__);
	$uidsarray = array();
	while ($row=mysql_fetch_array($res)) {
		$rgarray[] = $row;
		//$uidsarray[$row['id']] = $row['owners'];
		//if ($row['members']) $memarray[$row['id']] = $row['members'];
	}
	if (!$rgarray) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_relgroups'));

	$REL_TPL->stdhead($REL_LANG->say_by_key('relgroups'));
	$REL_TPL->begin_frame($REL_LANG->say_by_key('relgroups'));
	print("<table width=\"100%\">");
	foreach ($rgarray as $row) {

		?>

<div class="relgroups_table">
<div class="relgroups_image"><img src="<?=$row['image']?>"
	title="<?=makesafe($row['name'])?>" /><? $REL_LANG->say_by_key('no_image')?>
</div>
<div class="relgroups_name">
<dl class="clearfix">
	<dt>Название</dt>
	<dd class="result_name"><a
		href="<?=$REL_SEO->make_link('relgroups','id',$row['id']);?>"><?=makesafe($row['name']).($row['private']?' (Приватная)':'') ?></a></dd>
		<?php if ($row['page_pay'] || $row['private']) {?>
	<dt>Подписано</dt>
	<dd><?=(int)$row['users']?> человек</dd>
	<dt>Информация о подписке</dt>
	<dd><?=($row['page_pay']?$REL_LANG->say_by_key('pay_required'):$REL_LANG->say_by_key('no_pay')).' ('.($row['subscribe_length']?$row['subscribe_length'].' дней':$REL_LANG->say_by_key('lifetime')).')'?></dd>
	<?php }?>
	<dt>Специализация</dt>
	<dd><?=makesafe($row['spec'])?></dd>
</dl>
</div>
<div class="relgroups_input">

<ul class="relgroups_input">
	<li><a href="<?=$REL_SEO->make_link('relgroups','id',$row['id']);?>">Просмотр</a></li>
	<?php
	if ($row['private']) {
		$i_subscribed = mysql_fetch_row(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid={$row['id']}"));

		$open_relgroup=false;
	}
	else $open_relgroup= true;
	if ($open_relgroup) {}
	elseif ($i_subscribed) print ("<li><a href=\"".$REL_SEO->make_link('relgroups','id',$row['id'],'action','deny')."\">Отписаться от группы</a></li><li><a href=\"".$REL_SEO->make_link('relgroups','id',$row['id'],'action','invite')."\">{$REL_LANG->say_by_key('create_invite')}</a></li>");
	else print ("<li>".(($row['private']&&$row['only_invites'])?$REL_LANG->say_by_key('private_group_friend_subscribe'):"<a href=\"".($row['page_pay']?$row['page_pay']:$REL_SEO->make_link('relgroups','id',$row['id'],'action','suggest'))."\">Подписаться на релизы</a>")."</li>");
	?>
</ul>
</div>


</div>
	<?

	}
	print('</table>');
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
}
else {
	$res = sql_query("SELECT relgroups.*, (SELECT SUM(1) FROM rg_subscribes WHERE rgid=$id) AS users, (SELECT 1 FROM rg_subscribes WHERE rgid=$id AND userid={$CURUSER['id']}) AS relgroup_allowed, (SELECT SUM(1) FROM torrents WHERE relgroup=$id) AS releases FROM relgroups WHERE id=$id GROUP BY id") or sqlerr(__FILE__,__LINE__);
	$row = mysql_fetch_array($res);
	if (!$row) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));


	if(!$action) {
	if (!$row['relgroup_allowed'] && $row['private'] && (!get_privilege('access_to_private_relgroups',false))) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_access_priv_rg'));
		
		if (!pagercheck()) {
			$REL_TPL->stdhead(sprintf($REL_LANG->say_by_key('relgroup_title'),$row['name'],$row['spec']));

			if ($row['owners']||$row['members']) {
				$ownersres = sql_query("SELECT id, username, class, donor, warned, enabled FROM users WHERE id IN(".$row['owners'].($row['members']?','.$row['members']:'').")") or sqlerr(__FILE__,__LINE__);
				if ($row['members']) $row['members'] = explode(',',$row['members']);

				while($ownersrow = mysql_fetch_assoc($ownersres)) if ($row['members'] && in_array($ownersrow['id'],$row['members'])) $members[$ownersrow['id']] = make_user_link($ownersrow); else $owners[$ownersrow['id']] = make_user_link($ownersrow);

				$I_OWNER = (in_array($CURUSER['id'],explode(',',$row['owners']))||(get_privilege('edit_relgroups',false)));
			}
			if ($owners) //die($REL_LANG->say_by_key('error_no_onwers'));
			$ownersview = implode(', ',$owners); else $ownersview = $REL_LANG->say_by_key('from_system');

			if ($members) $membersview = implode(', ',$members); else $membersview=$REL_LANG->say_by_key('no');
			// print('<table width="100%">');
			?>

<div id="relgroups_header" class="relgroups_header">
<div align="center"><?=makesafe($row['name']) ?>&nbsp;&nbsp;<?   print(ratearea($row['ratingsum'],$row['id'],'relgroups',($I_OWNER?$row['id']:0))."");?></div>
<div align="right" style="margin-top: -22px;"><a
	href="<?=$REL_SEO->make_link('relgroups');?>"><img
	src="/themes/<?php print $REL_CONFIG['ss_uri'];?>/images/strelka.gif"
	border="0" title="Вернуться к просмотру групп"
	style="margin-top: 5px; margin-right: 5px;" /></a></div>

</div>
<div id="relgroups_table_right">
<div id="relgroups_image" class="relgroups_image_right">
<div class="relgroups_avatar_right"><img src="<?=$row['image']?>"
	title="<?=makesafe($row['name'])?>" /><? $REL_LANG->say_by_key('no_image')?>

<div id="input_right" class="relgroups_input_right"><? //print (int)$row['users'].'&nbsp;';
			//ПЕРЕДЕЛАТЬ
			//$i_subscribed = @mysql_result(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid=$id"),0);
			if ($row['private'])
			$i_subscribed = mysql_fetch_row(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid={$row['id']}"));
			if ($i_subscribed) print ("<li><a href=\"".$REL_SEO->make_link('relgroups','id',$row['id'],'action','deny')."\">Отписаться от группы</a></li><li><a href=\"".$REL_SEO->make_link('relgroups','id',$row['id'],'action','invite')."\">{$REL_LANG->say_by_key('create_invite')}</a></li>");
			else print ("<li>".(($row['private']&&$row['only_invites'])?$REL_LANG->say_by_key('private_group_friend_subscribe'):"<a href=\"".($row['page_pay']?$row['page_pay']:$REL_SEO->make_link('relgroups','id',$id,'action','suggest'))."\">Подписаться на релизы</a>")."</li>");
			?></div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right">
<h3 class="box_right_block"><span>Директор</span><?print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd><?=$ownersview?></dd>

</div>
</div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right_adm">
<h3 class="box_right_block"><span>Состав Группы</span><? print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd><?=$membersview?></dd>

</div>
</div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right_adm">
<h3 class="box_right_block"><span>Тип Группы</span><? print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="inside basic_infor_summary_list">
<dl class="infor">
	<dd><?php
	if ($row['page_pay']) print $REL_LANG->say_by_key('pay_required');
	elseif ($row['private']) print $REL_LANG->say_by_key('private');
	else print $REL_LANG->say_by_key('no_pay');?></dd>

</div>
</div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right_adm">
<h3 class="box_right_block"><span>Группы Друзья</span><?print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
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
<h3 class="box_right_left"><span>Информация о группе</span><?print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="basic_infor_summary_list box_left_page">
<dl class="infor_left">
	<dt>Название</dt>
	<dd><b><?=makesafe($row['name']) ?></b></dd>
	<dt>Специализация</dt>
	<dd><?=$row['spec']?></dd>
	<dt><?=$REL_LANG->say_by_key('description')?></dt>
	<dd><?=$row['descr']?></dd>
	<dt><?=$REL_LANG->say_by_key('releases')?></dt>
	<dd><?=($row['releases']?$row['releases'].' [<a href="'.$REL_SEO->make_link('browse','relgroup',$row['id']).'">'.$REL_LANG->say_by_key('view').'</a>]':'0')?></dd>


</dl>
</div>
</div>
	<?php if ($row['private']) {
		$subscribessql = sql_query("SELECT rg_subscribes.userid, users.class, users.username, users.id, users.donor, users.warned, users.enabled FROM rg_subscribes LEFT JOIN users ON rg_subscribes.userid=users.id WHERE rg_subscribes.rgid={$id} ORDER BY rg_subscribes.id DESC LIMIT 10") or sqlerr(__FILE__,__LINE__);
		while ($rgsubs = mysql_fetch_assoc($subscribessql)) {
			$rgusers[] = make_user_link($rgsubs);
		}
		if ($rgusers) { $rgusers = implode(', ',$rgusers);
		?>
<div id="box_left" class="box_left" style="margin-top: 9px;">
<h3 class="box_right_left"><span>Участники (последние 10)</span> <?print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
<div class="basic_infor_summary_list box_left_page">
<dl class="infor_left">
<?=$rgusers?>
</dl>
</div>
</div>
<?php }
	}?>
<div id="box_left" class="box_left" style="margin-top: 9px;">
<h3 class="box_right_left"><span>Обсуждение последних новостей</span></h3>
	<?print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"".$REL_SEO->make_link('rgnews','id',$id)."\"></a>" : "").'</div>');

	$resource = $REL_CACHE->get('relgroups-'.$id, 'newsquery');

	if ($resource===false) {

		$resource = array();
		$resourcerow = sql_query("SELECT * FROM rgnews WHERE rgnews.relgroup=$id ORDER BY rgnews.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);
		while ($res = mysql_fetch_array($resourcerow))
		$resource[] = $res;

		$REL_CACHE->set('relgroups-'.$id, 'newsquery', $resource);
	}

	if ($resource) {
		$content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">\n";
		foreach($resource as $array) {
			if ($news_flag == 0) {
				$content .=
										  "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable unfolded\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div style=\"display: block;\" class=\"sp-body\">".format_comment($array['body']);
				$content .="<hr/><div align=\"right\">";
				if ((get_privilege('edit_relgroups',false)) || $I_OWNER) {
					$content .= "[<a href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link('relgroups', 'id',$id))."\"><b>E</b></a>]";
					$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link('relgroups', 'id',$id))."\"><b>D</b></a>] ";
				}
				$content .= "Комментариев: ".$array['comments']." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$array['id'])."#comments\">Комментировать</a>]</div>";
				$content .= "</div></div>";
				$news_flag = 1;
			} else {
				$content .=
										  "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div class=\"sp-body\">".format_comment($array['body']);
				$content .="<hr/><div align=\"right\">";
				if ((get_privilege('edit_relgroups',false)) || $I_OWNER) {
					$content .= "[<a href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link('relgroups', 'id',$id))."\"><b>E</b></a>]";
					$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link('relgroups', 'id',$id))."\"><b>D</b></a>] ";
				}
				$content .= "Комментариев: ".$array['comments']." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$array['id'])."\">Комментировать</a>]</div>";
				$content .= "</div></div>";
			}
		}
		$content .= "<p align=\"right\">[<a href=\"".$REL_SEO->make_link('rgnewsarchive','id',$id)."\">Архив новостей</a>]</p></td></tr></table>\n";
	} else {
		$content .= "<table class=\"main\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\" border=\"0\">";
		$content .= "<div align=\"center\"><h3>".$REL_LANG->say_by_key('no_news')."</h3></div>\n";
		$content .= "</td></tr></table>";
	}
	print $content;


	?></div>
</div>
<div id="box_left_wall" class="box_left"
	style="margin-top: 9px; margin-bottom: 5px;">
<h3 class="box_right_left"><span>Комментарии к Группе</span><? print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></a></h3>
	<?php
	$REL_TPL->begin_frame();

		}
		
		$REL_TPL->assignByRef('to_id',$id);
		$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $id, 'rgcomments' ));
		$REL_TPL->assign('textbbcode',textbbcode('text'));
		$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('Release group'));
		$FORM_TYPE = 'rg';
		$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
		$REL_TPL->display('commenttable_form.tpl');
		
		$count = get_row_count("comments","WHERE toid=$id AND type='rg'");

		if (!$count) {

			print('<div id="newcomment_placeholder">'."<table id=\"comments-table\" class=\"rgcomm\" cellspacing=\"0\" cellPadding=\"5\">");
			print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
			print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев | <b><u>Новости комментируются отдельно</u></b></div>");
			print("<div align=\"right\">Добавить комментарий</div>");
			print("</td></tr><tr><td align=\"center\">");
			print("Комментариев нет. Желаете добавить?");
			print("</td></tr></table></div>");

		}
		else {
			$limit = ajaxpager(25, $count, array('relgroups','id',$id), 'comments-table > tbody:last');
			$subres = sql_query("SELECT c.type, c.id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
												  "u.username, u.title, u.info, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, s.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id  LEFT JOIN sessions AS s ON s.uid=u.id WHERE c.toid = " .
												  "$id AND c.type='rg' GROUP BY c.id ORDER BY c.id DESC $limit") or sqlerr(__FILE__, __LINE__);
			$allrows = prepare_for_commenttable($subres,$row['name'],$REL_SEO->make_link('relgroups','id',$id));
			if (!pagercheck()) {
				print("<div id=\"pager_scrollbox\"><table id=\"comments-table\" class=\"rgcomm\" cellspacing=\"0\" cellPadding=\"5\">");
				print("<tr>
					<td class=\"colhead_rgcomm\" align=\"center\">");
				print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев | <b><u>Новости комментируются отдельно</u></b></div>");
				print("<div align=\"right\"><b>{$REL_LANG->_('Add comment (%s)',$REL_LANG->_('Release group'))}</b></div>");
				print("</td>
			</tr>");
				
				print("<tr><td>");
				commenttable($allrows);
				print("</td></tr>");
				print("</table></div>");
			} else {
				print("<tr><td>");
				commenttable($allrows);
				print("</td></tr>");
				die();
			}
		}


		?></div>
</div>
</div>

		<?

		if (@array_key_exists($CURUSER['id'],$owners) || get_privilege('edit_relgroups',false)) $I_OWNER=true;


		$resource = $REL_CACHE->get('relgroups-'.$id, 'newsquery');

		if ($resource===false) {

			$resource = array();
			$resourcerow = sql_query("SELECT * FROM rgnews WHERE rgnews.relgroup=$id ORDER BY rgnews.added DESC LIMIT 3") or sqlerr(__FILE__, __LINE__);
			while ($res = mysql_fetch_array($resourcerow))
			$resource[] = $res;

			$REL_CACHE->set('relgroups-'.$id, 'newsquery', $resource);
		}

		if ($resource) {
			$content .= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">\n";
			foreach($resource as $array) {
				if ($news_flag == 0) {
					$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable unfolded\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div style=\"display: block;\" class=\"sp-body\">".format_comment($array['body']);
					$content .="<hr/><div align=\"right\">";
					if ((get_privilege('edit_relgroups',false)) || $I_OWNER) {
						$content .= "[<a href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link(substr($_SERVER['PHP_SELF'], 0, (mb_strlen($_SERVER['PHP_SELF']) - 1), 'id',$id)))."\"><b>E</b></a>]";
						$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link(substr($_SERVER['PHP_SELF'], 0, (mb_strlen($_SERVER['PHP_SELF']) - 1), 'id',$id)))."\"><b>D</b></a>] ";
					}
					$content .= "Комментариев: ".$array['comments']." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$array['id'])."#comments\">Комментировать</a>]</div>";
					$content .= "</div></div>";
					$news_flag = 1;
				} else {
					$content .=
      "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>".mkprettytime($array['added'])."</i> - <b>".$array['subject']."</b></td></tr></table></div><div class=\"sp-body\">".format_comment($array['body']);
					$content .="<hr/><div align=\"right\">";
					if ((get_privilege('edit_relgroups',false)) || $I_OWNER) {
						$content .= "[<a href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link("relgroups.php", 'id',$id))."\"><b>E</b></a>]";
						$content .= "[<a onclick=\"return confirm('Вы уверены?');\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$id,'newsid',$array['id'],'returnto',$REL_SEO->make_link("relgroups","id",$id))."\"><b>D</b></a>] ";
					}
					$content .= "Комментариев: ".$array['comments']." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$array['id'])."\">Комментировать</a>]</div>";
					$content .= "</div></div>";
				}
			}
			$content .= "<p align=\"right\">[<a href=\"".$REL_SEO->make_link('rgnewsarchive','id',$id)."\">Архив новостей</a>]</p></td></tr></table>\n";
		} else {
			$content .= "<table class=\"main\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"text\">";
			$content .= "<div align=\"center\"><h3>".$REL_LANG->say_by_key('no_news')."</h3></div>\n";
			$content .= "</td></tr></table>";

			$REL_TPL->end_frame();
		}
		$REL_TPL->stdfoot();
	}
	elseif ($action=='suggest') {
		//die('fuck!');
		$invitecode = htmlspecialchars(trim((string)($_GET['invitecode']?$_GET['invitecode']:$_POST['invitecode'])));

		if (!$row) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
		if ($row['page_pay']) safe_redirect($row['page_pay'],0);
		//if ($row['only_invites'] && !$invitecode) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('only_invites_enabled'));
		if (!$row['private']) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('subscribe_unneeded'));

		if ($_SERVER['REQUEST_METHOD']<>'POST') {
			$message = '';
			if (!$row['only_invites'])
			$message .= sprintf($REL_LANG->say_by_key('join_notice'),$row['name'],($row['subscribe_length']?"{$row['subscribe_length']} дней":$REL_LANG->say_by_key('lifetime')),$row['amount'],$CURUSER['discount']);
			else
			$message .= sprintf($REL_LANG->say_by_key('join_by_invite'),$row['name'],($row['subscribe_length']?"{$row['subscribe_length']} дней":$REL_LANG->say_by_key('lifetime')),$invitecode,$row['name']);
			$message .= "<br/><div align=\"center\"><form action=\"".$REL_SEO->make_link('relgroups','id',$id,'action','suggest')."\" method=\"POST\"><input type=\"hidden\" name=\"invitecode\" value=\"$invitecode\"><input type=\"submit\" value=\"{$REL_LANG->say_by_key('continue')}\"></form><hr/><form action=\"".$REL_SEO->make_link('relgroups','id',$id,'action','suggest')."\" method=\"POST\">{$REL_LANG->say_by_key('enter_invite_code')}<input type=\"text\" name=\"invitecode\" size=\"32\" maxlength=\"32\" value=\"$invitecode\">&nbsp;<input type=\"submit\" value=\"{$REL_LANG->say_by_key('continue')}\"></form></div>";
			stderr($REL_LANG->say_by_key('rginvite_my'),$message,'success');
		}

		else {
			if ($invitecode) {
				$check = @mysql_result(sql_query("SELECT 1 FROM rg_invites WHERE invite=".sqlesc($invitecode)),0);
				if (!$check)
				stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_invite_code'));
				sql_query("DELETE FROM invites WHERE invite=".sqlesc($invitecode));
			}
			else {
				if ($CURUSER['discount']<$row['amount']) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_discount'));
				sql_query("UPDATE users SET discount=discount-{$row['amount']} WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
			}
			sql_query("INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES ({$CURUSER['id']},$id,{$row['subscribe_length']}*86400)");
			if (mysql_errno()==1062) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('fail_invite'));

			safe_redirect($REL_SEO->make_link('relgroups','id',$id),1);
			stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('success_invite'),'success');

		}
	}
	elseif ($action=='deny') {
		sql_query("DELETE FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid=$id");
		safe_redirect($REL_SEO->make_link('relgroups'),1);
		stderr($REL_LANG->say_by_key('rginvite_deny'),$REL_LANG->say_by_key('deny_success'),'success');
	}
	elseif ($action=='invite') {

		if (!$row) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
		if ($row['page_pay']) safe_redirect($row['page_pay'],0);
		if (!$row['private']) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('subscribe_unneeded'));
		if ($_SERVER['REQUEST_METHOD']<>'POST') {

			$REL_TPL->stdhead($REL_LANG->say_by_key('invite_code')." \"{$row['name']}\"");
			$REL_TPL->begin_frame($REL_LANG->say_by_key('invite_code')." \"{$row['name']}\"");
			$invsql = sql_query("SELECT invite,time_invited FROM rg_invites WHERE rgid=$id AND inviter={$CURUSER['id']}");
			print ("<table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->say_by_key('invite_code')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('invite_added')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('invite_per')}</td><td class=\"colhead\">{$REL_LANG->say_by_key('invite_link')}</td></tr>");
			while ($invite = mysql_fetch_assoc($invsql)) {
				print ("<tr><td><strong>{$invite['invite']}</strong></td><td>".mkprettytime($invite['time_invited'])."</td><td>".($row['subscribe_length']?mkprettytime($invite['time_invited']+($row['subscribe_length']*86400)):$REL_LANG->say_by_key('lifetime'))."</td><td><input type=\"text\" value=\"{$REL_SEO->make_link('relgroups','id',$id,'action','suggest','invitecode',$invite['invite'])}\" onclick=\"javascript:this.select();\"></td></tr>");
			}
			print ('</table><div align="center"><form action="'.$REL_SEO->make_link('relgroups','id',$id,'action','invite').'" method="POST"><input type="submit" value="'.$REL_LANG->say_by_key('create_invite').'"></form></div>');
			$REL_TPL->end_frame();
			$REL_TPL->stdfoot();
			die();
		} else {
			if (!isset($_GET['ok'])) {
				stderr($REL_LANG->say_by_key('invite_code'),sprintf($REL_LANG->say_by_key('invite_notice_rg'),$row['name'],($row['subscribe_length']?"{$row['subscribe_length']} дней":$REL_LANG->say_by_key('lifetime')),$row['amount'],$CURUSER['discount'])
				. "<br/><div align=\"center\"><form action=\"".$REL_SEO->make_link('relgroups','id',$id,'action','invite','ok','')."\" method=\"POST\"><input type=\"submit\" value=\"{$REL_LANG->say_by_key('continue')}\"></form></div>",'success');
			} else {
				if ($CURUSER['discount']<$row['amount']) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_discount_invite'));
				$code = md5(microtime()+rand(0,100));
				sql_query("INSERT INTO rg_invites (inviter,rgid,invite,time_invited) VALUES ({$CURUSER['id']},$id,'$code',".time().")") or sqlerr(__FILE__,__LINE__);
				sql_query("UPDATE users SET discount=discount-{$row['amount']} WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
				safe_redirect($REL_SEO->make_link('relgroups','id',$id,'action','invite'),1);
				stderr($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('inivite_code_created'),$code,$row['name']),'success');

			}
		}

	}

	else stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
}

?>