<?php
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../index.php");
	exit;
}

global $tracker_lang, $ss_uri, $CACHE;


$content.= generate_notify_popup(true);
$content.= generate_ratio_popup_warning(true);

?>