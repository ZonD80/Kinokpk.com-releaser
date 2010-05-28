<?php
if (!defined('BLOCK_FILE')) {
	Header("Location: ../index.php");
	exit;
}

global $tracker_lang;

getlang('blocks');
$content = '<form method="get" action="browse.php">
			<span class="embedded">&nbsp;'.$tracker_lang['torrents'].'
				<input type="text" name="search" size="20"/><br />
				<input type="submit" value="'.$tracker_lang['search_btn'].'" />
			</span>	
			</form>
			<form method="get" action="viewrequests.php">
			<span class="embedded">&nbsp;'.$tracker_lang['requests'].'<input type="text" name="search" size="20"/><br />
				<input type="submit" value="'.$tracker_lang['search_btn'].'" />
			</span>
			</form>
<!-- Google Search -->
			<form action="http://www.google.com/cse" id="cse-search-box">
			<span class="embedded" style="padding-top: 3px;">&nbsp;'.$tracker_lang['search_google'].'
				<input name="cx" value="008925083164290612781:v-qk13aiplq" type="hidden" />
				<input name="ie" value="windows-1251" type="hidden" />
				<input name="q" size="20" type="text" /><br />
				<input name="sa" value="'.$tracker_lang['search_google'].'!" type="submit" />
			</span>
			</form>
<!-- / Google Search -->';
?>