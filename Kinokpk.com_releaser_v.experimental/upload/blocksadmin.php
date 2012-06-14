<?php
/**
 * Blocks Administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();
get_privilege('blocksadmin');

loggedinorreturn();


httpauth();

define("NO_WYSIWYG", true);

function prepare_data($arr)
{
    $stripped = array('title', 'blockfile', 'which', 'custom_tpl');
    $intval = array('weight');
    foreach ($stripped as $k) $arr[$k] = sqlesc(trim(htmlspecialchars((string)$arr[$k])));
    $arr['expire'] = (int)strtotime((string)$arr['expire']);
    $arr['content'] = sqlesc((string)$arr['content']);
    foreach ($intval as $k) $arr[$k] = (int)$arr[$k];
    $arr['view'] = sqlesc(implode(',', (array)$arr['view']));
    $arr['active'] = ($arr['active'] ? 1 : 0);
    $arr['bposition'] = sqlesc(substr((string)$arr['bposition'], 0, 1));
    return $arr;
}

$action = trim((string)$_GET['a']);
$id = (int)$_GET['id'];
$allowed_actions = array('add', 'edit', 'saveedit', 'saveadd', 'reorder', 'react', 'delete');
if ($action && !in_array($action, $allowed_actions)) $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Unknown action'));

if ($action == 'react') {
    $curact = @mysql_result($REL_DB->query("SELECT active FROM orbital_blocks WHERE bid=$id"), 0);
    $REL_DB->query("UPDATE orbital_blocks SET active=" . ($curact ? 0 : 1) . " WHERE bid=$id");
    $REL_CACHE->clearGroupCache('blocks');
    headers(REL_AJAX);
    die($curact ? $REL_LANG->_("No") : $REL_LANG->_("Yes"));
} elseif ($action == 'delete') {
    $REL_DB->query("DELETE FROM orbital_blocks WHERE bid=$id");
    $REL_CACHE->clearGroupCache('blocks');
    $REL_TPL->stderr($REL_LANG->_("Successful"), $REL_LANG->_("Block deleted"), 'success');
} elseif ($action == 'reorder') {
    $arr = array_map('intval', (array)$_POST['order']);
    if (!$arr) $REL_TPL->stderr($REL_LANG->_("Error"), $REL_LANG->_("Missing form data"));
    foreach ($arr as $bid => $order) {
        $REL_DB->query("UPDATE orbital_blocks SET weight=$order WHERE bid=" . (int)$bid);
    }
    $REL_CACHE->clearGroupCache('blocks');
    safe_redirect($REL_SEO->make_link("blocksadmin"), 1);
    $REL_TPL->stderr($REL_LANG->_("Successful"), $REL_LANG->_("Blocks reordered"), 'success');
}
$REL_TPL->stdhead($REL_LANG->_("Blocks administration panel"));

$REL_TPL->begin_frame("<a href=\"{$REL_SEO->make_link('blocksadmin')}\">{$REL_LANG->_("Blocks administration panel")}</a> | <a href=\"{$REL_SEO->make_link("blocksadmin","a",'add')}\">{$REL_LANG->_("Add new")}</a>");
if (!$action) {
    $barray = array();
    $res = $REL_DB->query("SELECT bid,title,bposition,weight,active,blockfile,view,expire,which,custom_tpl FROM orbital_blocks ORDER BY bposition ASC, weight ASC,bid ASC");
    while ($row = mysql_fetch_assoc($res)) {
        $row['view'] = ($row['view'] ? implode(', ', array_map('get_user_class_name', explode(',', $row['view']))) : $REL_LANG->_('All'));
        if ($row['expire']) $row['expire'] = mkprettytime($row['expire']) . '<br/>' . get_elapsed_time($row['expire']) . " {$REL_LANG->_("remaining")}";
        $barray[$row['bposition']][] = $row;
    }
    $REL_TPL->assign('blocks', $barray);
} elseif ($action == 'edit' || $action == 'add') {
    if ($action == 'edit') {
        $block = $REL_DB->query("SELECT * FROM orbital_blocks WHERE bid=$id");
        $block = mysql_fetch_assoc($block);
        if (!$block) {
            $REL_TPL->stdmsg($REL_LANG->_("Error"), $REL_LANG->_("Invalid id"));
            $REL_TPL->stdfoot();
            die();
        }
    } else     $block['weight'] = 0;
    $block['content'] = textbbcode('arr[content]', $block['content']);
    $REL_TPL->assignByRef('block', $block);

    $REL_TPL->assign('ACTION', $action);
    $filelist = array();
    foreach (glob('*.php') as $file) $filelist[] = str_replace(".php", '', $file);
    $REL_TPL->assign('filelist', $filelist);
    $handle = opendir("blocks");
    while ($file = readdir($handle)) {
        if (preg_match("/^block\-(.+)\.php/", $file, $matches)) {
            $found = str_replace("_", " ", $matches[1]);
            $blockfiles[$found] = $file;

        }
    }
    closedir($handle);
    $REL_TPL->assign('blockfiles', $blockfiles);
    $REL_TPL->assign('user_classes', make_classes_checkbox("arr[view]", $block['view']));

}
elseif ($action == 'saveedit' || $action == 'saveadd') {
    $arr = (array)$_POST['arr'];
    if (!$arr) $REL_TPL->stderr($REL_LANG->_("Error"), $REL_LANG->_("Missing form data"));
    $arr = prepare_data($arr);
    if ($action == 'saveadd') {
        $REL_DB->query("INSERT INTO orbital_blocks (" . implode(',', array_keys($arr)) . ") VALUES (" . implode(',', array_values($arr)) . ")");
    } else {
        foreach ($arr as $k => $a) $to_query[] = "$k=$a";
        $REL_DB->query("UPDATE orbital_blocks SET " . implode(',', $to_query) . " WHERE bid=$id");
    }
    $REL_CACHE->clearGroupCache('blocks');
    //safe_redirect($REL_SEO->make_link('blocksadmin'),1);
    if (!mysql_errno()) $REL_TPL->stdmsg($REL_LANG->_("Successful"), $REL_LANG->_("Block saved"), 'success');
    else $REL_TPL->stdmsg($REL_LANG->_("Error"), $REL_LANG->_("Block does not saved due MySQL error:") . ' ' . mysql_error());
    $REL_TPL->end_frame();
    $REL_TPL->stdfoot();
    die();
}
$REL_TPL->output(($action == 'add') ? 'edit' : $action);
$REL_TPL->end_frame();

$REL_TPL->stdfoot();
?>