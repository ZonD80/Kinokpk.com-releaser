<?php
/**
 * Activity statistics
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();

get_privilege('view_general_statistics');

$REL_TPL->stdhead($REL_LANG->_('Statisitcs'));
?>

<STYLE TYPE="text/css" MEDIA=screen>
    <!--
    a.colheadlink:link, a.colheadlink:visited {
        font-weight: bold;
        color: #FFFFFF;
        text-decoration: none;
    }

    a.colheadlink:hover {
        text-decoration: underline;
    }

    -->
</STYLE>

<?php
$REL_TPL->begin_main_frame();

$res = $REL_DB->query("SELECT SUM(1) FROM torrents");
$n = mysql_fetch_row($res);
$n_tor = $n[0];

$res = $REL_DB->query("SELECT SUM(1) FROM xbt_files_users WHERE active=1");
$n = mysql_fetch_row($res);
$n_peers = $n[0];

$classes = init_class_array();
$level = get_class_priority($classes['uploader']);
foreach ($classes as $cid => $class) {
    if (is_int($cid) && $class['priority'] && $class['priority'] > $level) $to_select[] = $cid;
}

$uporder = urlencode($_GET['uporder']);
$catorder = urlencode($_GET["catorder"]);

if ($uporder == "lastul")
    $orderby = "last DESC, name";
elseif ($uporder == "torrents")
    $orderby = "n_t DESC, name";
elseif ($uporder == "peers")
    $orderby = "n_p DESC, name";
else
    $orderby = "name";

$query = "SELECT u.id, u.username AS name, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.fid) as n_p
	FROM users as u LEFT JOIN torrents as t ON u.id = t.owner LEFT JOIN xbt_files_users as p ON t.id = p.fid WHERE u.class = {$classes['uploader']}
	GROUP BY u.id UNION SELECT u.id, u.username AS name, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.fid) as n_p
	FROM users as u LEFT JOIN torrents as t ON u.id = t.owner LEFT JOIN xbt_files_users as p ON t.id = p.fid WHERE u.class IN (" . implode(',', $to_select) . ")
	GROUP BY u.id ORDER BY $orderby";

$res = $REL_DB->query($query);
if (mysql_num_rows($res) == 0)
    $REL_TPL->stdmsg($REL_LANG->_('Error'), $REL_LANG->_('There are no releasers'));
else {
    $REL_TPL->begin_frame($REL_LANG->_('Releasesrs statistics'), True);
    print("<table width=\"100%\"><tr>\n
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'uporder', 'uploader', 'catorder', $catorder) . "\" class=colheadlink>{$REL_LANG->_('Username')}</a></td>\n
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'lastul', 'uploader', 'catorder', $catorder) . "\" class=colheadlink>{$REL_LANG->_('Last released at')}</a></td>\n
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'torrents', 'uploader', 'catorder', $catorder) . "\" class=colheadlink>{$REL_LANG->_('Releases count')}</a></td>\n
	<td class=colhead>{$REL_LANG->_('Completed')}</td>\n
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'uporder', 'peers', 'catorder', $catorder) . "\" class=colheadlink>{$REL_LANG->_('Peers')}</a></td>\n
	<td class=colhead>{$REL_LANG->_('Completed')}</td>\n
	</tr>\n");
    while ($uper = mysql_fetch_array($res)) {
        print("<tr><td><a href=\"" . $REL_SEO->make_link('userdetails', 'id', $uper['id'], 'username', translit($uper['name'])) . "\"><b>" . $uper['name'] . "</b></a></td>\n");
        print("<td " . ($uper['last'] ? (">" . mkprettytime($uper['last']) . " (" . get_elapsed_time($uper['last']) . " {$REL_LANG->_('ago')})") : "align=center>---") . "</td>\n");
        print("<td align=right>" . $uper['n_t'] . "</td>\n");
        print("<td align=right>" . ($n_tor > 0 ? number_format(100 * $uper['n_t'] / $n_tor, 1) . "%" : "---") . "</td>\n");
        print("<td align=right>" . $uper['n_p'] . "</td>\n");
        print("<td align=right>" . ($n_peers > 0 ? number_format(100 * $uper['n_p'] / $n_peers, 1) . "%" : "---") . "</td></tr>\n");
    }
    print('</table>');
    $REL_TPL->end_frame();
}
if ($n_tor == 0)
    $REL_TPL->stdmsg($REL_LANG->_('Error'), $REL_LANG->_('There are no data by categories'));
else {
    if ($catorder == "lastul")
        $orderby = "last DESC, c.name";
    elseif ($catorder == "torrents")
        $orderby = "n_t DESC, c.name";
    elseif ($catorder == "peers")
        $orderby = "n_p DESC, c.name";
    else
        $orderby = "c.name";
    $tree = make_tree();
    $res = $REL_DB->query("SELECT c.id as catid, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.fid) AS n_p
	FROM categories as c LEFT JOIN torrents as t ON t.category = c.id LEFT JOIN xbt_files_users as p
	ON t.id = p.fid GROUP BY c.id ORDER BY $orderby");

    $REL_TPL->begin_frame($REL_LANG->_('Categories activity'), True);
    print("<table width=\"100%\" border=\"1\">
	<tr><td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'uporder', $uporder, 'catorder', 'category') . "\" class=colheadlink>{$REL_LANG->_('Category')}</a></td>
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'uporder', $uporder, 'catorder', 'lastul') . "\" class=colheadlink>{$REL_LANG->_('Time of last release')}</a></td>
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'uporder', $uporder, 'catorder', 'torrents') . "\" class=colheadlink>{$REL_LANG->_('Releases')}</a></td>
	<td class=colhead>{$REL_LANG->_('Completed')}</td>
	<td class=colhead><a href=\"" . $REL_SEO->make_link('stats', 'uporder', $uporder, 'catorder', 'peers') . "\" class=colheadlink>{$REL_LANG->_('Peers')}</a></td>
	<td class=colhead>{$REL_LANG->_('Completed')}</td></tr>\n");
    while ($cat = mysql_fetch_array($res)) {
        print("<tr><td class=rowhead>" . get_cur_position_str($tree, $cat['catid']) . "</b></a></td>");
        print("<td " . ($cat['last'] ? (">" . mkprettytime($cat['last']) . " (" . get_elapsed_time($cat['last']) . " {$REL_LANG->_('ago')})") : "align = center>---") . "</td>");
        print("<td align=right>" . $cat['n_t'] . "</td>");
        print("<td align=right>" . number_format(100 * $cat['n_t'] / $n_tor, 1) . "%</td>");
        print("<td align=right>" . $cat['n_p'] . "</td>");
        print("<td align=right>" . ($n_peers > 0 ? number_format(100 * $cat['n_p'] / $n_peers, 1) . "%" : "---") . "</td>\n");
    }
    print ('</table>');
    $REL_TPL->end_frame();
}
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();
die;
?>