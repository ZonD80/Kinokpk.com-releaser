<?php
/**
 * Browse
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();

//loggedinorreturn();
//httpauth();


//loggedinorreturn();

$page = (int) $_GET["page"];

$cat = (int) $_GET['cat'];
$relgroup = (int) $_GET['relgroup'];

$tree = make_tree();

if (isset($_GET['dead'])) $dead = 1; else $dead = 0;
if (isset($_GET['nofile'])) $nofile = 1; else $nofile = 0;
if (isset($_GET['unchecked'])) $unchecked = 1; else $unchecked = 0;

$searchstr = (string) $_GET['search'];
$cleansearchstr = htmlspecialchars($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

if (($cat!=0) && is_valid_id($cat)) {

	$cats = get_full_childs_ids($tree,$cat);
	if (!$cats) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	else {
		foreach ($cats as $catid) $catq[] = " FIND_IN_SET($catid,torrents.category) ";

		if ($catq) $catq = implode('OR',$catq);

		$wherea['cat'] = $catq;
		$addparam .= "cat=$cat&";
	}
}

if ($dead) {
	$wherea['dead'] = "torrents.visible = 0";
	$addparam .= "dead&";
	$dead = "''";
}
if ($nofile) {
	$wherea['nofile'] = "torrents.filename = 'nofile'";
	$addparam .= "nofile&";
	$nofile = "''";

}
if ($unchecked) {
	$wherea['unchecked'] = "torrents.moderatedby = 0";
	$addparam .= "unchecked&";
	$unchecked = "''";
}

if ($relgroup) {
	$wherea['relgroup'] = "torrents.relgroup =  $relgroup";
	$addparam .= "relgroup=$relgroup&";
}

if ((get_user_class()>=UC_MODERATOR)) { $modview=true; }

if ($unchecked && !$modview) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('unchecked_only_moders'));


if (!is_array($wherea) || !$modview) $wherea[] = "torrents.visible=1 AND torrents.banned=0 AND torrents.moderatedby<>0";

if (isset($cleansearchstr))
{
	$wherea['search'] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";

}

function p2v($str){//возвращает значение параметра по его имени
		$result='';
		if(!is_string($str)){
			_die('stop');
		}
		if(is_array($_POST) && array_key_exists($str,$_POST)){
			$result=$_POST[$str];
		}elseif(is_array($_GET) && array_key_exists($str,$_GET)){
			$result=$_GET[$str];
		}
		return $result;
	}

if (is_array($wherea)) $where = implode(" AND ", $wherea);

// CACHE SYSTEM REMOVED UNTIL 2.75

$res = sql_query("SELECT SUM(1) FROM torrents".($where?" WHERE $where":'')) or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($res);
$count = $row[0];


list($pagertop, $pagerbottom, $limit) = pager($REL_CONFIG['torrentsperpage'], $count, $addparam);

$query = "SELECT torrents.id, torrents.comments, torrents.freefor,torrents.descr, torrents.last_action,".($modview?" torrents.moderated, torrents.moderatedby, 
(SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, 
(SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, torrents.visible, torrents.banned,":'')." torrents.category, torrents.images, torrents.free, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.numfiles, torrents.filename, torrents.sticky, torrents.owner, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage,".($CURUSER?" IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']}))":' IF((torrents.relgroup=0) OR (relgroups.private=0),1,0)')." AS relgroup_allowed, " .
        "users.username, users.class, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM torrents LEFT JOIN relgroups ON torrents.relgroup=relgroups.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN trackers ON torrents.id=trackers.torrent".($where?" WHERE $where":'')." GROUP BY torrents.id ORDER BY torrents.sticky DESC, torrents.added DESC $limit";
$res = sql_query($query) or sqlerr(__FILE__,__LINE__);

$resarray = prepare_for_torrenttable($res);

/*

print('<pre>');
print_r($resarray1);
print('</pre>');
*/
if (REL_AJAX && p2v('action')=='tbbrowse'){
$page=p2v('page');
if($page>1){

print json_safe_encode($resarray);
}


//print json_encode($resarray);


}else{
if (!$resarray) stderr($REL_LANG->say_by_key('error'),"Ничего не найдено. <a href=\"javascript: history.go(-1)\">Назад</a>");

$REL_TPL->stdhead($REL_LANG->say_by_key('browse'));

$REL_TPL->begin_frame('Список релизов '.($modview?'[<a href="'.$REL_SEO->make_link('browse','unchecked','').'">Показать непроверенные релизы отдельно</a>]':''));




$rgarrayres = sql_query("SELECT id,name FROM relgroups ORDER BY added DESC");
while($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
	$rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
}

if ($rgarray) {
	$rgselect = '<span class="browse_relgroup">'.$REL_LANG->say_by_key('relgroup').':</span> <select style="width: 120px;" name="relgroup"><option value="0">'.$REL_LANG->say_by_key('choose').'</option>';
	foreach ($rgarray as $rgid=>$rgname) $rgselect.='<option   value="'.$rgid.'"'.(($relgroup==$rgid)?" selected=\"1\"":'').'>'.$rgname."</option>\n";
	$rgselect.='</select>';
}


?>
<script language="javascript" type="text/javascript" src="js/jquery.json.js"></script>

<style type="text/css">

/**/
#container{
border:1px solid #C2CFF1;
margin:10px 0;
width:964px;
}
#scrollbox{
width:964px;
height:422px;
overflow:auto; overflow-x:hidden;
}
#container > p{
background:#eee;
color:#666;
font-family:Arial, sans-serif; font-size:0.75em;
padding:5px; margin:0;
text-align:right;
}


