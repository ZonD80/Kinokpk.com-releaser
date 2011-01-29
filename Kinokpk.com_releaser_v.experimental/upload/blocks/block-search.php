<?php
global $REL_LANG, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}


$content = '<form method="get" action="'.$REL_SEO->make_link('browse').'">
			<span class="embedded">&nbsp;'.$REL_LANG->say_by_key('torrents').'
				<input type="text" name="search" size="20"/><br />
				<input type="submit" value="'.$REL_LANG->say_by_key('search_btn').'" />
			</span>	
			</form>
			<form method="get" action="'.$REL_SEO->make_link('viewrequests').'">
			<span class="embedded">&nbsp;'.$REL_LANG->say_by_key('requests').'<input type="text" name="search" size="20"/><br />
				<input type="submit" value="'.$REL_LANG->say_by_key('search_btn').'" />
			</span>
			</form>
<!-- Google Search -->
			<form action="http://www.google.com/cse" id="cse-search-box">
			<span class="embedded" style="padding-top: 3px;">&nbsp;'.$REL_LANG->say_by_key('search_google').'
				<input name="cx" value="008925083164290612781:v-qk13aiplq" type="hidden" />
				<input name="ie" value="utf-8" type="hidden" />
				<input name="q" size="20" type="text" /><br />
				<input name="sa" value="'.$REL_LANG->say_by_key('search_google').'!" type="submit" />
			</span>
			</form>
<!-- / Google Search -->';
?>