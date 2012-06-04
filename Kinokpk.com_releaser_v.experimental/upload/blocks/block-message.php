<?php
global  $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO, $REL_DB, $CURUSER;

if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}

$blocktitle = $REL_LANG->say_by_key('messages');
$content .= '<table border="0" width="100%" cellspacing="0" cellpadding="5">';
$content .= '<tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/inbox.png" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('message').'">'.$REL_LANG->say_by_key('inbox').'</a></td>
     </tr><tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/outbox.png" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('message','action','viewmailbox','box',-1).'">'.$REL_LANG->say_by_key('outbox').'</a></td>
	</tr></table>';





























?>