#current-entry.expanded {
border-color:#6688EE;
border-style:solid;
border-width:1px;
}
#current-entry.read h2.entry-title{
color: DarkGray;
}
#content .read .collapsed {
background:none repeat scroll 0 0 #F3F5FC;
border:2px solid #F3F5FC;
}
.torrent_browse_img{
float:left;height:360px;padding:5px;width:240px;
}
.torrent_browse_discr_bottom,.torrent_browse_discr_top{
float:right;
padding:5px;
width:675px;
}
.entry {
border-bottom:1px solid #CCCCCC;margin:0;overflow:hidden;padding:0;
}
.entry-icons {
float:left;
left:0.2em;
}
.entry-date {
direction:ltr;
float:right;
margin-right:10px;
width:6.5em;
padding-right:15px;
text-align:right;
}

.entry-author,.comment-time,.entry-date {
color:#666666;text-decoration:none;
}
.entry-main {
height:25px;
margin-left:0;
margin-top:-6px;
padding-left:20px;
width:770px;
float:left;
margin-right:10px;
overflow:auto;
white-space:nowrap;
}

 .entry-secondary {
 left:0;
 margin:0 10em 0 15em;
 padding:0 0 0 1px;
 color:#777777;
 overflow:hidden;
 position:absolute;
 top:3px;
 white-space:normal;
 width:auto;

 }

.entry-title {
display:inline;margin:0;padding:0;position:static;width:auto;
font-weight:normal;
color:#000000;display:inline;font-size:100%;font-weight:bold;margin-right:0.5em;
}

 .entry-body,.entry-title, .entry-likers {
 max-width:650px;
 }

.snippet {
float:none;margin:0;padding:0;position:static;width:auto;
}
.collapsed {
-moz-user-select:none;background:none repeat scroll 0 0 #FFFFFF;border:2px solid #FFFFFF;cursor:pointer;height:2.2ex;line-height:2.3ex;margin:0;overflow:hidden;padding:3px 0;position:relative;width:auto;
}
.entry-source-title {
color:#555555;display:block;font-size:100%;left:2.1em;overflow:hidden;padding:0 1em 0 0;position:absolute;top:3px;white-space:nowrap;width:11em;
}
.star {
height:17px;padding:0;width:15px;
}
.link {
margin-left:0.3em;margin-top:-0.2em;
}
 .entry-original {
 float:none;margin:0;padding:0;position:absolute;top:3px;white-space:normal;width:1.3em;z-index:2;
background:url("/pic/action-icons.png") no-repeat scroll left -418px transparent;right:0.2em;
background-repeat:no-repeat;height:14px;margin-top:-7px;position:absolute;top:50%;width:14px;

 }

.star {
background:url("/pic/action-icons.png") no-repeat scroll 0 0 transparent;white-space:nowrap;
}
 .item-star-active {
 background-position:-16px -33px;
 }


