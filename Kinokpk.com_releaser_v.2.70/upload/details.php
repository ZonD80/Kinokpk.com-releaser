<?

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

require_once("include/bittorrent.php");

dbconn();

getlang('details');

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;

	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

	$page = (int) $_GET["page"];

} else $ajax=0;

gzip();

if (!$ajax) {
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
		elseif (preg_match("^Deadman Walking", $httpagent))
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
		elseif (preg_match("^0P3R4H", $httpagent))
		return "Opera BT Client";
		elseif (substr($peer_id, 0, 6) == "A310--")
		return "ABC/3.1";
		elseif (preg_match("^XBT Client", $httpagent))
		return "XBT Client";
		elseif (preg_match("^BitTorrent\/BitSpirit$", $httpagent))
		return "BitSpirit";
		elseif (preg_match("^DansClient", $httpagent))
		return "XanTorrent";
		else
		return "Unknown";
	}

	function dltable($name, $arr, $torrent)
	{

		global $CURUSER, $tracker_lang, $row;
		if ($name == $tracker_lang['details_seeding']) $inclusion=$row['remote_seeders']; else $inclusion=$row['remote_leechers'];
		$s = "<b>" . count($arr) . " $name</b> ".sprintf($tracker_lang['include_remote'],$inclusion,$name)."\n";
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
		$now = time();
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
			$s .= "<td align=\"center\">" . ($e[connectable] ? "<span style=\"color: green; cursor: help;\" title=\"Порт открыт. Этот пир может подключатся к любому пиру.\">".$tracker_lang['yes']."</span>" : "<span style=\"color: red; cursor: help;\" title=\"Порт закрыт. Рекомендовано проверить настройки Firwewall'а.\">".$tracker_lang['no']."</span>") . "</td>\n";
			$s .= "<td align=\"right\"><nobr>" . mksize($e["uploaded"]) . "</nobr></td>\n";
			$s .= "<td align=\"right\"><nobr>" . mksize($e["uploadoffset"] / $secs) . "/s</nobr></td>\n";
			$s .= "<td align=\"right\"><nobr>" . mksize($e["downloaded"]) . "</nobr></td>\n";
			//if (!$e["seeder"])
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

}

