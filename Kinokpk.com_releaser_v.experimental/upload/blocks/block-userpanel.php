<?php
global $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO;

if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}



$content = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/upload.png" alt="upload" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('upload').'">'.$REL_LANG->say_by_key('upload').'</a></td>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/rss.png" alt="rss" title="rss" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('rss').'">'.$REL_LANG->say_by_key('rss').'</a></td>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/present.png" alt="present" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('present').'">'.$REL_LANG->_("Friends and presents").'</a></td>
	</tr>
	<tr>
			<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/users.png" alt="users" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('users').'">'.$REL_LANG->say_by_key('users').'</a></td>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/shop.gif" alt="shop" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('donate').'">'.$REL_LANG->_("Shop").'</a></td>
		<!-- <td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/forum.png" alt="forum" width="16" height="16" /></td>
	 <td class="stblock"><a href="'.$REL_SEO->make_link('forums').'">'.$REL_LANG->_("Forum").'</a></td> -->
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/mytorrents.png" alt="mytorrents" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('mytorrents').'">'.$REL_LANG->say_by_key('my_torrents').'</a></td>
	</tr>
	<tr>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/video.png" alt="video" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('browse').'">'.$REL_LANG->say_by_key('browse').'</a></td>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/group.gif" alt="group" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('relgroups').'">'.$REL_LANG->_('Release groups').'</a></td>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/vk_logo.gif" alt="vk" width="16" height="16" /></td>
		<td class="stblock"><a href="http://vkontakte.ru/club10496150">'.$REL_LANG->_("We in vkontakte.ru").'</a></td>
	</tr>
</table>
<hr />';
$content.= generate_ratio_popup_warning(true);
$content .= '<hr />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
   <tr>
      <td class="invtitle" colspan="3"><strong>'.$REL_LANG->_('Invite your friends').'</strong></td>
   </tr>
	<tr>
		<td class="imginvite"><a href="'.$REL_SEO->make_link('invite').'"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/invite.png" alt="invite" title="'.$REL_LANG->_('Invite your friends').'" width="107" height="78" border="0" /></a></td>
		<td class="invcontent" valign="top"><a href="'.$REL_SEO->make_link('invite').'">'.$REL_LANG->_('Invite your friends').'</a><br />'.$REL_LANG->_('Divide the pleasure of using our tracker with your friends').'.</td>
	</tr>
</table>';

?>
