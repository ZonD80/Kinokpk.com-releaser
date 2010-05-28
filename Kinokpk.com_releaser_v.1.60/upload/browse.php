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

gzip();

dbconn(false);

//loggedinorreturn();
parked();

$cats = genrelist();

$searchstr = unesc($_GET["search"]);
$cleansearchstr = htmlspecialchars($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

// sorting by MarkoStamcar

if ($_GET['sort'] && $_GET['type']) {

$column = '';
$ascdesc = '';

switch($_GET['sort']) {
case '1': $column = "name"; break;
case '2': $column = "numfiles"; break;
case '3': $column = "comments"; break;
case '4': $column = "added"; break;
case '5': $column = "size"; break;
case '6': $column = "times_completed"; break;
case '7': $column = "seeders"; break;
case '8': $column = "leechers"; break;
case '9': $column = "owner"; break;
case '10': if (get_user_class() >= UC_MODERATOR) $column = "moderatedby"; break;
default: $column = "added"; break;
}

    switch($_GET['type']) {
  case 'asc': $ascdesc = "ASC"; $linkascdesc = "asc"; break;
  case 'desc': $ascdesc = "DESC"; $linkascdesc = "desc"; break;
  default: $ascdesc = "DESC"; $linkascdesc = "desc"; break;
    }


$orderby = "ORDER BY torrents." . $column . " " . $ascdesc;
$pagerlink = "sort=" . intval($_GET['sort']) . "&type=" . $linkascdesc . "&";

} else {

$orderby = "ORDER BY torrents.sticky ASC, torrents.added DESC";
$pagerlink = "";

}

$addparam = "";
$wherea = array();
$wherecatina = array();

if ($_GET["incldead"] == 1)
{
        $addparam .= "incldead=1&amp;";
        if (!isset($CURUSER) || get_user_class() < UC_ADMINISTRATOR)
                $wherea[] = "banned != 'yes'";
}
elseif ($_GET["incldead"] == 2)
{
        $addparam .= "incldead=2&amp;";
                $wherea[] = "visible = 'no'";
}
elseif ($_GET["incldead"] == 3)
{
        $addparam .= "incldead=3&amp;";
                $wherea[] = "free = 'yes'";
               $wherea[] = "visible = 'yes'";
}
elseif ($_GET["incldead"] == 4)
{
        $addparam .= "incldead=4&amp;";
                $wherea[] = "seeders = 0";
                $wherea[] = "visible = 'yes'";
}
elseif ($_GET["incldead"] == 5)
{
        $addparam .= "incldead=5&amp;";
                $wherea[] = "filename = 'nofile'";

}
        else
                $wherea[] = "visible = 'yes'";

$category = (int)$_GET["cat"];

$all = $_GET["all"];

if (!$all)
        if (!$_GET && $CURUSER["notifs"])
        {
          $all = True;
          foreach ($cats as $cat)
          {
            $all &= $cat[id];
            if (strpos($CURUSER["notifs"], "[cat" . $cat[id] . "]") !== False)
            {
              $wherecatina[] = $cat[id];
              $addparam .= "c$cat[id]=1&amp;";
            }
          }
        }
        elseif ($category)
        {
          if (!is_valid_id($category))
            stderr($tracker_lang['error'], "Invalid category ID.");
          $wherecatina[] = $category;
          $addparam .= "cat=$category&amp;";
        }
        else
        {
          $all = True;
          foreach ($cats as $cat)
          {
            $all &= $_GET["c$cat[id]"];
            if ($_GET["c$cat[id]"])
            {
              $wherecatina[] = $cat[id];
              $addparam .= "c$cat[id]=1&amp;";
            }
          }
        }

if ($all)
{
        $wherecatina = array();
  $addparam = "";
}

if (count($wherecatina) > 1)
        $wherecatin = implode(",",$wherecatina);
elseif (count($wherecatina) == 1)
        $wherea[] = "category = $wherecatina[0]";

$wherebase = $wherea;

if (isset($cleansearchstr))
{
		$wherea[] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'";
        $addparam .= "search=" . urlencode($searchstr) . "&amp;";
}

$where = implode(" AND ", $wherea);
if ($wherecatin)
        $where .= ($where ? " AND " : "") . "category IN (" . $wherecatin . ")";

if ($where != "")
        $where = "WHERE $where";

$res = sql_query("SELECT COUNT(*) FROM torrents $where") or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];
$num_torrents = $count;

if (!$count && isset($cleansearchstr)) {
        $wherea = $wherebase;
        //$orderby = "ORDER BY id DESC";
        $searcha = explode(" ", $cleansearchstr);
        $sc = 0;
        foreach ($searcha as $searchss) {
                if (strlen($searchss) <= 1)
                        continue;
                $sc++;
                if ($sc > 6)
                        break;
                $ssa = array();
                $ssa[] = "torrents.name LIKE '%" . sqlwildcardesc($searchss) . "%'";
        }
        if ($sc) {
                $where = implode(" AND ", $wherea);
                if ($where != "")
                        $where = "WHERE $where";
                $res = sql_query("SELECT COUNT(*) FROM torrents $where");
                $row = mysql_fetch_array($res);
                $count = $row[0];
        }
}

$torrentsperpage = $CURUSER["torrentsperpage"];
if (!$torrentsperpage)
        $torrentsperpage = 25;