if (!is_valid_id($_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$id = (int) $_GET["id"];



$res = sql_query("SELECT torrents.category, torrents.free, torrents.ratingsum, torrents.descr, torrents.seeders+torrents.remote_seeders AS seeders, torrents.remote_seeders, torrents.remote_leechers, torrents.leechers+torrents.remote_leechers AS leechers, torrents.banned, torrents.info_hash, torrents.topic_id, torrents.filename, torrents.last_action AS lastseed, torrents.name, torrents.owner, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.ismulti, torrents.numfiles, torrents.images, torrents.online, torrents.moderatedby, (SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, (SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, users.username, users.ratingsum AS userrating, users.class FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id")
or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);

$owned = $moderator = 0;
if (get_user_class() >= UC_MODERATOR)
$owned = $moderator = 1;
elseif ($CURUSER["id"] == $row["owner"])
$owned = 1;

if (!$row || ($row["banned"] && !$moderator))
stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);
else {
	if (!$ajax) {
		stdhead($tracker_lang['torrent_details']." \"" . $row["name"] . "\"");

		if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR || ($row["filename"] == "nofile" && (get_user_class() == UC_UPLOADER)))
		$owned = 1;
		else
		$owned = 0;

		$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

		if ($_GET["report"])
		stdmsg("Успешно", "Жалоба отправлена администрации");
		elseif (($_GET["alreadyreport"]))
		{
			stdmsg("Ошибка", "Вы уже отправляли жалобу на этот торрент");
		}
		elseif(($_GET["ownreport"]))
		{
			stdmsg("Ошибка", "Вы не можете подать жалобу на собственную раздачу");
		}

		print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
		print("<tr><td class=\"colhead\" colspan=\"2\"><div style=\"float: left; width: auto;\">:: ".$tracker_lang['torrent_details']."</div><div align=\"right\"><a href=\"details.php?id=$id#comments\"><b>{$tracker_lang['add_comment']}</b></a> | <a href=\"bookmark.php?torrent=$row[id]\"><b>Добавить в избранное</b></a> | <a href=\"exportrelease.php?id=$id\"><small>Экспортировать на сайт</small></a></div></td></tr>");
		$url = "edit.php?id=" . $row["id"];
		if (isset($_GET["returnto"])) {
			$addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
			$url .= $addthis;
			$keepget .= $addthis;
		}
		$editlink = "a href=\"$url\" class=\"sublink\"";

		if ($owned)
		{ $s="<br />";
		if  ($row["filename"] == "nofile" && (get_user_class() == UC_UPLOADER)) {
			$s .= " $spacer<$editlink>[Редактрировать для добавления торрента]</a>";
		} else {$s .= " $spacer<$editlink>[".$tracker_lang['edit']."]</a>";} }

		tr ($tracker_lang['name'].'<br /><b>'.$tracker_lang['download'].'</b>'.$s, "<h1>".(($row['free'])?"<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\"/>&nbsp;":'')."<a class=\"index\" href=\"download.php?id=$id\">" . $row["name"] . "</a></h1>", 1, 1, "10%");

		function hex_esc($matches) {
			return sprintf("%02x", ord($matches[0]));
		}

		// make main category and childs

		$tree = make_tree();

		$cats = explode(',',$row['category']);
		$cat= array_shift($cats);
		$cat = get_cur_branch($tree,$cat);
		$childs = get_childs($tree,$cat['parent_id']);
		if ($childs) {
			foreach($childs as $child)
			if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"browse.php?cat={$child['id']}\">".makesafe($child['name'])."</a>";
		}
		tr ($tracker_lang['type'],get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):''),1);
		//tr ($tracker_lang['subcats'],implode(', ',$chsel),1);

		tr($tracker_lang['info_hash'], $row["info_hash"]);
		tr($tracker_lang['check'],'<div id="checkfield">'.($row['moderatedby']?$tracker_lang['checked_by'].'</font><a href="userdetails.php?id='.$row['moderatedby'].'">'.get_user_class_color($row['modclass'],$row['modname']).'</a> '.((get_user_class() >= UC_MODERATOR)?'<a onclick="return ajaxcheck();" href="takeedit.php?checkonly&id='.$id.'">'.$tracker_lang['uncheck'].'</a>':''):$tracker_lang['not_yet_checked'].((get_user_class() >= UC_MODERATOR)?' <a onclick="return ajaxcheck();" href="takeedit.php?checkonly&id='.$id.'">'.$tracker_lang['check'].'</a>':'')).'</div>',1);
		$spbegin = "<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>{$tracker_lang['screens']} ({$tracker_lang['view']})</i></td></tr></table></div><div class=\"news-body\">";
		$spend = "</div></div>";

		if ($row['images']) {
			$images = explode(',',$row['images']);

			$k = 0;
			foreach ($images as $img) {
				$k++;

				$img = "<a href=\"$img\" target=\"_blank\"><img style=\"border: 2px dashed #c1d0d8;\" alt='Изображение для ".$row["name"]." (кликните для просмотра полного изображения)' width=\"240\" src=\"$img\" /></a><br />";
				if ($k<=1) $imgcontent.=$img; else $imgspoiler.=$img;

			} }

			print ('<tr><td colspan="2"><table width="100%"><tr><td style="vertical-align: top;">'.($imgcontent?$imgcontent:'<img src="pic/noimage.gif"/>').(!empty($imgspoiler)?$spbegin.$imgspoiler.$spend:'').'</td><td style="vertical-align: top; text-align:left;">'.format_comment($row['descr']).'</td></tr></table></td></tr>');

			if ($CACHEARRAY['use_integration']) {
				tr("Релиз на форуме {$CACHEARRAY['forumname']}","<a href=\"{$CACHEARRAY['forumurl']}/index.php?showtopic=".$row['topic_id']."\">{$CACHEARRAY['forumurl']}/index.php?showtopic=".$row['topic_id']."</a>",1);
				$topicid = $row['topic_id'];
			}

			if (!$CURUSER)    {             print("</table>\n"); stdfoot(); die(); }
			//    if (!empty($row['online']))
			// tr("Смотреть онлайн<br />","<form method=\"post\" action=\"online/onlinevideo.php\"><input type=\"hidden\" name=\"onlinevideo\" value=\"".$row['online']."\"><input type=\"submit\" value=\"Смотреть онлайн на $CACHEARRAY['defaultbaseurl']\" /></form> <b>ТОЛЬКО ДЛЯ КПК НА WINDOWS MOBILE 5.0 ВЫШЕ! ФОРМАТ WMV</b>",1,1);
			if (!$row["visible"])
			tr($tracker_lang['visible'], "<b>".$tracker_lang['no']."</b> (".$tracker_lang['dead'].")", 1);
			if ($moderator)
			tr($tracker_lang['banned'], (!$row["banned"] ? $tracker_lang['no'] : $tracker_lang['yes']) );


			if ($row['filename'] != 'nofile') tr($tracker_lang['seeder'], $tracker_lang['seeder_last_seen']." ".get_elapsed_time($row["lastseed"]) . " ".$tracker_lang['ago']);
			tr($tracker_lang['size'],mksize($row["size"]) . " (" . number_format($row["size"]) . " ".$tracker_lang['bytes'].")");

			tr($tracker_lang['added'], mkprettytime($row["added"]));
			tr($tracker_lang['views'], $row["views"]);

			if ($row['filename'] != 'nofile') {
				tr($tracker_lang['hits'], $row["hits"]);
				tr($tracker_lang['snatched'], $row["times_completed"] . " ".$tracker_lang['times']);
			}
			$keepget = "";
			$uprow = (isset($row["username"]) ? ("<a href=userdetails.php?id=" . $row["owner"] . ">" . get_user_class_color($row['class'],$row["username"]) . "</a>") : "<i>Аноним</i>");
			/*
			 if ($owned)
			 $uprow .= " $spacer<$editlink><b>[".$tracker_lang['edit']."]</b></a>";
			 */

			tr("Выложил", $uprow.$spacer.ratearea($row['userrating'],$row['owner'],'users'), 1);
			tr($tracker_lang['vote'],ratearea($row['ratingsum'],$id,'torrents'),1);

			if ($row["ismulti"]) {
				if (!$_GET["filelist"])
				tr($tracker_lang['files']."<br /><a href=\"details.php?id=$id&amp;filelist=1$keepget#filelist\" class=\"sublink\">[".$tracker_lang['open_list']."]</a>", $row["numfiles"] . " ".$tracker_lang['files_l'], 1);
				else {
					tr($tracker_lang['files'], $row["numfiles"] . " ".$tracker_lang['files_l'], 1);

					$s = "<table class=main border=\"1\" cellspacing=0 cellpadding=\"5\">\n";

					$subres = sql_query("SELECT * FROM files WHERE torrent = $id ORDER BY id");
					$s.="<tr><td class=colhead>".$tracker_lang['path']."</td><td class=colhead align=right>".$tracker_lang['size']."</td></tr>\n";
					while ($subrow = mysql_fetch_array($subres)) {
						$s .= "<tr><td>" . $subrow["filename"] .
                            			"</td><td align=\"right\">" . mksize($subrow["size"]) . "</td></tr>\n";
					}

					$s .= "</table>\n";
					tr("<a name=\"filelist\">".$tracker_lang['file_list']."</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[".$tracker_lang['close_list']."]</a>", $s, 1);
				}
			}

			if (!$_GET["dllist"]) {
				if ($row['filename'] != 'nofile')
				tr($tracker_lang['downloading']."<br /><a href=\"details.php?id=$id&amp;dllist=1$keepget#seeders\" class=\"sublink\">[".$tracker_lang['open_list']."]</a>", $row["seeders"] . " ".$tracker_lang['seeders_l'].", " . $row["leechers"] . " ".$tracker_lang['leechers_l']." = " . ($row["seeders"] + $row["leechers"]) . " ".$tracker_lang['peers_l'], 1);
			} else {
				$downloaders = array();
				$seeders = array();
				$subres = sql_query("SELECT seeder, finishedat, downloadoffset, uploadoffset, peers.ip, port, peers.uploaded, peers.downloaded, to_go, started AS st, connectable, agent, peer_id, last_action AS la, prev_action AS pa, userid, users.username, users.class FROM peers INNER JOIN users ON peers.userid = users.id WHERE torrent = $id") or sqlerr(__FILE__, __LINE__);
				while ($subrow = mysql_fetch_array($subres)) {
					if ($subrow["seeder"])
					$seeders[] = $subrow;
					else
					$downloaders[] = $subrow;
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

				tr("<a name=\"seeders\">".$tracker_lang['details_seeding']."</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[".$tracker_lang['close_list']."]</a>", dltable($tracker_lang['details_seeding'], $seeders, $row), 1);
				tr("<a name=\"leechers\">".$tracker_lang['details_leeching']."</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[".$tracker_lang['close_list']."]</a>", dltable($tracker_lang['details_leeching'], $downloaders, $row), 1);
			}

			if ($row["times_completed"] > 0) {
				$res = sql_query("SELECT users.id, users.username, users.title, users.uploaded, users.downloaded, users.donor, users.enabled, users.warned, users.last_access, users.class, snatched.startedat, snatched.last_action, snatched.completedat, snatched.seeder, snatched.userid, snatched.uploaded AS sn_up, snatched.downloaded AS sn_dn FROM snatched INNER JOIN users ON snatched.userid = users.id WHERE snatched.finished=1 AND snatched.torrent =" . sqlesc($id) . " ORDER BY users.class DESC $limit") or sqlerr(__FILE__,__LINE__);
				$snatched_full = "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
				$snatched_full .= "<tr><td class=colhead>Юзер</td><td class=colhead>Раздал</td><td class=colhead>Скачал</td><td class=colhead>Рейтинг</td><td class=colhead align=center>Начал / Закончил</td><td class=colhead align=center>Действие</td><td class=colhead align=center>Сидирует</td><td class=colhead align=center>ЛС</td></tr>";

				while ($arr = mysql_fetch_assoc($res)) {
					//start Global
					if ($arr["downloaded"] > 0) {
						$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
						//  $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
					}
					else if ($arr["uploaded"] > 0)
					$ratio = "Inf.";
					else
					$ratio = "---";
					$uploaded = mksize($arr["uploaded"]);
					$downloaded = mksize($arr["downloaded"]);
					//start torrent
					if ($arr["sn_dn"] > 0) {
						$ratio2 = number_format($arr["sn_up"] / $arr["sn_dn"], 2);
						$ratio2 = "<font color=" . get_ratio_color($ratio2) . ">$ratio2</font>";
					}
					else
					if ($arr["sn_up"] > 0)
					$ratio2 = "Inf.";
					else
					$ratio2 = "---";
					$uploaded2 = mksize($arr["sn_up"]);
					$downloaded2 = mksize($arr["sn_dn"]);
					//end
					//$highlight = $CURUSER["id"] == $arr["id"] ? " bgcolor=#00A527" : "";;
					$snatched_small[] = "<a href=userdetails.php?id=$arr[userid]>".get_user_class_color($arr["class"], $arr["username"])." (<font color=" . get_ratio_color($ratio) . ">$ratio</font>)</a>";
					$snatched_full .= "<tr$highlight><td><a href=userdetails.php?id=$arr[userid]>".get_user_class_color($arr["class"], $arr["username"])."</a>".get_user_icons($arr)."</td><td><nobr>$uploaded&nbsp;Общего<br />$uploaded2&nbsp;Торрент</nobr></td><td><nobr>$downloaded&nbsp;Общего<br />$downloaded2&nbsp;Торрент</nobr></td><td><nobr>$ratio&nbsp;Общего<br />$ratio2&nbsp;Торрент</nobr></td><td align=center><nobr>" . $arr["startedat"] . "<br />" . $arr["completedat"] . "</nobr></td><td align=center><nobr>" . $arr["last_action"] . "</nobr></td><td align=center>" . ($arr["seeder"] ? "<b><font color=green>Да</font>" : "<font color=red>Нет</font></b>") .
							"</td><td align=center><a href=message.php?action=sendmessage&amp;receiver=$arr[userid]><img src=pic/button_pm.gif border=\"0\"></a></td></tr>\n";
				}
				$snatched_full .= "</table>\n";

				if ($row["seeders"] == 0 || ($row["leechers"] / $row["seeders"] >= 2))
				$reseed_button = "<form action=\"takereseed.php\"><input type=\"hidden\" name=\"torrent\" value=\"$id\" /><input type=\"submit\" value=\"Позвать скачавших\" /></form>";
				if (!$_GET["snatched"]==1)
				tr("Скачавшие<br /><a href=\"details.php?id=$id&amp;snatched=1#snatched\" class=\"sublink\">[Посмотреть список]</a>", "<div class=\"news-wrap\"><div class=\"news-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Открыть</i></td></tr></table></div><div class=\"news-body\">".@implode(", ", $snatched_small).$reseed_button.'</div></div>', 1);
				else
				tr("Скачавшие<br /><a href=\"details.php?id=$id\" class=\"sublink\" name=\"snatched\">[Cпрятать список]</a>", $snatched_full,1);
			}
			if ($row['filename'] != 'nofile') tr($tracker_lang['torrent_info'], "<a href=\"torrent_info.php?id=$id\">".$tracker_lang['show_data']."</a>", 1);



			$report_sql = sql_query("SELECT userid FROM report WHERE torrentid = $id");
			$report_row = mysql_fetch_assoc($report_sql);
			if ($CURUSER["id"] != $row["owner"] AND $report_row["userid"] != $CURUSER["id"])
			if ($CURUSER["id"] != $row["owner"])
			tr("Пожаловаться", "<form method=\"post\" action=\"report.php\">&nbsp;<input name=\"motive\" cols=\"40\" OnFocus=\"this.value=''\" value=\"Причина\">&nbsp;<input type=\"submit\" value=\"Отправить\" /><input type=\"hidden\" name=\"torrentid\" value=\"$id\"></form>", 1);

			?>
<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;
var switched = 0;

function ajaxcheck() {

      (function($){
      if ($) no_ajax = false;
   $("#checkfield").empty();
   $("#checkfield").append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    $.get("takeedit.php", { ajax: 1, checkonly: "", id: <?=$id;?> }, function(data){
   $("#checkfield").empty();
   $("#checkfield").append(data);
});
})(jQuery);

return no_ajax;

}

function pageswitcher(page) {

   (function($){
     if ($) no_ajax = false;
   $("#comments-table").empty();
   $("#comments-table").append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
   $.get("details.php", { ajax: 1, page: page, id: <?=$id;?> }, function(data){
   $("#comments-table").empty();
   $("#comments-table").append(data);

});
})(jQuery);

if (!switched){
window.location.href = window.location.href+"#comments-table";
switched++;
}
else window.location.href = window.location.href;

return no_ajax;
}
//]]>
</script>
			<?

			print("</table></p>\n");

			print("<div align=\"center\"><a href=\"#\" onclick=\"location.href='pass_on.php?to=pre&from=" .$id. "'; return false;\">
<< Предыдущий релиз</a>&nbsp;
<a href=\"#\" onclick=\"location.href='pass_on.php?to=pre&from=" .$id. "&cat=" .$row['category']. "'; return false;\">[из этой категории]</a>
&nbsp; | &nbsp;
<a href=\"#\" onclick=\"location.href='pass_on.php?to=next&from=" .$id. "&cat=" .$row['category']. "'; return false;\">[из этой категории]</a>&nbsp;
<a href=\"#\" onclick=\"location.href='pass_on.php?to=next&from=" .$id. "'; return false;\">
Следующий релиз >></a><br />
<a href=\"browse.php\">Все релизы</a>
&nbsp; | &nbsp;
<a href=\"browse.php?cat=" .$row['category']. "\">Все релизы этой категории</a></div>");

	}
}
$subres = sql_query("SELECT COUNT(*) FROM comments WHERE torrent = $id");
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];

