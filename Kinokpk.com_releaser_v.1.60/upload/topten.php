<?php

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

require "include/bittorrent.php";

gzip();

dbconn(false);

loggedinorreturn();

  function usertable($res, $frame_caption)
  {
  	global $CURUSER;
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead>Место</td>
<td class=colhead align=left>Пользователь</td>
<td class=colhead>Раздач</td>
<td class=colhead align=left>Скорость раздачи</td>
<td class=colhead>Закачал</td>
<td class=colhead align=left>Скорость закачки</td>
<td class=colhead align=right>Рейтинг</td>
<td class=colhead align=left>Зарегистрирован</td>

</tr>
<?
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      $highlight = $CURUSER["id"] == $a["userid"] ? " bgcolor=#BBAF9B" : "";
      if ($a["downloaded"])
      {
        $ratio = $a["uploaded"] / $a["downloaded"];
        $color = get_ratio_color($ratio);
        $ratio = number_format($ratio, 2);
        if ($color)
          $ratio = "<font color=$color>$ratio</font>";
      }
      else
        $ratio = "Inf.";
      print("<tr$highlight><td align=center>$num</td><td align=left$highlight><a href=userdetails.php?id=" .
      		$a["userid"] . "><b>" . $a["username"] . "</b>" .
      		"</td><td align=right$highlight>" . mksize($a["uploaded"]) .
					"</td><td align=right$highlight>" . mksize($a["upspeed"]) . "/s" .
         	"</td><td align=right$highlight>" . mksize($a["downloaded"]) .
      		"</td><td align=right$highlight>" . mksize($a["downspeed"]) . "/s" .
      		"</td><td align=right$highlight>" . $ratio .
      		"</td><td align=left>" . date("Y-m-d",strtotime($a["added"])) . " (" .
      		get_elapsed_time(sql_timestamp_to_unix_timestamp($a["added"])) . " назад)</td></tr>");
    }
    end_table();
    end_frame();
  }

  function _torrenttable($res, $frame_caption)
  {
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead align=center>Место</td>
<td class=colhead align=left>Название</td>
<td class=colhead align=right>Скачено</td>
<td class=colhead align=right>Данные</td>
<td class=colhead align=right>Раздающих</td>
<td class=colhead align=right>Качающих</td>
<td class=colhead align=right>Всего</td>
<td class=colhead align=right>Рейтинг</td>
</tr>
<?
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      if ($a["leechers"])
      {
        $r = $a["seeders"] / $a["leechers"];
        $ratio = "<font color=" . get_ratio_color($r) . ">" . number_format($r, 2) . "</font>";
      }
      else
        $ratio = "Inf.";
      print("<tr><td align=center>$num</td><td align=left><a href=details.php?id=" . $a["id"] . "&hit=1><b>" .
        $a["name"] . "</b></a></td><td align=right>" . number_format($a["times_completed"]) .
				"</td><td align=right>" . mksize($a["data"]) . "</td><td align=right>" . number_format($a["seeders"]) .
        "</td><td align=right>" . number_format($a["leechers"]) . "</td><td align=right>" . ($a["leechers"] + $a["seeders"]) .
        "</td><td align=right>$ratio</td>\n");
    }
    end_table();
    end_frame();
  }

  function countriestable($res, $frame_caption, $what)
  {
    global $CURUSER;
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead>Место</td>
<td class=colhead align=left>Страна</td>
<td class=colhead align=right><?=$what?></td>
</tr>
<?
  	$num = 0;
		while ($a = mysql_fetch_assoc($res))
		{
	    ++$num;
	    if ($what == "Пользователи")
	      $value = number_format($a["num"]);
	    elseif ($what == "Раздача")
	      $value = mksize($a["ul"]);
	    elseif ($what == "Среднее")
	    	$value = mksize($a["ul_avg"]);
 	    elseif ($what == "Рейтинг")
 	    	$value = number_format($a["r"],2);
	    print("<tr><td align=center>$num</td><td align=left><table border=0 class=main cellspacing=0 cellpadding=0><tr><td class=embedded>".
	      "<img align=center src=pic/flag/$a[flagpic]></td><td class=embedded style='padding-left: 5px'><b>$a[name]</b></td>".
	      "</tr></table></td><td align=right>$value</td></tr>\n");
	  }
    end_table();
    end_frame();
  }

  function peerstable($res, $frame_caption)
  {
    begin_frame($frame_caption, true);
    begin_table();

		print("<tr><td class=colhead>Rank</td><td class=colhead>Username</td><td class=colhead>Upload rate</td><td class=colhead>Download rate</td></tr>");

		$n = 1;
		while ($arr = mysql_fetch_assoc($res))
		{
      $highlight = $CURUSER["id"] == $arr["userid"] ? " bgcolor=#BBAF9B" : "";
			print("<tr><td$highlight>$n</td><td$highlight><a href=userdetails.php?id=" . $arr["userid"] . "><b>" . $arr["username"] . "</b></td><td$highlight>" . mksize($arr["uprate"]) . "/s</td><td$highlight>" . mksize($arr["downrate"]) . "/s</td></tr>\n");
			++$n;
		}

    end_table();
    end_frame();
  }

  stdhead("Top 10");
  begin_main_frame();
