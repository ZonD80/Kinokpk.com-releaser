<?php
global  $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_SEO, $REL_DB, $CURUSER;

if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}


$content .= '<table border="0" width="100%" cellspacing="0" cellpadding="5">';
if ($CURUSER) {
if ($CURUSER['avatar']) {
$content .= '<center><a href="'.$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'name',translit($CURUSER['username'])).'"><img src="'.$CURUSER['avatar'].'" alt="'.$CURUSER['username'].'" /></a></center></br>';
} else {
$content .= '<center><a href="'.$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'name',translit($CURUSER['username'])).'"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/default_avatar.gif" alt="'.$CURUSER['username'].'" /></a></center></br>';
}


if (get_privilege('access_to_admincp',false)) {
		$content .= '<tr>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/options.png" alt="admin" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('admincp').'">'.$REL_LANG->say_by_key('Admin').'</a></td>
</tr>';
	}


$content .= '<tr>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/options.png" alt="settings" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('my').'">'.$REL_LANG->say_by_key('account_settings').'</a></td>
       </tr>	<tr>
		<td class="imgblock"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/upload.png" alt="upload" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('upload').'">'.$REL_LANG->say_by_key('upload').'</a></td>
</tr><tr>
		<!-- <td class="imgblock" ><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/forum.png" alt="forum" width="16" height="16" /></td>
	 <td class="stblock"><a href="'.$REL_SEO->make_link('forums').'">'.$REL_LANG->_("Forum").'</a></td> -->
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/mytorrents.png" alt="mytorrents" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('mytorrents').'">'.$REL_LANG->say_by_key('my_torrents').'</a></td>
</tr><tr>	
                <td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/present.png" alt="present" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('present').'">'.$REL_LANG->_("Friends and presents").'</a></td>
	</tr><tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/users.png" alt="users" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('users').'">'.$REL_LANG->say_by_key('users').'</a></td>
    </tr>';



	}

$content .= '<tr>
               <td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/rss.png" alt="rss" title="rss" width="16" height="16" /></td>
		<td class="stblock" ><a href="'.$REL_SEO->make_link('rss').'">'.$REL_LANG->say_by_key('rss').'</a></td>
</tr><tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/shop.gif" alt="shop" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('donate').'">'.$REL_LANG->_("Shop").'</a></td>
</tr><tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/video.png" alt="video" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('browse').'">'.$REL_LANG->say_by_key('browse').'</a></td>
 </tr><tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/group.gif" alt="group" width="16" height="16" /></td>
		<td class="stblock"><a href="'.$REL_SEO->make_link('relgroups').'">'.$REL_LANG->_('Release groups').'</a></td>
     </tr><tr>
		<td class="imgblock" style="padding-top: 5px;"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/vk_logo.gif" alt="vk" width="16" height="16" /></td>
		<td class="stblock"><a href="http://vkontakte.ru/club10496150">'.$REL_LANG->_("We in vkontakte.ru").'</a></td>
	</tr>


</table>';








$content.= generate_ratio_popup_warning(true);
if ($CURUSER) {
$content .= '<center><a href="'.$REL_SEO->make_link('logout').'"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/logout.gif" width="16" height="16" align="top" style="padding-right: 5px;"/>'.$REL_LANG->say_by_key('logout').'</a></center>';
}
$content .= '<hr /><table border="0" width="100%" cellspacing="0" cellpadding="0">
   <!--tr>
      <td class="invtitle" colspan="3"><strong>'.$REL_LANG->_('Invite your friends').'</strong></td>
   </tr-->
	<tr >
		<!--td class="imginvite"><a href="'.$REL_SEO->make_link('invite').'"><img src="themes/'.$REL_CONFIG['ss_uri'].'/images/invite.png" alt="invite" title="'.$REL_LANG->_('Invite your friends').'" width="107" height="78" border="0" /></a></td-->
		<td class="invcontent" valign="top"><a href="'.$REL_SEO->make_link('invite').'"><center>'.$REL_LANG->_('Invite your friends').'</center></a><br />'.$REL_LANG->_('Divide the pleasure of using our tracker with your friends').'.</td>
	</tr>
</table>';

?>
