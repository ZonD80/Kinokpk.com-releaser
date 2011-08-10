<?php
/**
 * Release groups news viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();


$rgnewsid = (int) $_GET['id'];
if (!is_valid_id($rgnewsid))
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
//$action = $_GET["action"];
//$returnto = $_GET["returnto"];




$sql = sql_query("SELECT * FROM rgnews WHERE id = {$rgnewsid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);

if (mysql_num_rows($sql) == 0) {
	stderr($REL_LANG->say_by_key('error'),'Извините...Нет новости с таким ID!');

}

$rgnews = mysql_fetch_assoc($sql);

if (!pagercheck()) {
	$relgroup = sql_query("SELECT id,name,owners,private FROM relgroups WHERE id={$rgnews['relgroup']}") or sqlerr(__FILE__,__LINE__);
	$relgroup = mysql_fetch_assoc($relgroup);

	if (!$relgroup) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	if (in_array($CURUSER['id'],@explode(',',$relgroup['owners'])) || (get_privilege('edit_relgroups',false))) $I_OWNER=true;

	if ($relgroup['private']) {
		if (!$I_OWNER && !in_array($CURUSER['id'],@explode(',',$relgroup['onwers']))) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_access_priv_rg'));
	}

	$REL_TPL->stdhead("Комментирование новости");
	?>
<div id="relgroups_header" class="relgroups_header">
<div align="center">Обзор Новости Релиз группы <?=makesafe($relgroup['name']) ?>&nbsp;&nbsp;<?   print(ratearea($relgroup['ratingsum'],$relgroup['id'],'relgroups',($I_OWNER?$relgroup['id']:0))."");?></div>
<div align="right" style="margin-top: -22px;"><a
	href="<?=$REL_SEO->make_link('relgroups');?>"><img
	src="/themes/kinokpk/images/strelka.gif" border="0"
	title="Вернуться к просмотру групп"
	style="margin-top: 5px; margin-right: 5px;" /></a></div>

</div>
<div id="relgroups_table_right">
<div id="relgroups_image" class="relgroups_image_right">
<div class="relgroups_avatar_right"><img src="<?=$relgroup['image']?>"
	title="<?=makesafe($relgroup['name'])?>" /><? $REL_LANG->say_by_key('no_image')?>
<div id="input_right" class="relgroups_input_right"><? //print (int)$relgroup['users'].'&nbsp;';
	//ПЕРЕДЕЛАТЬ
	//$i_subscribed = @mysql_result(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid=$id"),0);
	if ($relgroup['private'])
	$i_subscribed = mysql_fetch_row(sql_query("SELECT 1 FROM rg_subscribes WHERE userid={$CURUSER['id']} AND rgid={$relgroup['id']}"));
	if ($i_subscribed) print ("<li><a href=\"".$REL_SEO->make_link('relgroups','id',$relgroup['id'],'action','deny')."\">Отписаться от группы</a></li><li><a href=\"".$REL_SEO->make_link('relgroups','id',$relgroup['id'],'action','invite')."\">{$REL_LANG->say_by_key('create_invite')}</a></li>");
	else print ("<li>".(($relgroup['private']&&$relgroup['only_invites'])?$REL_LANG->say_by_key('private_group_friend_subscribe'):"<a href=\"".($relgroup['page_pay']?$relgroup['page_pay']:$REL_SEO->make_link('relgroups','id',$id,'action','suggest"'))."\">Подписаться на релизы</a>")."</li>");
	?></div>
</div>
<div id="boxes_right" class="box_right">
<div id="box_app_right">
<h3 class="box_right_block"><span>Директор</span><?print('<div align="center">'.((get_privilege('edit_relgroups',false) || $I_OWNER) ? "<a class=\"box_editor_left\" href=\"#\"></a>" : "").'</div>');?></h3>
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
	if ($relgroup['page_pay']) print $REL_LANG->say_by_key('pay_required');
	elseif ($relgroup['private']) print $REL_LANG->say_by_key('private');
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
<h3 class="box_right_left"><span>Обсуждаем: <?=$rgnews['subject']?></span>
	<?print('<div align="center">'.(((get_privilege('edit_relgroups',false)) || $I_OWNER) ? "<a class=\"box_editor_left\" title=".$REL_LANG->say_by_key('edit')." href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$relgroup['id'],'newsid',$rgnewsid,'returnto',$REL_SEO->make_link('rgnews','id',$rgnewsid))."\"></a>" : "").'</div>');?></h3>
<div class="basic_infor_summary_list box_left_page"><?

$added = mkprettytime($rgnews['added']) . " (" . (get_elapsed_time($rgnews["added"],false)) . " {$REL_LANG->say_by_key('ago')})";



print("<div class='newsbody'><p>".format_comment($rgnews['body'])."</p><small>".$added."</small></div></div>");
}


$REL_TPL->assignByRef('to_id',$rgnewsid);
$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $rgnewsid, 'rgnewscomments' ));
$REL_TPL->assign('textbbcode',textbbcode('text'));
$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('Release group news'));
$FORM_TYPE = 'rgnews';
$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
$REL_TPL->display('commenttable_form.tpl');

$subres = sql_query("SELECT SUM(1) FROM comments WHERE toid = ".$rgnewsid." AND type='rgnews'");
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];


if (!$count) {

	print('<div id="newcomment_placeholder">'."<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к новости</div>");
	print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("Комментариев нет. <a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comments\">Желаете добавить?</a>");
	print("</td></tr></table><br /></div>");

}
else {

	$subres = sql_query("SELECT nc.type, nc.id, nc.ip, nc.text, nc.ratingsum, nc.user, nc.added, nc.editedby, nc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.info, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS nc LEFT JOIN users AS u ON nc.user = u.id LEFT JOIN sessions ON nc.user=sessions.uid LEFT JOIN users AS e ON nc.editedby = e.id WHERE nc.toid = " .
                  "".$rgnewsid." AND nc.type='rgnews' ORDER BY nc.id DESC $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = prepare_for_commenttable($subres,$rgnews['subject'],$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid));
	if (!pagercheck()) {
		print("<div id=\"pager_scrollbox\"><table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
		print("<tr><td class=\"colhead\" align=\"center\" >");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr>");
		
		print ( "<tr><td>" );
		commenttable ( $allrows);
		print ( "</td></tr>" );

		print ( "</table></div>" );
	} else { 	print ( "<tr><td>" );
	commenttable ( $allrows);
	print ( "</td></tr>" ); die(); }
}

?></div>
</div>
</div>
<div class="clear"></div>
<?php

$REL_TPL->stdfoot();
?>