//  $r = sql_query("SELECT * FROM users ORDER BY donated DESC, username LIMIT 100") or die;
//  donortable($r, "Top 10 Donors");
	$type = isset($_GET["type"]) ? 0 + $_GET["type"] : 0;
	if (!in_array($type,array(1,2,3,4)))
		$type = 1;
	$limit = isset($_GET["lim"]) ? 0 + $_GET["lim"] : false;
	$subtype = isset($_GET["subtype"]) ? $_GET["subtype"] : false;

	print("<p align=center>"  .
		($type == 1 && !$limit ? "<b>Пользователи</b>" : "<a href=topten.php?type=1>Пользователи</a>") .	" | " .
 		($type == 2 && !$limit ? "<b>Торренты</b>" : "<a href=topten.php?type=2>Торренты</a>") . " | " .
		($type == 3 && !$limit ? "<b>Страны</b>" : "<a href=topten.php?type=3>Страны</a>") . " | " .
		($type == 4 && !$limit ? "<b>Пиры</b>" : "<a href=topten.php?type=4>Пиры</a>") . "</p>\n");

	$pu = get_user_class() >= UC_POWER_USER;

  if (!$pu)
  	$limit = 10;

  if ($type == 1)
  {
    $mainquery = "SELECT id as userid, username, added, uploaded, downloaded, uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS upspeed, downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS downspeed FROM users WHERE enabled = 'yes'";

  	if (!$limit || $limit > 250)
  		$limit = 10;

  	if ($limit == 10 || $subtype == "ul")
  	{
			$order = "uploaded DESC";
			$r = sql_query($mainquery . " ORDER BY $order " . " LIMIT $limit") or sqlerr(__FILE__, __LINE__);
	  	usertable($r, "Top $limit заливающих" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=ul>Top 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=ul>Top 250</a>]</font>" : ""));
	  }

    if ($limit == 10 || $subtype == "dl")
  	{
			$order = "downloaded DESC";
		  $r = sql_query($mainquery . " ORDER BY $order " . " LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  usertable($r, "Top $limit качающих" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=dl>Top 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=dl>Top 250</a>]</font>" : ""));
	  }

    if ($limit == 10 || $subtype == "uls")
  	{
			$order = "upspeed DESC";
			$r = sql_query($mainquery . " ORDER BY $order " . " LIMIT $limit") or sqlerr(__FILE__, __LINE__);
	  	usertable($r, "Top $limit быстрейших заливающих <font class=small>(среднее, включая период неактивности)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=uls>Top 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=uls>Top 250</a>]</font>" : ""));
	  }

    if ($limit == 10 || $subtype == "dls")
  	{
			$order = "downspeed DESC";
			$r = sql_query($mainquery . " ORDER BY $order " . " LIMIT $limit") or sqlerr(__FILE__, __LINE__);
	  	usertable($r, "Top $limit быстрейших качающих <font class=small>(среднее, включая период неактивности)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=dls>Top 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=dls>Top 250</a>]</font>" : ""));
	  }

    if ($limit == 10 || $subtype == "bsh")
  	{
			$order = "uploaded / downloaded DESC";
			$extrawhere = " AND downloaded > 1073741824";
	  	$r = sql_query($mainquery . $extrawhere . " ORDER BY $order " . " LIMIT $limit") or sqlerr(__FILE__, __LINE__);
	  	usertable($r, "Top $limit лучших раздающих <font class=small>(минимум 1 GB скачано)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=bsh>Top 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=bsh>Top 250</a>]</font>" : ""));
		}

    if ($limit == 10 || $subtype == "wsh")
  	{
			$order = "uploaded / downloaded ASC, downloaded DESC";
  		$extrawhere = " AND downloaded > 1073741824";
	  	$r = sql_query($mainquery . $extrawhere . " ORDER BY $order " . " LIMIT $limit") or sqlerr(__FILE__, __LINE__);
	  	usertable($r, "Top $limit худших раздающих <font class=small>(минимум 1 GB скачано)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=wsh>Top 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=wsh>Top 250</a>]</font>" : ""));
	  }
  }

  elseif ($type == 2)
  {
   	if (!$limit || $limit > 50)
  		$limit = 10;

   	if ($limit == 10 || $subtype == "act")
  	{
		  $r = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY seeders + leechers DESC, seeders DESC, added ASC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  _torrenttable($r, "Top $limit Most Active Torrents" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=act>Top 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=act>Top 50</a>]</font>" : ""));
	  }

   	if ($limit == 10 || $subtype == "sna")
   	{
	  	$r = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY times_completed DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  _torrenttable($r, "Top $limit Most Snatched Torrents" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=sna>Top 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=sna>Top 50</a>]</font>" : ""));
	  }

   	if ($limit == 10 || $subtype == "mdt")
   	{
		  $r = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND leechers >= 5 AND times_completed > 0 GROUP BY t.id ORDER BY data DESC, added ASC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  _torrenttable($r, "Top $limit Most Data Transferred Torrents" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=mdt>Top 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=mdt>Top 50</a>]</font>" : ""));
		}

   	if ($limit == 10 || $subtype == "bse")
   	{
		  $r = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND seeders >= 5 GROUP BY t.id ORDER BY seeders / leechers DESC, seeders DESC, added ASC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
	  	_torrenttable($r, "Top $limit Best Seeded Torrents <font class=small>(with minimum 5 seeders)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=bse>Top 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=bse>Top 50</a>]</font>" : ""));
    }

   	if ($limit == 10 || $subtype == "wse")
   	{
		  $r = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND leechers >= 5 AND times_completed > 0 GROUP BY t.id ORDER BY seeders / leechers ASC, leechers DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  _torrenttable($r, "Top $limit Worst Seeded Torrents <font class=small>(with minimum 5 leechers, excluding unsnatched torrents)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=wse>Top 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=wse>Top 50</a>]</font>" : ""));
		}
  }
  elseif ($type == 3)
  {
  	if (!$limit || $limit > 25)
  		$limit = 10;

   	if ($limit == 10 || $subtype == "us")
   	{
		  $r = sql_query("SELECT name, flagpic, COUNT(users.country) as num FROM countries LEFT JOIN users ON users.country = countries.id GROUP BY name ORDER BY num DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  countriestable($r, "Top $limit Countries<font class=small> (users)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=3&amp;lim=25&amp;subtype=us>Top 25</a>]</font>" : ""),"Пользователи");
    }

   	if ($limit == 10 || $subtype == "ul")
   	{
	  	$r = sql_query("SELECT c.name, c.flagpic, sum(u.uploaded) AS ul FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = 'yes' GROUP BY c.name ORDER BY ul DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  countriestable($r, "Top $limit Countries<font class=small> (total uploaded)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=3&amp;lim=25&amp;subtype=ul>Top 25</a>]</font>" : ""),"Раздача");
    }

		if ($limit == 10 || $subtype == "avg")
		{
		  $r = sql_query("SELECT c.name, c.flagpic, sum(u.uploaded)/count(u.id) AS ul_avg FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = 'yes' GROUP BY c.name HAVING sum(u.uploaded) > 1099511627776 AND count(u.id) >= 100 ORDER BY ul_avg DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  countriestable($r, "Top $limit Countries<font class=small> (average total uploaded per user, with minimum 1TB uploaded and 100 users)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=3&amp;lim=25&amp;subtype=avg>Top 25</a>]</font>" : ""),"Среднее");
    }

		if ($limit == 10 || $subtype == "r")
		{
		  $r = sql_query("SELECT c.name, c.flagpic, sum(u.uploaded)/sum(u.downloaded) AS r FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = 'yes' GROUP BY c.name HAVING sum(u.uploaded) > 1099511627776 AND sum(u.downloaded) > 1099511627776 AND count(u.id) >= 100 ORDER BY r DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
		  countriestable($r, "Top $limit Countries<font class=small> (ratio, with minimum 1TB uploaded, 1TB downloaded and 100 users)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=3&amp;lim=25&amp;subtype=r>Top 25</a>]</font>" : ""),"Рейитнг");
	  }
  }
	elseif ($type == 4)
	{
		print("<h1 align=center><font color=red>Under construction!</font></h1>\n");
  	if (!$limit || $limit > 250)
  		$limit = 10;

	    if ($limit == 10 || $subtype == "ul")
  		{
//				$r = sql_query("SELECT users.id AS userid, peers.id AS peerid, username, peers.uploaded, peers.downloaded, peers.uploaded / (UNIX_TIMESTAMP(NOW()) - (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_action)) - UNIX_TIMESTAMP(started)) AS uprate, peers.downloaded / (UNIX_TIMESTAMP(NOW()) - (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_action)) - UNIX_TIMESTAMP(started)) AS downrate FROM peers LEFT JOIN users ON peers.userid = users.id ORDER BY uprate DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
//				peerstable($r, "Top $limit Fastest Uploaders" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=4&amp;lim=100&amp;subtype=ul>Top 100</a>] - [<a href=topten.php?type=4&amp;lim=250&amp;subtype=ul>Top 250</a>]</font>" : ""));

//				$r = sql_query("SELECT users.id AS userid, peers.id AS peerid, username, peers.uploaded, peers.downloaded, (peers.uploaded - peers.uploadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS uprate, (peers.downloaded - peers.downloadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS downrate FROM peers LEFT JOIN users ON peers.userid = users.id ORDER BY uprate DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
//				peerstable($r, "Top $limit Fastest Uploaders (timeout corrected)" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=4&amp;lim=100&amp;subtype=ul>Top 100</a>] - [<a href=topten.php?type=4&amp;lim=250&amp;subtype=ul>Top 250</a>]</font>" : ""));

				$r = sql_query( "SELECT users.id AS userid, username, (peers.uploaded - peers.uploadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS uprate, IF(seeder = 'yes',(peers.downloaded - peers.downloadoffset)  / (finishedat - UNIX_TIMESTAMP(started)),(peers.downloaded - peers.downloadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started))) AS downrate FROM peers LEFT JOIN users ON peers.userid = users.id ORDER BY uprate DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
				peerstable($r, "Top $limit Fastest Uploaders" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=4&amp;lim=100&amp;subtype=ul>Top 100</a>] - [<a href=topten.php?type=4&amp;lim=250&amp;subtype=ul>Top 250</a>]</font>" : ""));
	  	}

	    if ($limit == 10 || $subtype == "dl")
  		{
//				$r = sql_query("SELECT users.id AS userid, peers.id AS peerid, username, peers.uploaded, peers.downloaded, (peers.uploaded - peers.uploadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS uprate, (peers.downloaded - peers.downloadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS downrate FROM peers LEFT JOIN users ON peers.userid = users.id ORDER BY downrate DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
//				peerstable($r, "Top $limit Fastest Downloaders (timeout corrected)" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=4&amp;lim=100&amp;subtype=dl>Top 100</a>] - [<a href=topten.php?type=4&amp;lim=250&amp;subtype=dl>Top 250</a>]</font>" : ""));

				$r = sql_query("SELECT users.id AS userid, peers.id AS peerid, username, peers.uploaded, peers.downloaded,(peers.uploaded - peers.uploadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started)) AS uprate, IF(seeder = 'yes',(peers.downloaded - peers.downloadoffset)  / (finishedat - UNIX_TIMESTAMP(started)),(peers.downloaded - peers.downloadoffset) / (UNIX_TIMESTAMP(last_action) - UNIX_TIMESTAMP(started))) AS downrate FROM peers LEFT JOIN users ON peers.userid = users.id ORDER BY downrate DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__);
				peerstable($r, "Top $limit Fastest Downloaders" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=4&amp;lim=100&amp;subtype=dl>Top 100</a>] - [<a href=topten.php?type=4&amp;lim=250&amp;subtype=dl>Top 250</a>]</font>" : ""));
	  	}
	}
  end_main_frame();
  //print("<p><font class=small>Started recording account xfer stats on 2003-08-31</font></p>");
  stdfoot();
?>


