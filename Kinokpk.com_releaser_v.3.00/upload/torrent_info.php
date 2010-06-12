<?php
/**
 * Displays all torrent information
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";
require_once "include/benc.php";

/**
 * Gets bittorrent-client name
 * @param string $httpagent Client http-agent header
 * @param string $peer_id Peer id
 * @return string Nice name of client
 */
function getagent($httpagent, $peer_id = "") {
	if (preg_match("/^Azureus ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]\_B([0-9][0-9|*])(.+)$)/", $httpagent, $matches))
	return "Azureus/$matches[1]";
	elseif (preg_match("/^Azureus ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]\_CVS)/", $httpagent, $matches))
	return "Azureus/$matches[1]";
	elseif (preg_match("/^Java\/([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
	return "Azureus/<2.0.7.0";
	elseif (preg_match("/^Azureus ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
	return "Azureus/$matches[1]";
	elseif (preg_match("/BitTorrent\/S-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
	return "Shadow's/$matches[1]";
	elseif (preg_match("/BitTorrent\/U-([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
	return "UPnP/$matches[1]";
	elseif (preg_match("/^BitTor(rent|nado)\\/T-(.+)$/", $httpagent, $matches))
	return "BitTornado/$matches[2]";
	elseif (preg_match("/^BitTornado\\/T-(.+)$/", $httpagent, $matches))
	return "BitTornado/$matches[1]";
	elseif (preg_match("/^BitTorrent\/ABC-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
	return "ABC/$matches[1]";
	elseif (preg_match("/^ABC ([0-9]+\.[0-9]+(\.[0-9]+)*)\/ABC-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
	return "ABC/$matches[1]";
	elseif (preg_match("/^Python-urllib\/.+?, BitTorrent\/([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
	return "BitTorrent/$matches[1]";
	elseif (preg_match("/^BitTorrent\/brst(.+)/", $httpagent, $matches))
	return "Burst";
	elseif (preg_match("/^RAZA (.+)$/", $httpagent, $matches))
	return "Shareaza/$matches[1]";
	elseif (preg_match("/Rufus\/([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
	return "Rufus/$matches[1]";
	elseif (preg_match("/^Python-urllib\\/([0-9]+\\.[0-9]+(\\.[0-9]+)*)/", $httpagent, $matches))
	return "G3 Torrent";
	elseif (preg_match("/MLDonkey\/([0-9]+).([0-9]+).([0-9]+)*/", $httpagent, $matches))
	return "MLDonkey/$matches[1].$matches[2].$matches[3]";
	elseif (preg_match("/ed2k_plugin v([0-9]+\\.[0-9]+).*/", $httpagent, $matches))
	return "eDonkey/$matches[1]";
	elseif (preg_match("/uTorrent\/([0-9]+)([0-9]+)([0-9]+)([0-9A-Z]+)/", $httpagent, $matches))
	return "µTorrent/$matches[1].$matches[2].$matches[3].$matches[4]";
	elseif (preg_match("/CT([0-9]+)([0-9]+)([0-9]+)([0-9]+)/", $peer_id, $matches))
	return "cTorrent/$matches[1].$matches[2].$matches[3].$matches[4]";
	elseif (preg_match("/Transmission\/([0-9]+).([0-9]+)/", $httpagent, $matches))
	return "Transmission/$matches[1].$matches[2]";
	elseif (preg_match("/KT([0-9]+)([0-9]+)([0-9]+)([0-9]+)/", $peer_id, $matches))
	return "KTorrent/$matches[1].$matches[2].$matches[3].$matches[4]";
	elseif (preg_match("/rtorrent\/([0-9]+\\.[0-9]+(\\.[0-9]+)*)/", $httpagent, $matches))
	return "rTorrent/$matches[1]";
	elseif (preg_match("/^ABC\/Tribler_ABC-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
	return "Tribler/$matches[1]";
	elseif (preg_match("/^BitsOnWheels( |\/)([0-9]+\\.[0-9]+).*/", $httpagent, $matches))
	return "BitsOnWheels/$matches[2]";
	elseif (preg_match("/BitTorrentPlus\/(.+)$/", $httpagent, $matches))
	return "BitTorrent Plus!/$matches[1]";
	elseif (preg_match("/^Deadman Walking/", $httpagent))
	return "Deadman Walking";
	elseif (preg_match("/^eXeem( |\/)([0-9]+\\.[0-9]+).*/", $httpagent, $matches))
	return "eXeem$matches[1]$matches[2]";
	elseif (preg_match("/^libtorrent\/(.+)$/", $httpagent, $matches))
	return "libtorrent/$matches[1]";
	elseif (substr($peer_id, 0, 12) == "d0c")
	return "Mainline";
	elseif (substr($peer_id, 0, 1) == "M")
	return "Mainline/Decoded";
	elseif (substr($peer_id, 0, 3) == "-BB")
	return "BitBuddy";
	elseif (substr($peer_id, 0, 8) == "-AR1001-")
	return "Arctic Torrent/1.2.3";
	elseif (substr($peer_id, 0, 6) == "exbc\08")
	return "BitComet/0.56";
	elseif (substr($peer_id, 0, 6) == "exbc\09")
	return "BitComet/0.57";
	elseif (substr($peer_id, 0, 6) == "exbc\0:")
	return "BitComet/0.58";
	elseif (substr($peer_id, 0, 4) == "-BC0")
	return "BitComet/0.".substr($peer_id, 5, 2);
	elseif (substr($peer_id, 0, 7) == "exbc\0L")
	return "BitLord/1.0";
	elseif (substr($peer_id, 0, 7) == "exbcL")
	return "BitLord/1.1";
	elseif (substr($peer_id, 0, 3) == "346")
	return "TorrenTopia";
	elseif (substr($peer_id, 0, 8) == "-MP130n-")
	return "MooPolice";
	elseif (substr($peer_id, 0, 8) == "-SZ2210-")
	return "Shareaza/2.2.1.0";
	elseif (preg_match("/^0P3R4H/", $httpagent))
	return "Opera BT Client";
	elseif (substr($peer_id, 0, 6) == "A310--")
	return "ABC/3.1";
	elseif (preg_match("/^XBT Client/", $httpagent))
	return "XBT Client";
	elseif (preg_match("/^BitTorrent\/BitSpirit$/", $httpagent))
	return "BitSpirit";
	elseif (preg_match("/^DansClient/", $httpagent))
	return "XanTorrent";
	else
	return "Unknown";
}

/**
 * Creates nice peers table
 * @param string $name Name of a table
 * @param array $arr Array to be processed
 * @param int $torrent ID of a torrent
 * @return string HTML code of a table
 */
function dltable($name, $arr, $torrent)
{

	global $CURUSER, $tracker_lang;
	$s = "<b>" . count($arr) . " $name</b>\n";
	if (!count($arr))
	return $s;
	$s .= "\n";
	$s .= "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
	$s .= "<tr><td class=colhead>".$tracker_lang['user']."</td>" .
          "<td class=colhead align=center>".$tracker_lang['port_open']."</td>".
          "<td class=colhead align=right>".$tracker_lang['uploaded']."</td>".
          "<td class=colhead align=right>".$tracker_lang['ul_speed']."</td>".
          "<td class=colhead align=right>".$tracker_lang['downloaded']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['dl_speed']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['ratio']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['completed']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['connected']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['idle']."</td>" .
          "<td class=colhead align=left>".$tracker_lang['client']."</td></tr>\n";
	$moderator = (isset($CURUSER) && get_user_class() >= UC_MODERATOR);
	$mod = get_user_class() >= UC_MODERATOR;
	foreach ($arr as $e) {
		// user/ip/port
		// check if anyone has this ip
		$s .= "<tr>\n";
		if ($e["username"])
		$s .= "<td><a href=\"userdetails.php?id=$e[userid]\"><b>".get_user_class_color($e["class"], $e["username"])."</b></a>".($mod ? "&nbsp;[<span title=\"{$e["ip"]}\" style=\"cursor: pointer\">IP</span>]" : "")."</td>\n";
		else
		$s .= "<td>" . ($mod ? $e["ip"] : preg_replace('/\.\d+$/', ".xxx", $e["ip"])) . "</td>\n";
		$secs = max(10, ($e["la"]) - $e["pa"]);
		$revived = $e["revived"] == 1;
		$s .= "<td align=\"center\">" . ($e[connectable] ? "<span style=\"color: green; cursor: help;\" title=\"Порт открыт. Этот пир может подключатся к любому пиру.\">".$tracker_lang['yes']."</span>" : "<span style=\"color: red; cursor: help;\" title=\"Порт закрыт. Рекомендовано проверить настройки Firewall'а.\">".$tracker_lang['no']."</span>") . "</td>\n";
		$s .= "<td align=\"right\"><nobr>" . mksize($e["uploaded"]) . "</nobr></td>\n";
		$s .= "<td align=\"right\"><nobr>" . mksize($e["uploadoffset"] / $secs) . "/s</nobr></td>\n";
		$s .= "<td align=\"right\"><nobr>" . mksize($e["downloaded"]) . "</nobr></td>\n";
		//if ($e["seeder"] == "no")
		$s .= "<td align=\"right\"><nobr>" . mksize($e["downloadoffset"] / $secs) . "/s</nobr></td>\n";
		/*else
		 $s .= "<td align=\"right\"><nobr>" . mksize($e["downloadoffset"] / max(1, $e["finishedat"] - $e["st"])) . "/s</nobr></td>\n";*/
		if ($e["downloaded"]) {
			$ratio = floor(($e["uploaded"] / $e["downloaded"]) * 1000) / 1000;
			$s .= "<td align=\"right\"><font color=" . get_ratio_color($ratio) . ">" . number_format($ratio, 3) . "</font></td>\n";
		} else
		if ($e["uploaded"])
		$s .= "<td align=\"right\">Inf.</td>\n";
		else
		$s .= "<td align=\"right\">---</td>\n";
		$s .= "<td align=\"right\">" . sprintf("%.2f%%", 100 * (1 - ($e["to_go"] / $torrent["size"]))) . "</td>\n";
		$s .= "<td align=\"right\">" . get_elapsed_time($e["st"]) . "</td>\n";
		$s .= "<td align=\"right\">" . get_elapsed_time($e["la"]) . "</td>\n";
		$s .= "<td align=\"left\">" . htmlspecialchars(getagent($e["agent"], $e["peer_id"])) . "</td>\n";
		$s .= "</tr>\n";
	}
	$s .= "</table>\n";
	return $s;
}

dbconn();
getlang('remotepeers');

loggedinorreturn();

$id = (int)$_GET["id"];

if (!$id)
stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

$nofr = @mysql_result(sql_query("SELECT filename FROM torrents WHERE id=$id"),0);
if ($nofr == 'nofile') die ("Блядь ну хули смотреть? Это не торрент релиз! Данных о торренте нет! <a href='details.php?id=".$id."'>К описанию релиза</a>");
elseif (!$nofr) 	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

$nof = sql_query("SELECT tracker,lastchecked,state,seeders,leechers FROM trackers WHERE torrent = $id ORDER by lastchecked DESC");
while (list($tracker,$lastchecked,$state,$seeders,$leechers) = mysql_fetch_array($nof)) {
	if ($tracker=='localhost') {
		$data[$i]['tracker'] = $CACHEARRAY['defaultbaseurl'];
		$data[$i]['state'] = 'ok_local';
	}
	else { $data[$i]['tracker']=$tracker;   $data[$i]['state'] = $state; }

	$data[$i]['lastchecked'] = get_elapsed_time($lastchecked)." {$tracker_lang['ago']}";
	$data[$i]['seeders'] = $seeders;
	$data[$i]['leechers'] = $leechers;
	$i++;
}

stdhead("Данные о торенте");
print("<h1>Данные о торенте</h1>\n");
/*print("<table cellspacing=\"0\" cellpadding=\"0\" class=\"tabs\"><tbody><tr>
 <td class=\"tab0\"> </td><td nowrap=\"\" class=\"tab2\"><a href=\"details.php?id=$id\">Описание</a></td>
 <td class=\"tab\"> </td><td nowrap=\"\" class=\"tab1\"><a href=\"torrent_info.php?id=$id\">Данные о торренте</a></td>
 <td class=\"tab3\"> </td></tr></tbody></table>\n");*/
print("<div id=\"tabs\"><ul>
	<li class=\"tab2\"><a href=\"details.php?id=$id\"><span>Описание</span></a></li>
	<li nowrap=\"\" class=\"tab1\"><a href=\"torrent_info.php?id=$id\"><span>{$tracker_lang ['torrent_info']}</span></a></li>
	</ul></div>\n <br />");
print('<table width="100%" style="float:left"><tr><td class="colhead">'.$tracker_lang['tracker'].'</td><td class="colhead">Сидов</td><td class="colhead">Личей</td><td class="colhead">Всего</td><td class="colhead">Время проверки</td><td class="colhead">'.$tracker_lang['status'].'</td></tr>');
if ($data)
foreach ($data as $tracker)
print ("<tr><td>".$tracker['tracker']."</td><td>{$tracker['seeders']}</td><td>{$tracker['leechers']}</td><td>".($tracker['seeders']+$tracker['leechers'])."</td><td>{$tracker['lastchecked']}</td><td>".cleanhtml($tracker['state'])."</td></tr>");
//print('</table>');
else print ('<tr><td colspan="6" align="center">Данный релиз является анонсом или релизом без торрента</td></tr>');
end_frame();


print('<h3><a href="torrent_info.php?id='.$id.'&info">Посмотреть структуру торрент-файла</a> или <a href="torrent_info.php?id='.$id.'&amp;dllist">Посмотреть списки пиров на сайте</a></h3>');
if (isset($_GET['info'])) {
	/**
	 * Prints Nice array
	 * @param array $array Array to be processed
	 * @param string $offset_symbol Offset symbol
	 * @param string $offset Offset
	 * @param string $parent Parent
	 */
	function print_array($array, $offset_symbol = "|--", $offset = "", $parent = "")
	{
		if (!is_array($array))
		{
			echo "[$array] is not an array!<br />";
			return;
		}

		reset($array);


		switch($array['type'])
		{
			case "string":
				printf("<li><div class=string> - <span class=icon>[STRING]</span> <span class=title>[%s]</span> <span class=length>(%d)</span>: <span class=value>%s</span></div></li>",$parent,$array['strlen'],$array['value']);
				break;
			case "integer":
				printf("<li><div class=integer> - <span class=icon>[INT]</span> <span class=title>[%s]</span> <span class=length>(%d)</span>: <span class=value>%s</span></div></li>",$parent,$array['strlen'],$array['value']);
				break;
			case "list":
				printf("<li><div class=list> + <span class=icon>[LIST]</span> <span class=title>[%s]</span> <span class=length>(%d)</span></div>",$parent,$array['strlen']);
				echo "<ul>";
				print_array($array['value'], $offset_symbol, $offset.$offset_symbol);
				echo "</ul></li>";
				break;
			case "dictionary":
				printf("<li><div class=dictionary> + <span class=icon>[DICT]</span> <span class=title>[%s]</span> <span class=length>(%d)</span></div>",$parent,$array['strlen']);
				while (list($key, $val) = each($array))
				{
					if (is_array($val))
					{
						echo "<ul>";
						print_array($val, $offset_symbol, $offset.$offset_symbol,$key);
						echo "</ul>";
					}
				}
				echo "</li>";

				break;
			default:
				while (list($key, $val) = each($array))
				{
					if (is_array($val))
					{
						//echo $offset;
						print_array($val, $offset_symbol, $offset, $key);
					}
				}
				break;

		}

	}

	$fn = "torrents/$id.torrent";

	if (!is_readable($fn)) {
		stdmsg($tracker_lang['error'],'Невозможно прочитать torrent-файл','error');   stdfoot(); die(); }
		?>

<style type="text/css">
<!-- /* list styles */
ul ul {
	margin-left: 15px;
}

ul,li {
	padding: 0px;
	list-style-type: none;
	color: #000;
	font-weight: normal;
}

ul a,li a {
	color: #009;
	text-decoration: none;
	font-weight: normal;
}

li {
	display: inline;
}  /* fix for IE blank line bug */
ul>li {
	display: list-item;
}

li div.string {
	padding: 3px;
}

li div.integer {
	padding: 3px;
}

li div.dictionary {
	padding: 3px;
}

li div.list {
	padding: 3px;
}

li div.string span.icon {
	color: #090;
	padding: 2px;
}

li div.integer span.icon {
	color: #990;
	padding: 2px;
}

li div.dictionary span.icon {
	color: #909;
	padding: 2px;
}

li div.list span.icon {
	color: #009;
	padding: 2px;
}

li span.title {
	font-weight: bold;
}
-->
</style>

		<?php

		begin_main_frame();

		$info = bdec_file($fn, (1024*1024));

		// Start table
		/*print("<table cellspacing=\"0\" cellpadding=\"0\" class=\"tabs\"><tbody><tr>
		 <td class=\"tab0\"> </td><td nowrap=\"\" class=\"tab2\"><a href=\"details.php?id=$id\">Описание</a></td>
		 <td class=\"tab\"> </td><td nowrap=\"\" class=\"tab1\"><a href=\"torrent_info.php?id=$id\">Данные о торренте</a></td>
		 <td class=\"tab3\"> </td></tr></tbody></table>\n");*/
		print("<table width=100% border=1 cellspacing=0 cellpadding=5>");

		print("<td>");

		$info['value']['pieces']['value'] = "0x".bin2hex(substr($info['value']['pieces']['value'], 0, 25))."...";

		echo "<ul id=colapse>";
		print_array($info,"*", "", "info");
		print $anstring;
		echo "</ul>";

		// End table
		print("</td></table>");

		?>

<script type="text/javascript" language="javascript1.2"><!--
var openLists = [], oIcount = 0;
function compactMenu(oID,oAutoCol,oPlMn,oMinimalLink) {
	if( !document.getElementsByTagName || !document.childNodes || !document.createElement ) { return; }
	var baseElement = document.getElementById( oID ); if( !baseElement ) { return; }
	compactChildren( baseElement, 0, oID, oAutoCol, oPlMn, baseElement.tagName.toUpperCase(), oMinimalLink && oPlMn );
}
function compactChildren( oOb, oLev, oBsID, oCol, oPM, oT, oML ) {
	if( !oLev ) { oBsID = escape(oBsID); if( oCol ) { openLists[oBsID] = []; } }
	for( var x = 0, y = oOb.childNodes; x < y.length; x++ ) { if( y[x].tagName ) {
		//for each immediate LI child
		var theNextUL = y[x].getElementsByTagName( oT )[0];
		if( theNextUL ) {
			//collapse the first UL/OL child
			theNextUL.style.display = 'none';
			//create a link for expanding/collapsing
			var newLink = document.createElement('A');
			newLink.setAttribute( 'href', '#' );
			newLink.onclick = new Function( 'clickSmack(this,' + oLev + ',\'' + oBsID + '\',' + oCol + ',\'' + escape(oT) + '\');return false;' );
			//wrap everything upto the child U/OL in the link
			if( oML ) { var theHTML = ''; } else {
				var theT = y[x].innerHTML.toUpperCase().indexOf('<'+oT);
				var theA = y[x].innerHTML.toUpperCase().indexOf('<A');
				var theHTML = y[x].innerHTML.substr(0, ( theA + 1 && theA < theT ) ? theA : theT );
				while( !y[x].childNodes[0].tagName || ( y[x].childNodes[0].tagName.toUpperCase() != oT && y[x].childNodes[0].tagName.toUpperCase() != 'A' ) ) {
					y[x].removeChild( y[x].childNodes[0] ); }
			}
			y[x].insertBefore(newLink,y[x].childNodes[0]);
			y[x].childNodes[0].innerHTML = oPM + theHTML.replace(/^\s*|\s*$/g,'');
			theNextUL.MWJuniqueID = oIcount++;
			compactChildren( theNextUL, oLev + 1, oBsID, oCol, oPM, oT, oML );
} } } }
function clickSmack( oThisOb, oLevel, oBsID, oCol, oT ) {
	if( oThisOb.blur ) { oThisOb.blur(); }
	oThisOb = oThisOb.parentNode.getElementsByTagName( unescape(oT) )[0];
	if( oCol ) {
		for( var x = openLists[oBsID].length - 1; x >= oLevel; x-=1 ) { if( openLists[oBsID][x] ) {
			openLists[oBsID][x].style.display = 'none'; if( oLevel != x ) { openLists[oBsID][x] = null; }
		} }
		if( oThisOb == openLists[oBsID][oLevel] ) { openLists[oBsID][oLevel] = null; }
		else { oThisOb.style.display = 'block'; openLists[oBsID][oLevel] = oThisOb; }
	} else { oThisOb.style.display = ( oThisOb.style.display == 'block' ) ? 'none' : 'block'; }
}
function stateToFromStr(oID,oFStr) {
	if( !document.getElementsByTagName || !document.childNodes || !document.createElement ) { return ''; }
	var baseElement = document.getElementById( oID ); if( !baseElement ) { return ''; }
	if( !oFStr && typeof(oFStr) != 'undefined' ) { return ''; } if( oFStr ) { oFStr = oFStr.split(':'); }
	for( var oStr = '', l = baseElement.getElementsByTagName(baseElement.tagName), x = 0; l[x]; x++ ) {
		if( oFStr && MWJisInTheArray( l[x].MWJuniqueID, oFStr ) && l[x].style.display == 'none' ) { l[x].parentNode.getElementsByTagName('a')[0].onclick(); }
		else if( l[x].style.display != 'none' ) { oStr += (oStr?':':'') + l[x].MWJuniqueID; }
	}
	return oStr;
}
function MWJisInTheArray(oNeed,oHay) { for( var i = 0; i < oHay.length; i++ ) { if( oNeed == oHay[i] ) { return true; } } return false; }
function selfLink(oRootElement,oClass,oExpand) {
	if(!document.getElementsByTagName||!document.childNodes) { return; }
	oRootElement = document.getElementById(oRootElement);
	for( var x = 0, y = oRootElement.getElementsByTagName('a'); y[x]; x++ ) {
		if( y[x].getAttribute('href') && !y[x].href.match(/#$/) && getRealAddress(y[x]) == getRealAddress(location) ) {
			y[x].className = (y[x].className?(y[x].className+' '):'') + oClass;
			if( oExpand ) {
				oExpand = false;
				for( var oEl = y[x].parentNode, ulStr = ''; oEl != oRootElement && oEl != document.body; oEl = oEl.parentNode ) {
					if( oEl.tagName && oEl.tagName == oRootElement.tagName ) { ulStr = oEl.MWJuniqueID + (ulStr?(':'+ulStr):''); } }
				stateToFromStr(oRootElement.id,ulStr);
} } } }
function getRealAddress(oOb) { return oOb.protocol + ( ( oOb.protocol.indexOf( ':' ) + 1 ) ? '' : ':' ) + oOb.hostname + ( ( typeof(oOb.pathname) == typeof(' ') && oOb.pathname.indexOf('/') != 0 ) ? '/' : '' ) + oOb.pathname + oOb.search; }

compactMenu('colapse',false,'');
//--></script>

		<?
		// Standard html footers
		end_main_frame();
}
elseif (isset($_GET['dllist'])) {
	$downloaders = array();
	$seeders = array();
	$subres = sql_query("SELECT seeder, finishedat, downloadoffset, uploadoffset, peers.ip, port, peers.uploaded, peers.downloaded, to_go, started AS st, connectable, agent, peer_id, peers.last_action AS la, prev_action AS pa, userid, users.username, users.class,torrents.size FROM peers LEFT JOIN torrents ON peers.torrent=torrents.id INNER JOIN users ON peers.userid = users.id WHERE torrent = $id") or sqlerr(__FILE__, __LINE__);
	while ($subrow = mysql_fetch_array($subres)) {
		if ($subrow["seeder"])
		$seeders[] = $subrow;
		else
		$downloaders[] = $subrow;
		$row['size'] = $subrow['size'];
	}

	function leech_sort($a,$b) {
		if ( isset( $_GET["usort"] ) ) return seed_sort($a,$b);
		$x = $a["to_go"];
		$y = $b["to_go"];
		if ($x == $y)
		return 0;
		if ($x < $y)
		return -1;
		return 1;
	}
	function seed_sort($a,$b) {
		$x = $a["uploaded"];
		$y = $b["uploaded"];
		if ($x == $y)
		return 0;
		if ($x < $y)
		return 1;
		return -1;
	}

	usort($seeders, "seed_sort");
	usort($downloaders, "leech_sort");
	print '<table>';
	tr("<div id=\"seeders\"></div>".$tracker_lang['details_seeding'], dltable($tracker_lang['details_seeding'], $seeders, $row), 1);
	tr("<div id=\"leechers\"></div>".$tracker_lang['details_leeching'], dltable($tracker_lang['details_leeching'], $downloaders, $row), 1);
	print '</table>';
}
stdfoot();

?>