if ($count)
{
    if ($addparam != "") {
 if ($pagerlink != "") {
  if ($addparam{strlen($addparam)-1} != ";") { // & = &amp;
    $addparam = $addparam . "&" . $pagerlink;
  } else {
    $addparam = $addparam . $pagerlink;
  }
 }
    } else {
 $addparam = $pagerlink;
    }
        list($pagertop, $pagerbottom, $limit) = pager($torrentsperpage, $count, "browse.php?" . $addparam);
        $query = "SELECT torrents.id, torrents.moderated, torrents.moderatedby, torrents.category, torrents.image1, torrents.leechers, torrents.seeders, torrents.free, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.sticky, torrents.owner," .
        "IF(torrents.numratings < $minvotes, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, categories.name AS cat_name, categories.image AS cat_pic, users.username, users.class".($CURUSER ? ", EXISTS(SELECT * FROM readtorrents WHERE readtorrents.userid = ".sqlesc($CURUSER["id"])." AND readtorrents.torrentid = torrents.id) AS readtorrent" : ", 1 AS readtorrent")." FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id $where $orderby $limit";
        $res = sql_query($query) or die(mysql_error());
}
else
        unset($res);
if (isset($cleansearchstr))
        stdhead($tracker_lang['search_results_for']." \"$searchstr\"");
else
        stdhead($tracker_lang['browse']);

?>

<STYLE TYPE="text/css" MEDIA=screen>

  a.catlink:link, a.catlink:visited{
                text-decoration: none;
        }

        a.catlink:hover {
                color: #A83838;
        }

</STYLE>

<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
<tr><td class="colhead" align="center" colspan="12">Список релизов</td></tr>
<tr><td colspan="12">

<form method="get" action="browse.php">
<table class="embedded" align="center">
<tr>
<td class="bottom">
        <table class="bottom">
        <tr>

<?
$i = 0;
foreach ($cats as $cat)
{
        $catsperrow = 5;
        print(($i && $i % $catsperrow == 0) ? "</tr><tr>" : "");
        print("<td class=\"bottom\" style=\"padding-bottom: 2px;padding-left: 7px\"><input name=\"c$cat[id]\" type=\"checkbox\" " . (in_array($cat[id],$wherecatina) ? "checked " : "") . "value=\"1\"><a class=\"catlink\" href=\"browse.php?cat=$cat[id]\">" . htmlspecialchars($cat[name]) . "</a></td>\n");
        $i++;
}

$alllink = "<div align=\"left\">(<a href=\"browse.php?all=1\"><b>".$tracker_lang['show_all']."</b></a>)</div>";

$ncats = count($cats);
@$nrows = ceil($ncats/$catsperrow);
@$lastrowcols = $ncats % $catsperrow;

if ($lastrowcols != 0)
{
        if ($catsperrow - $lastrowcols != 1)
                {
                        print("<td class=\"bottom\" rowspan=\"" . ($catsperrow  - $lastrowcols - 1) . "\">&nbsp;</td>");
                }
}
?>
        </tr>
        </table>
</td>
</tr>
</form>
<tr><td class="embedded">
<form method="get" action="browse.php">
<center>
<?=$tracker_lang['search'];?>:
<input type="text" id="searchinput" name="search" size="40" autocomplete="off" ondblclick="suggest(event.keyCode,this.value);" onkeyup="suggest(event.keyCode,this.value);" onkeypress="return noenter(event.keyCode);" value="<?= htmlspecialchars($searchstr) ?>" />
<?=$tracker_lang['in'];?>
<select name="incldead">
<option value="0"><?=$tracker_lang['active'];?></option>
<option value="1"<? print($_GET["incldead"] == 1 ? " selected" : ""); ?>><?=$tracker_lang['including_dead'];?></option>
<option value="2"<? print($_GET["incldead"] == 2 ? " selected" : ""); ?>><?=$tracker_lang['only_dead'];?></option>
<option value="3"<? print($_GET["incldead"] == 3 ? " selected" : ""); ?>><?=$tracker_lang['golden_torrents'];?></option>
<option value="4"<? print($_GET["incldead"] == 4 ? " selected" : ""); ?>><?=$tracker_lang['no_seeds'];?></option>
<option value="5"<? print($_GET["incldead"] == 5 ? " selected" : ""); ?>>Без торрентов</option>
</select>
<select name="cat">
<option value="0">(<?=$tracker_lang['all_types'];?>)</option>
<?


//$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
if ($cat["id"] == $_GET["cat"])
$catdropdown .= " selected=\"selected\"";
$catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
}

?>
<?= $catdropdown ?>
</select>
<input class="btn" type="submit" value="<?=$tracker_lang['search'];?>!" />
</center>
</form>
<script language="JavaScript" src="js/suggest.js" type="text/javascript"></script>
<div id="suggcontainer" style="text-align: left; width: 520px; display: none;">
<div id="suggestions" style="cursor: default; position: absolute; background-color: #FFFFFF; border: 1px solid #777777;"></div>
</div>
</td></tr></table>

<?

if (isset($cleansearchstr))
print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['search_results_for']." \"" . htmlspecialchars($searchstr) . "\"</td></tr>\n");

print("</td></tr>");

if ($num_torrents) {

        print("<tr><td class=\"index\" colspan=\"12\">");
        print($pagertop);
        print("</td></tr>");
        $returnto = urlencode(basename($_SERVER["REQUEST_URI"]));
        
        torrenttable($res, "index", $returnto);

        print("<tr><td class=\"index\" colspan=\"12\">");
        print($pagerbottom);
        print("</td></tr>");

}
else {
        if (isset($cleansearchstr)) {
                print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['nothing_found']."</td></tr>\n");
                //print("<p>Попробуйте изменить запрос поиска.</p>\n");
        }
        else {
                print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['nothing_found']."</td></tr>\n");
                //print("<p>Извините, данная категория пустая.</p>\n");
        }
}

print("</table>");

stdfoot();

?>