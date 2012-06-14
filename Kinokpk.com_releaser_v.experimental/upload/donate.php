<?php

// paying system via sms
require_once("include/bittorrent.php");
define("IN_CONTACT", true);
INIT();

$REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Payments temporarily disabled'));
?>