$limited = 10;

if (!$count) {

	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев {$CACHEARRAY['defaultbaseurl']}</div>");
	print("<div align=\"right\"><a href=details.php?id=$id#comments class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("Комментариев нет. <a href=details.php?id=$id#comments>Желаете добавить?</a>");
	print("</td></tr></table><br />");

}
else {
	list($pagertop, $pagerbottom, $limit) = browsepager($limited, $count, "details.php?id=$id&", "#comments-table", array('lastpagedefault' => 1));

	$subres = sql_query("SELECT c.id, c.post_id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.gender, u.last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id WHERE torrent = " .
                  "$id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = array();
	if ($CACHEARRAY['use_integration']) {
		// ipb comment transfer
		$postids = array();
		// end, cont below
	}
	while ($subrow = mysql_fetch_array($subres))
	$allrows[] = $subrow;

	if ($CACHEARRAY['use_integration']) {
		// ipb comment transfer
		foreach ($allrows as $rows)
		$postids[] = $rows['post_id'];
		// end,cont below
	}

	print("<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
	print("<tr><td class=\"colhead\" align=\"center\" >");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
	print("<div align=\"right\"><a href=\"details.php?id=$id#comments\" class=\"altlink_white\">{$tracker_lang['add_comment']}</a></div>");
	print("</td></tr>");

	print("<tr><td>");
	print($pagertop);
	print("</td></tr>");
	print("<tr><td>");
	commenttable($allrows);
	print("</td></tr>");
	print("<tr><td>");
	print($pagerbottom);
	print("</td></tr>");
	print("</table>");
}

if (!$ajax) {
	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>:: {$tracker_lang['add_comment']} к релизу</b></td></tr>");
	print("<tr><td width=\"100%\" align=\"center\" >");
	//print("Ваше имя: ");
	//print("".$CURUSER['username']."<p>");
	print("<form name=comment method=\"post\" action=\"comment.php?action=add\">");
	print("<center><table border=\"0\"><tr><td class=\"clear\">");
	print("<div align=\"center\">". textbbcode("text","") ."</div>");
	print("</td></tr></table></center>");
	print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
	print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
	print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
	print("</td></tr></form></table>");
}

if ($CACHEARRAY['use_integration']) {
	if ($topicid <> 0) {

		// connecting to IPB DB
		forumconn();
		//connection opened

		if (count($postids) >= 1) $condition = "AND pid NOT IN (". implode(",", $postids) .")"; else $condition = "";

		$postsarray = sql_query("SELECT author_name, post, post_date FROM ".$fprefix."posts WHERE topic_id=".$topicid." AND new_topic<>1 ".$condition." ORDER BY post_date DESC LIMIT 5");
		$forumid = sql_query("SELECT forum_id FROM ".$fprefix."topics WHERE tid=".$topicid);
		$forumid = @mysql_result($forumid,0);

		if (!$forumid) sql_query("UPDATE torrents SET topic_id=0 WHERE id=$id"); else {

			while ($posts = mysql_fetch_array($postsarray)) {
				$count=1;
				if ($count == 1) { print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
				print("<tr><td class=\"colhead\" align=\"center\" >");
				print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев форума {$CACHEARRAY['forumname']}</div>");
				print("</td></tr>");
				print("<tr><td>");
				}
				print("<b><i>".$posts['author_name']."</i></b> от ".mkprettytime($posts['post_date']).":<br /><br />");
				print(str_replace("style_emoticons/<#EMO_DIR#>",$CACHEARRAY['forumurl']."/style_emoticons/".$CACHEARRAY['emo_dir'],$posts['post'])."<hr />");
				if ($count == 1) {
					print("</tr></td>");
					print("</table>");
				}
				$count++;
			}


			relconn();
		}
	}
}

if (!$ajax) {sql_query("UPDATE torrents SET views = views + 1 WHERE id = $id");

stdfoot();
}

?>