</style>
<?
print("<div class=\"friends_search\">
<form class='formbr' action=\"".$REL_SEO->make_link('browse')."\" method=\"get\">".'
<input type="text" class="browse_search" name="search" size="30" style="margin-right: 10px;"/>
'.gen_select_area('cat',$tree,$cat, true).'<br />
<div class="brel">
'.$rgselect.'

<input class="button" type="submit" size="40" value="'.$REL_LANG->say_by_key('search').'!" />
</div>
</form>
<div class="clear"></div>
<!-- Google Search -->
<form action="http://www.google.com/cse">
    <input name="cx" value="008925083164290612781:gpt7xhlrdou" type="hidden" />
    <input name="ie" value="windows-1251" type="hidden" />
    <input name="q" size="43" type="text" />
    <input name="sa" class="button" value="Поиск Google!" type="submit" />
</form>
<!-- Google Search -->
</div>
');
$REL_TPL->end_frame();
/*
if (isset($cleansearchstr)){
	$REL_TPL->begin_frame($REL_LANG->say_by_key('search_results_for')." \"" . $cleansearchstr . "\"\n");
}else{
	$REL_TPL->begin_frame('Релизы');
}
*/

print('<div id="top_container"><div id="container">
		<div id="scrollbox" >
			<div id="content" class="browse_content">
				<div class="page">Страница :<div class="nompage-1">1</div>');

if ($count) {

	$returnto = urlencode(basename($_SERVER["REQUEST_URI"]));
//	torrenttable_browse($resarray, "index", $returnto);
torrenttable($resarray, "browse", $returnto);

}
else {
	if (isset($cleansearchstr)) {
		print("<tr><td class=\"index\" colspan=\"12\">".$REL_LANG->say_by_key('nothing_found')."</td></tr>\n");
		//print("<p>Попробуйте изменить запрос поиска.</p>\n");
	}
	else {
		print("<tr><td class=\"index\" colspan=\"12\">".$REL_LANG->say_by_key('nothing_found')."</td></tr>\n");
		//print("<p>Извините, данная категория пустая.</p>\n");
	}
}

print ('</div>');
print ('</div>');
//print ('</div>');
print ('<p><span id="status" ></span><span class="allt">'.$count.'<span></p></div>');
print ('</div>');

$REL_TPL->end_frame();
$REL_TPL->stdfoot();


?>

<script language="javascript">
<!--
$('document').ready(function(){
	updatestatus();
	scrollalert();
	
});


      function html_entity_decode(str) {
        var ta = document.createElement("textarea");
        ta.innerHTML=str.replace(/</g,"&lt;").replace(/>/g,"&gt;");
        toReturn = ta.value;
        ta = null;
        return toReturn
      }



function updatestatus(){
	
// Показывает количество загруженных пунктов
var totalItems=$('#content div.entry').length;
var allpage=totalItems/25;
$('#status').text('Всего страниц '+ allpage +' загружено '+totalItems+' элементов из ');
$('div.entry-main').unbind('click');
	$('div.entry-main').bind('click', function(evt){
		evt.preventDefault();
			var $detail= $(this).parent().eq(0);
			if($detail.next('div').children()[0].offsetHeight > 0){
				$('div.detils_browse').hide();
			}else{
				$('#content div.entry > div.detils_browse').hide();	
				$detail.next('div.detils_browse').eq(0).show();
				var coords = $(this).evElementCoords('#scrollbox');
				$('#scrollbox')[0].scrollTop=coords.top;
		}
				//$(this).children(".detils_browse").slideToggle();
	});
$("div.star").unbind('click');
	$("div.star").bind('click',function(){
		var $itemsplit = $(this).attr('class').split(" ");
			if(!$itemsplit[2]){
				$(this).addClass("item-star-active");
				var classes=$(this).parents('.entry').attr('class').split(" ");//.css("border", "2px red solid");
					for(var i=0; i<classes.length; i++){
						 if(classes[i].substr(0,6)=='entry-'){
							var id=classes[i].split('-')
							//console.log(id[1]);
						}
					 }
				 $.get("bookmark.php", {"torrent": id[1]});
			}else{
				$(this).removeClass("item-star-active");
				var classes=$(this).parents('.entry').attr('class').split(" ");//.css("border", "2px red solid");
					for(var i=0; i<classes.length; i++){
						 if(classes[i].substr(0,6)=='entry-'){
							var id=classes[i].split('-');
							var idtorr=id[1];
							//console.log(id[1]);
						}
					 }
				 $.post("takedelbookmark.php", {'delbookmark[]':[idtorr] });
			}
		   //   $(this).toggleClass("item-star-active");
			 // http://www.torrentsbook.com/bookmark.php?torrent=44857&name=okeani_/_oceans_(2009)_[hdrip_|_licenziya]
		});
	
}

var page_loading_flags={}

function scrollalert(){
	var scrolltop=$('#scrollbox').attr('scrollTop');
	var scrollheight=$('#scrollbox').attr('scrollHeight');
	var windowheight=$('#scrollbox').attr('clientHeight');
	var scrolloffset=300;
//console.log(scrolltop,scrollheight,windowheight,scrolloffset)

if(scrolltop>=(scrollheight-(windowheight+scrolloffset))){

		var nam=1;
		var page =$('div.page:last').children().attr('class').split("-");//1
		var page2=parseInt(page[1])+parseInt(nam); //2
		var page3=parseInt(page)-parseInt(nam); //2
		var page0=$('span.allt').text()/25; //0

if(typeof page_loading_flags['page_'+page2]=='undefined'){
	page_loading_flags['page_'+page2]=true;

	$('#status').text(' Загрузка новых элементов...');
	var sendajax = $.getJSON('browse2.php', {action:"tbbrowse",page:page2}, function(newitems ,textStatus){
		var newite = JSON.stringify(newitems);
		var newitem = JSON.parse(newite);
		var thing = newitem ; ;var encoded = $.toJSON(thing); var name = $.evalJSON(encoded); 
	$(document.createElement('div')).attr({'class':'page'}).text('Страница :').
		append($(document.createElement('div')).attr({'class':'nompage-'+page2}).text(page2)).appendTo($('#content'));
			for (key in name){
				if(name[key].id>0){
					var namedescr = name[key].descr;
					var cat_names1 = name[key].cat_names;
					var cat_names = html_entity_decode(cat_names1);

					var namedescr = html_entity_decode(namedescr);




			

					
					if(name[key].new==null){var read='read';}else{var read='';}
						var torrents = $(document.createElement('div')).attr({class:'entry entry-'+name[key].id +' '+ read}).
											append($(document.createElement('div')).attr({'class':'collapsed'}).
												append($(document.createElement('div')).attr({'class':'entry-icons'}).append($(document.createElement('div')).attr({'class':'star link'}))).
													append($(document.createElement('div')).attr({'class':'entry-date'}).text(name[key].month_added)).
														append($(document.createElement('div')).attr({'class':'entry-main'}).
															append($(document.createElement('a')).attr({'class':'entry-original','target':'blank','href':'#'})).
																append($(document.createElement('span')).attr({'class':'entry-source-title'})).
																	append($(document.createElement('div')).attr({'class':'entry-secondary'}).
																		append($(document.createElement('h2')).attr({'class':'entry-title'}).text(name[key].name)).
																			append($(document.createElement('span')).attr({'class':'entry-secondary-snippet'}).text('-').
																				append($(document.createElement('span')).attr({'class':'snippet'}).text(cat_names))
																			)
																		)


																)
													).append($(document.createElement('div')).attr({'class':'detils_browse','style':'display:none'}).
														append($(document.createElement('div')).attr({'class':'torrent_browse_img'}).
															append($(document.createElement('a')).attr({'href':'javascript:$.facebox({ image:"'+ name[key].images +'"});'}).
																append($(document.createElement('img')).attr({'width':'240','src':name[key].images,'alt':'Изображение для:'+name[key].name}))
																	)
																).
															append($(document.createElement('div')).attr({'class':'torrent_browse_discr'}).
																append($(document.createElement('div')).attr({'class':'torrent_browse_discr_top'}).
																	append($(document.createElement('h2')).attr({'class':'entry-title'}).append($(document.createElement('a')).attr({'class':'entry-title-link','traget':'blank','href':'#'}).text(name[key].name)
																	))
																).
																append($(document.createElement('div')).attr({'class':'torrent_browse_discr_bottom'}).text(namedescr)
																)
															)
																							
													
															).appendTo($('div.nompage-'+page2));/**/
				}
			}

			updatestatus();
			
		});

	}


		
		
	}
	
setTimeout('scrollalert();', 100);
		
	
}




//-->
</script>
<?
}
?>