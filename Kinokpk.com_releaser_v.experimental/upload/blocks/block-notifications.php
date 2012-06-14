<?php
global $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO, $REL_DB, $CURUSER;

if (!defined('BLOCK_FILE')) {
    safe_redirect($REL_SEO->make_link('index'));
    exit;
}
$notifs = generate_notify_array();
if ($notifs['total']) {

    $content .= "<table width=\"100%\" style=\"color:red;\"><tr><td align=\"center\">{$REL_LANG->_('Notifications')} ({$notifs['total']}):</td>";
    foreach ($notifs['notifs'] as $notify => $ncount) {
        $content .= "<td align=\"center\"><b><a href=\"" . ($notify <> 'unread' ? $REL_SEO->make_link('mynotifs', 'type', $notify) : $REL_SEO->make_link('message')) . "\">" . $REL_LANG->_(ucfirst($notify)) . ": $ncount</a></b></td>";
    }
    $content .= '</tr></table>';
} else {
    $content .= "<table width=\"100%\" ><tr><td align=\"center\"><a href=\"{$REL_SEO->make_link('mynotifs','settings','1')}\">[{$REL_LANG->_('Setup notifications')}]</a></td></tr></table>";
}

$content .= generate_ratio_popup_warning(true);

?>
