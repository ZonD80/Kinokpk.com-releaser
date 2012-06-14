<?php
require "include/bittorrent.php";
INIT();
loggedinorreturn();

$REL_TPL->stderr($REL_LANG->_('Sorry'), $REL_LANG->_('This option disabled'));

?>