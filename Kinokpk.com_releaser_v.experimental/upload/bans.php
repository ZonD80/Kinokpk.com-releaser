<?php
/**
 * Bans administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");

INIT();
loggedinorreturn();

get_privilege('bans_admin');

httpauth();


if (is_valid_id($_GET['remove'])) {
    $remove = (int)$_GET['remove'];
    $REL_DB->query("DELETE FROM bans WHERE id=$remove");
    write_log($REL_LANG->_('Ban with ID %s was removed by %s', $remove, make_user_link()), "bans");

    $REL_CACHE->clearGroupCache("bans");
    safe_redirect($REL_SEO->make_link('bans'), 0);
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mask = trim($_POST['mask']);
    $descr = trim($_POST['descr']);
    if (!$mask)
        $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('missing_form_data'));
    $mask = sqlesc(htmlspecialchars($mask));
    $descr = sqlesc(htmlspecialchars($descr));
    $userid = $CURUSER['id'];
    $added = time();
    $REL_DB->query("INSERT INTO bans (mask,descr,user,added) VALUES($mask,$descr,$userid,$added)");
    write_log($REL_LANG->_('Mask %s was banned by %s', $mask, make_user_link()), "bans");

    $REL_CACHE->clearGroupCache("bans");
    safe_redirect($REL_SEO->make_link('bans'), 0);
    die;
}

$res = $REL_DB->query("SELECT bans.*, users.username, users.class, users.warned, users.donor, users.enabled FROM bans LEFT JOIN users ON bans.user = users.id ORDER BY id DESC");

$REL_TPL->stdhead($REL_LANG->_('IP bans administration'));

if (mysql_num_rows($res) == 0)
    print("<p align=\"center\"><b>" . $REL_LANG->say_by_key('nothing_found') . "</b></p>\n");

else {
    //print("<table border=1 cellspacing=0 cellpadding=5>\n");
    print('<table width="100%" border="1">');
    print("<h1>{$REL_LANG->_('IP bans list')}</h1>\n");
    print("<tr><td class=\"colhead\" align=\"center\">{$REL_LANG->_('Added')}</td><td class=\"colhead\" align=\"center\">{$REL_LANG->_('IP Address')}</td><td class=\"colhead\" align=\"center\">{$REL_LANG->_('Reason')}</td><td class=\"colhead\" align=\"center\">{$REL_LANG->_('Banned by')}</td><td class=\"colhead\" align=\"center\">{$REL_LANG->_('Modify')}</td></tr>\n");

    while ($arr = mysql_fetch_assoc($res)) {
        $user = $arr;
        $user['id'] = $user['user'];
        print("<tr><td  class=\"row1\" align=\"center\">" . mkprettytime($arr['added']) . "</td>" .
            "<td  class=\"row1\" align=\"center\">$arr[mask]</td>" .
            "<td  class=\"row1\" align=\"center\">$arr[descr]</td>" .
            "<td  class=\"row1\" align=\"center\">" . make_user_link($user) . "</td>" .
            "<td  class=\"row1\" align=\"center\"><a href=\"" . $REL_SEO->make_link('bans', 'remove', $arr['id']) . "\">D</a></td></tr>\n");
    }
    print('</table>');
}

print("<br />\n");
print("<form method=\"post\" action=\"" . $REL_SEO->make_link('bans') . "\">\n");
print('<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">');
print("<tr><td class=\"colhead\" colspan=\"2\">{$REL_LANG->_('Add a ban')}</td></tr>");
print("<tr><td class=\"rowhead\">{$REL_LANG->_('Subnet mask / IP address')}</td><td class=\"row1\"><input type=\"text\" name=\"mask\" size=\"40\"/></td></tr>\n");
print("<tr><td class=\"rowhead\">{$REL_LANG->_('Reason')}</td><td class=\"row1\"><input type=\"text\" name=\"descr\" size=\"40\"/></td></tr>\n");
print("<tr><td class=\"row1\" align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"{$REL_LANG->_('Add')}\" class=\"btn\"/></td></tr>\n");
print('</table>');
print("</form>\n");

$REL_TPL->stdfoot();

?>