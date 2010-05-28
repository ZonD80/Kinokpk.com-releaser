<?php
if (!defined('BLOCK_FILE')) {
	Header("Location: ../index.php");
	exit;
}
getlang('blocks');
global  $tracker_lang;
$blocktitle = $tracker_lang['our_films'];

$content = cloud();
$content .='<br/><div align="center">[<a href="alltags.php">'.$tracker_lang['large_tags'].'</a>]</div>'

?>