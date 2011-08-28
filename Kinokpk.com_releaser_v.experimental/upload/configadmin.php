<?php
/**
 * Configuration script (main)
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";
INIT();
loggedinorreturn();
get_privilege('edit_general_configuration');
httpauth();

if (!isset($_GET['action'])){
	$REL_TPL->stdhead($REL_LANG->_('General settings'));

	$REL_TPL->begin_frame($REL_LANG->_('General settings of Kinokpk.com releaser v.%s',RELVERSION));
	$REL_TPL->assignByRef('REL_CONFIG', $REL_CONFIG);
	$tree = make_tree();
	$REL_TPL->assign('pron_selector',gen_select_area('pron_cats', $tree,$REL_CONFIG['pron_cats'],false,true));
	$REL_TPL->assign('REL_CACHEDRIVER',REL_CACHEDRIVER);
	$xbt = $REL_DB->query_return("SELECT * FROM xbt_config");
	foreach ($xbt as $xbtconfrow) {
		$xbtconf[$xbtconfrow['name']] = $xbtconfrow['value'];
	}
	$REL_TPL->assign('xbt',$xbtconf);
	$REL_TPL->output();
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();

}

elseif ($_GET['action'] == 'save'){
	$reqparametres = array('forum_enabled','torrentsperpage','maxusers','max_torrent_size','max_images','defaultbaseurl','siteemail','adminemail','sitename','description','keywords',
'yourcopy','pm_max','default_language','static_language','siteonline','cache_template','cache_template_time',
'avatar_max_width','avatar_max_height','default_theme','use_dc','deny_signup','allow_invite_signup',
'use_ttl','use_email_act','use_captcha','use_blocks','use_gzip','use_ipbans',
'as_timeout','as_check_messages','debug_mode','debug_language','debug_template','pron_cats','register_timezone','site_timezone','low_comment_hide','sign_length','default_notifs','default_emailnotifs','use_kinopoisk_trailers','use_xbt');
	$captcha_param = array('re_publickey','re_privatekey');

	$updateset = array();

	foreach ($reqparametres as $param) {
		if (!isset($_POST[$param]) && ($param != 'pron_cats')) stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Field %s does not filled',$param));
		$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
	}

	if ($_POST['use_captcha'] == 1) {
		foreach ($captcha_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Private and public keys of ReCaptcha was not defined'));
			$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
		}
	}
	
	$xbt = (array)$_POST['xbt'];
	$xbt = array_map('strval',$xbt);
	foreach ($xbt AS $key=>$value) {
		$updateset[] = "UPDATE xbt_config SET value=".sqlesc($value)." WHERE name=".sqlesc($key);
	}

	foreach ($updateset as $query) sql_query($query);

	$REL_CACHE->clearCache('system','config');

	safe_redirect($REL_SEO->make_link('configadmin'));

}

else stderr($REL_LANG->say_by_key('error'),$REL_LANG->_("Unknown action"));

?>
