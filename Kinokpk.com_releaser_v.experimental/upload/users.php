<?php
/**
 * Global user statistics
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

INIT();

loggedinorreturn();

$page = (int)$_GET["page"];

$search = (string)$_GET['search'];

$search = htmlspecialchars(trim($search));

$class = (int)$_GET['class'];
if ($class == '-' || !is_valid_user_class($class))
    $class = '';
$q[] = 'users';
if ($search != '' || $class) {
    $query = "username LIKE '%" . sqlwildcardesc($search) . "%' AND confirmed=1";
    if ($search)
        $q[] = "search";
    $q[] = $search;
}

if (is_valid_user_class($class)) {
    $query .= " AND class = $class";
    $q[] = "class";
    $q[] = $class;
}

if ($query) $query = " WHERE " . $query;


if (!pagercheck())
    $REL_TPL->stdhead($REL_LANG->say_by_key('users'));


if ((get_privilege('is_moderator', false)) && $_GET['act']) {
    if ($_GET['act'] == "users") {
        $REL_TPL->begin_frame($REL_LANG->_('Users with rating below zero'));

        echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
        echo "<tr><td class=colhead align=left>{$REL_LANG->_('Username')}</td><td class=colhead>{$REL_LANG->_('Rating')}</td><td class=colhead>IP</td><td class=colhead>{$REL_LANG->_('Registered at')}</td><td class=colhead>{$REL_LANG->_('Last seen')}</td></tr>";


        $result = $REL_DB->query("SELECT users.id,users.username,users.class,users.ratingsum,users.added,users.last_access,users.ip,users.warned,users.enabled,users.donor FROM users WHERE ratingsum<0 AND enabled = 1 ORDER BY ratingsum DESC");
        while ($row = mysql_fetch_array($result)) {
            $records = true;
            $ratio = ratearea($row['ratingsum'], $row['id'], 'users', $CURUSER['id']);
            echo "<tr><td>" . make_user_link($row) . "</td><td><strong>" . $ratio . "</strong></td><td>" . $row["ip"] . "</td><td>" . mkprettytime($row["added"]) . "</td><td>" . mkprettytime($row["last_access"]) . " (" . get_elapsed_time($row["last_access"], false) . " {$REL_LANG->say_by_key('ago')})</td></tr>";


        }
        if (!$records) $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('nothing_found'), 'error');

        echo "</table>";
        $REL_TPL->end_frame();
    } elseif ($_GET['act'] == "last") {
        $REL_TPL->begin_frame($REL_LANG->_('Last users'));

        echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
        echo "<tr><td class=colhead align=left>{$REL_LANG->_('Username')}</td><td class=colhead>{$REL_LANG->_('Rating')}</td><td class=colhead>IP</td><td class=colhead>{$REL_LANG->_('Registered at')}</td><td class=colhead>{$REL_LANG->_('Last seen')}</td></tr>";

        $result = $REL_DB->query("SELECT * FROM users WHERE enabled = 1 AND confirmed=1 ORDER BY added DESC LIMIT 100");
        while ($row = mysql_fetch_array($result)) {
            $records = true;
            $ratio = ratearea($row['ratingsum'], $row['id'], 'users', $CURUSER['id']);
            echo "<tr><td>" . make_user_link($row) . "</td><td><strong>" . $ratio . "</strong></td><td>" . $row["ip"] . "</td><td>" . mkprettytime($row["added"]) . "</td><td>" . mkprettytime($row["last_access"]) . "</td></tr>";


        }
        if (!$records) $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('nothing_found'), 'error');
        echo "</table>";
        $REL_TPL->end_frame();
    }

    elseif ($_GET['act'] == "banned") {
        $REL_TPL->begin_frame($REL_LANG->_('Banned users'));

        echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
        echo "<tr><td class=colhead align=left>{$REL_LANG->_('Username')}</td><td class=colhead>{$REL_LANG->_('Rating')}</td><td class=colhead>IP</td><td class=colhead>{$REL_LANG->_('Registered at')}</td><td class=colhead>{$REL_LANG->_('Last seen')}</td></tr>";
        $result = $REL_DB->query("SELECT * FROM users WHERE enabled = 0 ORDER BY last_access DESC ");
        if ($row = mysql_fetch_array($result)) {
            do {
                $ratio = ratearea($row['ratingsum'], $row['id'], 'users', $CURUSER['id']);
                echo "<tr><td>" . make_user_link($row) . "</td><td><strong>" . $ratio . "</strong></td><td>" . $row["ip"] . "</td><td>" . mkprettytime($row["added"]) . "</td><td>" . mkprettytime($row["last_access"]) . "</td></tr>";


            } while ($row = mysql_fetch_array($result));
        } else {
            print "<tr><td colspan=7>{$REL_LANG->_('Noting was found')}</td></tr>";
        }
        echo "</table>";
        $REL_TPL->end_frame();
    }

} elseif (!isset($_GET['act'])) {

    if (!pagercheck()) {
        print("<h1>{$REL_LANG->_('Users')}</h1>\n");
        print("<div class=\"friends_search\">");
        print("<form method=\"get\" style='margin-bottom: 20px;' action=\"" . $REL_SEO->make_link('users') . "\">\n");
        print("<span class='browse_users'>" . $REL_LANG->say_by_key('search') . "<input type=\"text\" size=\"30\" name=\"search\" value=\"" . $search . "\"></span> \n");
        print make_classes_select('class', $class);
        print("<input type=\"submit\" class=\"button\" style=\"margin-top:5px\" value=\"{$REL_LANG->say_by_key('go')}\">\n");
        print("</form>\n");
        print("</div>\n");
    }
    $res = $REL_DB->query("SELECT SUM(1) FROM users$query");
    $count = mysql_result($res, 0);
    if (!$count) {
        $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('nothing_found'), 'error');
        $REL_TPL->stdfoot();
        die();
    }

    $limit = ajaxpager(25, $count, array('users'), 'userst');


    $res = $REL_DB->query("SELECT u.*, c.name, c.flagpic FROM users AS u LEFT JOIN countries AS c ON c.id = u.country$query ORDER BY id DESC $limit");
    $num = mysql_num_rows($res);

    if (!pagercheck()) {
        print("<div id=\"pager_scrollbox\"><table id=\"userst\"  cellspacing=\"0\" cellpadding=\"5\" border=\"1\" style=\"width: 100%;\">\n");
        print("<tr><td class=\"colhead\" align=\"left\">{$REL_LANG->_('Username')}</td><td class=\"colhead\">{$REL_LANG->_('Registered at')}</td><td class=\"colhead\">{$REL_LANG->_('Last seen')}</td><td class=\"colhead\">{$REL_LANG->_('Rating')}</td><td class=\"colhead\">{$REL_LANG->_('Gender')}</td><td class=\"colhead\" align=\"left\">{$REL_LANG->_('Class')}</td><td class=\"colhead\">{$REL_LANG->_('Country')}</td></tr>\n");
    }
    while ($arr = mysql_fetch_assoc($res)) {
        if ($arr['country'] > 0) {
            $country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
        } else
            $country = "<td align=\"center\">---</td>";
        $ratio = ratearea($arr['ratingsum'], $arr['id'], 'users', $CURUSER['id']);

        if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"{$REL_LANG->_('Male')}\" title=\"{$REL_LANG->_('Male')}\" style=\"margin-left: 4pt\">";
        elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"{$REL_LANG->_('Female')}\" title=\"{$REL_LANG->_('Female')}\" style=\"margin-left: 4pt\">";
        else $gender = "<div align=\"center\"><b>?</b></div>";

        print("<tr><td align=\"left\">" . make_user_link($arr) . "</td>" .
            "<td>" . mkprettytime($arr['added']) . "</td><td>" . mkprettytime($arr['last_access']) . " (" . get_elapsed_time($arr["last_access"], false) . " {$REL_LANG->say_by_key('ago')})</td><td>$ratio</td><td>$gender</td>" .
            "<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country</tr>\n");
    }
    if (pagercheck()) die();
    print("</table></div>");

}
$REL_TPL->stdfoot();

?>
