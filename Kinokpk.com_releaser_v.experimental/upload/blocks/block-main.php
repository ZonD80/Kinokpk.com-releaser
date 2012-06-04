<?php
global  $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO, $REL_DB, $CURUSER;

if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}


$content .= '<a class="menu" href="/">'.$REL_LANG->say_by_key('homepage').'</a><a class="menu" href="'.$REL_CONFIG['forumurl'].'">'.$REL_LANG->say_by_key('forum').'</a><a class="menu" href="'.$REL_SEO->make_link('browse').'">'.$REL_LANG->say_by_key('browse').'</a><a class="menu" href="'.$REL_SEO->make_link('staff').'">'.$REL_LANG->say_by_key('staff').'</a><a class="menu" href="'.$REL_SEO->make_link('topten').'">'.$REL_LANG->say_by_key('topten').'</a>';

































?>
