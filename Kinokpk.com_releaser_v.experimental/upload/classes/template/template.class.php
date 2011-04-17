<?php
/**
 * Class for Kinokpk.com releaser template operations. Extends smarty
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once('Smarty.class.php');
class REL_TPL extends Smarty {

	private $config;


	/**
	 * Class constructor depending on releaser's configuration
	 * @param array $REL_CONFIG Releaser configuration
	 */
	function __construct($REL_CONFIG) {
		global $CURUSER, $REL_LANG,$REL_SEO;
		define('SMARTY_RESOURCE_CHAR_SET','utf-8');
		parent::__construct();
		$this->template_dir = ROOT_PATH.'themes/'.$REL_CONFIG['ss_uri'];
		$compile_dir = ROOT_PATH.'cache/compiled_template_'.$REL_CONFIG['ss_uri'];
		if (!is_dir($compile_dir)) mkdir($compile_dir);
		$this->compile_dir = $compile_dir;
		//$this->config_dir = ROOT_PATH.'include';
		$cachedir = ROOT_PATH.'cache/cached_template_'.$REL_CONFIG['ss_uri'];
		if (!is_dir($cachedir)) mkdir($cachedir);
		$this->cache_dir = $cachedir;
		//$this->security = true;
		$this->php_handling = SMARTY_PHP_REMOVE;
		$this->security_settings->PHP_TAGS = false;
		$this->config['stdhead'] = 'stdhead.tpl';
		$this->config['stdfoot'] = 'stdfoot.tpl';
		$this->config['stdhead_ajax'] = 'stdhead_ajax.tpl';
		$this->config['stdfoot_ajax'] = 'stdfoot_ajax.tpl';
		//$this->register->templateFunction('show_blocks','show_blocks');
		if ($CURUSER) {
			$this->assignByRef('REL_NOTIFS',generate_notify_array());
			$this->assignByRef('REL_RATING_POPUP',generate_ratio_popup_warning());
		}
		if ($REL_CONFIG['cache_template']) {
			$this->caching = true;
			$this->cache_lifetime = $REL_CONFIG['cache_template_time'];
		}
		if ($REL_CONFIG['debug_template'] && get_privilege('debug_template',false)) {
			$this->debugging = true;
		}
		$this->assignByRef('REL_LANG',$REL_LANG);
		$this->assignByRef('REL_SEO',$REL_SEO);
		$this->assignByRef('CURUSER',$CURUSER);
		$this->assignByRef('REL_CONFIG',$REL_CONFIG);
	}

	/**
	 * Begins frame
	 */
	function begin_main_frame() {
		$this->display('begin_main_frame.tpl');
	}

	/**
	 * Outputs template's head
	 * @param string $title Page title
	 * @param string $descradd Page description addition
	 * @param string $keywordsadd Page keywords addition
	 * @param string $headadd Page <head> tag addition
	 */
	function stdhead($title = "", $descradd = '', $keywordsadd = "", $headadd = '') {
		global $REL_CONFIG, $CURUSER;

		if (!$REL_CONFIG['siteonline'] && get_privilege('deny_disabled_site',false)) {
			$this->display('offline.tpl');
			die();
		}
		$offline = false;
		if (get_privilege('view_disabled_site_notice',false) && !$REL_CONFIG['siteonline']) {
			$offline=true;
		}
		$this->assignByRef('OFFLINE',$offline);
		$access_overrided=(isset($_COOKIE['override_class']) && $CURUSER);
		$this->assignByRef('access_overrided',$access_overrided);
		$this->assign('IS_MODERATOR',get_privilege('is_moderator',false));
		headers(REL_AJAX);
		$this->assignByRef('title',$title);
		$this->assignByRef('descradd',$descradd);
		$this->assignByRef('keywordsadd',$keywordsadd);
		$this->assignByRef('headadd',$headadd);
		if (REL_AJAX) {
			$this->display($this->config['stdhead_ajax']);
		} else {
			$this->display($this->config['stdhead']);
		}
	}

	/**
	 * Configures class to use specific templates
	 * @param string $param Config parameter to be assigned for
	 * @param string $value Value to be assigned
	 */
	function configure($param,$value) {
		$this->config[$param]=$value;
	}

	/**
	 * Outputs theme footer
	 */
	function stdfoot() {
		$this->assign('COPYRIGHT',TBVERSION.(BETA?BETA_NOTICE:""));
		generate_post_javascript();
		close_sessions();
		run_cronjobs();
		debug();
		if (REL_AJAX) {
			$this->display($this->config['stdfoot_ajax']);
		} else {
			$this->display($this->config['stdfoot']);
		}
	}

	/**
	 * Ends frame
	 */
	function end_main_frame() {
		$this->display('end_main_frame.tpl');
	}

	/**
	 * Begins table
	 * @param boolean $fullwidth Use 100% width for table? (yes)
	 * @param integer $padding Table padding (5)
	 */
	function begin_table($fullwidth = true, $padding = 5)
	{
		if ($fullwidth) $this->assign('TABLE_WIDTH','100%');
		$this->assign('TABLE_PADDING',$padding);
		$this->display('begin_table.tpl');
	}

	/**
	 * Ends table
	 */
	function end_table() {
		$this->display('end_table.tpl');
	}

	/**
	 * Begins frame with selected parametres
	 * @param string $caption Frame caption (blank)
	 * @param boolean $center Use center? (false)
	 * @param integer $padding Frame padding (0)
	 */
	function begin_frame($caption = "", $center = false, $padding = 0)
	{
		$this->assign('FRAME_TITLE',$caption);

		if ($center) $this->assign('FRAME_CENTER',$center);
		$this->assign('FRAME_PADDING',$padding);

		$this->display('begin_frame.tpl');
	}

	/**
	 * Ends frame
	 */
	function end_frame() {
		$this->display('end_frame.tpl');
	}

	/**
	 * Outputs standart information message
	 * @param string $heading Message title (blank)
	 * @param string $text Message text (blank)
	 * @param string $div Message type (success)
	 * @param boolean $htmlstrip Strip html? (false)
	 */
	function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
		if ($htmlstrip) {
			$heading = strip_tags(trim($heading));
			$text = strip_tags(trim($text));
		}
		$this->assignByRef('MSG_TITLE',$heading);
		$this->assignByRef('MSG_TEXT',$text);
		if (REL_AJAX) $this->display('stdmsg_'.$div.'_ajax.tpl'); else
		$this->display('stdmsg_'.$div.'.tpl');
		return;
	}

	/**
	 * Outputs error and die
	 * @param string $heading Message title (blank)
	 * @param string $text Message text (blank)
	 * @param string $div Message type (error)
	 * @param boolean $htmlstrip Strip html? (false)
	 */
	function stderr($heading = '', $text = '', $div ='error', $htmlstrip = false) {
		$this->stdhead($heading);
		$this->stdmsg($heading, $text, $div, $htmlstrip);
		$this->stdfoot();
		die;
		return;
	}

	/**
	 * Displays module tpl located in {THEME_DIR}/modules/$module/$action.tpl
	 * @param string $action Action of selected module
	 * @param string $module Custom module usage
	 */
	function output($action='',$module='') {
		if (!$action) $action='index';
		if (!$module) $module = str_replace ( ".php", "", basename ( $_SERVER ["PHP_SELF"] ) );
		$this->display("modules/$module/$action.tpl");
	}
}
?>