<?php
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}
global  $REL_LANG, $REL_SEO;

//$blocktitle = $REL_LANG->say_by_key('our_films');

$content = cloud('cloud-small','','',300,300);
$content .='<br/><div align="center">[<a href="'.$REL_SEO->make_link('alltags').'">'.$REL_LANG->say_by_key('large_tags').'</a>]</div>'

?>