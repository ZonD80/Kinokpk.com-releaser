<?php
/**
 * Stamp administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

function bark($msg)
{
    global $REL_LANG, $REL_DB;
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $msg);
}

INIT();
loggedinorreturn();
get_privilege('stampadmin');
httpauth();

if (!isset($_GET['action'])) {
    $REL_TPL->stdhead($REL_LANG->_('Stamps administration'));
    print("<div algin=\"center\"><h1>{$REL_LANG->_('Stamps administration')}</h1></div>");
    print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"" . $REL_SEO->make_link('stampadmin', 'action', 'add') . "\">{$REL_LANG->_('Add new stamp')}</a></td></tr></table>");
    $stamparray = $REL_DB->query("SELECT * FROM stamps ORDER BY id ASC");
    print("<form name=\"saveids\" action=\"" . $REL_SEO->make_link('stampadmin', 'action', 'saveids') . "\" method=\"post\"><table width=\"100%\" border=\"1\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$REL_LANG->_('Picture')}</td><td class=\"colhead\">{$REL_LANG->_('Sort')}</td><td class=\"colhead\">{$REL_LANG->_('Access level')}</td><td class=\"colhead\">{$REL_LANG->_('Actions')}</td></tr>");
    while ($stamp = mysql_fetch_array($stamparray)) {
        print("<tr><td>" . $stamp['id'] . "</td><td><img src=\"pic/stamp/" . $stamp['image'] . "\"></td><td><input type=\"text\" name=\"sort[" . $stamp['id'] . "]\" size=\"4\" value=\"" . $stamp['sort'] . "\"></td><td>" . get_user_class_name($stamp['class']) . "</td><td><a href=\"" . $REL_SEO->make_link('stampadmin', 'action', 'edit', 'id', $stamp['id']) . "\">E</a> | <a onClick=\"return confirm('{$REL_LANG->_('Are you sure?')}')\" href=\"" . $REL_SEO->make_link('stampadmin', 'action', 'delete', 'id', $stamp['id']) . "\">D</a></td></tr>");
    }
    print("</table><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->_('Save sort order')}\"></form>");
    $REL_TPL->stdfoot();
} elseif ($_GET['action'] == 'saveids') {

    if (is_array($_POST['sort'])) {

        foreach ($_POST['sort'] as $id => $s) {

            $REL_DB->query("UPDATE stamps SET sort = " . intval($s) . "  WHERE id = " . $id);
        }
        safe_redirect($REL_SEO->make_link('stampadmin'));
        exit();
    } else bark("Missing form data");
}

elseif ($_GET['action'] == 'add') {
    $REL_TPL->stdhead($REL_LANG->_('Adding stamp'));
    print("<form action=\"" . $REL_SEO->make_link('stampadmin', 'action', 'saveadd') . "\" name=\"savearray\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->_('Picture (must be hosted on your server, relative path)')}</td></tr><tr><td><input type=\"text\" name=\"image\" size=\"80\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->_('Sort order')}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->_('Access level')}</td></tr><tr><td>
  " . make_classes_select() . "</td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->_('Add')}\"></td></tr></table></form>");
    $REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'delete') {
    if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
    $REL_DB->query("DELETE FROM stamps WHERE id = " . $_GET['id']);
    safe_redirect($REL_SEO->make_link('stampadmin'));
    exit();

}

elseif ($_GET['action'] == 'edit') {
    if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

    $stamparray = $REL_DB->query("SELECT * FROM stamps WHERE id=" . $_GET['id']);
    list($id, $sort, $class, $image) = mysql_fetch_array($stamparray);

    $REL_TPL->stdhead($REL_LANG->_('Editing stamp'));
    print("<form name=\"save\" action=\"" . $REL_SEO->make_link('stampadmin', 'action', 'saveedit') . "\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$REL_LANG->_('Picture (must be hosted on your server, relative path)')}</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"" . $id . "\"><input type=\"text\" name=\"image\" size=\"80\" value=\"" . $image . "\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->_('Sort order')}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\" value=\"" . $sort . "\"></td></tr><tr><td class=\"colhead\">{$REL_LANG->_('Access level')}</td></tr><tr><td>
" . make_classes_select('class', $class) . "</td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$REL_LANG->_('Edit')}\"></td></tr></table></form>");
    $REL_TPL->stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
    $REL_DB->query("UPDATE stamps SET image=" . sqlesc(htmlspecialchars((string)$_POST['image'])) . ",sort=" . intval($_POST['sort']) . ",class=" . intval($_POST['class']) . " WHERE id=" . intval($_POST['id']));
    safe_redirect($REL_SEO->make_link('stampadmin'));
    exit();
}
elseif ($_GET['action'] == 'saveadd') {

    $REL_DB->query("INSERT INTO stamps (image,sort,class) VALUES (" . sqlesc(htmlspecialchars((string)$_POST['image'])) . "," . intval($_POST['sort']) . "," . intval($_POST['class']) . ")");
    safe_redirect($REL_SEO->make_link('stampadmin'));
    exit();
}


