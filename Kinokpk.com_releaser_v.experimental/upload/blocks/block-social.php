<?php
global $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO, $REL_DB, $CURUSER;

if (!defined('BLOCK_FILE')) {
    safe_redirect($REL_SEO->make_link('index'));
    exit;
}


$content .= '<center><script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script><!-- VK Widget --><div id="vk_groups"></div><script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 0, width: "235", height: "290"}, 10496150);</script>';

$content .= '<br><div class="fb-like-box" data-href="http://www.facebook.com/torrentsbook" data-width="235" data-show-faces="true" data-stream="false" data-header="true"></div></center>';

























?>
